<?php
require 'fadmin.php';
update_status ("�d�߾ǥͱK�X");

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
if ( isset( $id ) ) {
	if ( $id != NULL ) {
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$message = "��Ʈw�s�����~!!";
			show_page_d( $message );
			return;
		}
		for(reset($id);$key=key($id);next($id))
		{
			global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
			$Q1 = "Update user Set pass='$id[$key]' Where id='$key'";
			if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
				$message = "��Ʈw��s���~!!";
				show_page_d( $message );
				exit;
			}
		}
		if ( $version == "C" )
			$message = "�K�X�إߧ���!!";
		else
			$message = "Password created!!";
	}
}
show_page_d( $message );

function show_page_d ( $message = "" ) {
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id, $version, $id, $skinnum, $course_year, $course_term;
	$Q1 = "Select tc.student_id From take_course tc,user u Where tc.course_id='$course_id' and tc.year = '$course_year' and tc.term = '$course_year' and tc.credit='1' and tc.student_id=u.a_id Order By u.id ASC";
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		echo ( "��Ʈw�s�����~!!" );
		return;
	}
	if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
		echo ( "��ƮwŪ�����~!!" );
		return;
	}
	if( mysql_num_rows( $result ) == 0 )
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
		if($version=="C")
			$tpl->define(array(pass_list => "give_pass1_Ch.tpl"));
		else
			$tpl->define(array(pass_list => "give_pass1_En.tpl"));
		$tpl->define_dynamic("row", "pass_list");
		$tpl->assign( SKINNUM , $skinnum );
		$color == "#F0FFEE";
		while ( $row = mysql_fetch_array($result) )
		{
			if ( $color == "#F0FFEE" )
				$color = "#E6FFFC";
			else
				$color = "#F0FFEE";
			$tpl->assign( COLOR , $color );
			$Q2 = "Select * From user Where a_id='".$row['student_id']."'";
			if ( !($result1 = mysql_db_query( $DB, $Q2 ) ) ) {
				echo ( "��ƮwŪ�����~!!" );
				return;
			}
			$row1 = mysql_fetch_array($result1);
			$tpl->assign(STUDENT_ID, $row1['id']);
			if ( $id[$row1['id']] == "" || $id[$row1['id']] == NULL )
				$tpl->assign(PASSWORD, $row1['pass']);
			else
				$tpl->assign(PASSWORD, $id[$row1['id']]);
			$tpl->parse(ROWS, ".row");
		}
		$tpl->assign(MESSAGE, $message);
		$tpl->parse(BODY, "pass_list");
		$tpl->FastPrint("BODY");

	}
}
?>
