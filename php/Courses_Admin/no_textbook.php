<?php

// �d�ݤw�W�ǤΥ��W�ǽҵ{�Ч����t��

require 'fadmin.php';
global $DB, $version,$skinnum;
$count = 0;			//�`�ҵ{��
$has_texbook = 0;		//���j�����ҵ{��
$no_textbook = 0;		//�L�j�����ҵ{��

if (!(isset($PHPSESSID) && session_check_admin($PHPSESSID)) )
{
	show_page( "not_access.tpl" ,"�v�����~");
}

$Q0 = "select * from this_semester";
$result0 = mysql_db_query($DB, $Q0);
$row0 = mysql_fetch_array($result0);

include("class.FastTemplate.php3");
$tpl = new FastTemplate ( "./templates" );
$tpl->define ( array ( body => "no_textbook.tpl" ) );
$tpl->define_dynamic ( "no_textbook_list" , "body" );
$tpl->assign( SKINNUM , $skinnum );
$tpl->assign( TYPE , "colspan=2" );

if($status != 2)
{
	//�̶��ǿ�X�t�ҦW��
	$Q2 = "select name, a_id from course_group where is_leaf='1' order by a_id";
	if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) )
	{
		$message = "$message - ��ƮwŪ�����~2!!";
	}
	$tpl->assign(STATUS, "�w�W�ǽҵ{�Ч��C��");
	$tpl->assign(SELE1, "selected");
	$tpl->assign(SELE2, "");
	while($row2 = mysql_fetch_array($result2))
	{
		//��X�Өt�ҥH�U���Ҧ��ҵ{
		//$Q3 = "select distinct course.a_id course_id, course.name cname, course.course_no from course, teach_course where teach_course.year='".$row0[year]."' and teach_course.term='".$row0[term]."' and teach_course.course_id=course.a_id and course.group_id='".$row2[a_id]."' order by course.group_id, course.course_no";
		$Q3 = "select course.a_id course_id, course.name cname, course.course_no, user.name tname from course, teach_course, user where teach_course.year='".$row0[year]."' and teach_course.term='".$row0[term]."' and teach_course.course_id=course.a_id and course.group_id='".$row2[a_id]."'and teach_course.teacher_id=user.a_id and user.authorization =1 order by course.group_id, course.course_no";
		if ( !($result3 = mysql_db_query( $DB, $Q3 ) ) )
		{
			$message = "$message - ��ƮwŪ�����~3!!";
		}
		//�Өt�ҡ@�Ҧ����ҵ{�`��
		//$dept_total=mysql_num_rows($result3);
		$dept_total=0;
		//�ݦ��X���ҬO�w�W��
		$self_count=0;
		$course_no="";
		while($row3 = mysql_fetch_array($result3))
		{
			if( $course_no != $row3["course_no"]){
				$count++;
				$dept_total++;
			}			
			//�Y�ؿ��U���Fmisc�~�S����L�ɮ� �N�O�S�W��
			$dir = "../../$row3[course_id]/textbook";
			$handle = opendir($dir);
			$own_text = 0; //�P�_�O�_�L��L�ɮ�
			
			//��|���߷�@�����w�W�� 960425
			if($row2['a_id']=="88"){
				$own_text = 1;
			}
			else{
				while (false !== ($file = readdir($handle))) {  //jp.960326
					if($file!="misc" && $file!="." && $file!=".."){
						$own_text = 1;
						break;
					}
				}
			}
			if($own_text == "1") {
				if( $course_no != $row3["course_no"]){
					$has_textbook++;
					$self_count++;
					
					$answer[$self_count][name]=$row2[name];				//�t�ҦW��
					$answer[$self_count][cname]=$row3[cname];			//�ҵ{�W��
					$answer[$self_count][course_no]=$row3[course_no];	//�ҵ{�s��
					$answer[$self_count][tname]=$row3[tname];			//--9604�W�[�Юv�W�����
				}else {
					$answer[$self_count][tname].="�B".$row3[tname];		//--9604�W�[�Юv�W�����
				}
				
			}
			$course_no=$row3["course_no"];
			//
			/*�Y�j�������šB�j���O.html�B�j���O.htm�B�j���O.doc�B�j���O.pdf�B�j���O.ppt
			if( $row3[introduction] != "" || is_file("../../$row3[course_id]/intro/index.html") || is_file("../../$row3[course_id]/intro/index.htm") || is_file("../../$row3[course_id]/intro/index.doc") || is_file("../../$row3[course_id]/intro/index.pdf") || is_file("../../$row3[course_id]/intro/index.ppt"))
			{
				$has_intro++;
				$self_count++;
				
				$answer[$self_count][name]=$row2[name];				//�t�ҦW��
				$answer[$self_count][cname]=$row3[cname];			//�ҵ{�W��
				$answer[$self_count][course_no]=$row3[course_no];	//�ҵ{�s��
			}
			*/
		}
		if($self_count!=0)
		{
			$color = "#000066";
			$tpl->assign( COLOR , $color );
			$tpl->assign( YEAR , $row0[year] );
			$tpl->assign( TERM, $row0[term] );
			$tpl->assign( GNAME , "<font color =#FFFFFF>�}�ҳ��</font>" );
			$tpl->assign( CNO , "<font color =#FFFFFF>�ҵ{�s��</font>" );
			$tpl->assign( CNAME , "<font color =#FFFFFF>�ҵ{�W��</font>" );
			$tpl->assign( TNAME , "<font color =#FFFFFF>���ұЮv</font>" );
			$tpl->assign(SELF, "");
			$tpl->parse( NO_TEXTBOOK_LIST, ".no_textbook_list" );
		
			$color = "#F0FFEE";
			for($i=1;$i<=$self_count;$i++)
			{
				$tpl->assign( COLOR ,  $color );
				$tpl->assign( GNAME , $answer[$i][name] );			//�t�ҦW��
				$tpl->assign( CNAME , $answer[$i][cname] );			//�ҵ{�W��
				$tpl->assign( CNO , $answer[$i][course_no] );		//�ҵ{�s��
				$tpl->assign( TNAME , $answer[$i][tname] );			//���ұЮv
				if($i==$self_count)
					$tpl->assign(SELF, "<tr bgcolor=\"white\" align=\"center\"><td><font color=\"green\">�@�p".$dept_total."����</font></td><td><font color=\"green\">�w�W��".$self_count."��</font></td><td colspan=2><font color=\"red\">".sprintf("%0.2f",($self_count/$dept_total)*100)."%</font></td></tr>");
				else
					$tpl->assign(SELF, "");
				$tpl->parse ( NO_TEXTBOOK_LIST, ".no_textbook_list" );
			}
		}
	}

	$percent = sprintf("%.2f", ($has_textbook/$count)*100);
	$tpl->assign( PERCENT, "�w�W�Ǥ�v <font color=red>".$percent."</font>%<br><br>" );
	$tpl->assign( TOTAL, $count );
	$tpl->assign( TEXTBOOK, $has_textbook );
	$tpl->parse( BODY, "body" );
	$tpl->FastPrint("BODY");
}
else
{
	//�̶��ǿ�X�t�ҦW��
	$Q2 = "select name, a_id from course_group where is_leaf='1' order by a_id";
	if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) )
	{
		$message = "$message - ��ƮwŪ�����~2!!";
	}
	$tpl->assign(STATUS, "���W�ǽҵ{�Ч��C��");
	$tpl->assign(SELE2, "selected");
	$tpl->assign(SELE1, "");
	while($row2 = mysql_fetch_array($result2))
	{
		//��X�Өt�ҥH�U���Ҧ��ҵ{
		//$Q3 = "select distinct course.a_id course_id, course.name cname, course.course_no from course, teach_course where teach_course.year='".$row0[year]."' and teach_course.term='".$row0[term]."' and teach_course.course_id=course.a_id and course.group_id='".$row2[a_id]."' order by course.group_id, course.course_no";
		$Q3 = "select course.a_id course_id, course.name cname, course.course_no, user.name tname from course, teach_course, user where teach_course.year='".$row0[year]."' and teach_course.term='".$row0[term]."' and teach_course.course_id=course.a_id and course.group_id='".$row2[a_id]."'and teach_course.teacher_id=user.a_id and user.authorization =1 order by course.group_id, course.course_no";
		if ( !($result3 = mysql_db_query( $DB, $Q3 ) ) )
		{
			$message = "$message - ��ƮwŪ�����~3!!";
		}
		//�Өt�ҡ@�Ҧ����ҵ{�`��
		$dept_total=mysql_num_rows($result3);	
		$dept_total=0;	
		//�ݦ��X���ҬO���W��
		$self_count=0;
		$course_no="";
		while($row3 = mysql_fetch_array($result3))
		{
			if( $course_no != $row3["course_no"]){
				$count++;
				$dept_total++;
			}
			//�Y�ؿ��U���Fmisc�~�S����L�ɮ� �N�O�S�W��
			$dir = "../../$row3[course_id]/textbook";
			$handle = opendir($dir);
			$own_text = 0; //�P�_�O�_�L��L�ɮ�
			//��|���߷�@�����w�W�� 960425
			if($row2['a_id']=="88"){
				$own_text = 1;
			}
			else{
				while (false !== ($file = readdir($handle))) {  //jp.960326
					if($file!="misc" && $file!="." && $file!=".."){
						$own_text = 1;
						break;
					}
				}
			}
			if($own_text == "0"){
				if( $course_no != $row3["course_no"]){
					$no_textbook++;
					$self_count++;
					
					$answer[$self_count][name]=$row2[name];				//�t�ҦW��
					$answer[$self_count][cname]=$row3[cname];			//�ҵ{�W��
					$answer[$self_count][course_no]=$row3[course_no];	//�ҵ{�s��
					$answer[$self_count][tname]=$row3[tname];			//--9604�W�[�Юv�W�����
				}else {
					$answer[$self_count][tname].="�B".$row3[tname];		//--9604�W�[�Юv�W�����
				}
				
			}
			$course_no=$row3["course_no"];
			//
			/*�Y�j�����šB�B�j�����O.html�B�j�����O.htm�B�j�����O.doc�B�j�����O.pdf�B�j�����O.ppt
			if( $row3[introduction] =="" && !(is_file("../../$row3[course_id]/intro/index.html")) && !(is_file("../../$row3[course_id]/intro/index.htm")) && !(is_file("../../$row3[course_id]/intro/index.doc")) && !(is_file("../../$row3[course_id]/intro/index.pdf")) && !(is_file("../../$row3[course_id]/intro/index.ppt")))
			{
				$no_intro++;
				$self_count++;
				
				$answer[$self_count][name]=$row2[name];				//�t�ҦW��
				$answer[$self_count][cname]=$row3[cname];			//�ҵ{�W��
				$answer[$self_count][course_no]=$row3[course_no];	//�ҵ{�s��
			}
			*/
		}
		if($self_count!=0)
		{
			$color = "#000066";
			$tpl->assign( COLOR , $color );
			$tpl->assign( YEAR , $row0[year] );
			$tpl->assign( TERM, $row0[term] );
			$tpl->assign( GNAME , "<font color =#FFFFFF>�}�ҳ��</font>" );
			$tpl->assign( CNO , "<font color =#FFFFFF>�ҵ{�s��</font>" );
			$tpl->assign( CNAME , "<font color =#FFFFFF>�ҵ{�W��</font>" );
			$tpl->assign( TNAME , "<font color =#FFFFFF>���ұЮv</font>" );
			$tpl->assign( SELF, "" );
			$tpl->parse( NO_TEXTBOOK_LIST, ".no_textbook_list" );
		
			$color = "#F0FFEE";
			for($i=1;$i<=$self_count;$i++)
			{
				$tpl->assign( COLOR ,  $color );
				$tpl->assign( GNAME , $answer[$i][name] );			//�t�ҦW��
				$tpl->assign( CNAME , $answer[$i][cname] );			//�ҵ{�W��
				$tpl->assign( CNO , $answer[$i][course_no] );		//�ҵ{�s��
				$tpl->assign( TNAME , $answer[$i][tname] );			//���ұЮv
				if($i==$self_count)
					$tpl->assign(SELF, "<tr bgcolor=\"white\" align=\"center\"><td><font color=\"green\">�@�p".$dept_total."����</font></td><td><font color=\"green\">���W��".$self_count."��</font></td><td colspan=2><font color=\"red\">".sprintf("%0.2f",($self_count/$dept_total)*100)."%</font></td></tr>");
				else
					$tpl->assign(SELF, "");
				$tpl->parse ( NO_TEXTBOOK_LIST, ".no_textbook_list" );
			}
		}
	}

	$percent = sprintf("%.2f", ($no_textbook/$count)*100);
	$tpl->assign( PERCENT, "���W�Ǥ�v <font color=red>".$percent."</font>%<br><br>" );
	$tpl->assign( TOTAL, $count );
	$tpl->assign( TEXTBOOK, $no_textbook );
	$tpl->parse( BODY, "body" );
	$tpl->FastPrint("BODY");
}
?>