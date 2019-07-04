<?php

//devon 問答題

require 'fadmin.php';
update_status ("改線上測驗");
if( isset($PHPSESSID) && (session_check_teach($PHPSESSID) >= 2) )
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}
	if( $action == "insertgrade" )
	{
		$Q1 = "select grade from qa where exam_id='$exam_id' and student_id='$student_a_id' ";
		if( !($result1 = mysql_db_query( $DB.$course_id, $Q1 ) ) )
			echo("資料庫讀取錯誤1!!");
		$row1 = mysql_num_rows($result1);
		
		echo "exam_id: ".$exam_id."<br>"."student_a_id: ".$student_a_id."<br>";
		$tempgrade = 0;
		for( $i=0; $i<$row1; $i++ )
		{
			$qagrade = $_POST["grade".$i];
			if ($qagrade == '')
				$qagrade = 0;
			$item = $_POST["item_id".$i];
			$tempgrade += $qagrade;
			
			$Q2 = "UPDATE qa SET grade=$qagrade where item_id=$item and exam_id=$exam_id and student_id=$student_a_id";
			if( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) )
				show_page( "not_access.tpl", "資料庫更新錯誤!!" );
		}
//		echo "QQQ".$tempgrade."<br>";
		$message = "問答題成績加入!!";
		
		$Q3 = "select nonqa_grade from take_exam where exam_id=$exam_id and student_id=$student_a_id";
		if( !($result3 = mysql_db_query( $DB.$course_id, $Q3 ) ) )
			echo("資料庫讀取錯誤1!!");
		$row3 = mysql_fetch_array($result3);
		$totalgrade = $tempgrade + $row3[nonqa_grade];
//		echo "Qoo".$totalgrade;
		$Q4 = "UPDATE take_exam SET grade=$totalgrade WHERE	exam_id=$exam_id and student_id=$student_a_id";
		if( !($result4 = mysql_db_query( $DB.$course_id, $Q4 ) ) )
			show_page( "not_access.tpl", "資料庫更新錯誤!!" );
		
		header( "Location:./modify_test.php?exam_id=$exam_id&action=showgrade&message=$message" );
	}
	
	//學生姓名改由DB query (2007/6/21) by carlyle
	//------------------------------------------------------------
	$Q1 = "SELECT name FROM user WHERE id='" . $id. "'";
	$result = mysql_db_query($DB,$Q1);
	$rows = mysql_fetch_array($result);
	$student_name = $rows['name'];
	//------------------------------------------------------------

	include("class.FastTemplate.php3");
	$tpl = new FastTemplate ( "./templates" );
	$tpl->define( array( body=>"modify_qa.tpl" ) );
	$tpl->define_dynamic( "qa_list", "body" );
	$tpl->assign( STUNAME, $student_name );
	$tpl->assign( STUID, $id );
	
	$QAsql = "select question, answer, item_id, grade_limit from qa where exam_id='$exam_id' and student_id='$student_id' order by item_id";
	$result = mysql_db_query( $DB.$course_id, $QAsql );
	$i=0;
	while( $rows = mysql_fetch_array($result) )
	{
		$tpl->assign( QANAME,$rows[question] );
		$tpl->assign( QATEXT,$rows[answer] );
		$tpl->assign( POINTS, $rows[grade_limit] );
		$tpl->assign( ITEM_ID, $rows[item_id] );
		$tpl->assign( EXAM_ID, $exam_id );
		$tpl->assign( STUAID, $student_id );
		
		$itemgrade = "grade".$i;
		$tpl->assign( ITEMGRADE, $itemgrade );
		$itemid = "item_id".$i;
		$tpl->assign( ITEM, $itemid );
		$i++;
		
		$tpl->parse( QA_LIST, ".qa_list" );
	}
	$tpl->parse( BODY, "body" );
	$tpl->FastPrint("BODY");
}

else
{
	if( $version=="C" )
		show_page( "not_access.tpl" ,"你沒有權限使用此功能");
	else
		show_page( "not_access.tpl" ,"You have No Permission!!");
}
  
?>
