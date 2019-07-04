<?php
/*
require 'fadmin.php';

if(($error = creat_backup()) == 1){
	echo "$error (備份資料庫已建立)<br>";
}
else{
	echo "$error (備份資料庫建立失敗)<br>";
}

function creat_backup(){
		global $DB_SERVER, $DB_LOGIN, $DB_PASSWORD;
		
		$BACKUPDB = "bugrade";
		
		$Q1 = "CREATE DATABASE bugrade";
		$Q2 = "grant all privileges on bugrade.* to study";
		$Q3 = "flush privileges";
		$Q4 = "CREATE TABLE exam ( a_id int(11) NOT NULL default '0', course_id int(11) NOT NULL default '0', year tinyint(4) NOT NULL default '0', term tinyint(4) NOT NULL default '0', chap_num tinyint(3) unsigned DEFAULT '0' NOT NULL, name varchar(40), is_online char(1) DEFAULT '1' NOT NULL, beg_time timestamp(14), end_time timestamp(14), public char(1) DEFAULT '0' NOT NULL, percentage tinyint(4), mtime timestamp(14), PRIMARY KEY (a_id, course_id, year, term))";
		$Q5 = "CREATE TABLE take_exam ( exam_id int(11) DEFAULT '0' NOT NULL, student_id int(11) DEFAULT '0' NOT NULL, course_id int(11) NOT NULL default '0', year tinyint(4) NOT NULL default '0', term tinyint(4) NOT NULL default '0', grade float, nonqa_grade float, public tinyint(3) DEFAULT '1' NOT NULL, mtime timestamp(14), PRIMARY KEY (exam_id, student_id, course_id, year, term))";
		$Q6 = "CREATE TABLE homework ( a_id int(11) NOT NULL, course_id int(11) NOT NULL default '0', year tinyint(4) NOT NULL default '0', term tinyint(4) NOT NULL default '0', chap_num tinyint(3) unsigned DEFAULT '0' NOT NULL, name varchar(40), public char(1) NOT NULL default '0', percentage tinyint(4), due date, mtime timestamp(14), PRIMARY KEY (a_id, course_id, year, term) )";
		$Q7 = "CREATE TABLE handin_homework ( homework_id int(11) DEFAULT '0' NOT NULL, student_id int(11) DEFAULT '0' NOT NULL, course_id int(11) NOT NULL default '0', year tinyint(4) NOT NULL default '0', term tinyint(4) NOT NULL default '0', grade float, public char(1) DEFAULT '0' NOT NULL, handin_time date, mtime timestamp(14), PRIMARY KEY (homework_id, student_id, course_id, year, term))";

		$Q8 = "CREATE TABLE take_course ( group_id tinyint(3) unsigned NOT NULL default '0', course_id int(11) NOT NULL default '0',  student_id int(11) NOT NULL default '0', year tinyint(4) NOT NULL default '0', term tinyint(4) NOT NULL default '0',  validated char(1) default '0',  note tinytext,  credit char(1) default NULL,  mtime timestamp(14) NOT NULL,  PRIMARY KEY  (group_id,course_id,student_id,year,term))";

	
		$error = "資料庫建立錯誤";
		
		// 連結mysql
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "資料庫連結錯誤!!";
			return $error;
		}
		
		for ( $i = 1 ; $i <= 3 ; $i ++ ) {
			$Q = "Q$i";
			if ( !($result = mysql_query( $$Q , $link ) ) ) {
				$error = $error." ".$$Q;
				return $error;
			}
		}
	
		for ( $i = 4 ; $i <= 8 ; $i ++ ) {
			$Q = "Q$i";
			if ( !($result = mysql_db_query( $BACKUPDB, $$Q ) ) ) {
				$error = $error." ".$$Q;
				return $error;
			}
		}
		return 1;	
}
*/
?>
