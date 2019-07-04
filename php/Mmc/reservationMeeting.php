<?php
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
    if(isset($PHPSESSID) && session_check_teach($PHPSESSID) == 2) {

      global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id, $user_id;

      $personal_id = db_getAid();            //���o�ӤH�s��
      $begin_course_cd = $course_id;    //���o�ҵ{�N�X

    /*
    $query = "select *  from begin_course where begin_course_cd = '{$begin_course_cd}'";

    $query_Result = db_getAll($query);

    $temptitle = $query_Result[0]['begin_course_name'];
     */
      $temptitle = db_getCourseName();

      // ��X�׽ҤH�� + �Ѯv + ��@�w�]�s�u�H��
      $courseStuNum = db_getCourseStuNum(); 

      $tpl = new Smarty;
      $tpl->assign("imagePath", $IMAGE_PATH);
      $tpl->assign("cssPath", $CSS_PATH);
      $tpl->assign("absoluteURL", $absoluteURL);

      $tempDate = date("Y-m-d H:i:s", strtotime('now'));
      $tpl->assign("temptitle", $temptitle." - ".$tempDate);
      $tpl->assign("tempconnectionCount", $courseStuNum);

    if( !is_null($_POST['checkedTimeSlot'])) {
        $timeStamp= strtotime($_POST['year']."-".$_POST['month']."-".$_POST['day']) + $_POST['checkedTimeSlot'][0]*30*60 - 15*60;
        $duration = sizeof($_POST['checkedTimeSlot'])*1800;
        $tpl->assign("stamp", $timeStamp);
        $tpl->assign("duration", $duration);
    }
    else {
        $tpl->assign("stamp", time());
        $tpl->assign("duration", 1800);
    }

      //�ثe������
      $tpl->assign("currentPage", "reservationMeeting.php");

      $tpl->display("$mmc_templates/reservationMeeting.tpl");
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


