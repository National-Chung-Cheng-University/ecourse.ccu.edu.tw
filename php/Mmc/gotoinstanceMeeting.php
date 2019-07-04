<?php

  require_once("jnjData.php");
  require_once("mmc_config.php");
  require_once("platform_config.php");
  require_once("hit_encryption.php");
  $RELEATED_PATH = "../";
  require_once($RELEATED_PATH . "fadmin.php");
  // require_once($RELEATED_PATH . "config.php");
  // require_once($RELEATED_PATH . "session.php");

  // session_start();


  global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id, $user_id;

  $encryptor = new EncryptionTool();
  $jnjData = new JnjData();

  // $mmc_db_config = new MMC_DB_Config();
  $mcu_config = new MCU_Config();
  $mmc_jnj_config = new MMC_Jnj_Config();

  $personal_id = db_getAid();            // 取得個人編號

  // $begin_course_cd = $_SESSION['begin_course_cd'];    //���敺�隤脩��隞�蝣�

  
  $jnjData->courseId = $course_id;

  $jnjData->meetingTitle = $_POST['title'];
  $jnjData->meetingId = ''; // ��其��敺�SetInstanceJnj敺����瘙箏��
  $jnjData->jointBrowsingUrl = $_POST['website'];
  // $jnjData->allQuestioner    = $_POST['allquestionerType']; //��箸�砌����芣�����0嚗������箏云敺∟牧��臭誑���摰���踵��嚗����隞交��瘝�������蝔桀�����

  $jnjData->continueType = $_POST['continueType'] ;
  $jnjData->recording = $_POST['recordType'];
  $jnjData->continueRecordingId = $_POST['continueMeeting'];

  // ��典��閬�敺���亦����唳�孵��敺�
  
  // 鋆�摮� ups �����剔��1 ��嗅��銝���唬��雿���貉��鋆�皛蹂��雿����
  // 閬����UPS��桀��鈭箸�豢��隤芰�渲�砌��
  $ownerId = $personal_id;
  while (strlen($ownerId) < 4) {
     $ownerId = '0'.$ownerId;
  }

  $ownerId = '2'.$ownerId;  // 每個平台有不同的開頭id ecourse為2

  //$query = "select *  from  personal_basic  where personal_id = '{$personal_id}'"; // ��芣�����銝�蝑�嚗����隞乩��敺����鞈������質�� index(0)��喳�� 

  //$query_Result = db_getAll($query);

  // ��嗡��jnjData���鞈����,�����臬遣蝡���單�����霅�

  $jnjData->ownerId = $ownerId;
  $jnjData->ownerName = db_getPersonalName(); //���敺�session霈�personal_id嚗����敺���郡ersonal Name
  // 因為ecourse是big5資料庫是utf8，所以要轉成utf8
  //$jnjData->ownerName = mb_convert_encoding($jnjData->ownerName,"UTF-8","big5");

  $jnjData->ownerEmail = db_getPersonalBasic(); //敺�session霈�personal_id嚗����敺���送mail
  $jnjData->command = 'meeting'; //甇斤�箏�單�����霅�
  $tempCourseName = db_getCourseName();
  
  $tempCourseName  = mb_convert_encoding($tempCourseName,"UTF-8","big5"); 
 
  //$jnjData->diskQuota = '50'; // 瘥�������撣急��憭����50MB
  //$jnjData->guaranteed = '0' ; 
  //$jnjData->maxoutconnection = '200'; //���霅唳��憭�摰寧����拍�曆犖(瘝�撖阡��皜祇��)
   

  //���撠���單�����霅啁��JNJ撱箇��憟踝��銝虫��撠�鞈����撖怠�啗�����摨�
  // [jfish]��嗅祕銋����撌脩��撖怠�衷njData鈭�嚗�蝑�蝑���函��銝�銝�銋�敺���舀��暻潸�����嚗����靘�靽宮ode

  

  
  $jnjformat = $jnjData->SetInstanceJnj($_POST['title'], $_POST['allquestionerType'], $_POST['website'], $_POST['recordType'], $_POST['continueType']);
  SetMyCourseNameOfMeeting($jnjData->meetingId, $tempCourseName);
  StartJoinnet($jnjformat);
  
  /*
  $errcode =$encryptor->pkeEncrypt($ans,$jnjformat,$mmc_jnj_config->privateKeyPath,$mmc_jnj_config->publicKeyPath,$mmc_jnj_config->siteId,$mmc_jnj_config->passPhrase);

  if($errcode != 0) {
      // error jnjEncrpty
      echo "error!!!";
      die ;
  }
  else
      ;
  
  // [jfish]閬���餌��UPSmmc���jnjFile閮剖��
  $jnjFile = "# if you see this file, please download and reinstall JoinNet software from http://www.homemeeting.com\r\n[general]\r\ncodetype=13\r\nip={$mcu_config->Mcu_ip}\r\ndomain=HomeMeeting\r\nportm=443\r\nportm2=\r\ngui_rec_ver=\r\ngui_min_ver=\r\nskin2=http://{$mcu_config->Mcu_ip}/Skin/Skin2_cybercc";
  $jnjFile .= "\r\nuserinfo={$ans}";

  // 蝣箄��摰����銋�敺������臭誑���  銝���嗆��甇餃��敺�������

        echo"<p>雿輻�刻牧���嚗����������蝬脰楝���霅圈��閬�摰�鋆� JoinNet 頠�擃�靘�雿輻�剁��JoinNet ��箏��鋆���潔蝙��刻��蝡臭�����鞎餉��擃����<br>
        ��冽�典��鋆� JoinNet 頠�擃�銋�敺�嚗���其����臭誑��拍�典�瑁��皜祈岫蝎暸��靘�蝣箏����函����餉�行�臬�衣泵���蝟餌絞���瘙����</p>
        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
        <tbody><tr>
        <td nowrap=\"true\">
        <p>
        <a href=\"http://www.webmeeting.com.tw/download_joinnet.php\" target=\"_blank\"><img src=\"http://mmc.elearning.ccu.edu.tw/images/icon_download.gif\" align=\"absmiddle\" border=\"0\" vspace=\"1\" hspace=\"5\">銝�頛� JoinNet</a>

        </p>
        </td>
        </tr>
        <tr>
        <td nowrap=\"true\">
        <p>
        <a href=\"http://mmc.elearning.ccu.edu.tw/joinnet_wizard.php\"><img src=\"http://mmc.elearning.ccu.edu.tw/images/icon_test_wizard.gif\" align=\"absmiddle\" border=\"0\" vspace=\"1\" hspace=\"5\">��瑁��皜祈岫蝎暸��                     </a>
        </p>

        </td>
        </tr>
        </tbody></table>";

  
  $_SESSION['jnjFile'] = $jnjFile;
  echo "<meta http-equiv='refresh' content='0;url=startJoinnet.php'>";
  */
  //hitLaunchJoinnet($jnjFile);
  
?>
