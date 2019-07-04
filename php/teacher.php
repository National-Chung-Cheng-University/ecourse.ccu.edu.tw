<?php
	require 'fadmin.php';
	$refreshmin = 1.5;
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	$Q1 = "delete from online where (".date("U")." - time) > ($refreshmin * 60)";
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$error = "資料庫連結錯誤!!";
		if ( $ver == "C" )
			show_page ( "index_teach.tpl", $error, $id );
		else
			show_page ( "index_teach_E.tpl", $error, $id );
		exit;
	}
//	mysql_db_query( $DB, $Q1 );
	if ( $id != "" && $ver != "" ) {
		$Q2 = "select a_id from online where user_id = '$id'";
		if ( !($result2 = mysql_db_query( $DB, $Q2 )) ) {
			$error = "資料庫讀取錯誤!!";
			if ( $ver == "C" )
				show_page ( "index_teach.tpl", $error, $id );
			else
				show_page ( "index_teach_E.tpl", $error, $id );
			exit;
		}
		else {
			if ( mysql_num_rows( $result2 ) != 0 && $scorm == 1) {
				if ( $ver == "C" )
					show_page ( "index_teach.tpl", "你已重複登入 請稍後再登入!!", $id );
				else
					show_page ( "index_teach_E.tpl", "You had login before, Please wait for login", $id );
				exit;
			}
		}
		if ( ($error = auth()) == -1 ) {
			session_start();
			session_unregister("admin");
			session_unregister("course_id");
			session_register("teacher");
			session_register("user_id");
			session_register("version");
			session_unregister("time");
			session_unregister("guest");
			//計算使用時間用
			session_register("time");
			$time = date("U");
			$teacher = 1;
			$user_id = $id;
			$version = $ver;
			add_log ( 1, $id );
      			add_message ();
      			unset($id);
      			unset($ver);
			header( "Location: http://$SERVER_NAME/php/Courses_Admin/teach_course.php?PHPSESSID=".session_id());
		}
		else {
			if ( $ver == "C" )
				show_page ( "index_teach.tpl", $error, $id );
			else
				show_page ( "index_teach_E.tpl", $error, $id );
		}
	}
	else if ( !isset($ver) )
		if (isset($PHPSESSID) && session_check_stu($PHPSESSID) ) {
			$Q2 = "select a_id from online where user_id = '$user_id'";
			if ( !($result2 = mysql_db_query( $DB, $Q2 )) ) {
				$error = "資料庫讀取錯誤!!";
				if ( $version == "C" )
					show_page ( "index_teach.tpl", $error, $id );
				else
					show_page ( "index_teach_E.tpl", $error, $id );
				exit;
			}
			else {
				if ( mysql_num_rows( $result2 ) != 0 && $scorm == 1 ) {
					if ( $version == "C" )
						show_page ( "index_teach.tpl", "你已重複登入 請稍後再登入!!", $id );
					else
						show_page ( "index_teach_E.tpl", "You had login before, Please wait for login", $id );
					exit;
				}
			}
			header( "Location: http://$SERVER_NAME/php/Courses_Admin/teach_course.php?PHPSESSID=".session_id());
		}
		else
			show_page( "index_teach.tpl", "登入版本錯誤!!!");
	else if ( $ver == "C" ) {
		if ( isset($id) )
			show_page( "index_teach.tpl", "請輸入你的帳號及密碼!!!", $id);
		else
			show_page( "index_teach.tpl" );
	}
	else {
		if ( isset($id) )
			show_page( "index_teach_E.tpl", "Please Input Your ID and PASSWORD!!!", $id );
		else
			show_page( "index_teach_E.tpl" );
	}

	function auth() {
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $id, $pass, $ver;
		$Q1 = "SELECT pass, authorization FROM user where id = '$id'";
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "資料庫連結錯誤!!";
			return $error;
		}
		if ( !($result = mysql_db_query( $DB, $Q1  )) ) {
			$error = "資料庫讀取錯誤!!";
			return $error;
		}
		if ( ($row = mysql_fetch_array($result)) && ($pass == $row["pass"]) && ($row["authorization"] <= 2 ) )
			return -1;
		else {
			if ( $ver == "C" )
				$error = "使用者帳號或密碼錯誤!!";
			else
				$error = "User ID and PASSWORD INCORRECT!!";
			return $error;
		}
	}

	function show_page_d ( $message="" ) {
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		global $ver, $course_id;
		if ( $ver == "C" )
			$tpl->define ( array ( body => "teacher.tpl" ) );
		else
			$tpl->define ( array ( body => "teacher_E.tpl" ) );
		$tpl->define_dynamic ( "course_list" , "body" );
		$tpl->define_dynamic ( "reset_list" , "body" );
		$color = "#4d6eb2";
		$tpl->assign( COLOR , $color );
		$tpl->assign( TYPE , "colspan=2" );
		if ( $ver == "C" ) {
			$tpl->assign( GNAME , "<b><font color =#FFFFFF>開課單位</font></b>" );
			$tpl->assign( CNO , "<b><font color =#FFFFFF>課程編號</font></b>" );
			$tpl->assign( CNAME , "<b><font color =#FFFFFF>課程名稱</font></b>" );
			$tpl->assign( CRNAME , "<b><font color =#FFFFFF>重置課程連結</font></b>" );
			$tpl->assign( CTEACH , "<b><font color =#FFFFFF>授課教師</font></b>" );
		}
		else {
			$tpl->assign( GNAME , "<b><font color =#FFFFFF>Department</font></b>" );
			$tpl->assign( CNO , "<b><font color =#FFFFFF>No.</font></b>" );
			$tpl->assign( CNAME , "<b><font color =#FFFFFF>Course Name</font></b>" );
			$tpl->assign( CRNAME , "<b><font color =#FFFFFF>Reset Course Link</font></b>" );
			$tpl->assign( CTEACH , "<b><font color =#FFFFFF>Teachers</font></b>" );
		}
		$tpl->parse ( COURSE_LIST, ".course_list" );
		$tpl->parse ( RESET_LIST, ".reset_list" );
		$color = "#BFCEBD";
		$tpl->assign( TYPE , "" );
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $id;
		$Q1 = "select c.group_id, cg.name AS gname, tc.course_id, c.course_no, c.name AS cname FROM course c, course_group cg, teach_course tc , user u where u.id = '$id' and tc.teacher_id = u.a_id and c.a_id = tc.course_id and cg.a_id = c.group_id";
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$message = "$message - 資料庫連結錯誤!!";
		}
		if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
			$message = "$message - 資料庫讀取錯誤!!";
		}
		else if ( mysql_num_rows( mysql_db_query( $DB, $Q1 ) ) != 0 ) {	
			$tpl->assign( HRLINE , "<hr>" );		
			while ( $row = mysql_fetch_array( $result ) ) {
				if ( $color == "#BFCEBD" )
					$color = "#D0DFE3";
				else
					$color = "#BFCEBD";
				$tpl->assign( COLOR , $color );
				$Q2 = "select u.id, u.name, u.nickname, u.a_id, u.php FROM user u , teach_course tc where tc.course_id = '".$row["course_id"]."' and tc.teacher_id = u.a_id";
				if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) ) {
					$message = "$message - 資料庫讀取錯誤!!";
				}
				$tpl->assign( GNAME , "‧</td><td bgcolor=$color width=75><font size=-1>".$row["gname"] );
				$tpl->assign( CNO , $row["course_no"] );
				$tpl->assign( CNAME , "<a href=\"login.php?courseid=". $row["course_id"] . "\">".$row["cname"]."</a>" );
				$name = "";
				while ( $row2 = mysql_fetch_array( $result2 ) ) {
					if ( $row2['name'] != NULL ) {
						if ( $row2['php'] != NULL ) {
							$name = $name." <a href=# onClick=\"window.open('".$row2['php']."', '', 'resizable=1,scrollbars=1');\">".$row2['name']."</a>";
						}
						else {
							$name = $name." <a href=# onClick=\"window.open('./Learner_Profile/TTDATAQuery1.php?PHPSESSID=".session_id()."&user_aid=".$row2['a_id']."&query=1', '', 'resizable=1,scrollbars=1');\">".$row2['name']."</a>";
						}
					}
					else if ( $row2['nickname'] != NULL ) {
						if ( $row2['php'] != NULL ) {
							$name = $name." <a href=# onClick=\"window.open('".$row2['php']."', '', 'resizable=1,scrollbars=1');\">".$row2['nickname']."</a>";
						}
						else {
							$name = $name." <a href=# onClick=\"window.open('./Learner_Profile/TTDATAQuery1.php?PHPSESSID=".session_id()."&user_aid=".$row2['a_id']."&query=1', '', 'resizable=1,scrollbars=1');\">".$row2['nickname']."</a>";
						}
					}
					else {
						if ( $row2['php'] != NULL ) {
							$name = $name." <a href=# onClick=\"window.open('".$row2['php']."', '', 'resizable=1,scrollbars=1');\">".$row2['id']."</a>";
						}
						else {
							$name = $name." <a href=# onClick=\"window.open('./Learner_Profile/TTDATAQuery1.php?PHPSESSID=".session_id()."&user_aid=".$row2['a_id']."&query=1', '', 'resizable=1,scrollbars=1');\">".$row2['id']."</a>";
						}
					}
				}
				$tpl->assign( CTEACH , $name );
				$tpl->parse ( COURSE_LIST, ".course_list" );
				if ( $ver == "C" )
					$tpl->assign( CRNAME , "<a href=\"./Courses_Admin/reset_course.php?courseid=". $row["course_id"] . "\">重置 ".$row["cname"]."</a>" );
				else
					$tpl->assign( CRNAME , "<a href=\"./Courses_Admin/reset_course.php?courseid=". $row["course_id"] . "\">Reset ".$row["cname"]."</a>" );
				$tpl->parse ( RESET_LIST, ".reset_list" );
			}
		}
		else {
			$tpl->assign( HRLINE , "<hr>" );
		}
		$tpl->assign( PHPSID , session_id() );
		if ( $course_id != "" )
			$tpl->assign( SYS , "//" );
		else
			$tpl->assign( SYS , "" );
		$tpl->parse( BODY, "body" );
		$tpl->FastPrint("BODY");
	}

?>
