<?php

/*
 * 新增於 2008.08.05 by w60292
 * 功能為提供選課系統顯示課程大綱的超連結轉址
 */

  require 'fadmin.php';
  
	if (!isset($ver) && isset($PHPSESSID) && session_check_stu($PHPSESSID)) {
		session_unregister("teacher");
		session_unregister("admin");
		session_register("guest");
		$guest = 1;
	}
	else {
		session_start();
		session_unregister("teacher");
		session_unregister("admin");
		session_unregister("course_id");
		//計算使用時間用
		session_unregister("time");
		session_register("time");
		session_register("user_id");
		session_register("version");
		session_register("guest");
		$version = $ver;
		$user_id = $id;
		$guest = 1;
		$time = date("U");
		add_log ( 1, $user_id );
		unset($ver);
		//header( "Location: http://$SERVER_NAME/php/Courses_Admin/guest.php?groupid=$groupid&PHPSESSID=".session_id());
	}

  $sessid = $_GET["PHPSESSID"];
  $courseno = $_GET["courseno"];
  $year = $_GET["year"];
  $term = $_GET["term"];
  $isOld = 0;
  
  //檢查session id
//  if($sessid != "0466f8e4b492c9294334e34ad49e1de8")
//  {
//    show_page ("not_access.tpl" ,"權限錯誤");
//  }
  //session id 正確無誤 連結資料庫
//  else
//  {
    global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
 
    //取得與course_no相對應的course_id
    $Q1 = "select a.a_id from course a, teach_course b where a.course_no='$courseno' and a.a_id=b.course_id and b.year='$year' and b.term='$term'";
 
    if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) )
    {
      echo ("資料庫連結錯誤!!");
      exit;
    }
    if ( !($result1 = mysql_db_query( $DB, $Q1 )) )
    {
      echo ("資料庫讀取錯誤!!");
      exit;
    }
    $row = mysql_fetch_array( $result1 );
    $cid = $row['a_id'];

    $Q2 = "select year, term from teach_course where course_id='$cid' and year='$year' and term='$term'";

    if ( !($result2 = mysql_db_query( $DB, $Q2 )) )
    {
      echo ("資料庫讀取錯誤!!");
      exit;
    }

    if( mysql_num_rows( $result2 ) == 0 )
    {
      $isOld = 1;
    }
    header("Location:./intro.php?year=$year&term=$term&courseid=$cid&query=1&isOld=$isOld&PHPSESSID=".session_id());
//  }

?>
