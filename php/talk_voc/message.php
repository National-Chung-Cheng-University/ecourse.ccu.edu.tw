<?php
	require 'fadmin.php';
	update_status ("╗yн╡▓сд╤л╟");
	if ( !(isset($PHPSESSID) && session_check_teach($PHPSESSID)) ) {
		show_page( "not_access.tpl" ,"┼vнн┐∙╗~");
	}
	if ( $guest == "1" ) {

	}
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate ( "./templates" );
	$tpl->define ( array ( body => "message.tpl" ) );
	$tpl->assign( PEERNAME, $PerN );
	$PerI = trim ( $PerI );
	$tpl->assign( PERID, $PerI );
	$tpl->assign( TEXTC, $text );
	$tpl->assign( MYNAME, $MyN );
	$tpl->assign( PHPSID, $PHPSESSID );
	
	$tpl->parse( BODY, "body" );
	$tpl->FastPrint("BODY");
?>