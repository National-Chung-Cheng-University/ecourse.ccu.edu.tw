<?
//  �H�ɶ� �@���P�_���ϥΪ̬O�_�b�u�W���̾�
//  �C������php�|reload�@��, ���ɷ|�N�W�L6�����^�����ϥΪ̮���
//  �ɮ׮榡��   $user_id, date("U") ,$course_id
//	8/5 �[�Jlog
//  �|�ϥΨ�session����$time.

	require 'common.php';
	if ( !(isset($PHPSESSID) && $user_type = session_check_teach($PHPSESSID)) ) {
		show_page( "not_access.tpl" ,"�v�����~");
		exit;
	}

	$ip = getenv ( "REMOTE_ADDR" );
	if ( $ip == "" )
		$ip = $HTTP_X_FORWARDED_FOR;
	if ( $ip == "" )
		$ip = $REMOTE_ADDR;

	//$refreshmin = 1.5;
	$refreshmin = 6;   //heater 0622
	$countnow = 0;
	$countcourse = 0;

	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $skinnum;
	$Q1 = "delete from online where (".date("U")." - time) > ($refreshmin * 60)";
	$Q2 = "select a_id, idle from online where user_id = '$user_id' and host = '$ip'";
	$Q3 = "select count(a_id) as sum from online";
	$Q4 = "select count(a_id) as sum from online where course_id = '$course_id' group by course_id";
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		echo( "��Ʈw�s�����~!!" );
		exit;
	}
//	echo $Q1;
//	mysql_db_query( $DB, $Q1 );
	if ( $result2 = mysql_db_query( $DB, $Q2 ) ) {
		if ( mysql_num_rows( $result2 ) == 0 ) {
			$Q22 = "insert into online ( course_id, user_id, host, time, idle ) values ('$course_id', '$user_id', '$ip', '".date("U")."', '".date("U")."')";
			$stime = date("U");
		}
		else {
			$row_online = mysql_fetch_array( $result2 );
			if ( $move == 1 ) {
				$Q22 = "update online set course_id='$course_id', time='".date("U")."', host='$ip', idle='".date("U")."' where a_id = '".$row_online['a_id']."'";
				$stime = date("U");
			}
			else {
				$Q22 = "update online set course_id='$course_id', time='".date("U")."', host='$ip' where a_id = '".$row_online['a_id']."'";
				$stime = $row_online['idle'];
			}
		}
		mysql_db_query( $DB, $Q22 );
	}
	if ( $user_id != "guest" ) {
		$Q24 = "select a_id ,forbear from user where id = '$user_id'";
		if ( $result24 = mysql_db_query( $DB, $Q24 ) )
			$row24 = mysql_fetch_array( $result24 );
		if ( $move == 1 ) {
			if ( (date("U") - $respone) > 5 ) {
				
				if ( $row24['forbear'] <= 180 )
					$forb = 180;
				else
					$forb = $row24['forbear'] - 30;
			}
			else if( (date("U") - $respone) > 300 ) {
				session_destroy($PHPSESSID);
				exit;
			}
			else
				$forb = $row24['forbear'] + 30;
			$Q23 = "update user set forbear = $forb where id = '$user_id'";
			mysql_db_query( $DB, $Q23 );
			$row24['forbear'] = $forb;
		}

		// ���d�Xuser��a_id
		$sql = "select a_id from user where id='$user_id'";
		$result = mysql_db_query($DB, $sql);
		$row = mysql_fetch_array($result);
		$aid = $row["a_id"];
	
		// �g�Jlog.
		$usedtime = date("U") - $time;
		$time = date("U");
		add_log ( 7, $user_id, "" , $course_id, $usedtime );
		$receive = show_message ( $aid, 1 );
	}
	if ( $result3 = mysql_db_query( $DB, $Q3 ) ) {
		$row3 = mysql_fetch_array( $result3 );
		$countnow = $row3['sum'];
	}
	if ( $result4 = mysql_db_query( $DB, $Q4 ) ) {
		$row4 = mysql_fetch_array( $result4 );
		$countcourse = $row4['sum'];
	}

	//���m�q�i

	if ( $user_type == 1 ) {
		$sysmeg[0] = "�٦b�Υ\��?\\n�[�o�r!!";
		$sysmeg[1] = "�ù��ݤ[�F�n����!!\\n�ﲴ������n^^";
		$sysmeg[2] = "�u�n!�A�٦b!!\\n�V�O�Υ\�a";
		$sysmeg[3] = "�A�w�g�W���ܤ[�F!!\\n�u�O�n�ǥ�!!";
		$sysmeg[4] = "�ù��ݤ[�F!!\\n�O�o�_�Ө����r!!";
		$sysmeg[5] = "���[�F!!\\n�O�o�_�Ӱ�����ާr!!";
		$sysmeg[6] = "�Ѯ�N!!\\n�O�o�h�[�Ǧ�A�r!!";
		$sysmeg[7] = "��ѷ|���|�ܱI��r!!\\n�S���Y�ڭ̻P�A�P�b!!";
		$sysmeg[8] = "�A�ݦ��y�P ������!!\\n�F�A��^^ �u�O�ݬݧA���S���M��!!";
		$sysmeg[9] = "���ѤӶ��x�x��\\n���X�h���ΤӶ���!!";
		$sysmeg[10] = "�A���U�h�n�o�`��!!\\n�ְ_�Ӱʰ�!!";
		$sysmeg[11] = "�H���O�K�����r!!\\n�ѭn�� ����]�n�U�n��!!";
		$sysmeg[12] = "�M�߫״���!!\\n�Цb�����^���T��!!";
	}
	else {
		$sysmeg[0] = "�٦b�u�@��?\\n�[�o�r!!";
		$sysmeg[1] = "�u�@�[�F�n�𮧧r!!\\n�ﲴ������n^^";
		$sysmeg[2] = "�u�n!�A�٦b!!\\n�V�O�a";
		$sysmeg[3] = "�A�w�g�W���ܤ[�F!!\\n�u�O�n�Ѯv!!";
		$sysmeg[4] = "�ù��ݤ[�F!!\\n�O�o�_�Ө����r!!";
		$sysmeg[5] = "���[�F!!\\n�O�o�_�Ӱ�����ާr!!";
		$sysmeg[6] = "�Ѯ�N!!\\n�O�o�h�[�Ǧ�A�r!!";
		$sysmeg[7] = "�u�@�|���|�ܱI��r!!\\n�S���Y�ڭ̻P�A�P�b!!";
		$sysmeg[8] = "���ѤӶ��x�x��\\n���X�h���ΤӶ���!!";
		$sysmeg[9] = "�A���U�h�n�o�`��!!\\n�ְ_�Ӱʰ�!!";
		$sysmeg[10] = "�A�ݦ��y�P ������!!\\n�F�A��^^ �u�O�ݬݧA���S���M��!!";
		$sysmeg[11] = "�H���O�K�����r!!\\n�u�@�n�� ����]�n�U�n��!!";
		$sysmeg[12] = "�M�߫״���!!\\n�Цb�����^���T��!!";
	}

	if ( $row24['forbear'] != NULL )
		$forbear = $row24['forbear'];
	else
		$forbear = 1800;
		
	/*�M�߫״���
	if ( ( (date("U") - $stime) >= $forbear ) && $receive != 1 ) {
		if ( $version == "C" ) {
			$name = "From �t�ΰT��:";
			$message = $sysmeg[12];
			//$message = $sysmeg[(date("U") - $stime)%13];
		}
		else {
			$name = "From System Messager:";
			$message = $sysmeg[12];
			//$message = $sysmeg[(date("U") - $stime)%13];
		}
		$receive = 1;
		$system = 1;
		$move = 1;
	}
	*/
	
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate("./templates");
        
	if($version == "C") {
	   	$tpl->define(array(main => "online.tpl"));
	}
	else {
	   	$tpl->define(array(main => "online_E.tpl"));
	}   	
	$tpl->assign( SKINNUM , $skinnum );
	$tpl->assign("COUNTNOW", $countnow);
	$tpl->assign("COUNTCOURSE", $countcourse);
	$tpl->assign("PHPID", $PHPSESSID);
	if ( $close == 1 )
		$tpl->assign("CLOSE", "");
	else
		$tpl->assign("CLOSE", "//");

	if ( $receive == 1 ) {
		if ( $system != 1 ) {
			$tpl->assign("SYS", "//");
			$tpl->assign("MOVE", "//");
			$tpl->assign("HAVE", "");
			$tpl->assign("USER", $id);
			$tpl->assign("MID", $a_id);
			$tpl->assign("TIME", $posttime);
			$tpl->assign("MULTI", $multi);
			$tpl->assign("MESSAGE", $message);
		}
		else {
			$tpl->assign("SYS", "");
			if ( $move == 1 )
				$tpl->assign("MOVE", "");
			else
				$tpl->assign("MOVE", "//");
			$tpl->assign("HAVE", "//");
			$tpl->assign("RESPONE", date("U"));
			$tpl->assign("SYM", $name."\\n".$message."\\n".$posttime);
		}	
	}
	else {
		$tpl->assign("SYS", "//");
		$tpl->assign("HAVE", "//");
		$tpl->assign("MOVE", "//");
		$tpl->assign("MESSAGE", "");
	}

    $tpl->parse(BODY, "main");
    $tpl->FastPrint(BODY);
?>
