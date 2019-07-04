<?php
require 'fadmin.php';
update_status ("學生預警系統");

if(isset($PHPSESSID) && session_check_teach($PHPSESSID) == 2)
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_year, $course_term;	
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
		exit;
	}
	//更新
	if($_POST['update'] == 'yes'){
		//查出所有的學生
		$sql = "SELECT u.a_id, u.id FROM user u, take_course tc WHERE u.a_id=tc.student_id AND tc.course_id='$course_id' and tc.year='$course_year' and tc.term = '$course_term'  and tc.credit = '1' ORDER BY u.id ASC";
		$res = mysql_db_query($DB, $sql);
		$num = mysql_num_rows($res);	
		for($i=0; $i< $num ;$i++){
			$all_stu[$i] = mysql_fetch_array($res);
		}				
		$sql = "SELECT * FROM early_warning WHERE course_id='".$_POST['c_id']."' and year='".$_POST['c_year']."' and term='".$_POST['c_term']."'";
		$result = mysql_db_query($DB, $sql);
		$num_cnt = mysql_num_rows($result);			//做為判斷無預警課程使用
		for($i=0; $i < $num; $i++){
			$tmp =$i+1;
			if($_POST["who_$tmp"] == "on"){//有選擇
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
			if($all_stu[$i]['select'] == '0' && $isHave == 1){  //沒有選 delete 但是存在 需要刪除
				$sql = "DELETE FROM early_warning WHERE a_id='".$row['a_id']."'";
			}
			elseif($all_stu[$i]['select'] == '1' &&  $isHave == 1){ //有資料 update 				
				$sql = "UPDATE early_warning SET reason='".$_POST["reason_$tmp"]."' WHERE a_id='".$row['a_id']."'";
			}
			elseif($all_stu[$i]['select'] == '1' &&  $isHave == 0){ //無資料 insert 
				$sql = "INSERT INTO early_warning (course_id, student_id, year, term, reason, mdate) values ('".$_POST['c_id']."', '".$_POST["student_id_$tmp"]."', '".$_POST['c_year']."', '".$_POST['c_term']."', '".$_POST["reason_$tmp"]."', curdate() )";				
			}
			$res = mysql_db_query($DB, $sql);								
		}
		if($num_cnt == 0) {
			$sql = "INSERT INTO early_warning (course_id, student_id, year, term, reason, mdate) values ( $course_id, 9999999, $course_year, $course_term, 4, curdate() )";
			//echo $sql;
			$res = mysql_db_query($DB, $sql);
		}
		//2012.11.26 教學組要求將'已經更新'修改為'已完成預警' ******************
		echo "<script language='javascript'>";
		//echo "alert('已經更新')";
		echo "alert('已完成預警')";
		echo "</script>";		
		echo "<font color='red'>已完成預警</font><br>";	
	}	
	//取出該門課的學生
	$stu_list = get_student_list($course_id, $course_year, $course_term);
	//有學生才做
	if($stu_list != 0){
		//查出該門課所有的 作業 與 測驗 的名稱與比率
		$res_exam 		= get_exam_score_name($course_id);
		$res_homework	= get_homework_score_name($course_id);
		$exam_num		= mysql_num_rows($res_exam);
		$homework_num	= mysql_num_rows($res_homework);
		//只要有一個不為 0 就進去算
		//if(($exam_num != 0)||($homework_num != 0)){
			//查出學生的 作業 與 測驗 與總分
			$all_data = get_all_stu_score($course_id, $stu_list, $res_exam, $res_homework, $exam_num, $homework_num);
			//將結果show出
			//echo "<pre>".print_r($all_data,true)."</pre>";
			output_page($all_data, $course_year, $course_term, $course_id);			
		//}
		//沒有成績
		//else{
			//if( $version=="C" )
				//show_page( "not_access.tpl" ,"此課程尚未有任何成績!");
			//else
				//show_page( "not_access.tpl" ,"There is no SCORE in this Class!!");
		//}
	}
	//沒有學生
	else{
		if( $version=="C" )
			show_page( "not_access.tpl" ,"此課程尚未有任何學生!");
		else
			show_page( "not_access.tpl" ,"There is no Student in this Class!!");	
	}	
}
	
//取出該門課的學生	
function get_student_list($course_id, $course_year, $course_term)
{
	global $DB;
	//取出該門課的學生
	$sql = "SELECT u.a_id, u.id, u.name , u.email, u.job, u.grade FROM user u, take_course tc WHERE u.a_id=tc.student_id AND tc.course_id='$course_id' and tc.year='$course_year' and tc.term = '$course_term'  and tc.credit = '1' ORDER BY u.id ASC";
	if ( !($res = mysql_db_query( $DB, $sql ) ) ) {
		show_page( "not_access.tpl" ,"資料庫讀取錯誤1!!" );
		exit;
	}
	//判斷是否有學生，如果沒有就傳回 0
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

//查出測驗的比率與分數
function get_exam_score_name($course_id)
{
	global $DB;
	//選出exam　的名稱　百分比　a_id is_online 如果有public 且時間不為0
	$sql = "SELECT name,percentage,a_id  FROM exam where ( public = '1' or (end_time != '00000000000000' && beg_time <= ".date("YmdHis")." ) ) ORDER BY a_id";
	if ( !($res = mysql_db_query( $DB.$course_id, $sql ) ) ) {
		show_page( "not_access.tpl" ,"資料庫讀取錯誤2!!" );
		exit;
	}	
	return $res;
}

//查出作業的比率與分數
function get_homework_score_name($course_id)
{
	global $DB;
	//選出　homework的名稱　百分比　a_id 
	$sql = "SELECT name,percentage,a_id FROM homework where public = '1' or public = '3' ORDER BY a_id";
	if ( !($res = mysql_db_query( $DB.$course_id, $sql ) ) ) {
		show_page( "not_access.tpl" ,"資料庫讀取錯誤3!!" );
		exit;
	}
	return $res; 
}

//查出學生的 作業 與 測驗 與總分 並將學生資料加入陣列
function get_all_stu_score($course_id, $stu_list, $res_exam, $res_homework, $exam_num, $homework_num)
{
	global $DB;
	// 0          1     2       3         4         5      6...         ...          ...
	//系所班級,　學號, 姓名, email, 預警勾選, 預警原因, 測驗成績... , 作業成績... , 總成績
	
	//將學生的資料插入陣列的 0~5
	$all_data = add_stu_list_into_array($stu_list);		
	//測驗 -- 比率與名稱
	if($exam_num != 0){
		//將測驗的名稱與比率加在 $all_data[0] ,從 $all_data[0][6]繼續新增
		for($j=6; $j < $exam_num+6; $j++){
			$row_e = mysql_fetch_array($res_exam);
			$all_data[0][$j]['a_id'] = $row_e['a_id'];
			$all_data[0][$j]['name'] = $row_e['name'];
			$all_data[0][$j]['percentage'] = $row_e['percentage'];
		}		
		//紀錄測驗使用到$all_data的位置
		//$used_count = $exam_num+6;
	}

	$used_count = $exam_num+6;	
	//作業 -- 比率與名稱
	if($homework_num != 0){
		for($j = $used_count; $j < $homework_num+$used_count; $j++){
			$row_h = mysql_fetch_array($res_homework);
			$all_data[0][$j]['a_id'] = $row_h['a_id'];
			$all_data[0][$j]['name'] = $row_h['name'];
			$all_data[0][$j]['percentage'] = $row_h['percentage'];
		}
		//紀錄作業使用到$all_data的位置
		$used_count += $homework_num;			
	}		
	//總分的標頭
	$all_data[0][$used_count] = "總成績";		
	//查出每個學生測驗的分數
	for($i = 1; $i <= count($stu_list) ; $i++){
		$total_score = 0;
		if($exam_num != 0){
			for($j=6; $j< $used_count-$homework_num; $j++){
				$sql = "SELECT grade FROM take_exam WHERE exam_id ='".$all_data[0][$j]['a_id']."' AND student_id = '".$stu_list[$i-1]['a_id']."'";
				if ( !($res_j = mysql_db_query( $DB.$course_id, $sql ) ) ) {
					show_page( "not_access.tpl" ,"資料庫讀取錯誤3!!" );
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
					show_page( "not_access.tpl" ,"資料庫讀取錯誤3!!" );
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
		//加入總分
		$all_data[$i][$used_count] = $total_score;
	}
			
	return $all_data;
}	

function add_stu_list_into_array($stu_list)
{
	global $DB;
	// 先加上 每一個col的標頭
	$all_data[0][0] = "系所班級";
	$all_data[0][1] = "學號";
	$all_data[0][2] = "姓名";
	$all_data[0][3] = "email";
	$all_data[0][4] = "勾選預警名單";
	$all_data[0][5] = "課業需要加強原因";
	//查出已經有點選的
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
						case '0' :$choose .= "<OPTION value='0' selected>需加強原因<OPTION value='1'>成績不佳
												<OPTION value='2'>缺課<OPTION value='3'>成績不佳且缺課
												<OPTION value='4'>作業未依規定繳交<OPTION value='5'>缺考<OPTION value='6'>其他";
								  $all_data[$i]['reason'] = "需加強原因";
								  break;
						case '1' :$choose .= "<OPTION value='0' >需加強原因<OPTION value='1' selected>成績不佳
												<OPTION value='2'>缺課<OPTION value='3'>成績不佳且缺課
												<OPTION value='4'>作業未依規定繳交<OPTION value='5'>缺考<OPTION value='6'>其他";
								$all_data[$i]['reason'] = "成績不佳";
								 break;
						case '2' :$choose .= "<OPTION value='0'>需加強原因<OPTION value='1'>成績不佳
												<OPTION value='2' selected>缺課<OPTION value='3'>成績不佳且缺課
												<OPTION value='4'>作業未依規定繳交<OPTION value='5'>缺考<OPTION value='6'>其他";
								 $all_data[$i]['reason'] = "缺課";
								 break;
						case '3' :$choose .= "<OPTION value='0'>需加強原因<OPTION value='1'>成績不佳
												<OPTION value='2'>缺課<OPTION value='3' selected>成績不佳且缺課
												<OPTION value='4'>作業未依規定繳交<OPTION value='5'>缺考<OPTION value='6'>其他";
								$all_data[$i]['reason'] = "成績不佳且缺課";
								 break;
						case '4' :$choose .= "<OPTION value='0'>需加強原因<OPTION value='1'>成績不佳
												<OPTION value='2'>缺課<OPTION value='3'>成績不佳且缺課
												<OPTION value='4' selected>作業未依規定繳交<OPTION value='5'>缺考<OPTION value='6'>其他";
								$all_data[$i]['reason'] = "作業未依規定繳交";
								 break;										
						case '5' :$choose .= "<OPTION value='0'>需加強原因<OPTION value='1'>成績不佳
												<OPTION value='2'>缺課<OPTION value='3'>成績不佳且缺課
												<OPTION value='4'>作業未依規定繳交<OPTION value='5' selected>缺考<OPTION value='6'>其他";
								$all_data[$i]['reason'] = "缺考";
								 break;										
						case '6' :$choose .= "<OPTION value='0'>需加強原因<OPTION value='1'>成績不佳
												<OPTION value='2'>缺課<OPTION value='3'>成績不佳且缺課
												<OPTION value='4'>作業未依規定繳交<OPTION value='5'>缺考<OPTION value='6' selected>其他";
								$all_data[$i]['reason'] = "其他";
								 break;																				
					}					
					$choose .="<SELECT>";break;				
				}
				else{
					$who = "<input name='who_".$i."' type='checkbox'>";
					$choose = "<SELECT NAME='reason_".$i."'>
							<OPTION value='0'>需加強原因
							<OPTION value='1'>成績不佳
							<OPTION value='2'>缺課
							<OPTION value='3'>成績不佳且缺課
							<OPTION value='4'>作業未依規定繳交
							<OPTION value='5'>缺考
							<OPTION value='6'>其他
							<SELECT>"	;	
					$all_data[$i]['select'] = '0';
					$all_data[$i]['reason'] = "需加強原因";			
				}
		}				
		$all_data[$i][4] = $who;
		$all_data[$i][5] = $choose;			
	}
	return $all_data;
}


//秀出結果
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

	//$csd = @sybase_select_db($SDB, $cnx);

	$conn_string = "host=140.123.30.12 dbname=academic user=acauser password=!!acauser13";
	$cnx = pg_pconnect($conn_string) or die('資料庫沒有回應，請稍後再試');		
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
	$tpl->assign( C_ID, $course_id);
	$tpl->assign( CID, $course_info['cid'] );
	$tpl->assign( GID, $course_info['gid'] );
	$tpl->assign( CREDIT, $course_info["credit"] );
	$tpl->assign( CNAME, $course_info['cname'] );
	$tpl->assign( GNAME, $course_info['gname'] );
	$tpl->assign( TEACHER, $course_info['tch'] );
	$tpl->assign( LOOP, count($all_data)-1);		
	$tpl->assign( DATE, (date("Y")-1911).date("/m/d"));		
	//以上是列出開頭的課程資訊
	//製作輸出的檔案
	$file_name="../../$course_id/early_warning_".$course_id.".xls";
	if(file_exists($file_name))	unlink($file_name);
	$file=fopen("$file_name","w");
	//顯示表格的標頭
	$row_data = show_table_header($all_data,$file);
	$tpl->assign( DATA, $row_data);	
	$tpl->parse(ROW,"row");
	//顯示表格的內容
	$row_data = show_table_data($all_data,$file);		
	$tpl->assign( DATA, $row_data);
	fclose($file);
	//顯示下載表單的連結
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
		fwrite($file,$all_data[0][$i]."\t");//寫檔
	}
	//勾選名單 用紅色的字
	$row_data .= "<th>";
	$row_data .= "<div align='center'><font color='#FF0000' size='2'>".$all_data[0][4]."</fonr></div>";	
	$row_data .= "</th>";
	//選擇原因 用黃色的字
	$row_data .= "<th>";
	$row_data .= "<div align='center'><font color='#FFFF66' size='2'>".$all_data[0][5]."</fonr></div>";	
	$row_data .= "</th>";
	fwrite($file, "原因\t");//寫檔
	for($i=6; $i < count($all_data[0])-1; $i++){
		$row_data .= "<th>";
		$row_data .= "<div align='center'><font color='$font_color' size='2'>".$all_data[0][$i]['name']."(".$all_data[0][$i]['percentage']."%)</fonr></div>";	
		$row_data .= "</th>";
		fwrite($file, $all_data[0][$i]['name']."(".$all_data[0][$i]['percentage']."%)\t");//寫檔
	}	
	$row_data .= "<th>";
	$row_data .= "<div align='center'><font color='$font_color' size='2'>總成績</fonr></div>";	
	$row_data .= "</th>";		
	$row_data .="</tr>";
	fwrite($file,"總成績\n");//寫檔
	return $row_data;
}

function show_table_data($all_data,$file)
{
	global $stu_list;
	$row_data = '';
	for($i=1; $i < count($all_data);$i++){
		$tmp_data ='';
		//先決定tr的顏色
		if ( $bg_color == "#F0FFEE" )
			$bg_color = "#E6FFFC";
		else
			$bg_color = "#F0FFEE";
		$row_data .= "<tr bgcolor ='$bg_color'>";
		//先顯示 系所班級 學號 
		$row_data .= "<td><div align='center'><font size='2'>".$all_data[$i][0]."</font></div></td>";
		$row_data .= "<td><div align='center'><font size='2'>".$all_data[$i][1]."</font></div></td>";
		$row_data .= "<input type='hidden' name='student_id_".$i."' value='".$stu_list[$i-1]['a_id']."'>";
		$tmp_data .= $all_data[$i][0]."\t".$all_data[$i][1]."\t";
		//姓名要加上點了出現email
		$row_data .= "<td><div align='center'><font size='2'><A HREF=mailto:".$all_data[$i][3].">".$all_data[$i][2]."</a></font></div></td>";
		$tmp_data .= $all_data[$i][2]."\t";
		//在顯示 勾選名單 跟 原因
		$row_data .= "<td><div align='center'><font size='2'>".$all_data[$i][4]."</font></div></td>";
		$row_data .= "<td><div align='center'><font size='2'>".$all_data[$i][5]."</font></div></td>";
		$tmp_data .= $all_data[$i]['reason']."\t";
		//顯示所有成績
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
