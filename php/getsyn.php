<?php
require_once 'common.php';
require_once 'fadmin.php';


//global $course_id;
global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;

$pubid=$_GET['pubid'];
$subject=$_GET['subject'];
$date=$_GET['date'];
$course_id=$_GET['course_id'];


echo"i am server4:getsyn.php:";
print_r($_GET);


//若為刪除錄影檔之同步，則到資料庫中刪除對應連結
if (isset($_GET['del']) ){
$link="http://mmc.elearning.ccu.edu.tw/pub_recording_view.php?id=$pubid$&c=playback";

$Q2 = "DELETE FROM on_line WHERE link = '$link'";
if ( !($sqllink = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD))) 
	echo "connect db error.";
if ( !($result = mysql_db_query( $DB.$course_id, $Q2 ))) 
	echo "db write error.";
return;
}

//todo 自動找 course_id from mmc
//$course_id=676;
$link="http://mmc.elearning.ccu.edu.tw/pub_recording_view.php?id=$pubid$&c=playback";
echo "<br />\n";
//print_r($GLOBALS);
//$subject= iconv( "utf-8", "big5",$subject);

$Q1 = "INSERT INTO on_line ( date, subject, link ) values ( '$date', '$subject', '$link' )";
print_r($Q1);

if ( !($sqllink = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD))) 
	echo "connect db error.";
if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ))) 
	echo "db write error.";

exit;


//query teacher id 

if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
	echo( "資料庫連結錯誤!!" );
	exit;
}

$Q1 = "select * from teach_course where course_id= '$course_id' ";
$a_id=query_db($Q1, 'teacher_id');


//find email
$Q2 = "select * from user where a_id= '$a_id' ";
$teacher_email=query_db($Q2, 'email');


//find pass
$Q2 = "select * from user where a_id= '$a_id' ";
$teacher_pass=query_db($Q2, 'pass');


/*
//find meetingId from remote mmc
list($teacherid,$meetingid)=file_get_contents("http://mmc.elearning.ccu.edu.tw/get_joinnet.php?teacher_name=$teacher_name_encode");


//var_dump($meetingid);
//print_r($meetingid);
if (!is_numeric($teacherid) ) 
{
	echo("some thing wrong: return value is not numeric. <b>Debug code: $teacherid, $meetingid, $teacher_name</b>");
}


//$this_user_name = iconv('big5', 'utf-8', $this_user_name);
//$this_user_name = urlencode($this_user_name);
echo "http://mmc.elearning.ccu.edu.tw/meeting_view.php?id=$meetingid";
exit;
*/
echo "end of server4:syn.php.";

header("Location: http://mmc.elearning.ccu.edu.tw/t_get_joinnet.php?email=$teacher_email&password=$teacher_pass");

function query_db($query, $column) {

	global $DB;
	if ( $result1 = mysql_db_query( $DB, $query) ) {

		if ( mysql_num_rows( $result1 ) != 0 ) {
			$row_result= mysql_fetch_array( $result1 );
			if ( is_null($row_result["$column"]))echo "server4: some thing wrong. Query result is null?!";

			return  $row_result["$column"];
		}
		else echo "some thing wrong. Query result is null?";
	}
	else echo "some thing wrong. Can't Query?";
}



?>
