<?php
	require 'fadmin.php';
	update_status ("�ҵ{����");
	if ( !(isset($PHPSESSID) && ($check = session_check_teach($PHPSESSID)) || ( isset( $courseid ) && ($check = session_check_stu($PHPSESSID)) ) ) ) {
		show_page( "not_access.tpl" ,"�v�����~");
	}
	echo $courseid;

?>