<?php
    // �o�Gmeeting

    include_once("db_meeting.php");
    require_once("platform_config.php");
    $RELEATED_PATH = "../";
    require_once($RELEATED_PATH . "fadmin.php");
    /*
    $RELEATED_PATH = "../";
    require_once($RELEATED_PATH . "config.php");
    require_once($RELEATED_PATH . "session.php");
    */
    $IMAGE_PATH = $IMAGE_PATH;
    $CSS_PATH = $RELEATED_PATH . $CSS_PATH;
    $absoluteURL = $HOMEURL . "Mmc/";

if(isset($PHPSESSID) && session_check_teach($PHPSESSID) == 2) {

    $personal_id = db_getAid();            //���o�ӤH�s��
    $role_cd = $_SESSION['role_cd'];                    //���o����
    $begin_course_cd = $courseId;    //���o�ҵ{�N�X

    $recordingId = $_GET['rid'];
    $folderId = $_GET['cfid'];
    $seq = $_GET['cseq'];
    /*
    $query = "select *  from begin_course where begin_course_cd = '{$begin_course_cd}'"; // �u�|���@���A�ҥH���᪺��Ƴ�Ū index(0)�Y�i

    $query_Result = db_getAll($query);

    $tempCourse_cd = $query_Result[0]["course_cd"]; //�]���u���@���ҥH�uŪ0
    $courseName = $query_Result[0]["begin_course_name"];
    */
    $courseName = db_getCourseName();
    /*
    $query = "select *  from  personal_basic  where personal_id = '{$personal_id}'"; // �u�|���@��

    $query_Result = db_getAll($query);
    */

    // �Ѯv�W�r�Pemail
    /*
    $ownerName = $query_Result[0]["personal_name"]; //�]���u���@���ҥH�uŪ0
    $ownerEmail = $query_Result[0]["email"]; //�]���u���@���ҥH�uŪ0
    */

    $ownerName = db_getPersonalName(); //�|�qsessionŪpersonal_id�A�ӱo��personal Name
    
    $ownerEmail = db_getPersonalBasic(); //�qsessionŪpersonal_id�A�ӱo��email    

    // ��srecording����ơA���ۥ[�@����ƨ�pub ��myOnline mapping
    PublishMeetingInDB($recordingId,$courseName,$begin_course_cd,$ownerName,$ownerEmail);

    echo "<meta http-equiv='refresh' content='0;url=recordingManagement_list.php?rid=".$folderId."&seq=".$seq."'>";

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

