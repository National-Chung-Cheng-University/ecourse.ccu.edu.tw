<?php
	/* 
	@ Author: carlyle
	*/
	
	require_once 'fadmin.php';

	/* �����ǥʹ��窺���� */
	function saveStudentExamAnswer($DB,$DB_CONN,$course_id,$tiku_a_id,$student_id,$type,$ismultiple,$ans_1,$ans_2,$ans_3,$ans_4) {
		//���Xexam_id
		$query = "SELECT exam_id FROM tiku WHERE a_id='" . $tiku_a_id . "'";
		if (!($result1 = mysql_db_query($DB.$course_id,$query,$DB_CONN))) {
			show_page("not_access.tpl","��ƮwŪ�����~!!");
		} else
			$row1 = mysql_fetch_array($result1);
	
		//�ˬd�o�ӾǥͬO�_�w�g����L�F
		$query = "SELECT COUNT(*) FROM qa2 WHERE tiku_a_id='" . $tiku_a_id . "' AND student_id='" . $student_id . "'";
		if (!($result2 = mysql_db_query($DB.$course_id,$query,$DB_CONN))) {
			show_page("not_access.tpl","��ƮwŪ�����~!!");
		} else
			$row2 = mysql_fetch_array($result2);

		if ($row2[0] == 0) { //�o�ӾǥͨS�����L����
			$query = "INSERT INTO `qa2` (`tiku_a_id`,`exam_id`,`student_id`,`type`,`ismultiple`,`stu_ans_1`,`stu_ans_2`,`stu_ans_3`,`stu_ans_4`) VALUES ('" . $tiku_a_id . "','" . $row1['exam_id'] . "','" . $student_id . "','" . $type . "','" . $ismultiple . "','" . $ans_1 . "','" . $ans_2 . "','" . $ans_3 . "','" . $ans_4 . "')";
		} else { //�Ϥ�
			//�� grade = -1 �ɤ~��s���Z
			$query = "SELECT COUNT(*) FROM take_exam WHERE grade='-1' AND exam_id='" . $row1['exam_id'] . "' AND student_id='" . $student_id . "'";
			if (!($result3 = mysql_db_query($DB.$course_id,$query,$DB_CONN))) {
				show_page("not_access.tpl","��ƮwŪ�����~!!");
			} else
				$row3 = mysql_fetch_array($result3);

			if ($row3[0] != 0)
				$query = "UPDATE `qa2` SET `exam_id` = '" . $row1['exam_id'] . "',`type` = '" . $type . "',`ismultiple` = '" . $ismultiple . "',`stu_ans_1` = '" . $ans_1 . "',`stu_ans_2` = '" . $ans_2 . "',`stu_ans_3` = '" . $ans_3 . "',`stu_ans_4` = '" . $ans_4 . "',`mtime` = NOW('CURRENT_TIMESTAMP') WHERE `tiku_a_id` = '" . $tiku_a_id . "' AND student_id='" . $student_id . "'";
			else
				$query = "";
		}

		//�����ǥ͵���
		if ($query != "") {
			if (!mysql_db_query($DB.$course_id,$query,$DB_CONN)) {
				show_page("not_access.tpl","��ƮwŪ�����~!!");
			} 
		}
	}
?>
