<?php
	require 'fadmin.php';
	update_status ("�[�ݸ귽");
	if ( !(isset($PHPSESSID) && session_check_teach($PHPSESSID) && ($check = check_group ( $course_id, $coopgroup, $coopcaseid )) != 0 ) ) {
		show_page( "not_access.tpl" ,"�v�����~");
	}

	if ( isset($submit) && ( $submit == "�R��" || $submit == "Del" )  ) {
		del_file();
		show_main ();
	}
	else if ( isset($submit) && ( $submit == "�դ�����" || $submit == "Share inside group" )  ) {
		share_file(1);
		show_main ();
	}
	else if ( isset($submit) && ( $submit == "�ն�����" || $submit == "Share to other group" )  ) {
		share_file(2);
		show_main ();
	}
	else if ( isset($submit) && ( $submit == "������" || $submit == "Not Share" )  ) {
		share_file(0);
		show_main ();
	}
	else if ( isset($submit) && ( $submit == "���O��" || $submit == "Note" )  ) {
		note_file();
		show_main ();
	}
	else if ( isset($submit) && ( $submit == "�e�X" || $submit == "Send" )) {
		if ( !(($style=="upload" && $file == "none") || ($style=="url" && $url == "") || $style == "") ) {
			add_file();
			$content = "";
			$url = "";
		}
		show_main ();
	}
	else if ( isset($submit) && ( $submit == "�s�W" || $submit == "Creat" )) {
		if ( $name=="" ) {
			show_main ();
		}
		else {
			add_dir();
			$name = "";
			show_main ( 1 );
		}
	}
	else if ( isset($submit) && ( $submit == "�R����Ƨ�" || $submit == "Del Dir" )  ) {
		del_share_dir();
		show_main ( 1 );
	}
	else
		show_main ();
	
	function del_file () {
		global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $course_id, $coopgroup, $coopcaseid, $user_id, $PHPSESSID;
		$Q1 = "select * from share_".$coopcaseid." where group_num = '$coopgroup' and student_id = '".GetUserAID($user_id)."'";
		if ( !($sqllink = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$message = "$message - ��Ʈw�s�����~!!";
		}
		else if ( !($result = mysql_db_query( $DBC.$course_id, $Q1 ) ) ) {
			$message = "$message - ��ƮwŪ�����~!!";
		}
		else if ( mysql_num_rows( $result ) != 0 ) {
			while ( $row = mysql_fetch_array( $result ) ) {
				global $$row['a_id'];
				if ( $$row['a_id'] == "NO" ) {
					$Q2 = "delete from share_".$coopcaseid." where a_id = '".$row['a_id']."'";
					if ( !($result2 = mysql_db_query( $DBC.$course_id, $Q2 ) ) ) {
						$message = "$message - ��Ʈw�R�����~!!";
					}
					if ( is_file( "../../../".$course_id."/coop/$coopcaseid/share/".$row['filename'] ) )
						unlink ( "../../../".$course_id."/coop/$coopcaseid/share/".$row['filename'] );
					if( (check_group ( $course_id, $coopgroup, $coopcaseid ) >= 2) && (strcmp($guest,"1") != 0) && (session_check_teach($PHPSESSID) != 3)) {
						add_log_coop( 8, $user_id, "1", $course_id, "", "", $coopgroup, $coopcaseid );
					}
				}
			}
		}
	}

	function note_file () {
		global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $course_id, $coopgroup, $coopcaseid, $user_id;
		$Q1 = "select * from share_".$coopcaseid." where group_num = '$coopgroup' and student_id = '".GetUserAID($user_id)."'";
		if ( !($sqllink = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$message = "$message - ��Ʈw�s�����~!!";
		}
		else if ( !($result = mysql_db_query( $DBC.$course_id, $Q1 ) ) ) {
			$message = "$message - ��ƮwŪ�����~!!";
		}
		else if ( mysql_num_rows( $result ) != 0 ) {
			while ( $row = mysql_fetch_array( $result ) ) {
				global $$row['a_id'];
				if ( $$row['a_id'] == "NO" ) {
					$Q2 = "delete from share_".$coopcaseid." where a_id = '".$row['a_id']."'";
					if ( !($result2 = mysql_db_query( $DBC.$course_id, $Q2 ) ) ) {
						$message = "$message - ��Ʈw�R�����~!!";
					}
					if ( is_file( "../../../".$course_id."/coop/$coopcaseid/share/".$row['filename'] ) )
						unlink ( "../../../".$course_id."/coop/$coopcaseid/share/".$row['filename'] );
				}
			}
		}
	}

	function share_file ( $type = 1 ) {
		global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $course_id, $coopgroup, $coopcaseid, $user_id;
		$Q1 = "select * from share_".$coopcaseid." where group_num = '$coopgroup' and student_id = '".GetUserAID($user_id)."'";
		if ( !($sqllink = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$message = "$message - ��Ʈw�s�����~!!";
		}
		else if ( !($result = mysql_db_query( $DBC.$course_id, $Q1 ) ) ) {
			$message = "$message - ��ƮwŪ�����~!!";
		}
		else if ( mysql_num_rows( $result ) != 0 ) {
			while ( $row = mysql_fetch_array( $result ) ) {
				global $$row['a_id'];
				if ( $$row['a_id'] == "NO" ) {
					$Q2 = "update share_".$coopcaseid." set share = '$type' where a_id = '".$row['a_id']."'";
					if ( !($result2 = mysql_db_query( $DBC.$course_id, $Q2 ) ) ) {
						$message = "$message - ��Ʈw��s���~!!";
					}
	
				}
			}
		}
	}
	
	function add_file () {
		global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $user_id, $course_id, $content, $url, $file, $message, $file_name, $style, $coopgroup, $coopcaseid, $group_id, $PHPSESSID;
		
		if ( $style == "url" ) {
			$Q1 = "insert into share_".$coopcaseid." ( group_num, student_id, filename, content, type ) values ( '$coopgroup', '".GetUserAID($user_id)."', '$url', '$content', '$group_id' )";
		}
		else if ( $style == "upload" ) {
			$Q1 = "insert into share_".$coopcaseid." ( group_num, student_id, filename, content, type, upload ) values ( '$coopgroup', '".GetUserAID($user_id)."', '$file_name', '$content', '$group_id', '1' )";
		}

		($sqllink = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) || die ("��Ʈw�s�����~");
		($result = mysql_db_query( $DBC.$course_id, $Q1 )) || die ("��Ʈw�g�J���~$Q1");
		$aid = mysql_insert_id();

		if ( $style == "upload" ) {
			$ext = strrchr( $file_name, '.' );
			$filename=$aid.$ext;
			$path = "../../../$course_id/coop/$coopcaseid/share/";
			fileupload ( $file, $path, $filename, $mode=0755 );
			$Q2 = "update share_".$coopcaseid." set filename = '$filename' where a_id = '$aid'";
			mysql_db_query( $DBC.$course_id, $Q2 ) || die ("��Ʈw�g�J���~$Q2");
		}
		if( (check_group ( $course_id, $coopgroup, $coopcaseid ) >= 2) && (strcmp($guest,"1") != 0) && (session_check_teach($PHPSESSID) != 3)) {
			add_log_coop( 8, $user_id, "", $course_id, "", "", $coopgroup, $coopcaseid );
		}
		return $message;
	}

	function add_dir () {
		global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $course_id, $message, $name, $coopgroup, $coopcaseid, $group_id;
		
		$Q1 = "insert into share_group_".$coopcaseid." ( group_num, name, parent_id ) values ( '$coopgroup', '$name', '$group_id' )";

		($sqllink = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) || die ("��Ʈw�s�����~");
		($result = mysql_db_query( $DBC.$course_id, $Q1 )) || die ("��Ʈw�g�J���~$Q1");
		
	}
	
	function del_share_dir () {
		global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $course_id, $coopgroup, $coopcaseid, $group_id;
		
		$Q1 = "delete from share_group_".$coopcaseid." where group_num = '$coopgroup' and a_id = '$group_id'";

		($sqllink = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) || die ("��Ʈw�s�����~");
		($result = mysql_db_query( $DBC.$course_id, $Q1 )) || die ("��Ʈw�R�����~$Q1");
		
	}

	function show_main ( $reload=0 ) {
		global $version, $check, $skinnum, $teacher, $course_id, $coopgroup, $coopcaseid, $user_id;
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		$tpl->assign( SKINNUM , $skinnum );
		$tpl->define ( array ( body => "source_mag.tpl" ) );

		$tpl->define_dynamic ( "course_list" , "body" );
		
		global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $message, $group_id, $content, $url;
		if ( !($sqllink = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$message = "$message - ��Ʈw�s�����~!!";
		}
		
		if($reload == 1) {
			$Q = "select a_id from share_group_".$coopcaseid." where group_num='$coopgroup' and parent_id = '-1'";
			$tpl->assign("RELOAD_CTRL", " onLoad=\"parent.tree.location.reload();\"");
			($result = mysql_db_query( $DBC.$course_id, $Q )) || die ("��ƮwŪ�����~$Q");
			$row = mysql_fetch_array ( $result );
			$group_id = $row['a_id'];
		}
		else
			$tpl->assign("RELOAD_CTRL", "");
		$color = "#4d6eb2";
		$tpl->assign( COLOR , $color );
		if ( $version == "C" ) {
			if ( $check == 1 )
				$tpl->assign( CHECK , "<font color=#FFFFFF><b>�s��</b></font>" );
			else
				$tpl->assign( CHECK , "<font color=#FFFFFF><b>���</b></font>" );
			$tpl->assign( DATE , "<font color=#FFFFFF><b>���</b></font>" );
			$tpl->assign( CONTENT , "<font color=#FFFFFF><b>���e�y�z</b></font>" );
			$tpl->assign( STATUS , "<font color=#FFFFFF><b>���A</b></font>" );
			$tpl->assign( LINK , "<font color=#FFFFFF><b>�ɮ׳s��</b></font>" );
		}
		else {
			if ( $check == 1 )
				$tpl->assign( CHECK , "<font color=#FFFFFF><b>No.</b></font>" );
			else
				$tpl->assign( CHECK , "<font color=#FFFFFF><b>Check</b></font>" );
			$tpl->assign( DATE , "<font color=#FFFFFF><b>Date</b></font>" );
			$tpl->assign( CONTENT , "<font color=#FFFFFF><b>Content</b></font>" );
			$tpl->assign( STATUS , "<font color=#FFFFFF><b>Status</b></font>" );
			$tpl->assign( LINK , "<font color=#FFFFFF><b>File Link</b></font>" );
		}
		$tpl->parse ( COURSE_LIST, ".course_list" );
		
		$Q1 = "select * from share_".$coopcaseid." where group_num='$coopgroup' and type = '$group_id' and (share='1' || student_id = '".GetUserAID($user_id)."') order by mtime DESC";
		if ( !($result = mysql_db_query( $DBC.$course_id, $Q1 ) ) ) {
			$message = "$message - ��ƮwŪ�����~!!";
		}
		else if ( mysql_num_rows( $result ) != 0 ) {
			if ( $version == "C" )
				$message = "<font size=5 color=#0000ff>�ثe���ɮ׸귽�C��</font>";
			else
				$message = "<font size=5 color=#0000ff>The File List</font>";
			$i = 0;
			$color = "#BFCEBD";
			while ( $row = mysql_fetch_array( $result ) ) {
				if ( $color == "#BFCEBD" )
					$color = "#D0DFE3";
				else
					$color = "#BFCEBD";
				$tpl->assign( COLOR , $color );
				$i ++;
				if ( $check == 1 )
					$tpl->assign( CHECK , "$i" );
				else
					$tpl->assign( CHECK , "<input type=checkbox name=". $row['a_id'] ." value=NO onBlur=\"selected=(selected||this.checked);\">" );
				if ( $row['share'] == 1 && $row['student_id'] != GetUserAID($user_id) ) {
					if ( $version == "C" ) {
						$tpl->assign( STATUS , "�դ��L�H����" );
					}
					else {
						$tpl->assign( STATUS , "Others Share inside Group" );
					}
				}
				else if ( $row['share'] == 1 ) {
					if ( $version == "C" ) {
						$tpl->assign( STATUS , "�դ�����" );
					}
					else {
						$tpl->assign( STATUS , "Share inside Group" );
					}
				}
				else if ( $row['share'] == 2 && $row['student_id'] != GetUserAID($user_id) ) {
					if ( $version == "C" ) {
						$tpl->assign( STATUS , "�L�H�ն�����" );
					}
					else {
						$tpl->assign( STATUS , "Others Group Share " );
					}
				}
				else if ( $row['share'] == 2 ) {
					if ( $version == "C" ) {
						$tpl->assign( STATUS , "�ն�����" );
					}
					else {
						$tpl->assign( STATUS , "Share to other Group" );
					}
				}
				else {
					if ( $version == "C" ) {
						$tpl->assign( STATUS , "������" );
					}
					else {
						$tpl->assign( STATUS , "Not Share" );
					}
				}
				$tpl->assign( DATE , $row['mtime'] );
				$tpl->assign( CONTENT , $row['content'] );
				if ( $row['upload'] == 1 ) {
					if ( $version == "C" )
						$tpl->assign( LINK , "<a href=\"../../../$course_id/coop/$coopcaseid/share/".$row['filename']."\" target='_blank' >�ɮ�</a>" );
					else
						$tpl->assign( LINK , "<a href=\"../../../$course_id/coop/$coopcaseid/share/".$row['filename']."\" target='_blank' >File</a>" );
				}
				else {
					if ( stristr($row['filename'],"http://") == NULL ) {
						$page= "http://".$row['filename'];
					}
					else {
						$page = $row['filename'];
					}
					if ( $version == "C" )
						$tpl->assign( LINK , "<a href=\"$page\" target='_blank' >�ɮ�</a>" );
					else
						$tpl->assign( LINK , "<a href=\"$page\" target='_blank' >File</a>" );
				}
				$tpl->parse ( COURSE_LIST, ".course_list" );
			}
		}
		else if ( $version == "C" )
			$message = "<font size=4 color=#00aa00><b>�|�����ѥ���귽�ɮ�</b></font><br><br>";
		else
			$message = "<font size=4 color=#00aa00><b>There is no File</b></font><br><br>";

		$tpl->assign( MES , $message );
		$tpl->assign( GID , $group_id );
		
		$content = stripslashes( $content );
		$url = stripslashes( $url );
		$tpl->assign( TEXT , $content );
		$tpl->assign( URL , $url );

		$Q2 = "select * from share_".$coopcaseid." where group_num='$coopgroup' and type = '$group_id'";
		if ( !($result2 = mysql_db_query( $DBC.$course_id, $Q2 ) ) ) {
			$message = "$message - ��ƮwŪ�����~2!!";
		}
		$Q3 = "select * from share_group_".$coopcaseid." where group_num='$coopgroup' and parent_id = '$group_id'";
		if ( !($result3 = mysql_db_query( $DBC.$course_id, $Q3 ) ) ) {
			$message = "$message - ��ƮwŪ�����~3!!";
		}
		if ( mysql_num_rows( $result2 ) == 0 && mysql_num_rows( $result3 ) == 0 ) {
			if ( $version == "C" )
				$tpl->assign( BUTTOM , "<input type=\"submit\" value=\"�R����Ƨ�\" name=\"submit\">" );
			else
				$tpl->assign( BUTTOM , "<input type=\"submit\" value=\"Del Dir\" name=\"submit\">" );
		}
		else {
			if ( $version == "C" )
				$tpl->assign( BUTTOM , "��Ƨ��|�����" );
			else
				$tpl->assign( BUTTOM , "Still have Data" );
		}

		if ( $check == 1 ) {
			$tpl->assign( ENDLINE , "</table></form></center></body></html>" );
			$tpl->parse( BODY, "body" );
			$tpl->FastPrint("BODY");
		}else {
			$tpl->assign( ENDLINE , "" );
			if ( $version == "C" )
				$tpl->define ( array ( tial => "sourcet.tpl" ) );
			else
				$tpl->define ( array ( tial => "sourcet_E.tpl" ) );
			$tpl->parse( BODY, "body" );
			$tpl->FastPrint("BODY");
			$tpl->parse( TIAL, "tial" );
			$tpl->FastPrint("TIAL");
		}
	}
	
	function GetUserAID($user_id) {

		global $DB;

		$sql = "select a_id from user where id='$user_id'";
		$result = mysql_db_query($DB, $sql) or die("��Ʈw�d�߿��~, $sql");

		// check name field. if exists, use it as poster name.
		$row = mysql_fetch_array( $result );
		
		return $row['a_id'];
	}
?>