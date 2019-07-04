<?php
    // 取消發佈meeting

    include_once("db_meeting.php");

    $RELEATED_PATH = "../";
    require_once("platform_config.php");
    $RELEATED_PATH = "../";
    require_once($RELEATED_PATH . "fadmin.php");
    /*
    require_once($RELEATED_PATH . "config.php");
    require_once($RELEATED_PATH . "session.php");
    */
    
    $IMAGE_PATH = $IMAGE_PATH;
    $CSS_PATH = $RELEATED_PATH . $CSS_PATH;
    $absoluteURL = $HOMEURL . "Mmc/";

if(isset($PHPSESSID) && session_check_teach($PHPSESSID) == 2) {


    $personal_id = db_getAid();            //取得個人編號
    $begin_course_cd = $course_id;    //取得課程代碼

    // 先確定session是否有值，有值才可以取消published meeting
    if(!empty($personal_id)) {
      $recordingId = $_GET['rid'];
      $folderId = $_GET['cfid'];
      $seq = $_GET['cseq'];

    /*

    $query = "select *  from begin_course where begin_course_cd = '{$begin_course_cd}'"; // 只會有一筆，所以之後的資料都讀 index(0)即可

    $query_Result = db_getAll($query);

    $tempCourse_cd = $query_Result[0]["course_cd"]; //因為只有一筆所以只讀0
    $courseName = $query_Result[0]["begin_course_name"];
    */

      CancelPublishMeetingInDB($recordingId);

      echo "<meta http-equiv='refresh' content='0;url=recordingManagement_list.php?rid=".$folderId."&seq=".$seq."'>"; 
    }
    else 
      echo "Error"; 
  }
  else {

        if( $version=="C" ) {
                show_page( "not_access.tpl" ,"你沒有權限使用此功能");
                exit;
        }
        else {
                show_page( "not_access.tpl" ,"You have No Permission!!");
                exit;
        }
  }
   
?>

