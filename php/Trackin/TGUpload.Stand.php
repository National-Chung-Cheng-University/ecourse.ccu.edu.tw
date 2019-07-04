<?php
require 'fadmin.php';
global $count, $action;
$test = $_POST["ss"];
if(isset($PHPSESSID) && session_check_teach($PHPSESSID) == 2 ){
	// 上傳成績及顯示成績登記表

	// 連結mysql
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$error = "資料庫連結錯誤!!";
		return $error;
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
				//log IP及動作
	                        add_log('12',$user_id,'',$course_id,'',"上傳成績完成");
				echo "<script language=\"JavaScript\" type=\"text/JavaScript\">alert(\"成績己上傳$count 筆，請檢視及列印成績登記表。\");</script>";
				//若upload_score()成功則redirect
                                //echo "<script>window.location = 'TGShowFrame2.php?course=$course_id&year=$year&term=$term&PHPSESSID=$PHPSESSID'</script>";
				show_page_d(1);
			}
			else{
				add_log('12',$user_id,'',$course_id,'',"上傳成績失敗");
				echo "<script language=\"JavaScript\" type=\"text/JavaScript\">alert(\"成績上傳失敗！\");</script>";
			}
		}
		else if($_POST["action"]=="preview"){
			$action = "preview";
			update_score();
			show_page_d(1);
		}
		else if ($_POST["action"] == "savescore") //by carlyle
		{
			$msg = save_score_temp();
			show_page_SST($msg);
		}
		else if($_POST["action"]=="upload_preview"){//add by intree
                        upload_preview();
                }
		else if($page!=NULL){
			//show_page_d($page);
			show_page_d(1);
		}
	}
}
else{
	if( $version=="C" ) {
		show_page( "not_access.tpl" ,"你沒有權限使用此功能,3");
		exit;
	}
	else {
		show_page( "not_access.tpl" ,"You have No Permission!!,3");
		exit;
	}
}

/* 成績暫存 by carlyle */
function save_score_temp() 
{
        global $DB_SERVER,$DB_LOGIN,$DB,$DB_PASSWORD,$course_id,$report;

        if (!($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD))) {
                show_page("not_access.tpl","資料庫連結錯誤!!");
                exit;
        }

	$k = 0;
        for ($i=0;$i<sizeof($report);$i++) {
                if($_POST["grade".$report[$i]['std_no']]!="" && $_POST["state".$report[$i]['std_no']]==1) {
                        $std_no = $report[$i]['std_no'];
                        $final = $_POST["grade".$report[$i]['std_no']];
                      
			$Q1 = "SELECT a_id FROM user WHERE id = '$std_no'";
                        if (!($result1 = mysql_db_query($DB,$Q1)))
                        	return ("mysql資料庫讀取錯誤!!" . $Q1 . "<br>");
			else
				$row1 = mysql_fetch_array($result1);

                        $Q2 = "SELECT * FROM take_exam WHERE exam_id = '-2' and student_id = '$row1[a_id]'";
			if (!($result2 = mysql_db_query($DB.$course_id,$Q2)))
                        	return ("mysql資料庫讀取錯誤!!" . $Q2 . "<br>");
			else
				$count = mysql_num_rows($result2);

			if ($count == 0) //此學生的成績尚未存入
				$Q3 = "INSERT INTO take_exam (exam_id,student_id,grade,public) VALUES ('-2','$row1[a_id]','$final','0')";
			else //此學生的成績已存在
	                	$Q3 = "UPDATE take_exam SET grade = '$final' WHERE exam_id = '-2' and student_id = '$row1[a_id]'";

                        if (!(mysql_db_query($DB.$course_id,$Q3)))
				return 	("mysql資料庫讀取錯誤!!" . $Q3 . "<br>");
			else
				$k++;
               }
        }

	return "暫存成績資料成功！";
}

/* 顯示成績暫存結果頁面 by carlyle */
function show_page_SST($msg)
{
	include("class.FastTemplate.php3");
        $tpl = new FastTemplate("./templates");
	$tpl->define(array(main=>"TGUpload_savetemp.tpl"));
        $tpl->assign("#!MESSAGE!#",$msg);
        $tpl->assign("#!LINK!#","/php/Trackin/TGShowFrame.php?loadtemp=1");
        $tpl->parse(BODY,"main");
        $tpl->FastPrint("BODY");
}

function update_score(){
	global $course_id, $report, $count, $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
		exit;
	}
	$Qs = "delete from take_exam where exam_id = '-1'";
	if (!($result = mysql_db_query($DB.$course_id,$Qs))){
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
			
			$Q2 = "insert into take_exam (exam_id, student_id, grade) values ( '-1', '$row1[a_id]', '$final')";
			if (!($result2 = mysql_db_query($DB.$course_id,$Q2))){
				$error = "mysql資料庫讀取錯誤!!";
				return "$error $Q2<br>";
			}
			$count++;
		}
	}
}

//上傳成績至教務系統
function upload_score(){
	global $SDB, $course_id, $report, $course_info, $count;
	$ip = getenv("REMOTE_ADDR");	//取得上線ip
	if ( $ip == "" )
		$ip = $HTTP_X_FORWARDED_FOR;
	if ( $ip == "" )
		$ip = $REMOTE_ADDR;

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
			$cur = sybase_query($Qins, $cnx);	//測試用，不上傳，如需要時再開啟--jp96
			if(!$cur) {  
				Error_handler( "在 sybase_exec 有錯誤發生( 沒有指標傳回 ) " , $cnx );  
			}
			$count++;
		}
	}
	sybase_close( $cnx);
	return -1;
}

/*成績上傳前的檢視 , by intree */
function upload_preview(){
        global $report,$course_id,$skinnum,$course_info,$SDB;

        include("class.FastTemplate.php3");
        $tpl = new FastTemplate("./templates");
        $tpl->define(array(main=>"TGUpload_preview.tpl"));
        $tpl->define_dynamic("grade", "main");
        $tpl->assign(  COURSEID, $course_id);
        $tpl->assign( SKINNUM, $skinnum );
        $tpl->assign("#!YEAR!#",$course_info[year]);
        $tpl->assign("#!TERM!#",$course_info[term]);

	$tpl->assign(YEAR, $course_info['year']);
        $tpl->assign(TERM, $course_info['term']);
        $tpl->assign(CID, $course_info['cid']);
        $tpl->assign(GID, $course_info['gid']);
        $tpl->assign(CREDIT, $course_info["credit"] );
        $tpl->assign(GNAME, $course_info['gname']);
        $tpl->assign(CNAME, $course_info['cname']);
        $tpl->assign(TEACHER, $course_info['tch']);
        $tpl->assign(DATE, (date("Y")-1911).date("/m/d"));
		//$tpl->assign(STD, get_STD($course_id,$course_info));
		$tpl->assign(STD, sizeof($report));
		//
        $tpl->assign(UPLOADED, $_POST[uploaded_count]);
        //$tpl->assign(YETUPLOAD, (get_STD($course_id,$course_info)-$_POST[uploaded_count]) );
	
	$deptcd = get_deptcd();

	$upload_count=0;
        for($i=0;$i<sizeof($report);$i++){
                if($_POST["grade".$report[$i]['std_no']]!="" && $_POST["state".$report[$i]['std_no']]==1){
		  $upload_count++;
                  $std_grade = $_POST["grade".$report[$i]['std_no']];
                  $std_radio_name = "state".$report[$i]['std_no'];
                  $grp_data = "<td><div align=\"center\">".$report[$i][grp]."</div></td>";
                  $std_no_data = "<td><div align=\"center\">".$report[$i][std_no]."</div></td>";
                  $name_data = "<td><div align=\"center\">".$report[$i][name]."</div></td>";
		  $grade_color = is_fail($report[$i][std_no],$std_grade,$deptcd);
                  $score_data = "<td><div align=\"center\"><font color='$grade_color'>$std_grade</font><input type='hidden' name='".'grade'.$report[$i]['std_no']."' value=\"".$std_grade."\" size=2 readonly></div></td>";
                  $check_data =  "<td><div align=\"center\"><input type='radio' name='".$std_radio_name."' value='1' checked>是</div></td>";
                  $tpl->assign(DATA,$report[$i][color].$grp_data.$std_no_data.$name_data.$score_data.$check_data);
                  $tpl->parse(GRADE,".grade");
                }
        }
	//判斷如果是在職班就在科目後面標上碩士在職專班
	getSDB();
	if($SDB == "academic")
		$tpl->assign(SDB, "");
        else
		$tpl->assign(SDB, '(碩士在職專班)');


	$tpl->assign(UPLOADING, $upload_count);
        $tpl->parse(BODY,"main");
        $tpl->FastPrint("BODY");
}


function getSDB(){
        global $SDB,$DB,$course_id;
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
}

function show_page_d($page){
	global $PHPSESSID, $course_info, $SDB, $action, $course_id, $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	include("class.FastTemplate.php3");
	getSDB();
	$tpl=new FastTemplate("./templates");
	if($action == "upload"){
		if($SDB == "academic")
			$tpl->define(array(main=>"TGUpload.tpl"));
		else
			$tpl->define(array(main=>"TGUpload_gra.tpl"));
	}
	else if ($action == "preview"){
		if($SDB == "academic")
			$tpl->define(array(main=>"TGPreview.tpl"));
		else
			$tpl->define(array(main=>"TGPreview_gra.tpl"));
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
	//$tpl->assign(SDLINE, $course_info['s_deadline']);
	//$tpl->assign(GDLINE, $course_info['g_deadline']);

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
		$Q1 = "select grade from take_exam where exam_id='-1' and student_id='$row1[a_id]'";
		if (($result1 = mysql_db_query($DB.$course_id, $Q1))){
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
		/*if($data[$i]['status']=="休學" || $data[$i]['status']=="退學")
			$Qs3 = "select deptcd now_dept, grade now_grade, class now_class, abbrev from a11vstd_rec_tea vstd, h0rtunit_a_ unit where vstd.id='$std_no' and vstd.deptcd=unit.cd";
		else
			$Qs3 = "select now_dept, now_grade, now_class, abbrev from a11tstd_his_rec tstd, h0rtunit_a_ unit where tstd.std_no='$std_no' and tstd.now_dept=unit.cd and tstd.year='$course_info[year]' and tstd.term='$course_info[term]'";
		*/
		// 952修改:在學生學藉資料以a11tstd_his_rec為準,休學生以a11vstd_rec_tea為準,退學生以a11vleave_rec_tea為準,取出學生之系所班級等學籍資料
		if($data[$i]['status']=="休學")
			$Qs3 = "select deptcd now_dept, grade now_grade, class now_class, abbrev from a11vstd_rec_tea vstd, h0rtunit_a_ unit where vstd.id='$std_no' and vstd.deptcd=unit.cd";
		else if($data[$i]['status']=="退學")
			$Qs3 = "select deptcd now_dept, grade now_grade, class now_class, abbrev from a11vleave_rec_tea vstd, h0rtunit_a_ unit where vstd.id='$std_no' and vstd.deptcd=unit.cd";
		else
			$Qs3 = "select now_dept, now_grade, now_class, abbrev from a11tstd_his_rec tstd, h0rtunit_a_ unit where tstd.std_no='$std_no' and tstd.now_dept=unit.cd and tstd.year='$course_info[year]' and tstd.term='$course_info[term]'";
		//
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
		/*****$i from 16 -> 24, modified by intree*****/
		$deptcd = get_deptcd();
		for($i=0;$i<24;$i++){
			$tpl->assign(DATA, "<tr height=\"30\">");
			//$tpl->parse(GRADE,".grade");
			/*************bluejam*********/
			if($i==0){
				$tpl->parse(GRADE,"grade");
			}
			else{
				$tpl->parse(GRADE,".grade");
			}
			/*********bluejam**********/
			 /*****$i from 16 -> 24,$j*16 => $j*24, modified by intree*****/
			for($j=0;$j<2;$j++){
				$k = ($page-1)*48+$j*24+$i;
				// 不滿48*page筆的部分用空格補滿
				if($data[$k]==null){
					$data[$k]['grp'] = "<font size=\"4\">&nbsp;</font>";
					$data[$k]['std_no'] = "<font size=\"4\">&nbsp;</font>";
					$data[$k]['name'] = "<font size=\"4\">&nbsp;</font>";
					$data[$k]['score'] = "<font size=\"4\">&nbsp;</font>";
				}
				$grade_color=is_fail($data[$k]['std_no'],$data[$k]['score'],$deptcd);
				$temp = "<td>".$data[$k]['grp']."</td><td>".$data[$k]['std_no']."</td><td>".$data[$k]['name']."</td><td align=\"center\"><font color='$grade_color'>".$data[$k]['score']."</font></td>";
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

/*用course_id 取得此課是系或是研所開的 , by intree */
function get_deptcd(){
        global $course_id,$DB,$report;
        $Q11 =  "select course.name as cname, course_group.name as gname, course_group.deptcd as deptcd from course, course_group where course.group_id=course_group.a_id and course.a_id = '$course_id'";

        if( $result = mysql_db_query($DB,$Q11) )
        {
                $row1 = mysql_fetch_array($result);
                return $row1['deptcd'];
        }
        else{
                $error = "資料庫讀取錯誤!!";
                return "$error $Q11<br>";
        }

}

/*不及格分數變色 , by intree */
function is_fail($std_no , $score , $deptcd){
        //red為不及格 , black為及格
        $pass = 60;

        $dep = substr($deptcd,3);

        $std_id = substr($std_no,0,1);

        //系開的課60分及格
        if($dep==4 || $deptcd==7006 || $deptcd==7306 || $deptcd=='F000' || $deptcd=='V000' || $deptcd=='Z121'){
                if($score < $pass) return 'red';
                else return 'black';
        }
        //研所開的課,要看學號是大學生或研究生
        else if($dep==6){
                //若是大學生則60分及格
                if($std_id==4){
                        if($score < $pass) return 'red';
                        else return 'black';
                }
                //若是研究生則70分及格
                if($std_id==5 || $std_id==6 || $std_id==8){
                        $pass=70;
                        if($score < $pass) return 'red';
                        else return 'black';
                }
        }

}

//從教務處的資料庫來取得該課程的修課總人數
/*function get_STD($course_id,$course_info){
        global $DB;
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

         // 查詢選課資料
        $cour_cd = $course_info['cid'];
        $grp = $course_info['gid'];
        // 取出所有選課學生學號及姓名
        $Qs = "select cs.std_no, vstd.name, vstd.status from a31v_sel_class_tea cs, a11vstd_rec_tea vstd where cs.cour_cd = '$cour_cd' and cs.grp = '$grp' and cs.std_no=vstd.id and cs.year = '$course_info[year]' and cs.term = '$course_info[term]' ".
        "union select cs.std_no, vstd.name, vstd.status from a31vhis_sel_class_tea cs, a11vstd_rec_tea vstd where cs.cour_cd = '$cour_cd' and cs.grp = '$grp' and cs.std_no=vstd.id and cs.year = '$course_info[year]' and cs.term = '$course_info[term]' ";

        $cur = sybase_query($Qs, $cnx);

        if(!$cur) {
                Error_handler( "在 sybase_exec 有錯誤發生( 沒有指標傳回 ) " , $cnx );
        }

        //回傳修課總人數
        return sybase_num_rows($cur);
}
*/

?>
