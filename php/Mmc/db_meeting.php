<?php

  /*
  $RELEATED_PATH = "../";
  require_once($RELEATED_PATH . "config.php");
  */
  require_once("mmc_config.php");
  require_once("platform_config.php");
  $RELEATED_PATH = "../";
  require_once($RELEATED_PATH . "fadmin.php");
  /*
  require_once("../session.php");
  require_once("../library/common.php");
  require_once "../library/content.php";
  */


  // 此function為開啟即時會議前先將會議資訊寫入DB
  function InsertInstanceMeetingToDB($jnjData) {
      /*
      $DB_SERVER='140.123.23.78';
      $DB_LOGIN='cyberccu2Test';
      $DB_PASSWORD2='mmc315@cyberccu2';
      $DB='mmcDb';  
      */
      $mmc_db_config = new MMC_DB_Config();
      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
          die( "資料庫連結錯誤!!" );
      

      // 要把timestamp的計算拿到instance
      $jnjData->timestamp = strtotime('now');
      
      // 在ecourse不會自己改變值 所以要先記下來 再讓function回傳
      $returnRow['timestamp'] = $jnjData->timestamp ;

      $tempDate = date("Y-m-d H:i:s", $jnjData->timestamp);
            
      $query = "select * from  Member where memberId = '".$jnjData->ownerId."'";

      // 判斷此人(只有老師身分才可以開會議)是否已在MMC資料庫內
      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {
          if(mysql_num_rows($queryObj) == 0){
              $query = "insert into Member(`memberId`,`email_2`,`nativeName`,`role`,`emailPrivacy`,`leaveMessagePrivacy`,`onlinePrivacy`,`maxGuest`,`diskQuota`,`messageLimit`,
                        `createdDate`) values ($jnjData->ownerId,'$jnjData->ownerEmail','$jnjData->ownerName',1,0,0,0,100,100000,180,'$tempDate')";
              if($queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query))
                  ;
              else
                  echo "SQL error!!(Member II)";
          }
          else
              ;
      }
      else
          echo "SQL error!!(Member)";

      // 判斷有沒有接續會議，有的話要找出錄影檔的ID
      if($jnjData->continueType == 0)
         $recordingId = 0 ; 
      else
         $recordingId =  SearchingRecordingId($jnjData->continueRecordingId);


      // 會議分成有設定共同瀏覽網頁跟沒有設定
      if (!empty($jnjData->jointBrowsingUrl)) {
          $query = "insert into Meeting(`coordinatorId`,`parentId`,`meetingType`,`maxNumAttendee`,`startTime`,`endTime`,`title`,`mcuIp`,`recording`,`createdDate`,
                   `resumeRecordingId`,`allQuestioner`,`jointBrowsingUrl`,`courseId`) values ($jnjData->ownerId,0,16,0,'$tempDate','$tempDate',
                   '$jnjData->meetingTitle','140.123.23.78',$jnjData->recording,'$tempDate',$recordingId,0,'$jnjData->jointBrowsingUrl',$jnjData->courseId)";
      }
      else {
      $query = "insert into Meeting(`coordinatorId`,`parentId`,`meetingType`,`maxNumAttendee`,`startTime`,`endTime`,`title`,`mcuIp`,`recording`,`createdDate`,
                `resumeRecordingId`,`allQuestioner`,`courseId`) values ($jnjData->ownerId,0,16,0,'$tempDate','$tempDate',
                '$jnjData->meetingTitle','140.123.23.78',$jnjData->recording,'$tempDate',$recordingId,0,$jnjData->courseId)";
      }

      // 將會議資訊寫入資料庫
      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {
          $jnjData->meetingId = mysql_insert_id();
           $returnRow['meetingId'] = $jnjData->meetingId ;
      }
      else
          echo "SQL error!!";
      return $returnRow;
      
  }

  // 此function為預約會議前先將會議資訊寫入DB
  function InsertReservationMeetingToDB($jnjData) {
      /*
      $DB_SERVER='140.123.23.78';
      $DB_LOGIN='cyberccu2Test';
      $DB_PASSWORD2='mmc315@cyberccu2';
      $DB='mmcDb';
      */

      $jnjData->ownerName = mb_convert_encoding($jnjData->ownerName,"UTF-8","big5");
      $jnjData->meetingTitle = mb_convert_encoding($jnjData->meetingTitle,"UTF-8","big5");
      $jnjData->jointBrowsingUrl =mb_convert_encoding($jnjData->jointBrowsingUrl,"UTF-8","big5");
      $jnjData->agenda = mb_convert_encoding($jnjData->agenda,"UTF-8","big5");


      $tempDate = date("Y-m-d H:i:s", strtotime('now'));

      $mmc_db_config = new MMC_DB_Config();

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
          die( "資料庫連結錯誤!!" );
      
      // 如果資料不在MMC資料庫內，自訂加入member到MMC資料庫 
      $query = "select * from  Member where memberId = '".$jnjData->ownerId."'";

      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {
          if(mysql_num_rows($queryObj) == 0){
              $query = "insert into Member(`memberId`,`email_2`,`nativeName`,`role`,`emailPrivacy`,`leaveMessagePrivacy`,`onlinePrivacy`,`maxGuest`,`diskQuota`,`messageLimit`,
                        `createdDate`) values ($jnjData->ownerId,'$jnjData->ownerEmail','$jnjData->ownerName',1,0,0,0,100,100000,180,'$tempDate')";

              if($queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query))
                  ;
              else
                  echo "SQL error!!(Member II)";
          }
          else
              ;
      }
      else
          echo "SQL error!!(Member)";


      $tempDate = date("Y-m-d H:i:s", strtotime('now'));
      // 將開始時間轉換成Date格式
      $tempDateS = date("Y-m-d H:i:s", $jnjData->timestamp);
      $tempDateE = date("Y-m-d H:i:s",$jnjData->timestamp + $jnjData->duration);
      if($jnjData->continueType == 0)
             $recordingId = 0 ;
      else
             $recordingId =  SearchingRecordingId($jnjData->continueRecordingId);

      $query = "insert into Meeting(`coordinatorId`,`parentId`,`meetingType`,`maxNumAttendee`,`startTime`,`endTime`,`title`,`agenda`,`mcuIp`,`recording`,`createdDate`,
                `resumeRecordingId`,`allQuestioner`,`courseId`) values ($jnjData->ownerId,0,0,'$jnjData->maxoutconnection','$tempDateS','$tempDateE',
                '$jnjData->meetingTitle','$jnjData->agenda','140.123.23.78',$jnjData->recording,'$tempDate',$recordingId,0,$jnjData->courseId)";

      
      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {
          // 因為不用馬上進入會議，所以不用抓出meeting id
          // substr($row["meetingId"],0,1)."<br>";  // 這個可以用來判斷 ID進而判斷從哪裡來的 ，好像不用多加欄位
          // $jnjData->meetingId = mysql_insert_id();
      }
      else
          echo "SQL error!!";
      
    
  }

  // 將留言的資料寫入資料庫
  function InsertLevaeMsgToDB(&$jnjData) {
      /*
      $DB_SERVER='140.123.23.78';
      $DB_LOGIN='cyberccu2Test';
      $DB_PASSWORD2='mmc315@cyberccu2';
      $DB='mmcDb';

      if ( !( $link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD2)) )
          die( "資料庫連結錯誤!!" );
      */
      $mmc_db_config = new MMC_DB_Config();

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
          die( "資料庫連結錯誤!!" );

      // 要把timestamp的計算拿到instance
      $jnjData->timestamp = strtotime('now');
      $tempDate = date("Y-m-d H:i:s", $jnjData->timestamp);

      $query = "select * from  Member where memberId = '".$jnjData->ownerId."'";

      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {
          if(mysql_num_rows($queryObj) == 0){
              $query = "insert into Member(`memberId`,`email_2`,`nativeName`,`role`,`emailPrivacy`,`leaveMessagePrivacy`,`onlinePrivacy`,`maxGuest`,`diskQuota`,`messageLimit`,
                        `createdDate`) values ($jnjData->ownerId,'$jnjData->ownerEmail','$jnjData->ownerName',1,0,0,0,100,100000,180,'$tempDate')";

              if($queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query))
                  ;
              else
                  echo "SQL error!!(Member II)";
          }
          else
              ;
      }
      else
          echo "SQL error!!(Member)";

      if($jnjData->continueType == 0)
         $recordingId = 0 ;
      else
         $recordingId =  SearchingRecordingId($jnjData->continueRecordingId);

      // 分成有設定共同瀏覽網頁跟沒有設定
      if (!empty($jnjData->jointBrowsingUrl)) {
          $query = "insert into Meeting(`coordinatorId`,`parentId`,`meetingType`,`maxNumAttendee`,`startTime`,`endTime`,`title`,`mcuIp`,`recording`,`createdDate`,
                   `resumeRecordingId`,`allQuestioner`,`jointBrowsingUrl`,`courseId`) values ($jnjData->ownerId,0,32,0,'$tempDate','$tempDate',
                   '$jnjData->meetingTitle','140.123.23.78',$jnjData->recording,'$tempDate',$recordingId,0,'$jnjData->jointBrowsingUrl',$jnjData->courseId)";
      }
      else {
      $query = "insert into Meeting(`coordinatorId`,`parentId`,`meetingType`,`maxNumAttendee`,`startTime`,`endTime`,`title`,`mcuIp`,`recording`,`createdDate`,
                `resumeRecordingId`,`allQuestioner`,`courseId`) values ($jnjData->ownerId,0,32,0,'$tempDate','$tempDate',
                '$jnjData->meetingTitle','140.123.23.78',$jnjData->recording,'$tempDate',$recordingId,0,$jnjData->courseId)";
      }

      // $query = "select *  from  Meeting  where coordinatorId = '1332'";
      // echo $query;
      // die;


      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {
          // substr($row["meetingId"],0,1)."<br>";  // 這個可以用來判斷 ID進而判斷從哪裡來的 ，好像不用多加欄位
          $jnjData->meetingId = mysql_insert_id();
      }
      else
          echo "SQL error!!";

  }


  // 找出使用者的所有會議
  function SearchingMeeting($ownerId) {
      /*
      $DB_SERVER='140.123.23.78';
      $DB_LOGIN='cyberccu2Test';
      $DB_PASSWORD2='mmc315@cyberccu2';
      $DB='mmcDb';
      */
      $mmc_db_config = new MMC_DB_Config();

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
          die( "資料庫連結錯誤!!" );

      $query = "select *  from  Recording  where coordinatorId = '{$ownerId}' order by actualStartTime desc";
      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {
   
          return($queryObj);
      }
      else
          echo "SQL error!!";
      
  }

  // 根據meeting找出記錄檔的名字，通常是做接續會議用的，刪除錄影檔時也需要錄影檔的名字
  function SearchingRecordingFileName($meetingId) {
      //$DB_SERVER='140.123.23.78';
      //$DB_LOGIN='cyberccu2Test';
      //$DB_PASSWORD2='mmc315@cyberccu2';
      //$DB='mmcDb';

      $mmc_db_config = new MMC_DB_Config();

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
          die( "資料庫連結錯誤!!" );

      $query = "select *  from  Recording  where meetingId = '{$meetingId}' ";
      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {
          $row = mysql_fetch_assoc($queryObj);
          return($row['recordingFile']);
      }
      else
          echo "SQL error!!";

  }

  // 根據meeting找出記錄檔的id，通常是做接續會議用的
  function SearchingRecordingId($meetingId) {
      //$DB_SERVER='140.123.23.78';
      //$DB_LOGIN='cyberccu2Test';
      //$DB_PASSWORD2='mmc315@cyberccu2';
      //$DB='mmcDb';
      
      $mmc_db_config = new MMC_DB_Config();

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
          die( "資料庫連結錯誤!!" );

      $query = "select *  from  Recording  where meetingId = '{$meetingId}' ";
      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {
          $row = mysql_fetch_assoc($queryObj);
          return($row['recordingId']);
      }
      else
          echo "SQL error!!";

  }

  // 根據記錄檔的id，找出該會議的meeting Id
  function SearchingRecordingMeetingId($recordingId) {
      //$DB_SERVER='140.123.23.78';
      //$DB_LOGIN='cyberccu2Test';
      //$DB_PASSWORD2='mmc315@cyberccu2';
      //$DB='mmcDb';

      $mmc_db_config = new MMC_DB_Config();

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
          die( "資料庫連結錯誤!!" );

      $query = "select *  from  Recording  where recordingId = '{$recordingId}' ";
      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {
          $row = mysql_fetch_assoc($queryObj);
          return($row['meetingId']);
      }
      else
          echo "SQL error!!";

  }

  // 查看會議事不是正在進行，如果是會有Meeting ID放在jnjData->meetingId
  function SearchOnlineMeeting($courseId,$site) {
      //$DB_SERVER='140.123.23.78';
      //$DB_LOGIN='cyberccu2Test';
      //$DB_PASSWORD2='mmc315@cyberccu2';
      //$DB='mmcDb';
      
      $mmc_db_config = new MMC_DB_Config();
      $mmc_path_config = new MMC_Path_Config();


      $tempCourseName = db_getCourseName($courseId);


      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
        die( "資料庫連結錯誤!!" );

 
      $tempCourseName  = mb_convert_encoding($tempCourseName,"UTF-8","big5");
      $query = "select meetingId from MyCourseNameOfMeeting where courseName = '{$tempCourseName}' ORDER BY `id` DESC LIMIT 0 , 1 ";

      // $query = mb_convert_encoding($query,"UTF-8","big5");

      if ( $queryObj = mysql_db_query(  $mmc_db_config->Mmc_DB, $query)) {
          $row = mysql_fetch_assoc($queryObj);
          $temp_meetingId = $row['meetingId'] ;
      }
      else
          echo "SQL error!!";

      $temp_site = $mmc_path_config->path.$site;	  
      // 找出正在進行的會議，根據課堂跟主持人做判斷，防止別課的學生進入到不同的課堂，meeting type 32代表留言模式，需過濾掉
      // $query = "select *  from  Meeting  where courseId = '{$jnjData->courseId}' AND coordinatorId = '{$jnjData->ownerId}' AND actualStartTime IS NOT NULL AND actualEndTime IS NULL AND meetingType != 32";
      echo "<meta http-equiv='refresh' content='0;url=link_MMC.php?mid={$temp_meetingId}&fn={$temp_site}&op=4'>";	  
  }

  function GetOnlineMeetingInfo($jnjData,$temp_meetingId) {

      $mmc_db_config = new MMC_DB_Config();


      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
          die( "資料庫連結錯誤!!" );


      $query = "select *  from  Meeting  where meetingId = '{$temp_meetingId}'";
       
      if ( $queryObj = mysql_db_query($mmc_db_config->Mmc_DB, $query)) {
          if(mysql_num_rows($queryObj) != 0){
              $row = mysql_fetch_assoc($queryObj);
              $jnjData->ownerId = $row['coordinatorId'];
              $jnjData->meetingId = $row['meetingId'];
              $jnjData->meetingTitle = $row['title'];
              $jnjData->recording = $row['recording'];

              // 要算duration
              $jnjData->duration = ((int) (strtotime($row['endTime']) - strtotime($row['startTime']))/60);
              
              /*
              if ($row['meetingType'] == 16) { //即時會議
                  $jnjData->duration = 0 ;
              }
              else // 可能還會加其他判斷，目前只做到即時會議
                  ;
               */

              $jnjData->numofConnection = $row['maxNumAttendee'] ;

              if($row['jointBrowsingUrl'] != NULL )
                  $jnjData->jointBrowsingUrl = $row['jointBrowsingUrl'];
              else
                  ;
              if($row['resumeRecordingId'] != 0 ) {
                  $jnjData->continueType = 1 ;
                  $jnjData->continueRecordingId = SearchingRecordingMeetingId($row['resumeRecordingId']);
                  // 反查meetingId
              }
              else
                  $jnjData->continueType = 0 ;

              // 抓老師名字跟email
              $query = "select *  from  Member where memberId  = '{$jnjData->ownerId}'";
              if ( $queryObj = mysql_db_query(  $mmc_db_config->Mmc_DB, $query)) {
                  $row = mysql_fetch_assoc($queryObj);
                  $jnjData->ownerName = $row['nativeName'];
                  $jnjData->ownerEmail = $row['email_2'];
              }
              else
                ;


          }
          else
            ;
          return $jnjData;
      }
      else
          echo "SQL error!!";

  }

  function IsTodayReservationMeeting(&$jnjData) {
    /*
      $DB_SERVER='140.123.23.78';
      $DB_LOGIN='cyberccu2Test';
      $DB_PASSWORD2='mmc315@cyberccu2';
      $DB='mmcDb';
     */

      $mmc_db_config = new MMC_DB_Config();

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
        die( "資料庫連結錯誤!!" );

      // 不能單單只用course ID，不然平台不同會抓錯，在用老師ID判斷
      $query = "select *  from  Meeting  where courseId = '{$jnjData->courseId}' AND coordinatorId = '{$jnjData->ownerId}' AND meetingType = 0 AND actualEndTime IS NULL order by startTime asc";

      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {
          if(mysql_num_rows($queryObj) != 0){
              // 要回圈判斷全部，因為有可能老師預約了但並沒有上課
              while ( $row = mysql_fetch_assoc($queryObj) ) {

                // 判斷是不是今天的meeting
                if( (date('Ymd',strtotime($row['startTime'])) == date('Ymd')) && (strtotime(($row['startTime'])) > (time()-10800)) 
                    && (strtotime($row['endTime']) > time()) ) {
                  // 是今天的話將meeting資訊記錄好，以便之後如果是要進入預約會議可以直接使用
                  $jnjData->ownerId = $row['coordinatorId'];
                  $jnjData->meetingId = $row['meetingId'];
                  $jnjData->meetingTitle = $row['title'];
                  $jnjData->recording = $row['recording'];

              
                  // 要算duration
                  $jnjData->duration = ((int) (strtotime($row['endTime']) - strtotime($row['startTime']))/60);

                  // 要抓出maxacount
                  $jnjData->numofConnection = $row['maxNumAttendee'] ;

                  if($row['jointBrowsingUrl'] != NULL )
                      $jnjData->jointBrowsingUrl = $row['jointBrowsingUrl'];
                  else
                      ;
                  if($row['resumeRecordingId'] != 0 ) {
                      $jnjData->continueType = 1 ;
                      $jnjData->continueRecordingId = SearchingRecordingMeetingId($row['resumeRecordingId']);
                      // 反查meetingId
                  }
                  else
                      $jnjData->continueType = 0 ;

                  // 在這邊處理完，是否開準備還是直接進入
                  // 因為對startTime排序過，所以第一個如果還在15分鐘前，其他的一定超過15分鐘
                  if ((strtotime($row['startTime']) - time()) >900) { //進入準備
                      return "prepare";
                  }
                  else //會議開始了，直接進入會議
                      return "enter";
                } // if today meeting end
                else
                    ;

              } // while wnd
              // 如果迴圈之內都沒return，代表有資料但沒有今天的會議，所以直接跳到instance
              return "instance";
          } // if end
          else //沒有任何一個預約會議需要開始，代表所有的預約會議幾結束了
            return "instance";
      }
      else
            echo "SQL error!!";

  }

  // 如果今天有預約會議，但還沒到開始時間可讓老師看今天有哪些會議，以及讓老師可以取消跟進入準備模式
  // 參數courseId:課程的id
  //     ownerId:使用者的id
  //     courseName:課程名稱
  //     teacherName:老師姓名
  // 回傳所有今天的meeting資訊，資訊有課程名稱、老師名字、取消與準備的網址(會根據meetingId)
  function AllTodayMeeting($courseId,$ownerId,$courseName,$teacherName) {
      
      /*
      $DB_SERVER='140.123.23.78';
      $DB_LOGIN='cyberccu2Test';
      $DB_PASSWORD2='mmc315@cyberccu2';
      $DB='mmcDb';
      */

      $mmc_db_config = new MMC_DB_Config();

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
        die( "資料庫連結錯誤!!" );

      // 不能單單只用course ID，不然平台不同會抓錯，可能在用老師ID(或在加平台碼)
      $query = "select *  from  Meeting  where courseId = '{$courseId}' AND coordinatorId = '{$ownerId}' AND meetingType = 0 AND actualEndTime IS NULL order by startTime asc";

      $allTodayMeeting = Array();

      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {
          if(mysql_num_rows($queryObj) != 0){
              // 要回圈判斷全部，因為有可能老師預約了但並沒有上課
              while ( $row = mysql_fetch_assoc($queryObj) ) {

                // 判斷是不是今天的meeting
                if( (date('Ymd',strtotime($row['startTime'])) == date('Ymd')) && (strtotime(($row['startTime'])) > (time()-10800))
                      && (strtotime($row['endTime']) > time()) ) {

                  // 在這邊處理完，是否開準備還是直接進入
                  if ((strtotime($row['startTime']) - time()) >900) { //要show的meeting這先是今天還沒到的，可以讓老師做準備或編輯

                        /*
                        $query = "select *  from begin_course where begin_course_cd = '{$row['courseId']}'"; // 只會有一筆，所以之後的資料都讀 index(0)即可

                        $query_Result = db_getAll($query);

                        $tempCourse_cd = $query_Result[0]["course_cd"]; //因為只有一筆所以只讀0
                        $row['courseName'] = $query_Result[0]["begin_course_name"];
                        */
                        $row['courseName'] = $courseName;
                        /*
                        $query = "select *  from course_basic where course_cd = '{$tempCourse_cd}'"; // 只會有一筆，所以之後的資料都讀 index(0)即可

                        $query_Result = db_getAll($query);

                        $tempTeacher_cd = $query_Result[0]["teacher_cd"]; //因為只有一筆所以只讀0

                        $query = "select *  from personal_basic where  personal_id  = '{$tempTeacher_cd}'"; // 只會有一筆，所以之後的資料都讀 index(0)即可

                        $query_Result = db_getAll($query);
                        */
                        // 老師資訊
                        //$row['teacherName'] = $query_Result[0]["personal_name"]; //因為只有一筆所以只讀0
                        $row['teacherName'] = $teacherName;

                        // 準備/取消會議網址 (每個meeting id不一樣所以網址會不同)
                        $row['preparaMeeting'] = "preparaMeeting.php?mid=".$row['meetingId']; 
                        $row['cancelMeeting'] = "cancelMeeting.php?mid=".$row['meetingId'];
                        $row['title'] = mb_convert_encoding($row['title'],"big5","UTF-8"); 
                        $allTodayMeeting[]= $row;  // add資訊到此陣列以便之後show出來
                  }
                  else //會議開始了，直接進入會議
                      ;
                } // if today meeting end
                else
                    ;

              } // while wnd
          } // if end
          else
            ;

          return $allTodayMeeting ;
      }
      else
            echo "SQL error!!"; 

  }

  // 藉由Meeting Id把jnjData的其他資料設定好
  function SearchMeetingByMeetingId(&$jnjData) {
      /*
      $DB_SERVER='140.123.23.78';
      $DB_LOGIN='cyberccu2Test';
      $DB_PASSWORD2='mmc315@cyberccu2';
      $DB='mmcDb';
      */
      $mmc_db_config = new MMC_DB_Config();

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
        die( "資料庫連結錯誤!!" );

      $query = "select *  from  Meeting  where meetingId = '{$jnjData->meetingId}'";

      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {
          if(mysql_num_rows($queryObj) != 0){
              $row = mysql_fetch_assoc($queryObj);
              $jnjData->ownerId = $row['coordinatorId'];
              $jnjData->meetingId = $row['meetingId'];
              $jnjData->meetingTitle = $row['title'];
              $jnjData->recording = $row['recording'];

              // 要算duration
              $jnjData->duration = ((int) (strtotime($row['endTime']) - strtotime($row['startTime']))/60);

              // 要抓出maxacount
              $jnjData->numofConnection = $row['maxNumAttendee'] ;

              if($row['jointBrowsingUrl'] != NULL )
                  $jnjData->jointBrowsingUrl = $row['jointBrowsingUrl'];
              else
                  ;
              if($row['resumeRecordingId'] != 0 ) {
                  $jnjData->continueType = 1 ;
                  $jnjData->continueRecordingId = SearchingRecordingMeetingId($row['resumeRecordingId']);
                  // 反查meetingId
              }
              else
                   $jnjData->continueType = 0 ;

          }
          else
            ;
          // 取得老師名稱以及email
          $query = "select * from  Member where memberId = '{$row['coordinatorId']}'";
          $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query);
          $row2 = mysql_fetch_assoc($queryObj);

	  $jnjData->ownerName = $row2['nativeName'];
	  $jnjData->ownerEmail = $row2['email_2'];

      }
      else
            echo "SQL error!!";

  }

  //根據MeetingId，把會議刪除
  function DeleteMeetingByMeetingId($meetingId) {
      //$DB_SERVER='140.123.23.78';
      //$DB_LOGIN='cyberccu2Test';
      //$DB_PASSWORD2='mmc315@cyberccu2';
      //$DB='mmcDb';

      $mmc_db_config = new MMC_DB_Config();
      
      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
        die( "資料庫連結錯誤!!" );

      $query = "delete from  Meeting  where meetingId = '{$meetingId}'";

      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {
            echo "會議取消完成";
      }
      else
            echo "SQL error!!";

  }

  // 根據資料夾id與使用者找出對應的錄影檔
  function searchRecording($ownerId,$folderId,$seq) {
      /*
      $DB_SERVER='140.123.23.78';
      $DB_LOGIN='cyberccu2Test';
      $DB_PASSWORD2='mmc315@cyberccu2';
      $DB='mmcDb';

      if ( !( $link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD2)) )
        die( "資料庫連結錯誤!!" );
      */
      $mmc_db_config = new MMC_DB_Config();
      $mmc_path_config = new MMC_Path_Config();

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
        die( "資料庫連結錯誤!!" );

      if($seq == 0) {
          $query = "select *, t1.recordingId as recordingId from  Recording t1 left join MyRecordsInFolder t2  on t1.recordingId = t2.recordingId 
              where t1.coordinatorId = '{$ownerId}' AND t2.folderId is null order by t1.actualStartTime desc";
      }
      else {
          $query = "select *, t1.recordingId as recordingId from  Recording t1 left join MyRecordsInFolder t2  on t1.recordingId = t2.recordingId
              where t1.coordinatorId = '{$ownerId}' AND t2.folderId = '{$folderId}' order by t1.actualStartTime desc";
      }
      
      $allRecording = Array();

      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {
          if(mysql_num_rows($queryObj) != 0){
              // 要回圈判斷全部，全部的錄影檔都要放進來

              $index = 0 ;
              while ( $row = mysql_fetch_assoc($queryObj) ) {

                if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
                   die( "資料庫連結錯誤!!" );

                        $index = $index + 1 ;
                        $result = "";
                        
                        // 先找出這個會議的courseId才可以用查課名
                        
                        $query = "select *  from  Meeting  where meetingId = '{$row['meetingId']}' ";
                        $queryObj2 = mysql_db_query( $mmc_db_config->Mmc_DB, $query);
                        $row2 = mysql_fetch_assoc($queryObj2);

                        // 藉由課程id找出老師的名字與email
                        // 下面這個是平台的資料庫
                        /* 
                        $query = "select *  from begin_course where begin_course_cd = '{$row2['courseId']}'"; // 只會有一筆，所以之後的資料都讀 index(0)即可

                        $query_Result = db_getAll($query);
 
                        $tempCourse_cd = $query_Result[0]["course_cd"]; //因為只有一筆所以只讀0
                         
                        $row['courseName'] = $query_Result[0]["begin_course_name"];
                        */
                        if(!is_null($row2['courseId']))
                          $row['courseName'] = db_getCourseName($row2['courseId']);

                        $recordingHours = intval(($row['actualDuration']/3600));
                        $recordingMinutes = intval(($row['actualDuration']%3600)/60);
                        $recordingSeconds = intval($row['actualDuration']%60);

                        if ($recordingHours != 0)
                           $result = "{$recordingHours} 小時 ";
                        if($recordingMinutes != 0)
                           $result .= "{$recordingMinutes} 分 ";

                        $result .="{$recordingSeconds} 秒" ;

                        $row['durationString'] = $result ;
                        $row['recordingIndex'] = $index ;
                        $row['publishUrl'] = "publishMeeting.php?rid=".$row['recordingId']."&cfid=".$folderId."&cseq=".$seq;
                        $row['cancelpublishUrl'] = "cancelPublishMeeting.php?rid=".$row['recordingId']."&cfid=".$folderId."&cseq=".$seq;
                        $row['operationUrl'] = "operationRecording.php?rid=".$row['recordingId']."&cfid=".$folderId."&cseq=".$seq;
                        /*
                        $row['slidingshowUrl'] = "{$mmc_path_config->mcu_localfile_path}my_records_slidingshow.php?cid=".$row['coordinatorId']."&mid=".$row['meetingId'];
                        $row['chatUrl'] = "{$mmc_path_config->mcu_localfile_path}my_records_chat.php?cid=".$row['coordinatorId']."&mid=".$row['meetingId'];
                         */
                        $row['slidingshowUrl'] = "link_MMC.php?cid=".$row['coordinatorId']."&mid=".$row['meetingId']."&op=2";
                        $row['chatUrl'] = "link_MMC.php?cid=".$row['coordinatorId']."&mid=".$row['meetingId']."&op=3";

                        $row['title'] = mb_convert_encoding($row['title'],"big5","UTF-8");

                        $allRecording[]= $row;  // add資訊到此陣列以便之後show出來


                  }
                } 
                else
                    ;

          return $allRecording ;
      }
      else
            echo "SQL error!!";

  }

  //發佈會議，將資料寫到到資料庫
  function PublishMeetingInDB($recordingId,$courseName,$begin_course_cd,$ownerName,$ownerEmail) {
      /*      
      $DB_SERVER='140.123.23.78';
      $DB_LOGIN='cyberccu2Test';
      $DB_PASSWORD2='mmc315@cyberccu2';
      $DB='mmcDb';
      */
      $mmc_db_config = new MMC_DB_Config();
      $mmc_path_config = new MMC_Path_Config();

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
        die( "資料庫連結錯誤!!" );

      $query = "update Recording set isRead = 1 where recordingId = '{$recordingId}'";


      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {
              
              $query = "select * from Recording where recordingId = '{$recordingId}'";
              $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query);
              $row = mysql_fetch_assoc($queryObj);

              $ownerId = $row['coordinatorId'];
              $meetingTitle = $row['title'] ;
              $startTime = $row['actualStartTime'];
              $endTime = $row['actualEndTime'];
              $actualDuration = $row['actualDuration'];
              $fileName = $row['recordingFile'];              
              $fileSize = $row['recordingSize'];
              $publishDate = date("Y-m-d H:i:s", strtotime('now'));

	      $ownerName = mb_convert_encoding($ownerName,"UTF-8","big5");

              // insert PubRecording
              $query = "insert into PubRecording(`recordingId`,`publisherId`,`coordinatorEmail`,`coordinatorName`,`title`,`actualStartTime`,`actualEndTime`,`actualDuration`,`recordingFile`
                ,`recordingSize`,`openAccess`,`publishDate`) values ($recordingId,$ownerId,'$ownerEmail','$ownerName','$meetingTitle','$startTime','$endTime',$actualDuration,
                    '$fileName',$fileSize,2,'$publishDate')";
              $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query);
              
              // insert MyOnLineMapping
              $meetingDate = date("Y-m-d", strtotime($startTime));
              $pubRecordingId = mysql_insert_id();
              /*
              $query = "insert into MyOnLineMapping(`pubRecordingId`,`courseName`,`courseId`,`subject`,`date`) 
                   values ($pubRecordingId,'$courseName',$begin_course_cd,'$meetingTitle','$publishDate')";
              $queryObj = mysql_db_query( $DB, $query);              
              */
	      $pubUrl = "{$mmc_path_config->path}publishedRecording.php?id=$pubRecordingId";

              $meetingTitle = mb_convert_encoding($meetingTitle,"big5","UTF-8");

	      db_setOn_line($pubRecordingId,$meetingDate,$meetingTitle, $pubUrl);
              /*      
              $Content_cd = get_Content_cd($begin_course_cd);
              
              // 這個資料庫式平台的並非MMC的
              $query = "INSERT INTO on_line ( content_cd , seq, d_course , subject , rfile )
                VALUES ( $Content_cd,NULL, '$publishDate', '$meetingTitle', '$pubUrl') ";
              db_query($query);
              */
              
      }
      else
          echo "SQL error!!";
       

      
  }

  function CancelPublishMeetingInDB($recordingId) {

      /*
      $DB_SERVER='140.123.23.78';
      $DB_LOGIN='cyberccu2Test';
      $DB_PASSWORD2='mmc315@cyberccu2';
      $DB='mmcDb';
       */

      $mmc_db_config = new MMC_DB_Config();
      $mmc_path_config = new MMC_Path_Config(); 
    
      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
        die( "資料庫連結錯誤!!" );

      $query = "update Recording set isRead = 0 where recordingId = '{$recordingId}'";


      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {

              // select  PubRecording  PubRecordingId
              $query = "select * from PubRecording where recordingId = '{$recordingId}'";  
              $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query);
              $row = mysql_fetch_assoc($queryObj);
              $pubRecordingId = $row['pubRecordingId'];

              // delete PubRecording              
              $query = "delete from PubRecording where recordingId = '{$recordingId}'";
              $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query);
              

              $pubUrl = "{$mmc_path_config->path}publishedRecording.php?id=$pubRecordingId";
	      db_delOn_line($pubRecordingId,$pubUrl);
              /*
              //這個資料庫不在MMC上，是平台本來的資料庫，刪除同步中的資訊
              $query = "delete from on_line where rfile = '{$pubUrl}'";
              db_query($query);
              */

      }
      else
            echo "SQL error!!";

  }

  // 藉由recordingId，取得錄影檔資訊，並且將資訊回傳 
  function GetPubRecordingInfo($recordingId,$course_Name) {
      /*
      $DB_SERVER='140.123.23.78';
      $DB_LOGIN='cyberccu2Test';
      $DB_PASSWORD2='mmc315@cyberccu2';
      $DB='mmcDb';
      */
      $mmc_db_config = new MMC_DB_Config();
      $mmc_path_config = new MMC_Path_Config();

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
        die( "資料庫連結錯誤!!" );

      $query = "select * from PubRecording where pubRecordingId = '{$recordingId}'";

      $recordingInfo = Array();

      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {

          $row = mysql_fetch_assoc($queryObj);
          $temprecordingId = $row['recordingId'];

          $query = "select * from Recording where recordingId = '{$temprecordingId}'";
          $queryObj2 = mysql_db_query( $mmc_db_config->Mmc_DB, $query);
          $row2 = mysql_fetch_assoc($queryObj2);
          /*
          $row['slidingshowUrl'] = "{$mmc_path_config->mcu_localfile_path}my_records_slidingshow.php?cid=".$row2['coordinatorId']."&mid=".$row2['meetingId'];
          $row['chatUrl'] = "{$mmc_path_config->mcu_localfile_path}my_records_chat.php?cid=".$row2['coordinatorId']."&mid=".$row2['meetingId'];
          */
          $row['slidingshowUrl'] = "link_MMC.php?cid=".$row2['coordinatorId']."&mid=".$row2['meetingId']."&op=2";
          $row['chatUrl'] = "link_MMC.php?cid=".$row2['coordinatorId']."&mid=".$row2['meetingId']."&op=3";

          $row['meetingId'] = $row2['meetingId'];


          $row['courseName'] = $course_Name;
          $row['actualTime'] = $row['actualStartTime']." - ".$row['actualEndTime'];

          $recordingHours = intval(($row['actualDuration']/3600));
          $recordingMinutes = intval(($row['actualDuration']%3600)/60);
          $recordingSeconds = intval($row['actualDuration']%60);

          if ($recordingHours != 0)
              $result = "{$recordingHours} 小時 ";
          if($recordingMinutes != 0)
              $result .= "{$recordingMinutes} 分 ";

          $result .="{$recordingSeconds} 秒" ;
        
          $row['durationString'] = $result ;

          $row['title'] = mb_convert_encoding($row['title'],"big5","UTF-8");
          $row['coordinatorName'] = mb_convert_encoding($row['coordinatorName'],"big5","UTF-8"); //因為只有一筆
	  
          //$recordingInfo[]= $row;
          return $row;       

      }
      else
            echo "SQL error!!";
  }

  // 取得錄影檔的資訊(原則上只有老師看的到)
  // 參數recordingId:錄影檔的id
  //     course_Name:課程的名稱
  function GetRecordingInfo($recordingId,$course_Name) {
      /*
      $DB_SERVER='140.123.23.78';
      $DB_LOGIN='cyberccu2Test';
      $DB_PASSWORD2='mmc315@cyberccu2';
      $DB='mmcDb';
      */
      $mmc_db_config = new MMC_DB_Config();
      $mmc_path_config = new MMC_Path_Config();

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
        die( "資料庫連結錯誤!!" );


      $query = "select * from Recording where recordingId = '{$recordingId}'";
      $queryObj2 = mysql_db_query( $mmc_db_config->Mmc_DB, $query);
      $row = mysql_fetch_assoc($queryObj2);

      // [jfish] 這邊的網址，應該要做一些處理
      /*
      $row['slidingshowUrl'] = "{$mmc_path_config->mcu_localfile_path}my_records_slidingshow.php?cid=".$row['coordinatorId']."&mid=".$row['meetingId'];
      $row['chatUrl'] = "{$mmc_path_config->mcu_localfile_path}my_records_chat.php?cid=".$row['coordinatorId']."&mid=".$row['meetingId'];
      */
      $row['slidingshowUrl'] = "link_MMC.php?cid=".$row['coordinatorId']."&mid=".$row['meetingId']."&op=2";
      $row['chatUrl'] = "link_MMC.php?cid=".$row['coordinatorId']."&mid=".$row['meetingId']."&op=3";
       

      //$row['courseName'] = $course_Name;
      $row['actualTime'] = $row['actualStartTime']." - ".$row['actualEndTime'];

      $recordingHours = intval(($row['actualDuration']/3600));
      $recordingMinutes = intval(($row['actualDuration']%3600)/60);
      $recordingSeconds = intval($row['actualDuration']%60);

      if ($recordingHours != 0)
           $result = "{$recordingHours} 小時 ";
      if($recordingMinutes != 0)
           $result .= "{$recordingMinutes} 分 ";

      $result .="{$recordingSeconds} 秒" ;

      $row['durationString'] = $result ;


      // 取得老師名稱以及email
      $query = "select * from  Member where memberId = '{$row['coordinatorId']}'";
      $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query);
      $row2 = mysql_fetch_assoc($queryObj);

      // 找出課名
      $query = "select * from Meeting where meetingId = '{$row['meetingId']}'";
      $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query);
      $row3 = mysql_fetch_assoc($queryObj);
      
      $row['title'] = mb_convert_encoding($row['title'],"big5","UTF-8");
      $row['coordinatorName'] = mb_convert_encoding($row2['nativeName'],"big5","UTF-8"); //因為只有一筆
      $row['coordinatorEmail'] = $row2['email_2']; //因為只有一筆
      if(!empty($row3['courseId']))
      	$row['courseName'] = db_getCourseName($row3['courseId']);
      else
	;


      return $row;

  }

  // 藉由Meeting把錄影檔的資訊取出
  function GetPlaymodeInfoByMeetingId(&$jnjData) {
      // $DB_SERVER='140.123.23.78';
      // $DB_LOGIN='cyberccu2Test';
      // $DB_PASSWORD2='mmc315@cyberccu2';
      // $DB='mmcDb';

      $mmc_db_config = new MMC_DB_Config();

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
        die( "資料庫連結錯誤!!" );

      $query = "select *  from  Meeting  where meetingId = '{$jnjData->meetingId}'";

      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {
          if(mysql_num_rows($queryObj) != 0){
              $row = mysql_fetch_assoc($queryObj);
              $jnjData->ownerId = $row['coordinatorId'];

              // 要算duration
              $jnjData->duration = ((int) (strtotime($row['endTime']) - strtotime($row['startTime']))/60);
              
              // 要抓出maxacount
              $jnjData->numofConnection = $row['maxNumAttendee'] ;

              // 要試試看有沒有差
              if($row['jointBrowsingUrl'] != NULL )
                  $jnjData->jointBrowsingUrl = $row['jointBrowsingUrl'];
              else
                  ;

              // 抓出錄影檔檔名
              $query = "select *  from  Recording  where meetingId = '{$jnjData->meetingId}'";
              $queryObj2 = mysql_db_query( $mmc_db_config->Mmc_DB, $query);
              $row2 = mysql_fetch_assoc($queryObj2);

              // [jfish] 忘記這是做啥用的 要在確認一下
              $jnjData->continueRecordingId = $row2['recordingId'];
	      
              $query = "select *  from  Member  where memberId = '{$jnjData->ownerId}'";
              $queryObj2 = mysql_db_query( $mmc_db_config->Mmc_DB, $query);
              $row2 = mysql_fetch_assoc($queryObj2);
              
	      $jnjData->ownerName = $row2['nativeName'];
	      $jnjData->ownerEmail = $row2['email_2'];
              // 要試試看有沒有差
              /*
              if($row['resumeRecordingId'] != 0 ) {
                  $jnjData->continueType = 1 ;
                  $jnjData->continueRecordingId = SearchingRecordingMeetingId($row['resumeRecordingId']);
                  // 反查meetingId
              }
              else
                  $jnjData->continueType = 0 ;
               */

          }
          else
            ;
      }
      else
            echo "SQL error!!";

  }

  /* ----以下是有關管理錄影檔的資料夾在DB的function----*/

  // 創造根目錄資料夾
  // 一個老師第一次進來在DB會沒有資料夾的資訊，會先創造一個跟目錄
  // 傳入的參數為owner的Id來辨認是哪個老師
  function CreateRootFolder($ownerId) {
      /*
      $DB_SERVER='140.123.23.78';
      $DB_LOGIN='cyberccu2Test';
      $DB_PASSWORD2='mmc315@cyberccu2';
      $DB='mmcDb';
      */
      $mmc_db_config = new MMC_DB_Config();

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
        die( "資料庫連結錯誤!!" );

      $query = "select *  from  MyRecordsFolder  where memberId = '{$ownerId}'";
      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {
          // 如果DB中找不到資料夾的資料才會增加跟目錄資料夾
          if(mysql_num_rows($queryObj) == 0){
              $query = "insert into MyRecordsFolder(`parentId`,`memberId`,`folderCaption`,`sequence`) values (0,$ownerId,'root',0)";

              if($queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query))
                  ;
              else
                  echo "SQL error!!(Member II)";
          }
          else
              ;
      }
      else
          echo "SQL error!!(Member)";

  }

  // 找出老師所有的資料夾(根據ownerId)
  // 回傳floder的array
  function GetAllFolder($ownerId) {
      /*
      $DB_SERVER='140.123.23.78';
      $DB_LOGIN='cyberccu2Test';
      $DB_PASSWORD2='mmc315@cyberccu2';
      $DB='mmcDb';
      */
      $mmc_db_config = new MMC_DB_Config();

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
        die( "資料庫連結錯誤!!" );
      
      $query = "select *  from  MyRecordsFolder  where memberId = '{$ownerId}'";
      
      // 第一筆資料一定是root所以拿第一筆做比對即可
      $folderList = Array();
      
      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {
          
          if(mysql_num_rows($queryObj) != 0){
              while ( $row = mysql_fetch_assoc($queryObj) ) {
                    // [jfish] 忘記這是什麼東西了，為什麼要設成0
                    $row['addToCreate'] = 0 ;
		    $row['folderCaption'] = mb_convert_encoding($row['folderCaption'],"big5","UTF-8"); 
                    $folderList [] = $row ;
              }
                return $folderList;
            
          }
          else
              ;
      }
      else
          echo "SQL error!!(Member)";

  }

  // 根據folder的id找出該folder的名稱
  function GetFolderNameByFolderId($folderId) {
      /*
      $DB_SERVER='140.123.23.78';
      $DB_LOGIN='cyberccu2Test';
      $DB_PASSWORD2='mmc315@cyberccu2';
      $DB='mmcDb';
      */
      $mmc_db_config = new MMC_DB_Config();

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
        die( "資料庫連結錯誤!!" );

      $query = "select folderCaption from  MyRecordsFolder  where folderId = '{$folderId}'";

      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {

          if(mysql_num_rows($queryObj) != 0){
              $row = mysql_fetch_assoc($queryObj);
                    $folderName = $row['folderCaption'] ;
              return $folderName;

          }
          else
              ;
      }
      else
          echo "SQL error!!(Member)";

  }

  // 在DB中新增一個資料夾
  // 參數folderName:新增的資料夾名稱
  //     folderId:上層資料夾的id
  //     ownerId:使用者的id
  function CreateFolderInDB($folderName,$folderId,$ownerId) {
      /*
      $DB_SERVER='140.123.23.78';
      $DB_LOGIN='cyberccu2Test';
      $DB_PASSWORD2='mmc315@cyberccu2';
      $DB='mmcDb';
      */
      $mmc_db_config = new MMC_DB_Config();
      
      $folderName = mb_convert_encoding($folderName,"UTF-8","big5");

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
        die( "資料庫連結錯誤!!" );

      $query = "insert into MyRecordsFolder(`parentId`,`memberId`,`folderCaption`,`sequence`) values ($folderId,$ownerId,'$folderName',1)";

      if($queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query))
          ;
      else
          echo "SQL error!!(Member II)";

  }

  // 在DB中修改資料夾名稱
  // 參數folderName:要改的名稱
  //     folderId:要更改名稱的資料夾id
  function RenameFolderInDB($folderName,$folderId) {
      /*
      $DB_SERVER='140.123.23.78';
      $DB_LOGIN='cyberccu2Test';
      $DB_PASSWORD2='mmc315@cyberccu2';
      $DB='mmcDb';
      */
      $mmc_db_config = new MMC_DB_Config();

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
        die( "資料庫連結錯誤!!" );
      
      $folderName = mb_convert_encoding($folderName,"UTF-8","big5");
      $query = "update MyRecordsFolder set folderCaption = '{$folderName}' where folderId = '{$folderId}'";

      if($queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query))
          ;
      else
          echo "SQL error!!(Member II)";

  }

  // 在DB中刪除資料夾資訊
  // 參數folderId:要刪除的資料夾id
  function DeleteFolderInDB($folderId) {
      /*
      $DB_SERVER='140.123.23.78';
      $DB_LOGIN='cyberccu2Test';
      $DB_PASSWORD2='mmc315@cyberccu2';
      $DB='mmcDb';
      */
      $mmc_db_config = new MMC_DB_Config();

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
        die( "資料庫連結錯誤!!" );
      
      $query = "delete from MyRecordsFolder where folderId = '{$folderId}'";

      if($queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query))
          ;
      else
          echo "SQL error!!(Member II)";
      

  } 

  // insert MyStudentMeetingLog資訊
  // 參數courseId:課程ID
  //     courseName:課程名稱
  //     stuId:學生ID
  function InsertMyStudentMeetingLog($courseId, $courseName, $stuId){
        $mmc_db_config = new MMC_DB_Config();

        if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
          die( "資料庫連結錯誤!!" );

        $valid_query = "select * from MyStudentMeetingLog where courseId = $courseId and courseName = '$courseName' and stuId = $stuId order by datetime desc limit 1";
        if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB,  $valid_query)) {

          if(mysql_num_rows($queryObj) != 0){
              $row = mysql_fetch_assoc($queryObj); //抓一個row
              $lastTime = strtotime($row['datetime']); //從row裡面去抓datetime
          }
          else
              ;
        }
        else
          echo "SQL error!!";

        if(( ( time() - $lastTime)/ 360 ) > 3 ){
                $query = "insert into MyStudentMeetingLog(`id`, `courseId`, `courseName`, `stuId`, `datetime`) values (NULL, $courseId, '$courseName', $stuId, now())";

                if($queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query))
                  ;
                else
                  echo "SQL error!!";
        }
  }


  // 找到使用者的根目錄的id
  // 參數ownerId:使用者id
  function GetRootFolderId($ownerId) {
      /*
      $DB_SERVER='140.123.23.78';
      $DB_LOGIN='cyberccu2Test';
      $DB_PASSWORD2='mmc315@cyberccu2';
      $DB='mmcDb';
      */
      $mmc_db_config = new MMC_DB_Config();

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
        die( "資料庫連結錯誤!!" );

      $query = "select *  from  MyRecordsFolder  where memberId = '{$ownerId}' AND parentId = 0";

      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {

          if(mysql_num_rows($queryObj) != 0){
              // 只會有一筆
              $row = mysql_fetch_assoc($queryObj);
                return $row['folderId'];
          }
          else
              ;
      }
      else
          echo "SQL error!!(Member)";

  }

  // 將錄影檔從資料移動到根目
  // 參數recordingId:要變動的錄影檔id
  function DeleteRecordingInFloder($recordingId) {
      /*
      $DB_SERVER='140.123.23.78';
      $DB_LOGIN='cyberccu2Test';
      $DB_PASSWORD2='mmc315@cyberccu2';
      $DB='mmcDb';
      */
      $mmc_db_config = new MMC_DB_Config();

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
        die( "資料庫連結錯誤!!" );

      $query = "delete from MyRecordsInFolder where recordingId = '{$recordingId}'";

      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {
      }
      else
          echo "SQL error!!(Member)";

  }

  // 將錄影檔移到特定資料夾內(從根目錄移動的錄影檔)
  // 參數recordingId:要變動的錄影檔id
  //     folderId:移動到資料夾id
  //     ownerId:使用者的id
  function InsertRecordingInFloder($recordingId,$folderId,$ownerId) {
      /*
      $DB_SERVER='140.123.23.78';
      $DB_LOGIN='cyberccu2Test';
      $DB_PASSWORD2='mmc315@cyberccu2';
      $DB='mmcDb';
      */
      $mmc_db_config = new MMC_DB_Config();

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
        die( "資料庫連結錯誤!!" );

      $query = "insert into MyRecordsInFolder(`recordingId`,`folderId`,`memberId`) values ($recordingId,$folderId,$ownerId)";

      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {
      }
      else
          echo "SQL error!!(Member)";

  }

  // 將錄影檔移到特定資料夾內(從非根目錄移動的錄影檔)
  // 參數recordingId:要變動的錄影檔id
  //     folderId:移動到資料夾id 
  function UpdateRecordingInFloder($recordingId,$folderId) {
      /*
      $DB_SERVER='140.123.23.78';
      $DB_LOGIN='cyberccu2Test';
      $DB_PASSWORD2='mmc315@cyberccu2';
      $DB='mmcDb';
      */
      $mmc_db_config = new MMC_DB_Config();

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
        die( "資料庫連結錯誤!!" );

      $query = "update MyRecordsInFolder set folderId='{$folderId}' where recordingId = '{$recordingId}'";

      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {
      }
      else
          echo "SQL error!!(Member)";

  }

  /*----以上是有關管理錄影檔的資料夾在DB的function----*/

  // [jfish] 好像沒有用到??
  function GetRecordingIdByPubRecordingId($pubRecordingId) {
      /*
      $DB_SERVER='140.123.23.78';
      $DB_LOGIN='cyberccu2Test';
      $DB_PASSWORD2='mmc315@cyberccu2';
      $DB='mmcDb';
      */
      $mmc_db_config = new MMC_DB_Config();

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
        die( "資料庫連結錯誤!!" );


      $query = "select *  from  PubRecording  where pubRecordingId = '{$pubRecordingId}'";

      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {

          if(mysql_num_rows($queryObj) != 0){
              // 只會有一筆
              $row = mysql_fetch_assoc($queryObj);
                return $row['recordingId'];
          }
          else
              ;
      }
      else
          echo "SQL error!!(Member)";

  }

  function GetCourseIdByMeetingId($meetingId) {
      /*
      $DB_SERVER='140.123.23.78';
      $DB_LOGIN='cyberccu2Test';
      $DB_PASSWORD2='mmc315@cyberccu2';
      $DB='mmcDb';

      if ( !( $link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD2)) )
        die( "資料庫連結錯誤!!" );
      */
      $mmc_db_config = new MMC_DB_Config();

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
        die( "資料庫連結錯誤!!" );

      $query = "select *  from  Meeting  where meetingId = '{$meetingId}'";

      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {

          if(mysql_num_rows($queryObj) != 0){
              // 只會有一筆
              $row = mysql_fetch_assoc($queryObj);
                return $row['courseId'];
          }
          else
              ;
      }
      else
          echo "SQL error!!(Member)";

  }

  function SetMyCourseNameOfMeeting($meetingId, $courseName) {
      $mmc_db_config = new MMC_DB_Config();

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
        die( "資料庫連結錯誤!!" );

      $query = "insert into MyCourseNameOfMeeting(`meetingId`,`courseName`) values ($meetingId, '$courseName')";
      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) { // sql指令執行正確
      }
      else
          echo "SQL error!!(Course)";


  }

  function GetTotalRecordingSize($ownerId) {

      $mmc_db_config = new MMC_DB_Config();

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
          die( "資料庫連結錯誤!!" );
      $totalSize = 0 ;
      $query = "select *  from  Recording  where coordinatorId = '{$ownerId}' order by actualStartTime desc";
      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {
              while ( $row = mysql_fetch_assoc($queryObj) ) {
                    $totalSize += $row['recordingSize'] ;
              }
      }
      else
          echo "SQL error!!";

      $query = "select * from  Member where memberId = '{$ownerId}'";
      $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query);
      $row2 = mysql_fetch_assoc($queryObj);

      $sizeArray['totalused'] = round(($totalSize/1048576),3);
      $sizeArray['totalquota'] = $row2['diskQuota'];
      $sizeArray['used'] = round(($sizeArray['totalused']/$row2['diskQuota'])*100,0);

      return $sizeArray;
  }

  function CreateMemberInDB($ownerId,$ownerEmail,$ownerName) {

      $mmc_db_config = new MMC_DB_Config();

      $tempDate = date("Y-m-d H:i:s", strtotime('now'));
      $ownerName = mb_convert_encoding($ownerName,"UTF-8","big5");

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
        die( "資料庫連結錯誤!!" );

      $query = "select * from  Member where memberId = '".$ownerId."'";

      // 判斷此人(只有老師身分才可以開會議)是否已在MMC資料庫內
      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {
          if(mysql_num_rows($queryObj) == 0){
              $query = "insert into Member(`memberId`,`email_2`,`nativeName`,`role`,`emailPrivacy`,`leaveMessagePrivacy`,`onlinePrivacy`,`maxGuest`,`diskQuota`,`messageLimit`,
                        `createdDate`) values ($ownerId,'$ownerEmail','$ownerName',1,0,0,0,100,100000,180,'$tempDate')";

              if($queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query))
                  ;
              else {
                  echo "SQL error!!(Member II)";
                  die;
              }
          }
          else
              ;
      }
      else {
        echo "Error!!";
        die;
      }
  }

  function GetDiskQuota($ownerId) {
      $mmc_db_config = new MMC_DB_Config();

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
        die( "資料庫連結錯誤!!" );

      $query = "select * from  Member where memberId = '".$ownerId."'";
      $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query);
      $row = mysql_fetch_assoc($queryObj) ;
      return $row['diskQuota'];
  }
  
  function IsMember($email) {
      $mmc_db_config = new MMC_DB_Config();

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
        die( "資料庫連結錯誤!!" );

      $query = "select * from  Member where email = '".$email."'";
      if ($queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {
      	if(mysql_num_rows($queryObj) == 0){
		return false;
        }
        else 
		return true;
      }
      else
        return false;
  }
  function AllThisYearMeeting($ownerId) {
      $mmc_db_config = new MMC_DB_Config();

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
        die( "資料庫連結錯誤!!" );

      // 不能單單只用course ID，不然平台不同會抓錯，可能在用老師ID(或在加平台碼)
      $query = "select *  from  Meeting  where coordinatorId = '{$ownerId}' AND meetingType = 0 AND actualEndTime IS NULL order by startTime asc";

      $allTodayMeeting = Array();

      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {
          if(mysql_num_rows($queryObj) != 0){
              // 要回圈判斷全部，因為有可能老師預約了但並沒有上課
              while ( $row = mysql_fetch_assoc($queryObj) ) {

                // 找出所有未來的meeting
                if( (strtotime($row['endTime']) > time()) && (strtotime($row['endTime']) < time()+31536000) ) {

                  // 在這邊處理完，是否開準備還是直接進入

                        /*
                        $query = "select *  from begin_course where begin_course_cd = '{$row['courseId']}'"; // 只會有一筆，所以之後的資料都讀 index(0)即可

                        $query_Result = db_getAll($query);

                        $tempCourse_cd = $query_Result[0]["course_cd"]; //因為只有一筆所以只讀0
                        $row['courseName'] = $query_Result[0]["begin_course_name"];
                        */
                        $row['courseName'] = db_getCourseName($row['courseId']);
                        /*
                        $query = "select *  from course_basic where course_cd = '{$tempCourse_cd}'"; // 只會有一筆，所以之後的資料都讀 index(0)即可

                        $query_Result = db_getAll($query);

                        $tempTeacher_cd = $query_Result[0]["teacher_cd"]; //因為只有一筆所以只讀0

                        $query = "select *  from personal_basic where  personal_id  = '{$tempTeacher_cd}'"; // 只會有一筆，所以之後的資料都讀 index(0)即可

                        $query_Result = db_getAll($query);
                        */
                        // 老師資訊
                        //$row['teacherName'] = $query_Result[0]["personal_name"]; //因為只有一筆所以只讀0
                        $row['teacherName'] = db_getPersonalName();
                      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
                         die( "資料庫連結錯誤!!" );


                        // 準備/取消會議網址 (每個meeting id不一樣所以網址會不同)
                        $row['preparaMeeting'] = "preparaMeeting.php?mid=".$row['meetingId'];
                        $row['cancelMeeting'] = "cancelMeeting.php?mid=".$row['meetingId'];
                        $row['title'] = mb_convert_encoding($row['title'],"big5","UTF-8");
                        $allTodayMeeting[]= $row;  // add資訊到此陣列以便之後show出來
                } // if today meeting end
                else
                    ;

              } // while wnd
          } // if end
          else
            ;

          return $allTodayMeeting ;
      }
      else
            echo "SQL error!!";

  }

  function  AllWeekReservationMeeting($year,$month,$day,$yearE,$monthE,$dayE) {

      /*
      $DB_SERVER='140.123.23.78';
      $DB_LOGIN='cyberccu2Test';
      $DB_PASSWORD2='mmc315@cyberccu2';
      $DB='mmcDb';
      */

      $mmc_db_config = new MMC_DB_Config();

      $startTime = $year."-".$month."-".$day." 00:00:00";
      $endTime = $yearE."-".$monthE."-".$dayE." 23:59:59";
      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
        die( "資料庫連結錯誤!!" );

      // 不能單單只用course ID，不然平台不同會抓錯，可能在用老師ID(或在加平台碼)
      $query = "select *  from  Meeting  where meetingType = 0 AND (startTime between '{$startTime}' AND '{$endTime}' ) order by startTime asc";

      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {
          if(mysql_num_rows($queryObj) != 0){
              // 要回圈判斷全部，因為有可能老師預約了但並沒有上課
              while ( $row = mysql_fetch_assoc($queryObj) ) {

                        $tempindex = floor(((strtotime($row['startTime'])-strtotime($startTime))/1800));
                        $tempduration = floor(((strtotime($row['endTime'])-strtotime($startTime)-1)/1800)) - $tempindex;

                        for($i=0;$i <= $tempduration ; $i++) {
                            if(is_null( $list[$tempindex]))
                                $list[$tempindex] = $row['maxNumAttendee'];
                            else
                                $list[$tempindex] += $row['maxNumAttendee'];
                            $tempindex++;
                        }

              } // while wnd
          } // if end
          else
            ;
          return $list ;
      }
      else
            echo "SQL error!!";

  }

  function  AllSelectMeeting($year,$month,$day,$yearE,$monthE,$dayE,$Selectindex) {

      /*
      $DB_SERVER='140.123.23.78';
      $DB_LOGIN='cyberccu2Test';
      $DB_PASSWORD2='mmc315@cyberccu2';
      $DB='mmcDb';
      */

      $mmc_db_config = new MMC_DB_Config();

      $startTime = $year."-".$month."-".$day." 00:00:00";
      $endTime = $yearE."-".$monthE."-".$dayE." 23:59:59";
      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
        die( "資料庫連結錯誤!!" );

      // 不能單單只用course ID，不然平台不同會抓錯，可能在用老師ID(或在加平台碼)
      $query = "select *  from  Meeting  where meetingType = 0 AND (startTime between '{$startTime}' AND '{$endTime}' ) order by startTime asc";

      $allMeeting =array();
      $tempArrayIndex = 1 ;
      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {
          if(mysql_num_rows($queryObj) != 0){
              // 要回圈判斷全部，因為有可能老師預約了但並沒有上課
              while ( $row = mysql_fetch_assoc($queryObj) ) {

                        $tempindex = floor(((strtotime($row['startTime'])-strtotime($startTime))/1800));
                        $tempduration = floor(((strtotime($row['endTime'])-strtotime($startTime)-1)/1800)) - $tempindex;

                        for($i=0;$i <= $tempduration ; $i++) {
                            if($Selectindex == $tempindex) {
                                // 符合條件
                                $list = array();
                                $list['title'] = $row['title'];
                                $list['title'] = mb_convert_encoding($list['title'],"big5","UTF-8");
                                $list['reservationTime'] = $row['startTime']."  -  ".$row['endTime'];
                                $list['maxNumAttendee'] = $row['maxNumAttendee'] ;

                                $query = "select nativeName from Member where memberId = {$row['coordinatorId']}";
                                $queryObj2 = mysql_db_query( $mmc_db_config->Mmc_DB, $query);
                                $row2 = mysql_fetch_assoc($queryObj2);
                                $list['nativeName'] = $row2['nativeName'];
                                $list['nativeName'] = mb_convert_encoding($list['nativeName'],"big5","UTF-8");
                                $list['end'] = 0 ;
                                if(!is_null($row['actualEndTime']))
                                    $list['end'] = 1;
                                $list['index_num'] = $tempArrayIndex;
                                $allMeeting[] = $list;
                                $tempArrayIndex++;
                                $i=$tempduration;
                            }
                            $tempindex++;
                        }

              } // while wnd
          } // if end
          else
            ;
          return $allMeeting ;
      }
      else
            echo "SQL error!!";

  }

  function  AllSelectDayMeeting($year,$month,$day) {

      /*
      $DB_SERVER='140.123.23.78';
      $DB_LOGIN='cyberccu2Test';
      $DB_PASSWORD2='mmc315@cyberccu2';
      $DB='mmcDb';
      */

      $mmc_db_config = new MMC_DB_Config();

      $startTime = $year."-".$month."-".$day." 00:00:00";
      $endTime = $year."-".$month."-".$day." 23:59:59";
      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
        die( "資料庫連結錯誤!!" );

      // 不能單單只用course ID，不然平台不同會抓錯，可能在用老師ID(或在加平台碼)
      $query = "select *  from  Meeting  where meetingType = 0 AND (startTime between '{$startTime}' AND '{$endTime}' ) order by startTime asc";

      $allMeeting =array();
      $tempArrayIndex = 1 ;
      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {
          if(mysql_num_rows($queryObj) != 0){
              // 要回圈判斷全部，因為有可能老師預約了但並沒有上課
              while ( $row = mysql_fetch_assoc($queryObj) ) {

                                // 符合條件
                                $list = array();
                                $list['title'] = $row['title'];
                                $list['title'] = mb_convert_encoding($list['title'],"big5","UTF-8");
                                $list['reservationTime'] = $row['startTime']."  -  ".$row['endTime'];
                                $list['maxNumAttendee'] = $row['maxNumAttendee'] ;

                                $query = "select nativeName from Member where memberId = {$row['coordinatorId']}";
                                $queryObj2 = mysql_db_query( $mmc_db_config->Mmc_DB, $query);
                                $row2 = mysql_fetch_assoc($queryObj2);
                                $list['nativeName'] = $row2['nativeName'];
                                $list['nativeName'] = mb_convert_encoding($list['nativeName'],"big5","UTF-8");
                                $list['end'] = 0 ;
                                if(!is_null($row['actualEndTime']))
                                    $list['end'] = 1;
                                $list['index_num'] = $tempArrayIndex;
                                $allMeeting[] = $list;
                                $tempArrayIndex++;

              } // while wnd
          } // if end
          else
            ;
          return $allMeeting ;
      }
      else
            echo "SQL error!!";

  }

  
?>


