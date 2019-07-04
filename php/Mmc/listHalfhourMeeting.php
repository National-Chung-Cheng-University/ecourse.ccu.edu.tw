<?php
// 顯現出所有這周Mmc所有預約的會議(人數)
    //ini_set("display_errors",1);
    //error_reporting(E_ALL);

    include_once("db_meeting.php");
    require_once("platform_config.php");
    $RELEATED_PATH = "../";
    require_once($RELEATED_PATH . "fadmin.php");
    include("Smarty/Smarty.class.php") ;

    /*
    include_once("db_meeting.php");

    $RELEATED_PATH = "../";
    require_once($RELEATED_PATH . "config.php");
    require_once($RELEATED_PATH . "session.php");
    */
    $IMAGE_PATH = $IMAGE_PATH;
    $CSS_PATH = $RELEATED_PATH . $CSS_PATH;
    $absoluteURL = $HOMEURL . "Mmc/";


    $tpl = new Smarty;
    $tpl->assign("imagePath", $IMAGE_PATH);
    $tpl->assign("cssPath", $CSS_PATH);
    $tpl->assign("absoluteURL", $absoluteURL);

    global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id, $user_id;

    /*
    $allMeeting = array();
    $allMeeting = AllWeekReservationMeeting();
     */
if(isset($PHPSESSID) && session_check_teach($PHPSESSID) == 2) {
    if(!is_null($_GET['s'])) {
        $firstday = strtotime($_GET['y']."-".$_GET['m']."-".$_GET['d']);

        $i=0;
        $year[$i]= date("Y",$firstday+$i*24*60*60);
        $month[$i] = date("m",$firstday+$i*24*60*60);
        $day[$i] = date("d",$firstday+$i*24*60*60);
        $i=6;
        $year[$i]= date("Y",$firstday+$i*24*60*60);
        $month[$i] = date("m",$firstday+$i*24*60*60);
        $day[$i] = date("d",$firstday+$i*24*60*60);

        $allMeeting = array();
        $allMeeting = AllSelectMeeting($year[0],$month[0],$day[0],$year[6],$month[6],$day[6],$_GET['s']);

    }
    else if(!is_null($_GET['w'])) {
        $firstday = strtotime($_GET['y']."-".$_GET['m']."-".$_GET['d']);

        $i=$_GET['w'];
        $year= date("Y",$firstday+$i*24*60*60);
        $month = date("m",$firstday+$i*24*60*60);
        $day = date("d",$firstday+$i*24*60*60);

        $allMeeting = array();
        $allMeeting = AllSelectDayMeeting($year,$month,$day);

    }
    else {
    }

    $tpl->assign("meetingList", $allMeeting);
    /*
    $tpl->assign("year", $year);
    $tpl->assign("month", $month);
    $tpl->assign("day", $day);
    $tpl->assign("week", $week);
     */
    $tpl->assign("currentPage", "listHalfhourMeeting.php");
    $tpl->display("$mmc_templates/listHalfhourMeeting.tpl") ;
    // assignTemplate($tpl, "/mmc/listTodayMeeting.tpl");
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

