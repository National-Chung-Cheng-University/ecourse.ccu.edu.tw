<?php
    // �o�Gmeeting

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

    $personal_id = db_getAid();            //���o�ӤH�s��
    $begin_course_cd = $course_id;    //���o�ҵ{�N�X

    // �ɦr cyberccu2���վ� �}�Y��1 �M�ᤣ�줭��ƭn�ɺ������
    $ownerId = $personal_id;
    while (strlen($ownerId) < 4) {
       $ownerId = '0'.$ownerId;
    }

    $ownerId = '2'.$ownerId;  // ���P�����x�n ID�}�Y�n���P ecourse��2



    $funcfolder = $_POST['func'];
    
    // �p�G���O�R����Ƨ��o�Ӱʧ@$_POST['folderName']�@�w�n���Ȥ~�|���T����A�_��������
    if(empty($_POST['folderName']) && $funcfolder != "�R����Ƨ�")
        $funcfolder = "����";

    if($funcfolder == "�s�W��Ƨ�") {
        CreateFolder($_POST['folderName'],$_POST['folderId'],$ownerId);
    }
    else if($funcfolder == "���s�R�W��Ƨ�") {
        RenameFolder($_POST['folderName'],$_POST['folderId']);
    }
    else if($funcfolder == "�R����Ƨ�") {
        DeleteFolder($_POST['folderId'],$ownerId);
    }
    else // ����
        ;

    echo "<meta http-equiv='refresh' content='0;url=recordingManagement_folder.php?c=1'>";
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

