<?php
require 'fadmin.php';

update_status ("課程使用總記錄");

if( !(isset($PHPSESSID) && ($check = session_check_teach($PHPSESSID)) ) && !(session_is_registered("admin") && $admin == 1) )
{
	show_page( "not_access.tpl" ,"權限錯誤");
	exit;
}
if($check < 2 )
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


global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $skinnum, $course_year, $course_term;
if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
	echo ( "資料庫連結錯誤!!" );
	return;
}
$Q1 = "Select student_id From take_course Where course_id='$course_id' and year='$course_year' and term = '$course_term' Order By student_id ASC";
if ( !($resultOBJ = mysql_db_query( $DB, $Q1 ) ) )
{
	echo ("資料庫讀取錯誤!!");
	return;
}
else
{
	if( ($student_num = mysql_num_rows ( $resultOBJ )) == 0 )
	{
		if( $version=="C" )
			show_page( "not_access.tpl" ,"此課程尚未有任何學生!");
		else
			show_page( "not_access.tpl" ,"There is no Student in this Class!!");
	}
	else
	{
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate("./templates");
		$tpl->define(array(student_list => "showCourseUseLogSum.tpl"));

		$tpl->define_dynamic("row", "student_list");
		$tpl->assign( SKINNUM , $skinnum );
		$color = "#000066";

		$tpl->assign( COLOR , $color );
		$tpl->assign( STUDENT_NUM, "<b><font color=#FFFFFF>實際修課人數</font></b>" );
		$tpl->assign( LOGIN_TIMES, "<b><font color=#FFFFFF>課程登入總次數</font></b>" );
		$tpl->assign( STAY_TIME, "<b><font color=#FFFFFF>課程使用總時數</font></b>" );
		$tpl->assign( POST_TIMES, "<b><font color=#FFFFFF>討論區發表文章總次數</font></b>" );
		$tpl->assign( BROWSE_TIMES, "<b><font color=#FFFFFF>瀏覽教材總次數</font></b>" );
		$tpl->assign( BROWSE_TEXTBOOK_TIME, "<b><font color=#FFFFFF>瀏覽教材總時數</font></b>" );

		$tpl->parse(ROWS, ".row");


		$Q1 = "Select sum(tag3) From log Where event_id = '2'"; //課程登入總次數
		$Q2 = "Select sum(tag3) From log Where event_id = '7'"; //課程使用總時數
		$Q3 = "Select sum(tag3) From log Where event_id = '6'"; //討論區發表文章總次數
		$Q4 = "Select sum(tag3) From log Where event_id = '3'"; //瀏覽教材總次數
		$Q5 = "Select sum(tag3) From log Where event_id = '11' and tag4 = '0'";        //瀏覽教材總時數

		if ( !($resultOBJ1 = mysql_db_query( $DB.$course_id, $Q1)) ) {
                	echo "資料庫讀取錯誤1!!";
		        exit;
	        }
        	$row1 = mysql_fetch_array ( $resultOBJ1 );

	        if ( !($resultOBJ2 = mysql_db_query( $DB.$course_id, $Q2)) ) {
        	        echo "資料庫讀取錯誤2!!";
                	exit;
	        }
        	$row2 = mysql_fetch_array ( $resultOBJ2 );

	        if ( !($resultOBJ3 = mysql_db_query( $DB.$course_id, $Q3)) ) {
        	        echo "資料庫讀取錯誤3!!";
                	exit;
	        }
        	$row3 = mysql_fetch_array ( $resultOBJ3 );

	        if ( !($resultOBJ4 = mysql_db_query( $DB.$course_id, $Q4)) ) {
        	        echo "資料庫讀取錯誤4!!";
                	exit;
	        }
        	$row4 = mysql_fetch_array ( $resultOBJ4 );

	        if ( !($resultOBJ5 = mysql_db_query( $DB.$course_id, $Q5)) ) {
        	        echo "資料庫讀取錯誤5!!";
                	exit;
	        }
        	$row5 = mysql_fetch_array ( $resultOBJ5 );

	        $login_times = $row1['sum(tag3)'];
        	$stay_time = $row2['sum(tag3)'];
        	$post_times = $row3['sum(tag3)'];
        	$browse_times = $row4['sum(tag3)'];
	        $browse_textbook_time = $row5['sum(tag3)'];
	
		$tpl->assign(STUDENT_NUM, $student_num);
		$tpl->assign(LOGIN_TIMES, $login_times);
		$tpl->assign(STAY_TIME, round($stay_time/60/60,1) . "小時");
		$tpl->assign(POST_TIMES, $post_times);
		$tpl->assign(BROWSE_TIMES, $browse_times);
		$tpl->assign(BROWSE_TEXTBOOK_TIME, round($browse_textbook_time/60/60,1) . "小時");

			if ( $color == "#F0FFEE" )
				$color = "#E6FFFC";
			else
				$color = "#F0FFEE";
			$tpl->assign( COLOR , $color );

		$tpl->parse(ROWS, ".row");
		$tpl->parse(BODY, "student_list");
		$tpl->FastPrint("BODY");
	}
}

?>
