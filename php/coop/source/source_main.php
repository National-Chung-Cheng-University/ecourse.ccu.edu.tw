<?
	require 'fadmin.php';
	update_status ("�s���귽");

	if ( !(isset($PHPSESSID) && session_check_teach($PHPSESSID) && ($check = check_group ( $course_id, $coopgroup, $coopcaseid )) >= 2 ) ) {
		show_page( "not_access.tpl" ,"�v�����~");
	}

	include("class.FastTemplate.php3");

	$tpl = new FastTemplate("./templates");

	$tpl->define(array(main => "source_main.tpl"));

	$tpl->assign("PHPID", $PHPSESSID);

	$tpl->parse(BODY, "main");

	$tpl->FastPrint(BODY);

?>