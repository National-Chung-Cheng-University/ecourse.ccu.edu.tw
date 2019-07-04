<?
require 'fadmin.php';
	if (!(isset($PHPSESSID) && session_check_stu($PHPSESSID)) ) {
		show_page( "not_access.tpl" ,"權限錯誤");
	}
	
	else {
		global $courseid, $action;
		$Q1 = "select authorization FROM user where id = '$user_id'";
		if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
			$message = "$message - 資料庫讀取錯誤!!";
		}else
			$row = mysql_fetch_array( $result );

		if ( $row['authorization'] == "4" ) {
			if($action == "showintro"){
				if(!session_is_registered("course_id")){
					session_register("course_id");
				}
				$course_id = $courseid;
				header( "Location: intro.php?PHPSESSID=".session_id());
			}
			else if($action == "upload_material"){
				if(!session_is_registered("course_id")){
					session_register("course_id");
				}
				$course_id = $courseid;
				header( "Location: ../textbook/Upload_main.php?PHPSESSID=".session_id());
			}
			else if($action == "edit_material"){
				if(!session_is_registered("course_id")){
					session_register("course_id");
				}
				$course_id = $courseid;
				header( "Location: ../textbook/editor.php?PHPSESSID=".session_id());
			}
			else if($action == "preview_material"){
				if(!session_is_registered("course_id")){
					session_register("course_id");
				}
				$course_id = $courseid;
				header( "Location: ../textbook/material.php?PHPSESSID=".session_id());
			}
			else if($action == "import_material"){
				if(!session_is_registered("course_id")){
					session_register("course_id");
				}
				$course_id = $courseid;
				header( "Location: ../textbook/import2.php?PHPSESSID=".session_id());
			}
			else{
				show_page_d ();
			}
		}
		else {
			header( "Location: http://$SERVER_NAME/php/Courses_Admin/guest.php?PHPSESSID=".session_id());
		}
		
	}

	function show_page_d ( $message="" ){
		global $version, $course_id, $skinnum, $DB, $user_id;
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		$tpl->define ( array ( body => "upload_intro_his.tpl" ) );
		$tpl->define_dynamic ( "course_list" , "body" );
		$tpl->assign( SKINNUM , $skinnum );
		$color = "#000066";
		$tpl->assign( COLOR , $color );
		$tpl->assign( TYPE , "colspan=2" );
		if ( $version == "C" ) {
			$tpl->assign( GNAME , "<font color =#FFFFFF>開課單位</font>" );
			$tpl->assign( CNO , "<font color =#FFFFFF>課程編號</font>" );
			$tpl->assign( CNAME , "<font color =#FFFFFF>課程名稱</font>" );
			$tpl->assign( CMATERIAL , "<font color =#FFFFFF>授課教材</font>" );
			$tpl->assign( CTEACH , "<font color =#FFFFFF>授課教師</font>" );
		}
		else {
			$tpl->assign( GNAME , "<font color =#FFFFFF>Department</font>" );
			$tpl->assign( CNO , "<font color =#FFFFFF>No.</font>" );
			$tpl->assign( CNAME , "<font color =#FFFFFF>Course Name</font>" );
			$tpl->assign( CMATERIAL , "<font color =#FFFFFF>Material</font>" );
			$tpl->assign( CTEACH , "<font color =#FFFFFF>Teachers</font>" );
		}
		$tpl->parse ( COURSE_LIST, ".course_list" );
		$tpl->assign( TYPE , "" );
		
		$Q1 = "select name FROM user where id = '$user_id'";
		if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
			$message = "$message - 資料庫讀取錯誤!!";
		}else
			$row = mysql_fetch_array( $result );
		$Q2 = "select course.name, course.course_no, course.a_id course_id from course, course_group where course_group.name = '$row[name]' and course_group.a_id = course.group_id order by course.course_no";
		if ( $result2 = mysql_db_query( $DB, $Q2 ) ) {
			while($row2 = mysql_fetch_array( $result2 )){
				$color = "#F0FFEE";
				$Q3 = "select introduction, name FROM course where a_id ='$row2[course_id]'";
				if ( $result3 = mysql_db_query( $DB, $Q3 ) ) {
					$row3 = mysql_fetch_array( $result3 );
					if( $row3[introduction]!= "" || is_file("../../$row2[course_id]/intro/index.html") || is_file("../../$row2[course_id]/intro/index.htm") || is_file("../../$row2[course_id]/intro/index.doc") || is_file("../../$row2[course_id]/intro/index.pdf")){
						$color = "#E6FFFC";
					}
				}
				else{
					$message = "$message - 資料庫讀取錯誤!!";
				}
				
				$Q4 = "select user.name from user, teach_course where teach_course.course_id = $row2[course_id] and teach_course.teacher_id = user.a_id";
				$name = "";
				if ( $result4 = mysql_db_query( $DB, $Q4 ) ) {
					while($row4 = mysql_fetch_array( $result4 )){
						$name = $name.$row4[name]." ";
					}
				}
				else{
					$message = "$message - 資料庫讀取錯誤!!";
				}
				
				$tpl->assign( COLOR , $color );
				$tpl->assign( GNAME , $row["name"] );
				$tpl->assign( CNAME , "<a href=\"upload_intro_his.php?action=showintro&courseid=$row2[course_id]\">$row2[name]</a>" );
				$tpl->assign( CMATERIAL , "<a href=\"upload_intro_his.php?action=upload_material&courseid=$row2[course_id]\">上傳</a>　".
										  "<a href=\"upload_intro_his.php?action=edit_material&courseid=$row2[course_id]\">編輯</a>　".
										  "<a href=\"upload_intro_his.php?action=preview_material&courseid=$row2[course_id]\">預覽</a>　".
										  "<a href=\"upload_intro_his.php?action=import_material&courseid=$row2[course_id]\">匯入</a>　");
				$tpl->assign( CNO , $row2[course_no] );
				$tpl->assign( CTEACH, $name );
				$tpl->parse ( COURSE_LIST, ".course_list" );
			}
		}
		else{
			$message = "$message - 資料庫讀取錯誤!!";
		}
		$tpl->parse( BODY, "body" );
		$tpl->FastPrint("BODY");
	}	
?>