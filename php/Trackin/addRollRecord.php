<?php
require 'fadmin.php';
include 'picture_encryption.php';
update_status ("學生完整使用記錄");

if( !(isset($PHPSESSID) && ($check = session_check_teach($PHPSESSID)) ) && !(session_is_registered("admin") && $admin == 1) )
{
	show_page( "not_access.tpl" ,"權限錯誤");
	exit;
}
if($check < 2 )
{
	if( $version=="C" ) {
		show_page( "not_access.tpl" ,"你沒有權限使用此功能");
		exit;
	}
	else {
		show_page( "not_access.tpl" ,"You have No Permission!!");
		exit;
	}
}


global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $skinnum, $course_year, $course_term;
if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
	echo ( "資料庫連結錯誤!!" );
	return;
}
$Q1 = "Select student_id From take_course Where course_id='$course_id' and year='$course_year' and term = '$course_term' Order By student_id ASC";
if ( !($resultOBJ = mysql_db_query( $DB, $Q1 ) ) )
{
	echo ("資料庫讀取錯誤!!");
	return;
}
else
{
	if( mysql_num_rows ( $resultOBJ ) == 0 )
	{
		if( $version=="C" )
			show_page( "not_access.tpl" ,"此課程尚未有任何學生!");
		else
			show_page( "not_access.tpl" ,"There is no Student in this Class!!");
	}
	else
	{
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate("./templates");
		
		$tpl->define(array(student_list => "addRollRecord.tpl"));

		$tpl->define_dynamic("row", "student_list");
		$tpl->assign( SKINNUM , $skinnum );
		$color = "#000066";
		$tpl->assign( COLOR , $color );
		if ( $version == "C" ) {
			$tpl->assign( STUDENT_ID, "<b><font color=#FFFFFF>學號</font></b>" );
			$tpl->assign( STUDENT_NAME, "<b><font color=#FFFFFF>姓名</font></b>" );

			/*2008.07.23 @modify by w60292 新增照片一欄*/			

			$tpl->assign( STUDENT_PIC, "<b><font color=#FFFFFF>照片</font></b>" );
		
			/*******************************************/

			$tpl->assign( STATE, "<b><font color=#FFFFFF>出席情況<br>
										<input type='radio' name='state' value='0' onClick='select_all();' checked>
									  出席
										<input type='radio' name='state' value='1' onClick='select_all();'>
									  缺席
										<input type='radio' name='state' value='2' onClick='select_all();'>
									  遲到
										<input type='radio' name='state' value='3' onClick='select_all();'>
									  早退
										<input type='radio' name='state' value='4' onClick='select_all();'>
									  請假
										<input type='radio' name='state' value='5' onClick='select_all();'>
									  其他 [全選] 
									  </font></b>" );
									  
			$tpl->assign( NOTE, "<b><font color=#FFFFFF>備註</font></b>" );
		}
		else {//en version
			
			$tpl->assign( STUDENT_ID, "<b><font color=#FFFFFF>ID</font></b>" );
			$tpl->assign( STUDENT_NAME, "<b><font color=#FFFFFF>Name</font></b>" );

			/*2008.07.23 @modify by w60292 新增照片一欄*/

			$tpl->assign( STUDENT_PIC, "<b><font color=#FFFFFF>Picture</font></b>" );

			/*******************************************/
			/*
			$tpl->assign( STATE, "<b><font color=#FFFFFF>state<br>
										<input type='radio' name='state' value='0' onClick='select_all();' checked>
									  Attendance
										<input type='radio' name='state' value='1' onClick='select_all();'>
									  Absentation
										<input type='radio' name='state' value='2' onClick='select_all();'>
									  Arriving late
										<input type='radio' name='state' value='3' onClick='select_all();'>
									  Leaving early
										<input type='radio' name='state' value='4' onClick='select_all();'>
									  Asking for leave
										<input type='radio' name='state' value='5' onClick='select_all();'>
									  Else [Select All] 
									  </font></b>" );	
			*/
			$tpl->assign( STATE, "<b><font color=#FFFFFF>State<br>
                                                                                <input type='radio' name='state' value='0' onClick='select_all();' checked>
                                                                          出席
                                                                                <input type='radio' name='state' value='1' onClick='select_all();'>
                                                                          缺席
                                                                                <input type='radio' name='state' value='2' onClick='select_all();'>
                                                                          遲到
                                                                                <input type='radio' name='state' value='3' onClick='select_all();'>
                                                                          早退
                                                                                <input type='radio' name='state' value='4' onClick='select_all();'>
                                                                          請假
                                                                                <input type='radio' name='state' value='5' onClick='select_all();'>
                                                                          Else [Select All]
                                                                          </font></b>" );
			$tpl->assign( NOTE, "<b><font color=#FFFFFF>Note</font></b>" );
		}
		$tpl->parse(ROWS, ".row");
		$color = "#BFCEBD";
		//if ( $nocredit != 1 )
			$Q1 = "Select u.id, u.email, u.name, u.a_id From user u ,take_course tc Where u.a_id=tc.student_id And tc.course_id='$course_id' and tc.credit = '1' and tc.year='$course_year' and tc.term = '$course_term' Order By u.id ASC";
		//else
			//$Q1 = "Select u.id, u.email, u.name, u.a_id From user u ,take_course tc Where u.a_id=tc.student_id And tc.course_id='$course_id' and tc.credit = '0' Order By u.id ASC";
		$result = mysql_db_query( $DB, $Q1 );
		$color == "#F0FFEE";
		while ( $row1 = mysql_fetch_array($result) )
		{
			if ( $color == "#F0FFEE" )
				$color = "#E6FFFC";
			else
				$color = "#F0FFEE";
			$tpl->assign( COLOR , $color );
			
			$tpl->assign(STUDENT_ID, $row1[id]);

			if( $row1['email'] != NULL )
				$tpl->assign(STUDENT_NAME, "<A HREF=mailto:".$row1['email'].">".$row1['name']."</A>");
			else
				$tpl->assign(STUDENT_NAME, $row1['name']);

			/*2008.07.23 @modify by w60292 新增照片一欄*/

			//$tpl->assign(STUDENT_PIC, "<IMG SRC=\"../../Stu-Photo/".$row1[id].".jpg\" width=\"103\" height=\"133\">");
			$picid = pic_encrypt($row1[id]);
                        $tpl->assign(STUDENT_PIC, "<img src=\"../url_convert.php?id=".$picid."\" width=\"103\" height=\"133\">");
			/*******************************************/

			$a_id = $row1['a_id'];
			//$tpl->assign(HIDDEN, "<input name='student$a_id' type='hidden' value='$a_id'>");

			$tpl->assign(STATE, "<b><input name='state$a_id' type='radio' value='0' checked>
									  出席
										<input type='radio' name='state$a_id'  value='1'>
									  缺席
										<input type='radio' name='state$a_id' value='2'>
									  遲到
										<input type='radio' name='state$a_id' value='3'>
									  早退
										<input type='radio' name='state$a_id' value='4'>
									  請假
										<input type='radio' name='state$a_id' value='5'>
									  其他  </b>");
									  
			$tpl->assign(NOTE, "<input type='text' name='note$a_id'>");				
			
			
			$tpl->parse(ROWS, ".row");
		}
		
		
		$tpl->assign(NOCREDIT, $nocredit);
		$tpl->assign(CID.$nocredit, "selected");
		
		
		$tpl->parse(BODY, "student_list");
		$tpl->FastPrint("BODY");
	}
}
?>
