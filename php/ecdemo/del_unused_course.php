<?PHP
/*
 清除一年以外的課程
 刪除course中的該筆紀錄、drop db、remove htdocs下該課號之檔案目錄
*/
require 'fadmin.php';
?>
<html>
<head>
<title>更新開課</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
</head>
<body background = "/images/img/bg.gif"><center>
<div id="progress">	　
</div>

<?PHP
if ( isset($PHPSESSID) && session_check_admin($PHPSESSID) ) {
	$success = true;
	echo "清除無效課程資料開始<br>";
	if( del_unused_course() != -1)
	{
		$success = false;
		echo "清除無效課程資料錯誤<br>";
	}	
	
	if($success == true){
		echo "清除無效課程資料完畢!!<br>";
	}
	else{
		echo "清除無效課程資料失敗<br>";
	}
	
	echo "<br><a href=../check_admin.php>回系統管理介面</a>";
}
else
	show_page( "index_ad.tpl", "你的權限錯誤，請重新登入!!");

function del_unused_course(){
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;
	
	// 連結mysql
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$error = "資料庫連結錯誤!!";
		return $error;
	}
	
	//抓出本學期的學年學期資料
	$qs1 = "SELECT DISTINCT year, term FROM teach_course ORDER BY year DESC, term DESC";
	if ($result1 = mysql_db_query($DB, $qs1)){
		if(($row1 = mysql_fetch_array($result1))==0){
			$error = "學期資料不存在!!<BR>";
			return $error;
		}
	}
	else{
		$error = "mysql資料庫讀取錯誤1!!<br>";
		return $error;
	}
	$now_year = $row1['year'];
	$now_term = $row1['term'];
	$pre_year = "";
	$pre_term = "";;
	if($now_term =="1")
	{
		$pre_year = $now_year - 1;
		$pre_term = 2;
	}
	else{
		$pre_year = $now_year;
		$pre_term = 1;
	}
	
	
	
	
	//log 開檔
	$log_fp = fopen("/home/study/logs/del_unused_course_".$now_year."_".$now_term.".log", "a");
	$count = 0;
	//
	
	//抓出所有course資料
	
	$qs2 = "SELECT * FROM course order by a_id";
	if ($result2 = mysql_db_query($DB, $qs2)){
		//計算進度用
		$realcount=0;
		$temp = -1;
		$total = mysql_num_rows($result2);
		echo "總共 $total 門課<br>";
		ob_end_flush();
		ob_implicit_flush(1);
		//
		while($row2 = mysql_fetch_array($result2)){
			//計算進度用
			$realcount++;	
			$p = number_format((100*$realcount)/$total);
			if($p>$temp){
				echo "<script language=\"JavaScript\" type=\"text/JavaScript\">
						document.all.progress.innerHTML = \"確認及更新中，請稍侯 $p%\" ; </script>";
			}
			$temp = $p;
			//
			$qs3 = "SELECT count(teacher_id) as teach_num FROM teach_course where course_id='$row2[a_id]' AND ((year='$now_year' AND term='$now_term') OR (year='$pre_year' AND term='$pre_term'))";
			if ($result3 = mysql_db_query($DB, $qs3)){
				$row3 = mysql_fetch_array($result3);
				//如果1年中的資料找不到，則刪除課程
				if( $row3["teach_num"]==0){
					//log起來
					$count++;
					echo "刪除課程:".$row2['a_id']."<BR>";
					$log_content = "刪除課程:".$row2['a_id']."\n";
					fwrite($log_fp, $log_content);
					//
					del_course ($row2['a_id'],$log_fp );
				}
			}
			else{
				$error = "mysql資料庫讀取錯誤3!!<br>";
				return $error;
			}
		}			
	}
	else{
		$error = "mysql資料庫讀取錯誤2!!<br>";
		return $error;
	}
	
	//關LOG檔
	
	echo "總共刪除:".$count."個資料庫<BR>";
	$log_content = "總共刪除:".$count."個資料庫\n";
	fwrite($log_fp, $log_content);
	fclose($log_fp);
	
	return -1;
}
	
function del_course ($course_aid,$log_fp ) {
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	
	$Q1 = "delete from course where a_id = '$course_aid'";
//	$Q2 = "delete from teach_course where course_id = '$course_aid'";
	$Q3 = "drop database study$course_aid";
	$Q4 = "drop database coop$course_aid";
	
	$error = -1;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$error = "資料庫連結錯誤1!!";
	}
	for ( $i = 1 ; $i <= 1 ; $i ++ ) {
		$Q = "Q$i";
		$log_content = "執行".$$Q."\n";
		fwrite($log_fp, $log_content);

		if ( !( mysql_db_query( $DB, $$Q ) ) ) {
			$error = "$error - 資料庫刪除錯誤2$i!!";
		}

	}
	//不刪掉使用者
/*		
	$U1 = "select student_id from take_course where course_id = '$course_aid'";
	$U2 = "delete from take_course where course_id = '$course_aid'";
	if ( !($result1 = mysql_db_query( $DB, $U1 ) ) ) {
		$error = "$error - 資料庫讀取錯誤3!!";
	}
	if ( !($result = mysql_db_query( $DB, $U2 ) ) ) {
		$error = "$error - 資料庫刪除錯誤4!!";
	}
	while ( $row1 = mysql_fetch_array( $result1 ) ) {
		$U3 = "select * from take_course where student_id = '".$row1['student_id']."'";
		if ( $result = mysql_db_query( $DB, $U3 ) ) {
			if ( mysql_num_rows( $result ) == 0 ) {
				$U4 = "delete from user where a_id = '".$row1['student_id']."'";
				$U5 = "delete from log where user_id = '".$row1['student_id']."'";
				$U6 = "delete from gbfriend where my_id = '".$row1['student_id']."' or friend_id='".$row1['student_id']."'";
				if ( !( mysql_db_query( $DB, $U4 ) ) ) {
					$error = "$error - 資料庫刪除錯誤5!!";
				}
				if ( !( mysql_db_query( $DB, $U5 ) ) ) {
					$error = "$error - 資料庫刪除錯誤6!!";
				}
				if ( !( mysql_db_query( $DB, $U6 ) ) ) {
					$error = "$error - 資料庫刪除錯誤6!!";
				}
			}
		}
		else
			$error = "$error - 資料庫刪除錯誤7!!";
	}
*/	
	$log_content = "執行".$Q3."\n";
	fwrite($log_fp, $log_content);	

	if ( !( mysql_query( $Q3 , $link ) ) ) {
			$error = "$error - 資料庫刪除錯誤8!!";
	}

	//不刪合作學習資料庫，因為也沒建^^"
/*
	if ( !( mysql_query( $Q4 , $link ) ) ) {
			$error = "$error - 資料庫刪除錯誤9!!";
	}
*/	
	$target = "../../".$course_aid;
	$cmd ="rm -rfv ".$target;
	$output = shell_exec($cmd);
	$log_content = "刪除目錄".$target.": ".$output."\n";
	fwrite($log_fp, $log_content);	

	$target = "/backup/".$course_aid;
	$cmd ="rm -rfv ".$target;
	$output = shell_exec($cmd);
	$log_content = "刪除目錄".$target.": ".$output."\n";
	fwrite($log_fp, $log_content);	

	$log_content ="\n";
	fwrite($log_fp, $log_content);	
	return $error;
}

?>
</div>
</center>
</body>
</html>