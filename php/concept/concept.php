<?
//update by Autumn
//2002 03 29 12 41

	require 'fadmin.php';
	update_status ("┬s─¤▒╨зў");

	if ( !(isset($PHPSESSID) && session_check_teach($PHPSESSID)) ) {
		show_page( "not_access.tpl" ,"┼vнн┐∙╗~");
	}

	include("class.FastTemplate.php3");

	$tpl = new FastTemplate("./templates");

	$tpl->define(array(main => "concept_main.tpl"));

	$tpl->assign("CONTENT", "/$course_id/textbook/".$content);
	$tpl->assign( AID, $a_id );
	$tpl->assign("PHPID", $PHPSESSID);

	$tpl->parse(BODY, "main");

	$tpl->FastPrint(BODY);

?>