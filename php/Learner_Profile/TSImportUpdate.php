<?php
/**************************/
/*�ɦW:TSImportUpdate.php*/
/*����:�h���ǥ͸�ƿ�J(�P��Ҩt�ΦP�B)*/
/*************************/
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

if(($error = add_student()) == -1){
	show_page( "not_access.tpl" ,"�ǥͤw�פJ����", "", "<a href=\"./TSInsertMS.php\">�^�ǥͷs�W</a>");
}
else{
	echo "$error<br>";
}

function add_student(){
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $SDB, $course_id, $course_year, $course_term;
	// �s��mysql
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$error = "��Ʈw�s�����~!!";
		return $error;
	}
	// ���X�ҵ{�N�X
	$Qs1 = "select course_no from course where a_id=$course_id";
	if ($result1 = mysql_db_query($DB,$Qs1)){
		$row1 = mysql_fetch_array($result1);
		$course_no = explode("_",$row1['course_no']);
		$cour_cd = $course_no[0];
		$grp = $course_no[1];
	}
	else{
		$error = "mysql��ƮwŪ�����~!!";
		return "$error $Qs1<br>";
	}

	// �s����Ҩt�θ�Ʈw
	if( !($cnx = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) ){  
		Error_handler( "�b sybase_connect �����~�o��" , $cnx );  
	}
	$csd = @sybase_select_db($SDB, $cnx);
	
	// �q��Ҩt�Ψ��X��ҦW��
	$Qs2 = "select std_no from a31v_sel_class_tea where cour_cd = '$cour_cd' and grp = '$grp'";
	$cur = sybase_query($Qs2, $cnx);
	if(!$cur) {  
		Error_handler( "�b sybase_exec �����~�o��( �S�����жǦ^ ) " , $cnx );  
	}	
	// ��s��ҦW��
	while($array=sybase_fetch_array($cur)){
		$Qs3 = "select a_id from user where id='$array[std_no]'";
		if ($result3 = mysql_db_query($DB,$Qs3)){
			if(($row3 = mysql_fetch_array($result3))==0){
				$error = "���ǥͤ��s�b!!";
				echo "$error $array[std_no]<br>";
				continue;
			}
		}
		else{
			$error = "mysql��ƮwŪ�����~!!";
			return "$error $Qs3<br>";
		}
		
		$Qs4 = "select group_id from course where a_id='$course_id'";
		if ($result4 = mysql_db_query($DB,$Qs4)){
			$row4 = mysql_fetch_array($result4);
		}
		else{
			$error = "mysql��ƮwŪ�����~!!";
			return "$error $Qs4<br>";
		}
		
		$Qins = "insert into take_course (course_id, student_id, group_id, validated, credit, year, term) values ('$course_id', '$row3[a_id]', '$row4[group_id]', '1', '1', '$course_year', '$course_term')";
		if(!($resulti = mysql_db_query($DB,$Qins))){
			$error = "mysql��Ʈw�g�J���~!!";
			continue;
		}
	}

	// �q�оǨt�Ψ��X��Ҹ��
	$Qs5 = "select student_id, id from user u,take_course tc where course_id = '$course_id' and u.a_id=tc.student_id and tc.year='$course_year' and tc.term = '$course_term'";
	if ($result5 = mysql_db_query($DB,$Qs5)){
		while($row5 = mysql_fetch_array($result5)){
			$std_id = $row5['student_id'];
			$std_no = $row5['id'];
			// �q��Ҩt�θ�Ʈw�����X�ӦW�ǥͦb�����Ҫ���Ҹ��
			$Qs6 = "select std_no from a31v_sel_class_tea where cour_cd = '$cour_cd' and grp = '$grp' and std_no = '$std_no'";
			$cur = sybase_query($Qs6, $cnx);
			if(!$cur) {  
				Error_handler( "�b sybase_exec �����~�o��( �S�����жǦ^ ) " , $cnx );  
			}
			// �Y�L��ƫh�R����Ҹ�Ƥά�������
			if(($array=sybase_fetch_array($cur))==0){
				delete_stu($std_id);
			}
		}
	}
	else{
		$error = "mysql��ƮwŪ�����~!!";
		return "$error $Qs5<br>";
	}
	
	sybase_close( $cnx);
	return -1;		
}

// �R����Ҹ�Ƥά�������
function delete_stu ($key='') {
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $course_id, $course_year, $course_term;
	
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$message = "��Ʈw�s�����~!!";
		show_page_d ( $message );
		return;
	}

	$Q1 = "Select student_id From take_course Where student_id='$key' and year='$course_year' and term = '$course_term'";
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
	mysql_db_query( $DB.$course_id, "Delete From log Where user_id='$key'");
	mysql_db_query( $DB, "Delete From take_course Where student_id='$key' and course_id = '$course_id' and year='$course_year' and term = '$course_term'");
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
	if( mysql_num_rows ( $resultOBJ ) == 1 )
	{
		mysql_db_query( $DB, "Delete From log Where user_id='$key'");
		//mysql_db_query( $DB, "Delete From user Where a_id='$key'");
		mysql_db_query( $DB, "delete from gbfriend where my_id = '$key' or friend_id='$key'" );	
	}
}

function Error_Handler( $msg, $cnx ) {  
	echo "$msg \n";
	sybase_close( $cnx); exit();  
}
?>
