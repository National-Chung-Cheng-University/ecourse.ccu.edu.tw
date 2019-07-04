<?php
require 'fadmin.php';
update_status ("新增專案");

if(isset($PHPSESSID) && session_check_teach($PHPSESSID) == 2)
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}

	if($action == "newcase")
	{
		$row = CheckError();
		if($row == "null")
		{
			if($version == "C")
				$message = "請輸入專案名稱和比例!";
			else
				$message = "Please input case name or ratio!";
			show_page_d();
		}
		elseif($row == "errorvalue")
		{
			if($version == "C")
				$message = "比例須介於0~100之間";
			else
				$message = "Please input ratio between 0 and 100!";
			show_page_d();
		}
		elseif($row == "exist")
		{
			if($version == "C")
				$message = $case_name."已存在,請更換專案名稱!";
			else
				$message = "This case name $case_name exist, and please change the case name!";
			show_page_d();
		}
		else
		{
			if ( $case_type == "self_case" ) {
				if ( $case_private == "pri_case" )
					$Q1 = "INSERT INTO coop (name,percentage, beg_time, private) values ('$case_name','0', '00000000000000', '1')";
				else
					$Q1 = "INSERT INTO coop (name,percentage, beg_time, private) values ('$case_name','0', '00000000000000', '0')";
			}else {
				if ( $case_private == "pri_case" )
					$Q1 = "INSERT INTO coop (name,percentage, beg_time, private) values ('$case_name','$case_ratio', '00000000000000', '1')";
				else
					$Q1 = "INSERT INTO coop (name,percentage, beg_time, private) values ('$case_name','$case_ratio', '00000000000000', '0')";

					
			}
			if ( !($result = mysql_db_query( $DBC.$course_id, $Q1 ) ) ) {
				show_page( "not_access.tpl" ,"資料庫寫入錯誤1!!" );
			}
			$case_id = mysql_insert_id();
			
			$Q2 = "SELECT tc.student_id, u.id FROM take_course tc,user u WHERE tc.course_id = '$course_id' AND tc.student_id = u.a_id";
			if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) ) {
				show_page( "not_access.tpl" ,"資料庫讀取錯誤1!!" );
			}
			while ( $row2 = mysql_fetch_array($result2) ) {
				$Q3 = "INSERT INTO take_coop (case_id,student_id,grade) values ('$case_id','$row2[0]','-1')";
				mysql_db_query($DBC.$course_id,$Q3);
			}
			$Q3 = "CREATE TABLE coop_".$case_id."_group ( a_id int(11) unsigned NOT NULL auto_increment, group_num tinyint(4) NOT NULL default '0', student_id varchar(12) NOT NULL default '', duty char(2) NOT NULL default '0', leader_id int(11) default NULL, PRIMARY KEY  (a_id), KEY a_id (a_id))";
			if ( !($result3 = mysql_db_query( $DBC.$course_id, $Q3 ) ) ) {
				show_page( "not_access.tpl" ,"資料庫建立錯誤1!!" );
			}
			$Q31 = "select user.id from user,take_course where take_course.course_id=$course_id and user.a_id=take_course.student_id and take_course.credit='1' order by user.id";
			$result31 = mysql_db_query($DB, $Q31)  or die("資料庫查詢錯誤, $Q31");
			while($row31 = mysql_fetch_array($result31)) {
				$Q32 = "insert into coop_".$case_id."_group (student_id, group_num ) values ( '".$row31["id"]."', '-1' )";
				mysql_db_query($DBC.$course_id, $Q32)  or die("資料庫查詢錯誤, $Q32");
			}

			$Q4 = "CREATE TABLE discuss_".$case_id."_info ( a_id mediumint(8) unsigned NOT NULL auto_increment, discuss_name varchar(100) NOT NULL default '', comment varchar(100) default 'NULL', group_num tinyint(4) NOT NULL default '0', access tinyint(1) NOT NULL default '0', PRIMARY KEY  (a_id), UNIQUE KEY a_id (a_id))";
			if ( !($result4 = mysql_db_query( $DBC.$course_id, $Q4 ) ) ) {
				show_page( "not_access.tpl" ,"資料庫建立錯誤2!!" );
			}
			$Q5 = "CREATE TABLE discuss_".$case_id."_subscribe ( a_id int(10) unsigned NOT NULL auto_increment, user_id varchar(12) NOT NULL default '', discuss_id smallint(5) unsigned NOT NULL default '0', PRIMARY KEY  (a_id), KEY a_id (a_id))";
			if ( !($result5 = mysql_db_query( $DBC.$course_id, $Q5 ) ) ) {
				show_page( "not_access.tpl" ,"資料庫建立錯誤3!!" );
			}
			$Q6 = "CREATE TABLE guestbook_".$case_id." ( a_id int(11) NOT NULL auto_increment, user_id int(11) NOT NULL default '0', group_num tinyint(4) NOT NULL default '0', content text NOT NULL, host varchar(16) NOT NULL default '', date varchar(20) NOT NULL default '', mtime timestamp(14) NOT NULL, PRIMARY KEY  (a_id))";
			if ( !($result6 = mysql_db_query( $DBC.$course_id, $Q6 ) ) ) {
				show_page( "not_access.tpl" ,"資料庫建立錯誤4!!" );
			}
			$Q7 = "CREATE TABLE log_".$case_id." ( a_id int(10) unsigned NOT NULL auto_increment, user_id int(11) NOT NULL default '0', group_num tinyint(4) NOT NULL default '0', event_id tinyint(4) NOT NULL default '0', tag1 varchar(100) default NULL, tag2 varchar(100) default NULL, tag3 int(11) default NULL, tag4 varchar(255) default NULL, mtime timestamp(14) NOT NULL, PRIMARY KEY  (a_id), UNIQUE KEY a_id (a_id))";
			if ( !($result7 = mysql_db_query( $DBC.$course_id, $Q7 ) ) ) {
				show_page( "not_access.tpl" ,"資料庫建立錯誤5!!" );
			}
			$Q8 = "CREATE TABLE info_".$case_id." ( a_id int(11) NOT NULL auto_increment, type char(2) NOT NULL default '0', share tinyint(3) NOT NULL default '0', upload tinyint(3) NOT NULL default '0', group_id tinyint(4) NOT NULL default '0', content longtext, mtime timestamp(14) NOT NULL, PRIMARY KEY  (a_id))";
			if ( !($result8 = mysql_db_query( $DBC.$course_id, $Q8 ) ) ) {
				show_page( "not_access.tpl" ,"資料庫建立錯誤6!!" );
			}
			$Q9 = "CREATE TABLE news_".$case_id." ( a_id int(11) NOT NULL auto_increment, group_num tinyint(4) default '0', begin_day date NOT NULL default '0000-00-00', end_day date NOT NULL default '0000-00-00', cycle date NOT NULL default '0000-00-00', week tinyint(4) NOT NULL default '0', important tinyint(4) NOT NULL default '1', handle char(1) NOT NULL default '0', subject varchar(100) NOT NULL default '', content text NOT NULL, mtime timestamp(14) NOT NULL, PRIMARY KEY  (a_id))";
			if ( !($result9 = mysql_db_query( $DBC.$course_id, $Q9 ) ) ) {
				show_page( "not_access.tpl" ,"資料庫建立錯誤7!!" );
			}
			$Q10 = "CREATE TABLE schedule_".$case_id." ( group_num tinyint(4) NOT NULL default '0', day varchar(11) NOT NULL default '', unit varchar(8) NOT NULL default '', idx int(4) NOT NULL default '0', subject varchar(100) NOT NULL default '', content text, file varchar(255) default NULL, mtime timestamp(14) NOT NULL, PRIMARY KEY  (idx,group_num))";
			if ( !($result10 = mysql_db_query( $DBC.$course_id, $Q10 ) ) ) {
				show_page( "not_access.tpl" ,"資料庫建立錯誤8!!" );
			}
			$Q11 = "CREATE TABLE share_".$case_id." ( a_id int(11) NOT NULL auto_increment, group_num tinyint(4) NOT NULL default '0', student_id int(11) NOT NULL default '0', filename varchar(255) NOT NULL default '', upload char(1) NOT NULL default '0', type varchar(255) NOT NULL default '', content text, share char(1) NOT NULL default '0', mtime timestamp(14) NOT NULL, PRIMARY KEY  (a_id) )";
			if ( !($result11 = mysql_db_query( $DBC.$course_id, $Q11 ) ) ) {
				show_page( "not_access.tpl" ,"資料庫建立錯誤8!!" );
			}
			$Q12 = "CREATE TABLE share_group_".$case_id." ( a_id int(11) NOT NULL auto_increment, group_num tinyint(4) NOT NULL default '0', name varchar(16) NOT NULL default '', parent_id int(11) NOT NULL default '0', mtime timestamp(14) NOT NULL, PRIMARY KEY  (a_id) )";
			if ( !($result12 = mysql_db_query( $DBC.$course_id, $Q12 ) ) ) {
				show_page( "not_access.tpl" ,"資料庫建立錯誤8!!" );
			}
			$Q13 = "insert share_group_".$case_id." ( group_num, name, parent_id ) values ( '-1', 'Root', '-1' )";
			if ( !($result13 = mysql_db_query( $DBC.$course_id, $Q13 ) ) ) {
				show_page( "not_access.tpl" ,"資料庫建立錯誤8!!" );
			}
			$Q14 = "CREATE TABLE note_".$case_id." ( a_id int(11) NOT NULL auto_increment, group_num tinyint(4) NOT NULL default '0', student_id int(11) NOT NULL default '0', subject varchar(255) NOT NULL default '', content text, share char(1) NOT NULL default '0', mtime timestamp(14) NOT NULL, PRIMARY KEY  (a_id) )";
			if ( !($result14 = mysql_db_query( $DBC.$course_id, $Q14 ) ) ) {
				show_page( "not_access.tpl" ,"資料庫建立錯誤8!!" );
			}
			$Q15 = "CREATE TABLE grade_".$case_id." ( a_id int(11) NOT NULL auto_increment, give_id int(11) NOT NULL default '0', gived_id int(11) NOT NULL default '0', grade float NOT NULL default '0', judge text, grade_type tinyint(4) NOT NULL default '0', mtime timestamp(14) NOT NULL, PRIMARY KEY  (a_id))";
			if ( !($result15 = mysql_db_query( $DBC.$course_id, $Q15 ) ) ) {
				show_page( "not_access.tpl" ,"資料庫建立錯誤9!!" );
			}
			
			mkdir( "../../$course_id/coop/$case_id", 0771 );
			chmod( "../../$course_id/coop/$case_id", 0771 );
			if ( !is_dir ( "../../$course_id/coop/$case_id/share" ) ) {
				mkdir( "../../$course_id/coop/$case_id/share", 0771 );
				chmod( "../../$course_id/coop/$case_id/share", 0771 );
			}
			if ( !is_dir ( "../../$course_id/coop/$case_id/-1" ) ) {
				mkdir( "../../$course_id/coop/$case_id/-1", 0771 );
				chmod( "../../$course_id/coop/$case_id/-1", 0771 );
			}
			if ( !is_dir ( "../../$course_id/coop/$case_id/-1/info" ) ) {
				mkdir( "../../$course_id/coop/$case_id/-1/info", 0771 );
				chmod( "../../$course_id/coop/$case_id/-1/info", 0771 );
			}
			if ( !is_dir ( "../../$course_id/coop/$case_id/-1/result" ) ) {
				mkdir( "../../$course_id/coop/$case_id/-1/result", 0771 );
				chmod( "../../$course_id/coop/$case_id/-1/result", 0771 );
			}
			if ( !is_dir ( "../../$course_id/coop/$case_id/-1/schedule" ) ) {
				mkdir( "../../$course_id/coop/$case_id/-1/schedule", 0771 );
				chmod( "../../$course_id/coop/$case_id/-1/schedule", 0771 );
			}
			if($version == "C")
				$message = "建立完成";
			else
				$message = "Create successful";
			show_page_d();
		}
	}
	else if($action == "newcaseq")
	{
		show_content ( $case_id, $type );
	}
	else
		show_page_d();
}
else
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

function CheckError()
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $case_name, $case_ratio, $course_id, $case_type;

	if($case_name == "" || ( $case_ratio == "" && $case_type != "self_case" ))
		return "null";
	elseif(($case_ratio > 100 || $case_ratio < 0) && $case_type != "self_case")
		return "errorvalue";
	else
	{
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
		}
		$Q1 = "SELECT name FROM coop WHERE name='$case_name'";
		if ( !($result = mysql_db_query( $DBC.$course_id, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
		}
		if( mysql_num_rows($result) != 0 )
			return "exist";
		else
			return "ok";
	}
}

function show_page_d () {
	global $message, $case_name, $case_ratio, $case_type, $case_private, $version, $skinnum;
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate("./templates");
	if($version == "C") {
		$tpl->define( array(main=>"create_case.tpl") );
		$tpl->assign(BUTTON,"建立專案");
		$tpl->assign(TITLE, "新增專案" );
	}
	else {
		$tpl->define( array(main=>"create_case_E.tpl") );
		$tpl->assign(BUTTON,"Create_Case");
		$tpl->assign(TITLE, "Create Case" );
	}
	$tpl->assign(SKINNUM, $skinnum );
	$tpl->assign(ACT1,"create_case.php");
	$tpl->assign(ACT2,"newcase");
	$tpl->assign(CASE_NAME,$case_name);
	$tpl->assign(CASE_RATIO,$case_ratio);
	if ( $case_private != "pri_case")
		$tpl->assign( PUB_CASE, "selected");
	else
		$tpl->assign( PRI_CASE, "selected");
	
	if ( $case_type != "real_case")
		$tpl->assign( SELF_CASE, "selected");
	else
		$tpl->assign( REAL_CASE, "selected");
		
	$tpl->assign(CASEID,"");
	$tpl->assign(MESSAGE,$message);
	$tpl->parse(BODY,"main");
	$tpl->FastPrint("BODY");
}


?>