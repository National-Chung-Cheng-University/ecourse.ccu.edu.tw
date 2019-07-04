<?php
require 'fadmin.php';
//require './templates/top.tpl';
update_status ("編輯問卷"); 

if(isset($PHPSESSID) && session_check_teach($PHPSESSID) == 2)
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}

	if($action == "newquestionary")
	{
		$row = CheckError();
		if($row == "null")
		{
			if($version == "C")
				$message = "請輸入問卷名稱!";
			else
				$message = "Please input questionary name!";
			show_page_d();
		}
		/*elseif($row == "exist") 
		{
			if($version == "C")
				$message = $questionary_name."已存在,請更換問卷名稱!";
			else
				$message = "This questionary name $questionary_name exist, and please change the questionary name!";
			show_page_d();
		}*/
		else
		{	
			$_SESSION['year']=$year; 
			$_SESSION['term']=$term;
			$Q1 = "INSERT INTO mid_subject (year, term, name, beg_time) values ('$year', '$term', '$questionary_name', '00000000000000')";

			if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
				show_page( "not_access.tpl" ,"資料庫寫入錯誤!!" );
			}
			$q_id = mysql_insert_id();
			//text=teach_course
			$Q2 = "SELECT teach_course.course_id FROM teach_course WHERE teach_course.year = '$year' AND teach_course.term = '$term' "; //選擇此學期的課程id
			if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) ) {
				show_page( "not_access.tpl" ,"資料庫讀取錯誤3!!" );
			}		
			while ( $row2 = mysql_fetch_array($result2) ) {			//每個課程皆建立 mid_ans:存問卷回答(只預設五題存放空間)
				if ($row2['course_id'] == '0') continue;

				$Q3 = "CREATE TABLE IF NOT EXISTS mid_ans (
						a_id INT( 11 ) NOT NULL AUTO_INCREMENT ,
						q_id INT( 11 ) DEFAULT '0' NOT NULL ,
						year INT( 4 ) DEFAULT '0',
						term CHAR( 2 ) NOT NULL ,
						student_id INT( 11 ) ,
						q1 TEXT ,q2 TEXT ,q3 TEXT ,q4 TEXT ,q5 TEXT , q6 TEXT ,q7 TEXT, q8 TEXT ,q9 TEXT,
						mtime TIMESTAMP( 14 ) ,
						PRIMARY KEY ( a_id ))";
				//echo($row2['course_id']."<br>");
				if ( !mysql_db_query($DB.$row2['course_id'],$Q3) ){
					show_page( "not_access.tpl" ,"資料庫寫入錯誤4!!" );
				}
			}
			/*$Q2 = "SELECT tc.student_id, u.id FROM take_course tc,user u WHERE tc.course_id = '$course_id' AND tc.student_id = u.a_id";
			if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) ) {
				show_page( "not_access.tpl" ,"資料庫讀取錯誤3!!" );
			}
			$Q3 = "CREATE TABLE questionary_$q_id ( a_id int(11) NOT NULL auto_increment, q_id int(11) DEFAULT '0' NOT NULL, student_id int(11) DEFAULT '0' NOT NULL, mtime timestamp(14), PRIMARY KEY (a_id))";
			mysql_db_query($DB.$course_id,$Q3);   //建一個questionary_$q_id的table
			while ( $row2 = mysql_fetch_array($result2) ) {
				$Q4 = "INSERT INTO take_questionary (q_id,student_id) values ('$q_id','$row2[0]')";
				if ( !mysql_db_query($DB.$course_id,$Q4) ){
					show_page( "not_access.tpl" ,"資料庫寫入錯誤4!!" );
				}
			}*/
			show_edit ( $q_id, 0 );
		}
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
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $questionary_name, $course_id;

	if($questionary_name == "" )
		return "null";
	else
	{
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
		}
		$Q1 = "SELECT name FROM mid_subject WHERE name='$questionary_name'";
		if ( !($result = mysql_db_query( $DB, $Q1 )) ) {
			show_page( "not_access.tpl" ,"資料庫讀取錯誤1!!" );
		}
		if( mysql_num_rows($result) != 0 )
			return "exist";
		else
			return "ok";
	}
}

function show_page_d () {
	global $message, $year, $term, $questionary_name, $version;  //$version="C"; 期中問卷預設語言
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate("./templates");
	if($version == "C") {
		$tpl->define( array(main=>"create_questionary.tpl") );
		$tpl->assign(BUTTON,"進入問卷編輯介面");
	}
	else {
		$tpl->define( array(main=>"create_questionary_E.tpl") );
		$tpl->assign(BUTTON,"Enter_Edit_Test_Interface");
	}
	$tpl->assign(IMG,"a331.gif");
	$tpl->assign(ACT1,"create_questionary.php");
	$tpl->assign(ACT2,"newquestionary");
	$tpl->assign(QUES_NAME,$questionary_name);
	$tpl->assign(YEAR,$year);
	/*if ( $is_once < 10 )
		$R = "R0".$is_once;
	else
		$R = "R".$is_once;
	$tpl->assign( $R, "selected");*/
	
	/*if ( $term = "1")
		$tpl->assign( TERM1, "checked");
	else
		$tpl->assign( TERM2, "checked");*/
	$tpl->assign(QUESID,"");
	$tpl->assign(MESSAGE,$message);
	$tpl->parse(BODY,"main");
	$tpl->FastPrint("BODY");
}

function show_edit ( $q_id ="", $type ) {
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate("./templates");
	$tpl->define(array(main=>"editor.tpl"));

	$tpl->assign(ACT1,"create_questionary.php");
	$tpl->assign(ACT2,"newquestionaryq");
	$tpl->assign(QUESID,$q_id);
	$tpl->parse(BODY,"main");
	$tpl->FastPrint("BODY");
}
?>
