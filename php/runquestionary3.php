<?php
require 'fadmin.php';

if( isset($PHPSESSID) && ( $check = session_check_teach($PHPSESSID)) )
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
		exit;
	}
	print_r($_SESSION);
	//$Q1 = INS


}