<?php
	require 'fadmin.php';
	if ( isset($PHPSESSID) && session_check_admin($PHPSESSID) ) {
			show_page_d ( );
	}
	else
		show_page( "index_ad.tpl", "�A���v�����~�A�Э��s�n�J!!" );
	
		
	function show_page_d ( $message="" ) {
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		$tpl->define ( array ( body => "search_teacher_info.tpl" ) );
		$tpl->define_dynamic ( "tech_list" , "body" );

		
		$tpl->assign( TYPE , "����" );
		$tpl->assign( NAME , "�m�W" );
		$tpl->assign( UID , "�b��" );
		$tpl->assign( PASS , "�K�X" );
		$tpl->assign( EMAIL , "E-Mail" );
		
		$tpl->parse ( TECH_LIST, ".tech_list" );
		
		global $condition;
		
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$message = "$message - ��Ʈw�s�����~!!";
		}
		
		$result=null;
		$result_1=null;
		
		if($condition==0) {
			global $teacher_id;
			//$Q1 = "select  id, name, authorization, pass FROM user WHERE (authorization = '1' or authorization = '2') and name='$teacher_name'";
			$Q1 = "select  id, name, authorization, pass, email FROM user WHERE  id='$teacher_id'";
			
			if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
			
				$message = "$message - ��ƮwŪ�����~!!";
				$result=null;
			}else{
				//OK
			}
			
		}else if($condition==1 || $condition==4) {
			//$Q1 = "select  id, name, authorization, pass FROM user WHERE (authorization = '1' or authorization = '2') and name='$teacher_name'";
			if($condition==1){
				global $teacher_name;
				$Q1 = "select  id, name, authorization, pass, email FROM user WHERE  name='$teacher_name'";
			}if($condition==4){
				global $email;
			$Q1 = "select  id, name, authorization, pass, email FROM user WHERE  email='$email'";
			}
			
			if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
			
				$message = "$message - ��ƮwŪ�����~!!";
				$result=null;
			}else{
				//OK
			}
			
		}else if($condition==2 || $condition==3) {
			$Q1 ="";
			if($condition==2){
				global $course_no;				
				$Q1 = "select a_id FROM course WHERE course_no='$course_no'";
			}if($condition==3){
				global $course_name;
				$Q1 = "select a_id FROM course WHERE name='$course_name'";
			}
			if ( !($result_1 = mysql_db_query( $DB, $Q1 ) ) ) {
				$message = "$message - ��ƮwŪ�����~!!";
				$result=null;
			}else{
				//OK
				if( $row_1 = mysql_fetch_array( $result_1 ) ) {	
					
						
					$Q2 = "select u.id, u.name, u.authorization, u.pass, u.email FROM user u , teach_course tc where tc.course_id = '".$row_1["a_id"]."' and tc.teacher_id = u.a_id ";
					
					if ( !($result = mysql_db_query( $DB, $Q2 ) ) ) {
						$message = "$message - ��ƮwŪ�����~!!".$Q2;
						$result=null;
					}else{
						//OK
					}
				}	
			}
		}
		
		
		if($result!=null)
			while ( $row = mysql_fetch_array( $result ) ) {
				
				if ( $row["authorization"] == "1" )
					$tpl->assign( TYPE , "�Юv" );
				else if ( $row["authorization"] == "2" )
					$tpl->assign( TYPE , "�U��" );
				else
					$tpl->assign( TYPE , "�ǥ�" );	
				$tpl->assign( UID , $row["id"] );
				$tpl->assign( NAME , $row["name"] );
				if ( $row["pass"] == "" )
					$tpl->assign( PASS , "�@" );
				else
					$tpl->assign( PASS , passwd_decrypt(htmlentities($row["pass"])) );
				$tpl->assign( EMAIL , $row["email"] );		
				$tpl->parse ( TECH_LIST, ".tech_list" );
			}
		
			
		$tpl->assign( MES , $message );

		$tpl->parse( BODY, "body" );
		$tpl->FastPrint("BODY");
	}
	
?>
