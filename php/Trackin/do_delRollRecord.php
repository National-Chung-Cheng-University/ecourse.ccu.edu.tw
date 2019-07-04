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


global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $skinnum;
if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
	echo ( "資料庫連結錯誤!!" );
	return;
}
	

			
				$Q2 =  "DELETE FROM roll_book WHERE roll_id = '$roll_id'";
				echo $Q2."<br>";
				
				if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) 
				{
						echo ("刪除錯誤");		
						return;	
				}
				
				
		
		header("Location: RollBook.php?PHPSESSID=".$PHPSESSID);
		

?>
