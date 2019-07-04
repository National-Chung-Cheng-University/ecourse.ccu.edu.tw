<?

	require 'fadmin.php';
	update_status ("歷史區瀏覽教材");

	if ( !(isset($PHPSESSID) && session_check_teach($PHPSESSID)) ) {
		show_page( "not_access.tpl" ,"權限錯誤");
	}
	
	if ( $scorm == 1 ) {
		include("class.FastTemplate.php3");

		$tpl = new FastTemplate("./templates");

		$tpl->define(array(main => "hist_scorm.tpl"));

		$tpl->assign("COURSE_ID", $course_id);

		$tpl->parse(BODY, "main");

		$tpl->FastPrint(BODY);
	}
	else { // 編輯器的教材
		include("class.FastTemplate.php3");

		$tpl = new FastTemplate("./templates");

		$tpl->define(array(main => "hist_material.tpl"));

		$tpl->assign("PHPID", $PHPSESSID);

		$tpl->parse(BODY, "main");

		$tpl->FastPrint(BODY);
	}

?>