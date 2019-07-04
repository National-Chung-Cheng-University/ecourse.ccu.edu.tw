<?php
  //刪除錄影檔

  include_once("mmc_config.php");
  include_once("db_meeting.php");
  require_once("jnjData.php");
  require_once("hit_encryption.php");

  require_once("platform_config.php");
  $RELEATED_PATH = "../";
  require_once($RELEATED_PATH . "fadmin.php");
  /*
  require_once($RELEATED_PATH . "config.php");
  require_once($RELEATED_PATH . "session.php");
  */

if(isset($PHPSESSID) && session_check_teach($PHPSESSID) == 2) {
  $personal_id = db_getAid();            //取得個人編號
  $begin_course_cd = $course_id;    //取得課程代碼

  //$encryptor = new EncryptionTool();
  $jnjData = new jnjData();

  $mmc_path_config = new MMC_Path_Config();

  //$jnjData->courseId = $begin_course_cd;

  $jnjData->meetingId = $_REQUEST['meetingId'];
 
  $jnjData->courseId =  GetCourseIdByMeetingId($jnjData->meetingId); // [jfish] 不知道還要不要，因為刪除好像沒差，看起來是之前用別之改沒清乾淨
  // 藉由課程id找出老師的名字與email
  //
  /*
  $query = "select *  from begin_course where begin_course_cd = '{$jnjData->courseId}'"; // 只會有一筆，所以之後的資料都讀 index(0)即可

  $query_Result = db_getAll($query);

  $tempCourse_cd = $query_Result[0]["course_cd"]; //因為只有一筆所以只讀0

  $query = "select *  from course_basic where course_cd = '{$tempCourse_cd}'"; // 只會有一筆，所以之後的資料都讀 index(0)即可

  $query_Result = db_getAll($query);

  $tempTeacher_cd = $query_Result[0]["teacher_cd"]; //因為只有一筆所以只讀0

  $query = "select *  from personal_basic where  personal_id  = '{$tempTeacher_cd}'"; // 只會有一筆，所以之後的資料都讀 index(0)即可

  $query_Result = db_getAll($query);
  */
  /*
  $query = "select teacher_cd from teach_begin_course where begin_course_cd={$begin_cd} and course_master=1";

  //$query = "select *  from begin_course where begin_course_cd = '{$begin_course_cd}'"; // 只會有一筆，所以之後的資料都讀 index(0)即可

  $tempTeacher_cd = db_getOne($query);

  // 老師資訊
  $jnjData->ownerName = db_getPersonalName($tempTeacher_cd);
  $jnjData->ownerEmail = db_getPersonalBasic($tempTeacher_cd,'email');

  $query = "select *  from personal_basic where  personal_id  = '{$personal_id}'"; // 只會有一筆，所以之後的資料都讀 index(0)即可

  $query_Result = db_getAll($query);

  $personName = $query_Result[0]["personal_name"];
  */
  GetPlaymodeInfoByMeetingId($jnjData);
  $fileName=SearchingRecordingFileName($jnjData->meetingId);
  // ps為驗證string當到mcu那台機器要用這個驗證"eow3HXfg7x"
  // [jfish] 這邊也想改用javascript，不然一點用都沒有
  echo "<meta http-equiv='refresh' content='0;url=link_MMC.php?id={$jnjData->ownerId}&mid={$jnjData->meetingId}&fn={$fileName}&op=1'>";
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

