<?php
/**************************/
/*�ɦW:Contact.php*/
/*����:�p�հ򥻸��*/
/*�����ɮ�:*/
/*************************/
require 'fadmin.php';
update_status ("�p�ո��");

if(!(isset($PHPSESSID) && session_check_teach($PHPSESSID) && check_group ( $course_id, $coopgroup, $coopcaseid ) >= 2 ) )
{
	if( $version=="C" ) {
		show_page( "not_access.tpl" ,"�A�S���v���ϥΦ��\��");
		exit;
	}
	else {
		show_page( "not_access.tpl" ,"You have No Permission!!");
		exit;
	}
}

global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;
$Q1 = "Select student_id From coop_".$coopcaseid."_group Where group_num = '$coopgroup'";

if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
	$message = "��Ʈw�s�����~!!";
	show_page( "not_access.tpl" , $message);
	return;
}
else if ( !($resultOBJ = mysql_db_query( $DBC.$course_id, $Q1 ) ) ) {
	$message = "��ƮwŪ�����~!!";
	show_page( "not_access.tpl" , $message);
	return;
}
else if ( mysql_num_rows ( $resultOBJ ) != 0 ) {
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate ( "./templates" );
	if ( $version == "C" ) {
		$tpl->define ( array ( body => "contact.tpl") );
	}
	else {
		$tpl->define ( array ( body => "contact.tpl") );
	}
	
	$tpl->define_dynamic("user_list", "body");
	$i = 0;
	$color = "#CCCCCC";
	while ( $row = mysql_fetch_array ( $resultOBJ ) ) {
		$Q2 = "Select * From user Where id = '".$row['student_id']."'";
		if ( !($resultOBJ2 = mysql_db_query( $DB, $Q2 ) ) ) {
			$message .= "-- ��ƮwŪ�����~!!";
		}
		$row2 = mysql_fetch_array ( $resultOBJ2 );
		$i ++;
		if ( $color == "#CCCCCC" )
			$color = "#F0FFEE";
		else
			$color = "#CCCCCC";
		$tpl->assign( BCOLOR , $color );
		$tpl->assign( "NO", $i);
		$tpl->assign("AID", $row2['a_id']);
		$tpl->assign("NICKN", $row2['nickname']);
		$tpl->assign("NAME", $row2['name']);
		
		$sexIndex = $row2['color'];
		if($sexIndex == "0")
		{
			if ( $version == "C" ) {
		    		$sexStr = "�k";
			}
			else {
		    		$sexStr = "F";
			}
		}
		else if($sexIndex == "1")
		{
			if ( $version == "C" ) {
		    		$sexStr = "�k";
			}
			else {
		    		$sexStr = "M";
			}
		}

		$tpl->assign("SEX", $sexStr);
		if ( $version == "C" ) {
			if($row2['color'] == 1)
				$scolor = "���";
			else if($row2['color'] == 2)
				$scolor = "����";
			else if($row2['color'] == 3)
				$scolor = "�Ŧ�";
			else if($row2['color'] == 4)
				$scolor = "���";
			else
				$scolor = "�m�i";
		}else {
			if($row2['color'] == 1)
				$scolor = "Orange";
			else if($row2['color'] == 2)
				$scolor = "Gold";
			else if($row2['color'] == 3)
				$scolor = "Blue";
			else if($row2['color'] == 4)
				$scolor = "Green";
			else
				$scolor = "Rainbow";
		}
		$tpl->assign("SCOLOR", $scolor);
		$tpl->assign("BIRTH", $row2['birthday']);
		$tpl->assign("JOB", $row2['job']);
		$tpl->assign("TEL", $row2['tel']);
		$tpl->assign("ADDR", $row2['addr']);
		if ( $row2['php'] == "" || $row2['php'] == NULL ) {
			$tpl->assign("ID", $row2['id']);
		}
		else {
			$tpl->assign("ID", "<a href=".$row2['php']." target=_blank>".$row2['id']."</a>");
		}
		$tpl->assign("EMAIL", $row2['email']);
		$tpl->parse(ROW, ".user_list");
	}
	$tpl->parse(BODY,"body");
	$tpl->FastPrint("BODY");
	
}
else {
	if( $version=="C" )
		show_page( "not_access.tpl" ,"�ثe�L����խ����");
	else
		show_page( "not_access.tpl" ,"No data now!!");
}
?>
