<?php

session_id($PHPSESSID);
session_start();

include("class.FastTemplate.php3");
$tpl = new FastTemplate ( "./templates" );
$tpl->define ( array ( make_color => "make_color1.tpl") );
$tpl->assign ( MESSAGE, NULL );
$tpl->parse( BODY, "make_color" );
$tpl->FastPrint("BODY");
?>