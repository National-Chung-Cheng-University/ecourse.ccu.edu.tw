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
  $personal_id = db_getAid();            //取得個人編號
  $begin_course_cd = $course_id;    //取得課程代碼
  $jnjData->courseId = $begin_course_cd;

  $jnjData->meetingTitle = $_POST['title'];
  $jnjData->meetingId = '';
  $jnjData->continueType = $_POST['continueType'] ;
  $jnjData->recording = $_POST['recordType'];
  $jnjData->continueRecordingId = $_POST['continueMeeting'];


  // 開始時間要重新組合，有的資料為 月、日、年、小時、分鐘、早上/下午
  // 轉成timestamp，因為要算結束時間會比較方便
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

  // 部分要從別的地方取得

  // 補字 cyberccu2測試機 開頭為1 然後不到五位數要補滿五位數
  $ownerId = $personal_id;
  while (strlen($ownerId) < 4) {
     $ownerId = '0'.$ownerId;
  }

  $ownerId = '2'.$ownerId;  // [jfish] 不同的平台要 ID開頭要不同 ecourse為2

  /*
  $query = "select *  from  personal_basic  where personal_id = '{$personal_id}'"; // 只會有一筆
   
  $query_Result = db_getAll($query);
   */
  /*
  $query = "select *  from begin_course where begin_course_cd = '{$begin_course_cd}'"; // 只會有一筆，所以之後的資料都讀 index(0)即可

  $query_Result = db_getAll($query);
  */
  // $tempCourse_cd = $query_Result[0]["course_cd"]; //因為只有一筆所以只讀0
  // $courseName = $query_Result[0]["begin_course_name"];
  $courseName = db_getCourseName();

  // 其他jnjData的資料,這是建立預約會議

  $jnjData->ownerId = $ownerId;
  $jnjData->ownerName = db_getPersonalName(); //因為只有一筆所以只讀0
  $jnjData->ownerEmail = db_getPersonalBasic(); //因為只有一筆所以只讀0
  $jnjData->command = ''; // 不需要填這個，因為開會議的時候會填上
  // $jnjData->diskQuota = '50';
  // $jnjData->guaranteed = '0' ;

  // 將資料寫入資料庫
  InsertReservationMeetingToDB($jnjData);

  if ($tempSendMail == 1) { // 寄信給全班
      mailMeetingInfo_to_students ($jnjData,$dateString);	
/*
      $mail_entry = db_getCourseStuEmail();

      $from= 'elearning@hsng.cs.ccu.edu.tw'; // [jfish] 要改mail位置
      $fromName= '學習平台'; // [jfish] 可能也要改
      $subject = $jnjData->ownerName . '老師的'.$courseName .' 課程預約會議通知';

      $minDuration = $jnjData->duration/60;
      $tempagenda = nl2br($jnjData->agenda);

      // [jfish] 信件內容可能也要改
      $message = "
      同學您好,
       <br><br>
     {$jnjData->ownerName}老師在{$courseName}課程預約了會議。
     <br>標題：{$jnjData->meetingTitle}
     <br>開始時間：{$dateString}
     <br>時間長度：{$minDuration} 分鐘
     <br>議程/留言：
     <br>{$tempagenda}
     <br><br>
     請在會議開始時間，至ecourse的'討論區' --> '老師網路辦公室'
     <br>這是系統自動發出的信件，請勿回覆。
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

  // 想讓他導到一頁去show建立預約會議成功 
  echo "預約會議: ".$jnjData->meetingTitle."<br>開始時間: ". $dateString."<br><br>建立完成";
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
