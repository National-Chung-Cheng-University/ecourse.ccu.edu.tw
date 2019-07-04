<?php
require 'fadmin.php';
global $count, $action;
$buDB = "bugrade";
//if(isset($PHPSESSID) && session_check_teach($PHPSESSID) == 2 ){
if(isset($PHPSESSID) && (session_check_teach($PHPSESSID) ==0 || session_check_teach($PHPSESSID) == 2) ){

	
	// 上傳成績及顯示成績登記表
	
	// 連結mysql
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$error = "資料庫連結錯誤!!";
		return $error;
	}
	
	//檢查身份，如果不是老師就不讓他輸入成績
	$Q0 = "select authorization from user where id='$user_id'";
	$result0 = mysql_db_query($DB, $Q0);
	$row0 = mysql_fetch_array($result0);
	if($row0['authorization'] != 1)
	{
		if( $version=="C" ) {
			show_page( "not_access.tpl" ,"你沒有權限使用此功能，請冾正式教師");
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
		$error = "mysql資料庫讀取錯誤!!";
		return "$error $Qs<br>";
	}
	
	if($now_date<$start_date || $now_date>$end_date){
		show_page( "not_access.tpl" ,"成績上傳日期為$start_date 至$end_date ,請在規定時間內上傳!");
		exit;
	}
	else{
		if ($_POST["action"]=="upload"){
			$action = "upload";
			if(upload_score()==-1){
				echo "<script language=\"JavaScript\" type=\"text/JavaScript\">alert(\"成績己上傳$count 筆，請檢視及列印成績登記表。\");</script>";
				show_page_d(1);
			}
			else{
				echo "<script language=\"JavaScript\" type=\"text/JavaScript\">alert(\"成績上傳失敗！\");</script>";
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
		show_page( "not_access.tpl" ,"你沒有權限使用此功能");
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
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
		exit;
	}
	$Qs = "delete from take_exam where exam_id = '-1' WHERE course_id='$course_id' AND year='$course_info[year]' AND term='$course_info[term]'";
	if (!($result = mysql_db_query($buDB,$Qs))){
		$error = "mysql資料庫讀取錯誤!!";
		return "$error $Qs<br>";
	}
	// 上傳成績
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
				$error = "mysql資料庫讀取錯誤!!";
				return "$error $Q1<br>";
			}
			
			$Q2 = "insert into take_exam (exam_id, student_id, course_id, year, term, grade) values ( '-1', '$row1[a_id]', '$course_id', '$course_info[year]', '$course_info[term]', '$final')";
			if (!($result2 = mysql_db_query($buDB,$Q2))){
				$error = "mysql資料庫讀取錯誤!!";
				return "$error $Q2<br>";
			}
			$count++;
		}
	}
}

//上傳成績至教務系統
function upload_score(){
	global $SDB, $course_id, $report, $course_info, $count, $DB, $buDB;
	$ip = getenv("REMOTE_ADDR");	//取得上線ip
	if ( $ip == "" )
		$ip = $HTTP_X_FORWARDED_FOR;
	if ( $ip == "" )
		$ip = $REMOTE_ADDR;

	//取得SDB的值
	$Q4 = "SELECT course_no from course where a_id = $course_id";
	if ( !($result4 = mysql_db_query( $DB, $Q4  )) ) {
		$error = "資料庫讀取錯誤!!44444";
		show_page ( "not_access.tpl", $error );		
	}else{
		$row = mysql_fetch_array ($result4);
		$c_id = $row['course_no'];
		if ( substr( $c_id, 3, 1 ) == "A" || substr( $c_id, 3, 1 ) == "B" || substr( $c_id, 3, 1 ) == "C" || substr( $c_id, 3, 1 ) == "D" )
			$SDB = "academic_gra";
		else
			$SDB = "academic";
	}
	
	// 連結選課系統資料庫
	if( !($cnx = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) ){  
		Error_handler( "在 sybase_connect 有錯誤發生" , $cnx );  
	}
	$csd = @sybase_select_db($SDB, $cnx);
		
	// 上傳成績
	$count = 0;
	for($i=0;$i<sizeof($report);$i++){
		if($_POST["grade".$report[$i]['std_no']]!="" && $_POST["state".$report[$i]['std_no']]==1){
			$std_no = $report[$i]['std_no'];
			$grade = $_POST["grade".$report[$i]['std_no']];
			$date = date("Y/m/d H:i:s");
			$Qins = "insert into a32t_sel_score_t (year, term, cour_cd, grp, std_no, trmgrd, ipaddr, sysdate) values ('$course_info[year]', '$course_info[term]', '$course_info[cid]', '$course_info[gid]', '$std_no', $grade, '$ip', '$date')";
			$cur = sybase_query($Qins, $cnx);
			if(!$cur) {  
				Error_handler( "在 sybase_exec 有錯誤發生( 沒有指標傳回 ) " , $cnx );  
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
	
	
	//決定SDB的值
	$Q4 = "SELECT course_no from course where a_id = $course_id";
	if ( !($result4 = mysql_db_query( $DB, $Q4  )) ) {
		$error = "資料庫讀取錯誤!!44444";
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
	
	// 連結選課系統資料庫
	if( !($cnx = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) ){  
		Error_handler( "在 sybase_connect 有錯誤發生" , $cnx );  
	}
	$csd = @sybase_select_db($SDB, $cnx);
	
	// 連結教學系統資料庫
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
		exit;
	}
	
	// 查詢選課資料
	$cour_cd = $course_info['cid'];
	$grp = $course_info['gid'];
	// 取出所有選課學生學號及姓名
	$Qs = "select cs.std_no, vstd.name, vstd.status from a31v_sel_class_tea cs, a11vstd_rec_tea vstd where cs.cour_cd = '$cour_cd' and cs.grp = '$grp' and cs.std_no=vstd.id and cs.year = '$course_info[year]' and cs.term = '$course_info[term]' ".
	"union select cs.std_no, leave.name, leave.status from a31v_sel_class_tea cs, a11vleave_rec_tea leave where cs.cour_cd = '$cour_cd' and cs.grp = '$grp' and cs.std_no=leave.id and cs.year = '$course_info[year]' and cs.term = '$course_info[term]' ".
	"union select cs.std_no, vstd.name, vstd.status from a31vhis_sel_class_tea cs, a11vstd_rec_tea vstd where cs.cour_cd = '$cour_cd' and cs.grp = '$grp' and cs.std_no=vstd.id and cs.year = '$course_info[year]' and cs.term = '$course_info[term]' ".
	"union select cs.std_no, leave.name, leave.status from a31vhis_sel_class_tea cs, a11vleave_rec_tea leave where cs.cour_cd = '$cour_cd' and cs.grp = '$grp' and cs.std_no=leave.id and cs.year = '$course_info[year]' and cs.term = '$course_info[term]' order by std_no";
	$cur = sybase_query($Qs, $cnx);
	if(!$cur) {  
		Error_handler( "在 sybase_exec 有錯誤發生( 沒有指標傳回 ) " , $cnx );  
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
			$error = "mysql資料庫讀取錯誤!!";
			return "$error $Q1<br>";
		}
		// 查詢教學系統中是否有該生成績資料
		$Q1 = "select grade from take_exam where exam_id='-1' and student_id='$row1[a_id]' AND course_id='$course_id' AND year='$course_info[year]' AND term='$course_info[term]'";
		if (($result1 = mysql_db_query($buDB, $Q1))){
			$row_grade = mysql_fetch_array($result1);
		}
		else{
			$error = "mysql資料庫讀取錯誤!!";
			return "$error $Q1<br>";
		}

		// 查詢成績資料庫中是否有該生成績,若無則用"I"表示
		$Qs2 = "select trmgrd from a32t_sel_score_t where std_no = '$row[std_no]' and cour_cd = '$cour_cd' and grp = '$grp' and year = '$course_info[year]' and term = '$course_info[term]'";
		$cur2 = sybase_query($Qs2, $cnx);
		if(!$cur2) {
			Error_handler( "在 sybase_exec 有錯誤發生( 沒有指標傳回 ) " , $cnx );  
		}
		// 查詢棄選資料
		$Qs3 = "select std_no from a32v_sel_score_withdraw where std_no = '$row[std_no]' and cour_cd = '$course_info[cid]' and grp = '$course_info[gid]' and year = '$course_info[year]' and term = '$course_info[term]'";
		$cur3 = sybase_query($Qs3, $cnx);
		if(!$cur3) {  
			Error_handler( "在 sybase_exec 有錯誤發生( 沒有指標傳回 ) " , $cnx );  
		}
/*********************************************************************************************************************************/
/*******devon 2006-02-09************/
/*******判斷休學、退學的狀態********/
/*************************************************
		a11tstd_his_rec:是目前在學的資料
        a11vleave_rec_tea:是退學的資料
*************************************************/
		$Qs5 = "select * from a11tstd_his_rec where std_no = '$row[std_no]' and year = '$course_info[year]' and term = '$course_info[term]'";
		$Qs6 = "select status from a11vleave_rec_tea where id = '$row[std_no]'";
		$cur5 = sybase_query($Qs5, $cnx);
		$cur6 = sybase_query($Qs6, $cnx);
		$nums = sybase_num_rows($cur5);
		$row6 = sybase_fetch_array($cur6);
		if($nums == 0)
		{
			if($row6[status]=="退學")
				$data[$i]['status'] = $row6[status];
			else
				$data[$i]['status'] = "休學";
		}
/*********************************************************************************************************************************/

		if($data[$i]['status']=="休學" || $data[$i]['status']=="退學"){
			$data[$i]['score'] = $data[$i]['status'];
		}
		else if(sybase_fetch_array($cur3)!=0){
			$data[$i]['score'] = "棄選";	//若有棄選資料則不論有無成績都標為棄選
		}
		else if(($row = sybase_fetch_array($cur2))!=0){
			$data[$i]['score'] = $row['trmgrd'];
		}
		else if($row_grade!=0 && $action=="preview"){
			$data[$i]['score'] = $row_grade['grade'];
		}
		else{
			$data[$i]['score'] = "<font color=\"#999999\">I</font>";	//若無成績資料則標為I
		}
		$std_no = $data[$i]['std_no'];
		// 在學生學藉資料以a11tstd_his_rec為準,休學生以a11vstd_rec_tea為準,取出學生之系所班級等學籍資料
		if($data[$i]['status']=="休學" || $data[$i]['status']=="退學")
			$Qs3 = "select deptcd now_dept, grade now_grade, class now_class, abbrev from a11vstd_rec_tea vstd, h0rtunit_a_ unit where vstd.id='$std_no' and vstd.deptcd=unit.cd";
		else
			$Qs3 = "select now_dept, now_grade, now_class, abbrev from a11tstd_his_rec tstd, h0rtunit_a_ unit where tstd.std_no='$std_no' and tstd.now_dept=unit.cd and tstd.year='$course_info[year]' and tstd.term='$course_info[term]'";
		$cur3 = sybase_query($Qs3, $cnx);
		if(!$cur3) {
			Error_handler( "在 sybase_exec 有錯誤發生( 沒有指標傳回 ) " , $cnx );  
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
				// 不滿48*page筆的部分用空格補滿
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
