<?php
	require 'fadmin.php';
	update_status ("學生資料查詢");
	include("Generate_studinfo.php");
	if ( !(isset($PHPSESSID) && session_check_teach($PHPSESSID)) ) {
		show_page( "not_access.tpl" ,"權限錯誤");
	}

	if ( $guest == "1" ) {
		if( $version == "C" ) {
			show_page ( "not_access.tpl", "$error 你沒有權限使用此功能!!" );
			exit;
		}
		else {
			show_page ( "not_access.tpl", "$error You have No Permission!!" );
			exit;
		}
	}
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	$Q1 = "Select c.course_no, c.name From course c Where c.a_id = '$course_id'";
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		echo ( "資料庫連結錯誤!!" );
		return;
	}
	if ( !($resultOBJ = mysql_db_query( $DB, $Q1 ) ) ) {
		echo ( "資料庫讀取錯誤!!" );
		return;
	}
	$row = mysql_fetch_array ( $resultOBJ );
	if($version=="C")
		$file_name="../../$course_id/student_info/s_".$row['course_no'].".bin";
	else
		$file_name="../../$course_id/student_info/s_".$row['course_no']."_E.bin";
	
	if(file_exists($file_name)) {
		$file=fopen("$file_name","r");
		$contents = fread ($file, filesize ($file_name));
		echo( $contents );
		fclose ( $file );
		//header("Location:$file_name");
	}
	else {
		if( $version=="C" )
			show_page( "not_access.tpl" ,"目前無任何學生資料");
		else
			show_page( "not_access.tpl" ,"No data now!!");
	}
	/*
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	$Q1 = "SELECT u.name, u.id, u.email, u.sex, u.php, u.authorization FROM user u, take_course tc where tc.course_id = '$course_id' and tc.student_id = u.a_id ORDER BY u.id";
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$error = "資料庫連結錯誤!!";
		show_page ( "not_access.tpl", $error );
		exit;
	}else if ( !($result = mysql_db_query( $DB, $Q1  )) ) {
		$error = "資料庫讀取錯誤!!";
		show_page ( "not_access.tpl", $error );
		exit;
	}else {
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		if ( $version == "C" )
			$tpl->define ( array ( body => "SSQueryFrame1.tpl" ) );
		else
			$tpl->define ( array ( body => "SSQueryFrame1_E.tpl" ) );
		$tpl->define_dynamic ( "stu_list" , "body" );
		$flag = 0;
		while ( $row = mysql_fetch_array($result) ) {
			$tpl->assign ( NAME, $row['name'] );
			$tpl->assign ( ID, $row['id'] );
			if ( $version == "C" ) {
				if ( $row['sex'] != 0 )
					$tpl->assign ( SEX, "男" );
				else
					$tpl->assign ( SEX, "女" );
			}
			else {
				if ( $row['sex'] != 0 )
					$tpl->assign ( SEX, "MAN" );
				else
					$tpl->assign ( SEX, "WOMAN" );
			}
			if ( $row['email'] == "" )
				$tpl->assign ( EMAIL, "N/A" );
			else
				$tpl->assign ( EMAIL, $row['email'] );
			if ( $row['php'] == "" )
				$tpl->assign ( PHP, "N/A" );
			else
				$tpl->assign ( PHP, $row['php'] );
			$tpl->parse( STU_LIST, ".stu_list" );
			$flag = 1;
		}
		if ( $flag == 0 ) { 
			$tpl->assign ( NAME, "N/A" );
			$tpl->assign ( ID, "N/A" );
			$tpl->assign ( SEX, "N/A" );
			$tpl->assign ( EMAIL, "N/A" );
			$tpl->assign ( PHP, "N/A" );
			$tpl->parse( STU_LIST, ".stu_list" );
		}
		$tpl->parse( BODY, "body" );
		$tpl->FastPrint("BODY");
	}*/
?>