<?php
	require 'fadmin.php';
	if ( isset($PHPSESSID) && session_check_admin($PHPSESSID) ) {
		echo "<body background = \"/images/img/bg.gif\"><center>";
		if(($error = update_user()) == -1)
			echo "�ǥ͸�Ƨ�s����!!<br>";
		else{
			echo "$error<br>";
		}
		if(($error = update_user_gra()) == -1)
			echo "�M�Z�ǥ͸�Ƨ�s����!!<br>";
		else{
			echo "$error<br>";
		}
		
		echo "<br><a href=../check_admin.php>�^�t�κ޲z����</a></center></body>";
	}
	else
		show_page( "index_ad.tpl", "�A���v�����~�A�Э��s�n�J!!");
	
	// ��s�ǥͦW��
	function update_user() {
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $id, $pass;
	    
		if( !($cnx = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) ){  
			Error_handler( "�b sybase_connect �����~�o��" , $cnx );  
		}
		$csd = @sybase_select_db("academic", $cnx);
		//�b�ǥ͸��(�t���)
		$cur = sybase_query("select * from a11vstd_rec_tea", $cnx);
		if(!$cur) {  
			Error_handler( "�b sybase_exec �����~�o��( �S�����жǦ^ ) " , $cnx );  
		}
		//���~�ΰh��
		$cur2 = sybase_query("select * from a11vleave_rec_tea", $cnx);
		if(!$cur2) {  
			Error_handler( "�b sybase_exec �����~�o��( �S�����жǦ^ ) " , $cnx );  
		} 

		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "mysql��Ʈw�s�����~!!";
			echo "$error";
		}
		
		while($result=sybase_fetch_array($cur))
		{
			$Q2 = "select id,name,disable from user where id = '$result[id]'";
			if ($result2 = mysql_db_query($DB,$Q2)){			
				if($result[status]=="�b��"){
					$row = mysql_fetch_array($result2);
					// �s��
					if(!$row){
						$stu_name = addslashes($result[name]);
						$Q3 = "insert into user (id, pass, ftppass, authorization, name, sex, tel, zip, addr, email) values (\"$result[id]\", \"" . passwd_encrypt($result[ps]) . "\", \"" . md5($result[ps]) . "\", \"3\", \"$stu_name\", \"$result[sex]\", \"$result[tel] \", \"$result[zip]\", \"$result[addr]\", \"$result[email]\")";
						if ( !($result3 = mysql_db_query($DB,$Q3)) ){
							$error = "mysql��Ʈw�g�J���~!!";
							echo "$error".": $Q3".": $result3<br>";
						}
					}
					// ��ǥʹ_��,�ҥΨϥ��v��
					else if($row && $row[disable]=='1'){
						$Q3 = "update user set disable='0' where id='$result[id]'";
						if ( !($result3 = mysql_db_query($DB,$Q3)) ){
							$error = "mysql��Ʈw�g�J���~!!";
							echo "$error".": $Q3".": $result3<br>";
						}
					}
					// �ǥͤw��W�ɡA���W�r
					else if($row && ( strcmp($row[name],$result[name]) !=0 )){
						$Q3 = "update user set name='$result[name]' where id='$result[id]'";
						if ( !($result3 = mysql_db_query($DB,$Q3)) ){
							$error = "mysql��Ʈw�g�J���~!!";
							echo "$error".": $Q3".": $result3<br>";
						}
						echo $result[id].$row[name]." ��W�� ".$result[name]."<br>";
					}
				}
				
				else if($result[status]=="���"){
					$row = mysql_fetch_array($result2);
					// ��ǥ�(�Ĥ@����s��Ʈɤ~�|�Ψ�)
					if(!$row){
						$stu_name = addslashes($result[name]);
						$Q3 = "insert into user (id, pass, ftppass, authorization, disable, name, sex, tel, zip, addr, email) values (\"$result[id]\", \"" . passwd_encrypt($result[ps]) . "\", \"" . md5($result[ps]) . "\", \"3\", \"1\", \"$stu_name\", \"$result[sex]\", \"$result[tel] \", \"$result[zip]\", \"$result[addr]\", \"$result[email]\")";
						if ( !($result3 = mysql_db_query($DB,$Q3)) ){
							$error = "mysql��Ʈw�g�J���~!!";
							echo "$error".": $Q3".": $result3<br>";
						}
					}
					// �b�ǥͥ��,�Ȱ��ϥ��v��
					else if($row && $row[disable]=='0'){
						$Q3 = "update user set disable='1' where id='$result[id]'";
						if ( !($result3 = mysql_db_query($DB,$Q3)) ){
							$error = "mysql��Ʈw�g�J���~!!";
							echo "$error".": $Q3".": $result3<br>";
						}
					}
				}
			}
			else{
				$error = "mysql��ƮwŪ�����~!!";
				echo "$error";
			}
		}
		
		while($result=sybase_fetch_array($cur2)){
			$Q2 = "select id from user where id = '$result[id]'";
			if ($result2 = mysql_db_query($DB,$Q2)){
				$row = mysql_fetch_row($result2);
				// ���~�ͩΰh�ǥͫh�������
				if($row){
					$Q3 = "delete from user where id='$result[id]'";
					echo "�@��ǥ͸�Ƥw�M��$result[id]<br>";
					if ( !($result3 = mysql_db_query($DB,$Q3)) ){
						$error = "mysql��Ʈw�g�J���~!!";
						echo "$error".": $Q3".": $result3<br>";
					}
				}
			}
			else{
				$error = "mysql��ƮwŪ�����~!!";
				echo "$error";
			}
		}
		
		sybase_close( $cnx);
		return -1;
	}

	// ��s�M�Z�ǥͦW��
	function update_user_gra() {
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $id, $pass;
	    
		if( !($cnx = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) ){  
			Error_handler( "�b sybase_connect �����~�o��" , $cnx );  
		}
		$csd = @sybase_select_db("academic_gra", $cnx);
		// �b�ǥ͸��(�t���)
		$cur = sybase_query("select * from a11vstd_rec_tea", $cnx);
		if(!$cur) {  
			Error_handler( "�b sybase_exec �����~�o��( �S�����жǦ^ ) " , $cnx );  
		}
		// ���~�ΰh��
		$cur2 = sybase_query("select * from a11vleave_rec_tea", $cnx);
		if(!$cur2) {  
			Error_handler( "�b sybase_exec �����~�o��( �S�����жǦ^ ) " , $cnx );  
		} 

		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "mysql��Ʈw�s�����~!!";
			echo "$error";
		}
		
		while($result=sybase_fetch_array($cur))
		{
			$Q2 = "select id,name,disable from user where id = '$result[id]'";
			if ($result2 = mysql_db_query($DB,$Q2)){			
				if($result[status]=="�b��"){
					$row = mysql_fetch_array($result2);
					// �s��
					if(!$row){
						$stu_name = addslashes($result[name]);
						$Q3 = "insert into user (id, pass, ftppass, authorization, name, sex, tel, zip, addr, email) values (\"$result[id]\", \"" . passwd_encrypt($result[ps]) . "\", \"" . md5($result[ps]) . "\", \"3\", \"$stu_name\", \"$result[sex]\", \"$result[tel] \", \"$result[zip]\", \"$result[addr]\", \"$result[email]\")";
						if ( !($result3 = mysql_db_query($DB,$Q3)) ){
							$error = "mysql��Ʈw�g�J���~!!";
							echo "$error".": $Q3".": $result3<br>";
						}
					}
					// ��ǥʹ_��,�ҥΨϥ��v��
					else if($row && $row[disable]=='1'){
						$Q3 = "update user set disable='0' where id='$result[id]'";
						if ( !($result3 = mysql_db_query($DB,$Q3)) ){
							$error = "mysql��Ʈw�g�J���~!!";
							echo "$error".": $Q3".": $result3<br>";
						}
					}
					// �ǥͤw��W�ɡA���W�r
					else if($row && ( strcmp($row[name],$result[name]) !=0 )){
						$Q3 = "update user set name='$result[name]' where id='$result[id]'";
						if ( !($result3 = mysql_db_query($DB,$Q3)) ){
							$error = "mysql��Ʈw�g�J���~!!";
							echo "$error".": $Q3".": $result3<br>";
						}
						//echo "$row[name] , $result[name]<br>";
						echo $result[id].$row[name]." ��W�� ".$result[name]."<br>";
					}
				}
				
				else if($result[status]=="���"){
					$row = mysql_fetch_array($result2);
					// ��ǥ�(�Ĥ@����s��Ʈɤ~�|�Ψ�)
					if(!$row){
						$stu_name = addslashes($result[name]);
						$Q3 = "insert into user (id, pass, ftppass, authorization, disable, name, sex, tel, zip, addr, email) values (\"$result[id]\", \"" . passwd_encrypt($result[ps]) . "\", \"" . md5($result[ps]) . "\", \"3\", \"1\", \"$stu_name\", \"$result[sex]\", \"$result[tel] \", \"$result[zip]\", \"$result[addr]\", \"$result[email]\")";
						if ( !($result3 = mysql_db_query($DB,$Q3)) ){
							$error = "mysql��Ʈw�g�J���~!!";
							echo "$error".": $Q3".": $result3<br>";
						}
					}
					// �b�ǥͥ��,�Ȱ��ϥ��v��
					else if($row && $row[disable]=='0'){
						$Q3 = "update user set disable='1' where id='$result[id]'";
						if ( !($result3 = mysql_db_query($DB,$Q3)) ){
							$error = "mysql��Ʈw�g�J���~!!";
							echo "$error".": $Q3".": $result3<br>";
						}
					}
				}
			}
			else{
				$error = "mysql��ƮwŪ�����~!!";
				echo "$error";
			}
		}
		
		while($result=sybase_fetch_array($cur2)){
			$Q2 = "select id from user where id = '$result[id]'";
			if ($result2 = mysql_db_query($DB,$Q2)){
				$row = mysql_fetch_row($result2);
				// ���~�ͩΰh�ǫh�R�����
				if($row){
					$Q3 = "delete from user where id='$result[id]'";
					echo "�M�Z�ǥ͸�Ƥw�M��$result[id]<br>";
					if ( !($result3 = mysql_db_query($DB,$Q3)) ){
						$error = "mysql��Ʈw�g�J���~!!";
						echo "$error".": $Q3".": $result3<br>";
					}
				}
			}
			else{
				$error = "mysql��ƮwŪ�����~!!";
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
