<?php
	/*
	@ Author: carlyle
	@ Description: ��mrtg�Y���y�q�έp���
	*/

	require 'common.php';

	global $DB_SERVER,$DB_LOGIN,$DB,$DB_PASSWORD;

        $Q = "SELECT COUNT(a_id) AS SUM FROM online";
        if (!($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)))
		die("��Ʈw�s�����~!!");

        if (!$result = mysql_db_query($DB,$Q)) 
		die("��Ʈw�s�����~!!");
	else
		$row = mysql_fetch_array($result);

	echo $row[0] . "\n";
	echo "0\n";
	echo shell_exec("/usr/bin/uptime | awk '{print $3 \" \" $4 \" \" $5}'");	
	echo "ecourse.elearning.ccu.edu.tw";	
?>
