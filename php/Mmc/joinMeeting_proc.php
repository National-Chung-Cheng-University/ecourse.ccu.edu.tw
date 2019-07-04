<?php
  // 感冒快點好
  // 沒有的話進入show出是否要留言

  require_once("jnjData.php");
  require_once("mmc_config.php");
  include_once("hit_encryption.php");
  include_once("db_meeting.php");


  $RELEATED_PATH = "../";
  require_once($RELEATED_PATH . "config.php");
  require_once($RELEATED_PATH . "session.php");

  $personal_id = $_SESSION['personal_id'];            //取得個人編號
  $role_cd = $_SESSION['role_cd'];                    //取得角色
  $begin_course_cd = $_SESSION['begin_course_cd'];    //取得課程代碼


  $encryptor = new EncryptionTool();
  $jnjData = new JnjData();
  $mcu_config = new MCU_Config();
  $mmc_jnj_config = new MMC_Jnj_Config();

  $personName =  db_getPersonalName();

  if (!empty($jnjData->meetingId)) {

    // 進入Meeting
    $jnjformat = $jnjData->SetJoinMeetingJnj($personName);
    StartJoinnet($jnjformat);
    /*
    $errcode =$encryptor->pkeEncrypt($ans,$jnjformat,$mmc_jnj_config->privateKeyPath,$mmc_jnj_config->publicKeyPath,$mmc_jnj_config->siteId,$mmc_jnj_config->passPhrase);

    if($errcode != 0) {
      // error jnjEncrpty
         echo "error";
         die ;
    }
    else
        ;

    $jnjFile = "# if you see this file, please download and reinstall JoinNet software from http://www.homemeeting.com\r\n[general]\r\ncodetype=13\r\nip={$mcu_config->Mcu_ip}\r\ndomain=HomeMeeting\r\nportm=443\r\nportm2=\r\ngui_rec_ver=\r\ngui_min_ver=\r\nskin2=http://{$mcu_config->Mcu_ip}/Skin/Skin2_cybercc";
    $jnjFile .= "\r\nuserinfo={$ans}";

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

  //hitLaunchJoinnet($jnjFile);
     */

  }
  else {// 要有選項問要不要進入老師辦公室留言
      echo "<meta http-equiv='refresh' content='0;url=leavemessage.php'>";
  }

?>
