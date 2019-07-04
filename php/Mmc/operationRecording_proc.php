<?php

    // 發佈meeting

    include_once("folderManagement.php");
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

    // 補字 cyberccu2測試機 開頭為1 然後不到五位數要補滿五位數
    $ownerId = $personal_id;
    while (strlen($ownerId) < 4) {
       $ownerId = '0'.$ownerId;
    }

    $ownerId = '2'.$ownerId;  // 不同的平台要 ID開頭要不同 ecourse為2



    $func = $_POST['func'];
    $folderId = $_POST['rid'];
    $seq = $_POST['seq'];
    $meetingId = $_POST['meetingId'];
    if($func == "播放") {
        echo "<meta http-equiv='refresh' content='0;url=playmodeMeeting.php?meetingId=".$meetingId."'>"; 
        // 播放錄影檔
        
    }
    else if($func == "下載") {
        echo "<meta http-equiv='refresh' content='0;url=downloadMeeting.php?meetingId=".$meetingId."'>";
        // 下載錄影檔

    }
    else if($func == "刪除") {
        echo "<meta http-equiv='refresh' content='0;url=deleteRecording.php?meetingId=".$meetingId."'>";
        // 刪除錄影檔

    }
    else // 取消，應該不會有這四個選項以外的
        echo "<meta http-equiv='refresh' content='0;url=recordingManagement_list.php?rid=".$folderId."&seq=".$seq."'>";
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

