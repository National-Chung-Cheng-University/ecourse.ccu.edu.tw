<?php

  include_once("db_meeting.php");

  require_once("platform_config.php");
  $RELEATED_PATH = "../";
  require_once($RELEATED_PATH . "fadmin.php");
  /*
  require_once($RELEATED_PATH . "config.php");
  require_once($RELEATED_PATH . "session.php");
  */
  $personal_id = db_getAid();            //���o�ӤH�s��
  $begin_course_cd = $course_id;    //���o�ҵ{�N�X

  $currentFolderId = $_POST['currentFolderId'];
  $currentSeq = $_POST['currentSeq'];
  $folderId = $_POST['moveTofolderId'];
  $recordingId = $_POST['recordingId'];

  // �ɦr cyberccu2���վ� �}�Y��1 �M�ᤣ�줭��ƭn�ɺ������
  $ownerId = $personal_id;
  while (strlen($ownerId) < 4) {
     $ownerId = '0'.$ownerId;
  }

  $ownerId = '2'.$ownerId;  // ���P�����x�n ID�}�Y�n���P ecourse��2


  if(($folderId != $currentFolderId)) { // ���ʨ쪺��Ƨ��ëD�{�b�o�Ӹ�Ƨ�
      // �P�_�n���ʨ쪺��Ƨ��O���Oroot��Ƨ�
      if($folderId == GetRootFolderId($ownerId)) { // �O�n���ʨ�Root
          for($i = 0 ; $i < sizeof($recordingId); $i++){
              DeleteRecordingInFloder($recordingId[$i]);
          }
      }
      else { // ���ʨ��L����Ƨ�
          // root��Ƨ�����O����Ƨ�
          if($currentSeq == 0) {// �qroot���ʨ�O����Ƨ�
              for($i = 0 ; $i < sizeof($recordingId); $i++){
                  InsertRecordingInFloder($recordingId[$i],$folderId,$ownerId);
              }
          }
          else {
              for($i = 0 ; $i < sizeof($recordingId); $i++){
                  UpdateRecordingInFloder($recordingId[$i],$folderId);
              }
          }
      }     
  }
  else
      ;

  echo "<meta http-equiv='refresh' content='0;url=recordingManagement_list.php?rid=".$currentFolderId."&seq=".$currentSeq."'>";
?>


