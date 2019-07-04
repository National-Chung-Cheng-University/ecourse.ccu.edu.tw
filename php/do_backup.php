<?php
	require 'fadmin.php';
	
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	$Q1 = "select * FROM course c order by a_id";
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		echo ("資料庫連結錯誤!!" );
	}
	else if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
			echo ("資料庫讀取錯誤!!" );
	}
	else if ( mysql_num_rows( mysql_db_query( $DB, $Q1 ) ) != 0 ) {
		chdir ( "/datacenter/htdocs/" );
		$sys = "";
		$i = 0;
		$j = 0;
		$sys2 = "";
		while ( $row = mysql_fetch_array( $result ) ) {
			if ( $sys2 == "" ) {
				$sys .= "tar -zcvf /backup/htdocs-".date( "Ymd" )."-".$j.".tar.gz ";
			}
			$sys2 .= $row['a_id']." ";
			$i ++;

			if ( $i%2 == 0 ) {
				$j ++;
				$sys .= $sys2.";";
				$sys2 = "";
			}
		}
		if ( $i % 2 != 0 ) {
			$sys .= $sys2;
		}
//		echo ( $sys );
		system( $sys );
	}
?>
