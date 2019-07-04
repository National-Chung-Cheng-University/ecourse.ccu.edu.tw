<?php
	require 'fadmin.php';
	if ( $id == "guest" ) {
		header( "Location: http://$SERVER_NAME/php/Courses_Admin/guest.php?id=$id&ver=$ver");
		exit;
	}
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
		if ( $user_id != "guest" ) {
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
				if ( mysql_num_rows( $result2 ) != 0 && $scorm == 1 ) {
					if ( $ver == "C" )
						show_page ( "index_stu.tpl", "你已重複登入 請稍後再登入!!", $id );
					else
						show_page ( "index_stu_E.tpl", "You had login before, Please wait for login", $id );
					exit;
				}
			}
		}
		if ( ($error = auth()) == -1 ) {
			session_start();
			session_unregister("teacher");
			session_unregister("admin");
			session_unregister("user_id");
			session_unregister("version");
			session_unregister("course_id");
			//計算使用時間用
			session_unregister("time");
			session_register("time");
			session_register("user_id");
			session_register("version");
			$user_id = $id;
			$version = $ver;
			$Q1 = "select email, authorization FROM user where id = '$id'";
			$time = date("U");
			if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
				$message = "$message - 資料庫讀取錯誤!!";
			}else
				$row = mysql_fetch_array( $result );

			if ( $pass == "" || $row['email'] == "")
				header( "Location: ./Learner_Profile/chang_pass2.php?PHPSESSID=".session_id() );
			else {
	      			add_log ( 1, $id );
      				add_message ();
      				unset($id);
	      			unset($ver);
				if ( $row['authorization'] == "3" ) {
					header( "Location: http://$SERVER_NAME/php/student.php?PHPSESSID=".session_id());
				}
				else {
					header( "Location: http://$SERVER_NAME/php/Courses_Admin/guest.php?PHPSESSID=".session_id());
				}
      			}
		}
		else {
			if ( $ver == "C" )
				show_page ( "index_stu.tpl", $error, $id );
			else
				show_page ( "index_stu_E.tpl", $error, $id );
		}
	}
	else if ( !isset($ver) ) {
		if (isset($PHPSESSID) && session_check_stu($PHPSESSID) ) {
			if ( $user_id != "guest" ) {
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
							show_page ( "index_stu.tpl", "你已重複登入 請稍後再登入!!", $id );
						else
							show_page ( "index_stu_E.tpl", "You had login before, Please wait for login", $id );
						exit;
					}
				}
			}
			$ver = $version;
			$id = $user_id;
			$Q1 = "select email, authorization FROM user where id = '$id'";
			if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
				$message = "$message - 資料庫讀取錯誤!!";
			}else
				$row = mysql_fetch_array( $result );

			if ( $row['email'] == "" )
				header( "Location: ./Learner_Profile/chang_pass2.php?PHPSESSID=".session_id() );
			else {
				if ( $row['authorization'] == "3" ) {
					header( "Location: http://$SERVER_NAME/php/Courses_Admin/take_course.php?PHPSESSID=".session_id());
				}
				else {
					header( "Location: http://$SERVER_NAME/php/Courses_Admin/guest.php?PHPSESSID=".session_id());
				}
			}
		}
		else
			show_page( "index_stu.tpl", "登入版本錯誤!!!", $id );
	}
	else if ( $ver == "C" ) {
		if ( isset($id) )
			show_page( "index_stu.tpl", "請輸入你的帳號及密碼!!!", $id );
		else
			show_page( "index_stu.tpl" );
	}
	else {
		if ( isset($id) )
			show_page( "index_stu_E.tpl", "Please Input Your ID and PASSWORD!!!", $id );
		else
			show_page( "index_stu_E.tpl" );
	}

	function auth() {
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $id, $pass, $ver;
		$Q1 = "SELECT pass, authorization, email FROM user where id = '$id'";
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "資料庫連結錯誤!!";
			return $error;
		}
		if ( !($result = mysql_db_query( $DB, $Q1  )) ) {
			$error = "資料庫讀取錯誤!!";
			return $error;
		}
		if ( ($row = mysql_fetch_array($result)) && ($pass == $row["pass"]) && ($row["authorization"] == 3 || $row["authorization"] == 9) ) {
			return -1;
		}
		else {
			if ( $ver == "C" )
				$error = "使用者帳號或密碼錯誤!!";
			else
				$error = "User ID and PASSWORD INCORRECT!!";
			return $error;
		}
	}

?>
