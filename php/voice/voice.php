<?php
	require 'fadmin.php';
	update_status ("�y����ѫ�");
	if ( !(isset($PHPSESSID) && session_check_teach($PHPSESSID)) ) {
		show_page( "not_access.tpl" ,"�v�����~");
	}
	if ( $guest == "1" ) {

	}
	show_main();

	function show_main () {
		global $SERVER_NAME;
		$ip = getenv("SERVER_NAME");
		if ( $ip == "" )
			$ip = $SERVER_NAME;
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		$tpl->define ( array ( body => "voice.tpl" ) );
		$tpl->assign( IP, $ip );
		$tpl->parse( BODY, "body" );
		$tpl->FastPrint("BODY");
	}
?>