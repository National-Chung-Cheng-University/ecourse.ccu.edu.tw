<?php
require 'fadmin.php';
//update_status ("�ץX�ǥ͹wĵ�t��");
	if ( isset($PHPSESSID) && session_check_admin($PHPSESSID) ) {
	}
	else{
		show_page( "index_ad.tpl", "�A���v�����~�A�Э��s�n�J!!");
		exit;
	}	
//
	include("class.FastTemplate.php3");		
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $version, $skinnum,  $PHPSESSID, $SDB;	
	$tpl=new FastTemplate("./templates");
	if ( $version == "C" ){
		$tpl->define(array(main=>"export_earlywarning.tpl"));
	}else{
		$tpl->define(array(main=>"TGCaution_E.tpl"));
	}
	// new template
	$tpl->define_dynamic("row","main");
	//��ܨt�ҿﶵ��bar
	$sql = "SELECT g.name, g.deptcd FROM course_group g WHERE g.level >= 2  ORDER BY g.deptcd ASC";
	if ($result = mysql_db_query($DB, $sql)){
		while($row = mysql_fetch_array($result)){
			$group .= "<option value='".$row[deptcd]."'>".$row[name]."</option>";
		}
	}
	$tpl->assign("GROUP",$group);
	//��ܹL
	//2009.11.10�оǲխn�D�s�W�wĵ������(�Y�Юv�ק�wĵ�ǥͷ��)
	//��37�B43�B49�B54�B59�B70�B74�B92�B94�B97��W�[�@��e.mdate���
	if($_GET[action] == "select"){
		switch($_POST[type]){
			case "1": //�Ǵ��Ǧ~
				$output_type = "[".$_POST[year]."�Ǵ�".$_POST[term]."�Ǧ~]";
				$file_name="../../early_warning_".$_POST[year]."_".$_POST[term].".xls";
				$sql = "SELECT e.year, e.term, c.course_no, c.name  as course_name, g.deptcd, u.grade, u.job, u.id, u.name, e.reason, e.mdate FROM early_warning e, user u, course c, course_group g  WHERE e.course_id=c.a_id and e.student_id=u.a_id and c.group_id=g.a_id and e.year='".$_POST[year]."' and e.term='".$_POST[term]."' ORDER BY e.course_id ASC";	
				
				break;
			case "2": //��ئW�� / ��إN�X
				$output_type = "[".$_POST[course_name]."(".$_POST[course_no].")]";
				$file_name="../../early_warning_course_no_".$_POST[course_no].".xls";
				$sql = "SELECT e.year, e.term, c.course_no, c.name  as course_name, g.deptcd, u.grade, u.job, u.id, u.name, e.reason, e.mdate FROM early_warning e, user u, course c, course_group g  WHERE e.course_id=c.a_id and e.student_id=u.a_id and c.group_id=g.a_id and c.name='".$_POST[course_name]."' and c.course_no='".$_POST[course_no]."'  ORDER BY e.course_id ASC";	

				break;
			case "3": //�t�� (�t�ҥN�X)
				$output_type = "[". getGroupNameByGroupCd($DB, $_POST[group]) ."(".$_POST[group].")]";
				$file_name="../../early_warning_deptcd_".$_POST[group].".xls";
				$sql = "SELECT e.year, e.term, c.course_no, c.name  as course_name, g.deptcd, u.grade, u.job, u.id, u.name, e.reason, e.mdate FROM early_warning e, user u, course c, course_group g  WHERE e.course_id=c.a_id and e.student_id=u.a_id and c.group_id=g.a_id and g.deptcd='".$_POST[group]."' ORDER BY e.course_id ASC";					
				break;
			case "4": //��]
				$output_type = "[".getReasonByReasonCd($_POST[reason])."]";
				$file_name="../../early_warning_reason_".$_POST[reason].".xls";
				$sql = "SELECT e.year, e.term, c.course_no, c.name  as course_name, g.deptcd, u.grade, u.job, u.id, u.name, e.reason, e.mdate FROM early_warning e, user u, course c, course_group g  WHERE e.course_id=c.a_id and e.student_id=u.a_id and c.group_id=g.a_id and e.reason='".$_POST[reason]."' ORDER BY e.course_id ASC";	
				break;
			case "5": //����
				$output_type = "[�����ץX]";
				$file_name="../../early_warning_all.xls";
				$sql = "SELECT e.year, e.term, c.course_no, c.name  as course_name, g.deptcd, u.grade, u.job, u.id, u.name, e.reason, e.mdate FROM early_warning e, user u, course c, course_group g  WHERE e.course_id=c.a_id and e.student_id=u.a_id and c.group_id=g.a_id ORDER BY e.course_id ASC";	
				break;
			default;
				exit;																	
		}
		
		//�}��
		if(file_exists($file_name))	unlink($file_name);	
		$file = fopen("$file_name","w");		
		//�g�J�ɮ�
		//excel�����Y
		fwrite($file, "�Ǧ~\t�Ǵ�\t��إN�X\t��ئW��\t�Z�O\t�t�ҥN�X\t�t��\t�Ǹ�\t�m�W\t��]\t�wĵ���\n");//�g��
		//excel�����e
		if ($result = mysql_db_query($DB, $sql)){
			while($row = mysql_fetch_array($result)){
				fwrite($file, "$row[year]\t$row[term]\t$row[course_no]\t$row[course_name]\t$row[grade]\t$row[deptcd]\t$row[job]\t$row[id]\t$row[name]\t". getReasonByReasonCd($row[reason]) . "\t$row[mdate]\n");//�g��
			}
		}else{
			echo "��ƮwŪ�����~!!";
			exit;
		}
		//����
		fclose($file);					
	}
	//�w�]
	else{
		$output_type = "[�����ץX]";
		//�}��
		$file_name="../../early_warning_all.xls";
		if(file_exists($file_name))	unlink($file_name);	
		$file = fopen("$file_name","w");
		//�g�J�ɮ�
		//excel�����Y
		fwrite($file, "�Ǧ~\t�Ǵ�\t��إN�X\t��ئW��\t�Z�O\t�t�ҥN�X\t�t��\t�Ǹ�\t�m�W\t��]\t�wĵ���\n");//�g��
		//excel�����e
		$sql = "SELECT e.year, e.term, c.course_no, c.name  as course_name, g.deptcd, u.grade, u.job, u.id, u.name, e.reason, e.mdate FROM early_warning e, user u, course c, course_group g  WHERE e.course_id=c.a_id and e.student_id=u.a_id and c.group_id=g.a_id ORDER BY e.course_id ASC";	
		if ($result = mysql_db_query($DB, $sql)){
			while($row = mysql_fetch_array($result)){
				fwrite($file, "$row[year]\t$row[term]\t$row[course_no]\t$row[course_name]\t$row[grade]\t$row[deptcd]\t$row[job]\t$row[id]\t$row[name]\t". getReasonByReasonCd($row[reason]) . "\t$row[mdate]\n");//�g��
			}
		}else{
			echo "��ƮwŪ�����~!!";
			exit;
		}
		//����
		fclose($file);		
	}
	
	//assign�ɮ׸��|
	$tpl->assign("OUTPUT_TYPE",$output_type);
	$tpl->assign("FILE_PATH",$file_name);
	
	//output page
	$tpl->parse(ROW,".row");	
	$tpl->parse(BODY,"main");	
	$tpl->FastPrint("BODY");
//-----function area------
function getReasonByReasonCd($reason_cd){
/*
	<OPTION value='0'>�ݥ[�j��]
	<OPTION value='1'>���Z����
	<OPTION value='2'>�ʽ�
	<OPTION value='3'>���Z���ΥB�ʽ�
*/
	$tmp = array('0'=>'�ݥ[�j��]','1'=>'���Z����','2'=>'�ʽ�','3'=>'���Z���ΥB�ʽ�');
	return $tmp[$reason_cd];
}	
function getGroupNameByGroupCd( $DB, $group_cd ){
	$sql = "SELECT name FROM course_group WHERE deptcd='$group_cd'";
	if ($result = mysql_db_query($DB, $sql)){
		$row = mysql_fetch_array($result);
	}
	return $row[name];	
}

?>