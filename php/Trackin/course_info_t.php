<?php
	require 'fadmin.php';
	require 'DiscussBoardStatistics.php';

	if ( !(isset($PHPSESSID) && session_check_teach($PHPSESSID) == 3) )
		show_page( "not_access.tpl" ,"�v�����~");
		
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;

	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$error = "��Ʈw�s�����~!!";
		show_page ( "not_access.tpl", $error );
	}

	$Q2 = "select count(a_id) as news from news";
	if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2  )) ) {
		$error = "��ƮwŪ�����~2!!";
		show_page ( "not_access.tpl", $error );
	}
	else
		$news = mysql_fetch_array( $result2 );
	$Q3 = "select count(idx) as sch from course_schedule";
	$Q4 = "select schedule_unit from course where a_id = '$course_id'";
	if ( !($result3 = mysql_db_query( $DB.$course_id, $Q3  )) ) {
		$error = "��ƮwŪ�����~3!!";
		show_page ( "not_access.tpl", $error );
	}
	else
		$sch = mysql_fetch_array( $result3 );
	if ( !($result4 = mysql_db_query( $DB, $Q4  )) ) {
		$error = "��ƮwŪ�����~4!!";
		show_page ( "not_access.tpl", $error );
	}
	else
		$index = mysql_fetch_array( $result4 );

	$Q5 = "select count(a_id) as work from homework";
	if ( !($result5 = mysql_db_query( $DB.$course_id, $Q5 )) ) {
		$error = "��ƮwŪ�����~5!!";
		show_page ( "not_access.tpl", $error );
	}
	else
		$work = mysql_fetch_array( $result5 );

	$Q6 = "select count(a_id) as test from exam";
	if ( !($result6 = mysql_db_query( $DB.$course_id, $Q6 )) ) {
		$error = "��ƮwŪ�����~6!!";
		show_page ( "not_access.tpl", $error );
	}
	else
		$test = mysql_fetch_array( $result6 );

	$Q7 = "select count(course_id) as user from take_course where course_id = '$course_id' group by course_id";
	if ( !($result7 = mysql_db_query( $DB, $Q7 )) ) {
		$error = "��ƮwŪ�����~7!!";
		show_page ( "not_access.tpl", $error );
	}
	else
		$user = mysql_fetch_array( $result7 );

	$Q8 = "select SUM(tag3) as login from log where event_id = '2'";
	if ( !($result8 = mysql_db_query( $DB.$course_id, $Q8 )) ) {
		$error = "��ƮwŪ�����~8!!";
		show_page ( "not_access.tpl", $error );
	}
	else
		$login = mysql_fetch_array( $result8 );

	$Q9 = "select SUM(tag3) as time from log where event_id = '7'";
	if ( !($result9 = mysql_db_query( $DB.$course_id, $Q9 )) ) {
		$error = "��ƮwŪ�����~9!!";
		show_page ( "not_access.tpl", $error );
	}
	else
		$times = mysql_fetch_array( $result9 );	
	$min = $times['time'] / 60;
	$min = $min % 60;
	$hour = $times['time'] / 3600;
	$hour = $hour % 24;
	
	$Q10 = "select count(a_id) as dis from discuss_info";
	if ( !($result10 = mysql_db_query( $DB.$course_id, $Q10 )) ) {
		$error = "��ƮwŪ�����~10!!";
		show_page ( "not_access.tpl", $error );
	}
	else
		$dis = mysql_fetch_array( $result10 );
	
	$allpage = 0;
	$Q11 = "select a_id from discuss_info";
	if ( !($result11 = mysql_db_query( $DB.$course_id, $Q11 )) ) {
		$error = "��ƮwŪ�����~11!!";
		show_page ( "not_access.tpl", $error );
	}
	while ( $row = mysql_fetch_array( $result11 ) ) {
		$Q12 = "select count(*) as page from discuss_".$row['a_id'];
		if ( !($result12 = mysql_db_query( $DB.$course_id, $Q12 )) ) {
			$error = "��ƮwŪ�����~12$Q12!!";
			show_page ( "not_access.tpl", $error );
		}
		else {
			$page = mysql_fetch_array( $result12 );
			$allpage += $page['page'];
		}
		
	}
	
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate ( "./templates" );
	$tpl->define ( array ( body => "course_info_t.tpl") );
	if ( $news['news'] == "" )
		$tpl->assign ( NEWS, 0 );
	else
		$tpl->assign ( NEWS, $news['news'] );
	if ( $sch['sch'] == "" )
		$tpl->assign ( SCHED, "�@��0�q(���:".$index['schedule_unit'].")" );
	else
		$tpl->assign ( SCHED, "�@��".$sch['sch']."�q(���:".$index['schedule_unit'].")" );
	if ( $work['work'] == "" )
		$tpl->assign ( WORK, 0 );
	else
		$tpl->assign ( WORK, $work['work'] );
	if ( $test['test'] == "" )
		$tpl->assign ( TEST, 0 );
	else
		$tpl->assign ( TEST, $test['test'] );
	if ( $user['user'] == "" )
		$tpl->assign ( USER, 0 );
	else
		$tpl->assign ( USER, $user['user'] );
	if ( $login['login'] == "" )
		$tpl->assign ( LOGIN, 0 );
	else
		$tpl->assign ( LOGIN, $login['login'] );
	if ( $dis['dis'] == "" )
		$tpl->assign ( BOARD, 0 );
	else
		$tpl->assign ( BOARD, $dis['dis'] );

	$tpl->assign ( PHPID, $PHPSESSID );
	$tpl->assign ( PAGE, $allpage );
	$tpl->assign ( HOUR, $hour );
	$tpl->assign ( MINU, $min );
	$tpl->assign ( UNAME, $id );
	$tpl->assign ( CID, $course_id );
	$tpl->assign ( CNAME, $cname );

	//�s����Ų�з� (2008/2/17)
	$tpl->assign("#!STAT1!#",getBoards($course_id));	
	$tpl->assign("#!STAT2!#",getPosts($course_id));	
	$tpl->assign("#!STAT3!#",getTeacherPosts($course_id,0));	
	$tpl->assign("#!STAT4!#",getTeacherPosts($course_id,1));	
	$tpl->assign("#!STAT5!#",getStudentPosts($course_id,0));	
	$tpl->assign("#!STAT6!#",getStudentPosts($course_id,1));	
	$tpl->assign("#!STAT7!#",getStuAvgPostsPerWeek($course_id));	
	$tpl->assign("#!STAT8!#",getAvgPostPerWeek($course_id));	
	$tpl->assign("#!STAT9!#",getTchReplyRatio($course_id,2,0));	
	$tpl->assign("#!STAT10!#",getTchReplyRatio($course_id,7,0));	
	$tpl->assign("#!STAT11!#",getTchReplyRatio($course_id,7,1));	
	$tpl->assign("#!STAT12!#",getAvgReplyDelay($course_id));	

	$tpl->parse( BODY, "body");
	$tpl->FastPrint("BODY");

?>
