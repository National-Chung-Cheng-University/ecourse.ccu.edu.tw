<?php
	require 'fadmin.php';
	update_status ("�i�צw��");
	if ( isset($PHPSESSID) && session_check_teach($PHPSESSID) && ($check = check_group ( $course_id, $coopgroup, $coopcaseid )) != 0 ) {
		if ( !isset($flag) && $check == 2 ) {
			show_page_d ( );
		}
		else if ( $flag == "1" && $check == 2 ) {
			if ( $AddKind == 1 )
				$error = add_sched( );
			else
				$error = add_sched2( );
			show_page_d ( $error );
		}
		else if ( $check == 1 || $check == 3 )
			show_page_d ( );
		else
			show_page ( $course_id, "not_access.tpl", "�v�����~" );
	}
	else
		show_page ( "not_access.tpl", "�v�����~" );
	
	function add_sched ( ) {
		global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $course_id, $year, $month, $day , $week, $subject, $unit, $version, $coopcaseid, $coopgroup, $file, $file_name, $content;
		if ( !checkdate( $month, $day , $year ) ) {
			if ( $version == "C" )
				$error = "������~!!";
			else
				$error = "Date Error!!";
			return $error;
		}
		$Q1 = "select idx FROM schedule_".$coopcaseid." WHERE idx='$week' and group_num='$coopgroup'";
		if ( $file != "none" ) {
			$Q2 = "insert into schedule_".$coopcaseid." ( group_num, day, unit, idx, subject, content, file ) values ( '$coopgroup', '$year-$month-$day', '$unit', '$week', '$subject', '$content', '$file_name')";
			$Q3 = "update schedule_".$coopcaseid." set day='$year-$month-$day', subject='$subject', content='$content', file='$file_name', unit='$unit' WHERE idx='$week' and group_num='$coopgroup'";
		}
		else {
			$Q2 = "insert into schedule_".$coopcaseid." ( group_num, day, unit, idx, subject, content) values ( '$coopgroup', '$year-$month-$day', '$unit', '$week', '$subject', '$content')";
			$Q3 = "update schedule_".$coopcaseid." set day='$year-$month-$day', subject='$subject', content='$content', unit='$unit' WHERE idx='$week' and group_num='$coopgroup'";
		
		}
		$Q4 = "delete from schedule_".$coopcaseid." WHERE idx='$week' and group_num='$coopgroup'";
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "��Ʈw�s�����~!!";
			return $error;
		}
		if ( mysql_num_rows( mysql_db_query( $DBC.$course_id, $Q1 ) ) == 0 ) {
			if ( $subject == "" ) {
				if ( $version == "C" )
					$error = "�ж�g���D!!";
				else
					$error = "Please Input Subject!!";
				return $error;
			}
			else if ( !($result = mysql_db_query( $DBC.$course_id, $Q2 ) ) ) {
				$error = "��Ʈw�g�J���~!!";
				return $error;
			}
		}
		else {
			if ( $subject == "" ) {
				if ( !($result = mysql_db_query( $DBC.$course_id, $Q4 ) ) ) {
					$error = "��Ʈw�R�����~!!";
					return $error;
				}
			}
			else if ( !($result = mysql_db_query( $DBC.$course_id, $Q3 ) ) ) {
				$error = "��Ʈw��s���~!!";
				return $error;
			}
		}
		if ( $file != "none" ) {
			if ( !fileupload ( $file, "../../../$course_id/coop/$coopcaseid/$coopgroup/schedule", $file_name ) ) {
				$error = "�ɮפW�ǿ��~!!";
				return $error;
			}
		}
		$content = "";
		$subject = "";
		return "";
	}

	function add_sched2 ( ) {
		global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $course_id, $year, $month, $day , $inswk, $subject, $unit, $coopcaseid, $coopgroup, $file, $file_name, $content;
		if ( !checkdate( $month, $day , $year ) ) {
			if ( $version == "C" )
				$error = "������~!!";
			else
				$error = "Date Error!!";
			return $error;
		}
		if ( $inswk == 0 ) {
			if ( $version == "C" )
				$error = "�п�ܴ��J�I!!";
			else
				$error = "Insert Point Error!!";
			return $error;
		}
		if ( $inswk != 1 )
			$week = $inswk-1;
		else
			$week = $inswk;
		$Q1 = "select idx FROM schedule_".$coopcaseid." WHERE idx='$week' and group_num='$coopgroup'";
		if ( $file != "none" ) {
			$Q2 = "insert into schedule_".$coopcaseid." (group_num, day, unit, idx, subject, content, file ) values ('$coopgroup', '$year-$month-$day', '$unit', '$week', '$subject', '$content', '$file_name')";
			$Q4 = "insert into schedule_".$coopcaseid." (group_num, day, unit, idx, subject, content, file ) values ('$coopgroup','$year-$month-$day', '$unit', '$inswk', '$subject', '$content', '$file_name')";
		}
		else {
			$Q2 = "insert into schedule_".$coopcaseid." (group_num, day, unit, idx, subject, content ) values ('$coopgroup', '$year-$month-$day', '$unit', '$week', '$subject', '$content')";
			$Q4 = "insert into schedule_".$coopcaseid." (group_num, day, unit, idx, subject, content ) values ('$coopgroup','$year-$month-$day', '$unit', '$inswk', '$subject', '$content')";
		}
		$Q3 = "select idx FROM schedule_".$coopcaseid." where group_num='$coopgroup' order by idx DESC";
		
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "��Ʈw�s�����~!!";
			return $error;
		}
		if ( mysql_num_rows( mysql_db_query( $DBC.$course_id, $Q1 ) ) == 0 ) {
			if ( $subject == "" ) {
					if ( $version == "C" )
					$error = "�ж�g���D!!";
				else
					$error = "Please Input Subject!!";
				return $error;
			}
			else if ( !($result = mysql_db_query( $DBC.$course_id, $Q2 ) ) ) {
				$error = "��Ʈw�g�J���~!!";
				return $error;
			}
		}
		else {
			if ( $subject == "" ) {
				$error = "�ж�g���D!!";
				return $error;
			}
			else {
				if ( !($result3 = mysql_db_query( $DBC.$course_id, $Q3 )) ) {
					$error = "��ƮwŪ�����~!!";
					return $error;
				}
				while ( $row3 = mysql_fetch_array( $result3 ) ) {
					$Q3 = "update schedule_".$coopcaseid." set idx= idx+1 WHERE idx = '".$row3['idx']."' and group_num='$coopgroup'";
					if ( $row3['idx'] >= $inswk  ) {
						if ( !($result = mysql_db_query( $DBC.$course_id, $Q3 ) ) ) {
							$error = "��Ʈw��s���~!!";
							return $error;
						}
					}
				}
				if ( !($result = mysql_db_query( $DBC.$course_id, $Q4 ) ) ) {
					$error = "��Ʈw�g�J���~!!";
					return $error;
				}
			}
		}
		if ( !fileupload ( $file, "../../../$course_id/coop/$coopcaseid/$coopgroup/schedule", $file_name ) ) {
			$error = "�ɮפW�ǿ��~!!";
			return $error;
		}
		$content = "";
		$subject = "";
		return "";
	}

	function show_page_d ( $message="" ) {
		global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $course_id, $check, $version, $skinnum, $coopcaseid, $coopgroup, $content, $subject, $PHPSESSID;
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		$tpl->define ( array ( head => "show_schedb.tpl" ) );
		$tpl->define_dynamic ( "sch_list" , "head" );

		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "$error ��Ʈw�s�����~!!";
		}

		$color = "#000066";
		$tpl->assign( SKINNUM , $skinnum );
		$tpl->assign( COLOR , $color );
		if ( $version == "C" ) {
			$tpl->assign( TITLE , "�u�@��x" );
			$tpl->assign( WEEK , "<font color=#FFFFFF size=2>����</font>" );
			$tpl->assign( DAY , "<font color=#FFFFFF size=2>���</font>" );
			$tpl->assign( SUBJECT, "<font color=#FFFFFF size=2>���e</font>" );
		}
		else {
			$tpl->assign( TITLE , "Work Schedule" );
			$tpl->assign( WEEK , "<font color=#FFFFFF>Index</font>" );
			$tpl->assign( DAY , "<font color=#FFFFFF size=2>Date</font>" );
			$tpl->assign( SUBJECT, "<font color=#FFFFFF size=2>Subject</font>" );
		}
		
		$tpl->parse ( SCH_LIST, ".sch_list" );
		$color = "#CCCCCC";
		$Q2 = "select idx, day, subject, unit, content, file FROM schedule_".$coopcaseid." WHERE group_num='$coopgroup' ORDER by idx";
			
		if ( !($result2 = mysql_db_query( $DBC.$course_id, $Q2 ) ) ) {
			$error = "$error ��ƮwŪ�����~!!";
		}
		else {
			while ( $row2 = mysql_fetch_array( $result2 ) ) {
				if ( $color == "#CCCCCC" )
					$color = "#F0FFEE";
				else
					$color = "#CCCCCC";
				$tpl->assign( COLOR , $color );
				if ( $version == "C" )
					$tpl->assign( WEEK , "��".$row2['idx'].$row2['unit'] );
				else {
					if ( $row2['unit'] == "���" )
						$unit = "month";
					else if ( $row2['unit'] == "��" )
						$unit = "day";
					else if ( $row2['unit'] == "��" )
						$unit = "times";
					else 
						$unit = "week";
					$tpl->assign( WEEK , $row2['idx'].$unit );
				}
				$tpl->assign( DAY , $row2['day'] );
				if ( $row2['content'] != NULL || $row2['file'] != NULL ) {
					$tpl->assign( SUBJECT, "<a href=\"#\" onClick=\"window.open('./content.php?idx=".$row2['idx']."&PHPSESSID=$PHPSESSID', '', 'width=500,height=400,resizable=1,scrollbars=1');\">".$row2['subject']."</a>" );
				}
				else {
					$tpl->assign( SUBJECT, $row2['subject'] );
				}
				$tpl->parse ( SCH_LIST, ".sch_list" );
				$max_idx = $row2['idx'];
			}
		}		
		if ( $check == 2 ) {
			if ( $version == "C" )
				$tpl->define ( array ( tail => "show_schedt.tpl" ) );
			else
				$tpl->define ( array ( tail => "show_schedt_E.tpl" ) );
			$tpl->define_dynamic ( "week_list" , "tail" );
			$tpl->define_dynamic ( "ins_list" , "tail" );
			$tpl->define_dynamic ( "year_list" , "tail" );
			$tpl->define_dynamic ( "m_list" , "tail" );
			$tpl->define_dynamic ( "d_list" , "tail" );

			if ( $row2['unit'] == "���" )
				$tpl->assign( U1 , "selected" );
			else if ( $row2['unit'] == "��" )
				$tpl->assign( U3 , "selected" );
			else if ( $row2['unit'] == "��" )
				$tpl->assign( U4 , "selected" );
			else 
				$tpl->assign( U2 , "selected" );
			
			$tpl->assign( INSV , "0" );
			if ( $version == "C" )
				$tpl->assign( INSD , "�п�ܴ��J�I" );
			else
				$tpl->assign( INSD , "Choise Point" );
			$tpl->parse ( Ins_list, ".ins_list" );
			$result2 = mysql_db_query( $DBC.$course_id, $Q2 );
			while ( $row3 = mysql_fetch_array( $result2 ) ) {
				$tpl->assign( INSV , $row3['idx'] );
				if ( $version == "C" )
					$tpl->assign( INSD , "��".$row3['idx'].$row3['unit']."���e" );
				else {
					if ( $row3['unit'] == "���" )
						$unit = "month";
					else if ( $row3['unit'] == "��" )
						$unit = "day";
					else if ( $row3['unit'] == "��" )
						$unit = "times";
					else 
						$unit = "week";
					$tpl->assign( INSD , "Before".$row3['idx'].$unit );
				}
				$tpl->parse ( Ins_list, ".ins_list" );
			}
			for ( $i = 1 ; $i <= $max_idx+10 ; $i++ ) {
				if ( $i == 1 )
					$tpl->assign( WEV , $i." selected" );
				else
					$tpl->assign( WEV , $i );
				$tpl->assign( WED , $i );
				$tpl->parse ( Weak_list, ".week_list" );
			}
			for ( $i = 0 ; $i <= 10 ; $i++ ) {
				$y = date("Y") + $i;
				if ( $i == 0 )
					$tpl->assign( YEV , $y ." selected" );
				else
					$tpl->assign( YEV , $y );
				$tpl->assign( YED , $y );
				$tpl->parse ( Year_list, ".year_list" );
			}
			for ( $i = 1 ; $i <= 12 ; $i++ ) {
				if ( date("n") == $i)
					$tpl->assign( MOV , $i . " selected");
				else
					$tpl->assign( MOV , $i );
				$tpl->assign( MOD , $i );
				$tpl->parse ( M_LIST, ".m_list" );
			}
			for ( $i = 1 ; $i <= 31 ; $i++ ) {
				if ( date("j") == $i)
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
			$tpl->assign( TEXT , $content );
			$tpl->assign( SUBJECT , $subject );
			$tpl->parse( TAIL, "tail" );	
			$tpl->FastPrint("TAIL");
		}			
	}
?>