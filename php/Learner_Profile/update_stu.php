<?php
	require 'fadmin.php';
	if ( isset($PHPSESSID) && session_check_admin($PHPSESSID) ) {
		echo "<body background = \"/images/img/bg.gif\"><center>";
		if(($error = update_user()) == -1)
			echo "學生資料更新完畢!!<br>";
		else{
			echo "$error<br>";
		}
		if(($error = update_user_gra()) == -1)
			echo "專班學生資料更新完畢!!<br>";
		else{
			echo "$error<br>";
		}
		
		echo "<br><a href=../check_admin.php>回系統管理介面</a></center></body>";
	}
	else
		show_page( "index_ad.tpl", "你的權限錯誤，請重新登入!!");
	
	// 更新學生名單
	function update_user() {
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $id, $pass;
	    
		if( !($cnx = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) ){  
			Error_handler( "在 sybase_connect 有錯誤發生" , $cnx );  
		}
		$csd = @sybase_select_db("academic", $cnx);
		//在學生資料(含休學)
		$cur = sybase_query("select * from a11vstd_rec_tea", $cnx);
		if(!$cur) {  
			Error_handler( "在 sybase_exec 有錯誤發生( 沒有指標傳回 ) " , $cnx );  
		}
		//畢業及退學
		$cur2 = sybase_query("select * from a11vleave_rec_tea", $cnx);
		if(!$cur2) {  
			Error_handler( "在 sybase_exec 有錯誤發生( 沒有指標傳回 ) " , $cnx );  
		} 

		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "mysql資料庫連結錯誤!!";
			echo "$error";
		}
		
		while($result=sybase_fetch_array($cur))
		{
			$Q2 = "select id,name,disable from user where id = '$result[id]'";
			if ($result2 = mysql_db_query($DB,$Q2)){			
				if($result[status]=="在學"){
					$row = mysql_fetch_array($result2);
					// 新生
					if(!$row){
						$stu_name = addslashes($result[name]);
						$Q3 = "insert into user (id, pass, ftppass, authorization, name, sex, tel, zip, addr, email) values (\"$result[id]\", \"" . passwd_encrypt($result[ps]) . "\", \"" . md5($result[ps]) . "\", \"3\", \"$stu_name\", \"$result[sex]\", \"$result[tel] \", \"$result[zip]\", \"$result[addr]\", \"$result[email]\")";
						if ( !($result3 = mysql_db_query($DB,$Q3)) ){
							$error = "mysql資料庫寫入錯誤!!";
							echo "$error".": $Q3".": $result3<br>";
						}
					}
					// 休學生復學,啟用使用權限
					else if($row && $row[disable]=='1'){
						$Q3 = "update user set disable='0' where id='$result[id]'";
						if ( !($result3 = mysql_db_query($DB,$Q3)) ){
							$error = "mysql資料庫寫入錯誤!!";
							echo "$error".": $Q3".": $result3<br>";
						}
					}
					// 學生已更名時，更改名字
					else if($row && ( strcmp($row[name],$result[name]) !=0 )){
						$Q3 = "update user set name='$result[name]' where id='$result[id]'";
						if ( !($result3 = mysql_db_query($DB,$Q3)) ){
							$error = "mysql資料庫寫入錯誤!!";
							echo "$error".": $Q3".": $result3<br>";
						}
						echo $result[id].$row[name]." 更名為 ".$result[name]."<br>";
					}
				}
				
				else if($result[status]=="休學"){
					$row = mysql_fetch_array($result2);
					// 休學生(第一次更新資料時才會用到)
					if(!$row){
						$stu_name = addslashes($result[name]);
						$Q3 = "insert into user (id, pass, ftppass, authorization, disable, name, sex, tel, zip, addr, email) values (\"$result[id]\", \"" . passwd_encrypt($result[ps]) . "\", \"" . md5($result[ps]) . "\", \"3\", \"1\", \"$stu_name\", \"$result[sex]\", \"$result[tel] \", \"$result[zip]\", \"$result[addr]\", \"$result[email]\")";
						if ( !($result3 = mysql_db_query($DB,$Q3)) ){
							$error = "mysql資料庫寫入錯誤!!";
							echo "$error".": $Q3".": $result3<br>";
						}
					}
					// 在學生休學,暫停使用權限
					else if($row && $row[disable]=='0'){
						$Q3 = "update user set disable='1' where id='$result[id]'";
						if ( !($result3 = mysql_db_query($DB,$Q3)) ){
							$error = "mysql資料庫寫入錯誤!!";
							echo "$error".": $Q3".": $result3<br>";
						}
					}
				}
			}
			else{
				$error = "mysql資料庫讀取錯誤!!";
				echo "$error";
			}
		}
		
		while($result=sybase_fetch_array($cur2)){
			$Q2 = "select id from user where id = '$result[id]'";
			if ($result2 = mysql_db_query($DB,$Q2)){
				$row = mysql_fetch_row($result2);
				// 畢業生或退學生則取消資料
				if($row){
					$Q3 = "delete from user where id='$result[id]'";
					echo "一般學生資料已清除$result[id]<br>";
					if ( !($result3 = mysql_db_query($DB,$Q3)) ){
						$error = "mysql資料庫寫入錯誤!!";
						echo "$error".": $Q3".": $result3<br>";
					}
				}
			}
			else{
				$error = "mysql資料庫讀取錯誤!!";
				echo "$error";
			}
		}
		
		sybase_close( $cnx);
		return -1;
	}

	// 更新專班學生名單
	function update_user_gra() {
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $id, $pass;
	    
		if( !($cnx = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) ){  
			Error_handler( "在 sybase_connect 有錯誤發生" , $cnx );  
		}
		$csd = @sybase_select_db("academic_gra", $cnx);
		// 在學生資料(含休學)
		$cur = sybase_query("select * from a11vstd_rec_tea", $cnx);
		if(!$cur) {  
			Error_handler( "在 sybase_exec 有錯誤發生( 沒有指標傳回 ) " , $cnx );  
		}
		// 畢業及退學
		$cur2 = sybase_query("select * from a11vleave_rec_tea", $cnx);
		if(!$cur2) {  
			Error_handler( "在 sybase_exec 有錯誤發生( 沒有指標傳回 ) " , $cnx );  
		} 

		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "mysql資料庫連結錯誤!!";
			echo "$error";
		}
		
		while($result=sybase_fetch_array($cur))
		{
			$Q2 = "select id,name,disable from user where id = '$result[id]'";
			if ($result2 = mysql_db_query($DB,$Q2)){			
				if($result[status]=="在學"){
					$row = mysql_fetch_array($result2);
					// 新生
					if(!$row){
						$stu_name = addslashes($result[name]);
						$Q3 = "insert into user (id, pass, ftppass, authorization, name, sex, tel, zip, addr, email) values (\"$result[id]\", \"" . passwd_encrypt($result[ps]) . "\", \"" . md5($result[ps]) . "\", \"3\", \"$stu_name\", \"$result[sex]\", \"$result[tel] \", \"$result[zip]\", \"$result[addr]\", \"$result[email]\")";
						if ( !($result3 = mysql_db_query($DB,$Q3)) ){
							$error = "mysql資料庫寫入錯誤!!";
							echo "$error".": $Q3".": $result3<br>";
						}
					}
					// 休學生復學,啟用使用權限
					else if($row && $row[disable]=='1'){
						$Q3 = "update user set disable='0' where id='$result[id]'";
						if ( !($result3 = mysql_db_query($DB,$Q3)) ){
							$error = "mysql資料庫寫入錯誤!!";
							echo "$error".": $Q3".": $result3<br>";
						}
					}
					// 學生已更名時，更改名字
					else if($row && ( strcmp($row[name],$result[name]) !=0 )){
						$Q3 = "update user set name='$result[name]' where id='$result[id]'";
						if ( !($result3 = mysql_db_query($DB,$Q3)) ){
							$error = "mysql資料庫寫入錯誤!!";
							echo "$error".": $Q3".": $result3<br>";
						}
						//echo "$row[name] , $result[name]<br>";
						echo $result[id].$row[name]." 更名為 ".$result[name]."<br>";
					}
				}
				
				else if($result[status]=="休學"){
					$row = mysql_fetch_array($result2);
					// 休學生(第一次更新資料時才會用到)
					if(!$row){
						$stu_name = addslashes($result[name]);
						$Q3 = "insert into user (id, pass, ftppass, authorization, disable, name, sex, tel, zip, addr, email) values (\"$result[id]\", \"" . passwd_encrypt($result[ps]) . "\", \"" . md5($result[ps]) . "\", \"3\", \"1\", \"$stu_name\", \"$result[sex]\", \"$result[tel] \", \"$result[zip]\", \"$result[addr]\", \"$result[email]\")";
						if ( !($result3 = mysql_db_query($DB,$Q3)) ){
							$error = "mysql資料庫寫入錯誤!!";
							echo "$error".": $Q3".": $result3<br>";
						}
					}
					// 在學生休學,暫停使用權限
					else if($row && $row[disable]=='0'){
						$Q3 = "update user set disable='1' where id='$result[id]'";
						if ( !($result3 = mysql_db_query($DB,$Q3)) ){
							$error = "mysql資料庫寫入錯誤!!";
							echo "$error".": $Q3".": $result3<br>";
						}
					}
				}
			}
			else{
				$error = "mysql資料庫讀取錯誤!!";
				echo "$error";
			}
		}
		
		while($result=sybase_fetch_array($cur2)){
			$Q2 = "select id from user where id = '$result[id]'";
			if ($result2 = mysql_db_query($DB,$Q2)){
				$row = mysql_fetch_row($result2);
				// 畢業生或退學則刪除資料
				if($row){
					$Q3 = "delete from user where id='$result[id]'";
					echo "專班學生資料已清除$result[id]<br>";
					if ( !($result3 = mysql_db_query($DB,$Q3)) ){
						$error = "mysql資料庫寫入錯誤!!";
						echo "$error".": $Q3".": $result3<br>";
					}
				}
			}
			else{
				$error = "mysql資料庫讀取錯誤!!";
				echo "$error";
			}
		}
		
		sybase_close( $cnx);
		return -1;
	}
	
	function Error_Handler( $msg, $cnx ) {  
		echo "$msg \n";
		sybase_close( $cnx); exit();  
	}	
?>
