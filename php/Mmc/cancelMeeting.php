<?php
  //�����|ĳ��php�{��

  include_once("db_meeting.php");


  $RELEATED_PATH = "../";
  require_once($RELEATED_PATH . "fadmin.php");  /*
  require_once($RELEATED_PATH . "config.php");
  require_once($RELEATED_PATH . "session.php");
  */
  require_once("platform_config.php");
  $personal_id = db_getAid();            //���o�ӤH�s��
  $begin_course_cd = $course_id;    //���o�ҵ{�N�X

  if(!empty($personal_id)) {
    $meetingId = $_GET['mid'];
    // �n��ĵ�i����
    DeleteMeetingByMeetingId($meetingId);
  }
  else
      echo "error";
?>
