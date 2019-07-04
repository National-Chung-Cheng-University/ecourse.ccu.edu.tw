<?php
/*
	author: rja
	�o��{���O���� access �@��  mmc �� service  �Ϊ�
*/
require_once 'common.php';
require_once 'fadmin.php';
//�� cyber ���P�Acih �ݭn�U���o���� 
require_once 'passwd_encryption.php';
require_once 'my_rja_db_lib.php';


//ini_set('display_errors', 'on'); error_reporting(E_ALL);          

global $course_id;
global $user_id;
#global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;

$action = $_GET['action'];

$platform = 'cih' ; 

if ($action =='stuCheckMeeting'){
	stuCheckMeeting();
}else if ($action =='meeting'){
	meeting();
}else if ($action =='reservation'){
	reservation();
}else if ($action =='recordingManagement'){
	recordingManagement();
}else if ($action =='gotoPrepareModeMeeting'){
	$meetingId = $_REQUEST['meetingId'];
	$courseName = urlencode($_REQUEST['courseName']);
	gotoPrepareModeMeeting($meetingId, $courseName);
}else if ($action =='gotoInstantMeeting'){
	$courseName = urlencode($_REQUEST['courseName']);
	gotoInstantMeeting( $courseName);
}else if ($action =='delReservation'){
	$meetingId = urlencode($_REQUEST['meetingId']);
	delReservation( $courseName);
}

?>

<?PHP

function recordingManagement(){
	global $course_id;
	global $user_id;

	$Q1 = "select email from user where id= '$user_id' ";
	$teacher_email=query_db_to_value($Q1);
	$Q2 = "select pass from user where id= '$user_id' ";
	$encrypted_passwd=query_db_to_value($Q2);
	//cyber�O���X�A���� decrypt�Acih�h�ݭn
	$teacher_pass=passwd_decrypt($encrypted_passwd);
	//$teacher_pass=($encrypted_passwd);

	$find_course_name_sql = "select name from course where a_id = $course_id; ";
	$course_name = query_db_to_value($find_course_name_sql);
	$course_name = urlencode($course_name);
	$teacher_pass = urlencode($teacher_pass);
	$reservationUrl= "Location: http://mmc.elearning.ccu.edu.tw/t_get_joinnet.php?platform=$platform&action=recordingManagement&email=$teacher_email&password=$teacher_pass&course_name=$course_name";

	header($reservationUrl);

}
function reservation(){
	global $course_id;
	global $user_id;

	$Q1 = "select email from user where id= '$user_id' ";
	$teacher_email=query_db_to_value($Q1);
	$Q2 = "select pass from user where id= '$user_id' ";
	$encrypted_passwd=query_db_to_value($Q2);
	//cyber�O���X�A���� decrypt�Acih�h�ݭn
	$teacher_pass=passwd_decrypt($encrypted_passwd);
	//$teacher_pass=($encrypted_passwd);

	$find_course_name_sql = "select name from course where a_id = $course_id; ";
	$course_name = query_db_to_value($find_course_name_sql);
	$course_name = urlencode($course_name);
	$teacher_pass = urlencode($teacher_pass);
	$reservationUrl= "Location: http://mmc.elearning.ccu.edu.tw/t_get_joinnet.php?platform=$platform&action=reservation&email=$teacher_email&password=$teacher_pass&course_name=$course_name";

	header($reservationUrl);

}

function meeting(){
	/*
	   ���ݤ@�U�Ѯv�o���Ҧ��S���w���|ĳ

	   if( !���Ѧ��|ĳ)
		   �}�Y�ɷ|ĳ
	   else if �ɶ��٨S��}�l�ɶ���������
		   print page : ��ܭn�i�ǳƼҦ��A�٦��ѥ[�|ĳ�A�æL�X�����T���A�ǳƼҦ��n���}
	   else �����i�J�o���w�����|ĳ

	 */
global $course_id;
global $user_id;
global $action;


/* 
	include ���o�� my_reservation_list_proc.php �A���ӴN�|����@�� $reservation_meeting �ܼ� 
	$reservation_meeting  �o���ܼưO�ۤ@�� mmc �W���|ĳ��T
	(�w�]�O�q���Ѷ}�l��b�~�����|ĳ�A�P�Юv�Ҷ}���ҦW�٬ۦP���w���|ĳ)
*/
include("./my_reservation_list_proc.php");
//var_dump( $reservation_meeting);

$find_course_name_sql = "select name from course where a_id = $course_id; ";
$course_name = query_db_to_value($find_course_name_sql);
$nextMeetingOfToday = getNextMeetingOfToday($reservation_meeting, $course_name);

if(empty($nextMeetingOfToday)){ 
	//���ѨS�|ĳ�A�ҥH�}�Y�ɷ|ĳ
	gotoInstantMeeting($course_name);
} else if( ( $nextMeetingOfToday['startTime'] - time() ) > 900 ){
	//�ɶ��٨S��}�l�ɶ� 15 ������
	$meetingOfToday = getAllMeetingOfToday($reservation_meeting, $course_name);
	printPreparePage($meetingOfToday);
	exit;
} else {
	//���Ѧ��|ĳ�A�ӥB�ɶ��w�g��F�A���}�l�ɶ����������]��ɶ��� ( �ɶ��W�L�T�p�ɤ��] ok )
	$meetingId = $nextMeetingOfToday['meetingId'];
	gotoReservedMeeting($meetingId, $course_name);

}



}

function printPreparePage($meetingOfToday){
	/*
	   �w���|ĳ: �Y���Ѧ��w���|ĳ�A���Ѯv�I�U"�i�J�����줽��"�ɡA�ɶ��b�e15�����H�e�A�h�X�{�G�ӿﶵ:
	   (���U�ӳo�O�o�� function �n������)
	   1. �i�J�ǳƼҦ�
	   (�L�X����Ҧ����|ĳ�O����ɭԡA�٦��|ĳ��T �A�i��|�i�t�@�ӷ|ĳ���ǳƼҦ�)
	   (���r�L�X������)

	   2. �}�Y�ɷ|ĳ
	 */

	include_once("./my_reservation_list_print_table_lib.php");

	//var_dump( $reservation_meeting);

	echo "�����ҵ{����w���|ĳ�M��G";
	$listTable = editTableContent($meetingOfToday);
	printTable($listTable);

	echo '���骺�|ĳ�w���ɶ��٨S����A�z�]�i�H��ܥ��i�J"�ǳƽҵ{�Ҧ�"�A';
	echo '�b"�ǳƽҵ{�Ҧ�"���A�z�i�H���W�Ǥ@�ǱЧ����|ĳ�e���ǳơA�䥦�H���|�i�ӥ��Z�z�C';
	echo '���|ĳ�ɶ��@��A����"�ǳƽҵ{�Ҧ�"�A���s�i�J�����줽�ǡA�|ĳ���䥦�ѻP�̴N�i�H�i�ӤF�C';
	echo '<p><font color="red">�ݭn�`�N���O�A�b"�ǳƽҵ{�Ҧ�"���A�Y��F�|ĳ�w���ɶ��A�@�w�n�O�o���s�i�J�����줽��';

	echo '�A�_�h��L�|ĳ�ѻP�̵L�k�i�J�A���w���|ĳ�C</font>';

	echo '<p>�Y�z�u�O�Q�}�@�ӧY�ɷ|ĳ�A�i�H';
	global $course_id;
	$Q1="select name from course where a_id = $course_id";
	$courseName = query_db_to_value($Q1);
	$courseName = urlencode($courseName);

	$instantMeetingUrl="<a href='./my_gotojoinnet.php?platform=$platform&action=gotoInstantMeeting&courseName=$courseName'>
	���o�̴N���W�}�@�ӧY�ɷ|ĳ</a></p>";
	echo  $instantMeetingUrl;


	}
function delReservation($course_name){
	global $course_id;
	global $user_id;
	global $action;
	global $meetingId;

	list($teacher_email, $teacher_pass)=getMailAndPasswd($user_id);

	//$find_course_name_sql = "select name from course where a_id = $course_id; ";
	//$course_name = query_db_to_value($find_course_name_sql);
	//$course_name = urlencode($course_name);
	
	header("Location: http://mmc.elearning.ccu.edu.tw/t_get_joinnet.php?platform=$platform&action=delReservation&email=$teacher_email&password=$teacher_pass&myMeetingId=$meetingId");

}
function gotoInstantMeeting($course_name){
//�i�J�Y�ɷ|ĳ��
	global $course_id;
	global $user_id;
	global $action;

	list($teacher_email, $teacher_pass)=getMailAndPasswd($user_id);

	$find_course_name_sql = "select name from course where a_id = $course_id; ";
	$course_name = query_db_to_value($find_course_name_sql);
	$course_name = urlencode($course_name);
	$teacher_pass = urlencode($teacher_pass);
//call �U���o��{���A�N�|��ϥΪ̾ɹL�h mmc �������A�æ۰ʵn�J mmc �A�H�ζi��Y�ɷ|ĳ
	$nextUrl = "Location: http://mmc.elearning.ccu.edu.tw/t_get_joinnet.php?platform=$platform&action=instantMeeting&email=$teacher_email&password=$teacher_pass&course_name=$course_name";
	header($nextUrl);

}
function gotoReservedMeeting($meetingId, $course_name){
	global $course_id;
	global $user_id;

	list($teacher_email, $teacher_pass)=getMailAndPasswd($user_id);

	$find_course_name_sql = "select name from course where a_id = $course_id; ";
	$course_name = query_db_to_value($find_course_name_sql);
	$course_name = urlencode($course_name);
	$teacher_pass = urlencode($teacher_pass);

	header("Location: http://mmc.elearning.ccu.edu.tw/t_get_joinnet.php?platform=$platform&action=gotoReservedMeeting&email=$teacher_email&password=$teacher_pass&course_name=$course_name&myMeetingId=$meetingId");

}
function gotoPrepareModeMeeting($meetingId, $course_name){
	//$course_name �i��S�Ψ�
	global $course_id;
	global $user_id;

	list($teacher_email, $teacher_pass)=getMailAndPasswd($user_id);

	$find_course_name_sql = "select name from course where a_id = $course_id; ";
	$course_name = query_db_to_value($find_course_name_sql);
	$course_name = urlencode($course_name);
	$teacher_pass = urlencode($teacher_pass);

	header("Location: http://mmc.elearning.ccu.edu.tw/t_get_joinnet.php?platform=$platform&action=gotoPrepareModeMeeting&email=$teacher_email&password=$teacher_pass&course_name=$course_name&myMeetingId=$meetingId");

}
function getMailAndPasswd($user_id){
	$Q1 = "select email from user where id= '$user_id' ";
	$teacher_email=query_db_to_value($Q1);
	$Q2 = "select pass from user where id= '$user_id' ";
	$encrypted_passwd=query_db_to_value($Q2);
	//cyber�O���X�A���� decrypt, cih �h�ݭn

	$teacher_pass=passwd_decrypt($encrypted_passwd);
	//$teacher_pass=($encrypted_passwd);

	return array($teacher_email, $teacher_pass);
}

// get next one meeting of today  in this courseName
function getNextMeetingOfToday($reservation_meeting, $courseName){
	//var_dump($reservation_meeting);


	foreach($reservation_meeting as $value){
		if( $value['courseName'] == $courseName)   {
			//startTime ���T�p�ɤ����|ĳ�N�O�U�@�ӡA�p�G�w�g�L�T�p�ɪ��|ĳ�N���n�F
			if (( date('Ymd',$value['startTime']) == date('Ymd'))&&(($value['startTime']) > (time()-10800))  ){
				//�w�g�������|ĳ�]���n
				if( ! $value['finished'] and  ($value['endTime'] > time())  ){
					return $value;
				}
			}
		}

	}
}

// get all meeting of today  in this courseName
function getAllMeetingOfToday($reservation_meeting, $courseName){
	//var_dump($reservation_meeting);
	$allTodayMeeting = Array();


	foreach($reservation_meeting as $value){
		if( $value['courseName'] == $courseName)   {
			//startTime ���T�p�ɤ����|ĳ�N�O�U�@�ӡA�p�G�w�g�L�T�p�ɪ��|ĳ�N���n�F
			if ( date('Ymd',$value['startTime']) == date('Ymd') ){
				//�w�g�������|ĳ�]���n
				if( ! $value['finished'] and  ($value['endTime'] > time())  ){
					$allTodayMeeting[]=$value;
				}
			}
		}

	}
	return $allTodayMeeting;
}

/*
   �ǥͧP�_�o���Ҫ��Ѯv�ثe���|ĳ���p
	�p�G�b�u�W�N�i�J�A�p�G���b�u�W�Nshow�d������
	�ݭn�`�N���O�A���i�J�Ѯv�����줽�ǡA�O�i�J�@��"�Ѯv"���줽�ǡA�Ӥ��O�@���ҵ{���줽��
*/

function stuCheckMeeting(){
	global $course_id;
	$find_course_name_sql = "select name from course where a_id = $course_id; ";
	$course_name = query_db_to_value($find_course_name_sql);


	$query_teacherid_from_coursename = "http://mmc.elearning.ccu.edu.tw/my_get_mmc_info.php?platform=$platfrom&action=getOnlineTeacherByCourseName&course_name=$course_name";
	//print $query_teacherid_from_coursename;
	$onlineTeacherId = file_get_contents($query_teacherid_from_coursename);
	$onlineTeacherId = explode(',',$onlineTeacherId);
	$onlineTeacherId = (int)$onlineTeacherId[0];
#var_dump($onlineTeacherId);

	//find user name from user id
	//���i�J�|ĳ�ɡA�۰ʿ�J�W��
	global $user_id;
	$my_query_user_name = "select name from user where id = '{$user_id}' ";
	$my_user_name = query_db_to_value($my_query_user_name);

        if(empty($my_user_name)){
                $my_user_name = $user_id;
        }


	if (!is_numeric($onlineTeacherId) ) {
		echo("some thing wrong: return value is not numeric. <b>Debug code: $teacher_id, $my_teacher_name</b>");
	}                                             

	//error check
	if(is_numeric($onlineTeacherId) && $onlineTeacherId!=0){
		//�Ѯv�b�u�W

		//update: �U���o��A���γo�Ӥ覡�O�F
		//���b table log ���O�U�o������ѰO��
		//addLogChat();

		$a_id = getAIDFromUserID($user_id);
		$courseName =getCourseNameFromCourserID($course_id);

//var_dump($_SESSION);

		$courseNameEncode = urlencode($courseName);
		//��ϥΪ̾ɦV mmc 
		$my_joinmeeting_url = "http://mmc.elearning.ccu.edu.tw/gotomeeting.php?u=$onlineTeacherId&c=visit&name=$my_user_name&userId=$a_id&courseName=$courseNameEncode&courseId=$course_id";
//print $my_joinmeeting_url; die;
		header("Location: $my_joinmeeting_url");

	}else{


		//$my_query_teacher_name = "select  name from teach_course,user where teacher_id = user.a_id and  course_id = '{$course_id}' ";
		//$my_teacher_name = query_db_to_value($my_query_teacher_name);
		//var_dump($my_teacher_name);

		//�ثe���Ҽ{�@�ӽҵ{���G�ӥH�W���Ѯv�����p�A�p�G���n�Ҽ{�o�ر��p�A�N�O�⤣�P�Ѯv���m�W�C�X�ӡA�ǵ� mmc�Ammc �W�t�@��{���A �P�_���ӦѮv���}�Y�ɷ|ĳ��
		//note: order by name desc �O�@�ӼȥΪ� trick, cyber�W��java5 ���ܦh�Ѯv�A�ڥu�Q�n����D�^��m�W���A�ȮɥΤ@�U
		$Q1 = "select name from teach_course as t1, user as t2 where course_id= '$course_id' and  t1.teacher_id = t2.a_id and authorization = 1 and t2.name !='name'  order by name desc ;  ";
		$teacher_name=query_db_to_value($Q1);



		$teacher_name_encode = urlencode($teacher_name);
		//find meetingId from remote mmc
		$remoteUrl = "http://mmc.elearning.ccu.edu.tw/get_joinnet.php?teacher_name=$teacher_name_encode";
		$teacher_id=file_get_contents($remoteUrl);
		//�Ѯv���b�u�W
		$my_joinmeeting_url = "http://mmc.elearning.ccu.edu.tw/gotomeeting.php?u=$teacher_id&c=visit&name=$my_user_name";
		print "�Ѯv�ثe���b�u�W�A�]�i�H�Q�� joinnet <a href=\"{$my_joinmeeting_url}\">�d�����Ѯv</a>�C";
		printHelpInfo();
	}
}
// �쥻���ӬO�ΨӼW�[��Ѧ��ƥΪ��A�{�b���Q�γo�Ӥ�k�F
function addLogChat(){
	global $course_id;
	global $user_id;
	$event = 4;
	$a_id = getAIDFromUserID($user_id);
#�Q�� cih �e�H�� library function�A�ڤ]�����D������n�γo��·Ъ���k
	$now_time = get_now_time_str();
	if (! checkLogTimeValidation($a_id, $now_time))
		return ; 

	$sql = "INSERT INTO log (a_id, user_id, event_id,  tag2, tag3, mtime ) values ('', $a_id, $event, '$course_id' , '1' , '$now_time')";
#var_dump(($sql));
	$thisDB = 'study' . $course_id;
	query_db($sql, $thisDB);


}
function checkLogTimeValidation($a_id, $now_time){
	global $course_id;
	$sql = "select mtime from log where user_id = '$a_id' and ( NOW() - mtime  < 10800)";
	//print $sql;
	//die;
	$result = (query_db_to_value($sql));
	if (!empty($result) )
		return false;
	else
		return true;

}



function printHelpInfo(){

	?>
		<p>�ϥλ����G�ڭ̪������|ĳ�ݭn�w�� JoinNet �n��ӨϥΡAJoinNet ���w�˩�ϥΪ̺ݤ��K�O�n��C
		�b�z�w�� JoinNet �n�餧��A�z�]�i�H�Q�ΰ�����պ��F�ӽT�w�z���q���O�_�ŦX�t�λݨD�C</p>
		<table border="0" cellpadding="0" cellspacing="0">
		<tbody><tr>
		<td nowrap="true">
		<p>
		<a href="http://www.webmeeting.com.tw/download_joinnet.php" target="_blank"><img src="http://mmc.elearning.ccu.edu.tw/images/icon_download.gif" align="absmiddle" border="0" vspace="1" hspace="5">�U�� JoinNet</a>

		</p>
		</td>
		</tr>
		
		<tr>
                <td nowrap="true">
                <p>
                <a href="http://mmc.elearning.ccu.edu.tw/download/standalone_setup_joinnet_5.1.0.0629_hmtg_multilang.rar"><img src="http://mmc.elearning.ccu.edu.tw/images/icon_download.gif" align="absmiddle" border="0" vspace="1" hspace="5">�U�� JoinNet (�ƥ����I)</a>
                </p>
                </td>
                </tr>

		<tr>
		<td nowrap="true">
		<p>
		<a href="http://mmc.elearning.ccu.edu.tw/joinnet_wizard.php"><img src="http://mmc.elearning.ccu.edu.tw/images/icon_test_wizard.gif" align="absmiddle" border="0" vspace="1" hspace="5">������պ��F						</a>
		</p>
		</td>
		</tr>
		
		</tbody></table>

		<?php

}


?>
