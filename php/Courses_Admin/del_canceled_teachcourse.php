<?php
/*****刪除當學期位開程課程的教師課程關係*********************
	bluejam 2006-03-20
	del_canceled_teachcourse.php
	
	show_ad2.tpl-->按鈕名稱：清除未開成功之課程
	將本來有開但最後取消開課那些課程的教師名單清掉
**********************************************/
require 'fadmin.php';
if ( isset($PHPSESSID) && session_check_admin($PHPSESSID) ) {
	$content = list_canceled_course();
}
else
{
	show_page( "index_ad.tpl", "你的權限錯誤，請重新登入!!");
}
?>
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
</head>

<body>

<?php
echo "<br><a href=../check_admin.php>回系統管理介面</a>";

function Error_Handler( $msg, $cnx ) {  
	echo "$msg \n";
	sybase_close( $cnx); exit();  
}

function list_canceled_course()
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;
	
	// 連結選課系統資料庫
	$cnx = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13");  
	if( ! $cnx ) {  
		Error_handler( "在 sybase_connect 有錯誤發生" , $cnx );  
	}
	
	// 分別由"academic"(一般生) 和 "academic_gra"(在職專班) 取出本學期確定開課之課程代碼
	/*
		year 開課學年,   
		term 開課學期,   
		unitname 開課系所,   
		class 開課系所年級,   
		cour_cd 課程代號,   
		grp 班別,   
		id 開課教師身份證號 
	*/
	//取出"academic"(一般生)
	$csd = @sybase_select_db("academic", $cnx);
	$cur = sybase_query("select distinct year, term ,unitname ,class,cour_cd, grp from a31vcurriculum_tea order by cour_cd", $cnx);	 
	if(!$cur) {  
		Error_handler( "在 sybase_exec 有錯誤發生( 沒有指標傳回 ) 一般生資料表" , $cnx );  
	}
	$num_row=0;  
	// 取出"academic"(一般生)成功傳回資料內的cour_cd 課程代號和grp 班別,組合成課程編號
	while( $result=sybase_fetch_array( $cur ) ) {  
		$num_row++; 
		$sybascihid[] = $result[4]."_".$result[5];
	}
	
	//取出"academic_gra"(在職專班)
	$csd = @sybase_select_db("academic_gra", $cnx);
	$cur = sybase_query("select distinct year, term ,unitname ,class,cour_cd, grp from a31vcurriculum_tea order by cour_cd", $cnx); 
	if(!$cur) {  
		Error_handler( "在 sybase_exec 有錯誤發生( 沒有指標傳回 )--在職專班資料表 " , $cnx );  
	}   
	// 取出"academic_gra"(在職專班)成功傳回資料內的cour_cd 課程代號和grp 班別,組合成課程編號
	while( $result=sybase_fetch_array( $cur ) ) {  
		$num_row++; 
		$sybascihid[] = $result[4]."_".$result[5];
	}
	
	//**********取得學期*********
		
	$result = sybase_query("select distinct year, term from a31vcurriculum_tea", $cnx);
	if(!$result) {  
		Error_handler( "在 sybase_exec 有錯誤發生( 沒有指標傳回 ) " , $cnx );  
	}
	if(!$row=sybase_fetch_array($result)){
		Error_handler( "在 sybase_exec 有錯誤發生( 沒有學期資料 ) " , $cnx );  
	}
	$year = $row[0];
	$term = $row[1];		
	//***************************
	sybase_close( $cnx);
	
	//----------------------------------------------------------------------
	// 連結mysql
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) )
	{
		$error = "資料庫連結錯誤!!";
		return $error;
	}
	
	
	
	//取出本學期所開之課程
	$Q = "select distinct  course_no.course_id, teach_course.year, teach_course.term, course_group.name as group_name, course_no.course_no, course.name from course_no , course , teach_course , course_group Where ".$year." = teach_course.year AND ".$term." = teach_course.term AND teach_course.course_id = course.a_id AND teach_course.course_id = course_no.course_id AND course.group_id = course_group.a_id order by course_no.course_no";
	if( !($result = mysql_db_query($DB, $Q)) ){
		$error = "資料庫query錯誤!! $Qi<br>";
		return $error;
	}
	
	$canceledcount = 0;		//取消之課程數目
	$deletecount = 0;		//刪除老師課程關係之課程數目
	$cihnum = 0;		//本學期目前所開課程數目
	
	$cihrow = "<tr><th>Index</th><th>學年</th><th>學期</th><th>系所</th><th>課程編號</th><th>課程名稱</th><th>修課學生</th><th>刪除</th></tr>";
	//取出本學期所開課程的詳細資料
	while(($row = mysql_fetch_array($result))){
		$cihnum++;
		$cancel=1;			//判斷是否為取消之課程
		foreach ($sybascihid as $value){
			if($value == $row[4] ){
				$cancel=0;
				break;
			}
		}
		//核對取消之課程的修課學生人數是否為0
		if( $cancel==1 ){
			$canceledcount++;
			$Q2 = "select count(student_id) from take_course where course_id=".$row[0];
			if( !($result2 = mysql_db_query($DB, $Q2)) ){
				$error = "資料庫query錯誤!! $Qi<br>";
				return $error;
			}			
			$row2 = mysql_fetch_array($result2);
			$cihrow .= "<tr><td>$canceledcount</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td><td>$row[4]</td><td>$row[5]</td><td>$row2[0]</td>";
			if(strcmp($row2[0],"0") == 0 ){
				$course_id = $row[0];
				$delete = delete_teach($course_id, $year, $term);
				$deletecount++;
				$cihrow .= "<td>".$delete."</td></tr>";
			}
			else{
				$cihrow .= "<td></td></tr>";
			}
			
		}
	}
	$cihrow .= "<tr><td colspan=7>目前課程共有".$cihnum."班, 取消開課的班數有".$canceledcount."班, 此次刪除的班數為".$deletecount."</td></tr>";
	mysql_close ($link);
	
	return $cihrow ;
}

function delete_teach($course_id, $year, $term)
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;
	
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$message = "資料庫連結錯誤!!";
		show_page_d ( $message );
		return;
	}

	$Q1 = "Delete From teach_course Where course_id='$course_id' AND year='$year' AND term='$term'";
	if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
		$message = "資料庫讀取錯誤!!";
		show_page_d ( $message );
		return;
	}
	return "確定刪除";
}

?>
<table width="100%" border="1" cellpadding="0">
  <tr>
  	<td valign="top"><?=$content?></td>
  </tr>
</table>
</body>
</html>
