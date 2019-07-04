<?php
	/*
	@ Author: carlyle
	@ Description: �����{�ҩһݪ��@�ǰQ�װϲέp���
	@ Date: 2008/2/14
	*/

	require_once 'fadmin.php';

	//�@�Ǵ��g��
	define("TOTAL_WEEKS","18");

	//�峹�����^�Юɶ�, return�Ȫ���쬰�� (day)
	function getAvgReplyDelay($course_id) {
                global $DB;

                $conn = getSQLconnection();
                if (!$conn) return -1;

                //�U�Q�ת�����ƪ�W��
                $board_names = getBoardTableNames($conn,$DB,$course_id);

                $delay = 0;
		$posts = 0;
                for ($i=0;$i<sizeof($board_names);$i++) {
                        $sql_1 = "SELECT parent, sA.created, sB.created, TIMEDIFF(sA.created, sB.created)\n";
			$sql_1 = $sql_1 . "FROM " . $board_names[$i] . " AS sA, (\n";
                        $sql_1 = $sql_1 . "	SELECT a_id, created\n";
                        $sql_1 = $sql_1 . "	FROM `" . $board_names[$i] . "`\n";
                        $sql_1 = $sql_1 . "	WHERE parent = 0\n";
                        $sql_1 = $sql_1 . ") AS sB\n";
                        $sql_1 = $sql_1 . "WHERE sA.parent = sB.a_id\n";
                        $sql_1 = $sql_1 . "ORDER BY parent, 2 ASC\n"; //�o�̪�"2"�����OTIMEDIFF�p�⵲�G����

			$result_1 = mysql_db_query($DB.$course_id,$sql_1,$conn);
                        if (!$result_1) continue;
                        if (mysql_num_rows($result_1) == 0) continue;

			$last_parent_id = -1;
			while ($row_1 = mysql_fetch_array($result_1)) {
				//�u���C�ӥD�D���Ĥ@�g�^�Ф峹�ӭp��o�媺�ɶ��t
				if ($last_parent_id != $row_1['parent'])
					$last_parent_id = $row_1['parent'];
				else
					continue;
			
				//SQL��X���ɶ��t (xx:xx:xx)
				$diff = $row_1[3];
				list($t_hour,$t_minute,$t_second) = sscanf($diff,"%d:%d:%d");

				//rhhwang said: �W�L��g�����C�J�p��
				if ($t_hour <= 336) {
					$delay += $t_hour;
					$posts++;
				}
			}
                }

		if ($posts != 0)
			return round(($delay/$posts)/24,1);
		else
			return 0;
	}

	//�Ѯv�ΧU�Щ�n�Ѥ�($after = 0)��n�ѫ�($after = 1)�^�Ф�v, return�Ȫ���쬰%
	function getTchReplyRatio($course_id,$n,$after) {
		global $DB;

                $conn = getSQLconnection();
                if (!$conn) return -1;

                //�U�Q�ת�����ƪ�W��
                $board_names = getBoardTableNames($conn,$DB,$course_id);

                //���o�o��ҦѮv�̪�id (�t�U��)
                $teacher_ids = array();
                $teacher_count = 0;

                $sql_1 = "SELECT teacher_id FROM `teach_course` WHERE course_id = '" . $course_id . "'";
                $result_1 = mysql_db_query($DB,$sql_1,$conn);
                if (!$result_1) return -1;
                if (mysql_num_rows($result_1) == 0) return -1;

                while ($row_1 = mysql_fetch_array($result_1)) {
                        $sql_2 = "SELECT id FROM `user` WHERE a_id = '" . $row_1['teacher_id'] . "'";
                        $result_2 = mysql_db_query($DB,$sql_2,$conn);
                        if (!$result_2) continue;
                        if (mysql_num_rows($result_2) == 0) continue;
                        $row_2 = mysql_fetch_array($result_2);

                        $teacher_ids[$teacher_count] = $row_2['id'];
                        $teacher_count++;
                }

                if ($teacher_count == 0) return -1;

                $count = 0;
                for ($i=0;$i<sizeof($board_names);$i++) {
			$sql_3 = "SELECT COUNT(DISTINCT parent) FROM " . $board_names[$i] . " AS sA,\n";
			$sql_3 = $sql_3 . "(\n";
			$sql_3 = $sql_3 . "	SELECT a_id, created\n";
			$sql_3 = $sql_3 . "	FROM `" . $board_names[$i] . "`\n";
			$sql_3 = $sql_3 . "	WHERE parent = 0\n";
			$sql_3 = $sql_3 . ") As sB\n";
			$sql_3 = $sql_3 . "WHERE sA.parent = sB.a_id AND\n";
			if ($after == 0) //n�Ѥ�
				$sql_3 = $sql_3 . "(DATE_ADD(sB.created, INTERVAL '" . $n . "' DAY) >= sA.created) AND\n";
			else //n�ѫ�
				$sql_3 = $sql_3 . "(DATE_ADD(sB.created, INTERVAL '" . $n . "' DAY) < sA.created) AND\n";
			$sql_3 = $sql_3 . "(\n";	

                        for ($j=0;$j<sizeof($teacher_ids);$j++) {
                                $sql_3 = $sql_3 . "poster = '" . $teacher_ids[$j] . "'";
                                if ($j != (sizeof($teacher_ids) - 1))
                                        $sql_3 = $sql_3 . " OR ";
                                else
                                        $sql_3 = $sql_3 . "\n)";
                        }

                        $result_3 = mysql_db_query($DB.$course_id,$sql_3,$conn);
                        if (!$result_3) continue;
                        if (mysql_num_rows($result_3) == 0) continue;
                        $row_3 = mysql_fetch_array($result_3);

                        $count += $row_3[0];
                }

		$denominator = getTchStuPosts($course_id,0);
		if ($denominator != 0)
			return round((float)($count/$denominator),3) * 100;
		else
			return 0;
	}

	//�����C�g�o��ĳ�D�Ӽ�
	function getAvgPostPerWeek($course_id) {
		$posts = getTeacherPosts($course_id,0) + getStudentPosts($course_id,0);
                return round((float)($posts/TOTAL_WEEKS),1); //�u�d��p�ƫ�Ĥ@��	
	}

	//�ǥͥ����C�g�o������
	function getStuAvgPostsPerWeek($course_id) {
		$posts = getStudentPosts($course_id,0) + getStudentPosts($course_id,1);
		return round((float)($posts/TOTAL_WEEKS),1); //�u�d��p�ƫ�Ĥ@��
	}

	//�Q�ת���
	function getBoards($course_id) {
		global $DB;

		$conn = getSQLconnection();
		if (!$conn) return -1;

		$sql_1 = "SELECT COUNT(*) FROM `discuss_info`";
		$result_1 = mysql_db_query($DB.$course_id,$sql_1,$conn);
		if (!$result_1) return -1;
		$row_1 = mysql_fetch_array($result_1);

		return $row_1[0];
	}

	//�Q�װϤ峹�`��
	function getPosts($course_id) {
		return getTchStuPosts($course_id,0) + getTchStuPosts($course_id,1);
	}

	//�ǥ͵o��($is_reply = 0)�Φ^��($is_reply = 1)�`��
	function getStudentPosts($course_id,$is_reply) {
		return getTchStuPosts($course_id,$is_reply) - getTeacherPosts($course_id,$is_reply);
	}

	//�Ѯv�o��($is_reply = 0)�Φ^��($is_reply = 1)�`��
	function getTeacherPosts($course_id,$is_reply) {
		global $DB;

		$conn = getSQLconnection();
		if (!$conn) return -1;

		//�U�Q�ת�����ƪ�W��
		$board_names = getBoardTableNames($conn,$DB,$course_id);

		//���o�o��ҦѮv�̪�id (�t�U��)
		$teacher_ids = array();
		$teacher_count = 0;

		$sql_1 = "SELECT teacher_id FROM `teach_course` WHERE course_id = '" . $course_id . "'";
		$result_1 = mysql_db_query($DB,$sql_1,$conn);
		if (!$result_1) return -1;
		if (mysql_num_rows($result_1) == 0) return -1;

		while ($row_1 = mysql_fetch_array($result_1)) {
			$sql_2 = "SELECT id FROM `user` WHERE a_id = '" . $row_1['teacher_id'] . "'";
			$result_2 = mysql_db_query($DB,$sql_2,$conn);
			if (!$result_2) continue;
			if (mysql_num_rows($result_2) == 0) continue;
			$row_2 = mysql_fetch_array($result_2);

			$teacher_ids[$teacher_count] = $row_2['id'];
			$teacher_count++;
		}

		if ($teacher_count == 0) return -1;

		//�p��Ѯv�o��Φ^���`��
		$posts = 0;
		for ($i=0;$i<sizeof($board_names);$i++) { 
			if ($is_reply == 0)
				$sql_3 = "SELECT COUNT(*) FROM `" . $board_names[$i] . "` WHERE parent = 0 AND (";
			else
				$sql_3 = "SELECT COUNT(*) FROM `" . $board_names[$i] . "` WHERE parent != 0 AND (";
			for ($j=0;$j<sizeof($teacher_ids);$j++) {
				$sql_3 = $sql_3 . "poster = '" . $teacher_ids[$j] . "'";
				if ($j != (sizeof($teacher_ids) - 1))
					$sql_3 = $sql_3 . " OR ";
				else
					$sql_3 = $sql_3 . ")";
			}

			$result_3 = mysql_db_query($DB.$course_id,$sql_3,$conn);
			if (!$result_3) continue;
			if (mysql_num_rows($result_3) == 0) continue;
			$row_3 = mysql_fetch_array($result_3);

			$posts += $row_3[0];
		}

		return $posts;
	}

	//�Ѯv�ξǥ͵o��($is_reply = 0)�Φ^��($is_reply = 1)�`��
	function getTchStuPosts($course_id,$is_reply) {
		global $DB;

                $conn = getSQLconnection();
                if (!$conn) return -1;

                //�U�Q�ת�����ƪ�W��
                $board_names = getBoardTableNames($conn,$DB,$course_id);

                $posts = 0;
                for ($i=0;$i<sizeof($board_names);$i++) {
                        if ($is_reply == 0)
                                $sql_1 = "SELECT COUNT(*) FROM `" . $board_names[$i] . "` WHERE parent = 0";
                        else
                                $sql_1 = "SELECT COUNT(*) FROM `" . $board_names[$i] . "` WHERE parent != 0";
                        $result_1 = mysql_db_query($DB.$course_id,$sql_1,$conn);
                        if (!$result_1) continue;
                        if (mysql_num_rows($result_1) == 0) continue;
                        $row_1 = mysql_fetch_array($result_1);

                        $posts += $row_1[0];
                }

                return $posts;
	}

	//���o�U�Q�ת�����ƪ�W�� (e.g. discuss_1, discuss_2, ...)
	function getBoardTableNames($conn,$DB,$course_id) {
		$board_names = array();
		$board_count = 0;

		$sql_1 = "SELECT a_id FROM `discuss_info`";
		$result_1 = mysql_db_query($DB.$course_id,$sql_1,$conn);
		if (!$result_1) return $board_names;
		if (mysql_num_rows($result_1) == 0) return $board_names;
		
		while ($row_1 = mysql_fetch_array($result_1)) {
			$board_names[$board_count] = "discuss_" . $row_1['a_id'];
			$board_count++;
		}

		return $board_names;
	}

	function getSQLconnection() {
		global $DB_SERVER,$DB_LOGIN,$DB_PASSWORD;
		$conn = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD);
		return $conn;
	}

?>
