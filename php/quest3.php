<?php

/*
 * �s�W�� 2008.08.05 by w60292
 * �\�ର���ѿ�Ҩt����ܽҵ{�j�����W�s����}
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

  //�ˬdsession id
  if($sessid != "0466f8e4b492c9294334e34ad49e1de8")
  {
    show_page ("not_access.tpl" ,"�v�����~");
  }
  //session id ���T�L�~ �s����Ʈw
  else
  {
    global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
 
    //���o�Pcourse_no�۹�����course_id
    $Q1 = "select a_id from course where course_no='$courseno'";
 
    if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) )
    {
      echo ("��Ʈw�s�����~!!");
      exit;
    }
    if ( !($result1 = mysql_db_query( $DB, $Q1 )) )
    {
      echo ("��ƮwŪ�����~!!");
      exit;
    }
    $row = mysql_fetch_array( $result1 );
    $cid = $row['a_id'];

    $Q2 = "select year, term from teach_course where course_id='$cid' and year='$year' and term='$term'";

    if ( !($result2 = mysql_db_query( $DB, $Q2 )) )
    {
      echo ("��ƮwŪ�����~!!");
      exit;
    }

    if( mysql_num_rows( $result2 ) == 0 )
    {
      $isOld = 1;
    }
    header("Location:Courses_Admin/intro.php?PHPSESSID=$sessid&year=$year&term=$term&courseid=$cid&query=1&isOld=$isOld");
  }

?>
