<?php
require 'fadmin.php';

if(!(isset($PHPSESSID) && $check = session_check_teach($PHPSESSID)) )
{
	show_page( "not_access.tpl" ,"�v�����~");
	exit;
}
if($check != 2)
{
	if( $version=="C" ) {
		show_page( "not_access.tpl" ,"�A�S���v���ϥΦ��\��");
		exit;
	}
	else {
		show_page( "not_access.tpl" ,"You have No Permission!!");
		exit;
	}
}
global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $skinnum, $course_year, $course_term;
$Q1 = "Select student_id From take_course Where course_id='$course_id' and year='$course_year' and term = '$course_term' Order By student_id ASC";
if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
	echo ( "��Ʈw�s�����~!!" );
	return;
}
if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
	echo ( "��ƮwŪ�����~!!" );
	return;
}
else
{
	if( mysql_num_rows( $result ) == 0 )
	{
		if( $version=="C" )
			show_page( "not_access.tpl" ,"���ҵ{�|��������ǥ�!");
		else
			show_page( "not_access.tpl" ,"There is no Student in this Class!!");
	}
	else
	{
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate("./templates");
		$tpl->define(array(student_list => "ShowStudentListForTraceInfo.tpl"));
		$tpl->define_dynamic("row", "student_list");
		if ( $nocredit != 1 )
			$Q2 = "Select u.a_id, u.name, u.id From user u,take_course tc Where u.a_id=tc.student_id And tc.course_id='$course_id' and tc.credit = '1' and tc.year='$course_year' and tc.term = '$course_term' Order By u.id ASC";
		else
			$Q2 = "Select u.a_id, u.name, u.id From user u,take_course tc Where u.a_id=tc.student_id And tc.course_id='$course_id' and tc.credit = '0' and tc.year='$course_year' and tc.term = '$course_term' Order By u.id ASC";
		if ( !($result1 = mysql_db_query( $DB, $Q2 ) ) ) {
			echo ( "��ƮwŪ�����~!!" );
			return;
		}
		$tpl->assign( SKINNUM , $skinnum );
		$color = "#000066";
		$tpl->assign( COLOR , $color );
		if ( $version == "C" ) {
			$tpl->assign( STUDENT_ID, "<b><font color=#FFFFFF>�Ǹ�</font></b>" );
			$tpl->assign( STUDENT_NAME, "<b><font color=#FFFFFF>�m�W</font></b>" );
		}
		else {
			$tpl->assign( STUDENT_ID, "<b><font color=#FFFFFF>ID</font></b>" );
			$tpl->assign( STUDENT_NAME, "<b><font color=#FFFFFF>Name</font></b>" );
		}
		$tpl->parse(ROWS, ".row");
		$color == "#F0FFEE";
		while ( $row1 = mysql_fetch_array($result1) )
		{
			if ( $color == "#F0FFEE" )
				$color = "#E6FFFC";
			else
				$color = "#F0FFEE";
			$tpl->assign( COLOR , $color );
			$tpl->assign(A_ID, $row1['a_id']);
			$tpl->assign(STUDENT_NAME, $row1['name']);
			$tpl->assign(STUDENT_ID, "<A HREF=StudentTraceInfo.php?student_aid=A_ID>".$row1['id']."</A>");
			$tpl->parse(ROWS, ".row");
		}
		if ( $version == "C" ) {
			$tpl->assign(IMG, "img");
			$tpl->assign(CNAME0, "���ץ�");
			$tpl->assign(CNAME1, "��ť��");
			$tpl->assign(NOTE, "��ܱ��d�ߪ��ǥ�");
		}
		else {
			$tpl->assign(IMG, "img_E");
			$tpl->assign(CNAME0, "Credit");
			$tpl->assign(CNAME1, "No Credit");
			$tpl->assign(NOTE, "Choose the students you want to inquire");
		}
		$tpl->assign(NOCREDIT, $nocredit);
		$tpl->assign(CID.$nocredit, "selected");
		$tpl->parse(BODY, "student_list");
		$tpl->FastPrint("BODY");
	}
}
?>
