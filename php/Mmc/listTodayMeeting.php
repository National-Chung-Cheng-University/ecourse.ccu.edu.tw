<?php

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
    global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id, $user_id;

if(isset($PHPSESSID) && session_check_teach($PHPSESSID) == 2) {

    $personal_id = db_getAid();            //���o�ӤH�s��
    $begin_course_cd = $course_id;    //���o�ҵ{�N�X

    // �ɦr cyberccu2���վ� �}�Y��1 �M�ᤣ�줭��ƭn�ɺ������
    $ownerId = $personal_id;
    while (strlen($ownerId) < 4) {
       $ownerId = '0'.$ownerId;
    }

    $ownerId = '2'.$ownerId;  // ���P�����x�n ID�}�Y�n���P ecourse��2


    $tpl = new Smarty;
    $tpl->assign("imagePath", $IMAGE_PATH);
    $tpl->assign("cssPath", $CSS_PATH);
    $tpl->assign("absoluteURL", $absoluteURL);

    $course_Name = db_getCourseName();
    $teacher_Name = db_getPersonalName();    


    $allMeeting = array();
    $allMeeting = AllTodayMeeting($begin_course_cd,$ownerId,$course_Name,$teacher_Name);
    // $allMeeting = array(1,2,3);
    // �n�Nall meeting ���F���tamplate assign��tpl�n���L�L�Xtable
    $tpl->assign("meetingList", $allMeeting);


    //�ثe������
    $tpl->assign("currentPage", "listTodayMeeting.php");

    $tpl->display("$mmc_templates/listTodayMeeting.tpl") ;
    // assignTemplate($tpl, "/mmc/listTodayMeeting.tpl");
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

