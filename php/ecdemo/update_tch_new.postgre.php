<?php
/**
 *�Юv�]�A�H�Ƹ�Ƥ��������Юv�A�H�Φp�Ȯy�б¡B���Z�оǵ������ݸu���A�����Юv�C
 *���{���B�z�q�H�Ƹ�ƨӪ������Юv��ơC
 */
	require 'fadmin.php';
	if ( isset($PHPSESSID) && session_check_admin($PHPSESSID) ) {
		echo "<body background = \"/images/img/bg.gif\"><center>";
		if(($error = update_user("academic")) == -1)
			echo "�Юv��Ƨ�s����!!<br>";
		else{
			echo "$error<br>";
		}
		if(($error = update_user("academic_gra")) == -1)
			echo "�M�Z�Юv��Ƨ�s����!!<br>";
		else{
			echo "$error<br>";
		}
		echo "<br><a href=../check_admin.php>�^�t�κ޲z����</a></center></body>";
	}
	else
		show_page( "index_ad.tpl", "�A���v�����~�A�Э��s�n�J!!");
	
	// ��s�@��Юv
	function update_user($db_name) {
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $id, $pass;
	    
		/*if( !($cnx = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) ){  
			Error_handler( "�b sybase_connect �����~�o��" , $cnx );  
		}
		$csd = @sybase_select_db($db_name, $cnx);
		
		$cur = sybase_query("select * from h0bvbasic_e_tea", $cnx);
		if(!$cur) {  
			Error_handler( "�b sybase_exec �����~�o��( �S�����жǦ^ ) " , $cnx );  
		}
		*/
		$conn_string = "host=140.123.30.12 dbname=".$db_name." user=acauser password=!!acauser13";
		$cnx = pg_pconnect($conn_string) or die('��Ʈw�S���^���A�еy��A��');
		
		$cur = pg_query($cnx, "select * from h0bvbasic_e_tea") or die('��ƪ��s�b�A�гq���q�⤤��');		

		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "mysql��Ʈw�s�����~!!";
			echo "$error";
		}
		
		//while($result=sybase_fetch_array($cur)){
		while($result=pg_fetch_array($cur, null, PGSQL_ASSOC)){
			$Q2 = "select id from user where id = '$result[id]'";
			//echo $Q2;
			if ($result2 = mysql_db_query($DB,$Q2)){			
				$row = mysql_fetch_array($result2);
				// �s�Юv
				if(!$row){
					//$tch_name = addslashes($result[name]);
					$tch_name = addslashes(mb_convert_encoding($result[name], "big5", "utf-8"));
					//$pswd = mkPasswd();
					$pswd = "";
					//2006.12 �����Юvvalidated='1'
					$Q3 = "insert into user (id, pass, ftppass, authorization, name, validated) values ('$result[id]', '$pswd', '', '1', '$tch_name', '1')";
					if ( !($result3 = mysql_db_query($DB,$Q3)) ){
						$error = "mysql��Ʈw�g�J���~!!";
						echo "$error".": $Q3".": $result3<br>";
					}
				}
				//��s�Юv�m�W
				else
				{
					//$tch_name = addslashes($result[name]);
					$tch_name = addslashes(mb_convert_encoding($result[name], "big5", "utf-8"));
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
		
		//sybase_close( $cnx);
		pg_close( $cnx);
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
		//sybase_close( $cnx); exit(); 
		pg_close( $cnx); exit();   
	}	
?>
