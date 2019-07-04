<?php
require 'fadmin.php';
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
if ( !($result1 = mysql_db_query( $DB, $Q1 ) ) )
{
	echo ("資料庫讀取錯誤!!");
	return;
}
else
{					
		while ( $row = mysql_fetch_array($result1) )
		{
			
				$Q2 =  "UPDATE roll_book SET roll_date = '$date',state = '".$_POST["state".$row['student_id']]."' ,note = '".$_POST["note".$row['student_id']]."' WHERE user_id = '".$row['student_id']."' and roll_id ='$roll_id'";
				//echo $Q2."<br>";
				
				if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) 
				{
						echo ("資料庫寫入錯誤");		
						return;	
				}
				
				
		}	
		header("Location: RollBook.php?PHPSESSID=".$PHPSESSID);
		
}
?>
