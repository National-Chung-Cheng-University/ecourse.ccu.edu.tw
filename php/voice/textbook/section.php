<?
// param: chap_num  (editor_chap/sect)
//        course_id (session)
//        sect_num (editor_chap/sect)
//        sect_title (editor_chap/sect)
//        return_type (editor_sect)
//        doc_root  (session, from Upload_main.php or editor.php)

	require 'fadmin.php';

	// �ˬd�ϥ��v��.
	if( !isset($PHPSESSID) || session_check_teach($PHPSESSID) != 2) {
		show_page("not_access.tpl", "�A�S���v�����榹�\��.<br>\nYou have no permission to perform this function.");
		exit();
	}

	mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("��Ʈw�s�����~");

	switch($action) {
		case 1:	// add/update
			$sql = "select count(*) from chap_title where chap_num=$chap_num and sect_num=$sect_num";
			$result = mysql_db_query($DB.$course_id, $sql) or die("��Ʈw�d�߿��~, $sql");
			$row = mysql_fetch_array($result);

			if($row[0] == 0) {   
				// ���`����ƥ��b��Ʈw��, �s�W�@����ƨ��Ʈw, �ëإߨ�M�ݥؿ�.
				$sql = "insert into chap_title(chap_num,chap_title,sect_num,sect_title) values($chap_num,'',$sect_num,'$sect_title');";
				$errno = 3;
				if(!is_dir( $doc_root."/".$chap_num."/".$sect_num )) {
					mkdir( $doc_root."/".$chap_num."/".$sect_num, 0751 );
				}

				// �ƻs�w�]��index.html
				//copy("../../learn/public/material.html", $doc_root."/".$chap_num."/".$sect_num."/index.html");
			}
			else  {  
				// ��s���.
				$sql = "update chap_title set sect_title='$sect_title' where chap_num=$chap_num and sect_num=$sect_num";
				$errno = 4;
			}
			mysql_db_query($DB.$course_id, $sql) or die("��Ʈw�d�߿��~, $sql");

			if(mysql_affected_rows() == 0)
				$errno = 5;

			if(empty($return_type)) {
				header("Location:editor_main.php?chap=$chap_num&errno=$errno&PHPSESSID=$PHPSESSID&reload=1"); 
			}
			else {
				header("Location:editor_main.php?chap=$chap_num&section=$sect_num&errno=$errno&PHPSESSID=$PHPSESSID&reload=1"); 
			}
			break;
		case 2:	//delete
		echo $sect_num." ".$chap_num."<br>";
			if( (isset($chap_num)) && ($chap_num != 0) && (isset($sect_num)) && ($sect_num != 0) ) {
				// delete this section and its all file/redord.
				// ���R����Ʈw�����.
				echo "aaa";
				$sql = "delete from chap_title where chap_num=$chap_num and sect_num=$sect_num";
				mysql_db_query($DB.$course_id, $sql) or die("��Ʈw�d�߿��~, $sql");

				$sql = "delete from log where event_id=3 and tag1='$chap_num' and tag4='$sect_num'";
				mysql_db_query($DB.$course_id, $sql) or die("��Ʈw�d�߿��~, $sql");


				// �A�R�������Ҧ��ؿ�.
				if( is_dir($doc_root."/".$chap_num."/".$sect_num) ) {
					deldir($doc_root."/".$chap_num."/".$sect_num);
				}

				header("Location: editor_main.php?PHPSESSID=$PHPSESSID&reload=1&chap=$chap_num");
			}
			else {
				show_page("not_access.tpl", "�ѼƤ���<br>\nParameter is not enough.", "<a href='' onClick='history.go(-1)'>�^�W�@��/Back to previous page</a>");
				exit();
			}

	}
?>