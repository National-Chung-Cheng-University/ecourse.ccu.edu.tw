<?php
require 'fadmin.php';
update_status ("�u�W�ݨ���");

if( isset($PHPSESSID) && (session_check_teach($PHPSESSID)) )
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"��Ʈw�s�����~!!" );
	}
	if($action == "takequestionary")
	{
		$Q1 = "SELECT end_time ,public FROM questionary WHERE a_id='$q_id' and end_time > '".date("Y-m-d H:i:s")."'";
		if ( !($result1 = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"��ƮwŪ�����~!!" );
		}
		if ( mysql_num_rows( $result1 ) != 0 )
		{
			$Q1 = "SELECT a_id, q_id, type, question, selection1, selection2, selection3, selection4, selection5, ismultiple, grade FROM qtiku WHERE q_id='$q_id' and type='3' order by a_id";
			if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
				show_page( "not_access.tpl" ,"��ƮwŪ�����~!!" );
			}
			if ( mysql_num_rows($result) != 0 ) {
				include("class.FastTemplate.php3");
				$tpl = new FastTemplate("./templates");
				$tpl->define(array(main=>"showquestionary.tpl"));
				if($version == "C") {
					$tpl->assign(IMG,"img");
					$tpl->assign(SUBMIT,"ú��ݨ�");
					$tpl->assign(RESET,"���s��g");
				}
				else {
					$tpl->assign(IMG,"img_E");
					$tpl->assign(SUBMIT,"Complete");
					$tpl->assign(RESET,"Reset");
				}
				$tpl->define_dynamic("rows","main");
				$qcounter = 0;
				while ( $rows = mysql_fetch_array($result) ) {
					$tpl->assign(QUESTION,$rows['question']);
					$tpl->assign(TYPE,"");
					$tpl->assign(QGRADE,"");
					$tpl->assign(QNO,"");
					$tpl->parse(ROWS,".rows");
					
					$Q2 = "SELECT q_id, type, question, selection1, selection2, selection3, selection4, selection5, ismultiple, grade FROM qtiku WHERE q_id='$q_id' and block_id='".$rows['a_id']."' and type != '3' order by a_id";
					if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
						show_page( "not_access.tpl" ,"��ƮwŪ�����~!!" );
					}
					while ( $rows2 = mysql_fetch_array($result2) ) {
						$qcounter ++;
						$tpl->assign(QNO,$qcounter);
						$tpl->assign(QUESTION,$rows2['question']);
						$tpl->assign(SEL,"");
						if ( $rows2['grade'] != null || $rows2['grade'] != "" ) {
							if ( $version == "C" )
								$tpl->assign(QGRADE,"(".$rows2['grade']."�v�� ");
							else
								$tpl->assign(QGRADE,"(".$rows2['grade']."Weight ");
						}
						if ( $rows2['type'] == "1" ) {
							if($rows2['ismultiple'] == "0") {
								if($version == "C") {
									$tpl->assign(TYPE,"����D)");
								}
								else {
									$tpl->assign(TYPE,"Single-select)");
								}
								$tpl->define(array(cont=>"showquestionarys.tpl"));
								$tpl->define_dynamic("row","cont");
								for ( $i = 0 ; $i < 5 ; $i ++ ) {
									$j = $i + 1;
									$sele = "selection".$j;
									if ( $rows2["$sele"] != null || $rows2["$sele"] != "" ) {
										$tpl->assign(ORDER, $j);
										$tpl->assign(VALUE,pow(2,$i));
										$tpl->assign(QUES, $rows2["$sele"]);
										$tpl->parse(CONT,".row");
									}
								}
							}
							else {
								if($version == "C") {
									$tpl->assign(TYPE,"�ƿ��D)");
								}
								else {
									$tpl->assign(TYPE,"Multi-select)");
								}
								$tpl->define(array(cont=>"showquestionarym.tpl"));
								$tpl->define_dynamic("row","cont");
								for ( $i = 0 ; $i < 5 ; $i ++ ) {
									$j = $i + 1;
									$sele = "selection".$j;
									if ( $rows2["$sele"] != null || $rows2["$sele"] != "" ) {
										$tpl->assign(ORDER, $j);
										$tpl->assign(QUES, $rows2["$sele"]);
										$tpl->parse(CONT,".row");
									}
								}
							}
						}
						else if ( $rows2['type'] == "2" ) {
							if($version == "C") {
								$tpl->assign(TYPE,"�ݵ��D)");
							}
							else {
								$tpl->assign(TYPE,"Q&A)");
							}
							$tpl->define(array(cont=>"showquestionaryf.tpl"));
							$tpl->assign(TEXTAREA, "");
						}
						//��parse���s��cont���V���ɮ� �S��Ϊk
						$tpl->parse(ROWS,".rows");
						$tpl->parse(ROWS,".cont");
						$tpl->row = "";
						$tpl->CONT = "";
						$tpl->cont = "";
					}
				}
				$tpl->assign(QID,$q_id);
				$tpl->parse(BODY,"main");
	 			$tpl->FastPrint("BODY");
			}
			else {
				if($version == "C")
					$message = "���D�S���D��!!";
				else
					$message = "There is no Question on this Exam!!";
				show_page_d ();
			}
		}
		else
		{
			if( $version=="C" )
				$message = "��g�ɶ��w��,�L�k�i�J����!";
			else
				$message = "Over the test time ! Can't enter the questionary.";
			show_page_d();
		}
	}
	else
		show_page_d();
}
else
{
	if( $version=="C" )
		show_page( "not_access.tpl" ,"�A�S���v���ϥΦ��\��");
	else
		show_page( "not_access.tpl" ,"You have No Permission!!");
}

function show_page_d () {
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $version, $course_id, $message, $user_id;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"��Ʈw�s�����~!!" );
	}
	$Q1 = "SELECT a_id from user where id='$user_id'";
	if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
		show_page( "not_access.tpl" ,"��ƮwŪ�����~!!" );
	}
	$rows = mysql_fetch_array($result);
	$Q2 = "SELECT q.name, q.is_once, q.is_named, q.a_id, q.end_time, q.end_time, tq.count FROM questionary q,take_questionary tq WHERE q.is_once > tq.count and tq.student_id = '".$rows['a_id']."' and q.a_id=tq.q_id AND ( q.public='1' ||  q.end_time != '0000-00-00 00:00:00' ) and q.beg_time <= '".date("Y-m-d H:i:s")."' and q.end_time > '".date("Y-m-d H:i:s")."' ORDER BY q.a_id";
	
	if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
		show_page( "not_access.tpl" ,"��ƮwŪ�����~!!" );
	}
	
	
	if(mysql_num_rows($result2) != 0)
	{
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate("./templates");
		if ( $version == "C" )
			$tpl->define(array(main=>"show_allquestionary.tpl"));
		else
			$tpl->define(array(main=>"show_allquestionary_E.tpl"));
		$tpl->define_dynamic("row","main");
		$count = 0;
		$color == "#BFCEBD";
		while ( $row1 = mysql_fetch_array($result2) )
		{
			if ( $color == "#BFCEBD" )
				$color = "#D0DFE3";
			else
				$color = "#BFCEBD";
			$tpl->assign( COLOR , $color );
			
			$tpl->assign(QNAME,$row1['name']);
			if($version == "C")
			{
				if($row1['is_named'] == 0)
					$tpl->assign(QTYPE,"���O�W");
				else
					$tpl->assign(QTYPE,"�O�W");

				$tpl->assign(QCOUNT,$row1['count']."��");
			}
			else
			{
				if($row1['is_named'] == 0)
					$tpl->assign(QTYPE,"Named");
				else
					$tpl->assign(QTYPE,"No Named");
				$tpl->assign(QCOUNT,$row1['count']."Times");
			}
			$tpl->assign(QID,$row1['a_id']);
			$tpl->assign(END_TIME,substr($row1['end_time'],0,4)."-".substr($row1['end_time'],5,2)."-".substr($row1['end_time'],8,2)." ".substr($row1['end_time'],11,2).":".substr($row1['end_time'],14,2));
			$tpl->parse(ROW,".row");
			$count ++;
		}
		if ( $count == 0 ) {
			$tpl->define(array(main=>"not_access.tpl"));
			if( $version=="C" )
				$message = "�ثe�S������ݨ�!";
			else
				$message = "No Records !";
			$tpl->assign(MES,$message);
			$tpl->assign(RET,"");
		}
		$tpl->assign(MESSAGE,$message);
		$tpl->parse(BODY,"main");
		$tpl->FastPrint("BODY");
	}
	else
	{
		if( $version=="C" )
			show_page( "not_access.tpl" ,"�ثe�L����ݨ�<br>�Τw��g���ݨ�!");
		else
			show_page( "not_access.tpl" ,"No Records !");
	}
}

?>
