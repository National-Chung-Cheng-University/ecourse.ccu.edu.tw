<?php
	/*
	@ author: carlyle
	*/

	define ("LOGFILE_1","/datacenter/htdocs/logs/course.log"); //��s�}�Ҫ�logfile
	define ("LOGFILE_2","/datacenter/htdocs/logs/takcih.log"); //��s��Ҫ�logfile
	define ("LOGFILE_3","/datacenter/htdocs/logs/temptakcih.log"); //��s�Ȯɩʿ�Ҫ�logfile
	define ("LOGFILE_4","/datacenter/htdocs/logs/update_student.log"); //��s�ǥͪ�logfile

	/* ��s�}��, ��s���, ��s�Ȯɩʿ�Ҫ�log function */
	function updateLog($message,$filesel) {
		$ip = $_SERVER['REMOTE_ADDR'];	
		$datetime = date("Y/m/d H:i:s"); 	
		$logstr = $ip . " - [" . $datetime . "] - \"" . $message . "\"\n";

		if ($filesel == 1)
			$filename = LOGFILE_1;
		else if ($filesel == 2)
			$filename = LOGFILE_2;
		else if ($filesel == 3)
			$filename = LOGFILE_3;
		else if ($filesel == 4)
			$filename = LOGFILE_4;
		else
			return 0;

		$fp = fopen($filename,"a");
		if ($fp == FALSE) return 0;
		fwrite($fp,$logstr);
		fclose($fp);

		return 1;
	}
?>
