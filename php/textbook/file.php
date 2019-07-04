<?
// 檔案處理程式 for Upload_main.php and editor_main.php
// 計有 上傳檔案處理, 刪除檔案, 建立目錄, 刪除目錄, 更換目錄 等功能.
// param: action and lilikoko

	require "fadmin.php";
	if(session_check_teach($PHPSESSID) !=2) {
		show_page("not_access.tpl", "你沒有權限執行此功能.<br>\nYou have no permission to perform this function.");
		exit();
	}

	$doc_root = realpath("../../$course_id/textbook");

	if( strcmp($action, "upload") == 0 ) {

		if(isset($editor)) {
			// for editor_main.php.
			// Handle return page hyperlink and target directory.

			if(isset($chap_num) && isset($sect_num)) {
				// section.
				$returnlink = "editor_main.php?section=$sect_num&chap=$chap_num&PHPSESSID=$PHPSESSID";
				$target_dir = $doc_root."/".$chap_num."/".$sect_num;
			}
			else if(isset($chap_num) && empty($sect_num)) {
				// chapter.
				$returnlink = "editor_main.php?chap=$chap_num&PHPSESSID=$PHPSESSID";
				$target_dir = $doc_root."/".$chap_num;
			}
			else {
				// root.
				$returnlink = "editor_main.php?PHPSESSID=$PHPSESSID";
				$target_dir = $doc_root;
			}

			// 錯誤處理. 可能情況為使用者選了大小為0的檔案...etc
			if(!is_file($newfile)) {
				$errno = 10;
				header("Location: ".$returnlink."&errno=$errno"); 
				exit();
			}
		
			//modify by intree 2007/09/27	
			if( fileupload( $newfile, $target_dir, str_replace('&','＆',$newfile_name)  ) ) {
				$errno = 9; // copy ok.
			}
			else
				$errno = 10; // copy error.

			header("Location: ".$returnlink."&errno=$errno"); 
		}
		else {
			// for Upload_main.php.
			// 錯誤處理. 可能情況為使用者選了大小為0的檔案...etc
			if(!is_file($newfile)) {
				$error = 1;
				header("Location: Upload_main.php?errno=$errno&PHPSESSID=$PHPSESSID"); 
				exit();
			}

			if( fileupload( $newfile, $work_dir, str_replace('&','＆',$newfile_name)  ) ) {
				$errno = 0; // copy ok.
			}
			else
				$errno = 1; // copy error.

			header("Location: Upload_main.php?errno=$errno&PHPSESSID=$PHPSESSID"); 
		}
	}
	else if( strcmp( $action, "delete" ) ==0) {

		if(empty($editor)) {
			// error handle.
			if(strlen($filename) == 0) {
				header("Location: Upload_main.php?PHPSESSID=$PHPSESSID");
			}

			$_target = realpath($work_dir."/".$filename);

			// 安全檢查
			$_target2 = str_replace ( "\\", "/", $_target );

			$pos = strpos($_target2, $doc_root);
			if($pos === false) {
				show_page("not_access.tpl", "Access Denied.");
				exit();
			}
		
			if( unlink($_target) ) 
				$errno = 6; // delete ok.
			else
				$errno = 7; // delete error.

			header("Location: Upload_main.php?errno=$errno&PHPSESSID=$PHPSESSID"); 
		}
		else {
			// return page.
			switch($editor) {
				case 1:
					$return_link = "editor_main.php?PHPSESSID=$PHPSESSID";
					break;
				case 2:
					$return_link = "editor_main.php?PHPSESSID=$PHPSESSID&chap=$chap";
					break;
				case 3:
					$return_link = "editor_main.php?section=$section&chap=$chap&PHPSESSID=$PHPSESSID";
					break;
			}

			// error handle.
			if(strlen($filename) == 0) {
				header("Location: $return_link&errno=14");
			}

			$_target = realpath($filename);

			// 安全檢查
			$pos = strpos($_target, $doc_root);
			if($pos === false) {
				show_page("not_access.tpl", "Access Denied.");
				exit();
			}
		
			if(unlink($_target)) 
				$errno = 13; // delete ok.
			else
				$errno = 14; // delete error.

			header("Location: $return_link&errno=$errno");
		}
	}
	else if( strcmp( $action, "deldir" )==0 ) {
		// 檢查使用權限.
		if(session_check_teach($PHPSESSID) != 2) {
			show_page("not_access.tpl", "You have no permission to perform this function.");
			exit();
		}

		$target = realpath($work_dir."/".$deldir);

		// 安全檢查
		$pos = strpos($target, $doc_root);
		if(($pos === false) || (strcmp($target, $doc_root) == 0)) {
			show_page("not_access.tpl", "Access Denied.");
			exit();
		}

		deldir($target);
		
		if(is_dir($target)) {
			$errno=9;
		}
		else {
			$errno=8;
		}

		header("Location: Upload_main.php?errno=$errno&PHPSESSID=$PHPSESSID");
	}
	else if( strcmp( $action, "credir" ) ==0) {
		// 中文字錯誤處理
		$pos = strpos($newdir, "\\");
		if($pos !== false) {
			show_page("not_access.tpl", "目錄名中含有不正確字元.");
			exit();
		}
		
		$target = realpath($work_dir)."/".$newdir;
		
		// 安全檢查
		$pos = strpos($target, $doc_root);
		if($pos === false) {
			show_page("not_access.tpl", "Access Denied.");
			exit();
		}
		
		if(mkdir($target,0777)) 
			$errno = 2; // mkdir ok.
		else
			$errno = 3; // mkdir error.
	   

		header("Location: Upload_main.php?errno=$errno&PHPSESSID=$PHPSESSID");	
	}
	else if( strcmp( $action, "chgdir" ) ==0 ) {
		// 檢查使用權限.
		if(session_check_teach($PHPSESSID) != 2) {
			show_page("not_access.tpl", "You have no permission to perform this function.");
			exit();
		}

		// 回上層目錄檢查
		if(strcmp($chgdir,"..") !=0) 
			$_target = $work_dir."/".$chgdir;
		else
			$_target = dirname($work_dir);

		// 安全檢查
		$pos = strpos(realpath($_target), $doc_root);
		if($pos === false) {
			show_page("not_access.tpl", "Access Denied.");			
			exit();
		}

		if(chdir($_target)) {
			$errno = 4; // chdir ok.
			$work_dir = $_target;
			//echo $_target;
			//exit;
		}
		else
			$errno = 5; // chdir error.


		header("Location: Upload_main.php?errno=$errno&PHPSESSID=$PHPSESSID");
	}
	else if( strcmp( $action, "rename" ) == 0 ) {
		
		// 檢查使用權限.
		if(session_check_teach($PHPSESSID) != 2) {
			show_page("not_access.tpl", "You have no permission to perform this function.");
			exit();
		}


		//chiefboy1230@20120105，修正中文檔名無法更名的問題。
//		if (rename( $work_dir."/".$oldfile, $work_dir."/".$newfile )) {
		if (rename( iconv('UTF-8', 'BIG5', $work_dir."/".$oldfile) , iconv('UTF-8', 'BIG5', $work_dir."/".$newfile) )) {
			$errno = 10;
		}
		else {
			$errno = 11;
		}

		header("Location: Upload_main.php?errno=$errno&PHPSESSID=$PHPSESSID");
	}
	else {
		show_page("not_access.tpl", "參數錯誤.<br>Parameter Error.", "", "<a href='editor_main.php'>回編輯工具</a>");
	}
?>
