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



    $func = $_POST['func'];
    $folderId = $_POST['rid'];
    $seq = $_POST['seq'];
    $meetingId = $_POST['meetingId'];
    if($func == "����") {
        echo "<meta http-equiv='refresh' content='0;url=playmodeMeeting.php?meetingId=".$meetingId."'>"; 
        // ������v��
        
    }
    else if($func == "�U��") {
        echo "<meta http-equiv='refresh' content='0;url=downloadMeeting.php?meetingId=".$meetingId."'>";
        // �U�����v��

    }
    else if($func == "�R��") {
        echo "<meta http-equiv='refresh' content='0;url=deleteRecording.php?meetingId=".$meetingId."'>";
        // �R�����v��

    }
    else // �����A���Ӥ��|���o�|�ӿﶵ�H�~��
        echo "<meta http-equiv='refresh' content='0;url=recordingManagement_list.php?rid=".$folderId."&seq=".$seq."'>";
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

