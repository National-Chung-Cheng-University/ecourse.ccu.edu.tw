<?
	require 'fadmin.php';
	// param:  $course_id : ��session�ǤJ
	//         $errno
	//         $doc_root  (session, SELF)
	//         $work_dir  (session, SELF)
	// ���F�w�����D $doc_root���s���O�Q��realpath�o�쪺������|
	//              $work_dir �h�s�۹���|  �n�ˬd���ɭԦA�Q��realpath()
	
	// �ˬd�ϥ��v��.
	if(session_check_teach($PHPSESSID) != 2) {
		show_page("not_access.tpl", "�A�S���v�����榹�\��.<br>\nYou have no permission to perform this function.");
		exit();
	}

	if( $version == "C" ) {
		$error_msg[0] = "�ɮפW�� ���\ ";
		$error_msg[1] = "�ɮפW�� ���� ";
		$error_msg[2] = "�s�ؿ��إ� ���\ ";
		$error_msg[3] = "�s�ؿ��إ� ���� ";
		$error_msg[4] = "�����ثe�u�@�ؿ� ���\ ";
		$error_msg[5] = "�����ثe�u�@�ؿ� ���� ";
		$error_msg[6] = "�ɮקR�� ���\ ";
		$error_msg[7] = "�ɮקR�� ���� ";
		$error_msg[8] = "�ؿ��R�� ���\ ";
		$error_msg[9] = "�ؿ��R�� ���� ";
		$error_msg[10] = "�ɮק�W ���\ ";
		$error_msg[11] = "�ɮק�W ���� ";
	}
	else {
		$error_msg[0] = "File Upload Succeed.";
		$error_msg[1] = "File Upload Failed.";
		$error_msg[2] = "New Directory Is Created.";
		$error_msg[3] = "New Directory Is Not Created.";
		$error_msg[4] = "Current Working Directory Changed.";
		$error_msg[5] = "Current Working Directory Changing Failed.";
		$error_msg[6] = "File Delete Succeed.";
		$error_msg[7] = "File Delete Failed.";
		$error_msg[8] = "Directory Delete Succeed.";
		$error_msg[9] = "Directory Delete Failed.";
		$error_msg[10] = "File Rename Succeed";
		$error_msg[11] = "File Rename Failed";
	}

	$doc_root = "../../$course_id/textbook";
	
	// �ثe�u�@�ؿ�.
	if(!session_is_registered("work_dir")) {
		session_register("work_dir");
		//$work_dir = $doc_root;
	}
	//�P�_�O�_�O�P�@�ҵ{��$course_id
	$doc = explode( "/", $doc_root );
	$work = explode( "/", $work_dir );
	if( $doc[2]!= $work[2] )
	{
		//echo $doc[2]."-Qoo-".$work[2]."<br>";
		$work_dir = $doc_root;
	}
	
	// ���~�B�z�ܼ�.
	$set_file=0;
	$set_dir=0;

  
	// �P�_�O�_�w�g��F�ڥؿ�.
	if( strcmp( realpath($doc_root), realpath($work_dir) ) == 0 ) {
		$is_root = 1;
	}
	else {
		$is_root = 0;
	}

	include("class.FastTemplate.php3");
  	$tpl = new FastTemplate("./templates");

	// ���^��.
	if($version == "C") {
		$tpl->define(array(main => "Upload_main.tpl"));
	}
	else {
		$tpl->define(array(main => "Upload_main_E.tpl"));
	}
  
	// ���wdynamic block in Upload_main.tpl .
	$tpl->define_dynamic("directory_lista", "main");
	$tpl->define_dynamic("directory_listb", "main");
	$tpl->define_dynamic("file_list", "main");

	$tpl->assign(array("TITLE" => "�ɮפW�Ǻ޲z����"));
	$tpl->assign( "PHP_ID", $PHPSESSID );

	// ���wupload.tpl�����u�@�ؿ�.
	if( $version == "C" ) {
		$current = str_replace( realpath($doc_root), "/�Ч��ؿ�", realpath($work_dir) );
	}
	else {
		$current = str_replace( realpath($doc_root), "/Textbook", realpath($work_dir) );	
	}
	$tpl->assign(array("CURRENT_DIR" => $current ));

	// ���w���~�T��.
	$tpl->assign(array("ERROR_MSG" => $error_msg[$errno]));

	// ���~�B�z.
	$tpl->assign("DIRNB", "");

	//�ѥؿ���Ū�X�ɮפ@����, �çP�_�䬰�ɮשΥؿ�.
//	var_dump( $work_dir );
//	echo "<br>";
	$files = sort_file_list($work_dir);
	
	foreach($files as $file)
	{
		if(is_dir($work_dir."/".$file) && (strcmp($file,"..") == 0))  {
			if($is_root==0) {   //�ˬd�O�_���ڥؿ�
				$tpl->assign("NAMEA", $file);

				if($version == "C") {
					$tpl->assign("DIRNA", "(�^��W�@�ӥؿ�)");
				}
				else {
					$tpl->assign("DIRNA", "(Previous Directory)");
				}

				$tpl->parse(ROWDA, ".directory_lista");
				$set_dir = 1;
			}
		}
		else if(is_dir($work_dir."/".$file) && (strcmp($file,".") != 0) && (strcmp($file,"..") !=0) ) {   
			// ��L�ؿ���X

      		$tpl->assign("NAMEA", $file);
      		$tpl->assign("NAMEB", $file);
			$tpl->assign("DIRNA", $file);
			$tpl->assign("DIRNB", $file);

      		$tpl->parse(ROWDA, ".directory_lista");
			$tpl->parse(ROWDB, ".directory_listb");
			$set_dir = 1;
		}
		else if(strcmp($file,".") !=0) {   
		// ���F '.' ���~���ɮ׿�X
			$tpl->assign("FILE_N", $file);
			//�ץ��Y���ťզr���|��ܳs�����~ by chiefboy1230
			//$tpl->assign("FILE_LINK", $work_dir."/".urlencode($file));
			//�ץ������ɦW�L�k�U�� by chiefboy1230
			//$tpl->assign("FILE_LINK", $work_dir."/". $file);
			$tpl->assign("FILE_DEL", urlencode($file));
			$tpl->assign("FILE_LINK", $work_dir."/". rawurlencode($file));
			$tpl->assign("FILE_SIZE", filesize($work_dir."/".stripslashes($file)));
			$tpl->assign("FILE_DATE", date("Y-m-d H:i:s",filemtime($work_dir."/".$file)));

			// �C�ⱱ��.
			if($i)
				$tpl->assign("F_COLOR", "#ffffff");
			else
				$tpl->assign("F_COLOR", "#edf3fa");
		
			$i=!$i;

			$tpl->parse(ROWF, ".file_list");
			$set_file = 1;
		}
	}
	


	// �S�������ɮשΥؿ��ɪ��ҥ~�B�z
	if($set_dir==0) {
		$tpl->assign("NAMEA", "");
		$tpl->assign("DIRNA", "");
		$tpl->assign("NAMEB", "");
	}

	if($set_file==0) {
		$tpl->assign("FILE_N", "");
		$tpl->assign("FILE_SIZE", "");
		$tpl->assign("FILE_DATE", "");
	}

	$tpl->parse(BODY, "main");

	$tpl->FastPrint(BODY);
	
	
function sort_file_list($dir_name)
{
	$handle = dir($dir_name);
    $i=false;
	$files = array();
	
	while (false !== ( $file = $handle->read() ) ) 
	{
		$files[filemtime($dir_name."/".$file)] = $file;
		ksort($files);	//�̤���Ƨ�
	}
	
	$handle->close();
	return $files;    
}
?>
