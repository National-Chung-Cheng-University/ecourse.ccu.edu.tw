<?php
require 'fadmin.php';

if( isset($PHPSESSID) && ( $check = session_check_teach($PHPSESSID)) )
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
		exit;
	}
	
	$Q1 = "SELECT a_id, q_id, type, question, selection1, selection2, selection3, selection4, selection5, ismultiple, grade FROM qtiku WHERE q_id='$q_id' and type='3' order by a_id";
	
	if ( !($result1 = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
		show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
		exit;
	}
	
	$Q2 = "select is_named from questionary where a_id = '$q_id'";
	if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
		show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
		exit;
	}
	$row2 = mysql_fetch_array($result2);
	$Q3 = "select a_id from user where id = '$user_id'";
	if ( !($result3 = mysql_db_query( $DB, $Q3 ) ) ) {
		show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
		exit;
	}
	$row3 = mysql_fetch_array($result3);
	$aid = $row3['a_id'];
	if ( $row2['is_named'] == 1 ) {
		$Q3 = "insert into questionary_$q_id ( q_id, student_id";
		$Q32 = ") values ( '$q_id', '$aid'";
	}
	else {
		$Q3 = "insert into questionary_$q_id ( q_id";
		$Q32 = ") values ( $q_id ";
	}

	$qno = 1;
	$unfinished = 1; $someempty = 0;
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
		$Q2 = "SELECT a_id, q_id, type, question, selection1, selection2, selection3, selection4, selection5, ismultiple, grade FROM qtiku WHERE q_id='$q_id' and block_id='".$row1['a_id']."' and type != '3' order by a_id";
		
		if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
		}	
		if ( mysql_num_rows($result2) != 0 ) {
			while ( $rows = mysql_fetch_array($result2) ) {
				$qcounter=0;
				$tpl->assign(QUESTION,$rows['question']);
				$tpl->assign(QNO,$qno);
				if ( $rows['type'] == 1 ) {
					if($rows['ismultiple'] == "0") {
						if($version == "C") {
							$tpl->assign(TYPE,"單選題");
						}
						else {
							$tpl->assign(TYPE,"Single-select");
						}
						$tpl->define(array(cont=>"showquestionarys.tpl"));
						$tpl->define_dynamic("row","cont");
						
						for ( $i = 0 ; $i < 5 ; $i ++ ) {
							$j = $i + 1;
							$sele = "selection".$j;
							if ( $rows["$sele"] != null || $rows["$sele"] != "" ) {
								$tpl->assign(ORDER, $j);
								$tpl->assign(VALUE,pow(2,$i));
								$tpl->assign(QUES, $rows["$sele"]);
								$check = "selection_".$qno;
								$checkbox = $$check;
//modified by jimmykuo @ 20100702
//確認是否有題目的選項未填, 若qcounter=0表示某題的選項未填
								if ( $checkbox == pow(2, $i) ){
									$tpl->assign(SEL,"checked");
									$qcounter=1;
								}
								else
									$tpl->assign(SEL,"");
								$tpl->parse(CONT,".row");
							}
						}
						if( $qcounter == 0 ){
							echo "<font color=red>第".$qno."題尚未填寫完成，請重新填寫</font><br>";
							$unfinished = 1;
							$someempty = 1;
							//echo "<font color=red>有空格 ".$unfinished.$someempty."</font><br>"; 
							

						}
						else{
							if (  $someempty == 0 )	
								$unfinished = 0;
							// echo "<font color=red>都填完了". $unfinished.$someempty."</font><br>";

							$Q3 = $Q3.", q".$rows['a_id'];
							$Q32 = $Q32.", '$checkbox'";
						}
//end modified
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
//modified by jimmykuo @ 20100702
//確認是否有題目的選項未填, 若qcounter=0表示某題的選項未填
//modified by yuwan @20110122
//新增$someempty記錄有空白題目未填寫
								if ( $$check == $j ){
									$tpl->assign(SEL,"checked");
									$qcounter=1;
								}
								else
									$tpl->assign(SEL,"");
								$tpl->parse(CONT,".row");
							}
						}
						if( $qcounter == 0){
							echo "<font color=red>第".$qno."題尚未填寫完成，請重新填寫</font><br>";
							$unfinished = 1;
							$someempty = 1;
						}
						else{
							if ( $someempty == 0 )
                                                                $unfinished = 0;
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
						
							$Q3 = $Q3.", q".$rows['a_id'];
							$Q32 = $Q32.", '$checkbox'";
						}
					}
					$tpl->assign(S1,$rows['selection1']);
					$tpl->assign(S2,$rows['selection2']);
					$tpl->assign(S3,$rows['selection3']);
					$tpl->assign(S4,$rows['selection4']);
//end modified
				}else {
					$unfinished=0;
					$tpl->define(array(cont=>"showquestionaryf.tpl"));
					if($version == "C")
						$tpl->assign(TYPE,"問答題");
					else
						$tpl->assign(TYPE,"Q&A");

					$sele = "selection1_".$qno;
					$tpl->assign(TEXTAREA,$$sele);

					$Q3 = $Q3.", q".$rows['a_id'];
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
	if ( $teacher != 1 ) {
		$Q4 = "select q.end_time, tq.count , q.is_once from questionary q, take_questionary tq where q.a_id = '$q_id' and tq.q_id = q.a_id and tq.student_id = '$aid'";
		
		($result4 = mysql_db_query( $DB.$course_id, $Q4 ) ) or die ("資料庫讀取錯誤!!Q4");
		$row4 = mysql_fetch_array($result4);
		
		$end_y = (int) substr($row4['end_time'],0,4);
		$end_mo = (int) substr($row4['end_time'],5,2);
		$end_d = (int) substr($row4['end_time'],8,2);
		$end_h = (int) substr($row4['end_time'],11,2);
		$end_m = (int) substr($row4['end_time'],14,2);
		$now_y = (int) substr(date("YmdHi"),0,4);
		$now_mo = (int) substr(date("YmdHi"),4,2);
		$now_d = (int) substr(date("YmdHi"),6,2);
		$now_h = (int) substr(date("YmdHi"),8,2);
		$now_m = (int) substr(date("YmdHi"),10,2);
		$range = timecount($now_y, $now_mo, $now_d, $now_h, $now_m, $end_y, $end_mo, $end_d, $end_h, $end_m);

		if ( $row4['is_once'] <= $row4['count'] ) {
			if($version == "C")
				$tpl->assign(MESSAGE,"您已經填寫足夠問卷所以此次不列入記錄!!!");
			else
				$tpl->assign(MESSAGE,"You have enought fill out, so this won't be recorded!!!");
		}
		else if( $range > 0 && ($row4['end_time'] != "0000-00-00 00:00:00") ) {
			if($version == "C")	
				$tpl->assign(MESSAGE,"時間已過,所以此次不列入計算!!!");
			else
				$tpl->assign(MESSAGE,"It's OVER the end time, so this won't be recorded !!!");
//modified by jimmykuo @ 20100702
//若qcounter=0表示有某題的選項未填,因此不會把作答紀錄寫入資料庫中,作答次數也不會更新
		} else if ( $quest != 1 && $unfinished==0) {
			$Q32 = $Q32. ")";
			$Q4 = "update take_questionary set count = count+1 where student_id = '$aid' and q_id = '$q_id'";
			if ( !($result3 = mysql_db_query( $DB.$course_id, $Q3.$Q32 ) ) ) {
				var_dump( $Q3.$Q32 );
				exit;
			}
			if ( !($result4 = mysql_db_query( $DB.$course_id, $Q4 ) ) ) {
				var_dump( $Q4 );
				exit;
			}
		}
//end modified
		$tpl->assign(MESSAGE,"");
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
