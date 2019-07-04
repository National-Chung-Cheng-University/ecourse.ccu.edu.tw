<?php
	require 'fadmin.php';

	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	$ftp_server = "140.123.230.52";
	$ftp_user_name = "backup";
	$ftp_user_pw = "backup!";
	$directory = "server4";

	$Q1 = "select * FROM course c order by a_id";
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		echo ("資料庫連結錯誤!!" );
	}
	else if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
			echo ("資料庫讀取錯誤!!" );
	}
	else if ( ($num = mysql_num_rows( mysql_db_query( $DB, $Q1 ) ) )!= 0 ) {

		$tarnum = ceil($num/5);

		$conn_id = ftp_connect($ftp_server);
		$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pw); 

		if ((!$conn_id) || (!$login_result)) { 
			echo "FTP connection has failed!";
			echo "Attempted to connect to $ftp_server for user $ftp_user_name"; 
			die; 
		} 
		else {
			echo "Connected to $ftp_server, for user $ftp_user_name";
		}

		ftp_chdir($conn_id, $directory);
		ftp_mkdir($conn_id, date( "Ymd" ) );
		
		for( $i=0; $i<$tarnum; $i++ ) {

			$source_file = "/backup/htdocs-".date( "Ymd" )."-".$i.".tar.gz";
			$destination_file = "htdocs-".date( "Ymd" )."-".$i.".tar.gz";

			$upload = ftp_put($conn_id, $destination_file, $source_file, FTP_BINARY);
			if (!$upload) { 
		        echo "FTP upload has failed!";
		    } 
			else {
				unlink($source_file);
				echo "Uploaded $source_file to $ftp_server as $destination_file";
		    }
		}

		ftp_close($conn_id); 
	}
?>