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

	// �ˬd�ϥ��v��.
	if(session_check_teach($PHPSESSID) != 2) {
		show_page("not_access.tpl", "�A�S���v�����榹�\��.<br>\nYou have no permission to perform this function.");
		exit();
	}

	mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("��Ʈw�s�����~");

	switch($action) {
		case 1:   // add/update chapter's title
			$sql = "select count(*) from chap_title where chap_num=$chap_num and sect_num=0 and sect_title=''";
			$result = mysql_db_query($DB.$course_id, $sql) or die("��Ʈw�d�߿��~, $sql");
			$row = mysql_fetch_array($result);

			if($row[0] == 0) {   // ��������ƥ��b��Ʈw��, �s�W�@����ƨ��Ʈw, �÷s�W�M�Υؿ�.
				$sql = "insert into chap_title(chap_num,chap_title,sect_num,sect_title) values($chap_num,'$chap_title',0,'');";
				$errno = 0;
				if ( !is_dir($doc_root."/".$chap_num ) ) {
					mkdir($doc_root."/".$chap_num,0751);
				}
				
				// �ƻs�w�]��index.html
				//copy("../learn/public/material.html", $doc_root."/".$chap_num."/index.html");
			}
			else  {  // ��s���.
				$sql = "update chap_title set chap_title='$chap_title' where chap_num=$chap_num and sect_num=0";
				$errno = 1;
			}
			mysql_db_query($DB.$course_id, $sql) or die("��Ʈw�d�߿��~, $sql");

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
			// ���R����Ʈw�����.
			if( (isset($chap_num)) && ($chap_num != 0) ) {
				$sql = "delete from chap_title where chap_num=$chap_num";
				mysql_db_query($DB.$course_id, $sql) or die("��Ʈw�d�߿��~, $sql");

				$sql = "delete from log where event_id=3 and tag1=$chap_num";
				mysql_db_query($DB.$course_id, $sql) or die("��Ʈw�d�߿��~, $sql");


				// �A�R�������Ҧ��ؿ�.
				if( is_dir($doc_root."/".$chap_num) ) {
					deldir($doc_root."/".$chap_num);
				}

				header("Location:editor_main.php?PHPSESSID=$PHPSESSID&reload=1");
			}
			else {
				show_page("not_access.tpl", "�ѼƤ���<br>\nParameter is not enough.", "<a href='' onClick='history.go(-1)'>�^�W�@��/Back to previous page</a>");
				exit();
			}
	}
?>