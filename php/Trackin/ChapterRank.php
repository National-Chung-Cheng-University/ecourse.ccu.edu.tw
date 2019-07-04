<?php
require 'fadmin.php';
update_status ("教材瀏覽記錄");

if( !(isset($PHPSESSID) && ($check = session_check_teach($PHPSESSID)) ) && !(session_is_registered("admin") && $admin == 1) )
{
	show_page( "not_access.tpl" ,"權限錯誤");
	exit;
}
if($check < 2)
{
	if( $version=="C" ) {
		show_page( "not_access.tpl" ,"你沒有權限使用此功能");
		exit;
	}
	else {
		show_page( "not_access.tpl" ,"You have No Permission!!");
		exit;
	}
}
global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $skinnum;
if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
	echo ( "資料庫連結錯誤!!" );
	return;
}

$Q1 = "Select * From chap_title Where sect_num='0' Order By chap_num ASC";
if ( !($resultOBJ = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
	echo ( "資料庫讀取錯誤!!" );
	return;
}
else
{
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate("./templates");
	$tpl->assign( SKINNUM , $skinnum );
	if( mysql_num_rows ( $resultOBJ ) == 0 )
	{
		if($version=="C")
			$tpl->define(array(error_msg => "ChapterRankMessage_Ch.tpl"));
		else
			$tpl->define(array(error_msg => "ChapterRankMessage_En.tpl"));
		$Q1 = "Select tag3 From log Where event_id='3' AND tag1='0' AND tag4='0'";
		$result = mysql_db_query( $DB.$course_id, $Q1);
		$count = 0;
		while($row = mysql_fetch_array ( $result ))
			$count += $row['tag3'];
		$tpl->assign(COUNT, $count);
		$tpl->parse(BODY, "error_msg");
		$tpl->FastPrint("BODY");
		return;
	}
	else
	{
		if($version=="C")
			$tpl->define(array(teaching_list => "ChapterRank_Ch.tpl"));
		else
			$tpl->define(array(teaching_list => "ChapterRank_En.tpl"));

		$tpl->define_dynamic("row", "teaching_list");
		
		$Q1 = "Select * From chap_title Where sect_num='0' Order By chap_num ASC";
		$result1 = mysql_db_query( $DB.$course_id, $Q1 );
		$first_dir = true;
		while($row1 = mysql_fetch_array ( $result1 ))
		{
			$Q2 = "Select tag3 From log Where event_id='3' AND tag1='".$row1['chap_num']."' AND tag4='0'";
			$tempresult = mysql_db_query( $DB.$course_id, $Q2 );
			$tempcount = 0;
			while($temprow = mysql_fetch_array ( $tempresult ))
				$tempcount += $temprow['tag3'];

			$Q21 = "Select tag3 From log Where event_id = '11' AND tag1 = '".$row1['chap_num']."' AND tag4='0'";
			$tempresult = mysql_db_query( $DB.$course_id, $Q21 );
			$minutes = 0;
			$seconds = 0;
			$period = 0;
			while ( $temprow = mysql_fetch_array( $tempresult ) ) {
				$period += $temprow['tag3'];
			}
			$minutes = (int)($period/60);
			$seconds = $period%60;

			$chapter[] = $row1['chap_num'];
			$Q3 = "Select * From chap_title Where chap_num = '".$row1['chap_num']."' AND sect_num != '0'";
			$sectresult = mysql_db_query( $DB.$course_id, $Q3 );

			if(mysql_num_rows ( $sectresult ) != 0)
			{
				$parent = "window.JTree".$row1[chap_num]." = new Tree(\"".$row1['chap_title']."　( $tempcount | $minutes:$seconds )\");";
				$tpl->assign(PARENT, $parent);
				if($first_dir)
				{
					$firstroot = $row1['chap_num'];
					$image = "JTree".$row1['chap_num'].".folderIcons = new Array(\"/images/coursefolder.gif\",\"/images/coursefolder_h.gif\",\"/images/coursefolder_s.gif\",\"/images/coursefolder_s.gif\");\n".
						"JTree".$row1['chap_num'].".itemIcons = new Array(\"/images/courseitem.gif\",\"/images/courseitem_h.gif\",\"/images/courseitem.gif\",\"/images/courseitem_h.gif\");\n";
					$tpl->assign(IMAGE, $image);
					$first_dir = false;
				}
				else
					$tpl->assign(IMAGE, NULL);

        		$Q2 = "Select * From chap_title Where chap_num=".$row1['chap_num']." AND sect_num != '0' Order By sect_num ASC";
				$result2 = mysql_db_query( $DB.$course_id, $Q2 );
				$children = "";

				while($row2 = mysql_fetch_array ( $result2 ))
				{
					$Q3 = "Select tag3 From log Where event_id='3' AND tag1='".$row2['chap_num']."' AND tag4='".$row2['sect_num']."'";
					$result3 = mysql_db_query( $DB.$course_id, $Q3 );
					$count = 0;
					if( mysql_num_rows($result3) ) {
						while($row3 = mysql_fetch_array ( $result3 ))
							$count += $row3['tag3'];
					}
					
					$Q32 = "Select tag3 From log Where event_id='11' AND tag1='".$row2['chap_num']."' AND tag4='".$row2['sect_num']."'";
					$result32 = mysql_db_query( $DB.$course_id, $Q32 );
					$minutes = 0;
					$seconds = 0;
					$period = 0;
					while( $row32 = mysql_fetch_array($result32) ) {
						$period += $row32['tag3'];
					}					
					$minutes = (int)($period/60);
					$seconds = $period % 60;

					$children .= "JTree".$row1['chap_num'].".addTreeItem( \"".$row2['sect_title']."　( $count | $minutes:$seconds )\" );\n";
				}
				$children .= "JTree".$row1['chap_num'].".protoTree = JTree$firstroot;\n";
				$tpl->assign(CHILDREN, $children);
				$tpl->parse(ROW, ".row");
			}
		}
        
		$showtree = "window.tRoot = new Tree(\"tRoot\");\n";
		if($first_dir)
		{
			$image = "tRoot.folderIcons = new Array(\"/images/coursefolder.gif\",\"/images/coursefolder_h.gif\",\"/images/coursefolder_s.gif\",\"/images/coursefolder_s.gif\");\n".
				"tRoot.itemIcons = new Array(\"/images/courseitem.gif\",\"/images/courseitem_h.gif\",\"/images/courseitem.gif\",\"/images/courseitem_h.gif\");\n";
			$showtree .= $image;
			$base = "tRoot.protoTree = tRoot;\n";
			$first_dir = false;
			$tpl->assign(PARENT, NULL);
			$tpl->assign(IMAGE, NULL);
			$tpl->assign(CHILDREN, NULL);
			$tpl->parse(ROW, ".row");
		}
		else
			$base = "tRoot.protoTree = JTree$firstroot;\n";
		
		
		$Q40 = "Select SUM(tag3) From log Where event_id='3' AND tag1 = '0' AND tag4='0'";
		$tempresult = mysql_db_query( $DB.$course_id, $Q40 );
		$count = 0;
		if( $temprow = mysql_fetch_array( $tempresult ) ) {
			$count = $temprow[0];
		}

		$Q41 = "Select SUM(tag3) From log Where event_id='11' AND tag1 = '0' AND tag4='0'";
		$tempresult = mysql_db_query( $DB.$course_id, $Q41 );
		$minutes = 0;
		$seconds = 0;
		if( $temprow = mysql_fetch_array( $tempresult ) ) {
			$period = $temprow[0];
			$minutes = (int)($period/60);
			$seconds = $period%60;
		}

		if( $version == "C" ) {
			$showtree .= "tRoot.addTreeItem( \" 課程導論 ( $count | $minutes:$seconds )\" );\n";
		}
		else {
			$showtree .= "tRoot.addTreeItem( \" Introduce ( $count | $minutes:$seconds ) \" );\n";				
		}

		for( $i = 0 ; $i < count($chapter) ; $i++ )
		{
			$Q4 = "Select * From chap_title Where chap_num = '$chapter[$i]' AND sect_num != '0'";
			$sectresult = mysql_db_query( $DB.$course_id, $Q4);
			if(mysql_num_rows ( $sectresult ) != 0 )
				$showtree .= "tRoot.addTreeItem( JTree$chapter[$i] );\n";
			else
			{
				$Q5 = "Select chap_title From chap_title Where chap_num = '$chapter[$i]' AND sect_num = '0'";
				$chapname = mysql_db_query( $DB.$course_id, $Q5 );
				$chapnameresult = mysql_fetch_array ( $chapname );
				$Q6 = "Select tag3 From log Where event_id = '3' AND tag1 = '$chapter[$i]' AND tag4='0'";
				$tempresult = mysql_db_query( $DB.$course_id, $Q6 );
				$tempcount = 0;
				while($temprow = mysql_fetch_array ( $tempresult ))
					$tempcount += $temprow['tag3'];

				$Q7 = "Select tag3 From log Where event_id = '11' AND tag1 = '$chapter[$i]' AND tag4='0'";
				$tempresult7 = mysql_db_query( $DB.$course_id, $Q7 );
				$minutes = 0;
				$seconds = 0;
				$period = 0;
				while ( $temprow7 = mysql_fetch_array( $tempresult7 ) ) {
					$period = $temprow7['tag3'];
				}
				$minutes = (int)($period/60);
				$seconds = $period%60;

				$showtree .= "tRoot.addTreeItem( \"".$chapnameresult['chap_title']."　( $tempcount | $minutes:$seconds )\" );\n";
			}
		}
		$showtree .= $base."showTree(window.tRoot,-50,65);\n";
		$tpl->assign(SHOWTREE, $showtree);
		$tpl->parse(BODY, "teaching_list");
		$tpl->FastPrint("BODY");
	}
}
?>
