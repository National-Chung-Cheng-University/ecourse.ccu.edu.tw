<?php
    // 發佈meeting

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

    $personal_id = db_getAid();            //取得個人編號
    $role_cd = $_SESSION['role_cd'];                    //取得角色
    $begin_course_cd = $courseId;    //取得課程代碼

    $recordingId = $_GET['rid'];
    $folderId = $_GET['cfid'];
    $seq = $_GET['cseq'];
    /*
    $query = "select *  from begin_course where begin_course_cd = '{$begin_course_cd}'"; // 只會有一筆，所以之後的資料都讀 index(0)即可

    $query_Result = db_getAll($query);

    $tempCourse_cd = $query_Result[0]["course_cd"]; //因為只有一筆所以只讀0
    $courseName = $query_Result[0]["begin_course_name"];
    */
    $courseName = db_getCourseName();
    /*
    $query = "select *  from  personal_basic  where personal_id = '{$personal_id}'"; // 只會有一筆

    $query_Result = db_getAll($query);
    */

    // 老師名字與email
    /*
    $ownerName = $query_Result[0]["personal_name"]; //因為只有一筆所以只讀0
    $ownerEmail = $query_Result[0]["email"]; //因為只有一筆所以只讀0
    */

    $ownerName = db_getPersonalName(); //會從session讀personal_id，而得到personal Name
    
    $ownerEmail = db_getPersonalBasic(); //從session讀personal_id，而得到email    

    // 更新recording的資料，接著加一筆資料到pub 跟myOnline mapping
    PublishMeetingInDB($recordingId,$courseName,$begin_course_cd,$ownerName,$ownerEmail);

    echo "<meta http-equiv='refresh' content='0;url=recordingManagement_list.php?rid=".$folderId."&seq=".$seq."'>";

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

