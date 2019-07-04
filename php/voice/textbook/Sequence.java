// 2002/03/08
// 修改block的判斷, 修改只有sco的狀況, 部分屬性取得位置更改

// 2002/03/09
// 修改sco_id作為table name時可能遇到的問題, 改用a_id作為tablename
// 新增lesson(block)也可指向resource.

// 2002/03/10
// 修改資料庫增加有關 prerequisities 的欄位
// parser 部分新增有關此部份的程式

// 2002/03/29
// 修改sco.location取得的位置 ( resources.resource.file-href )
// time_limit_action default value

import java.io.*;
import java.util.*;
import java.sql.*;
import java.net.*;
import org.jdom.*;
import org.jdom.input.DOMBuilder;
import org.jdom.JDOMException;
import java.io.IOException;
import java.net.InetAddress;

public class Sequence {
	
	private Document doc;
	private File file;
	private List resourceList;
	private Hashtable ns = new Hashtable();
	
	private int courseid;
	private int sequence = 0;
	private int level = 0;	
	private SysDatabase dbms = new SysDatabase();
	private StringBuffer errormsg = new StringBuffer("");

	
	public String begin( String course_id, String filename ) {
		
		courseid = Integer.parseInt( course_id, 10 );
				
		try { 	
			file = new File( filename );
		} catch ( NullPointerException e ) {
			
			errormsg = errormsg.append("XML course file not found.");
			System.out.println( "XML course file not found." );
		}
		
		DOMBuilder builder = new DOMBuilder();
		
		// Parse.
		try {
			doc = builder.build( file );
			
		} catch ( JDOMException e ) {
			
			errormsg = errormsg.append("DOM Parser error.");
			e.printStackTrace();
		}
		
		// get root (<mainfest>) xml element.
		Element root = doc.getRootElement();
		
		// Get namespace definition.
		List nslist = root.getAdditionalNamespaces();
		Iterator i = nslist.iterator();
		while( i.hasNext() ) {
			
			Namespace n = (Namespace)i.next();
			ns.put( n.getPrefix(), n );
		}
		ns.put( "default", root.getNamespace() );
		
		// Get resource element.
		Element resources = root.getChild("resources", root.getNamespace());
		resourceList = resources.getChildren("resource", resources.getNamespace());
		
		// Get Origination.Originations 
		Element organizations = root.getChild("organizations", root.getNamespace());
		List lessons = organizations.getChild("organization", organizations.getNamespace()).getContent();
		Iterator lesson_i = lessons.iterator();
		
		// Parsing origination element.
		while( lesson_i.hasNext() ) {
						
			Object o = lesson_i.next();
			if( o instanceof Element ) {
				Element element = (Element)o;
				
				if( ( element.getName().equals("item") ) ) {
					if( element.getChild("item", element.getNamespace()) != null ) {
						
						String lesson_id = element.getAttributeValue("identifier");
						this.processBlock( element, "", lesson_id, 1 );
					}
					else {
						this.processSco( element, "", "");
						sequence++;
					}
				}
			}
		}
		
		return errormsg.toString();
	}
	
	
	public String begin( String course_id, String filename, int start )	{
		
		sequence = start;
		return this.begin( course_id, filename );
	}
	
/*
function processBlock:
	for parse item may be block(have item under it), 
	and insert data	into table 'lesson'
*/	
	private void processBlock( Element block, String parent_id, String root_id, int level ) {
		
		// data initial.
		String lesson_id = block.getAttributeValue("identifier");
		String title = block.getChildText("title", (Namespace)ns.get("default"));
		title = Converter.convertFromUnicode( title, "Big5" );
		
		int is_leaf = 1;
		String location = new String("");
		
		String ref_id = block.getAttributeValue("identifierref");
		if( (ref_id != null) && ( !ref_id.equals("") ) ) {
			
			Element resource = this.findResource( ref_id );
			location = resource.getAttributeValue("href");
			location = this.addSlashes( location );
		}
		
		// Insert data into table 'lesson'.
		String query = "INSERT INTO lesson( lesson_id, location, title, parent_id, level, is_leaf ) " + 
						"VALUES( '" + lesson_id + "', '" + location + "', '" + title + "', '" + parent_id + "', " + level + ", " + is_leaf + " )";
		dbms.DB_Query( "study" + courseid, query);
			
		if( !parent_id.equals("") ) {
			
			query = "UPDATE lesson SET is_leaf=0 WHERE lesson_id='" + parent_id + "'";
			dbms.DB_Query( "study" + courseid, query);
		}
		
		// Continue parseing children under this block.
		List list = block.getContent();
		Iterator i = list.iterator();
		while( i.hasNext() ) {
			
			Object o = i.next();
			
			if( o instanceof Element ) {
				Element element = (Element)o;
				if( element.getName().equals("item") ) {
					if( element.getChild("item",element.getNamespace()) != null ) {
						
						this.processBlock( element, lesson_id, root_id, level+1 );
					}
					else {
						
						this.processSco( element, lesson_id, root_id );
						sequence++;
					}
				}
			}
		}		
	}


/*
function processSco:
	for parse item may be a sco,
	and insert data into table 'sco_register'.
	Finally, create 3 table for it.
*/	
	private void processSco( Element sco, String parent_id, String lesson_id ) {
		
		// data initial.
		String id = sco.getAttributeValue("identifier");
		String idref = sco.getAttributeValue("identifierref");
		String scotitle = sco.getChildText("title", sco.getNamespace());
		scotitle = Converter.convertFromUnicode( scotitle, "Big5" );
		String location = new String("");
		String metadata = new String("");
		
		String prere = sco.getChildText("prerequisites", (Namespace)ns.get("adlcp"));		
		String maxtimeallowed = sco.getChildText( "maxtimeallowed", (Namespace)ns.get("adlcp") );
		String timelimitaction = sco.getChildText( "timelimitaction", (Namespace)ns.get("adlcp") );
		String masteryscore = sco.getChildText( "masteryscore", (Namespace)ns.get("adlcp"));
		String launch_data = sco.getChildText( "datafromlms", (Namespace)ns.get("adlcp") );
		
		if( prere == null ) {
			prere = "";
		}
		if( maxtimeallowed == null ) {
			maxtimeallowed = "";
		}
		if( timelimitaction == null ) {
			timelimitaction = "continue, no message";
		}
		if( masteryscore == null ) {
			masteryscore = "";
		}
		if( launch_data == null ) {
			launch_data = "";
		}
		
		
		// search resource block for location and metadata.
		Element resource = this.findResource(idref);
		if( resource!=null ) {
			
			location = resource.getChild("file", resource.getNamespace()).getAttributeValue("href");		
			Element temp = resource.getChild("metadata", resource.getNamespace());
			if ( temp != null ) {
				metadata = temp.getChildText("location", (Namespace)ns.get("adlcp") );
			}
			else {
				metadata = "";
			}
		}
		else {
			
			errormsg = errormsg.append( "sco " + id + " has no resource part" );			
			System.out.println("sco " + id + " has no resource part");
			return;
		}
		location = this.addSlashes( location );
		metadata = this.addSlashes( metadata );

		try {			
			// Insert data into DMBS Table.
			// check data in DBMS or not first.
			String Q1 = "SELECT * FROM sco_register WHERE sco_id='" + id + "'";
			ResultSet rs1 = dbms.DB_Query( "study" + courseid, Q1 );
									
			if ( !rs1.next() ) {
				// sco data doesn't exist, insert new row for it
				String Q3 = "INSERT INTO sco_register( sco_id, sco_name, lesson_id, parent_id, sequence, prerequisites, location, metadata, data_mastery_score,  data_max_time_allowed, data_time_limit_action, launch_data) " + 
							"VALUES( '" + id + "', '" + scotitle + "', '" + lesson_id + "', '" +  parent_id + "', " + sequence + ", '" + prere + "', '" + location + "', '" + metadata + "', '" + masteryscore + "', '" + maxtimeallowed + "', '" + timelimitaction + "', '" + launch_data + "')";
				dbms.DB_Query( "study" + courseid, Q3 );
				String Q31 = "SELECT a_id FROM sco_register WHERE sco_id='"+id+"' and sco_name='"+scotitle+"' order by a_id desc";
				ResultSet rs3 = dbms.DB_Query( "study" + courseid, Q31);
				rs3.next();
				int aid = rs3.getInt("a_id");
				
				//System.out.println( Q3 + ":\n" + aid );
				
				// Create table.
				String Q4 = "CREATE TABLE sco_" + aid + "_core (" +
							"  student_id varchar(255) NOT NULL default ''," +
							"  student_name varchar(255) NOT NULL default ''," +
							"  lesson_location varchar(255) NOT NULL default ''," +
							"  credit varchar(255) NOT NULL default ''," +
							"  lesson_status varchar(255) NOT NULL default ''," +
							"  entry varchar(255) NOT NULL default ''," +
							"  total_time varchar(255) NOT NULL default ''," +
							"  exit varchar(255) NOT NULL default ''," +
							"  session_time varchar(255) NOT NULL default ''," +
							"  lesson_mode varchar(255) default NULL," +
							"  score_raw float NOT NULL default '0'," +
							"  score_max float default NULL," +
							"  score_min float default NULL," +
							"  suspend_data text NOT NULL," +
							"  comments text," +
							"  preference_audio varchar(255) default NULL," +
							"  preference_language varchar(255) default NULL," +
							"  preference_speed int(11) default NULL," +
							"  preference_text int(11) default NULL," +
							"  count int(11) default NULL," +
							"  UNIQUE KEY idx1 (student_id)" +
							")";
							
				String Q5 = "CREATE TABLE sco_" + aid + "_interaction (" + 
							"  student_id varchar(255) NOT NULL default ''," + 
							"  id int(11) default NULL," + 
							"  n_id varchar(255) default NULL," +
							"  n_time varchar(255) default NULL," +
							"  n_type varchar(255) default NULL," +
							"  n_weighting varchar(255) default NULL," +
							"  n_student_response varchar(255) default NULL," +
							"  n_result varchar(255) default NULL," +
							"  n_latency varchar(255) default NULL," +
							"  obj_n_id text," +
							"  corres_n_pattern text," +
							"  UNIQUE KEY idx1 (student_id,id)" +
							")";
				
				String Q6 = "CREATE TABLE sco_" + aid +"_objectives (" +
							"  student_id char(255) NOT NULL default ''," +
							"  id int(11) default NULL," +
							"  n_id char(255) default NULL," +
							"  n_status char(255) default NULL," +
							"  n_score_raw float default NULL," +
							"  n_score_max float default NULL," +
							"  n_score_min float default NULL," +
							"  UNIQUE KEY idx1 (student_id,id)" +
							")";
							
				ResultSet temprs = dbms.DB_Query( "study" + courseid, Q4 );
				temprs = dbms.DB_Query( "study" + courseid, Q5 );
				temprs = dbms.DB_Query( "study" + courseid, Q6 );
			}
		} catch ( SQLException e ) {
			
			errormsg = errormsg.append("Error at processSco:" + e.getMessage() );
			System.out.println( "Error at processSco: " + e.getMessage() );
		}
	}

	
/*
function addSlashes:
	add additional '\' in String s.
*/
	private String addSlashes( String s ) {
		
		StringBuffer temp = new StringBuffer( "" );
		if( s.indexOf("\\") != -1 ) {
			StringTokenizer token = new StringTokenizer( s, "\\" );
			
			while( token.hasMoreTokens() ) {
				
				temp.append( token.nextToken() );
				if( token.hasMoreTokens() ) {
					
					temp.append( "\\\\" );
				}
			}
		}
		else {
			temp.append( s );
		}
		
		return temp.toString();
	}
	
/*	
function findResource:
	find right element in <resource> part.
*/
	private Element findResource( String ref_id ) {
		
		Iterator i = resourceList.iterator();
		while( i.hasNext() ) {
			
			Object o = i.next();
			if( o instanceof Element ) {
				Element element = (Element)o;
				if( element.getAttributeValue("identifier").equals(ref_id) ) {
					
					return element;
				}
			}
		}
		
		return null;
	}
}
