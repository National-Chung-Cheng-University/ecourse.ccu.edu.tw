<?PHP
require_once 'common.php';
require_once 'fadmin.php';
require_once 'my_rja_db_lib.php';

global $course_id;
 $Q1="select name from course where a_id = $course_id";
        $courseName = query_db_to_value($Q1);
        $courseName = urlencode($courseName);

    global $user_id;
        $Q2 = "select name from user where id = '$user_id' ";
        $teacherName = query_db_to_value($Q2);
        $teacherName = urlencode($teacherName);


$url = "http://mmc.elearning.ccu.edu.tw/my_get_mmc_info.php?teacherName=$teacherName&courseName=$courseName&action=videoListByCourseAndTeacherName";
//print $url;
$result =  file_get_contents($url);
print '<pre>';
//print $result;

$result = explode("\n", $result );
print_r( $result);
//array_pop($result) ;

$meetingList = array();
foreach($result as $key => $value){
        list (
                        $meetingList[$key]['teacherName'],
                        $meetingList[$key]['courseName'],
                        $meetingList[$key]['title'],
			$meetingList[$key]['startTime'],
             ) = split(" \| " , $value);
}
print '<pre>';
print_r($meetingList);


//next: ø���L�X meetingList, �A�� radio �ﶵ�i�H�ন�I�Wï




?>
