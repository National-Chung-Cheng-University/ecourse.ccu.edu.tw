<?php
require 'fadmin.php';
global $DB_SERVER, $DB_LOGIN, $DB_PASSWORD;

if (!(isset($PHPSESSID) && session_check_admin($PHPSESSID)) )
{
	show_page( "not_access.tpl" ,"�v�����~");
}

if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
	echo ( "��Ʈw�s�����~!!" );
	return;
}
?>
<html>
<head>
<title>���Ǧ~�Ǵ�</title>
</head>
<body>
<center>
<H1>�ШC�Ǵ���ʤ@����U�Ǧ~�ξǴ�</H1>
<a href=../check_admin.php>�^�t�κ޲z����</a>
<p>�ثe�Ǧ~�ξǴ����G</p>
<?php
/****************************/
/*�ت��G���Ǧ~�B�Ǵ�����*/
/*2006-02-08*/
/****************************/
global $DB;

if($action == "test")
{
	$Q0 = "update this_semester set year=".$new1.", term=".$new2;
	$result= mysql_db_query($DB, $Q0);

	echo "<font color=red>��s���\</font><br>";
}

$Q1 = "select * from this_semester";
$result1 = mysql_db_query($DB, $Q1);
$row1 = mysql_fetch_array($result1);
echo "�� <font color=red>".$row1['year']."</font> �Ǧ~�ײ� <font color=red>".$row1['term']."</font> �Ǵ�<br>";

echo "<p>����אּ</p>
<form method=post action=./set_semester.php>
�Ǧ~�G
<input type=text size=5 name=new1 value=".$row1['year'].">
�Ǵ��G
<input type=text size=5 name=new2 value=".$row1['term'].">
<br><br>
<input type=hidden name=action value=test>
<input type=submit value=�T�w�e�X>
</form>";

?>
</center>
</body>
</html>