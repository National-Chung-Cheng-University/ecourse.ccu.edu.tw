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


global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $skinnum, $course_year, $course_term;
if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
	echo ( "��Ʈw�s�����~!!" );
	return;
}
$Q1 = "Select student_id From take_course Where course_id='$course_id' and year='$course_year' and term = '$course_term' Order By student_id ASC";
if ( !($resultOBJ = mysql_db_query( $DB, $Q1 ) ) )
{
	echo ("��ƮwŪ�����~!!");
	return;
}
else
{
	if( mysql_num_rows ( $resultOBJ ) == 0 )
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
		
		$tpl->define(array(student_list => "modifyRollRecord.tpl"));

		$tpl->assign( ROLL_ID , $roll_id );
		
		$tpl->define_dynamic("row", "student_list");
		$tpl->assign( SKINNUM , $skinnum );
		$color = "#000066";
		$tpl->assign( COLOR , $color );
		if ( $version == "C" ) {
			$tpl->assign( STUDENT_ID, "<b><font color=#FFFFFF>�Ǹ�</font></b>" );
			$tpl->assign( STUDENT_NAME, "<b><font color=#FFFFFF>�m�W</font></b>" );
			$tpl->assign( STATE, "<b><font color=#FFFFFF>�X�u���p<br>
										<input type='radio' name='state' value='0' onClick='select_all();' checked>
									  �X�u
										<input type='radio' name='state' value='1' onClick='select_all();'>
									  �ʮu
										<input type='radio' name='state' value='2' onClick='select_all();'>
									  ���
										<input type='radio' name='state' value='3' onClick='select_all();'>
									  ���h
										<input type='radio' name='state' value='4' onClick='select_all();'>
									  �а�
										<input type='radio' name='state' value='5' onClick='select_all();'>
									  ��L [����] 
									  </font></b>" );
									  
			$tpl->assign( NOTE, "<b><font color=#FFFFFF>�Ƶ�</font></b>" );
		}
		else {//en version
			
			$tpl->assign( STUDENT_ID, "<b><font color=#FFFFFF>ID</font></b>" );
			$tpl->assign( STUDENT_NAME, "<b><font color=#FFFFFF>Name</font></b>" );
			$tpl->assign( STATE, "<b><font color=#FFFFFF>state<br>
										<input type='radio' name='state' value='0' onClick='select_all();' checked>
									  �X�u
										<input type='radio' name='state' value='1' onClick='select_all();'>
									  �ʮu
										<input type='radio' name='state' value='2' onClick='select_all();'>
									  ���
										<input type='radio' name='state' value='3' onClick='select_all();'>
									  ���h
										<input type='radio' name='state' value='4' onClick='select_all();'>
									  �а�
										<input type='radio' name='state' value='5' onClick='select_all();'>
									  else [Select All] 
									  </font></b>" );	
			$tpl->assign( NOTE, "<b><font color=#FFFFFF>Note</font></b>" );
		}
		$tpl->parse(ROWS, ".row");
		$color = "#BFCEBD";
		
		$Q1 = "Select u.id, u.email, u.name, u.a_id From user u ,take_course tc Where u.a_id=tc.student_id And tc.course_id='$course_id' and tc.credit = '1' and tc.year='$course_year' and tc.term = '$course_term' Order By u.id ASC";
		
		$result = mysql_db_query( $DB, $Q1 );
		$color == "#F0FFEE";
		while ( $row1 = mysql_fetch_array($result) )
		{
			if ( $color == "#F0FFEE" )
				$color = "#E6FFFC";
			else
				$color = "#F0FFEE";
			$tpl->assign( COLOR , $color );
			
			$tpl->assign(STUDENT_ID, $row1[id]);

			if( $row1['email'] != NULL )
				$tpl->assign(STUDENT_NAME, "<A HREF=mailto:".$row1['email'].">".$row1['name']."</A>");
			else
				$tpl->assign(STUDENT_NAME, $row1['name']);

			$a_id = $row1['a_id'];
			
			$Q2 = "Select state, note,roll_date From roll_book Where roll_id= '$roll_id' and user_id ='$a_id'";
		
			$result2 = mysql_db_query( $DB.$course_id, $Q2 );
			$row2 = mysql_fetch_array($result2);
			
			$tpl->assign(STATE, 	"<b><input name='state$a_id' type='radio' value='0'".checkOrNot(0,$row2[state]).">
									  �X�u
										<input type='radio' name='state$a_id' value='1'".checkOrNot(1,$row2[state]).">
									  �ʮu
										<input type='radio' name='state$a_id' value='2'".checkOrNot(2,$row2[state]).">
									  ���
										<input type='radio' name='state$a_id' value='3'".checkOrNot(3,$row2[state]).">
									  ���h
										<input type='radio' name='state$a_id' value='4'".checkOrNot(4,$row2[state]).">
									  �а�
										<input type='radio' name='state$a_id' value='5'".checkOrNot(5,$row2[state]).">
									  ��L  </b>");
									  
			$tpl->assign(NOTE, "<input type='text' name='note$a_id' value='".$row2[note]."'>");				
			$tpl->assign(ROLL_DATE, $row2[roll_date]);	
			
			$tpl->parse(ROWS, ".row");
		}
		
		
		$tpl->assign(NOCREDIT, $nocredit);
		$tpl->assign(CID.$nocredit, "selected");
		
		
		$tpl->parse(BODY, "student_list");
		$tpl->FastPrint("BODY");
	}
}
function checkOrNot($i,$j) {
  if($i==$j)
  	return " checked ";
  else
  	return "";	
}
?>
