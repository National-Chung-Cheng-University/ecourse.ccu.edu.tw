<?php
	/* ------------------------------------------------------ */
	/* assistantpub.php                                       */
	/* Written by carlyle.                                    */
	/* ------------------------------------------------------ */
	
	require 'fadmin.php';
	update_status ("�o�G�ݨ�");
		
	if (isset($PHPSESSID) && session_check_stu($PHPSESSID) ) //�ק�i�Ӫ�����
	{
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD,$user_id;
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			show_page( "not_access.tpl" ,"��Ʈw�s�����~!!" );
		}
	
		if ($action == "publish") {
			$range = timecount($ed_year,$ed_month,$ed_day,23,59,$sel_year,$sel_month,$sel_day,$sel_hour,$sel_minute);
			if ($range <= 0)
				$message = "\"�����ɶ�\" �]�w���~!!";
			else {
				if ($publictype == 'showname') //�O�W
					$is_showname = '1';
				else //���O�W
					$is_showname = '0';
			
				$beg_time=$sel_year.'-'.$sel_month.'-'.$sel_day.' '.$sel_hour.':'.$sel_minute.':'."00";
				$end_time=$ed_year.'-'.$ed_month.'-'.$ed_day.' 23:59:59';
					
				if ($sure == "�����o�G" || $sure == "Not Public") //�����o�G
					$Q1 = "UPDATE questionary SET is_showname='$is_showname',is_public='0' WHERE id='$q_id'";
				else if ($pub == 0) //�����o�G,���O��]�w������ɶ�
					$Q1 = "UPDATE questionary SET beg_time='$beg_time',end_time='$end_time',is_showname='$is_showname',is_public='0' WHERE id='$q_id'";
				else if ($pub == 1) //�ߧY�o�G
					$Q1 = "UPDATE questionary SET beg_time='$beg_time',end_time='$end_time',is_showname='$is_showname',is_public='1' WHERE id='$q_id'";
	
				if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
					show_page( "not_access.tpl" ,"��Ʈw��s���~!!" );
				} else {
					if ( $version == "C" )
						$message = "�]�w����!!";
					else
						$message = "Setup Complete!!!";
				}
			}
		}
		
		pubquestionary();
	} else
		die('�v�����~!');


	function pubquestionary () {
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $q_id, $version, $course_id, $message, $user_id;
		
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			show_page( "not_access.tpl" ,"��Ʈw�s�����~!!" );
		}
	
		$Q1 = "select name FROM user where id = '$user_id'";
		if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
			$message = "$message - ��ƮwŪ�����~!!";
		} else {
			$row1 = mysql_fetch_array($result);
			$tmp_groupname = $row1[0]; //�t�ҦW��
		}
	
		$Q1 = "select group_name,beg_time,end_time,is_public,id,is_showname FROM questionary where group_name = '$tmp_groupname'";
		if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
			$message = "$message - ��ƮwŪ�����~!!";
		} else {
			if (!mysql_num_rows($result)) { //�L�Өt���
				$Q1 = "SELECT id FROM `questionary` ORDER BY id DESC LIMIT 0, 1"; //get last id
				if (!($result_tmp = mysql_db_query($DB,$Q1))) {
					$message = "$message - ��ƮwŪ�����~!!";
				} else { //�۰ʷs�W�@��
					$row_tmp = mysql_fetch_array($result_tmp);
					$lastid = $row_tmp[0] + 1;
					$Q1 = "INSERT INTO questionary 
	(id,group_name,beg_time,end_time,is_showname,is_public) VALUES('$lastid','$tmp_groupname','0000-00-00 00:00:00','0000-00-00 00:00:00','1','0')";	
					if (!($result_tmp = mysql_db_query($DB,$Q1)))
						$message = "$message - ��ƮwŪ�����~!!";
					else {
						$q_id = $lastid;
						$is_showname = '1';
					}
				}
			} else {
				$row1 = mysql_fetch_array($result);
				$q_id = $row1[4];
				$is_showname = $row1[5];
			}
		}
	
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate("./templates");
		$tpl->define(array(main=>"assistantpub.tpl"));
		$tpl->define_dynamic("yy","main");
		$tpl->define_dynamic("ye","main");
	
		$tpl->assign(QNAME,$tmp_groupname);
		$tpl->assign(QID,$q_id);
		
		if ($is_showname == '1') { //�O�W
			$tpl->assign(PUBLICSEL1,"selected");
			$tpl->assign(PUBLICSEL2,"");
		} else { //���O�W
			$tpl->assign(PUBLICSEL1,"");
			$tpl->assign(PUBLICSEL2,"selected");
		}
		
		if ($row1[3] != "1")
		{  
			if($version == "C")  
				$tpl->assign(STATUS,"���o�G");
			else
				$tpl->assign(STATUS,"Never Public");
			$end_day = date("d");
			$end_month = date("m");
		}
	
		$beg_y = (int) substr($row1[1],0,4);
		$beg_mo = (int) substr($row1[1],5,2);
		$beg_d = (int) substr($row1[1],8,2);
		$beg_h = (int) substr($row1[1],11,2);
		$beg_m = (int) substr($row1[1],14,2);
		$end_y = (int) substr($row1[2],0,4);
		$end_mo = (int) substr($row1[2],5,2);
		$end_d = (int) substr($row1[2],8,2);
		$end_h = (int) substr($row1[2],11,2);
		$end_m = (int) substr($row1[2],14,2);
		$end_day = substr($row1[2],8,2);
		$end_month = substr($row1[2],5,2);
		$range = timecount($end_y, $end_mo, $end_d, $end_h, $end_m, $beg_y, $beg_mo, $beg_d, $beg_h, $beg_m);
	
		if ( $row1[3] == 1 ) {
			if($version == "C" )
				$tpl->assign(STATUS,date("Y-m-d H:i",mktime(substr($row1[1],11,2),substr($row1[1],14,2),0,substr($row1[1],5,2),substr($row1[1],8,2),substr($row1[1],0,4)))."�o�G  ".date("Y-m-d H:i",mktime(substr($row1[2],11,2),substr($row1[2],14,2),0,substr($row1[2],5,2),substr($row1[2],8,2),substr($row1[2],0,4)))."����");
			else
				$tpl->assign(STATUS,date("Y-m-d H:i",mktime(substr($row1[1],11,2),substr($row1[1],14,2),0,substr($row1[1],5,2),substr($row1[1],8,2),substr($row1[1],0,4)))."Public, End at".date("Y-m-d H:i",mktime(substr($row1[2],11,2),substr($row1[2],14,2),0,substr($row1[2],5,2),substr($row1[2],8,2),substr($row1[2],0,4))));
		}
	
		for($j=0;$j < 3;$j++)
		{
			$year = date("Y")+$j;
			if ( $year == substr( $row1[1], 0, 4 ) )
				$tpl->assign(YEAV, "$year selected");
			else
				$tpl->assign(YEAV,$year);
			$tpl->assign(YEAR,$year);
			$tpl->parse(YY,".yy");
		}
		for($j=0;$j < 3;$j++)
		{
			$year = date("Y")+$j;
			if ( ($j == 2 && $row1[3] != 1 && $row1[3] != 3) || ($year == substr( $row1[2], 0, 4 ) && $row1[3] == 1)  )
				$tpl->assign(YEAEV, "$year selected");
			else
				$tpl->assign(YEAEV,$year);
			$tpl->assign(YEAED,$year);
			$tpl->parse(YE,".ye");
		} 
		$beg_day=substr($row1[1],8,2);
		$beg_month=substr($row1[1],5,2);
		$DV = "DA".$beg_day;
		$MV = "MA".$beg_month;
		$tpl->assign($DV, "selected");	
		$tpl->assign($MV , "selected");
		$bh= substr($row1[1],11,2);
		$bm= substr($row1[1],14,2);
		$HV = "HB".$bh;
		$BV = "MB".$bm;
		$tpl->assign($HV, "selected");	
		$tpl->assign($BV, "selected");
		
		$DEV = "DE".$end_day;
		$MDV = "MOE".$end_month;
		$tpl->assign($DEV, "selected");	
		$tpl->assign($MDV , "selected");
		if ( $row1[3] == 1 )
			$tpl->assign(PUB, "checked" );
		$tpl->assign(MESSAGE, $message);
		$tpl->parse(BODY,"main");
		$tpl->FastPrint("BODY");
	}
?>
