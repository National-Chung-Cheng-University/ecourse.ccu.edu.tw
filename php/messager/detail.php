<?
	
	require 'common.php';
	$refreshmin = 1.5;
	if ( !(isset($PHPSESSID) && session_check_teach($PHPSESSID)) && $admin != "1" ) {
		show_page( "not_access.tpl" ,"權限錯誤");
		exit;
	}

	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
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

	$tpl->define_dynamic("course_list", "main");
	$tpl->define_dynamic("system_list", "main");
	$tpl->define_dynamic("friend_list", "main");
	$tpl->define_dynamic("friend_oist", "main");
	$tpl->assign("UID", $aid);
	$tpl->assign("USER_NAME3", "");
	$tpl->assign("USER_NICK3", "");
	$tpl->assign("USER_ID3", "");
	$tpl->assign("COURSE_ID3", "");
	$tpl->assign("AID3", "-1");
	$tpl->assign("HOME3", "");
	$tpl->assign("MAIL3", "");
	$tpl->assign("IDLE3", "");
	$tpl->assign("USER_NAME4", "無離線好友");
	$tpl->assign("USER_NICK4", "");
	$tpl->assign("USER_ID4", "");
	$tpl->assign("COURSE_ID4", "");
	$tpl->assign("AID4", "-1");
	$tpl->assign("HOME4", "");
	$tpl->assign("MAIL4", "");
	$tpl->assign("IDLE4", "");
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

			$Q7 = "select a_id from gbfriend where my_id = '$aid' and friend_id = '".$row3['a_id']."'";
			if ( $result7 = mysql_db_query( $DB, $Q7) )
				if ( mysql_num_rows( $result7) != 0 || $aid == $row3['a_id'] )
					$friend = 1;
					
			$idle = date("U") - $row2['idle'];
			$idlem = (int)($idle/60);
			$idles = $idle%60;

			if ( $friend == 1 ) {
				if ( $row3['name'] != NULL ) {
					$tpl->assign("USER_NAME3", $row3['name']);
					$tpl->assign("USER_NICK3", "(".$name.")");
				}
				else {
					$tpl->assign("USER_NAME3", $name);
					$tpl->assign("USER_NICK3", "(".$name.")");
				}
				if ( $row3['php'] != NULL )
					$tpl->assign("HOME3", "<a href=\"".$row3['php']."\" target=\"_blank\" onClick=\"javascript:urchinTracker('".$row3['php']."');\"><img src=\"/images/homepage.gif\" width = 10 border=0 alt=\"個人網頁\"></a>" );
				else
					$tpl->assign("HOME3", "" );
				if ( $row3['email'] != NULL )
					$tpl->assign("MAIL3", "<a href=\"mailto:".$row3['email']."\" ><img src=\"/images/email.gif\" width = 10 border=0 alt=\"Email\"></a>");
				else
					$tpl->assign("MAIL3", "" );
				$tpl->assign("PHPID", $PHPSESSID);
				$tpl->assign("USER_ID3", $row2['user_id']);
				$tpl->assign("AID3", $row2['user_id']);
				$tpl->assign("COURSE_ID3", $row4['name']."(".$row2['status'].")");
				$tpl->assign("IDLE3", $idlem."分".$idles."秒" );
				$tpl->parse(ROWF, ".friend_list");
			}
			else if( $row2['course_id'] == $course_id ) {
				$tpl->assign("USER_NAME1", $name);
				if ( $row3['php'] != NULL )
					$tpl->assign("HOME1", "<a href=\"".$row3['php']."\" target=\"_blank\" onClick=\"javascript:urchinTracker('".$row3['php']."');\"><img src=\"/images/homepage.gif\" width = 10 border=0 alt=\"個人網頁\"></a>" );
				else
					$tpl->assign("HOME1", "" );
				if ( $row3['email'] != NULL )
					$tpl->assign("MAIL1", "<a href=\"mailto:".$row3['email']."\" ><img src=\"/images/email.gif\" width = 10 border=0 alt=\"Email\"></a>");
				else
					$tpl->assign("MAIL1", "" );
				$tpl->assign("PHPID", $PHPSESSID);
				$tpl->assign("USER_ID1", $row2['user_id']);
				$tpl->assign("AID1", $row2['user_id']);
				if ( $teacher == 1)
					$tpl->assign("COURSE_ID1", $row4['name']."(".$row2['status'].")");
				else
					$tpl->assign("COURSE_ID1", $row4['name']);
				$tpl->assign("IDLE1", $idlem."分".$idles."秒" );
				$tpl->parse(ROWC, ".course_list");
			}
			else {
				$tpl->assign("USER_NAME2", $name);
				if ( $row3['php'] != NULL )
					$tpl->assign("HOME2", "<a href=\"".$row3['php']."\" target=\"_blank\" onClick=\"javascript:urchinTracker('".$row3['php']."');\"><img src=\"/images/homepage.gif\" width = 10 border=0 alt=\"個人網頁\"></a>" );
				else
					$tpl->assign("HOME2", "" );

				if ( $row3['email'] != NULL )
					$tpl->assign("MAIL2", "<a href=\"mailto:".$row3['email']."\" ><img src=\"/images/email.gif\" width = 10 border=0 alt=\"Email\"></a>" );
				else
					$tpl->assign("MAIL2", "" );
				$tpl->assign("PHPID", $PHPSESSID);
				$tpl->assign("USER_ID2", $row2['user_id']);
				$tpl->assign("AID2", $row2['user_id']);
				if ( $authorization < 2 )
					$tpl->assign("COURSE_ID2", $row4['name']."(".$row2['status'].")");
				else
					$tpl->assign("COURSE_ID2", $row4['name']);
				$tpl->assign("IDLE2", $idlem."分".$idles."秒" );
				$tpl->parse(ROWS, ".system_list");
			}
		}
	}
	$Q8 = "select u.a_id, g.friend_id, u.id, u.name, u.nickname, u.php, u.email from gbfriend g, user u where g.friend_id = u.a_id and g.my_id = '$aid'";
	if ( $result8 = mysql_db_query( $DB, $Q8 ) ) {
		while ( $row8 = mysql_fetch_array( $result8 ) ) {
			$tpl->assign("USER_NICK4", "");
			$Q9 = "select a_id from online where user_id = '".$row8["id"]."'";
			$Q10 = "select mtime from log where event_id = '1' and user_id = '".$row8["a_id"]."'";
			if ( mysql_num_rows( mysql_db_query( $DB, $Q9 ) ) == 0 ) {
				if($row8['nickname'] != NULL ) {
					$name = $row8['nickname'];
				}
				else if ($row8['name'] != NULL) {
					$name = $row8['name'];
				}
				else {
					$name = $row8['id'];
				}
				if ( $row8['name'] != NULL ) {
					$tpl->assign("USER_NAME4", $row8['name']);
					$tpl->assign("USER_NICK4", "(".$name.")");
				}
				else {
					$tpl->assign("USER_NAME4", $name);
					$tpl->assign("USER_NICK4", "(".$row8['nickname'].")");
				}
				if ( $row8['php'] != NULL )
					$tpl->assign("HOME4", "<a href=\"".$row8['php']."\" target=\"_blank\" onClick=\"javascript:urchinTracker('".$row8['php']."');\"><img src=\"/images/homepage.gif\" width = 10 border=0 alt=\"個人網頁\"></a>" );
				else
					$tpl->assign("HOME4", "" );
				if ( $row8['email'] != NULL )
					$tpl->assign("MAIL4", "<a href=\"mailto:".$row8['email']."\" ><img src=\"/images/email.gif\" width = 10 border=0 alt=\"Email\"></a>" );
				else
					$tpl->assign("MAIL4", "" );
				if ( $result10 = mysql_db_query( $DB, $Q10 ) )
					$row10 = mysql_fetch_array( $result10 );
				$tpl->assign("PHPID", $PHPSESSID);
				$tpl->assign("USER_ID4", $row8['id']);
				$tpl->assign("AID4", $row8['id']);
				$tpl->assign("COURSE_ID4", "上次上站時間\n".substr($row10['mtime'],0,4)."/".substr($row10['mtime'],4,2)."/".substr($row10['mtime'],6,2)." ".substr($row10['mtime'],8,2).":".substr($row10['mtime'],10,2).":".substr($row10['mtime'],12,2) );
				$tpl->parse(ROWO, ".friend_oist");
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
?>
