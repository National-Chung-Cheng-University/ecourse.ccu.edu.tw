<?php
require 'fadmin.php';
update_status ("�u�W�Ҹդ�");

if( isset($PHPSESSID) && (session_check_teach($PHPSESSID)) )
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"��Ʈw�s�����~!!" );
	}
	if($action == "takeexam")
	{
		$Q1 = "SELECT end_time ,public FROM exam WHERE a_id='$exam_id' and end_time > ".date("YmdHis");
		if ( !($result1 = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"��ƮwŪ�����~!!" );
		}
		if ( mysql_num_rows( $result1 ) != 0 )
		{
			$Q2 = "SELECT exam_id, type, question, selection1, selection2, selection3, selection4, ismultiple, answer, grade FROM tiku WHERE exam_id='$exam_id' order by a_id";
			if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
				show_page( "not_access.tpl" ,"��ƮwŪ�����~!!" );
			}
			if ( mysql_num_rows($result2) != 0 ) {
				include("class.FastTemplate.php3");
				$tpl = new FastTemplate("./templates");
				$tpl->define(array(main=>"showtest.tpl"));
				if($version == "C") {
					$tpl->assign(IMG,"img");
					$tpl->assign(SUBMIT,"ú��ը�");
					$tpl->assign(RESET,"���s��g");
					$tpl->assign(POINT, "��");
				}
				else {
					$tpl->assign(IMG,"img_E");
					$tpl->assign(SUBMIT,"Complete");
					$tpl->assign(RESET,"Reset");
					$tpl->assign(POINT, "Point");
				}
				$tpl->define_dynamic("rows","main");
				$qcounter = 0;
				while ( $rows = mysql_fetch_array($result2) ) {
					$qcounter ++;
					$tpl->assign(QNO,$qcounter);
					$tpl->assign(QUESTION,$rows['question']);
					$tpl->assign(QGRADE,$rows['grade']);
					if ( $rows['type'] == "1" ) {
						if($rows['ismultiple'] == "0") {
							if($version == "C") {
								$tpl->assign(TYPE,"����D");
							}
							else {
								$tpl->assign(TYPE,"Single-select");
							}
							$tpl->define(array(cont=>"showtests.tpl"));
						}
						else {
							if($version == "C") {
								$tpl->assign(TYPE,"�ƿ��D");
							}
							else {
								$tpl->assign(TYPE,"Multi-select");
							}
							$tpl->define(array(cont=>"showtestm.tpl"));
						}
						$tpl->assign(S1,$rows['selection1']);
						$tpl->assign(S2,$rows['selection2']);
						$tpl->assign(S3,$rows['selection3']);
						$tpl->assign(S4,$rows['selection4']);
					}
					else if ( $rows['type'] == "2" ) {
						$tpl->define(array(cont=>"showtestyn.tpl"));
						if($version == "C") {
							$tpl->assign(TYPE,"�O�D�D");
							$tpl->assign(NO,"�D");
							$tpl->assign(YES,"�O");
						}
						else
							$tpl->assign(TYPE,"Yes & No");
					}
					else {
						$tpl->define(array(cont=>"showtestf.tpl"));
						$tpl->define_dynamic ( "row", "cont" );
						if($version == "C")
							$tpl->assign(TYPE,"��R�D");
						else
							$tpl->assign(TYPE,"fill out");
						for ( $i = 1 ; $i <= $rows['answer'] ; $i ++ ) {
							$sele = "selection".$i."_".$qno;
							$tpl->assign(NUM, $i);
							$tpl->assign(ORDER, $i);
							$tpl->assign(VALUE,$$sele);
							$tpl->parse(CONT,".row");
						}
					}
					//��parse���s��cont���V���ɮ� �S��Ϊk
					$tpl->parse(ROWS,".rows");
					$tpl->parse(ROWS,".cont");
					$tpl->row = "";
					$tpl->CONT = "";
					$tpl->cont = "";
				}
				$tpl->assign(EXAMID,$exam_id);
				$tpl->parse(BODY,"main");
	 			$tpl->FastPrint("BODY");
			}
			else {
				if( $version=="C" )
					show_page( "not_access.tpl" ,"���D�S���D��!!");
				else
					show_page( "not_access.tpl" ,"There is no Question on this Exam!!");
			}
		}
		else
		{
			if( $version=="C" )
				$message = "�Ҹծɶ��w��,�L�k�i�J����!";
			else
				$message = "Over the test time ! Can't enter the test.";
			header ( "Location: ./runtest.php?exam_id=$exam_id" );
//			show_page_d();
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
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $version, $course_id, $message, $user_id, $skinnum, $chap;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"��Ʈw�s�����~!!" );
	}
	$Q1 = "SELECT a_id from user where id='$user_id'";
	if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
		show_page( "not_access.tpl" ,"��ƮwŪ�����~!!" );
	}
	$rows = mysql_fetch_array($result);
//	$Q2 = "SELECT e.name,e.percentage,e.a_id,te.grade,e.end_time, e.end_time FROM exam e,take_exam te WHERE te.student_id = '".$rows['a_id']."' and e.a_id=te.exam_id AND e.is_online='1' AND ( e.public='1' ||  e.end_time != '00000000000000' ) and e.beg_time <= ".date("YmdHis")." and e.end_time > ".date("YmdHis")." ORDER BY e.a_id";
	$Q2 = "SELECT e.name,e.percentage,e.a_id,te.grade,e.end_time, e.end_time FROM exam e,take_exam te WHERE chap_num='$chap' and te.student_id = '".$rows['a_id']."' and e.a_id=te.exam_id AND e.is_online='1' AND ( e.public='1' ||  e.end_time != '00000000000000' ) and e.beg_time <= ".date("YmdHis")." ORDER BY e.name";
	if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
		show_page( "not_access.tpl" ,"��ƮwŪ�����~!!" );
	}
	if(mysql_num_rows($result2) != 0)
	{
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate("./templates");
		if ( $version == "C" )
			$tpl->define(array(main=>"show_alltest.tpl"));
		else
			$tpl->define(array(main=>"show_alltest_E.tpl"));
		$tpl->define_dynamic("row","main");
		$tpl->assign( SKINNUM , $skinnum );
		$count = 0;
		while ( $row1 = mysql_fetch_array($result2) )
		{
			if ( $color == "#F0FFEE" )
				$color = "#E6FFFC";
			else
				$color = "#F0FFEE";
			$tpl->assign( COLOR , $color );
			
			$tpl->assign(TESTNAME,$row1[0]);
			if($version == "C")
			{
				if($row1[1] == 0)
					$tpl->assign(TESTTYPE,"�ۧڵ��q");
				else
					$tpl->assign(TESTTYPE,"��������");
				if($row1[3] != "-1")
					$tpl->assign(GRADE,$row1[3]);
				else
					$tpl->assign(GRADE,"��������");
			}
			else
			{
				if($row1[1] == 0)
					$tpl->assign(TESTTYPE,"Self-Test");
				else
					$tpl->assign(TESTTYPE,"Normal-Test");
				if($row1[3] != "-1")
					$tpl->assign(GRADE,$row1[3]);
				else
					$tpl->assign(GRADE,"Haven't taken exam");
			}
			if ( $row1['end_time'] > date("YmdHis") ) {
				if ( $version != "C" )
					$tpl->assign(INTO, "Take Exam");
				else
					$tpl->assign(INTO, "�i�J����");
			}
			else {
				if ( $version != "C" )
					$tpl->assign(INTO, "Look Ans");
				else
					$tpl->assign(INTO, "�[�ݸѵ�");
			}
			$tpl->assign(CHAP_NUM,$chap);
			$tpl->assign(RATIO,$row1[1]);
			$tpl->assign(EXAMID,$row1[2]);
			$tpl->assign(END_TIME,substr($row1[4],0,4)."-".substr($row1[4],4,2)."-".substr($row1[4],6,2)."-".substr($row1[4],8,2)."-".substr($row1[4],10,2));
			$tpl->parse(ROW,".row");
			$count ++;
		}
		if ( $count == 0 ) {
			$tpl->define(array(main=>"not_access.tpl"));
			if( $version=="C" )
				$message = "�ثe�S���������!";
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
			show_page( "not_access.tpl" ,"�ثe�S���������!");
		else
			show_page( "not_access.tpl" ,"No Records !");
	}
}

?>
