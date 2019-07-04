<?php
	require 'fadmin.php';
	update_status ("課程介紹");
	if ( !(isset($PHPSESSID) && ($check = session_check_teach($PHPSESSID)) || ( isset( $courseid ) && ($check = session_check_stu($PHPSESSID)) ) ) ) {
		show_page_d_new( "not_access.tpl" ,"權限錯誤");
	}
	if ( $query == 1 )
		$course_id = $courseid;

	show_page_d_new ();
	
	function show_page_d_new ( $error="" ) {
		global $course_id, $check, $teacher, $version, $query, $skinnum;
		global $action;
		
		if ( is_file("../../$course_id/intro/index.html") ) {
			if ( $check == 2 && $teacher == 1 ) {
				$fp = fopen("../../$course_id/intro/index.html", "r");
				if( filesize("../../$course_id/intro/index.html") > 0){
					$content = fread($fp , filesize("../../$course_id/intro/index.html"));
				}
				fclose($fp);
				include("class.FastTemplate.php3");
				$tpl = new FastTemplate ( "./templates" );
				$tpl->define ( array ( body => "intro2.tpl") );
				$ip = getenv ("SERVER_NAME" );
				if ( $ip == "" )
					$ip = $SERVER_NAME;
				$tpl->assign ( BASE, "http://$ip/$course_id/intro/");
				$tpl->assign ( HEAD, "<base href=\"http://$ip/php/Courses_Admin/\" target=\"_self\">");
				$tpl->assign( SKINNUM , $skinnum );
				
				if($action == "print")
				{
					$tpl->assign( BODY_ONLOAD , "onload='window.print();'");
					
				}
				
			}
			else {
				header( "Location: ../../$course_id/intro/");
				exit;

			}
		}
		else if ( is_file("../../$course_id/intro/index.htm") ) {
			if ( $check == 2 && $teacher == 1 ) {
				$fp = fopen("../../$course_id/intro/index.htm", "r");
				$content = fread($fp , filesize("../../$course_id/intro/index.htm"));
				fclose($fp);
				include("class.FastTemplate.php3");
				$tpl = new FastTemplate ( "./templates" );
				$tpl->define ( array ( body => "intro2.tpl") );
				$ip = getenv ("SERVER_NAME" );
				if ( $ip == "" )
					$ip = $SERVER_NAME;
				$tpl->assign ( BASE, "http://$ip/$course_id/intro/");
				$tpl->assign ( HEAD, "<base href=\"http://$ip/php/Courses_Admin/\" target=\"_self\">");
				$tpl->assign( SKINNUM , $skinnum );
				
				if($action == "print")
				{
					$tpl->assign( BODY_ONLOAD , "onload='window.print();'");
				}
			}
			else {
				header( "Location: ../../$course_id/intro/");
				exit;

			}
		}
		else if ( is_file("../../$course_id/intro/index.doc") ){
			global $check, $version, $course_id, $teacher;
			include("class.FastTemplate.php3");
			$tpl = new FastTemplate ( "./templates" );
			$content = "<HTML>\n<HEAD>\n<TITLE>授課大綱</TITLE>\n".
						"<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=../../$course_id/intro/index.doc\">\n".
						"</HEAD>\n</HTML>";
			$tpl->define ( array ( body => "intro2.tpl") );
			$tpl->assign ( HEAD, "");
			$tpl->assign( SKINNUM , $skinnum );
			
			if($action == "print")
			{
				$tpl->assign( BODY_ONLOAD , "onload='window.print();'");				
			}
		}
		else if ( is_file("../../$course_id/intro/index.pdf") ){
			global $check, $version, $course_id, $teacher;
			include("class.FastTemplate.php3");
			$tpl = new FastTemplate ( "./templates" );
			$content = "<HTML>\n<HEAD>\n<TITLE>授課大綱</TITLE>\n".
						"<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=../../$course_id/intro/index.pdf\">\n".
						"</HEAD>\n</HTML>";
			$tpl->define ( array ( body => "intro2.tpl") );
			$tpl->assign ( HEAD, "");
			$tpl->assign( SKINNUM , $skinnum );
			
			if($action == "print")
			{
				$tpl->assign( BODY_ONLOAD , "onload='window.print();'");				
			}
		}
		else if (is_file("../..".$old_path."/$course_id/intro/index.ppt"))
		{
			global $check, $version, $course_id, $teacher;
			include("class.FastTemplate.php3");
			$tpl = new FastTemplate ("./templates");
			$content = "<html>\n<head>\n<title>授課大綱</title>\n".
						"<meta http-equiv=REFRESH content=\"0;url=../..".$old_path."/$course_id/intro/index.ppt\">\n".
						"</head>\n</html>";
			$tpl->define (array(body => "intro2.tpl"));
			$tpl->assign (HEAD, "");
			$tpl->assign (SKINNUM, $skinnum);
			
			if($action == "print")
			{
				$tpl->assign( BODY_ONLOAD , "onload='window.print();'");				
			}
		}
		else {
			global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
			$Q1 = "select introduction, name FROM course where a_id ='$course_id'";
			if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
				$message = "$message - 資料庫連結錯誤!!";
				show_page ( "not_access.tpl", $message, "", "<a href=./guest.php>回上一頁</a>" );
			}
			else if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
				$message = "$message - 資料庫讀取錯誤!!";
				show_page ( "not_access.tpl", $message, "", "<a href=./guest.php>回上一頁</a>" );
			}
			else if( $row = mysql_fetch_array( $result ) ) {
				global $check, $version, $course_id, $teacher;
				include("class.FastTemplate.php3");
				$tpl = new FastTemplate ( "./templates" );
				$content = $row['introduction'];
				$tpl->define ( array ( body => "intro.tpl") );
				$tpl->assign ( HEAD, "");
				$tpl->assign( SKINNUM , $skinnum );
			}
			else
				show_page ( "not_access.tpl", $course_id."沒有資料" );
		}
		
		if ( $check == 2 && $teacher == 1 ) {

			$tpl->assign ( TITLE, $row['name'] );
			$tpl->assign ( MES, $content );
			$tpl->assign( SKINNUM , $skinnum );
			
			if( stristr($content, "MARK_ClassIntroIndexHtml") != false){
				$content = str_replace ( "MARK_ClassIntroIndexHtml", "", $content );
			}
			else if ( stristr($content,"<html>") == NULL ){
				$content = str_replace ( "\n", "<BR>", $content );
			}
			
			$tpl->assign ( MER, $content );
			$tpl->assign ( ERR, $error );
			$tpl->parse( BODY2, "body");
			
			if ( $_GET[showintro] == 1 ) {
				$tpl->FastPrint("BODY2");
			}
			else
				echo "<div align=\"center\" class=\"style1\"><a href=\"intro.php?showintro=1\">預覽授課大網</a></div>";
		}
		else {
			$tpl->assign ( ERR, $error );
			$tpl->define ( array ( body => "intro.tpl") );
			$tpl->assign ( TITLE, $row['name'] );
			$tpl->assign( SKINNUM , $skinnum );
			if ( stristr($content,"<html>") == NULL )
				$content = str_replace ( "\n", "<BR>", $content );
			$tpl->assign ( MER, $content );
			if ( $version == "C" )
				$tpl->assign ( IMAGE, "img" );
			else
				$tpl->assign ( IMAGE, "img_E" );
			$tpl->assign ( MES, $content );
			$tpl->parse( BODY, "body");
			$tpl->FastPrint("BODY");
			if ( $query == 1 )
				$course_id = "-1";
		}
	}
?>