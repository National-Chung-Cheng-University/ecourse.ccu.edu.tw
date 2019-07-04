<?
	require 'fadmin.php';

	if ( !(isset($PHPSESSID) && session_check_teach($PHPSESSID)) ) {
		show_page( "not_access.tpl" ,"權限錯誤");
		exit;
	}
	if ( $user == "guest" ) {
		if ( $version == "C" )
			show_page( "not_access.tpl" ,"此使用者無法接收訊息");
		else
			show_page( "not_access.tpl" ,"He may not receive message");
		exit;
	}
	if ( $user == "" ) {
		if ( $version == "C" )
			show_page( "not_access.tpl" ,"GUEST無法發送訊息");
		else
			show_page( "not_access.tpl" ,"GUEST can't send message");
		exit;
	}

	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	$Q0 = "select nickname, a_id, name, id from user where id = '$user'";
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		echo( "資料庫連結錯誤!!" );
		exit;
	}
	
	if ( ($result0 = mysql_db_query( $DB, $Q0 )) ) {
		if ( mysql_num_rows ( $result0 ) == 0 ) {
			show_page( "not_access.tpl" ,"使用者不存在");
			exit;
		}
		else {
			$row0 = mysql_fetch_array( $result0 );
		}
	}

	$Q1 = "select a_id from user where id = '$user_id'";
	if ( $result1 = mysql_db_query( $DB, $Q1 ) ) {
		$row1 = mysql_fetch_array( $result1 );
	}
	
	if ( $message != "" ) {
		$message = htmlspecialchars ( $message );
		$Q2 = "insert into message ( send, receive, message, time ) values ( '".$row1['a_id']."', '".$row0['a_id']."', '$message', '".date("Y/m/d H:i:s",time())."')";
		if ( mysql_db_query( $DB, $Q2 ) )
			$send = 1;
	}

	include("class.FastTemplate.php3");
	$tpl = new FastTemplate("./templates");
        
	if($version == "C") {
	   	$tpl->define(array(main => "messager.tpl"));
	}
	else {
	   	$tpl->define(array(main => "messager_E.tpl"));
	}

	if ( $row0['nickname'] != NULL )
		$name = $row0['nickname'];
	else if ( $row0['name'] != NULL )
		$name = $row0['name'];
	else
		$name = $row0['id'];
	$friend = 0;
	$Q3 = "select a_id from gbfriend where my_id = '".$row1['a_id']."' and friend_id = '".$row0['a_id']."'";
	if ( $result3 = mysql_db_query( $DB, $Q3) )
		if ( mysql_num_rows( $result3 ) != 0 || $row1['a_id'] == $row0['a_id'] )
			$friend = 1;
	if ( $friend == 1) {
		if ( $row0['name'] != NULL )
			$name = $row0['name'];
		else
			$name = $row0['id'];
	}

	
	if ( $send == 1 )
		$tpl->assign("CLOSE", "");
	else
		$tpl->assign("CLOSE", "//");
	
	if ( $back != NULL )
		$tpl->assign("HAVE", "");
	else
		$tpl->assign("HAVE", "//");

	$back = stripslashes( $back );
	$back = htmlspecialchars ( $back );
	$back2 = str_replace ( ":)", "<img src=/images/face/1.gif>", $back );
	$back2 = str_replace ( ":d", "<img src=/images/face/2.gif>", $back2 );
	$back2 = str_replace ( ":o", "<img src=/images/face/3.gif>", $back2 );
	$back2 = str_replace ( ":p", "<img src=/images/face/4.gif>", $back2 );
	$back2 = str_replace ( ":@", "<img src=/images/face/5.gif>", $back2 );
	$back2 = str_replace ( ":s", "<img src=/images/face/6.gif>", $back2 );
	$back2 = str_replace ( ":$", "<img src=/images/face/7.gif>", $back2 );
	$back2 = str_replace ( ":(", "<img src=/images/face/8.gif>", $back2 );
	$back2 = str_replace ( ":'(", "<img src=/images/face/9.gif>", $back2 );
	$back2 = str_replace ( ":|", "<img src=/images/face/10.gif>", $back2 );
	$back2 = str_replace ( "(i)", "<img src=/images/face/11.gif>", $back2 );
	$back2 = str_replace ( "(l)", "<img src=/images/face/12.gif>", $back2 );
	$back2 = str_replace ( "(k)", "<img src=/images/face/13.gif>", $back2 );
	$back2 = str_replace ( "(ll)", "<img src=/images/face/14.gif>", $back2 );
	$back2 = str_replace ( ":-)", "<img src=/images/face/1.gif>", $back2 );
	$back2 = str_replace ( ":-D", "<img src=/images/face/2.gif>", $back2 );
	$back2 = str_replace ( ":-O", "<img src=/images/face/3.gif>", $back2 );
	$back2 = str_replace ( ":-P", "<img src=/images/face/4.gif>", $back2 );
	$back2 = str_replace ( ":-@", "<img src=/images/face/5.gif>", $back2 );
	$back2 = str_replace ( ":-S", "<img src=/images/face/6.gif>", $back2 );
	$back2 = str_replace ( ":-$", "<img src=/images/face/7.gif>", $back2 );
	$back2 = str_replace ( ":-(", "<img src=/images/face/8.gif>", $back2 );
	$back2 = str_replace ( ":-|", "<img src=/images/face/10.gif>", $back2 );
	$back2 = str_replace ( "(I)", "<img src=/images/face/11.gif>", $back2 );
	$back2 = str_replace ( "(L)", "<img src=/images/face/12.gif>", $back2 );
	$back2 = str_replace ( "(K)", "<img src=/images/face/13.gif>", $back2 );
	$back2 = str_replace ( "(LL)", "<img src=/images/face/14.gif>", $back2 );
	if ( $back != NULL )
		if ( $multi == 1 )
			$tpl->assign("BACK", "<pre>From: $name(通告)\n".$back2."\n$posttime</pre>");
		else
			$tpl->assign("BACK", "<pre>From: $name\n".$back2."\n$posttime</pre>");
	else
		$tpl->assign("BACK", "" );
	if ( $back != "" ) {
		$body = explode("\r\n",$back);
		for($i=0;$i<sizeof($body);$i++) {
			$body[$i] = "&gt".$body[$i]; 
		}
		$body = implode("\r\n",$body);
	}
	$tpl->assign("MESSAGE", $body);
	$tpl->assign("USER_NAME", $name);
	$tpl->assign("AID", $user);

	$tpl->parse(BODY, "main");
	$tpl->FastPrint(BODY);
?>