<?php
  // 開啟準備模式的會議
  require_once("jnjData.php");
  include_once("hit_encryption.php");
  include_once("db_meeting.php");
 
  require_once("platform_config.php");
  $RELEATED_PATH = "../";
  require_once($RELEATED_PATH . "fadmin.php"); 
  /*
  require_once($RELEATED_PATH . "config.php");
  require_once($RELEATED_PATH . "session.php");
  */


if(isset($PHPSESSID) && session_check_teach($PHPSESSID) == 2) {
  $personal_id = db_getAid();            //取得個人編號
  $begin_course_cd = $course_id;    //取得課程代碼

  $encryptor = new EncryptionTool();
  $jnjData = new JnjData(); 
  $mcu_config = new MCU_Config();
  $mmc_jnj_config = new MMC_Jnj_Config();


  $jnjData->meetingId = $_GET['mid'];
  /*
  $query = "select *  from  personal_basic  where personal_id = '{$personal_id}'"; // 只會有一筆，所以之後的資料都讀 index(0)即可

  $query_Result = db_getAll($query);
  */
  /*
  $jnjData->ownerName = $query_Result[0]["personal_name"]; //因為只有一筆所以只讀0
  $jnjData->ownerEmail = $query_Result[0]["email"]; //因為只有一筆所以只讀0
   */
  $jnjData->ownerName = db_getPersonalName(); //會從session讀personal_id，而得到personal Name

  $jnjData->ownerEmail = db_getPersonalBasic(null,'email'); //從session讀personal_id，而得到email

  $jnjData->command = 'meeting';
  /*
  $jnjData->diskQuota = '50';
  $jnjData->guaranteed = '0' ;
  $jnjData->maxoutconnection = '200';
   */

  SearchMeetingByMeetingId($jnjData);
  // SetReservationJnj的參數是true代表是進入準備模式
  $jnjformat = $jnjData->SetReservationJnj(true);
  // 之後要要把下面的參數全都變成變數
  /*
  $errcode =$encryptor->pkeEncrypt($ans,$jnjformat,
                                    "/home/opera/WWW/Mmc/key_web_localhost",
                                    "/home/opera/WWW/Mmc/key_mcu_localhost.x509",
                                    "key_web_localhost",
                                    "autogenerate");
   */
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

  // 確認完成之後才可以開  不然會死得很難看
  // hitLaunchJoinnet($jnjFile);
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

  }
  else {

        if( $version=="C" ) {
                show_page( "not_access.tpl" ,"你沒有權限使用此功能");
                exit;
        }
        else {
                show_page( "not_access.tpl" ,"You have No Permission!!");
                exit;
        }
  }
?>

