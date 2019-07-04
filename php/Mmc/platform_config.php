<?php
  $RELEATED_PATH = "../";
  require_once($RELEATED_PATH . "fadmin.php");


function db_getAid() {
  global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id, $user_id;

  if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
      show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
      exit;
  }

  $sql="select * from user where id='{$user_id}'";
  if ( !($result = mysql_db_query( $DB, $sql ) ) ) {
       show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
       exit;
  }
  $row = mysql_fetch_array($result);
  return $row['a_id'];
}

function db_getTeacherAid() {
  global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id, $user_id;

  if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
      show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
      exit;
  }

  $sql="select * from teach_course where course_id='{$course_id}'";
  if ( !($result = mysql_db_query( $DB, $sql ) ) ) {
       show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
       exit;
  }
  $row = mysql_fetch_array($result);

  return $row['teacher_id'];

}

function db_getTeacherName() {
  global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id, $user_id;

  if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
      show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
      exit;
  }

  $sql="select * from teach_course where course_id='{$course_id}'";
  if ( !($result = mysql_db_query( $DB, $sql ) ) ) {
       show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
       exit;
  }
  $row = mysql_fetch_array($result);
  $sql = "select name from user where a_id={$row['teacher_id']}";
  if ( !($result = mysql_db_query( $DB, $sql ) ) ) {
       show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
       exit;
  }
  $row1 = mysql_fetch_array($result);
  return $row1['name'];
}

function db_getTeacherEmail() {
  global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id, $user_id;

  if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
      show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
      exit;
  }

  $sql="select * from teach_course where course_id='{$course_id}'";
  if ( !($result = mysql_db_query( $DB, $sql ) ) ) {
       show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
       exit;
  }
  $row = mysql_fetch_array($result);

  $sql = "select email from user where a_id={$row['teacher_id']}";
  if ( !($result = mysql_db_query( $DB, $sql ) ) ) {
       show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
       exit;
  }
  $row1 = mysql_fetch_array($result);

  return $row1['email'];

}



function db_getPersonalName() {
  global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id, $user_id;

  if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
      show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
      exit;
  }

  $sql="select * from user where id='{$user_id}'";
  if ( !($result = mysql_db_query( $DB, $sql ) ) ) {
       show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
       exit;
  }
  $row = mysql_fetch_array($result);
  
  return $row['name'];

}

function db_getPersonalBasic() {
  global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id, $user_id;

  if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
      show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
      exit;
  }

  $sql="select * from user where id='{$user_id}'";
  if ( !($result = mysql_db_query( $DB, $sql ) ) ) {
       show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
       exit;
  }
  $row = mysql_fetch_array($result);
  return $row['email'];

}

function db_getCourseName($courseId = null, $forLog = 0) {
  global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id, $user_id, $course_year, $course_term;

  if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
      show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
      exit;
  }

  if(is_null($courseId)) {
    $sql="select * from course where a_id='{$course_id}'";
  }
  else {
    $sql="select * from course where a_id='{$courseId}'";
  }
  if ( !($result = mysql_db_query( $DB, $sql ) ) ) {
       show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
       exit;
  }
  $row = mysql_fetch_array($result);
/*
  if(is_null($courseId)) {
    $sql="select * from teach_course where course_id='{$course_id}'";
  }
  else {
    $sql="select * from teach_course where course_id='{$courseId}'";  
  }

  if ( !($result1 = mysql_db_query( $DB, $sql ) ) ) {
       show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
       exit;
  }
  $row1 = mysql_fetch_array($result1);
*/
  if($forLog == 0)
    return $course_year."0".$course_term.$row['name'];
  else
    return $row['name'];
}

function db_getCourseStuNum() {
 global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id, $user_idi, $course_year, $course_term;

  if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
      show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
      exit;
  }

  $sql="select * from take_course where course_id='{$course_id}' && credit=1 && year='{$course_year}' && term='{$course_term}'";
  if ( !($result1 = mysql_db_query( $DB, $sql ) ) ) {
       show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
       exit;
  }
  $row = mysql_num_rows($result1);
  return $row+6;
}

function db_getCourseStuEmail() {
 global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id, $user_id;

  $query = "SELECT B.email
      FROM `take_course` T,`user` B
      WHERE T.course_id = '{$course_id}' AND B.email IS NOT NULL
      AND T.student_id = B.a_id ";

  if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
      show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
      exit;
  }

  if ( !($result1 = mysql_db_query( $DB, $sql ) ) ) {
       show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
       exit;
  }
  $row = mysql_fetch_array($result1);
  return $row;


}

function db_setOn_line($pubRecordingId,$meetingDate,$meetingTitle, $pubUrl) {
 global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id, $user_id;
	
  if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
      show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
      exit;
  }

  $query = "insert into syn_on_line(`course_id`, `link` , `recording_id`) VALUES ($course_id, '$pubUrl', $pubRecordingId)";

  if ( !($result1 = mysql_db_query( $DB, $query ) ) ) {
       show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
       exit;
  }

  $pTime = date("Y-m-d H:i:s", strtotime('now'));
  $query = "INSERT INTO on_line ( a_id , date , subject , link ,  mtime )
       VALUES ( '','$meetingDate', '$meetingTitle', '$pubUrl', '$pTime'); ";

  if ( !($result1 = mysql_db_query( $DB.$course_id, $query ) ) ) {
       show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
       exit;
  }
  
}

function db_delOn_line($pubRecordingId,$pubUrl) {
 global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id, $user_id;

  if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
      show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
      exit;
  }

  $query = "select course_id from syn_on_line where recording_id=$pubRecordingId";

  if ( !($result1 = mysql_db_query( $DB, $query ) ) ) {
       show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
       exit;
  }

  $row = mysql_fetch_array($result1);

  $query = "delete from syn_on_line where recording_id=$pubRecordingId";

  if ( !($result1 = mysql_db_query( $DB, $query ) ) ) {
       show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
       exit;
  }
  

  $query = "delete from on_line where link='$pubUrl'";

  if ( !($result1 = mysql_db_query( $DB.$row['course_id'], $query ) ) ) {
       show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
       exit;
  }

}

        //寄公告的內容給所有學生
        function mailMeetingInfo_to_students ($jnjData,$dateString) {
                //modify by chiefboy1230, add $user_id to global variable
                //global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id, $a_id, $system, $version, $course_year, $course_term;
                global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id, $a_id, $user_id, $system, $version, $course_year, $course_term;

		        if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		     	      show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
			      exit;
			}
  
                        $Q2 = "select name from course where a_id='".$course_id."'";
                        $result2 = mysql_db_query( $DB, $Q2 );
                        $row2 = mysql_fetch_array( $result2 );

                        //取出開課老師信箱
                        $teacher_email = "";
                        //modify by chiefboy1230, fix 'mail post to all students' displaying error sender e-mail.
                        //$Q3 =  "select u.email from teach_course tc, user u where tc.course_id='".$course_id."' and tc.year='".$course_year."' and tc.term='".$course_term."' and tc.teacher_id=u.a_id";
                        $Q3 =  "select u.email from teach_course tc, user u where tc.course_id='".$course_id."' and tc.year='".$course_year."' and tc.term='".$course_term."' and u.id='".$user_id."'";
                        $result3 = mysql_db_query( $DB, $Q3 );
                        $row3 = mysql_fetch_array( $result3);
                        $teacher_email=$row3['email'];

                        //取出修課學生信箱
                        $Q3 = "select u.email from take_course tc, user u where tc.course_id='".$course_id."' and tc.year='".$course_year."' and tc.term='".$course_term."' and tc.student_id=u.a_id";
                        $result3 = mysql_db_query( $DB, $Q3 );

                        $email = array();
                        $index = 0;
                        $tmp_list = "";
                        while ($row3 = mysql_fetch_array($result3)) {
                                if ($row3['email'] != NULL) {
                                        $tmp_list = $tmp_list . $row3['email'] . ",";
                                }

                                if (strlen($tmp_list) >= 450) {
                                        $email[$index] = $tmp_list;
                                        $tmp_list = "";
                                        $index++;
                                }
                        }
                        $email[$index] = $tmp_list;
                        $index++;

                        $subject = $jnjData->ownerName . '老師的'.$row2['name'] .' 課程預約會議通知';

                        $minDuration = $jnjData->duration/60;
                        $tempagenda = $jnjData->agenda;

                        // [jfish] 信件內容可能也要改
                        $message = "同學您好,
{$jnjData->ownerName}老師在\"{$row2['name']}\"課程預約了會議。
標題：{$jnjData->meetingTitle}
開始時間：{$dateString}
時間長度：{$minDuration} 分鐘
議程/留言：
{$tempagenda}


請在會議開始時間，至ecourse的'討論區' --> '老師網路辦公室'
這是系統自動發出的信件，請勿回覆。";

                        
                        //學生人數過多的話，分多次寄信
                        $is_succeed = true;
                        for ($i=0;$i<count($email);$i++) {
                                $header = "From: $teacher_email" . "\n";
                                $header .= "Bcc: " . $email[$i] . "\n";
                                $config = "-fstudy@mail.elearning.ccu.edu.tw";

                                $r = mail("", $subject, $message, $header, $config);
                                if (!$r) {
                                        $is_succeed= false;
                                        break;
                                }
                        }

                        if ($is_succeed == true)
                                $error = "寄信完成<BR>";
                        else
                                $error = "寄信失敗，請檢查學生的e-mail（一位學生僅能輸入一組e-mail）<BR>";
        }


?>
