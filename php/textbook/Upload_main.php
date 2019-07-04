<?
	require 'fadmin.php';
	// param:  $course_id : 由session傳入
	//         $errno
	//         $doc_root  (session, SELF)
	//         $work_dir  (session, SELF)
	// 為了安全問題 $doc_root中存的是利用realpath得到的絕對路徑
	//              $work_dir 則存相對路徑  要檢查的時候再利用realpath()
	
	// 檢查使用權限.
	if(session_check_teach($PHPSESSID) != 2) {
		show_page("not_access.tpl", "你沒有權限執行此功能.<br>\nYou have no permission to perform this function.");
		exit();
	}

	if( $version == "C" ) {
		$error_msg[0] = "檔案上傳 成功 ";
		$error_msg[1] = "檔案上傳 失敗 ";
		$error_msg[2] = "新目錄建立 成功 ";
		$error_msg[3] = "新目錄建立 失敗 ";
		$error_msg[4] = "切換目前工作目錄 成功 ";
		$error_msg[5] = "切換目前工作目錄 失敗 ";
		$error_msg[6] = "檔案刪除 成功 ";
		$error_msg[7] = "檔案刪除 失敗 ";
		$error_msg[8] = "目錄刪除 成功 ";
		$error_msg[9] = "目錄刪除 失敗 ";
		$error_msg[10] = "檔案更名 成功 ";
		$error_msg[11] = "檔案更名 失敗 ";
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
	
	// 目前工作目錄.
	if(!session_is_registered("work_dir")) {
		session_register("work_dir");
		//$work_dir = $doc_root;
	}
	//判斷是否是同一課程的$course_id
	$doc = explode( "/", $doc_root );
	$work = explode( "/", $work_dir );
	if( $doc[2]!= $work[2] )
	{
		//echo $doc[2]."-Qoo-".$work[2]."<br>";
		$work_dir = $doc_root;
	}
	
	// 錯誤處理變數.
	$set_file=0;
	$set_dir=0;

  
	// 判斷是否已經到了根目錄.
	if( strcmp( realpath($doc_root), realpath($work_dir) ) == 0 ) {
		$is_root = 1;
	}
	else {
		$is_root = 0;
	}

	include("class.FastTemplate.php3");
  	$tpl = new FastTemplate("./templates");

	// 中英文.
	if($version == "C") {
		$tpl->define(array(main => "Upload_main.tpl"));
	}
	else {
		$tpl->define(array(main => "Upload_main_E.tpl"));
	}
  
	// 指定dynamic block in Upload_main.tpl .
	$tpl->define_dynamic("directory_lista", "main");
	$tpl->define_dynamic("directory_listb", "main");
	$tpl->define_dynamic("file_list", "main");

	$tpl->assign(array("TITLE" => "檔案上傳管理介面"));
	$tpl->assign( "PHP_ID", $PHPSESSID );

	// 指定upload.tpl中的工作目錄.
	if( $version == "C" ) {
		$current = str_replace( realpath($doc_root), "/教材目錄", realpath($work_dir) );
	}
	else {
		$current = str_replace( realpath($doc_root), "/Textbook", realpath($work_dir) );	
	}
	$tpl->assign(array("CURRENT_DIR" => $current ));

	// 指定錯誤訊息.
	$tpl->assign(array("ERROR_MSG" => $error_msg[$errno]));

	// 錯誤處理.
	$tpl->assign("DIRNB", "");

	//由目錄中讀出檔案一覽表, 並判斷其為檔案或目錄.
//	var_dump( $work_dir );
//	echo "<br>";
	$files = sort_file_list($work_dir);
	
	foreach($files as $file)
	{
		if(is_dir($work_dir."/".$file) && (strcmp($file,"..") == 0))  {
			if($is_root==0) {   //檢查是否為根目錄
				$tpl->assign("NAMEA", $file);

				if($version == "C") {
					$tpl->assign("DIRNA", "(回到上一個目錄)");
				}
				else {
					$tpl->assign("DIRNA", "(Previous Directory)");
				}

				$tpl->parse(ROWDA, ".directory_lista");
				$set_dir = 1;
			}
		}
		else if(is_dir($work_dir."/".$file) && (strcmp($file,".") != 0) && (strcmp($file,"..") !=0) ) {   
			// 其他目錄輸出

      		$tpl->assign("NAMEA", $file);
      		$tpl->assign("NAMEB", $file);
			$tpl->assign("DIRNA", $file);
			$tpl->assign("DIRNB", $file);

      		$tpl->parse(ROWDA, ".directory_lista");
			$tpl->parse(ROWDB, ".directory_listb");
			$set_dir = 1;
		}
		else if(strcmp($file,".") !=0) {   
		// 除了 '.' 之外的檔案輸出
			$tpl->assign("FILE_N", $file);
			//修正若有空白字元會顯示連結錯誤 by chiefboy1230
			//$tpl->assign("FILE_LINK", $work_dir."/".urlencode($file));
			//修正中文檔名無法下載 by chiefboy1230
			//$tpl->assign("FILE_LINK", $work_dir."/". $file);
			$tpl->assign("FILE_DEL", urlencode($file));
			$tpl->assign("FILE_LINK", $work_dir."/". rawurlencode($file));
			$tpl->assign("FILE_SIZE", filesize($work_dir."/".stripslashes($file)));
			$tpl->assign("FILE_DATE", date("Y-m-d H:i:s",filemtime($work_dir."/".$file)));

			// 顏色控制.
			if($i)
				$tpl->assign("F_COLOR", "#ffffff");
			else
				$tpl->assign("F_COLOR", "#edf3fa");
		
			$i=!$i;

			$tpl->parse(ROWF, ".file_list");
			$set_file = 1;
		}
	}
	


	// 沒有任何檔案或目錄時的例外處理
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
		ksort($files);	//依日期排序
	}
	
	$handle->close();
	return $files;    
}
?>
