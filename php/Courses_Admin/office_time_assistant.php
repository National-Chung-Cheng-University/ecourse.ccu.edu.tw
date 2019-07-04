<?php
	/********************
	教師辦公室時間
	使用template
	office_time_assistant.tpl
	********************/
	require 'fadmin.php';
	update_status ("編輯辦公室時間");
	
	if ( !(isset($PHPSESSID) && session_check_stu($PHPSESSID))) {
		show_page( "not_access.tpl" ,"權限錯誤");
	}
	
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD , $skinnum;
	
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$error = "資料庫連結錯誤!!";
		show_page ( "not_access.tpl", $error );
		exit;
	}
	
	if($action=="update"){
		update_data();
	}
	else{
		show_page_d();
	}
	
	function update_data() {
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $skinnum, $version, $teacher_id;

		//將取到的POST變數組合成陣列，並轉換成UPDATE的內容
		$count = 0; //用來判斷是否有滿兩小時，超過兩個被勾選就一定超過兩小時
		$mon = array();
		$tue = array();
		$wed = array();
		$tur = array();		
		$fri = array();
		$i=1;
		for($i=1;$i<=25;$i++){
			switch($i){
				case 16:$j="A";break;
				case 17:$j="B";break;
				case 18:$j="C";break;
				case 19:$j="D";break;
				case 20:$j="E";break;
				case 21:$j="F";break;
				case 22:$j="G";break;
				case 23:$j="H";break;
				case 24:$j="I";break;
				case 25:$j="J";break;
				default: $j=$i;break;
			}			
		
			if( $_POST["1_".$j] == "1"){
				$mon[$i-1] = 1;
				$count++;
			}
			else{
				$mon[$i-1] = 0;
			}
			
			if(  $_POST["2_".$j]  == "1"){
				$tue[$i-1] = 1;
				$count++;
			}
			else{
				$tue[$i-1] = 0;
			}
			
			if(  $_POST["3_".$j] == "1"){
				$wed[$i-1] = 1;
				$count++;
			}
			else{
				$wed[$i-1] = 0;
			}
			
			if(  $_POST["4_".$j] == "1"){
				$tur[$i-1] = 1;
				$count++;
			}
			else{
				$tur[$i-1] = 0;
			}
			
			if(  $_POST["5_".$j] == "1"){
				$fri[$i-1] = 1;
				$count++;
			}else{
				$fri[$i-1] = 0;
			}
		}
		//判斷時間有無衝突
		if( ($error = check( $mon, $tue, $wed, $tur,$fri))!=1 ){			
			show_page_d($error);
		}
		else if($count<2)
		{
			show_page_d("選擇時間未滿兩小時");
		}
		else{					
			$updatemon = implode(",",$mon);
			$updatetue = implode(",",$tue);
			$updatewed = implode(",",$wed);
			$updatetur = implode(",",$tur);
			$updatefri = implode(",",$fri);
			
	
			$Q1 = "Update OfficeTime set Mon = '".$updatemon."', Tue = '".$updatetue."', Wed = '".$updatewed."', Thu = '".$updatetur."', Fri = '".$updatefri."', location ='".$_POST["location"]."', comment ='".$_POST["Message"]."' where teacher_id='".$teacher_id."'" ;
			if ( !($result = mysql_db_query( $DB, $Q1  )) ) {
				show_page_d("更新失敗");
			}else{
				show_page_d("更新完成");
			}
		}		
	}
	
	function check($mon, $tue, $wed, $tur,$fri){
		if( ( $error = check2($mon, "一") ) !=1)
			return $error;
		if( ( $error = check2($tue, "二") ) !=1)
			return $error;	
		if( ( $error = check2($wed, "三") ) !=1)
			return $error;	
		if( ( $error = check2($tur, "四") ) !=1)
			return $error;
		if( ( $error = check2($fri, "五") ) !=1)
			return $error;	
			
		return	1;
	}
	
	function check2($array, $week){
	
		if( $array["15"] == 1 && ($array["0"] == 1 || $array["1"] == 1) ){
			return "星期".$week."的A與1或2衝突";
		}
		if( $array["16"] == 1 && ($array["1"] == 1 || $array["2"] == 1) ){
			return "星期".$week."的B與2或3衝突";
		}
		if( $array["17"] == 1 && ($array["3"] == 1 || $array["4"] == 1) ){
			return "星期".$week."的C與4或5衝突";
		}
		if( $array["18"] == 1 && ($array["4"] == 1 || $array["5"] == 1) ){
			return "星期".$week."的D與5或6衝突";
		}
		if( $array["19"] == 1 && ($array["6"] == 1 || $array["7"] == 1) ){
			return "星期".$week."的E與7或8衝突";
		}
		if( $array["20"] == 1 && ($array["7"] == 1 || $array["8"] == 1) ){
			return "星期$".$week."的F與8或9衝突";
		}
		if( $array["21"] == 1 && ($array["9"] == 1 || $array["10"] == 1) ){
			return "星期".$week."的G與10或11衝突";
		}
		if( $array["22"] == 1 && ($array["10"] == 1 || $array["11"] == 1) ){
			return "星期".$week."的H與11或2衝突";
		}
		if( $array["23"] == 1 && ($array["12"] == 1 || $array["13"] == 1) ){
			return "星期".$week."的I與13或14衝突";
		}
		if( $array["24"] == 1 && ($array["13"] == 1 || $array["14"] == 1) ){
			return "星期".$week."的J與14或15衝突";
		}

		return 1;
	}
	
	function show_page_d( $message="" ){
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $teacher_id, $skinnum, $version, $year, $term ;
		
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );

		$tpl->define ( array ( body => "office_time_assistant.tpl") );

		
		 $Q0 = "SELECT * FROM user where a_id = '$teacher_id'";
		if ( !($result0 = mysql_db_query( $DB, $Q0  )) ) {
				$error = "資料庫讀取錯誤!!Q1";
				show_page ( "not_access.tpl", $error );
				exit;
		}
		if ( !($row0 = mysql_fetch_array($result0)) ) {
				$error = "使用者不存在!!";
				show_page ( "not_access.tpl", $error );
				exit;
		}		
		
		$Q1 = "Select * From OfficeTime Where teacher_id='".$teacher_id."'";
		if ( !($result = mysql_db_query( $DB, $Q1  )) ) {
			$error = "資料庫讀取錯誤!!Q1";
			show_page ( "not_access.tpl", $error );
			exit;
		}
		
		
		if ( !($row = mysql_fetch_array($result)) ) {	//不存在時新增教師辦公室資料，並reload一次
			$Q2 = "Insert into OfficeTime (teacher_id) values ('".$row0['a_id']."')";				
			if ( !($result2 = mysql_db_query( $DB, $Q2  )) ) {
				$error = "資料庫讀取錯誤!!Q2";
				show_page ( "not_access.tpl", $error );
				exit;
			}
			$Q3 = "Select * From OfficeTime Where teacher_id='".$row0['a_id']."'";
			if ( !($result3 = mysql_db_query( $DB, $Q3  )) ) {
				$error = "資料庫讀取錯誤!!Q1";
				show_page ( "not_access.tpl", $error );
				exit;
			}
			if ( !($row = mysql_fetch_array($result3)) ) {
				$error = "資料庫讀取錯誤!!Q3";
				show_page ( "not_access.tpl", $error );
				exit;
			}	
		}
		
		//取出星期一到五的資料，分別切割後存在五個陣列裡
		$mon = explode(",",$row['Mon']);
		$tue = explode(",",$row['Tue']);
		$wed = explode(",",$row['Wed']);
		$thu = explode(",",$row['Thu']);
		$fri = explode(",",$row['Fri']);
		$i=0;		
		for($i=0 ; $i< 25 ; $i++){
			
			switch($i){
				case 15:$j="A";break;
				case 16:$j="B";break;
				case 17:$j="C";break;
				case 18:$j="D";break;
				case 19:$j="E";break;
				case 20:$j="F";break;
				case 21:$j="G";break;
				case 22:$j="H";break;
				case 23:$j="I";break;
				case 24:$j="J";break;
				default: $j=$i+1;break;
			}
			if($mon[$i] == "1"){
				$tpl->assign ( "1_".$j."CHECKED", "CHECKED" );
			}
			if($tue[$i] == "1"){
				$tpl->assign ( "2_".$j."CHECKED", "CHECKED" );
			}
			if($wed[$i] == "1"){
				$tpl->assign ( "3_".$j."CHECKED", "CHECKED" );
			}
			if($thu[$i] == "1"){
				$tpl->assign ( "4_".$j."CHECKED", "CHECKED" );
			}
			if($fri[$i] == "1"){
				$tpl->assign ( "5_".$j."CHECKED", "CHECKED" );
			}		
		}
		
		
		$tpl->assign ( MES, $message );	
		$tpl->assign ( TEACHID, $teacher_id );
		$tpl->assign ( COURSE_YEAR, $year );	
		$tpl->assign ( COURSE_TERM, $term );
		$tpl->assign ( OFFICEPOS, $row['location'] );	
		$tpl->assign ( TEL,$row0['tel'] );
		$tpl->assign ( EMAIL, $row0['email'] );
		$tpl->assign ( COMMENT, $row['comment'] );
		$tpl->assign ( SKINNUM, $skinnum );
		$tpl->parse( BODY, "body" );
		$tpl->FastPrint("BODY");
	}
		
?>