<?php
	/*
	@ Author: carlyle
	@ Description: 給mrtg吃的流量統計資料
	*/

	require 'common.php';

	global $DB_SERVER,$DB_LOGIN,$DB,$DB_PASSWORD;
	
        $Q = "SELECT COUNT(a_id) AS SUM FROM online";
        if (!($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)))
		die("資料庫連結錯誤!!");

        if (!$result = mysql_db_query($DB,$Q)) 
		die("資料庫存取錯誤!!");
	else
		$row = mysql_fetch_array($result);
	
	$time_cur = time();
	
	echo $time_cur." ".$row[0] . "\n";
?>
