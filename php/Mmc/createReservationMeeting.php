<?php

  require_once("jnjData.php");
  require_once("platform_config.php");
  $RELEATED_PATH = "../";
  require_once($RELEATED_PATH . "fadmin.php");
  /*
  require_once($RELEATED_PATH . "config.php");
  require_once($RELEATED_PATH . "session.php");
  */

  // require_once(  '../library/mail.php');
  global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id, $user_id;  
  $jnjData = new JnjData();

if(isset($PHPSESSID) && session_check_teach($PHPSESSID) == 2) {
  $personal_id = db_getAid();            //���o�ӤH�s��
  $begin_course_cd = $course_id;    //���o�ҵ{�N�X
  $jnjData->courseId = $begin_course_cd;

  $jnjData->meetingTitle = $_POST['title'];
  $jnjData->meetingId = '';
  $jnjData->continueType = $_POST['continueType'] ;
  $jnjData->recording = $_POST['recordType'];
  $jnjData->continueRecordingId = $_POST['continueMeeting'];


  // �}�l�ɶ��n���s�զX�A������Ƭ� ��B��B�~�B�p�ɡB�����B���W/�U��
  // �নtimestamp�A�]���n�⵲���ɶ��|�����K
  $tempMonth = $_POST['startTimeMonth'];
  $tempDay = $_POST['startTimeDay'];
  $tempYear = $_POST['startTimeYear'];
  $tempHour = $_POST['startTimeHour'];
  $tempMinutes = $_POST['startTimeMinutes'];
  if ($_POST['morring'] != 0 ) {
    $tempAMPM = "AM";
  }
  else {
    $tempAMPM = "PM";
  }

  $dateString = $tempYear."-".$tempMonth."-".$tempDay." ".$tempHour.":".$tempMinutes.":00 ".$tempAMPM;
  $jnjData->timestamp = strtotime($dateString);

  $jnjData->duration = $_POST['duration'];
  $jnjData->maxoutconnection = $_POST['connectionCount'];
  $jnjData->agenda = $_POST['agenda'];
  $tempSendMail = $_POST['sendMail'];

  // �����n�q�O���a����o

  // �ɦr cyberccu2���վ� �}�Y��1 �M�ᤣ�줭��ƭn�ɺ������
  $ownerId = $personal_id;
  while (strlen($ownerId) < 4) {
     $ownerId = '0'.$ownerId;
  }

  $ownerId = '2'.$ownerId;  // [jfish] ���P�����x�n ID�}�Y�n���P ecourse��2

  /*
  $query = "select *  from  personal_basic  where personal_id = '{$personal_id}'"; // �u�|���@��
   
  $query_Result = db_getAll($query);
   */
  /*
  $query = "select *  from begin_course where begin_course_cd = '{$begin_course_cd}'"; // �u�|���@���A�ҥH���᪺��Ƴ�Ū index(0)�Y�i

  $query_Result = db_getAll($query);
  */
  // $tempCourse_cd = $query_Result[0]["course_cd"]; //�]���u���@���ҥH�uŪ0
  // $courseName = $query_Result[0]["begin_course_name"];
  $courseName = db_getCourseName();

  // ��LjnjData�����,�o�O�إ߹w���|ĳ

  $jnjData->ownerId = $ownerId;
  $jnjData->ownerName = db_getPersonalName(); //�]���u���@���ҥH�uŪ0
  $jnjData->ownerEmail = db_getPersonalBasic(); //�]���u���@���ҥH�uŪ0
  $jnjData->command = ''; // ���ݭn��o�ӡA�]���}�|ĳ���ɭԷ|��W
  // $jnjData->diskQuota = '50';
  // $jnjData->guaranteed = '0' ;

  // �N��Ƽg�J��Ʈw
  InsertReservationMeetingToDB($jnjData);

  if ($tempSendMail == 1) { // �H�H�����Z
      mailMeetingInfo_to_students ($jnjData,$dateString);	
/*
      $mail_entry = db_getCourseStuEmail();

      $from= 'elearning@hsng.cs.ccu.edu.tw'; // [jfish] �n��mail��m
      $fromName= '�ǲߥ��x'; // [jfish] �i��]�n��
      $subject = $jnjData->ownerName . '�Ѯv��'.$courseName .' �ҵ{�w���|ĳ�q��';

      $minDuration = $jnjData->duration/60;
      $tempagenda = nl2br($jnjData->agenda);

      // [jfish] �H�󤺮e�i��]�n��
      $message = "
      �P�Ǳz�n,
       <br><br>
     {$jnjData->ownerName}�Ѯv�b{$courseName}�ҵ{�w���F�|ĳ�C
     <br>���D�G{$jnjData->meetingTitle}
     <br>�}�l�ɶ��G{$dateString}
     <br>�ɶ����סG{$minDuration} ����
     <br>ĳ�{/�d���G
     <br>{$tempagenda}
     <br><br>
     �Цb�|ĳ�}�l�ɶ��A��ecourse��'�Q�װ�' --> '�Ѯv�����줽��'
     <br>�o�O�t�Φ۰ʵo�X���H��A�ФŦ^�СC
     ";

    $output=Array();
    //print $message;
    foreach($mail_entry as $value){
        $output[] = $v;
    if (!empty($value['email']))
        $to[]=$value['email'];
    }

    mailto($from , $fromName, $to, $subject, $message );
*/
  }
  else
      ;

  // �Q���L�ɨ�@���hshow�إ߹w���|ĳ���\ 
  echo "�w���|ĳ: ".$jnjData->meetingTitle."<br>�}�l�ɶ�: ". $dateString."<br><br>�إߧ���";
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
