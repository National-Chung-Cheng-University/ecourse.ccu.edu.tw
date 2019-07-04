<?
	// �b���P�����p�U, �ϥΤ��P��template.
	// param: errno (chap.php / section.php / file_upload.php)
	//        course_id (session)
	//        chap  (editor_main.php / editor_chap/sect )
	//        section   (editor_main.php / editor_chap/sect )
	//        reload    (editor_main.php / chap / sect)  ��s�W���`�᪺reload����.
	// 02/03/12, 1:00 am
	// �s�W����server5, scorm, 105.20��bug. (function rem_sesid)
	// 02/03/20, 10:00am
	// �ק�p�����T��

	include("class.FastTemplate.php3");
	include("htmlgen.php");
	require 'fadmin.php';

	function rem_sesid( $str ) {
		
		$str2 = strstr($str, "PHPSESSID");

		if( ($str2 != "") && ($str2 != null) ) {
			
			$posa = strpos( $str, "href" );
			$posb = strpos( $str, "PHPSESSID" );
			$link = substr( $str, $posa+4, $posb-$posa-4 );

			if ( $link{strlen ( $link )-1} == "?" ) {
				$link = substr( $link, 0, strlen ( $link )-1 ); 
			}else if ( $link{strlen ( $link )-1} == "&" ) {
				$link = substr( $link, 0, strlen ( $link )-1 ); 
			}

			if ( strstr ( $link, "\"" ) != null ) {
				$link.= "\"";
			}
			else if ( strstr ( $link, "'" ) != null ) {
				$link .= "'";
			}
			$parta = substr( $str, 0, $posa+4 );
			$partb = strstr( $str2, ">" );
			return rem_sesid($parta.$link.$partb);
		}
		else
			return $str;
	}

	if($version == "C") {
		$error_msg[0] = "�s�W &lt;��&gt; <font color='blue'>���\</font>";
		$error_msg[1] = "��s &lt;��&gt; <font color='blue'>���\</font>";
		$error_msg[2] = "�s�W/��s &lt;��&gt; ����";
		$error_msg[3] = "�s�W &lt;�`&gt; <font color='blue'>���\</font>";
		$error_msg[4] = "��s &lt;�`&gt; <font color='blue'>���\</font>";
		$error_msg[5] = "�s�W/��s &lt;�`&gt; ����";
		$error_msg[6] = "�ҵ{�ɽ׭������e <font color='blue'>�w��s</font>";
		$error_msg[7] = "�����������e <font color='blue'>�w��s</font>";
		$error_msg[8] = "���`�������e <font color='blue'>�w��s</font>";
		$error_msg[9] = "�ɮפW�� <font color='blue'>���\</font>";
		$error_msg[10] = "�ɮפW�� ����";
		$error_msg[11] = "�v���ק� <font color='blue'>���\</font>";
		$error_msg[12] = "�v���ק� ����";
		$error_msg[13] = "�ɮקR�� <font color='blue'>���\</font>";
		$error_msg[14] = "�ɮקR�� ����";
		$error_msg[15] = "��s�Q�װϳs�� <font color='blue'>���\</font>";
		$error_msg[16] = "��s�Q�װϳs�� ����";
	}
	else {
		$error_msg[0] = "Chapter title Insert ok.";
		$error_msg[1] = "Chapter title Update ok.";
		$error_msg[2] = "Chapter title Insert/Update error.";
		$error_msg[3] = "Section title Insert ok.";
		$error_msg[4] = "Section title Update ok.";
		$error_msg[5] = "Section title Insert/Update error.";
		$error_msg[6] = "Introduce page of this course Updated.";
		$error_msg[7] = "Chapter page Updated.";
		$error_msg[8] = "Section page Updated.";
		$error_msg[9] = "New File Upload Succeed.";
		$error_msg[10] = "New File Upload Failed.";
		$error_msg[11] = "Permission Change Succeed.";
		$error_msg[12] = "Permission Change Falied.";
		$error_msg[13] = "File delete succeed.";
		$error_msg[14] = "File delete failed.";
		$error_msg[15] = "Update discuss_board succeed";
		$error_msg[16] = "Update discuss_board failed";	
	}

	
	// �ˬd�ϥ��v��.
	if(session_check_teach($PHPSESSID) != 2) {
		show_page("not_access.tpl", "�A�S���v�����榹�\��.<br>\nYou have no permission to perform this function.");
		exit();
	}

	mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("��Ʈw�s�����~");

	$tpl = new FastTemplate("./templates");

	// �P�_�ϥΦ��template. �`�N���^��.
	if(isset($chap) && empty($section)) {       // editor_chap.tpl   ���s�����
		if($version == "C") {
			$tpl->define(array(main => "editor_chap.tpl"));
		}
		else {
			$tpl->define(array(main => "editor_chap_E.tpl"));
		}
	}
	else if(isset($chap) && isset($section)) {   // editor_sect.tpl   �`�s�����
		if($version == "C") {
			$tpl->define(array(main => "editor_sect.tpl"));	  
		}
		else {
			$tpl->define(array(main => "editor_sect_E.tpl"));	  
		}
	}
	else {                                          // editor_root.tpl  �ҵ{/�ɽ׽s�����
		if($version == "C") {
			$tpl->define(array(main => "editor_root.tpl"));	  
		}
		else {
			$tpl->define(array(main => "editor_root_E.tpl"));	  
		}
	}

 
	// ��X���~�T��.
	if(isset($errno)) 
		$tpl->assign("ERROR_MSG", $error_msg[$errno]);
	else
		$tpl->assign("ERROR_MSG", "");


	// ����s�W��e����s������.
	if(isset($reload)) 
		$tpl->assign("RELOAD_CTRL", " onLoad=\"parent.left.location.reload();\"");
	else
		$tpl->assign("RELOAD_CTRL", "");

	// ����U�ؿ�X���B�z�{��.
	if(isset($chap) && empty($section)) {         
		// editor_chap.tpl  �U���s��e��
		$dir_name = "../../$course_id/textbook/$chap";
		$tpl->define_dynamic("sect_list", "main");

		$tpl->assign("CHAP_NUM", $chap);
		
		$tpl->assign("SECT_NUM", "");
		$tpl->assign("SECT_TITLE", "");

		$sql = "select * from chap_title where chap_num=$chap and sect_num!=0 order by sect_num";
		$result = mysql_db_query($DB.$course_id, $sql) or die("��Ʈw�d�߿��~, $sql");

		// color control.
		$i = false;
		while($row = mysql_fetch_array($result)) {
            $tpl->assign("SECT_NUM", $row["sect_num"]);
			$tpl->assign("SECT_TITLE", $row["sect_title"]);
    
	        if($i) 
				$tpl->assign("ED_COLOR", "#ffffff");
			else
				$tpl->assign("ED_COLOR", "#edf3fa");

			$i = !$i;
			$tpl->parse(ROWS, ".sect_list");
	    }

		//��ܰQ�װ�
		$Qt = "SELECT * from discuss_info";
		if ( !($result_t = mysql_db_query( $DB.$course_id, $Qt ) ) ) {
			show_page( "not_access.tpl" ,"�Х��إ߰Q�װϸs��" );
		}
		$total_topic = mysql_num_rows($result_t);
		if(empty($select_topic_num)){
			$sql = "select discuss_id from discuss_list where chap_num=$chap";
			$result_s = mysql_db_query($DB.$course_id, $sql) or die("��Ʈw�d�߿��~, $sql");
			$row_s = mysql_fetch_array($result_s);
			$select_topic = $select_topic."<option value="."0"." >"."  "."</option>";
			for($i=1; $i<=$total_topic; $i++){
				$rowt = mysql_fetch_array($result_t);
				$topic_id = $rowt["a_id"];
				$topic_name = $rowt["discuss_name"];
				if($topic_id==$row_s["discuss_id"]){
					$select_topic = $select_topic."<option value=".$topic_id." selected>".$topic_name."</option>";
				}else{
					$select_topic = $select_topic."<option value=".$topic_id." >".$topic_name."</option>";
				}
			}
		}else{
			$select_topic = $select_topic."<option value=".$select_topic_num." >".$select_topic_num."</option>";
		}
	
		$tpl->assign(SELECT_TOPIC,$select_topic);	

		if(is_file("$dir_name/index.html"))	{
			$fp = fopen("$dir_name/index.html", "r");
			$content = fread($fp , filesize("$dir_name/index.html"));
			$tpl->assign("HTML_CONTENT", rem_sesid($content));
			fclose($fp);
		}
		else
			$tpl->assign("HTML_CONTENT", "");
	}
	else if(isset($chap) && isset($section)) {    
		// editor_sect.tpl  �U�`�e��
		$dir_name = "../../$course_id/textbook/$chap/$section";
		$tpl->assign("CHAP_NUM", $chap);
		$tpl->assign("SECT_NUM", $section);

  		// ���� /textbook/$chap_num/$sect_num/index.html �����e.
		if(is_file("$dir_name/index.html"))	{
			$fp = fopen("$dir_name/index.html", "r");
			$content =  fread( $fp , filesize("$dir_name/index.html") );
			$tpl->assign("HTML_CONTENT", rem_sesid($content) );
			fclose($fp);
		}
		else
			$tpl->assign("HTML_CONTENT", "");
	}
	else {                                           
		// editor_root.tpl  �򥻵e��
		$dir_name = "../../$course_id/textbook";
		$tpl->define_dynamic("chap_list", "main");

		$tpl->assign("CHAP_NUM", "");
   		$tpl->assign("CHAP_TITLE", "");

		// Guest permission change part.
		// query validated value from DBMS.
		$sql = "select validated from course where a_id=$course_id";
		$result = mysql_db_query($DB, $sql) or die("��Ʈw�d�߿��~, $sql");
		if( $row = mysql_fetch_array($result) ) {
			$validated = $row["validated"];
		}
		else {
			show_page("not_access.tpl", "�o�ͤ������~, �еy��A��.<br>\nUndefined error occoured, please try later.");
		}
		if($validated % 2 == 0) {
			// textbook is public.
			if($version == "C") {
				$tpl->assign("VAL_STATUS", "�i��ť");
			}
			else {
				$tpl->assign("VAL_STATUS", "Yes");
			}
		}
		else {
			// textbook is private.
			if($version == "C") {
				$tpl->assign("VAL_STATUS", "<font color=red>���i��ť</font>");
			}
			else {
				$tpl->assign("VAL_STATUS", "No");
			}
		}
		$tpl->assign("VAL_VALUE", $validated);

		$sql = "select * from chap_title where sect_num=0 order by chap_num";
		$result = mysql_db_query($DB.$course_id, $sql) or die("��Ʈw�d�߿��~, $sql");

		$i = false;
		while($row = mysql_fetch_array($result)) {
			$tpl->assign("CHAP_NUM", $row["chap_num"]);
			$tpl->assign("CHAP_TITLE", $row["chap_title"]);

			if($i) 
				$tpl->assign("ED_COLOR", "#ffffff");
			else
				$tpl->assign("ED_COLOR", "#edf3fa");

			$i = !$i;

			$tpl->parse(ROWC, ".chap_list");
		}

		if(is_file("$dir_name/index.html"))	{
			$fp = fopen("$dir_name/index.html", "r");
			$content = fread($fp , filesize("$dir_name/index.html"));
			$tpl->assign("HTML_CONTENT", rem_sesid($content) );
			fclose($fp);
		}
		else
			$tpl->assign("HTML_CONTENT", "");
	}

	$tpl->assign("BASEHREF", $dir_name);


	$tpl->define_dynamic("file_list", "main");
	// Process file list under this directory.
	// directory name is already initialed.  ($dir_name)
	$files = sort_file_list($dir_name);
	foreach($files as $file) {
		if( ( strcmp($file,".")!=0 ) && ( strcmp($file,"..")!=0 ) && !is_dir($dir_name."/".$file) ) {   

			// some control variable with del_file.php
			if(isset($chap) && isset($section)) {
				$var = "&section=$section&editor=3&chap=$chap";
			}
			else if(isset($chap) && empty($section)) {
				$var = "&editor=2&chap=$chap";				
			}
			else {
				$var = "&editor=1";				
			}
			$calais = rawurlencode("&");
			$file2 = str_replace ( "&", $calais , $file );
			$tpl->assign("FILE_DEL", $dir_name."/".urlencode($file2).$var);

			// ���F '.', '..', �ؿ��~���ɮ׿�X
			$tpl->assign("FILE_N", $file);
			//$tpl->assign("FILE_LINK", $dir_name."/".$file);
			$tpl->assign("FILE_LINK", $dir_name."/". urlencode($file));
			$tpl->assign("FILE_SIZE", filesize($dir_name."/".stripslashes($file)));
			$tpl->assign("FILE_DATE", date("Y-m-d H:i:s",filemtime($dir_name."/".$file)));

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
	

	// exception handling : no file exists.
	if($set_file==0) {
		$tpl->assign("FILE_N", "");
		$tpl->assign("FILE_SIZE", "");
		$tpl->assign("FILE_DATE", "");
	}

	$tpl->assign("PHP_ID", $PHPSESSID);

	$tpl->parse(BODY, "main");

	$tpl->FastPrint(BODY);
	
	
function sort_file_list($dir_name)
{
	$handle = dir($dir_name);
    $i=false;
	$files = array();
	
	while (false !== ( $file = $handle->read() ) ) 
	{
		if( ( strcmp($file,".")!=0 ) && ( strcmp($file,"..")!=0 ) && !is_dir($dir_name."/".$file) ) 
		{
			$files[filemtime($dir_name."/".$file)] = $file;
		}
	}
	ksort($files);	//�̤���Ƨ�
	$handle->close();
	return $files;    
}
?>
