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

    $personal_id = db_getAid();            //���o�ӤH�s��
    $begin_course_cd = $course_id;    //���o�ҵ{�N�X

    // �ɦr cyberccu2���վ� �}�Y��1 �M�ᤣ�줭��ƭn�ɺ������
    $ownerId = $personal_id;
    while (strlen($ownerId) < 4) {
       $ownerId = '0'.$ownerId;
    }

    $ownerId = '2'.$ownerId;  // ���P�����x�n ID�}�Y�n���P ecourse��2

    $folderName = GetFolderNameByFolderId($_POST['folderId']);
    $folderName = mb_convert_encoding($folderName,"big5","UTF-8");    

    $tpl = new Smarty;
    $tpl->assign("imagePath", $IMAGE_PATH);
    $tpl->assign("cssPath", $CSS_PATH);
    $tpl->assign("absoluteURL", $absoluteURL);

    // $allMeeting = array(1,2,3);
    // �n�Nall meeting ���F���tamplate assign��tpl�n���L�L�Xtable
    $tpl->assign("funcFolder", $_POST['func']);
    $tpl->assign("folderName", $folderName);
    $tpl->assign("folderId", $_POST['folderId']); 


    //�ثe������
    $tpl->assign("currentPage", "folderView.php");
    // assignTemplate($tpl, "/mmc/folderView.tpl");
    $tpl->display("$mmc_templates/folderView.tpl") ;
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


