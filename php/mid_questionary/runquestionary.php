<?php
require 'fadmin.php';
//print_r($_POST);print_r($_SESSION);
if( isset($PHPSESSID) && ( $check = session_check_teach($PHPSESSID)) )
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id, $message, $user_id;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
		exit;
	}
	$Qthis_semester = "select * from this_semester";
	$resultts = mysql_db_query($DB, $Qthis_semester);
	$rowts = mysql_fetch_array($resultts);
	$year = $rowts['year'];
	$term = $rowts['term'];
	//99.10.15 修改17行,update by Jim 加入ismultiple
	$Q1 = "SELECT a_id, q_id, type, question, selection1, selection2, selection3, selection4, selection5, ismultiple FROM mid_question WHERE q_id='$q_id' and type='3' order by a_id";
	if ( !($result1 = mysql_db_query( $DB, $Q1 ) ) ) {
		show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
		exit;
	}

	$Q3 = "select a_id from user where id = '$user_id'";
	if ( !($result3 = mysql_db_query( $DB, $Q3 ) ) ) {
		show_page( "not_access.tpl" ,"資料庫讀取錯誤1!!" );
		exit;
	}
	$row3 = mysql_fetch_array($result3);
	$aid = $row3['a_id'];

	$Q3 = "insert into mid_ans ( year, term, q_id, student_id ";
	$Q32 = ") values ( '$year', '$term', '$q_id', '$aid' ";
	
	$qno = 1;
	include("class.FastTemplate.php3");
	$tpl=new FastTemplate("./templates");
	$tpl->define(array(main=>"runquestionary.tpl"));
	if($version == "C") {
		$tpl->assign(IMG,"img");
	}
	else {
		$tpl->assign(IMG,"img_E");
	}
	$tpl->define_dynamic("rows","main");
	while ( $row1 = mysql_fetch_array($result1) ) {
		$tpl->assign(QNO,"");
		$tpl->assign(QUESTION,$row1['question']);
		$tpl->assign(QGRADE,"");
		$tpl->assign(TYPE,"");
		$tpl->parse(ROWS,".rows");
		//99.10.15 修改52行,update by Jim 加入ismultiple
		$Q2 = "SELECT a_id, q_id, type, question, selection1, selection2, selection3, selection4, selection5, ismultiple FROM mid_question WHERE q_id='$q_id' and block_id='".$row1['a_id']."' and type != '3' order by a_id";
		if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫讀取錯誤2!!" );
		}
		if ( mysql_num_rows($result2) != 0 ) {
			while ( $rows = mysql_fetch_array($result2) ) {
				$tpl->assign(QUESTION,$rows['question']);
				$tpl->assign(QNO,$qno);
				if ( $rows['type'] == 1 )
				{
					if($rows['ismultiple'] == "0") {
						if($version == "C") {
							$tpl->assign(TYPE,"單選題");
						}
						else {
							$tpl->assign(TYPE,"Single-select");
						}
						$tpl->define(array(cont=>"showquestionarys.tpl"));
						$tpl->define_dynamic("row","cont");
							
						for ( $i = 0 ; $i < 5 ; $i ++ )
						{
							$j = $i + 1;
							if($i == 0)
								$temp = $i + 5;
							else if($i == 1)
								$temp = $i + 3;
							else if($i == 2)
								$temp = $i + 1;
							else if($i == 3)
								$temp = $i - 1;
							else if($i == 4)
								$temp = $i - 3;
								
							$sele = "selection".$j;
							if ( $rows["$sele"] != null || $rows["$sele"] != "" )
							{
								$tpl->assign(ORDER, $temp);
								$tpl->assign(VALUE, $j);
								$tpl->assign(QUES, $rows["$sele"]);
								$check = "selection_".$qno;
								$checkbox = $$check;
								if ( $checkbox == $j )
									$tpl->assign(SEL,"checked");
								else
									$tpl->assign(SEL,"");
								$tpl->parse(CONT,".row");
							}
						}
						$Q3 = $Q3.", q".$qno;
						$Q32 = $Q32.", '$checkbox'";
					}
					else {
						if($version == "C") {
							$tpl->assign(TYPE,"複選題");
						}
						else {
							$tpl->assign(TYPE,"Multi-select");
						}
						$tpl->define(array(cont=>"showquestionarym.tpl"));
						$tpl->define_dynamic("row","cont");
						for ( $i = 0 ; $i < 5 ; $i ++ ) {
							$j = $i + 1;
							$sele = "selection".$j;
							$check = $sele."_".$qno;
							if ( $rows["$sele"] != null || $rows["$sele"] != "" ) {
								$tpl->assign(ORDER, $j);
								$tpl->assign(QUES, $rows["$sele"]);
								if ( $$check == $j )
									$tpl->assign(SEL,"checked");
								else
									$tpl->assign(SEL,"");
								$tpl->parse(CONT,".row");
							}
						}
						
						$checkbox=0;
						$check1="selection1_".$qno;
						if($$check1 == "1") {
							$checkbox=$checkbox + 1;
						}
						$check2="selection2_".$qno;
						if($$check2 == "2") {
							$checkbox=$checkbox + 2;
						}
						$check3="selection3_".$qno;
						if($$check3 == "3") {
							$checkbox=$checkbox + 4;
						}
						$check4="selection4_".$qno;
						if($$check4 == "4") {
							$checkbox=$checkbox + 8;
						}
						$check5="selection5_".$qno;
						if($$check5 == "5") {
							$checkbox=$checkbox + 16;
						}
						
						$Q3 = $Q3.", q".$qno;
						$Q32 = $Q32.", '$checkbox'";						
					}
					$tpl->assign(S1,$rows['selection1']);
					$tpl->assign(S2,$rows['selection2']);
					$tpl->assign(S3,$rows['selection3']);
					$tpl->assign(S4,$rows['selection4']);
					$tpl->assign(S5,$rows['selection5']);

				}
				else {
					$tpl->define(array(cont=>"showquestionaryf.tpl"));
					if($version == "C")
						$tpl->assign(TYPE,"簡答題");
					else
						$tpl->assign(TYPE,"Q&A");

					$sele = "selection1_".$qno;
					$tpl->assign(TEXTAREA,$$sele);

					$Q3 = $Q3.", q".$qno;
					$Q32 = $Q32.", '".$$sele."'";
				}
				$tpl->parse(ROWS,".rows");
				$tpl->parse(ROWS,".cont");
				$tpl->row = "";
				$tpl->CONT = "";
				$tpl->cont = "";
				$qno ++;
			}
		}
	}
	//2010.11.09 add by JIM 加入183~188行,做為判斷是否答題使用
	$ans_arry = explode( ',' , $Q32 );
	//echo $ans_arry[4]."-".$ans_arry[5]."-".$ans_arry[6]."-".$ans_arry[7]."-".$ans_arry[8]."-".$ans_arry[9]."-".$ans_arry[10];
	if ( trim($ans_arry[4]) == "'0'" or trim($ans_arry[5]) == "'0'" or trim($ans_arry[6]) == "'0'" or trim($ans_arry[7]) == "''" or trim($ans_arry[8]) == "''" or trim($ans_arry[9]) == "''" or trim($ans_arry[10]) == "''" )
	{
		show_page( "not_access.tpl" ,"你尚有題目未做答，請重新輸入，謝謝您!!!");
		exit;
	}
	
	if ( $teacher != 1 ) {
		$Q4 = "select q.end_time from mid_subject q where q.a_id = '$q_id'";
		($result4 = mysql_db_query( $DB, $Q4 ) ) or die ("資料庫讀取錯誤!!Q4");
		$row4 = mysql_fetch_array($result4);
		$end_y = (int) substr($row4['end_time'],0,4);
		$end_mo = (int) substr($row4['end_time'],5,2);
		$end_d = (int) substr($row4['end_time'],8,2);
		$end_h = (int) substr($row4['end_time'],11,2);
		$end_m = (int) substr($row4['end_time'],14,2);
		$now_y = (int) substr(date("Y-m-d H:i"),0,4);
		$now_mo = (int) substr(date("Y-m-d H:i"),5,2);
		$now_d = (int) substr(date("Y-m-d H:i"),8,2);
		$now_h = (int) substr(date("Y-m-d H:i"),11,2);
		$now_m = (int) substr(date("Y-m-d H:i"),14,2);
		$range = timecount($now_y, $now_mo, $now_d, $now_h, $now_m, $end_y, $end_mo, $end_d, $end_h, $end_m);
		
		$Q5 = "select * from mid_ans where q_id = $q_id and student_id = $aid ";
		$result5 = mysql_db_query( $DB.$course_id, $Q5 );
		if ( mysql_num_rows($result5) != 0 )
		{
			if ( $version == "C")
				$tpl->assign(MESSAGE,"<font color=\"red\">您已經填寫過此問卷所以此次不列入記錄!!!</font>");
			else
				$tpl->assign(MESSAGE,"<font color=\"red\">You have enought fill out, so this won't be recorded!!!</font>");
		}
		else if( $range > 0 && ($row4['end_time'] != "00000000000000") ) {
			if($version == "C")	
				$tpl->assign(MESSAGE,"時間已過,所以此次不列入計算!!!");
			else
				$tpl->assign(MESSAGE,"It's OVER the end time, so this won't be recorded !!!");
		} 
		else if ( $quest != 1 ) {
			$Q32 = $Q32. ")";
			
			if ( !($result3 = mysql_db_query( $DB.$course_id, $Q3.$Q32 ) ) ) {
				var_dump( $Q3.$Q32 );
				exit;
			}
			$tpl->assign(MESSAGE,"以上是你所填寫的資料，謝謝您協助本問卷調查!!");
		}
	}
	else {
		$tpl->assign(MESSAGE,"");
	}
	$tpl->parse(BODY,"main");
	$tpl->FastPrint("BODY");
}
else
{
	if( $version=="C" )
		show_page( "not_access.tpl" ,"你沒有權限使用此功能");
	else
		show_page( "not_access.tpl" ,"You have No Permission!!");
}

function answer($rows)
{
	if($rows['answer'] == "1")
		return "(1)";
	elseif($rows['answer'] == "2")
		return "(2)";
	elseif($rows['answer'] == "3")
		return "(1),(2)";
	elseif($rows['answer'] == "4")
		return "(3)";
	elseif($rows['answer'] == "5")
		return "(1),(3)";
	elseif($rows['answer'] == "6")
		return "(2),(3)";
	elseif($rows['answer'] == "7")
		return "(1),(2),(3)";
	elseif($rows['answer'] == "8")
		return "(4)";
	elseif($rows['answer'] == "9")
		return "(1),(4)";
	elseif($rows['answer'] == "10")
		return "(2),(4)";
	elseif($rows['answer'] == "11")
		return "(1),(2),(4)";
	elseif($rows['answer'] == "12")
		return "(3),(4)";
	elseif($rows['answer'] == "13")
		return "(1),(3),(4)";
	elseif($rows['answer'] == "14")
		return "(2),(3),(4)";
	elseif($rows['answer'] == "15")
		return "(1),(2),(3),(4)";
}
?>
