<?php
  // show ���v�ɸ�T�åB�� ����B²���Y�ϡB��ͤ�r�i�I
    include_once("db_meeting.php");
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

    include("Smarty/Smarty.class.php") ;
  if(isset($PHPSESSID) ) {

    $personal_id = db_getAid();            //���o�ӤH�s��
    $begin_course_cd = $course_id;    //���o�ҵ{�N�X

    $recordingId = $_GET['id'];

    $tpl = new Smarty;
    $tpl->assign("imagePath", $IMAGE_PATH);
    $tpl->assign("cssPath", $CSS_PATH);
    $tpl->assign("absoluteURL", $absoluteURL);

    // �ǥѽҵ{id��X�ҵ{�W
    /*
    $query = "select *  from begin_course where begin_course_cd = '{$begin_course_cd}'"; // �u�|���@���A�ҥH���᪺��Ƴ�Ū index(0)�Y�i

    $query_Result = db_getAll($query);

    $tempCourse_Name = $query_Result[0]["begin_course_name"]; //�]���u���@���ҥH�uŪ0
    */
    $tempCourse_Name = db_getCourseName(); 
    // $meetingInfo = array();
    $meetingInfo = GetPubRecordingInfo($recordingId,$tempCourse_Name);
    // $allMeeting = array(1,2,3);
    // �n�Nall meeting ���F���tamplate assign��tpl�n���L�L�Xtable
    $tpl->assign("meetingInfo", $meetingInfo);

    //�ثe������
    $tpl->assign("currentPage", "publishedRecording.php");
    //assignTemplate($tpl, "/mmc/publishedRecording.tpl");
    $tpl->display("$mmc_templates/publishedRecording.tpl");
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

