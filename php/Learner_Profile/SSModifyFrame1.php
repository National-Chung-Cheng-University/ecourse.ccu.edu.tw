<?php
	require 'fadmin.php';
	update_status ("�s��ӤH���");
	if ( !(isset($PHPSESSID) && session_check_teach($PHPSESSID)) ) {
		show_page( "not_access.tpl" ,"�v�����~");
	}

	if ( $user_id == "guest" ) {
		if( $version == "C" ) {
			show_page ( "not_access.tpl", "$error �A�S���v���ϥΦ��\��!!" );
			exit;
		}
		else {
			show_page ( "not_access.tpl", "$error You have No Permission!!" );
			exit;
		}
	}

	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $skinnum;
	$Q1 = "SELECT * FROM user where id = '$user_id'";
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$error = "��Ʈw�s�����~!!";
		show_page ( "not_access.tpl", $error );
		exit;
	}
	else if ( !($result = mysql_db_query( $DB, $Q1  )) ) {
		$error = "��ƮwŪ�����~!!";
		show_page ( "not_access.tpl", $error );
		exit;
	}
	else if ( !($row = mysql_fetch_array($result)) ) {
		$error = "�ϥΪ̤��s�b!!";
		show_page ( "not_access.tpl", $error );
		exit;
	}
	else if ( $row["authorization"] == 9 ) {
		if( $version == "C" ) {
			show_page ( "not_access.tpl", "$error �A�S���v���ϥΦ��\��!!" );
			exit;
		}
		else {
			show_page ( "not_access.tpl", "$error You have No Permission!!" );
			exit;
		}
	}
	
	// new added by kof9x at 2001/12/04.
	switch((int)$row['color']) {
		case 1:
			$color = "<font color=orange>���</font>";
			break;
		case 2:
			$color = "<font color=gold>����</font>";
			break;
		case 3:
			$color = "<font color=blue>�Ŧ�</font>";
			break;
		case 4:
			$color = "<font color=green>���</font>";
			break;
		default:
			$color = "�m�i (�٨S���L����)";
			break;
	}

	if ( ($btn == "�W���ɮ�" || $btn == "UPLOAD") ) {
		if( file_exists("../../studentPage".$pic) )
		{
			$error = "�Ӥ��ɮפw�s�b�A�Х��R����A�W��!!";
		}else {
			if( $pic_file != "none" && fileupload ( $pic_file, "../../studentPage", $pic, "0774" ) ) {
				if ( $version == "C" )
					$error = "�W�Ǧ��\\";
				else
					$error = "Upload Success";
			}
   			else {
   				if ( $version == "C" )
					$error = "�W�ǥ���";
				else
					$error = "Upload Abort";
			}
		}
	}
	else if ( ($btn == "�R���ɮ�" || $btn == "Delete") ) {
		if( unlink( "../../studentPage/$user_id.gif" ) ) {
			if ( $version == "C" )
				$error = "�R�����\\";
			else
				$error = "Delete Success";
		}
   		else {
   			if ( $version == "C" )
				$error = "�R������";
			else
				$error = "Delete Abort";
		}
	}

	if ( isset($name) && isset($year) && isset($tel) && isset($email) ) {
		$ip = getenv("SERVER_NAME");
		
		if ( $ip == "" )
			$ip = $SERVER_NAME;
		$error = "�ж�";
		if ( $year == "" )
			$error = "$error �X�ͦ~��";
		if ( $tel == "" )
			$error = "$error �p���q��";
		if ( $email == "" )
				$error = "$error �q�l�l����}";
		if ( $pageKind == 1 ) {
			if( $version == "C" )
	    			$uurl = "http://".$ip."/studentPage/". $user_id .".html";
    			else
	    			$uurl = "http://".$ip."/studentPage/". $user_id .".html";
		}

		if ( $error == "�ж�" ) {
			if ( $btn == "�����w��" || $btn == "PREVIEW") {
				show_homepage ( );
				exit;
			}
			else if ( $btn == "�T�w" || $btn =="Submit" ) {
				$add = add_page ( );
				if ( $add == 1 ) {
					$error = "��ƥ[�J���\\";
					$Q2 = "update user set sex = '$sex', birthday = '$year-$month-$day', tel = '$tel',
						addr = '$addr', email = '$email', php = '$uurl', job = '$job', introduction = '$intro',
				  		interest = '$interest', skill = '$skill', nickname = '$nickname' where id = '$user_id'";
 					if ( !($result = mysql_db_query( $DB, $Q2  )) )
						$error = "��Ʈw�g�J���~!!";
				}
				else
					$error = "�����إߥ���";
			}
			else
				$error = "";
		}
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		if( $version == "C" )
			$tpl->define ( array ( body => "SSModifyFrame1.tpl") );
		else
			$tpl->define ( array ( body => "SSModifyFrame1_E.tpl") );
		$tpl->assign ( ID, $row['id'] );
		$tpl->assign( SKINNUM , $skinnum );
		$name = stripslashes( $name );
		$nickname = stripslashes( $nickname );
		$interest = stripslashes( $interest );
		$skill = stripslashes( $skill );
		$intro = stripslashes( $intro );
		$tel = stripslashes( $tel );
		$addr = stripslashes( $addr );
		$email = stripslashes( $email );
		
		$tpl->assign ( NAME, $name );
		$tpl->assign ( NICK, $nickname );
		$tpl->assign ( "BL$blood", "selected" );
		$tpl->assign ( "ST$star", "selected" );
		$tpl->assign ( INTEREST, $interest );
		$tpl->assign ( "SEX$sex", "selected" );

		//�ǥͤ������ �אּ��sybase�P�B
		$row['job'] = iconv('utf-8','big5',$row['job']);
		$tpl->assign ( JOB,  $row['job'] );
/*
		if ( $job == "��u�t" )
			$tpl->assign ( JOB01, "selected" );
		else if ( $job == "��u��" )
			$tpl->assign ( JOB02, "selected" );
		else if ( $job == "�q���t" )
			$tpl->assign ( JOB03, "selected" );
		else if ( $job == "�q����" )
			$tpl->assign ( JOB04, "selected" );
		else if ( $job == "����t" )
			$tpl->assign ( JOB05, "selected" );
		else if ( $job == "�����" )
			$tpl->assign ( JOB06, "selected" );
		else if ( $job == "�Ƥu�t" )
			$tpl->assign ( JOB07, "selected" );
		else if ( $job == "�Ƥu��" )
			$tpl->assign ( JOB08, "selected" );
		else if ( $job == "�q�T�u�{�t" )
			$tpl->assign ( JOB67, "selected" );
		else if ( $job == "�q�T�u�{��" )
			$tpl->assign ( JOB09, "selected" );
		else if ( $job == "���q��" )
			$tpl->assign ( JOB10, "selected" );
		else if ( $job == "�ƾǨt" )
			$tpl->assign ( JOB11, "selected" );
		else if ( $job == "���Ʃ�" )
			$tpl->assign ( JOB12, "selected" );
		else if ( $job == "�a�_��" )
			$tpl->assign ( JOB13, "selected" );
		else if ( $job == "���z�t" )
			$tpl->assign ( JOB14, "selected" );
		else if ( $job == "���z��" )
			$tpl->assign ( JOB15, "selected" );
		else if ( $job == "�ƾǨt" )
			$tpl->assign ( JOB16, "selected" );
		else if ( $job == "�ƾǩ�" )
			$tpl->assign ( JOB17, "selected" );
		else if ( $job == "�ƲΩ�" )
			$tpl->assign ( JOB18, "selected" );
		else if ( $job == "�ά��" )
			$tpl->assign ( JOB19, "selected" );
		else if ( $job == "���Φa����" )
			$tpl->assign ( JOB20, "selected" );
		else if ( $job == "�ƾǩ�" )
			$tpl->assign ( JOB21, "selected" );
		else if ( $job == "���l�ͪ���" )
			$tpl->assign ( JOB22, "selected" );
		else if ( $job == "�g�پǨt" )
			$tpl->assign ( JOB23, "selected" );
		else if ( $job == "��g��" )
			$tpl->assign ( JOB24, "selected" );
		else if ( $job == "�]���t" )
			$tpl->assign ( JOB25, "selected" );
		else if ( $job == "�]����" )
			$tpl->assign ( JOB26, "selected" );
		else if ( $job == "���ިt" )
			$tpl->assign ( JOB27, "selected" );
		else if ( $job == "���ީ�" )
			$tpl->assign ( JOB28, "selected" );
		else if ( $job == "�|�p�t" )
			$tpl->assign ( JOB29, "selected" );
		else if ( $job == "�|�p��" )
			$tpl->assign ( JOB30, "selected" );
		else if ( $job == "��ިt" )
			$tpl->assign ( JOB31, "selected" );
		else if ( $job == "��ީ�" )
			$tpl->assign ( JOB32, "selected" );
		else if ( $job == "���֨t" )
			$tpl->assign ( JOB33, "selected" );
		else if ( $job == "���֩�" )
			$tpl->assign ( JOB34, "selected" );
		else if ( $job == "�߲z�t" )
			$tpl->assign ( JOB35, "selected" );
		else if ( $job == "�߲z��" )
			$tpl->assign ( JOB36, "selected" );
		else if ( $job == "�Ҥu�t" )
			$tpl->assign ( JOB37, "selected" );
		else if ( $job == "�Ҥu��" )
			$tpl->assign ( JOB38, "selected" );
		else if ( $job == "�F�v�t" )
			$tpl->assign ( JOB39, "selected" );
		else if ( $job == "�F�v��" )
			$tpl->assign ( JOB40, "selected" );
		else if ( $job == "�Ǽ��t" )
			$tpl->assign ( JOB41, "selected" );
		else if ( $job == "�q�ǩ�" )
			$tpl->assign ( JOB42, "selected" );
		else if ( $job == "�ǵ{����" )
			$tpl->assign ( JOB43, "selected" );
		else if ( $job == "����t" )
			$tpl->assign ( JOB44, "selected" );
		else if ( $job == "�����" )
			$tpl->assign ( JOB45, "selected" );
		else if ( $job == "�~��t" )
			$tpl->assign ( JOB46, "selected" );
		else if ( $job == "�~���" )
			$tpl->assign ( JOB47, "selected" );
		else if ( $job == "���v�t" )
			$tpl->assign ( JOB48, "selected" );
		else if ( $job == "���v��" )
			$tpl->assign ( JOB49, "selected" );
		else if ( $job == "���Ǩt" )
			$tpl->assign ( JOB50, "selected" );
		else if ( $job == "���ǩ�" )
			$tpl->assign ( JOB51, "selected" );
		else if ( $job == "�y����" )
			$tpl->assign ( JOB52, "selected" );
		else if ( $job == "�����ǩ�" )
			$tpl->assign ( JOB53, "selected" );
		else if ( $job == "�k�ߨt" )
			$tpl->assign ( JOB54, "selected" );
		else if ( $job == "�k�ߩ�" )
			$tpl->assign ( JOB55, "selected" );
		else if ( $job == "�k�ǲ�" )
			$tpl->assign ( JOB56, "selected" );
		else if ( $job == "�]�g�k��" )
			$tpl->assign ( JOB57, "selected" );
		else if ( $job == "�k���" )
			$tpl->assign ( JOB58, "selected" );
		else if ( $job == "�]�g��" )
			$tpl->assign ( JOB59, "selected" );
		else if ( $job == "���Шt" )
			$tpl->assign ( JOB60, "selected" );
		else if ( $job == "���Щ�" )
			$tpl->assign ( JOB61, "selected" );
		else if ( $job == "�Ш|��" )
			$tpl->assign ( JOB62, "selected" );
		else if ( $job == "�Ǩ��t" )
			$tpl->assign ( JOB63, "selected" );
		else if ( $job == "�Ǩ���" )
			$tpl->assign ( JOB64, "selected" );
		else if ( $job == "�𶢱Ш|��s��" )
			$tpl->assign ( JOB65, "selected" );
		else
			$tpl->assign ( JOB66, "selected" );
*/			
		$tpl->assign ( YEAR, $year );
		$tpl->assign ( "M$month", "selected" );
		$tpl->assign ( "D$day", "selected" );
		$tpl->assign ( SKILL, $skill );
		$tpl->assign ( INTRO, $intro );
		$tpl->assign ( TEL, $tel );
		$tpl->assign ( ADDR, $addr );
		$tpl->assign ( EMAIL, $email );
		$tpl->assign ( "P$pageKind", "checked" );
		$tpl->assign ( URL, $uurl );
	}	
	else {
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		if( $version == "C" )
			$tpl->define ( array ( body => "SSModifyFrame1.tpl") );
		else
			$tpl->define ( array ( body => "SSModifyFrame1_E.tpl") );
		$tpl->assign ( ID, $row['id'] );
		$tpl->assign( SKINNUM , $skinnum );
		$ip = getenv("SERVER_NAME");
		if ( $ip == "" )
			$ip = $SERVER_NAME;

		$path = "../../studentPage/". $user_id .".html";
		if ( is_file ( $path ) ) {
			$fp=fopen( $path, "r");
			$tmp = fread($fp, filesize($path) );				
			fclose($fp);
			if ( ($buf = stristr($tmp,"�嫬") ) != NULL) {
				$i = 0;
				while($buf{$i}!='%')
					$i++;
				$i += 3;
				if ( $buf{$i} == "A" && $buf{$i + 1 } == "B" )
					$tpl->assign ( "BL4", "selected" );
				else if ( $buf{$i} == "A" )
					$tpl->assign ( "BL2", "selected" );
				else if ( $buf{$i} == "B" )
					$tpl->assign ( "BL3", "selected" );
				else
					$tpl->assign ( "BL1", "selected" );
			}
			if ( ($buf = stristr($tmp,"�P�y") ) != NULL) {
				$i = 0;
				while($buf{$i}!='%')
					$i++;
				$i += 3;
				for ( $j = 0; $j <= 5; $j ++, $i ++ )
					$tmp2{$j} = $buf{$i};
				$tmp2 = implode("", $tmp2);
				if( $tmp2 == "�d�Ϯy" )
					$tpl->assign ( "ST01", "selected" );
				else if($tmp2 == "�����y")
					$tpl->assign ( "ST02", "selected" );
				else if($tmp2 == "���l�y")
					$tpl->assign ( "ST03", "selected" );
				else if($tmp2 == "���ɮy")
					$tpl->assign ( "ST04", "selected" );
				else if($tmp2 == "��l�y")
					$tpl->assign ( "ST05", "selected" );
				else if($tmp2 == "�B�k�y")
					$tpl->assign ( "ST06", "selected" );
				else if($tmp2 == "�ѯ��y")
					$tpl->assign ( "ST07", "selected" );
				else if($tmp2 == "���Ȯy")
					$tpl->assign ( "ST08", "selected" );
				else if($tmp2 == "�g��y")
					$tpl->assign ( "ST09", "selected" );
				else if($tmp2 == "�]�~�y")
					$tpl->assign ( "ST10", "selected" );
				else if($tmp2 == "���~�y")
					$tpl->assign ( "ST11", "selected" );
				else
					$tpl->assign ( "ST12", "selected" );
			}
		}

		$tpl->assign ( NAME, $row['name'] );
		$tpl->assign ( NICK, $row['nickname'] );
		$tpl->assign ( INTEREST, $row['interest'] );
		$tpl->assign ( "SEX".$row['sex'], "selected" );

		//�ǥͤ������ �אּ��sybase�P�B
		$row['job'] = iconv('utf-8','big5',$row['job']);
		$tpl->assign ( JOB,  $row['job'] );
/*		
		if ( $row['job'] == "��u�t" )
			$tpl->assign ( JOB01, "selected" );
		else if ( $row['job'] == "��u��" )
			$tpl->assign ( JOB02, "selected" );
		else if ( $row['job'] == "�q���t" )
			$tpl->assign ( JOB03, "selected" );
		else if ( $row['job'] == "�q����" )
			$tpl->assign ( JOB04, "selected" );
		else if ( $row['job'] == "����t" )
			$tpl->assign ( JOB05, "selected" );
		else if ( $row['job'] == "�����" )
			$tpl->assign ( JOB06, "selected" );
		else if ( $row['job'] == "�Ƥu�t" )
			$tpl->assign ( JOB07, "selected" );
		else if ( $row['job'] == "�Ƥu��" )
			$tpl->assign ( JOB08, "selected" );
		else if ( $row['job'] == "�q�T�u�{��" )
			$tpl->assign ( JOB09, "selected" );
		else if ( $row['job'] == "���q��" )
			$tpl->assign ( JOB10, "selected" );
		else if ( $row['job'] == "�ƾǨt" )
			$tpl->assign ( JOB11, "selected" );
		else if ( $row['job'] == "���Ʃ�" )
			$tpl->assign ( JOB12, "selected" );
		else if ( $row['job'] == "�a�_��" )
			$tpl->assign ( JOB13, "selected" );
		else if ( $row['job'] == "���z�t" )
			$tpl->assign ( JOB14, "selected" );
		else if ( $row['job'] == "���z��" )
			$tpl->assign ( JOB15, "selected" );
		else if ( $row['job'] == "�ƾǨt" )
			$tpl->assign ( JOB16, "selected" );
		else if ( $row['job'] == "�ƾǩ�" )
			$tpl->assign ( JOB17, "selected" );
		else if ( $row['job'] == "�ƲΩ�" )
			$tpl->assign ( JOB18, "selected" );
		else if ( $row['job'] == "�ά��" )
			$tpl->assign ( JOB19, "selected" );
		else if ( $row['job'] == "���Φa����" )
			$tpl->assign ( JOB20, "selected" );
		else if ( $row['job'] == "�ƾǩ�" )
			$tpl->assign ( JOB21, "selected" );
		else if ( $row['job'] == "���l�ͪ���" )
			$tpl->assign ( JOB22, "selected" );
		else if ( $row['job'] == "�g�پǨt" )
			$tpl->assign ( JOB23, "selected" );
		else if ( $row['job'] == "��g��" )
			$tpl->assign ( JOB24, "selected" );
		else if ( $row['job'] == "�]���t" )
			$tpl->assign ( JOB25, "selected" );
		else if ( $row['job'] == "�]����" )
			$tpl->assign ( JOB26, "selected" );
		else if ( $row['job'] == "���ިt" )
			$tpl->assign ( JOB27, "selected" );
		else if ( $row['job'] == "���ީ�" )
			$tpl->assign ( JOB28, "selected" );
		else if ( $row['job'] == "�|�p�t" )
			$tpl->assign ( JOB29, "selected" );
		else if ( $row['job'] == "�|�p��" )
			$tpl->assign ( JOB30, "selected" );
		else if ( $row['job'] == "��ިt" )
			$tpl->assign ( JOB31, "selected" );
		else if ( $row['job'] == "��ީ�" )
			$tpl->assign ( JOB32, "selected" );
		else if ( $row['job'] == "���֨t" )
			$tpl->assign ( JOB33, "selected" );
		else if ( $row['job'] == "���֩�" )
			$tpl->assign ( JOB34, "selected" );
		else if ( $row['job'] == "�߲z�t" )
			$tpl->assign ( JOB35, "selected" );
		else if ( $row['job'] == "�߲z��" )
			$tpl->assign ( JOB36, "selected" );
		else if ( $row['job'] == "�Ҥu�t" )
			$tpl->assign ( JOB37, "selected" );
		else if ( $row['job'] == "�Ҥu��" )
			$tpl->assign ( JOB38, "selected" );
		else if ( $row['job'] == "�F�v�t" )
			$tpl->assign ( JOB39, "selected" );
		else if ( $row['job'] == "�F�v��" )
			$tpl->assign ( JOB40, "selected" );
		else if ( $row['job'] == "�Ǽ��t" )
			$tpl->assign ( JOB41, "selected" );
		else if ( $row['job'] == "�q�ǩ�" )
			$tpl->assign ( JOB42, "selected" );
		else if ( $row['job'] == "�ǵ{����" )
			$tpl->assign ( JOB43, "selected" );
		else if ( $row['job'] == "����t" )
			$tpl->assign ( JOB44, "selected" );
		else if ( $row['job'] == "�����" )
			$tpl->assign ( JOB45, "selected" );
		else if ( $row['job'] == "�~��t" )
			$tpl->assign ( JOB46, "selected" );
		else if ( $row['job'] == "�~���" )
			$tpl->assign ( JOB47, "selected" );
		else if ( $row['job'] == "���v�t" )
			$tpl->assign ( JOB48, "selected" );
		else if ( $row['job'] == "���v��" )
			$tpl->assign ( JOB49, "selected" );
		else if ( $row['job'] == "���Ǩt" )
			$tpl->assign ( JOB50, "selected" );
		else if ( $row['job'] == "���ǩ�" )
			$tpl->assign ( JOB51, "selected" );
		else if ( $row['job'] == "�y����" )
			$tpl->assign ( JOB52, "selected" );
		else if ( $row['job'] == "�����ǩ�" )
			$tpl->assign ( JOB53, "selected" );
		else if ( $row['job'] == "�k�ߨt" )
			$tpl->assign ( JOB54, "selected" );
		else if ( $row['job'] == "�k�ߩ�" )
			$tpl->assign ( JOB55, "selected" );
		else if ( $row['job'] == "�k�ǲ�" )
			$tpl->assign ( JOB56, "selected" );
		else if ( $row['job'] == "�]�g�k��" )
			$tpl->assign ( JOB57, "selected" );
		else if ( $row['job'] == "�k���" )
			$tpl->assign ( JOB58, "selected" );
		else if ( $row['job'] == "�]�g��" )
			$tpl->assign ( JOB59, "selected" );
		else if ( $row['job'] == "���Шt" )
			$tpl->assign ( JOB60, "selected" );
		else if ( $row['job'] == "���Щ�" )
			$tpl->assign ( JOB61, "selected" );
		else if ( $row['job'] == "�Ш|��" )
			$tpl->assign ( JOB62, "selected" );
		else if ( $row['job'] == "�Ǩ��t" )
			$tpl->assign ( JOB63, "selected" );
		else if ( $row['job'] == "�Ǩ���" )
			$tpl->assign ( JOB64, "selected" );
		else if ( $row['job'] == "�𶢱Ш|��s��" )
			$tpl->assign ( JOB65, "selected" );
		else
			$tpl->assign ( JOB66, "selected" );
*/
		$tpl->assign ( YEAR, $row['birthday'][0].$row['birthday'][1].$row['birthday'][2].$row['birthday'][3] );
		$month = $row['birthday'][5].$row['birthday'][6];
		if ( $month == "" )
			$month = "01";
		$tpl->assign ( "M$month", "selected" );
		$day = $row['birthday'][8].$row['birthday'][9];
		if ( $day == "" )
			$day = "01";
		$tpl->assign ( "D$day", "selected" );
		$tpl->assign ( SKILL, $row['skill'] );
		$tpl->assign ( INTRO, $row['introduction'] );
		$tpl->assign ( TEL, $row['tel'] );
		$tpl->assign ( ADDR, $row['addr'] );
		$tpl->assign ( EMAIL, $row['email'] );
		if ( $row['php'] == "" ) {
			$tpl->assign ( "P1", "checked" );
			$tpl->assign ( URL, "http://" );
		}
		else {
			$tpl->assign ( "P2", "checked" );
			$tpl->assign ( URL, $row['php'] );
		}
	}

	$tpl->assign ( VERSION, $version );
	$tpl->assign ( MES, $error );
	$tpl->parse( BODY, "body" );
	$tpl->FastPrint("BODY");
	
	function add_page ( ) {
		global $course_id, $user_id, $name, $sex, $job, $email, $blood, $star, $interest, $skill, $intro, $color;
		$path = "../../studentPage/". $user_id .".html";
		$name2 = stripslashes( $name );
		$interest2 = stripslashes( $interest );
		$skill2 = stripslashes( $skill );
		$intro2 = stripslashes( $intro );
		$email2 = stripslashes( $email );
		$job2 = stripslashes( $job );
		if ($fs = fopen( $path , "w")) {
			fwrite($fs, "<html>\n");
			fwrite($fs, "<head>\n");
			fwrite($fs,"<META HTTP-EQUIV=\"Expires\" CONTENT=0>\n");
			fwrite($fs, "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=big5\">\n");
			fwrite($fs, "<title>".$user_id."������</title>\n");
			fwrite($fs, "</head>\n");
			fwrite($fs, "<body background=\"/images/img/bg.gif\">\n");
			fwrite($fs, "<div align=\"center\">\n");
			fwrite($fs, "<center>\n");
			fwrite($fs, "<table border=\"2\" cellspacing=\"4\" width=\"90%\">\n");
			fwrite($fs, "<tr>\n");
			fwrite($fs, "<td width=\"20%\" rowspan=\"10\" valign=\"top\" align=\"center\"><img border=\"0\" src=\"/studentPage/".$user_id.".gif\" width=\"100%\"></td>\n");
			fwrite($fs, "<td width=\"16%\"><b><font color=\"#0000FF\">�m�W :&nbsp;</font></b></td>\n");
			fwrite($fs, "<td width=\"64%\">". $name2 ."</td>\n");
			fwrite($fs, "</tr>\n");
			fwrite($fs, "<tr>\n");
			fwrite($fs, "<td width=\"16%\"><font color=\"#FFFF00\"><b>�ʧO :&nbsp;</b></font></td>\n");
			if($sex == 0)
				fwrite($fs, "<td width=\"64%\">�k</td>\n");
			else
				fwrite($fs, "<td width=\"64%\">�k</td>\n");	
			fwrite($fs, "</tr>\n");
			fwrite($fs, "<tr>\n");
			fwrite($fs, "<td width=\"16%\"><b><font color=\"#0000FF\">�t�� :&nbsp;</font></b></td>\n");

			if($job == NULL)
				fwrite($fs, "<td width=\"64%\">N/A</td>\n");
			else
				fwrite($fs, "<td width=\"64%\">$job2</td>\n");

			fwrite($fs, "</tr>\n");
			fwrite($fs, "<tr>\n");
			fwrite($fs, "<td width=\"16%\"><b><font color=\"#33CC33\">E-mail :&nbsp;</font></b></td>\n");
			fwrite($fs, "<td width=\"64%\">".$email2." </td>\n");
			fwrite($fs, "</tr>\n");
			fwrite($fs, "<tr>\n");
			fwrite($fs, "<td width=\"16%\"><b><font color=\"#0000FF\">�嫬 :&nbsp;</font></b></td>\n");
			if($blood == 0)
				fwrite($fs, "<td width=\"64%\">N/A</td>\n");
			else if($blood ==1)
				fwrite($fs, "<td width=\"64%\">O��</td>\n");
			else if($blood ==2)
				fwrite($fs, "<td width=\"64%\">A��</td>\n");
			else if($blood ==3)
				fwrite($fs, "<td width=\"64%\">B��</td>\n");
			else if($blood ==4)
				fwrite($fs, "<td width=\"64%\">AB��</td>\n");
			else
				fwrite($fs, "<td width=\"64%\">Error</td>\n");
			fwrite($fs, "</tr>\n");
			fwrite($fs, "<tr>\n");
			fwrite($fs, "<td width=\"16%\"><b><font color=\"#FFFF00\">�P�y :&nbsp;</font></b></td>\n");
			if($star == "00")
				fwrite($fs, "<td width=\"64%\">N/A</td>\n");
			else if($star == "01")
				fwrite($fs, "<td width=\"64%\">�d�Ϯy</td>\n");
			else if($star == "02")
				fwrite($fs, "<td width=\"64%\">�����y</td>\n");
			else if($star == "03")
				fwrite($fs, "<td width=\"64%\">���l�y</td>\n");
			else if($star == "04")
				fwrite($fs, "<td width=\"64%\">���ɮy</td>\n");
			else if($star == "05")
				fwrite($fs, "<td width=\"64%\">��l�y</td>\n");
			else if($star == "06")
				fwrite($fs, "<td width=\"64%\">�B�k�y</td>\n");
			else if($star == "07")
				fwrite($fs, "<td width=\"64%\">�ѯ��y</td>\n");
			else if($star == "08")
				fwrite($fs, "<td width=\"64%\">���Ȯy</td>\n");
			else if($star == "09")
				fwrite($fs, "<td width=\"64%\">�g��y</td>\n");
			else if($star == "10")
				fwrite($fs, "<td width=\"64%\">�]�~�y</td>\n");
			else if($star == "11")
				fwrite($fs, "<td width=\"64%\">���~�y</td>\n");
			else if($star == "12")
				fwrite($fs, "<td width=\"64%\">�����y</td>\n");
			else
				fwrite($fs, "<td width=\"64%\">Error</td>\n");
			fwrite($fs, "</tr>\n");
			fwrite($fs, "<tr>\n");

			$content = $interest2;
			$content = str_replace ( "\n", "<BR>", $content );
			fwrite($fs, "<td width=\"16%\"><b><font color=\"#0000FF\">���� :</font></b></td>\n");
			fwrite($fs, "<td width=\"64%\">". $content ."</td>\n");
			fwrite($fs, "</tr>\n");
			fwrite($fs, "<tr>\n");

			$content = $skill2;
			$content = str_replace ( "\n", "<BR>", $content );
			fwrite($fs, "<td width=\"16%\"><b><font color=\"#33CC33\">�M�� :</font></b></td>\n");
			fwrite($fs, "<td width=\"64%\">".$content."</td>\n");
			fwrite($fs, "</tr>\n");
			fwrite($fs, "<tr>\n");

			fwrite($fs, "<td width=\"16%\"><b><font color=\"#33CC33\">�ө��C�� :</font></b></td>\n");
			fwrite($fs, "<td width=\"64%\"><!--begin-->".$color."<!--end--></td>\n");
			fwrite($fs, "</tr>\n");
			fwrite($fs, "<tr>\n");

			$content = $intro2;
			$content = str_replace ( "\n", "<BR>", $content );
			fwrite($fs, "<td width=\"16%\"><b><font color=\"#0000FF\">�ӤH²�� :</font></b></td>\n");
			fwrite($fs, "<td width=\"64%\">".$content."</td>\n");
			fwrite($fs, "</tr>\n");
			fwrite($fs, "</table>\n");
			fwrite($fs, "</center>\n");
			fwrite($fs, "</div>\n");
			fwrite($fs, "</body>\n");
			fwrite($fs, "</html>\n");
			fclose($fs);
		}
		else
			return 0;
		return 1;
	}
	
	function show_homepage( ) {
		global $course_id, $user_id, $name, $sex, $job, $email, $blood, $star, $interest, $skill, $intro, $color, $nickname, $year, $month, $day, $tel, $addr, $pageKind, $uurl;
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		$tpl->define ( array ( body => "stu_page.tpl" ) );
		$name2 = stripslashes( $name );
		$nickname2 = stripslashes( $nickname );
		$interest2 = stripslashes( $interest );
		$skill2 = stripslashes( $skill );
		$intro2 = stripslashes( $intro );
		$email2 = stripslashes( $email );
		$job2 = stripslashes( $job );
		$tpl->assign( NAME, $name2 );
		$tpl->assign( NICK, $nickname2 );
		$tpl->assign( USER, $user_id );
		$tpl->assign( YEAR, $year );
		$tpl->assign( MONTH, $month );
		$tpl->assign( TEL, $tel );
		$tpl->assign( ADDR, $addr );
		$tpl->assign( DAY, $day );
		$tpl->assign( PAGEKIND, $pageKind );
		$tpl->assign( UURL, $uurl );
		$tpl->assign( SEN , $sex );
		if ( $sex == 1 )
			$tpl->assign( SEX , "�k" );
		else
			$tpl->assign( SEX , "�k" );

		if($job == NULL)
			$tpl->assign( JOB , "N/A" );
		else
			$tpl->assign( JOB , $job2 );

		$tpl->assign( EMAIL , $email2 );
		$tpl->assign( BLOON , $blood );
		if($blood == 0)
			$tpl->assign( BLOOD , "N/A" );
		else if($blood ==1)
			$tpl->assign( BLOOD , "O��" );
		else if($blood ==2)
			$tpl->assign( BLOOD , "A��" );
		else if($blood ==3)
			$tpl->assign( BLOOD , "B��" );
		else if($blood ==4)
			$tpl->assign( BLOOD , "AB��" );
		else
			$tpl->assign( BLOOD , "Error" );
		$tpl->assign( STAN , $star );
		if($star == "00")
			$tpl->assign( STAR , "N/A" );
		else if($star == "01")
			$tpl->assign( STAR , "�d�Ϯy" );
		else if($star == "02")
			$tpl->assign( STAR , "�����y" );
		else if($star == "03")
			$tpl->assign( STAR , "���l�y" );
		else if($star == "04")
			$tpl->assign( STAR , "���ɮy" );
		else if($star == "05")
			$tpl->assign( STAR , "��l�y" );
		else if($star == "06")
			$tpl->assign( STAR , "�B�k�y" );
		else if($star == "07")
			$tpl->assign( STAR , "�ѯ��y" );
		else if($star == "08")
			$tpl->assign( STAR , "���Ȯy" );
		else if($star == "09")
			$tpl->assign( STAR , "�g��y" );
		else if($star == "10")
			$tpl->assign( STAR , "�]�~�y" );
		else if($star == "11")
			$tpl->assign( STAR , "���~�y" );
		else if($star == "12")
			$tpl->assign( STAR , "�����y" );
		else
			$tpl->assign( STAR , "Error" );

		$tpl->assign( CLR , $color);

		$content = $interest2;
		$tpl->assign( INTERESN , $interest2 );
		$content = str_replace ( "\n", "<BR>", $content );
		$tpl->assign( INTEREST , $content );
		$content = $skill2;
		$tpl->assign( SKILN , $skill2 );
		$content = str_replace ( "\n", "<BR>", $content );
		$tpl->assign( SKILL , $content );
		$content = $intro2;
		$tpl->assign( INTRN , $intro2 );
		$content = str_replace ( "\n", "<BR>", $content );
		$tpl->assign( INTRO , $content );

		$tpl->parse( BODY, "body" );
		$tpl->FastPrint("BODY");
	}
?>
