<?php
	/* 
	@ Author: carlyle
	*/
	
	require_once 'fadmin.php';

	function showStudentExamAnswer($DB,$DB_CONN,$course_id,$tiku_a_id,$student_id,$tpl,$qno,$fieldcount = "") {
		//取出exam_id
		$query = "SELECT exam_id FROM tiku WHERE a_id='" . $tiku_a_id . "'";
		if (!($result1 = mysql_db_query($DB.$course_id,$query,$DB_CONN))) {
			show_page("not_access.tpl","資料庫讀取錯誤!!");
		} else
			$row1 = mysql_fetch_array($result1);
	
		//檢查這個學生是否已經測驗過了
		$query = "SELECT * FROM qa2 WHERE tiku_a_id='" . $tiku_a_id . "' AND student_id='" . $student_id . "' AND exam_id='" . $row1['exam_id'] . "'";
		if (!($result2 = mysql_db_query($DB.$course_id,$query,$DB_CONN))) {
			show_page("not_access.tpl","資料庫讀取錯誤!!");
		}
		if (mysql_num_rows($result2) == 0) //no result
			return 1;
		else
			$row2 = mysql_fetch_array($result2);
		
		//echo "tiku" . $row2['tiku_a_id']. "stu_ans_a= " .$row2['stu_ans_1']."<br>";		
		$type = $row2['type'];
		$ismultiple = $row2['ismultiple'];
		$ans_1 = $row2['stu_ans_1'];
		$ans_2 = $row2['stu_ans_2'];
		$ans_3 = $row2['stu_ans_3'];
		$ans_4 = $row2['stu_ans_4'];	
		if ($type == 1) {
			if ($ismultiple == "0") {
                                $tpl->assign("SE".$ans_1."_".$qno." ","checked");
			} else {
				$ans_1 = (int) $ans_1;
				if (($ans_1 & 1) == 1)  $tpl->assign("SE1_".$qno." ","checked");
				if (($ans_1 & 2) == 2)  $tpl->assign("SE2_".$qno." ","checked");
				if (($ans_1 & 4) == 4)  $tpl->assign("SE3_".$qno." ","checked");
				if (($ans_1 & 8) == 8)  $tpl->assign("SE4_".$qno." ","checked");
			}
		} else if ($type == 2) {
			$tpl->assign("CH".$ans_1."_".$qno." ", "checked");
		} else if ($type == 3) {
			for ($i=1;$i<=$fieldcount;$i++) {
				$ansStr= "ans_".$i;
				$tmp = $$ansStr;
				$tpl->assign("NUM",$i);
				$tpl->assign("ORDER",$i);
				$tpl->assign("VALUE",$tmp);
				$tpl->parse(CONT,".row");
			}	
		}

		return 0;
	}
?>
