<?php
	require 'fadmin.php';

		
	function add_course($link, $course_no, $course_name, $course_unitname){
		global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $scorm;
		$Qn = "select a_id from course_group where name='$course_unitname'";
		if ( !($resultn = mysql_db_query( $DB, $Qn ) ) ) {
			$error = "資料庫讀取錯誤!!";
			return $error;
		}
		if ( !($arrayn = mysql_fetch_array($resultn) ) ) {
			$error = "$course_unitname 此課程類別不存在!!";
			return $error;
		}
		
		$group = $arrayn[a_id];
		$Q1 = "insert into course (group_id, name, schedule_unit ) values ('$group', '$course_name', '週')";

		if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
			$error = "資料庫寫入錯誤!!".$Q1;
			return $error;
		}
		$aid = mysql_insert_id();
		$Q2 = "insert into course_no (course_id, course_no ) values ('$aid', '$course_no')";
		if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) ) {
			$error = "資料庫寫入錯誤!!".$Q2;
			return $error;
		}

		$Q1 = "CREATE DATABASE study$aid";
		$Q2 = "CREATE DATABASE coop$aid";
		$Q3 = "grant all privileges on study$aid.* to study";
		$Q4 = "grant all privileges on coop$aid.* to study";
		$Q5 = "flush privileges";
		$Q6 = "CREATE TABLE course_schedule ( day varchar(11) NOT NULL, idx tinyint(4) DEFAULT '0' NOT NULL, subject varchar(100) NOT NULL, mtime timestamp(14), PRIMARY KEY (idx))";
		$Q7 = "CREATE TABLE news ( a_id int(11) NOT NULL auto_increment, system tinyint(4) DEFAULT '0', begin_day date DEFAULT '0000-00-00' NOT NULL, end_day date DEFAULT '0000-00-00' NOT NULL, cycle date DEFAULT '0000-00-00' NOT NULL, week tinyint(4) DEFAULT '0' NOT NULL, important tinyint(4) DEFAULT '1' NOT NULL, handle char(1) DEFAULT '0' NOT NULL, subject varchar(100) NOT NULL, content text NOT NULL, mtime timestamp(14), PRIMARY KEY (a_id))";
		$Q8 = "CREATE TABLE log ( a_id int(10) unsigned NOT NULL auto_increment, user_id int(11) DEFAULT '0' NOT NULL, event_id tinyint(4) DEFAULT '0' NOT NULL, tag1 varchar(100), tag2 varchar(100), tag3 int(11), tag4 varchar(255), mtime timestamp(14), PRIMARY KEY (a_id), UNIQUE a_id (a_id), KEY a_id_2 (a_id))";
		$Q9 = "CREATE TABLE homework ( a_id int(11) NOT NULL auto_increment, chap_num tinyint(3) unsigned DEFAULT '0' NOT NULL, name varchar(40), public char(1) NOT NULL default '0', question text, q_type varchar(5), answer text, ans_type varchar(5), percentage tinyint(4), late char(1) NOT NULL default '1', due date, mtime timestamp(14), PRIMARY KEY (a_id) )";
		$Q10 = "CREATE TABLE handin_homework ( homework_id int(11) DEFAULT '0' NOT NULL, student_id int(11) DEFAULT '0' NOT NULL, work text, upload tinyint(4) DEFAULT '0', comment text, grade float, public char(1) DEFAULT '0' NOT NULL, handin_time date, mtime timestamp(14), PRIMARY KEY (homework_id, student_id))";
		$Q11 = "CREATE TABLE exam ( a_id int(11) NOT NULL auto_increment, chap_num tinyint(3) unsigned DEFAULT '0' NOT NULL, name varchar(40), is_online char(1) DEFAULT '1' NOT NULL, random char(1) DEFAULT '0' NOT NULL, beg_time timestamp(14), end_time timestamp(14), public char(1) DEFAULT '0' NOT NULL, percentage tinyint(4), mtime timestamp(14), PRIMARY KEY (a_id))";
		$Q12 = "CREATE TABLE tiku ( a_id int(11) NOT NULL auto_increment, type tinyint(4) DEFAULT '1' NOT NULL, exam_id int(11) DEFAULT '0' NOT NULL, question text NOT NULL, selection1 text NOT NULL, selection2 text NOT NULL, selection3 text NOT NULL, selection4 text NOT NULL, ismultiple char(1) NOT NULL, answer char(2) NOT NULL, grade tinyint(4) DEFAULT '0' NOT NULL, answer_desc text, question_media text, answer_media text, mtime timestamp(14), PRIMARY KEY (a_id))";
		$Q13 = "CREATE TABLE take_exam ( exam_id int(11) DEFAULT '0' NOT NULL, student_id int(11) DEFAULT '0' NOT NULL, grade float, mtime timestamp(14), PRIMARY KEY (exam_id, student_id))";
		$Q14 = "CREATE TABLE on_line ( a_id int(11) NOT NULL auto_increment, date date DEFAULT '0000-00-00' NOT NULL, subject varchar(50), link varchar(100), file varchar(25), rfile varchar(100), mtime timestamp(14), PRIMARY KEY (a_id))";
		$Q15 = "CREATE TABLE chap_title ( a_id int(10) unsigned NOT NULL auto_increment, chap_num tinyint(3) unsigned DEFAULT '0' NOT NULL, chap_title varchar(128) NOT NULL, sect_num tinyint(3) unsigned DEFAULT '0' NOT NULL, sect_title varchar(128) NOT NULL, PRIMARY KEY (a_id))";
		$Q16 = "CREATE TABLE discuss_info ( a_id mediumint(8) unsigned NOT NULL auto_increment, discuss_name varchar(100) NOT NULL, comment varchar(100) DEFAULT 'NULL', group_num tinyint(4) DEFAULT '0' NOT NULL, access tinyint(1) DEFAULT '0' NOT NULL, PRIMARY KEY (a_id), UNIQUE a_id (a_id))";
		$Q17 = "CREATE TABLE discuss_group ( a_id int(10) unsigned NOT NULL auto_increment, group_num tinyint(4) DEFAULT '0' NOT NULL, student_id varchar(12) NOT NULL, PRIMARY KEY (a_id), KEY a_id (a_id))";
		$Q18 = "CREATE TABLE discuss_group_map ( a_id int(11) NOT NULL auto_increment, discuss_id mediumint(8) NOT NULL default '0', student_id int(11) NOT NULL default '0', mtime timestamp(14) NOT NULL, PRIMARY KEY  (a_id) )";
		$Q19 = "CREATE TABLE discuss_subscribe ( a_id int(10) unsigned NOT NULL auto_increment, user_id varchar(12) NOT NULL, discuss_id smallint(5) unsigned DEFAULT '0' NOT NULL, PRIMARY KEY (a_id), KEY a_id (a_id))";
		$Q20 = "CREATE TABLE qtiku ( a_id int(11) NOT NULL auto_increment, q_id int(11) NOT NULL default '0', block_id tinyint(4) NOT NULL default '0', type tinyint(4) NOT NULL default '1', question text NOT NULL, selection1 text NOT NULL, selection2 text NOT NULL, selection3 text NOT NULL, selection4 text NOT NULL, selection5 text NOT NULL, note tinyint(4) default NULL, ismultiple char(1) NOT NULL default '', grade tinyint(4) NOT NULL default '0', question_desc text, mtime timestamp(14) NOT NULL, PRIMARY KEY (a_id))";
		$Q21 = "CREATE TABLE questionary ( a_id int(11) NOT NULL auto_increment, name varchar(40) default NULL, is_once tinyint(4) NOT NULL default '1', beg_time timestamp(14) NOT NULL, end_time timestamp(14) NOT NULL, public char(1) NOT NULL default '0', is_named tinyint(4) NOT NULL default '0', mtime timestamp(14) NOT NULL, PRIMARY KEY  (a_id))";
		$Q22 = "CREATE TABLE take_questionary ( q_id tinyint(4) NOT NULL default '0', student_id int(11) NOT NULL default '0', count tinyint(4) NOT NULL default '0', mtime timestamp(14) NOT NULL, PRIMARY KEY (q_id,student_id))";		
		
		$Q23 = "CREATE TABLE function_list ( u_id varchar(40), news char(1) NOT NULL default '1', intro char(1) NOT NULL default '1', sched char(1) NOT NULL default '0', info char(1) NOT NULL default '1', tein char(1) NOT NULL default '1', 
				tgins char(1) NOT NULL default '1', tgdel char(1) NOT NULL default '1', tgmod char(1) NOT NULL default '1', tgquery char(1) NOT NULL default '1', 
				upload char(1) NOT NULL default '1', editor char(1) NOT NULL default '1', online char(1) NOT NULL default '0', material char(1) NOT NULL default '1', import char(1) NOT NULL default '1', 
				create_work char(1) NOT NULL default '1', modify_work char(1) NOT NULL default '1', check_work char(1) NOT NULL default '1', 
				create_test char(1) NOT NULL default '1', modify_test char(1) NOT NULL default '1', 
				create_case char(1) NOT NULL default '0', mag_case char(1) NOT NULL default '0', check_case char(1) NOT NULL default '0', 
				create_qs char(1) NOT NULL default '0', modify_qs char(1) NOT NULL default '0', 
				chat char(1) NOT NULL default '0', discuss char(1) NOT NULL default '1', talk_voc char(1) NOT NULL default '0', talk_int char(1) NOT NULL default '0', eboard char(1) NOT NULL default '0', 
				strank char(1) NOT NULL default '0', chrank char(1) NOT NULL default '0', sttrace char(1) NOT NULL default '0', complete char(1) NOT NULL default '0', rollbook char(1) NOT NULL default '1', 
				tsins char(1) NOT NULL default '0', tsdel char(1) NOT NULL default '0', tsmod char(1) NOT NULL default '0', tschg char(1) NOT NULL default '0', tsquery char(1) NOT NULL default '1', psswd char(1) NOT NULL default '0', PRIMARY KEY (u_id))";

		$Q24 = "CREATE TABLE function_list2 ( u_id varchar(40), news char(1) NOT NULL default '1',
				info char(1) NOT NULL default '1', sched char(1) NOT NULL default '0', sgquery char(1) NOT NULL default '1', ssquery char(1) NOT NULL default '0', email char(1) NOT NULL default '0',
				material char(1) NOT NULL default '1', online char(1) NOT NULL default '0',
				show_work char(1) NOT NULL default '1',show_test char(1) NOT NULL default '1', show_qs char(1) NOT NULL default '0', check_case char(1) NOT NULL default '0',
				chat char(1) NOT NULL default '0', discuss char(1) NOT NULL default '1', talk_voc char(1) NOT NULL default '0', talk_int char(1) NOT NULL default '0', eboard char(1) NOT NULL default '0',
				search char(1) NOT NULL default '0', stinfo char(1) NOT NULL default '0', psswd char(1) NOT NULL default '0', strank char(1) NOT NULL default '0', ssmodify char(1) NOT NULL default '1', PRIMARY KEY (u_id))";
		$Q25 = "CREATE TABLE coop ( a_id int(11) NOT NULL auto_increment, name varchar(40) default NULL, topic text, beg_time timestamp(14) NOT NULL, end_time timestamp(14) NOT NULL, public char(1) NOT NULL default '0', private char(1) NOT NULL default '0', percentage float default NULL, mtime timestamp(14) NOT NULL, PRIMARY KEY (a_id))";
		$Q26 = "CREATE TABLE take_coop ( case_id int(11) NOT NULL default '0', student_id int(11) NOT NULL default '0', grade float default NULL, mtime timestamp(14) NOT NULL, PRIMARY KEY  (case_id,student_id))";


		$error = "資料庫建立錯誤";
		
		for ( $i = 1 ; $i <= 5 ; $i ++ ) {
			$Q = "Q$i";
			if ( !($result = mysql_query( $$Q , $link ) ) ) {
				$error = $error." ".$$Q;
				return $error;
			}
		}
		
		for ( $i = 6 ; $i <= 24 ; $i ++ ) {
			$Q = "Q$i";
			if ( !($result = mysql_db_query( $DB.$aid, $$Q ) ) ) {
				$error = $error." ".$$Q;
				return $error;
			}
		}
				
		for ( $i = 25 ; $i <= 26 ; $i ++ ) {
			$Q = "Q$i";
			if ( !($result = mysql_db_query( $DBC.$aid, $$Q ) ) ) {
				$error = $error." ".$$Q;
				return $error;
			}
		}
		
		mkdir ( "../../".$aid, 0771 );
		chmod ( "../../".$aid, 0771 );
		mkdir ( "/backup/".$aid, 0771 );
		chmod ( "/backup/".$aid, 0771 );
		mkdir ( "../../".$aid."/homework", 0771 );
		chmod ( "../../".$aid."/homework", 0771 );
		mkdir ( "../../".$aid."/homework/comment", 0771 );
		chmod ( "../../".$aid."/homework/comment", 0771 );
		mkdir ( "../../".$aid."/homework/upload", 0771 );
		chmod ( "../../".$aid."/homework/upload", 0771 );
		mkdir ( "../../".$aid."/on_line", 0771 );
		chmod ( "../../".$aid."/on_line", 0771 );
		mkdir ( "../../".$aid."/textbook", 0771 );
		chmod ( "../../".$aid."/textbook", 0771 );
		mkdir ( "../../".$aid."/textbook/misc", 0771 );
		chmod ( "../../".$aid."/textbook/misc", 0771 );
		mkdir ( "../../".$aid."/student_info", 0771 );
		chmod ( "../../".$aid."/student_info", 0771 );
		mkdir ( "../../".$aid."/board", 0771 );
		chmod ( "../../".$aid."/board", 0771 );
		mkdir ( "../../".$aid."/intro", 0771 );
		chmod ( "../../".$aid."/intro", 0771 );
		mkdir ( "../../".$aid."/coop", 0771 );
		chmod ( "../../".$aid."/coop", 0771 );

		if ( $scorm == 1 ) {
			mkdir ( "../../".$aid."/scorm", 0771 );
			chmod ( "../../".$aid."/scorm", 0771 );
			$S1 = "CREATE TABLE lesson ( a_id int(11) NOT NULL auto_increment, lesson_id varchar(255) NOT NULL default '', location text, title text NOT NULL, parent_id varchar(255) NOT NULL default '', level tinyint(4) NOT NULL default '0', is_leaf tinyint(4) NOT NULL default '0', PRIMARY KEY  (a_id,lesson_id), KEY a_id (a_id), KEY lesson_id (lesson_id))";
			$S2 = "CREATE TABLE sco_register ( a_id int(11) NOT NULL auto_increment, sco_id varchar(255) NOT NULL default '0', parent_id varchar(255) NOT NULL default '0', lesson_id varchar(255) NOT NULL default '0', sequence int(11) NOT NULL default '0', prerequisites varchar(200) default NULL, sco_name text NOT NULL, location text NOT NULL, metadata text NOT NULL, data_mastery_score float default NULL, data_max_time_allowed varchar(255) default NULL, data_time_limit_action varchar(255) default NULL, launch_data text NOT NULL, comments_from_lms text, PRIMARY KEY  (a_id), KEY a_id (a_id) )";
			
			for ( $i = 1 ; $i <= 2 ; $i ++ ) {
				$S = "S$i";
				if ( !($result = mysql_db_query( $DB.$aid, $$S ) ) ) {
					$error = $error." $i";
					return $error;
				}
			}
		}
		return -1;
	}

?>