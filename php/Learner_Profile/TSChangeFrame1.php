<?php
/**************************/
/*�ɦW:TSDeleteFrame1.php*/
/*����:�ק�ǥ��ݩ�(��ť�A����)*/
/*�����ɮ�:*/
/*************************/
require 'fadmin.php';
update_status ("�ק�ǥ�");

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
if( isset($student) && $student != NULL )
	$error = change_stu ();
show_page_d ( $error );


function change_stu () {
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $student, $version, $course_id, $nocredit, $course_year, $course_term;
	
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$message = "��Ʈw�s�����~!!";
		show_page_d ( $message );
		return;
	}
	for(reset($student);$key=key($student);next($student))
	{
		if ( $nocredit != 1 )
			$Q1 = "update take_course set credit='0' where student_id='$key' and course_id='$course_id' and year='$course_year' and term='$course_term'";
		else
			$Q1 = "update take_course set credit='1' where student_id='$key' and course_id='$course_id' and year='$course_year' and term='$course_term'";
		if ( !($resultOBJ = mysql_db_query( $DB, $Q1 ) ) ) {
			$message = $message . " �ǥ� $key �ק���~ ";
		}
	}
	include("Generate_studinfo.php");
	return $message;
}

function show_page_d ( $message = "" ) {
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id, $version, $nocredit, $skinnum, $course_year, $course_term;
	
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		echo ( "��Ʈw�s�����~!!" );
		return;
	}
	
	$Q1 = "Select student_id From take_course Where course_id='$course_id' and year='$course_year' and term='$course_term' Order By student_id ASC";
	if ( !($resultOBJ = mysql_db_query( $DB, $Q1 ) ) ) {
		echo ( "��ƮwŪ�����~!!" );
		return;
	}
	else
	{
		if( mysql_num_rows ( $resultOBJ ) == 0 )
		{
			if( $version=="C" )
				show_page( "not_access.tpl" ,"�ثe�L����ǥ͸��");
			else
				show_page( "not_access.tpl" ,"No data now!!");
		}
		else
		{
			include("class.FastTemplate.php3");
			$tpl = new FastTemplate("./templates");
			$tpl->define(array(change_list => "TSChangeFrame1.tpl"));
			$tpl->define_dynamic("row", "change_list");
			
			if ( $nocredit != 1 )
				$Q1 = "Select u.*, tc.credit From user u,take_course tc Where u.a_id=tc.student_id And tc.course_id='$course_id' and credit='1' and tc.year='$course_year' and tc.term='$course_term' Order By u.id ASC";
			else
				$Q1 = "Select u.*, tc.credit From user u,take_course tc Where u.a_id=tc.student_id And tc.course_id='$course_id' and credit='0' and tc.year='$course_year' and tc.term='$course_term' Order By u.id ASC";
			$resultOBJ = mysql_db_query( $DB, $Q1);
			$tpl->assign( SKINNUM , $skinnum );
			$color = "#000066";
			$tpl->assign( COLOR , $color );
			if ( $version == "C" ) {
				$tpl->assign(CHOICE, "<b><font color = #FFFFFF>���</font></b>");
				$tpl->assign(STUDENT_NAME, "<b><font color = #FFFFFF>�m�W</font></b>");
				$tpl->assign(STUDENT_ID, "<b><font color = #FFFFFF>�Ǹ�</font></b>");
			}
			else {
				$tpl->assign(CHOICE, "<b><font color = #FFFFFF>Choice</font></b>");
				$tpl->assign(STUDENT_NAME, "<b><font color = #FFFFFF>Name</font></b>");
				$tpl->assign(STUDENT_ID, "<b><font color = #FFFFFF>ID</font></b>");
			}
			$tpl->parse(ROWS, ".row");
			
			$color == "#F0FFEE";
			while ( $row1 = mysql_fetch_array ( $resultOBJ ) )
			{
				if ( $color == "#F0FFEE" )
					$color = "#E6FFFC";
				else
					$color = "#F0FFEE";
				$tpl->assign( COLOR , $color );
				$tpl->assign(CHOICE, "<input type=checkbox name=\"student[".$row1['a_id']."]\">");
				$tpl->assign(STUDENT_NAME, $row1['name']);
				$tpl->assign(STUDENT_ID, $row1['id']);
				$tpl->parse(ROWS, ".row");
			}
			if ( $version == "C" ) {
				$tpl->assign(IMG, "img");
				$tpl->assign(CNAME0, "���ץ�");
				$tpl->assign(CNAME1, "��ť��");
				$tpl->assign(DATAQUERY, "��ƳB�z��....");
				if ( $nocredit != 1 )
					$tpl->assign(DELETE, "�ର��ť��");
				else
					$tpl->assign(DELETE, "�ର���ץ�");
				$tpl->assign(CLEAR, "���s���");
			}
			else {
				$tpl->assign(IMG, "img_E");
				$tpl->assign(CNAME0, "Credit");
				$tpl->assign(CNAME1, "No Credit");
				if ( $nocredit != 1 )
					$tpl->assign(DELETE, "To not Credit");
				else
					$tpl->assign(DELETE, "To Credit");
				$tpl->assign(DATAQUERY, "Data Querying....");
				$tpl->assign(CLEAR, "Clear");
			}
			$tpl->assign(NOCREDIT, $nocredit);
			$tpl->assign(CID.$nocredit, "selected");
			$tpl->parse(BODY, "change_list");
			$tpl->FastPrint("BODY");
		}
	}
}
?>
