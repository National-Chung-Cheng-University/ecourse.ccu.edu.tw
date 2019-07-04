<?php
  // 會議錄影檔管理

    include_once("db_meeting.php");
    include_once("folderManagement.php");
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

    $personal_id = db_getAid();            //取得個人編號
    $begin_course_cd = $course_id;    //取得課程代碼

    $folderId = $_GET['rid'];
    $seq = $_GET['seq'];

    $tpl = new Smarty;
    $tpl->assign("imagePath", $IMAGE_PATH);
    $tpl->assign("cssPath", $CSS_PATH);
    $tpl->assign("absoluteURL", $absoluteURL);


    // 補字 cyberccu2測試機 開頭為1 然後不到五位數要補滿五位數
    $ownerId = $personal_id;
    while (strlen($ownerId) < 4) {
         $ownerId = '0'.$ownerId;
    }

    $ownerId = '2'.$ownerId;  // 不同的平台要 ID開頭要不同 ecourse為2


    // 找出此使用者所有的資料夾，會與右邊的資料夾結構相同
    $folderList = array();
    $folderList = FolderStructCreate($ownerId);
    
    $totalSize = GetTotalRecordingSize($ownerId);

    $recordingList = array();
    if(is_null($seq) || $seq == 0 ) {
        // 避免從上面工作列點選，沒給值的情況，所以把null的seq設成0，因root資料夾的seq本來就只會是0
        if (is_null($seq)) {
            $seq = 0;
            $folderId= GetRootFolderId($ownerId);
        }
        else
            ;
        // searchRecording的第三個參數是用來判斷是不是root資料夾的，為1代表是root資料夾
        $recordingList = searchRecording($ownerId,$folderId,$seq);
    }
    else {
        $recordingList = searchRecording($ownerId,$folderId,$seq);
    }

    $tpl->assign("totalused",$totalSize['totalused']);
    $tpl->assign("totalquota",$totalSize['totalquota']);
    $tpl->assign("used",$totalSize['used']);

    $tpl->assign("currentSeq",$seq);
    $tpl->assign("currentFolderId",$folderId);
    $tpl->assign("folderList", $folderList);
    $tpl->assign("recordingList", $recordingList);

    //目前的頁面
    $tpl->assign("currentPage", "recordingManagement_list.php");
    // assignTemplate($tpl, "/mmc/recordingManagement_list.tpl");
    $tpl->display("$mmc_templates/recordingManagement_list.tpl");
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

