<?
	
	require 'fadmin.php';
	
	$refreshmin = 1.5;
	if(!(isset($PHPSESSID) && session_check_teach($PHPSESSID) && check_group ( $course_id, $coopgroup, $coopcaseid ) >= 2 ) )
	{
		if( $version=="C" ) {
			show_page( "not_access.tpl" ,"你無法傳送訊息");
			exit;
		}
		else {
			show_page( "not_access.tpl" ,"You may not send message!!");
			exit;
		}
	}

	if ( $user_id == 'guest' ) {
		if ( $version == "C" )
			show_page( "not_access.tpl" ,"你無法傳送訊息");
		else
			show_page( "not_access.tpl" ,"You may not send message");
		exit;
	}

	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;
	$Q0 = "select * from online where user_id = '$user_id'";
	$Q2 = "select * from online";
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$error = "資料庫連結錯誤!!";
		return $error;
	}
	if ( $result0 = mysql_db_query( $DB, $Q0 ) )
		if ( mysql_num_rows ( $result0 ) == 0 && $admin != "1") {
			show_page( "not_access.tpl" ,"你尚未登入");
			exit;
		}

	
	if ( $message != "" ) {
		$message = htmlspecialchars ( $message );
		$Q5 = "select a_id from user where id = '$user_id'";
		if ( $result5 = mysql_db_query( $DB, $Q5 ) ) {
			$row5 = mysql_fetch_array( $result5 );
		}
		for ( $i = 1 ; $i <= $num ; $i ++ ) {
			$u = "user".$i;
			$Q6 = "select u.a_id from online o, user u where u.id = '".$$u."' and u.id = o.user_id";
			if ( $result6 = mysql_db_query( $DB, $Q6 ) ) {
				if ( mysql_num_rows ( $result6 ) != 0 ) {
					$row6 = mysql_fetch_array ( $result6 );
					$Q7 = "insert into message ( send, receive, multi, message, time ) values ( '".$row5['a_id']."', '".$row6['a_id']."', '1', '$message', '".date("Y/m/d H:i:s",time())."')";
					if ( mysql_db_query( $DB, $Q7 ) )
						$send = 1;
				}
			}
		}
	}

	$Q9 = "select a_id from user where id='$user_id'";
	$result9 = mysql_query($Q9);
	$row9 = mysql_fetch_array($result9);
	$aid = $row9["a_id"];

	include("class.FastTemplate.php3");
	$tpl = new FastTemplate("./templates");
        
	if($version == "C") {
	   	$tpl->define(array(main => "multmesg.tpl"));
	}
	else {
	   	$tpl->define(array(main => "multmesg_E.tpl"));
	}
   	$tpl->assign( SKINNUM , $skinnum );
	if ( $send == 1 )
		$tpl->assign("CLOSE", "");
	else
		$tpl->assign("CLOSE", "//");
		
	$tpl->define_dynamic("online_list", "main");
	$tpl->define_dynamic("offline_list", "main");

	$tpl->assign("USER_NAME1", "無使用者");
	$tpl->assign("USER_NAME2", "無使用者");
	$num = 0;
	if ( $result2 = mysql_db_query( $DB, $Q2 ) ) {
		
		while ( $row2 = mysql_fetch_array($result2) ) {
			$friend = 0;
			if ( $row2['user_id'] != 'guest' ) {
				$Q3 = "select a_id, nickname, name from user where id='".$row2['user_id']."'";
				if ($result3 = mysql_db_query( $DB, $Q3)) {
					if( $row3 = mysql_fetch_array($result3)) {
						if($row3['nickname'] != NULL) {
							$name = $row3['nickname'];
						}
						else if ($row3['name'] != NULL) {
							$name = $row3['name'];
						}
						else {
							$name = $row2['user_id'];
						}
					}
					$num ++;
				}
				else {
					$name = "系統侵略者";
					$row3 = "";
					$row3['a_id'] = -1;
				}
				
				$Q4 = "select name from course where a_id = '".$row2['course_id']."'";
				if ($result4 = mysql_db_query( $DB, $Q4))
					$row4 = mysql_fetch_array($result4);
	
				$Q5 = "select id from user where a_id = '".$row3['a_id']."'";
				if ($result5 = mysql_db_query( $DB, $Q5))
					$row5 = mysql_fetch_array($result5);
	
				$Q7 = "select student_id from coop_".$coopcaseid."_group where student_id = '".$row5['id']."' and group_num = '$coopgroup'";
				if ( $result7 = mysql_db_query( $DBC.$course_id, $Q7) )
					if ( mysql_num_rows( $result7) != 0 || $aid == $row3['a_id'] )
						$friend = 1;

				if ( $friend == 1 ) {
					if ( $row3['name'] != NULL )
						$name = $row3['name'];
					$tpl->assign("USER_NAME1", $name);
					$tpl->assign("NUM1", $num);
					$tpl->assign("AID1", $row2['user_id']);
					$tpl->parse(ROWF, ".online_list");
				}
			}
		}
	}
	$Q8 = "select student_id from coop_".$coopcaseid."_group where group_num = '$coopgroup'";
	if ( $result8 = mysql_db_query( $DBC.$course_id, $Q8 ) ) {
		while ( $row8 = mysql_fetch_array( $result8 ) ) {
			$Q9 = "select a_id from online where user_id = '".$row8["student_id"]."'";
			if ( mysql_num_rows( mysql_db_query( $DB, $Q9 ) ) == 0 ) {
				$Q10 = "select * from user where id = '".$row8["student_id"]."'";
				$result10 = mysql_db_query( $DB, $Q10 );
				$row10 = mysql_fetch_array ( $result10 );

				$num ++;
				if($row10['name'] != NULL ) {
					$name = $row10['name'];
				}
				else if ($row10['nickname'] != NULL) {
					$name = $row10['nickname'];
				}
				else {
					$name = $row10['id'];
				}
				$tpl->assign("USER_NAME2", $name );
				$tpl->assign("NUM2", $num);
				$tpl->assign("AID2", $row10['id']);
				$tpl->parse(ROWO, ".offline_list");
			}
		}
	}
	$tpl->assign("MESSAGE", $message );
	$tpl->assign("NUM3", $num);
	$tpl->parse(BODY, "main");
	$tpl->FastPrint(BODY);
?>