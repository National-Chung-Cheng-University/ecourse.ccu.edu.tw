<?php
  //取消會議的php程式

  include_once("db_meeting.php");


  $RELEATED_PATH = "../";
  require_once($RELEATED_PATH . "fadmin.php");  /*
  require_once($RELEATED_PATH . "config.php");
  require_once($RELEATED_PATH . "session.php");
  */
  require_once("platform_config.php");
  $personal_id = db_getAid();            //取得個人編號
  $begin_course_cd = $course_id;    //取得課程代碼

  if(!empty($personal_id)) {
    $meetingId = $_GET['mid'];
    // 要有警告視窗
    DeleteMeetingByMeetingId($meetingId);
  }
  else
      echo "error";
?>
