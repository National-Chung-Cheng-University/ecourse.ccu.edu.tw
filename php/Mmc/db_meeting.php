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


  // ��function���}�ҧY�ɷ|ĳ�e���N�|ĳ��T�g�JDB
  function InsertInstanceMeetingToDB($jnjData) {
      /*
      $DB_SERVER='140.123.23.78';
      $DB_LOGIN='cyberccu2Test';
      $DB_PASSWORD2='mmc315@cyberccu2';
      $DB='mmcDb';  
      */
      $mmc_db_config = new MMC_DB_Config();
      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
          die( "��Ʈw�s�����~!!" );
      

      // �n��timestamp���p�⮳��instance
      $jnjData->timestamp = strtotime('now');
      
      // �becourse���|�ۤv���ܭ� �ҥH�n���O�U�� �A��function�^��
      $returnRow['timestamp'] = $jnjData->timestamp ;

      $tempDate = date("Y-m-d H:i:s", $jnjData->timestamp);
            
      $query = "select * from  Member where memberId = '".$jnjData->ownerId."'";

      // �P�_���H(�u���Ѯv�����~�i�H�}�|ĳ)�O�_�w�bMMC��Ʈw��
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

      // �P�_���S������|ĳ�A�����ܭn��X���v�ɪ�ID
      if($jnjData->continueType == 0)
         $recordingId = 0 ; 
      else
         $recordingId =  SearchingRecordingId($jnjData->continueRecordingId);


      // �|ĳ�������]�w�@�P�s��������S���]�w
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

      // �N�|ĳ��T�g�J��Ʈw
      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {
          $jnjData->meetingId = mysql_insert_id();
           $returnRow['meetingId'] = $jnjData->meetingId ;
      }
      else
          echo "SQL error!!";
      return $returnRow;
      
  }

  // ��function���w���|ĳ�e���N�|ĳ��T�g�JDB
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
          die( "��Ʈw�s�����~!!" );
      
      // �p�G��Ƥ��bMMC��Ʈw���A�ۭq�[�Jmember��MMC��Ʈw 
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
      // �N�}�l�ɶ��ഫ��Date�榡
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
          // �]�����ΰ��W�i�J�|ĳ�A�ҥH���Χ�Xmeeting id
          // substr($row["meetingId"],0,1)."<br>";  // �o�ӥi�H�ΨӧP�_ ID�i�ӧP�_�q���̨Ӫ� �A�n�����Φh�[���
          // $jnjData->meetingId = mysql_insert_id();
      }
      else
          echo "SQL error!!";
      
    
  }

  // �N�d������Ƽg�J��Ʈw
  function InsertLevaeMsgToDB(&$jnjData) {
      /*
      $DB_SERVER='140.123.23.78';
      $DB_LOGIN='cyberccu2Test';
      $DB_PASSWORD2='mmc315@cyberccu2';
      $DB='mmcDb';

      if ( !( $link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD2)) )
          die( "��Ʈw�s�����~!!" );
      */
      $mmc_db_config = new MMC_DB_Config();

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
          die( "��Ʈw�s�����~!!" );

      // �n��timestamp���p�⮳��instance
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

      // �������]�w�@�P�s��������S���]�w
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
          // substr($row["meetingId"],0,1)."<br>";  // �o�ӥi�H�ΨӧP�_ ID�i�ӧP�_�q���̨Ӫ� �A�n�����Φh�[���
          $jnjData->meetingId = mysql_insert_id();
      }
      else
          echo "SQL error!!";

  }


  // ��X�ϥΪ̪��Ҧ��|ĳ
  function SearchingMeeting($ownerId) {
      /*
      $DB_SERVER='140.123.23.78';
      $DB_LOGIN='cyberccu2Test';
      $DB_PASSWORD2='mmc315@cyberccu2';
      $DB='mmcDb';
      */
      $mmc_db_config = new MMC_DB_Config();

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
          die( "��Ʈw�s�����~!!" );

      $query = "select *  from  Recording  where coordinatorId = '{$ownerId}' order by actualStartTime desc";
      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {
   
          return($queryObj);
      }
      else
          echo "SQL error!!";
      
  }

  // �ھ�meeting��X�O���ɪ��W�r�A�q�`�O������|ĳ�Ϊ��A�R�����v�ɮɤ]�ݭn���v�ɪ��W�r
  function SearchingRecordingFileName($meetingId) {
      //$DB_SERVER='140.123.23.78';
      //$DB_LOGIN='cyberccu2Test';
      //$DB_PASSWORD2='mmc315@cyberccu2';
      //$DB='mmcDb';

      $mmc_db_config = new MMC_DB_Config();

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
          die( "��Ʈw�s�����~!!" );

      $query = "select *  from  Recording  where meetingId = '{$meetingId}' ";
      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {
          $row = mysql_fetch_assoc($queryObj);
          return($row['recordingFile']);
      }
      else
          echo "SQL error!!";

  }

  // �ھ�meeting��X�O���ɪ�id�A�q�`�O������|ĳ�Ϊ�
  function SearchingRecordingId($meetingId) {
      //$DB_SERVER='140.123.23.78';
      //$DB_LOGIN='cyberccu2Test';
      //$DB_PASSWORD2='mmc315@cyberccu2';
      //$DB='mmcDb';
      
      $mmc_db_config = new MMC_DB_Config();

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
          die( "��Ʈw�s�����~!!" );

      $query = "select *  from  Recording  where meetingId = '{$meetingId}' ";
      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {
          $row = mysql_fetch_assoc($queryObj);
          return($row['recordingId']);
      }
      else
          echo "SQL error!!";

  }

  // �ھڰO���ɪ�id�A��X�ӷ|ĳ��meeting Id
  function SearchingRecordingMeetingId($recordingId) {
      //$DB_SERVER='140.123.23.78';
      //$DB_LOGIN='cyberccu2Test';
      //$DB_PASSWORD2='mmc315@cyberccu2';
      //$DB='mmcDb';

      $mmc_db_config = new MMC_DB_Config();

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
          die( "��Ʈw�s�����~!!" );

      $query = "select *  from  Recording  where recordingId = '{$recordingId}' ";
      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {
          $row = mysql_fetch_assoc($queryObj);
          return($row['meetingId']);
      }
      else
          echo "SQL error!!";

  }

  // �d�ݷ|ĳ�Ƥ��O���b�i��A�p�G�O�|��Meeting ID��bjnjData->meetingId
  function SearchOnlineMeeting($courseId,$site) {
      //$DB_SERVER='140.123.23.78';
      //$DB_LOGIN='cyberccu2Test';
      //$DB_PASSWORD2='mmc315@cyberccu2';
      //$DB='mmcDb';
      
      $mmc_db_config = new MMC_DB_Config();
      $mmc_path_config = new MMC_Path_Config();


      $tempCourseName = db_getCourseName($courseId);


      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
        die( "��Ʈw�s�����~!!" );

 
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
      // ��X���b�i�檺�|ĳ�A�ھڽҰ��D���H���P�_�A����O�Ҫ��ǥͶi�J�줣�P���Ұ�Ameeting type 32�N��d���Ҧ��A�ݹL�o��
      // $query = "select *  from  Meeting  where courseId = '{$jnjData->courseId}' AND coordinatorId = '{$jnjData->ownerId}' AND actualStartTime IS NOT NULL AND actualEndTime IS NULL AND meetingType != 32";
      echo "<meta http-equiv='refresh' content='0;url=link_MMC.php?mid={$temp_meetingId}&fn={$temp_site}&op=4'>";	  
  }

  function GetOnlineMeetingInfo($jnjData,$temp_meetingId) {

      $mmc_db_config = new MMC_DB_Config();


      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
          die( "��Ʈw�s�����~!!" );


      $query = "select *  from  Meeting  where meetingId = '{$temp_meetingId}'";
       
      if ( $queryObj = mysql_db_query($mmc_db_config->Mmc_DB, $query)) {
          if(mysql_num_rows($queryObj) != 0){
              $row = mysql_fetch_assoc($queryObj);
              $jnjData->ownerId = $row['coordinatorId'];
              $jnjData->meetingId = $row['meetingId'];
              $jnjData->meetingTitle = $row['title'];
              $jnjData->recording = $row['recording'];

              // �n��duration
              $jnjData->duration = ((int) (strtotime($row['endTime']) - strtotime($row['startTime']))/60);
              
              /*
              if ($row['meetingType'] == 16) { //�Y�ɷ|ĳ
                  $jnjData->duration = 0 ;
              }
              else // �i���ٷ|�[��L�P�_�A�ثe�u����Y�ɷ|ĳ
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
                  // �ϬdmeetingId
              }
              else
                  $jnjData->continueType = 0 ;

              // ��Ѯv�W�r��email
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
        die( "��Ʈw�s�����~!!" );

      // ������u��course ID�A���M���x���P�|����A�b�ΦѮvID�P�_
      $query = "select *  from  Meeting  where courseId = '{$jnjData->courseId}' AND coordinatorId = '{$jnjData->ownerId}' AND meetingType = 0 AND actualEndTime IS NULL order by startTime asc";

      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {
          if(mysql_num_rows($queryObj) != 0){
              // �n�^��P�_�����A�]�����i��Ѯv�w���F���èS���W��
              while ( $row = mysql_fetch_assoc($queryObj) ) {

                // �P�_�O���O���Ѫ�meeting
                if( (date('Ymd',strtotime($row['startTime'])) == date('Ymd')) && (strtotime(($row['startTime'])) > (time()-10800)) 
                    && (strtotime($row['endTime']) > time()) ) {
                  // �O���Ѫ��ܱNmeeting��T�O���n�A�H�K����p�G�O�n�i�J�w���|ĳ�i�H�����ϥ�
                  $jnjData->ownerId = $row['coordinatorId'];
                  $jnjData->meetingId = $row['meetingId'];
                  $jnjData->meetingTitle = $row['title'];
                  $jnjData->recording = $row['recording'];

              
                  // �n��duration
                  $jnjData->duration = ((int) (strtotime($row['endTime']) - strtotime($row['startTime']))/60);

                  // �n��Xmaxacount
                  $jnjData->numofConnection = $row['maxNumAttendee'] ;

                  if($row['jointBrowsingUrl'] != NULL )
                      $jnjData->jointBrowsingUrl = $row['jointBrowsingUrl'];
                  else
                      ;
                  if($row['resumeRecordingId'] != 0 ) {
                      $jnjData->continueType = 1 ;
                      $jnjData->continueRecordingId = SearchingRecordingMeetingId($row['resumeRecordingId']);
                      // �ϬdmeetingId
                  }
                  else
                      $jnjData->continueType = 0 ;

                  // �b�o��B�z���A�O�_�}�ǳ��٬O�����i�J
                  // �]����startTime�ƧǹL�A�ҥH�Ĥ@�Ӧp�G�٦b15�����e�A��L���@�w�W�L15����
                  if ((strtotime($row['startTime']) - time()) >900) { //�i�J�ǳ�
                      return "prepare";
                  }
                  else //�|ĳ�}�l�F�A�����i�J�|ĳ
                      return "enter";
                } // if today meeting end
                else
                    ;

              } // while wnd
              // �p�G�j�餧�����Sreturn�A�N����Ʀ��S�����Ѫ��|ĳ�A�ҥH��������instance
              return "instance";
          } // if end
          else //�S������@�ӹw���|ĳ�ݭn�}�l�A�N��Ҧ����w���|ĳ�X�����F
            return "instance";
      }
      else
            echo "SQL error!!";

  }

  // �p�G���Ѧ��w���|ĳ�A���٨S��}�l�ɶ��i���Ѯv�ݤ��Ѧ����Ƿ|ĳ�A�H�����Ѯv�i�H������i�J�ǳƼҦ�
  // �Ѽ�courseId:�ҵ{��id
  //     ownerId:�ϥΪ̪�id
  //     courseName:�ҵ{�W��
  //     teacherName:�Ѯv�m�W
  // �^�ǩҦ����Ѫ�meeting��T�A��T���ҵ{�W�١B�Ѯv�W�r�B�����P�ǳƪ����}(�|�ھ�meetingId)
  function AllTodayMeeting($courseId,$ownerId,$courseName,$teacherName) {
      
      /*
      $DB_SERVER='140.123.23.78';
      $DB_LOGIN='cyberccu2Test';
      $DB_PASSWORD2='mmc315@cyberccu2';
      $DB='mmcDb';
      */

      $mmc_db_config = new MMC_DB_Config();

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
        die( "��Ʈw�s�����~!!" );

      // ������u��course ID�A���M���x���P�|����A�i��b�ΦѮvID(�Φb�[���x�X)
      $query = "select *  from  Meeting  where courseId = '{$courseId}' AND coordinatorId = '{$ownerId}' AND meetingType = 0 AND actualEndTime IS NULL order by startTime asc";

      $allTodayMeeting = Array();

      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {
          if(mysql_num_rows($queryObj) != 0){
              // �n�^��P�_�����A�]�����i��Ѯv�w���F���èS���W��
              while ( $row = mysql_fetch_assoc($queryObj) ) {

                // �P�_�O���O���Ѫ�meeting
                if( (date('Ymd',strtotime($row['startTime'])) == date('Ymd')) && (strtotime(($row['startTime'])) > (time()-10800))
                      && (strtotime($row['endTime']) > time()) ) {

                  // �b�o��B�z���A�O�_�}�ǳ��٬O�����i�J
                  if ((strtotime($row['startTime']) - time()) >900) { //�nshow��meeting�o���O�����٨S�쪺�A�i�H���Ѯv���ǳƩνs��

                        /*
                        $query = "select *  from begin_course where begin_course_cd = '{$row['courseId']}'"; // �u�|���@���A�ҥH���᪺��Ƴ�Ū index(0)�Y�i

                        $query_Result = db_getAll($query);

                        $tempCourse_cd = $query_Result[0]["course_cd"]; //�]���u���@���ҥH�uŪ0
                        $row['courseName'] = $query_Result[0]["begin_course_name"];
                        */
                        $row['courseName'] = $courseName;
                        /*
                        $query = "select *  from course_basic where course_cd = '{$tempCourse_cd}'"; // �u�|���@���A�ҥH���᪺��Ƴ�Ū index(0)�Y�i

                        $query_Result = db_getAll($query);

                        $tempTeacher_cd = $query_Result[0]["teacher_cd"]; //�]���u���@���ҥH�uŪ0

                        $query = "select *  from personal_basic where  personal_id  = '{$tempTeacher_cd}'"; // �u�|���@���A�ҥH���᪺��Ƴ�Ū index(0)�Y�i

                        $query_Result = db_getAll($query);
                        */
                        // �Ѯv��T
                        //$row['teacherName'] = $query_Result[0]["personal_name"]; //�]���u���@���ҥH�uŪ0
                        $row['teacherName'] = $teacherName;

                        // �ǳ�/�����|ĳ���} (�C��meeting id���@�˩ҥH���}�|���P)
                        $row['preparaMeeting'] = "preparaMeeting.php?mid=".$row['meetingId']; 
                        $row['cancelMeeting'] = "cancelMeeting.php?mid=".$row['meetingId'];
                        $row['title'] = mb_convert_encoding($row['title'],"big5","UTF-8"); 
                        $allTodayMeeting[]= $row;  // add��T�즹�}�C�H�K����show�X��
                  }
                  else //�|ĳ�}�l�F�A�����i�J�|ĳ
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

  // �ǥ�Meeting Id��jnjData����L��Ƴ]�w�n
  function SearchMeetingByMeetingId(&$jnjData) {
      /*
      $DB_SERVER='140.123.23.78';
      $DB_LOGIN='cyberccu2Test';
      $DB_PASSWORD2='mmc315@cyberccu2';
      $DB='mmcDb';
      */
      $mmc_db_config = new MMC_DB_Config();

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
        die( "��Ʈw�s�����~!!" );

      $query = "select *  from  Meeting  where meetingId = '{$jnjData->meetingId}'";

      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {
          if(mysql_num_rows($queryObj) != 0){
              $row = mysql_fetch_assoc($queryObj);
              $jnjData->ownerId = $row['coordinatorId'];
              $jnjData->meetingId = $row['meetingId'];
              $jnjData->meetingTitle = $row['title'];
              $jnjData->recording = $row['recording'];

              // �n��duration
              $jnjData->duration = ((int) (strtotime($row['endTime']) - strtotime($row['startTime']))/60);

              // �n��Xmaxacount
              $jnjData->numofConnection = $row['maxNumAttendee'] ;

              if($row['jointBrowsingUrl'] != NULL )
                  $jnjData->jointBrowsingUrl = $row['jointBrowsingUrl'];
              else
                  ;
              if($row['resumeRecordingId'] != 0 ) {
                  $jnjData->continueType = 1 ;
                  $jnjData->continueRecordingId = SearchingRecordingMeetingId($row['resumeRecordingId']);
                  // �ϬdmeetingId
              }
              else
                   $jnjData->continueType = 0 ;

          }
          else
            ;
          // ���o�Ѯv�W�٥H��email
          $query = "select * from  Member where memberId = '{$row['coordinatorId']}'";
          $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query);
          $row2 = mysql_fetch_assoc($queryObj);

	  $jnjData->ownerName = $row2['nativeName'];
	  $jnjData->ownerEmail = $row2['email_2'];

      }
      else
            echo "SQL error!!";

  }

  //�ھ�MeetingId�A��|ĳ�R��
  function DeleteMeetingByMeetingId($meetingId) {
      //$DB_SERVER='140.123.23.78';
      //$DB_LOGIN='cyberccu2Test';
      //$DB_PASSWORD2='mmc315@cyberccu2';
      //$DB='mmcDb';

      $mmc_db_config = new MMC_DB_Config();
      
      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
        die( "��Ʈw�s�����~!!" );

      $query = "delete from  Meeting  where meetingId = '{$meetingId}'";

      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {
            echo "�|ĳ��������";
      }
      else
            echo "SQL error!!";

  }

  // �ھڸ�Ƨ�id�P�ϥΪ̧�X���������v��
  function searchRecording($ownerId,$folderId,$seq) {
      /*
      $DB_SERVER='140.123.23.78';
      $DB_LOGIN='cyberccu2Test';
      $DB_PASSWORD2='mmc315@cyberccu2';
      $DB='mmcDb';

      if ( !( $link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD2)) )
        die( "��Ʈw�s�����~!!" );
      */
      $mmc_db_config = new MMC_DB_Config();
      $mmc_path_config = new MMC_Path_Config();

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
        die( "��Ʈw�s�����~!!" );

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
              // �n�^��P�_�����A���������v�ɳ��n��i��

              $index = 0 ;
              while ( $row = mysql_fetch_assoc($queryObj) ) {

                if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
                   die( "��Ʈw�s�����~!!" );

                        $index = $index + 1 ;
                        $result = "";
                        
                        // ����X�o�ӷ|ĳ��courseId�~�i�H�άd�ҦW
                        
                        $query = "select *  from  Meeting  where meetingId = '{$row['meetingId']}' ";
                        $queryObj2 = mysql_db_query( $mmc_db_config->Mmc_DB, $query);
                        $row2 = mysql_fetch_assoc($queryObj2);

                        // �ǥѽҵ{id��X�Ѯv���W�r�Pemail
                        // �U���o�ӬO���x����Ʈw
                        /* 
                        $query = "select *  from begin_course where begin_course_cd = '{$row2['courseId']}'"; // �u�|���@���A�ҥH���᪺��Ƴ�Ū index(0)�Y�i

                        $query_Result = db_getAll($query);
 
                        $tempCourse_cd = $query_Result[0]["course_cd"]; //�]���u���@���ҥH�uŪ0
                         
                        $row['courseName'] = $query_Result[0]["begin_course_name"];
                        */
                        if(!is_null($row2['courseId']))
                          $row['courseName'] = db_getCourseName($row2['courseId']);

                        $recordingHours = intval(($row['actualDuration']/3600));
                        $recordingMinutes = intval(($row['actualDuration']%3600)/60);
                        $recordingSeconds = intval($row['actualDuration']%60);

                        if ($recordingHours != 0)
                           $result = "{$recordingHours} �p�� ";
                        if($recordingMinutes != 0)
                           $result .= "{$recordingMinutes} �� ";

                        $result .="{$recordingSeconds} ��" ;

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

                        $allRecording[]= $row;  // add��T�즹�}�C�H�K����show�X��


                  }
                } 
                else
                    ;

          return $allRecording ;
      }
      else
            echo "SQL error!!";

  }

  //�o�G�|ĳ�A�N��Ƽg����Ʈw
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
        die( "��Ʈw�s�����~!!" );

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
              
              // �o�Ӹ�Ʈw�����x���ëDMMC��
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
        die( "��Ʈw�s�����~!!" );

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
              //�o�Ӹ�Ʈw���bMMC�W�A�O���x���Ӫ���Ʈw�A�R���P�B������T
              $query = "delete from on_line where rfile = '{$pubUrl}'";
              db_query($query);
              */

      }
      else
            echo "SQL error!!";

  }

  // �ǥ�recordingId�A���o���v�ɸ�T�A�åB�N��T�^�� 
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
        die( "��Ʈw�s�����~!!" );

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
              $result = "{$recordingHours} �p�� ";
          if($recordingMinutes != 0)
              $result .= "{$recordingMinutes} �� ";

          $result .="{$recordingSeconds} ��" ;
        
          $row['durationString'] = $result ;

          $row['title'] = mb_convert_encoding($row['title'],"big5","UTF-8");
          $row['coordinatorName'] = mb_convert_encoding($row['coordinatorName'],"big5","UTF-8"); //�]���u���@��
	  
          //$recordingInfo[]= $row;
          return $row;       

      }
      else
            echo "SQL error!!";
  }

  // ���o���v�ɪ���T(��h�W�u���Ѯv�ݪ���)
  // �Ѽ�recordingId:���v�ɪ�id
  //     course_Name:�ҵ{���W��
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
        die( "��Ʈw�s�����~!!" );


      $query = "select * from Recording where recordingId = '{$recordingId}'";
      $queryObj2 = mysql_db_query( $mmc_db_config->Mmc_DB, $query);
      $row = mysql_fetch_assoc($queryObj2);

      // [jfish] �o�䪺���}�A���ӭn���@�ǳB�z
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
           $result = "{$recordingHours} �p�� ";
      if($recordingMinutes != 0)
           $result .= "{$recordingMinutes} �� ";

      $result .="{$recordingSeconds} ��" ;

      $row['durationString'] = $result ;


      // ���o�Ѯv�W�٥H��email
      $query = "select * from  Member where memberId = '{$row['coordinatorId']}'";
      $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query);
      $row2 = mysql_fetch_assoc($queryObj);

      // ��X�ҦW
      $query = "select * from Meeting where meetingId = '{$row['meetingId']}'";
      $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query);
      $row3 = mysql_fetch_assoc($queryObj);
      
      $row['title'] = mb_convert_encoding($row['title'],"big5","UTF-8");
      $row['coordinatorName'] = mb_convert_encoding($row2['nativeName'],"big5","UTF-8"); //�]���u���@��
      $row['coordinatorEmail'] = $row2['email_2']; //�]���u���@��
      if(!empty($row3['courseId']))
      	$row['courseName'] = db_getCourseName($row3['courseId']);
      else
	;


      return $row;

  }

  // �ǥ�Meeting����v�ɪ���T���X
  function GetPlaymodeInfoByMeetingId(&$jnjData) {
      // $DB_SERVER='140.123.23.78';
      // $DB_LOGIN='cyberccu2Test';
      // $DB_PASSWORD2='mmc315@cyberccu2';
      // $DB='mmcDb';

      $mmc_db_config = new MMC_DB_Config();

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
        die( "��Ʈw�s�����~!!" );

      $query = "select *  from  Meeting  where meetingId = '{$jnjData->meetingId}'";

      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {
          if(mysql_num_rows($queryObj) != 0){
              $row = mysql_fetch_assoc($queryObj);
              $jnjData->ownerId = $row['coordinatorId'];

              // �n��duration
              $jnjData->duration = ((int) (strtotime($row['endTime']) - strtotime($row['startTime']))/60);
              
              // �n��Xmaxacount
              $jnjData->numofConnection = $row['maxNumAttendee'] ;

              // �n�ոլݦ��S���t
              if($row['jointBrowsingUrl'] != NULL )
                  $jnjData->jointBrowsingUrl = $row['jointBrowsingUrl'];
              else
                  ;

              // ��X���v���ɦW
              $query = "select *  from  Recording  where meetingId = '{$jnjData->meetingId}'";
              $queryObj2 = mysql_db_query( $mmc_db_config->Mmc_DB, $query);
              $row2 = mysql_fetch_assoc($queryObj2);

              // [jfish] �ѰO�o�O��ԣ�Ϊ� �n�b�T�{�@�U
              $jnjData->continueRecordingId = $row2['recordingId'];
	      
              $query = "select *  from  Member  where memberId = '{$jnjData->ownerId}'";
              $queryObj2 = mysql_db_query( $mmc_db_config->Mmc_DB, $query);
              $row2 = mysql_fetch_assoc($queryObj2);
              
	      $jnjData->ownerName = $row2['nativeName'];
	      $jnjData->ownerEmail = $row2['email_2'];
              // �n�ոլݦ��S���t
              /*
              if($row['resumeRecordingId'] != 0 ) {
                  $jnjData->continueType = 1 ;
                  $jnjData->continueRecordingId = SearchingRecordingMeetingId($row['resumeRecordingId']);
                  // �ϬdmeetingId
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

  /* ----�H�U�O�����޲z���v�ɪ���Ƨ��bDB��function----*/

  // �гy�ڥؿ���Ƨ�
  // �@�ӦѮv�Ĥ@���i�ӦbDB�|�S����Ƨ�����T�A�|���гy�@�Ӹ�ؿ�
  // �ǤJ���ѼƬ�owner��Id�ӿ�{�O���ӦѮv
  function CreateRootFolder($ownerId) {
      /*
      $DB_SERVER='140.123.23.78';
      $DB_LOGIN='cyberccu2Test';
      $DB_PASSWORD2='mmc315@cyberccu2';
      $DB='mmcDb';
      */
      $mmc_db_config = new MMC_DB_Config();

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
        die( "��Ʈw�s�����~!!" );

      $query = "select *  from  MyRecordsFolder  where memberId = '{$ownerId}'";
      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {
          // �p�GDB���䤣���Ƨ�����Ƥ~�|�W�[��ؿ���Ƨ�
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

  // ��X�Ѯv�Ҧ�����Ƨ�(�ھ�ownerId)
  // �^��floder��array
  function GetAllFolder($ownerId) {
      /*
      $DB_SERVER='140.123.23.78';
      $DB_LOGIN='cyberccu2Test';
      $DB_PASSWORD2='mmc315@cyberccu2';
      $DB='mmcDb';
      */
      $mmc_db_config = new MMC_DB_Config();

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
        die( "��Ʈw�s�����~!!" );
      
      $query = "select *  from  MyRecordsFolder  where memberId = '{$ownerId}'";
      
      // �Ĥ@����Ƥ@�w�Oroot�ҥH���Ĥ@�������Y�i
      $folderList = Array();
      
      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {
          
          if(mysql_num_rows($queryObj) != 0){
              while ( $row = mysql_fetch_assoc($queryObj) ) {
                    // [jfish] �ѰO�o�O����F��F�A������n�]��0
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

  // �ھ�folder��id��X��folder���W��
  function GetFolderNameByFolderId($folderId) {
      /*
      $DB_SERVER='140.123.23.78';
      $DB_LOGIN='cyberccu2Test';
      $DB_PASSWORD2='mmc315@cyberccu2';
      $DB='mmcDb';
      */
      $mmc_db_config = new MMC_DB_Config();

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
        die( "��Ʈw�s�����~!!" );

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

  // �bDB���s�W�@�Ӹ�Ƨ�
  // �Ѽ�folderName:�s�W����Ƨ��W��
  //     folderId:�W�h��Ƨ���id
  //     ownerId:�ϥΪ̪�id
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
        die( "��Ʈw�s�����~!!" );

      $query = "insert into MyRecordsFolder(`parentId`,`memberId`,`folderCaption`,`sequence`) values ($folderId,$ownerId,'$folderName',1)";

      if($queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query))
          ;
      else
          echo "SQL error!!(Member II)";

  }

  // �bDB���ק��Ƨ��W��
  // �Ѽ�folderName:�n�諸�W��
  //     folderId:�n���W�٪���Ƨ�id
  function RenameFolderInDB($folderName,$folderId) {
      /*
      $DB_SERVER='140.123.23.78';
      $DB_LOGIN='cyberccu2Test';
      $DB_PASSWORD2='mmc315@cyberccu2';
      $DB='mmcDb';
      */
      $mmc_db_config = new MMC_DB_Config();

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
        die( "��Ʈw�s�����~!!" );
      
      $folderName = mb_convert_encoding($folderName,"UTF-8","big5");
      $query = "update MyRecordsFolder set folderCaption = '{$folderName}' where folderId = '{$folderId}'";

      if($queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query))
          ;
      else
          echo "SQL error!!(Member II)";

  }

  // �bDB���R����Ƨ���T
  // �Ѽ�folderId:�n�R������Ƨ�id
  function DeleteFolderInDB($folderId) {
      /*
      $DB_SERVER='140.123.23.78';
      $DB_LOGIN='cyberccu2Test';
      $DB_PASSWORD2='mmc315@cyberccu2';
      $DB='mmcDb';
      */
      $mmc_db_config = new MMC_DB_Config();

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
        die( "��Ʈw�s�����~!!" );
      
      $query = "delete from MyRecordsFolder where folderId = '{$folderId}'";

      if($queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query))
          ;
      else
          echo "SQL error!!(Member II)";
      

  } 

  // insert MyStudentMeetingLog��T
  // �Ѽ�courseId:�ҵ{ID
  //     courseName:�ҵ{�W��
  //     stuId:�ǥ�ID
  function InsertMyStudentMeetingLog($courseId, $courseName, $stuId){
        $mmc_db_config = new MMC_DB_Config();

        if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
          die( "��Ʈw�s�����~!!" );

        $valid_query = "select * from MyStudentMeetingLog where courseId = $courseId and courseName = '$courseName' and stuId = $stuId order by datetime desc limit 1";
        if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB,  $valid_query)) {

          if(mysql_num_rows($queryObj) != 0){
              $row = mysql_fetch_assoc($queryObj); //��@��row
              $lastTime = strtotime($row['datetime']); //�qrow�̭��h��datetime
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


  // ���ϥΪ̪��ڥؿ���id
  // �Ѽ�ownerId:�ϥΪ�id
  function GetRootFolderId($ownerId) {
      /*
      $DB_SERVER='140.123.23.78';
      $DB_LOGIN='cyberccu2Test';
      $DB_PASSWORD2='mmc315@cyberccu2';
      $DB='mmcDb';
      */
      $mmc_db_config = new MMC_DB_Config();

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
        die( "��Ʈw�s�����~!!" );

      $query = "select *  from  MyRecordsFolder  where memberId = '{$ownerId}' AND parentId = 0";

      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {

          if(mysql_num_rows($queryObj) != 0){
              // �u�|���@��
              $row = mysql_fetch_assoc($queryObj);
                return $row['folderId'];
          }
          else
              ;
      }
      else
          echo "SQL error!!(Member)";

  }

  // �N���v�ɱq��Ʋ��ʨ�ڥ�
  // �Ѽ�recordingId:�n�ܰʪ����v��id
  function DeleteRecordingInFloder($recordingId) {
      /*
      $DB_SERVER='140.123.23.78';
      $DB_LOGIN='cyberccu2Test';
      $DB_PASSWORD2='mmc315@cyberccu2';
      $DB='mmcDb';
      */
      $mmc_db_config = new MMC_DB_Config();

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
        die( "��Ʈw�s�����~!!" );

      $query = "delete from MyRecordsInFolder where recordingId = '{$recordingId}'";

      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {
      }
      else
          echo "SQL error!!(Member)";

  }

  // �N���v�ɲ���S�w��Ƨ���(�q�ڥؿ����ʪ����v��)
  // �Ѽ�recordingId:�n�ܰʪ����v��id
  //     folderId:���ʨ��Ƨ�id
  //     ownerId:�ϥΪ̪�id
  function InsertRecordingInFloder($recordingId,$folderId,$ownerId) {
      /*
      $DB_SERVER='140.123.23.78';
      $DB_LOGIN='cyberccu2Test';
      $DB_PASSWORD2='mmc315@cyberccu2';
      $DB='mmcDb';
      */
      $mmc_db_config = new MMC_DB_Config();

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
        die( "��Ʈw�s�����~!!" );

      $query = "insert into MyRecordsInFolder(`recordingId`,`folderId`,`memberId`) values ($recordingId,$folderId,$ownerId)";

      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {
      }
      else
          echo "SQL error!!(Member)";

  }

  // �N���v�ɲ���S�w��Ƨ���(�q�D�ڥؿ����ʪ����v��)
  // �Ѽ�recordingId:�n�ܰʪ����v��id
  //     folderId:���ʨ��Ƨ�id 
  function UpdateRecordingInFloder($recordingId,$folderId) {
      /*
      $DB_SERVER='140.123.23.78';
      $DB_LOGIN='cyberccu2Test';
      $DB_PASSWORD2='mmc315@cyberccu2';
      $DB='mmcDb';
      */
      $mmc_db_config = new MMC_DB_Config();

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
        die( "��Ʈw�s�����~!!" );

      $query = "update MyRecordsInFolder set folderId='{$folderId}' where recordingId = '{$recordingId}'";

      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {
      }
      else
          echo "SQL error!!(Member)";

  }

  /*----�H�W�O�����޲z���v�ɪ���Ƨ��bDB��function----*/

  // [jfish] �n���S���Ψ�??
  function GetRecordingIdByPubRecordingId($pubRecordingId) {
      /*
      $DB_SERVER='140.123.23.78';
      $DB_LOGIN='cyberccu2Test';
      $DB_PASSWORD2='mmc315@cyberccu2';
      $DB='mmcDb';
      */
      $mmc_db_config = new MMC_DB_Config();

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
        die( "��Ʈw�s�����~!!" );


      $query = "select *  from  PubRecording  where pubRecordingId = '{$pubRecordingId}'";

      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {

          if(mysql_num_rows($queryObj) != 0){
              // �u�|���@��
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
        die( "��Ʈw�s�����~!!" );
      */
      $mmc_db_config = new MMC_DB_Config();

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
        die( "��Ʈw�s�����~!!" );

      $query = "select *  from  Meeting  where meetingId = '{$meetingId}'";

      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {

          if(mysql_num_rows($queryObj) != 0){
              // �u�|���@��
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
        die( "��Ʈw�s�����~!!" );

      $query = "insert into MyCourseNameOfMeeting(`meetingId`,`courseName`) values ($meetingId, '$courseName')";
      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) { // sql���O���楿�T
      }
      else
          echo "SQL error!!(Course)";


  }

  function GetTotalRecordingSize($ownerId) {

      $mmc_db_config = new MMC_DB_Config();

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
          die( "��Ʈw�s�����~!!" );
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
        die( "��Ʈw�s�����~!!" );

      $query = "select * from  Member where memberId = '".$ownerId."'";

      // �P�_���H(�u���Ѯv�����~�i�H�}�|ĳ)�O�_�w�bMMC��Ʈw��
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
        die( "��Ʈw�s�����~!!" );

      $query = "select * from  Member where memberId = '".$ownerId."'";
      $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query);
      $row = mysql_fetch_assoc($queryObj) ;
      return $row['diskQuota'];
  }
  
  function IsMember($email) {
      $mmc_db_config = new MMC_DB_Config();

      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
        die( "��Ʈw�s�����~!!" );

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
        die( "��Ʈw�s�����~!!" );

      // ������u��course ID�A���M���x���P�|����A�i��b�ΦѮvID(�Φb�[���x�X)
      $query = "select *  from  Meeting  where coordinatorId = '{$ownerId}' AND meetingType = 0 AND actualEndTime IS NULL order by startTime asc";

      $allTodayMeeting = Array();

      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {
          if(mysql_num_rows($queryObj) != 0){
              // �n�^��P�_�����A�]�����i��Ѯv�w���F���èS���W��
              while ( $row = mysql_fetch_assoc($queryObj) ) {

                // ��X�Ҧ����Ӫ�meeting
                if( (strtotime($row['endTime']) > time()) && (strtotime($row['endTime']) < time()+31536000) ) {

                  // �b�o��B�z���A�O�_�}�ǳ��٬O�����i�J

                        /*
                        $query = "select *  from begin_course where begin_course_cd = '{$row['courseId']}'"; // �u�|���@���A�ҥH���᪺��Ƴ�Ū index(0)�Y�i

                        $query_Result = db_getAll($query);

                        $tempCourse_cd = $query_Result[0]["course_cd"]; //�]���u���@���ҥH�uŪ0
                        $row['courseName'] = $query_Result[0]["begin_course_name"];
                        */
                        $row['courseName'] = db_getCourseName($row['courseId']);
                        /*
                        $query = "select *  from course_basic where course_cd = '{$tempCourse_cd}'"; // �u�|���@���A�ҥH���᪺��Ƴ�Ū index(0)�Y�i

                        $query_Result = db_getAll($query);

                        $tempTeacher_cd = $query_Result[0]["teacher_cd"]; //�]���u���@���ҥH�uŪ0

                        $query = "select *  from personal_basic where  personal_id  = '{$tempTeacher_cd}'"; // �u�|���@���A�ҥH���᪺��Ƴ�Ū index(0)�Y�i

                        $query_Result = db_getAll($query);
                        */
                        // �Ѯv��T
                        //$row['teacherName'] = $query_Result[0]["personal_name"]; //�]���u���@���ҥH�uŪ0
                        $row['teacherName'] = db_getPersonalName();
                      if ( !( $link = mysql_pconnect($mmc_db_config->Mmc_DB_SERVER,$mmc_db_config->Mmc_DB_LOGIN,$mmc_db_config->Mmc_DB_PASSWORD)) )
                         die( "��Ʈw�s�����~!!" );


                        // �ǳ�/�����|ĳ���} (�C��meeting id���@�˩ҥH���}�|���P)
                        $row['preparaMeeting'] = "preparaMeeting.php?mid=".$row['meetingId'];
                        $row['cancelMeeting'] = "cancelMeeting.php?mid=".$row['meetingId'];
                        $row['title'] = mb_convert_encoding($row['title'],"big5","UTF-8");
                        $allTodayMeeting[]= $row;  // add��T�즹�}�C�H�K����show�X��
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
        die( "��Ʈw�s�����~!!" );

      // ������u��course ID�A���M���x���P�|����A�i��b�ΦѮvID(�Φb�[���x�X)
      $query = "select *  from  Meeting  where meetingType = 0 AND (startTime between '{$startTime}' AND '{$endTime}' ) order by startTime asc";

      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {
          if(mysql_num_rows($queryObj) != 0){
              // �n�^��P�_�����A�]�����i��Ѯv�w���F���èS���W��
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
        die( "��Ʈw�s�����~!!" );

      // ������u��course ID�A���M���x���P�|����A�i��b�ΦѮvID(�Φb�[���x�X)
      $query = "select *  from  Meeting  where meetingType = 0 AND (startTime between '{$startTime}' AND '{$endTime}' ) order by startTime asc";

      $allMeeting =array();
      $tempArrayIndex = 1 ;
      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {
          if(mysql_num_rows($queryObj) != 0){
              // �n�^��P�_�����A�]�����i��Ѯv�w���F���èS���W��
              while ( $row = mysql_fetch_assoc($queryObj) ) {

                        $tempindex = floor(((strtotime($row['startTime'])-strtotime($startTime))/1800));
                        $tempduration = floor(((strtotime($row['endTime'])-strtotime($startTime)-1)/1800)) - $tempindex;

                        for($i=0;$i <= $tempduration ; $i++) {
                            if($Selectindex == $tempindex) {
                                // �ŦX����
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
        die( "��Ʈw�s�����~!!" );

      // ������u��course ID�A���M���x���P�|����A�i��b�ΦѮvID(�Φb�[���x�X)
      $query = "select *  from  Meeting  where meetingType = 0 AND (startTime between '{$startTime}' AND '{$endTime}' ) order by startTime asc";

      $allMeeting =array();
      $tempArrayIndex = 1 ;
      if ( $queryObj = mysql_db_query( $mmc_db_config->Mmc_DB, $query)) {
          if(mysql_num_rows($queryObj) != 0){
              // �n�^��P�_�����A�]�����i��Ѯv�w���F���èS���W��
              while ( $row = mysql_fetch_assoc($queryObj) ) {

                                // �ŦX����
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


