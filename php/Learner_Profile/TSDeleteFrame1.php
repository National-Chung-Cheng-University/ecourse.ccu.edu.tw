<?php
//2006/11/7 �����i�R�����ץ� by julien Pi
/**************************/
/*�ɦW:TSDeleteFrame1.php*/
/*����:�R���ǥ͸��*/
/*�����ɮ�:*/
/*TSDeleteFrame2.php*/
/*************************/
require 'fadmin.php';
update_status ("�R���ǥ�");

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
delete_stu ();
show_page_d ( );


function delete_stu () {
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $student, $version, $course_id, $course_year, $course_term;
	
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$message = "��Ʈw�s�����~!!";
		show_page_d ( $message );
		return;
	}
	for(reset($student);$key=key($student);next($student))
	{
		$Q1 = "Select student_id From take_course Where student_id='$key' and year='$course_year' and term ='$course_term'";
		if ( !($resultOBJ = mysql_db_query( $DB, $Q1 ) ) ) {
			$message = "��ƮwŪ�����~!!";
			show_page_d ( $message );
			return;
		}
		$Q2 = "Select id From user Where a_id='$key'";
		if ( !($resultOBJid = mysql_db_query( $DB, $Q2 ) ) ) {
			$message = "��ƮwŪ�����~!!";
			show_page_d ( $message );
			return;
		}
		$row_id = mysql_fetch_array( $resultOBJid );
		mysql_db_query( $DB.$course_id, "Delete From handin_homework Where student_id='$key'" );
		$resultOBJ2 = mysql_db_query( $DB.$course_id, "Select a_id From homework");
		while($row = mysql_fetch_array ( $resultOBJ2 )) {
			$target = "../../$course_id/homework/".$row['a_id']."/".$row_id['id'];
			if ( is_dir($target) )
				deldir ( $target );
		}
		mysql_db_query( $DB.$course_id, "Delete From take_exam Where student_id='$key'");
		mysql_db_query( $DB.$course_id, "Delete From take_questionary Where student_id='$key'");
		mysql_db_query( $DB.$course_id, "Delete From log Where user_id='$key'");
		mysql_db_query( $DB, "Delete From take_course Where student_id='$key' and course_id = '$course_id' and year='$course_year' and term ='$course_term'");
		/*
		//coop
		$resultcoop = mysql_db_query( $DBC.$course_id, "Select a_id From coop");
		while($row_coop = mysql_fetch_array ( $resultcoop )) {
			mysql_db_query( $DBC.$course_id, "Delete From coop_".$row_coop['a_id']."_group Where student_id='".$row_id['id']."'");
			mysql_db_query( $DBC.$course_id, "Delete From discuss_".$row_coop['a_id']."_subscribe Where user_id='".$row_id['id']."'");
			mysql_db_query( $DBC.$course_id, "Delete From grade_".$row_coop['a_id']." Where give_id='$key' or gived_id ='$key'");
			mysql_db_query( $DBC.$course_id, "Delete From guestbook_".$row_coop['a_id']." Where user_id='$key'");
			mysql_db_query( $DBC.$course_id, "Delete From log_".$row_coop['a_id']." Where user_id='$key'");
			mysql_db_query( $DBC.$course_id, "Delete From note_".$row_coop['a_id']." Where student_id='$key'");
			mysql_db_query( $DBC.$course_id, "Delete From share_".$row_coop['a_id']." Where student_id='$key'");
			mysql_db_query( $DBC.$course_id, "Delete take_coop Where student_id='$key'");
		}
		*/
		/*
		if( mysql_num_rows ( $resultOBJ ) == 1 )
		{
			mysql_db_query( $DB, "Delete From log Where user_id='$key'");
			//mysql_db_query( $DB, "Delete From user Where a_id='$key'");
			mysql_db_query( $DB, "delete from gbfriend where my_id = '$key' or friend_id='$key'" );	
		}
		*/
	}
	include("Generate_studinfo.php");
}

function show_page_d ( $message = "" ) {
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id, $version, $nocredit, $skinnum, $course_year, $course_term;
	
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		echo ( "��Ʈw�s�����~!!" );
		return;
	}
	
	$Q1 = "Select student_id From take_course Where course_id='$course_id' and year='$course_year' and term ='$course_term' Order By student_id ASC";
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
			$tpl->define(array(delete_list => "TSDeleteFrame1.tpl"));
			$tpl->define_dynamic("row", "delete_list");
			$tpl->assign( SKINNUM , $skinnum );
			if ( !isset($nocredit) )//�]�������F���ץ͡A�w�]����ť��
			{
				$nocredit = 1;
			}
			//if ( $nocredit != 1 )
			//	$Q1 = "Select u.*, tc.credit From user u,take_course tc Where u.a_id=tc.student_id And tc.course_id='$course_id' and credit='1' Order By u.id ASC";
			//else
			if($nocredit == 1 ){	//��ť��
				$Q1 = "Select u.*, tc.credit From user u,take_course tc Where u.a_id=tc.student_id And tc.course_id='$course_id' and credit='0' and year='$course_year' and term ='$course_term' Order By u.id ASC";
			}
			else{					//�֯Z��
				$Q1 = "Select u.*, tc.credit From user u,take_course tc Where u.a_id=tc.student_id And tc.course_id='$course_id' and credit='1' and combine_course_id !='-1' and year='$course_year' and term ='$course_term' Order By u.id ASC";	
			}
			$resultOBJ = mysql_db_query( $DB, $Q1);
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
				//$tpl->assign(CNAME0, "���ץ�");
				$tpl->assign(CNAME1, "��ť��");
				$tpl->assign(CNAME2, "�֯Z��");
				$tpl->assign(DATAQUERY, "��ƳB�z��....");
				$tpl->assign(DELETE, "�R��");
				$tpl->assign(CLEAR, "���s���");
			}
			else {
				$tpl->assign(IMG, "img_E");
				//$tpl->assign(CNAME0, "Credit");
				$tpl->assign(CNAME1, "No Credit");
				$tpl->assign(CNAME2, "Combine");
				$tpl->assign(DATAQUERY, "Data Querying....");
				$tpl->assign(DELETE, "Delete");
				$tpl->assign(CLEAR, "Clear");
			}
			$tpl->assign(NOCREDIT, $nocredit);
			$tpl->assign(CID.$nocredit, "selected");
			$tpl->parse(BODY, "delete_list");
			$tpl->FastPrint("BODY");
		}
	}
}
?>
