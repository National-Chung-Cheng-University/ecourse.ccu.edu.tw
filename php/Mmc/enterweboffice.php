<?php

  require_once("jnjData.php");
  require_once("hit_encryption.php");
  require_once("mmc_config.php");
  // �o�x�ǩǪ��A�u�n�g�Ӥ������
  $RELEATED_PATH = "../";
  require_once($RELEATED_PATH . "fadmin.php");
  require_once("platform_config.php");
  /*
  require_once($RELEATED_PATH . "config.php");
  require_once($RELEATED_PATH . "session.php");
  */
  if(isset($PHPSESSID) && session_check_teach($PHPSESSID) == 2) {

    global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id, $user_id;

    $personal_id = db_getAid();            //���o�ӤH�s��
    $begin_course_cd = $_SESSION['begin_course_cd'];    //���o�ҵ{�N�X

    $encryptor = new EncryptionTool();
    $jnjData = new jnjData();

    $mcu_config = new MCU_Config();
    $mmc_jnj_config = new MMC_Jnj_Config();

    $jnjData->courseId = $course_id;

  
    // �ɦr cyberccu2���վ� �}�Y��1 �M�ᤣ�줭��ƭn�ɺ������
    $ownerId = $personal_id;
    while (strlen($ownerId) < 4) {
       $ownerId = '0'.$ownerId;
    }

    $ownerId = '2'.$ownerId;  // ���P�����x�n ID�}�Y�n���P ecourse��2
 
    // �����Downerid�~�i�H�h�d�L���w���|ĳ������
    $jnjData->ownerId = $ownerId;

   
    $jnjData->command = 'meeting';
    /*
    $jnjData->diskQuota = '50';
    $jnjData->guaranteed = '0' ;
    $jnjData->maxoutconnection = '200';
    */
    /*
    $query = "select *  from  personal_basic  where personal_id = '{$personal_id}'"; // �u�|���@���A�ҥH���᪺��Ƴ�Ū index(0)�Y�i

  $query_Result = db_getAll($query);
  */
  //$jnjData->ownerName = $query_Result[0]["personal_name"]; //�]���u���@���ҥH�uŪ0
  //$jnjData->ownerEmail = $query_Result[0]["email"]; //�]���u���@���ҥH�uŪ0

    $jnjData->ownerName = db_getPersonalName(); //�|�qsessionŪpersonal_id�A�ӱo��personal Name

    $jnjData->ownerEmail = db_getPersonalBasic(); //�qsessionŪpersonal_id�A�ӱo��email

    $personName = $jnjData->ownerName ;
    $tempCourseName = db_getCourseName(); 

    // ����h�ӦѮv�ЦP�@���ҡA�B�w�g���Ѯv�}�ҽu�W�|ĳ�F
    if($_POST['repeat'] != 1) {
        SearchOnlineMeeting($begin_course_cd,"enterweboffice.php");
        die;
    }

    if($_POST['mid'] != -1 ) {
       $jnjData = GetOnlineMeetingInfo($jnjData,$_POST['mid']);
    }
    // SearchOnlineMeeting($jnjData);
    if (!empty($jnjData->meetingId)) {
      // �i�JMeeting
      $jnjformat = $jnjData->SetJoinMeetingJnj($personName);
      StartJoinnet($jnjformat);
    }
    else {
      // �d�ݥ����Ҥ��ѬO�_���|ĳ�A�S���|ĳ������i�J�Y�ɷ|ĳ
      // IsTodayReservationMeeting �|�^�ǤT�ئr��instance (��ܤ��ѨS������w���|ĳ) 
      //                                         prepare(��ܤ��Ѧ��|ĳ�A�����}�l�٦�15������ܥX�����Ѯv�i�H��ܶi�J�ǳƼҦ�)
      //                                         enter(��O����w�����|ĳ�Y�N�b15�������}�l�A�����i�J���Ѫ��|ĳ)
      $meetingCommand = IsTodayReservationMeeting($jnjData);

      if ($meetingCommand == "instance") {
          // �����i�J�ХߧY�ɷ|ĳ
         echo "<meta http-equiv='refresh' content='0;url=instanceMeeting.php'>";
      }
      else if ( $meetingCommand == "prepare"){ // ���馳�|ĳ�A�����}�l�ɶ��٦�15�����H�W
          // ���X�ӷ|ĳ��T�A�åB�����ǳơA�έק� (����Ҧ����|ĳ)
         echo "<meta http-equiv='refresh' content='0;url=listTodayMeeting.php'>";   
      }
      else { //���馳�|ĳ�A�w�g�}�l�F (�ӥB�b�w���ɶ������e)
          // �]���n�Ǫ���ƤӦh�A�ҥH�|�b�o��php�����i�J�w���|ĳ�A�����ɭ�
          // �����i�J�̪񪺹w���|ĳ
          // echo "<meta http-equiv='refresh' content='0;url=joinReservationMeeting.php?mid=".$jnjData->meetingId."'>";
      
          // [jfish] SetReservationJnj���Ѽ� true��ܶi�J�ǳƼҦ��Aflase��ܪ����i�J�w���n���|ĳ
          $jnjformat = $jnjData->SetReservationJnj(false);
          $tempCourseName  = mb_convert_encoding($tempCourseName,"UTF-8","big5");
          SetMyCourseNameOfMeeting($jnjData->meetingId, $tempCourseName);
          StartJoinnet($jnjformat);
    
      // ����n�n��U�����Ѽƥ����ܦ��ܼ�
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

      // �T�{��������~�i�H�}  ���M�|���o������

        // hitLaunchJoinnet($jnjFile);
        echo"<p>�ϥλ����G�ڭ̪������|ĳ�ݭn�w�� JoinNet �n��ӨϥΡAJoinNet ���w�˩�ϥΪ̺ݤ��K�O�n��C<br>
        �b�z�w�� JoinNet �n�餧��A�z�]�i�H�Q�ΰ�����պ��F�ӽT�w�z���q���O�_�ŦX�t�λݨD�C</p>
        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
        <tbody><tr>
        <td nowrap=\"true\">
        <p>
        <a href=\"http://www.webmeeting.com.tw/download_joinnet.php\" target=\"_blank\"><img src=\"http://mmc.elearning.ccu.edu.tw/images/icon_download.gif\" align=\"absmiddle\" border=\"0\" vspace=\"1\" hspace=\"5\">�U�� JoinNet</a>

        </p>
        </td>
        </tr>
        <tr>
        <td nowrap=\"true\">
        <p>
        <a href=\"http://mmc.elearning.ccu.edu.tw/joinnet_wizard.php\"><img src=\"http://mmc.elearning.ccu.edu.tw/images/icon_test_wizard.gif\" align=\"absmiddle\" border=\"0\" vspace=\"1\" hspace=\"5\">������պ��F                     </a>
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
                show_page( "not_access.tpl" ,"�A�S���v���ϥΦ��\��");
                exit;
        }
        else {
                show_page( "not_access.tpl" ,"You have No Permission!!");
                exit;
        }
  }  
?>

