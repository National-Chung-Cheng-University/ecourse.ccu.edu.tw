<?PHP
	require 'fadmin.php';

	if ( isset($PHPSESSID) && session_check_admin($PHPSESSID) ) {
		if(($error = clear_teach_course()) != -1){
			echo "$error (�M���}�Ҹ��)<br>";
		}else{
			echo "<center>�}�Ҹ�ƲM������!!</center><br>";
		}
		echo "<br><center><a href=../check_admin.php>�^�t�κ޲z����</a></center>";

	}
	else
		show_page( "index_ad.tpl", "�A���v�����~�A�Э��s�n�J!!");


	// �M���½Ҹ��
	function clear_teach_course(){
		global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;
		// �s��sybase
		if( !($cnx = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) ){  
			Error_handler( "�b sybase_connect �����~�o��" , $cnx );  
		}
		$csd = @sybase_select_db("academic", $cnx);
		$cur = sybase_query("select year,term from a31vcurriculum_tea", $cnx);
		if(!$cur) {  
			Error_handler( "�b sybase_exec �����~�o��( �S�����жǦ^ ) " , $cnx );  
		}
		$array=sybase_fetch_array($cur);
		$year = $array['year'];
		$term = $array['term'];
		
		// �s��mysql
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "��Ʈw�s�����~!!";
			return $error;
		}
		
		$Qc = "delete from teach_course where year!=$year or term!=$term";
		if ( !($resultc = mysql_db_query( $DB, $Qc ) ) ) {
			$error = "��ƮwŪ�����~!!";
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
