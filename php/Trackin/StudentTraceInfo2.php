<?php
	/*
	@ Author: carlyle
	@ Description: 顯示學生作業&測驗列表
	*/

	require 'fadmin.php';

	global $DB_SERVER,$DB_LOGIN,$DB,$DB_PASSWORD,$PHPSESSID,$course_id,$user_id;

	if(!(isset($PHPSESSID) && $check = session_check_teach($PHPSESSID))) {
		show_page("not_access.tpl","權限錯誤");
		exit();
	}

	if (!($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD))) {
		show_page("not_acess.tpl","資料庫連結錯誤!!");
		exit();
	}

	$student_aid = $_GET['student_aid'];
	if (isset($student_aid))
		$useraid = $student_aid;
	else {
		$Q_useraid = "SELECT a_id FROM `user` WHERE id = '" . $user_id . "'";
		if (!($result_useraid = mysql_db_query($DB,$Q_useraid)))
			show_page("not_access.tpl","資料庫讀取錯誤!!");
		else {
			$row_useraid = mysql_fetch_array($result_useraid);
			$useraid = $row_useraid['a_id']; //user a_id
		}
	}

	include("class.FastTemplate.php3");
        $tpl = new FastTemplate("./templates");
        if ($version == "C")
                $tpl->define(array(student_info => "StudentTraceInfo2_Ch.tpl"));
        else
                $tpl->define(array(student_info => "StudentTraceInfo2_En.tpl"));

	// 作業列表
	// -----------------------------------------------------------------
	$tpl->define_dynamic("homework","student_info");

	$Q_hw = "SELECT name,percentage,due,public,a_id,chap_num,late FROM homework WHERE public='1' OR public='3' ORDER BY chap_num, a_id";
	if (!($result_hw = mysql_db_query($DB.$course_id,$Q_hw))) {
		show_page("not_access.tpl","資料庫讀取錯誤!!");
	}

	if (mysql_num_rows($result_hw) != 0) {
		while ($row_hw = mysql_fetch_array($result_hw)) {
			//名稱
			$tpl->assign("WORKNAME",$row_hw['name']);

			//題目
			$tmp_link = "<a href=\"#\" onClick=\"window.open('../Testing_Assessment/show_allwork.php?work_id=" . $row_hw['a_id'] . "&action=showwork&PHPSESSID=" . $PHPSESSID . "', '', 'width=800,height=600,resizable=1,scrollbars=1');\">作業題目</a>";
			$tpl->assign("WORKLINK",$tmp_link);

			//答案
			if($row_hw['public'] == "1")
				$tmp_answer = "<input type=submit value=未公佈><input type=hidden name=work_id value=" . $row_hw['a_id'] . "><input type=hidden name=action value=seeans>";
			else if ($row_hw['public'] == "3")
				$tmp_answer = "<input type=submit value=答案><input type=hidden name=work_id value=" . $row_hw['a_id'] . "><input type=hidden name=action value=seeans>";
			$tpl->assign("WORKANSWER",$tmp_answer);

			//分數
			$Q_hw_grade = "select h.name, hh.grade FROM homework h, handin_homework hh WHERE hh.student_id = '" . $useraid . "' and hh.homework_id = h.a_id and (h.public = '1' or h.public = '3')";
			if (!($result_hw_grade = mysql_db_query($DB.$course_id,$Q_hw_grade))) {
				show_page("not_access.tpl","資料庫讀取錯誤!!");
			}

			$tmp_grade = "";
			if (mysql_num_rows($result_hw_grade) != 0) {
				while ($row_hw_grade = mysql_fetch_array($result_hw_grade)) {
					//echo $row_hw_grade['name'] . " = " . $row_hw_grade['grade'] . "<br/>";
					if ($row_hw_grade['name'] == $row_hw['name']) {
						$tmp_grade = $row_hw_grade['grade'];
						break;
					}
				}
			}

			if ($tmp_grade == "") $tmp_grade = "你沒有成績";
			$tpl->assign("WORKGRADE",$tmp_grade);

			$tpl->parse(HOMEWORK,".homework");
		}
	} else {
		$tpl->assign("WORKNAME","");
		$tpl->assign("WORKLINK","");
		$tpl->assign("WORKANSWER","");
		$tpl->assign("WORKGRADE","");
	}
	// -----------------------------------------------------------------

	// 學生測驗
	// -----------------------------------------------------------------
	$tpl->define_dynamic("exam","student_info");

	$Q_exam = "SELECT e.name,e.percentage,e.a_id,te.grade,e.end_time, e.end_time,e.chap_num FROM exam e,take_exam te WHERE te.student_id = '".$useraid."' and e.a_id=te.exam_id AND e.is_online='1' AND ( e.public='1' ||  e.end_time != '00000000000000' ) and e.beg_time <= ".date("YmdHis")." ORDER BY e.name";
	if (!($result_exam = mysql_db_query($DB.$course_id,$Q_exam))) {
		show_page("not_access.tpl","資料庫讀取錯誤!!");
	}

	if (mysql_num_rows($result_exam) != 0) {
		while ($row_exam = mysql_fetch_array($result_exam)) {
			//名稱
			$tpl->assign("EXAMNAME",$row_exam['name']);

			//測驗
			$tmp_link = "<a href=\"../Testing_Assessment/show_alltest.php?exam_id=" . $row_exam['a_id'] . "&action=takeexam\">";
			if ($row_exam['end_time'] > date("YmdHis"))
				$tmp_link = $tmp_link . "進入測驗" . "</a>";
			else
				$tmp_link = $tmp_link . "觀看解答" . "</a>";
			$tpl->assign("EXAMLINK",$tmp_link);

			//分數
			if ($row_exam['grade'] != "-1") {
				$Q_exam2 = "SELECT public FROM take_exam WHERE exam_id='" . $row_exam['a_id'] . "'";
				$result_exam2 = mysql_db_query($DB.$course_id,$Q_exam2);
				$row_exam2 = mysql_fetch_array($result_exam2);

				if ($row_exam2['public'] == 1)
					$tpl->assign("EXAMGRADE",$row_exam['grade']);
				else if ($row_exam2['public'] == 0)
					$tpl->assign("EXAMGRADE","老師尚未公佈成績");
			} else
				$tpl->assign("EXAMGRADE","未受測驗");

			$tpl->parse(EXAM,".exam");
		}
	} else {
		$tpl->assign("EXAMNAME","");
		$tpl->assign("EXAMLINK","");
		$tpl->assign("EXAMGRADE","");
	}
	// -----------------------------------------------------------------

	$tpl->parse(BODY,"student_info");
	$tpl->FastPrint("BODY");
?>
