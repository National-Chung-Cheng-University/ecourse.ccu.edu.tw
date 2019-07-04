<?php

  include_once("db_meeting.php");

  require_once("platform_config.php");
  $RELEATED_PATH = "../";
  require_once($RELEATED_PATH . "fadmin.php");
  /*
  require_once($RELEATED_PATH . "config.php");
  require_once($RELEATED_PATH . "session.php");
  */
  $personal_id = db_getAid();            //取得個人編號
  $begin_course_cd = $course_id;    //取得課程代碼

  $currentFolderId = $_POST['currentFolderId'];
  $currentSeq = $_POST['currentSeq'];
  $folderId = $_POST['moveTofolderId'];
  $recordingId = $_POST['recordingId'];

  // 補字 cyberccu2測試機 開頭為1 然後不到五位數要補滿五位數
  $ownerId = $personal_id;
  while (strlen($ownerId) < 4) {
     $ownerId = '0'.$ownerId;
  }

  $ownerId = '2'.$ownerId;  // 不同的平台要 ID開頭要不同 ecourse為2


  if(($folderId != $currentFolderId)) { // 移動到的資料夾並非現在這個資料夾
      // 判斷要移動到的資料夾是不是root資料夾
      if($folderId == GetRootFolderId($ownerId)) { // 是要移動到Root
          for($i = 0 ; $i < sizeof($recordingId); $i++){
              DeleteRecordingInFloder($recordingId[$i]);
          }
      }
      else { // 移動到其他的資料夾
          // root資料夾移到別的資料夾
          if($currentSeq == 0) {// 從root移動到別的資料夾
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


