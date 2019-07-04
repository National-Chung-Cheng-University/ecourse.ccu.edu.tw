<?PHP
/*****************************************************************
	show_ad2.tpl-->按鈕名稱：清除上學期選課名單
*****************************************************************/
	require 'fadmin.php';
?>
<html>
<head>
<title>Delete Course</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
</head>
<body background = "/images/img/bg.gif"><center>
<div><table>
    <tr> 
      <td> 
        <div>
          <font color=#000000>清除上學期選課資料!</font>
        </div>
      </td>
    </tr>
</table>
<div id="progress">	　
</div>
<?PHP
if ( isset($PHPSESSID) && session_check_admin($PHPSESSID) ) {
	if(($error = add_student("academic")) == -1){
		echo "一般生修課資料清除完畢!!<br>";
	}
	else{
		echo "$error<br>";
	}

	if(($error = add_student("academic_gra")) == -1){
		echo "專班修課資料清除完畢!!<br>";
	}
	else{
		echo "$error<br>";
	}

	echo "<br><a href=../check_admin.php>回系統管理介面</a>";
}
else
	show_page( "index_ad.tpl", "你的權限錯誤，請重新登入!!");

function add_student($SDB){
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;
	// 連結mysql
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$error = "資料庫連結錯誤!!";
		return $error;
	}
	
	// 連結選課系統資料庫
	if( !($cnx = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) ){  
		Error_handler( "在 sybase_connect 有錯誤發生" , $cnx );  
	}
	$csd = @sybase_select_db($SDB, $cnx);
	
	// 取出課程代碼
	if($SDB == "academic_gra")
		$Qs1 = "select c.course_no, c.course_id from course_no c, teach_course t where c.course_id = t.course_id and (c.course_no like '___A%' or c.course_no like '___B%' or c.course_no like '___C%' or c.course_no like '___D%')";
	else if($SDB == "academic"){
		$Qs1 = "select c.course_no, c.course_id from course_no c, teach_course t where c.course_id = t.course_id and !(c.course_no like '___A%' or c.course_no like '___B%' or c.course_no like '___C%' or c.course_no like '___D%')";
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
	while ($row1 = mysql_fetch_array($result1)){
		$count++;
		$p = number_format((100*$count)/$total, 2);
		if($p>$temp){
			echo "<script language=\"JavaScript\" type=\"text/JavaScript\">
					document.all.progress.innerHTML = \"$class 修課資料清除中，請稍侯 $p%\" ; </script>";
		}
		$temp = $p;
		
		$course_no = explode("_",$row1['course_no']);
		$cour_cd = $course_no[0];
		$grp = $course_no[1];
		$course_id = $row1['course_id'];

		// 從教學系統取出選課資料
		$Qs5 = "select student_id, id from user u,take_course tc where course_id = '$course_id' and u.a_id=tc.student_id";
		if ($result5 = mysql_db_query($DB,$Qs5)){
			while($row5 = mysql_fetch_array($result5)){
				$std_id = $row5['student_id'];
				$std_no = $row5['id'];
				// 從選課系統資料庫中取出該名學生在本門課的選課資料
				$Qs6 = "select std_no from a31v_sel_class_tea where cour_cd = '$cour_cd' and grp = '$grp' and std_no = '$std_no'";
				$cur = sybase_query($Qs6, $cnx);
				if(!$cur) {  
					Error_handler( "在 sybase_exec 有錯誤發生( 沒有指標傳回 ) " , $cnx );  
				}
				// 若無資料則刪除選課資料及相關紀錄
				if(($array=sybase_fetch_array($cur))==0){
					//echo $SDB." - ".$std_no." - ".$cour_cd.$grp."<br>";
					delete_stu($std_id, $course_id);
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
function delete_stu ($key,$course_id) {
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;
	
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$message = "資料庫連結錯誤!!";
		show_page_d ( $message );
		return;
	}

	$Q1 = "Select student_id From take_course Where student_id='$key'";
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
	/*$resultOBJ2 = mysql_db_query( $DB.$course_id, "Select a_id From homework");
	while($row = mysql_fetch_array ( $resultOBJ2 )) {
		$target = "../../$course_id/homework/".$row['a_id']."/".$row_id['id'];
		if ( is_dir($target) )
			deldir ( $target );
	}*/
	mysql_db_query( $DB.$course_id, "Delete From take_exam Where student_id='$key'");
	mysql_db_query( $DB.$course_id, "Delete From log Where user_id='$key'");
	mysql_db_query( $DB, "Delete From take_course Where student_id='$key' and course_id = '$course_id'");
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
	if( mysql_num_rows ( $resultOBJ ) == 1 )
	{
		mysql_db_query( $DB, "Delete From log Where user_id='$key'");
		//mysql_db_query( $DB, "Delete From user Where a_id='$key'");
		mysql_db_query( $DB, "delete from gbfriend where my_id = '$key' or friend_id='$key'" );	
	}
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
