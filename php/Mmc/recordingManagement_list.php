<?php
  // �|ĳ���v�ɺ޲z

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

    $personal_id = db_getAid();            //���o�ӤH�s��
    $begin_course_cd = $course_id;    //���o�ҵ{�N�X

    $folderId = $_GET['rid'];
    $seq = $_GET['seq'];

    $tpl = new Smarty;
    $tpl->assign("imagePath", $IMAGE_PATH);
    $tpl->assign("cssPath", $CSS_PATH);
    $tpl->assign("absoluteURL", $absoluteURL);


    // �ɦr cyberccu2���վ� �}�Y��1 �M�ᤣ�줭��ƭn�ɺ������
    $ownerId = $personal_id;
    while (strlen($ownerId) < 4) {
         $ownerId = '0'.$ownerId;
    }

    $ownerId = '2'.$ownerId;  // ���P�����x�n ID�}�Y�n���P ecourse��2


    // ��X���ϥΪ̩Ҧ�����Ƨ��A�|�P�k�䪺��Ƨ����c�ۦP
    $folderList = array();
    $folderList = FolderStructCreate($ownerId);
    
    $totalSize = GetTotalRecordingSize($ownerId);

    $recordingList = array();
    if(is_null($seq) || $seq == 0 ) {
        // �קK�q�W���u�@�C�I��A�S���Ȫ����p�A�ҥH��null��seq�]��0�A�]root��Ƨ���seq���ӴN�u�|�O0
        if (is_null($seq)) {
            $seq = 0;
            $folderId= GetRootFolderId($ownerId);
        }
        else
            ;
        // searchRecording���ĤT�ӰѼƬO�ΨӧP�_�O���Oroot��Ƨ����A��1�N��Oroot��Ƨ�
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

    //�ثe������
    $tpl->assign("currentPage", "recordingManagement_list.php");
    // assignTemplate($tpl, "/mmc/recordingManagement_list.tpl");
    $tpl->display("$mmc_templates/recordingManagement_list.tpl");
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

