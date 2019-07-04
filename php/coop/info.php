<?php
	require 'fadmin.php';
	update_status ("小組說明");
	if ( !( isset($PHPSESSID) && session_check_teach($PHPSESSID) && check_group ( $course_id, $coopgroup, $coopcaseid ) ) ) {
		show_page( "not_access.tpl" ,"權限錯誤");
	}
	$access = check_group ( $course_id, $coopgroup, $coopcaseid );
	
	if ( $action == "modify" && $access == 2 ) {
		show_modify();
	}
	else if ( $action == "post" && $access == 2 ) {
		$message = add_info ( $info );
		if ( $message = -1 ) {
			if ( $version == "C" ) {
				$message = "更新完成";
			}
			else {
				$message = "Update Completed";
			}
		}
		show_modify( $message );
	}
	else if ( $action == "upload" && $access == 2 ) {
		if ( $file != "none" && $file != "") {
			if( fileupload ( $file, "../../$course_id/coop/$coopcaseid/$coopgroup/info", $file_name ) ) {
				$message = "檔案 $file_name 上傳完成";
   			}
   			else {
   				$message = "檔案 $file_name 上傳錯誤!!";
   			}
   		}
   		show_modify( $message );
	}
	else if ( $action == "del" && $access == 2 ) {
		if(strlen($filename) == 0) {
			header("Location: ./info.php?PHPSESSID=$PHPSESSID");
			exit;
		}

		$_target = realpath( "../../$course_id/coop/$coopcaseid/$coopgroup/info/$filename" );
		$doc_root = "/$course_id/coop/$coopcaseid/$coopgroup/info/";
		// 安全檢查
		$_target2 = str_replace ( "\\", "/", $_target );
		$pos = strpos($_target2, $doc_root);
		if($pos === false) {
			show_page("not_access.tpl", "Access Denied.");
			exit();
		}

		if(unlink($_target)) 
			$message = "檔案 $filename 刪除完成";
		else
			$message = "檔案 $filename 刪除錯誤!!";

		show_modify( $message );
   	}
	else {
		show_page_d ();
	}
	
	function add_info ( $info ) {
		global $course_id, $coopgroup, $coopcaseid;
/*		if ( is_file("../../$course_id/intro/index.html") ) {
			$fp = fopen("../../$course_id/intro/index.html", "w");
			//fwrite( $fp, $intro );
			$content = $intro;
			$content = str_replace ( "\\\"", "\"", $content );
			$content = str_replace ( "\\\'", "\'", $content );
			$content = str_replace ( "\\\\", "\\", $content );
			$content = str_replace ( "\\\?", "\?", $content );
			fwrite( $fp, $content );
			fclose($fp);
		}
		else {
*/			global $DB_SERVER, $DB_LOGIN, $DBC, $DB_PASSWORD;
			$Q0 = "select * from info_".$coopcaseid." where type = '0' and group_id='$coopgroup'";
			$Q1 = "insert info_".$coopcaseid." ( type, content, group_id ) values ( '0', '$info', '$coopgroup')";
			$Q2 = "update info_".$coopcaseid." set content = '$info' where group_id = '$coopgroup' and type = '0'";
			if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
				$error = "資料庫連結錯誤!!";
				return $error;
			}
			if ( mysql_num_rows ( mysql_db_query( $DBC.$course_id, $Q0 )) == 0 ) {
				if ( !($result = mysql_db_query( $DBC.$course_id, $Q1 ) ) ) {
					$error = "資料庫寫入錯誤!!";
					return $error;
				}
			}
			else {
				if ( !($result = mysql_db_query( $DBC.$course_id, $Q2 ) ) ) {
					$error = "資料庫更新錯誤!!";
					return $error;
				}
			}
			return -1;
//		}
	}
	
	function show_page_d ( $message="" ) {
		global $course_id, $coopgroup, $coopcaseid, $version, $skinnum, $user_id;
		if ( is_file("../../$course_id/coop/$coopcaseid/$coopgroup/info/index.html") ) {
			header( "Location: /$course_id/coop/$coopcaseid/$coopgroup/info/");
		}
		else {
			global $DB_SERVER, $DB_LOGIN, $DBC, $DB, $DB_PASSWORD;
			$Q1 = "select content from info_".$coopcaseid." where group_id = '$coopgroup' and type = '0'";

			if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
				echo ( "資料庫連結錯誤!!" );
			}
			else if ( !($result = mysql_db_query( $DBC.$course_id, $Q1 ) ) ) {
				echo ( "資料庫讀取錯誤!!" );
			}
			else {
				$row = mysql_fetch_array( $result );
				include("class.FastTemplate.php3");
				$tpl = new FastTemplate ( "./templates" );
				if ( $version == "C" ) {
					$tpl->assign ( TITLE, "小組說明");
				}
				else {
					$tpl->assign ( TITLE, "Information in Group");
				}
				$content = $row['content'];
				$content = str_replace ( "\n", "<br>", $content );
				
				$tpl->define ( array ( body => "info.tpl") );
				$tpl->assign ( ERR, $message);
				$tpl->assign ( MER, $content);
				$tpl->assign( SKINNUM , $skinnum );
				if ( check_group($course_id, $coopgroup, $coopcaseid) == 2 ) {
					$tpl->assign( MODIFY , "<a href=\"./info.php?action=modify\">修改</a>" );
				}
				else {
					$tpl->assign( MODIFY , "" );
				}
				$tpl->parse(BODY,"body");
				$tpl->FastPrint("BODY");
			}
		}
	}

	function show_modify ( $message="" ) {
		global $course_id, $coopgroup, $coopcaseid, $version, $skinnum, $user_id;
		global $DB_SERVER, $DB_LOGIN, $DBC, $DB, $DB_PASSWORD;
		$Q1 = "select content from info_".$coopcaseid." where group_id = '$coopgroup' and type = '0'";

		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$message = "$message - 資料庫連結錯誤!!";
		}
		else if ( !($result = mysql_db_query( $DBC.$course_id, $Q1 ) ) ) {
			$message = "$message - 資料庫讀取錯誤!!";
		}
		else {
			$row = mysql_fetch_array( $result );
			include("class.FastTemplate.php3");
			$tpl = new FastTemplate ( "./templates" );
			if ( $version == "C" ) {
				$tpl->assign ( TITLE, "小組說明");
			}
			else {
				$tpl->assign ( TITLE, "Information in Group");
			}
			$content = $row['content'];
			
			$tpl->define ( array ( body => "infoi.tpl") );
			$tpl->define_dynamic("file_list", "body");
			$work_dir = "../../$course_id/coop/$coopcaseid/$coopgroup/info";
			$handle = dir($work_dir);
			$i=false;
			while (( $file = $handle->read() ) ) {
				if(strcmp($file,".") !=0 && strcmp($file,"..")) {   
				// 除了 '.' '..'之外的檔案輸出
					$tpl->assign("FILE_N", $file);
					$tpl->assign("FILE_LINK", $work_dir."/".$file);
					$tpl->assign("FILE_SIZE", filesize($work_dir."/".stripslashes($file)));
					$tpl->assign("FILE_DATE", date("Y-m-d H:i:s",filemtime($work_dir."/".$file)));
					if ( $version == "C" ) {
						$tpl->assign("DELETE", "<a href=\"./info.php?action=del&filename=$file\" onclick=\"return confirm('你確定要刪除這個檔案嗎?');\">刪除這個檔案</a>" );
					}
					else {
						$tpl->assign("DELETE", "<a href=\"./info.php?action=del&filename=$file\" onclick=\"return confirm('Are You Sure to Del This File?');\">Del This File</a>" );
					}

				// 顏色控制.
					if($i)
						$tpl->assign("F_COLOR", "#F0FFEE");
					else
						$tpl->assign("F_COLOR", "#F0FFEE");
		
					$i=!$i;
					
					$tpl->parse(ROW, ".file_list");
					$set_file = 1;
				}
			}
			$handle->close();

			// 沒有任何檔案或目錄時的例外處理
			if($set_file==0) {
				$tpl->assign("FILE_N", "");
				$tpl->assign("FILE_SIZE", "");
				$tpl->assign("FILE_DATE", "");
				$tpl->assign("DELETE", "");
				$tpl->assign("F_COLOR", "#edf3fa");
			}
			
			$tpl->assign ( ERR, $message);
			$tpl->assign ( MES, $content);
			$tpl->assign( SKINNUM , $skinnum );

			$tpl->parse(BODY,"body");
			$tpl->FastPrint("BODY");
		}
	}
?>