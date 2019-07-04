<?php
require 'fadmin.php';

if(isset($PHPSESSID) && session_check_teach($PHPSESSID) == 2)
{
	if ( $action == "uploadpage" ) {
		uploadpage ();
	}
	else if ( $action == "upload" ) {
		if( eregi( "\.php$", $upname ) || eregi("\.cgi$", $upname) )
			echo "不合法檔案<br>";
		else
		{ 
			if ( fileupload ( $upfile, "../../$course_id/homework/upload", $upname, $mode=0644 )) {
				$fp = fopen( "../../$course_id/homework/comment/$upname.txt", "w+" );
				if($fp)
				{
					fputs( $fp, $comment );
					fclose( $fp );
				}
			}
		}
		uploadpage ();
	}
	else
		show_page_d();
}
else
{
	if( $version=="C" ) {
		show_page( "not_access.tpl" ,"你沒有權限使用此功能");
		exit;
	}
	else {
		show_page( "not_access.tpl" ,"You have No Permission!!");
		exit;
	}
}

function show_page_d () {
	global $PHPSESSID, $version;
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate("./templates");
	if($version == "C")
		$tpl->define( array(main=>"image.tpl") );
	else
		$tpl->define( array(main=>"image.tpl") );
	$tpl->assign(PHPSD,$PHPSESSID);
	$tpl->parse(BODY,"main");
	$tpl->FastPrint("BODY");
}

function uploadpage () {
	global $version, $message;
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate("./templates");
	if($version == "C")
		$tpl->define( array(main=>"upload_image.tpl") );
	else
		$tpl->define( array(main=>"upload_image.tpl") );
	$tpl->assign(MESSAGE,$message);
	$tpl->parse(BODY,"main");
	$tpl->FastPrint("BODY");
}