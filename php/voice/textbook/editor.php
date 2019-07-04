<?
	// param: $doc_root (session,SELF)
	//        $course_id (session)

	require 'fadmin.php';
	update_status ("編輯教材");
	include("class.FastTemplate.php3");

	session_id($PHPSESSID);
	session_start();

	//  專用變數. 紀錄本課程的網頁存放位置.
	//  根目錄專用變數. 從session中取得.
	if(!session_is_registered("doc_root")) {
		$doc_root = "../../$course_id/textbook";
		session_register("doc_root");
	}

	// 目前工作目錄. 避免某些情況下會發生的錯誤.
	if(!session_is_registered("work_dir")) {
		$work_dir = $doc_root;
		session_register("work_dir");
	}
	
	$doc_root = realpath($doc_root);

	if(session_check_teach($PHPSESSID) !=2) {
		show_page("not_access.tpl", "你沒有權限執行此功能.<br>\nYou have no permission to perform this function.");
		exit();
	}

	$tpl = new FastTemplate("./templates");

	$tpl->define(array("main" => "editor.tpl"));

	$tpl->parse(BODY, "main");

	$tpl->FastPrint(BODY);
?>