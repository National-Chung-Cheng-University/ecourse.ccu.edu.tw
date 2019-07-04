<?php
	/*
	@ author: carlyle
	*/

	define ("LOGFILE_1","/datacenter/htdocs/logs/course.log"); //更新開課的logfile
	define ("LOGFILE_2","/datacenter/htdocs/logs/takcih.log"); //更新選課的logfile
	define ("LOGFILE_3","/datacenter/htdocs/logs/temptakcih.log"); //更新暫時性選課的logfile
	define ("LOGFILE_4","/datacenter/htdocs/logs/update_student.log"); //更新學生的logfile

	/* 更新開課, 更新選課, 更新暫時性選課的log function */
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
