<?php
  //�R�����v��

  include_once("mmc_config.php");
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

if(isset($PHPSESSID) && session_check_teach($PHPSESSID) == 2) {
  $personal_id = db_getAid();            //���o�ӤH�s��
  $begin_course_cd = $course_id;    //���o�ҵ{�N�X

  //$encryptor = new EncryptionTool();
  $jnjData = new jnjData();

  $mmc_path_config = new MMC_Path_Config();

  //$jnjData->courseId = $begin_course_cd;

  $jnjData->meetingId = $_REQUEST['meetingId'];
 
  $jnjData->courseId =  GetCourseIdByMeetingId($jnjData->meetingId); // [jfish] �����D�٭n���n�A�]���R���n���S�t�A�ݰ_�ӬO���e�ΧO����S�M���b
  // �ǥѽҵ{id��X�Ѯv���W�r�Pemail
  //
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
  /*
  $query = "select teacher_cd from teach_begin_course where begin_course_cd={$begin_cd} and course_master=1";

  //$query = "select *  from begin_course where begin_course_cd = '{$begin_course_cd}'"; // �u�|���@���A�ҥH���᪺��Ƴ�Ū index(0)�Y�i

  $tempTeacher_cd = db_getOne($query);

  // �Ѯv��T
  $jnjData->ownerName = db_getPersonalName($tempTeacher_cd);
  $jnjData->ownerEmail = db_getPersonalBasic($tempTeacher_cd,'email');

  $query = "select *  from personal_basic where  personal_id  = '{$personal_id}'"; // �u�|���@���A�ҥH���᪺��Ƴ�Ū index(0)�Y�i

  $query_Result = db_getAll($query);

  $personName = $query_Result[0]["personal_name"];
  */
  GetPlaymodeInfoByMeetingId($jnjData);
  $fileName=SearchingRecordingFileName($jnjData->meetingId);
  // ps������string���mcu���x�����n�γo������"eow3HXfg7x"
  // [jfish] �o��]�Q���javascript�A���M�@�I�γ��S��
  echo "<meta http-equiv='refresh' content='0;url=link_MMC.php?id={$jnjData->ownerId}&mid={$jnjData->meetingId}&fn={$fileName}&op=1'>";
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

