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



    $funcfolder = $_POST['func'];
    
    // 如果不是刪除資料夾這個動作$_POST['folderName']一定要有值才會正確執行，否視為取消
    if(empty($_POST['folderName']) && $funcfolder != "刪除資料夾")
        $funcfolder = "取消";

    if($funcfolder == "新增資料夾") {
        CreateFolder($_POST['folderName'],$_POST['folderId'],$ownerId);
    }
    else if($funcfolder == "重新命名資料夾") {
        RenameFolder($_POST['folderName'],$_POST['folderId']);
    }
    else if($funcfolder == "刪除資料夾") {
        DeleteFolder($_POST['folderId'],$ownerId);
    }
    else // 取消
        ;

    echo "<meta http-equiv='refresh' content='0;url=recordingManagement_folder.php?c=1'>";
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

