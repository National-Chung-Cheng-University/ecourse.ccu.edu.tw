<?php
require 'fadmin.php';
require './templates/top.tpl';

	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $version, $q_id;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"��Ʈw�s�����~!!" );
	} 
	//select�Ҧ��ǰ|
	$Q1 = "SELECT a_id, name FROM course_group WHERE parent_id=1 AND a_id!=98";
	if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
		show_page( "not_access.tpl" ,"��ƮwŪ�����~1!!" );
	}		
	if( mysql_num_rows($result) != 0)
	{
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate("./templates");
		$tpl->define(array(main=>"statistic_college.tpl"));
		$tpl->define_dynamic("row","main");
		while ( $rows = mysql_fetch_array( $result ) ) {
			$tpl->assign(COLLEGE,$rows['name']);
			$tpl->assign(CID,$rows['a_id']);
			$tpl->assign(QUESYEAR,$year);
			$tpl->assign(QUESTERM,$term);
			$tpl->assign(QID, $q_id);
			$tpl->parse(ROWS,".row");
		}
	}
	$tpl->parse(BODY,"main");
 	$tpl->FastPrint("BODY");
	
	if ($c_id!="") //select�Ӿǰ|���t��
	{
		$Q2 = "SELECT a_id, name FROM course_group WHERE parent_id = $c_id and a_id!=92 order by name"; //a_id!=92�ư��@�P��
		if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) ) {
			show_page( "not_access.tpl" ,"��ƮwŪ�����~2!!" );
		}
		if( mysql_num_rows($result2) != 0)
		{
			$tpl = new FastTemplate("./templates");
			$tpl->define(array(main=>"statistic_department.tpl"));
			$tpl->define_dynamic("row","main");
			while ( $rows2 = mysql_fetch_array( $result2 ) ) {
				$tpl->assign(DEPART,$rows2['name']);
				$tpl->assign(DID,$rows2['a_id']);
				$tpl->assign(QUESYEAR,$year);
				$tpl->assign(QUESTERM,$term);
				$tpl->assign(QID, $q_id);
				$tpl->parse(ROWS,".row");
			}
		}
		$tpl->parse(BODY,"main");
 		$tpl->FastPrint("BODY");
	}
	
	if ($d_id!="")  //select�Өt�Ҫ��ҵ{
	{
		
		$Q3 = "SELECT course.name as cname, course.course_no, course.a_id, course_group.name as gname 
			FROM course_group, teach_course, course, user
			WHERE teach_course.year = $year 
			AND teach_course.term = $term
			AND course.a_id = teach_course.course_id 
			AND course.group_id = $d_id 
			AND teach_course.teacher_id = user.a_id 
			AND course_group.a_id = $d_id
			AND user.authorization = 1 Group by course_no";
		if ( !($result3 = mysql_db_query( $DB, $Q3 ) ) ) {
			show_page( "not_access.tpl" ,"��ƮwŪ�����~3!!" );
		}
		
		
			
		if( mysql_num_rows($result3) != 0)
		{
			$tpl = new FastTemplate("./templates");
			$tpl->define(array(main=>"statistic_course2.tpl"));
			$tpl->define_dynamic("row","main");
			 
			while ( $rows3 = mysql_fetch_array( $result3 ) ) {
				if ( $color == "#BFCEBD" )
					$color = "#D0DFE3";
				else $color = "#BFCEBD";
				
				//��X�½ҦѮv���W��
				$Q5 = "select distinct u.name FROM user u , teach_course tc where tc.course_id = '".$rows3['a_id']."' and tc.teacher_id = u.a_id and u.authorization='1' and tc.year='$year' and tc.term='$term'";
				if ( !($result5 = mysql_db_query( $DB, $Q5 ) ) ) {
					$message = "$message - ��ƮwŪ�����~4!!";
				}
				$name = "";
				while ( $row5 = mysql_fetch_array( $result5 ) )
				{
					if ( $row5['name'] != NULL )
					{
						$name = $name.$row5['name']."<br>";
					}
				}
				
				//�׽ҤH��
				$stu_no=0;
				$Q_tmp="select count(tc.student_id) as stu_no from take_course tc, user u where tc.student_id=u.a_id and u.disable='0' and tc.course_id=$rows3[a_id] and year='$year' and term='$term'";				
				if ( !($rs_temp = mysql_db_query( $DB, $Q_tmp ) ) ) {
					$message = "$message - ��ƮwŪ�����~-�׽ҤH��!!";
				}
				if($rw_tmp = mysql_fetch_array($rs_temp))
				{
					$stu_no = $rw_tmp['stu_no'];			
				}
				//��g�H��
				$join_no=0;
				$Q_tmp="select count(student_id) as join_no FROM mid_ans where year=$year and term='$term'";				
				if ( !($rs_temp = mysql_db_query( $DB.$rows3[a_id], $Q_tmp ) ) ) {
					$message = "$message - ��ƮwŪ�����~-��g�H��!!";
				}
				if($rw_tmp = mysql_fetch_array($rs_temp))
				{
					$join_no = $rw_tmp['join_no'];	
				}
				//��g�v
				$ratio1=0;
				if ($stu_no!=0)
					$ratio1=number_format((($join_no/$stu_no)*100),2);
					
				//Ū���ӽҵ{���ݨ����G�A�v���g�Jexcel�ɮ�
				$Q10 = "SELECT q1,q2 FROM mid_ans where year=$year and term='$term'";
				
				if ( !($result10 = mysql_db_query( $DB.$rows3[a_id], $Q10 ) ) ) {
					show_page( "not_access.tpl" ,"��ƮwŪ�����~10!!" );
				}
				//----��ӽҵ{�L�H��g�ݨ�
				if(!mysql_num_rows($result10)) 
				{
					$tpl->assign( DEPART,$rows3['gname']);   	//�t��
					$tpl->assign( COLOR , $color );
					$tpl->assign( CNO,$rows3['course_no'] ); 	//�ҵ{�s��
					$tpl->assign( COURSE,$rows3['cname'] );  	//�ҵ{�W��
					$tpl->assign( TEACHER, $name );			  	//�½ұЮv
					$tpl->assign( COUNT, $stu_no."�H" );      //�׽ҤH��
					$tpl->assign( FILLED,$join_no."�H" );  	//��g�H��
					$tpl->assign( FIPER, $ratio1."%" );      	//��g�v
					$tpl->assign( Q1, "" );							//���D�@
					$tpl->assign( Q2, "" );							//���D�G
					
					$tpl->parse(ROWS,".row");
				}	
				//----��ӽҵ{���ݨ���g���
				else 
				{
					while($rows10 = mysql_fetch_array( $result10 ) )
					{
						$tpl->assign( DEPART,$rows3['gname']);   	//�t��
						$tpl->assign( COLOR , $color );
						$tpl->assign( CNO,$rows3['course_no'] ); 	//�ҵ{�s��
						$tpl->assign( COURSE,$rows3['cname'] );  	//�ҵ{�W��
						$tpl->assign( TEACHER, $name );			  	//�½ұЮv
						$tpl->assign( COUNT, $stu_no."�H" );      //�׽ҤH��
						$tpl->assign( FILLED,$join_no."�H" );  	//��g�H��
						$tpl->assign( FIPER, $ratio1."%" );      	//��g�v
						$tpl->assign( Q1, $rows10[q1] );				//���D�@
						$tpl->assign( Q2, $rows10[q2] );				//���D�G
						
						$tpl->parse(ROWS,".row");
					}
				}
				
			} //end while
			$tpl->parse(BODY,"main");
 			$tpl->FastPrint("BODY");
		}
		else
		{
			echo "<br><center><font color=\"red\">�ثe�S������ҵ{!!</font></center>";
		}
	}

?>