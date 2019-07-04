<?php
	require 'fadmin.php';
	update_status ("加入助教");
	if ( isset($PHPSESSID) && session_check_teach($PHPSESSID) == 2 ) {
		$id2 = stripslashes( $id );
		$email2 = stripslashes( $email );
		if ( ($id != "" && $email != "" && $flag == 1) || ($id != "" && $flag == 2) ) {
			if ( ($error = add_tech( )) == -1 )
				if ( $version == "C" )
					show_page_d ( "助教 $id2 加入成功!!" );
				else
					show_page_d ( "TA $id2 Add Successful!!" );
			else if ( $flag == 1 )
				show_page_d ( $error, $id, $email);
			else
				show_page_d ( $error );
			
		}
		else if ( $a_id != "" && $flag != 1 && $flag != 2 ) {
			if ( ($error = del_teach( )) == -1 )
				if ( $version == "C" )
					show_page_d ( "助教 $id2 刪除成功!!" );
				else
					show_page_d ( "TA $id2 Deleted!!" );
			else
				show_page_d ( $error );
			
		}
		else if ( $flag != 1 && $flag != 2 && isset($a_id) )
			if ( $version == "C" )
				show_page_d ( "助教帳號錯誤" );
			else
				show_page_d ( "TA ID Error" );
		else if ( $flag == 1 && isset($id) && $id == "" )
			if ( $version == "C" )
				show_page_d ( "請輸入助教帳號!!!", $id2, $email2 );
			else
				show_page_d ( "Please Input The TA's ID !!!", $id2, $email2 );
		else if ( $flag == 1 && isset($email) && $email == "" )
			if ( $version == "C" )
				show_page_d ( "請輸入助教e-Mail!!!", $id2, $email2 );
			else
				show_page_d ( "Please Input TA's e-Mail!!!", $id2, $email2 );
		else
			show_page_d ( );
	}
	else if ( $version == "C" )
		show_page( "not_access.tpl", "你沒有權限使用此功能!!");
	else
		show_page( "not_access.tpl", "Access Deny!!");
	
	function add_tech ( ) {
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $id, $pass, $course_id, $email, $flag, $user_id, $course_year, $course_term;
		$Q1 = "select a_id FROM user WHERE id = '$id'";
		
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "資料庫連結錯誤!!";
			return $error;
		}
		$result = mysql_db_query( $DB,$Q1  );
		if(mysql_num_rows($result) == 0)
			$Q2 = "insert into user ( id, pass, ftppass, authorization, email, forbear ) values ( '$id', '" . passwd_encrypt($pass) . "', '" . md5($pass) . "', '2', '$email', '1800' )";
		else
			$Q2 = "update user set pass = '" . passwd_encrypt($pass) . "', ftppass = '" . md5($pass) . "', email = '$email' where id = '$id'";


		if ( !($result = mysql_db_query( $DB,$Q2  ) ) ) {
			$error = "資料庫寫入錯誤!! $Q2";
			return $error;
		}
		$Q3 = "select a_id FROM user WHERE id = '$id'";
		if ( !($result = mysql_db_query( $DB,$Q3  ) ) ) {
			$error = "資料庫讀取錯誤!! $Q3";
			return $error;
		}
		else
			$row2 = mysql_fetch_assoc($result);
		
		$Q4 = "select teacher_id from teach_course where teacher_id = '$row2[a_id]' and course_id = '$course_id' and year = '$course_year' and term = '$course_term'";
		if ( !($result = mysql_db_query( $DB,$Q4  ) ) ) {
			$error = "資料庫讀取錯誤!! ";
			return $error;
		}
		if(mysql_num_rows($result) == 0)
		{
			$Q5 = "insert into teach_course (teacher_id, course_id, year, term) values ('$row2[a_id]', '$course_id', '$course_year', '$course_term')";
			if ( !($result = mysql_db_query( $DB,$Q5  ) ) ) {
				$error = "資料庫寫入錯誤!! ";
				return $error;
			}
		}
		return -1;
	}

	function del_teach ( ) {
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $a_id, $course_id ;
		$S1 = "select * from teach_course where teacher_id = '$a_id'";
		$Q3 = "delete from user where a_id = '$a_id'";
		$Q4 = "delete from gbfriend where my_id = '$a_id' or friend_id='$a_id'";
		$Q1 = "delete from teach_course where teacher_id = '$a_id' and course_id = '$course_id'";
		$Q2 = "delete from log where user_id = '$a_id'";
		
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "資料庫連結錯誤!!";
			return $error;
		}
		for ( $i = 1 ; $i <= 1 ; $i ++ ) {
			$Q = "Q$i";
			if ( !($result = mysql_db_query( $DB, $$Q ) ) ) {
				$error = "資料庫刪除錯誤!!";
				return $error;
			}
		}
		if ( !($result = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
			$error = "資料庫刪除錯誤!!";
			return $error;
		}
		if ( !($result1 = mysql_db_query( $DB, $S1 ) ) ) {
				$error = "資料庫讀取錯誤!!";
				return $error;
		}
		if ( mysql_num_rows( $result1 ) == 0 ) {
			if ( !($result = mysql_db_query( $DB, $Q2 ) ) ) {
				$error = "資料庫刪除錯誤!!";
				return $error;
			}

			if ( !($result = mysql_db_query( $DB, $Q3 ) ) ) {
				$error = "資料庫刪除錯誤!!";
				return $error;
			}
			if ( !($result = mysql_db_query( $DB, $Q4 ) ) ) {
				$error = "資料庫刪除錯誤!!";
				return $error;
			}
		}

		return -1;
	}	
	
	function show_page_d ( $message="" , $id="", $email="" ) {
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id, $version, $skinnum, $user_id, $course_year, $course_term;
		$Q1 = "select authorization FROM user WHERE id = '$user_id'";
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$message = "$message - 資料庫連結錯誤!!";
		}
		if ( !($result1 = mysql_db_query( $DB, $Q1 ) ) ) {
			$message = "$message - 資料庫讀取錯誤!!";
		}
		$row1 = mysql_fetch_array( $result1 );
		if ( $row1['authorization'] > 1 ) {
			if ( $version == "C" )
				show_page( "not_access.tpl", "你沒有權限使用此功能!!");
			else
				show_page( "not_access.tpl", "Access Deny!!");
			exit;
		}
			
		
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		$tpl->define ( array ( body => "new_ta.tpl" ) );
		$tpl->define_dynamic ( "ta_list" , "body" );
		$tpl->define_dynamic ( "tid_list" , "body" );
		
		$tpl->assign( TVD , "" );
		$tpl->assign( TID , "助教帳號" );
		$tpl->parse ( TEID_LIST, ".tid_list" );
		$tpl->assign( SKINNUM , $skinnum );
		
		$color = "#000066";
		$tpl->assign( COLOR , $color );
		$tpl->assign( FORMSTART , "<form>" );
		if ( $version == "C" ) {
			$tpl->assign( TL1 , "從現有助教中選入" );
			$tpl->assign( TL2 , "加新助教(不可使用學號，建議使用TA+學號)" );
			$tpl->assign( TITLE , "新增助教資料" );
			$tpl->assign( UID , "<font color=#FFFFFF size=2>助教ID</font>" );
			//$tpl->assign( PASS , "<font color=#FFFFFF size=2>助教密碼</font>" );
			$tpl->assign( UI2 , "助教ID" );
			$tpl->assign( PAS2 , "助教密碼" );
			$tpl->assign( EMAIL , "<font color=#FFFFFF size=2>e-Mail</font>" );
			$tpl->assign( BUTTON , "<font color=#FFFFFF size=2>刪除此助教</font>" );
			$tpl->assign( ADD , "加入" );
			$tpl->assign( CLEAR , "清除" );
		}
		else {
			$tpl->assign( TL1 , "Add Old TA" );
			$tpl->assign( TL2 , "Add New TA" );
			$tpl->assign( TITLE , "Add The TA's Info" );
			$tpl->assign( UID , "<font color=#FFFFFF size=2>TA's ID</font>" );
			//$tpl->assign( PASS , "<font color=#FFFFFF size=2>TA's PASSWORD</font>" );
			$tpl->assign( UI2 , "TA's ID" );
			$tpl->assign( PAS2 , "TA's PASSWORD" );
			$tpl->assign( EMAIL , "<font color=#FFFFFF size=2>e-Mail</font>" );
			$tpl->assign( BUTTON , "<font color=#FFFFFF size=2>Delete</font>" );
		}
		$tpl->parse ( TA_LIST, ".ta_list" );
		$color = "#CCCCCC";
		$not_include = ""; 
		
		//linsy@20121009, 修正新學期課程會出現一年前同樣課程編號的課程助教名單，但助教卻看不到課程的問題。
		//$Q1 = "select tc.teacher_id as a_id, u.id, u.pass, u.email FROM teach_course tc, user u WHERE tc.course_id = '$course_id' and u.authorization = '2' and u.a_id = tc.teacher_id";
		$Q1 = "select tc.teacher_id as a_id, u.id, u.pass, u.email FROM teach_course tc, user u WHERE tc.course_id = '$course_id' and tc.year = '$course_year' and tc.term = '$course_term' and u.authorization = '2' and u.a_id = tc.teacher_id";
		if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
			$message = "$message - 資料庫讀取錯誤!!";
		}
		else if ( mysql_num_rows( $result ) != 0 ) {
			while ( $row = mysql_fetch_array( $result ) ) {
				$not_include .= "and a_id != '".$row["a_id"]."' ";
				if ( $color == "#CCCCCC" )
					$color = "#F0FFEE";
				else
					$color = "#CCCCCC";
				$tpl->assign( COLOR , $color );
				$tpl->assign( FORMSTART , "<form method=post action=./new_ta.php>" );
				$tpl->assign( UID , $row["id"] );
				/*
				if ( $row["pass"] == "" )
					$tpl->assign( PASS , "　" );
				else
					$tpl->assign( PASS , $row["pass"] );
				*/
				$tpl->assign( EMAIL , $row["email"] );
				if ( $version == "C" )
					$tpl->assign( BUTTON , "<input type=hidden name=a_id value=". $row["a_id"] ."><input type=hidden name=id value=". $row["id"] ."><input type=submit value=刪除>" );
				else
					$tpl->assign( BUTTON , "<input type=hidden name=a_id value=". $row["a_id"] ."><input type=hidden name=id value=". $row["id"] ."><input type=submit value=Del>" );
				$tpl->parse ( TA_LIST, ".ta_list" );
			}
		}
		
		/*
		$Q2 = "select id,a_id FROM user where authorization = '2' $not_include order by id";
		if ( !($result = mysql_db_query( $DB, $Q2 ) ) ) {
			$message = "$message - 資料庫讀取錯誤!!";
		}
		else
			while ( $row = mysql_fetch_array( $result ) ) {
				$tpl->assign( TID , $row["id"] );
				$tpl->assign( TVD , $row["id"] );
				$tpl->parse ( TEID_LIST, ".tid_list" );
			}
		*/		

		$tpl->assign( MES , $message );
		$tpl->assign( VID , $id );
		$tpl->assign( VMAIL , $email );
		$tpl->parse( BODY, "body" );
		$tpl->FastPrint("BODY");
	}
?>
