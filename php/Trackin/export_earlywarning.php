<?php
require 'fadmin.php';
//update_status ("匯出學生預警系統");
	if ( isset($PHPSESSID) && session_check_admin($PHPSESSID) ) {
	}
	else{
		show_page( "index_ad.tpl", "你的權限錯誤，請重新登入!!");
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
	//顯示系所選項的bar
	$sql = "SELECT g.name, g.deptcd FROM course_group g WHERE g.level >= 2  ORDER BY g.deptcd ASC";
	if ($result = mysql_db_query($DB, $sql)){
		while($row = mysql_fetch_array($result)){
			$group .= "<option value='".$row[deptcd]."'>".$row[name]."</option>";
		}
	}
	$tpl->assign("GROUP",$group);
	//選擇過
	//2009.11.10教學組要求新增預警日期欄位(即教師修改預警學生當天)
	//於37、43、49、54、59、70、74、92、94、97行增加一個e.mdate欄位
	if($_GET[action] == "select"){
		switch($_POST[type]){
			case "1": //學期學年
				$output_type = "[".$_POST[year]."學期".$_POST[term]."學年]";
				$file_name="../../early_warning_".$_POST[year]."_".$_POST[term].".xls";
				$sql = "SELECT e.year, e.term, c.course_no, c.name  as course_name, g.deptcd, u.grade, u.job, u.id, u.name, e.reason, e.mdate FROM early_warning e, user u, course c, course_group g  WHERE e.course_id=c.a_id and e.student_id=u.a_id and c.group_id=g.a_id and e.year='".$_POST[year]."' and e.term='".$_POST[term]."' ORDER BY e.course_id ASC";	
				
				break;
			case "2": //科目名稱 / 科目代碼
				$output_type = "[".$_POST[course_name]."(".$_POST[course_no].")]";
				$file_name="../../early_warning_course_no_".$_POST[course_no].".xls";
				$sql = "SELECT e.year, e.term, c.course_no, c.name  as course_name, g.deptcd, u.grade, u.job, u.id, u.name, e.reason, e.mdate FROM early_warning e, user u, course c, course_group g  WHERE e.course_id=c.a_id and e.student_id=u.a_id and c.group_id=g.a_id and c.name='".$_POST[course_name]."' and c.course_no='".$_POST[course_no]."'  ORDER BY e.course_id ASC";	

				break;
			case "3": //系所 (系所代碼)
				$output_type = "[". getGroupNameByGroupCd($DB, $_POST[group]) ."(".$_POST[group].")]";
				$file_name="../../early_warning_deptcd_".$_POST[group].".xls";
				$sql = "SELECT e.year, e.term, c.course_no, c.name  as course_name, g.deptcd, u.grade, u.job, u.id, u.name, e.reason, e.mdate FROM early_warning e, user u, course c, course_group g  WHERE e.course_id=c.a_id and e.student_id=u.a_id and c.group_id=g.a_id and g.deptcd='".$_POST[group]."' ORDER BY e.course_id ASC";					
				break;
			case "4": //原因
				$output_type = "[".getReasonByReasonCd($_POST[reason])."]";
				$file_name="../../early_warning_reason_".$_POST[reason].".xls";
				$sql = "SELECT e.year, e.term, c.course_no, c.name  as course_name, g.deptcd, u.grade, u.job, u.id, u.name, e.reason, e.mdate FROM early_warning e, user u, course c, course_group g  WHERE e.course_id=c.a_id and e.student_id=u.a_id and c.group_id=g.a_id and e.reason='".$_POST[reason]."' ORDER BY e.course_id ASC";	
				break;
			case "5": //全部
				$output_type = "[全部匯出]";
				$file_name="../../early_warning_all.xls";
				$sql = "SELECT e.year, e.term, c.course_no, c.name  as course_name, g.deptcd, u.grade, u.job, u.id, u.name, e.reason, e.mdate FROM early_warning e, user u, course c, course_group g  WHERE e.course_id=c.a_id and e.student_id=u.a_id and c.group_id=g.a_id ORDER BY e.course_id ASC";	
				break;
			default;
				exit;																	
		}
		
		//開檔
		if(file_exists($file_name))	unlink($file_name);	
		$file = fopen("$file_name","w");		
		//寫入檔案
		//excel的標頭
		fwrite($file, "學年\t學期\t科目代碼\t科目名稱\t班別\t系所代碼\t系所\t學號\t姓名\t原因\t預警日期\n");//寫檔
		//excel的內容
		if ($result = mysql_db_query($DB, $sql)){
			while($row = mysql_fetch_array($result)){
				fwrite($file, "$row[year]\t$row[term]\t$row[course_no]\t$row[course_name]\t$row[grade]\t$row[deptcd]\t$row[job]\t$row[id]\t$row[name]\t". getReasonByReasonCd($row[reason]) . "\t$row[mdate]\n");//寫檔
			}
		}else{
			echo "資料庫讀取錯誤!!";
			exit;
		}
		//關檔
		fclose($file);					
	}
	//預設
	else{
		$output_type = "[全部匯出]";
		//開檔
		$file_name="../../early_warning_all.xls";
		if(file_exists($file_name))	unlink($file_name);	
		$file = fopen("$file_name","w");
		//寫入檔案
		//excel的標頭
		fwrite($file, "學年\t學期\t科目代碼\t科目名稱\t班別\t系所代碼\t系所\t學號\t姓名\t原因\t預警日期\n");//寫檔
		//excel的內容
		$sql = "SELECT e.year, e.term, c.course_no, c.name  as course_name, g.deptcd, u.grade, u.job, u.id, u.name, e.reason, e.mdate FROM early_warning e, user u, course c, course_group g  WHERE e.course_id=c.a_id and e.student_id=u.a_id and c.group_id=g.a_id ORDER BY e.course_id ASC";	
		if ($result = mysql_db_query($DB, $sql)){
			while($row = mysql_fetch_array($result)){
				fwrite($file, "$row[year]\t$row[term]\t$row[course_no]\t$row[course_name]\t$row[grade]\t$row[deptcd]\t$row[job]\t$row[id]\t$row[name]\t". getReasonByReasonCd($row[reason]) . "\t$row[mdate]\n");//寫檔
			}
		}else{
			echo "資料庫讀取錯誤!!";
			exit;
		}
		//關檔
		fclose($file);		
	}
	
	//assign檔案路徑
	$tpl->assign("OUTPUT_TYPE",$output_type);
	$tpl->assign("FILE_PATH",$file_name);
	
	//output page
	$tpl->parse(ROW,".row");	
	$tpl->parse(BODY,"main");	
	$tpl->FastPrint("BODY");
//-----function area------
function getReasonByReasonCd($reason_cd){
/*
	<OPTION value='0'>需加強原因
	<OPTION value='1'>成績不佳
	<OPTION value='2'>缺課
	<OPTION value='3'>成績不佳且缺課
*/
	$tmp = array('0'=>'需加強原因','1'=>'成績不佳','2'=>'缺課','3'=>'成績不佳且缺課');
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