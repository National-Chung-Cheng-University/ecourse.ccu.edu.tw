<?php

require 'passwd_encryption.php';
include 'refreshSso.php';
//include 'readssoCcuRightXML.php';

//modify by Autumn
//2003/04/26 PM 07 32
$DB_SERVER = "localhost";  //mysql�D��IP
$DB_LOGIN = "study";            //��Ʈw�b��
$DB_PASSWORD = "study@mysql";         //��Ʈw�b�����K�X
$DB = "study";             //��Ʈw�W��
$DBC = "coop";             //�X�@�ǲ߸�Ʈw�W��
//$GLOBALID = "admin";            //�Φ@�K�X���b��
$GLOBALID = "GRD01";            //�Φ@�K�X���b��
$scorm = 0;                //scorm�ҥ� 0���ҥ� 1�ҥ�
$skinnum = 1;
$SSL = 0;                  //�ҥ� apache��ssl�\�� ��apache�өw
$user_period =0; //�ϥΪ̶פJ�e������O �v�TTSImportInsert1.php �ƭ� 0(semester2) 1(semester1)
$htopic;
$hip;
$huser_id;
$common_id;
$mmc_host="http://mmc.elearning.ccu.edu.tw";
//linsy@20130307, �[�Jmmc_templates
$mmc_templates="/datacenter/htdocs/php/Mmc/templates";
//date_default_timezone_set("Asia/Taipei");

function check_group ( $course_id, $group, $caseid ) {
	global $DB_SERVER, $DB_LOGIN, $DBC, $DB_PASSWORD, $user_id, $teacher;
	$Q1 = "select * from coop_".$caseid."_group where student_id = '$user_id' and group_num = '$group'";
	$Q2 = "select * from coop where a_id = '$caseid'";
	$Q3 = "select * from coop_".$caseid."_group where group_num = '$group' and duty='1'";
	
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		echo( "��Ʈw�s�����~!!" );
		exit;
	}
	else if ( !($result = mysql_db_query( $DBC.$course_id, $Q1 ) ) ) {
		echo( "��ƮwŪ�����~!!$Q1" );
		exit;
	}
	else if ( !($result2 = mysql_db_query( $DBC.$course_id, $Q2 ) ) ) {
		echo( "��ƮwŪ�����~!!$Q2" );
		exit;
	}
	else if ( !($result3 = mysql_db_query( $DBC.$course_id, $Q3 ) ) ) {
		echo( "��ƮwŪ�����~!!$Q3" );
		exit;
	}
	$row2 = mysql_fetch_array ( $result2 );

	if ( mysql_num_rows( $result ) == 0 && $teacher != 1 ) {
		if ( $row2['private'] != 1 ) {
			return 1;
		}else {
			return 0;
		}
	}
	else {
		if ( mysql_num_rows( $result3 ) != 0 ) {
			$row = mysql_fetch_array ( $result );
			if ( $row['duty'] == 1 || $teacher == 1 ) {
				return 2;
			}
			else {
				return 3;
			}
		}
		else {
			return 2;
		}
	}
}

function session_check_admin($PHPSESSID) {

	session_id($PHPSESSID);
	session_start();

	if ( session_is_registered("admin") || $admin == 1 || $admin == 2) {
		return 1;
	}
	else {
		return 0;
	}
}

function show_page ( $page , $error="", $id="", $return="" ) {
	global $PHPSESSID, $SERVER_NAME,$skinnum;
	include_once("class.FastTemplate.php3");
	if ( is_file( "./templates/$page" ) ) {
		$tpl = new FastTemplate ( "./templates" );
	}
	else {
		$tpl = new FastTemplate ( "../templates" );
	}
	$tpl->define ( array ( body => $page) );
	$tpl->assign ( UID, $id );
	$tpl->assign ( MES, $error);
	$tpl->assign ( TITLE , $error );
	$tpl->assign ( RET, $return );
	$tpl->assign ( PHPSD, $PHPSESSID );
	$tpl->assign ( SERVER, $SERVER_NAME );
	$tpl->assign ( SKINNUM, $skinnum );
	$tpl->parse( BODY, "body");
	$tpl->FastPrint("BODY");
	exit;
}

function session_check_stu($PHPSESSID) {

	session_id($PHPSESSID);
	session_start();
	if ( session_is_registered("user_id") && session_is_registered("version")  )
		return 1;
	else
		return 0;
}

function session_check_teach($PHPSESSID) {

	session_id($PHPSESSID);
	session_start();
	global $teacher, $admin, $guest;
	if ( session_is_registered("admin") && session_is_registered("course_id") && $admin == 1 && $guest != 1 ) {
		return 3;
	}
	else if ( session_is_registered("admin") && $admin == 2) {   //�Ω�����ݨ��n�J
		return 2; 
	}
	else if ( session_is_registered("user_id") && session_is_registered("course_id") && session_is_registered("version") && session_is_registered("teacher") && $teacher == 1 && $guest != 1  ) {
		return 2;
	}
	else if ( session_is_registered("user_id") && session_is_registered("course_id") && session_is_registered("version")  ) {
		return 1;
	}
	else {
		return 0;
	}
}

function show_message ( $aid, $admin ) {
	global $DB, $a_id , $id, $message, $multi, $posttime, $system, $close;
	if ( $admin )
		$Q1 = "select m.a_id, u.id, u.nickname, u.name, m.message, m.time, m.close, m.send, m.multi from message m ,user u where m.receive = $aid and m.send = u.a_id";
	else
		$Q1 = "select m.a_id, u.id, u.nickname, u.name, m.message, m.time, m.close, m.send, m.multi from message m ,user u where m.receive = $aid and m.send = u.a_id and u.id != 'admin'";
	if ( $result1 = mysql_db_query( $DB, $Q1 ) ) {
		if ( mysql_num_rows( $result1 ) != 0 ) {
			$row1 = mysql_fetch_array( $result1 );
			$a_id = $row1['a_id'];
			$id = $row1['id'];
			
			$Q2 = "delete from message where a_id = '".$row1['a_id']."'";
			mysql_db_query( $DB, $Q2 );

			if ( $row1['id'] != "admin" ) {
				$friend = 0;
				$Q1 = "select a_id from gbfriend where my_id = '$aid' and friend_id = '".$row1['send']."'";
				if ( $result1 = mysql_db_query( $DB, $Q1) )
					if ( mysql_num_rows( $result1 ) != 0 || $aid == $row1['send'] )
						$friend = 1;
				if ( $row1['multi'] == 1 )
					$multi = 1;
				$system = 0;
			}
			else {
				$system = 1;
				if ( $row1['close'] == 1 )
					$close = 1;
			}


			$posttime = $row1['time'];
			$message = $row1['message'];
			return 1;
		}
		else
			return 0;
	}
}

function add_log ( $event, $user_id, $tag1 = "" , $tag2 = "", $tag3 = "", $tag4 = ""  ) {
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	$Q1 = "select u.a_id FROM user u where u.id = '$user_id'";
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$message = "$message - ��Ʈw�s�����~!!";
	}
	if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
		$message = "$message - ��ƮwŪ�����~1!!";
	}
	else if ( $row1 = mysql_fetch_array( $result ) ) {
		$a_id = $row1['a_id'];
	}
	else {
		$message = "$message - ��ƮwŪ�����~2!!!";
	}
	switch($event) {
		case "1":	// �n�J�t��
			$ip = getenv ( "REMOTE_ADDR" );
			if ( $ip == "" ) {
				$ip = $HTTP_X_FORWARDED_FOR;
			}
			if ( $ip == "" ) {
				$ip = $REMOTE_ADDR;
			}
			$Q2 = "select a_id, mtime ,tag3 from log where user_id = '$a_id' and event_id = '$event'";
			if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) ) {
				return "$message - ��ƮwŪ�����~11!!";
			}
			if ( mysql_num_rows( $result2 ) != 0 ) {
				if ( $row = mysql_fetch_array( $result2 ) ) {
					$Q3 = "update log set tag2 = '". $row['mtime'] ."', tag3 = tag3 + 1, tag1 = '$ip' where a_id = '".$row['a_id']."'";
					
					/*/----
					//edited for joyce @20110110 ���s�@�ӵn�J���x��study_log_joyce.dat�ɮ�
					$filename_j="./joyce_log/".date('Y-m-d'). ".log";
					$fp = fopen($filename_j,"a+");

					$t_time = date('Y-m-d H:i:s');
					$tmp_sql =  "insert into log ( user_id, event_id, tag1, tag2, tag3, mtime ) values ( '".$a_id."','".$event."','".$ip."','".$row['mtime']."','".$row['tag3']."','".$t_time."');\r\n";
					fwrite($fp, $tmp_sql);
					fclose($fp);
					*///----
				}
				else {
					return "$message - ��Ʈw��s���~12!!";
				}
			}
			else {
				$Q3 = "insert into log ( user_id, event_id, tag1, tag2, tag3 ) values ( '$a_id', '1', '$ip', '".date("YmdHis")."', '1' )";
			}
			if ( !($result = mysql_db_query( $DB, $Q3 ) ) ) {
				$message = "$message - ��Ʈw�g�J���~13!!";
			}
			break;
		case "2":	// �i�J�ҵ{����
		case "4":	// �ϥβ�ѫǦ���
		case "5":	// �s���Q�װϦ���
		case "6":	// �i�K�峹����
			$Q2 = "select a_id, mtime from log where user_id = '$a_id' and event_id = '$event'";
			if ( !($result2 = mysql_db_query( $DB.$tag2, $Q2 ) ) ) {
				return "$message - ��ƮwŪ�����~1!!";
			}
			if ( mysql_num_rows( $result2 ) != 0 ) {
				if ( $row = mysql_fetch_array( $result2 ) ) {
					$Q3 = "update log set tag4 = '". $row['mtime'] ."', tag3 = tag3 + 1 where a_id = '".$row['a_id']."'";
				}
				else {
					return "$message - ��ƮwŪ�����~2!!";
				}
			}
			else {
				$Q3 = "insert into log ( user_id, event_id, tag3, tag4 ) values ( '$a_id', '$event', '1', '".date("YmdHis")."' )";
			}
			if ( !($result = mysql_db_query( $DB.$tag2, $Q3 ) ) ) {
				$message = "$message - ��Ʈw�g�J���~3!!";
			}
			break;
		case "3":	// �s���Ч�����
		case "11":	// �s���Ч��ɶ�
			$Q2 = "select a_id, mtime from log where user_id = '$a_id' and event_id = '$event' and tag1 = '$tag1' and tag4 = '$tag4'";
			if ( !($result2 = mysql_db_query( $DB.$tag2, $Q2 ) ) ) {
				return "$message - ��ƮwŪ�����~31!!";
			}
			if ( mysql_num_rows( $result2 ) != 0 ) {
				if ( $row = mysql_fetch_array( $result2 ) ) {
					$Q3 = "update log set tag3 = tag3 + '$tag3' where a_id = '".$row['a_id']."'";
				}
				else {
					return "$message - ��Ʈw��s���~32!!";
				}
			}
			else {
				$Q3 = "insert into log ( user_id, event_id, tag1, tag3, tag4 ) values ( '$a_id', '$event', '$tag1', '$tag3', '$tag4' )";
			}
			if ( !($result = mysql_db_query( $DB.$tag2, $Q3 ) ) ) {
				$message = "$message - ��Ʈw�g�J���~33!!";
			}
			break;
		case "7":	// �ϥήɼ�
			$Q2 = "select a_id, mtime from log where user_id = '$a_id' and event_id = '$event'";
			if ( !($result2 = mysql_db_query( $DB.$tag2, $Q2 ) ) ) {
				return "$message - ��ƮwŪ�����~71!!";
			}
			if ( mysql_num_rows( $result2 ) != 0 ) {
				if ( $row = mysql_fetch_array( $result2 ) ) {
					$Q3 = "update log set tag3 = tag3 + '$tag3' where a_id = '".$row['a_id']."'";
				}
				else {
					return "$message - ��Ʈw��s���~72!!";
				}
			}
			else {
				$Q3 = "insert into log ( user_id, event_id, tag3 ) values ( '$a_id', '$event', '$tag3')";
			}
			if ( !($result = mysql_db_query( $DB.$tag2, $Q3 ) ) ) {
				$message = "$message - ��Ʈw�g�J���~73!!";
			}
			break;
		case "8":	// �̷s����
			$Q2 = "select a_id, mtime from log where tag1 = '$tag1' and event_id = '$event'";
			if ( !($result2 = mysql_db_query( $DB.$tag2, $Q2 ) ) ) {
				return "$message - ��ƮwŪ�����~101!!";
			}
			if ( mysql_num_rows( $result2 ) != 0 ) {
				if ( $row = mysql_fetch_array( $result2 ) ) {
					$Q3 = "update log set tag3 = tag3 + 1 where a_id = '".$row['a_id']."'";
				}
				else {
					return "$message - ��Ʈw��s���~102!!";
				}
			}
			else {
				$Q3 = "insert into log ( event_id , tag1, tag3 ) values ( '$event', '$tag1', '0' )";
			}
			if ( !($result = mysql_db_query( $DB.$tag2, $Q3 ) ) ) {
				$message = "$message - ��Ʈw�g�J���~103!!";
			}
			break;
		case "9":	// �ƥ��ɶ�
			$Q2 = "select a_id, mtime from log where event_id = '$event'";
			if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) ) {
				return "$message - ��ƮwŪ�����~101!!";
			}
			if ( mysql_num_rows( $result2 ) != 0 ) {
				if ( $row = mysql_fetch_array( $result2 ) ) {
					$Q3 = "update log set tag1 = '$tag1', tag2 = '$tag2' ,tag3 = '$tag3' ,tag4 = '$tag4' where a_id = '".$row['a_id']."'";
				}
				else {
					return "$message - ��Ʈw��s���~102!!";
				}
			}
			else {
				$Q3 = "insert into log ( event_id , tag1, tag2, tag3, tag4 ) values ( '$event', '$tag1', '$tag2', '$tag3', '$tag4' )";
			}
			if ( !($result = mysql_db_query( $DB, $Q3 ) ) ) {
				$message = "$message - ��Ʈw�g�J���~103!!";
			}
			break;
		case "10":	// ���ʲ�ѫ�
			$Q2 = "select a_id, mtime from log where event_id = '$event'";
			if ( !($result2 = mysql_db_query( $DB.$tag2, $Q2 ) ) ) {
				return "$message - ��ƮwŪ�����~101!!";
			}
			if ( mysql_num_rows( $result2 ) != 0 ) {
				if ( $row = mysql_fetch_array( $result2 ) ) {
					$Q3 = "update log set tag1 = '$tag1', tag4 = '$tag4' where a_id = '".$row['a_id']."'";
				}
				else {
					return "$message - ��Ʈw��s���~102!!";
				}
			}
			else {
				$Q3 = "insert into log ( user_id, event_id , tag1, tag4 ) values ( '$a_id', '$event', '$tag1', '$tag4' )";
			}
			if ( !($result = mysql_db_query( $DB.$tag2, $Q3 ) ) ) {
				$message = "$message - ��Ʈw�g�J���~103!!";
			}
			break;
			
			
		case "12":
		//�n�J���x���ɶ� �H�εn�JIP
			$ip = getenv ( "REMOTE_ADDR" );
			if ( $ip == "" ) {
				$ip = $HTTP_X_FORWARDED_FOR;
			}
			if ( $ip == "" ) {
				$ip = $REMOTE_ADDR;
			}
			
			$sql2 = "SELECT course_no FROM course WHERE a_id = '$tag2'";
			if ( !($rel = mysql_db_query($DB, $sql2)) ){
				$message = "$message - course_no�j�M���~";
			}
			if ( !($row1 = mysql_fetch_array( $rel )) ){
				$message = "$message - course_no�^�����~";
			}
			$tag2 = $row1['course_no'];

			$now_time = get_now_time_str();

//added by jimmykuo @ 20110215, �\��:�N�W���ɶ���IP�O����study_log.dat��
			$sql = "SELECT * FROM log WHERE event_id = '$event' and user_id='$a_id'";
                        if( !($res = mysql_db_query($DB.$tag1, $sql ) ) ) {
                                return "$message - �n�����xlog�g�J���~(���Z)!!";
                        }
			//�p�G����ƪ�ܤw�g�s�b�A�u�ݭn��s,
			if(mysql_num_rows($res) !=0 ){
				if($row = mysql_fetch_array($res)){
					$sql = "UPDATE log SET tag1='$ip' WHERE a_id = '".$row['a_id']."'";
					//die($sql);
					if( !($res = mysql_db_query($DB, $sql ) ) ) {
						//die("update error @ event 12");
	                                        return "$message - �n�����xlog�g�J���~(���Z)!!";
        	                        }
				}
				else{
					return "$message - �n�����xlog��s���~(���Z)!!";
				}
			}
			else{ //�p�G���s�b��� ��O�Ĥ@���s�W
				$sql = "INSERT INTO log (user_id, event_id, tag1, tag2, tag3, tag4) values ('$a_id', '$event', '$ip', '$tag2', '0', '$tag4')";
				//die($sql);
				if( !($res = mysql_db_query($DB, $sql ) ) ) {
					//die("insert error @ event 12");
                                	return "$message - �n�����xlog�g�J���~(���Z)!!";
				}
                        }
//end added

			//return $user_id;
			break;
		//zqq �פ�ݭn�s�W-------------------------start
		case "13":	
		//�n�J�ҵ{���ɶ�
		//event_id = 13, tag1 = course_id, tag2 = time, tag3 = , tag4 = �������n�J�ҵ{�ɶ��t
		//�[�b login.php  add_log ( 13, $user_id, $course_id );  teacher
		//�[�b login_s.php add_log ( 13, $user_id, $course_id );  student
			$sql = "SELECT * FROM log WHERE event_id = '$event' and user_id='$a_id'";
			if( !($res = mysql_db_query($DB.$tag1, $sql ) ) ) {
				return "$message - �n�J�ҵ{���ɶ�log�g�J���~!!";
			}			
			//�p�G����ƪ�ܤw�g�s�b�A�u�ݭn��s,
			if(mysql_num_rows($res) !=0 ){
				if($row = mysql_fetch_array($res)){			
					$now_time = get_now_time_total_second();											
					$diff_time = $now_time - str_to_time($row['tag2']);
					//echo $diff_time;
					if($row['tag4'] != 0)
						$avg_diff_time = ($diff_time +  $row['tag4'])/2;
					else
						$avg_diff_time = $diff_time;	
					$sql = "UPDATE log SET tag2='". $row['mtime'] ."' ,tag4='".$avg_diff_time."' WHERE a_id='".$row['a_id']."'";
				}
				else{
					return "$message - �n�J�ҵ{���ɶ�log�g�J���~2!!";
				}
			}
			else{ //�p�G���s�b��� ��O�Ĥ@���s�W
				$now_time = get_now_time_str();			
				$sql = "INSERT INTO log (user_id, event_id, tag1, tag2, tag3, tag4) values ('$a_id', '$event', '$tag1', '$now_time','','0')";
			}
			//echo $sql;
			if ( !($res = mysql_db_query( $DB.$tag1, $sql ) ) ) {
				return "$message - �n�J�ҵ{���ɶ�log�g�J���~3!!";
			}							
			break;
		case '14':	
		//�Ĥ@���I�ݧ@�~�D�ت��ɶ�
		//event_id = 14, tag1 = Course_id - Homework->a_id , tag2 = first time, tag3 = Count, tag4 = �̪�@���I�\���ɶ�@�������ɶ��t
		//�ǤJ�� tag1���@�Ӱ}�C ['course_id'] ['h_id'] 
		//�[�b Testing_Assessment/show_allwork.php 
			$sql = "SELECT * FROM log WHERE event_id = '$event' and user_id='$a_id' and tag1='".$tag1['course_id']."-".$tag1['h_id']."'";
			
			if( !($res = mysql_db_query($DB.$tag1['course_id'], $sql ) ) ) {
				return "$message - �Ĥ@���I�ݧ@�~�D�ت��ɶ�log�g�J���~!!";
			}			
			//�p�G����ƪ�ܤw�g�s�b�A�u�ݭn��s,
			if(mysql_num_rows($res) !=0 ){
				if($row = mysql_fetch_array($res)){
					$now_time_str = get_now_time_str();
					$now_time = get_now_time_total_second();
					$row_array = explode("@",$row['tag4']);
					$last_time = str_to_time($row_array[0]);							
					$diff_time = $now_time - $last_time;
					if($row_array[1] != 0)
						$avg_diff_time = ($diff_time +  $row_array[1])/2;
					else
						$avg_diff_time = $diff_time;						
			
					$sql = "UPDATE log SET  tag3='".($row['tag3']+1)."'  ,tag4='".$now_time_str."@".$avg_diff_time."' WHERE a_id='".$row['a_id']."'";								
					//$sql = "UPDATE log SET  tag3='".($row['tag3']+1)."'  ,tag4='".$now_time."' WHERE a_id='".$row['a_id']."'";
				}
				else{
					return "$message - �Ĥ@���I�ݧ@�~�D�ت��ɶ�log�g�J���~2!!";
				}
			}
			else{ //�p�G���s�b��� ��O�Ĥ@���s�W
				$now_time_str = get_now_time_str();				
				$sql = "INSERT INTO log (user_id, event_id, tag1, tag2, tag3, tag4) values ('$a_id', '$event', '".$tag1['course_id']."-".$tag1['h_id']."', '$now_time_str', '1' , '".$now_time_str."@0')";
								
			}
			if ( !($res = mysql_db_query( $DB.$tag1['course_id'], $sql ) ) ) {
				return "$message - �Ĥ@���I�ݧ@�~�D�ت��ɶ�log�g�J���~3!!";
			}				
		
			break;
		case '15':	
		//ú��@�~���ɶ�
		//event_id = 15, tag1 = Course_id - Homework->a_id , tag2 = first time, tag3 = Count, tag4 = �̪�@���I�\���ɶ�
		//�ǤJ�� tag1���@�Ӱ}�C ['course_id'] ['h_id'] 
		//�[�b Testing_Assessment/show_allwork.php  /editanswer &/uploadwork 
		// add_log(15, $user_id, $tag1);
			$sql = "SELECT * FROM log WHERE event_id = '$event' and user_id='$a_id' and tag1='".$tag1['course_id']."-".$tag1['h_id']."'";
			
			if( !($res = mysql_db_query($DB.$tag1['course_id'], $sql ) ) ) {
				return "$message - ú��@�~���ɶ�log�g�J���~!!";
			}			
			//�p�G����ƪ�ܤw�g�s�b�A�u�ݭn��s,
			if(mysql_num_rows($res) !=0 ){
				if($row = mysql_fetch_array($res)){
					$now_time = get_now_time_str();			
					$sql = "UPDATE log SET  tag3='".($row['tag3']+1)."'  ,tag4='".$now_time."' WHERE a_id='".$row['a_id']."'";
				}
				else{
					return "$message - ú��@�~���ɶ�log�g�J���~2!!";
				}
			}
			else{ //�p�G���s�b��� ��O�Ĥ@���s�W
				$now_time = get_now_time_str();
				$course_h = $tag1['course_id']."-".$tag1['h_id'];			
				$sql = "INSERT INTO log (user_id, event_id, tag1, tag2, tag3, tag4) values ('$a_id', '$event', '$course_h', '$now_time', '1' , '$now_time')";
			}
			if ( !($res = mysql_db_query( $DB.$tag1['course_id'], $sql ) ) ) {
				return "$message - ú��@�~���ɶ�log�g�J���~3!!";
			}						
			break;						
		case '16':	
		//�[�ݤwú��@�~
		//event_id = 16, tag1 = Course_id - Homework->a_id , tag2 = first time, tag3 = Count, tag4 = �̪�@���I�\���ɶ�
		//�ǤJ�� tag1���@�Ӱ}�C ['course_id'] ['h_id'] 
		//�[�b Testing_Assessment/show_allwork.php  /seemywork 
		// add_log(16, $user_id, $tag1);
			$sql = "SELECT * FROM log WHERE event_id = '$event' and user_id='$a_id' and tag1='".$tag1['course_id']."-".$tag1['h_id']."'";
			
			if( !($res = mysql_db_query($DB.$tag1['course_id'], $sql ) ) ) {
				return "$message - ú��@�~���ɶ�log�g�J���~!!";
			}			
			//�p�G����ƪ�ܤw�g�s�b�A�u�ݭn��s,
			if(mysql_num_rows($res) !=0 ){
				if($row = mysql_fetch_array($res)){
					$now_time = get_now_time_str();			
					$sql = "UPDATE log SET  tag3='".($row['tag3']+1)."'  ,tag4='".$now_time."' WHERE a_id='".$row['a_id']."'";
				}
				else{
					return "$message - ú��@�~���ɶ�log�g�J���~2!!";
				}
			}
			else{ //�p�G���s�b��� ��O�Ĥ@���s�W
				$now_time = get_now_time_str();
				$course_h = $tag1['course_id']."-".$tag1['h_id'];			
				$sql = "INSERT INTO log (user_id, event_id, tag1, tag2, tag3, tag4) values ('$a_id', '$event', '$course_h', '$now_time', '1' , '$now_time')";
			}
			if ( !($res = mysql_db_query( $DB.$tag1['course_id'], $sql ) ) ) {
				return "$message - ú��@�~���ɶ�log�g�J���~3!!";
			}	
			break;			
		case '17':	
		//�Y���u�}�@�~
		//event_id = 17, tag1 = Course_id - Homework->a_id , tag2 = first time, tag3 = Count, tag4 = �̪�@���I�\���ɶ�
		//�ǤJ�� tag1���@�Ӱ}�C ['course_id'] ['h_id'] 
		//�[�b Testing_Assessment/show_allwork.php  /seegoodwork 
		// add_log(17, $user_id, $tag1);
			$sql = "SELECT * FROM log WHERE event_id = '$event' and user_id='$a_id' and tag1='".$tag1['course_id']."-".$tag1['h_id']."'";
			
			if( !($res = mysql_db_query($DB.$tag1['course_id'], $sql ) ) ) {
				return "$message - ú��@�~���ɶ�log�g�J���~!!";
			}			
			//�p�G����ƪ�ܤw�g�s�b�A�u�ݭn��s,
			if(mysql_num_rows($res) !=0 ){
				if($row = mysql_fetch_array($res)){
					$now_time = get_now_time_str();			
					$sql = "UPDATE log SET  tag3='".($row['tag3']+1)."'  ,tag4='".$now_time."' WHERE a_id='".$row['a_id']."'";
				}
				else{
					return "$message - ú��@�~���ɶ�log�g�J���~2!!";
				}
			}
			else{ //�p�G���s�b��� ��O�Ĥ@���s�W
				$now_time = get_now_time_str();
				$course_h = $tag1['course_id']."-".$tag1['h_id'];			
				$sql = "INSERT INTO log (user_id, event_id, tag1, tag2, tag3, tag4) values ('$a_id', '$event', '$course_h', '$now_time', '1' , '$now_time')";
			}
			if ( !($res = mysql_db_query( $DB.$tag1['course_id'], $sql ) ) ) {
				return "$message - ú��@�~���ɶ�log�g�J���~3!!";
			}	
			break;
		case '18':
		//�[�ݸѵ����ɶ�
		//event_id = 18, tag1 = Course_id - Homework->a_id , tag2 = first time, tag3 = Count, tag4 = �̪�@���I�\���ɶ�
		//�ǤJ�� tag1���@�Ӱ}�C ['course_id'] ['h_id'] 
		//�[�b Testing_Assessment/show_allwork.php  /seeans
		// add_log(18, $user_id, $tag1);
			$sql = "SELECT * FROM log WHERE event_id = '$event' and user_id='$a_id' and tag1='".$tag1['course_id']."-".$tag1['h_id']."'";
			
			if( !($res = mysql_db_query($DB.$tag1['course_id'], $sql ) ) ) {
				return "$message - ú��@�~���ɶ�log�g�J���~!!";
			}			
			//�p�G����ƪ�ܤw�g�s�b�A�u�ݭn��s,
			if(mysql_num_rows($res) !=0 ){
				if($row = mysql_fetch_array($res)){
					$now_time = get_now_time_str();			
					$sql = "UPDATE log SET  tag3='".($row['tag3']+1)."'  ,tag4='".$now_time."' WHERE a_id='".$row['a_id']."'";
				}
				else{
					return "$message - ú��@�~���ɶ�log�g�J���~2!!";
				}
			}
			else{ //�p�G���s�b��� ��O�Ĥ@���s�W
				$now_time = get_now_time_str();
				$course_h = $tag1['course_id']."-".$tag1['h_id'];			
				$sql = "INSERT INTO log (user_id, event_id, tag1, tag2, tag3, tag4) values ('$a_id', '$event', '$course_h', '$now_time', '1' , '$now_time')";
			}
			if ( !($res = mysql_db_query( $DB.$tag1['course_id'], $sql ) ) ) {
				return "$message - ú��@�~���ɶ�log�g�J���~3!!";
			}	
			break;			
		case '19':	
		//�����窺�ɶ�
		//event_id = 19, tag1 = Course_id - Exam->a_id, tag2 = time, tag3 = , tag4 = 
		//�[�b Testing_Assessment/show_alltest.php  /takeexam
		// add_log(19, $user_id, $tag1);
			$sql = "SELECT * FROM log WHERE event_id = '$event' and user_id='$a_id' and tag1='".$tag1['course_id']."-".$tag1['e_id']."'";
			
			if( !($res = mysql_db_query($DB.$tag1['course_id'], $sql ) ) ) {
				return "$message - ú��@�~���ɶ�log�g�J���~!!";
			}			
			//�p�G����ƪ�ܤw�g�s�b�A�u�ݭn��s,
			if(mysql_num_rows($res) !=0 ){
				if($row = mysql_fetch_array($res)){
					$now_time = get_now_time_str();			
					$sql = "UPDATE log SET  tag3='".($row['tag3']+1)."'  ,tag4='".$now_time."' WHERE a_id='".$row['a_id']."'";
				}
				else{
					return "$message - ú��@�~���ɶ�log�g�J���~2!!";
				}
			}
			else{ //�p�G���s�b��� ��O�Ĥ@���s�W
				$now_time = get_now_time_str();
				$course_e = $tag1['course_id']."-".$tag1['e_id'];			
				$sql = "INSERT INTO log (user_id, event_id, tag1, tag2, tag3, tag4) values ('$a_id', '$event', '$course_e', '$now_time', '1' , '$now_time')";
			}
			if ( !($res = mysql_db_query( $DB.$tag1['course_id'], $sql ) ) ) {
				return "$message - ú��@�~���ɶ�log�g�J���~3!!";
			}	
			break;			
		case '20':	
		//�ݴ��絪�ת��ɶ�
		//event_id = 20, tag1 = Course_id - Exam->a_id, tag2 = time, tag3 = course_id, tag4 = �������n�J�ҵ{�ɶ��t
		//�[�b Testing_Assessment/runtest.php 
		// add_log(20, $user_id, $tag1);
			$sql = "SELECT * FROM log WHERE event_id = '$event' and user_id='$a_id' and tag1='".$tag1['course_id']."-".$tag1['e_id']."'";
			
			if( !($res = mysql_db_query($DB.$tag1['course_id'], $sql ) ) ) {
				return "$message - ú��@�~���ɶ�log�g�J���~!!";
			}			
			//�p�G����ƪ�ܤw�g�s�b�A�u�ݭn��s,
			if(mysql_num_rows($res) !=0 ){
				if($row = mysql_fetch_array($res)){
					$now_time = get_now_time_str();			
					$sql = "UPDATE log SET  tag3='".($row['tag3']+1)."'  ,tag4='".$now_time."' WHERE a_id='".$row['a_id']."'";
				}
				else{
					return "$message - ú��@�~���ɶ�log�g�J���~2!!";
				}
			}
			else{ //�p�G���s�b��� ��O�Ĥ@���s�W
				$now_time = get_now_time_str();
				$course_e = $tag1['course_id']."-".$tag1['e_id'];			
				$sql = "INSERT INTO log (user_id, event_id, tag1, tag2, tag3, tag4) values ('$a_id', '$event', '$course_e', '$now_time', '1' , '$now_time')";
			}
			if ( !($res = mysql_db_query( $DB.$tag1['course_id'], $sql ) ) ) {
				return "$message - ú��@�~���ɶ�log�g�J���~3!!";
			}		
			break;	
		case '21':	
		//�C��,�`�Ĥ@���Q�I�諸�ɶ�
		//event_id = 21, tag1 = Chap-sect, tag2 = �Ĥ@���I�\���ɶ�, tag3 = count, tag4 = �̪�@���I�\���ɶ�@�������ɶ��t
		// tag1 �Ƕi�ӬO�@�Ӱ}�C ['course_id'], ['chap'],['sect']
		//�[�b  textbook/course_menu.php
		// add_log(21, $user_id, $tag1);
			$isSect = 0;
			if(isset($tag1['sect']) && isset($tag1['chap']) ){ //�I���O�`
				$isSect = 1;
				$sql = "SELECT * FROM log WHERE event_id = '$event' and user_id='$a_id' and tag1='".$tag1['chap']."-".$tag1['sect']."'";
			}
			else { //�I���O�� 
				$sql = "SELECT * FROM log WHERE event_id = '$event' and user_id='$a_id' and tag1='".$tag1['chap']."'";
			}			
			
			if( !($res = mysql_db_query($DB.$tag1['course_id'], $sql ) ) ) {
				return "$message - ú��@�~���ɶ�log�g�J���~!!";
			}			
			//�p�G����ƪ�ܤw�g�s�b�A�u�ݭn��s,
			if(mysql_num_rows($res) !=0 ){
				if($row = mysql_fetch_array($res)){
					$now_time_str = get_now_time_str();
					$now_time = get_now_time_total_second();
					$row_array = explode("@",$row['tag4']);
					$last_time = str_to_time($row_array[0]);							
					$diff_time = $now_time - $last_time;
					if($row_array[1] != 0)
						$avg_diff_time = ($diff_time +  $row_array[1])/2;
					else
						$avg_diff_time = $diff_time;						
								
					$sql = "UPDATE log SET  tag3='".($row['tag3']+1)."'  ,tag4='".$now_time_str."@".$avg_diff_time."' WHERE a_id='".$row['a_id']."'";
				}
				else{
					return "$message - ú��@�~���ɶ�log�g�J���~2!!";
				}
			}
			else{ //�p�G���s�b��� ��O�Ĥ@���s�W
				$now_time_str = get_now_time_str();
				if($isSect == 1)
					$chap_sect = $tag1['chap']."-".$tag1['sect'];
				else
					$chap_sect = $tag1['chap'];	
							
				$sql = "INSERT INTO log (user_id, event_id, tag1, tag2, tag3, tag4) values ('$a_id', '$event', '$chap_sect', '$now_time_str', '1' , '".$now_time_str."@0')";
			}
			if ( !($res = mysql_db_query( $DB.$tag1['course_id'], $sql ) ) ) {
				return "$message - ú��@�~���ɶ�log�g�J���~3!!";
			}			
			break;
		case '22':	
		//�I���[�ݤ��i������
		//event_id = 22, tag1 = course_id, tag2 = �Ĥ@���I�\���ɶ�, tag3 = count, tag4 = �̪�@���I�\���ɶ�@�������ɶ��t
		//�[�b  news/news.php
		// add_log(22, $user_id, $tag1);

			$sql = "SELECT * FROM log WHERE event_id = '$event' and user_id='$a_id'";

			if( !($res = mysql_db_query($DB.$tag1, $sql ) ) ) {
				return "$message - ú��@�~���ɶ�log�g�J���~!!";
			}			
			//�p�G����ƪ�ܤw�g�s�b�A�u�ݭn��s,
			if(mysql_num_rows($res) !=0 ){
				if($row = mysql_fetch_array($res)){
					$now_time_str = get_now_time_str();
					$now_time = get_now_time_total_second();
					$row_array = explode("@",$row['tag4']);
					$last_time = str_to_time($row_array[0]);							
					$diff_time = $now_time - $last_time;
					if($row_array[1] != 0)
						$avg_diff_time = ($diff_time +  $row_array[1])/2;
					else
						$avg_diff_time = $diff_time;						
			
					$sql = "UPDATE log SET  tag3='".($row['tag3']+1)."'  ,tag4='".$now_time_str."@".$avg_diff_time."' WHERE a_id='".$row['a_id']."'";
				}
				else{
					return "$message - ú��@�~���ɶ�log�g�J���~2!!";
				}
			}
			else{ //�p�G���s�b��� ��O�Ĥ@���s�W
				$now_time_str = get_now_time_str();						
				$sql = "INSERT INTO log (user_id, event_id, tag1, tag2, tag3, tag4) values ('$a_id', '$event', '$tag1', '$now_time_str', '1' , '".$now_time_str."@0')";
			}
			if ( !($res = mysql_db_query( $DB.$tag1, $sql ) ) ) {
				return "$message - ú��@�~���ɶ�log�g�J���~3!!";
			}				
			break;
		case '23':	
		//�I��Q�װϪ�����
		//event_id = 23, tag1 = discuss_id, tag2 = �Ĥ@���I�\���ɶ�, tag3 = count, tag4 = �̪�@���I�\���ɶ�@�������ɶ��t
		//tag1 �Ƕi�ӬO�@�Ӱ}�C ['course_id'], ['discuss_id'],		
		//�[�b  disscuss/article_list.php
		// add_log(23, $user_id, $tag1);
			$sql = "SELECT * FROM log WHERE event_id = '$event' and user_id='$a_id' and tag1='".$tag1['course_id']."-".$tag1['discuss_id']."'";

			if( !($res = mysql_db_query($DB.$tag1['course_id'], $sql ) ) ) {
				return "$message - ú��@�~���ɶ�log�g�J���~!!";
			}			
			//�p�G����ƪ�ܤw�g�s�b�A�u�ݭn��s,
			if(mysql_num_rows($res) !=0 ){
				if($row = mysql_fetch_array($res)){
					$now_time_str = get_now_time_str();
					$now_time = get_now_time_total_second();
					$row_array = explode("@",$row['tag4']);
					$last_time = str_to_time($row_array[0]);							
					$diff_time = $now_time - $last_time;
					if($row_array[1] != 0)
						$avg_diff_time = ($diff_time +  $row_array[1])/2;
					else
						$avg_diff_time = $diff_time;						
			
					$sql = "UPDATE log SET  tag3='".($row['tag3']+1)."'  ,tag4='".$now_time_str."@".$avg_diff_time."' WHERE a_id='".$row['a_id']."'";
				}
				else{
					return "$message - ú��@�~���ɶ�log�g�J���~2!!";
				}
			}
			else{ //�p�G���s�b��� ��O�Ĥ@���s�W
				$now_time_str = get_now_time_str();						
				$sql = "INSERT INTO log (user_id, event_id, tag1, tag2, tag3, tag4) values ('$a_id', '$event', '".$tag1['course_id']."-".$tag1['discuss_id']."', '$now_time_str', '1' , '".$now_time_str."@0')";
			}

			if ( !($res = mysql_db_query( $DB.$tag1['course_id'], $sql ) ) ) {
				return "$message - ú��@�~���ɶ�log�g�J���~3!!";
			}				
			break;			
		case '24':	
		//�����峹���I�\����
		//event_id = 24, tag1 = discuss_id-article_id, tag2 = �Ĥ@���I�\���ɶ�, tag3 = count, tag4 = �̪�@���I�\���ɶ�@�������ɶ��t
		//�u��Dpost�� log ����^�媺
		//tag1 �Ƕi�ӬO�@�Ӱ}�C ['course_id'], ['discuss_id'], ['article_id']		
		//�[�b  disscuss/show_article.php
		// add_log(24, $user_id, $tag1);
			$sql = "SELECT * FROM log WHERE event_id = '$event' and user_id='$a_id' and tag1='".$tag1['discuss_id']."-".$tag1['article_id']."'";
			if( !($res = mysql_db_query($DB.$tag1['course_id'], $sql ) ) ) {
				return "$message - ú��@�~���ɶ�log�g�J���~!!";
			}			
			//�p�G����ƪ�ܤw�g�s�b�A�u�ݭn��s,
			if(mysql_num_rows($res) !=0 ){
				if($row = mysql_fetch_array($res)){
					$now_time_str = get_now_time_str();
					$now_time = get_now_time_total_second();
					$row_array = explode("@",$row['tag4']);
					$last_time = str_to_time($row_array[0]);							
					$diff_time = $now_time - $last_time;
					if($row_array[1] != 0)
						$avg_diff_time = ($diff_time +  $row_array[1])/2;
					else
						$avg_diff_time = $diff_time;						
			
					$sql = "UPDATE log SET  tag3='".($row['tag3']+1)."'  ,tag4='".$now_time_str."@".$avg_diff_time."' WHERE a_id='".$row['a_id']."'";
				}
				else{
					return "$message - ú��@�~���ɶ�log�g�J���~2!!";
				}
			}
			else{ //�p�G���s�b��� ��O�Ĥ@���s�W
				$now_time_str = get_now_time_str();						
				$sql = "INSERT INTO log (user_id, event_id, tag1, tag2, tag3, tag4) values ('$a_id', '$event', '".$tag1['discuss_id']."-".$tag1['article_id']."', '$now_time_str', '1' , '".$now_time_str."@0')";
			}
			if ( !($res = mysql_db_query( $DB.$tag1['course_id'], $sql ) ) ) {
				return "$message - ú��@�~���ɶ�log�g�J���~3!!";
			}				
			break;
		case '25':	
		//�����窺�ɶ�
		//event_id = 25, tag1 = Course_id - q_id, tag2 = time, tag3 = , tag4 = 
		//�[�b questionary/show_allquestionary.php  /takequestionary
		//add_log(25, $user_id, $tag1);
			$sql = "SELECT * FROM log WHERE event_id = '$event' and user_id='$a_id' and tag1='".$tag1['course_id']."-".$tag1['q_id']."'";
			
			if( !($res = mysql_db_query($DB.$tag1['course_id'], $sql ) ) ) {
				return "$message - ú��@�~���ɶ�log�g�J���~!!";
			}			
			//�p�G����ƪ�ܤw�g�s�b�A�u�ݭn��s,
			if(mysql_num_rows($res) !=0 ){
				if($row = mysql_fetch_array($res)){
					$now_time = get_now_time_str();			
					$sql = "UPDATE log SET  tag3='".($row['tag3']+1)."'  ,tag4='".$now_time."' WHERE a_id='".$row['a_id']."'";
				}
				else{
					return "$message - ú��@�~���ɶ�log�g�J���~2!!";
				}
			}
			else{ //�p�G���s�b��� ��O�Ĥ@���s�W
				$now_time = get_now_time_str();
				$course_e = $tag1['course_id']."-".$tag1['q_id'];			
				$sql = "INSERT INTO log (user_id, event_id, tag1, tag2, tag3, tag4) values ('$a_id', '$event', '$course_e', '$now_time', '1' , '$now_time')";
			}
			if ( !($res = mysql_db_query( $DB.$tag1['course_id'], $sql ) ) ) {
				return "$message - ú��@�~���ɶ�log�g�J���~3!!";
			}	
			break;																
		//zqq �פ�ݭn�s�W-------------------------end

		case "26":
			//chiefboy1230@20120214�A�O���R���ҵ{�j�����ɶ��I�A�]�t�ϥΪ�id�B�n�JIP�B�ҵ{�N�X(�аȳB�оǲտ�év�Ҵ��X���ݨD)�C
			$ip = getenv("REMOTE_ADDR");
			if ($ip == "")
			{
				$ip = $HTTP_X_FORWARDED_FOR;
			}
			if ($ip == "")
			{
				$ip = $REMOTE_ADDR;
			}
			
			$sql2 = "SELECT course_no 
					FROM course 
					WHERE a_id = '$tag2'";
					
			if (!($rel = mysql_db_query($DB, $sql2)))
			{
				$message = "$message - course_no�j�M���~";
			}
			if (!($row1 = mysql_fetch_array($rel)))
			{
				$message = "$message - course_no�^�����~";
			}
			
			$tag2 = $row1['course_no'];

			$sql = "SELECT * 
					FROM log 
					WHERE event_id = '$event' 
					AND user_id='$a_id'";
					
            if(!($res = mysql_db_query($DB.$tag1, $sql)))
			{
				return "$message - �n�����xlog�g�J���~(�O���R���ҵ{�j���ɶ�)!!";
            }
			
			//�p�G����ƪ�ܤw�g�s�b�A�u�ݭn��s�C
			if(mysql_num_rows($res) !=0)
			{
				if($row = mysql_fetch_array($res))
				{
					$sql = "UPDATE log 
							SET tag1='$ip', mtime=NOW() 
							WHERE a_id = '".$row['a_id']."'";
					
					if(!($res = mysql_db_query($DB, $sql)))
					{
					    return "$message - �n�����xlog�g�J���~(�O���R���ҵ{�j���ɶ�)!!";
        	        }
				}
				else
				{
					return "$message - �n�����xlog�g�J���~(�O���R���ҵ{�j���ɶ�)!!";
				}
			}
			else
			{	
				//�p�G���s�b�A��ܲĤ@���s�W
				$sql = "INSERT INTO log (user_id, event_id, tag1, tag2, tag3, tag4) 
						VALUES ('$a_id', '$event', '$ip', '$tag2', NULL, NULL)";
				
				if(!($res = mysql_db_query($DB, $sql )))
				{
					return "$message - �n�����xlog�g�J���~(�O���R���ҵ{�j���ɶ�)!!";
				}
            }
			break;
		

		case "27":
			//chiefboy1230@20130923�A�O���W�ǽҵ{�j�����ɶ��I�A�]�t�ϥΪ�id�B�n�JIP�B�ҵ{�N�X(�аȳB�оǲտ�év�Ҵ��X���ݨD)�C
			$ip = getenv("REMOTE_ADDR");
			if ($ip == "")
			{
				$ip = $HTTP_X_FORWARDED_FOR;
			}
			if ($ip == "")
			{
				$ip = $REMOTE_ADDR;
			}
			
			$sql2 = "SELECT course_no 
					FROM course 
					WHERE a_id = '$tag2'";
					
			if (!($rel = mysql_db_query($DB, $sql2)))
			{
				$message = "$message - course_no�j�M���~";
			}
			if (!($row1 = mysql_fetch_array($rel)))
			{
				$message = "$message - course_no�^�����~";
			}
			
			$tag2 = $row1['course_no'];

			$sql = "SELECT * 
					FROM log 
					WHERE event_id = '$event' 
					AND user_id='$a_id'";
					
            if(!($res = mysql_db_query($DB.$tag1, $sql)))
			{
				return "$message - �n�����xlog�g�J���~(�O���R���ҵ{�j���ɶ�)!!";
            }
			
			//�p�G����ƪ�ܤw�g�s�b�A�u�ݭn��s�C
			if(mysql_num_rows($res) !=0)
			{
				if($row = mysql_fetch_array($res))
				{
					$sql = "UPDATE log 
							SET tag1='$ip', mtime=NOW() 
							WHERE a_id = '".$row['a_id']."'";
					
					if(!($res = mysql_db_query($DB, $sql)))
					{
					    return "$message - �n�����xlog�g�J���~(�O���R���ҵ{�j���ɶ�)!!";
        	        }
				}
				else
				{
					return "$message - �n�����xlog�g�J���~(�O���R���ҵ{�j���ɶ�)!!";
				}
			}
			else
			{	
				//�p�G���s�b�A��ܲĤ@���s�W
				$sql = "INSERT INTO log (user_id, event_id, tag1, tag2, tag3, tag4) 
						VALUES ('$a_id', '$event', '$ip', '$tag2', NULL, NULL)";
				
				if(!($res = mysql_db_query($DB, $sql )))
				{
					return "$message - �n�����xlog�g�J���~(�O���R���ҵ{�j���ɶ�)!!";
				}
            }
			break;
		
	}
//added by jimmykuo @ 20110215
	//�N�̷s���O���P�B�O�����ɮפ�
        $fp = fopen("study_log.dat","a+");
        $sql = "SELECT *  FROM log WHERE user_id = '".$a_id."' AND event_id='".$event."'";
        if($result = mysql_db_query($DB,$sql)){
        	$row = mysql_fetch_array($result);
	        fwrite($fp, $row['a_id'].", ".$row['user_id'].", ".$row['event_id'].", ".$row['tag1'].", ".$row['tag2'].", ".$row['tag3'].", ".$row['tag4'].", ".$row['mtime']."\r\n");
	}
        fclose($fp);
//end added
	return $message;
	
}

function add_log_coop ( $event, $user_id, $tag1 = "" , $tag2 = "", $tag3 = "", $tag4 = "", $group_id = "", $case_id = ""  ) {
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;
	$Q1 = "select u.a_id FROM user u where u.id = '$user_id'";
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$message = "$message - ��Ʈw�s�����~!!";
	}
	if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
		$message = "$message - ��ƮwŪ�����~1!!";
	}
	else if ( $row1 = mysql_fetch_array( $result ) ) {
		$a_id = $row1['a_id'];
	}
	else {
		$message = "$message - ��ƮwŪ�����~2!!";
	}
	switch($event) {
		case "1":	// �n�J�t��
			$ip = getenv ( "REMOTE_ADDR" );
			if ( $ip == "" ) {
				$ip = $HTTP_X_FORWARDED_FOR;
			}
			if ( $ip == "" ) {
				$ip = $REMOTE_ADDR;
			}
			$Q2 = "select a_id, mtime from log_".$case_id." where user_id = '$a_id' and event_id = '$event' and group_num = '$group_id'";
			if ( !($result2 = mysql_db_query( $DBC.$tag2, $Q2 ) ) ) {
				echo( "��ƮwŪ�����~11$Q2!!" );
			}
			if ( mysql_num_rows( $result2 ) != 0 ) {
				if ( $row = mysql_fetch_array( $result2 ) ) {
					$Q3 = "update log_".$case_id." set tag2 = '". $row['mtime'] ."', tag3 = tag3 + 1, tag1 = '$ip' where a_id = '".$row['a_id']."'";
				}
				else {
					echo( "��Ʈw��s���~12!!" );
				}
			}
			else {
				$Q3 = "insert into log_".$case_id." ( user_id, group_num, event_id, tag1, tag2, tag3 ) values ( '$a_id', '$group_id', '1', '$ip', '".date("YmdHis")."', '1' )";
			}
			if ( !($result = mysql_db_query( $DBC.$tag2, $Q3 ) ) ) {
				echo( "��Ʈw�g�J���~13!!$Q3" );
			}
			break;
		case "2":	// �[�ݬy��
		case "3":	// �o��y��
		case "4":	// �ϥβ�ѫǦ���
		case "5":	// �s���Q�װϦ���
		case "6":	// �i�K�峹����
		case "8":	// �귽��
		case "9":	// �ӤH���O��
			$Q2 = "select a_id, mtime from log_".$case_id." where user_id = '$a_id' and event_id = '$event' and group_num = '$group_id'";
			if ( !($result2 = mysql_db_query( $DBC.$tag2, $Q2 ) ) ) {
				return "$message - ��ƮwŪ�����~1!!";
			}
			if ( mysql_num_rows( $result2 ) != 0 ) {
				if ( $row = mysql_fetch_array( $result2 ) ) {
					if ( $tag1 == "1" ) {	 
						$Q3 = "update log_".$case_id." set tag4 = '". $row['mtime'] ."', tag3 = tag3 - 1 where a_id = '".$row['a_id']."'";
					}
					else {
						$Q3 = "update log_".$case_id." set tag4 = '". $row['mtime'] ."', tag3 = tag3 + 1 where a_id = '".$row['a_id']."'";
					}
				}
				else {
					return "$message - ��ƮwŪ�����~2!!";
				}
			}
			else {
				$Q3 = "insert into log_".$case_id." ( user_id, group_num, event_id, tag3, tag4 ) values ( '$a_id', '$group_id', '$event', '1', '".date("YmdHis")."' )";
			}
			if ( !($result = mysql_db_query( $DBC.$tag2, $Q3 ) ) ) {
				$message = "$message - ��Ʈw�g�J���~3!!";
			}
			break;
		case "10":	// �ϥήɼ�
			$Q2 = "select a_id, mtime from log_".$case_id." where user_id = '$a_id' and event_id = '$event' and group_num = '$group_id'";
			if ( !($result2 = mysql_db_query( $DBC.$tag2, $Q2 ) ) ) {
				return "$message - ��ƮwŪ�����~71!!";
			}
			if ( mysql_num_rows( $result2 ) != 0 ) {
				if ( $row = mysql_fetch_array( $result2 ) ) {
					$Q3 = "update log_".$case_id." set tag3 = tag3 + '$tag3' where a_id = '".$row['a_id']."'";
				}
				else {
					return "$message - ��Ʈw��s���~72!!";
				}
			}
			else {
				$Q3 = "insert into log_".$case_id." ( user_id, group_num, event_id, tag3 ) values ( '$a_id', '$group_id', '$event', '$tag3')";
			}
			if ( !($result = mysql_db_query( $DBC.$tag2, $Q3 ) ) ) {
				$message = "$message - ��Ʈw�g�J���~73!!";
			}
			break;
		case "7":	// �̷s����
			$Q2 = "select a_id, mtime from log_".$case_id." where tag1 = '$tag1' and event_id = '$event' and group_num = '$group_id'";
			if ( !($result2 = mysql_db_query( $DBC.$tag2, $Q2 ) ) ) {
				var_dump ( $Q2 );
				return "$message - ��ƮwŪ�����~101!!";
			}
			if ( mysql_num_rows( $result2 ) != 0 ) {
				if ( $row = mysql_fetch_array( $result2 ) ) {
					$Q3 = "update log_".$case_id." set tag3 = tag3 + 1 where a_id = '".$row['a_id']."'";
				}
				else {
					return "$message - ��Ʈw��s���~102!!";
				}
			}
			else {
				$Q3 = "insert into log_".$case_id." ( group_num, event_id , tag1, tag3 ) values ( '$group_id', '$event', '$tag1', '0' )";
			}
			if ( !($result = mysql_db_query( $DBC.$tag2, $Q3 ) ) ) {
				$message = "$message - ��Ʈw�g�J���~103!!";
			}
			break;
			
	}
	return $message;
	
}

function timecount($end_y, $end_mo, $end_d, $end_h, $end_m, $beg_y, $beg_mo, $beg_d, $beg_h, $beg_m)
{
	if ( ($end_y - $beg_y) < 0 )
		return -1;
	else if ( ($end_y - $beg_y) == 0 ) {
		if ( ($end_mo - $beg_mo) < 0 )
			return -2;
		else if ( ($end_mo - $beg_mo) == 0 ) {
			if ( ($end_d - $beg_d) < 0 ){
				echo "end_d = ". $end_d . "beg_d" . $beg_d. "<br>";	
				return -3;
			}
			else if ( ($end_d - $beg_d) == 0 ) {
				if ( ($end_h - $beg_h) < 0 )
					return -4;
				else if ( ($end_h - $beg_h) == 0 ) {
					if ( ($end_m - $beg_m) < 0 )
						return -5;
					else if ( ($end_m - $beg_m) == 0 ) {
						return 0;
					}
					else {
						return ($end_m - $beg_m);
					}
				}
			}
			else{
				return timecount ( $end_y, $end_mo, $beg_d, $end_h + ($end_d - $beg_d)*24, $end_m, $beg_y, $beg_mo, $beg_d, $beg_h, $beg_m);
			}
		}
		else {
			return timecount ( $end_y, $beg_mo, $end_d + ( $end_mo - $beg_mo )*31, $end_h, $end_m, $beg_y, $beg_mo, $beg_d, $beg_h, $beg_m);
		}
	}
	else {
		return timecount ( $beg_y, $end_mo + ($end_y - $beg_y)*12 , $end_d, $end_h, $end_m, $beg_y, $beg_mo, $beg_d, $beg_h, $beg_m);
	}
	$dif = $end_h - $beg_h;
	//echo "in common.php " . $dif. "<br>";
	return $dif;
}

function add_message () {
/*	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $user_id;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$error = "��Ʈw�s�����~!!";
		return $error;
	}

	$Q2 = "select a_id, nickname, name from user where id = '$user_id'";
	if ( $result2 = mysql_db_query( $DB, $Q2 ) ) {
		$row2 = mysql_fetch_array( $result2 );
	}
	if ( $row2['name'] != NULL )
		$name = $row2['name'];
	else if ( $row2['nickname'] != NULL )
		$name = $row2['nickname'];
	else
		$name = $user_id;
	$name = addslashes( $name );
	$Q4 = "select a_id from user where id = 'admin'";
	if ( $result4 = mysql_db_query( $DB, $Q4 ) ) {
		$row4 = mysql_fetch_array( $result4 );
	}
	$Q3 = "select u.a_id from online o, gbfriend g, user u where o.user_id = u.id and u.a_id = g.my_id and g.friend_id = '".$row2['a_id']."' and g.bgcode ='1'";
	if ( $result3 = mysql_db_query( $DB, $Q3 ) ) {
		while ( $row3 = mysql_fetch_array( $result3 ) ) {
			$message = "(�W���q��)\n".$name."�n�J�t�ι�!!^^";
			$Q4 = "insert into message ( send, receive, multi, message, time ) values ( '".$row2['a_id']."', '".$row3['a_id']."', '0', '$message', '".date("Y/m/d H:i:s",time())."')";
			mysql_db_query( $DB, $Q4 );
		}
	}
*/
}

// �ѩ��ɮ׫��Ц����D(�줣�� $file_name), �ҥH�ɦW�n�~�[....
function fileupload ( $filep, $path, $name, $mode=0755 ) {
	// check path exists or not.
	
	if(!validateFile($name) ){
		return;
	}
	
	if( !is_dir( $path ) ) {
		return false;
	}
	else {
		$path = $path."/";
	}

	$name = fileNameModify($name);

	// ready to copy file.
	$target = $path.$name;


	if( copy( $filep, $target ) ) {
		if( chmod( $target, $mode) ){
			return true;
		}
		else {
			return false;
		}
	}
	else {
		return false;
	}
}

function fileNameModify($name){
        //modify by intree@20080227 �B�z�O�d�r
        $name = str_replace('&','��',$name);
        $name = str_replace('#','��',$name);

        return $name;
}

function validateFile($name){
        $tokens = array(0=>".php",1=>".phps",2=>".asp",3=>".jsp",4=>".js");
        foreach($tokens as $token){
                if(strstr($name,$token)){
                        $validate = 0;
                        break;
                }
                $validate = 1;
        }

        return $validate;

}


//�N�r��榡���r���ন���
function str_to_time($str_time)
{
	$year	=0+substr($str_time,0,4);
	$month	=0+substr($str_time,5,2);
	$day	=0+substr($str_time,8,2);
	$hour	=0+substr($str_time,11,2);
	$min	=0+substr($str_time,14,2);
	$sec	=0+substr($str_time,17,2);	
	return mktime($hour,$min,$sec,$month,$day,$year);	
}
//���o�{�b���ɶ��ñN���ର��Ʀ^��
function get_now_time_total_second()
{
	$date_n	= getdate();
	$year	= $date_n['year'];
	$month	= $date_n['mon'];
	$day	= $date_n['mday'];
	$hour	= $date_n['hours'];
	$min	= $date_n['minutes'];
	$sec	= $date_n['seconds'];			
	return mktime($hour,$min,$sec,$month,$day,$year);
}

function get_now_time_str()
{
	$date_n	= getdate();
	$year	= $date_n['year'];
	$month	= $date_n['mon'];
	$day	= $date_n['mday'];
	$hour	= $date_n['hours'];
	$min	= $date_n['minutes'];
	$sec	= $date_n['seconds'];			
	return date("Y-m-d H:i:s",mktime($hour,$min,$sec,$month,$day,$year));
}

?>
