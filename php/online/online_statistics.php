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

	echo $row[0] . "\n";
	echo "0\n";
	echo shell_exec("/usr/bin/uptime | awk '{print $3 \" \" $4 \" \" $5}'");	
	echo "ecourse.elearning.ccu.edu.tw";	
?>
