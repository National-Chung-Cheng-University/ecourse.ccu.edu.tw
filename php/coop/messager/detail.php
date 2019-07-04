<?
	
	require 'common.php';
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

	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;
	$Q0 = "select * from online where user_id = '$user_id'";
	$Q2 = "select user_id, course_id, idle, status from online";
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$error = "資料庫連結錯誤!!";
		return $error;
	}
	$close = 0;
	if ( $result0 = mysql_db_query( $DB, $Q0 ) ) {
		if ( mysql_num_rows ( $result0 ) == 0 && $admin != "1") {
			$close = 1;
		}
		else {
			$row0 = mysql_fetch_array( $result0 );
			if ( date("U") - $row0['idle'] >= 1800 )
				show_page( "not_access.tpl" ,"閒置過久!!");
		}
	}
	
	$Q4 = "select a_id, authorization from user where id='$user_id'";
	$result4 = mysql_query($Q4);
	$row4 = mysql_fetch_array($result4);
	$aid = $row4["a_id"];
	$authorization = $row4["authorization"];
	$receive = show_message ( $aid, 0 );

	include("class.FastTemplate.php3");
	$tpl = new FastTemplate("./templates");
        
	if($version == "C") {
	   	$tpl->define(array(main => "detail.tpl"));
	}
	else {
	   	$tpl->define(array(main => "detail_E.tpl"));
	}	   	
	$tpl->assign( SKINNUM , $skinnum );
	$tpl->define_dynamic("online_list", "main");
	$tpl->define_dynamic("offline_list", "main");
	$tpl->assign("UID", $aid);
	$tpl->assign("USER_NAME1", "無使用者");
	$tpl->assign("USER_ID1", "");
	$tpl->assign("COURSE_ID1", "");
	$tpl->assign("AID1", "-1");
	$tpl->assign("HOME1", "");
	$tpl->assign("MAIL1", "");
	$tpl->assign("MAIL2", "");
	$tpl->assign("IDLE1", "");
	$tpl->assign("IDLE2", "");
	$tpl->assign("HOME2", "");
	$tpl->assign("COURSE_ID2", "");
	$tpl->assign("AID2", "-1");
	$tpl->assign("USER_NAME2", "無使用者");
	$tpl->assign("USER_ID2", "");
	if ( $result2 = mysql_db_query( $DB, $Q2 ) ) {
		while ( $row2 = mysql_fetch_array($result2) ) {
			$tpl->assign("USER_NICK3", "");
			$friend = 0;
			$Q3 = "select a_id, nickname, name, php, email, authorization from user where id='".$row2['user_id']."'";
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
			}
			else {
				$name = "系統侵略者";
				$row3 = "";
				$row2['user_id'] = "";
			}
			if ( $user_id == 'guest' )
				$row2['user_id'] = "";

			if ( $row3['authorization'] <= 2 && $row3['authorization'] != "" && $row3['php'] == NULL )
				$row3['php'] = "../Learner_Profile/TTDATAQuery1.php?user_aid=".$row3['a_id']."&userid=".$row2['user_id']."&query=1";

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
					
			$idle = date("U") - $row2['idle'];
			$idlem = (int)($idle/60);
			$idles = $idle%60;

			if ( $friend == 1 ) {
				if ( $row3['name'] != NULL ) {
					$tpl->assign("USER_NAME1", $row3['name']);
					$tpl->assign("USER_NICK1", "(".$name.")");
				}
				else {
					$tpl->assign("USER_NAME1", $name);
					$tpl->assign("USER_NICK1", "(".$name.")");
				}
				if ( $row3['php'] != NULL )
					$tpl->assign("HOME1", "<a href=\"".$row3['php']."\" target=\"_blank\"><img src=\"/images/homepage.gif\" width = 10 border=0 alt=\"個人網頁\"></a>" );
				else
					$tpl->assign("HOME1", "" );
				if ( $row3['email'] != NULL )
					$tpl->assign("MAIL1", "<a href=\"mailto:".$row3['email']."\" ><img src=\"/images/email.gif\" width = 10 border=0 alt=\"Email\"></a>");
				else
					$tpl->assign("MAIL1", "" );
				$tpl->assign("PHPID", $PHPSESSID);
				$tpl->assign("USER_ID1", $row2['user_id']);
				$tpl->assign("AID1", $row2['user_id']);
				$tpl->assign("COURSE_ID1", $row4['name']."(".$row2['status'].")");
				$tpl->assign("IDLE1", $idlem."分".$idles."秒" );
				$tpl->parse(ROWF, ".online_list");
			}
		}
	}
	$Q8 = "select student_id from coop_".$coopcaseid."_group where group_num = '$coopgroup'";
	
	if ( $result8 = mysql_db_query( $DBC.$course_id, $Q8 ) ) {
		
		while ( $row8 = mysql_fetch_array( $result8 ) ) {
			$tpl->assign("USER_NICK2", "");
			$Q9 = "select a_id from online where user_id = '".$row8["student_id"]."'";
			if ( mysql_num_rows( mysql_db_query( $DB, $Q9 ) ) == 0 ) {
				$Q10 = "select * from user where id = '".$row8["student_id"]."'";
				$result10 = mysql_db_query( $DB, $Q10 );
				$row10 = mysql_fetch_array ( $result10 );
				
				if($row10['nickname'] != NULL ) {
					$name = $row10['nickname'];
				}
				else if ($row10['name'] != NULL) {
					$name = $row10['name'];
				}
				else {
					$name = $row10['id'];
				}
				if ( $row10['name'] != NULL ) {
					$tpl->assign("USER_NAME2", $row10['name']);
					$tpl->assign("USER_NICK2", "(".$name.")");
				}
				else {
					$tpl->assign("USER_NAME2", $name);
					$tpl->assign("USER_NICK2", "(".$row10['nickname'].")");
				}
				if ( $row10['php'] != NULL )
					$tpl->assign("HOME2", "<a href=\"".$row10['php']."\" target=\"_blank\"><img src=\"/images/homepage.gif\" width = 10 border=0 alt=\"個人網頁\"></a>" );
				else
					$tpl->assign("HOME2", "" );
				if ( $row10['email'] != NULL )
					$tpl->assign("MAIL2", "<a href=\"mailto:".$row10['email']."\" ><img src=\"/images/email.gif\" width = 10 border=0 alt=\"Email\"></a>" );
				else
					$tpl->assign("MAIL2", "" );
				$Q11 = "select a_id from user where id = '".$row10["a_id"]."'";
				if ( $result11 = mysql_db_query( $DB, $Q11 ) )
					$row11 = mysql_fetch_array( $result11 );
				$Q12 = "select mtime from log where event_id = '1' and user_id = '".$row11["a_id"]."'";
				if ( $result12 = mysql_db_query( $DB, $Q12 ) )
					$row12 = mysql_fetch_array( $result12 );
				$tpl->assign("PHPID", $PHPSESSID);
				$tpl->assign("USER_ID2", $row10['id']);
				$tpl->assign("AID2", $row10['id']);
				$tpl->assign("COURSE_ID2", "上次上站時間\n".substr($row12['mtime'],0,4)."/".substr($row12['mtime'],4,2)."/".substr($row12['mtime'],6,2)." ".substr($row12['mtime'],8,2).":".substr($row12['mtime'],10,2).":".substr($row12['mtime'],12,2) );
				$tpl->parse(ROWO, ".offline_list");
			}
		}
	}
	if ( $close == 1 )
		$tpl->assign("CLOSE", "");
	else
		$tpl->assign("CLOSE", "//");
	
	if ( $receive == 1 ) {
		$tpl->assign("HAVE", "");
		$tpl->assign("MID", $a_id);
		$tpl->assign("USER", $id);
		$tpl->assign("TIME", $posttime);
		$tpl->assign("MULTI", $multi);
		$tpl->assign("MESSAGE", $message);	
	}
	else {
		$tpl->assign("HAVE", "//");
		$tpl->assign("MESSAGE", "");
	}
	$tpl->parse(BODY, "main");
	$tpl->FastPrint(BODY);
	$usedtime = date("U") - $cooptime;
	$cooptime = date("U");
	if( (check_group ( $course_id, $coopgroup, $coopcaseid ) >= 2) && (strcmp($guest,"1") != 0) && (session_check_teach($PHPSESSID) != 3)) {
		add_log_coop( 10, $user_id, "", $course_id, $usedtime, "", $coopgroup, $coopcaseid );
	}
?>