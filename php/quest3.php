<?php

/*
 * 新增於 2008.08.05 by w60292
 * 功能為提供選課系統顯示課程大綱的超連結轉址
 */

  require 'fadmin.php';

  if (!isset($ver) && isset($PHPSESSID) && session_check_stu($PHPSESSID)) 
  {
    session_unregister("teacher");
    session_unregister("admin");
    session_register("guest");
    $guest = 1;
  }

  $sessid = $_GET["PHPSESSID"];
  $courseno = $_GET["courseno"];
  $year = $_GET["year"];
  $term = $_GET["term"];
  $isOld = 0;

  //檢查session id
  if($sessid != "0466f8e4b492c9294334e34ad49e1de8")
  {
    show_page ("not_access.tpl" ,"權限錯誤");
  }
  //session id 正確無誤 連結資料庫
  else
  {
    global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
 
    //取得與course_no相對應的course_id
    $Q1 = "select a_id from course where course_no='$courseno'";
 
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
    header("Location:Courses_Admin/intro.php?PHPSESSID=$sessid&year=$year&term=$term&courseid=$cid&query=1&isOld=$isOld");
  }

?>
