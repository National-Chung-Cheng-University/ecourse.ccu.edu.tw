<?php
	require 'fadmin.php';
	if ( isset($PHPSESSID) && session_check_admin($PHPSESSID) ) {
		if ( $msg != "" ) {
			add_msg ();
			$meg = "�[�J���\!!";
		}

		if ( $mantain == 1 ) {
			$meg .= mantain ();
		}
		else if ( $delman == 1 ) {
			delman ();
			$meg = "$meg �t�����}���@���A!!";
		}
		
		show_page( "hotline.tpl", $meg );
	}
	else
		show_page( "index_ad.tpl", "�A���v�����~�A�Э��s�n�J!!");
		
	function mantain () {
		//chmod ( "../Chinese", 0000 );
		//chmod ( "../English", 0000 );
		chmod ( "../php", 0711 );
		copy ( "../index.bak", "../index.html" );
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			echo( "��Ʈw�s�����~!!" );
			exit;
		}
		$Q1 = "select * from log where event_id ='20'";
		$result1 = mysql_db_query( $DB, $Q1 );
		if ( mysql_num_rows( $result1 ) == 0 ) {
			$Q2 = "insert into log ( event_id , tag1 ) values ( '20', '1' )";
			$result2 = mysql_db_query( $DB, $Q2 );
			return "$meg �t�ζi�J���@���A!!";
		}
		else {
			return "�t�Τw�b���@���A!!";
		}
	}
	
	function delman () {
		//chmod ( "../Chinese", 0711 );
		//chmod ( "../English", 0711 );
		chmod ( "../php" , 0111 );
		if ( is_file("../index.html") )
			unlink ( "../index.html" );
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			echo( "��Ʈw�s�����~!!" );
			exit;
		}
		$Q1 = "delete from log where event_id ='20'";
		$result1 = mysql_db_query( $DB, $Q1 );
	}
	
	function add_msg ( ) {
		global $msg, $close;

		if ( $msg != "" ) {
			global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
			if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
				echo( "��Ʈw�s�����~!!" );
				exit;
			}
			$Q1 = "select a_id from user where id = 'admin'";
			if ( $result1 = mysql_db_query( $DB, $Q1 ) ) {
				$row1 = mysql_fetch_array( $result1 );
			}
			$Q2 = "select u.a_id from online o, user u where u.id = o.user_id";
			if ( $result2 = mysql_db_query( $DB, $Q2 ) ) {
				while ( $row2 = mysql_fetch_array( $result2 ) ) {
					$Q3 = "insert into message ( send, receive, close, message, time ) values ( '".$row1['a_id']."', '".$row2['a_id']."', '$close', '$msg', '".date("Y/m/d H:i:s",time())."')";
					mysql_db_query( $DB, $Q3 );
				}
			}
		}
	}
?>