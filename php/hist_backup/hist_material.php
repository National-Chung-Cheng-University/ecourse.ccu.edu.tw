<?

	require 'fadmin.php';
	update_status ("���v���s���Ч�");

	if ( !(isset($PHPSESSID) && session_check_teach($PHPSESSID)) ) {
		show_page( "not_access.tpl" ,"�v�����~");
	}
	
	if ( $scorm == 1 ) {
		include("class.FastTemplate.php3");

		$tpl = new FastTemplate("./templates");

		$tpl->define(array(main => "hist_scorm.tpl"));

		$tpl->assign("COURSE_ID", $course_id);

		$tpl->parse(BODY, "main");

		$tpl->FastPrint(BODY);
	}
	else { // �s�边���Ч�
		include("class.FastTemplate.php3");

		$tpl = new FastTemplate("./templates");

		$tpl->define(array(main => "hist_material.tpl"));

		$tpl->assign("PHPID", $PHPSESSID);

		$tpl->parse(BODY, "main");

		$tpl->FastPrint(BODY);
	}

?>