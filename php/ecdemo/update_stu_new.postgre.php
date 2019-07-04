<?php
require 'fadmin.php';
include 'logger.php';
?>
<html>
<head>
<title>更新學生資料</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
</head>
<body background = "/images/img/bg.gif"><center>
<div><table>
    <tr> 
      <td> 
        <div>
          <font color=#000000>更新學生資料!</font>
        </div>
      </td>
    </tr>
</table>
<div id="progress">	　
</div>
<?php
/*
if ( isset($PHPSESSID) && session_check_admin($PHPSESSID) ) {
*/
	echo "<body background = \"/images/img/bg.gif\"><center>";
	echo "一般學生資料開始更新!!<br>";
	updateLog("一般學生資料開始更新!!",4);
	if(($error = update_user("academic")) == -1) {
		echo "一般學生資料更新完畢!!<br>";
		updateLog("一般學生資料更新完畢!!",4);
	} else{
		echo "$error<br>";
	}
	echo "專班學生資料開始更新!!<br>";
	updateLog("專班學生資料開始更新!!",4);
	if(($error = update_user("academic_gra")) == -1) {
		echo "專班學生資料更新完畢!!<br>";
		updateLog("專班學生資料更新完畢!!",4);
	} else{
		echo "$error<br>";
	}
	
	echo "<br><a href=../check_admin.php>回系統管理介面</a></center></body>";
/*
}
else
	show_page( "index_ad.tpl", "你的權限錯誤，請重新登入!!");
*/


// 刪除兩學期以外的學生帳號確認之後才寫


	
// 更新學生名單
function update_user($db_name) {
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $id, $pass;
	
	/*
	if( !($cnx = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) ){  
		Error_handler( "在 sybase_connect 有錯誤發生" , $cnx );  
	}
	
	$csd = @sybase_select_db($db_name, $cnx);	
	//取得系所資訊 塞到自訂陣列 後面會使用
	$cur_unit = sybase_query("select * from h0rtunit_a_", $cnx);
	if(!$cur_unit) {  
		Error_handler( "在 sybase_exec 有錯誤發生( 沒有指標傳回 ) " , $cnx );  
	}
	*/
	$conn_string = "host=140.123.30.12 dbname=".$db_name." user=acauser password=!!acauser13";
	$cnx = pg_pconnect($conn_string) or die('資料庫沒有回應，請稍後再試');
	
	$cur_unit = pg_query($cnx, "select * from h0rtunit_a_") or die('資料表不存在，請通知電算中心');	
	
	$unit = array() ;
	//while($result_unit=sybase_fetch_array($cur_unit))
	while($result_unit=pg_fetch_array($cur_unit, null, PGSQL_ASSOC))
	{
		$unit_id = $result_unit['cd'];
		$unit[$unit_id] = $result_unit['abbrev'];
	}
	//
	
	
	//在學生資料(含休學)
	/*$cur = sybase_query("select * from a11vstd_rec_tea", $cnx);
	if(!$cur) {  
		Error_handler( "在 sybase_exec 有錯誤發生( 沒有指標傳回 ) " , $cnx );  
	}
	*/
	$cur = pg_query($cnx, "select * from a11vstd_rec_tea") or die('資料表不存在，請通知電算中心');
	
	//畢業及退學
	/*$cur2 = sybase_query("select * from a11vleave_rec_tea", $cnx);
	if(!$cur2) {  
		Error_handler( "在 sybase_exec 有錯誤發生( 沒有指標傳回 ) " , $cnx );  
	} 
	*/
	$cur2 = pg_query($cnx, "select * from a11vleave_rec_tea") or die('資料表不存在，請通知電算中心');	
	
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$error = "mysql資料庫連結錯誤!!";
		echo "$error";
	}
	
	//進度表用
	$count = 0;
	$temp = -1;
	//$total = sybase_num_rows($cur);
	$total = pg_num_rows($cur);
	ob_end_flush();
	ob_implicit_flush(1);
	//while($result=sybase_fetch_array($cur))
	while($result=pg_fetch_array($cur, null, PGSQL_ASSOC))
	{
		
		//進度表用
		$count++;
		$p = number_format((100*$count)/$total);
		if($p>$temp){
			echo "<script language=\"JavaScript\" type=\"text/JavaScript\">
					document.all.progress.innerHTML = \"學生資料新增中，請稍侯 $p%\" ; </script>";
		}
		$temp = $p;
		//
		//$stu_name = addslashes($result[name]);
		$stu_name = addslashes(mb_convert_encoding($result[name], "big5", "utf-8"));
		$Q2 = "select id,disable from user where id = '$result[id]'";
		//echo $Q2."<br>";
		
		if ($result2 = mysql_db_query($DB,$Q2)){
			$unit_id = $result['deptcd']; 	//取得系所代碼
			
			//if(trim($result[status])=="在學"){
			if(trim(mb_convert_encoding($result[status], "big5", "utf-8"))=="在學"){
				$row = mysql_fetch_array($result2);			
				// 新生
				if(!$row){	
					//echo "New Student";					
					//$Q3 = "insert into user (id, pass, ftppass, authorization, name, sex, tel, zip, addr, email, job ,grade) values (\"$result[id]\", \"" . passwd_encrypt($result[ps]) . "\", \"" . md5($result[ps]) . "\", \"3\", \"$stu_name\", \"$result[sex]\", \"$result[tel] \", \"$result[zip]\", \"$result[addr]\", \"$result[email]\", \"$unit[$unit_id]\", \"".$result['grade'].$result['class']."\")";
					$Q3 = "insert into user (id, pass, ftppass, authorization, name, sex, tel, zip, addr, email, job ,grade) values (\"$result[id]\", \"" . passwd_encrypt($result[ps]) . "\", \"" . md5($result[ps]) . "\", \"3\", \"$stu_name\", \"$result[sex]\", \"$result[tel] \", \"$result[zip]\", \"".mb_convert_encoding($result[addr], "big5", "utf-8")."\", \"$result[email]\", \"$unit[$unit_id]\", \"".$result['grade'].$result['class']."\")";
					if ( !($result3 = mysql_db_query($DB,$Q3)) ){
						$error = "mysql資料庫寫入錯誤!!";
						echo "$error".": $Q3".": $result3<br>";
					}
				}
				// 休學生復學,啟用使用權限
				else if($row && $row[disable]=='1'){
					$Q3 = "update user set disable='0', name='$stu_name', job='$unit[$unit_id]', grade='".$result['grade'].$result['class']."' where id='$result[id]'";
					if ( !($result3 = mysql_db_query($DB,$Q3)) ){
						$error = "mysql資料庫寫入錯誤!!";
						echo "$error".": $Q3".": $result3<br>";
					}
				}
				//其餘更新姓名年級
				else{
					$Q3 = "update user set name='$stu_name', job='$unit[$unit_id]', grade='".$result['grade'].$result['class']."' where id='$result[id]'";
					if ( !($result3 = mysql_db_query($DB,$Q3)) ){
						$error = "mysql資料庫寫入錯誤!!";
						echo "$error".": $Q3".": $result3<br>";
					}
				}
			}
			
			else if(trim(mb_convert_encoding($result[status], "big5", "utf-8"))=="休學"){
				$row = mysql_fetch_array($result2);
				// 休學生(第一次更新資料時才會用到)
				if(!$row){
					//$Q3 = "insert into user (id, pass, ftppass, authorization, disable, name, sex, tel, zip, addr, email,job ,grade) values (\"$result[id]\", \"" . passwd_encrypt($result[ps]) . "\", \"" . md5($result[ps]) . "\", \"3\", \"1\", \"$stu_name\", \"$result[sex]\", \"$result[tel] \", \"$result[zip]\", \"$result[addr]\", \"$result[email]\", \"$unit[$unit_id]\", \"".$result['grade'].$result['class']."\")";
					$Q3 = "insert into user (id, pass, ftppass, authorization, disable, name, sex, tel, zip, addr, email,job ,grade) values (\"$result[id]\", \"" . passwd_encrypt($result[ps]) . "\", \"" . md5($result[ps]) . "\", \"3\", \"1\", \"$stu_name\", \"$result[sex]\", \"$result[tel] \", \"$result[zip]\", \"".mb_convert_encoding($result[addr], "big5", "utf-8")."\", \"$result[email]\", \"$unit[$unit_id]\", \"".$result['grade'].$result['class']."\")";
					if ( !($result3 = mysql_db_query($DB,$Q3)) ){
						$error = "mysql資料庫寫入錯誤!!";
						echo "$error".": $Q3".": $result3<br>";
					}
				}
				// 在學生休學,暫停使用權限
				else if($row && $row[disable]=='0'){
					$Q3 = "update user set disable='1', name='$stu_name', job='$unit[$unit_id]', grade='".$result['grade'].$result['class']."' where id='$result[id]'";
					if ( !($result3 = mysql_db_query($DB,$Q3)) ){
						$error = "mysql資料庫寫入錯誤!!";
						echo "$error".": $Q3".": $result3<br>";
					}
				}
				//其餘更新姓名年級
				else{
					$Q3 = "update user set name='$stu_name', job='$unit[$unit_id]', grade='".$result['grade'].$result['class']."' where id='$result[id]'";
					if ( !($result3 = mysql_db_query($DB,$Q3)) ){
						$error = "mysql資料庫寫入錯誤!!";
						echo "$error".": $Q3".": $result3<br>";
					}
				}
			}
		}
		else{
			$error = "mysql資料庫讀取錯誤!!";
			echo "$error";
		}
	}
	
	//前一學年的資料才刪掉 即 最新的兩個學期的take_course有資料的或就不刪
	//從sybase取得新學期
	/*
	$cur3 = sybase_query("select DISTINCT year, term from a31v_sel_class_tea", $cnx);
	if(!$cur3) {  
		Error_handler( "在 sybase_exec 有錯誤發生( 沒有指標傳回 ) " , $cnx );  
	}
	*/
	$cur3 = pg_query($cnx, "select DISTINCT year, term from a31v_sel_class_tea") or die('資料表不存在，請通知電算中心');
	
	//if($array3=sybase_fetch_array($cur3))
	if($array3=pg_fetch_array($cur3, null, PGSQL_ASSOC))
	{
		$new_year = $array3['year'];
		$new_term = $array3['term'];
		if($new_term == 1){
			$pre_year = $new_year - 1;
			$pre_term = 2;
		}
		else{
			$pre_year = $new_year;
			$pre_term = 1;
		}
	}
	else{
		$error = "學期資料不存在!!<BR>";
		updateLog("學期資料不存在!!",4);
		return $error;
	}
	
	//進度表用
	$count = 0;
	$temp = -1;
	//$total = sybase_num_rows($cur2);
	$total = pg_num_rows($cur2);
	ob_end_flush();
	ob_implicit_flush(1);
	//while($result=sybase_fetch_array($cur2)){
	while($result=pg_fetch_array($cur2, null, PGSQL_ASSOC)){	
		//進度表用
		$count++;
		$p = number_format((100*$count)/$total,1);
		if($p>$temp){
			echo "<script language=\"JavaScript\" type=\"text/JavaScript\">
					document.all.progress.innerHTML = \"畢業及退學學生資料刪除中，請稍侯 $p%\" ; </script>";
		}
		$temp = $p;
		//
		$Q2 = "select a_id, id from user where id = '$result[id]'";
		if ($result2 = mysql_db_query($DB,$Q2)){
			$row = mysql_fetch_row($result2);
			// 畢業生或退學生則取消資料
			if($row){
				//查詢take_course 如果最新的兩個學期的take_course內無資料才刪
				$Q_t = "select count(student_id) as num from take_course where student_id = '".$row['0']."' and ( (year = $new_year and term = $new_term) OR (year = $pre_year and term = $pre_term) )";
				if($result_t = mysql_db_query($DB,$Q_t)){
					if($array_t = mysql_fetch_array($result_t)){
						if($array_t['num'] == 0){
							//刪除學生
							$Q3 = "delete from user where id='$result[id]'";
							echo "學生資料已清除$result[id]<br>";
							updateLog("學生資料已清除$result[id]",4);
							if ( !($result3 = mysql_db_query($DB,$Q3)) ){
								$error = "mysql資料庫寫入錯誤!!";
								echo "$error".": $Q3".": $result3<br>";
							}
						}
					}
				}
				else{
					$error = "mysql資料庫讀取錯誤t!!";
					echo "$error";
				}				
			}
		}
		else{
			$error = "mysql資料庫讀取錯誤!!";
			echo "$error";
		}
	}
	
	//sybase_close( $cnx);
	pg_close( $cnx);
	return -1;
}

function Error_Handler( $msg, $cnx ) {  
	echo "$msg \n";
	//sybase_close( $cnx); exit();  
	pg_close( $cnx); exit();  
}	
?>
