<?php
  //����play mode��jnj�åB�Ұ�joinnet

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
  $personal_id = db_getAid();            //���o�ӤH�s��
  $begin_course_cd = $couse_id;    //���o�ҵ{�N�X

  $encryptor = new EncryptionTool();
  $jnjData = new jnjData();
  $mcu_config = new MCU_Config();
  $mmc_jnj_config = new MMC_Jnj_Config();

  $jnjData->courseId = $begin_course_cd;

  $jnjData->meetingId = $_REQUEST['meetingId'];
  
  $jnjData->courseId =  GetCourseIdByMeetingId($jnjData->meetingId);
  // �ǥѽҵ{id��X�Ѯv���W�r�Pemail
  /*
  $query = "select *  from begin_course where begin_course_cd = '{$jnjData->courseId}'"; // �u�|���@���A�ҥH���᪺��Ƴ�Ū index(0)�Y�i

  $query_Result = db_getAll($query);

  $tempCourse_cd = $query_Result[0]["course_cd"]; //�]���u���@���ҥH�uŪ0

  $query = "select *  from course_basic where course_cd = '{$tempCourse_cd}'"; // �u�|���@���A�ҥH���᪺��Ƴ�Ū index(0)�Y�i

  $query_Result = db_getAll($query);

  $tempTeacher_cd = $query_Result[0]["teacher_cd"]; //�]���u���@���ҥH�uŪ0

  $query = "select *  from personal_basic where  personal_id  = '{$tempTeacher_cd}'"; // �u�|���@���A�ҥH���᪺��Ƴ�Ū index(0)�Y�i

  $query_Result = db_getAll($query);
  */
  
  //$query = "select teacher_cd from teach_begin_course where begin_course_cd={$jnjData->courseId} and course_master=1";
  
  //$query = "select *  from begin_course where begin_course_cd = '{$begin_course_cd}'"; // �u�|���@���A�ҥH���᪺��Ƴ�Ū index(0)�Y�i
  
  // $tempTeacher_cd = db_getOne($query);
  // �Ѯv��T
  /*
  $jnjData->ownerName = $query_Result[0]["personal_name"]; //�]���u���@���ҥH�uŪ0
  $jnjData->ownerEmail = $query_Result[0]["email"]; //�]���u���@���ҥH�uŪ0
  */
  
  //$jnjData->ownerName = db_getPersonalName($tempTeacher_cd);
  //$jnjData->ownerEmail = db_getPersonalBasic($tempTeacher_cd,'email');
  /*
  $query = "select *  from personal_basic where  personal_id  = '{$personal_id}'"; // �u�|���@���A�ҥH���᪺��Ƴ�Ū index(0)�Y�i

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

    // �i�JMeeting
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
  echo "<meta http-equiv='refresh' content='0;url=startJoinnet.php'>";
  */
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

