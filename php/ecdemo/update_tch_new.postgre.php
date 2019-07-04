<?php
/**
 *教師包括人事資料中的正式教師，以及如客座教授、遠距教學等之〝待聘狀態〞之教師。
 *本程式處理從人事資料來的正式教師資料。
 */
	require 'fadmin.php';
	if ( isset($PHPSESSID) && session_check_admin($PHPSESSID) ) {
		echo "<body background = \"/images/img/bg.gif\"><center>";
		if(($error = update_user("academic")) == -1)
			echo "教師資料更新完畢!!<br>";
		else{
			echo "$error<br>";
		}
		if(($error = update_user("academic_gra")) == -1)
			echo "專班教師資料更新完畢!!<br>";
		else{
			echo "$error<br>";
		}
		echo "<br><a href=../check_admin.php>回系統管理介面</a></center></body>";
	}
	else
		show_page( "index_ad.tpl", "你的權限錯誤，請重新登入!!");
	
	// 更新一般教師
	function update_user($db_name) {
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $id, $pass;
	    
		/*if( !($cnx = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) ){  
			Error_handler( "在 sybase_connect 有錯誤發生" , $cnx );  
		}
		$csd = @sybase_select_db($db_name, $cnx);
		
		$cur = sybase_query("select * from h0bvbasic_e_tea", $cnx);
		if(!$cur) {  
			Error_handler( "在 sybase_exec 有錯誤發生( 沒有指標傳回 ) " , $cnx );  
		}
		*/
		$conn_string = "host=140.123.30.12 dbname=".$db_name." user=acauser password=!!acauser13";
		$cnx = pg_pconnect($conn_string) or die('資料庫沒有回應，請稍後再試');
		
		$cur = pg_query($cnx, "select * from h0bvbasic_e_tea") or die('資料表不存在，請通知電算中心');		

		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "mysql資料庫連結錯誤!!";
			echo "$error";
		}
		
		//while($result=sybase_fetch_array($cur)){
		while($result=pg_fetch_array($cur, null, PGSQL_ASSOC)){
			$Q2 = "select id from user where id = '$result[id]'";
			//echo $Q2;
			if ($result2 = mysql_db_query($DB,$Q2)){			
				$row = mysql_fetch_array($result2);
				// 新教師
				if(!$row){
					//$tch_name = addslashes($result[name]);
					$tch_name = addslashes(mb_convert_encoding($result[name], "big5", "utf-8"));
					//$pswd = mkPasswd();
					$pswd = "";
					//2006.12 正式教師validated='1'
					$Q3 = "insert into user (id, pass, ftppass, authorization, name, validated) values ('$result[id]', '$pswd', '', '1', '$tch_name', '1')";
					if ( !($result3 = mysql_db_query($DB,$Q3)) ){
						$error = "mysql資料庫寫入錯誤!!";
						echo "$error".": $Q3".": $result3<br>";
					}
				}
				//更新教師姓名
				else
				{
					//$tch_name = addslashes($result[name]);
					$tch_name = addslashes(mb_convert_encoding($result[name], "big5", "utf-8"));
					//2006.12 如原來validated=0,則update為'1'
					$Q3 = "update user set name = '$tch_name',validated='1' where id = '$result[id]'";
					if ( !($result3 = mysql_db_query($DB,$Q3)) ){
						$error = "mysql資料庫更新錯誤!!";
						echo "$error".": $Q3".": $result3<br>";
					}
				}
			}
			else{
				$error = "mysql資料庫讀取錯誤!!";
				echo "$error";
			}
		}
		
		//sybase_close( $cnx);
		pg_close( $cnx);
		return -1;
	}

	// 亂數產生6位數密碼
	function mkPasswd( ) { 
		$consts='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'; 

		for ($x=0; $x < 6; $x++) { 
			mt_srand ((double) microtime() * 1000000); 
			$const[$x] = substr($consts,mt_rand(0,strlen($consts)-1),1);
		} 
		
		return $const[0].$const[1].$const[2].$const[3].$const[4].$const[5]; 
	}

	
	function Error_Handler( $msg, $cnx ) {  
		echo "$msg \n";
		//sybase_close( $cnx); exit(); 
		pg_close( $cnx); exit();   
	}	
?>
