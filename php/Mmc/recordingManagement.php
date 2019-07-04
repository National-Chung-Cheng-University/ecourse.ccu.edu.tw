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
    // 先判斷是否有root了，沒有root會自己建立一個root，如果已經有資料則不會建立

    $ownerName = db_getPersonalName(); //因為只有一筆所以只讀0
    $ownerEmail = db_getPersonalBasic(); //因為只有一筆所以只讀0

    CreateMemberInDB($ownerId,$ownerEmail,$ownerName);

    CreateRootFolder($ownerId);

    //$floderStruct = array();
    // paser出樹狀結構
    // 要有folderId，以便去收尋
    //$floderStruct = FolderStructCreate($ownerId); 

    /*    
    $recordingList = array();
    $recordingList = searchRecording($ownerId);
    */
    
    //$tpl->assign("folderList", $floderStruct);
 

    //目前的頁面
    $tpl->assign("currentPage", "recordingManagement.php");
    $tpl->display("$mmc_templates/recordingManagement.tpl");
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

