<?php
require 'fadmin.php';
global $DB_SERVER, $DB_LOGIN, $DB_PASSWORD;

if (!(isset($PHPSESSID) && session_check_admin($PHPSESSID)) )
{
	show_page( "not_access.tpl" ,"權限錯誤");
}

if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
	echo ( "資料庫連結錯誤!!" );
	return;
}
?>
<html>
<head>
<title>更改學年學期</title>
</head>
<body>
<center>
<H1>請每學期更動一次當下學年及學期</H1>
<a href=../check_admin.php>回系統管理介面</a>
<p>目前學年及學期為：</p>
<?php
/****************************/
/*目的：更改學年、學期的值*/
/*2006-02-08*/
/****************************/
global $DB;

if($action == "test")
{
	$Q0 = "update this_semester set year=".$new1.", term=".$new2;
	$result= mysql_db_query($DB, $Q0);

	echo "<font color=red>更新成功</font><br>";
}

$Q1 = "select * from this_semester";
$result1 = mysql_db_query($DB, $Q1);
$row1 = mysql_fetch_array($result1);
echo "第 <font color=red>".$row1['year']."</font> 學年度第 <font color=red>".$row1['term']."</font> 學期<br>";

echo "<p>欲更改為</p>
<form method=post action=./set_semester.php>
學年：
<input type=text size=5 name=new1 value=".$row1['year'].">
學期：
<input type=text size=5 name=new2 value=".$row1['term'].">
<br><br>
<input type=hidden name=action value=test>
<input type=submit value=確定送出>
</form>";

?>
</center>
</body>
</html>