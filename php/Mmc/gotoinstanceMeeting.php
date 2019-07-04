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

  $personal_id = db_getAid();            // ���o�ӤH�s��

  // $begin_course_cd = $_SESSION['begin_course_cd'];    //取得課程代碼

  
  $jnjData->courseId = $course_id;

  $jnjData->meetingTitle = $_POST['title'];
  $jnjData->meetingId = ''; // 在之後SetInstanceJnj後會決定
  $jnjData->jointBrowsingUrl = $_POST['website'];
  // $jnjData->allQuestioner    = $_POST['allquestionerType']; //基本上只會有0，因為太御說可以把它拿掉，所以我沒做這種功能

  $jnjData->continueType = $_POST['continueType'] ;
  $jnjData->recording = $_POST['recordType'];
  $jnjData->continueRecordingId = $_POST['continueMeeting'];

  // 部分要從別的地方取得
  
  // 補字 ups 開頭為1 然後不到五位數要補滿五位數
  // 要看UPS目前人數據說破萬了
  $ownerId = $personal_id;
  while (strlen($ownerId) < 4) {
     $ownerId = '0'.$ownerId;
  }

  $ownerId = '2'.$ownerId;  // �C�ӥ��x�����P���}�Yid ecourse��2

  //$query = "select *  from  personal_basic  where personal_id = '{$personal_id}'"; // 只會有一筆，所以之後的資料都讀 index(0)即可 

  //$query_Result = db_getAll($query);

  // 其他jnjData的資料,這是建立即時會議

  $jnjData->ownerId = $ownerId;
  $jnjData->ownerName = db_getPersonalName(); //會從session讀personal_id，而得到personal Name
  // �]��ecourse�Obig5��Ʈw�Outf8�A�ҥH�n�নutf8
  //$jnjData->ownerName = mb_convert_encoding($jnjData->ownerName,"UTF-8","big5");

  $jnjData->ownerEmail = db_getPersonalBasic(); //從session讀personal_id，而得到email
  $jnjData->command = 'meeting'; //此為即時會議
  $tempCourseName = db_getCourseName();
  
  $tempCourseName  = mb_convert_encoding($tempCourseName,"UTF-8","big5"); 
 
  //$jnjData->diskQuota = '50'; // 每個老師最多用50MB
  //$jnjData->guaranteed = '0' ; 
  //$jnjData->maxoutconnection = '200'; //會議最多容納兩百人(沒實際測過)
   

  //會將即時會議的JNJ建立好，並且將資料寫到資料庫
  // [jfish]其實之前已經寫到JnjData了，等等在看一下之後是怎麼處理，再來修code

  

  
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
  
  // [jfish]要去看UPSmmc的jnjFile設定
  $jnjFile = "# if you see this file, please download and reinstall JoinNet software from http://www.homemeeting.com\r\n[general]\r\ncodetype=13\r\nip={$mcu_config->Mcu_ip}\r\ndomain=HomeMeeting\r\nportm=443\r\nportm2=\r\ngui_rec_ver=\r\ngui_min_ver=\r\nskin2=http://{$mcu_config->Mcu_ip}/Skin/Skin2_cybercc";
  $jnjFile .= "\r\nuserinfo={$ans}";

  // 確認完成之後才可以開  不然會死得很難看

        echo"<p>使用說明：我們的網路會議需要安裝 JoinNet 軟體來使用，JoinNet 為安裝於使用者端之免費軟體。<br>
        在您安裝 JoinNet 軟體之後，您也可以利用執行測試精靈來確定您的電腦是否符合系統需求。</p>
        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
        <tbody><tr>
        <td nowrap=\"true\">
        <p>
        <a href=\"http://www.webmeeting.com.tw/download_joinnet.php\" target=\"_blank\"><img src=\"http://mmc.elearning.ccu.edu.tw/images/icon_download.gif\" align=\"absmiddle\" border=\"0\" vspace=\"1\" hspace=\"5\">下載 JoinNet</a>

        </p>
        </td>
        </tr>
        <tr>
        <td nowrap=\"true\">
        <p>
        <a href=\"http://mmc.elearning.ccu.edu.tw/joinnet_wizard.php\"><img src=\"http://mmc.elearning.ccu.edu.tw/images/icon_test_wizard.gif\" align=\"absmiddle\" border=\"0\" vspace=\"1\" hspace=\"5\">執行測試精靈                     </a>
        </p>

        </td>
        </tr>
        </tbody></table>";

  
  $_SESSION['jnjFile'] = $jnjFile;
  echo "<meta http-equiv='refresh' content='0;url=startJoinnet.php'>";
  */
  //hitLaunchJoinnet($jnjFile);
  
?>
