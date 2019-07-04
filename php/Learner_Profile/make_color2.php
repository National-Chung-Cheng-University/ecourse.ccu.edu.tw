<?php
require 'fadmin.php';
global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
include("class.FastTemplate.php3");
$tpl = new FastTemplate("./templates");

session_id($PHPSESSID);
session_start();

$empty = false;
for( $i=1;$i<=4;$i++ )
{
	if ( $row1[$i] == NULL || $row1[$i] == 0 )
		$empty = true;		
	if ( $row2[$i] == NULL || $row2[$i] == 0 )
		$empty = true;
	if ( $row3[$i] == NULL || $row3[$i] == 0 )
		$empty = true;
	if ( $row4[$i] == NULL || $row4[$i] == 0 )
		$empty = true;
	if ( $row5[$i] == NULL || $row5[$i] == 0 )
		$empty = true;
}
if( $empty )
{
	$tpl->define ( array ( make_color => "./make_color1.tpl") );
	$tpl->assign ( MESSAGE, "每個空格不可留空白或填0，請依規則填入" );
	$tpl->parse( BODY, "make_color" );
	$tpl->FastPrint("BODY");
	exit;
}


$repeat_flag1 = false;
$repeat_flag2 = false;
$repeat_flag3 = false;
$repeat_flag4 = false;
$repeat_flag5 = false;
$repeat = array_count_values($row1);
for(reset($repeat);$key=key($repeat);next($repeat))
{
  if($repeat[$key]>1)
  {
    $repeat_flag1 = true;
    break;
  }
}
$repeat = array_count_values($row2);
for(reset($repeat);$key=key($repeat);next($repeat))
{
  if($repeat[$key]>1)
  {
    $repeat_flag2 = true;
    break;
  }
}
$repeat = array_count_values($row3);
for(reset($repeat);$key=key($repeat);next($repeat))
{
  if($repeat[$key]>1)
  {
    $repeat_flag3 = true;
    break;
  }
}
$repeat = array_count_values($row4);
for(reset($repeat);$key=key($repeat);next($repeat))
{
  if($repeat[$key]>1)
  {
    $repeat_flag4 = true;
    break;
  }
}
$repeat = array_count_values($row5);
for(reset($repeat);$key=key($repeat);next($repeat))
{
  if($repeat[$key]>1)
  {
    $repeat_flag5 = true;
    break;
  }
}
if( $repeat_flag1 || $repeat_flag2 || $repeat_flag3 || $repeat_flag4 || $repeat_flag5 )
{
	$tpl->define ( array ( make_color => "./make_color1.tpl") );
	$tpl->assign ( MESSAGE, "每個群組中數字不可出現重複，請檢查並依規則填入1~4的數字" );
	$tpl->parse( BODY, "make_color" );
	$tpl->FastPrint("BODY");
	exit;
}


$sum1 = 0;
$sum2 = 0;
$sum3 = 0;
$sum4 = 0;
$sum5 = 0;
for( $i=1;$i<=4;$i++ )
{
	$sum1 += $row1[$i];
	$sum2 += $row2[$i];
	$sum3 += $row3[$i];
	$sum4 += $row4[$i];
	$sum5 += $row5[$i];
}
if( $sum1!=10 || $sum2!=10 || $sum3!=10 || $sum4!=10 || $sum5!=10 )
{
	$tpl->define ( array ( make_color => "./make_color1.tpl") );
	$tpl->assign ( MESSAGE, "數字可能填錯，請檢查並依規則填入1~4的數字" );
	$tpl->parse( BODY, "make_color" );
	$tpl->FastPrint("BODY");
	exit;
}


$outcome[1] = $row1[1] + $row2[1] + $row3[1] + $row4[1] + $row5[1];
$outcome[2] = $row1[2] + $row2[2] + $row3[2] + $row4[2] + $row5[2];
$outcome[3] = $row1[3] + $row2[3] + $row3[3] + $row4[3] + $row5[3];
$outcome[4] = $row1[4] + $row2[4] + $row3[4] + $row4[4] + $row5[4];
arsort($outcome);
$key = key($outcome);


if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) )
{
	$error = "資料庫連結錯誤!!";
	show_page ( "not_access.tpl", $error );
	exit;
}
$Q1 = "Update user Set color='$key' where id='$user_id'";
mysql_query($Q1);

if($key == 1)
	$mycolor = "<font color=orange>橘色</font>";
else if($key == 2)
	$mycolor = "<font color=gold>金色</font>";
else if($key == 3)
	$mycolor = "<font color=blue>藍色</font>";
else if($key == 4)
	$mycolor = "<font color=green>綠色</font>";
$ip = getenv("SERVER_NAME");
if ( $ip == "" )
	$ip = $SERVER_NAME;

$path = "../../studentPage/". $user_id .".html";
if ( is_file ( $path ) ) {
	$fp=fopen( $path, "r");
	$tmp = fread($fp, filesize($path) );				
	fclose($fp);
	list($pre, $after) = explode("<!--begin-->", $tmp);
	$fp=fopen( $path, "w");
	if ( ($buf = stristr($after,"<!--end-->") )) {
		fwrite ($fp, $pre );
		fwrite ($fp, "<!--begin-->".$mycolor );
		fwrite ($fp, $buf );
	}
	else {
		fwrite ($fp, $tmp );
	}
	fclose($fp);
}
$tpl->define ( array ( make_color => "./make_color1.tpl") );
$tpl->assign ( MESSAGE, "你的專屬顏色是 --- $mycolor" );
$tpl->parse( BODY, "make_color" );
$tpl->FastPrint("BODY");

?>