<?php
	require 'fadmin.php';
	update_status ("歷史區預覽課程大綱");
	if ( !(isset($PHPSESSID) && ($check = session_check_teach($PHPSESSID)) ) ) {
		show_page( "not_access.tpl" ,"權限錯誤");
	}

	show_page_d ();	
	
	function show_page_d ( $error="" ) {
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id, $check, $teacher, $version, $query, $skinnum, $PHPSESSID, $is_hist, $hist_year, $hist_term;
		if ( is_file("../../echistory/$hist_year/$hist_term/$course_id/intro/index.html") ) {
			if ( $check == 2 && $teacher == 1 ) {
				$fp = fopen("../../echistory/$hist_year/$hist_term/$course_id/intro/index.html", "r");
				$content = fread($fp , filesize("../../echistory/$hist_year/$hist_term/$course_id/intro/index.html"));
				fclose($fp);
				include("class.FastTemplate.php3");
				$tpl = new FastTemplate ( "./templates" );
				$tpl->define ( array ( body => "intro2.tpl") );
				$ip = getenv ("SERVER_NAME" );
				if ( $ip == "" )
					$ip = $SERVER_NAME;
				$tpl->assign ( BASE, "http://$ip/echistory/$hist_year/$hist_term/$course_id/intro/");
				$tpl->assign ( HEAD, "<base href=\"http://$ip/php/hist_backup/\" target=\"_self\">");
				$tpl->assign( SKINNUM , $skinnum );
			}
			else {
				header( "Location: ../../echistory/$hist_year/$hist_term/$course_id/intro/");
				exit;

			}
		}
		else if ( is_file("../../echistory/$hist_year/$hist_term/$course_id/intro/index.htm") ) {
			if ( $check == 2 && $teacher == 1 ) {
				$fp = fopen("../../echistory/$hist_year/$hist_term/$course_id/intro/index.htm", "r");
				$content = fread($fp , filesize("../../echistory/$hist_year/$hist_term/$course_id/intro/index.htm"));
				fclose($fp);
				include("class.FastTemplate.php3");
				$tpl = new FastTemplate ( "./templates" );
				$tpl->define ( array ( body => "intro2.tpl") );
				$ip = getenv ("SERVER_NAME" );
				if ( $ip == "" )
					$ip = $SERVER_NAME;
				$tpl->assign ( BASE, "http://$ip/echistory/$hist_year/$hist_term/$course_id/intro/");
				$tpl->assign ( HEAD, "<base href=\"http://$ip/php/hist_backup/\" target=\"_self\">");
				$tpl->assign( SKINNUM , $skinnum );
			}
			else {
				header( "Location: ../../$course_id/intro/");
				exit;

			}
		}
		else if ( is_file("../../echistory/$hist_year/$hist_term/$course_id/intro/index.doc") ){
			global $check, $version, $course_id, $teacher;
			include("class.FastTemplate.php3");
			$tpl = new FastTemplate ( "./templates" );
			$content = "<HTML>\n<HEAD>\n<TITLE>授課大綱</TITLE>\n".
						"<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=../../echistory/$hist_year/$hist_term/$course_id/intro/index.doc\">\n".
						"</HEAD>\n</HTML>";
			$tpl->define ( array ( body => "intro2.tpl") );
			$tpl->assign ( HEAD, "");
			$tpl->assign( SKINNUM , $skinnum );
		}
		else if ( is_file("../../echistory/$hist_year/$hist_term/$course_id/intro/index.pdf") ){
			global $check, $version, $course_id, $teacher;
			include("class.FastTemplate.php3");
			$tpl = new FastTemplate ( "./templates" );
			$content = "<HTML>\n<HEAD>\n<TITLE>授課大綱</TITLE>\n".
						"<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=../../echistory/$hist_year/$hist_term/$course_id/intro/index.pdf\">\n".
						"</HEAD>\n</HTML>";
			$tpl->define ( array ( body => "intro2.tpl") );
			$tpl->assign ( HEAD, "");
			$tpl->assign( SKINNUM , $skinnum );
		}
		else if (is_file("../../echistory/$hist_year/$hist_term/$course_id/intro/index.ppt"))
		{
			global $check, $version, $course_id, $teacher;
			include("class.FastTemplate.php3");
			$tpl = new FastTemplate ("./templates");
			$content = "<html>\n<head>\n<title>授課大綱</title>\n".
						"<meta http-equiv=REFRESH content=\"0;url=../../echistory/$hist_year/$hist_term/$course_id/intro/index.ppt\">\n".
						"</head>\n</html>";
			$tpl->define (array(body => "intro2.tpl"));
			$tpl->assign (HEAD, "");
			$tpl->assign (SKINNUM, $skinnum);
		}
		else {
				$Q1 = "select name FROM hist_course where year = '$hist_year' AND term = '$hist_term' AND a_id ='$course_id'";
				if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
					$message = "$message - 資料庫連結錯誤!!";
					show_page ( "not_access.tpl", $message );
				}
				else if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
					$message = "$message - 資料庫讀取錯誤!!";
					show_page ( "not_access.tpl", $message );
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

			if ( stristr($content,"<html>") == NULL )
				$content = str_replace ( "\n", "<BR>", $content );
			$tpl->assign ( MER, $content );
			$tpl->assign ( ERR, $error );
			$tpl->parse( BODY2, "body");
			
			if ( $_GET[showintro] == 1 ) {
				$tpl->FastPrint("BODY2");
			}
			else
				echo "<div align=\"center\" class=\"style1\"><a href=\"hist_intro.php?showintro=1\">預覽授課大網</a></div>";
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