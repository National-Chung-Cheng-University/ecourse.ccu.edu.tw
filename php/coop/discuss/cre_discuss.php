<?
// param:   $course_id  (session)
//          $discuss_name   (SELF)
//          $comment        (SELF)
//			$isgroup		(SELF)
//			$access			(SELF)
//          $error_mes      (SELF)
//			$amount			(SELF)

	require 'fadmin.php';

	if(session_check_teach($PHPSESSID) < 1 || check_group ( $course_id, $coopgroup, $coopcaseid ) != 2 ) {
		show_page("not_access.tpl", "�A�S���v�����榹�\��.\nYou have no permission to perform this function.");
		exit();
	}

	if( empty($amount) ) {   // ��X�s�W�Q�װϵe��
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate("./templates");

		if($version == "C") {
			$tpl->define(array(main => "cre_discuss.tpl"));
		}
		else {
			$tpl->define(array(main => "cre_discuss_E.tpl"));
		}
		$tpl->assign("SKINNUM", $skinnum);
		$tpl->assign("TITLE", "�s�W�Q�װ�");
		$tpl->assign("MES", "$error_mes");
		$tpl->assign("PHP_ID", $PHPSESSID);

		$tpl->parse(BODY, "main");

		$tpl->FastPrint(BODY);
	}
	else {                                            // �s�W�Q�װϨ�DB
		mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("��Ʈw�s�����~");
		$name = $discuss_name;

		// �s�W��ƨ�table discuss_info  (�Ҧ��Q�װϻP�ҵ{�����Y�@����)
		$sql = "insert into discuss_".$coopcaseid."_info (discuss_name,comment,group_num,access) values ('$name','$comment','$coopgroup','$access');";

		if(mysql_db_query($DBC.$course_id, $sql) or die("��Ʈw�d�߿��~, $sql")) {
			$errno = 0;
		}
		else {
			$errno = 1;
			header("Location: dis_list.php?errno=$errno&PHPSESSID=$PHPSESSID");
		}
		$discuss_id = mysql_insert_id();

		// �s�W�@�ӷstable�����Q�װ�, �W�٬�discuss_[$discuss_id]  e.g. discuss_1, discuss_2, blahblahblah...
		$tablename = "discuss_".$coopcaseid."_".$discuss_id;
		$sql =	"create table $tablename (".
				"	a_id INT NOT NULL AUTO_INCREMENT,".
				"	title VARCHAR(64),".
				"	poster VARCHAR(64),".
				"	created DATETIME,".
				"	replied DATETIME,".
				"	parent INT,".
				"	body BLOB,".
				"	viewed MEDIUMINT,".
				"	type VARCHAR(32),".
				"	sound VARCHAR(64),".
				"	PRIMARY KEY(a_id)".
				");";

		if(mysql_db_query($DBC.$course_id, $sql) or die("��Ʈw�d�߿��~, $sql")) {
			$errno = 0;
			// �s�W�@�ӥؿ��ѤW���ɮ�.
			mkdir("../../../$course_id/coop/$coopcaseid/$coopgroup/$discuss_id",0770);
		}
		else {
			$errno = 1;
		}

		header("Location: dis_list.php?errno=$errno&PHPSESSID=$PHPSESSID");
   }
?>