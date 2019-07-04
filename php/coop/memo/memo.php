<?
	require 'fadmin.php';
	update_status ("行事曆");

	if ( !(isset($PHPSESSID) && session_check_teach($PHPSESSID) && ($check = check_group ( $course_id, $coopgroup, $coopcaseid )) >= 2 ) ) {
		show_page( "not_access.tpl" ,"權限錯誤");
	}

	if ( $action == "update" ){
		do_upadte();
	}
	else {
		show_main( $year, $month, $day );
	}

	function show_main ( $year=0, $month=0, $day=0 ) {
		global $version, $check, $skinnum, $teacher, $course_id, $coopgroup, $coopcaseid, $user_id, $action;
		$year = ( ($year == 0 || $year == "") ? date("Y") : $year);
		$month = ( ($month == 0 || $month == "") ? date("m") : $month);
		$day = ( ($day == 0 || $day == "") ? ( ($year == date("Y") && $month == date("m")) ? date("d") : 1) : $day);

		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
		$content_array;
		$content;
		$Q1 = "select * from memo where user_id = '".GetUserAID($user_id)."' and year = '$year' and month = '$month' ";
		($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) or die ("資料庫連結錯誤!!");
		($result = mysql_db_query( $DB, $Q1 ) ) or die ("資料庫讀取錯誤!!");
		if ( mysql_num_rows( $result ) != 0 ) {
			while ( $row = mysql_fetch_array( $result ) ) {
				if ( $row['content'] != "" ) {
					$content_array[$row['day']] = 1;
					$content[$row['day']] = $row['content'];
				}
			}
		}
			
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		if ( $action == "edit" || $content[$day] == "" ) {
			if ( $version == "C" ) {
				$tpl->define(array ( main => "memo_edit.tpl" ));
			}
			else {
				$tpl->define(array ( main => "memo_edit_E.tpl" ));
			}
		}
		else {
			if ( $version == "C" ) {
				$tpl->define(array ( main => "memo.tpl" ));
			}
			else {
				$tpl->define(array ( main => "memo_E.tpl" ));
			}
		}
		$tpl->define_dynamic ( "day_list" , "main" );
		
		$limit = checkdate( $month, 31, $year ) ? 31 : (checkdate ( $month, 30, $year ) ? 30 : (checkdate( $month, 29, $year ) ? 29 : 28 ) );
		
		$show_day = getdate ( mktime(0,0,0,$month,1,$year ) );
//		$limit = $show_day['mday'];
		$tpl->assign("YEAR", $year);
		$tpl->assign("MONTH", $month);
		$tpl->assign("DY", $day);
		$k = 0;
		for ( $i = 1; $i <= $limit ; $i ++ ) {
			if ( $i == 1 ) {
				for ( $j = 0 ; $j < $show_day['wday'] ; $j ++ ) {
					$tpl->assign("C".$j, "#DDDD44");
					$tpl->assign("W".$j, "　");
				}
				$k = $j;
			}
			if ( $content_array[$i] == 1 ) {
				$tpl->assign("C".$k, "#EEAAAA");
			}
			else if ( $i == date("d") && $year == date("Y") && $month == date("m") ) {
				$tpl->assign("C".$k, "#AAEEAA");
			}else if ( $i == $day ) {
				$tpl->assign("C".$k, "#AAAAEE");
			}
			else {
				$tpl->assign("C".$k, "#DDDD44");
			}
			$tpl->assign("W".$k, "$i");
			if ( $k == 6 ) {
				$tpl->parse ( DAY_LIST, ".day_list" );
				$k = 0;
				continue;
			}
			$k ++;
		}
		if ( $k != 0 ) {
			for ( $i = $k ; $i <= 6 ; $i ++ ) {
				$tpl->assign("C".$i, "#DDDD44");
				$tpl->assign("W".$i, "　");
			}
			$tpl->parse ( DAY_LIST, ".day_list" );
		}
		$pre_month = getdate ( mktime(0,0,0,$month,1,$year )-1 );
		$next_month = getdate ( mktime(23,59,59,$month,$limit,$year )+1 );
		$tpl->assign("PREM", $pre_month['mon']);
		$tpl->assign("PREY", $pre_month['year']);
		$tpl->assign("NEXM", $next_month['mon']);
		$tpl->assign("NEXY", $next_month['year']);
		
		$pre_day = getdate ( mktime(0,0,0,$month,$day,$year )-1 );
		$next_day = getdate ( mktime(23,59,59,$month,$day,$year )+1 );
		$tpl->assign("PREDAD", $pre_day['mday']);
		$tpl->assign("PREDAM", $pre_day['mon']);
		$tpl->assign("PREDAY", $pre_day['year']);
		$tpl->assign("NEXDAD", $next_day['mday']);
		$tpl->assign("NEXDAM", $next_day['mon']);
		$tpl->assign("NEXDAY", $next_day['year']);

		if ( $version == "C" ) {
			$week = array ( "星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六" );
			$show_week = getdate(mktime(0,0,0,$month,$day,$year ));
			if ( $show_week['wday'] == 0 ) {
				$tpl->assign("WEEK", "<font color=\"#FF0000\">".$week[ $show_week['wday'] ]."</font>" );
			}
			else if ( $show_week['wday'] == 6 ) {
				$tpl->assign("WEEK", "<font color=\"##88AA88\">".$week[ $show_week['wday'] ]."</font>" );
			}
			else {
				$tpl->assign("WEEK", $week[ $show_week['wday'] ]);
			}
		}
		else {
			$tpl->assign("WEEK", $show_day['weekday']);
		}

		$tpl->assign("PHPSID", $PHPSESSID);
		$tpl->assign("SKINNUM", $skinnum);
		if ( $action == "edit" ) {
			$con = $content[$day];
		}
		else {
			$con = str_replace("\n" , "<BR>" , $content[$day]);
		}
		$tpl->assign("TEXT", $con);

		$tpl->parse(BODY, "main");
		$tpl->FastPrint(BODY);
	}
	
	function do_upadte() {
		global $user_id, $year, $month, $day, $content;
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
		$Q1 = "select * from memo where user_id = '".GetUserAID($user_id)."' and year = '$year' and month = '$month' and day='$day'";
		($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) or die ("資料庫連結錯誤!!");
		($result = mysql_db_query( $DB, $Q1 ) ) or die ("資料庫讀取錯誤!!");
		if ( mysql_num_rows( $result ) != 0 ) {
			$row = mysql_fetch_array( $result );
			$Q2 = "update memo set content='$content' where a_id = '".$row['a_id']."'";
		}
		else {
			$Q2 = "insert into memo ( user_id, year, month, day, content ) values ( '".GetUserAID($user_id)."', '$year', '$month', '$day', '$content' )";
		}
		($result = mysql_db_query( $DB, $Q2 ) ) or die ("資料庫寫入錯誤!!");
		$action = "";
		show_main( $year, $month, $day );
	}
	
	function GetUserAID($user_id) {

		global $DB;

		$sql = "select a_id from user where id='$user_id'";
		$result = mysql_db_query($DB, $sql) or die("資料庫查詢錯誤, $sql");

		// check name field. if exists, use it as poster name.
		$row = mysql_fetch_array( $result );
		
		return $row['a_id'];
	}
?>