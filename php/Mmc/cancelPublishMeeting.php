<?php
    // �����o�Gmeeting

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


    $personal_id = db_getAid();            //���o�ӤH�s��
    $begin_course_cd = $course_id;    //���o�ҵ{�N�X

    // ���T�wsession�O�_���ȡA���Ȥ~�i�H����published meeting
    if(!empty($personal_id)) {
      $recordingId = $_GET['rid'];
      $folderId = $_GET['cfid'];
      $seq = $_GET['cseq'];

    /*

    $query = "select *  from begin_course where begin_course_cd = '{$begin_course_cd}'"; // �u�|���@���A�ҥH���᪺��Ƴ�Ū index(0)�Y�i

    $query_Result = db_getAll($query);

    $tempCourse_cd = $query_Result[0]["course_cd"]; //�]���u���@���ҥH�uŪ0
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
                show_page( "not_access.tpl" ,"�A�S���v���ϥΦ��\��");
                exit;
        }
        else {
                show_page( "not_access.tpl" ,"You have No Permission!!");
                exit;
        }
  }
   
?>

