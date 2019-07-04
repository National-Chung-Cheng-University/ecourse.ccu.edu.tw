<?PHP
	require 'fadmin.php';

	if ( isset($PHPSESSID) && session_check_admin($PHPSESSID) ) {
		if(($error = clear_teach_course()) != -1){
			echo "$error (清除開課資料)<br>";
		}else{
			echo "<center>開課資料清除完畢!!</center><br>";
		}
		echo "<br><center><a href=../check_admin.php>回系統管理介面</a></center>";

	}
	else
		show_page( "index_ad.tpl", "你的權限錯誤，請重新登入!!");


	// 清除授課資料
	function clear_teach_course(){
		global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;
		// 連結sybase
		if( !($cnx = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) ){  
			Error_handler( "在 sybase_connect 有錯誤發生" , $cnx );  
		}
		$csd = @sybase_select_db("academic", $cnx);
		$cur = sybase_query("select year,term from a31vcurriculum_tea", $cnx);
		if(!$cur) {  
			Error_handler( "在 sybase_exec 有錯誤發生( 沒有指標傳回 ) " , $cnx );  
		}
		$array=sybase_fetch_array($cur);
		$year = $array['year'];
		$term = $array['term'];
		
		// 連結mysql
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "資料庫連結錯誤!!";
			return $error;
		}
		
		$Qc = "delete from teach_course where year!=$year or term!=$term";
		if ( !($resultc = mysql_db_query( $DB, $Qc ) ) ) {
			$error = "資料庫讀取錯誤!!";
			return $error;
		}
		sybase_close( $cnx);
		return -1;
	}

	function Error_Handler( $msg, $cnx ) {  
		echo "$msg \n";
		sybase_close( $cnx); exit();  
	}
	
?>
