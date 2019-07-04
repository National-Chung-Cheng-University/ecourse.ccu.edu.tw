<?php

require_once 'db_meeting.php';
require_once '../common.php';
require_once '../fadmin.php';

$sql = "select * from user where authorization='1'";
die;
  if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
      show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
      exit;
  }
  if ( !($result = mysql_db_query( $DB, $sql ) ) ) {
       show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
       exit;
  }
  while ($row = mysql_fetch_assoc($result) ) {
     if(is_null($_GET['id']) || $_GET['id']<$row['a_id']) {
        $ownerId = $row['a_id'];
        while (strlen($ownerId) < 4) {
                $ownerId = '0'.$ownerId;
        }
        $ownerId = '2'.$ownerId;
	if (IsMember($row['email']) && !is_null($row['email'])) {
        	CreateMemberInDB($ownerId,$row['email'],$row['name']);

        	$reservationUrl= "http://mmc.elearning.ccu.edu.tw/localFile/moveRecordingToNew.php?email={$row['email']}&newId={$ownerId}&id={$row['a_id']}";
        	echo "<meta http-equiv='refresh' content='0;url={$reservationUrl}'>";
	}
	else
		;
  if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
      show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
      exit;
  }

     }
     else
	;

  }


//$teacher_pass=passwd_decrypt($encrypted_passwd);

?>

