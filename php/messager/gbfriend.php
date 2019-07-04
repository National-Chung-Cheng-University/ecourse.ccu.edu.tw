<?
	require 'fadmin.php';
	$limit = 10;
	if ( !(isset($PHPSESSID) && session_check_teach($PHPSESSID)) ) {
		show_page( "not_access.tpl" ,"權限錯誤");
		exit;
	}
	if ( $user_id == 'guest' ) {
		if ( $version == "C" )
			show_page( "not_access.tpl" ,"你不能設定好友");
		else
			show_page( "not_access.tpl" ,"You may not set friends");
		exit;
	}
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		echo( "資料庫連結錯誤!!");
		exit;
	}


	include("class.FastTemplate.php3");
	$tpl = new FastTemplate("./templates");

	if($version == "C") {
	   	$tpl->define(array(main => "gbfriend.tpl"));
	}
	else {
	   	$tpl->define(array(main => "gbfriend_E.tpl"));
	}
	
	$tpl->define_dynamic("friend_list", "main");

	$Q1 = "select a_id from user where id = '$user_id'";
	if ( $result1 = mysql_db_query( $DB, $Q1 ) ) {
		$row1 = mysql_fetch_array ( $result1 );
		$aid = $row1['a_id'];
	}
	$Q0 = "select tag3 from log where user_id = '$aid' and event_id= '1'";
	if ( $result0 = mysql_db_query( $DB, $Q0 ) ) {
		$row0 = mysql_fetch_array ( $result0 );
		$limit += (int)( $row0["tag3"] / 100 );// - ( $row0["tag3"] % 100 )/100 );
	}

	for ( $i = 1 ; $i <= $limit ; $i ++ ) {
		$f = "friend".$i;
		$o = "online".$i;
		if ( isset($$f) && $$f != "" ) {
			$Q2 = "select a_id from gbfriend where my_id = '$aid' and number = '$i'";
			if ( $result2 = mysql_db_query( $DB, $Q2 ) ) {
				if ( mysql_num_rows($result2) == 0 )
					if ( $$o == 1 )
						$Q3 = "insert into gbfriend ( my_id, friend_id, bgcode, number ) values ( '$aid', '".$$f."', '".$$o."', '$i' )";
					else
						$Q3 = "insert into gbfriend ( my_id, friend_id, bgcode, number ) values ( '$aid', '".$$f."', '0', '$i' )";
				else
					if ( $$o == 1 )
						$Q3 = "update gbfriend set friend_id = '".$$f."', bgcode = '".$$o."' where my_id = '$aid' and number='$i'";
					else
						$Q3 = "update gbfriend set friend_id = '".$$f."', bgcode = '0' where my_id = '$aid' and number='$i'";
				mysql_db_query( $DB, $Q3 );
			}
		}
		else if ( isset($$f) && $$f == "" ){
			$Q2 = "delete from gbfriend where my_id = '$aid' and number = '$i'";
			mysql_db_query( $DB, $Q2 );
		}	
	}
	$tpl->assign(LIMIT, $limit);
	for ( $i = 1; $i <= $limit ; $i ++ ) {
		$Q4 = "select g.friend_id, g.bgcode, u.id, u.name, u.nickname from gbfriend g, user u where g.my_id = u.a_id and u.id = '$user_id' and g.number='$i'";
		if ( $result4 = mysql_db_query( $DB, $Q4 ) ) {
			$row4 = mysql_fetch_array( $result4 );
			$tpl->assign(NUM, $i );
			$tpl->assign(FRIEND, $row4['friend_id']);
			if ( $row4['bgcode'] == 1 )
				$tpl->assign(CHECK, "checked" );
			else
				$tpl->assign(CHECK, "" );
			$Q5 = "select id, name, nickname from user where a_id ='".$row4['friend_id']."'";
			$result5 = mysql_db_query( $DB, $Q5 );
			$row5 = mysql_fetch_array( $result5 );
			if ( $row5['name'] != NULL )
				$tpl->assign(NAME, $row5['name']);
			else if ( $row5['nickname'] != NULL )
				$tpl->assign(NAME, $row5['nickname']);
			else 
				$tpl->assign(NAME, $row5['id']);
			$tpl->parse(ROWF, ".friend_list");
		}
	}

	$tpl->parse(BODY, "main");
	$tpl->FastPrint(BODY);
?>