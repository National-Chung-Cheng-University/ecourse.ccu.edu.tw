<?php
  // �}�ҷǳƼҦ����|ĳ
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
  $personal_id = db_getAid();            //���o�ӤH�s��
  $begin_course_cd = $course_id;    //���o�ҵ{�N�X

  $encryptor = new EncryptionTool();
  $jnjData = new JnjData(); 
  $mcu_config = new MCU_Config();
  $mmc_jnj_config = new MMC_Jnj_Config();


  $jnjData->meetingId = $_GET['mid'];
  /*
  $query = "select *  from  personal_basic  where personal_id = '{$personal_id}'"; // �u�|���@���A�ҥH���᪺��Ƴ�Ū index(0)�Y�i

  $query_Result = db_getAll($query);
  */
  /*
  $jnjData->ownerName = $query_Result[0]["personal_name"]; //�]���u���@���ҥH�uŪ0
  $jnjData->ownerEmail = $query_Result[0]["email"]; //�]���u���@���ҥH�uŪ0
   */
  $jnjData->ownerName = db_getPersonalName(); //�|�qsessionŪpersonal_id�A�ӱo��personal Name

  $jnjData->ownerEmail = db_getPersonalBasic(null,'email'); //�qsessionŪpersonal_id�A�ӱo��email

  $jnjData->command = 'meeting';
  /*
  $jnjData->diskQuota = '50';
  $jnjData->guaranteed = '0' ;
  $jnjData->maxoutconnection = '200';
   */

  SearchMeetingByMeetingId($jnjData);
  // SetReservationJnj���ѼƬOtrue�N��O�i�J�ǳƼҦ�
  $jnjformat = $jnjData->SetReservationJnj(true);
  // ����n�n��U�����Ѽƥ����ܦ��ܼ�
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

