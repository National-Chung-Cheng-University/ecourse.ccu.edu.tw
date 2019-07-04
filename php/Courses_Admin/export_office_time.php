<?php
/**
 * 匯出教師辦公室時間的等資訊
 */
	require 'fadmin.php';
?>
<HTML>
<HEAD>
<TITLE>匯出辦公室時間</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<meta http-equiv="Pragma" content="no-cache">
</HEAD>
<BODY>
<?	
	if ( isset($PHPSESSID) && session_check_admin($PHPSESSID) ) {
		echo "<body background = \"/images/img/bg.gif\"><center>";
		if( ($error = export_office_time("academic")) == -1 )
			echo "<a href=\"./office_time.xls\">下載教師辦公室資訊</a><br>";
			
		else{
			echo "$error<br>";
		}

		echo "<br><a href=../check_admin.php>回系統管理介面</a></center></body>";
	}
	else
		show_page( "index_ad.tpl", "你的權限錯誤，請重新登入!!");
	
	// 更新一般教師
	function export_office_time($db_name) {
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $id, $pass;


		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "mysql資料庫連結錯誤!!";
			echo "$error";
		}
		/*
		if( !($cnx = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) ){  
			Error_handler( "在 sybase_connect 有錯誤發生" , $cnx );  
		}
		
		$csd = @sybase_select_db($db_name, $cnx);
		*/	
		$conn_string = "host=140.123.30.12 dbname=".$db_name." user=acauser password=!!acauser13";
		$cnx = pg_pconnect($conn_string) or die('資料庫沒有回應，請稍後再試');		
		
		$Q1 = "select distinct u.a_id, u.id, u.name, u.tel, u.email from user u, teach_course tc, this_semester ts where ts.year = tc.year and ts.term = ts.term and tc.teacher_id = u.a_id and u.authorization=1";
		$count=0;
		$index=0;
		$data = array(array()); //二維陣列存教師辦公室資料，因為後面要排序
		if ($result1 = mysql_db_query($DB,$Q1)){			
			$fp = fopen("./office_time.xls", "w");
			while( $row = mysql_fetch_array($result1))
			{				
				$Q2 = "select * from h0bvbasic_e_tea where id='".$row['id']."'";
				/*$cur = sybase_query($Q2, $cnx);
				if(!$cur) {  
					Error_handler( "在 sybase_exec 有錯誤發生( 沒有指標傳回 ) " , $cnx );  
				}*/
				
				$cur = pg_query($cnx, $Q2) or die('資料表不存在，請通知電算中心');
				
				//if($row2 = sybase_fetch_array($cur)){					//sybase內也有老師資訊
				if($row2 = pg_fetch_array($cur, null, PGSQL_ASSOC)){					
					$Q3 = "Select * From OfficeTime Where teacher_id='".$row['a_id']."'";
					if ($result3 = mysql_db_query($DB,$Q3)){
					
						$data[$index]["unitname"] = mb_convert_encoding($row2['unitname'],'big5','utf-8,big5');	//系所
						$data[$index]["name"] = mb_convert_encoding($row2['name'],'big5','utf-8,big5');    			//教師姓名
						
						if($row3 = mysql_fetch_array($result3)){		//如果office_time老師有填
						
						
							$mon = explode(",",$row3['Mon']);
							$tue = explode(",",$row3['Tue']);
							$wed = explode(",",$row3['Wed']);
							$thu = explode(",",$row3['Thu']);
							$fri = explode(",",$row3['Fri']);
						
							$data[$index]["time"] = "";					//辦公室時間
							
							//將傳回來的陣列組合成字串
							$temp = "";
							$temp = implode(",",add_time($mon));							
							$data[$index]["time"] .= $temp ? "一、".$temp."  " : $temp;
							$temp = implode(",",add_time($tue));	
							$data[$index]["time"] .= $temp ? "二、".$temp."  " : $temp;
							$temp = implode(",",add_time($wed));	
							$data[$index]["time"] .= $temp ? "三、".$temp."  " : $temp;
							$temp = implode(",",add_time($thu));	
							$data[$index]["time"] .= $temp ? "四、".$temp."  " : $temp;
							$temp = implode(",",add_time($fri));	
							$data[$index]["time"] .= $temp ? "五、".$temp : $temp;
							
							$data[$index]["location"] = $row3['location'];	//辦公室地點
						}
						else{											//如果office_time老師沒有填
							$data[$index]["time"] = "";						//辦公室時間
							$data[$index]["location"] = "";					//辦公室地點							
						}
						
						$data[$index]["tel"] = $row['tel'];				//聯絡電話
						$data[$index]["email"] = $row['email'];			//辦E-MAIL
						$index++;
					}
					$count++;
				}			
			}
			
			$data = qsort_multiarray($data,"unitname"); //針對系所排序
			
			$content = "系所\t教師姓名\t辦公室時間\t辦公室地點\t聯絡電話\tE-MAIL\n";
			fwrite($fp,$content);
			foreach($data as $value){
				$content = $value["unitname"]."\t".$value["name"]."\t".$value["time"]."\t".$value["location"]."\t".$value["tel"]."\t".$value["email"]."\n";
				fwrite($fp,$content);
			}
			echo "此學期共".$count."筆<BR><BR>";
		}
		fclose($fp);		
		return -1;
	}
	
	//組合成輸出陣列
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
