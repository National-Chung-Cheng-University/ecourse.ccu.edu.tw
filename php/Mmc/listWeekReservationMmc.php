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

    if(is_null($_POST['startTimeYear']) && is_null($_GET['y'])) {
        // 由今天找出本周
        $week = date("w", time());
        /*
        $year = date("Y",time()-$week*24*60*60);
        $month = date("m",time()-$week*24*60*60);
        $day = date("d",time()-$week*24*60*60);
         */
        $firstday = time()-$week*24*60*60;
        for($i=0;$i<7;$i++) {
            $year[$i]= date("Y",$firstday+$i*24*60*60);
            $month[$i] = date("m",$firstday+$i*24*60*60);
            $day[$i] = date("d",$firstday+$i*24*60*60);
        }

    }
    else if(!is_null($_POST['startTimeYear'])){
        $selectedTime = strtotime($_POST['startTimeYear']."-".$_POST['startTimeMonth']."-".$_POST['startTimeDay']);

        $week = date("w", $selectedTime);
        $firstday = $selectedTime-$week*24*60*60;
        for($i=0;$i<7;$i++) {
            $year[$i]= date("Y",$firstday+$i*24*60*60);
            $month[$i] = date("m",$firstday+$i*24*60*60);
            $day[$i] = date("d",$firstday+$i*24*60*60);
        }
    }
    else { // 用網頁超連結的
        $selectedTime = strtotime($_GET['y']."-".$_GET['m']."-".$_GET['d']);
        if(!is_null($_GET['roll'])) {
            // 上週
            if($_GET['roll'] == "up")
                $selectedTime = $selectedTime - 7*24*60*60;
            else
                $selectedTime = $selectedTime + 7*24*60*60;
        }
        else
            ;
        $week = date("w", $selectedTime);
        $firstday = $selectedTime-$week*24*60*60;
        for($i=0;$i<7;$i++) {
            $year[$i]= date("Y",$firstday+$i*24*60*60);
            $month[$i] = date("m",$firstday+$i*24*60*60);
            $day[$i] = date("d",$firstday+$i*24*60*60);
        }
    }

    //$allMeeting = array();
    $allMeeting = AllWeekReservationMeeting($year[0],$month[0],$day[0],$year[6],$month[6],$day[6]);
    $tpl->assign("meetingList", $allMeeting);
    $tpl->assign("year", $year);
    $tpl->assign("month", $month);
    $tpl->assign("day", $day);
    $tpl->assign("week", $week);

    $tpl->assign("currentPage", "listWeekReservationMmc.php");

    $tpl->display("$mmc_templates/listWeekReservationMmc.tpl") ;
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

