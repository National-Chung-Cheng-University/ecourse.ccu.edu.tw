<?php
	/********************
	�Юv�줽�Ǯɶ�
	�ϥ�template
	office_time_assistant.tpl
	********************/
	require 'fadmin.php';
	update_status ("�s��줽�Ǯɶ�");
	
	if ( !(isset($PHPSESSID) && session_check_stu($PHPSESSID))) {
		show_page( "not_access.tpl" ,"�v�����~");
	}
	
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD , $skinnum;
	
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$error = "��Ʈw�s�����~!!";
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

		//�N���쪺POST�ܼƲզX���}�C�A���ഫ��UPDATE�����e
		$count = 0; //�ΨӧP�_�O�_������p�ɡA�W�L��ӳQ�Ŀ�N�@�w�W�L��p��
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
		//�P�_�ɶ����L�Ĭ�
		if( ($error = check( $mon, $tue, $wed, $tur,$fri))!=1 ){			
			show_page_d($error);
		}
		else if($count<2)
		{
			show_page_d("��ܮɶ�������p��");
		}
		else{					
			$updatemon = implode(",",$mon);
			$updatetue = implode(",",$tue);
			$updatewed = implode(",",$wed);
			$updatetur = implode(",",$tur);
			$updatefri = implode(",",$fri);
			
	
			$Q1 = "Update OfficeTime set Mon = '".$updatemon."', Tue = '".$updatetue."', Wed = '".$updatewed."', Thu = '".$updatetur."', Fri = '".$updatefri."', location ='".$_POST["location"]."', comment ='".$_POST["Message"]."' where teacher_id='".$teacher_id."'" ;
			if ( !($result = mysql_db_query( $DB, $Q1  )) ) {
				show_page_d("��s����");
			}else{
				show_page_d("��s����");
			}
		}		
	}
	
	function check($mon, $tue, $wed, $tur,$fri){
		if( ( $error = check2($mon, "�@") ) !=1)
			return $error;
		if( ( $error = check2($tue, "�G") ) !=1)
			return $error;	
		if( ( $error = check2($wed, "�T") ) !=1)
			return $error;	
		if( ( $error = check2($tur, "�|") ) !=1)
			return $error;
		if( ( $error = check2($fri, "��") ) !=1)
			return $error;	
			
		return	1;
	}
	
	function check2($array, $week){
	
		if( $array["15"] == 1 && ($array["0"] == 1 || $array["1"] == 1) ){
			return "�P��".$week."��A�P1��2�Ĭ�";
		}
		if( $array["16"] == 1 && ($array["1"] == 1 || $array["2"] == 1) ){
			return "�P��".$week."��B�P2��3�Ĭ�";
		}
		if( $array["17"] == 1 && ($array["3"] == 1 || $array["4"] == 1) ){
			return "�P��".$week."��C�P4��5�Ĭ�";
		}
		if( $array["18"] == 1 && ($array["4"] == 1 || $array["5"] == 1) ){
			return "�P��".$week."��D�P5��6�Ĭ�";
		}
		if( $array["19"] == 1 && ($array["6"] == 1 || $array["7"] == 1) ){
			return "�P��".$week."��E�P7��8�Ĭ�";
		}
		if( $array["20"] == 1 && ($array["7"] == 1 || $array["8"] == 1) ){
			return "�P��$".$week."��F�P8��9�Ĭ�";
		}
		if( $array["21"] == 1 && ($array["9"] == 1 || $array["10"] == 1) ){
			return "�P��".$week."��G�P10��11�Ĭ�";
		}
		if( $array["22"] == 1 && ($array["10"] == 1 || $array["11"] == 1) ){
			return "�P��".$week."��H�P11��2�Ĭ�";
		}
		if( $array["23"] == 1 && ($array["12"] == 1 || $array["13"] == 1) ){
			return "�P��".$week."��I�P13��14�Ĭ�";
		}
		if( $array["24"] == 1 && ($array["13"] == 1 || $array["14"] == 1) ){
			return "�P��".$week."��J�P14��15�Ĭ�";
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
				$error = "��ƮwŪ�����~!!Q1";
				show_page ( "not_access.tpl", $error );
				exit;
		}
		if ( !($row0 = mysql_fetch_array($result0)) ) {
				$error = "�ϥΪ̤��s�b!!";
				show_page ( "not_access.tpl", $error );
				exit;
		}		
		
		$Q1 = "Select * From OfficeTime Where teacher_id='".$teacher_id."'";
		if ( !($result = mysql_db_query( $DB, $Q1  )) ) {
			$error = "��ƮwŪ�����~!!Q1";
			show_page ( "not_access.tpl", $error );
			exit;
		}
		
		
		if ( !($row = mysql_fetch_array($result)) ) {	//���s�b�ɷs�W�Юv�줽�Ǹ�ơA��reload�@��
			$Q2 = "Insert into OfficeTime (teacher_id) values ('".$row0['a_id']."')";				
			if ( !($result2 = mysql_db_query( $DB, $Q2  )) ) {
				$error = "��ƮwŪ�����~!!Q2";
				show_page ( "not_access.tpl", $error );
				exit;
			}
			$Q3 = "Select * From OfficeTime Where teacher_id='".$row0['a_id']."'";
			if ( !($result3 = mysql_db_query( $DB, $Q3  )) ) {
				$error = "��ƮwŪ�����~!!Q1";
				show_page ( "not_access.tpl", $error );
				exit;
			}
			if ( !($row = mysql_fetch_array($result3)) ) {
				$error = "��ƮwŪ�����~!!Q3";
				show_page ( "not_access.tpl", $error );
				exit;
			}	
		}
		
		//���X�P���@�줭����ơA���O���Ϋ�s�b���Ӱ}�C��
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