<?PHP
//this
//error_reporting(1);    
require_once 'common.php';
require_once 'fadmin.php';
#require_once 'passwd_encryption.php';
require_once 'my_rja_db_lib.php';

#error_reporting(256);

global $course_id;
global $user_id;
global $PHPSESSID;

$query_this_semeter = "SELECT * FROM this_semester";
$this_semeter = flatArray(query_db_to_array($query_this_semeter));
$this_year = $this_semeter[0];
$this_term = $this_semeter[1];

//$Q1 = "SELECT a_id FROM user where id = '$user_id'";
//$teacher_account = query_db_to_value($Q1);

/* 
�o�̻ݭn����Ѯv�P�ǥͪ����P
�Ѯv�O�ұª��ҵ{�C��
�ǥͬO�ҭת��ҵ{�C��
*/

if(isTeacher($user_id)){
//different with ecourse, cyberccu�����D������A���ά� year �� term 
	$Q1 = "select c.name AS courseName FROM course c, teach_course tc, user u where u.id = '$user_id' and tc.teacher_id = u.a_id and c.a_id = tc.course_id and tc.year = $this_year and tc.term = $this_term order by c.a_id ASC";
	//$Q1 = "select c.name AS courseName FROM course c, teach_course tc, user u where u.id = '$user_id' and tc.teacher_id = u.a_id and c.a_id = tc.course_id order by c.a_id ASC";
	//echo $Q1;
}else {
//different with ecourse, cyberccu�����D������A���ά� year �� term 

	//$Q1 = "SELECT c.name as courseName FROM user u, take_course t, course c where u.id= '{$user_id}'and  t.student_id = u.a_id and c.a_id = t.course_id and (( t.credit = '0' and c.validated%2 != '1') or t.credit = '1') order by  c.group_id ,c.a_id";
	$Q1 = "select c.name AS courseName FROM course c, take_course tc, user u where u.id = '$user_id' and tc.student_id = u.a_id and c.a_id = tc.course_id and tc.year = $this_year and tc.term = $this_term order by c.a_id ASC";
	//echo $Q1;
}


//$query_course_name_sql = "SELECT c.name as course_name FROM teach_course as tc, course as c WHERE tc.course_id = c.a_id and tc.teacher_id = {$teacher_account}";

#print $query_course_name_sql;
// $course_name_list  ���ӭn�q ecdemo ����ǨӡA�o��{�� "my_reservation_list.php" ���ӭn�Q ecdemo ���{�� require 

$course_name_list = flatArray(query_db_to_array($Q1));
#$course_name_list[]='�~�~a';
//var_dump($course_name_list);

if(empty($course_name_list))return;
$format_course_name = formatCourseName($course_name_list);
$format_course_name = urlencode($format_course_name);



$start_time = date("Ymd");
//�b mmc �W�A30�������@�ɬq�A�@�Ѧ� 48 �Ӯɬq
//�o�̥��d�Ӥ��Ѷ}�l�@�~��
$end_interval = 48*356;
$action = 'reservationLookupByCourseNameTime';

$reservation_url = "http://mmc.elearning.ccu.edu.tw/my_get_mmc_info.php?action=$action&courseNameList=$format_course_name&t=$start_time&s=0&i=$end_interval";


//this
$reservation_list = file_get_contents($reservation_url);
$reservation_list = explode("\n", $reservation_list );
array_pop($reservation_list) ;

$reservation_meeting = array();
//next: �� foreach �C�@�� �A�Τ@�Ӱ}�C����Ǧ^�Ӫ��ܼ� (�w�� | �j�})�A�w���O�H require �o��{���A�N�|����o�Ӱ}�C
foreach($reservation_list as $key => $value){
	list (
			$reservation_meeting[$key]['meetingId'],
			$reservation_meeting[$key]['teacherName'],
			$reservation_meeting[$key]['teacherIdNum'],
			$reservation_meeting[$key]['courseName'],
			$reservation_meeting[$key]['title'],
			$reservation_meeting[$key]['startTime'],
			$reservation_meeting[$key]['endTime'],
			$reservation_meeting[$key]['isOnline'],
			$reservation_meeting[$key]['maxNumAttendee'],
			$reservation_meeting[$key]['recording'],
			$reservation_meeting[$key]['finished'],
	     ) = split("\|@" , $value);

#sample
	//echo "{$meeting_value->meetingId}|{$teacher_name}|{$course_name}|{$meeting_value->title}|{$meeting_value->startTime}|{$isOnline}|{$meeting_value->maxNumAttendee}|{$meeting_value->recording}\n";
}

//var_dump( $reservation_meeting);

?>
<?PHP
function isTeacher($user_id){
	/*
	   authorization char(1) �ϥΪ��v�� 0:�޲z�� 1:���v 2:�U�� 3:�ǥ� 4:�t�ҧU�� 9:guest
	 */

	$Q1="select authorization from user where id = '$user_id' ";


	$auth = query_db_to_value($Q1);

	if (empty($auth)){
		print "my_reservation_list_proc.php says: no such user";
		return 0;
	}
	if (($auth == '1')){
		return true;
	}
	else return false;

}

function formatCourseName($course_name_list){
	$return_string = '';
	if(empty($course_name_list))
		print "my_reservation_list_proc.php say: empty array.";

	foreach ($course_name_list as $value){
		$return_string .= ($value . '|'); 

	}
	return rtrim($return_string , '|');
}

/*
   function reservation(){
   global $course_id;
   global $user_id;
   global $action;
   $Q1 = "select email from user where id= '$user_id' ";
   $teacher_email=query_db_to_value($Q1);
   $Q2 = "select pass from user where id= '$user_id' ";
   $encrypted_passwd=query_db_to_value($Q2);
   $teacher_pass=passwd_decrypt($encrypted_passwd);

   $find_course_name_sql = "select name from course where a_id = $course_id; ";
   $course_name = query_db_to_value($find_course_name_sql);
   $course_name = urlencode($course_name);

   header("Location: http://mmc.elearning.ccu.edu.tw/t_get_joinnet.php?action=$action&email=$teacher_email&password=$teacher_pass&course_name=$course_name");

   }
 */



?>
