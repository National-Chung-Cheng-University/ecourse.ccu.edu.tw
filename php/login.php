<?
	require 'fadmin.php';
	
	if ( !(isset($PHPSESSID) && session_check_stu($PHPSESSID)) )
		show_page( "not_access.tpl" ,"權限錯誤" );
	if( $guest == "1" || $teacher != 1 ) {
		if( $version == "C" )
			$error = "此網頁禁止非教授進入!";
		else
			$error = "Student can't login!";
		show_page ( "not_access.tpl", $error );
        }
	else {
		session_unregister("time");
		session_register("time");
		$time = date("U");
		if ( !session_is_registered ( "course_id" ) )
			session_register("course_id");
		if ( !session_is_registered ( "SDB" ) )
			session_register("SDB");
		/**********新增加選擇課程的學年學期資訊session**************/
		if ( !session_is_registered ( "course_year" ) )
			session_register("course_year");
		if ( !session_is_registered ( "course_term" ) )
			session_register("course_term");
		/***************************************************/
		/**********判斷是否為歷史區用的session**************/
		if ( !session_is_registered ( "is_hist" ) )
			session_register("is_hist");
		if ( !session_is_registered ( "hist_year" ) )
			session_register("hist_year");
		if ( !session_is_registered ( "hist_term" ) )
			session_register("hist_term");
		/***************************************************/
		if ( isset($courseid) ){
			if(strstr($courseid,"hist_")){ //歷史區特別處理 hist_year_term_courseid
				$histary = explode("_",$courseid);
				$hist_year = $histary[1];
				$course_year = $histary[1];
				$hist_term = $histary[2];
				$course_term =  $histary[2];
				$course_id = $histary[3];			
				$is_hist = 1;
			}
			else{
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
		}
		session_unregister("doc_root");
		session_unregister("work_dir");
		session_unregister("guest");
		session_unregister("texttime");
		session_unregister("prevchapter");
		session_unregister("prevsection");
	}
	if ( $course_id == "-1" ) {
		header("Location: ./Courses_Admin/teach_course.php?PHPSESSID=".session_id());
		exit;
	}
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	$Q1 = "SELECT u.a_id, u.name FROM teach_course tc, user u where tc.teacher_id = u.a_id and u.id = '$user_id' and tc.course_id = '$course_id' and tc.year = $course_year and tc.term = $course_term";
	$Q2 = "SELECT u_id FROM function_list WHERE u_id='$user_id'";
	$Q3 = "INSERT INTO function_list (u_id) VALUES ('$user_id')";
	if($is_hist==0){
		$Q4 = "SELECT course_no from course where a_id = $course_id";
	}
	else{
		$Q4 = "SELECT course_no from hist_course where a_id = $course_id and year = $course_year and term = $course_term";
	}
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$error = "資料庫連結錯誤!!";
		show_page ( "not_access.tpl", $error );
	}
	else if ( !($result = mysql_db_query( $DB, $Q1  )) ) {
		$error = "資料庫讀取錯誤!!11111";
		show_page ( "not_access.tpl", $error );
	}
	else if ( mysql_num_rows( $result ) == 0 ) {
		if( $version == "C" )
			$error = "此網頁只准該課的任課教授進入!$course_id";
		else
			$error = "Only this course's student!";
		show_page ( "not_access.tpl", $error );
	}
	else {
		//不是歷史區才查詢功能清單	
		if($is_hist==0){
			if ( !($result2 = mysql_db_query( "study".$course_id, $Q2  )) ) {
				$error = "資料庫讀取錯誤!!2222";
				show_page ( "not_access.tpl", $error );		
			}
			else{
				$row = mysql_fetch_array ($result2);
				if ($row['u_id']==""){
					if ( !($result3 = mysql_db_query( "study".$course_id, $Q3  )) ) {
						$error = "資料庫讀取錯誤!!33333";
						show_page ( "not_access.tpl", $error );		
					}
				}
			}
		}

		if ( !($result4 = mysql_db_query( $DB, $Q4  )) ) {
			$error = "資料庫讀取錯誤!!44444";
			show_page ( "not_access.tpl", $error );		
		}else{
			$row = mysql_fetch_array ($result4);
			$c_id = $row['course_no'];
			if ( substr( $c_id, 3, 1 ) == "A" || substr( $c_id, 3, 1 ) == "B" || substr( $c_id, 3, 1 ) == "C" || substr( $c_id, 3, 1 ) == "D" )
				$SDB = "academic_gra";
			else
				$SDB = "academic";
		}
		/*
		if($course_id ="11557"){
			$Q7 = "Select a_id From homework";
			if ( !($resultOBJ = mysql_db_query( $DB.$course_id, $Q7 ) ) ) {
				$message = "資料庫讀取錯誤2!!";
				show_page_d ( $message );
				return;
			}
			while($row7 = mysql_fetch_array($resultOBJ))
			{
				$Q5 = "SELECT tc.student_id, u.id FROM take_course tc,user u WHERE tc.course_id = '$course_id' AND tc.student_id = u.a_id and tc.credit ='1'";
				if ( !($result5 = mysql_db_query( $DB, $Q5 ) ) ) {
					show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
				}
				//echo "$Q5 <br>";
				while ( $row5 = mysql_fetch_array($result5) )
				{
					$Q6 = "INSERT INTO handin_homework (homework_id,student_id, handin_time) values ('".$row7['a_id']."','".$row5[0]."','0000-00-00')";
					mysql_db_query($DB.$course_id,$Q6);
				}
				//echo "$Q6 <br>";
			}
		}*/
		
		$row = mysql_fetch_array($result);
		if ( $frame != 1 && $scorm == 1 ) {
			if ( $version == "C" )
				$sendver = "Chinese";
			else
				$sendver = "English";
			header ( "Location: http://$SERVER_NAME/servlets/java_session?student_id=$user_id&student_name=".$row['name']."&credit=0&version=$sendver&course_id=$course_id&PHPSESSID=".session_id()."&next_page=/php/login.php?frame=1" );
			exit;
		}
		add_log ( 2, $user_id, "", $course_id );
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		$tpl->define ( array ( body => "login.tpl") );

		/******************判斷是否導入歷史區*******************************/
		if($is_hist==0){
			if( $version == "C" ) {
				$tpl->assign ( TITLE, "教師系統" );
				$tpl->assign ( RSSLINK, "");
				$tpl->assign ( APPFILE, "./online/online.php?PHPSESSID=$PHPSESSID");
				$tpl->assign ( BARFILE, "./bar.php");
			}
			else {
				$tpl->assign ( TITLE, "teacher system" );
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
		}
		else{
		/************歷史區*****************************/
			if( $version == "C" ) {
				$tpl->assign ( TITLE, "教師系統" );
				$tpl->assign ( RSSLINK, "");
				$tpl->assign ( APPFILE, "./online/online.php?PHPSESSID=$PHPSESSID");
				$tpl->assign ( BARFILE, "./bar_hist.php");
			}
			else {
				$tpl->assign ( TITLE, "teacher system" );
				$tpl->assign ( RSSLINK, "");
				$tpl->assign ( APPFILE, "./online/online.php?PHPSESSID=$PHPSESSID");
				$tpl->assign ( BARFILE, "./bar_hist.php");
			}
			if ( $scorm == 1 ) {
				$tpl->assign ( LOAD, "onload=\"initAPI()\"" );
				$tpl->assign ( APIPAGE, "/LMSClient/API.html");
			}
			else {
				$tpl->assign ( LOAD, "" );
				$tpl->assign ( APIPAGE, "#");
			}
		}
		$tpl->assign ( PHPID, $PHPSESSID);
		$tpl->parse( BODY, "body");
		$tpl->FastPrint("BODY");
	}

?>