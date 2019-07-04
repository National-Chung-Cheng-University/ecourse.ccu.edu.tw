<?php
require 'fadmin.php';
if(isset($PHPSESSID) && session_check_teach($PHPSESSID) == 2)
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $course_id, $skinnum, $SDB;
	include("class.FastTemplate.php3");
	$tpl=new FastTemplate("./templates");
	$tpl->define_dynamic("comment", "main");
	if ( $version == "C" )
		$tpl->define(array(main=>"print_caution.tpl"));
	else
		$tpl->define(array(main=>"print_caution_E.tpl"));
	//課程資訊
	
	//取出課程相關資訊
	// 取出課程代碼
	//$Qs = "select course_no from course_no where course_id='".$course_id."'";
	$Qs = "select course_no from course where a_id='".$course_id."'";
	if ($result = mysql_db_query($DB,$Qs)){
		$row = mysql_fetch_array($result);
		$course_no = explode("_",$row['course_no']);
		$cour_cd = $course_no[0];
		$grp = $course_no[1];
	}
	else{
		$error = "mysql資料庫讀取錯誤!!";
		return "$error $Qs<br>";
	}	
	$course_info['cid'] = $cour_cd;
	$course_info['gid'] = $grp;	

	//　取出課程名稱及系所名稱
	$cno = $cour_cd."_".$grp;
	//$Qs = "select course.name as cname, course_group.name as gname from course, course_no, course_group where course.group_id=course_group.a_id and course.a_id=course_no.course_id and course_no.course_no = '$cno'";
	$Qs = "select course.name as cname, course_group.name as gname from course, course_group where course.group_id=course_group.a_id and course.course_no = '$cno'";
	if ($result = mysql_db_query($DB,$Qs)){
		$row = mysql_fetch_array($result);
		$course_info['cname'] = $row['cname'];
		$course_info['gname'] = $row['gname'];
	}
	else{
		$error = "mysql資料庫讀取錯誤!!";
		return "$error $Qs<br>";
	}
	// 取出開課學年度及學期
	$course_info['year'] = $course_year;
	$course_info['term'] = $course_term;	
	
	// 取出教師姓名
	$Qs = "select u.name as name from user as u, teach_course as tc where u.authorization='1' and u.a_id=tc.teacher_id";
	$Qs .= " and tc.year=".$course_info['year']." and tc.term=".$course_info['term']." and tc.course_id=".$course_id;
	if ($result = mysql_db_query($DB,$Qs)){
		$row = mysql_fetch_array($result);
		$course_info['tch'] = $row['name'];
		while( $row = mysql_fetch_array($result) )
			$course_info['tch'] .= ','.$row['name'];
	}
	else{
		$error = "mysql資料庫讀取錯誤!!";
		return "$error $Qs<br>";
	}
	// 連結選課系統資料庫
	//if( !($cnx = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) ){  
	//	echo( "在 sybase_connect 有錯誤發生");  
	//}
	$conn_string = "host=140.123.30.12 dbname=".$SDB." user=acauser password=!!acauser13";
	$cnx = pg_pconnect($conn_string) or die('資料庫沒有回應，請稍後再試');
	
	//$csd = @sybase_select_db($SDB, $cnx);

	// 取得學分數
	$Qs = "select credit from a30vcourse_tea where course_no = '$cour_cd'";
	//$cur = sybase_query($Qs, $cnx);
	//if(!$cur) {  
	//	echo( "在 sybase_exec 有錯誤發生( 沒有指標傳回 ) ");  
	//}
	$cur = pg_query($cnx, $Qs) or die('資料表不存在，請通知電算中心');
	
	//if(($array=sybase_fetch_array($cur))!=0){
	if(($array=pg_fetch_array($cur, null, PGSQL_ASSOC))!=0){
		$course_info["credit"] = $array['credit'];
	}else{
		$course_info["credit"] = "";
	}
	$tpl->assign( YEAR, $course_info['year']);
	$tpl->assign( TERM, $course_info['term']);
	$tpl->assign( CID, $course_info['cid'] );
	$tpl->assign( GID, $course_info['gid'] );
	$tpl->assign( CREDIT, $course_info["credit"] );
	$tpl->assign( CNAME, $course_info['cname'] );
	$tpl->assign( GNAME, $course_info['gname'] );
	$tpl->assign( TEACHER, $course_info['tch'] );
	$tpl->assign( DATE, (date("Y")-1911).date("/m/d"));		
	//以上是列出開頭的課程資訊	

	//讀取學生資料檔
	//檔案路徑
	$file_name="../../$course_id/early_warning_$course_id.xls";
	//開檔
	$fp=fopen("$file_name","r");
	//讀檔
	$query_file = fread($fp, filesize($file_name)-1);
	fclose($fp);	
	//用\n 將query_file切成很多行
	$query_list = explode("\n",$query_file);
	$list_count = count($query_list);
	//用\t 再將每行切成很多個字串
	for($i=0; $i < $list_count; $i++){
		$all_grade[$i] = explode("\t",$query_list[$i]);
	}
	$col_num = count($all_grade[0]);
	for($i=0; $i< $list_count -1;$i++){
		if($all_grade[$i+1][0] != "")
			$caution_list[$i]['class'] = $all_grade[$i+1][0];
		else
			$caution_list[$i]['class'] = "<font size='4'>&nbsp;</font>";	
					
		if($all_grade[$i+1][1] != "")
			$caution_list[$i]['std_no'] = $all_grade[$i+1][1];
		else
			$caution_list[$i]['std_no'] = "<font size='4'>&nbsp;</font>";	
		$caution_list[$i]['name']	= $all_grade[$i+1][2];
		if($all_grade[$i+1][$col_num-1] != "")
			$caution_list[$i]['score']	= $all_grade[$i+1][$col_num-1];
		else
			$caution_list[$i]['score']	= "<font size='4'>I</font>";	
		$caution_list[$i]['reason'] = $all_grade[$i+1][3];
	}
	$tpl->assign(STD, sizeof($caution_list));
	$total = ceil(sizeof($caution_list)/32);
	if($total==0)	$total=1;

	$tpl->assign(TOTAL, $total);
	
	for($p=1; $p <= $total; $p++){
		$page = $p;
		$tpl->define_dynamic("grade", "main");
		for($i=0;$i<16;$i++){
			$tpl->assign(DATA, "<tr>");
			if($i==0){
				$tpl->parse(GRADE,"grade");
			}
			else{
				$tpl->parse(GRADE,".grade");
			}		
			for($j=0;$j< 2;$j++){
				$k = ($page-1)*32+$j*16+$i;
				// 不滿48*page筆的部分用空格補滿
				if($caution_list[$k] == null || $caution_list[$k] == NULL){
					$caution_list[$k]['class'] = "<font size='4'>&nbsp;</font>";
					$caution_list[$k]['std_no'] = "<font size='4'>&nbsp;</font>";
					$caution_list[$k]['name'] = "<font size='4'>&nbsp;</font>";
					$caution_list[$k]['score'] = "<font size='4'>&nbsp;</font>";
					$caution_list[$k]['reason'] = "<font size='4'>&nbsp;</font>";
				}
				//$temp = "<td>".$caution_list[$k]['class']."</td><td>".$caution_list[$k]['std_no']."</td><td>".$caution_list[$k]['name']."</td><td align=\"center\">".$caution_list[$k]['score']."</td><td align=\"center\">".$caution_list[$k]['reason']."</td>";
				$temp = "<td>".$caution_list[$k]['class']."</td><td>".$caution_list[$k]['std_no']."</td><td>".$caution_list[$k]['name']."</td><td align=\"center\">".$caution_list[$k]['reason']."</td>";
				$tpl->assign(DATA, $temp);
				$tpl->parse(GRADE,".grade");
			}
			
			$tpl->assign(DATA, "</tr>");
			$tpl->parse(GRADE,".grade");
		}
		$tpl->assign(PLIST, $page);
		if($p != $total){
			$tpl->assign(DIVIDEPAPER, "<P style='page-break-after:always'></P>");
		}
		else{
			$tpl->assign(DIVIDEPAPER, "");
		}
		$tpl->parse(COMMENT,".comment");
	}
	$tpl->parse(BODY,"main");
	$tpl->FastPrint("BODY");
}
?>
