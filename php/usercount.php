<?
	require 'common.php';
	mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("資料庫連結錯誤");

	$Q1 = "SELECT * FROM online";
	$result = mysql_db_query($DB,$Q1) or die("查詢時發生錯誤");
	$count = mysql_num_rows($result);
	if ( $count == "" )
		$count = 0;	
	echo $count;
?>