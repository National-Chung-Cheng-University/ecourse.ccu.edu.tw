<?php
  //產生play mode的jnj並且啟動joinnet

  include_once("db_meeting.php");
  require_once("jnjData.php");
  require_once("hit_encryption.php");
  require_once("platform_config.php");
  $RELEATED_PATH = "../";
  require_once($RELEATED_PATH . "fadmin.php");
  /*
  require_once($RELEATED_PATH . "config.php");
  require_once($RELEATED_PATH . "session.php");
  */
if(isset($PHPSESSID)) {
  $personal_id = db_getAid();            //取得個人編號
  $begin_course_cd = $couse_id;    //取得課程代碼

  $encryptor = new EncryptionTool();
  $jnjData = new jnjData();
  $mcu_config = new MCU_Config();
  $mmc_jnj_config = new MMC_Jnj_Config();

  $jnjData->courseId = $begin_course_cd;

  $jnjData->meetingId = $_REQUEST['meetingId'];
  
  $jnjData->courseId =  GetCourseIdByMeetingId($jnjData->meetingId);
  // 藉由課程id找出老師的名字與email
  /*
  $query = "select *  from begin_course where begin_course_cd = '{$jnjData->courseId}'"; // 只會有一筆，所以之後的資料都讀 index(0)即可

  $query_Result = db_getAll($query);

  $tempCourse_cd = $query_Result[0]["course_cd"]; //因為只有一筆所以只讀0

  $query = "select *  from course_basic where course_cd = '{$tempCourse_cd}'"; // 只會有一筆，所以之後的資料都讀 index(0)即可

  $query_Result = db_getAll($query);

  $tempTeacher_cd = $query_Result[0]["teacher_cd"]; //因為只有一筆所以只讀0

  $query = "select *  from personal_basic where  personal_id  = '{$tempTeacher_cd}'"; // 只會有一筆，所以之後的資料都讀 index(0)即可

  $query_Result = db_getAll($query);
  */
  
  //$query = "select teacher_cd from teach_begin_course where begin_course_cd={$jnjData->courseId} and course_master=1";
  
  //$query = "select *  from begin_course where begin_course_cd = '{$begin_course_cd}'"; // 只會有一筆，所以之後的資料都讀 index(0)即可
  
  // $tempTeacher_cd = db_getOne($query);
  // 老師資訊
  /*
  $jnjData->ownerName = $query_Result[0]["personal_name"]; //因為只有一筆所以只讀0
  $jnjData->ownerEmail = $query_Result[0]["email"]; //因為只有一筆所以只讀0
  */
  
  //$jnjData->ownerName = db_getPersonalName($tempTeacher_cd);
  //$jnjData->ownerEmail = db_getPersonalBasic($tempTeacher_cd,'email');
  /*
  $query = "select *  from personal_basic where  personal_id  = '{$personal_id}'"; // 只會有一筆，所以之後的資料都讀 index(0)即可

  $query_Result = db_getAll($query);

  $personName = $query_Result[0]["personal_name"];
   */
  $personName = db_getPersonalName();
  /*
  $jnjData->diskQuota = '50';
  $jnjData->guaranteed = '0' ;
  $jnjData->maxoutconnection = '200';
  */
  $jnjData->command = 'playback' ;


  GetPlaymodeInfoByMeetingId($jnjData);

    // 進入Meeting
    $jnjformat = $jnjData->SetPlaybackMeetingJnj($personName);
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

    $jnjFile = "# if you see this file, please download and reinstall JoinNet software from http://www.homemeeting.com\r\n[general]\r\ncodetype=13\r\nip={$mcu_config->Mcu_ip}\r\ndomain=HomeMeeting\r\nportm=443\r\nportm2=\r\naction=1\r\ngui_rec_ver=\r\ngui_min_ver=\r\nskin2=http://{$mcu_config->Mcu_ip}/Skin/Skin2_cybercc";
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

