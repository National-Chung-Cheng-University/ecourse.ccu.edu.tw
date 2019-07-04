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

		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $PHPSESSID;
		$content_array;
		$content;
		$Q1 = "select * from memo where user_id = '".GetUserAID($user_id)."' and year = '$year' and month = '$month' ";
		($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) or die ("資料庫連結錯誤!!");
		($result = mysql_db_query( $DB, $Q1 ) ) or die ("資料庫讀取錯誤!!$Q1");
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
//		if ( $action == "edit" || $content[$day] == "" ) {
		$tpl->define(array ( main => "month.tpl" ));
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
	
	function GetUserAID($user_id) {

		global $DB;

		$sql = "select a_id from user where id='$user_id'";
		$result = mysql_db_query($DB, $sql) or die("資料庫查詢錯誤, $sql");

		// check name field. if exists, use it as poster name.
		$row = mysql_fetch_array( $result );
		
		return $row['a_id'];
	}
?>