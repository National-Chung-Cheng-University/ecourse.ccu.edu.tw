<?php
require 'fadmin.php';
update_status ("�W�ǵ��y��");

if( isset($PHPSESSID) && (session_check_teach($PHPSESSID) == 2) )
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
        if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
                show_page( "not_access.tpl" ,"��Ʈw�s�����~!!" );
        }

	if($action == "upload_comment")
        {
                upload( "comment" );
        }
	else if($action == "upload_comment_others")
	{
		upload( "comment_others" );
	}
	elseif($action == "uploadstucomment")
        {
                $S1 = "select * from homework where a_id='$work_id'";
                if ( !($result1 = mysql_db_query($DB.$course_id, $S1)) ) {
                        show_page( "not_access.tpl" ,"��ƮwŪ�����~!!" );
                }

		$Q1 = "select id from user where a_id='$sid'";
		if ( !($result2 = mysql_db_query( $DB, $Q1 ) ) ) {
                        show_page( "not_access.tpl" ,"��ƮwŪ�����~!!" );
        	}

		$rows1 = mysql_fetch_array($result2);
		$id = $rows1['id'];
		
		
		if ( $file != "none" && $file != ""){
				if ( !is_dir( "../../$course_id/homework/$work_id/$id" ) )
                        	{
                                	mkdir( "../../$course_id/homework/$work_id/$id", 0771 );
	                                chmod( "../../$course_id/homework/$work_id/$id", 0771 );
        	                }

				if ( !is_dir( "../../$course_id/homework/$work_id/$id/comment" ) )
                                {
                                        mkdir( "../../$course_id/homework/$work_id/$id/comment", 0771 );
                                        chmod( "../../$course_id/homework/$work_id/$id/comment", 0771 );
                                }

				

                		if( fileupload ( $file, "../../$course_id/homework/$work_id/$id/comment", $file_name ) ) {
                                        $message = "�ɮ� $file_name �W�ǧ���";
                                }
                                else{
                                        $message = "�ɮ� $file_name �W�ǿ��~!!";
				}
		}
		else{
			show_page( "not_access.tpl" ,"�ɮ� $file_name �W�ǥ���");
		}
		
		upload( "comment" );

	}	


	elseif($action == "uploadothercomment")
	{
		$S1 = "select * from homework where a_id='$work_id'";
                if ( !($result1 = mysql_db_query($DB.$course_id, $S1)) ) {
                        show_page( "not_access.tpl" ,"��ƮwŪ�����~!!" );
                }

                $Q1 = "select id from user where a_id='$sid'";
                if ( !($result2 = mysql_db_query( $DB, $Q1 ) ) ) {
                        show_page( "not_access.tpl" ,"��ƮwŪ�����~!!" );
                }

                $rows1 = mysql_fetch_array($result2);
                $id = $rows1['id'];

		$success=0;
		if ( !is_dir( "../../$course_id/homework/$work_id/$id" ) )
                {
                        mkdir( "../../$course_id/homework/$work_id/$id", 0771 );
                        chmod( "../../$course_id/homework/$work_id/$id", 0771 );
                }
		if ( !is_dir( "../../$course_id/homework/$work_id/$id/comment" ) )
                {
                	mkdir( "../../$course_id/homework/$work_id/$id/comment", 0771 );
                	chmod( "../../$course_id/homework/$work_id/$id/comment", 0771 );
                }
	
                $location="../../$course_id/homework/$work_id/$id/comment";
                for ( $i = 0 ; $i <= 9 ; $i ++ ) {
                        $uploadfile = "uploadfile".$i;
                        $uploadfilename = "uploadfile".$i."_name";
                        if( ($$uploadfile != "none" && $$uploadfile != "") && fileupload ( $$uploadfile, $location, $$uploadfilename ) ) {
                                $success = 1;
                        }
                }

                if($success == 1)
                {
                        if($version == "C")
                                $message = "�ɮפW�Ǧ��\!";
                        else
                                $message = "File Upload successfully!";
                }
                else
                {
                        if($version == "C")
                                $message = "�ɮ� �W�ǥ���!";
                        else
                                $message = "File Upload Unsuccessfully!";
                }
                upload("comment");
	}

	
	elseif($action == "del")
	{
		
		$S1 = "select * from homework where a_id='$work_id'";
                if ( !($result1 = mysql_db_query($DB.$course_id, $S1)) ) {
                        show_page( "not_access.tpl" ,"��ƮwŪ�����~!!" );
                }

                $Q1 = "select id from user where a_id='$sid'";
                if ( !($result2 = mysql_db_query( $DB, $Q1 ) ) ) {
                        show_page( "not_access.tpl" ,"��ƮwŪ�����~!!" );
                }

                $rows1 = mysql_fetch_array($result2);
                $id = $rows1['id'];

		if(strlen($filename) == 0) {
                        upload( "comment" );
                        exit;
                }
                $_target = realpath( "../../$course_id/homework/$work_id/$id/comment/$filename" );
                $doc_root = "/$course_id/homework/$work_id/$id/comment/";
                if ( is_file( $_target ) ) {
                        // �w���ˬd
                        $_target2 = str_replace ( "\\", "/", $_target );
                        $pos = strpos($_target2, $doc_root);
                        if($pos === false) {
                                if ( $version == "C" ) {
                                        show_page("not_access.tpl", "�v�����~");
                                }
                                else {
                                        show_page("not_access.tpl", "Access Denied.");
                                }
                                exit();
                        }

                        if(unlink($_target)) {
                                if ( $version == "C" )
                                        $message = "�ɮ� $filename �R������";
                                else
                                        $message = "File $filename Delete Succes";
                        }
                        else {

                                if ( $version == "C" )
                                        $message = "�ɮ� $filename �R�����~!!";
                                else
                                        $message = "File $filename Delete false";
                        }
		}
                else {
                        if ( $version == "C" )
                                $message = "�ɮ� $filename �R�����~!!";
                        else
                                $message = "File $filename Delete false";
                }
                upload( "comment" );
	}


	else
	{
		if( $version=="C" )
	                show_page( "not_access.tpl" ,"�A�S���v���ϥΦ��\��");
        	else
                	show_page( "not_access.tpl" ,"You have No Permission!!");
	}
}

function upload ( $type ) {
        global $version, $work_id,$sid;
        include("class.FastTemplate.php3");
        $tpl = new FastTemplate("./templates");
        $tpl->assign(SKINNUM,1);
        if($version == "C")
                if ( $type == "comment_others" )
                        $tpl->define(array(main=>"uploadcomment_others.tpl"));
                else
                        $tpl->define(array(main=>"uploadcomment.tpl"));
        else
                if ( $type == "comment_others" )
                        $tpl->define(array(main=>"uploadcomment_others_E.tpl"));
                else
                        $tpl->define(array(main=>"uploadcomment_E.tpl"));
        if ( $type != "comment_others" )
                filelist ( $tpl );
        $tpl->assign(GOTOURL,"post_comment.php");
        $tpl->assign(WORKID,$work_id);
	$tpl->assign(PSSID,$PHPSESSID);
	echo $_GET['anchor'];
	//added by jimmykuo @20100423 �[�U���o��ت��O�b���^�W�@����,��^��쥻�ק�ǥͪ����I(��m)
	$tpl->assign(DEST,$_GET['anchor']);
        $tpl->assign(IMG,"a322.gif");
	$tpl->assign(SNO,$sid);
        $tpl->parse(BODY,"main");
        $tpl->FastPrint("BODY");
}

function filelist ( &$tpl) {
        global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $work_id, $course_id, $version, $sid, $skinnum;
	
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
                show_page( "not_access.tpl" ,"��Ʈw�s�����~!!" );
        }
        $Q1 = "SELECT id FROM user WHERE a_id = '$sid'";

        if ( !($result1 = mysql_db_query( $DB, $Q1 ) ) ) {
                show_page( "not_access.tpl" ,"��ƮwŪ�����~!!" );
        }

        if ( mysql_num_rows($result1) != 0 ) {
		$tpl->define_dynamic("file_list", "main");
                $tpl->assign( SKINNUM , $skinnum );
                $rows = mysql_fetch_array($result1);
                $work_dir = "../../$course_id/homework/$work_id/$rows[0]/comment";
                if ( is_dir( $work_dir ) ) {
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
                                        $tpl->assign("DELETE", "<a href=\"./post_comment.php?action=del&filename=$file&work_id=$work_id&sid=$sid\" onclick=\"return confirm('�A�T�w�n�R���o���ɮ׶�?');\">�R���o��
�ɮ�</a>" );
                                	}
	                                else {
                                        $tpl->assign("DELETE", "<a href=\"./post_comment.php?action=del&filename=$file&work_id=$work_id&sid=$sid\" onclick=\"return confirm('Suer to Delete?');\">Delete</a>" );
        	                        }
                
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
                        $handle->close();
                }
                if($set_file==0) {
                        $tpl->assign("FILE_N", "");
                        $tpl->assign("FILE_SIZE", "");
                        $tpl->assign("FILE_DATE", "");
			$tpl->assign("DELETE", "");
	                $tpl->assign("F_COLOR", "#edf3fa");
                }
	}
}


?>
