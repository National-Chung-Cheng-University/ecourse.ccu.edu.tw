<?php
require 'fadmin.php';
update_status ("組間互評");

if(!(isset($PHPSESSID) && session_check_teach($PHPSESSID) && check_group ( $course_id, $coopgroup, $coopcaseid ) >= 2 && $teacher != 1) )
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

if ( $action == "update" && $teacher != 1 ) {
	update();
}
show_page_d ();

function update () {
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $coopcaseid, $group_id, $course_id, $user_id, $version, $grade, $judge, $case_id;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$message = "資料庫連結錯誤!!";
		show_page( "not_access.tpl" , $message);
		return;
	}
	$Q1 = "select * from grade_".$coopcaseid." where give_id = '".GetUserAID($user_id)."' and gived_id = '".$group_id."' and grade_type = '1'";
	($result = mysql_db_query( $DBC.$course_id, $Q1 )) or die (  "資料庫讀取錯誤$Q1!!" );
	if ( mysql_num_rows( $result ) != 0 ) {
		$Q1 = "update grade_".$coopcaseid." set grade = '".$grade."', judge = '".$judge."' where give_id = '".GetUserAID($user_id)."' and gived_id = '".$group_id."' and grade_type='1'";
		mysql_db_query( $DBC.$course_id, $Q1 ) or die (  "資料庫更新錯誤!!" );
	}
	else {
		$Q1 = "insert into grade_".$coopcaseid." ( give_id, gived_id, grade, judge, grade_type ) values ( '".GetUserAID($user_id)."', '".$group_id."', '".$grade."', '".$judge."', '1' )";
		mysql_db_query( $DBC.$course_id, $Q1 ) or die (  "資料庫加入錯誤!!" );
	}

}

function show_page_d ( ) {
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $coopcaseid, $coopgroup, $course_id, $user_id, $version, $teacher, $skinnum;
	$Q1 = "Select group_num From coop_".$coopcaseid."_group group by group_num";
	$Q2 = "Select grade, judge From grade_".$coopcaseid." Where gived_id = '".$coopgroup."' and grade_type='1'";
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$message = "資料庫連結錯誤!!";
		show_page( "not_access.tpl" , $message);
		return;
	}
	else if ( !($resultOBJ = mysql_db_query( $DBC.$course_id, $Q1 ) ) ) {
		$message = "資料庫讀取錯誤1!!";
		show_page( "not_access.tpl" , $message);
		return;
	}
	else if ( mysql_num_rows ( $resultOBJ ) != 0 ) {
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		if ( $version == "C" ) {
			$tpl->define ( array ( body => "GBGrade.tpl") );
		}
		else {
			$tpl->define ( array ( body => "GBGrade.tpl") );
		}
		$tpl->define_dynamic("group_list", "body");
		$tpl->assign( SKINNUM, $skinnum);
		$color = "#CCCCCC";
		($resultOBJ2 = mysql_db_query( $DBC.$course_id, $Q2 ) ) or die ("資料庫讀取錯誤4!!");
		$total = 0;
		$total_grade = 0;
		while ( $row2 = mysql_fetch_array ( $resultOBJ2 ) ) {
			if ( $row2['grade'] != NULL && $row2['grade'] != "" ) {
				$total_grade += $row2['grade'];
				$total ++;
			}
		}
		if ( $total == 0 ) {
			$avg = 0;
		}else {
			$avg = $total_grade/$total;
		}
		$tpl->assign( TOTAL , $avg );
		while ( $row = mysql_fetch_array ( $resultOBJ ) ) {
			
			$Q3 = "Select * From grade_".$coopcaseid." Where give_id = '".GetUserAID($user_id)."' and gived_id = '".$row['group_num']."' and grade_type='1'";
			($resultOBJ3 = mysql_db_query( $DBC.$course_id, $Q3 ) ) or die ( "資料庫讀取錯誤$Q3!!" );
			$row3 = mysql_fetch_array ( $resultOBJ3 );
			
			$i ++;
			if ( $color == "#CCCCCC" )
				$color = "#F0FFEE";
			else
				$color = "#CCCCCC";
			if ( $row['group_num'] == $coopgroup ) {
				$color = "#AAEEAA";
			}
			$tpl->assign( COLOR , $color );
			$tpl->assign( GRADE , $row3['grade'] );
			$tpl->assign( JUDGE , $row3['judge'] );
			$tpl->assign( CASEID, $coopcaseid);
			$tpl->assign( GID, $row['group_num']);
			$tpl->parse(ROW, ".group_list");
		}
		$tpl->parse(BODY,"body");
		$tpl->FastPrint("BODY");
		
	}
	else {
		if( $version=="C" )
			show_page( "not_access.tpl" ,"目前無任何組資料");
		else
			show_page( "not_access.tpl" ,"No data now!!");
	}
}

function GetUserAID($user_id) {

	global $DB;

	$sql = "select a_id from user where id='$user_id'";
	$result = mysql_db_query($DB, $sql) or die("資料庫查詢錯誤, $sql");

	// check name field. if exists, use it as poster name.
	$row = mysql_fetch_array( $result );
	
	return $row['a_id'];
}
?>
