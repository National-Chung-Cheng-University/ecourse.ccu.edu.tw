<?
	require 'common.php';
	if ( !(isset($PHPSESSID) && session_check_stu($PHPSESSID)) ) {
		show_page( "not_access.tpl" ,"ลvญญฟ๙ป~");
		exit;
	}
	
	$refreshmin = 1.5;
	if ( date("U") - $time > 1200 )
		exit;
	
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate("./templates");
	$tpl->define(array(main => "noop.tpl"));
	$tpl->assign ( PHPID, $PHPSESSID );
	$tpl->assign ( TIME, date("U") - $time );
	$tpl->parse(BODY, "main");
	$tpl->FastPrint(BODY);
?>