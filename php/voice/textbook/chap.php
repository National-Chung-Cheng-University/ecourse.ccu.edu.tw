<?
// param: chap_num  (editor_root/chap)
//        course_id (session)
//        chap_title (editor_root/chap)
//        return_type (editor_chap)
//        doc_root  (session)
//        action   (editor_root/chap, 1:add/update | 2:delete, required.)

	require 'fadmin.php';

	session_id($PHPSESSID);
	session_start();

	// 檢查使用權限.
	if(session_check_teach($PHPSESSID) != 2) {
		show_page("not_access.tpl", "你沒有權限執行此功能.<br>\nYou have no permission to perform this function.");
		exit();
	}

	mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("資料庫連結錯誤");

	switch($action) {
		case 1:   // add/update chapter's title
			$sql = "select count(*) from chap_title where chap_num=$chap_num and sect_num=0 and sect_title=''";
			$result = mysql_db_query($DB.$course_id, $sql) or die("資料庫查詢錯誤, $sql");
			$row = mysql_fetch_array($result);

			if($row[0] == 0) {   // 此章的資料未在資料庫中, 新增一筆資料到資料庫, 並新增專用目錄.
				$sql = "insert into chap_title(chap_num,chap_title,sect_num,sect_title) values($chap_num,'$chap_title',0,'');";
				$errno = 0;
				if ( !is_dir($doc_root."/".$chap_num ) ) {
					mkdir($doc_root."/".$chap_num,0751);
				}
				
				// 複製預設的index.html
				//copy("../learn/public/material.html", $doc_root."/".$chap_num."/index.html");
			}
			else  {  // 更新資料.
				$sql = "update chap_title set chap_title='$chap_title' where chap_num=$chap_num and sect_num=0";
				$errno = 1;
			}
			mysql_db_query($DB.$course_id, $sql) or die("資料庫查詢錯誤, $sql");

			if(mysql_affected_rows() == 0)
				$errno = 2;

			if(empty($return_type)) {
				header("Location:editor_main.php?errno=$errno&PHPSESSID=$PHPSESSID&reload=1"); 
			}
			else {
				header("Location:editor_main.php?chap=$chap_num&errno=$errno&PHPSESSID=$PHPSESSID&reload=1"); 
			}  
			break;
		case 2:
			// delete this chapter and its all file/redord.
			// 先刪除資料庫的資料.
			if( (isset($chap_num)) && ($chap_num != 0) ) {
				$sql = "delete from chap_title where chap_num=$chap_num";
				mysql_db_query($DB.$course_id, $sql) or die("資料庫查詢錯誤, $sql");

				$sql = "delete from log where event_id=3 and tag1=$chap_num";
				mysql_db_query($DB.$course_id, $sql) or die("資料庫查詢錯誤, $sql");


				// 再刪除相關所有目錄.
				if( is_dir($doc_root."/".$chap_num) ) {
					deldir($doc_root."/".$chap_num);
				}

				header("Location:editor_main.php?PHPSESSID=$PHPSESSID&reload=1");
			}
			else {
				show_page("not_access.tpl", "參數不足<br>\nParameter is not enough.", "<a href='' onClick='history.go(-1)'>回上一頁/Back to previous page</a>");
				exit();
			}
	}
?>