<?php
require 'fadmin.php';
update_status ("�ǥ͹wĵ�t��");

if(isset($PHPSESSID) && session_check_teach($PHPSESSID) == 2)
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_year, $course_term;	
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"��Ʈw�s�����~!!" );
		exit;
	}
	//��s
	if($_POST['update'] == 'yes'){
		//�d�X�Ҧ����ǥ�
		$sql = "SELECT u.a_id, u.id FROM user u, take_course tc WHERE u.a_id=tc.student_id AND tc.course_id='$course_id' and tc.year='$course_year' and tc.term = '$course_term'  and tc.credit = '1' ORDER BY u.id ASC";
		$res = mysql_db_query($DB, $sql);
		$num = mysql_num_rows($res);	
		for($i=0; $i< $num ;$i++){
			$all_stu[$i] = mysql_fetch_array($res);
		}				
		$sql = "SELECT * FROM early_warning WHERE course_id='".$_POST['c_id']."' and year='".$_POST['c_year']."' and term='".$_POST['c_term']."'";
		$result = mysql_db_query($DB, $sql);
		$num_cnt = mysql_num_rows($result);			//�����P�_�L�wĵ�ҵ{�ϥ�
		for($i=0; $i < $num; $i++){
			$tmp =$i+1;
			if($_POST["who_$tmp"] == "on"){//�����
				$all_stu[$i]['select'] ='1';
				$all_stu[$i]['reason'] = $_POST["reason_$tmp"];
			}
			else{
				$all_stu[$i]['select'] ='0';
			}				
		}			
		//echo "<pre>".print_r($all_stu,true) ."</pre>";					
		for($i=0; $i < $num; $i++){
			$tmp =$i+1;
			$sql = "SELECT * FROM early_warning WHERE course_id='".$_POST['c_id']."'  and student_id='".$_POST["student_id_$tmp"]."' and year='".$_POST['c_year']."' and term='".$_POST['c_term']."'";
			$res = mysql_db_query($DB, $sql);
			$isHave = mysql_num_rows($res);
			$row = mysql_fetch_array($res);
			if($all_stu[$i]['select'] == '0' && $isHave == 1){  //�S���� delete ���O�s�b �ݭn�R��
				$sql = "DELETE FROM early_warning WHERE a_id='".$row['a_id']."'";
			}
			elseif($all_stu[$i]['select'] == '1' &&  $isHave == 1){ //����� update 				
				$sql = "UPDATE early_warning SET reason='".$_POST["reason_$tmp"]."' WHERE a_id='".$row['a_id']."'";
			}
			elseif($all_stu[$i]['select'] == '1' &&  $isHave == 0){ //�L��� insert 
				$sql = "INSERT INTO early_warning (course_id, student_id, year, term, reason, mdate) values ('".$_POST['c_id']."', '".$_POST["student_id_$tmp"]."', '".$_POST['c_year']."', '".$_POST['c_term']."', '".$_POST["reason_$tmp"]."', curdate() )";				
			}
			$res = mysql_db_query($DB, $sql);								
		}
		if($num_cnt == 0) {
			$sql = "INSERT INTO early_warning (course_id, student_id, year, term, reason, mdate) values ( $course_id, 9999999, $course_year, $course_term, 4, curdate() )";
			//echo $sql;
			$res = mysql_db_query($DB, $sql);
		}
		//2012.11.26 �оǲխn�D�N'�w�g��s'�קאּ'�w�����wĵ' ******************
		echo "<script language='javascript'>";
		//echo "alert('�w�g��s')";
		echo "alert('�w�����wĵ')";
		echo "</script>";		
		echo "<font color='red'>�w�����wĵ</font><br>";	
	}	
	//���X�Ӫ��Ҫ��ǥ�
	$stu_list = get_student_list($course_id, $course_year, $course_term);
	//���ǥͤ~��
	if($stu_list != 0){
		//�d�X�Ӫ��ҩҦ��� �@�~ �P ���� ���W�ٻP��v
		$res_exam 		= get_exam_score_name($course_id);
		$res_homework	= get_homework_score_name($course_id);
		$exam_num		= mysql_num_rows($res_exam);
		$homework_num	= mysql_num_rows($res_homework);
		//�u�n���@�Ӥ��� 0 �N�i�h��
		//if(($exam_num != 0)||($homework_num != 0)){
			//�d�X�ǥͪ� �@�~ �P ���� �P�`��
			$all_data = get_all_stu_score($course_id, $stu_list, $res_exam, $res_homework, $exam_num, $homework_num);
			//�N���Gshow�X
			//echo "<pre>".print_r($all_data,true)."</pre>";
			output_page($all_data, $course_year, $course_term, $course_id);			
		//}
		//�S�����Z
		//else{
			//if( $version=="C" )
				//show_page( "not_access.tpl" ,"���ҵ{�|���������Z!");
			//else
				//show_page( "not_access.tpl" ,"There is no SCORE in this Class!!");
		//}
	}
	//�S���ǥ�
	else{
		if( $version=="C" )
			show_page( "not_access.tpl" ,"���ҵ{�|��������ǥ�!");
		else
			show_page( "not_access.tpl" ,"There is no Student in this Class!!");	
	}	
}
	
//���X�Ӫ��Ҫ��ǥ�	
function get_student_list($course_id, $course_year, $course_term)
{
	global $DB;
	//���X�Ӫ��Ҫ��ǥ�
	$sql = "SELECT u.a_id, u.id, u.name , u.email, u.job, u.grade FROM user u, take_course tc WHERE u.a_id=tc.student_id AND tc.course_id='$course_id' and tc.year='$course_year' and tc.term = '$course_term'  and tc.credit = '1' ORDER BY u.id ASC";
	if ( !($res = mysql_db_query( $DB, $sql ) ) ) {
		show_page( "not_access.tpl" ,"��ƮwŪ�����~1!!" );
		exit;
	}
	//�P�_�O�_���ǥ͡A�p�G�S���N�Ǧ^ 0
	$stu_num = mysql_num_rows($res);
	if($stu_num == 0){
		return 0;
	}	
	else{
		for($i=0; $i < $stu_num ; $i++){
			$row = mysql_fetch_array($res);
			$stu_list[$i]['a_id'] = $row['a_id'];
			$stu_list[$i]['id'] = $row['id'];
			$stu_list[$i]['name'] = $row['name'];
			$stu_list[$i]['job'] = $row['job'];
			$stu_list[$i]['grade'] = $row['grade'];
			$stu_list[$i]['email'] = $row['email'];
		}
		return $stu_list;
	}
}	

//�d�X���窺��v�P����
function get_exam_score_name($course_id)
{
	global $DB;
	//��Xexam�@���W�١@�ʤ���@a_id is_online �p�G��public �B�ɶ�����0
	$sql = "SELECT name,percentage,a_id  FROM exam where ( public = '1' or (end_time != '00000000000000' && beg_time <= ".date("YmdHis")." ) ) ORDER BY a_id";
	if ( !($res = mysql_db_query( $DB.$course_id, $sql ) ) ) {
		show_page( "not_access.tpl" ,"��ƮwŪ�����~2!!" );
		exit;
	}	
	return $res;
}

//�d�X�@�~����v�P����
function get_homework_score_name($course_id)
{
	global $DB;
	//��X�@homework���W�١@�ʤ���@a_id 
	$sql = "SELECT name,percentage,a_id FROM homework where public = '1' or public = '3' ORDER BY a_id";
	if ( !($res = mysql_db_query( $DB.$course_id, $sql ) ) ) {
		show_page( "not_access.tpl" ,"��ƮwŪ�����~3!!" );
		exit;
	}
	return $res; 
}

//�d�X�ǥͪ� �@�~ �P ���� �P�`�� �ñN�ǥ͸�ƥ[�J�}�C
function get_all_stu_score($course_id, $stu_list, $res_exam, $res_homework, $exam_num, $homework_num)
{
	global $DB;
	// 0          1     2       3         4         5      6...         ...          ...
	//�t�үZ��,�@�Ǹ�, �m�W, email, �wĵ�Ŀ�, �wĵ��], ���禨�Z... , �@�~���Z... , �`���Z
	
	//�N�ǥͪ���ƴ��J�}�C�� 0~5
	$all_data = add_stu_list_into_array($stu_list);		
	//���� -- ��v�P�W��
	if($exam_num != 0){
		//�N���窺�W�ٻP��v�[�b $all_data[0] ,�q $all_data[0][6]�~��s�W
		for($j=6; $j < $exam_num+6; $j++){
			$row_e = mysql_fetch_array($res_exam);
			$all_data[0][$j]['a_id'] = $row_e['a_id'];
			$all_data[0][$j]['name'] = $row_e['name'];
			$all_data[0][$j]['percentage'] = $row_e['percentage'];
		}		
		//��������ϥΨ�$all_data����m
		//$used_count = $exam_num+6;
	}

	$used_count = $exam_num+6;	
	//�@�~ -- ��v�P�W��
	if($homework_num != 0){
		for($j = $used_count; $j < $homework_num+$used_count; $j++){
			$row_h = mysql_fetch_array($res_homework);
			$all_data[0][$j]['a_id'] = $row_h['a_id'];
			$all_data[0][$j]['name'] = $row_h['name'];
			$all_data[0][$j]['percentage'] = $row_h['percentage'];
		}
		//�����@�~�ϥΨ�$all_data����m
		$used_count += $homework_num;			
	}		
	//�`�������Y
	$all_data[0][$used_count] = "�`���Z";		
	//�d�X�C�Ӿǥʹ��窺����
	for($i = 1; $i <= count($stu_list) ; $i++){
		$total_score = 0;
		if($exam_num != 0){
			for($j=6; $j< $used_count-$homework_num; $j++){
				$sql = "SELECT grade FROM take_exam WHERE exam_id ='".$all_data[0][$j]['a_id']."' AND student_id = '".$stu_list[$i-1]['a_id']."'";
				if ( !($res_j = mysql_db_query( $DB.$course_id, $sql ) ) ) {
					show_page( "not_access.tpl" ,"��ƮwŪ�����~3!!" );
					exit;
				}
				$row_j = mysql_fetch_array($res_j);		
				if ($row_j['grade'] != "" && $row_j['grade'] != "-1" && $row_j['grade'] != null) {
					$total_score += ($row_j['grade'] * $all_data[0][$j]['percentage'] /100);
					$all_data[$i][$j] = $row_j['grade'];
				}
				else {
					$all_data[$i][$j] = " ";
				}			
			}
		}
		if($homework_num != 0){
			for($j=$used_count-$homework_num; $j< $used_count; $j++){
				$sql = "SELECT grade FROM handin_homework  WHERE homework_id = '".$all_data[0][$j]['a_id']."'AND student_id='".$stu_list[$i-1]['a_id']."'";
				if ( !($res_j = mysql_db_query( $DB.$course_id, $sql ) ) ) {
					show_page( "not_access.tpl" ,"��ƮwŪ�����~3!!" );
					exit;
				}
				$row_j = mysql_fetch_array($res_j);
				if ($row_j['grade'] != "" && $row_j['grade'] != "-1" && $row_j['grade'] != null) {
					$total_score += ($row_j['grade'] * $all_data[0][$j]['percentage'] * 0.01);
					$all_data[$i][$j] = $row_j['grade'];
				}
				else {
					$all_data[$i][$j] = " ";
				}							
			}		
		}
		//�[�J�`��
		$all_data[$i][$used_count] = $total_score;
	}
			
	return $all_data;
}	

function add_stu_list_into_array($stu_list)
{
	global $DB;
	// ���[�W �C�@��col�����Y
	$all_data[0][0] = "�t�үZ��";
	$all_data[0][1] = "�Ǹ�";
	$all_data[0][2] = "�m�W";
	$all_data[0][3] = "email";
	$all_data[0][4] = "�Ŀ�wĵ�W��";
	$all_data[0][5] = "�ҷ~�ݭn�[�j��]";
	//�d�X�w�g���I�諸
	$sql = "SELECT * FROM early_warning WHERE course_id='".$_SESSION['course_id']."' and year='".$_SESSION['course_year']."' and term='".$_SESSION['course_term']."'";
	$res = mysql_db_query($DB, $sql);
	for($i=0;$i<mysql_num_rows($res);$i++){
		$tmp[$i] =  mysql_fetch_array($res);
	}
	for($i=1; $i <= count($stu_list); $i++){
		$all_data[$i][0] = $stu_list[$i-1]['job'].$stu_list[$i-1]['grade'];
		$all_data[$i][1] = $stu_list[$i-1]['id'];
		$all_data[$i][2] = $stu_list[$i-1]['name'];
		$all_data[$i][3] = $stu_list[$i-1]['email'];
		for($j=0; $j < count($stu_list); $j++){
				if($tmp[$j]['student_id'] == $stu_list[$i-1]['a_id']){
					$who = "<input name='who_".$i."' type='checkbox' checked>";
					$choose = "<SELECT NAME='reason_".$i."'>";
					$all_data[$i]['select'] = '1';
					switch($tmp[$j]['reason']){
						case '0' :$choose .= "<OPTION value='0' selected>�ݥ[�j��]<OPTION value='1'>���Z����
												<OPTION value='2'>�ʽ�<OPTION value='3'>���Z���ΥB�ʽ�
												<OPTION value='4'>�@�~���̳W�wú��<OPTION value='5'>�ʦ�<OPTION value='6'>��L";
								  $all_data[$i]['reason'] = "�ݥ[�j��]";
								  break;
						case '1' :$choose .= "<OPTION value='0' >�ݥ[�j��]<OPTION value='1' selected>���Z����
												<OPTION value='2'>�ʽ�<OPTION value='3'>���Z���ΥB�ʽ�
												<OPTION value='4'>�@�~���̳W�wú��<OPTION value='5'>�ʦ�<OPTION value='6'>��L";
								$all_data[$i]['reason'] = "���Z����";
								 break;
						case '2' :$choose .= "<OPTION value='0'>�ݥ[�j��]<OPTION value='1'>���Z����
												<OPTION value='2' selected>�ʽ�<OPTION value='3'>���Z���ΥB�ʽ�
												<OPTION value='4'>�@�~���̳W�wú��<OPTION value='5'>�ʦ�<OPTION value='6'>��L";
								 $all_data[$i]['reason'] = "�ʽ�";
								 break;
						case '3' :$choose .= "<OPTION value='0'>�ݥ[�j��]<OPTION value='1'>���Z����
												<OPTION value='2'>�ʽ�<OPTION value='3' selected>���Z���ΥB�ʽ�
												<OPTION value='4'>�@�~���̳W�wú��<OPTION value='5'>�ʦ�<OPTION value='6'>��L";
								$all_data[$i]['reason'] = "���Z���ΥB�ʽ�";
								 break;
						case '4' :$choose .= "<OPTION value='0'>�ݥ[�j��]<OPTION value='1'>���Z����
												<OPTION value='2'>�ʽ�<OPTION value='3'>���Z���ΥB�ʽ�
												<OPTION value='4' selected>�@�~���̳W�wú��<OPTION value='5'>�ʦ�<OPTION value='6'>��L";
								$all_data[$i]['reason'] = "�@�~���̳W�wú��";
								 break;										
						case '5' :$choose .= "<OPTION value='0'>�ݥ[�j��]<OPTION value='1'>���Z����
												<OPTION value='2'>�ʽ�<OPTION value='3'>���Z���ΥB�ʽ�
												<OPTION value='4'>�@�~���̳W�wú��<OPTION value='5' selected>�ʦ�<OPTION value='6'>��L";
								$all_data[$i]['reason'] = "�ʦ�";
								 break;										
						case '6' :$choose .= "<OPTION value='0'>�ݥ[�j��]<OPTION value='1'>���Z����
												<OPTION value='2'>�ʽ�<OPTION value='3'>���Z���ΥB�ʽ�
												<OPTION value='4'>�@�~���̳W�wú��<OPTION value='5'>�ʦ�<OPTION value='6' selected>��L";
								$all_data[$i]['reason'] = "��L";
								 break;																				
					}					
					$choose .="<SELECT>";break;				
				}
				else{
					$who = "<input name='who_".$i."' type='checkbox'>";
					$choose = "<SELECT NAME='reason_".$i."'>
							<OPTION value='0'>�ݥ[�j��]
							<OPTION value='1'>���Z����
							<OPTION value='2'>�ʽ�
							<OPTION value='3'>���Z���ΥB�ʽ�
							<OPTION value='4'>�@�~���̳W�wú��
							<OPTION value='5'>�ʦ�
							<OPTION value='6'>��L
							<SELECT>"	;	
					$all_data[$i]['select'] = '0';
					$all_data[$i]['reason'] = "�ݥ[�j��]";			
				}
		}				
		$all_data[$i][4] = $who;
		$all_data[$i][5] = $choose;			
	}
	return $all_data;
}


//�q�X���G
function output_page($all_data, $course_year, $course_term, $course_id)
{	
	include("class.FastTemplate.php3");
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $version, $skinnum,  $PHPSESSID, $SDB;	
	$tpl=new FastTemplate("./templates");
	if ( $version == "C" )
		$tpl->define(array(main=>"TGCaution.tpl"));
	else
		$tpl->define(array(main=>"TGCaution_E.tpl"));
		
	$tpl->define_dynamic("row","main");
	$tpl->assign( SKINNUM , $skinnum );
	//���X�ҵ{������T
	// ���X�ҵ{�N�X
	//$Qs = "select course_no from course_no where course_id='".$course_id."'";
	$Qs = "select course_no from course where a_id='".$course_id."'";
	if ($result = mysql_db_query($DB,$Qs)){
		$row = mysql_fetch_array($result);
		$course_no = explode("_",$row['course_no']);
		$cour_cd = $course_no[0];
		$grp = $course_no[1];
	}
	else{
		$error = "mysql��ƮwŪ�����~!!";
		return "$error $Qs<br>";
	}	
	$course_info['cid'] = $cour_cd;
	$course_info['gid'] = $grp;	

	//�@���X�ҵ{�W�٤Ψt�ҦW��
	$cno = $cour_cd."_".$grp;
	//$Qs = "select course.name as cname, course_group.name as gname from course, course_no, course_group where course.group_id=course_group.a_id and course.a_id=course_no.course_id and course_no.course_no = '$cno'";
	$Qs = "select course.name as cname, course_group.name as gname from course, course_group where course.group_id=course_group.a_id and course.course_no = '$cno'";
	if ($result = mysql_db_query($DB,$Qs)){
		$row = mysql_fetch_array($result);
		$course_info['cname'] = $row['cname'];
		$course_info['gname'] = $row['gname'];
	}
	else{
		$error = "mysql��ƮwŪ�����~!!";
		return "$error $Qs<br>";
	}
	// ���X�}�ҾǦ~�פξǴ�
	$course_info['year'] = $course_year;
	$course_info['term'] = $course_term;	
	
	// ���X�Юv�m�W
	$Qs = "select u.name as name from user as u, teach_course as tc where u.authorization='1' and u.a_id=tc.teacher_id";
	$Qs .= " and tc.year=".$course_info['year']." and tc.term=".$course_info['term']." and tc.course_id=".$course_id;
	if ($result = mysql_db_query($DB,$Qs)){
		$row = mysql_fetch_array($result);
		$course_info['tch'] = $row['name'];
		while( $row = mysql_fetch_array($result) )
			$course_info['tch'] .= ','.$row['name'];
	}
	else{
		$error = "mysql��ƮwŪ�����~!!";
		return "$error $Qs<br>";
	}
	// �s����Ҩt�θ�Ʈw
	//if( !($cnx = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) ){  
	//	echo( "�b sybase_connect �����~�o��");  
	//}

	//$csd = @sybase_select_db($SDB, $cnx);

	$conn_string = "host=140.123.30.12 dbname=academic user=acauser password=!!acauser13";
	$cnx = pg_pconnect($conn_string) or die('��Ʈw�S���^���A�еy��A��');		
	// ���o�Ǥ���
	$Qs = "select credit from a30vcourse_tea where course_no = '$cour_cd'";
	//$cur = sybase_query($Qs, $cnx);
	//if(!$cur) {  
	//	echo( "�b sybase_exec �����~�o��( �S�����жǦ^ ) ");  
	//}
	$cur = pg_query($cnx, $Qs) or die('��ƪ��s�b�A�гq���q�⤤��');
	
	//if(($array=sybase_fetch_array($cur))!=0){
	if(($array=pg_fetch_array($cur, null, PGSQL_ASSOC))!=0){
		$course_info["credit"] = $array['credit'];
	}else{
		$course_info["credit"] = "";
	}
	$tpl->assign( YEAR, $course_info['year']);
	$tpl->assign( TERM, $course_info['term']);
	$tpl->assign( C_ID, $course_id);
	$tpl->assign( CID, $course_info['cid'] );
	$tpl->assign( GID, $course_info['gid'] );
	$tpl->assign( CREDIT, $course_info["credit"] );
	$tpl->assign( CNAME, $course_info['cname'] );
	$tpl->assign( GNAME, $course_info['gname'] );
	$tpl->assign( TEACHER, $course_info['tch'] );
	$tpl->assign( LOOP, count($all_data)-1);		
	$tpl->assign( DATE, (date("Y")-1911).date("/m/d"));		
	//�H�W�O�C�X�}�Y���ҵ{��T
	//�s�@��X���ɮ�
	$file_name="../../$course_id/early_warning_".$course_id.".xls";
	if(file_exists($file_name))	unlink($file_name);
	$file=fopen("$file_name","w");
	//��ܪ�檺���Y
	$row_data = show_table_header($all_data,$file);
	$tpl->assign( DATA, $row_data);	
	$tpl->parse(ROW,"row");
	//��ܪ�檺���e
	$row_data = show_table_data($all_data,$file);		
	$tpl->assign( DATA, $row_data);
	fclose($file);
	//��ܤU����檺�s��
	$tpl->assign(LOCATION, "$course_id/early_warning_".$course_id.".xls");
	$tpl->assign(PRINT_,"./print_caution.php");
	$tpl->parse(ROW,".row");
	$tpl->parse(BODY,"main");
	$tpl->FastPrint("BODY");	
}

function show_table_header($all_data,$file){
	$bg_color = "#000066";
	$font_color = "#FFFFFF";
	$row_data ="<tr bgcolor='$bg_color'>";
	for($i=0; $i < 3; $i++){
		$row_data .= "<th>";
		$row_data .= "<div align='center'><font color='$font_color' size='2'>".$all_data[0][$i]."</fonr></div>";	
		$row_data .= "</th>";
		fwrite($file,$all_data[0][$i]."\t");//�g��
	}
	//�Ŀ�W�� �ά��⪺�r
	$row_data .= "<th>";
	$row_data .= "<div align='center'><font color='#FF0000' size='2'>".$all_data[0][4]."</fonr></div>";	
	$row_data .= "</th>";
	//��ܭ�] �ζ��⪺�r
	$row_data .= "<th>";
	$row_data .= "<div align='center'><font color='#FFFF66' size='2'>".$all_data[0][5]."</fonr></div>";	
	$row_data .= "</th>";
	fwrite($file, "��]\t");//�g��
	for($i=6; $i < count($all_data[0])-1; $i++){
		$row_data .= "<th>";
		$row_data .= "<div align='center'><font color='$font_color' size='2'>".$all_data[0][$i]['name']."(".$all_data[0][$i]['percentage']."%)</fonr></div>";	
		$row_data .= "</th>";
		fwrite($file, $all_data[0][$i]['name']."(".$all_data[0][$i]['percentage']."%)\t");//�g��
	}	
	$row_data .= "<th>";
	$row_data .= "<div align='center'><font color='$font_color' size='2'>�`���Z</fonr></div>";	
	$row_data .= "</th>";		
	$row_data .="</tr>";
	fwrite($file,"�`���Z\n");//�g��
	return $row_data;
}

function show_table_data($all_data,$file)
{
	global $stu_list;
	$row_data = '';
	for($i=1; $i < count($all_data);$i++){
		$tmp_data ='';
		//���M�wtr���C��
		if ( $bg_color == "#F0FFEE" )
			$bg_color = "#E6FFFC";
		else
			$bg_color = "#F0FFEE";
		$row_data .= "<tr bgcolor ='$bg_color'>";
		//����� �t�үZ�� �Ǹ� 
		$row_data .= "<td><div align='center'><font size='2'>".$all_data[$i][0]."</font></div></td>";
		$row_data .= "<td><div align='center'><font size='2'>".$all_data[$i][1]."</font></div></td>";
		$row_data .= "<input type='hidden' name='student_id_".$i."' value='".$stu_list[$i-1]['a_id']."'>";
		$tmp_data .= $all_data[$i][0]."\t".$all_data[$i][1]."\t";
		//�m�W�n�[�W�I�F�X�{email
		$row_data .= "<td><div align='center'><font size='2'><A HREF=mailto:".$all_data[$i][3].">".$all_data[$i][2]."</a></font></div></td>";
		$tmp_data .= $all_data[$i][2]."\t";
		//�b��� �Ŀ�W�� �� ��]
		$row_data .= "<td><div align='center'><font size='2'>".$all_data[$i][4]."</font></div></td>";
		$row_data .= "<td><div align='center'><font size='2'>".$all_data[$i][5]."</font></div></td>";
		$tmp_data .= $all_data[$i]['reason']."\t";
		//��ܩҦ����Z
		for($j=6; $j<count($all_data[$i])-2;$j++){
			if($all_data[$i][$j] >=60){
				$row_data .= "<td><div align='center'><font size='2'>".$all_data[$i][$j]."</font></div></td>";
			}
			else if($all_data[$i][$j] < 60 ){
				$row_data .= "<td><div align='center'><font size='2' color='red'>".$all_data[$i][$j]."</font></div></td>";
			}
			$tmp_data .= $all_data[$i][$j]."\t";
		}
		$tmp_data .= "\n";
		if($all_data[$i]['select'] == '1'){fwrite($file, $tmp_data);}
	}
	return $row_data; 
}	
?>
