<?php
require 'fadmin.php';
update_status ("�ǥͧ���ϥΰO��");

if( !(isset($PHPSESSID) && ($check = session_check_teach($PHPSESSID)) ) && !(session_is_registered("admin") && $admin == 1) )
{
	show_page( "not_access.tpl" ,"�v�����~");
	exit;
}
if($check < 2 )
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


global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $skinnum;
if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
	echo ( "��Ʈw�s�����~!!" );
	return;
}
$Q1 = "Select student_id From take_course Where course_id='$course_id' and year='$course_year' and term = '$course_term' Order By student_id ASC";
if ( !($result1 = mysql_db_query( $DB, $Q1 ) ) )
{
	echo ("��ƮwŪ�����~!!");
	return;
}
else
{					
	$Qroll_id = "Select MAX(roll_id) From roll_book";
	$result_roll_id = mysql_db_query( $DB.$course_id, $Qroll_id );
	$row_roll_id = mysql_fetch_array($result_roll_id);		
	$roll_id =  $row_roll_id['MAX(roll_id)']+1;
	//echo	$roll_id;

	while ( $row = mysql_fetch_array($result1) )
	{
		//modify this line by rja: �쥻�O $_POST �A�{�b���F�]���o��  $_GET�A�ҥH����q�Y�� $_REQUEST

		$Q2 =  "insert into roll_book (user_id, roll_id, roll_date, state, note) values ('".$row['student_id']."', '$roll_id', '$date','".$_REQUEST["state".$row['student_id']]."', '".$_REQUEST["note".$row['student_id']]."')";
		//echo $Q2."<br>";

		if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) 
		{
			echo ("��Ʈw�g�J���~");		
			return;	
		}


	}	
	header("Location: RollBook.php?PHPSESSID=".$PHPSESSID);

	}
	?>
