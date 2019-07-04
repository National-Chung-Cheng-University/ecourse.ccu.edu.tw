<?php
  // show 錄影檔資訊並且有 播放、簡報縮圖、交談文字可點
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

    $personal_id = db_getAid();            //取得個人編號
    $begin_course_cd = $course_id;    //取得課程代碼

    $recordingId = $_GET['id'];

    $tpl = new Smarty;
    $tpl->assign("imagePath", $IMAGE_PATH);
    $tpl->assign("cssPath", $CSS_PATH);
    $tpl->assign("absoluteURL", $absoluteURL);

    // 藉由課程id找出課程名
    /*
    $query = "select *  from begin_course where begin_course_cd = '{$begin_course_cd}'"; // 只會有一筆，所以之後的資料都讀 index(0)即可

    $query_Result = db_getAll($query);

    $tempCourse_Name = $query_Result[0]["begin_course_name"]; //因為只有一筆所以只讀0
    */
    $tempCourse_Name = db_getCourseName(); 
    // $meetingInfo = array();
    $meetingInfo = GetPubRecordingInfo($recordingId,$tempCourse_Name);
    // $allMeeting = array(1,2,3);
    // 要將all meeting 的東西用tamplate assign給tpl好讓他印出table
    $tpl->assign("meetingInfo", $meetingInfo);

    //目前的頁面
    $tpl->assign("currentPage", "publishedRecording.php");
    //assignTemplate($tpl, "/mmc/publishedRecording.tpl");
    $tpl->display("$mmc_templates/publishedRecording.tpl");
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

