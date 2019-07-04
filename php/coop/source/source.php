<?php
	require 'fadmin.php';
	update_status ("�[�ݸ귽");
	if ( !(isset($PHPSESSID) && session_check_teach($PHPSESSID) && ($check = check_group ( $course_id, $coopgroup, $coopcaseid )) != 0 ) ) {
		show_page( "not_access.tpl" ,"�v�����~");
	}
	
	show_main ();

	function show_main ( $reload=0 ) {
		global $version, $check, $skinnum, $teacher, $course_id, $coopgroup, $coopcaseid, $user_id;
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		$tpl->assign( SKINNUM , $skinnum );
		$tpl->define ( array ( body => "source.tpl" ) );

		$tpl->define_dynamic ( "course_list" , "body" );
		
		if($reload == 1) 
			$tpl->assign("RELOAD_CTRL", " onLoad=\"parent.tree.location.reload();\"");
		else
			$tpl->assign("RELOAD_CTRL", "");
		$color = "#4d6eb2";
		$tpl->assign( COLOR , $color );
		if ( $version == "C" ) {
			$tpl->assign( CHECK , "<font color=#FFFFFF><b>�s��</b></font>" );
			$tpl->assign( DATE , "<font color=#FFFFFF><b>���</b></font>" );
			$tpl->assign( CONTENT , "<font color=#FFFFFF><b>���e�y�z</b></font>" );
			$tpl->assign( STATUS , "<font color=#FFFFFF><b>���A</b></font>" );
			$tpl->assign( LINK , "<font color=#FFFFFF><b>�ɮ׳s��</b></font>" );
		}
		else {
			$tpl->assign( CHECK , "<font color=#FFFFFF><b>No.</b></font>" );
			$tpl->assign( DATE , "<font color=#FFFFFF><b>Date</b></font>" );
			$tpl->assign( CONTENT , "<font color=#FFFFFF><b>Content</b></font>" );
			$tpl->assign( STATUS , "<font color=#FFFFFF><b>Status</b></font>" );
			$tpl->assign( LINK , "<font color=#FFFFFF><b>File Link</b></font>" );
		}
		$tpl->parse ( COURSE_LIST, ".course_list" );
		
		global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $message, $group_id, $content, $url;
		$Q1 = "select * from share_".$coopcaseid." where type = '$group_id' and ((group_num='$coopgroup' and (share='1' || student_id = '".GetUserAID($user_id)."')) || share='2') order by mtime DESC";
		if ( !($sqllink = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$message = "$message - ��Ʈw�s�����~!!";
		}
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
				$tpl->assign( CHECK , "$i" );
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

		$tpl->parse( BODY, "body" );
		$tpl->FastPrint("BODY");
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