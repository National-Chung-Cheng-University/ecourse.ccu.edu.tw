<?php
require 'fadmin.php';
include 'saveStudentExamAnswer.php'; //by carlyle
include 'showStudentExamAnswer.php';

if( isset($PHPSESSID) && ( $check = session_check_teach($PHPSESSID)) )
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}
	$Q1 = "SELECT a_id,type,question,answer,grade,selection1,selection2,selection3,selection4,answer_desc, ismultiple FROM tiku WHERE exam_id='$exam_id' ORDER BY a_id";
	if ( !($result1 = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
		show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
	}
	
	$Q2 = "SELECT e.end_time FROM exam e WHERE e.a_id ='$exam_id'";
	if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
		show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
	}
	$row2 = mysql_fetch_array($result2);
//-------------------------------------------------------------------------	
	$Q3 = "select a_id from user where id='$user_id'";
	if ( !($result3 = mysql_db_query( $DB, $Q3 ) ) ) {
		show_page( "not_access.tpl", "資料庫讀取錯誤!!" );
	}
	$row3 = mysql_fetch_array($result3);
//-------------------------------------------------------------------------

	$end_y = (int) substr($row2[0],0,4);
	$end_mo = (int) substr($row2[0],5,2);
	$end_d = (int) substr($row2[0],8,2);
	$end_h = (int) substr($row2[0],11,2);
	$end_m = (int) substr($row2[0],14,2);
	$now_y = (int) substr(date("YmdHi"),0,4);
	$now_mo = (int) substr(date("YmdHi"),4,2);
	$now_d = (int) substr(date("YmdHi"),6,2);
	$now_h = (int) substr(date("YmdHi"),8,2);
	$now_m = (int) substr(date("YmdHi"),10,2);
	$range = timecount($now_y, $now_mo, $now_d, $now_h, $now_m, $end_y, $end_mo, $end_d, $end_h, $end_m);
	$showans = 0;
	if( $range > 0 && ($row2[1] != "0000-00-00 00:00:00") ) {
		$showans = 1;
	}
	
	$T=0;
	$F=0;
	$grade=0;
	$qno = 1;
	include("class.FastTemplate.php3");
	$tpl=new FastTemplate("./templates");
	$tpl->define(array(main=>"runtest.tpl"));
	if($version == "C") {
		$tpl->assign(IMG,"img");
		$tpl->assign(POINT, "分");
	}	
	else {
		$tpl->assign(IMG,"img_E");
		$tpl->assign(POINT, "Point");
	}

	

	if ($version == "C")
		$tpl->assign(YOUR, "你的答案為:" );
	else
		$tpl->assign(YOUR, "Your Answer is:" );

	$tpl->define_dynamic("rows","main");
	while ( $row1 = mysql_fetch_array($result1) ) {
		$tpl->assign(QNO,$qno);
		$tpl->assign(QUESTION,$row1['question']);
		$tpl->assign(QGRADE,$row1['grade']);
		if ( $row1['type'] == 1 ) {
			if($row1['ismultiple'] == "0") {
				if($version == "C") {
					$tpl->assign(TYPE,"單選題");
				}
				else {
					$tpl->assign(TYPE,"Single-select");
				}
		
				$tpl->define(array(cont=>"showtests.tpl"));

				$iscontinue = 1;
				if ($showans == 1) {
					$rr = showStudentExamAnswer($DB,$link,$course_id,$row1['a_id'],$row3['a_id'],&$tpl,$qno);
					if ($rr == 0) $iscontinue = 0;
				}

				if ($iscontinue == 1) {
					$check1="selection_".$qno;
					$checkbox = $$check1;
					$tpl->assign("SE".$checkbox."_".$qno , "checked" );

					//紀錄學生的答案 by carlyle
					saveStudentExamAnswer($DB,$link,$course_id,$row1['a_id'],$row3['a_id'],$row1['type'],$row1['ismultiple'],$checkbox,'','','');
				}
			}
			else {
				if($version == "C") {
					$tpl->assign(TYPE,"複選題");
				}
				else {
					$tpl->assign(TYPE,"Multi-select");
				}

				$tpl->define(array(cont=>"showtestm.tpl"));

                                $iscontinue = 1;
                                if ($showans == 1) {
                                        $rr = showStudentExamAnswer($DB,$link,$course_id,$row1['a_id'],$row3['a_id'],&$tpl,$qno);
                                        if ($rr == 0) $iscontinue = 0;
                                }			

				if ($iscontinue == 1) {
					$checkbox=0;
					$check1="selection1_".$qno;
					if($$check1 == "1") {
						$checkbox=$checkbox + 1;
						$tpl->assign("SE1_".$qno , "checked" );
					}
					$check2="selection2_".$qno;
					if($$check2 == "2") {
						$checkbox=$checkbox + 2;
						$tpl->assign("SE2_".$qno , "checked" );
					}
					$check3="selection3_".$qno;
					if($$check3 == "3") {
						$checkbox=$checkbox + 4;
						$tpl->assign("SE3_".$qno , "checked" );
					}
					$check4="selection4_".$qno;
					if($$check4 == "4") {
						$checkbox=$checkbox + 8;
						$tpl->assign("SE4_".$qno , "checked" );
					}

					//紀錄學生的答案 by carlyle
					saveStudentExamAnswer($DB,$link,$course_id,$row1['a_id'],$row3['a_id'],$row1['type'],$row1['ismultiple'],$checkbox,'','','');
				}
			}
			if($checkbox == (int) $row1['answer'])
			{
				$T=$T + 1;
				$grade=$grade + $row1['grade'];
				if ( $showans == 1 ) {
					if($version == "C")
						$tpl->assign(RESULT,"答對了!");
					else
						$tpl->assign(RESULT,"Correct! the correct answer(s):");
				}
				else {
					if($version == "C")
						$tpl->assign(RESULT,"");
					else
						$tpl->assign(RESULT,"");
				}
			}
			else
			{
				$ans = answer( $row1 );
				$F=$F + 1;
				if ( $showans == 1 ) {
					if($version == "C")
						$tpl->assign(RESULT,"正確答案為:".$ans);
					else
						$tpl->assign(RESULT,"the correct answer(s):".$ans);
				}
				else {
					if($version == "C")
						$tpl->assign(RESULT,"");
					else
						$tpl->assign(RESULT,"");
				}
			}
			$tpl->assign(S1,$row1['selection1']);
			$tpl->assign(S2,$row1['selection2']);
			$tpl->assign(S3,$row1['selection3']);
			$tpl->assign(S4,$row1['selection4']);
		}
		else if ( $row1['type'] == 2 ) {
			if($version == "C") {
				$tpl->assign(TYPE,"是非題");
				$tpl->assign(NO,"非");
				$tpl->assign(YES,"是");
			}
			else
				$tpl->assign(TYPE,"Yes & No");

			$tpl->define(array(cont=>"showtestyn.tpl"));

			$iscontinue = 1;
			if ($showans == 1) {
				$rr = showStudentExamAnswer($DB,$link,$course_id,$row1['a_id'],$row3['a_id'],&$tpl,$qno);
				if ($rr == 0) $iscontinue = 0;
			}
			
			if ($iscontinue == 1) {
				$check="selection_".$qno;
				$tpl->assign("CH".$$check."_".$qno , "checked" );

				//紀錄學生的答案 by carlyle
				saveStudentExamAnswer($DB,$link,$course_id,$row1['a_id'],$row3['a_id'],$row1['type'],$row1['ismultiple'],$$check,'','','');
			}

			if($$check == $row1['answer'])
			{
				$T=$T + 1;
				$grade=$grade + $row1['grade'];
				if ( $showans == 1 ) {
					if($version == "C")
						$tpl->assign(RESULT,"答對了!");
					else
						$tpl->assign(RESULT,"Correct! the correct answer(s):");
				}
				else {
					if($version == "C")
						$tpl->assign(RESULT,"");
					else
						$tpl->assign(RESULT,"");
				}
			}
			else
			{
				if ( $row1['answer'] == "1" ) {
					if ( $version == "C" )
						$ans = "是";
					else
						$ans = "Yes";
				}
				else {
					if ( $version == "C" )
						$ans = "非";
					else
						$ans = "No";
				}
				$F=$F + 1;
				if ( $showans == 1 ) {
					if($version == "C")
						$tpl->assign(RESULT,"正確答案為:".$ans);
					else
						$tpl->assign(RESULT,"the correct answer(s):".$ans);
				}
				else {
					if($version == "C")
						$tpl->assign(RESULT,"");
					else
						$tpl->assign(RESULT,"");
				}
			}
		}
//-------------------------------------------------------------------
		//devon 問答題
		else if ( $row1['type'] == "4" ) {
			$tpl->define(array(cont=>"showtestqa.tpl"));
			if($version == "C")
				$tpl->assign(TYPE,"問答題");
			else
				$tpl->assign(TYPE,"Q & A");
			//身份是學生才將答案寫入資料庫；是老師，答案就不用寫入資料庫
			if ( $teacher != 1 )
			{/*
				$QAsql1 = "select grade from qa where student_id='".$row3[a_id]."' and exam_id='$exam_id' and item_id='".$row1[a_id]."'";
				if ( !($result = mysql_db_query( $DB.$course_id, $QAsql1 ) ) ) {
					show_page( "not_access.tpl" ,"資料庫寫入錯誤1!!" );
					exit;
				}
				*/
				$qacheck = "qatextarea_".$qno;
				$qatext = $$qacheck;
				
				$Qtest = "select * from qa where student_id='".$row3[a_id]."' and exam_id='$exam_id' and item_id='".$row1[a_id]."'";
				$numq = mysql_num_rows((mysql_db_query($DB.$course_id, $Qtest)));
				if($numq == 0)
				{
				    $row1[question] = addcslashes ($row1[question], "'");
					$QAsql2 = "insert into qa ( item_id, exam_id, student_id, question, answer, grade, grade_limit ) values ( '$row1[a_id]', '$exam_id', '$row3[a_id]', '$row1[question]', '$qatext', '-1', '$row1[grade]' )";
					//echo $QAsql2;
					//die();
					if ( !($result2 = mysql_db_query( $DB.$course_id, $QAsql2 ) ) )
					{
						show_page( "not_access.tpl" ,"資料庫寫入錯誤2!!" );
						exit;
					}
				}
			}
			if ($showans == 1) {
					$Qtest = "select * from qa where student_id='".$row3[a_id]."' and exam_id='$exam_id' and item_id='".$row1[a_id]."'";
					$res = mysql_db_query($DB.$course_id, $Qtest);
					$numq = mysql_num_rows($res);
					if($numq != 0)
					{
						$res1 = mysql_fetch_assoc($res);					
						$qatext = $res1['answer'];
					}
				}
			$tpl->assign(QATEXT, $qatext);
			
			//$tpl->assign(QATEXT, $$qacheck);

			if ( $showans == 1 ) {
				if($version == "C")
					$tpl->assign(RESULT,"正確答案為:\"".$row1[answer]."\"<br>");
				else
					$tpl->assign(RESULT,"the correct answer(s):".$row1[answer]."<br>");
			}
			else {
				if($version == "C")
					$tpl->assign(RESULT,"");
				else
					$tpl->assign(RESULT,"");
			}
		}
//-------------------------------------------------------------------

		else {
			$tpl->define(array(cont=>"showtestf.tpl"));
			$tpl->define_dynamic ( "row", "cont" );
			if($version == "C")
				$tpl->assign(TYPE,"填充題");
			else
				$tpl->assign(TYPE,"fill out");

			$iscontinue = 1;
			if ($showans == 1) {
				$rr = showStudentExamAnswer($DB,$link,$course_id,$row1['a_id'],$row3['a_id'],&$tpl,$qno,$row1['answer']);
				if ($rr == 0) $iscontinue = 0;
			}

			//暫存學生的答案 by carlyle
			$stu_ans_array = array('','','','');

			$ans = "<br>";
			if ( $row1['ismultiple'] == 1 ) {
				$check = 1;
				for ( $i = 1 ; $i <= $row1['answer'] ; $i ++ ) {
					$sele = "selection".$i."_".$qno;
					$answer = "selection".$i;
					$$sele = stripslashes( $$sele );
					$stu_ans_array[$i - 1] = $$sele;
					if ($iscontinue == 1) {
						$tpl->assign(NUM, $i);
						$tpl->assign(ORDER, $i);
						$tpl->assign(VALUE,$$sele);
					}
					if ( $row1[$answer] != $$sele )
						$check = 0;
					$ans .= $row1[$answer]."<br>";
					if ($iscontinue == 1) $tpl->parse(CONT,".row");
				}
			}
			else {
				$check = 0;
				$ansarr;
				for ( $i = 1 ; $i <= $row1['answer'] ; $i ++ ) {
					$sele = "selection".$i."_".$qno;
					$answer = "selection".$i;
					$$sele = stripslashes( $$sele );
					$stu_ans_array[$i - 1] = $$sele;
					if ($iscontinue == 1) {
						$tpl->assign(NUM, $i);
						$tpl->assign(ORDER, $i);
						$tpl->assign(VALUE,$$sele);
					}
					$ans .= "\"".$row1[$answer]."\"<br>";
					$ansarr[$answer]= $row1[$answer];
					if ($iscontinue == 1) $tpl->parse(CONT,".row");
				}
				for ( $i = 1 ; $i <= $row1['answer'] ; $i ++ ) {
					$sele = "selection".$i."_".$qno;
					if ( $$sele == "" ) {
						$check = 0;
						break;
					}
					for ( $j = 1 ; $j <= $row1['answer'] ; $j ++ ) {
						$answer = "selection".$j;
						if ( $ansarr[$answer] == $$sele ) {
							$ansarr[$answer] = "";
							$check ++;
							break;
						}
					}
				}
				if ( $check == $row1['answer'] )
					$check = 1;
			}

			if ($iscontinue == 1) {
				//紀錄學生的答案 by carlyle
				saveStudentExamAnswer($DB,$link,$course_id,$row1['a_id'],$row3['a_id'],$row1['type'],$row1['ismultiple'],$stu_ans_array[0],$stu_ans_array[1],$stu_ans_array[2],$stu_ans_array[3]);
			}

			if( $check == 1 )
			{
				$T=$T + 1;
				$grade=$grade + $row1['grade'];
				if ( $showans == 1 ) {
					if($version == "C")
						$tpl->assign(RESULT,"答對了!");
					else
						$tpl->assign(RESULT,"Correct! the correct answer(s):");
				}
				else {
					if($version == "C")
						$tpl->assign(RESULT,"");
					else
						$tpl->assign(RESULT,"");
				}
			}
			else
			{
				$F=$F + 1;
				if ( $showans == 1 ) {
					if($version == "C")
						$tpl->assign(RESULT,"正確答案為: ".$ans);
					else
						$tpl->assign(RESULT,"the correct answer(s): ".$ans);
				}
				else {
					if($version == "C")
						$tpl->assign(RESULT,"");
					else
						$tpl->assign(RESULT,"");
				}
			}
		}
		if ( $row1['answer_desc'] == "" )
			$tpl->assign(ANSLINK,"");
		else {
			if ( $showans == 1 ) {
				if ( $version == "C" )
					$tpl->assign(ANSLINK,"<a href=".$row1['answer_desc']." target=_blank>本題詳解</a>" );
				else
					$tpl->assign(ANSLINK,"<a href=".$row1['answer_desc']." target=_blank>Detailed Answer</a>" );
			}
			else {
				if ( $version == "C" )
					$tpl->assign(ANSLINK,"" );
				else
					$tpl->assign(ANSLINK,"" );
			}
		}

		$tpl->parse(ROWS,".rows");
		$tpl->parse(ROWS,".cont");
		$tpl->row = "";
		$tpl->CONT = "";
		$tpl->cont = "";
		$qno ++;
	}
	if ( $showans == 1 && $teacher != 1 ) {
		if($version == "C")
			$tpl->assign(GRADE,"");
		else
			$tpl->assign(GRADE,"");
	}
	else if ( $guest == 1 ) {
		$Q2 = "SELECT a_id from user where id='$user_id'";
		if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
		}
		$rows = mysql_fetch_array($result2);
		$Q3 = "UPDATE take_exam SET grade='$grade', nonqa_grade='$grade' WHERE exam_id='$exam_id' AND student_id='".$rows['a_id']."'";
		if ( !($result3 = mysql_db_query( $DB.$course_id, $Q3 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫更新錯誤!!" );
		}
		if($version == "C")
			$tpl->assign(GRADE,"共答對".$T."題"."  答錯".$F."題<br>得分為".$grade."分<br>如果題目有包含問答題，則問答題所佔的分數，將於老師批改完才再加上去。");
		else
			$tpl->assign(GRADE,"Total Correct".$T.";"."  Total Wrong".$F."<br>Total Score:".$grade."point");
	}
	else if ( $teacher != 1 ) {	
		$Q2 = "SELECT a_id from user where id='$user_id'";
		if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
		}
		$rows = mysql_fetch_array($result2);

		$Q2 = "SELECT te.grade, e.end_time, e.percentage FROM exam e,take_exam te WHERE te.student_id = '".$rows['a_id']."' and e.a_id=te.exam_id AND e.a_id ='$exam_id'";
		if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
		}
		$row2 = mysql_fetch_array($result2);
		
		$end_y = (int) substr($row2[1],0,4);
		$end_mo = (int) substr($row2[1],5,2);
		$end_d = (int) substr($row2[1],8,2);
		$end_h = (int) substr($row2[1],11,2);
		$end_m = (int) substr($row2[1],14,2);
		$now_y = (int) substr(date("YmdHi"),0,4);
		$now_mo = (int) substr(date("YmdHi"),4,2);
		$now_d = (int) substr(date("YmdHi"),6,2);
		$now_h = (int) substr(date("YmdHi"),8,2);
		$now_m = (int) substr(date("YmdHi"),10,2);
		
		$range = timecount($now_y, $now_mo, $now_d, $now_h, $now_m, $end_y, $end_mo, $end_d, $end_h, $end_m);
		$now=(int) substr(date("YmdHi"),2,10);
		$end_time=(int) substr($row3[0],2,10);
		if($row2[0] != '-1' && $row2[2] != 0) {
			if($version == "C")
				$tpl->assign(GRADE,"共答對".$T."題"."  答錯".$F."題<br>得分為".$grade."分<BR>您已經接受過測驗所以此次成績不列入計算!!!");
			else
				$tpl->assign(GRADE,"Total Correct".$T.";"."  Total Wrong".$F."<br>Total Score:".$grade."point<BR>You have taken this exam, so the score won't be recorded!!!");
		}
		else if( $range > 0 && ($row2[1] != "0000-00-00 00:00:00") && $row2[2] != 0) {
			if($version == "C")	
				$tpl->assign(GRADE,"共答對".$T."題"."  答錯".$F."題<br>得分為".$grade."分<BR>考試時間已過,所以此次成績不列入計算!!!");
			else
				$tpl->assign(GRADE,"Total Correct".$T.";"."  Total Wrong".$F."<br>Total Score:".$grade."point<BR>It's OVER the end time of exam, so the score won't be recorded !!!");
		}
		else
		{
			$Q3 = "UPDATE take_exam SET grade='$grade', nonqa_grade='$grade' WHERE exam_id='$exam_id' AND student_id='".$rows['a_id']."'";
			if ( !($result3 = mysql_db_query( $DB.$course_id, $Q3 ) ) ) {
				show_page( "not_access.tpl" ,"資料庫更新錯誤!!" );
			}
			/////////////////////////////////////////////////////////////////////////////////////
			//modified by devon 2005-04-18
			//如果老師沒有公佈該次測驗，學生則看不到成績
			$Q4 = "select public from take_exam where exam_id='$exam_id'";
			$result4 = mysql_db_query( $DB.$course_id, $Q4 );
			$rows4 = mysql_fetch_array( $result4 );
			if( $rows4['public'] == 1) {
				if($version == "C")
					$tpl->assign(GRADE,"共答對".$T."題"."  答錯".$F."題<br>得分為".$grade."分<br>如果題目有包含問答題，則問答題所佔的分數，將於老師批改完才再加上去。");
				else
					$tpl->assign(GRADE,"Total Correct".$T.";"."  Total Wrong".$F."<br>Total Score:".$grade."point");
			}else {
				if($version == "C")
					$tpl->assign(GRADE, "完成測驗，等待老師公佈成績!");
				else
					$tpl->assign(GRADE, "You have finished this test, just wait for the grades!");
			}
			/////////////////////////////////////////////////////////////////////////////////////
			/*if($version == "C")
				$tpl->assign(GRADE,"共答對".$T."題"."  答錯".$F."題<br>得分為".$grade."分<br>如果題目有包含問答題，則問答題所佔的分數，將於老師批改完才再加上去。");
			else
				$tpl->assign(GRADE,"Total Correct".$T.";"."  Total Wrong".$F."<br>Total Score:".$grade."point");
			*/
		}
	}
	else {
		if($version == "C")
			$tpl->assign(GRADE,"共答對".$T."題"."  答錯".$F."題<br>得分為".$grade."分<br>如果題目有包含問答題，則問答題所佔的分數，將於老師批改完才再加上去。");
		else
			$tpl->assign(GRADE,"Total Correct".$T.";"."  Total Wrong".$F."<br>Total Score:".$grade."point");
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
