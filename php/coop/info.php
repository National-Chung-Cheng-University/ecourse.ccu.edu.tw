<?php
	require 'fadmin.php';
	update_status ("�p�ջ���");
	if ( !( isset($PHPSESSID) && session_check_teach($PHPSESSID) && check_group ( $course_id, $coopgroup, $coopcaseid ) ) ) {
		show_page( "not_access.tpl" ,"�v�����~");
	}
	$access = check_group ( $course_id, $coopgroup, $coopcaseid );
	
	if ( $action == "modify" && $access == 2 ) {
		show_modify();
	}
	else if ( $action == "post" && $access == 2 ) {
		$message = add_info ( $info );
		if ( $message = -1 ) {
			if ( $version == "C" ) {
				$message = "��s����";
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
				$message = "�ɮ� $file_name �W�ǧ���";
   			}
   			else {
   				$message = "�ɮ� $file_name �W�ǿ��~!!";
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
		// �w���ˬd
		$_target2 = str_replace ( "\\", "/", $_target );
		$pos = strpos($_target2, $doc_root);
		if($pos === false) {
			show_page("not_access.tpl", "Access Denied.");
			exit();
		}

		if(unlink($_target)) 
			$message = "�ɮ� $filename �R������";
		else
			$message = "�ɮ� $filename �R�����~!!";

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
				$error = "��Ʈw�s�����~!!";
				return $error;
			}
			if ( mysql_num_rows ( mysql_db_query( $DBC.$course_id, $Q0 )) == 0 ) {
				if ( !($result = mysql_db_query( $DBC.$course_id, $Q1 ) ) ) {
					$error = "��Ʈw�g�J���~!!";
					return $error;
				}
			}
			else {
				if ( !($result = mysql_db_query( $DBC.$course_id, $Q2 ) ) ) {
					$error = "��Ʈw��s���~!!";
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
				echo ( "��Ʈw�s�����~!!" );
			}
			else if ( !($result = mysql_db_query( $DBC.$course_id, $Q1 ) ) ) {
				echo ( "��ƮwŪ�����~!!" );
			}
			else {
				$row = mysql_fetch_array( $result );
				include("class.FastTemplate.php3");
				$tpl = new FastTemplate ( "./templates" );
				if ( $version == "C" ) {
					$tpl->assign ( TITLE, "�p�ջ���");
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
					$tpl->assign( MODIFY , "<a href=\"./info.php?action=modify\">�ק�</a>" );
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
			$message = "$message - ��Ʈw�s�����~!!";
		}
		else if ( !($result = mysql_db_query( $DBC.$course_id, $Q1 ) ) ) {
			$message = "$message - ��ƮwŪ�����~!!";
		}
		else {
			$row = mysql_fetch_array( $result );
			include("class.FastTemplate.php3");
			$tpl = new FastTemplate ( "./templates" );
			if ( $version == "C" ) {
				$tpl->assign ( TITLE, "�p�ջ���");
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
				// ���F '.' '..'���~���ɮ׿�X
					$tpl->assign("FILE_N", $file);
					$tpl->assign("FILE_LINK", $work_dir."/".$file);
					$tpl->assign("FILE_SIZE", filesize($work_dir."/".stripslashes($file)));
					$tpl->assign("FILE_DATE", date("Y-m-d H:i:s",filemtime($work_dir."/".$file)));
					if ( $version == "C" ) {
						$tpl->assign("DELETE", "<a href=\"./info.php?action=del&filename=$file\" onclick=\"return confirm('�A�T�w�n�R���o���ɮ׶�?');\">�R���o���ɮ�</a>" );
					}
					else {
						$tpl->assign("DELETE", "<a href=\"./info.php?action=del&filename=$file\" onclick=\"return confirm('Are You Sure to Del This File?');\">Del This File</a>" );
					}

				// �C�ⱱ��.
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

			// �S�������ɮשΥؿ��ɪ��ҥ~�B�z
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