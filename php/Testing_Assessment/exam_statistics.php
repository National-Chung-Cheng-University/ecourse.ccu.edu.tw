<?php
	/*
	@ Author: carlyle
	*/

	require 'fadmin.php';

	//global variables
	global $DB_SERVER,$DB_LOGIN,$DB,$DB_PASSWORD,$version,$course_id,$user_id,$skinnum;

	//�v�����~
	if (!isset($PHPSESSID) || (session_check_teach($PHPSESSID) < 2)) {
		if ($version == "C")
			show_page("not_access.tpl","�A�S���v���ϥΦ��\��");
		else
			show_page("not_access.tpl","You have No Permission!!");
	}

	//connect to DB
	if (!($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD))) {
		show_page("not_access.tpl","��Ʈw�s�����~!");
		exit();
	}

	//template
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate("./templates");
	$tpl->define(array(main=>"exam_statistics.tpl"));
	$tpl->define_dynamic(c1,"main");
	$tpl->define_dynamic(c2,"main");
	$tpl->assign(MESSAGE,"���絪�ײέp (Exam Statistics)");
	$tpl->assign(SKINNUM,$skinnum);

	//retrieve exam_id
	$exam_id = $_GET['exam_id'];
	if (!isset($exam_id)) {
		show_page("not_access.tpl","���~��exam_id!");
		exit();
	}
	
	//�έp����D������ (�`�N: $tpl��call by reference���覡)
	_selection_problem($DB,$link,&$tpl,$course_id,$exam_id);

	//�έp�O�D�D������ (�`�N: $tpl��call by reference���覡)
	_yesorno_problem($DB,$link,&$tpl,$course_id,$exam_id);

	//display 
	$tpl->parse(BODY,"main");
	$tpl->FastPrint("BODY");
	exit();




// functions
//---------------------------------------------------------------------

	/* �έp����D������ */
	function _selection_problem($DB,$DB_CONN,$tpl,$course_id,$exam_id) {
		
		// added by jimmykuo @ 20101012, �\��:���o���T���D��
		$q3 = "SELECT a_id FROM tiku WHERE exam_id='".$exam_id."' ORDER BY 'a_id' ASC";
		if (!($result3 = mysql_db_query($DB.$course_id,$q3,$DB_CONN))) {
			show_page("not_access.tpl","��ƮwŪ�����~!!");
			exit();
		}
		$row3 = mysql_fetch_array($result3);
		$bias = $row3['a_id'];

		//���X�o�Ӵ���Ҧ�����D���D��
                $q1 = "SELECT a_id,ismultiple,question,answer FROM `tiku` WHERE exam_id='" . $exam_id . "' AND type='1' ORDER BY `a_id` ASC";
                if (!($result1 = mysql_db_query($DB.$course_id,$q1,$DB_CONN))) {
                        show_page("not_access.tpl","��ƮwŪ�����~!!");
			exit();
		}

		$total = mysql_num_rows($result1);
		if (!$total) { //�o�Ӵ��礣�]�t����D
			$tpl->assign(Q1,"");
			$tpl->assign(ANS1,"");
			$tpl->assign(ANS2,"");
			$tpl->assign(ANS3,"");
			$tpl->assign(ANS4,"");
			$tpl->assign(ANSR1,"");
			$tpl->assign(ANSR2,"");
			$tpl->assign(ANSR3,"");
			$tpl->assign(ANSR4,"");
			$tpl->assign(C1,"");
			$tpl->assign(CR1,"");
			return;
		}

		$td_color = "#F0FFEE";
		for ($i = 0;$i < $total;$i++) {
			//next
                        $row1 = mysql_fetch_array($result1);

			//���O���1,2,3,4�H�ε��諸�H��
			$sel_1_count = 0;
			$sel_2_count = 0;
			$sel_3_count = 0;
			$sel_4_count = 0;
			$sel_correct_count = 0;

			//���X�o�D�w�@���ǥͪ�����
			$q2 = "SELECT stu_ans_1 FROM `qa2` WHERE tiku_a_id='" . $row1['a_id'] . "' AND exam_id='" . $exam_id . "' AND type='1'";
			if (!($result2 = mysql_db_query($DB.$course_id,$q2,$DB_CONN))) {
				show_page("not_access.tpl","��ƮwŪ�����~!!");
				exit();
			}

			//�w�@���ǥͪ��ƥ�
			$stu_count = mysql_num_rows($result2);

			//�ˬd�C�Ӿǥͪ�����
			for ($j = 0;$j < $stu_count;$j++) {
				$row2 = mysql_fetch_array($result2);

				//���׬O�_���T
				if ($row1['answer'] == $row2['stu_ans_1']) $sel_correct_count++;

				if ($row1['ismultiple'] == '1') { //�ƿ��D
					if (($row2['stu_ans_1'] & 1) > 0) //�ﶵ�@�Q��F
						$sel_1_count++;

					if (($row2['stu_ans_1'] & 2) > 0) //�ﶵ�G�Q��F
						$sel_2_count++;

					if (($row2['stu_ans_1'] & 4) > 0) //�ﶵ�T�Q��F
						$sel_3_count++;

					if (($row2['stu_ans_1'] & 8) > 0) //�ﶵ�|�Q��F
						$sel_4_count++;
				} else { //����D
					if ($row2['stu_ans_1'] == '1')
						$sel_1_count++;
					else if ($row2['stu_ans_1'] == '2')
						$sel_2_count++;
					else if ($row2['stu_ans_1'] == '4')
						$sel_3_count++;
					else if ($row2['stu_ans_1'] == '8')
						$sel_4_count++;
				}
			}
			
			//assign template values
			if ($td_color == "#E6FFFC") {
				$tpl->assign(COLOR,$td_color);
				$td_color = "#F0FFEE";
			} else {
				$tpl->assign(COLOR,$td_color);
				$td_color = "#E6FFFC";
			}

			$Q_no = $row1['a_id']-$bias+1;
			$Q_NAME = "��" . $Q_no . "�D";

			if ($row1['ismultiple'] == '1') $Q_NAME = $Q_NAME . " (�ƿ�)";	
			$tpl->assign(Q1,$Q_NAME);

			if ($stu_count != 0)
				$rate = (int)(($sel_1_count / $stu_count) * 100);
			else
				$rate = 0;
			$tpl->assign(ANS1,$sel_1_count);
			$tpl->assign(ANSR1,($rate . "%"));

			if ($stu_count != 0)
				$rate = (int)(($sel_2_count / $stu_count) * 100);
			else
				$rate = 0;
			$tpl->assign(ANS2,$sel_2_count);
			$tpl->assign(ANSR2,($rate . "%"));

			if ($stu_count != 0)
				$rate = (int)(($sel_3_count / $stu_count) * 100);
			else
				$rate = 0;
			$tpl->assign(ANS3,$sel_3_count);
			$tpl->assign(ANSR3,($rate . "%"));

			if ($stu_count != 0)
				$rate = (int)(($sel_4_count / $stu_count) * 100);
			else
				$rate = 0;
			$tpl->assign(ANS4,$sel_4_count);
			$tpl->assign(ANSR4,($rate . "%"));

			if ($stu_count != 0)
				$rate = (int)(($sel_correct_count / $stu_count) * 100);
			else
				$rate = 0;
			$tpl->assign(C1,$sel_correct_count);
			$tpl->assign(CR1,($rate . "%"));

			$tpl->parse(C1,".c1");
		}
	}

	/* �έp�O�D�D������ */
	function _yesorno_problem($DB,$DB_CONN,$tpl,$course_id,$exam_id) {

 		// added by jimmykuo @ 20101012, �\��:���o���T���D��
		$q3 = "SELECT a_id FROM tiku WHERE exam_id='".$exam_id."' ORDER BY 'a_id' ASC";
		if (!($result3 = mysql_db_query($DB.$course_id,$q3,$DB_CONN))) {
			show_page("not_access.tpl","��ƮwŪ�����~!!");
			exit();
 		}
		$row3 = mysql_fetch_array($result3);
		$bias = $row3['a_id'];


		//���X�o�Ӵ���Ҧ��O�D�D���D��
                $q1 = "SELECT a_id,ismultiple,question,answer FROM `tiku` WHERE exam_id='" . $exam_id . "' AND type='2' ORDER BY `a_id` ASC";
                if (!($result1 = mysql_db_query($DB.$course_id,$q1,$DB_CONN))) {
                        show_page("not_access.tpl","��ƮwŪ�����~!!");
			exit();
		}

		$total = mysql_num_rows($result1);
		if (!$total) { //�o�Ӵ��礣�]�t�O�D�D
			$tpl->assign(Q2,"");
			$tpl->assign(NO,"");
			$tpl->assign(NR,"");
			$tpl->assign(YES,"");
			$tpl->assign(YR,"");
			$tpl->assign(C2,"");
			$tpl->assign(CR2,"");
			return;
		}

		$td_color = "#F0FFEE";
		for ($i = 0;$i < $total;$i++) {
			//next
                        $row1 = mysql_fetch_array($result1);

			//���O���yes or no�H�ε��諸�H��
			$sel_yes_count = 0;
			$sel_no_count = 0;
			$sel_correct_count = 0;

			//���X�o�D�w�@���ǥͪ�����
			$q2 = "SELECT stu_ans_1 FROM `qa2` WHERE tiku_a_id='" . $row1['a_id'] . "' AND exam_id='" . $exam_id . "' AND type='2'";
			if (!($result2 = mysql_db_query($DB.$course_id,$q2,$DB_CONN))) {
				show_page("not_access.tpl","��ƮwŪ�����~!!");
				exit();
			}

			//�w�@���ǥͪ��ƥ�
			$stu_count = mysql_num_rows($result2);

			//�ˬd�C�Ӿǥͪ�����
			for ($j = 0;$j < $stu_count;$j++) {
				$row2 = mysql_fetch_array($result2);

				//���׬O�_���T
				if ($row1['answer'] == $row2['stu_ans_1']) $sel_correct_count++;

				if ($row2['stu_ans_1'] == '1') //���yes
					$sel_yes_count++;
				else //���no
					$sel_no_count++;
			}
			
			//assign template values
			if ($td_color == "#E6FFFC") {
				$tpl->assign(COLOR,$td_color);
				$td_color = "#F0FFEE";
			} else {
				$tpl->assign(COLOR,$td_color);
				$td_color = "#E6FFFC";
			}
	
			$Q_no = $row1['a_id'] - $bias + 1;
			$Q_NAME = "��" . $Q_no . "�D";
			$tpl->assign(Q2,$Q_NAME);

			if ($stu_count != 0)
				$rate = (int)(($sel_no_count / $stu_count) * 100);
			else
				$rate = 0;
			$tpl->assign(NO,$sel_no_count);
			$tpl->assign(NR,($rate . "%"));

			if ($stu_count != 0)
				$rate = (int)(($sel_yes_count / $stu_count) * 100);
			else
				$rate = 0;
			$tpl->assign(YES,$sel_yes_count);
			$tpl->assign(YR,($rate . "%"));

			if ($stu_count != 0)
				$rate = (int)(($sel_correct_count / $stu_count) * 100);
			else
				$rate = 0;
			$tpl->assign(C2,$sel_correct_count);
			$tpl->assign(CR2,($rate . "%"));

			$tpl->parse(C2,".c2");
		}
	}
?>
