<?php
	require 'fadmin.php';

	if (!(isset($PHPSESSID) && session_check_admin($PHPSESSID)))
                show_page("not_access.tpl","Åv­­¿ù»~");

	include("class.FastTemplate.php3");
	$tpl = new FastTemplate("./templates");
	$tpl->define(array(body => "query_tch_log_index.tpl"));
	$tpl->parse(BODY,"body");
	$tpl->FastPrint("BODY");
?>
