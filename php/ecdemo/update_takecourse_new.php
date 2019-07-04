<?PHP
	require 'fadmin.php';
	include 'logger.php';
?>
<html>
<head>
<title>更新選課資料</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
</head>
<body background = "/images/img/bg.gif"><center>
<div><table>
    <tr> 
      <td> 
        <div>
          <font color=#000000>更新選課資料!</font>
        </div>
      </td>
    </tr>
</table>
<div id="progress">	　
</div>
<?php
	if ( isset($PHPSESSID) && session_check_admin($PHPSESSID) ) {
	
		if(($error = del_old_takecourse()) == -1){
			echo "前一年修課資料刪除完畢!!<br>";
			updateLog("前一年修課資料刪除完畢!!",2);
		}
		else{
			echo "$error<br>";
		}
	
		echo "一般生修課資料開始更新<BR>";
		updateLog("一般生修課資料開始更新",2);
		if(($error = update_takecourse("academic")) == -1){
			echo "一般生修課資料更新完畢!!<br>";
			updateLog("一般生修課資料更新完畢!!",2);
		}
		else{
			echo "$error<br>";
		}
		
		echo "專班修課資料開始更新<BR>";
		updateLog("專班修課資料開始更新",2);
		if(($error = update_takecourse("academic_gra")) == -1){
			echo "專班修課資料更新完畢!!<br>";
			updateLog("專班修課資料更新完畢!!",2);
		}
		else{
			echo "$error<br>";
		}
		
		echo "一般生修課資料開始清除<BR>";
		updateLog("一般生修課資料開始清除",2);
		if(($error = del_now_takecourse("academic")) == -1){
			echo "一般生未選上課程清除完畢!!<br>";
			updateLog("一般生未選上課程清除完畢!!",2);
		}
		else{
			echo "$error<br>";
		}
	
		echo "專班修課資料開始清除<BR>";
		updateLog("專班修課資料開始清除",2);
		if(($error = del_now_takecourse("academic_gra")) == -1){
			echo "專班未選上課程清除完畢!!<br>";
			updateLog("專班未選上課程清除完畢!!",2);
		}
		else{
			echo "$error<br>";
		}
		
		echo "<br><a href=../check_admin.php>回系統管理介面</a>";
	}
	else
		show_page( "index_ad.tpl", "你的權限錯誤，請重新登入!!");

//刪除一年前的選課資料
function del_old_takecourse()
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;	
	// 連結sybase 從sybase取得新學期
	if( !($cnx = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) ){  
		Error_handler( "在 sybase_connect 有錯誤發生" , $cnx );  
	}
	$csd = @sybase_select_db("academic", $cnx);
	
	$cur = sybase_query("select DISTINCT year, term from a31v_sel_class_tea", $cnx);
	if(!$cur) {  
		Error_handler( "在 sybase_exec 有錯誤發生( 沒有指標傳回 ) " , $cnx );  
	}
	if($array=sybase_fetch_array($cur))
	{
		$year = $array['year'] - 1;
		$term = $array['term'];
	}
	else{
		$error = "學期資料不存在!!<BR>";
		return $error;
	}
	
	/*	
	// 連結mysql
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$error = "資料庫連結錯誤!!";
		return $error;
	}
	
	//抓出本學期的學年學期資料
	$qs1 = "SELECT * FROM this_semester";
	if ($result1 = mysql_db_query($DB, $qs1)){
		if(($row1 = mysql_fetch_array($result1))==0){
			$error = "本學期資料不存在!!<BR>";
			return $error;
		}
	}
	else{
		$error = "mysql資料庫讀取錯誤1!!<br>";
		return $error;
	}
	//要刪除的為前一學年的選課資料 (前一學年 = 上上學期)
	$year = $row1['year'] - 1;
	$term = $row1['term'];
	*/
	
	$qs2 = "DELETE FROM take_course WHERE year='$year' AND term='$term'";
	$result2 = mysql_db_query($DB, $qs2);
	if (!$result2 ){
		$error = "刪除前一學年選課資料失敗!!$qs2<BR>";
		updateLog("刪除前一學年選課資料失敗!!$qs2",2);
		return $error;
	}
/*
	//刪除學生資料	
	$qs2 = "SELECT * FROM take_course WHERE year='$year' AND term='$term'";
	$result2 = mysql_db_query($DB, $qs2);
	if ($result2 ){
		//顯示進度start
		$count = 0;
		$temp = -1;
		$total = mysql_num_rows($result2);
		ob_end_flush();
		ob_implicit_flush(1);
		//顯示進度end
		while($array2=mysql_fetch_array($result2))
		{
			//顯示進度start
			$count++;
			$p = number_format((100*$count)/$total);
			if($p>$temp){
				echo "<script language=\"JavaScript\" type=\"text/JavaScript\">
						document.all.progress.innerHTML = \"$class 上學期選課學生資料清除中，請稍侯 $p%\" ; </script>";
			}
			$temp = $p;
			//顯示進度end
			delete_stu($array2[student_id], $array2[course_id]);
		}		
	}
	else{
		$error = "mysql資料庫讀取錯誤2!!<BR>";
		return "$error $qs2<BR>";
	}
	//-------
*/	
	sybase_close( $cnx);
	return -1;
}

function update_takecourse($db_name)
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;	
	
	// 連結sybase
	if( !($cnx = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) ){  
		Error_handler( "在 sybase_connect 有錯誤發生" , $cnx );  
	}
	$csd = @sybase_select_db($db_name, $cnx);
	
	$cur = sybase_query("select * from a31v_sel_class_tea", $cnx);
	if(!$cur) {  
		Error_handler( "在 sybase_exec 有錯誤發生( 沒有指標傳回 ) " , $cnx );  
	}
	
	// 連結mysql
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$error = "資料庫連結錯誤!!";
		return $error;
	}
	
	$count = 0;
	$temp = -1;
	$total = sybase_num_rows($cur);
	ob_end_flush();
	ob_implicit_flush(1);
	while($array=sybase_fetch_array($cur)){
		$count++;
		$p = number_format((100*$count)/$total,1);
		//$p = (int)((100*$count)/$total);
		if($p>$temp){
			echo "<script language=\"JavaScript\" type=\"text/JavaScript\">
					document.all.progress.innerHTML = \"修課資料更新中，請稍侯 $p%\" ; </script>";
		}
		$temp = $p;
		
		//判斷除了課號外還要加上teach_course的資訊
		$cno = $array[cour_cd]."_".$array[grp];
		$Qs1 = "select c.a_id, c.group_id from course c , teach_course tec where c.course_no='$cno' AND c.a_id=tec.course_id AND tec.year='$array[year]' AND tec.term='$array[term]' ";
		if ($result1 = mysql_db_query($DB,$Qs1)){
			if(($row1 = mysql_fetch_array($result1))==0){
				$error = "此課程不存在!!";
				//echo "$error $array[cour_cd]";
				continue;
			}
		}
		else{
			$error = "mysql資料庫讀取錯誤!!";
			return "$error $Qs1<br>";
		}
		
		$Qs2 = "select a_id from user where id='$array[std_no]'";
		if ($result2 = mysql_db_query($DB,$Qs2)){
			if(($row2 = mysql_fetch_array($result2))==0){
				$error = "此學生不存在!!";
				echo "$error $array[std_no]<br>";
				updateLog("$error $array[std_no]",2);
				continue;
			}
		}
		else{
			$error = "mysql資料庫讀取錯誤!!";
			return "$error $Qs2<br>";
		}
/*		
		$Qs3 = "select group_id from course where a_id='$row1[a_id]'";
		if ($result3 = mysql_db_query($DB,$Qs3)){
			$row3 = mysql_fetch_array($result3);
		}
		else{
			$error = "mysql資料庫讀取錯誤!!";
			return "$error $Qs3<br>";
		}
*/		
		//----devon---如果學生的credit是0且在選課系統裡有選課資料，那就update credit=1-------------------------------------------
		//加入學年的判斷來確定選取的是當學年選課資料
		$Q4 = "select * from take_course where student_id='".$row2[a_id]."' and course_id='".$row1[a_id]."' and credit=0 AND year='$array[year]' AND term='$array[term]'";

		if($result4 = mysql_db_query($DB, $Q4))
		{
			if(mysql_num_rows($result4) == 1)
			{
				echo "$Q4<br>";
				//加入學年的判斷來確定選取的是當學年選課資料
				mysql_db_query($DB, "update take_course set credit='1' where student_id='".$row2[a_id]."' and course_id='".$row1[a_id]."' AND year='$array[year]' AND term='$array[term]'");
			}
		}
	
		$Qins = "insert into take_course (course_id, student_id, group_id, validated, credit, year, term) values ('$row1[a_id]', '$row2[a_id]', '$row1[group_id]', '1', '1', '$array[year]', '$array[term]')";
		//echo "$Qins <br>";
		
		if(!($resulti = mysql_db_query($DB,$Qins))){
			$error = "mysql資料庫寫入錯誤!!$Qins";
			//echo $error." <BR>";
			//return "$error $Qins<br>";
			continue;
		}
	}

	sybase_close( $cnx);
	return -1;
}

function del_now_takecourse($SDB)
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;
	// 連結mysql
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) )
	{
		$error = "資料庫連結錯誤!!";
		return $error;
	}
	
	// 連結選課系統資料庫
	if( !($cnx = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) )
	{  
		Error_handler( "在 sybase_connect 有錯誤發生" , $cnx );  
	}
	$csd = @sybase_select_db($SDB, $cnx);
	
	
	//從sybase取得新學期	
	$cur = sybase_query("select DISTINCT year, term from a31v_sel_class_tea", $cnx);
	if(!$cur) {  
		Error_handler( "在 sybase_exec 有錯誤發生( 沒有指標傳回 ) " , $cnx );  
	}
	if($array=sybase_fetch_array($cur))
	{
		$year = $array['year'];
		$term = $array['term'];
	}
	else{
		$error = "學期資料不存在!!<BR>";
		updateLog("學期資料不存在!!",2);
		return $error;
	}
	//
	
	// 取出課程代碼
	if($SDB == "academic_gra")
		$Qs1 = "select c.course_no, c.a_id from course c, teach_course t where t.year = '$year' and t.term = '$term' and c.a_id = t.course_id and (c.course_no like '___A%' or c.course_no like '___B%' or c.course_no like '___C%' or c.course_no like '___D%')";
	else if($SDB == "academic"){
		$Qs1 = "select c.course_no, c.a_id from course c, teach_course t where t.year = '$year' and t.term = '$term' and c.a_id = t.course_id and !(c.course_no like '___A%' or c.course_no like '___B%' or c.course_no like '___C%' or c.course_no like '___D%')";
	}
	if(!($result1 = mysql_db_query($DB,$Qs1))){
		$error = "mysql資料庫讀取錯誤!!";
		return "$error $Qs1<br>";
	}

	$count = 0;
	$temp = -1;
	$total = mysql_num_rows($result1);
	ob_end_flush();
	ob_implicit_flush(1);
	while ($row1 = mysql_fetch_array($result1))
	{
		$count++;
		$p = number_format((100*$count)/$total);
		if($p>$temp){
			echo "<script language=\"JavaScript\" type=\"text/JavaScript\">
					document.all.progress.innerHTML = \"$class 已退選課程清除中，請稍侯 $p%\" ; </script>";
		}
		$temp = $p;
		
		$course_no = explode("_",$row1['course_no']);
		$cour_cd = $course_no[0];
		$grp = $course_no[1];
		$course_id = $row1['a_id'];

		// 從教學系統取出選課資料
		$Qs5 = "select student_id, id, combine_course_id from user u,take_course tc where course_id = '$course_id' and u.a_id=tc.student_id and tc.credit=1 AND tc.year='$year' AND tc.term = '$term' ";
		if ($result5 = mysql_db_query($DB,$Qs5)){
			while($row5 = mysql_fetch_array($result5)){
				$std_id = $row5['student_id'];
				$std_no = $row5['id'];
				
				//取出合併的課程資料
				$combine_course = $row5['combine_course_id'];
				
				if($combine_course == -1){
					$cour_cd_comb = -1;
					$grp_comb = -1;
					$course_id_comb = -1;
				}
				else{
					$Qs_comb = "select * from course where a_id = '".$combine_course."'";
					if(!($result_comb = mysql_db_query($DB,$Qs_comb))){
						$error = "mysql資料庫讀取錯誤!!";
						return "$error $Qs_comb<br>";
					}
					$row_comb = mysql_fetch_array($result_comb);
					$course_no_comb = explode("_",$row_comb ['course_no']);
					$cour_cd_comb = $course_no_comb[0];
					$grp_comb = $course_no_comb[1];
					$course_id_comb = $row_comb['a_id'];
				}
				//
				
				// 從選課系統資料庫中取出該名學生在本門課的選課資料
				$Qs6 = "select std_no from a31v_sel_class_tea where cour_cd = '$cour_cd' and grp = '$grp' and std_no = '$std_no'";
				$cur = sybase_query($Qs6, $cnx);
				if(!$cur) {  
					Error_handler( "在 sybase_exec 有錯誤發生( 沒有指標傳回 ) " , $cnx );  
				}
				// 若無資料則刪除選課資料及相關紀錄
				if(($array=sybase_fetch_array($cur))==0){
					//echo $SDB." - ".$std_no." - ".$cour_cd.$grp."<br>";
					//若併班上課也無資料才刪除
					$Qs7 = "select std_no from a31v_sel_class_tea where cour_cd = '$cour_cd_comb' and grp = '$grp_comb' and std_no = '$std_no'";
					$cur = sybase_query($Qs7, $cnx);
					if(!$cur) {  
						Error_handler( "在 sybase_exec 有錯誤發生( 沒有指標傳回 ) " , $cnx );  
					}
					// 若無資料才刪除選課資料及相關紀錄
					if(($array=sybase_fetch_array($cur))==0){
					//
						delete_stu($std_id, $course_id, $year, $term);
						echo "delete: $std_no<br>";
						updateLog("delete: $std_no",2);
					}
				}
			}
		}
		else{
			$error = "mysql資料庫讀取錯誤!!";
			return "$error $Qs5<br>";
		}
		
		if($SDB == "academic")
			$class = "一般生";
		else
			$class = "專班";
			

	}

	sybase_close( $cnx);
	return -1;		
}

// 刪除選課資料及相關紀錄
function delete_stu ($key,$course_id, $year, $term) {
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;
	
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$message = "資料庫連結錯誤!!";
		show_page_d ( $message );
		return;
	}

	//取得本學期資料
/*	$qs_sem = "SELECT * FROM this_semester";
	if ($result_sem = mysql_db_query($DB, $qs_sem )){
		if(($row_sem = mysql_fetch_array($result_sem))==0){
			$error = "本學期資料不存在!!<BR>";
			return $error;
		}
	}
	else{
		$error = "mysql資料庫讀取錯誤1!!<br>";
		return $error;
	
	}
	$year = $row_sem['year'];
	$term = $row_sem['term'];	
*/	//

//linsy@20120412, 原為刪除學生在課程中的所有資料及修課關連，現暫時改為僅刪除修課關係。
/*
	$Q1 = "Select student_id From take_course Where student_id='$key' AND year='$year' AND term='$term'";
	if ( !($resultOBJ = mysql_db_query( $DB, $Q1 ) ) ) {
		$message = "資料庫讀取錯誤!!";
		show_page_d ( $message );
		return;
	}
	$Q2 = "Select id From user Where a_id='$key'";
	if ( !($resultOBJid = mysql_db_query( $DB, $Q2 ) ) ) {
		$message = "資料庫讀取錯誤!!";
		show_page_d ( $message );
		return;
	}
	$row_id = mysql_fetch_array( $resultOBJid );
	mysql_db_query( $DB.$course_id, "Delete From handin_homework Where student_id='$key'" );
	$resultOBJ2 = mysql_db_query( $DB.$course_id, "Select a_id From homework");
	if($resultOBJ2){
		while($row = mysql_fetch_array ( $resultOBJ2 )) {
			$target = "../../$course_id/homework/".$row['a_id']."/".$row_id['id'];
			if ( is_dir($target) )
				deldir ( $target );
		}
	}
	mysql_db_query( $DB.$course_id, "Delete From take_exam Where student_id='$key'");
	//----------刪除問卷------------
	mysql_db_query( $DB.$course_id, "Delete From take_questionary Where student_id='$key'");
	//-----------------------
	mysql_db_query( $DB.$course_id, "Delete From log Where user_id='$key'");
*/
	mysql_db_query( $DB, "Delete From take_course Where student_id='$key' and course_id = '$course_id' AND year='$year' AND term='$term'");
	
	
	/*
	//coop
	$resultcoop = mysql_db_query( $DBC.$course_id, "Select a_id From coop");
	while($row_coop = mysql_fetch_array ( $resultcoop )) {
		mysql_db_query( $DBC.$course_id, "Delete From coop_".$row_coop['a_id']."_group Where student_id='".$row_id['id']."'");
		mysql_db_query( $DBC.$course_id, "Delete From discuss_".$row_coop['a_id']."_subscribe Where user_id='".$row_id['id']."'");
		mysql_db_query( $DBC.$course_id, "Delete From grade_".$row_coop['a_id']." Where give_id='$key' or gived_id ='$key'");
		mysql_db_query( $DBC.$course_id, "Delete From guestbook_".$row_coop['a_id']." Where user_id='$key'");
		mysql_db_query( $DBC.$course_id, "Delete From log_".$row_coop['a_id']." Where user_id='$key'");
		mysql_db_query( $DBC.$course_id, "Delete From note_".$row_coop['a_id']." Where student_id='$key'");
		mysql_db_query( $DBC.$course_id, "Delete From share_".$row_coop['a_id']." Where student_id='$key'");
		mysql_db_query( $DBC.$course_id, "Delete take_coop Where student_id='$key'");
	}
	*/
//linsy@20120412, 原為刪除學生在課程中的所有資料及修課關連，現暫時改為僅刪除修課關係。
/*
	if( mysql_num_rows ( $resultOBJ ) == 1 )
	{
		mysql_db_query( $DB, "Delete From log Where user_id='$key'");
		//mysql_db_query( $DB, "Delete From user Where a_id='$key'");
		mysql_db_query( $DB, "delete from gbfriend where my_id = '$key' or friend_id='$key'" );	
	}
*/
}

function Error_Handler( $msg, $cnx ) {  
	echo "$msg \n";
	sybase_close( $cnx); exit();  
}
?>
</div>
</center>
</body>
</html>
