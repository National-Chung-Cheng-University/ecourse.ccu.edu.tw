<?
	require 'fadmin.php';

	if ( !(isset($PHPSESSID) && session_check_teach($PHPSESSID)) ) {
		show_page( "not_access.tpl" ,"�v�����~");
	}

	if(is_file("../../echistory/$hist_year/$hist_term/$course_id/textbook/index.html") || is_file("../../echistory/$hist_year/$hist_term/$course_id/textbook/index.htm")) {
		header( "Location: /echistory/$hist_year/$hist_term/$course_id/textbook/");
	}
	else {
		header( "Location: /php/hist_backup/hist_course_menu.php");
//		show_page( "not_access.tpl" ,"�ثe�|�L�Ч�����,���I�索�C�s���[�ݦU���`�Ч�");
	}
?>
