<?php
	require 'fadmin.php';
	update_status ("課程安排");
	if ( isset($PHPSESSID) && ($check = session_check_teach($PHPSESSID)) ) {
		if ( (!isset($flag) || $flag=="0") && $check == 2 ) {
			show_page_d ( );
		}
		else if ( $flag == "1" && $check == 2 ) {
			if ( $AddKind == 1 )
				$error = add_sched( );
			else
				$error = add_sched2( );
			show_page_d ( $error );
		}
		else if ( $check == 1 || $check == 3)
			show_page_d ( );
		else
			show_page ( $course_id, "not_access.tpl", "權限錯誤" );
	}
	else
		show_page ( "not_access.tpl", "權限錯誤" );
	
	function add_sched ( ) {
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id, $year, $month, $day , $week, $subject, $unit, $version;
		if ( !checkdate( $month, $day , $year ) ) {
			if ( $version == "C" )
				$error = "日期錯誤!!";
			else
				$error = "Date Error!!";
			return $error;
		}
		$Q1 = "select idx FROM course_schedule WHERE idx='$week'";
		$Q2 = "insert into course_schedule ( day, idx, subject) values ('$year-$month-$day', '$week', '$subject')";
		$Q3 = "update course_schedule set day='$year-$month-$day', subject='$subject' WHERE idx='$week'";
		$Q4 = "delete from course_schedule WHERE idx='$week'";
		$Q5 = "update course set schedule_unit = '$unit' where a_id = '$course_id'";
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "資料庫連結錯誤!!";
			return $error;
		}
		mysql_db_query( $DB, $Q5 );
		if ( mysql_num_rows( mysql_db_query( $DB.$course_id, $Q1 ) ) == 0 ) {
			if ( $subject == "" ) {
				if ( $version == "C" )
					$error = "請填寫標題!!";
				else
					$error = "Please Input Subject!!";
				return $error;
			}
			else if ( !($result = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
				$error = "資料庫寫入錯誤!!";
				return $error;
			}
		}
		else {
			if ( $subject == "" ) {
				if ( !($result = mysql_db_query( $DB.$course_id, $Q4 ) ) ) {
					$error = "資料庫刪除錯誤!!";
					return $error;
				}
			}
			else if ( !($result = mysql_db_query( $DB.$course_id, $Q3 ) ) ) {
				$error = "資料庫更新錯誤!!";
				return $error;
			}
		}
		return "";
	}

	function add_sched2 ( ) {
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id, $year, $month, $day , $inswk, $subject, $unit;
		if ( !checkdate( $month, $day , $year ) ) {
			if ( $version == "C" )
				$error = "日期錯誤!!";
			else
				$error = "Date Error!!";
			return $error;
		}
		if ( $inswk == 0 ) {
			if ( $version == "C" )
				$error = "請選擇插入點!!";
			else
				$error = "Insert Point Error!!";
			return $error;
		}
		if ( $inswk != 1 )
			$week = $inswk-1;
		else
			$week = $inswk;
		$Q1 = "select idx FROM course_schedule WHERE idx='$week'";
		$Q2 = "insert into course_schedule (day, idx, subject) values ('$year-$month-$day', '$week', '$subject')";
		$Q3 = "select idx FROM course_schedule order by idx DESC";
		$Q4 = "insert into course_schedule (day, idx, subject) values ('$year-$month-$day', '$inswk', '$subject')";
		$Q5 = "update course set schedule_unit = '$unit' where a_id = '$course_id'";
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "資料庫連結錯誤!!";
			return $error;
		}
		mysql_db_query( $DB, $Q5 );
		if ( mysql_num_rows( mysql_db_query( $DB.$course_id, $Q1 ) ) == 0 ) {
			if ( $subject == "" ) {
					if ( $version == "C" )
					$error = "請填寫標題!!";
				else
					$error = "Please Input Subject!!";
				return $error;
			}
			else if ( !($result = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
				$error = "資料庫寫入錯誤!!";
				return $error;
			}
		}
		else {
			if ( $subject == "" ) {
				$error = "請填寫標題!!";
				return $error;
			}
			else {
				if ( !($result3 = mysql_db_query( $DB.$course_id, $Q3 )) ) {
					$error = "資料庫讀取錯誤!!";
					return $error;
				}
				while ( $row3 = mysql_fetch_array( $result3 ) ) {
					$Q3 = "update course_schedule set idx= idx+1 WHERE idx = '".$row3['idx']."'";
					if ( $row3['idx'] >= $inswk  ) {
						if ( !($result = mysql_db_query( $DB.$course_id, $Q3 ) ) ) {
							$error = "資料庫更新錯誤!!";
							return $error;
						}
					}
				}
				if ( !($result = mysql_db_query( $DB.$course_id, $Q4 ) ) ) {
					$error = "資料庫寫入錯誤!!";
					return $error;
				}
			}
		}
		return "";
	}

	function show_page_d ( $message="" ) {
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id, $check, $version, $skinnum, $week;
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		$tpl->define ( array ( head => "show_schedb.tpl" ) );
		$tpl->define_dynamic ( "sch_list" , "head" );
		$Q1 = "select schedule_unit from course where a_id = '$course_id'";
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "$error 資料庫連結錯誤!!";
		}
		
		if ( !($result1 = mysql_db_query( $DB, $Q1 ) ) ) {
			$error = "$error 資料庫讀取錯誤!!";
		}
		else
			$row1 = mysql_fetch_array( $result1 );
		$color = "#000066";
		$tpl->assign( SKINNUM , $skinnum );
		$tpl->assign( COLOR , $color );
		if ( $version == "C" ) {
			$tpl->assign( TITLE , "課程安排" );
			$tpl->assign( WEEK , "<font color=#FFFFFF>期數(".$row1['schedule_unit'].")</font>" );
			$tpl->assign( DAY , "<font color=#FFFFFF>日期</font>" );
			$tpl->assign( SUBJECT, "<font color=#FFFFFF>內容</font>" );
		}
		else {
			$tpl->assign( TITLE , "Course Schedule" );
			if ( $row1['schedule_unit'] == "月" )
				$tpl->assign( WEEK , "<font color=#FFFFFF>Index(Month)</font>" );
			else if ( $row1['schedule_unit'] == "天" )
				$tpl->assign( WEEK , "<font color=#FFFFFF>Index(Day)</font>" );
			else if ( $row1['schedule_unit'] == "次" )
				$tpl->assign( WEEK , "<font color=#FFFFFF>Index(Time)</font>" );
			else 
				$tpl->assign( WEEK , "<font color=#FFFFFF>Index(Week)</font>" );
			$tpl->assign( DAY , "<font color=#FFFFFF>Date</font>" );
			$tpl->assign( SUBJECT, "<font color=#FFFFFF>Subject</font>" );
		}
		
		$tpl->parse ( SCH_LIST, ".sch_list" );
		$color = "#CCCCCC";
		$Q2 = "select cs.idx, cs.day, cs.subject FROM course_schedule cs ORDER by idx";
			
		if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
			$error = "$error 資料庫讀取錯誤!!";
		}
		else {
			while ( $row2 = mysql_fetch_array( $result2 ) ) {
				if ( $color == "#E6FFFC" )
					$color = "#F0FFEE";
				else
					$color = "#E6FFFC";
				$tpl->assign( COLOR , $color );
				if ( $version == "C" )
					$tpl->assign( WEEK , "第".$row2['idx'].$row1['schedule_unit'] );
				else
					$tpl->assign( WEEK , $row2['idx'] );
				$tpl->assign( DAY , $row2['day'] );
				$tpl->assign( SUBJECT,"<pre>". $row2['subject'] ."</pre>");
				$tpl->parse ( SCH_LIST, ".sch_list" );
				$max_idx = $row2['idx'];
			}
		}		
		if ( $check == 2 ) {
			//若資料已存在，則取出資料放在相對應的欄位
			if(!isset($week)){
				$week = 1;
			}
			$Q4 = "SELECT * FROM course_schedule WHERE idx = '".$week."'";
			if ( !($result4 = mysql_db_query( $DB.$course_id, $Q4 ) ) ) {
				$error = "$error 資料庫讀取錯誤!!";
			}
			if( ($row4 = mysql_fetch_array( $result4 )) != NULL ){
				$tpl->assign(SUJT,$row4['subject']);
				$date = explode("-", $row4['day']);
				$dy = $date[0];
				$dm = $date[1];
				$dd = $date[2];
			}
			else{
				$tpl->assign(SUJT,"");
				$dy = date("Y");
				$dm = date("n");
				$dd = date("j");
			}
			//
			if ( $version == "C" )
				$tpl->define ( array ( tail => "show_schedt.tpl" ) );
			else
				$tpl->define ( array ( tail => "show_schedt_E.tpl" ) );
			$tpl->define_dynamic ( "week_list" , "tail" );
			$tpl->define_dynamic ( "ins_list" , "tail" );
			$tpl->define_dynamic ( "year_list" , "tail" );
			$tpl->define_dynamic ( "m_list" , "tail" );
			$tpl->define_dynamic ( "d_list" , "tail" );

			if ( $row1['schedule_unit'] == "月" )
				$tpl->assign( U1 , "selected" );
			else if ( $row1['schedule_unit'] == "天" )
				$tpl->assign( U3 , "selected" );
			else if ( $row1['schedule_unit'] == "次" )
				$tpl->assign( U4 , "selected" );
			else 
				$tpl->assign( U2 , "selected" );
			
			$tpl->assign( INSV , "0" );
			if ( $version == "C" )
				$tpl->assign( INSD , "請選擇插入點" );
			else
				$tpl->assign( INSD , "Choise Point" );
			$tpl->parse ( Ins_list, ".ins_list" );
			$result2 = mysql_db_query( $DB.$course_id, $Q2 );
			while ( $row3 = mysql_fetch_array( $result2 ) ) {
				$tpl->assign( INSV , $row3['idx'] );
				if ( $version == "C" )
					$tpl->assign( INSD , "第".$row3['idx'].$row1['schedule_unit']."之前" );
				else
					$tpl->assign( INSD , "Before".$row3['idx'] );
				$tpl->parse ( Ins_list, ".ins_list" );
			}
			for ( $i = 1 ; $i <= $max_idx+10 ; $i++ ) {
				if ( $i == $week )
					$tpl->assign( WEV , $i." selected" );
				else
					$tpl->assign( WEV , $i );
				$tpl->assign( WED , $i );
				$tpl->parse ( Weak_list, ".week_list" );
			}
			if($dy < date("Y")){
				$nowy = $dy;
			}
			else{
				$nowy = date("Y");
			}
			for ( $i = 0 ; $i <= 10 ; $i++ ) {
				$y = $nowy + $i;
				if ( $y == $dy )
					$tpl->assign( YEV , $y ." selected" );
				else
					$tpl->assign( YEV , $y );
				$tpl->assign( YED , $y );
				$tpl->parse ( Year_list, ".year_list" );
			}
			for ( $i = 1 ; $i <= 12 ; $i++ ) {
				if ( $i == $dm)
					$tpl->assign( MOV , $i . " selected");
				else
					$tpl->assign( MOV , $i );
				$tpl->assign( MOD , $i );
				$tpl->parse ( M_LIST, ".m_list" );
			}
			for ( $i = 1 ; $i <= 31 ; $i++ ) {
				if ( $i == $dd)
					$tpl->assign( DAV , $i . " selected");
				else
					$tpl->assign( DAV , $i );
				$tpl->assign( DAD , $i );
				$tpl->parse ( D_LIST, ".d_list" );
			}
			$tpl->assign( ENDLINE , "" );	
		}
		else
			$tpl->assign( ENDLINE , "</center></body></html>" );

		$tpl->assign( MEG, $message );
		$tpl->assign( MES, $error );
		$tpl->parse( HEAD, "head" );
		$tpl->FastPrint("HEAD");
		
		if ( $check == 2 ) {
			$tpl->parse( TAIL, "tail" );	
			$tpl->FastPrint("TAIL");
		}			
	}
?>
