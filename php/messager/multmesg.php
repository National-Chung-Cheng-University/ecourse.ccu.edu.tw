<?
	
	require 'fadmin.php';
	
	$refreshmin = 1.5;
	if ( !(isset($PHPSESSID) && session_check_teach($PHPSESSID)) && $admin != "1" ) {
		show_page( "not_access.tpl" ,"權限錯誤");
		exit;
	}
	if ( $user_id == 'guest' ) {
		if ( $version == "C" )
			show_page( "not_access.tpl" ,"你無法傳送訊息");
		else
			show_page( "not_access.tpl" ,"You may not send message");
		exit;
	}

	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
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
   	
	if ( $send == 1 )
		$tpl->assign("CLOSE", "");
	else
		$tpl->assign("CLOSE", "//");
		
	$tpl->define_dynamic("course_list", "main");
	$tpl->define_dynamic("system_list", "main");
	$tpl->define_dynamic("friend_list", "main");
	$tpl->define_dynamic("friend_oist", "main");

	$tpl->assign("USER_NAME1", "無使用者");
	$tpl->assign("USER_NAME2", "無使用者");
	$tpl->assign("USER_NAME4", "無使用者");
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
	
				$Q8 = "select a_id from gbfriend where my_id = '$aid' and friend_id = '".$row3['a_id']."'";
				if ( $result8 = mysql_db_query( $DB, $Q8) )
					if ( mysql_num_rows( $result8 ) != 0  || $aid == $row3['a_id'] )
						$friend = 1;
				if ( $friend == 1 ) {
					if ( $row3['name'] != NULL )
						$name = $row3['name'];
					$tpl->assign("USER_NAME3", $name);
					$tpl->assign("NUM3", $num);
					$tpl->assign("AID3", $row2['user_id']);
					$tpl->parse(ROWF, ".friend_list");
				}
				else if( $row2['course_id'] == $course_id ) {
					$tpl->assign("USER_NAME1", $name);
					$tpl->assign("NUM1", $num);
					$tpl->assign("AID1", $row2['user_id']);
					$tpl->parse(ROWC, ".course_list");
				}
				else {
					$tpl->assign("USER_NAME2", $name);
					$tpl->assign("NUM2", $num);				
					$tpl->assign("AID2", $row2['user_id']);
					$tpl->parse(ROWS, ".system_list");
				}
			}
		}
	}
	$Q10 = "select g.friend_id, u.id, u.name, u.nickname from gbfriend g, user u where g.friend_id = u.a_id and g.my_id = '$aid'";
	if ( $result10 = mysql_db_query( $DB, $Q10 ) ) {
		while ( $row10 = mysql_fetch_array( $result10 ) ) {
			$Q11 = "select a_id from online where user_id = '".$row10["id"]."'";
			if ( mysql_num_rows( mysql_db_query( $DB, $Q11 ) ) == 0 ) {
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
				$tpl->assign("USER_NAME4", $name );
				$tpl->assign("NUM4", $num);
				$tpl->assign("AID4", $row10['id']);
				$tpl->parse(ROWO, ".friend_oist");
			}
		}
	}
	$tpl->assign("MESSAGE", $message );
	$tpl->assign("NUM3", $num);
	$tpl->parse(BODY, "main");
	$tpl->FastPrint(BODY);
?>