<?php
	/* 
	@ Author: carlyle
	*/
	
	require_once 'fadmin.php';

	/* 紀錄學生測驗的答案 */
	function saveStudentExamAnswer($DB,$DB_CONN,$course_id,$tiku_a_id,$student_id,$type,$ismultiple,$ans_1,$ans_2,$ans_3,$ans_4) {
		//取出exam_id
		$query = "SELECT exam_id FROM tiku WHERE a_id='" . $tiku_a_id . "'";
		if (!($result1 = mysql_db_query($DB.$course_id,$query,$DB_CONN))) {
			show_page("not_access.tpl","資料庫讀取錯誤!!");
		} else
			$row1 = mysql_fetch_array($result1);
	
		//檢查這個學生是否已經測驗過了
		$query = "SELECT COUNT(*) FROM qa2 WHERE tiku_a_id='" . $tiku_a_id . "' AND student_id='" . $student_id . "'";
		if (!($result2 = mysql_db_query($DB.$course_id,$query,$DB_CONN))) {
			show_page("not_access.tpl","資料庫讀取錯誤!!");
		} else
			$row2 = mysql_fetch_array($result2);

		if ($row2[0] == 0) { //這個學生沒有做過測驗
			$query = "INSERT INTO `qa2` (`tiku_a_id`,`exam_id`,`student_id`,`type`,`ismultiple`,`stu_ans_1`,`stu_ans_2`,`stu_ans_3`,`stu_ans_4`) VALUES ('" . $tiku_a_id . "','" . $row1['exam_id'] . "','" . $student_id . "','" . $type . "','" . $ismultiple . "','" . $ans_1 . "','" . $ans_2 . "','" . $ans_3 . "','" . $ans_4 . "')";
		} else { //反之
			//當 grade = -1 時才更新成績
			$query = "SELECT COUNT(*) FROM take_exam WHERE grade='-1' AND exam_id='" . $row1['exam_id'] . "' AND student_id='" . $student_id . "'";
			if (!($result3 = mysql_db_query($DB.$course_id,$query,$DB_CONN))) {
				show_page("not_access.tpl","資料庫讀取錯誤!!");
			} else
				$row3 = mysql_fetch_array($result3);

			if ($row3[0] != 0)
				$query = "UPDATE `qa2` SET `exam_id` = '" . $row1['exam_id'] . "',`type` = '" . $type . "',`ismultiple` = '" . $ismultiple . "',`stu_ans_1` = '" . $ans_1 . "',`stu_ans_2` = '" . $ans_2 . "',`stu_ans_3` = '" . $ans_3 . "',`stu_ans_4` = '" . $ans_4 . "',`mtime` = NOW('CURRENT_TIMESTAMP') WHERE `tiku_a_id` = '" . $tiku_a_id . "' AND student_id='" . $student_id . "'";
			else
				$query = "";
		}

		//紀錄學生答案
		if ($query != "") {
			if (!mysql_db_query($DB.$course_id,$query,$DB_CONN)) {
				show_page("not_access.tpl","資料庫讀取錯誤!!");
			} 
		}
	}
?>
