<?
	require 'fadmin.php';
	
	session_id($PHPSESSID);
	session_start();
	
	// �ˬd�ϥ��v��.
	if(session_check_teach($PHPSESSID) != 2) {
		show_page("not_access.tpl", "�A�S���v�����榹�\��.<br>\nYou have no permission to perform this function.");
		exit();
	}

	mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("��Ʈw�s�����~");

	//���M�����e���O��
	$sql = "delete from discuss_list where chap_num=$chap_num";
	mysql_db_query($DB.$course_id, $sql) or die("��Ʈw�d�߿��~, $sql");
	//��s�O��
	if($discuss_num!=0){
		$sql = "insert into discuss_list (discuss_id, chap_num) values($discuss_num, $chap_num)";
		mysql_db_query($DB.$course_id, $sql) or die("��Ʈw�d�߿��~, $sql");
	}
	
	header("Location:editor_main.php?chap=$chap_num&errno=15&PHPSESSID=$PHPSESSID&reload=1"); 
?>