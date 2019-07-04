<?php
require 'fadmin.php';
update_status ("查看成績");

//if(isset($PHPSESSID) && session_check_teach($PHPSESSID) == 2)
//{
	$course_id = $course;
	if ( $action == "update" ) {
		do_update();
	}
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	$buDB = "bugrade";
	
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
		exit;
	}
	//取出該門課的學生
	$Q1 = "SELECT u.a_id, u.id, u.name FROM study.user u, bugrade.take_course tc WHERE u.a_id=tc.student_id AND tc.course_id='$course_id' AND tc.credit = '1' AND tc.year='$year' AND tc.term='$term' ORDER BY u.id";
	if ( !($result1 = mysql_db_query( $DB, $Q1 ) ) ) {
		show_page( "not_access.tpl" ,"資料庫讀取錯誤1!!" );
		exit;
	}
	//判斷是否有學生，如果沒有就不作
	$num1 = mysql_num_rows($result1);
	if ( $num1 != 0 ) {
		$countdata[0][0] = $num1;
		//選出exam　的名稱　百分比　a_id is_online 如果有public 且時間不為0
		$Q2 = "SELECT name,percentage,a_id , is_online FROM exam where ( public = '1' or (end_time != '00000000000000' && beg_time <= ".date("YmdHis")." ) ) AND course_id='$course_id' AND year='$year' AND term='$term' ORDER BY a_id";
		if ( !($result2 = mysql_db_query( $buDB, $Q2 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫讀取錯誤2!!" );
			exit;
		}
		//選出　homework的名稱　百分比　a_id 
		$Q3 = "SELECT name, percentage, a_id FROM homework where (public = '1' or public = '3') AND course_id='$course_id' AND year='$year' AND term='$term' ORDER BY a_id";
		if ( !($result3 = mysql_db_query( $buDB, $Q3 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫讀取錯誤3!!" );
			exit;
		}
/*		$Q31 = "SELECT name,percentage,a_id FROM coop where ( public = '1' or (end_time != '00000000000000' && beg_time <= ".date("YmdHis")." ) ) ORDER BY a_id";
		if ( !($result31 = mysql_db_query( $DBC.$course_id, $Q31 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫讀取錯誤31!!" );
			exit;
		}
*/
		$num_t = mysql_num_rows($result2);
		$num_h = mysql_num_rows($result3);
//		$num_c = mysql_num_rows($result31);
        //兩個只要有一個不為 0 , 就進去算
		if(($num_t != 0)||($num_h != 0)) {//||($num_c != 0)) {
			//各項
			$total_score = 0;
			$i = 0;
			while ( $rows = mysql_fetch_array($result2) ) {
				$max = 0;
				$min = 100;
				//由 take_exam 選出exam_id=$rows[2]的成績
				$Q4 = "SELECT grade FROM take_exam WHERE exam_id = '$rows[2]' AND course_id='$course_id' AND year='$year' AND term='$term'";
				if ( !($result4 = mysql_db_query( $buDB, $Q4 ) ) ) {
					show_page( "not_access.tpl" ,"資料庫讀取錯誤4!!" );
					exit;
				}
				$stu_num = mysql_num_rows($result4);
				$total = 0;
				//取出最高分最低分, 還有該 exam　的總分
				while ( $row = mysql_fetch_array($result4) ) {
					if ( $row[0] != "" && $row[0] != "-1" ) { //有成績
						$total = $total + $row[0];
						if ( (int)$row[0] < $min )
							$min = (int)$row[0];
						if ( (int)$row[0] > $max )
							$max = (int)$row[0];
					}
					else
						$stu_num --;
				}
				if( $stu_num != 0 ) {
				//0 平均 3 有效分數比數 4 標準差 5 is_online 6 a_id 7 exam or homework
					$countdata[0][ $i + 2 ] = $total/$stu_num;
					$countdata[3][ $i + 2 ] = $stu_num;
					$total_squire = 0;
					$Q4 = "SELECT grade FROM take_exam WHERE exam_id ='$rows[2]' AND course_id='$course_id' AND year='$year' AND term='$term'";
					if ( !($result4 = mysql_db_query( $buDB, $Q4 ) ) ) {
						show_page( "not_access.tpl" ,"資料庫讀取錯誤4!!" );
						exit;
					}
					while ( $row = mysql_fetch_array($result4) ) {
						if ( $row[0] != "" && $row[0] != "-1" ) {
							$total_squire += pow( $row[0]-$countdata[0][ $i + 2 ], 2 );
						}
					}
					
					$std = sqrt($total_squire/$stu_num);
					$countdata[4][ $i + 2 ] = $std;
				}
				else {
					$countdata[0][ $i + 2 ] = "　";
					$countdata[3][ $i + 2 ] = 0;
					$countdata[4][ $i + 2 ] = 0;
				}
				$countdata[5][ $i + 2 ] = $rows[3];
				$countdata[6][ $i + 2 ] = $rows[2];
				$countdata[7][ $i + 2 ] = 0;
				
				if ( $total == 0 )
					$min = 0;
				$countdata[1][ $i + 2 ] = $max;
				$countdata[2][ $i + 2 ] = $min;
				$i++;
			}
			while ( $rows = mysql_fetch_array($result3) ) {
				$max = 0;
				$min = 100;
				//由handin_homework中選出 homework_id = $rows[2] 的成績
				$Q4 = "SELECT grade FROM handin_homework WHERE homework_id ='$rows[2]' AND course_id='$course_id' AND year='$year' AND term='$term'";
				if ( !($result4 = mysql_db_query( $buDB, $Q4 ) ) ) {
					show_page( "not_access.tpl" ,"資料庫讀取錯誤4!!" );
					exit;
				}
				$stu_num = mysql_num_rows($result4); //homework成績的數量
				$total = 0;
				//得到作業成績的最高分與最低分
				while ( $row = mysql_fetch_array($result4) ) {
					if ( $row[0] != "" && $row[0] != "-1" ) {
						$total = $total + $row[0];
						if ( (int)$row[0] < $min )
							$min = (int)$row[0];
						if ( (int)$row[0] > $max )
							$max = (int)$row[0];
					}
					else
						$stu_num --;
				}
				if( $stu_num != 0 ) { //學生人數不為 0
					$countdata[0][ $i + 2 ] = $total/$stu_num; //算作業平均
					$countdata[3][ $i + 2 ] = $stu_num; //學生人數
					$total_squire = 0;
					//由handin_homework中選出 homework_id = $rows[2] 的成績
					$Q4 = "SELECT grade FROM handin_homework WHERE homework_id ='$rows[2]' AND course_id='$course_id' AND year='$year' AND term='$term'";
					if ( !($result4 = mysql_db_query( $buDB, $Q4 ) ) ) {
						show_page( "not_access.tpl" ,"資料庫讀取錯誤4!!" );
						exit;
					}
					while ( $row = mysql_fetch_array($result4) ) {
						if ( $row[0] != "" && $row[0] != "-1" ) {
							$total_squire += pow( $row[0]-$countdata[0][ $i + 2 ], 2 );
						}
					}
					$std = sqrt($total_squire/$stu_num);
					$countdata[4][ $i + 2 ] = $std;
				}
				else {
					$countdata[0][ $i + 2 ] = "　";
					$countdata[3][ $i + 2 ] = 0;
					$countdata[4][ $i + 2 ] = 0;
				}
				$countdata[5][ $i + 2 ] = 0;
				$countdata[6][ $i + 2 ] = $rows[2];
				$countdata[7][ $i + 2 ] = 1;
				
				if ( $total == 0 )
					$min = 0;
				$countdata[1][ $i + 2 ] = $max;
				$countdata[2][ $i + 2 ] = $min;
				$i++;
			}
			/*
			while ( $rows = mysql_fetch_array($result31) ) {
				$max = 0;
				$min = 100;
				$Q4 = "SELECT grade FROM take_coop WHERE case_id = '$rows[2]'";
				if ( !($result4 = mysql_db_query( $DBC.$course_id, $Q4 ) ) ) {
					show_page( "not_access.tpl" ,"資料庫讀取錯誤4!!" );
					exit;
				}
				$stu_num = mysql_num_rows($result4);
				$total = 0;

				while ( $row = mysql_fetch_array($result4) ) {
					if ( $row[0] != "" && $row[0] != "-1" ) {
						$total = $total + $row[0];
						if ( (int)$row[0] < $min )
							$min = (int)$row[0];
						if ( (int)$row[0] > $max )
							$max = (int)$row[0];
					}
					else
						$stu_num --;
				}
				if( $stu_num != 0 ) {
					//0 平均 3 有效分數比數 4 標準差 5 is_online 6 a_id 7 exam or homework
					$countdata[0][ $i + 2 ] = $total/$stu_num;
					$countdata[3][ $i + 2 ] = $stu_num;
					$total_squire = 0;
					$Q4 = "SELECT grade FROM take_coop WHERE case_id ='$rows[2]'";
					if ( !($result4 = mysql_db_query( $DBC.$course_id, $Q4 ) ) ) {
						show_page( "not_access.tpl" ,"資料庫讀取錯誤4!!" );
						exit;
					}
					while ( $row = mysql_fetch_array($result4) ) {
						if ( $row[0] != "" && $row[0] != "-1" ) {
							$total_squire += pow( $row[0]-$countdata[0][ $i + 2 ], 2 );
						}
					}
					
					$std = sqrt($total_squire/$stu_num);
					$countdata[4][ $i + 2 ] = $std;
				}
				else {
					$countdata[0][ $i + 2 ] = "　";
					$countdata[3][ $i + 2 ] = 0;
					$countdata[4][ $i + 2 ] = 0;
				}
				$countdata[5][ $i + 2 ] = $rows[3];
				$countdata[6][ $i + 2 ] = $rows[2];
				$countdata[7][ $i + 2 ] = 2;
				
				if ( $total == 0 )
					$min = 0;
				$countdata[1][ $i + 2 ] = $max;
				$countdata[2][ $i + 2 ] = $min;
				$i++;
			}*/
			
			//總分
			$countdata[0][1] = $i;
			$max = 0;
			$min = 100;
			$l = $num1;
			$j = 0;
			$scordate[0][0] = 0;
			while ( $row1 = mysql_fetch_array($result1) ) {
				$real_grade = 0;
				$mark = 0;
				$scordata[$j][0] = $row1[1];//學號
				$scordata[$j][1] = $row1[2];//姓名
				$k = 2;
				$Q5 = "SELECT te.grade,e.percentage FROM exam e,take_exam te WHERE e.a_id = te.exam_id AND te.student_id = '$row1[0]' and ( e.public = '1' or (e.end_time != '00000000000000' && e.beg_time <= ".date("YmdHis")." ) ) AND e.course_id='$course_id' AND e.year='$year' AND e.term='$term' AND e.course_id=te.course_id AND e.year=te.year AND e.term=te.term order by e.a_id";
				if ( !($result5 = mysql_db_query( $buDB, $Q5 ) ) ) {
					show_page( "not_access.tpl" ,"資料庫讀取錯誤5!!" );
					exit;
				}				
				while ( $row2 = mysql_fetch_array($result5) ) {
					//if ( $row2[0] != "" && $row2[0] != "-1" && $row2[0] != null && $row2[1] != 0 ) {//20060609jp    0%不會顯示
					if ( $row2[0] != "" && $row2[0] != "-1" && $row2[0] != null ) {//20060609jp　0%會顯示
						$real_grade = $real_grade + $row2[0]*$row2[1]/100;
						$scordata[$j][$k] = $row2[0];//每個人的所有分數
						$mark = 1;
					}
					else {
						$scordata[$j][$k] = " ";
					}
					$k ++;
				}

				$Q6 = "SELECT hh.grade,h.percentage FROM homework h, handin_homework hh WHERE h.a_id = hh.homework_id AND hh.student_id='$row1[0]' and (h.public = '1' or h.public = '3') AND h.course_id='$course_id' AND h.year='$year' AND h.term='$term' AND h.course_id=hh.course_id AND h.year=hh.year AND h.term=hh.term order by h.a_id";
				if ( !($result6 = mysql_db_query( $buDB, $Q6 ) ) ) {
					show_page( "not_access.tpl" ,"資料庫讀取錯誤6!!" );
					exit;
				}
				while ( $row3 = mysql_fetch_array($result6) ) {
					if ( $row3[0] != "" && $row3[0] != "-1" && $row3[0] != null && $row3[1] != 0 ) {//20060609jp
						$real_grade = $real_grade + $row3[0]*$row3[1]/100;
						$scordata[$j][$k] = $row3[0];
						$mark = 1;
					}
					else {
						$scordata[$j][$k] = " ";
					}
					$k ++;
				}
				/*
				$Q61 = "SELECT tc.grade,c.percentage FROM coop c, take_coop tc WHERE c.a_id = tc.case_id AND tc.student_id='$row1[0]' and c.public = '1' order by c.a_id";
				if ( !($result61 = mysql_db_query( $DBC.$course_id, $Q61 ) ) ) {
					show_page( "not_access.tpl" ,"資料庫讀取錯誤61!!" );
					exit;
				}
				while ( $row31 = mysql_fetch_array($result61) ) {
					if ( $row31[0] != "" && $row31[0] != "-1" ) {
						$real_grade = $real_grade + $row31[0]*$row31[1]/100;
						$scordata[$j][$k] = $row31[0];
						$mark = 1;
					}
					else {
						$scordata[$j][$k] = " ";
					}
					$k ++;
				}
				*/
				if ( $mark != 0 )
					$scordata[$j][$k] = $real_grade;
				else
					$scordata[$j][$k] = " ";
				
				if ( $mark != 0 )
				{
					$total_score = $total_score + $real_grade;
					if ( $real_grade < $min )
						$min = $real_grade;
					if ( $real_grade > $max )
						$max = $real_grade;
				}
				else
					$l --;
				$j ++;
			}
			if ( $l != 0 ) {
				$countdata[0][ $i + 2 ] = $total_score/$l;
				$countdata[3][ $i + 2 ] = $l;
				$total_squire = 0;
				for ( $m = 0 ; $m < $j ; $m ++ ) {
					if ( $scordata[$m][$k] != " " ) {
						$total_squire += pow( $scordata[$m][$k]-$countdata[0][ $i + 2 ], 2 );
					}
				}
				$std = sqrt($total_squire/$l);
				$countdata[4][ $i + 2 ] = $std;
			}
			else {
				$countdata[0][ $i + 2 ] = "　";
				$countdata[3][ $i + 2 ] = 0;
			}
			if ( $total_score == 0 ){
				$min = 0;
			}
			$countdata[1][ $i + 2 ] = $max;
			$countdata[2][ $i + 2 ] = $min;
			resort ();
		//----------------------------------------------------------------------------
			if($action == "upload"){
				$Qs = "delete from take_exam where exam_id = '-1' AND course_id='$course_id' AND year='$year' AND term='$term'";
				if (!($result = mysql_db_query($buDB,$Qs))){
					$error = "mysql資料庫讀取錯誤!!";
					return "$error $Qs<br>";
				}
				for($i=0;$i<sizeof($scordata);$i++){
					$std_no = $scordata[$i][0];
                    if( $scordata[$i][sizeof($scordata[$i])-1] != " " ){//20060609jp, with scores
					    $final = round($scordata[$i][sizeof($scordata[$i])-1]);
					    $Q1 = "select a_id from user where id = '$std_no'";
						if (($result1 = mysql_db_query($DB,$Q1))){
							$row1 = mysql_fetch_array($result1);
						}
						else{
							$error = "mysql資料庫讀取錯誤!!";
							return "$error $Q1<br>";
						}
						
						$Q2 = "insert into take_exam (exam_id, student_id, course_id, year, term, grade) values ( '-1', '$row1[a_id]', '$course_id', '$year', '$term','$final')";
						if (!($result2 = mysql_db_query($buDB, $Q2))){
							$error = "mysql資料庫讀取錯誤!!";
							return "$error $Q2<br>";
						 }
                	}
				}
				header("Location:BGShowFrame.php?year=$year&term=$term&course=$course_id&PHPSESSID=$PHPSESSID");
			}
			//--
			//elseif($action == "upload_excel"){			
				//upload_excel();
			//}
			//elseif($action == "download_excel"){
				//download_excel();
			//}			
			//------------------------------------------------------------------------------------
			//if($action != "upload_excel")
			show_page_d();			
			//------------------------------------------------------------------------------------
			//show_page_d();
		}
		else {
			//-------------------------------------------------
			if($action == "upload"){
				$Qs = "delete from take_exam where exam_id = '-1' AND course_id='$course_id' AND year='$year' AND term='$term'";
				if (!($result = mysql_db_query($buDB,$Qs))){
					$error = "mysql資料庫讀取錯誤!!";
					return "$error $Qs<br>";
				}
				header("Location:BGShowFrame.php?year=$year&term=$term&course=$course_id&PHPSESSID=$PHPSESSID");
			}
			//-----------------------------------------------------------------
			else if( $version=="C" )
				show_page( "not_access.tpl" ,"此課程尚未有任何成績!");
			else
				show_page( "not_access.tpl" ,"There is no SCORE in this Class!!");
		}
	}
	else {
		if( $version=="C" )
			show_page( "not_access.tpl" ,"此課程尚未有任何學生!");
		else
			show_page( "not_access.tpl" ,"There is no Student in this Class!!");
	}
/*
}
else
{
	echo "$year=".$year." $term=".$term." $coures_id=".$course_id."<BR>";
	if( $version=="C" ) {
		show_page( "not_access.tpl" ,"你沒有權限使用此功能");
		exit;
	}
	else {
		show_page( "not_access.tpl" ,"You have No Permission!!");
		exit;
	}
}
*/

function resort () {
	global $scordata, $action, $countdata, $sel;
	if ( $sel != NULL ) {
		if ( $sel == "Total" ) {
			$scordata = qsort_multiarray ( $scordata, $countdata[0][1]+2, 1 );
		}
		else if ( $sel == "name" ) {
			$scordata = qsort_multiarray ( $scordata, 1, 1 );
		}
		else
			$scordata = qsort_multiarray ( $scordata, $sel + 2, 1 );
	}
}


function do_update () {
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $mid, $maid, $work, $grade, $course_id, $year, $term;
	$buDB = "bugrade";
	
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}
	$Q1 = "SELECT a_id FROM user where id='".$mid."'";
	if ( !($result1 = mysql_db_query( $DB, $Q1 ) ) ) {
		show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
	}
	$row = mysql_fetch_array($result1);
	//----devon----2006-02-27-----修改成績
	$Q0 = "select grade from take_exam where exam_id='$maid' and student_id='".$row['a_id']."' AND course_id='$course_id' AND year='$year' AND term='$term' ";
	$result0 = mysql_db_query($buDB, $Q0);
	$num = mysql_num_rows($result0);
	if($num !=0)
	{
		if ( $work == 1 ) {
			$Q2 = "update handin_homework set grade = '$grade' where homework_id = '$maid' and student_id = '".$row['a_id']."' AND course_id='$course_id' AND year='$year' AND term='$term'";
		}
	/*
	else if ( $work == 2 ) {
		$Q2 = "update take_coop set grade = '$grade' where case_id = '$maid' and student_id = '".$row['a_id']."'";
	}
	*/
		else { 
			$Q2 = "update take_exam set grade = '$grade' where exam_id = '$maid' and student_id = '".$row['a_id']."' AND course_id='$course_id' AND year='$year' AND term='$term'";
			$Q3 = "select * from exam where is_online = '0' and a_id = '$maid' AND course_id='$course_id' AND year='$year' AND term='$term'";
			($result3 = mysql_db_query( $buDB, $Q3 )) or die ("資料庫讀取錯誤!!");
			if ( mysql_num_rows( $result3 ) == 0 ) {
				return;
			}
		}
	/*if ( $work == 2 ) {
		mysql_db_query( $DBC.$course_id, $Q2 ) or die ("資料庫更新錯誤!!");
	}*/
	//else {
		mysql_db_query( $buDB, $Q2 ) or die ("資料庫更新錯誤!!");
	//}
	}
	else
	{
		if ( $work == 1 ) {
			$Q2 = "update handin_homework set grade = '$grade' where homework_id = '$maid' and student_id = '".$row['a_id']."' AND course_id='$course_id' AND year='$year' AND term='$term'";
		}
	/*
	else if ( $work == 2 ) {
		$Q2 = "update take_coop set grade = '$grade' where case_id = '$maid' and student_id = '".$row['a_id']."'";
	}
	*/
		else { 
			$Q2 = "insert into take_exam (grade, exam_id, student_id, course_id, year, term) values ('$grade', '$maid', '$row[a_id]', '$course_id', '$year', '$term')";// = '$grade' where exam_id = '$maid' and student_id = '".$row['a_id']."'";
			$Q3 = "select * from exam where is_online = '0' and a_id = '$maid' AND course_id='$course_id' AND year='$year' AND term='$term'";
			($result3 = mysql_db_query( $buDB, $Q3 )) or die ("資料庫圖取錯誤!!");
			if ( mysql_num_rows( $result3 ) == 0 ) {
				return;
			}
		}
	/*if ( $work == 2 ) {
		mysql_db_query( $DBC.$course_id, $Q2 ) or die ("資料庫更新錯誤!!");
	}*/
	//else {
		mysql_db_query( $buDB, $Q2 ) or die ("資料庫更新錯誤22!!");
	}
}

function show_page_d () {
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $version, $message, $course_id, $scordata, $countdata, $sel, $skinnum, $a_id, $id, $action, $work, $PHPSESSID, $year, $term;
	$buDB = "bugrade";
	
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}
	$Q1 = "SELECT name,percentage,a_id FROM exam where  ( public = '1' or (end_time != '00000000000000' && beg_time <= ".date("YmdHis")." ) )  AND course_id='$course_id' AND year='$year' AND term='$term' ORDER BY a_id";
	if ( !($result1 = mysql_db_query( $buDB, $Q1 ) ) ) {
		show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
	}
	$Q2 = "SELECT name,percentage,a_id FROM homework where (public = '1' or public = '3') AND course_id='$course_id' AND year='$year' AND term='$term' ORDER BY a_id";
	if ( !($result2 = mysql_db_query( $buDB, $Q2 ) ) ) {
		show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
	}
	/*
	$Q21 = "SELECT name,percentage,a_id FROM coop where ( public = '1' or (end_time != '00000000000000' && beg_time <= ".date("YmdHis")." ) ) ORDER BY a_id";
	if ( !($result21 = mysql_db_query( $DBC.$course_id, $Q21 ) ) ) {
		show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
	}
	*/
	include("class.FastTemplate.php3");
	$tpl=new FastTemplate("./templates");
	if ( $version == "C" )
		$tpl->define(array(main=>"BGQueryFrame1.tpl"));
	else
		$tpl->define(array(main=>"BGQueryFrame1_E.tpl"));
	$tpl->define_dynamic("row","main");
	$tpl->define_dynamic("grade","main");
	$tpl->assign( SKINNUM , $skinnum );
	$file_name="../../old_grade/$year/$term/$course_id/score_$course_id.xls";
	if(file_exists($file_name))
		unlink($file_name);
	$file=fopen("$file_name","w");
	if($version == "C")
		fwrite($file,"學號\t姓名");
	else
		fwrite($file,"Student ID\tName");
	$i = 0;
	while ( $row = mysql_fetch_array($result1) ) {
		$tpl->assign(TESTNAME,"<a href=\"BGQueryFrame1.php?sel=$i&year=$year&term=$term&course=$course_id\"><font color = \"#FFFFFF\" size=\"2\">".$row[0]."</font></a>");
		$tpl->assign(RATIO,$row[1]);
		$tpl->parse(ROW,".row");
		fwrite($file,"\t$row[0]");
		$i ++;
	}
	while ( $row = mysql_fetch_array($result2) ) {
		$tpl->assign(TESTNAME,"<a href=\"BGQueryFrame1.php?sel=$i&year=$year&term=$term&course=$course_id\"><font color = \"#FFFFFF\" size=\"2\">".$row[0]."</font></a>");
    		$tpl->assign(RATIO,$row[1]);
		$tpl->parse(ROW,".row");
		fwrite($file,"\t$row[0]");
		$i ++;
	}
	/*
	while ( $row = mysql_fetch_array($result21) ) {
		$tpl->assign(TESTNAME,"<a href=\"TGQueryFrame1.php?sel=$i\"><font color = \"#FFFFFF\" size=\"2\">".$row[0]."</font></a>");
    		$tpl->assign(RATIO,$row[1]);
		$tpl->parse(ROW,".row");
		fwrite($file,"\t$row[0]");
		$i ++;
	}
	*/
	if($version == "C")
		fwrite($file,"\t總成績\n");
	else
		fwrite($file,"\tTotal Score\n");
	
	$color = "#E6FFFC";
	$i = 0;
	if ( $sel == NULL ) {
		$sel = 0;
	}
	else if ( $sel == "name" ) {
		$sel = 1;
	}
	else if ( $sel == "Total" ) {
		$sel = $countdata[0][1]+2;
	}
	else {
		$sel = $sel + 2;
	}
	for ( $j = 0 ; $j < $countdata[0][0]; $j ++ )
	{
		if ( $color == "#F0FFEE" )
			$color = "#E6FFFC";
		else
			$color = "#F0FFEE";
		for($k = 0; $k <= $countdata[0][1] + 2; $k ++ )
		{
			if($k == 0 || $k == 1 ) {
				if ( $k == 0 ) {
					//排名
					if ( $j == 0 ) {
						$grade = $scordata[$j][$sel];
						$i ++;
					}
					else if ( $grade == $scordata[$j][$sel] ) {
						$m ++;
					}
					else {
						$i = $i + $m + 1;
						$m = 0;
						$grade = $scordata[$j][$sel];
					}
					if ( $scordata[$j][$sel] == " " ) {
						$i = 0;
					}
					if ( $i == 0 ) {
						$i = " ";
					}

					$tpl->assign(DATA,"<tr bgcolor = $color onmouseover=\"set_obj( this, '$j')\" onmousedown=\"rang_bg('start', '$j')\" onmouseup=\"rang_bg('end', '$j')\"><td><div align=\"center\"><font size=\"2\">$i</font></div></td><td><div align=\"center\"><font size=\"2\">".$scordata[$j][$k]."</font></div></td>");
					fwrite($file,$scordata[$j][$k]);
				}
				else {
					$tpl->assign(DATA,"<td><div align=\"center\"><font size=\"2\">".$scordata[$j][$k]."</font></div></td>");
					fwrite($file,"\t".$scordata[$j][$k]);
				}
			}
			else {
				if ( $k == $countdata[0][1] + 2 ) {
					if($scordata[$j][$k] < 60)
						$tpl->assign(DATA,"<td><div align=\"center\"><font color=#ff0000 size=\"2\">".$scordata[$j][$k]."</font></div></td></tr>");
					else
						$tpl->assign(DATA,"<td><div align=\"center\"><font size=\"2\">".$scordata[$j][$k]."</font></div></td></tr>");
				}
				else {
					if ( $countdata[5][$k] == 0 ) {
						if ( $action == "modify_single" && $countdata[6][$k] == $a_id && $scordata[$j][0] == $id ) {
							$tpl->assign(DATA,"<form action=./BGQueryFrame1.php method=post><td><input type=hidden name=mid value=".$scordata[$j][0]." ><input type=hidden name=work value=$work><input type=hidden name=maid value=".$countdata[6][$k]."><input type=hidden name=action value=update><input type=hidden name=year value=".$year."><input type=hidden name=term value=".$term."><input type=hidden name=course value=".$course_id."><input type=text name=grade value=\"".$scordata[$j][$k]."\" size=2><input type=submit value=\"送出\" ></td></form>");
						}
						else if ( $scordata[$j][$k] == " " ) {
							$tpl->assign(DATA,"<td><div align=\"center\"><a href=\"./BGQueryFrame1.php?action=modify_single&a_id=".$countdata[6][$k]."&id=".$scordata[$j][0]."&year=".$year."&term=".$term."&course=".$course_id."&work=".$countdata[7][$k]."\" >.</a></div></td>");
						}
						else {
							if($scordata[$j][$k] < 60)
								$tpl->assign(DATA,"<td><div align=\"center\"><a href=\"./BGQueryFrame1.php?action=modify_single&a_id=".$countdata[6][$k]."&id=".$scordata[$j][0]."&year=".$year."&term=".$term."&course=".$course_id."&work=".$countdata[7][$k]."\" ><font color=#ff0000 size=\"2\">".$scordata[$j][$k]."</font></a></div></td>");
							else
								$tpl->assign(DATA,"<td><div align=\"center\"><a href=\"./BGQueryFrame1.php?action=modify_single&a_id=".$countdata[6][$k]."&id=".$scordata[$j][0]."&year=".$year."&term=".$term."&course=".$course_id."&work=".$countdata[7][$k]."\" ><font size=\"2\">".$scordata[$j][$k]."</font></a></div></td>");
						}
					}
					else {
						if($scordata[$j][$k] < 60)
							$tpl->assign(DATA,"<td><div align=\"center\"><font color=#ff0000 size=\"2\">".$scordata[$j][$k]."</font></div></td>");
						else
							$tpl->assign(DATA,"<td><div align=\"center\"><font size=\"2\">".$scordata[$j][$k]."</font></div></td>");
					}
					
				}
				fwrite($file,"\t".$scordata[$j][$k]);
			}
			$tpl->parse(GRADE,".grade");
		}
		fwrite($file,"\n");
	}
	$color = "#B0BFC3";
	if($version == "C") {
		fwrite($file,"　\t最高分");
		$tpl->assign(DATA,"<tr bgcolor = $color><th COLSPAN=3 bgcolor=\"#000066\"><div align=\"center\"><font size=\"2\" color=\"#FFFFFF\">最高分</font></div></th>");
	}
	else {
		fwrite($file,"　\tTOP");
		$tpl->assign(DATA,"<tr bgcolor = $color><th COLSPAN=3 bgcolor=\"#000066\"><div align=\"center\"><font size=\"2\" color=\"#FFFFFF\">TOP</font></div></th>");
	}
	$tpl->parse(GRADE,".grade");
	for( $j = 0; $j < $countdata[0][1] + 1; $j++ )
	{
		if($countdata[1][$j + 2] < 60)
			$tpl->assign(DATA,"<th><div align=\"center\"><font color=#ff0000 size=\"2\">".$countdata[1][$j + 2]."</font></div></th>");
		else
			$tpl->assign(DATA,"<th><div align=\"center\"><font size=\"2\">".$countdata[1][$j + 2]."</font></div></th>");
		$tpl->parse(GRADE,".grade");
		fwrite($file,"\t".$countdata[1][$j + 2]);
	}
	fwrite($file,"\n");
	if($version == "C") {
		fwrite($file,"　\t最低分");
		$tpl->assign(DATA,"<tr bgcolor = $color><th COLSPAN=3 bgcolor=\"#000066\"><div align=\"center\"><font size=\"2\" color=\"#FFFFFF\">最低分</font></div></th>");
	}
	else {
		fwrite($file,"　\tWorst");
		$tpl->assign(DATA,"<tr bgcolor = $color><th COLSPAN=3 bgcolor=\"#000066\"><div align=\"center\"><font size=\"2\" color=\"#FFFFFF\">Worst</font></div></th>");
	}
	$tpl->parse(GRADE,".grade");
	for( $j = 0; $j < $countdata[0][1] + 1; $j++ )
	{
		if($countdata[2][$j + 2] < 60)
			$tpl->assign(DATA,"<th><div align=\"center\"><font color=#ff0000 size=\"2\">".$countdata[2][$j + 2]."</font></div></th>");
		else
			$tpl->assign(DATA,"<th><div align=\"center\"><font size=\"2\">".$countdata[2][$j + 2]."</font></div></th>");
		$tpl->parse(GRADE,".grade");
		fwrite($file,"\t".$countdata[2][$j + 2]);
	}
	fwrite($file,"\n");
	if($version == "C") {
		fwrite($file,"　\t平均");
		$tpl->assign(DATA,"<tr bgcolor = $color><th COLSPAN=3 bgcolor=\"#000066\"><div align=\"center\"><font size=\"2\" color=\"#FFFFFF\">平均</font></div></th>");
	}
	else {
		fwrite($file,"　\tAverage");
		$tpl->assign(DATA,"<tr bgcolor = $color><th COLSPAN=3 bgcolor=\"#000066\"><div align=\"center\"><font size=\"2\" color=\"#FFFFFF\">Average</font></div></th>");
	}
	$tpl->parse(GRADE,".grade");
	for( $j = 0; $j < $countdata[0][1] + 1; $j++ )
	{
		$point = strpos( $countdata[0][$j + 2], '.' );
		$point2 = strpos( $countdata[0][$j + 2], '/' );
		if($countdata[0][$j + 2] < 60)
			$tpl->assign(DATA,"<th><div align=\"center\"><font color=#ff0000 size=\"2\">".substr($countdata[0][$j + 2], 0, $point+3)."</font></div></th>");
		else
			$tpl->assign(DATA,"<th><div align=\"center\"><font size=\"2\">".substr($countdata[0][$j + 2], 0, $point+3)."</font></div>");
		$tpl->parse(GRADE,".grade");
		fwrite($file,"\t".substr($countdata[0][$j + 2], 0, $point+3));
	}
	fclose($file);
	if($version == "C") {
		$tpl->assign(DATA,"<tr bgcolor = $color><th COLSPAN=3 bgcolor=\"#000066\"><div align=\"center\"><font size=\"2\" color=\"#FFFFFF\">標準差</font></div></th>");
	}
	else {
		$tpl->assign(DATA,"<tr bgcolor = $color><th COLSPAN=3 bgcolor=\"#000066\"><div align=\"center\"><font size=\"2\" color=\"#FFFFFF\">STD</font></div></th>");
	}	
	$tpl->parse(GRADE,".grade");
	for( $j = 0; $j < $countdata[0][1] + 1; $j++ )
	{
		$point = strpos( $countdata[4][$j + 2], '.' );
		$tpl->assign(DATA,"<th><div align=\"center\"><font size=\"2\">".substr($countdata[4][$j + 2], 0, $point+3)."</font></div></th>");
		$tpl->parse(GRADE,".grade");
	}
	if($version == "C") {
		$tpl->assign(DATA,"<tr bgcolor = $color><th COLSPAN=3 bgcolor=\"#000066\"><div align=\"center\"><font size=\"2\" color=\"#FFFFFF\">有效資料</font></div></th>");
		$countname = "筆"; 
	}
	else {
		$tpl->assign(DATA,"<tr bgcolor = $color><th COLSPAN=3 bgcolor=\"#000066\"><div align=\"center\"><font size=\"2\" color=\"#FFFFFF\">Avaliaibe Data</font></div></th>");
		$countname = "Rows"; 
	}	
	$tpl->parse(GRADE,".grade");
	for( $j = 0; $j < $countdata[0][1] + 1; $j++ )
	{
		$tpl->assign(DATA,"<th><div align=\"center\"><font size=\"2\">".$countdata[3][$j + 2]."$countname</font></div></th>");
		$tpl->parse(GRADE,".grade");
	}
	$tpl->assign(LOCATION,"old_grade/$year/$term/$course_id/score_$course_id.xls");
	$tpl->assign(YR, $year);
	$tpl->assign(TM, $term);
	$tpl->assign(CID, $course_id);
	$tpl->parse(BODY,"main");
	$tpl->FastPrint("BODY");
}

?>
