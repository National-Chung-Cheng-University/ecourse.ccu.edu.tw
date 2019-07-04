<?php
require 'fadmin.php';
update_status ("上傳評語檔");

if( isset($PHPSESSID) && (session_check_teach($PHPSESSID) == 2) )
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
        if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
                show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
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
                        show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
                }

		$Q1 = "select id from user where a_id='$sid'";
		if ( !($result2 = mysql_db_query( $DB, $Q1 ) ) ) {
                        show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
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
                                        $message = "檔案 $file_name 上傳完成";
                                }
                                else{
                                        $message = "檔案 $file_name 上傳錯誤!!";
				}
		}
		else{
			show_page( "not_access.tpl" ,"檔案 $file_name 上傳失敗");
		}
		
		upload( "comment" );

	}	


	elseif($action == "uploadothercomment")
	{
		$S1 = "select * from homework where a_id='$work_id'";
                if ( !($result1 = mysql_db_query($DB.$course_id, $S1)) ) {
                        show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
                }

                $Q1 = "select id from user where a_id='$sid'";
                if ( !($result2 = mysql_db_query( $DB, $Q1 ) ) ) {
                        show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
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
                                $message = "檔案上傳成功!";
                        else
                                $message = "File Upload successfully!";
                }
                else
                {
                        if($version == "C")
                                $message = "檔案 上傳失敗!";
                        else
                                $message = "File Upload Unsuccessfully!";
                }
                upload("comment");
	}

	
	elseif($action == "del")
	{
		
		$S1 = "select * from homework where a_id='$work_id'";
                if ( !($result1 = mysql_db_query($DB.$course_id, $S1)) ) {
                        show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
                }

                $Q1 = "select id from user where a_id='$sid'";
                if ( !($result2 = mysql_db_query( $DB, $Q1 ) ) ) {
                        show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
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
                        // 安全檢查
                        $_target2 = str_replace ( "\\", "/", $_target );
                        $pos = strpos($_target2, $doc_root);
                        if($pos === false) {
                                if ( $version == "C" ) {
                                        show_page("not_access.tpl", "權限錯誤");
                                }
                                else {
                                        show_page("not_access.tpl", "Access Denied.");
                                }
                                exit();
                        }

                        if(unlink($_target)) {
                                if ( $version == "C" )
                                        $message = "檔案 $filename 刪除完成";
                                else
                                        $message = "File $filename Delete Succes";
                        }
                        else {

                                if ( $version == "C" )
                                        $message = "檔案 $filename 刪除錯誤!!";
                                else
                                        $message = "File $filename Delete false";
                        }
		}
                else {
                        if ( $version == "C" )
                                $message = "檔案 $filename 刪除錯誤!!";
                        else
                                $message = "File $filename Delete false";
                }
                upload( "comment" );
	}


	else
	{
		if( $version=="C" )
	                show_page( "not_access.tpl" ,"你沒有權限使用此功能");
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
	//added by jimmykuo @20100423 加下面這行目的是在按回上一頁時,能回到原本修改學生的錨點(位置)
	$tpl->assign(DEST,$_GET['anchor']);
        $tpl->assign(IMG,"a322.gif");
	$tpl->assign(SNO,$sid);
        $tpl->parse(BODY,"main");
        $tpl->FastPrint("BODY");
}

function filelist ( &$tpl) {
        global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $work_id, $course_id, $version, $sid, $skinnum;
	
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
                show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
        }
        $Q1 = "SELECT id FROM user WHERE a_id = '$sid'";

        if ( !($result1 = mysql_db_query( $DB, $Q1 ) ) ) {
                show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
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
                                // 除了 '.' '..'之外的檔案輸出
                                        $tpl->assign("FILE_N", $file);
                                        $tpl->assign("FILE_LINK", $work_dir."/".$file);
                                        $tpl->assign("FILE_SIZE", filesize($work_dir."/".stripslashes($file)));
                                        $tpl->assign("FILE_DATE", date("Y-m-d H:i:s",filemtime($work_dir."/".$file)));

					if ( $version == "C" ) {
                                        $tpl->assign("DELETE", "<a href=\"./post_comment.php?action=del&filename=$file&work_id=$work_id&sid=$sid\" onclick=\"return confirm('你確定要刪除這個檔案嗎?');\">刪除這個
檔案</a>" );
                                	}
	                                else {
                                        $tpl->assign("DELETE", "<a href=\"./post_comment.php?action=del&filename=$file&work_id=$work_id&sid=$sid\" onclick=\"return confirm('Suer to Delete?');\">Delete</a>" );
        	                        }
                
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
