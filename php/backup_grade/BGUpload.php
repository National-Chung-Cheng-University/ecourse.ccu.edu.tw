<?php
require 'fadmin.php';
global $count, $action;
$buDB = "bugrade";
//if(isset($PHPSESSID) && session_check_teach($PHPSESSID) == 2 ){
if(isset($PHPSESSID) && (session_check_teach($PHPSESSID) ==0 || session_check_teach($PHPSESSID) == 2) ){

	
	// �W�Ǧ��Z����ܦ��Z�n�O��
	
	// �s��mysql
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$error = "��Ʈw�s�����~!!";
		return $error;
	}
	
	//�ˬd�����A�p�G���O�Ѯv�N�����L��J���Z
	$Q0 = "select authorization from user where id='$user_id'";
	$result0 = mysql_db_query($DB, $Q0);
	$row0 = mysql_fetch_array($result0);
	if($row0['authorization'] != 1)
	{
		if( $version=="C" ) {
			show_page( "not_access.tpl" ,"�A�S���v���ϥΦ��\��A���ϥ����Юv");
			exit;
		}
		else {
			show_page( "not_access.tpl" ,"You have No Permission!!Please Contact With Your Teacher!!");
			exit;
		}
	}
	
	$Qs = "select start_date, end_date from extra";
	if ($result = mysql_db_query($DB,$Qs)){
		$row = mysql_fetch_array($result);
		$st = explode('-', $row['start_date']);
		$ed = explode('-', $row['end_date']);
		$start_date = $st[0].$st[1].$st[2];
		$end_date = $ed[0].$ed[1].$ed[2];
		$now_date = (date("Y")-1911).date("md");
	}
	else{
		$error = "mysql��ƮwŪ�����~!!";
		return "$error $Qs<br>";
	}
	
	if($now_date<$start_date || $now_date>$end_date){
		show_page( "not_access.tpl" ,"���Z�W�Ǥ����$start_date ��$end_date ,�Цb�W�w�ɶ����W��!");
		exit;
	}
	else{
		if ($_POST["action"]=="upload"){
			$action = "upload";
			if(upload_score()==-1){
				echo "<script language=\"JavaScript\" type=\"text/JavaScript\">alert(\"���Z�v�W��$count ���A���˵��ΦC�L���Z�n�O��C\");</script>";
				show_page_d(1);
			}
			else{
				echo "<script language=\"JavaScript\" type=\"text/JavaScript\">alert(\"���Z�W�ǥ��ѡI\");</script>";
			}
		}
		else if($_POST["action"]=="preview"){
			$action = "preview";
			update_score();
			show_page_d(1);
		}
		else if($page!=NULL){
			//show_page_d($page);
			show_page_d(1);
		}
	}
}
else{
	if( $version=="C" ) {
		show_page( "not_access.tpl" ,"�A�S���v���ϥΦ��\��");
		exit;
	}
	else {
		show_page( "not_access.tpl" ,"You have No Permission!!");
		exit;
	}
}



function update_score(){
	global $course_id, $report, $count, $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_info, $buDB;

	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"��Ʈw�s�����~!!" );
		exit;
	}
	$Qs = "delete from take_exam where exam_id = '-1' WHERE course_id='$course_id' AND year='$course_info[year]' AND term='$course_info[term]'";
	if (!($result = mysql_db_query($buDB,$Qs))){
		$error = "mysql��ƮwŪ�����~!!";
		return "$error $Qs<br>";
	}
	// �W�Ǧ��Z
	$count = 0;
	for($i=0;$i<sizeof($report);$i++){
		if($_POST["grade".$report[$i]['std_no']]!="" && $_POST["state".$report[$i]['std_no']]==1){
			$std_no = $report[$i]['std_no'];
			$final = $_POST["grade".$report[$i]['std_no']];
			$Q1 = "select a_id from user where id = '$std_no'";
			if (($result1 = mysql_db_query($DB,$Q1))){
				$row1 = mysql_fetch_array($result1);
			}
			else{
				$error = "mysql��ƮwŪ�����~!!";
				return "$error $Q1<br>";
			}
			
			$Q2 = "insert into take_exam (exam_id, student_id, course_id, year, term, grade) values ( '-1', '$row1[a_id]', '$course_id', '$course_info[year]', '$course_info[term]', '$final')";
			if (!($result2 = mysql_db_query($buDB,$Q2))){
				$error = "mysql��ƮwŪ�����~!!";
				return "$error $Q2<br>";
			}
			$count++;
		}
	}
}

//�W�Ǧ��Z�ܱаȨt��
function upload_score(){
	global $SDB, $course_id, $report, $course_info, $count, $DB, $buDB;
	$ip = getenv("REMOTE_ADDR");	//���o�W�uip
	if ( $ip == "" )
		$ip = $HTTP_X_FORWARDED_FOR;
	if ( $ip == "" )
		$ip = $REMOTE_ADDR;

	//���oSDB����
	$Q4 = "SELECT course_no from course where a_id = $course_id";
	if ( !($result4 = mysql_db_query( $DB, $Q4  )) ) {
		$error = "��ƮwŪ�����~!!44444";
		show_page ( "not_access.tpl", $error );		
	}else{
		$row = mysql_fetch_array ($result4);
		$c_id = $row['course_no'];
		if ( substr( $c_id, 3, 1 ) == "A" || substr( $c_id, 3, 1 ) == "B" || substr( $c_id, 3, 1 ) == "C" || substr( $c_id, 3, 1 ) == "D" )
			$SDB = "academic_gra";
		else
			$SDB = "academic";
	}
	
	// �s����Ҩt�θ�Ʈw
	if( !($cnx = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) ){  
		Error_handler( "�b sybase_connect �����~�o��" , $cnx );  
	}
	$csd = @sybase_select_db($SDB, $cnx);
		
	// �W�Ǧ��Z
	$count = 0;
	for($i=0;$i<sizeof($report);$i++){
		if($_POST["grade".$report[$i]['std_no']]!="" && $_POST["state".$report[$i]['std_no']]==1){
			$std_no = $report[$i]['std_no'];
			$grade = $_POST["grade".$report[$i]['std_no']];
			$date = date("Y/m/d H:i:s");
			$Qins = "insert into a32t_sel_score_t (year, term, cour_cd, grp, std_no, trmgrd, ipaddr, sysdate) values ('$course_info[year]', '$course_info[term]', '$course_info[cid]', '$course_info[gid]', '$std_no', $grade, '$ip', '$date')";
			$cur = sybase_query($Qins, $cnx);
			if(!$cur) {  
				Error_handler( "�b sybase_exec �����~�o��( �S�����жǦ^ ) " , $cnx );  
			}
			$count++;
		}
	}
	sybase_close( $cnx);
	return -1;
}

function show_page_d($page){
	global $PHPSESSID, $course_info, $SDB, $action, $course_id, $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $buDB;
	include("class.FastTemplate.php3");
	$tpl=new FastTemplate("./templates");
	if($action == "upload"){
		if($SDB == "academic")
			$tpl->define(array(main=>"BGUpload.tpl"));
		else
			$tpl->define(array(main=>"BGUpload_gra.tpl"));
	}
	else if ($action == "preview"){
		if($SDB == "academic")
			$tpl->define(array(main=>"BGPreview.tpl"));
		else
			$tpl->define(array(main=>"BGPreview_gra.tpl"));
	}
	/******bluejam****/
	//$tpl->define_dynamic("grade", "main");
	$tpl->define_dynamic("comment", "main");
	/********bluejam**********/
	$tpl->assign(YEAR, $course_info['year']);
	$tpl->assign(TERM, $course_info['term']);
	$tpl->assign(CID, $course_info['cid']);
	$tpl->assign(GID, $course_info['gid']);
	$tpl->assign(CREDIT, $course_info["credit"] );
	$tpl->assign(GNAME, $course_info['gname']);
	$tpl->assign(CNAME, $course_info['cname']);
	$tpl->assign(TEACHER, $course_info['tch']);
	$tpl->assign(DATE, (date("Y")-1911).date("/m/d"));
	$tpl->assign(SDLINE, $course_info['s_deadline']);
	$tpl->assign(GDLINE, $course_info['g_deadline']);
	
	
	//�M�wSDB����
	$Q4 = "SELECT course_no from course where a_id = $course_id";
	if ( !($result4 = mysql_db_query( $DB, $Q4  )) ) {
		$error = "��ƮwŪ�����~!!44444";
		show_page ( "not_access.tpl", $error );		
	}else{
		$row = mysql_fetch_array ($result4);
		$c_id = $row['course_no'];
		if ( substr( $c_id, 3, 1 ) == "A" || substr( $c_id, 3, 1 ) == "B" || substr( $c_id, 3, 1 ) == "C" || substr( $c_id, 3, 1 ) == "D" )
			$SDB = "academic_gra";
		else
			$SDB = "academic";
	}
	//echo $SDB." -- ".$course_id." -- ".$action."<br>";
	
	// �s����Ҩt�θ�Ʈw
	if( !($cnx = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) ){  
		Error_handler( "�b sybase_connect �����~�o��" , $cnx );  
	}
	$csd = @sybase_select_db($SDB, $cnx);
	
	// �s���оǨt�θ�Ʈw
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"��Ʈw�s�����~!!" );
		exit;
	}
	
	// �d�߿�Ҹ��
	$cour_cd = $course_info['cid'];
	$grp = $course_info['gid'];
	// ���X�Ҧ���Ҿǥ;Ǹ��Ωm�W
	$Qs = "select cs.std_no, vstd.name, vstd.status from a31v_sel_class_tea cs, a11vstd_rec_tea vstd where cs.cour_cd = '$cour_cd' and cs.grp = '$grp' and cs.std_no=vstd.id and cs.year = '$course_info[year]' and cs.term = '$course_info[term]' ".
	"union select cs.std_no, leave.name, leave.status from a31v_sel_class_tea cs, a11vleave_rec_tea leave where cs.cour_cd = '$cour_cd' and cs.grp = '$grp' and cs.std_no=leave.id and cs.year = '$course_info[year]' and cs.term = '$course_info[term]' ".
	"union select cs.std_no, vstd.name, vstd.status from a31vhis_sel_class_tea cs, a11vstd_rec_tea vstd where cs.cour_cd = '$cour_cd' and cs.grp = '$grp' and cs.std_no=vstd.id and cs.year = '$course_info[year]' and cs.term = '$course_info[term]' ".
	"union select cs.std_no, leave.name, leave.status from a31vhis_sel_class_tea cs, a11vleave_rec_tea leave where cs.cour_cd = '$cour_cd' and cs.grp = '$grp' and cs.std_no=leave.id and cs.year = '$course_info[year]' and cs.term = '$course_info[term]' order by std_no";
	$cur = sybase_query($Qs, $cnx);
	if(!$cur) {  
		Error_handler( "�b sybase_exec �����~�o��( �S�����жǦ^ ) " , $cnx );  
	}
	for($i=0;$i<sybase_num_rows($cur);$i++){
		$row = sybase_fetch_array($cur);
		$data[$i]['std_no'] = $row['std_no'];
		$data[$i]['name'] = $row['name'];
		//$data[$i]['status'] = $row['status'];
		$Q1 = "select a_id from user where id = '$row[std_no]'";
		if (($result1 = mysql_db_query($DB, $Q1))){
			$row1 = mysql_fetch_array($result1);
		}
		else{
			$error = "mysql��ƮwŪ�����~!!";
			return "$error $Q1<br>";
		}
		// �d�߱оǨt�Τ��O�_���ӥͦ��Z���
		$Q1 = "select grade from take_exam where exam_id='-1' and student_id='$row1[a_id]' AND course_id='$course_id' AND year='$course_info[year]' AND term='$course_info[term]'";
		if (($result1 = mysql_db_query($buDB, $Q1))){
			$row_grade = mysql_fetch_array($result1);
		}
		else{
			$error = "mysql��ƮwŪ�����~!!";
			return "$error $Q1<br>";
		}

		// �d�ߦ��Z��Ʈw���O�_���ӥͦ��Z,�Y�L�h��"I"���
		$Qs2 = "select trmgrd from a32t_sel_score_t where std_no = '$row[std_no]' and cour_cd = '$cour_cd' and grp = '$grp' and year = '$course_info[year]' and term = '$course_info[term]'";
		$cur2 = sybase_query($Qs2, $cnx);
		if(!$cur2) {
			Error_handler( "�b sybase_exec �����~�o��( �S�����жǦ^ ) " , $cnx );  
		}
		// �d�߱����
		$Qs3 = "select std_no from a32v_sel_score_withdraw where std_no = '$row[std_no]' and cour_cd = '$course_info[cid]' and grp = '$course_info[gid]' and year = '$course_info[year]' and term = '$course_info[term]'";
		$cur3 = sybase_query($Qs3, $cnx);
		if(!$cur3) {  
			Error_handler( "�b sybase_exec �����~�o��( �S�����жǦ^ ) " , $cnx );  
		}
/*********************************************************************************************************************************/
/*******devon 2006-02-09************/
/*******�P�_��ǡB�h�Ǫ����A********/
/*************************************************
		a11tstd_his_rec:�O�ثe�b�Ǫ����
        a11vleave_rec_tea:�O�h�Ǫ����
*************************************************/
		$Qs5 = "select * from a11tstd_his_rec where std_no = '$row[std_no]' and year = '$course_info[year]' and term = '$course_info[term]'";
		$Qs6 = "select status from a11vleave_rec_tea where id = '$row[std_no]'";
		$cur5 = sybase_query($Qs5, $cnx);
		$cur6 = sybase_query($Qs6, $cnx);
		$nums = sybase_num_rows($cur5);
		$row6 = sybase_fetch_array($cur6);
		if($nums == 0)
		{
			if($row6[status]=="�h��")
				$data[$i]['status'] = $row6[status];
			else
				$data[$i]['status'] = "���";
		}
/*********************************************************************************************************************************/

		if($data[$i]['status']=="���" || $data[$i]['status']=="�h��"){
			$data[$i]['score'] = $data[$i]['status'];
		}
		else if(sybase_fetch_array($cur3)!=0){
			$data[$i]['score'] = "���";	//�Y������ƫh���צ��L���Z���Ь����
		}
		else if(($row = sybase_fetch_array($cur2))!=0){
			$data[$i]['score'] = $row['trmgrd'];
		}
		else if($row_grade!=0 && $action=="preview"){
			$data[$i]['score'] = $row_grade['grade'];
		}
		else{
			$data[$i]['score'] = "<font color=\"#999999\">I</font>";	//�Y�L���Z��ƫh�Ь�I
		}
		$std_no = $data[$i]['std_no'];
		// �b�ǥ;��Ǹ�ƥHa11tstd_his_rec����,��ǥͥHa11vstd_rec_tea����,���X�ǥͤ��t�үZ�ŵ����y���
		if($data[$i]['status']=="���" || $data[$i]['status']=="�h��")
			$Qs3 = "select deptcd now_dept, grade now_grade, class now_class, abbrev from a11vstd_rec_tea vstd, h0rtunit_a_ unit where vstd.id='$std_no' and vstd.deptcd=unit.cd";
		else
			$Qs3 = "select now_dept, now_grade, now_class, abbrev from a11tstd_his_rec tstd, h0rtunit_a_ unit where tstd.std_no='$std_no' and tstd.now_dept=unit.cd and tstd.year='$course_info[year]' and tstd.term='$course_info[term]'";
		$cur3 = sybase_query($Qs3, $cnx);
		if(!$cur3) {
			Error_handler( "�b sybase_exec �����~�o��( �S�����жǦ^ ) " , $cnx );  
		}
		if(($row3 = sybase_fetch_array($cur3))!=0){
			$data[$i]['grp'] = $row3['abbrev'].$row3['now_grade'].$row3['now_class'];
		}
		else{
			$data[$i]['grp'] = "<font size=\"4\">&nbsp;</font>";
		}
		$data[$i]['index'] = $row3['now_dept'].$row3['now_grade'].$row3['now_class'].$data[$i]['std_no'];
	}


	
	$tpl->assign(STD, sizeof($data));
	$total = ceil(sizeof($data)/48);
	/******bluejam****/
	
	if($total==0)
	{
		$total=1;
	}
	$tpl->assign(TOTAL, $total);
	
	for($p=1;$p<=$total;$p++){
		$page = $p;
		$tpl->define_dynamic("grade", "main");
	
	/****bluejam***/
		//$data = qsort_multiarray ( $data, 'index', SORT_ASC );
		for($i=0;$i<16;$i++){
			$tpl->assign(DATA, "<tr>");
			//$tpl->parse(GRADE,".grade");
			/*************bluejam*********/
			if($i==0){
				$tpl->parse(GRADE,"grade");
			}
			else{
				$tpl->parse(GRADE,".grade");
			}
			/*********bluejam**********/			
			for($j=0;$j<3;$j++){
				$k = ($page-1)*48+$j*16+$i;
				// ����48*page���������ΪŮ�ɺ�
				if($data[$k]==null){
					$data[$k]['grp'] = "<font size=\"4\">&nbsp;</font>";
					$data[$k]['std_no'] = "<font size=\"4\">&nbsp;</font>";
					$data[$k]['name'] = "<font size=\"4\">&nbsp;</font>";
					$data[$k]['score'] = "<font size=\"4\">&nbsp;</font>";
				}
				$temp = "<td>".$data[$k]['grp']."</td><td>".$data[$k]['std_no']."</td><td>".$data[$k]['name']."</td><td align=\"center\">".$data[$k]['score']."</td>";
				$tpl->assign(DATA, $temp);
				$tpl->parse(GRADE,".grade");
			}
			
			$tpl->assign(DATA, "</tr>");
			$tpl->parse(GRADE,".grade");
		}

		/**************bluejam***********/
		$tpl->assign(PLIST, $page);
		if($p != $total){
			$tpl->assign(DIVIDEPAPER, "<P style='page-break-after:always'></P>");
		}
		else{
			$tpl->assign(DIVIDEPAPER, "");
		}
		$tpl->parse(COMMENT,".comment");
	}

	/***********bluejam***********
	for($i=1;$i<=$total;$i++){
		if($page == $i){
			$s = "selected";
			$tpl->assign(PAGE, $page);
		}
		else{
			$s = "";
		}
		$plist = $plist."<option value=\"TGUpload.php?PHPSESSID=$PHPSESSID&action=$action&page=$i\" $s>$i</option>";
	}
	$tpl->assign(PLIST, $plist);
	
	**************bluejam************/
	$tpl->parse(BODY,"main");
	$tpl->FastPrint("BODY");
	sybase_close( $cnx);
}

function Error_Handler( $msg, $cnx ) {  
	echo "$msg \n";
	sybase_close( $cnx); exit();  
}
?>
