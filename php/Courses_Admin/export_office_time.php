<?php
/**
 * �ץX�Юv�줽�Ǯɶ�������T
 */
	require 'fadmin.php';
?>
<HTML>
<HEAD>
<TITLE>�ץX�줽�Ǯɶ�</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<meta http-equiv="Pragma" content="no-cache">
</HEAD>
<BODY>
<?	
	if ( isset($PHPSESSID) && session_check_admin($PHPSESSID) ) {
		echo "<body background = \"/images/img/bg.gif\"><center>";
		if( ($error = export_office_time("academic")) == -1 )
			echo "<a href=\"./office_time.xls\">�U���Юv�줽�Ǹ�T</a><br>";
			
		else{
			echo "$error<br>";
		}

		echo "<br><a href=../check_admin.php>�^�t�κ޲z����</a></center></body>";
	}
	else
		show_page( "index_ad.tpl", "�A���v�����~�A�Э��s�n�J!!");
	
	// ��s�@��Юv
	function export_office_time($db_name) {
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $id, $pass;


		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "mysql��Ʈw�s�����~!!";
			echo "$error";
		}
		/*
		if( !($cnx = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) ){  
			Error_handler( "�b sybase_connect �����~�o��" , $cnx );  
		}
		
		$csd = @sybase_select_db($db_name, $cnx);
		*/	
		$conn_string = "host=140.123.30.12 dbname=".$db_name." user=acauser password=!!acauser13";
		$cnx = pg_pconnect($conn_string) or die('��Ʈw�S���^���A�еy��A��');		
		
		$Q1 = "select distinct u.a_id, u.id, u.name, u.tel, u.email from user u, teach_course tc, this_semester ts where ts.year = tc.year and ts.term = ts.term and tc.teacher_id = u.a_id and u.authorization=1";
		$count=0;
		$index=0;
		$data = array(array()); //�G���}�C�s�Юv�줽�Ǹ�ơA�]���᭱�n�Ƨ�
		if ($result1 = mysql_db_query($DB,$Q1)){			
			$fp = fopen("./office_time.xls", "w");
			while( $row = mysql_fetch_array($result1))
			{				
				$Q2 = "select * from h0bvbasic_e_tea where id='".$row['id']."'";
				/*$cur = sybase_query($Q2, $cnx);
				if(!$cur) {  
					Error_handler( "�b sybase_exec �����~�o��( �S�����жǦ^ ) " , $cnx );  
				}*/
				
				$cur = pg_query($cnx, $Q2) or die('��ƪ��s�b�A�гq���q�⤤��');
				
				//if($row2 = sybase_fetch_array($cur)){					//sybase���]���Ѯv��T
				if($row2 = pg_fetch_array($cur, null, PGSQL_ASSOC)){					
					$Q3 = "Select * From OfficeTime Where teacher_id='".$row['a_id']."'";
					if ($result3 = mysql_db_query($DB,$Q3)){
					
						$data[$index]["unitname"] = mb_convert_encoding($row2['unitname'],'big5','utf-8,big5');	//�t��
						$data[$index]["name"] = mb_convert_encoding($row2['name'],'big5','utf-8,big5');    			//�Юv�m�W
						
						if($row3 = mysql_fetch_array($result3)){		//�p�Goffice_time�Ѯv����
						
						
							$mon = explode(",",$row3['Mon']);
							$tue = explode(",",$row3['Tue']);
							$wed = explode(",",$row3['Wed']);
							$thu = explode(",",$row3['Thu']);
							$fri = explode(",",$row3['Fri']);
						
							$data[$index]["time"] = "";					//�줽�Ǯɶ�
							
							//�N�Ǧ^�Ӫ��}�C�զX���r��
							$temp = "";
							$temp = implode(",",add_time($mon));							
							$data[$index]["time"] .= $temp ? "�@�B".$temp."  " : $temp;
							$temp = implode(",",add_time($tue));	
							$data[$index]["time"] .= $temp ? "�G�B".$temp."  " : $temp;
							$temp = implode(",",add_time($wed));	
							$data[$index]["time"] .= $temp ? "�T�B".$temp."  " : $temp;
							$temp = implode(",",add_time($thu));	
							$data[$index]["time"] .= $temp ? "�|�B".$temp."  " : $temp;
							$temp = implode(",",add_time($fri));	
							$data[$index]["time"] .= $temp ? "���B".$temp : $temp;
							
							$data[$index]["location"] = $row3['location'];	//�줽�Ǧa�I
						}
						else{											//�p�Goffice_time�Ѯv�S����
							$data[$index]["time"] = "";						//�줽�Ǯɶ�
							$data[$index]["location"] = "";					//�줽�Ǧa�I							
						}
						
						$data[$index]["tel"] = $row['tel'];				//�p���q��
						$data[$index]["email"] = $row['email'];			//��E-MAIL
						$index++;
					}
					$count++;
				}			
			}
			
			$data = qsort_multiarray($data,"unitname"); //�w��t�ұƧ�
			
			$content = "�t��\t�Юv�m�W\t�줽�Ǯɶ�\t�줽�Ǧa�I\t�p���q��\tE-MAIL\n";
			fwrite($fp,$content);
			foreach($data as $value){
				$content = $value["unitname"]."\t".$value["name"]."\t".$value["time"]."\t".$value["location"]."\t".$value["tel"]."\t".$value["email"]."\n";
				fwrite($fp,$content);
			}
			echo "���Ǵ��@".$count."��<BR><BR>";
		}
		fclose($fp);		
		return -1;
	}
	
	//�զX����X�}�C
	function add_time($array)
	{
		$time = array();
		if($array[15]==1){
			$time[]= "A ";
		}
		for($i=0;$i<=2;$i++){
			if($array[$i]==1){
				$time[]= ($i+1);
			}
		}
		if($array[16]==1){
			$time[]= "B";
		}
		if($array[17]==1){
			$time[]= "C";
		}
		for($i=3;$i<=5;$i++){
			if($array[$i]==1){
				$time[]= ($i+1);
			}
		}
		if($array[18]==1){
			$time[]= "D";
		}
		if($array[19]==1){
			$time[]= "E";
		}
		for($i=6;$i<=8;$i++){
			if($array[$i]==1){
				$time[]= ($i+1);
			}
		}
		if($array[20]==1){
			$time[]= "F";
		}
		if($array[21]==1){
			$time[]= "G";
		}
		for($i=9;$i<=11;$i++){
			if($array[$i]==1){
				$time[]= ($i+1);
			}
		}
		if($array[22]==1){
			$time[]= "H";
		}
		if($array[23]==1){
			$time[]= "I";
		}
		for($i=12;$i<=14;$i++){
			if($array[$i]==1){
				$time[]= ($i+1);
			}
		}
		if($array[24]==1){
			$time[]= "J";
		}
		
		return $time;
	}
	
	function Error_Handler( $msg, $cnx ) {  
		echo "$msg \n";
		//sybase_close( $cnx); exit();  
		pg_close( $cnx); exit();  
	}	
?>
</BODY>
</HTML>
