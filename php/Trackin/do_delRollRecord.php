<?php
require 'fadmin.php';
update_status ("�ǥͧ���ϥΰO��");
	
if( !(isset($PHPSESSID) && ($check = session_check_teach($PHPSESSID)) ) && !(session_is_registered("admin") && $admin == 1) )
{
	show_page( "not_access.tpl" ,"�v�����~");
	exit;
}
if($check < 2 )
{
	if( $version=="C" ) {
		show_page( "not_access.tpl" ,"�A�S���v���ϥΦ��\��");
		exit;
	}
	else {
		show_page( "not_access.tpl" ,"You have No Permission!!");
		exit;
	}
}


global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $skinnum;
if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
	echo ( "��Ʈw�s�����~!!" );
	return;
}
	

			
				$Q2 =  "DELETE FROM roll_book WHERE roll_id = '$roll_id'";
				echo $Q2."<br>";
				
				if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) 
				{
						echo ("�R�����~");		
						return;	
				}
				
				
		
		header("Location: RollBook.php?PHPSESSID=".$PHPSESSID);
		

?>
