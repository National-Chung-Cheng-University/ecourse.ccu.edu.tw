<?php
	require 'fadmin.php';
	if ( !(isset($PHPSESSID) && session_check_stu($PHPSESSID)) ) {
		show_page( "not_access.tpl" ,"權限錯誤" );
	}
	else {
		session_register("course_id");
		$course_id = $courseid;
	}

	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $skinnum;
	$Q1 = "SELECT u.a_id FROM teach_course tc, user u where tc.teacher_id = u.a_id and u.id = '$user_id' and tc.course_id = '$course_id'";
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$error = "資料庫連結錯誤!!";
		show_page ( "not_access.tpl", $error );
	}
	else if ( !($result = mysql_db_query( $DB, $Q1  )) ) {
		$error = "資料庫讀取錯誤!!";
		show_page ( "not_access.tpl", $error );
	}
	else if ( mysql_num_rows( mysql_db_query( $DB, $Q1 ) ) == 0 )
		$teach = 1;
	else
		$teach = 0;

	if( $guest == "1" ) {
		if( $version == "C" )
			$error = "此網頁禁止GUEST進入!";
		else
			$error = "GUEST can't login!";
		show_page ( "not_access.tpl", $error );
        }
	else if( $teach == 1 ){
		if( $version == "C" )
			$error = "此網頁只准該課的任課教授進入!";
		else
			$error = "Only this course's student!";
		show_page ( "not_access.tpl", $error );
	}
	
	$C = "SELECT name FROM course where a_id = '$course_id'";
	if ( !($resultc = mysql_db_query( $DB, $C  )) ) {
		$error = "資料庫讀取錯誤!!";
		show_page ( "not_access.tpl", $error );
	}
	else if ( $rowc = mysql_fetch_array ( $resultc ) ) {
		$cname = $rowc['name'];
	}

	if ( ( $submit == "全部重置" || $submit == "ALL" ) && $flag==1 && isset($flag) ) {
//	if ( isset($all) && $all == 1 ) {
		$flag = 1;
		$news = 1;
		$sched = 1;
		$discuss = 1;
		$test_online = 1;
		$work = 1;
		$grade = 1;
		$trace = 1;
		$stud = 1;
		$on_line = 1;
		$chap = 1;
		$ta == 1;
	}

	$backup_dir = "/backup/$courseid";
	if ( !is_dir( $backup_dir ) ) {
		mkdir ( $backup_dir, 0771 );
		chmod ( $backup_dir, 0771 );
	}
	if ( $chap == 1 || $discuss == 1 || $work == 1 || $on_line == 1 ) {
		symlink( "../../$courseid", $courseid );
		$target1 = "course$courseid-".date("YmdHis").".tar.gz";
		exec ( "tar -zcvf $backup_dir/$target1 $courseid/*" );
		unlink ( $courseid );
	}
	if ( $stud == 1 ) {
		symlink( "../../studentPage", "studentPage" );
		$target1 = "stupage$courseid-".date("YmdHis").".tar.gz";
		exec ( "tar -zcvf $backup_dir/$target1 studentPage/*" );
		unlink ( "studentPage" );
		symlink( "/usr/local/mysql/data/study", "study" );
		$target1 = "study-".date("YmdHis").".tar.gz";
		exec( "tar -zcvf $backup_dir/$target1 study/*" );
		unlink ( "study" );
	}
	symlink( "/usr/local/mysql/data/study$courseid", "study$courseid" );
	$target1 = "study$courseid-".date("YmdHis").".tar.gz";
	exec( "tar -zcvf $backup_dir/$target1 study$courseid/*" );
	unlink ( "study$courseid" );

	if ( isset($flag) && $flag == 1 ) {
		$D1 = "delete from news";
		$D2 = "delete from course_schedule";
		$D3 = "delete from discuss_info";
		$D4 = "delete from discuss_group";
		$D5 = "delete from take_exam";
		$D6 = "delete from log where event_id != '8'";
		$D7 = "delete from log where event_id = '8'";
		$D8 = "delete from chap_title";
		$D9 = "delete from discuss_subscribe";
		$D10 = "delete from exam";

		if ( $news == 1) {
			mysql_db_query( $DB.$courseid, $D1 );
			mysql_db_query( $DB.$courseid, $D7 );
		}
		if ( $sched == 1 )
			mysql_db_query( $DB.$courseid, $D2 );
		if ( $discuss == 1 ) {
			$H1 = "select a_id from discuss_info";
			if ( $result = mysql_db_query( $DB.$courseid, $H1 ) ) {
				while ($row = mysql_fetch_array( $result )) {
					$H2 = "drop table discuss_".$row['a_id'];
					mysql_db_query( $DB.$courseid, $H2 );
					$target = "../../".$courseid."/board/".$row['a_id'];
					if ( is_dir($target) )
						deldir ( $target );
				}
			}
			mysql_db_query( $DB.$courseid, $D3 );
			mysql_db_query( $DB.$courseid, $D4 );
			mysql_db_query( $DB.$courseid, $D9 );
		}
		if ( $test_online == 1 ) {
			$E1 = "select a_id from exam where is_online ='1'";
			if( $result = mysql_db_query( $DB.$courseid, $E1 ) ) {
				while ( $row = mysql_fetch_array( $result ) ) {
					$E2 = "delete from take_exam where exam_id = '". $row['a_id']."'";
					$E3 = "delete from tiku where exam_id = '". $row['a_id']."'";
					mysql_db_query( $DB.$courseid, $E2 );
					mysql_db_query( $DB.$courseid, $E3 );
				}
			}
		}
		if ( $work == 1 ) {
			$W1 = "select a_id from homework";
			if ( $result = mysql_db_query( $DB.$courseid, $W1 ) ) {
				while ($row = mysql_fetch_array( $result )) {
					$target = "../../$courseid/homework/".$row['a_id'];
					if ( is_dir($target) )
						deldir ( $target );
				}
			}
			$W2 = "delete from handin_homework";
			$W3 = "delete from homework";
			mysql_db_query( $DB.$courseid, $W2 );
			mysql_db_query( $DB.$courseid, $W3 );
		}
		if ( $grade == 1 ) {
			if ( $list != "" || $list2 != "" ) {
				if ( $list != "" ) {
					$G2 = "delete from take_exam where $list";
					mysql_db_query( $DB.$courseid, $G2 );
				}
				if ( $list != "" ) {
					$G3 = "delete from exam where $list2";
					mysql_db_query( $DB.$courseid, $G3 );
				}
			}
			else {
				mysql_db_query( $DB.$courseid, $D5 );
				mysql_db_query( $DB.$courseid, $D10 );
			}
		}
		if ( $trace == 1 )
			mysql_db_query( $DB.$courseid, $D6 );
		if ( $stud == 1 ) {
			$U1 = "select tc.student_id, u.id from take_course tc, user u where tc.course_id = '$courseid' and tc.student_id = u.a_id";
			$U2 = "delete from take_course where course_id = '$courseid'";
			if ( !($result1 = mysql_db_query( $DB, $U1 ) ) ) {
				$error = "資料庫讀取錯誤!!";
			}
			if ( !( mysql_db_query( $DB, $U2 ) ) ) {
				$error = "資料庫刪除錯誤!!";
			}
			while ( $row1 = mysql_fetch_array( $result1 ) ) {
				$U3 = "select student_id from take_course where student_id = '".$row1['student_id']."'";
				if ( $result = mysql_db_query( $DB, $U3 ) ) {
					if ( mysql_num_rows( $result ) == 0 ) {
						$U4 = "delete from user where a_id = '".$row1['student_id']."'";
						$U5 = "delete from log where user_id = '".$row1['student_id']."'";
						$U6 = "delete from gbfriend where my_id = '".$row1['student_id']."' or friend_id='".$row1['student_id']."'";
						$U7 = "delete from handin_homework where student_id = '".$row1['student_id']."'";
						$U8 = "delete from take_exam where student_id = '".$row1['student_id']."'";
						$U9 = "delete from take_questionary where student_id = '".$row1['student_id']."'";
						
						if ( !( mysql_db_query( $DB, $U4 ) ) ) {
							$error = "資料庫刪除錯誤!!";
						}
						if ( !( mysql_db_query( $DB.$courseid, $U5 ) ) ) {
							$error = "資料庫刪除錯誤!!";
						}
						if ( !( mysql_db_query( $DB, $U5 ) ) ) {
							$error = "資料庫刪除錯誤!!";
						}
						if ( !( mysql_db_query( $DB, $U6 ) ) ) {
							$error = "資料庫刪除錯誤!!";
						}
						if ( !( mysql_db_query( $DB.$courseid, $U7 ) ) ) {
							echo( "資料庫刪除錯誤!!");
						}
						if ( !( mysql_db_query( $DB.$courseid, $U8 ) ) ) {
							echo( "資料庫刪除錯誤!!");
						}
						if ( !( mysql_db_query( $DB.$courseid, $U9 ) ) ) {
							echo( "資料庫刪除錯誤!!");
						}
						
						//coop
						$resultcoop = mysql_db_query( $DBC.$course_id, "Select a_id From coop");
						while($row_coop = mysql_fetch_array ( $resultcoop )) {
							mysql_db_query( $DBC.$course_id, "Delete From coop_".$row_coop['a_id']."_group Where student_id='".$row1['id']."'");
							mysql_db_query( $DBC.$course_id, "Delete From discuss_".$row_coop['a_id']."_subscribe Where user_id='".$row1['id']."'");
							mysql_db_query( $DBC.$course_id, "Delete From grade_".$row_coop['a_id']." Where give_id='".$row1['student_id']."' or gived_id ='".$row1['student_id']."'");
							mysql_db_query( $DBC.$course_id, "Delete From guestbook_".$row_coop['a_id']." Where user_id='".$row1['student_id']."'");
							mysql_db_query( $DBC.$course_id, "Delete From log_".$row_coop['a_id']." Where user_id='".$row1['student_id']."'");
							mysql_db_query( $DBC.$course_id, "Delete From note_".$row_coop['a_id']." Where student_id='".$row1['student_id']."'");
							mysql_db_query( $DBC.$course_id, "Delete From share_".$row_coop['a_id']." Where student_id='".$row1['student_id']."'");
							mysql_db_query( $DBC.$course_id, "Delete take_coop Where student_id='".$row1['student_id']."'");
						}
						
						if ( is_file( "../../studentPage/".$row1['id'].".html" ) )
							unlink ( "../../studentPage/".$row1['id'].".html" );
						if ( is_file( "../../studentPage/".$row1['id'].".html" ) )
							unlink ( "../../studentPage/".$row1['id'].".gif" );
					}
				}
			}
			$target = "../../".$courseid."/student_info";
			if ( is_dir($target) )
				deldir ( $target );
			mkdir ( "../../".$courseid."/student_info", 0771 );
			chmod ( "../../".$courseid."/student_info", 0771 );
		}
		if ( $on_line == 1 ) {
			$O1 = "select a_id from on_line";
			$O2 = "delete from on_line";
			if ( !($result = mysql_db_query( $DB.$courseid, $O1 ) ) ) {
				$error = "資料庫刪除錯誤!!";
			}
			if ( !mysql_db_query( $DB.$courseid, $O2 ) ) {
				$error = "資料庫刪除錯誤!!";
			}
			else if ( mysql_num_rows( $result ) != 0 ) {
				while ( $row = mysql_fetch_array( $result ) ) {	
					$target = "../../".$courseid."/on_line/".$row['a_id'];
					if ( is_dir($target) )
						deldir ( $target );
				}
			}
		}
		if ( $chap == 1 ) {
			mysql_db_query( $DB.$courseid, $D8 );
			$target = "../../".$courseid."/textbook";
			if ( is_dir($target) )
				deldir ( $target );
			mkdir ( "../../".$courseid."/textbook", 0771 );
			chmod ( "../../".$courseid."/textbook", 0771 );
			mkdir ( "../../".$courseid."/textbook/misc", 0771 );
		}
		if ( $ta == 1) {
			$T0 = "select tc.teacher_id as a_id FROM teach_course tc, user u WHERE tc.course_id = '$courseid' and u.authorization = '2' and u.a_id = tc.teacher_id";
			if ( $resultT0 = mysql_db_query( $DB, $T0 ) ) {
				$error = "資料庫讀取錯誤!!";
			}
			if ( mysql_num_rows( $resultT0 ) != 0 ) {
				while ( $rowT0 = mysql_fetch_array( $resultT0 ) ) {
					$a_id = $rowT0['a_id'];

					$T1 = "delete from teach_course where teacher_id = '$a_id' and course_id = '$courseid'";
					$T2 = "delete from log where user_id = '$a_id'";
					$T3 = "select * from teach_course where teacher_id = '$a_id'";
					$T4 = "delete from user where a_id = '$a_id'";					
					if ( !($resultT1 = mysql_db_query( $DB, $T1 ) ) ) {
						$error = "資料庫讀取錯誤!!";
					}
					if ( !($resultT2 = mysql_db_query( $DB.$courseid, $T2 ) ) ) {
						$error = "資料庫讀取錯誤!!";
					}
					if ( !($resultT3 = mysql_db_query( $DB, $T3 ) ) ) {
						$error = "資料庫讀取錯誤!!";
					}
					if ( mysql_num_rows( $resultT3 ) == 0 ) {
						if ( !($result4 = mysql_db_query( $DB, $T4 ) ) ) {
							$error = "資料庫刪除錯誤!!";
						}
						if ( !($result2 = mysql_db_query( $DB, $T2 ) ) ) {
							$error = "資料庫刪除錯誤!!";
						}
					}
				}
			}
		}		
	}
	$Q2 = "select * from news";
	$Q3 = "select * from course_schedule";
	$Q4 = "select * from discuss_info";
	$Q5 = "select * from exam where is_online = '1'";
	$Q6 = "select * from homework";
	$Q7 = "select * from exam where is_online = '0'";
	$Q8 = "select * from log where event_id != '8'";
	$Q9 = "select * from take_course where course_id = '$courseid'";
	$Q10 = "select * from on_line";
	$Q11 = "select * from chap_title";
	$Q12 = "select tc.teacher_id as a_id FROM teach_course tc, user u WHERE tc.course_id = '$courseid' and u.authorization = '2' and u.a_id = tc.teacher_id";

	include("class.FastTemplate.php3");
	$tpl = new FastTemplate ( "./templates" );
	$tpl->assign ( SKINNUM, $skinnum );
	if( $version == "C" ) {
		$tpl->define ( array ( body => "reset_course.tpl") );
		$tpl->assign ( COURSEI, $courseid );
		$tpl->assign ( COURSEN, $cname );
		if ( mysql_num_rows( mysql_db_query( $DB.$courseid, $Q2 ) ) == 0 )
			$tpl->assign ( NESR, "重置完成" );
		else
			$tpl->assign ( NESR, "尚有資料存在");
		if ( mysql_num_rows( mysql_db_query( $DB.$courseid, $Q3 ) ) == 0 )
			$tpl->assign ( SCHEDR, "重置完成");
		else
			$tpl->assign ( SCHEDR, "尚有資料存在");
		if ( mysql_num_rows( mysql_db_query( $DB.$courseid, $Q4 ) ) == 0 )
			$tpl->assign ( DISR, "重置完成" );
		else
			$tpl->assign ( DISR, "尚有資料存在");
		if ( mysql_num_rows( mysql_db_query( $DB.$courseid, $Q5 ) ) == 0 )
			$tpl->assign ( ONLINER, "重置完成" );
		else
			$tpl->assign ( ONLINER, "尚有資料存在");
		if ( mysql_num_rows( mysql_db_query( $DB.$courseid, $Q6 ) ) == 0 )
			$tpl->assign ( WORKR, "重置完成");
		else
			$tpl->assign ( WORKR, "尚有資料存在");
		if ( mysql_num_rows( mysql_db_query( $DB.$courseid, $Q7 ) ) == 0 )
			$tpl->assign ( GRANDR, "重置完成");
		else
			$tpl->assign ( GRANDR, "尚有資料存在");
		if ( mysql_num_rows( mysql_db_query( $DB.$courseid, $Q8 ) ) == 0 )
			$tpl->assign ( TRACER, "重置完成" );
		else
			$tpl->assign ( TRACER, "尚有資料存在");
		if ( mysql_num_rows( mysql_db_query( $DB, $Q9 ) ) == 0 )
			$tpl->assign ( STUR, "重置完成" );
		else
			$tpl->assign ( STUR, "尚有資料存在");
		if ( mysql_num_rows( mysql_db_query( $DB.$courseid, $Q10 ) ) == 0 )
			$tpl->assign ( VLINE, "重置完成" );
		else
			$tpl->assign ( VLINE, "尚有資料存在");
		if ( mysql_num_rows( mysql_db_query( $DB.$courseid, $Q11 ) ) == 0 )
			$tpl->assign ( CHAP, "重置完成" );
		else
			$tpl->assign ( CHAP, "尚有資料存在");
		if ( mysql_num_rows( mysql_db_query( $DB, $Q12 ) ) == 0 )
			$tpl->assign ( TA, "重置完成" );
		else
			$tpl->assign ( TA, "尚有資料存在");
	}
	else {
		$tpl->define ( array ( body => "reset_course_E.tpl") );
		$tpl->assign ( COURSEI, $courseid );
		$tpl->assign ( COURSEN, $cname );
		if ( mysql_num_rows( mysql_db_query( $DB.$courseid, $Q2 ) ) == 0 )
			$tpl->assign ( NESR, "Comolete" );
		else
			$tpl->assign ( NESR, "Have Data");
		if ( mysql_num_rows( mysql_db_query( $DB.$courseid, $Q3 ) ) == 0 )
			$tpl->assign ( SCHEDR, "Comolete");
		else
			$tpl->assign ( SCHEDR, "Have Data");
		if ( mysql_num_rows( mysql_db_query( $DB.$courseid, $Q4 ) ) == 0 )
			$tpl->assign ( DISR, "Comolete" );
		else
			$tpl->assign ( DISR, "Have Data");
		if ( mysql_num_rows( mysql_db_query( $DB.$courseid, $Q5 ) ) == 0 )
			$tpl->assign ( ONLINER, "Comolete" );
		else
			$tpl->assign ( ONLINER, "Have Data");
		if ( mysql_num_rows( mysql_db_query( $DB.$courseid, $Q6 ) ) == 0 )
			$tpl->assign ( WORKR, "Comolete");
		else
			$tpl->assign ( WORKR, "Have Data");
		if ( mysql_num_rows( mysql_db_query( $DB.$courseid, $Q7 ) ) == 0 )
			$tpl->assign ( GRANDR, "Comolete");
		else
			$tpl->assign ( GRANDR, "Have Data");
		if ( mysql_num_rows( mysql_db_query( $DB.$courseid, $Q8 ) ) == 0 )
			$tpl->assign ( TRACER, "Comolete" );
		else
			$tpl->assign ( TRACER, "Have Data");
		if ( mysql_num_rows( mysql_db_query( $DB, $Q9 ) ) == 0 )
			$tpl->assign ( STUR, "Comolete" );
		else
			$tpl->assign ( STUR, "Have Data");
		if ( mysql_num_rows( mysql_db_query( $DB.$courseid, $Q10 ) ) == 0 )
			$tpl->assign ( VLINE, "Comolete" );
		else
			$tpl->assign ( VLINE, "Have Data");
		if ( mysql_num_rows( mysql_db_query( $DB.$courseid, $Q11 ) ) == 0 )
			$tpl->assign ( CHAP, "Comolete" );
		else
			$tpl->assign ( CHAP, "Have Data");
		if ( mysql_num_rows( mysql_db_query( $DB, $Q12 ) ) == 0 )
			$tpl->assign ( TA, "Comolete" );
		else
			$tpl->assign ( TA, "Have Data");
	}  
	$tpl->parse( BODY, "body");
	$tpl->FastPrint("BODY");
?>