<?php
	require 'common.php';
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		echo "資料庫連結錯誤 !!"."<br>";
	}
	$count = 0;
	$year = 102; 
	$term = 1;
	$num_rows3 = 0;
	
	//取得問卷代碼
	$Q1 = "select a_id from mid_subject where year='$year' and term='$term' ";
	$result1 = mysql_db_query($DB, $Q1);
	$rows1 = mysql_fetch_array($result1);	
	$q_id = $rows1['a_id'];
	
	$Q9 = "DELETE FROM mid_statistic_gen WHERE q_id = '$q_id' AND year = '$year' AND term = '$term' ";
	if ( !($result9 = mysql_db_query( $DB, $Q9 ) ) ) {
		echo "資料庫連結錯誤9 !!"."<br>";;
	}				
	
  //取得通識課程代碼
	$Q2 = "SELECT DISTINCT c.a_id, c.name, c.course_no FROM course c, teach_course tc WHERE c.group_id =87 AND c.a_id = tc.course_id AND tc.year = '$year' AND tc.term = '$term' ORDER BY c.a_id ";
	//echo $Q2;
	if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) ) {
		echo "資料庫連結錯誤2 !!"."<br>";;
	}			
	while ( $rows2 = mysql_fetch_array($result2) ) {
		$c_id = $rows2['a_id'];
		$c_no = $rows2['course_no'];
		$c_name = $rows2['name'];
		
		$Q6 = "select count(*) as c_count from take_course where course_id = $c_id and year='$year' and term='$term' ";
		$result6 = mysql_db_query($DB, $Q6);
		$rows6 = mysql_fetch_array($result6);	
		$c_count = $rows6['c_count'];		
		
		$count++;
		//echo $count."---".$c_id."--".$c_no."--".$c_name."--"."</br>";
		
		$Q3 = "SELECT student_id, q1, q2, q3, q4, q5,q6, q7, q8, q9, mtime FROM mid_ans WHERE q_id = '$q_id' AND year = '$year' AND term = '$term' ";
		//echo $Q3."<br>";
		//echo $DB.$c_id;
		if ( !($result3 = mysql_db_query( $DB.$c_id, $Q3 ) ) ) {
			echo "資料庫連結錯誤3 !!"."<br>";;
		}		
		$num_rows3 = mysql_num_rows($result3);
		echo $num_rows3."<br>";
		if($num_rows3 == 0) {
			$Q9="";
			$Q9 = "INSERT INTO mid_statistic_gen ( q_id, year, term, c_id, c_no, c_name, s_id, s_no, q1, q2, q3, q4, q5, q6, q7, q8, q9, c_count, mtime )";
			$Q9 = $Q9." VALUES ( '$q_id', '$year', '$term', '$c_id', '$c_no', '$c_name', ";
			$Q9 = $Q9." '$s_id', '$s_no', -1, -1, -1, -1, -1, -1, -1, -1, -1, '$c_count', '$mtime' )" ;
			//echo $Q5."<br>";
			if ( !($result9 = mysql_db_query( $DB, $Q9 ) ) ) {
				echo "資料庫連結錯誤5 !!"."<br>";;
			}							
			
		} else {
		
			
				while ( $rows3 = mysql_fetch_array($result3) ) {
					$s_id = $rows3['student_id'];
					
					$Q4 = "SELECT id, name FROM user WHERE a_id = '$s_id' ";
					$result4 = mysql_db_query($DB, $Q4);
					$rows4 = mysql_fetch_array($result4);
					
					$s_no = $rows4['id'];
					$s_name = $rows4['name'];
					$q1 = $rows3['q1'];
					$q2 = $rows3['q2'];
					$q3 = $rows3['q3'];
					$q4 = $rows3['q4'];
					$q5 = $rows3['q5'];
					$q6 = $rows3['q6'];
					$q7 = $rows3['q7'];
					$q8 = $rows3['q8'];
					$q9 = $rows3['q9'];
					$mtime = $rows3['mtime'];
					
					echo $c_id."--".$c_no."--".$c_name."--";
					echo $s_id."--".$s_no."--".$s_name."--";
					echo $rows3['q1'].",".$rows3['q2'].",".$rows3['q3'].",".$rows3['q4'].",".$rows3['q5'].",".$rows3['q6'].",".$rows3['q7'].",".$rows3['q8'].",".$rows3['q9']."--".$c_count."</br>";
					
					$Q5 = "INSERT INTO mid_statistic_gen ( q_id, year, term, c_id, c_no, c_name, s_id, s_no, q1, q2, q3, q4, q5, q6, q7, q8, q9, c_count, mtime )";
					$Q5 = $Q5." VALUES ( '$q_id', '$year', '$term', '$c_id', '$c_no', '$c_name', ";
					$Q5 = $Q5." '$s_id', '$s_no', '$q1', '$q2', '$q3', '$q4', '$q5', '$q6', '$q7', '$q8', '$q9', '$c_count', '$mtime' )" ;
					//echo $Q5."<br>";
					if ( !($result5 = mysql_db_query( $DB, $Q5 ) ) ) {
						echo "資料庫連結錯誤5 !!"."<br>";;
					}				
				}
			
		}
	}
  echo "Total Numbers Is :".$count;
?>
