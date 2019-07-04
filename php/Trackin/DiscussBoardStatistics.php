<?php
	/*
	@ Author: carlyle
	@ Description: 收集認證所需的一些討論區統計資料
	@ Date: 2008/2/14
	*/

	require_once 'fadmin.php';

	//一學期週數
	define("TOTAL_WEEKS","18");

	//文章平均回覆時間, return值的單位為天 (day)
	function getAvgReplyDelay($course_id) {
                global $DB;

                $conn = getSQLconnection();
                if (!$conn) return -1;

                //各討論版的資料表名稱
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
                        $sql_1 = $sql_1 . "ORDER BY parent, 2 ASC\n"; //這裡的"2"指的是TIMEDIFF計算結果那欄

			$result_1 = mysql_db_query($DB.$course_id,$sql_1,$conn);
                        if (!$result_1) continue;
                        if (mysql_num_rows($result_1) == 0) continue;

			$last_parent_id = -1;
			while ($row_1 = mysql_fetch_array($result_1)) {
				//只取每個主題的第一篇回覆文章來計算發文的時間差
				if ($last_parent_id != $row_1['parent'])
					$last_parent_id = $row_1['parent'];
				else
					continue;
			
				//SQL算出的時間差 (xx:xx:xx)
				$diff = $row_1[3];
				list($t_hour,$t_minute,$t_second) = sscanf($diff,"%d:%d:%d");

				//rhhwang said: 超過兩週的不列入計算
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

	//老師或助教於n天內($after = 0)或n天後($after = 1)回覆比率, return值的單位為%
	function getTchReplyRatio($course_id,$n,$after) {
		global $DB;

                $conn = getSQLconnection();
                if (!$conn) return -1;

                //各討論版的資料表名稱
                $board_names = getBoardTableNames($conn,$DB,$course_id);

                //取得這堂課老師們的id (含助教)
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
			if ($after == 0) //n天內
				$sql_3 = $sql_3 . "(DATE_ADD(sB.created, INTERVAL '" . $n . "' DAY) >= sA.created) AND\n";
			else //n天後
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

	//平均每週發表議題個數
	function getAvgPostPerWeek($course_id) {
		$posts = getTeacherPosts($course_id,0) + getStudentPosts($course_id,0);
                return round((float)($posts/TOTAL_WEEKS),1); //只留到小數後第一位	
	}

	//學生平均每週發言次數
	function getStuAvgPostsPerWeek($course_id) {
		$posts = getStudentPosts($course_id,0) + getStudentPosts($course_id,1);
		return round((float)($posts/TOTAL_WEEKS),1); //只留到小數後第一位
	}

	//討論版數
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

	//討論區文章總數
	function getPosts($course_id) {
		return getTchStuPosts($course_id,0) + getTchStuPosts($course_id,1);
	}

	//學生發文($is_reply = 0)或回覆($is_reply = 1)總數
	function getStudentPosts($course_id,$is_reply) {
		return getTchStuPosts($course_id,$is_reply) - getTeacherPosts($course_id,$is_reply);
	}

	//老師發文($is_reply = 0)或回覆($is_reply = 1)總數
	function getTeacherPosts($course_id,$is_reply) {
		global $DB;

		$conn = getSQLconnection();
		if (!$conn) return -1;

		//各討論版的資料表名稱
		$board_names = getBoardTableNames($conn,$DB,$course_id);

		//取得這堂課老師們的id (含助教)
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

		//計算老師發文或回覆總數
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

	//老師及學生發文($is_reply = 0)或回覆($is_reply = 1)總數
	function getTchStuPosts($course_id,$is_reply) {
		global $DB;

                $conn = getSQLconnection();
                if (!$conn) return -1;

                //各討論版的資料表名稱
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

	//取得各討論版的資料表名稱 (e.g. discuss_1, discuss_2, ...)
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
