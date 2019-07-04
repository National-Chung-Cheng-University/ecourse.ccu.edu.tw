<?php

    include_once("db_meeting.php");
    require_once("platform_config.php");
    $RELEATED_PATH = "../";
    require_once($RELEATED_PATH . "fadmin.php");

    /*
    require_once($RELEATED_PATH . "config.php");
    require_once($RELEATED_PATH . "session.php");
    */
    include("Smarty/Smarty.class.php") ;
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

    $folderName = GetFolderNameByFolderId($_POST['folderId']);
    $folderName = mb_convert_encoding($folderName,"big5","UTF-8");    

    $tpl = new Smarty;
    $tpl->assign("imagePath", $IMAGE_PATH);
    $tpl->assign("cssPath", $CSS_PATH);
    $tpl->assign("absoluteURL", $absoluteURL);

    // $allMeeting = array(1,2,3);
    // 要將all meeting 的東西用tamplate assign給tpl好讓他印出table
    $tpl->assign("funcFolder", $_POST['func']);
    $tpl->assign("folderName", $folderName);
    $tpl->assign("folderId", $_POST['folderId']); 


    //目前的頁面
    $tpl->assign("currentPage", "folderView.php");
    // assignTemplate($tpl, "/mmc/folderView.tpl");
    $tpl->display("$mmc_templates/folderView.tpl") ;
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


