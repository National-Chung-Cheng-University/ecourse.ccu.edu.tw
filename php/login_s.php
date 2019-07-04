<?
	require 'fadmin.php';
	if ( !(isset($PHPSESSID) && session_check_stu($PHPSESSID)) ) {
		show_page( "not_access.tpl" ,"權限錯誤" );
	}
	else {
		session_unregister("time");
		session_register("time");
		$time = date("U");
		if ( !session_is_registered ( "course_id" ) )
			session_register("course_id");
		/**********新增加選擇課程的學年學期資訊session**************/
		if ( !session_is_registered ( "course_year" ) )
			session_register("course_year");
		if ( !session_is_registered ( "course_term" ) )
			session_register("course_term");
		/***************************************************/
		if ( isset($courseid) ){
			if( $courseid == -1){
				$course_id = "-1" ;
			}else{
				$courseary = explode("_",$courseid);
				$course_year = $courseary [0];
				$course_term = $courseary [1];
				$course_id = $courseary [2];
				$is_hist = 0;				
			}
		}
		session_unregister("doc_root");
		session_unregister("work_dir");
		session_unregister("guest");
		session_unregister("texttime");
		session_unregister("prevchapter");
		session_unregister("prevsection");
	}
	if ( $course_id == "-1" ) {
		header("Location: ./Courses_Admin/take_course.php?PHPSESSID=".session_id());
		exit;
	}
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	$Q1 = "SELECT a_id,authorization,name FROM user where id = '$user_id'";
	$QA = "SELECT u_id FROM function_list2 WHERE u_id='$user_id'";
	$QB = "INSERT INTO `function_list2` (u_id) VALUES ('$user_id')";
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$error = "資料庫連結錯誤!!";
		show_page ( "not_access.tpl", $error );
	}
	else if ( !($result = mysql_db_query( $DB, $Q1  )) ) {
		$error = "資料庫讀取錯誤!!";
		show_page ( "not_access.tpl", $error );
	}
	else if ( mysql_num_rows( mysql_db_query( $DB, $Q1 ) ) == 0 ) {
		$error = "無此使用者!!";
		show_page ( "not_access.tpl", $error );
	}
	else{
		if ( !($resultA = mysql_db_query( "study".$course_id, $QA  )) ) {
			echo $QA;
			echo $course_id;
			$error = "3資料庫讀取錯誤!!";
			show_page ( "not_access.tpl", $error );		
		}
		else{
			$row = mysql_fetch_array ($resultA);
			if ($row['u_id']==""){

				if ( !($resultA = mysql_db_query( "study".$course_id, $QB  )) ) {
					$error = "4資料庫讀取錯誤!!";
					show_page ( "not_access.tpl", $error );		
				}

			}
		}
		$row = mysql_fetch_array($result);
	}

	if ( $row["authorization"] == 3 )
	{
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
		$Q2 = "SELECT u.a_id, tc.credit FROM take_course tc, user u where tc.student_id = u.a_id and u.id = '$user_id' and tc.course_id = '$course_id' and year='$course_year' and term ='$course_term'";
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "資料庫連結錯誤!!";
			show_page ( "not_access.tpl", $error );
		}
		else if ( !($result2 = mysql_db_query( $DB, $Q2  )) ) {
			$error = "資料庫讀取錯誤!!";
			show_page ( "not_access.tpl", $error );
		}
		else if ( mysql_num_rows( $result2 ) == 0 ) {
			show_page("not_access.tpl","你不是此堂課的修課學生！");

			/* commented at 2007/11/15 (rhhwang instructed)
			$Q3 = "Select group_id From course Where a_id='$course_id'";
			if ( !($result3 = mysql_db_query( $DB, $Q3 ) ) ) {
				echo( "資料庫讀取錯誤1!!" );
				return;
			}
			$row3 = mysql_fetch_array($result3); 
			$Q4 = "Insert Into take_course (group_id,course_id,student_id,validated,credit,year,term) values ('$row3[0]','$course_id','".$row['a_id']."','1','0','$course_year','$course_term')";
			if ( !($result4 = mysql_db_query( $DB, $Q4  )) ) {
				echo( "資料庫寫入錯誤!!");
				return;
			}
			session_register("guest");
			$guest = 1;
			*/
		}
		else {
			$row2 = mysql_fetch_array($result2);
			if ( $row2['credit'] != 1 ) {
				session_register("guest");
				$guest = 1;
			}
			else {
				session_unregister("guest");
				$guest = 0;
				//devon 2006-03-13-----如果有線上問卷 則顯示問卷調查
				$Q1 = "select * from questionary";
				$result1 = mysql_db_query($DB.$course_id, $Q1);
				if(mysql_num_rows($result1)!=0)
				{
					$Q2 = "update function_list2 set show_qs='1' where u_id='$user_id'";
					$result2 = mysql_db_query($DB.$course_id, $Q2);
				}
				
			}
		}
	}
	else {
		if ( $course_id == "-1" ) {
			header("Location: ./Courses_Admin/guest.php?PHPSESSID=".session_id());
			exit;
		}
		session_register("guest");
		$guest = 1;
	}
	if ( $guest == 1 ) {
		$Q5 = "SELECT validated FROM course where a_id = '$course_id'";
		if ( !($result5 = mysql_db_query( $DB, $Q5  )) ) {
			$error = "資料庫讀取錯誤!!";
		}
		else if ( mysql_num_rows( $result5 ) == 0 ) {
			$error = "資料庫錯誤!!";
			show_page ( "not_access.tpl", $error );
		}
		else {
			$row5 = mysql_fetch_array($result5);
		}
		if( ($row5["validated"]%2 == 1) ) {
			if ( $row["authorization"] == 2 ) {
				header("Location: ./Course_Admin/take_course.php?PHPSESSID=".session_id());
			}
			else {
				header("Location: ./Courses_Admin/guest.php?PHPSESSID=".session_id());
			}
			exit;
		}
	}
	if ( $guest == 1 )
		$credit = 0;
	else
		$credit = 1;
	if ( $frame != 1 && $scorm == 1) {
		if ( $version == "C" )
			$sendver = "Chinese";
		else
			$sendver = "English";
		header ( "Location: http://$SERVER_NAME/servlets/java_session?student_id=$user_id&student_name=".$row['name']."&credit=$credit&version=$sendver&course_id=$course_id&PHPSESSID=".session_id()."&next_page=/php/login_s.php?frame=1" );
		exit;
	}
	add_log ( 2, $user_id, "", $course_id );
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate ( "./templates" );
	$tpl->define ( array ( body => "login.tpl") );

	if( $version == "C" ) {
		$tpl->assign ( TITLE, "學生系統" );
		$tpl->assign ( RSSLINK, "");
		$tpl->assign ( APPFILE, "./online/online.php?PHPSESSID=$PHPSESSID");
		$tpl->assign ( BARFILE, "./bar.php");
	}
	else {
		$tpl->assign ( TITLE, "student system" );
		$tpl->assign ( RSSLINK, "");
		$tpl->assign ( APPFILE, "./online/online.php?PHPSESSID=$PHPSESSID");
		$tpl->assign ( BARFILE, "./bar.php");
	}
	if ( $scorm == 1 ) {
		$tpl->assign ( LOAD, "onload=\"initAPI()\"" );
		$tpl->assign ( APIPAGE, "/LMSClient/API.html");
	}
	else {
		$tpl->assign ( LOAD, "" );
		$tpl->assign ( APIPAGE, "#");
	}
	$tpl->assign ( PHPID, $PHPSESSID);
	$tpl->parse( BODY, "body");
	$tpl->FastPrint("BODY");
?>
