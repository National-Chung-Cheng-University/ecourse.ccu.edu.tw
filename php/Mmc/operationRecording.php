<?php
  // show ���v�ɸ�T�åB�� ����B²���Y�ϡB��ͤ�r�i�I
    include_once("db_meeting.php");
    require_once("platform_config.php");
    $RELEATED_PATH = "../";
    require_once($RELEATED_PATH . "fadmin.php");    
    /*
    $RELEATED_PATH = "../";
    require_once($RELEATED_PATH . "config.php");
    require_once($RELEATED_PATH . "session.php");
    */
    $IMAGE_PATH = $IMAGE_PATH;
    $CSS_PATH = $RELEATED_PATH . $CSS_PATH;
    $absoluteURL = $HOMEURL . "Mmc/";

    include("Smarty/Smarty.class.php") ;
  if(isset($PHPSESSID) && session_check_teach($PHPSESSID) == 2) {

    $personal_id = db_getAid();            //���o�ӤH�s��
    $begin_course_cd = $course_id;    //���o�ҵ{�N�X

    $recordingId = $_GET['rid'];
    $folderId = $_GET['cfid'];
    $seq = $_GET['cseq'];

    $tpl = new Smarty;
    $tpl->assign("imagePath", $IMAGE_PATH);
    $tpl->assign("cssPath", $CSS_PATH);
    $tpl->assign("absoluteURL", $absoluteURL);


    // �ǥѽҵ{id��X�Ѯv���W�r�Pemail
    /*
    $query = "select *  from begin_course where begin_course_cd = '{$begin_course_cd}'"; // �u�|���@���A�ҥH���᪺��Ƴ�Ū index(0)�Y�i

    $query_Result = db_getAll($query);

    $tempCourse_Name = $query_Result[0]["begin_course_name"]; //�]���u���@���ҥH�uŪ0
     */
    $tempCourse_Name = db_getCourseName();
     
    // $meetingInfo = array();
    $meetingInfo = GetRecordingInfo($recordingId,$tempCourse_Name);
    // $allMeeting = array(1,2,3);
    // �n�Nall meeting ���F���tamplate assign��tpl�n���L�L�Xtable

    $meetingInfo['fid'] = $folderId;
    $meetingInfo['seq'] = $seq;
    $tpl->assign("meetingInfo", $meetingInfo);

    //�ثe������
    $tpl->assign("currentPage", "operationRecording.php");
    // assignTemplate($tpl, "/mmc/operationRecording.tpl");
    $tpl->display("$mmc_templates/operationRecording.tpl");
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

