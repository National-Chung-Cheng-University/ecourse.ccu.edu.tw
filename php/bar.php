<?php
	require 'fadmin.php';
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $skinnum;
	$Q3 = "SELECT que FROM function";
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$error = "��Ʈw�s�����~!!";
		show_page ( "not_access.tpl", $error );
	}
	else if ( !($result = mysql_db_query( $DB, $Q3  )) ) {
		$error = "��ƮwŪ�����~!!";
		show_page ( "not_access.tpl", $error );
	}
	else
		$questionary = mysql_fetch_array($result);

	if ( $questionary['que'] ==1 ){  //�}�Ҿǥ�"�ݨ��լd" &&�Ѯv�L�k�ݲέp
		$bars_content = array ( "�̷s����", "�ҵ{��T", "�ҵ{�Ч�", "�ۧڵ��q", "�Q�װ�", "�ӤH�u��" ,"�ݨ��լd");
		$bars_content_E = array ( "Announcement", "Course Information", "Courseware", "Homework&Quiz", "Discussion", "Tools","Midterm Questionary" );
		$bars_show = array ( "show", "show", "show", "show", "show", "show","show" );
		//�Ѯv��bar
		$bar_content = array ( "�ҵ{��T", "���Z�t��", "�½ұЧ�", "�u�W�@�~", "�u�W����", "�u�W�ݨ�", "�Q�װ�", "�ǲ߰l��", "�ǥͺ޲z" );
		$bar_content_E = array ( "Course Information", "Score", "Courseware", "Homework", "Online Quiz", "Questionary", "Discussion", "Traces", "ST. Management" );
		$bar_show = array ( "show", "show", "show", "show", "show", "show", "show", "show", "show" );
	}
	else if ( $questionary['que'] ==0 ){  //�����ǥ�"�ݨ��լd" &&�Ѯv�i�ݲέp
		$bars_content = array ( "�̷s����", "�ҵ{��T", "�ҵ{�Ч�", "�ۧڵ��q", "�Q�װ�", "�ӤH�u��" );
		$bars_content_E = array ( "Announcement", "Course Information", "Courseware", "Homework&Quiz", "Discussion", "Tools" );
		$bars_show = array ( "show", "show", "show", "show", "show", "show" );
		//�Ѯv��bar
		$bar_content = array ( "�ҵ{��T", "���Z�t��", "�½ұЧ�", "�u�W�@�~", "�u�W����", "�u�W�ݨ�", "�Q�װ�", "�ǲ߰l��", "�ǥͺ޲z", "�����ݨ�" );
		$bar_content_E = array ( "Course Information", "Score", "Courseware", "Homework", "Online Quiz", "Questionary", "Discussion", "Traces", "ST. Management","Midterm Questionary" );
		$bar_show = array ( "show", "show", "show", "show", "show", "show", "show", "show", "show", "show" );
	}
	else {   //que=2�A�ǥ�"�ݨ��լd" &&�Ѯv�έp������
		$bars_content = array ( "�̷s����", "�ҵ{��T", "�ҵ{�Ч�", "�ۧڵ��q", "�Q�װ�", "�ӤH�u��" );
		$bars_content_E = array ( "Announcement", "Course Information", "Courseware", "Homework&Quiz", "Discussion", "Tools" );
		$bars_show = array ( "show", "show", "show", "show", "show", "show" );
		//�Ѯv��bar
		$bar_content = array ( "�ҵ{��T", "���Z�t��", "�½ұЧ�", "�u�W�@�~", "�u�W����", "�u�W�ݨ�", "�Q�װ�", "�ǲ߰l��", "�ǥͺ޲z" );
		$bar_content_E = array ( "Course Information", "Score", "Courseware", "Homework", "Online Quiz", "Questionary", "Discussion", "Traces", "ST. Management" );
		$bar_show = array ( "show", "show", "show", "show", "show", "show", "show", "show", "show" );
	}
	if ( !(isset($PHPSESSID) && session_check_stu($PHPSESSID)) )
		show_page( "not_access.tpl" ,"�v�����~");
		
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $skinnum;
	$Q1 = "SELECT a_id,authorization FROM user where id = '$user_id'";
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$error = "��Ʈw�s�����~!!";
		show_page ( "not_access.tpl", $error );
	}
	else if ( !($result = mysql_db_query( $DB, $Q1  )) ) {
		$error = "��ƮwŪ�����~!!1";
		show_page ( "not_access.tpl", $error );
	}
	else if ( mysql_num_rows( mysql_db_query( $DB, $Q1 ) ) == 0 ) {
		$error = "�L���ϥΪ�!!";
		show_page ( "not_access.tpl", $error );
	}
	else
		$row = mysql_fetch_array($result);

	//�����Ǵ���T
	$Qsemester = "select * from this_semester";
	$resultsemester = mysql_db_query($DB, $Qsemester);
	$rowssemester = mysql_fetch_array($resultsemester);
	/********************�P�_�W�U�Ǵ��ӵ�����**************************/
	$p_n_year = 0;	//�w�]�W�U�Ǵ�year = 0 , term = 0
	$p_n_term = 0;
	$is_next = 0;   
	if($rowssemester['term'] == 1){
		$Qhave_next = "SELECT * FROM teach_course WHERE year ='".$rowssemester['year']."' AND term = '2'";
	}
	else{
		$Qhave_next = "SELECT * FROM teach_course WHERE year ='".($rowssemester['year']+1)."' AND term = '1'";
	}
	if ( !($result_have = mysql_db_query( $DB, $Qhave_next ) ) ) {
		$error = "��ƮwŪ�����~!!Q";
		show_page ( "not_access.tpl", $error );
	}
	else{
		if(mysql_num_rows($result_have) == 0){
			//�S���U�Ǵ� ��ܤW�Ǵ�
			$is_next = 0;
			if($rowssemester['term'] == 1){
				$p_n_year = $rowssemester['year']-1;
				$p_n_term = 2;
			}
			else{
				$p_n_year = $rowssemester['year'];
				$p_n_term = 1;
			}
		}
		else{
			//���U�Ǵ�
			$is_next = 1;
			if($rowssemester['term'] == 1){
				$p_n_year = $rowssemester['year'];
				$p_n_term = 2;
			}
			else{
				$p_n_year = $rowssemester['year']+1;
				$p_n_term = 1;
			}
		}
	}
	/**************************************************************/
	if ( $row["authorization"] <= 2 && $teacher == "1" ){
		//�h���oyear�Mterm 2007-1-2
		//���o���Ǵ����ҵ{��T
		$Q21 = "SELECT t.course_id, c.name, c.course_no, t.year, t.term  from teach_course t, course c where t.teacher_id = '".$row["a_id"]."' and c.a_id = t.course_id and t.year = $rowssemester[year] and t.term = $rowssemester[term] order by t.year desc, t.term asc, c.group_id ASC, c.a_id ASC";
		//���o�W�U�Ǵ����ҵ{��T
		$Q22 = "SELECT t.course_id, c.name, c.course_no, t.year, t.term  from teach_course t, course c where t.teacher_id = '".$row["a_id"]."' and c.a_id = t.course_id and t.year = $p_n_year and t.term = $p_n_term order by t.year desc, t.term asc, c.group_id ASC, c.a_id ASC";
	}
	else if ( $row["authorization"] == "9" )
		$Q2 = "SELECT a_id as course_id, name , course_no from course where validated%2 != '1'";
	else{
		$Q2 = "SELECT t.course_id, t.year, t.term , c.name, c.course_no from take_course t, course c where t.student_id = '".$row["a_id"]."' and c.a_id = t.course_id and (( t.credit = '0' and c.validated%2 != '1') or t.credit = '1') and (t.year >= $rowssemester[year] and t.term >= $rowssemester[term]) order by t.year asc, t.term asc, c.group_id ,c.a_id";
	}

	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$error = "��Ʈw�s�����~!!";
		show_page ( "not_access.tpl", $error );
	}
	if($row["authorization"] <= 2 && $teacher == "1"){
		if ( !($result21 = mysql_db_query( $DB, $Q21  )) ) {
			$error = "��ƮwŪ�����~!!21";
			show_page ( "not_access.tpl", $error );
		}
		if ( !($result22 = mysql_db_query( $DB, $Q22  )) ) {
			$error = "��ƮwŪ�����~!!22";
			show_page ( "not_access.tpl", $error );
		}
	}
	else{
		if ( !($result2 = mysql_db_query( $DB, $Q2  )) ) {
			$error = "��ƮwŪ�����~!!2";
			show_page ( "not_access.tpl", $error );
		}
	}	
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate ( "./templates" );
	$tpl->assign( SKINNUM , $skinnum );
	$tpl->assign( PHPID, $PHPSESSID);
	if ( $row["authorization"] <= 2 && $teacher == "1" ) {
		if( $version == "C" ) {
			$tpl->define ( array ( body => "bar1.tpl") );
		}
		else {
			$tpl->define ( array ( body => "bar1_E.tpl") );
		}
		$tpl->define_dynamic ( "course_list" , "body" );
		//�s�Wgroup
		$tpl->define_dynamic ( "course_group" , "body" );
		//
		
		$Qid = "select a_id from mid_subject where year = '".$rowssemester['year']."' and term = '".$rowssemester['term']."'";
		$resultqid = mysql_db_query($DB,$Qid);
		$rowsqid = mysql_fetch_array($resultqid);
		$tpl->assign( QID, $rowsqid['a_id'] );
		
		$Q3 = "SELECT * FROM function_list where u_id='$user_id'";
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "��Ʈw�s�����~!!";
			show_page ( "not_access.tpl", $error );
		}
		else if ( !($result3 = mysql_db_query( "study".$course_id, $Q3  )) ) {
			$error = "��ƮwŪ�����~!!!";
			show_page ( "not_access.tpl", $error );
		}
		$function = mysql_fetch_array($result3);
		
		if ($function['sched']) {
			if ( $version == "C" ) {
				$tpl->assign( SCHED, "<td width=\"70\"><div align=\"center\"><a href=#./Courses_Admin/show_sched.php onClick=parent.target.window.location=\"./Courses_Admin/show_sched.php\"><font size=\"2\">��</font><font color=\"#000000\"><font size=\"2\">�ҵ{�w��</font></font></a></div></td>" );
			}
			else {
				$tpl->assign( SCHED, "<td width=\"70\"><div align=\"center\"><a href=./Courses_Admin/show_sched.php onClick=parent.target.window.location=\"\"><font size=\"2\">��</font><font color=\"#000000\"><font size=\"2\">Weekly Schedule</font></font></a></div></td>" );
			}
		}
		else{
			$tpl->assign( SCHED, "");
		}
			
		if($function['officehr']) {
			if ( $version == "C" ) {
				$tpl->assign( OFFICEHR, "<td width=\"90\"><div align=\"center\"><font size=\"2\"><a href=\"#./Learner_Profile/office_time_teacher.php\" onClick=parent.target.window.location=\"./Learner_Profile/office_time_teacher.php\"><font size=\"2\">��</font><font color=\"#000000\">�줽�Ǯɶ�</font></a></font></div></td>");
			}
			else {
				$tpl->assign( OFFICEHR, "<td width=\"90\"><div align=\"center\"><font size=\"2\"><a href=\"./Learner_Profile/office_time_teacher.php\" onClick=parent.target.window.location=\"\"><font size=\"2\">��</font><font color=\"#000000\">Office Hour</font></a></font></div></td>");
			}
		}
		else{
			$tpl->assign( OFFICEHR, "");
		}
		
		if($function['core']) {
			if ( $version == "C" ) {
				$tpl->assign( CORE, "<td><div align=\"center\"><a href=\"#./ClassRelative/relative_table.php\" onClick=parent.target.window.location=\"./ClassRelative/relative_table.php\"><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">�ҵ{���[</font></font></a></div></td>");
			}
			else {
				$tpl->assign( CORE, "<td><div align=\"center\"><a href=\"./ClassRelative/relative_table.php\" onClick=parent.target.window.location=\"\"><font size=\"2\">>��</font><font size=\"2\"><font color=\"#000000\">Core Competencies</font></font></a></div></td>");
			}
		}
		else{
			$tpl->assign( CORE, "");
		}

		if($function['evaluate']) {
			if ( $version == "C" ) {
				$tpl->assign( EVALUATE, "<td><div align=\"center\"><a href=#./Self_Evaluate/self_evaluate.php onClick=parent.target.window.location=\"./Self_Evaluate/self_evaluate.php\"><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">�ҵ{�۵�</font></font></a></div></td>");
			}
			else {
				$tpl->assign( EVALUATE, "<td><div align=\"center\"><a href=./Self_Evaluate/self_evaluate.php onClick=parent.target.window.location=\"\"><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">Self Evaluate</font></font></a></div></td>");
			}
		}
		else{
			$tpl->assign( EVALUATE, "");
		}

		//linsy@20130226, ���wĵ�t�Τ�������
		//if($function['warning']) {
			if ( $version == "C" ) {
                                $tpl->assign( WARNING,"<td width=\"70\"><div align=\"center\"><a href=\"#./Trackin/TGCaution.php\" onClick=parent.target.window.location=\"./Trackin/TGCaution.php\"><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">�wĵ�t��</font></font></a></div></td>");
			}
			else {
				$tpl->assign( WARNING,"<td width=\"70\"><div align=\"center\"><a href=\"./Trackin/TGCaution.php\" onClick=parent.target.window.location=\"\"><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">Caution</font></font></a></div></td>");
			}
		//}
		//else{
		//	$tpl->assign( WARNING, "");
		//}


		if($function['eroll']) {
			if ( $version == "C" ) {
				$tpl->assign( EROLL, "<td width=\"70\"><div align=\"center\"><a href=#./Trackin/ElectionRoll.php onClick=parent.target.window.location=\"./Trackin/TGCaution.php\"><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">�q�l�I�W</font></font></a></div></td>");
			}
			else {
				$tpl->assign( EROLL, "<td width=\"70\"><div align=\"center\"><a href=./Trackin/ElectionRoll.php onClick=parent.target.window.location=\"\"><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">Election Roll</font></font></a></div></td>");
			}
		}
		else{
			$tpl->assign( EROLL, "");
		}
	
		if ( $row["authorization"] < 2 ) {
			if ( $version == "C" ) {
				$tpl->assign( INFO, "<td width=\"70\"><div align=\"center\"><a href=#./Learner_Profile/new_ta.php onClick=parent.target.window.location=\"./Learner_Profile/new_ta.php\"><font size=\"2\">��</font><font color=\"#000000\"><font size=\"2\">�U�и��</font></font></a></div></td>");
				$tpl->assign( TEIN, "<td width=\"70\"><div align=\"center\"><a href=#./Learner_Profile/email_tch.php onClick=parent.target.window.location=\"./Learner_Profile/email_tch.php\"><font size=\"2\">��</font><font color=\"#000000\"><font size=\"2\">�Юv���</font></font></a></div></td>" );
				$tpl->assign( TGUPLOAD, "<td width=\"140\"><div align=\"center\"><a href=\"#./Trackin/TGQueryFrame1.php?action=upload\" onClick=parent.target.window.location=\"./Trackin/TGQueryFrame1.php?action=upload\"><font color=\"red\" size=\"2\">���Ǵ����Z�n���ΤW��</font></a></div></td>");
				//by carlyle
				$tpl->assign("#!IEET_RESULT!#","<td width=\"120\"><div align=\"center\"><font size=\"2\"><a href=\"#./questionary/assistantquestionary_showresult.php?courseid=" . $course_id . "&year=" . $rowssemester['year'] . "&term=" . $rowssemester['term'] . "\" onClick=parent.target.window.location=\"./questionary/assistantquestionary_showresult.php?courseid=" . $course_id . "&year=" . $rowssemester['year'] . "&term=" . $rowssemester['term'] . "\"><font size=\"2\">��</font><font color=\"#000000\">�[��IEET�ݨ����G</font></a></font></div></td>");
			}
			else {
				$tpl->assign( INFO, "<td width=\"70\"><div align=\"center\"><a href=#./Learner_Profile/new_ta.php onClick=parent.target.window.location=\"./Learner_Profile/new_ta.php\"><font size=\"2\">��</font><font color=\"#000000\"><font size=\"2\">TA Info</font></font></a></div></td>");
				$tpl->assign( TEIN, "<td width=\"70\"><div align=\"center\"><a href=#./Learner_Profile/email_tch.php onClick=parent.target.window.location=\"./Learner_Profile/email_tch.php\"><font size=\"2\">��</font><font color=\"#000000\"><font size=\"2\">Pro. Info</font></font></a></div></td>" );
				$tpl->assign( TGUPLOAD, "<td width=\"70\"><div align=\"center\"><a href=\"#./Trackin/TGQueryFrame1.php?action=upload\" onClick=parent.target.window.location=\"./Trackin/TGQueryFrame1.php?action=upload\"><font color=\"red\" size=\"2\">��Score Upload</font></a></div></td>");
				//by carlyle
				$tpl->assign("#!IEET_RESULT!#","<td width=\"120\"><div align=\"center\"><font size=\"2\"><a href=\"./questionary/assistantquestionary_showresult.php?courseid=" . $course_id . "&year=" . $rowssemester['year'] . "&term=" . $rowssemester['term'] . "\" onClick=parent.target.window.location=\"./questionary/assistantquestionary_showresult.php?courseid=" . $course_id . "&year=" . $rowssemester['year'] . "&term=" . $rowssemester['term'] . "\"><font size=\"2\">��</font><font color=\"#000000\">IEET Result</font></a></font></div></td>");
			}
		}
		else {
			if ( $version == "C" ) {
				$tpl->assign( INFO, "<td width=\"70\"><div align=\"center\"><a href=#./Learner_Profile/email_tch.php onClick=parent.target.window.location=\"./Learner_Profile/email_tch.php\"><font size=\"2\">��</font><font color=\"#000000\"><font size=\"2\">�U�и��</font></font></a></div></td>");
			}
			else{
				$tpl->assign( INFO, "<td width=\"70\"><div align=\"center\"><a href=#./Learner_Profile/email_tch.php onClick=parent.target.window.location=\"./Learner_Profile/email_tch.php\"><font size=\"2\">��</font><font color=\"#000000\"><font size=\"2\">TA Info</font></font></a></div></td>");
			}
			$tpl->assign( TEIN, "" );
			$tpl->assign( TGUPLOAD, "" );
			//by carlyle
			$tpl->assign("#!IEET_RESULT!#","");
		} 
		
		if( $version == "C") {
			if ($function['online']) {
				//changed by rja
                                //�ª��s��

				$tpl->assign( ONLINE, "<td width=\"70\"><div align=\"center\"><a href=#./on_line/on_line.php onClick=parent.target.window.location=\"./on_line/on_line.php\"><font size=\"2\">��</font><font color=\"#000000\"><font size=\"2\">�H����T</font></font></a></div></td>" );
				//�s��,�j�bJoin net(M2)
				//update: ���Ӥ��ݭn�o�ӳs���F
				//$tpl->assign( ONLINE,"<td width=\"70\"><div align=\"center\"><a href=\"get_m2.php\" onClick=parent.target.window.location=\"\"><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">�H����T</font></font></a></div></td>");
				//end of change

			}
			else{
				$tpl->assign( ONLINE, "");
			}
			/*
			if ($function['create_case']) {
				$tpl->assign( CREATE_CASE, "<td><div align=\"center\"><a href=./coop/create_case.php onClick=parent.target.window.location=\"\"><font size=\"2\">��</font><font color=\"#000000\"><font size=\"2\">�s�W�M��</font></font></a></div></td>" );
			}
			else{
				$tpl->assign( CREATE_CASE, "");
			}
			if ($function['mag_case']) {
				$tpl->assign( MAG_CASE, "<td><div align=\"center\"><a href=./coop/Mag_case.php onClick=parent.target.window.location=\"\"><font size=\"2\">��</font><font color=\"#000000\"><font size=\"2\">�M�׺޲z</font></font></a></div></td>" );
			}
			else{
				$tpl->assign( MAG_CASE, "");
			}
			if ($function['check_case']) {
				$tpl->assign( CHECK_CASE, "<td><div align=\"center\"><a href=./coop/check_allcase.php onClick=parent.target.window.location=\"\"><font size=\"2\">��</font><font color=\"#000000\"><font size=\"2\">�[�ݱM��</font></font></a></div></td>" );
			}
			else{
				$tpl->assign( CHECK_CASE, "");
			}
			*/
			if ($function['create_qs']) {
				$tpl->assign( CREATE_QS, "<td width=\"70\"><div align=\"center\"><a href=#./questionary/create_questionary.php onClick=parent.target.window.location=\"./questionary/create_questionary.php\"><font size=\"2\">��</font><font color=\"#000000\"><font size=\"2\">�s�@�ݨ�</font></font></a></div></td>" );
			}
			else{
				$tpl->assign( CREATE_QS, "");
			}
			if ($function['modify_qs']) {
				$tpl->assign( MODIFY_QS, "<td width=\"70\"><div align=\"center\"><a href=#./questionary/modify_questionary.php onClick=parent.target.window.location=\"./questionary/modify_questionary.php\"><font size=\"2\">��</font><font color=\"#000000\"><font size=\"2\">�ק�ݨ�</font></font></a></div></td>" );
			}
			else{
				$tpl->assign( MODIFY_QS, "");
			}
			
			if ($function['chat']) {
				$tpl->assign( CHAT, "<td width=\"90\"><div align=\"center\"><a href=\"#\" onClick=\"window.open('./chat/chat_int.php?PHPSESSID=$PHPSESSID', '', 'width=750,height=480,resizable=1,scrollbars=1');\" ><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">�u�W�Q�װ�</font></font></a></div></td>" );
			}
			else{
				$tpl->assign( CHAT, "");
			}
   /*
                           changed by rja
                         */


			if ($function['talk_voc']) {
				$tpl->assign( TALK_VOC, "<td width=\"90\"><div align=\"center\"><a href=\"#\" onClick=\"window.open('./talk_voc/talk_voc.php?PHPSESSID=$PHPSESSID', '', 'width=770,height=540,resizable=1,scrollbars=1');\"><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">�y����ѫ�</font></font></a></div></td>" );
			}
			else{
				$tpl->assign( TALK_VOC, "");
			}
			if ($function['talk_int']) {
				$tpl->assign( TALK_INT, "<td width=\"90\"><div align=\"center\"><a href=\"#\" onClick=\"window.open('./talk_int/talk_int.php?PHPSESSID=$PHPSESSID', '', 'width=750,height=520,resizable=1,scrollbars=1');\"><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">���ʲ�ѫ�</font></font></a></div></td>" );
			}
			else{
				$tpl->assign( TALK_INT, "");
			}
//�o�̥i��n���t�ο�ܶ}���}��o�ӥ\��ΡA�ثe���O���}��
//if( $function['joint_net']){
			if(true){
				//�Y���U�СA���ǥͬݫ�
				if ($row['authorization']==2){
					$tpl->assign( RESERVATION, "");
// modified by jfish 20110213
					$tpl->assign( CHAT, "<td width=\"120\"><div align=\"center\"><a href=\"#Mmc/joinMeeting.php\" onClick=parent.target.window.location=\"Mmc/joinMeeting.php\"><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">�i�J�Ѯv�����줽��</font></font></a></div></td>" );
					$tpl->assign( RECORDING, "");
				}else{
#$tpl->assign(JOINTNET,"<td><div align=\"center\"><a href=./Learner_Profile/JointNet.php onClick=parent.target.window.location=\"\"><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">�[�ݺ����줽��</font></font></a></div></td>");
					$tpl->assign( RESERVATION, "<td width=\"100\"><div align=\"center\"><a href=\"#Mmc/reservationMeeting.php\" onClick=parent.target.window.location=\"Mmc/reservationMeeting.php\"><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">�w�������줽��</font></font></a></div></td>" );
					$tpl->assign( CHAT, "<td width=\"100\"><div align=\"center\"><a href=\"#Mmc/enterweboffice.php\" onClick=parent.target.window.location=\"Mmc/enterweboffice.php\"><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">�e�������줽��</font></font></a></div></td>" );
					$tpl->assign( RECORDING, "<td width=\"100\"><div align=\"center\"><a href=\"#Mmc/recordingManagement.php\" onClick=parent.target.window.location=\"Mmc/recordingManagement.php\"><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">���v�ɺ޲z</font></font></a></div></td>" );
//  modified by jfish 20110213  end

				}
			}else{
				$tpl->assign(JOINTNET,"");
			}

                        //modify by rja, eboard ���ݭn�F
			//modify by rja 2 line, eboard ���ݭn�F
			//if ($function['eboard']) {
			if (false) {
				$tpl->assign( EBOARD, "<td width=\"70\"><div align=\"center\"><a href=\"#\" onClick=\"window.open('./eboard/eboard_int.php?PHPSESSID=$PHPSESSID', '', 'width=800,height=600,resizable=1,scrollbars=1');\"><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">EBoard</font></font></a></div></td>" );
			}
			else{
				$tpl->assign( EBOARD, "");
			}

                        /*
                           end of changed by rja
                         */

			
			if ($function['strank']) {
				$tpl->assign( STRANK, "<td><div align=\"center\"><a href=#./Trackin/StudentRank1.php onClick=parent.target.window.location=\"./Trackin/StudentRank1.php\"><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">�t�ΨϥΰO��</font></font></a></div></td>" );
			}
			else{
				$tpl->assign( STRANK, "");
			}
			if ($function['chrank']) {
				$tpl->assign( CHRANK, "<td><div align=\"center\"><a href=#./Trackin/ChapterRank.php onClick=parent.target.window.location=\"./Trackin/ChapterRank.php\"><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">�Ч��s���O��</font></font></a></div></td>" );
			}
			else{
				$tpl->assign( CHRANK, "");
			}
			if ($function['sttrace']) {
				$tpl->assign( STTRACE, "<td><div align=\"center\"><a href=#./Trackin/ShowStudentListForTraceInfo.php onClick=parent.target.window.location=\"./Trackin/ShowStudentListForTraceInfo.php\"><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">�ǥͭӧO�O��</font></font></a></div></td>" );
			}
			else{
				$tpl->assign( STTRACE, "");
			}
			if ($function['complete']) {
				$tpl->assign( COMPLETE, "<td><div align=\"center\"><a href=#./Trackin/CompleteUsageList.php onClick=parent.target.window.location=\"./Trackin/CompleteUsageList.php\" onClick=\"msgwin('��ƳB�z��, �еy��..')\"><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">�O������C��</font></font></a></div></td>" );
			}
			else{
				$tpl->assign( COMPLETE, "");
			}
			
			if ($function['tsins']) {
				$tpl->assign( TSINS, "<td><div align=\"center\"><a href=#./Learner_Profile/TSInsertMS.php onClick=parent.target.window.location=\"./Learner_Profile/TSInsertMS.php\"><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">�ǥͷs�W</font></font></a></div></td>" );
			}
			else{
				$tpl->assign( TSINS, "");
			}
			if ($function['tsdel']) {
				$tpl->assign( TSDEL, "<td><div align=\"center\"><a href=#./Learner_Profile/TSDeleteFrame1.php onClick=parent.target.window.location=\"./Learner_Profile/TSDeleteFrame1.php\"><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">�ǥͧR��</font></font></a></div></td>" );
			}
			else{
				$tpl->assign( TSDEL, "");
			}
			if ($function['tsmod']) {
				$tpl->assign( TSMOD, "<td><div align=\"center\"><a href=#./Learner_Profile/TSModifyFrame1.php onClick=parent.target.window.location=\"./Learner_Profile/TSModifyFrame1.php\"><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">�ǥͭק�</font></font></a></div></td>" );
			}
			else{
				$tpl->assign( TSMOD, "");
			}
			if ($function['tschg']) {
/*mark by intree
				$tpl->assign( TSCHG, "<td><div align=\"center\"><a href=./Learner_Profile/TSChangeFrame1.php onClick=parent.target.window.location=\"\"><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">�ק鶴��</font></font></a></div></td>" );
*/			
				 $tpl->assign( TSCHG,"");
			}
			else{
				$tpl->assign( TSCHG, "");
			}
			if ($function['psswd']) {
				$tpl->assign( PSSWD, "<td><div align=\"center\"><a href=#./Learner_Profile/give_pass1.php onClick=parent.target.window.location=\"./Learner_Profile/give_pass1.php\"><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">�d�߾ǥͱK�X</font></font></a></div></td>" );
			}
			else{
				$tpl->assign( PSSWD, "");
			}
		}
		else{
			if ($function['online']) {
				//changed by rja
				//�ª�

				$tpl->assign( ONLINE, "<td width=\"70\"><div align=\"center\"><a href=#./on_line/on_line.php onClick=parent.target.window.location=\"./on_line/on_line.php\"><font size=\"2\">��</font><font color=\"#000000\"><font size=\"2\">Vedio Material</font></font></a></div></td>" );
				//�s��,�j�bjoin net(M2)�A���Ӥ��ݭn�F
				//$tpl->assign( ONLINE,"<td width=\"70\"><div align=\"center\"><a href=\"http://140.123.23.5/_guest_publications.php\" onClick=parent.target.window.location=\"\"><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">Vedio Material</font></font></a></div></td>");
				//end of change


			}
			else{
				$tpl->assign( ONLINE, "");
			}
			/*
			if ($function['create_case']) {
				$tpl->assign( CREATE_CASE, "<td><div align=\"center\"><a href=./coop/create_case.php onClick=parent.target.window.location=\"\"><font size=\"2\">��</font><font color=\"#000000\"><font size=\"2\">New</font></font></a></div></td>" );
			}
			else{
				$tpl->assign( CREATE_CASE, "");
			}
			if ($function['mag_case']) {
				$tpl->assign( MAG_CASE, "<td><div align=\"center\"><a href=./coop/Mag_case.php onClick=parent.target.window.location=\"\"><font size=\"2\">��</font><font color=\"#000000\"><font size=\"2\">Mag</font></font></a></div></td>" );
			}
			else{
				$tpl->assign( MAG_CASE, "");
			}
			if ($function['check_case']) {
				$tpl->assign( CHECK_CASE, "<td><div align=\"center\"><a href=./coop/check_allcase.php onClick=parent.target.window.location=\"\"><font size=\"2\">��</font><font color=\"#000000\"><font size=\"2\">Preview</font></font></a></div></td>" );
			}
			else{
				$tpl->assign( CHECK_CASE, "");
			}
			*/
			if ($function['create_qs']) {
				$tpl->assign( CREATE_QS, "<td width=\"70\"><div align=\"center\"><a href=#./questionary/create_questionary.php onClick=parent.target.window.location=\"./questionary/create_questionary.php\"><font size=\"2\">��</font><font color=\"#000000\"><font size=\"2\">New</font></font></a></div></td>" );
			}
			else{
				$tpl->assign( CREATE_QS, "");
			}
			if ($function['modify_qs']) {
				$tpl->assign( MODIFY_QS, "<td width=\"70\"><div align=\"center\"><a href=#./questionary/modify_questionary.php onClick=parent.target.window.location=\"./questionary/modify_questionary.php\"><font size=\"2\">��</font><font color=\"#000000\"><font size=\"2\">Edit</font></font></a></div></td>" );
			}
			else{
				$tpl->assign( MODIFY_QS, "");
			}
			
			if ($function['chat']) {
				$tpl->assign( CHAT, "<td width=\"90\"><div align=\"center\"><a href=\"#\" onClick=\"window.open('./chat/chat_int.php?PHPSESSID=$PHPSESSID', '', 'width=750,height=480,resizable=1,scrollbars=1');\" ><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">Chat Room</font></font></a></div></td>" );
			}
			else{
				$tpl->assign( CHAT, "");
			}
			//modify by w60292 @ 20090326 ������l
			$tpl->assign( TALK_VOC, "");
			$tpl->assign( TALK_INT, "");
			$tpl->assign( EBOARD, "");

//changed by rja
/*
			if ($function['talk_voc']) {
				$tpl->assign( TALK_VOC, "<td width=\"90\"><div align=\"center\"><a href=\"#\" onClick=\"window.open('./talk_voc/talk_voc.php?PHPSESSID=$PHPSESSID', '', 'width=770,height=540,resizable=1,scrollbars=1');\"><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">Audio Chat Room</font></font></a></div></td>" );
			}
			else{
				$tpl->assign( TALK_VOC, "");
			}
			if ($function['talk_int']) {
				$tpl->assign( TALK_INT, "<td width=\"90\"><div align=\"center\"><a href=\"#\" onClick=\"window.open('./talk_int/talk_int.php?PHPSESSID=$PHPSESSID', '', 'width=750,height=520,resizable=1,scrollbars=1');\"><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">Interactive Chat Room</font></font></a></div></td>" );
			}
			else{
				$tpl->assign( TALK_INT, "");
			}
			if ($function['eboard']) {
				$tpl->assign( EBOARD, "<td width=\"70\"><div align=\"center\"><a href=\"#\" onClick=\"window.open('./eboard/eboard_int.php?PHPSESSID=$PHPSESSID', '', 'width=800,height=600,resizable=1,scrollbars=1');\"><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">EBoard</font></font></a></div></td>" );
			}
			else{
				$tpl->assign( EBOARD, "");
			}
*/
//�o�̦n���S����

//�o�̥i��n���t�ο�ܶ}���}��o�ӥ\��ΡA�ثe���O���}��
//if( $function['joint_net']){
			if(true){
				//�Y���U�СA���ǥͬݫ�
				if ($row['authorization']==2){
					$tpl->assign( RESERVATION, "");
					$tpl->assign( CHAT, "<td width=\"120\"><div align=\"center\"><a href=\"#Mmc/joinMeeting.php\" onClick=parent.target.window.location=\"Mmc/joinMeeting.php\"><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">Teacher's Web Office</font></font></a></div></td>" );
					$tpl->assign( RECORDING, "");
				}else{
#$tpl->assign(JOINTNET,"<td><div align=\"center\"><a href=./Learner_Profile/JointNet.php onClick=parent.target.window.location=\"\"><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">�[�ݺ����줽��</font></font></a></div></td>");
					$tpl->assign( RESERVATION, "<td width=\"100\"><div align=\"center\"><a href=\"#./my_gotojoinnet.php?action=reservation\" onClick=parent.target.window.location=\"./my_gotojoinnet.php?action=reservation\"><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">Reserve Web Office
							</font></font></a></div></td>" );
					$tpl->assign( CHAT, "<td width=\"100\"><div align=\"center\"><a href=\"#./my_gotojoinnet.php?action=meeting\" onClick=parent.target.window.location=\"./my_gotojoinnet.php?action=meeting\"><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">Web Office</font></font></a></div></td>" );
					$tpl->assign( RECORDING, "<td width=\"100\"><div align=\"center\"><a href=\"#./my_gotojoinnet?action=recordingManagement\" onClick=parent.target.window.location=\"./my_gotojoinnet?action=recordingManagement\"><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">Record Management</font></font></a></div></td>" );

				}
			}else{
				$tpl->assign(JOINTNET,"");
			}

        if ($function['joint_net']) {
                                $tpl->assign( JOINTNET, "<div align=\"center\"><a href=\"#./Learner_Profile/JointNet.php\" onClick=parent.target.window.location=\"./Learner_Profile/JointNet.php\"><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">���X�Ѯv�����줽��</font></font></a></div></td>" );
                        }
                        else{
                                $tpl->assign( JOINTNET, "");
                        }
			//end of changed by rja

			
			if ($function['strank']) {
				$tpl->assign( STRANK, "<td><div align=\"center\"><a href=#./Trackin/StudentRank1.php onClick=parent.target.window.location=\"./Trackin/StudentRank1.php\"><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">Ranking</font></font></a></div></td>" );
			}
			else{
				$tpl->assign( STRANK, "");
			}
			if ($function['chrank']) {
				$tpl->assign( CHRANK, "<td><div align=\"center\"><a href=#./Trackin/ChapterRank.php onClick=parent.target.window.location=\"./Trackin/ChapterRank.php\"><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">Browsing</font></font></a></div></td>" );
			}
			else{
				$tpl->assign( CHRANK, "");
			}
			if ($function['sttrace']) {
				$tpl->assign( STTRACE, "<td><div align=\"center\"><a href=#./Trackin/ShowStudentListForTraceInfo.php onClick=parent.target.window.location=\"./Trackin/ShowStudentListForTraceInfo.php\"><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">Acivities</font></font></a></div></td>" );
			}
			else{
				$tpl->assign( STTRACE, "");
			}
			if ($function['complete']) {
				$tpl->assign( COMPLETE, "<td><div align=\"center\"><a href=#./Trackin/CompleteUsageList.php onClick=parent.target.window.location=\"./Trackin/CompleteUsageList.php\" onClick=\"msgwin('��ƳB�z��, �еy��..')\"><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">Complete list</font></font></a></div></td>" );
			}
			else{
				$tpl->assign( COMPLETE, "");
			}
			
			if ($function['tsins']) {
				$tpl->assign( TSINS, "<td><div align=\"center\"><a href=#./Learner_Profile/TSInsertMS.php onClick=parent.target.window.location=\"./Learner_Profile/TSInsertMS.php\"><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">New</font></font></a></div></td>" );
			}
			else{
				$tpl->assign( TSINS, "");
			}
			if ($function['tsdel']) {
				$tpl->assign( TSDEL, "<td><div align=\"center\"><a href=#./Learner_Profile/TSDeleteFrame1.php onClick=parent.target.window.location=\"./Learner_Profile/TSDeleteFrame1.php\"><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">Delete</font></font></a></div></td>" );
			}
			else{
				$tpl->assign( TSDEL, "");
			}
			if ($function['tsmod']) {
				$tpl->assign( TSMOD, "<td><div align=\"center\"><a href=#./Learner_Profile/TSModifyFrame1.php onClick=parent.target.window.location=\"./Learner_Profile/TSModifyFrame1.php\"><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">Modify</font></font></a></div></td>" );
			}
			else{
				$tpl->assign( TSMOD, "");
			}
			if ($function['tschg']) {
/*mark by intree
				$tpl->assign( TSCHG, "<td><div align=\"center\"><a href=./Learner_Profile/TSChangeFrame1.php onClick=parent.target.window.location=\"\"><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">Change</font></font></a></div></td>" );
*/			
				 $tpl->assign( TSCHG,"");
			}
			else{
				$tpl->assign( TSCHG, "");
			}
			if ($function['psswd']) {
				$tpl->assign( PSSWD, "<td><div align=\"center\"><a href=#./Learner_Profile/give_pass1.php onClick=parent.target.window.location=\"./Learner_Profile/give_pass1.php\"><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">Password</font></font></a></div></td>" );
			}
			else{
				$tpl->assign( PSSWD, "");
			}		
		}
		//$Q2 = "SELECT t.course_id, c.name from teach_course t, course c where t.teacher_id = '".$row["a_id"]."' and c.a_id = t.course_id";
		$max = 10;
		

		//���F��ܤU�Կ�檺group
		$group_year = 0;
		$group_term = 0;
		$c_group = 0;
		$change_group = 0;
		$have_group1 = 0; //�P�_�O�_����Ǵ����s��
		/*********************���Ǵ�*****************************************/
		while ( $row21 = mysql_fetch_array($result21) ) {
						
			//���F��ܤU�Կ�檺group
			$change_group = 0;
			if( ($group_year != $row21['year'] || $group_term!= $row21['term']) ){
				$tpl->assign( CGROUP, "��Ǵ��ҵ{" );
				if($have_group1==0){
					$have_group1=1;
				}
				$change_group = 1;
				$group_year = $row21['year'];
				$group_term = $row21['term'];				
			}
			//			
			if (  ($row21['year']."_".$row21['term']."_".$row21['course_id']) == ($course_year."_".$course_term."_".$course_id) && $is_hist == 0) //���O���v�Ϥ~��
				$tpl->assign( CID, $row21['year']."_".$row21['term']."_".$row21['course_id']." selected");
			else
				$tpl->assign( CID, $row21['year']."_".$row21['term']."_".$row21['course_id'] );

			$course_no = $row21['course_no'];
			if( substr( $course_no, 3, 1 ) == "A" || substr( $course_no, 3, 1 ) == "B" || substr( $course_no, 3, 1 ) == "C" || substr( $course_no, 3, 1 ) == "D" ){
				$tpl->assign( CNAME, $row21['name']." (�M�Z)" );
				$max = strlen( $row21['name']." (�M�Z)" ) > $max ? strlen( $row21['name']." (�M�Z)" ) : $max;
			}
			else{
				$tpl->assign( CNAME, $row21['name'] );
				$max = strlen( $row21['name'] ) > $max ? strlen( $row21['name'] ) : $max;
			}
			//���F��ܤU�Կ�檺group
			if($change_group == 1){
				$tpl->parse( C_L, "course_list");		
			}
			else{
				$tpl->parse( C_L, ".course_list");
			}
			//
			//$tpl->parse( C_L, ".course_list");			
		}			
		
		//���F��ܤU�Կ�檺group
		$group_year = 0;
		$group_term = 0;
		$c_group = 0;
		$change_group = 0;
		$have_group2 = 0; //�P�_�O�_���W/�U�Ǵ����s��
		/*************************�W/�U�Ǵ�************************/		
		while ( $row22 = mysql_fetch_array($result22) ) {	
			//���F��ܤU�Կ�檺group
			$change_group = 0;
			if( ($group_year != $row22['year'] || $group_term!= $row22['term'])){

				if($have_group1==1){				
					$tpl->parse( C_G, ".course_group");
				}
				
				if($is_next == 1){
					$tpl->assign( CGROUP, "�U�Ǵ��ҵ{" );
				}
				else{
					$tpl->assign( CGROUP, "�W�Ǵ��ҵ{" );
				}
				
				if($have_group2==0){
					$have_group2=1;
				}
				
				$change_group = 1;				

				$group_year = $row22['year'];
				$group_term = $row22['term'];				
			}
			//
					
			if ( ($row22['year']."_".$row22['term']."_".$row22['course_id']) == ($course_year."_".$course_term."_".$course_id) && $is_hist == 0) //���O���v�Ϥ~��
				$tpl->assign( CID, $row22['year']."_".$row22['term']."_".$row22['course_id']." selected");
			else
				$tpl->assign( CID, $row22['year']."_".$row22['term']."_".$row22['course_id'] );

			$course_no = $row22['course_no'];
			if( substr( $course_no, 3, 1 ) == "A" || substr( $course_no, 3, 1 ) == "B" || substr( $course_no, 3, 1 ) == "C" || substr( $course_no, 3, 1 ) == "D" ){
				$tpl->assign( CNAME, $row22['name']." (�M�Z)" );
				$max = strlen( $row22['name']." (�M�Z)"  ) > $max ? strlen( $row22['name']." (�M�Z)"  ) : $max;
			}
			else{
				$tpl->assign( CNAME, $row22['name'] );
				$max = strlen( $row22['name'] ) > $max ? strlen( $row22['name'] ) : $max;
			};
			//���F��ܤU�Կ�檺group
			if($change_group == 1){
				$tpl->parse( C_L, "course_list");		
			}
			else{
				$tpl->parse( C_L, ".course_list");
			}
			//
			//$tpl->parse( C_L, ".course_list");			
		}
		
		/************************���v��************************/
		//�ҵ{��T�ݯS�O�qhist_course��
		$Q22 = "SELECT t.course_id, c.name, c.course_no, t.year, t.term  from teach_course t, hist_course c where t.teacher_id = '".$row["a_id"]."' and c.a_id = t.course_id and c.year = t.year and c.term=t.term and !((t.year = $rowssemester[year] and t.term = $rowssemester[term] ) OR (t.year = $p_n_year and t.term = $p_n_term)) order by t.year desc, t.term asc ";
		if ( !($result22 = mysql_db_query( $DB, $Q22  )) ) {
			$error = "��ƮwŪ�����~!!22";
			show_page ( "not_access.tpl", $error );
		}
		//���F��ܤU�Կ�檺group

		$change_group = 1;
		while ( $row22 = mysql_fetch_array($result22) ) {
			if($change_group == 1){
				if($have_group1==1 || $have_group1==2 ){				
					$tpl->parse( C_G, ".course_group");
				}
				$tpl->assign( CGROUP, "���v�ҵ{" );
			}				
			/************���v�ϽҸ��n�[�Whist_**********/		
			$hist_courid = "hist_".$row22['year']."_".$row22['term']."_".$row22['course_id'];				
			if ( $row22['course_id'] == $course_id && $is_hist == 1) //�O���v�Ϥ~��		
				$tpl->assign( CID, $hist_courid." selected");
			else
				$tpl->assign( CID, $hist_courid );
			/*************************************/

			$course_no = $row22['course_no'];
			if( substr( $course_no, 3, 1 ) == "A" || substr( $course_no, 3, 1 ) == "B" || substr( $course_no, 3, 1 ) == "C" || substr( $course_no, 3, 1 ) == "D" ){
				$tpl->assign( CNAME, $row22['name']." (�M�Z)" );
				$max = strlen( $row22['name']." (�M�Z)" ) > $max ? strlen( $row22['name']." (�M�Z)" ) : $max;
			}
			else{
				$tpl->assign( CNAME, $row22['name'] );
				$max = strlen( $row22['name'] ) > $max ? strlen( $row22['name'] ) : $max;
			}			

			//���F��ܤU�Կ�檺group
			if($change_group == 1){
				$tpl->parse( C_L, "course_list");
				$change_group = 0;
			}
			else{
				$tpl->parse( C_L, ".course_list");
			}			
		}		
		/************************************************/
		
		$tpl->assign( CID, "-1" );
		if ( $version == "C" ) {
			$tpl->assign( CNAME, "�ڪ��ҵ{" );
		}
		else {
			$tpl->assign( CNAME, "My Courses" );
		}
		$tpl->parse( C_L, ".course_list");
		
		//���F��ܤU�Կ�檺group
		$tpl->parse( C_G, ".course_group");
		//		
		
		$location = 100 + ( $max - 10 )*6;
		if ( $version == "C" ) {
			$content = $bar_content;
		}
		else {
			$content = $bar_content_E;
		}

		$tpl->define_dynamic ( "layer_time" , "body" );
		$tpl->define_dynamic ( "layer_show" , "body" );
		$tpl->define_dynamic ( "option_show" , "body" );
		$tpl->assign( VALUE6, $location + 20 );
		for ( $i = 0; $i < count( $content ) ; $i ++ ) {
			if ($content[$i]=="�����ݨ�")
				$tpl->assign( TCOLOR, "#FFFF00" );
			else
				$tpl->assign( TCOLOR, "#FFFFFF" );
			$tpl->assign( ORDER1, $i );
			$tpl->assign( ORDER2, $i+1 );
			$tpl->assign( VALUE1, 10+$i*3 );
			$tpl->assign( VALUE2, 13+$i*3 );
			$tpl->assign( VALUE3, 21+$i*3 );
			if ( /*($i==5 && (!$function['create_case']) && (!$function['mag_case']) && (!$function['check_case'])) ||*/
				 ($i==5 && (!$function['create_qs']) && (!$function['modify_qs'])) ){
				$tpl->assign( LSHOW, "" );
				$tpl->assign( VALUE4, $location );
				$tpl->assign( VALUE5, 0);
			}
			else {
				$tpl->assign( LSHOW, $content[$i] );
				if ( $i != 0 ) {
					$tpl->assign( VALUE4, $location + strlen( $content[$i - 1] )*7 + 19 );
					$location = $location + strlen( $content[$i - 1] )*7 + 19;
				}
				else
					$tpl->assign( VALUE4, $location );
				$tpl->assign( VALUE5, strlen( $content[$i] )*7+14 );
			}
			$status = "";
			for ( $j = 0; $j < count( $content ) ; $j ++ ) {
				$k = $j+1;
				if ( $i == $j ) {
					if ( $bar_show[$j] == "notready" ) {
						$status .= "'Layer".$k."1','','hide'";
					}else {
						$status .= "'Layer".$k."1','','".$bar_show[$j]."'";
					}
				}
				else {
					$status .= "'Layer".$k."1','','hide'";
				}
				if ( $j != count( $content ) - 1 )
					$status .= ",";
				else {
					if ( $bar_show[$i] == "notready" ) {
						$status .= ",'notready','','show'";
					}
					else {
						$status .= ",'notready','','hide'";
					}
				}
			}
			$tpl->assign( STATUS, $status );
			
			$tpl->parse( L_T, ".layer_time");
			$tpl->parse( L_S, ".layer_show");
			$tpl->parse( O_S, ".option_show");
		}

		$tpl->assign( LAYERNUM, count( $content ) );
		$tpl->assign( COURSE, $course_id);
		$tpl->assign( USER, $user_id);
		
		if ( $scorm == 1 ) {
			$tpl->assign( IMPORT, "import.php");
		}
		else {
			$tpl->assign( IMPORT, "import2.php");
		}
		
		$tpl->parse( BODY, "body");
		$tpl->FastPrint("BODY");
	}
	else {
		$tpl->assign( LOGIN, "login_s.php" );
		if( $version == "C" ) {
			$tpl->define ( array ( body => "bar2.tpl") );
		}
		else {
			$tpl->define ( array ( body => "bar2_E.tpl") );
		}
		$tpl->define_dynamic ( "course_list" , "body" );
		//�s�Wgroup
		$tpl->define_dynamic ( "course_group" , "body" );
		//
		
		$Q4 = "SELECT * FROM function_list2 where u_id='$user_id'";
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "��Ʈw�s�����~!!";
			show_page ( "not_access.tpl", $error );
		}
		else if ( !($result4 = mysql_db_query( "study".$course_id, $Q4  )) ) {
			$error = "��ƮwŪ�����~!!!";
			show_page ( "not_access.tpl", $error );
		}
		$function = mysql_fetch_array($result4);
		
		if( $version == "C") {
			if ($function['sched']) {
				$tpl->assign( SCHED, "<td width=\"70\"><div align=\"center\"><a href=#./Courses_Admin/show_sched.php onClick=parent.target.window.location=\"./Courses_Admin/show_sched.php\"><font size=\"2\">��</font><font color=\"#000000\"><font size=\"2\">�ҵ{�w��</font></font></a></div></td>" );
			}
			else{
				$tpl->assign( SCHED, "");
			}
			if ($function['ssquery']) {
				$tpl->assign( SSQUERY, "<td width=\"70\"><div align=\"center\"><a href=#./Learner_Profile/SSQueryFrame1.php onClick=parent.target.window.location=\"./Learner_Profile/SSQueryFrame1.php\"><font size=\"2\">��</font><font color=\"#000000\"><font size=\"2\">�P�Ǭd��</font></font></a></div></td>" );
			}
			else{
				$tpl->assign( SSQUERY, "");
			}
			if ($function['email']) {
				$tpl->assign( EMAIL, "<td width=\"70\"><div align=\"center\"><a href=#./Learner_Profile/email_stu.php onClick=parent.target.window.location=\"./Learner_Profile/email_stu.php\"><font size=\"2\">��</font><font color=\"#000000\"><font size=\"2\">�ЮvE-mail</font></font></a></div></td>" );
			}
			else{
				$tpl->assign( EMAIL, "");
			}
			
			if ($function['online']) {
				$tpl->assign( ONLINE, "<td width=\"70\"><div align=\"center\"><a href=#./on_line/on_line.php onClick=parent.target.window.location=\"./on_line/on_line.php\"><font size=\"2\">��</font><font color=\"#000000\"><font size=\"2\">�H����T</font></font></a></div></td>" );
			}
			else{
				$tpl->assign( ONLINE, "");
			}
			
			if ($function['show_qs']) {
				$tpl->assign( SHOW_QS, "<td width=\"70\"><div align=\"center\"><a href=#./questionary/show_allquestionary.php onClick=parent.target.window.location=\"./questionary/show_allquestionary.php\"><font size=\"2\">��</font><font color=\"#000000\"><font size=\"2\">�ݨ��լd</font></font></a></div></td>" );
			}
			else{
				$tpl->assign( SHOW_QS, "");
			}
			if ($function['check_case']) {
				$tpl->assign( CHECK_CASE, "<td width=\"70\"><div align=\"center\"><a href=#./coop/check_allcase.php onClick=parent.target.window.location=\"./coop/check_allcase.php\"><font size=\"2\">��</font><font color=\"#000000\"><font size=\"2\">�X�@�ǲ�</font></font></a></div></td>" );
			}
			else{
				$tpl->assign( CHECK_CASE, "");
			}
			
			if ($function['chat']) {
				$tpl->assign( CHAT, "<td width=\"80\"><div align=\"center\"><a href=\"#\" onClick=\"window.open('./chat/chat_int.php?PHPSESSID=$PHPSESSID', '', 'width=750,height=480,resizable=1,scrollbars=1');\" ><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">�u�W�Q�װ�</font></font></a></div></td>" );
			}
			else{
				$tpl->assign( CHAT, "");
			}
			if ($function['talk_voc']) {
				$tpl->assign( TALK_VOC, "<td width=\"80\"><div align=\"center\"><a href=\"#\" onClick=\"window.open('./talk_voc/talk_voc.php?PHPSESSID=$PHPSESSID', '', 'width=770,height=540,resizable=1,scrollbars=1');\"><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">�y����ѫ�</font></font></a></div></td>" );
			}
			else{
				$tpl->assign( TALK_VOC, "");
			}
			if ($function['talk_int']) {
				$tpl->assign( TALK_INT, "<td width=\"80\"><div align=\"center\"><a href=\"#\" onClick=\"window.open('./talk_int/talk_int.php?PHPSESSID=$PHPSESSID', '', 'width=750,height=520,resizable=1,scrollbars=1');\"><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">���ʲ�ѫ�</font></font></a></div></td>" );
			}
			else{
				$tpl->assign( TALK_INT, "");
			}
			if ($function['eboard']) {
				$tpl->assign( EBOARD, "<td width=\"70\"><div align=\"center\"><a href=\"#\" onClick=\"window.open('./eboard/eboard_int.php?PHPSESSID=$PHPSESSID', '', 'width=800,height=600,resizable=1,scrollbars=1');\"><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">EBoard</font></font></a></div></td>" );
			}
			else{
				$tpl->assign( EBOARD, "");
			}
			
			if ($function['search']) {
				$tpl->assign( SEARCH, "<td width=\"70\"><div align=\"center\"><font size=\"2\"><a href=#./search.php onClick=parent.target.window.location=\"./search.php\"><font size=\"2\">��</font><font color=\"#000000\">�����˯�</font></a></font></div></td>" );
			}
			else{
				$tpl->assign( SEARCH, "");
			}
			if ($function['stinfo']) {
				$tpl->assign( STINFO, "<td width=\"100\"><div align=\"center\"><font size=\"2\"><a href=#./Trackin/StudentTraceInfo.php onClick=parent.target.window.location=\"./Trackin/StudentTraceInfo.php\"><font size=\"2\">��</font><font color=\"#000000\">�ӤH�ϥΰO��</font></a></font></div></td>" );
			}
			else{
				$tpl->assign( STINFO, "");
			}
			if ($function['psswd']) {
				$tpl->assign( PSSWD, "<td width=\"70\"><div align=\"center\"><font size=\"2\"><a href=#./Learner_Profile/chang_pass.php onClick=parent.target.window.location=\"./Learner_Profile/chang_pass.php\"><font size=\"2\">��</font><font color=\"#000000\">�ק�K�X</font></a></font></div></td>" );
			}
			else{
				$tpl->assign( PSSWD, "");
			}
			if ($function['strank']) {
				$tpl->assign( STRANK, "<td width=\"100\"><div align=\"center\"><font size=\"2\"><a href=#./Trackin/StudentRank1.php onClick=parent.target.window.location=\"./Trackin/StudentRank1.php\"><font size=\"2\">��</font><font color=\"#000000\">�ǥͨϥΰO��</font></a></font></div></td>" );
			}
			else{
				$tpl->assign( STRANK, "");
			}
		}

		else {
			if ($function['sched']) {
				$tpl->assign( SCHED, "<td><div align=\"center\"><a href=./Courses_Admin/show_sched.php onClick=parent.target.window.location=\"\"><font size=\"2\">��</font><font color=\"#000000\"><font size=\"2\">Weekly Schedule</font></font></a></div></td>" );
			}
			else{
				$tpl->assign( SCHED, "");
			}
			if ($function['ssquery']) {
				$tpl->assign( SSQUERY, "<td width=\"120\"><div align=\"center\"><a href=./Learner_Profile/SSQueryFrame1.php onClick=parent.target.window.location=\"\"><font size=\"2\">��</font><font color=\"#000000\"><font size=\"2\">Student Information</font></font></a></div></td>" );
			}
			else{
				$tpl->assign( SSQUERY, "");
			}
			if ($function['email']) {
				$tpl->assign( EMAIL, "<td><div align=\"center\"><a href=./Learner_Profile/email_stu.php onClick=parent.target.window.location=\"\"><font size=\"2\">��</font><font color=\"#000000\"><font size=\"2\">E-mail</font></font></a></div></td>" );
			}
			else{
				$tpl->assign( EMAIL, "");
			}
			
			if ($function['online']) {
				$tpl->assign( ONLINE, "<td><div align=\"center\"><a href=./on_line/on_line.php onClick=parent.target.window.location=\"\"><font size=\"2\">��</font><font color=\"#000000\"><font size=\"2\">Video Material</font></font></a></div></td>" );
			}
			else{
				$tpl->assign( ONLINE, "");
			}
			
			if ($function['show_qs']) {
				$tpl->assign( SHOW_QS, "<td><div align=\"center\"><a href=./questionary/show_allquestionary.php onClick=parent.target.window.location=\"\"><font size=\"2\">��</font><font color=\"#000000\"><font size=\"2\">Questionary</font></font></a></div></td>" );
			}
			else{
				$tpl->assign( SHOW_QS, "");
			}
			
			if ($function['chat']) {
				$tpl->assign( CHAT, "<td><div align=\"center\"><a href=\"#\" onClick=\"window.open('./chat/chat_int.php?PHPSESSID=$PHPSESSID', '', 'width=750,height=480,resizable=1,scrollbars=1');\" ><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">Chat Room</font></font></a></div></td>" );
			}
			else{
				$tpl->assign( CHAT, "");
			}
			if ($function['talk_voc']) {
				$tpl->assign( TALK_VOC, "<td><div align=\"center\"><a href=\"#\" onClick=\"window.open('./talk_voc/talk_voc.php?PHPSESSID=$PHPSESSID', '', 'width=770,height=540,resizable=1,scrollbars=1');\"><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">Voice Chat Room</font></font></a></div></td>" );
			}
			else{
				$tpl->assign( TALK_VOC, "");
			}
			if ($function['talk_int']) {
				$tpl->assign( TALK_INT, "<td><div align=\"center\"><a href=\"#\" onClick=\"window.open('./talk_int/talk_int.php?PHPSESSID=$PHPSESSID', '', 'width=750,height=520,resizable=1,scrollbars=1');\"><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">Interactive Chat Room</font></font></a></div></td>" );
			}
			else{
				$tpl->assign( TALK_INT, "");
			}
			if ($function['eboard']) {
				$tpl->assign( EBOARD, "<td><div align=\"center\"><a href=\"#\" onClick=\"window.open('./eboard/eboard_int.php?PHPSESSID=$PHPSESSID', '', 'width=800,height=600,resizable=1,scrollbars=1');\"><font size=\"2\">��</font><font size=\"2\"><font color=\"#000000\">EBoard</font></font></a></div></td>" );
			}
			else{
				$tpl->assign( EBOARD, "");
			}
			
			if ($function['search']) {
				$tpl->assign( SEARCH, "<td><div align=\"center\"><font size=\"2\"><a href=./search.php onClick=parent.target.window.location=\"\"><font size=\"2\">��</font><font color=\"#000000\">Search</font></a></font></div></td>" );
			}
			else{
				$tpl->assign( SEARCH, "");
			}
			if ($function['stinfo']) {
				$tpl->assign( STINFO, "<td><div align=\"center\"><font size=\"2\"><a href=./Trackin/StudentTraceInfo.php onClick=parent.target.window.location=\"\"><font size=\"2\">��</font><font color=\"#000000\">Activity Record</font></a></font></div></td>" );
			}
			else{
				$tpl->assign( STINFO, "");
			}
			if ($function['psswd']) {
				$tpl->assign( PSSWD, "<td><div align=\"center\"><font size=\"2\"><a href=./Learner_Profile/chang_pass.php onClick=parent.target.window.location=\"\"><font size=\"2\">��</font><font color=\"#000000\">Password</font></a></font></div></td>" );
			}
			else{
				$tpl->assign( PSSWD, "");
			}
			if ($function['strank']) {
				$tpl->assign( STRANK, "<td><div align=\"center\"><font size=\"2\"><a href=./Trackin/StudentRank1.php onClick=parent.target.window.location=\"\"><font size=\"2\">��</font><font color=\"#000000\">Student Record</font></a></font></div></td>" );
			}
			else{
				$tpl->assign( STRANK, "");
			}
		}
		
		$max = 10;
		
		//���F��ܤU�Կ�檺group
		$group_year = 0;
		$group_term = 0;
		$c_group = 0;
		$change_group = 0;
		//
		while ( $row2 = mysql_fetch_array($result2) ) {
			//���F��ܤU�Կ�檺group
			$change_group = 0;
			if( ($group_year != $row2['year'] || $group_term!= $row2['term']) && $c_group!=2){
				$c_group++;
				if($c_group == 1){			
					$tpl->assign( CGROUP, "��Ǵ��ҵ{" );
					$change_group = 1;
				}
				else{
					$tpl->parse( C_G, ".course_group");
					$tpl->assign( CGROUP, "�U�Ǵ��ҵ{" );
					$change_group = 1;
				}
				$group_year = $row2['year'];
				$group_term = $row2['term'];				
			}
			//	
			if ( ($row2['year']."_".$row2['term']."_".$row2['course_id']) == ($course_year."_".$course_term."_".$course_id) )
				$tpl->assign( CID, $row2['year']."_".$row2['term']."_".$row2['course_id']." selected");
			else
				$tpl->assign( CID, $row2['year']."_".$row2['term']."_".$row2['course_id'] );
			$course_no = $row2['course_no'];
			if( substr( $course_no, 3, 1 ) == "A" || substr( $course_no, 3, 1 ) == "B" || substr( $course_no, 3, 1 ) == "C" || substr( $course_no, 3, 1 ) == "D" ){
				$tpl->assign( CNAME, $row2['name']."(�M�Z)" );
				$max = strlen( $row2['name']."(�M�Z)" ) > $max ? strlen( $row2['name']."(�M�Z)"  ) : $max;
			}
			else{
				$tpl->assign( CNAME, $row2['name'] );
				$max = strlen( $row2['name'] ) > $max ? strlen( $row2['name'] ) : $max;
			}
			//���F��ܤU�Կ�檺group
			if($change_group == 1){
				$tpl->parse( C_L, "course_list");		
			}
			else{
				$tpl->parse( C_L, ".course_list");
			}
			//
		}
		$tpl->assign( CID, "-1" );
		if ( $version == "C" )
			$tpl->assign( CNAME, "�ڪ��ҵ{" );
		else
			$tpl->assign( CNAME, "My Courses" );

		$tpl->parse( C_L, ".course_list");
		//���F��ܤU�Կ�檺group
		$tpl->parse( C_G, ".course_group");
		//
		
		$location = 100 + ( $max - 10 )*6;
		if ( $version == "C" ) {
			$content = $bars_content;
		}
		else {
			$content = $bars_content_E;
		}

		$tpl->define_dynamic ( "layer_time" , "body" );
		$tpl->define_dynamic ( "layer_show" , "body" );
		$tpl->define_dynamic ( "option_show" , "body" );
		$tpl->assign( VALUE6, $location + 20 );
		for ( $i = 0; $i < count( $content ) ; $i ++ ) {
			$status = "";
			if ($content[$i]=="�ݨ��լd")
				$tpl->assign( TCOLOR, "#FFFF00" );
			else
				$tpl->assign( TCOLOR, "#FFFFFF" );
			$tpl->assign( ORDER1, $i );
			$tpl->assign( ORDER2, $i+1 );
			$tpl->assign( VALUE1, 10+$i*3 );
			$tpl->assign( VALUE2, 13+$i*3 );
			$tpl->assign( VALUE3, 21+$i*3 );
			$tpl->assign( LSHOW, $content[$i] );
			if ( $i != 0 ) {
				$tpl->assign( VALUE4, $location + strlen( $content[$i - 1] )*7 + 19 );
				$location = $location + strlen( $content[$i - 1] )*7 + 19;
			}
			else
				$tpl->assign( VALUE4, $location );
			$tpl->assign( VALUE5, strlen( $content[$i] )*7+14 );
			for ( $j = 0; $j < count( $content ) ; $j ++ ) {
				$k = $j+1;
				if ( $i == $j ) {
					if ( $bars_show[$j] == "notready" ) {
						$status .= "'Layer".$k."1','','hide'";
					}else {
						$status .= "'Layer".$k."1','','".$bars_show[$j]."'";
					}
				}
				else {
					$status .= "'Layer".$k."1','','hide'";
				}
				if ( $j != count( $content ) - 1 )
					$status .= ",";
				else {
					if ( $bars_show[$i] == "notready" ) {
						$status .= ",'notready','','show'";
					} else {
						$status .= ",'notready','','hide'";
					}
				}
			}
			$tpl->assign( STATUS, $status );
			
			$tpl->parse( L_T, ".layer_time");
			$tpl->parse( L_S, ".layer_show");
			$tpl->parse( O_S, ".option_show");
		}

		$tpl->assign( LAYERNUM, count( $content ) );
		$tpl->assign( COURSE, $course_id);
		$tpl->assign( USER, $user_id);
		$tpl->parse( BODY, "body");
		$tpl->FastPrint("BODY");
	}

?>
