<?
/*對公告和log的處理不完全
*/
	require 'fadmin.php';
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("資料庫連結錯誤");

	$S1 = "select a_id, course_no from course";
	$S3 = "CREATE TABLE course_no ( a_id int(11) NOT NULL auto_increment,course_id int(11) NOT NULL default '0',course_no varchar(15) NOT NULL default '',mtime timestamp(14) NOT NULL,PRIMARY KEY (a_id))";
	$S2 = "update take_course set credit = '1'";
	$S5 = "delete from log where event_id != '1' and event_id != '8' and event_id != '9'";
	$S6 = "delete from news where system != '1'";
	$S7 = "insert into user ( id, authorization ) values ( 'guest', '9' )";
	$S8 = "CREATE TABLE memo ( a_id int(11) NOT NULL auto_increment, user_id int(11) NOT NULL default '0', year varchar(6) NOT NULL default '0', month varchar(4) NOT NULL default '0', day varchar(4) NOT NULL default '0', content text, mtime timestamp(14) NOT NULL, PRIMARY KEY  (a_id))";
	($results1 = mysql_db_query( $DB, $S1 )) or die("$S1");
//	($results3 = mysql_db_query( $DB, $S3 )) or die("$S3");
//	($results2 = mysql_db_query( $DB, $S2 )) or die("$S2");
//	($results5 = mysql_db_query( $DB, $S5 )) or die("$S5");
//	($results6 = mysql_db_query( $DB, $S6 )) or die("$S6");
//	($results7 = mysql_db_query( $DB, $S7 ));
//	($results8 = mysql_db_query( $DB, $S8 ));
	while ( $rows1 = mysql_fetch_array($results1) ) {
		$course_id = $rows1['a_id'];
/*		if ( !is_dir ( "../$course_id/coop" ) ) {
			mkdir( "../$course_id/coop", 0771 );
			chmod( "../$course_id/coop", 0771 );
			$S8 = "CREATE DATABASE coop".$course_id;
			$result = mysql_query( $S8 );
			$S9 = "CREATE TABLE coop ( a_id int(11) NOT NULL auto_increment, name varchar(40) default NULL, topic text, beg_time timestamp(14) NOT NULL, end_time timestamp(14) NOT NULL, public char(1) NOT NULL default '0', private char(1) NOT NULL default '0', percentage float default NULL, mtime timestamp(14) NOT NULL, PRIMARY KEY (a_id))";
			$S10 = "CREATE TABLE take_coop ( case_id int(11) NOT NULL default '0', student_id int(11) NOT NULL default '0', grade float default NULL, mtime timestamp(14) NOT NULL, PRIMARY KEY  (case_id,student_id))";
			($results9 = mysql_db_query( "coop".$course_id, $S9 ));
			($results10 = mysql_db_query( "coop".$course_id, $S10 ));
		}

		($results9 = mysql_db_query( "coop".$course_id, $S9 )) or die("$S9");
		($results10 = mysql_db_query( "coop".$course_id, $S10 )) or die("$S10");

		$S4 = "insert into course_no ( course_id , course_no ) values ( '$course_id', '".$rows1['course_no']."' )";
		$results4 = mysql_db_query( $DB, $S4 ) or die("$S4");
		if ( $course_id == "" || $course_id == null ) {
			echo ( "unexpect course_id!!\n" );
			exit; 
		}
		$S3 = "grant all privileges on study$course_id.* to study";
		$S4 = "flush privileges";
		$results3 = mysql_db_query( $DB, $S3 ) or die("$S3");
		$results4 = mysql_db_query( $DB, $S4 ) or die("$S4");
		var_dump ("handle course $course_id\n");

		// rename discuss group table part.
		$q1 = "show tables";
		$result1 = mysql_db_query( $DB.$course_id, $q1 )or die("$q1");

		while ( $row1 = mysql_fetch_array($result1) ) {
			
			$name = $row1[0];

			if( (strpos( $name, "discuss" )!==false) && (strcmp($name,"discuss_group")!=0) && (strcmp($name,"discuss_info")!=0) && (strcmp($name,"discuss_subscribe")!=0) ) {
				
				$array = explode( "_", $name );
				if ( $array[1] == "$course_id" ) {
					$newname = $array[0]."_".$array[2];

					$q2 = "ALTER TABLE $name RENAME $newname";
					mysql_db_query( $DB.$course_id, $q2 ) or die ("$q2");
				}
			}
		}
		$Q1 = "delete from course_schedule where course_id != '$course_id'";
		$Q2 = "delete from chap_title where course_id != '$course_id'";
		$Q3 = "delete from news where course_id != '$course_id' or system='1'";
		$Q4 = "delete from log where event_id = '1' or event_id = '9' or ( (event_id = '2' or event_id = '3' or event_id = '4' or event_id = '5' or event_id = '6' or event_id = '7' or event_id = '8' or event_id = '10') and tag2 != '$course_id' )";
		$Q5 = "delete from on_line where course_id != '$course_id'";
		$Q6 = "delete from discuss_info where course_id != '$course_id'";
		$Q7 = "delete from discuss_group where course_id != '$course_id'";
		$Q8 = "delete from discuss_subscribe where course_id != '$course_id'";
		
		for ( $i = 1 ; $i <= 8 ; $i ++ ) {
			$Q = "Q".$i;
			mysql_db_query( $DB.$course_id, $$Q ) or die( "$$Q" );
		}

		$H1 = "select a_id from homework where course_id != '$course_id'";
		$resulth1 = mysql_db_query( $DB.$course_id, $H1 ) or die("$H1");
		while ( $rowh1 = mysql_fetch_array( $resulth1 ) ) {
			$H2 = "delete from handin_homework where homework_id = '".$rowh1['a_id']."'";
			mysql_db_query( $DB.$course_id, $H2 ) or die ("$H2");
		}
		$H3 = "delete from homework where course_id != '$course_id'";
		mysql_db_query( $DB.$course_id, $H3 ) or die("$H3");
		
		$E1 = "select a_id from exam where course_id != '$course_id'";
		$resulte1 = mysql_db_query( $DB.$course_id, $E1 ) or die("$E1");
		while ( $rowe1 = mysql_fetch_array( $resulte1 ) ) {
			$E2 = "delete from tiku where exam_id = '".$rowe1['a_id']."'";
			$E3 = "delete from take_exam where exam_id = '".$rowe1['a_id']."'";
			$E32 = "update take_exam set grade='-1' where grade IS NULL";
			mysql_db_query( $DB.$course_id, $E2 ) or die("$E2");
			mysql_db_query( $DB.$course_id, $E3 ) or die("$E3");
			mysql_db_query( $DB.$course_id, $E32 ) or die("$E32");
		}
		$E4 = "delete from exam where course_id != '$course_id'";
		mysql_db_query( $DB.$course_id, $E4 ) or die("$E4");
		
		$D1 = "drop table user";
		$D2 = "drop table online";
		$D3 = "drop table take_course";
		$D4 = "drop table teach_course";
		$D5 = "drop table gbfriend";
		$D6 = "drop table message";
		$D7 = "drop table course";
		$D8 = "drop table course_group";
		$D9 = "update exam set public ='1' where is_online = '0'";
		$D10 = "alter table tiku add type tinyint not null default 0 after exam_id"; 
		$D13 = "update tiku set type = '1'";
		$D11 = "alter table homework change percentage percentage float(4)";
		$D12 = "alter table exam change percentage percentage float(4)";
		$D13 = "alter table homework add q_type varchar(5) after question";
		$D14 = "alter table homework add ans_type varchar(5) after answer";
//		$D15 = "CREATE TABLE qtiku ( a_id int(11) NOT NULL auto_increment, q_id int(11) NOT NULL default '0', block_id tinyint(4) NOT NULL default '0', type tinyint(4) NOT NULL default '1', question text NOT NULL, selection1 text NOT NULL, selection2 text NOT NULL, selection3 text NOT NULL, selection4 text NOT NULL, selection5 text NOT NULL, note tinyint(4) default NULL, ismultiple char(1) NOT NULL default '', grade tinyint(4) NOT NULL default '0', question_desc text, mtime timestamp(14) NOT NULL, PRIMARY KEY (a_id))";
//		$D16 = "CREATE TABLE questionary ( a_id int(11) NOT NULL auto_increment, name varchar(40) default NULL, is_once tinyint(4) NOT NULL default '1', beg_time timestamp(14) NOT NULL, end_time timestamp(14) NOT NULL, public char(1) NOT NULL default '0', is_named tinyint(4) NOT NULL default '0', mtime timestamp(14) NOT NULL, PRIMARY KEY  (a_id))";
//		$D17 = "CREATE TABLE take_questionary ( q_id tinyint(4) NOT NULL default '0', student_id int(11) NOT NULL default '0', count tinyint(4) NOT NULL default '0', mtime timestamp(14) NOT NULL, PRIMARY KEY (q_id,student_id))";		
//		$D18 = "alter table take_questionary change student_id student_id int(11) NOT NULL default '0'";
//		$D19 = "CREATE TABLE discuss_group_map ( a_id int(11) NOT NULL auto_increment, discuss_id mediumint(8) NOT NULL default '0', student_id int(11) NOT NULL default '0', mtime timestamp(14) NOT NULL, PRIMARY KEY  (a_id) )";
/		for ( $i = 1 ; $i <= 14 ; $i ++ ) {


		for ( $i = 14 ; $i <= 14 ; $i ++ ) {
			$D = "D".$i;
			if ( !mysql_db_query( $DB.$course_id, $$D ) ) {
				echo ( $$D );
			}
		}
*/	}

?>