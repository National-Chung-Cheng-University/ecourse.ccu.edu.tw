
<?php
require_once 'common.php';
require_once 'fadmin.php';
#require_once 'passwd_encryption.php';
require_once 'my_rja_db_lib.php';

function getStuJoinMeetingList($course_id, $stuIdList){

	if (is_array($stuIdList )){
		$stuId = array_shift($stuIdList);
		foreach($stuIdList as $value){
			if(empty($value)) break;
			$stuId = $stuId . ',' . $value;
		}
	}else{
		$stuId = $stuIdList;
	}
	$courseName = getCourseNameFromCourserID($course_id );
	$courseName = urlencode($courseName);

	//print '<pre>';
	//print_r($GLOBALS);
	$action = 'joinChatListByCourseNameAndCourseIdAndStudentId';
	$this_url="http://mmc.elearning.ccu.edu.tw/my_get_mmc_info.php?action=$action&courseName=$courseName&courseId=$course_id&stuId=$stuId";
//print $this_url;

	$stuJoinMeetingList = Array();

	$contents=  file_get_contents($this_url);
	// �� foreach �C�@�� �A�Τ@�Ӱ}�C����Ǧ^�Ӫ��ܼ� (�w�� |@a �j�})�A�w���O�H require �o��{���A�N�|����o�Ӱ}�C
	$get_result = explode("\n", $contents );

	//print '<pre>';
	//print_r($get_result);
	foreach($get_result as $key => $value){
		if (empty($value)) break;

		list (
				$stuJoinMeetingList[$key]['stuId'],
				$stuJoinMeetingList[$key]['count']
		     ) = split("\|@a" , $value);
	}
	//print_r($stuJoinMeetingList);

	return $stuJoinMeetingList;


}


?>
