<?
	require 'common.php';
	mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("��Ʈw�s�����~");

	$Q1 = "SELECT * FROM online";
	$result = mysql_db_query($DB,$Q1) or die("�d�߮ɵo�Ϳ��~");
	$count = mysql_num_rows($result);
	if ( $count == "" )
		$count = 0;	
	echo $count;
?>