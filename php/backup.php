<?php
	require 'fadmin.php';
	if ( isset($PHPSESSID) && session_check_admin($PHPSESSID) ) {
		if ( isset( $hh1 ) && isset( $hh2 ) && isset ( $mm1 ) && isset( $mm2) )
			$message = add_backup();
		show_page_d ( $message );
	}
	else
		show_page( "index_ad.tpl", "你的權限錯誤，請重新登入!!" );
	
	function add_backup () {
		
		global $hh1, $hh2, $mm1, $mm2;
		add_log ( 9, "", $hh1, $mm1, $hh2, $mm2 );
		
		if( ($fp=fopen( "/home/study/sh/cron", "w")) == NULL) {
			$message = "$message - 檔案cron寫入錯誤!!";
		}else {
			fwrite($fp,"MAILTO=\"\"\n");
			fwrite($fp,"$mm1 $hh1 * * 0 (/home/study/sh/autobackup2)\n");
			fwrite($fp,"$mm2 $hh2 * * 0 (/home/study/sh/autoupload)\n");
			fwrite($fp,"0 0 * * * (/home/study/sh/automail)\n");
			fclose($fp);
		}
		system("crontab /home/study/sh/cron");

		return $message;
	}
	
	function show_page_d ( $message="" ) {
		$hh1 = 0;
		$hh2 = 0;
		$mm1 = 0;
		$mm2 = 0;
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
		$Q1 = "select tag1, tag2, tag3, tag4 FROM log where event_id = '9'";
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$message = "$message - 資料庫連結錯誤!!";
		}
		if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
			$message = "$message - 資料庫讀取錯誤!!";
		}
		else if ( mysql_num_rows ( $result ) != 0 ) {
			$row = mysql_fetch_array( $result );
			$hh1 = $row['tag1'];
			$hh2 = $row['tag3'];
			$mm1 = $row['tag2'];
			$mm2 = $row['tag4'];
		}

		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		$tpl->define ( array ( body => "backup.tpl" ) );
		$tpl->define_dynamic ( "backup_hh" , "body" );
		$tpl->define_dynamic ( "backup_mm" , "body" );
		$tpl->define_dynamic ( "backup_h2" , "body" );
		$tpl->define_dynamic ( "backup_m2" , "body" );

		$tpl->assign( HH1, "$hh1" );
		$tpl->assign( MM1, $mm1 );
		$tpl->assign( HH2, $hh2 );
		$tpl->assign( MM2, $mm2 );
		
		for ( $i = 0; $i <=23 ; $i ++ ) {
			if ( $i == $hh1 )
				$tpl->assign( HBV, $i." selected" );
			else
				$tpl->assign( HBV, $i );
			$tpl->assign( HBD, $i );
			$tpl->parse ( BACKUP_H1, ".backup_hh" );
			
		}
		for ( $i = 0; $i <=59 ; $i ++ ) {
			if ( $i == $mm1 )
				$tpl->assign( MBV, $i." selected" );
			else
				$tpl->assign( MBV, $i );;
			$tpl->assign( MBD, $i );
			$tpl->parse ( BACKUP_M1, ".backup_mm" );
		}
		for ( $i = 0; $i <=23 ; $i ++ ) {
			if ( $i == $hh2 )
				$tpl->assign( HUV, $i." selected" );
			else
				$tpl->assign( HUV, $i );
			$tpl->assign( HUD, $i );
			$tpl->parse ( BACKUP_H2, ".backup_h2" );
		}
		for ( $i = 0; $i <=59 ; $i ++ ) {
			if ( $i == $mm2 )
				$tpl->assign( MUV, $i." selected" );
			else
				$tpl->assign( MUV, $i );
			$tpl->assign( MUD, $i );
			$tpl->parse ( BACKUP_M2, ".backup_m2" );
		}
		$tpl->assign( MES , $message );

		$tpl->parse( BODY, "body" );
		$tpl->FastPrint("BODY");
	}
?>
