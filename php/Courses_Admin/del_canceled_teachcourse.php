<?php
/*****�R����Ǵ���}�{�ҵ{���Юv�ҵ{���Y*********************
	bluejam 2006-03-20
	del_canceled_teachcourse.php
	
	show_ad2.tpl-->���s�W�١G�M�����}���\���ҵ{
	�N���Ӧ��}���̫�����}�Ҩ��ǽҵ{���Юv�W��M��
**********************************************/
require 'fadmin.php';
if ( isset($PHPSESSID) && session_check_admin($PHPSESSID) ) {
	$content = list_canceled_course();
}
else
{
	show_page( "index_ad.tpl", "�A���v�����~�A�Э��s�n�J!!");
}
?>
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
</head>

<body>

<?php
echo "<br><a href=../check_admin.php>�^�t�κ޲z����</a>";

function Error_Handler( $msg, $cnx ) {  
	echo "$msg \n";
	sybase_close( $cnx); exit();  
}

function list_canceled_course()
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;
	
	// �s����Ҩt�θ�Ʈw
	$cnx = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13");  
	if( ! $cnx ) {  
		Error_handler( "�b sybase_connect �����~�o��" , $cnx );  
	}
	
	// ���O��"academic"(�@���) �M "academic_gra"(�b¾�M�Z) ���X���Ǵ��T�w�}�Ҥ��ҵ{�N�X
	/*
		year �}�ҾǦ~,   
		term �}�ҾǴ�,   
		unitname �}�Ҩt��,   
		class �}�Ҩt�Ҧ~��,   
		cour_cd �ҵ{�N��,   
		grp �Z�O,   
		id �}�ұЮv�����Ҹ� 
	*/
	//���X"academic"(�@���)
	$csd = @sybase_select_db("academic", $cnx);
	$cur = sybase_query("select distinct year, term ,unitname ,class,cour_cd, grp from a31vcurriculum_tea order by cour_cd", $cnx);	 
	if(!$cur) {  
		Error_handler( "�b sybase_exec �����~�o��( �S�����жǦ^ ) �@��͸�ƪ�" , $cnx );  
	}
	$num_row=0;  
	// ���X"academic"(�@���)���\�Ǧ^��Ƥ���cour_cd �ҵ{�N���Mgrp �Z�O,�զX���ҵ{�s��
	while( $result=sybase_fetch_array( $cur ) ) {  
		$num_row++; 
		$sybascihid[] = $result[4]."_".$result[5];
	}
	
	//���X"academic_gra"(�b¾�M�Z)
	$csd = @sybase_select_db("academic_gra", $cnx);
	$cur = sybase_query("select distinct year, term ,unitname ,class,cour_cd, grp from a31vcurriculum_tea order by cour_cd", $cnx); 
	if(!$cur) {  
		Error_handler( "�b sybase_exec �����~�o��( �S�����жǦ^ )--�b¾�M�Z��ƪ� " , $cnx );  
	}   
	// ���X"academic_gra"(�b¾�M�Z)���\�Ǧ^��Ƥ���cour_cd �ҵ{�N���Mgrp �Z�O,�զX���ҵ{�s��
	while( $result=sybase_fetch_array( $cur ) ) {  
		$num_row++; 
		$sybascihid[] = $result[4]."_".$result[5];
	}
	
	//**********���o�Ǵ�*********
		
	$result = sybase_query("select distinct year, term from a31vcurriculum_tea", $cnx);
	if(!$result) {  
		Error_handler( "�b sybase_exec �����~�o��( �S�����жǦ^ ) " , $cnx );  
	}
	if(!$row=sybase_fetch_array($result)){
		Error_handler( "�b sybase_exec �����~�o��( �S���Ǵ���� ) " , $cnx );  
	}
	$year = $row[0];
	$term = $row[1];		
	//***************************
	sybase_close( $cnx);
	
	//----------------------------------------------------------------------
	// �s��mysql
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) )
	{
		$error = "��Ʈw�s�����~!!";
		return $error;
	}
	
	
	
	//���X���Ǵ��Ҷ}���ҵ{
	$Q = "select distinct  course_no.course_id, teach_course.year, teach_course.term, course_group.name as group_name, course_no.course_no, course.name from course_no , course , teach_course , course_group Where ".$year." = teach_course.year AND ".$term." = teach_course.term AND teach_course.course_id = course.a_id AND teach_course.course_id = course_no.course_id AND course.group_id = course_group.a_id order by course_no.course_no";
	if( !($result = mysql_db_query($DB, $Q)) ){
		$error = "��Ʈwquery���~!! $Qi<br>";
		return $error;
	}
	
	$canceledcount = 0;		//�������ҵ{�ƥ�
	$deletecount = 0;		//�R���Ѯv�ҵ{���Y���ҵ{�ƥ�
	$cihnum = 0;		//���Ǵ��ثe�Ҷ}�ҵ{�ƥ�
	
	$cihrow = "<tr><th>Index</th><th>�Ǧ~</th><th>�Ǵ�</th><th>�t��</th><th>�ҵ{�s��</th><th>�ҵ{�W��</th><th>�׽Ҿǥ�</th><th>�R��</th></tr>";
	//���X���Ǵ��Ҷ}�ҵ{���ԲӸ��
	while(($row = mysql_fetch_array($result))){
		$cihnum++;
		$cancel=1;			//�P�_�O�_���������ҵ{
		foreach ($sybascihid as $value){
			if($value == $row[4] ){
				$cancel=0;
				break;
			}
		}
		//�ֹ�������ҵ{���׽ҾǥͤH�ƬO�_��0
		if( $cancel==1 ){
			$canceledcount++;
			$Q2 = "select count(student_id) from take_course where course_id=".$row[0];
			if( !($result2 = mysql_db_query($DB, $Q2)) ){
				$error = "��Ʈwquery���~!! $Qi<br>";
				return $error;
			}			
			$row2 = mysql_fetch_array($result2);
			$cihrow .= "<tr><td>$canceledcount</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td><td>$row[4]</td><td>$row[5]</td><td>$row2[0]</td>";
			if(strcmp($row2[0],"0") == 0 ){
				$course_id = $row[0];
				$delete = delete_teach($course_id, $year, $term);
				$deletecount++;
				$cihrow .= "<td>".$delete."</td></tr>";
			}
			else{
				$cihrow .= "<td></td></tr>";
			}
			
		}
	}
	$cihrow .= "<tr><td colspan=7>�ثe�ҵ{�@��".$cihnum."�Z, �����}�Ҫ��Z�Ʀ�".$canceledcount."�Z, �����R�����Z�Ƭ�".$deletecount."</td></tr>";
	mysql_close ($link);
	
	return $cihrow ;
}

function delete_teach($course_id, $year, $term)
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;
	
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$message = "��Ʈw�s�����~!!";
		show_page_d ( $message );
		return;
	}

	$Q1 = "Delete From teach_course Where course_id='$course_id' AND year='$year' AND term='$term'";
	if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
		$message = "��ƮwŪ�����~!!";
		show_page_d ( $message );
		return;
	}
	return "�T�w�R��";
}

?>
<table width="100%" border="1" cellpadding="0">
  <tr>
  	<td valign="top"><?=$content?></td>
  </tr>
</table>
</body>
</html>
