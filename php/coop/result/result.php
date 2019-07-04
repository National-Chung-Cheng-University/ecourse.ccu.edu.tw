<?php
	require 'fadmin.php';
	update_status ("���G");
	$caseid2 = $coopcaseid;
	$group2 = $coopgroup;

	if ( $case_id != NULL) {
		$caseid2 = $case_id;
	}
	if ( $group_id != NULL) {
		$group2 = $group_id;
	}
	
	if ( !( isset($PHPSESSID) && session_check_teach($PHPSESSID) && ($teacher== 1 || check_group ( $course_id, $coopgroup, $coopcaseid )) ) ) {
		show_page( "not_access.tpl" ,"�v�����~");
	}
	$access = check_group ( $course_id, $group2, $caseid2 );
	
	if ( $action == "modify" && $access == 2 ) {
		show_modify();
	}
	else if ( $action == "share" && $access == 2 ) {
		share();
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
			if( fileupload ( $file, "../../../$course_id/coop/$coopcaseid/$coopgroup/result", $file_name ) ) {
				$message = "�ɮ� $file_name �W�ǧ���";
				global $DB_SERVER, $DB_LOGIN, $DBC, $DB_PASSWORD;
				$Q0 = "select * from info_".$coopcaseid." where type = '1'";
				$Q1 = "insert info_".$coopcaseid." ( type, content, group_id, upload ) values ( '1', '', '$coopgroup', '1')";
				$Q2 = "update info_".$coopcaseid." set upload = '1' where group_id = '$coopgroup' and type = '1'";
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
   			}
   			else {
   				$message = "�ɮ� $file_name �W�ǿ��~!!";
   			}
   		}
   		show_modify( $message );
	}
	else if ( $action == "del" && $access == 2 ) {
		if(strlen($filename) == 0) {
			header("Location: ./result.php?PHPSESSID=$PHPSESSID");
			exit;
		}

		$_target = realpath( "../../../$course_id/coop/$coopcaseid/$coopgroup/result/$filename" );
		$doc_root = "/$course_id/coop/$coopcaseid/$coopgroup/result/";
		// �w���ˬd
		$_target2 = str_replace ( "\\", "/", $_target );
		$pos = strpos($_target2, $doc_root);
		if($pos === false) {
			show_page("not_access.tpl", "Access Denied.");
			exit();
		}

		if(unlink($_target)) {
			$message = "�ɮ� $filename �R������";
			global $DB_SERVER, $DB_LOGIN, $DBC, $DB_PASSWORD;
			$Q0 = "select * from info_".$coopcaseid." where type = '1' and upload='1'";
			$Q1 = "update info_".$coopcaseid." set upload ='0' where group_id = '$coopgroup' and type = '1' and upload ='1'";
			if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
				$error = "��Ʈw�s�����~!!";
				return $error;
			}
			if ( mysql_num_rows ( mysql_db_query( $DBC.$course_id, $Q0 )) != 0 ) {
				if ( !($result = mysql_db_query( $DBC.$course_id, $Q1 ) ) ) {
					$error = "��Ʈw�g�J���~!!";
					return $error;
				}
			}
		}
		else
			$message = "�ɮ� $filename �R�����~!!";
		show_modify( $message );
   	}
	else {
		show_page_d ( $group2, $caseid2 );
	}

	function share ( ) {
		global $course_id, $coopgroup, $coopcaseid;
		global $DB_SERVER, $DB_LOGIN, $DBC, $DB_PASSWORD;
		$Q0 = "select * from info_".$coopcaseid." where type = '1'";
		$Q1 = "update info_".$coopcaseid." set share = '1' where group_id = '$coopgroup' and type = '1'";
		$Q2 = "update info_".$coopcaseid." set share = '0' where group_id = '$coopgroup' and type = '1'";

		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "��Ʈw�s�����~!!";
			return $error;
		}
		if ( mysql_num_rows ( mysql_db_query( $DBC.$course_id, $Q0 )) == 0 ) {
			return 1;
		}
		else {
			($result = mysql_db_query( $DBC.$course_id, $Q0 ) ) or die ("��ƮwŪ�����~");
			($row = mysql_fetch_array( $result )) or die ("��ƮwŪ�����~");
			if ( $row['share'] == "1" ) {
				($result = mysql_db_query( $DBC.$course_id, $Q2 )) or die ("��Ʈw��s���~");
			}
			else {
				($result = mysql_db_query( $DBC.$course_id, $Q1 )) or die ("��Ʈw��s���~");
			}
			return 0;
		}
	}

	function add_info ( $info ) {
		global $course_id, $coopgroup, $coopcaseid;
/*		if ( is_file("../../../$course_id/intro/index.html") ) {
			$fp = fopen("../../../$course_id/intro/index.html", "w");
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
			$Q0 = "select * from info_".$coopcaseid." where type = '1'";
			$Q1 = "insert info_".$coopcaseid." ( type, content, group_id ) values ( '1', '$info', '$coopgroup')";
			$Q2 = "update info_".$coopcaseid." set content = '$info', upload = '1' where group_id = '$coopgroup' and type = '1'";
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
	
	function show_page_d ( $group2, $caseid2 ) {
		global $course_id, $version, $skinnum, $coopgroup, $coopcaseid;
		if ( is_file("../../../$course_id/coop/$caseid/$group2/result/index.html") ) {
			header( "Location: /$course_id/coop/$caseid2/$group/result/index.html");
		}
		else {
			global $DB_SERVER, $DB_LOGIN, $DBC, $DB, $DB_PASSWORD;
			$Q1 = "select content, share from info_".$caseid2." where group_id = '$group2' and type = '1'";

			if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
				echo ( "��Ʈw�s�����~!!" );
			}
			else if ( !($result = mysql_db_query( $DBC.$course_id, $Q1 ) ) ) {
				echo ( "��ƮwŪ�����~!!$Q1" );
			}
			else {
				$row = mysql_fetch_array( $result );
				include("class.FastTemplate.php3");
				$tpl = new FastTemplate ( "./templates" );
				if ( $version == "C" ) {
					$tpl->assign ( TITLE, "�p�զ��G");
				}
				else {
					$tpl->assign ( TITLE, "Achievements of Group");
				}
				if ( $row['share'] != 1 && check_group ( $course_id, $group2, $caseid2 ) < 2 ) {
					$content = "������";
				}
				else {
					$content = $row['content'];
				}
				
				$tpl->define ( array ( body => "result.tpl") );
				$tpl->assign ( ERR, $message);
				$tpl->assign ( MER, $content);
				$tpl->assign( SKINNUM , $skinnum );
				if ( check_group($course_id, $group2, $caseid2) == 2 ) {
					$tpl->assign( MODIFY , "<a href=\"./result.php?action=modify\">�ק�</a>" );
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
		
		global $course_id, $coopgroup, $coopcaseid, $version, $skinnum;
		global $DB_SERVER, $DB_LOGIN, $DBC, $DB, $DB_PASSWORD;
		$Q1 = "select content, share from info_".$coopcaseid." where group_id = '$coopgroup' and type = '1'";

		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			echo ("��Ʈw�s�����~!!" );
		}
		else if ( !($result = mysql_db_query( $DBC.$course_id, $Q1 ) ) ) {
			echo ( "��ƮwŪ�����~!!" );
		}
		else {
			$row = mysql_fetch_array( $result );
			include("class.FastTemplate.php3");
			$tpl = new FastTemplate ( "./templates" );
			if ( $version == "C" ) {
				$tpl->assign ( TITLE, "�p�զ��G");
			}
			else {
				$tpl->assign ( TITLE, "Achievements of GroupGroup");
			}
			$content = $row['content'];
			
			$tpl->define ( array ( body => "resulti.tpl") );
			$tpl->define_dynamic("file_list", "body");
			$work_dir = "../../../$course_id/coop/$coopcaseid/$coopgroup/result";

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
						$tpl->assign("DELETE", "<a href=\"./result.php?action=del&filename=$file\" onclick=\"return confirm('�A�T�w�n�R���o���ɮ׶�?');\">�R���o���ɮ�</a>" );
					}
					else {
						$tpl->assign("DELETE", "<a href=\"./result.php?action=del&filename=$file\" onclick=\"return confirm('Are You Sure to Del This File?');\">Del This File</a>" );
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
			
			if ( $row['share'] == "1" ) {
				if ( $version == "C" ) {
					$tpl->assign("STATUS", "���}");
					$tpl->assign("BUTTOM", "�����}");
					
				}
				else {
					$tpl->assign("STATUS", "Public");
					$tpl->assign("BUTTOM", "No Public");
				}
			}
			else {
				if ( $version == "C" ) {
					$tpl->assign("STATUS", "�����}");
					$tpl->assign("BUTTOM", "���}");
				}
				else {
					$tpl->assign("STATUS", "No Public");
					$tpl->assign("BUTTOM", "Public");
				}
			}
			
			$tpl->assign ( ERR, $message);
			$tpl->assign ( MES, $content);
			$tpl->assign( SKINNUM , $skinnum );
			$tpl->parse(BODY,"body");
			$tpl->FastPrint("BODY");
		}
	}
?>