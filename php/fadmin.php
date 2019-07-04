<?php
	require_once 'common.php';
	
	if( isset($PHPSESSID) || session_is_registered($PHPSESSID) ) {
		session_id($PHPSESSID);
		session_start();
	}

	$ip = getenv ( "REMOTE_ADDR" );
	if ( $ip == "" )
		$ip = $HTTP_X_FORWARDED_FOR;
	if ( $ip == "" )
	$ip = $REMOTE_ADDR;

	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	$Q1 = "select a_id from online where user_id = '$user_id' and host = '$ip'";

	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		echo( "資料庫連結錯誤!!" );
		exit;
	}
	if ( $result1 = mysql_db_query( $DB, $Q1 ) ) {
		if ( mysql_num_rows( $result1 ) != 0 ) {
			$row_online = mysql_fetch_array( $result1 );
			$Q2 = "update online set time='".date("U")."', idle='".date("U")."' where a_id = '".$row_online['a_id']."'";	
			mysql_db_query( $DB, $Q2 );
		}
	}

	function qsort_multiarray($array,$num = 0, $order = 0, $left = 0,$right = -1)
	{
		if($right == -1)
		{
			$right = count($array) - 1;
		}
		$links = $left;
		$rechts = $right;
		$mitte = $array[($left + $right) / 2][$num];
		$mid = ($left + $right) / 2;
		if($rechts > $links)
		{
			do
			{
				if ( $order == 1 ) {
					while($array[$links][$num] > $mitte ) $links ++;
					while($array[$rechts][$num] < $mitte ) $rechts --;
				}
				else {
					while($array[$links][$num] < $mitte ) $links++;
					while($array[$rechts][$num] > $mitte ) $rechts--;
				}
					
				if($links <= $rechts)
				{
					$tmp = $array[$links];
					$array[$links] = $array[$rechts];
					$array[$rechts] = $tmp;
					if ( $links < $right )
						$links ++;
					if ( $rechts > $left )
						$rechts --;
				}
			} while($links <= $rechts);
			$array = qsort_multiarray($array,$num,$order,$left, $rechts);
			$array = qsort_multiarray($array,$num,$order,$links,$right);
		}
		return $array;
	}
	
	function update_status ( $status_now ) {

		$status_now = addslashes( $status_now );

		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $user_id, $ip;
		global $texttime, $prevchapter, $prevsection, $course_id;

		if( session_is_registered("texttime") && session_is_registered("prevchapter") && session_is_registered("prevsection") ) {

			$period = date("U") - $texttime;

			add_log(11, $user_id, $prevchapter, $course_id, $period, $prevsection);

			session_unregister( "texttime" );
			session_unregister( "prevchapter" );
			session_unregister( "prevsection" );
		}

		$Q1 = "select a_id from online where user_id = '$user_id' and host = '$ip'";

		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			echo( "資料庫連結錯誤!!" );
			exit;
		}
		if ( $result1 = mysql_db_query( $DB, $Q1 ) ) {
			if ( mysql_num_rows( $result1 ) != 0 ) {
				$row_online = mysql_fetch_array( $result1 );
				$Q2 = "update online set status='$status_now' where a_id = '".$row_online['a_id']."'";
				mysql_db_query( $DB, $Q2 );
			}
		}
	}

	function deldir( $path ) { 
		if ( substr( $path,-1 ) <> "/" )
			$path = $path."/";
		$all = opendir( $path );
		while ( $file = readdir ($all) ) {
			if ( is_dir( $path.$file ) && $file <> ".." && $file <> "." ) {
				deldir( $path.$file );
				//rmdir( $path.$file );
				//print "Removed directory ($path$file)\n"; 
				unset( $file );
			} elseif ( !is_dir( $path.$file ) ) {
				unlink ( $path.$file );
				//print "Removed file ($path$file)\n";
				unset($file);
			}
		}
		rmdir( $path );
	}
?>
