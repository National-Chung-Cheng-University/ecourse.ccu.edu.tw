<?php
	require 'fadmin.php';
	if ( !(isset($PHPSESSID) && session_check_teach($PHPSESSID)) ) {
		show_page( "not_access.tpl" ,"權限錯誤");
	}
	if ( $user_id == "guest" ) {
		if( $version == "C" ) {
			show_page ( "not_access.tpl", "$error 你沒有權限使用此功能!!" );
			exit;
		}
		else {
			show_page ( "not_access.tpl", "$error You have No Permission!!" );
			exit;
		}
	}

	include("class.FastTemplate.php3");
	$tpl = new FastTemplate ( "./templates" );
	$tpl->define ( array ( body => "SSMS.tpl") );

	if( $version == "C" ) {
		$tpl->assign ( PATH, "img" );
		$tpl->assign ( IMGONE, "sDataInput.gif" );
		$tpl->assign ( IMGTWO, "sDataQue.gif" );
	}
	else {
		$tpl->assign ( PATH, "img_E" );
		$tpl->assign ( IMGONE, "sDataInput_E.gif" );
		$tpl->assign ( IMGTWO, "sDataQue_E.gif" );
	}
	$tpl->parse( BODY, "body" );
	$tpl->FastPrint("BODY");
?>