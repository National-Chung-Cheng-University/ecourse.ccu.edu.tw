<?php

  require_once("jnjData.php");
  require_once("hit_encryption.php");
  require_once("mmc_config.php");
  // 這台怪怪的，只好寫個中文註解
  $RELEATED_PATH = "../";
  require_once($RELEATED_PATH . "fadmin.php");
  require_once("platform_config.php");
  /*
  require_once($RELEATED_PATH . "config.php");
  require_once($RELEATED_PATH . "session.php");
  */
  if(isset($PHPSESSID) && session_check_teach($PHPSESSID) == 2) {

    global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id, $user_id;

    $personal_id = db_getAid();            //取得個人編號
    $begin_course_cd = $_SESSION['begin_course_cd'];    //取得課程代碼

    $encryptor = new EncryptionTool();
    $jnjData = new jnjData();

    $mcu_config = new MCU_Config();
    $mmc_jnj_config = new MMC_Jnj_Config();

    $jnjData->courseId = $course_id;

  
    // 補字 cyberccu2測試機 開頭為1 然後不到五位數要補滿五位數
    $ownerId = $personal_id;
    while (strlen($ownerId) < 4) {
       $ownerId = '0'.$ownerId;
    }

    $ownerId = '2'.$ownerId;  // 不同的平台要 ID開頭要不同 ecourse為2
 
    // 先知道ownerid才可以去查他的預約會議有哪些
    $jnjData->ownerId = $ownerId;

   
    $jnjData->command = 'meeting';
    /*
    $jnjData->diskQuota = '50';
    $jnjData->guaranteed = '0' ;
    $jnjData->maxoutconnection = '200';
    */
    /*
    $query = "select *  from  personal_basic  where personal_id = '{$personal_id}'"; // 只會有一筆，所以之後的資料都讀 index(0)即可

  $query_Result = db_getAll($query);
  */
  //$jnjData->ownerName = $query_Result[0]["personal_name"]; //因為只有一筆所以只讀0
  //$jnjData->ownerEmail = $query_Result[0]["email"]; //因為只有一筆所以只讀0

    $jnjData->ownerName = db_getPersonalName(); //會從session讀personal_id，而得到personal Name

    $jnjData->ownerEmail = db_getPersonalBasic(); //從session讀personal_id，而得到email

    $personName = $jnjData->ownerName ;
    $tempCourseName = db_getCourseName(); 

    // 防止多個老師教同一門課，且已經有老師開啟線上會議了
    if($_POST['repeat'] != 1) {
        SearchOnlineMeeting($begin_course_cd,"enterweboffice.php");
        die;
    }

    if($_POST['mid'] != -1 ) {
       $jnjData = GetOnlineMeetingInfo($jnjData,$_POST['mid']);
    }
    // SearchOnlineMeeting($jnjData);
    if (!empty($jnjData->meetingId)) {
      // 進入Meeting
      $jnjformat = $jnjData->SetJoinMeetingJnj($personName);
      StartJoinnet($jnjformat);
    }
    else {
      // 查看本門課今天是否有會議，沒有會議直接到進入即時會議
      // IsTodayReservationMeeting 會回傳三種字串instance (表示今天沒有任何預約會議) 
      //                                         prepare(表示今天有會議，但離開始還有15分鐘顯示出來讓老師可以選擇進入準備模式)
      //                                         enter(表是今日預約的會議即將在15分鐘內開始，直接進入今天的會議)
      $meetingCommand = IsTodayReservationMeeting($jnjData);

      if ($meetingCommand == "instance") {
          // 直接進入創立即時會議
         echo "<meta http-equiv='refresh' content='0;url=instanceMeeting.php'>";
      }
      else if ( $meetingCommand == "prepare"){ // 今日有會議，但離開始時間還有15分鐘以上
          // 跳出該會議資訊，並且給予準備，或修改 (今日所有的會議)
         echo "<meta http-equiv='refresh' content='0;url=listTodayMeeting.php'>";   
      }
      else { //今日有會議，已經開始了 (而且在預約時間結束前)
          // 因為要傳的資料太多，所以會在這之php直接進入預約會議，不做導頁
          // 直接進入最近的預約會議
          // echo "<meta http-equiv='refresh' content='0;url=joinReservationMeeting.php?mid=".$jnjData->meetingId."'>";
      
          // [jfish] SetReservationJnj的參數 true表示進入準備模式，flase表示直接進入預約好的會議
          $jnjformat = $jnjData->SetReservationJnj(false);
          $tempCourseName  = mb_convert_encoding($tempCourseName,"UTF-8","big5");
          SetMyCourseNameOfMeeting($jnjData->meetingId, $tempCourseName);
          StartJoinnet($jnjformat);
    
      // 之後要要把下面的參數全都變成變數
      /*
      $errcode =$encryptor->pkeEncrypt($ans,$jnjformat,
                                    "/home/opera/WWW/Mmc/key_web_localhost",
                                    "/home/opera/WWW/Mmc/key_mcu_localhost.x509",
                                    "key_web_localhost",
                                    "autogenerate");
       */
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
       // echo "<meta http-equiv='refresh' content='0;url=startJoinnet.php'>";
      // hitLaunchJoinnet($jnjFile);
      */
      }
    }
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

