<?php

require 'passwd_encryption.php';
include 'refreshSso.php';
//include 'readssoCcuRightXML.php';

//modify by Autumn
//2003/04/26 PM 07 32
$DB_SERVER = "localhost";  //mysql主機IP
$DB_LOGIN = "study";            //資料庫帳號
$DB_PASSWORD = "study@mysql";         //資料庫帳號之密碼
$DB = "study";             //資料庫名稱
$DBC = "coop";             //合作學習資料庫名稱
//$GLOBALID = "admin";            //用共密碼之帳號
$GLOBALID = "GRD01";            //用共密碼之帳號
$scorm = 0;                //scorm啟用 0不啟用 1啟用
$skinnum = 1;
$SSL = 0;                  //啟用 apache之ssl功能 視apache而定
$user_period =0; //使用者匯入前後期分別 影響TSImportInsert1.php 數值 0(semester2) 1(semester1)
$htopic;
$hip;
$huser_id;
$common_id;
$mmc_host="http://mmc.elearning.ccu.edu.tw";
//linsy@20130307, 加入mmc_templates
$mmc_templates="/datacenter/htdocs/php/Mmc/templates";
//date_default_timezone_set("Asia/Taipei");

function check_group ( $course_id, $group, $caseid ) {
	global $DB_SERVER, $DB_LOGIN, $DBC, $DB_PASSWORD, $user_id, $teacher;
	$Q1 = "select * from coop_".$caseid."_group where student_id = '$user_id' and group_num = '$group'";
	$Q2 = "select * from coop where a_id = '$caseid'";
	$Q3 = "select * from coop_".$caseid."_group where group_num = '$group' and duty='1'";
	
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		echo( "資料庫連結錯誤!!" );
		exit;
	}
	else if ( !($result = mysql_db_query( $DBC.$course_id, $Q1 ) ) ) {
		echo( "資料庫讀取錯誤!!$Q1" );
		exit;
	}
	else if ( !($result2 = mysql_db_query( $DBC.$course_id, $Q2 ) ) ) {
		echo( "資料庫讀取錯誤!!$Q2" );
		exit;
	}
	else if ( !($result3 = mysql_db_query( $DBC.$course_id, $Q3 ) ) ) {
		echo( "資料庫讀取錯誤!!$Q3" );
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
	else if ( session_is_registered("admin") && $admin == 2) {   //用於期中問卷登入
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
		$message = "$message - 資料庫連結錯誤!!";
	}
	if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
		$message = "$message - 資料庫讀取錯誤1!!";
	}
	else if ( $row1 = mysql_fetch_array( $result ) ) {
		$a_id = $row1['a_id'];
	}
	else {
		$message = "$message - 資料庫讀取錯誤2!!!";
	}
	switch($event) {
		case "1":	// 登入系統
			$ip = getenv ( "REMOTE_ADDR" );
			if ( $ip == "" ) {
				$ip = $HTTP_X_FORWARDED_FOR;
			}
			if ( $ip == "" ) {
				$ip = $REMOTE_ADDR;
			}
			$Q2 = "select a_id, mtime ,tag3 from log where user_id = '$a_id' and event_id = '$event'";
			if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) ) {
				return "$message - 資料庫讀取錯誤11!!";
			}
			if ( mysql_num_rows( $result2 ) != 0 ) {
				if ( $row = mysql_fetch_array( $result2 ) ) {
					$Q3 = "update log set tag2 = '". $row['mtime'] ."', tag3 = tag3 + 1, tag1 = '$ip' where a_id = '".$row['a_id']."'";
					
					/*/----
					//edited for joyce @20110110 錄製一個登入平台的study_log_joyce.dat檔案
					$filename_j="./joyce_log/".date('Y-m-d'). ".log";
					$fp = fopen($filename_j,"a+");

					$t_time = date('Y-m-d H:i:s');
					$tmp_sql =  "insert into log ( user_id, event_id, tag1, tag2, tag3, mtime ) values ( '".$a_id."','".$event."','".$ip."','".$row['mtime']."','".$row['tag3']."','".$t_time."');\r\n";
					fwrite($fp, $tmp_sql);
					fclose($fp);
					*///----
				}
				else {
					return "$message - 資料庫更新錯誤12!!";
				}
			}
			else {
				$Q3 = "insert into log ( user_id, event_id, tag1, tag2, tag3 ) values ( '$a_id', '1', '$ip', '".date("YmdHis")."', '1' )";
			}
			if ( !($result = mysql_db_query( $DB, $Q3 ) ) ) {
				$message = "$message - 資料庫寫入錯誤13!!";
			}
			break;
		case "2":	// 進入課程次數
		case "4":	// 使用聊天室次數
		case "5":	// 瀏覽討論區次數
		case "6":	// 張貼文章次數
			$Q2 = "select a_id, mtime from log where user_id = '$a_id' and event_id = '$event'";
			if ( !($result2 = mysql_db_query( $DB.$tag2, $Q2 ) ) ) {
				return "$message - 資料庫讀取錯誤1!!";
			}
			if ( mysql_num_rows( $result2 ) != 0 ) {
				if ( $row = mysql_fetch_array( $result2 ) ) {
					$Q3 = "update log set tag4 = '". $row['mtime'] ."', tag3 = tag3 + 1 where a_id = '".$row['a_id']."'";
				}
				else {
					return "$message - 資料庫讀取錯誤2!!";
				}
			}
			else {
				$Q3 = "insert into log ( user_id, event_id, tag3, tag4 ) values ( '$a_id', '$event', '1', '".date("YmdHis")."' )";
			}
			if ( !($result = mysql_db_query( $DB.$tag2, $Q3 ) ) ) {
				$message = "$message - 資料庫寫入錯誤3!!";
			}
			break;
		case "3":	// 瀏覽教材次數
		case "11":	// 瀏覽教材時間
			$Q2 = "select a_id, mtime from log where user_id = '$a_id' and event_id = '$event' and tag1 = '$tag1' and tag4 = '$tag4'";
			if ( !($result2 = mysql_db_query( $DB.$tag2, $Q2 ) ) ) {
				return "$message - 資料庫讀取錯誤31!!";
			}
			if ( mysql_num_rows( $result2 ) != 0 ) {
				if ( $row = mysql_fetch_array( $result2 ) ) {
					$Q3 = "update log set tag3 = tag3 + '$tag3' where a_id = '".$row['a_id']."'";
				}
				else {
					return "$message - 資料庫更新錯誤32!!";
				}
			}
			else {
				$Q3 = "insert into log ( user_id, event_id, tag1, tag3, tag4 ) values ( '$a_id', '$event', '$tag1', '$tag3', '$tag4' )";
			}
			if ( !($result = mysql_db_query( $DB.$tag2, $Q3 ) ) ) {
				$message = "$message - 資料庫寫入錯誤33!!";
			}
			break;
		case "7":	// 使用時數
			$Q2 = "select a_id, mtime from log where user_id = '$a_id' and event_id = '$event'";
			if ( !($result2 = mysql_db_query( $DB.$tag2, $Q2 ) ) ) {
				return "$message - 資料庫讀取錯誤71!!";
			}
			if ( mysql_num_rows( $result2 ) != 0 ) {
				if ( $row = mysql_fetch_array( $result2 ) ) {
					$Q3 = "update log set tag3 = tag3 + '$tag3' where a_id = '".$row['a_id']."'";
				}
				else {
					return "$message - 資料庫更新錯誤72!!";
				}
			}
			else {
				$Q3 = "insert into log ( user_id, event_id, tag3 ) values ( '$a_id', '$event', '$tag3')";
			}
			if ( !($result = mysql_db_query( $DB.$tag2, $Q3 ) ) ) {
				$message = "$message - 資料庫寫入錯誤73!!";
			}
			break;
		case "8":	// 最新消息
			$Q2 = "select a_id, mtime from log where tag1 = '$tag1' and event_id = '$event'";
			if ( !($result2 = mysql_db_query( $DB.$tag2, $Q2 ) ) ) {
				return "$message - 資料庫讀取錯誤101!!";
			}
			if ( mysql_num_rows( $result2 ) != 0 ) {
				if ( $row = mysql_fetch_array( $result2 ) ) {
					$Q3 = "update log set tag3 = tag3 + 1 where a_id = '".$row['a_id']."'";
				}
				else {
					return "$message - 資料庫更新錯誤102!!";
				}
			}
			else {
				$Q3 = "insert into log ( event_id , tag1, tag3 ) values ( '$event', '$tag1', '0' )";
			}
			if ( !($result = mysql_db_query( $DB.$tag2, $Q3 ) ) ) {
				$message = "$message - 資料庫寫入錯誤103!!";
			}
			break;
		case "9":	// 備份時間
			$Q2 = "select a_id, mtime from log where event_id = '$event'";
			if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) ) {
				return "$message - 資料庫讀取錯誤101!!";
			}
			if ( mysql_num_rows( $result2 ) != 0 ) {
				if ( $row = mysql_fetch_array( $result2 ) ) {
					$Q3 = "update log set tag1 = '$tag1', tag2 = '$tag2' ,tag3 = '$tag3' ,tag4 = '$tag4' where a_id = '".$row['a_id']."'";
				}
				else {
					return "$message - 資料庫更新錯誤102!!";
				}
			}
			else {
				$Q3 = "insert into log ( event_id , tag1, tag2, tag3, tag4 ) values ( '$event', '$tag1', '$tag2', '$tag3', '$tag4' )";
			}
			if ( !($result = mysql_db_query( $DB, $Q3 ) ) ) {
				$message = "$message - 資料庫寫入錯誤103!!";
			}
			break;
		case "10":	// 互動聊天室
			$Q2 = "select a_id, mtime from log where event_id = '$event'";
			if ( !($result2 = mysql_db_query( $DB.$tag2, $Q2 ) ) ) {
				return "$message - 資料庫讀取錯誤101!!";
			}
			if ( mysql_num_rows( $result2 ) != 0 ) {
				if ( $row = mysql_fetch_array( $result2 ) ) {
					$Q3 = "update log set tag1 = '$tag1', tag4 = '$tag4' where a_id = '".$row['a_id']."'";
				}
				else {
					return "$message - 資料庫更新錯誤102!!";
				}
			}
			else {
				$Q3 = "insert into log ( user_id, event_id , tag1, tag4 ) values ( '$a_id', '$event', '$tag1', '$tag4' )";
			}
			if ( !($result = mysql_db_query( $DB.$tag2, $Q3 ) ) ) {
				$message = "$message - 資料庫寫入錯誤103!!";
			}
			break;
			
			
		case "12":
		//登入平台的時間 以及登入IP
			$ip = getenv ( "REMOTE_ADDR" );
			if ( $ip == "" ) {
				$ip = $HTTP_X_FORWARDED_FOR;
			}
			if ( $ip == "" ) {
				$ip = $REMOTE_ADDR;
			}
			
			$sql2 = "SELECT course_no FROM course WHERE a_id = '$tag2'";
			if ( !($rel = mysql_db_query($DB, $sql2)) ){
				$message = "$message - course_no搜尋錯誤";
			}
			if ( !($row1 = mysql_fetch_array( $rel )) ){
				$message = "$message - course_no擷取錯誤";
			}
			$tag2 = $row1['course_no'];

			$now_time = get_now_time_str();

//added by jimmykuo @ 20110215, 功能:將上站時間及IP記錄到study_log.dat中
			$sql = "SELECT * FROM log WHERE event_id = '$event' and user_id='$a_id'";
                        if( !($res = mysql_db_query($DB.$tag1, $sql ) ) ) {
                                return "$message - 登錄平台log寫入錯誤(成績)!!";
                        }
			//如果有資料表示已經存在，只需要更新,
			if(mysql_num_rows($res) !=0 ){
				if($row = mysql_fetch_array($res)){
					$sql = "UPDATE log SET tag1='$ip' WHERE a_id = '".$row['a_id']."'";
					//die($sql);
					if( !($res = mysql_db_query($DB, $sql ) ) ) {
						//die("update error @ event 12");
	                                        return "$message - 登錄平台log寫入錯誤(成績)!!";
        	                        }
				}
				else{
					return "$message - 登錄平台log更新錯誤(成績)!!";
				}
			}
			else{ //如果不存在表示 表是第一次新增
				$sql = "INSERT INTO log (user_id, event_id, tag1, tag2, tag3, tag4) values ('$a_id', '$event', '$ip', '$tag2', '0', '$tag4')";
				//die($sql);
				if( !($res = mysql_db_query($DB, $sql ) ) ) {
					//die("insert error @ event 12");
                                	return "$message - 登錄平台log寫入錯誤(成績)!!";
				}
                        }
//end added

			//return $user_id;
			break;
		//zqq 論文需要新增-------------------------start
		case "13":	
		//登入課程的時間
		//event_id = 13, tag1 = course_id, tag2 = time, tag3 = , tag4 = 平均的登入課程時間差
		//加在 login.php  add_log ( 13, $user_id, $course_id );  teacher
		//加在 login_s.php add_log ( 13, $user_id, $course_id );  student
			$sql = "SELECT * FROM log WHERE event_id = '$event' and user_id='$a_id'";
			if( !($res = mysql_db_query($DB.$tag1, $sql ) ) ) {
				return "$message - 登入課程的時間log寫入錯誤!!";
			}			
			//如果有資料表示已經存在，只需要更新,
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
					return "$message - 登入課程的時間log寫入錯誤2!!";
				}
			}
			else{ //如果不存在表示 表是第一次新增
				$now_time = get_now_time_str();			
				$sql = "INSERT INTO log (user_id, event_id, tag1, tag2, tag3, tag4) values ('$a_id', '$event', '$tag1', '$now_time','','0')";
			}
			//echo $sql;
			if ( !($res = mysql_db_query( $DB.$tag1, $sql ) ) ) {
				return "$message - 登入課程的時間log寫入錯誤3!!";
			}							
			break;
		case '14':	
		//第一次點看作業題目的時間
		//event_id = 14, tag1 = Course_id - Homework->a_id , tag2 = first time, tag3 = Count, tag4 = 最近一次點閱的時間@平均的時間差
		//傳入的 tag1為一個陣列 ['course_id'] ['h_id'] 
		//加在 Testing_Assessment/show_allwork.php 
			$sql = "SELECT * FROM log WHERE event_id = '$event' and user_id='$a_id' and tag1='".$tag1['course_id']."-".$tag1['h_id']."'";
			
			if( !($res = mysql_db_query($DB.$tag1['course_id'], $sql ) ) ) {
				return "$message - 第一次點看作業題目的時間log寫入錯誤!!";
			}			
			//如果有資料表示已經存在，只需要更新,
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
					return "$message - 第一次點看作業題目的時間log寫入錯誤2!!";
				}
			}
			else{ //如果不存在表示 表是第一次新增
				$now_time_str = get_now_time_str();				
				$sql = "INSERT INTO log (user_id, event_id, tag1, tag2, tag3, tag4) values ('$a_id', '$event', '".$tag1['course_id']."-".$tag1['h_id']."', '$now_time_str', '1' , '".$now_time_str."@0')";
								
			}
			if ( !($res = mysql_db_query( $DB.$tag1['course_id'], $sql ) ) ) {
				return "$message - 第一次點看作業題目的時間log寫入錯誤3!!";
			}				
		
			break;
		case '15':	
		//繳交作業的時間
		//event_id = 15, tag1 = Course_id - Homework->a_id , tag2 = first time, tag3 = Count, tag4 = 最近一次點閱的時間
		//傳入的 tag1為一個陣列 ['course_id'] ['h_id'] 
		//加在 Testing_Assessment/show_allwork.php  /editanswer &/uploadwork 
		// add_log(15, $user_id, $tag1);
			$sql = "SELECT * FROM log WHERE event_id = '$event' and user_id='$a_id' and tag1='".$tag1['course_id']."-".$tag1['h_id']."'";
			
			if( !($res = mysql_db_query($DB.$tag1['course_id'], $sql ) ) ) {
				return "$message - 繳交作業的時間log寫入錯誤!!";
			}			
			//如果有資料表示已經存在，只需要更新,
			if(mysql_num_rows($res) !=0 ){
				if($row = mysql_fetch_array($res)){
					$now_time = get_now_time_str();			
					$sql = "UPDATE log SET  tag3='".($row['tag3']+1)."'  ,tag4='".$now_time."' WHERE a_id='".$row['a_id']."'";
				}
				else{
					return "$message - 繳交作業的時間log寫入錯誤2!!";
				}
			}
			else{ //如果不存在表示 表是第一次新增
				$now_time = get_now_time_str();
				$course_h = $tag1['course_id']."-".$tag1['h_id'];			
				$sql = "INSERT INTO log (user_id, event_id, tag1, tag2, tag3, tag4) values ('$a_id', '$event', '$course_h', '$now_time', '1' , '$now_time')";
			}
			if ( !($res = mysql_db_query( $DB.$tag1['course_id'], $sql ) ) ) {
				return "$message - 繳交作業的時間log寫入錯誤3!!";
			}						
			break;						
		case '16':	
		//觀看已繳交作業
		//event_id = 16, tag1 = Course_id - Homework->a_id , tag2 = first time, tag3 = Count, tag4 = 最近一次點閱的時間
		//傳入的 tag1為一個陣列 ['course_id'] ['h_id'] 
		//加在 Testing_Assessment/show_allwork.php  /seemywork 
		// add_log(16, $user_id, $tag1);
			$sql = "SELECT * FROM log WHERE event_id = '$event' and user_id='$a_id' and tag1='".$tag1['course_id']."-".$tag1['h_id']."'";
			
			if( !($res = mysql_db_query($DB.$tag1['course_id'], $sql ) ) ) {
				return "$message - 繳交作業的時間log寫入錯誤!!";
			}			
			//如果有資料表示已經存在，只需要更新,
			if(mysql_num_rows($res) !=0 ){
				if($row = mysql_fetch_array($res)){
					$now_time = get_now_time_str();			
					$sql = "UPDATE log SET  tag3='".($row['tag3']+1)."'  ,tag4='".$now_time."' WHERE a_id='".$row['a_id']."'";
				}
				else{
					return "$message - 繳交作業的時間log寫入錯誤2!!";
				}
			}
			else{ //如果不存在表示 表是第一次新增
				$now_time = get_now_time_str();
				$course_h = $tag1['course_id']."-".$tag1['h_id'];			
				$sql = "INSERT INTO log (user_id, event_id, tag1, tag2, tag3, tag4) values ('$a_id', '$event', '$course_h', '$now_time', '1' , '$now_time')";
			}
			if ( !($res = mysql_db_query( $DB.$tag1['course_id'], $sql ) ) ) {
				return "$message - 繳交作業的時間log寫入錯誤3!!";
			}	
			break;			
		case '17':	
		//欣賞優良作業
		//event_id = 17, tag1 = Course_id - Homework->a_id , tag2 = first time, tag3 = Count, tag4 = 最近一次點閱的時間
		//傳入的 tag1為一個陣列 ['course_id'] ['h_id'] 
		//加在 Testing_Assessment/show_allwork.php  /seegoodwork 
		// add_log(17, $user_id, $tag1);
			$sql = "SELECT * FROM log WHERE event_id = '$event' and user_id='$a_id' and tag1='".$tag1['course_id']."-".$tag1['h_id']."'";
			
			if( !($res = mysql_db_query($DB.$tag1['course_id'], $sql ) ) ) {
				return "$message - 繳交作業的時間log寫入錯誤!!";
			}			
			//如果有資料表示已經存在，只需要更新,
			if(mysql_num_rows($res) !=0 ){
				if($row = mysql_fetch_array($res)){
					$now_time = get_now_time_str();			
					$sql = "UPDATE log SET  tag3='".($row['tag3']+1)."'  ,tag4='".$now_time."' WHERE a_id='".$row['a_id']."'";
				}
				else{
					return "$message - 繳交作業的時間log寫入錯誤2!!";
				}
			}
			else{ //如果不存在表示 表是第一次新增
				$now_time = get_now_time_str();
				$course_h = $tag1['course_id']."-".$tag1['h_id'];			
				$sql = "INSERT INTO log (user_id, event_id, tag1, tag2, tag3, tag4) values ('$a_id', '$event', '$course_h', '$now_time', '1' , '$now_time')";
			}
			if ( !($res = mysql_db_query( $DB.$tag1['course_id'], $sql ) ) ) {
				return "$message - 繳交作業的時間log寫入錯誤3!!";
			}	
			break;
		case '18':
		//觀看解答的時間
		//event_id = 18, tag1 = Course_id - Homework->a_id , tag2 = first time, tag3 = Count, tag4 = 最近一次點閱的時間
		//傳入的 tag1為一個陣列 ['course_id'] ['h_id'] 
		//加在 Testing_Assessment/show_allwork.php  /seeans
		// add_log(18, $user_id, $tag1);
			$sql = "SELECT * FROM log WHERE event_id = '$event' and user_id='$a_id' and tag1='".$tag1['course_id']."-".$tag1['h_id']."'";
			
			if( !($res = mysql_db_query($DB.$tag1['course_id'], $sql ) ) ) {
				return "$message - 繳交作業的時間log寫入錯誤!!";
			}			
			//如果有資料表示已經存在，只需要更新,
			if(mysql_num_rows($res) !=0 ){
				if($row = mysql_fetch_array($res)){
					$now_time = get_now_time_str();			
					$sql = "UPDATE log SET  tag3='".($row['tag3']+1)."'  ,tag4='".$now_time."' WHERE a_id='".$row['a_id']."'";
				}
				else{
					return "$message - 繳交作業的時間log寫入錯誤2!!";
				}
			}
			else{ //如果不存在表示 表是第一次新增
				$now_time = get_now_time_str();
				$course_h = $tag1['course_id']."-".$tag1['h_id'];			
				$sql = "INSERT INTO log (user_id, event_id, tag1, tag2, tag3, tag4) values ('$a_id', '$event', '$course_h', '$now_time', '1' , '$now_time')";
			}
			if ( !($res = mysql_db_query( $DB.$tag1['course_id'], $sql ) ) ) {
				return "$message - 繳交作業的時間log寫入錯誤3!!";
			}	
			break;			
		case '19':	
		//做測驗的時間
		//event_id = 19, tag1 = Course_id - Exam->a_id, tag2 = time, tag3 = , tag4 = 
		//加在 Testing_Assessment/show_alltest.php  /takeexam
		// add_log(19, $user_id, $tag1);
			$sql = "SELECT * FROM log WHERE event_id = '$event' and user_id='$a_id' and tag1='".$tag1['course_id']."-".$tag1['e_id']."'";
			
			if( !($res = mysql_db_query($DB.$tag1['course_id'], $sql ) ) ) {
				return "$message - 繳交作業的時間log寫入錯誤!!";
			}			
			//如果有資料表示已經存在，只需要更新,
			if(mysql_num_rows($res) !=0 ){
				if($row = mysql_fetch_array($res)){
					$now_time = get_now_time_str();			
					$sql = "UPDATE log SET  tag3='".($row['tag3']+1)."'  ,tag4='".$now_time."' WHERE a_id='".$row['a_id']."'";
				}
				else{
					return "$message - 繳交作業的時間log寫入錯誤2!!";
				}
			}
			else{ //如果不存在表示 表是第一次新增
				$now_time = get_now_time_str();
				$course_e = $tag1['course_id']."-".$tag1['e_id'];			
				$sql = "INSERT INTO log (user_id, event_id, tag1, tag2, tag3, tag4) values ('$a_id', '$event', '$course_e', '$now_time', '1' , '$now_time')";
			}
			if ( !($res = mysql_db_query( $DB.$tag1['course_id'], $sql ) ) ) {
				return "$message - 繳交作業的時間log寫入錯誤3!!";
			}	
			break;			
		case '20':	
		//看測驗答案的時間
		//event_id = 20, tag1 = Course_id - Exam->a_id, tag2 = time, tag3 = course_id, tag4 = 平均的登入課程時間差
		//加在 Testing_Assessment/runtest.php 
		// add_log(20, $user_id, $tag1);
			$sql = "SELECT * FROM log WHERE event_id = '$event' and user_id='$a_id' and tag1='".$tag1['course_id']."-".$tag1['e_id']."'";
			
			if( !($res = mysql_db_query($DB.$tag1['course_id'], $sql ) ) ) {
				return "$message - 繳交作業的時間log寫入錯誤!!";
			}			
			//如果有資料表示已經存在，只需要更新,
			if(mysql_num_rows($res) !=0 ){
				if($row = mysql_fetch_array($res)){
					$now_time = get_now_time_str();			
					$sql = "UPDATE log SET  tag3='".($row['tag3']+1)."'  ,tag4='".$now_time."' WHERE a_id='".$row['a_id']."'";
				}
				else{
					return "$message - 繳交作業的時間log寫入錯誤2!!";
				}
			}
			else{ //如果不存在表示 表是第一次新增
				$now_time = get_now_time_str();
				$course_e = $tag1['course_id']."-".$tag1['e_id'];			
				$sql = "INSERT INTO log (user_id, event_id, tag1, tag2, tag3, tag4) values ('$a_id', '$event', '$course_e', '$now_time', '1' , '$now_time')";
			}
			if ( !($res = mysql_db_query( $DB.$tag1['course_id'], $sql ) ) ) {
				return "$message - 繳交作業的時間log寫入錯誤3!!";
			}		
			break;	
		case '21':	
		//每章,節第一次被點選的時間
		//event_id = 21, tag1 = Chap-sect, tag2 = 第一次點閱的時間, tag3 = count, tag4 = 最近一次點閱的時間@平均的時間差
		// tag1 傳進來是一個陣列 ['course_id'], ['chap'],['sect']
		//加在  textbook/course_menu.php
		// add_log(21, $user_id, $tag1);
			$isSect = 0;
			if(isset($tag1['sect']) && isset($tag1['chap']) ){ //點的是節
				$isSect = 1;
				$sql = "SELECT * FROM log WHERE event_id = '$event' and user_id='$a_id' and tag1='".$tag1['chap']."-".$tag1['sect']."'";
			}
			else { //點的是章 
				$sql = "SELECT * FROM log WHERE event_id = '$event' and user_id='$a_id' and tag1='".$tag1['chap']."'";
			}			
			
			if( !($res = mysql_db_query($DB.$tag1['course_id'], $sql ) ) ) {
				return "$message - 繳交作業的時間log寫入錯誤!!";
			}			
			//如果有資料表示已經存在，只需要更新,
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
					return "$message - 繳交作業的時間log寫入錯誤2!!";
				}
			}
			else{ //如果不存在表示 表是第一次新增
				$now_time_str = get_now_time_str();
				if($isSect == 1)
					$chap_sect = $tag1['chap']."-".$tag1['sect'];
				else
					$chap_sect = $tag1['chap'];	
							
				$sql = "INSERT INTO log (user_id, event_id, tag1, tag2, tag3, tag4) values ('$a_id', '$event', '$chap_sect', '$now_time_str', '1' , '".$now_time_str."@0')";
			}
			if ( !($res = mysql_db_query( $DB.$tag1['course_id'], $sql ) ) ) {
				return "$message - 繳交作業的時間log寫入錯誤3!!";
			}			
			break;
		case '22':	
		//點選觀看公告的次數
		//event_id = 22, tag1 = course_id, tag2 = 第一次點閱的時間, tag3 = count, tag4 = 最近一次點閱的時間@平均的時間差
		//加在  news/news.php
		// add_log(22, $user_id, $tag1);

			$sql = "SELECT * FROM log WHERE event_id = '$event' and user_id='$a_id'";

			if( !($res = mysql_db_query($DB.$tag1, $sql ) ) ) {
				return "$message - 繳交作業的時間log寫入錯誤!!";
			}			
			//如果有資料表示已經存在，只需要更新,
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
					return "$message - 繳交作業的時間log寫入錯誤2!!";
				}
			}
			else{ //如果不存在表示 表是第一次新增
				$now_time_str = get_now_time_str();						
				$sql = "INSERT INTO log (user_id, event_id, tag1, tag2, tag3, tag4) values ('$a_id', '$event', '$tag1', '$now_time_str', '1' , '".$now_time_str."@0')";
			}
			if ( !($res = mysql_db_query( $DB.$tag1, $sql ) ) ) {
				return "$message - 繳交作業的時間log寫入錯誤3!!";
			}				
			break;
		case '23':	
		//點選討論區的次數
		//event_id = 23, tag1 = discuss_id, tag2 = 第一次點閱的時間, tag3 = count, tag4 = 最近一次點閱的時間@平均的時間差
		//tag1 傳進來是一個陣列 ['course_id'], ['discuss_id'],		
		//加在  disscuss/article_list.php
		// add_log(23, $user_id, $tag1);
			$sql = "SELECT * FROM log WHERE event_id = '$event' and user_id='$a_id' and tag1='".$tag1['course_id']."-".$tag1['discuss_id']."'";

			if( !($res = mysql_db_query($DB.$tag1['course_id'], $sql ) ) ) {
				return "$message - 繳交作業的時間log寫入錯誤!!";
			}			
			//如果有資料表示已經存在，只需要更新,
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
					return "$message - 繳交作業的時間log寫入錯誤2!!";
				}
			}
			else{ //如果不存在表示 表是第一次新增
				$now_time_str = get_now_time_str();						
				$sql = "INSERT INTO log (user_id, event_id, tag1, tag2, tag3, tag4) values ('$a_id', '$event', '".$tag1['course_id']."-".$tag1['discuss_id']."', '$now_time_str', '1' , '".$now_time_str."@0')";
			}

			if ( !($res = mysql_db_query( $DB.$tag1['course_id'], $sql ) ) ) {
				return "$message - 繳交作業的時間log寫入錯誤3!!";
			}				
			break;			
		case '24':	
		//紀錄文章的點閱次數
		//event_id = 24, tag1 = discuss_id-article_id, tag2 = 第一次點閱的時間, tag3 = count, tag4 = 最近一次點閱的時間@平均的時間差
		//只算主post的 log 不算回文的
		//tag1 傳進來是一個陣列 ['course_id'], ['discuss_id'], ['article_id']		
		//加在  disscuss/show_article.php
		// add_log(24, $user_id, $tag1);
			$sql = "SELECT * FROM log WHERE event_id = '$event' and user_id='$a_id' and tag1='".$tag1['discuss_id']."-".$tag1['article_id']."'";
			if( !($res = mysql_db_query($DB.$tag1['course_id'], $sql ) ) ) {
				return "$message - 繳交作業的時間log寫入錯誤!!";
			}			
			//如果有資料表示已經存在，只需要更新,
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
					return "$message - 繳交作業的時間log寫入錯誤2!!";
				}
			}
			else{ //如果不存在表示 表是第一次新增
				$now_time_str = get_now_time_str();						
				$sql = "INSERT INTO log (user_id, event_id, tag1, tag2, tag3, tag4) values ('$a_id', '$event', '".$tag1['discuss_id']."-".$tag1['article_id']."', '$now_time_str', '1' , '".$now_time_str."@0')";
			}
			if ( !($res = mysql_db_query( $DB.$tag1['course_id'], $sql ) ) ) {
				return "$message - 繳交作業的時間log寫入錯誤3!!";
			}				
			break;
		case '25':	
		//做測驗的時間
		//event_id = 25, tag1 = Course_id - q_id, tag2 = time, tag3 = , tag4 = 
		//加在 questionary/show_allquestionary.php  /takequestionary
		//add_log(25, $user_id, $tag1);
			$sql = "SELECT * FROM log WHERE event_id = '$event' and user_id='$a_id' and tag1='".$tag1['course_id']."-".$tag1['q_id']."'";
			
			if( !($res = mysql_db_query($DB.$tag1['course_id'], $sql ) ) ) {
				return "$message - 繳交作業的時間log寫入錯誤!!";
			}			
			//如果有資料表示已經存在，只需要更新,
			if(mysql_num_rows($res) !=0 ){
				if($row = mysql_fetch_array($res)){
					$now_time = get_now_time_str();			
					$sql = "UPDATE log SET  tag3='".($row['tag3']+1)."'  ,tag4='".$now_time."' WHERE a_id='".$row['a_id']."'";
				}
				else{
					return "$message - 繳交作業的時間log寫入錯誤2!!";
				}
			}
			else{ //如果不存在表示 表是第一次新增
				$now_time = get_now_time_str();
				$course_e = $tag1['course_id']."-".$tag1['q_id'];			
				$sql = "INSERT INTO log (user_id, event_id, tag1, tag2, tag3, tag4) values ('$a_id', '$event', '$course_e', '$now_time', '1' , '$now_time')";
			}
			if ( !($res = mysql_db_query( $DB.$tag1['course_id'], $sql ) ) ) {
				return "$message - 繳交作業的時間log寫入錯誤3!!";
			}	
			break;																
		//zqq 論文需要新增-------------------------end

		case "26":
			//chiefboy1230@20120214，記錄刪除課程大綱之時間點，包含使用者id、登入IP、課程代碼(教務處教學組賴永宗所提出之需求)。
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
				$message = "$message - course_no搜尋錯誤";
			}
			if (!($row1 = mysql_fetch_array($rel)))
			{
				$message = "$message - course_no擷取錯誤";
			}
			
			$tag2 = $row1['course_no'];

			$sql = "SELECT * 
					FROM log 
					WHERE event_id = '$event' 
					AND user_id='$a_id'";
					
            if(!($res = mysql_db_query($DB.$tag1, $sql)))
			{
				return "$message - 登錄平台log寫入錯誤(記錄刪除課程大綱時間)!!";
            }
			
			//如果有資料表示已經存在，只需要更新。
			if(mysql_num_rows($res) !=0)
			{
				if($row = mysql_fetch_array($res))
				{
					$sql = "UPDATE log 
							SET tag1='$ip', mtime=NOW() 
							WHERE a_id = '".$row['a_id']."'";
					
					if(!($res = mysql_db_query($DB, $sql)))
					{
					    return "$message - 登錄平台log寫入錯誤(記錄刪除課程大綱時間)!!";
        	        }
				}
				else
				{
					return "$message - 登錄平台log寫入錯誤(記錄刪除課程大綱時間)!!";
				}
			}
			else
			{	
				//如果不存在，表示第一次新增
				$sql = "INSERT INTO log (user_id, event_id, tag1, tag2, tag3, tag4) 
						VALUES ('$a_id', '$event', '$ip', '$tag2', NULL, NULL)";
				
				if(!($res = mysql_db_query($DB, $sql )))
				{
					return "$message - 登錄平台log寫入錯誤(記錄刪除課程大綱時間)!!";
				}
            }
			break;
		

		case "27":
			//chiefboy1230@20130923，記錄上傳課程大綱之時間點，包含使用者id、登入IP、課程代碼(教務處教學組賴永宗所提出之需求)。
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
				$message = "$message - course_no搜尋錯誤";
			}
			if (!($row1 = mysql_fetch_array($rel)))
			{
				$message = "$message - course_no擷取錯誤";
			}
			
			$tag2 = $row1['course_no'];

			$sql = "SELECT * 
					FROM log 
					WHERE event_id = '$event' 
					AND user_id='$a_id'";
					
            if(!($res = mysql_db_query($DB.$tag1, $sql)))
			{
				return "$message - 登錄平台log寫入錯誤(記錄刪除課程大綱時間)!!";
            }
			
			//如果有資料表示已經存在，只需要更新。
			if(mysql_num_rows($res) !=0)
			{
				if($row = mysql_fetch_array($res))
				{
					$sql = "UPDATE log 
							SET tag1='$ip', mtime=NOW() 
							WHERE a_id = '".$row['a_id']."'";
					
					if(!($res = mysql_db_query($DB, $sql)))
					{
					    return "$message - 登錄平台log寫入錯誤(記錄刪除課程大綱時間)!!";
        	        }
				}
				else
				{
					return "$message - 登錄平台log寫入錯誤(記錄刪除課程大綱時間)!!";
				}
			}
			else
			{	
				//如果不存在，表示第一次新增
				$sql = "INSERT INTO log (user_id, event_id, tag1, tag2, tag3, tag4) 
						VALUES ('$a_id', '$event', '$ip', '$tag2', NULL, NULL)";
				
				if(!($res = mysql_db_query($DB, $sql )))
				{
					return "$message - 登錄平台log寫入錯誤(記錄刪除課程大綱時間)!!";
				}
            }
			break;
		
	}
//added by jimmykuo @ 20110215
	//將最新的記錄同步記錄到檔案中
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
		$message = "$message - 資料庫連結錯誤!!";
	}
	if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
		$message = "$message - 資料庫讀取錯誤1!!";
	}
	else if ( $row1 = mysql_fetch_array( $result ) ) {
		$a_id = $row1['a_id'];
	}
	else {
		$message = "$message - 資料庫讀取錯誤2!!";
	}
	switch($event) {
		case "1":	// 登入系統
			$ip = getenv ( "REMOTE_ADDR" );
			if ( $ip == "" ) {
				$ip = $HTTP_X_FORWARDED_FOR;
			}
			if ( $ip == "" ) {
				$ip = $REMOTE_ADDR;
			}
			$Q2 = "select a_id, mtime from log_".$case_id." where user_id = '$a_id' and event_id = '$event' and group_num = '$group_id'";
			if ( !($result2 = mysql_db_query( $DBC.$tag2, $Q2 ) ) ) {
				echo( "資料庫讀取錯誤11$Q2!!" );
			}
			if ( mysql_num_rows( $result2 ) != 0 ) {
				if ( $row = mysql_fetch_array( $result2 ) ) {
					$Q3 = "update log_".$case_id." set tag2 = '". $row['mtime'] ."', tag3 = tag3 + 1, tag1 = '$ip' where a_id = '".$row['a_id']."'";
				}
				else {
					echo( "資料庫更新錯誤12!!" );
				}
			}
			else {
				$Q3 = "insert into log_".$case_id." ( user_id, group_num, event_id, tag1, tag2, tag3 ) values ( '$a_id', '$group_id', '1', '$ip', '".date("YmdHis")."', '1' )";
			}
			if ( !($result = mysql_db_query( $DBC.$tag2, $Q3 ) ) ) {
				echo( "資料庫寫入錯誤13!!$Q3" );
			}
			break;
		case "2":	// 觀看流言
		case "3":	// 發表流言
		case "4":	// 使用聊天室次數
		case "5":	// 瀏覽討論區次數
		case "6":	// 張貼文章次數
		case "8":	// 資源數
		case "9":	// 個人筆記本
			$Q2 = "select a_id, mtime from log_".$case_id." where user_id = '$a_id' and event_id = '$event' and group_num = '$group_id'";
			if ( !($result2 = mysql_db_query( $DBC.$tag2, $Q2 ) ) ) {
				return "$message - 資料庫讀取錯誤1!!";
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
					return "$message - 資料庫讀取錯誤2!!";
				}
			}
			else {
				$Q3 = "insert into log_".$case_id." ( user_id, group_num, event_id, tag3, tag4 ) values ( '$a_id', '$group_id', '$event', '1', '".date("YmdHis")."' )";
			}
			if ( !($result = mysql_db_query( $DBC.$tag2, $Q3 ) ) ) {
				$message = "$message - 資料庫寫入錯誤3!!";
			}
			break;
		case "10":	// 使用時數
			$Q2 = "select a_id, mtime from log_".$case_id." where user_id = '$a_id' and event_id = '$event' and group_num = '$group_id'";
			if ( !($result2 = mysql_db_query( $DBC.$tag2, $Q2 ) ) ) {
				return "$message - 資料庫讀取錯誤71!!";
			}
			if ( mysql_num_rows( $result2 ) != 0 ) {
				if ( $row = mysql_fetch_array( $result2 ) ) {
					$Q3 = "update log_".$case_id." set tag3 = tag3 + '$tag3' where a_id = '".$row['a_id']."'";
				}
				else {
					return "$message - 資料庫更新錯誤72!!";
				}
			}
			else {
				$Q3 = "insert into log_".$case_id." ( user_id, group_num, event_id, tag3 ) values ( '$a_id', '$group_id', '$event', '$tag3')";
			}
			if ( !($result = mysql_db_query( $DBC.$tag2, $Q3 ) ) ) {
				$message = "$message - 資料庫寫入錯誤73!!";
			}
			break;
		case "7":	// 最新消息
			$Q2 = "select a_id, mtime from log_".$case_id." where tag1 = '$tag1' and event_id = '$event' and group_num = '$group_id'";
			if ( !($result2 = mysql_db_query( $DBC.$tag2, $Q2 ) ) ) {
				var_dump ( $Q2 );
				return "$message - 資料庫讀取錯誤101!!";
			}
			if ( mysql_num_rows( $result2 ) != 0 ) {
				if ( $row = mysql_fetch_array( $result2 ) ) {
					$Q3 = "update log_".$case_id." set tag3 = tag3 + 1 where a_id = '".$row['a_id']."'";
				}
				else {
					return "$message - 資料庫更新錯誤102!!";
				}
			}
			else {
				$Q3 = "insert into log_".$case_id." ( group_num, event_id , tag1, tag3 ) values ( '$group_id', '$event', '$tag1', '0' )";
			}
			if ( !($result = mysql_db_query( $DBC.$tag2, $Q3 ) ) ) {
				$message = "$message - 資料庫寫入錯誤103!!";
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
		$error = "資料庫連結錯誤!!";
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
			$message = "(上站通知)\n".$name."登入系統嘍!!^^";
			$Q4 = "insert into message ( send, receive, multi, message, time ) values ( '".$row2['a_id']."', '".$row3['a_id']."', '0', '$message', '".date("Y/m/d H:i:s",time())."')";
			mysql_db_query( $DB, $Q4 );
		}
	}
*/
}

// 由於檔案指標有問題(抓不到 $file_name), 所以檔名要外加....
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
        //modify by intree@20080227 處理保留字
        $name = str_replace('&','＆',$name);
        $name = str_replace('#','＃',$name);

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


//將字串格式的字串轉成秒數
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
//取得現在的時間並將其轉為秒數回傳
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
