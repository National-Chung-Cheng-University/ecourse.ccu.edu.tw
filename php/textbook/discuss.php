<?
	require 'fadmin.php';
	
	session_id($PHPSESSID);
	session_start();
	
	// 檢查使用權限.
	if(session_check_teach($PHPSESSID) != 2) {
		show_page("not_access.tpl", "你沒有權限執行此功能.<br>\nYou have no permission to perform this function.");
		exit();
	}

	mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("資料庫連結錯誤");

	//先清掉之前的記錄
	$sql = "delete from discuss_list where chap_num=$chap_num";
	mysql_db_query($DB.$course_id, $sql) or die("資料庫查詢錯誤, $sql");
	//更新記錄
	if($discuss_num!=0){
		$sql = "insert into discuss_list (discuss_id, chap_num) values($discuss_num, $chap_num)";
		mysql_db_query($DB.$course_id, $sql) or die("資料庫查詢錯誤, $sql");
	}
	
	header("Location:editor_main.php?chap=$chap_num&errno=15&PHPSESSID=$PHPSESSID&reload=1"); 
?>