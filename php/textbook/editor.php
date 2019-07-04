<?
	// param: $doc_root (session,SELF)
	//        $course_id (session)

	require 'fadmin.php';
	update_status ("�s��Ч�");
	include("class.FastTemplate.php3");

	session_id($PHPSESSID);
	session_start();

	//  �M���ܼ�. �������ҵ{�������s���m.
	//  �ڥؿ��M���ܼ�. �qsession�����o.
	if(!session_is_registered("doc_root")) {
		$doc_root = "../../$course_id/textbook";
		session_register("doc_root");
	}

	// �ثe�u�@�ؿ�. �קK�Y�Ǳ��p�U�|�o�ͪ����~.
	if(!session_is_registered("work_dir")) {
		$work_dir = $doc_root;
		session_register("work_dir");
	}
	
	$doc_root = realpath($doc_root);

	if(session_check_teach($PHPSESSID) !=2) {
		show_page("not_access.tpl", "�A�S���v�����榹�\��.<br>\nYou have no permission to perform this function.");
		exit();
	}

	$tpl = new FastTemplate("./templates");

	$tpl->define(array("main" => "editor.tpl"));

	$tpl->parse(BODY, "main");

	$tpl->FastPrint(BODY);
?>