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
    // ���P�_�O�_��root�F�A�S��root�|�ۤv�إߤ@��root�A�p�G�w�g����ƫh���|�إ�

    $ownerName = db_getPersonalName(); //�]���u���@���ҥH�uŪ0
    $ownerEmail = db_getPersonalBasic(); //�]���u���@���ҥH�uŪ0

    CreateMemberInDB($ownerId,$ownerEmail,$ownerName);

    CreateRootFolder($ownerId);

    //$floderStruct = array();
    // paser�X�𪬵��c
    // �n��folderId�A�H�K�h���M
    //$floderStruct = FolderStructCreate($ownerId); 

    /*    
    $recordingList = array();
    $recordingList = searchRecording($ownerId);
    */
    
    //$tpl->assign("folderList", $floderStruct);
 

    //�ثe������
    $tpl->assign("currentPage", "recordingManagement.php");
    $tpl->display("$mmc_templates/recordingManagement.tpl");
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

