<?php
/**
 *�Юv�]�A�H�Ƹ�Ƥ��������Юv�A�H�Φp�Ȯy�б¡B���Z�оǵ������ݸu���A�����Юv�C
 *���{���B�z�q�H�Ƹ�ƨӪ������Юv��ơC
 */
	require 'fadmin.php';
	if ( isset($PHPSESSID) && session_check_admin($PHPSESSID) ) {
		echo "<body background = \"/images/img/bg.gif\"><center>";
		if(($error = update_user()) == -1)
			echo "�Юv��Ƨ�s����!!<br>";
		else{
			echo "$error<br>";
		}
		if(($error = update_user_gra()) == -1)
			echo "�M�Z�Юv��Ƨ�s����!!<br>";
		else{
			echo "$error<br>";
		}
		echo "<br><a href=../check_admin.php>�^�t�κ޲z����</a></center></body>";
	}
	else
		show_page( "index_ad.tpl", "�A���v�����~�A�Э��s�n�J!!");
	
	// ��s�@��Юv
	function update_user() {
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $id, $pass;
	    
		if( !($cnx = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) ){  
			Error_handler( "�b sybase_connect �����~�o��" , $cnx );  
		}
		$csd = @sybase_select_db("academic", $cnx);
		
		$cur = sybase_query("select * from h0bvbasic_e_tea", $cnx);
		if(!$cur) {  
			Error_handler( "�b sybase_exec �����~�o��( �S�����жǦ^ ) " , $cnx );  
		}

		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "mysql��Ʈw�s�����~!!";
			echo "$error";
		}
		
		while($result=sybase_fetch_array($cur)){
			$Q2 = "select id from user where id = '$result[id]'";
			if ($result2 = mysql_db_query($DB,$Q2)){			
				$row = mysql_fetch_array($result2);
				// �s�Юv
				if(!$row){
					$tch_name = addslashes($result[name]);
					//$pswd = mkPasswd();
					$pswd = "";
					//2006.12 �����Юvvalidated='1'
					$Q3 = "insert into user (id, pass, authorization, name, validated) values ('$result[id]', '$pswd', '1', '$tch_name', '1')";
					if ( !($result3 = mysql_db_query($DB,$Q3)) ){
						$error = "mysql��Ʈw�g�J���~!!";
						echo "$error".": $Q3".": $result3<br>";
					}
				}
				//��s�Юv�m�W
				else
				{
					$tch_name = addslashes($result[name]);
					//2006.12 �p���validated=0,�hupdate��'1'
					$Q3 = "update user set name = '$tch_name',validated='1' where id = '$result[id]'";
					if ( !($result3 = mysql_db_query($DB,$Q3)) ){
						$error = "mysql��Ʈw��s���~!!";
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

	// ��s�M�Z�Юv
	function update_user_gra() {
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $id, $pass;
	    
		if( !($cnx = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) ){  
			Error_handler( "�b sybase_connect �����~�o��" , $cnx );  
		}
		$csd = @sybase_select_db("academic_gra", $cnx);
		
		$cur = sybase_query("select * from h0bvbasic_e_tea", $cnx);
		if(!$cur) {  
			Error_handler( "�b sybase_exec �����~�o��( �S�����жǦ^ ) " , $cnx );  
		}

		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "mysql��Ʈw�s�����~!!";
			echo "$error";
		}
		
		while($result=sybase_fetch_array($cur)){
			$Q2 = "select id from user where id = '$result[id]'";
			if ($result2 = mysql_db_query($DB,$Q2)){			
				$row = mysql_fetch_array($result2);
				// �s�Юv
				if(!$row){
					$tch_name = addslashes($result[name]);
					//$pswd = mkPasswd();
					$pswd = "";
					//2006.12 �����Юvvalidated='1'
					$Q3 = "insert into user (id, pass, authorization, name, validated) values ('$result[id]', '$pswd', '1', '$tch_name', '1')";
					if ( !($result3 = mysql_db_query($DB,$Q3)) ){
						$error = "mysql��Ʈw�g�J���~!!";
						echo "$error".": $Q3".": $result3<br>";
					}
				}
				//��s�Юv�m�W
				else
				{
					$tch_name = addslashes($result[name]);
					//2006.12 �p���validated=0,�hupdate��'1'
					$Q3 = "update user set name = '$tch_name', validated='1' where id = '$result[id]'";
					if ( !($result3 = mysql_db_query($DB,$Q3)) ){
						$error = "mysql��Ʈw��s���~!!";
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

	// �üƲ���6��ƱK�X
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
		sybase_close( $cnx); exit();  
	}	
?>
