<?PHP
/**
 *course中保持2學期的課程
 */
require 'fadmin.php';
include 'logger.php';
?>
<html>
<head>
<title>更新開課</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
</head>
<body background = "/images/img/bg.gif"><center>
<div><table>
    <tr> 
      <td> 
        <div>
          <font color=#000000>更新開課資料!</font>
        </div>
      </td>
    </tr>
</table>
<div id="progress">	　
</div>
<?PHP
if ( isset($PHPSESSID) && session_check_admin($PHPSESSID) ) {
	$success = true;
	echo "更新本學期一般生開課資料開始<br>";
	updateLog("更新本學期一般生開課資料開始",1);
	if( update_course("academic") != -1)
	{
		$success = false;
		echo "更新本學期一般生開課資料錯誤<br>";
		updateLog("更新本學期一般生開課資料錯誤",1);
	}
	
	/*echo "更新本學期專班生開課資料開始<br>";
	updateLog("更新本學期專班生開課資料開始",1);
	if( update_course("academic_gra") != -1)
	{
		$success = false;
		echo "更新本學期專班生開課資料錯誤<br>";
		updateLog("更新本學期專班生開課資料錯誤",1);
	}
	*/
	if($success == true){
		echo "課程資料更新完畢!!<br>";
		updateLog("課程資料更新完畢!!",1);
	}
	else{
		echo "課程資料更新失敗<br>";
		updateLog("課程資料更新失敗",1);
	}
	
	echo "<br><a href=../check_admin.php>回系統管理介面</a>";
}
else
	show_page( "index_ad.tpl", "你的權限錯誤，請重新登入!!");

//function update_course($sybase_name)
function update_course($db_name)
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;
	//select sybase 之一般開課
	/*if( !($cnx = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) )
	{	
		Error_handler( "在 sybase_connect 有錯誤發生" , $cnx );
	}
	$csd = @sybase_select_db($sybase_name, $cnx);	
	$cur = sybase_query("select a.year, a.term, a.unitname, a.cour_cd, a.grp, b.name, a.id from a31vcurriculum_tea a, a30vcourse_tea b where a.cour_cd=b.course_no" , $cnx);
	if(!$cur) 
	{  
		Error_handler( "在 sybase_exec 有錯誤發生( 沒有指標傳回 ) " , $cnx );  
	}
	*/
	$conn_string = "host=140.123.30.12 dbname=".$db_name." user=acauser password=!!acauser13";
	$cnx = pg_pconnect($conn_string) or die('資料庫沒有回應，請稍後再試');
	
	$cur = pg_query($cnx, "select a.year, a.term, a.unitname, a.cour_cd, a.grp, b.name, a.id from a31vcurriculum_tea a, a30vcourse_tea b where a.cour_cd=b.course_no and a.cour_cd='4154040'") or die('資料表不存在，請通知電算中心');
	
	
	// 連結mysql
	($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) or
		die('Not connected : ' . mysql_error());	//改為pconnect
	
	//計算進度用
	$count = 0;
	$temp = -1;
	//$total = sybase_num_rows($cur);
	$total = pg_num_rows($cur);
	
	if($total==0){
		echo "沒有課程!!<br>";
		updateLog("沒有課程!!",1);
		return -1;
	}
	//
	
	//測試log 開檔

	$testlog_fp = fopen("/home/study/logs/update_course.log", "a");
	
	$date_info = date("Y")."-".date("m")."-".date("d").": ".date("H").":".date("i").":".date("s");
	$testlog_content = $date_info."更新".$db_name."，總共".$total ."堂課程教師資料\n";
	fwrite($testlog_fp,$testlog_content);	
	//-----------特別紀錄的count 延用資料庫的數目
	$count_1_1 = 0;
	//-----------
	//while($array=sybase_fetch_array($cur))
	while($array=pg_fetch_array($cur, null, PGSQL_ASSOC))
	{	
		$count++;
		$cno = $array["cour_cd"]."_".$array["grp"];	 //cour_cd = course_no
		//$year = $array["year"]-1;
		echo "0.0".$cno."-".$array[year]."-".$array[term]."<br>";
		$q0 = "select c.a_id cid, u.id tid from course c, teach_course tc, user u";
		$q0.=" where u.a_id=tc.teacher_id and u.authorization = '1'  and tc.course_id=c.a_id and c.course_no='$cno' and tc.year=$array[year] and tc.term=$array[term]";
		echo "0.0".$q0."<br>";
		$rs0 = mysql_db_query($DB,$q0);		//去掉link 因為上面已用pconnect
		$num_rs0 = mysql_num_rows($rs0);
		//test log
		$testlog_content = "課號no: ".$cno."，";
		fwrite($testlog_fp,$testlog_content);	
		if($num_rs0 > 0) //1 該課號是否已存在且已建立為該學期之開課
		{	
			$cid = ""; //--課程流水號
			$inrow = false;		
			while($rows0 = mysql_fetch_array($rs0))
			{	
				//echo "已有教師$rows0[tid]<BR>";
				$cid = $rows0["cid"];
				//有教師未定情形時需特別處理 平台:id = undefined(a_id =17740)  sybase: id = 99999 
				if($rows0['tid']=="undefined"){
					if ("99999" == $array["id"]){
						$inrow=true;
					}
				}
				else{
					if ($rows0["tid"] == $array["id"]){
						$inrow=true;
					}
				}
			}
			//test log
			$testlog_content = $cid."課號已存在且為當學期開課，1.1更新課號，";
			fwrite($testlog_fp,$testlog_content);								
			update_coursename($cid, mb_convert_encoding($array["name"], "big5", "utf-8"), mb_convert_encoding($array["unitname"], "big5", "utf-8") );//更新課程名稱
			if(!$inrow) //建教師開課資料	1.2無教師資訊則開課(teach_course)
			{	
				//echo "新增教師$array[id]<BR>";
				//有教師未定情形時需特別處理 平台:id = undefined(a_id =17740)  sybase: id = 99999 
				//test log
				$testlog_content = "1.2加入教師id=$array[id]，";
				fwrite($testlog_fp,$testlog_content);								
				
				if($array["id"]=="99999"){
					add_teach_course("undefined", $cid, $array["year"], $array["term"]);
				}
				else{
					add_teach_course($array["id"], $cid, $array["year"], $array["term"]);
				}
				
			}		
		}//1 end
		else	//2 上上學期是否有該開課
		{	
			//102.05.27 by Jim
			$year = $array["year"]-1;
			echo "2.1".$cid."-".$year."-".$array[term]."<br>";
			$q1 = "select c.a_id cid, u.id tid from course c, teach_course tc, user u";
			$q1.=" where u.a_id=tc.teacher_id and u.authorization = '1' and tc.course_id=c.a_id and c.course_no='$cno' and tc.year=$year and tc.term=$array[term]";
			echo "2.1".$q1."<br>";
			$rs1 = mysql_db_query($DB,$q1); //去掉link 因為上面已用pconnect
			$num_rs1=mysql_num_rows($rs1);
			//test log
			$testlog_content = "課號不存在或不為當學期開課，";
			fwrite($testlog_fp,$testlog_content);
			if($num_rs1 > 0) //2.1 該課程為上上學期的開課
			{	
			
				//開課教師為多人
				$q_num = "select count(a.id) as num from a31vcurriculum_tea a where a.year='".$array['year']."' AND a.term='".$array['term']."' AND a.cour_cd='$array[cour_cd]' AND a.grp='$array[grp]'";
				/*$cur_num = sybase_query($q_num , $cnx);
				if(!$cur_num) 
				{  
					Error_handler( "在 sybase_exec 有錯誤發生( 沒有指標傳回 ) " , $cnx );  
				}
				$array_num = sybase_fetch_array($cur_num);
				*/
				$cur_num = pg_query($cnx, $q_num) or die('資料表不存在，請通知電算中心');
				$array_num = pg_fetch_array($cur_num, null, PGSQL_ASSOC);
				
				if($array_num['num']>1){
					echo "2.2<br>";
					//$cid = add_course($cno,$array["name"],$array["unitname"], $link);
					//$cid = add_course($cno,mb_convert_encoding($array["name"], "big5", "utf-8"),mb_convert_encoding($array["unitname"], "big5", "utf-8"), $link);
					//test log
					$testlog_content = "授課教師為多(".$array_num['num'].")人，2.1.0新增課程流水號".$cid."，";
					fwrite($testlog_fp,$testlog_content);
					//test log
					$testlog_content = "加入教師id=$array[id]，";
					fwrite($testlog_fp,$testlog_content);
					//教師未定情形時需特別處理 平台:id = undefined(a_id =17740)  sybase: id = 99999 
					
					if($array["id"]=="99999"){
						echo "2.2.1<br>";
						//add_teach_course("undefined", $cid, $array["year"], $array["term"]);
					}
					else{
						echo "2.2.2<br>";
						echo "2.2.2".$array["id"]."-".$cid."-".$array["year"]."-".$array["term"]."<br>";
						//add_teach_course($array["id"], $cid, $array["year"], $array["term"]);
					}
						
				}
				//
				else{
					$cid = ""; //--課程流水號
					$inrow = false;		
					while($rows1 = mysql_fetch_array($rs1))
					{  
						$cid = $rows1["cid"];
						
						//有教師未定情形時需特別處理 平台:id = undefined(a_id =17740)  sybase: id = 99999
						if($rows1['tid']=="undefined"){
							if ("99999" == $array["id"]){
								$inrow=true;
							}
						}
						else{
							if ($rows1["tid"] == $array["id"]){
								$inrow=true;
							}
						}
					}
					if($inrow)//2.1.1 若為相同教師則沿用課程流水號、課程資料庫
					{						
						//echo "課程ID=$cid,課程名稱=$array[name],課程group=$array[unitname]<BR>";exit;
						
						//加入判斷 流水號如果上學期已經有了則強制新增課程資料庫 上學期沒有才沿用
						$pre_year =0;	//上學年
						$pre_term =0;	//上學期
						if($array["term"] ==2){
							$pre_year = $array["year"];
							$pre_term = 1;
						}
						else{
							$pre_year = $array["year"]-1;
							$pre_term = 2;
						}						
						$q_force = "select count(teacher_id) as num from teach_course where year='$pre_year' and term='$pre_term' and course_id='$cid'";
						if ( $result_force = mysql_db_query( $DB, $q_force )  ) {				
							$array_force = mysql_fetch_array($result_force);
							if($array_force['num'] > 0 ){	//如果上學期有使用此流水號
								$testlog_content = "上學期此課程流水號".$cid."已使用，";
								//$cid = add_course($cno,$array["name"],$array["unitname"], $link);
								$cid = add_course($cno,mb_convert_encoding($array["name"], "big5", "utf-8"),mb_convert_encoding($array["unitname"], "big5", "utf-8"), $link);
								//test log
								$testlog_content .= "2.1.1.0新增課程流水號".$cid."，";
								fwrite($testlog_fp,$testlog_content);
							}
							else{
								//test log
								$testlog_content = "上上學期有開課且相同教師，2.1.1沿用課程流水號".$cid."，整理資料庫，更新課號，";
								fwrite($testlog_fp,$testlog_content);
								$count_1_1++ ;
								clean_up_course_db($cid, $array["year"], $array["term"]);//整理資料庫內容;
								//update_coursename($cid,$array["name"],$array["unitname"]);	
								update_coursename($cid,mb_convert_encoding($array["name"], "big5", "utf-8"),mb_convert_encoding($array["unitname"], "big5", "utf-8") );	
							}
						}
						else{
							$error = "資料庫讀取錯誤!!force";
							echo "$error<BR>";
							exit;
						}
					}//2.1.1 end
					else //2.1.2 若為不同教師,新增課程資料庫
					{
						//echo "課程ID=$cid,課程名稱=$array[name],課程group=$array[unitname],授課教師=$array[id]<BR>";exit;						
						//$cid = add_course($cno,$array["name"],$array["unitname"], $link);
						$cid = add_course($cno,mb_convert_encoding($array["name"], "big5", "utf-8"),mb_convert_encoding($array["unitname"], "big5", "utf-8"), $link);
						//test log
						$testlog_content = "上上學期有開課但不同教師，2.1.2新增課程流水號".$cid."，";
						fwrite($testlog_fp,$testlog_content);
					}//2.1.2 end
					//新增本學期開課記錄
					//有教師未定情形時需特別處理 平台:id = undefined(a_id =17740)  sybase: id = 99999
					//test log
					$testlog_content = "加入教師id=".$array['id']."，";
					fwrite($testlog_fp,$testlog_content); 
					
					if($array["id"]=="99999"){
						add_teach_course("undefined", $cid, $array["year"], $array["term"]);
					}
					else{
						add_teach_course($array["id"], $cid, $array["year"], $array["term"]);
					}
					
				}
							
			}//2.1 end 
			else	//2.2 不是上上學期的開課--新增課程流水號與課程資料庫
			{	
				
				//$cid = add_course($cno,$array["name"],$array["unitname"], $link);
				$cid = add_course($cno,mb_convert_encoding($array["name"], "big5", "utf-8"),mb_convert_encoding($array["unitname"], "big5", "utf-8"), $link);
				//test log
				$testlog_content = "上上學期無開課，2.2新增課程流水號".$cid."，加入教師id=".$array['id']."，";
				fwrite($testlog_fp,$testlog_content);
				//有教師未定情形時需特別處理 平台:id = undefined(a_id =17740)  sybase: id = 99999 
				
				if($array["id"]=="99999"){
					add_teach_course("undefined", $cid, $array["year"], $array["term"]);
				}
				else{
					add_teach_course($array["id"], $cid, $array["year"], $array["term"]);
				}
						
			}//2.2 end 
		}//2 end
		//計算進度用
		$p = (int)((100*$count)/$total);
		if($p>$temp){
			echo "<script language=\"JavaScript\" type=\"text/JavaScript\">
					document.all.progress.innerHTML = \"課程資料更新中，請稍侯 $p%\" ; </script>";
		}
		$temp = $p;
		flush();
		ob_flush();
		//test log
		$testlog_content = "結束。\n";
		fwrite($testlog_fp,$testlog_content);
	}//end while $array
	
	//test log
	$testlog_content = "總計沿用".$count_1_1."堂課程\n";
	fwrite($testlog_fp,$testlog_content);
	fclose($testlog_fp);
	//del_teach_course($cnx,$sybase_name); //刪除已取消之開課
	del_teach_course($cnx,$db_name); //刪除已取消之開課
	
	mysql_close($link);
	//sybase_close($cnx);
	pg_close($cnx);
	
	return -1;
}

//清除課程中與修課學生的相關資訊
function clean_up_course_db($cid,$year,$term)
{
	global $DB;
	
	//log 開檔
	$log_fp = fopen("/home/study/logs/update_course_del_".$year."_".$term.".log", "a");
	$log_content = "延用資料庫$cid\n執行清空資料庫$cid\n";
	fwrite($log_fp,$log_content);
	//
	$Q1 = "DELETE FROM handin_homework";
	$Q2 = "DELETE FROM take_exam";
	$Q3 = "DELETE FROM take_questionary";
	$Q4 = "DELETE FROM log";
	$Q5 = "DELETE FROM function_list2";
	for ( $i = 1 ; $i <= 5 ; $i++ ) {
		$Q = "Q$i";
		if ( !($result = mysql_db_query( $DB.$cid, $$Q ) ) ) {
			//log起來
			$log_content = "執行".$$Q."失敗\n";
			fwrite($log_fp,$log_content);
			//
		}
	}
	//清空課程下學生繳交的homework資料
	$Q11 = "select * from homework";
	$rs_11 = mysql_db_query($DB.$cid, $Q11);

	while($rows_11 = mysql_fetch_array($rs_11)){
		$dir = "../../".$cid."/homework/".$rows_11['a_id']."/";
		if ( is_dir($dir) ){
			$handle = opendir( $dir );
			while ( $file = readdir ($handle) ) {
				if (  $file != ".." && $file != "." && $file!="teacher" ) {
					deldir( $dir.$file );					
				}
			}
		}
		$file = $dir.".tar";
		if(is_file($file)){
			unlink($file);
		}
	}
	//log起來
	$log_content = "清空../../".$cid."/homework/下的所有學生檔案\n";
	fwrite($log_fp,$log_content);
	//
	
	//--------------沿用課程刪掉的資料 問卷的內容	
	$Q6 = "select * from questionary";
	$rs_6 = mysql_db_query($DB.$cid, $Q6);

	if($rs_6){
		while($rows_6 = mysql_fetch_array($rs_6)){
			$Q7 = "DELETE FROM questionary_".$rows_6['a_id'];
			if(!($rs_7 = mysql_db_query($DB.$cid, $Q7) ) ){
				echo "清空問卷統計失敗－$cid,$cno,$cname,$rows_6[a_id]<BR>";
				//log起來
				$log_content = "清空問卷".$rows_6['a_id']."統計失敗\n";
				updateLog("清空問卷".$rows_6['a_id']."統計失敗",1);
				fwrite($log_fp,$log_content);
				//
			}
		}
	}
	//--------------	
	fclose($log_fp);
}


function update_coursename($cid,$cname,$unitname)
{	
	global $DB;
	$q_temp = "select a_id from course_group where name='$unitname'";
	$rs_temp = mysql_db_query($DB,$q_temp);
	$rows_temp = mysql_fetch_array($rs_temp);
	$gid = $rows_temp["a_id"];
	$fixed_name = addslashes($cname);
	$q_temp="update course set name='$fixed_name',group_id=$gid where a_id=$cid";
	$rs_temp=mysql_db_query($DB,$q_temp);
	if(!$rs_temp) {
		echo "更新課程名稱失敗－$cid,$cno,$cname";
		updateLog("更新課程名稱失敗－$cid,$cno,$cname",1);
	}
}

function add_teach_course($id,$cid,$year,$term)
{
	global $DB;
	$q_temp = "select a_id from user where id='$id'";
	$rs_temp = mysql_db_query($DB,$q_temp);
	$rows_temp = mysql_fetch_array($rs_temp);
	if(!$rows_temp)
	{	
		echo "開課教師不存在--".$id."--新增教師開課失敗!(課流水號$cid)學期$year,$term<br>";
		updateLog("開課教師不存在--".$id."--新增教師開課失敗!(課流水號$cid)學期$year,$term",1);
		//continue;
	}
	else
	{	
		$q_temp="insert into teach_course (teacher_id, course_id, year, term) values ( \"$rows_temp[a_id]\", \"$cid\", \"$year\", \"$term\")";
		if(!mysql_db_query($DB,$q_temp))
		{	
			echo $q_temp."<BR>";
			updateLog($q_temp,1);
			echo "新增教師開課失敗－教師流水號$rows_temp[a_id],課流水號$cid學期$year,$term<br>";
			updateLog("新增教師開課失敗－教師流水號$rows_temp[a_id],課流水號$cid學期$year,$term",1);
			//continue;
		}
	}
	//debug
	if($cid==0){
		echo "課程aid==0<BR>";
		updateLog("課程aid==0",1);
		exit;
	}
}
function add_course($course_no, $course_name, $course_unitname, $link) //沒事需確認資料表
{	
	global $DB;
	$Qn = "select a_id from course_group where name='$course_unitname'";
	if ( !($resultn = mysql_db_query( $DB, $Qn ) ) ) {
		$error = "資料庫讀取錯誤!!";
		echo "$error<BR>";
		return $error;
	}
	if ( !($arrayn = mysql_fetch_array($resultn) ) ) {
		$error = "$course_unitname 此課程類別不存在!!";
		echo "$error<BR>";
		updateLog("$error",1);
		return $error;
	}
	
	$group = $arrayn[a_id];
	$Q1 = "insert into course (group_id, course_no, name, schedule_unit ) values ('$group', '$course_no', '$course_name', '週')";

	if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
		$error = "資料庫寫入錯誤!!".$Q1;
		echo "$error<BR>";
		return $error;
	}
	
	$aid = mysql_insert_id();
	
	//可以註解的部份
	/*
	$Q2 = "insert into course_no (course_id, course_no ) values ('$aid', '$course_no')";
	if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) ) {
		$error = "資料庫寫入錯誤!!".$Q2;
		echo "$error<BR>";
		return $error;
	}
	*/
	//尚未確認
	/*20070214測試註解*/
	
	
	$Q1 = "CREATE DATABASE study$aid";
	//$Q2 = "CREATE DATABASE coop$aid";
	$Q2 = "grant all privileges on study$aid.* to study";
	//$Q4 = "grant all privileges on coop$aid.* to study";
	$Q3 = "flush privileges";
	$Q6 = "CREATE TABLE course_schedule ( day varchar(11) NOT NULL, idx tinyint(4) DEFAULT '0' NOT NULL, subject varchar(100) NOT NULL, mtime timestamp(14), PRIMARY KEY (idx))";
	$Q7 = "CREATE TABLE news ( a_id int(11) NOT NULL auto_increment, system tinyint(4) DEFAULT '0', begin_day date DEFAULT '0000-00-00' NOT NULL, end_day date DEFAULT '0000-00-00' NOT NULL, cycle date DEFAULT '0000-00-00' NOT NULL, week tinyint(4) DEFAULT '0' NOT NULL, important tinyint(4) DEFAULT '1' NOT NULL, handle char(1) DEFAULT '0' NOT NULL, subject varchar(100) NOT NULL, content text NOT NULL, mtime timestamp(14), PRIMARY KEY (a_id))";
	$Q8 = "CREATE TABLE log ( a_id int(10) unsigned NOT NULL auto_increment, user_id int(11) DEFAULT '0' NOT NULL, event_id tinyint(4) DEFAULT '0' NOT NULL, tag1 varchar(100), tag2 varchar(100), tag3 int(11), tag4 varchar(255), mtime timestamp(14), PRIMARY KEY (a_id), UNIQUE a_id (a_id), KEY a_id_2 (a_id))";
	//add CoreAbilities(128) field to homework, exam by chiefboy1230
	$Q9 = "CREATE TABLE homework ( a_id int(11) NOT NULL auto_increment, chap_num tinyint(3) unsigned DEFAULT '0' NOT NULL, name varchar(40), public char(1) NOT NULL default '0', question text, q_type varchar(5), answer text, ans_type varchar(5), percentage tinyint(4), late char(1) NOT NULL default '1', due date, mtime timestamp(14), CoreAbilities varchar(128), PRIMARY KEY (a_id) )";
	$Q10 = "CREATE TABLE handin_homework ( homework_id int(11) DEFAULT '0' NOT NULL, student_id int(11) DEFAULT '0' NOT NULL, work text, upload tinyint(4) DEFAULT '0', comment text, grade float, public char(1) DEFAULT '0' NOT NULL, handin_time date, mtime timestamp(14), PRIMARY KEY (homework_id, student_id))";
	$Q11 = "CREATE TABLE exam ( a_id int(11) NOT NULL auto_increment, chap_num tinyint(3) unsigned DEFAULT '0' NOT NULL, name varchar(40), is_online char(1) DEFAULT '1' NOT NULL, random char(1) DEFAULT '0' NOT NULL, beg_time timestamp(14), end_time timestamp(14), public char(1) DEFAULT '0' NOT NULL, percentage tinyint(4), mtime timestamp(14), CoreAbilities varchar(128), PRIMARY KEY (a_id))";
	$Q12 = "CREATE TABLE tiku ( a_id int(11) NOT NULL auto_increment, type tinyint(4) DEFAULT '1' NOT NULL, exam_id int(11) DEFAULT '0' NOT NULL, question text NOT NULL, selection1 text NOT NULL, selection2 text NOT NULL, selection3 text NOT NULL, selection4 text NOT NULL, ismultiple char(1) NOT NULL, answer text NOT NULL, grade tinyint(4) DEFAULT '0' NOT NULL, answer_desc text, question_media text, answer_media text, file_picture_type varchar(32) NOT NULL, file_av_type varchar(32) NOT NULL , mtime timestamp(14), PRIMARY KEY (a_id))";
	$Q13 = "CREATE TABLE take_exam ( exam_id int(11) DEFAULT '0' NOT NULL, student_id int(11) DEFAULT '0' NOT NULL, grade float, nonqa_grade float, public tinyint(3) DEFAULT '1' NOT NULL, mtime timestamp(14), PRIMARY KEY (exam_id, student_id))";
	$Q14 = "CREATE TABLE on_line ( a_id int(11) NOT NULL auto_increment, date date DEFAULT '0000-00-00' NOT NULL, subject varchar(50), link varchar(100), file varchar(25), rfile varchar(100), mtime timestamp(14), PRIMARY KEY (a_id))";
	$Q15 = "CREATE TABLE chap_title ( a_id int(10) unsigned NOT NULL auto_increment, chap_num tinyint(3) unsigned DEFAULT '0' NOT NULL, chap_title varchar(128) NOT NULL, sect_num tinyint(3) unsigned DEFAULT '0' NOT NULL, sect_title varchar(128) NOT NULL, PRIMARY KEY (a_id))";
	$Q16 = "CREATE TABLE discuss_info ( a_id mediumint(8) unsigned NOT NULL auto_increment, discuss_name varchar(100) NOT NULL, comment varchar(100) DEFAULT 'NULL', group_num tinyint(4) DEFAULT '0' NOT NULL, access tinyint(1) DEFAULT '0' NOT NULL, PRIMARY KEY (a_id), UNIQUE a_id (a_id))";
	$Q17 = "CREATE TABLE discuss_group ( a_id int(10) unsigned NOT NULL auto_increment, group_num tinyint(4) DEFAULT '0' NOT NULL, student_id varchar(12) NOT NULL, PRIMARY KEY (a_id), KEY a_id (a_id))";
	$Q18 = "CREATE TABLE discuss_group_map ( a_id int(11) NOT NULL auto_increment, discuss_id mediumint(8) NOT NULL default '0', student_id int(11) NOT NULL default '0', mtime timestamp(14) NOT NULL, PRIMARY KEY  (a_id) )";
	$Q19 = "CREATE TABLE discuss_subscribe ( a_id int(10) unsigned NOT NULL auto_increment, user_id varchar(12) NOT NULL, discuss_id smallint(5) unsigned DEFAULT '0' NOT NULL, PRIMARY KEY (a_id), KEY a_id (a_id))";
	$Q20 = "CREATE TABLE qa ( item_id int(11) NOT NULL, exam_id int(11) NOT NULL, student_id int(11) NOT NULL, question text, answer text, grade float NOT NULL, grade_limit float NOT NULL)";
	$Q21 = "CREATE TABLE qtiku ( a_id int(11) NOT NULL auto_increment, q_id int(11) NOT NULL default '0', block_id int(11) NOT NULL default '0', type tinyint(4) NOT NULL default '1', question text NOT NULL, selection1 text NOT NULL, selection2 text NOT NULL, selection3 text NOT NULL, selection4 text NOT NULL, selection5 text NOT NULL, note tinyint(4) default NULL, ismultiple char(1) NOT NULL default '', grade tinyint(4) NOT NULL default '0', question_desc text, mtime timestamp(14) NOT NULL, PRIMARY KEY (a_id))";
	$Q22 = "CREATE TABLE questionary ( a_id int(11) NOT NULL auto_increment, name varchar(40) default NULL, is_once tinyint(4) NOT NULL default '1', beg_time timestamp(14) NOT NULL, end_time timestamp(14) NOT NULL, public char(1) NOT NULL default '0', is_named tinyint(4) NOT NULL default '0', mtime timestamp(14) NOT NULL, PRIMARY KEY  (a_id))";
	$Q23 = "CREATE TABLE take_questionary ( q_id tinyint(4) NOT NULL default '0', student_id int(11) NOT NULL default '0', count tinyint(4) NOT NULL default '0', mtime timestamp(14) NOT NULL, PRIMARY KEY (q_id,student_id))";		
	
	/*
	$Q24 = "CREATE TABLE function_list ( u_id varchar(40) NOT NULL, news char(1) NOT NULL default '1', intro char(1) NOT NULL default '1', sched char(1) NOT NULL default '0', info char(1) NOT NULL default '1', tein char(1) NOT NULL default '1', officehr CHAR(1) NOT NULL DEFAULT '0',  core CHAR(1) NOT NULL DEFAULT '0', evaluate CHAR(1) NOT NULL DEFAULT '0',
			tgins char(1) NOT NULL default '1', tgdel char(1) NOT NULL default '1', tgmod char(1) NOT NULL default '1', tgquery char(1) NOT NULL default '1', warning CHAR(1) NOT NULL DEFAULT '0',
			upload char(1) NOT NULL default '1', editor char(1) NOT NULL default '1', online char(1) NOT NULL default '0', material char(1) NOT NULL default '1', import char(1) NOT NULL default '1', 
			create_work char(1) NOT NULL default '1', modify_work char(1) NOT NULL default '1', check_work char(1) NOT NULL default '1', 
			create_test char(1) NOT NULL default '1', modify_test char(1) NOT NULL default '1', 
			create_case char(1) NOT NULL default '0', mag_case char(1) NOT NULL default '0', check_case char(1) NOT NULL default '0', 
			create_qs char(1) NOT NULL default '0', modify_qs char(1) NOT NULL default '0', 
			chat char(1) NOT NULL default '0', discuss char(1) NOT NULL default '1', reservation CHAR(1) NOT NULL DEFAULT '0', recording CHAR(1) NOT NULL DEFAULT '0', talk_voc char(1) NOT NULL default '0', talk_int char(1) NOT NULL default '0', eboard char(1) NOT NULL default '0', 
			strank char(1) NOT NULL default '0', chrank char(1) NOT NULL default '0', sttrace char(1) NOT NULL default '0', complete char(1) NOT NULL default '0', rollbook char(1) NOT NULL default '1', eroll CHAR(1) NOT NULL DEFAULT '0',
			tsins char(1) NOT NULL default '1', tsdel char(1) NOT NULL default '0', tsmod char(1) NOT NULL default '1', tschg char(1) NOT NULL default '0', tsquery char(1) NOT NULL default '1', psswd char(1) NOT NULL default '0', PRIMARY KEY (u_id))";
	*/

	//chiefboy1230@20111122, 教學組要求變更預警系統預設值為1
	$Q24 = "CREATE TABLE function_list ( u_id varchar(40) NOT NULL, news char(1) NOT NULL default '1', intro char(1) NOT NULL default '1', sched char(1) NOT NULL default '0', info char(1) NOT NULL default '1', tein char(1) NOT NULL default '1', officehr CHAR(1) NOT NULL DEFAULT '0',  core CHAR(1) NOT NULL DEFAULT '0', evaluate CHAR(1) NOT NULL DEFAULT '0',
			tgins char(1) NOT NULL default '1', tgdel char(1) NOT NULL default '1', tgmod char(1) NOT NULL default '1', tgquery char(1) NOT NULL default '1', warning CHAR(1) NOT NULL DEFAULT '1',
			upload char(1) NOT NULL default '1', editor char(1) NOT NULL default '1', online char(1) NOT NULL default '0', material char(1) NOT NULL default '1', import char(1) NOT NULL default '1', 
			create_work char(1) NOT NULL default '1', modify_work char(1) NOT NULL default '1', check_work char(1) NOT NULL default '1', 
			create_test char(1) NOT NULL default '1', modify_test char(1) NOT NULL default '1', 
			create_case char(1) NOT NULL default '0', mag_case char(1) NOT NULL default '0', check_case char(1) NOT NULL default '0', 
			create_qs char(1) NOT NULL default '0', modify_qs char(1) NOT NULL default '0', 
			chat char(1) NOT NULL default '0', discuss char(1) NOT NULL default '1', reservation CHAR(1) NOT NULL DEFAULT '0', recording CHAR(1) NOT NULL DEFAULT '0', talk_voc char(1) NOT NULL default '0', talk_int char(1) NOT NULL default '0', eboard char(1) NOT NULL default '0', 
			strank char(1) NOT NULL default '0', chrank char(1) NOT NULL default '0', sttrace char(1) NOT NULL default '0', complete char(1) NOT NULL default '0', rollbook char(1) NOT NULL default '1', eroll CHAR(1) NOT NULL DEFAULT '0',
			tsins char(1) NOT NULL default '1', tsdel char(1) NOT NULL default '0', tsmod char(1) NOT NULL default '1', tschg char(1) NOT NULL default '0', tsquery char(1) NOT NULL default '1', psswd char(1) NOT NULL default '0', PRIMARY KEY (u_id))";


	$Q25 = "CREATE TABLE function_list2 ( u_id varchar(40) NOT NULL, news char(1) NOT NULL default '1',
			info char(1) NOT NULL default '1', sched char(1) NOT NULL default '0', sgquery char(1) NOT NULL default '1', ssquery char(1) NOT NULL default '0', email char(1) NOT NULL default '0',
			material char(1) NOT NULL default '1', online char(1) NOT NULL default '0',
			show_work char(1) NOT NULL default '1',show_test char(1) NOT NULL default '1', show_qs char(1) NOT NULL default '0', check_case char(1) NOT NULL default '0',
			chat char(1) NOT NULL default '0', discuss char(1) NOT NULL default '1', talk_voc char(1) NOT NULL default '0', talk_int char(1) NOT NULL default '0', eboard char(1) NOT NULL default '0',
			search char(1) NOT NULL default '0', stinfo char(1) NOT NULL default '0', psswd char(1) NOT NULL default '0', strank char(1) NOT NULL default '0', ssmodify char(1) NOT NULL default '1', PRIMARY KEY (u_id))";
	$Q26 = "CREATE TABLE roll_book ( roll_id int(10) unsigned NOT NULL default '0', user_id int(11) NOT NULL default '0', roll_date varchar(100), state tinyint(4), note varchar(100))";
	$Q27 = "CREATE TABLE discuss_list ( discuss_id mediumint(8) unsigned NOT NULL ,  chap_num tinyint(3) unsigned DEFAULT '0' NOT NULL)";
	
	//$Q50 = "CREATE TABLE coop ( a_id int(11) NOT NULL auto_increment, name varchar(40) default NULL, topic text, beg_time timestamp(14) NOT NULL, end_time timestamp(14) NOT NULL, public char(1) NOT NULL default '0', private char(1) NOT NULL default '0', percentage float default NULL, mtime timestamp(14) NOT NULL, PRIMARY KEY (a_id))";
	//$Q51 = "CREATE TABLE take_coop ( case_id int(11) NOT NULL default '0', student_id int(11) NOT NULL default '0', grade float default NULL, mtime timestamp(14) NOT NULL, PRIMARY KEY  (case_id,student_id))";

	//add user_profile、qa2 tables to db studyXXXXX by chiefboy1230
	$Q52 = "CREATE TABLE user_profile ( student_id varchar(255))";
        $Q53 = "CREATE TABLE qa2 ( tiku_a_id int(11) NOT NULL default '0', `exam_id` int(11) NOT NULL default '0', `student_id` int(11) NOT NULL default '0', `type` tinyint(4) NOT NULL default '1', `ismultiple` char(1) NOT NULL default '', `stu_ans_1` text NOT NULL, `stu_ans_2` text NOT NULL, `stu_ans_3` text NOT NULL, `stu_ans_4` text NOT NULL, `mtime` timestamp(14) NOT NULL, KEY `tiku_a_id` (`tiku_a_id`,`exam_id`,`student_id`))";



	$error = "資料庫建立錯誤";
	
	for ( $i = 1 ; $i <= 3 ; $i ++ ) {
		$Q = "Q$i";
		if ( !($result = mysql_query( $$Q , $link ) ) ) {
			$error = $error." ".$$Q;
			return $error;
		}
	}
	
	for ( $i = 6 ; $i <= 27 ; $i ++ ) {
		$Q = "Q$i";
		if ( !($result = mysql_db_query( $DB.$aid, $$Q ) ) ) {
			$error = $error." ".$$Q;
			return $error;
		}
	}
	/*
	for ( $i = 50 ; $i <= 51 ; $i ++ ) {
		$Q = "Q$i";
		if ( !($result = mysql_db_query( $DBC.$aid, $$Q ) ) ) {
			$error = $error." ".$$Q;
			return $error;
		}
	}
	*/
	
	//execute Q52、Q53 by chiefboy1230
        for ( $i = 52 ; $i <= 53 ; $i ++ ) {
                $Q = "Q$i";
                if ( !($result = mysql_db_query( $DB.$aid, $$Q ) ) ) {
                        $error = $error." $i";
                        return $error;
                }
        }


	mkdir ( "../../".$aid, 0771 );
	chmod ( "../../".$aid, 0771 );
	mkdir ( "/backup/".$aid, 0771 );
	chmod ( "/backup/".$aid, 0771 );
	mkdir ( "../../".$aid."/homework", 0771 );
	chmod ( "../../".$aid."/homework", 0771 );
	mkdir ( "../../".$aid."/homework/comment", 0771 );
	chmod ( "../../".$aid."/homework/comment", 0771 );
	mkdir ( "../../".$aid."/homework/upload", 0771 );
	chmod ( "../../".$aid."/homework/upload", 0771 );
	mkdir ( "../../".$aid."/on_line", 0771 );
	chmod ( "../../".$aid."/on_line", 0771 );
	mkdir ( "../../".$aid."/textbook", 0771 );
	chmod ( "../../".$aid."/textbook", 0771 );
	mkdir ( "../../".$aid."/textbook/misc", 0771 );
	chmod ( "../../".$aid."/textbook/misc", 0771 );
	mkdir ( "../../".$aid."/student_info", 0771 );
	chmod ( "../../".$aid."/student_info", 0771 );
	mkdir ( "../../".$aid."/board", 0771 );
	chmod ( "../../".$aid."/board", 0771 );
	mkdir ( "../../".$aid."/intro", 0771 );
	chmod ( "../../".$aid."/intro", 0771 );
	mkdir ( "../../".$aid."/coop", 0771 );
	chmod ( "../../".$aid."/coop", 0771 );
	mkdir ( "../../".$aid."/exam", 0771 );
	chmod ( "../../".$aid."/exam", 0771 );

	if ( $scorm == 1 ) {
		mkdir ( "../../".$aid."/scorm", 0771 );
		chmod ( "../../".$aid."/scorm", 0771 );
		$S1 = "CREATE TABLE lesson ( a_id int(11) NOT NULL auto_increment, lesson_id varchar(255) NOT NULL default '', location text, title text NOT NULL, parent_id varchar(255) NOT NULL default '', level tinyint(4) NOT NULL default '0', is_leaf tinyint(4) NOT NULL default '0', PRIMARY KEY  (a_id,lesson_id), KEY a_id (a_id), KEY lesson_id (lesson_id))";
		$S2 = "CREATE TABLE sco_register ( a_id int(11) NOT NULL auto_increment, sco_id varchar(255) NOT NULL default '0', parent_id varchar(255) NOT NULL default '0', lesson_id varchar(255) NOT NULL default '0', sequence int(11) NOT NULL default '0', prerequisites varchar(200) default NULL, sco_name text NOT NULL, location text NOT NULL, metadata text NOT NULL, data_mastery_score float default NULL, data_max_time_allowed varchar(255) default NULL, data_time_limit_action varchar(255) default NULL, launch_data text NOT NULL, comments_from_lms text, PRIMARY KEY  (a_id), KEY a_id (a_id) )";
		
		for ( $i = 1 ; $i <= 2 ; $i ++ ) {
			$S = "S$i";
			if ( !($result = mysql_db_query( $DB.$aid, $$S ) ) ) {
				$error = $error." $i";
				return $error;
			}
		}
	}
	
	return $aid;
}

//function del_teach_course($cnx, $sybase_name)
function del_teach_course($cnx, $db_name)
{
	global $DB;
	/*$csd = @sybase_select_db($sybase_name, $cnx);
	$cur = sybase_query("SELECT DISTINCT year, term FROM a31vcurriculum_tea" , $cnx);
	if(!$cur) 
	{ 
		Error_handler( "在 sybase_exec 有錯誤發生( 沒有指標傳回 ) " , $cnx );  
	}
	*/
	$cur = pg_query($cnx, "SELECT DISTINCT year, term FROM a31vcurriculum_tea") or die('資料表不存在，請通知電算中心');
	
	//test_log
	$testlog_fp = fopen("/home/study/logs/update_course.log", "a");
	$testlog_content = "刪除課程教師資料\n";
	fwrite($testlog_fp,$testlog_content);	
	$count_t = 0;
	//while($array=sybase_fetch_array($cur))
	while($array=pg_fetch_array($cur, null, PGSQL_ASSOC))
	{	
		//if($sybase_name=="academic"){
		if($db_name=="academic"){
			$qt = "SELECT tc.teacher_id taid, u.id tid, c.course_no cno, c.a_id cid, tc.year year, tc.term term FROM teach_course tc, user u, course c WHERE tc.teacher_id=u.a_id AND c.a_id=tc.course_id AND u.authorization='1' AND year='$array[year]' AND term='$array[term]' AND !(c.course_no like '___A%' or c.course_no like '___B%' or c.course_no like '___C%' or c.course_no like '___D%')";
		}
		else{
			$qt = "SELECT tc.teacher_id taid, u.id tid, c.course_no cno, c.a_id cid, tc.year year, tc.term term FROM teach_course tc, user u, course c WHERE tc.teacher_id=u.a_id AND c.a_id=tc.course_id AND u.authorization='1' AND year='$array[year]' AND term='$array[term]' AND (c.course_no like '___A%' or c.course_no like '___B%' or c.course_no like '___C%' or c.course_no like '___D%')";
		}		
		$result = mysql_db_query($DB, $qt);	
		if ( !$result ) {
			$error = "資料庫讀取錯誤!!".$qt;
			echo "$error<BR>";
			return $error;
		}
		//計算進度用
		$count = 0;
		$temp = -1;
		$total = mysql_num_rows($result);
		if($total==0){
			echo "沒有教師課程關係!!<br>";
			updateLog("沒有教師課程關係!!",1);
			return -1;
		}
		//
				
		while($row = mysql_fetch_array($result)){
			$count++;
			$row1=explode("_", $row[cno]);
			$cid = $row1[0];
			$gid = $row1[1];
			/*有教師未定情形時需特別處理 平台:id = undefined(a_id =17740)  sybase: id = 99999 */
			if($row["tid"]=="undefined"){
				$qtno = "SELECT * FROM a31vcurriculum_tea WHERE year='$row[year]' AND term='$row[term]' AND cour_cd='$cid' AND grp='$gid' AND id='99999'";
			}
			else{
				$qtno = "SELECT * FROM a31vcurriculum_tea WHERE year='$row[year]' AND term='$row[term]' AND cour_cd='$cid' AND grp='$gid' AND id='$row[tid]'";
			}
						
			/*$cur1 = sybase_query($qtno, $cnx);
			if(!$cur1) 
			{  
				Error_handler( "在 sybase_exec 有錯誤發生( 沒有指標傳回 ) " , $cnx );  
			}
			*/
			$cur1 = pg_query($cnx, $qtno) or die('資料表不存在，請通知電算中心');
			
			//$num = sybase_num_rows($cur1);
			$num = pg_num_rows($cur1);
			if($num <= 0) //沒有開課資料 -> 刪除teach_course中的教師
			{
				$del_tc = "DELETE FROM teach_course where course_id='$row[cid]' AND teacher_id='$row[taid]' AND year='$row[year]' AND term='$row[term]'";				
				if( !($resul_dltc = mysql_db_query($DB, $del_tc)) )
				{
					echo "資料庫刪除錯誤!!$del_tc<br>";
					continue;
				}
				else{
					$count_t++;
					$testlog_content = "刪除課程 ".$row[cid]." 與教師 ".$row["tid"]."資料\n";
					fwrite($testlog_fp,$testlog_content);
				}
			}
			//計算進度用
			$p = (int)((100*$count)/$total);
			if($p>$temp){
				echo "<script language=\"JavaScript\" type=\"text/JavaScript\">
						document.all.progress.innerHTML = \"刪除無效的教師課程資料中，請稍侯 $p%\" ; </script>";
			}
			$temp = $p;
			flush();
			ob_flush();	 
		}		
	}
	//test log
	$testlog_content = "總計刪除".$count_t."堂課程與教師資料\n";
	fwrite($testlog_fp,$testlog_content);
	fclose($testlog_fp);
}

?>
</div>
</center>
</body>
</html>
