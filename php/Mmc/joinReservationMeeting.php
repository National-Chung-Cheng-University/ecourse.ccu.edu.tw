<?php
  // 進入預約會議會議

  require_once("jnjData.php");
  include_once("hit_encryption.php");
  include_once("db_meeting.php");


  $RELEATED_PATH = "../";
  require_once($RELEATED_PATH . "config.php");
  require_once($RELEATED_PATH . "session.php");

  $personal_id = $_SESSION['personal_id'];            //取得個人編號
  $role_cd = $_SESSION['role_cd'];                    //取得角色
  $begin_course_cd = $_SESSION['begin_course_cd'];    //取得課程代碼
  
  $meetingId = $_GET['mid'];
  
?>
