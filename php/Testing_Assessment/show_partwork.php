<?php
require 'fadmin.php';
update_status ("線上作業中");

if(isset($PHPSESSID) && session_check_teach($PHPSESSID))
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}
	if($action == "showwork")
	{
		$Q1 = "SELECT question,q_type, name FROM homework WHERE a_id='$work_id'";
		if ( !($result1 = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫更新錯誤!!" );
		}
		$row1 = mysql_fetch_array($result1);
		$q_file = "../../$course_id/homework/$work_id/teacher/Question".$row1['q_type'];
		if ( $row1['question'] == "" && $row1['q_type'] != "" && is_file ( $q_file ) ) {
			header( "location: $q_file" );
			exit;
		}
		else {
			include("class.FastTemplate.php3");
			$tpl=new FastTemplate("./templates");
			if($version == "C")
			{
				$tpl->define(array(main=>"showwork.tpl"));
				$tpl->assign(SHOWTYPE,"作業題目");
			}
			else
			{
				$tpl->define(array(main=>"showwork_E.tpl"));
				$tpl->assign(SHOWTYPE,"Topic");
			}
			$tpl->assign( SKINNUM , $skinnum );
			$content = $row1['question'];
			if ( stristr($content,"<html>") == NULL ) {
				$content=htmlspecialchars( $content );
				$content=ereg_replace("\n","<BR>\n",$content);
			}
			$tpl->assign(QUESTION,$content);
			$tpl->assign(WORKNAME,$row1['name']);
			
			$tpl->parse(BODY,"main");
			$tpl->FastPrint("BODY");
		}
	}
	elseif($action == "seeans")
	{
		$Q1 = "SELECT answer,ans_type,name, public FROM homework WHERE a_id='$work_id'";
		if ( !($result1 = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫更新錯誤!!" );
		}
		$row1 = mysql_fetch_array($result1);
		if ( $row1['public'] != "1" && $row1['public'] != "0" ) {
			$ans_file = "../../$course_id/homework/$work_id/teacher/Answer".$row1['ans_type'];
			if ( $row1['answer'] == "" && $row1['ans_type'] != "" && is_file ( $ans_file ) ) {
				header( "location: $ans_file" );
				exit;
			}
			else {
				include("class.FastTemplate.php3");
				$tpl=new FastTemplate("./templates");
				if($version == "C")
				{
					$tpl->define(array(main=>"showwork.tpl"));
					$tpl->assign(SHOWTYPE,"作業解答");
				}
				else
				{
					$tpl->define(array(main=>"showwork_E.tpl"));
					$tpl->assign(SHOWTYPE,"Answer");
				}
				$tpl->assign( SKINNUM , $skinnum );
				$content = $row1['answer'];
				if ( stristr($content,"<html>") == NULL ) {
					$content=htmlspecialchars( $content );
					$content=ereg_replace("\n","<BR>\n",$content);
				}
				$tpl->assign(QUESTION,$content);
				$tpl->assign(WORKNAME,$row1['name']);
				$tpl->parse(BODY,"main");
				$tpl->FastPrint("BODY");
			}
		}
		else {
			if($version == "C")
				$message = "解答尚未公佈!";
			else
				$message = "Answer is not public!";
			show_page_d();
		}
	}
	elseif($action == "editanswer")
	{
		$Q1 = "SELECT name FROM homework WHERE a_id='$work_id'";
		if ( !($result1 = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫更新錯誤!!" );
		}
		$row1 = mysql_fetch_array( $result1 );
		$Q2 = "SELECT u.a_id , tc.credit FROM user u, take_course tc WHERE id='$user_id' and tc.student_id = u.a_id and tc.course_id='$course_id' and tc.year='$course_year' and tc.term = '$course_term'";
		if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫更新錯誤!!" );
		}
		$row2 = mysql_fetch_array( $result2 );
		if ( $row2['credit'] == 1 ) {
			$Q3 = "SELECT work FROM handin_homework WHERE homework_id='$work_id' AND student_id='$row2[0]'";
			if ( !($result3 = mysql_db_query( $DB.$course_id, $Q3 ) ) ) {
				show_page( "not_access.tpl" ,"資料庫更新錯誤!!" );
			}
			$row3 = mysql_fetch_array( $result3 );
			
			include("class.FastTemplate.php3");
			$tpl=new FastTemplate("./templates");
			if($version == "C")
				$tpl->define(array(main=>"editanswer.tpl"));
			else
				$tpl->define(array(main=>"editanswer_E.tpl"));
			$tpl->assign( SKINNUM , $skinnum );
			$tpl->assign(WORKNAME,$row1[0]);
			$tpl->assign(WORKID,$work_id);
			$ans = $row3[0];
			$ans = ereg_replace("<html><body onload=parent.bMain=true;>","",$ans);
			$ans = ereg_replace("</body></html>","",$ans);
			$ans=ereg_replace("<BR>","\n",$ans);
			$tpl->assign(CONTENT,$ans);
			$tpl->parse(BODY,"main");
			$tpl->FastPrint("BODY");
		}
		else
			show_page_d();
	}
	elseif($action == "handinwork")
	{
		$Q1 = "SELECT a_id FROM user WHERE id='$user_id'";
		if ( !($result1 = mysql_db_query( $DB, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫更新錯誤!!" );
		}
		$row1 = mysql_fetch_array( $result1 );
		
		$content = $ans;
		if ( stristr($content,"<html>") == NULL ) {
			$content=htmlspecialchars( $content );
			$content=ereg_replace("\n","<BR>",$content);
		}
		
		$ans = "<html><body onload=parent.bMain=true;>\n".$content."\n</body></html>";
		$handin_time=date("Y-m-d");
		
		$Q2 = "UPDATE handin_homework SET work='$ans',handin_time='$handin_time', upload = '0' WHERE homework_id='$work_id' AND student_id='$row1[0]'";
		if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫更新錯誤!!" );
		}
		
		if($version == "C")
			$message = "完成繳交作業!";
		else
			$message = "Hand in hoemwork completely!";
		show_page_d();
	}
	elseif($action == "uploadwork")
	{
		$Q1 = "SELECT u.name, tc.credit FROM user u, take_course tc WHERE id='$user_id' and tc.student_id = u.a_id and tc.course_id='$course_id' and tc.year='$course_year' and tc.term = '$course_term'";
		$Q2 = "SELECT public FROM homework WHERE a_id = '$work_id'";
		if ( !($result1 = mysql_db_query( $DB, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
		}
		if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
		}
		$row1 = mysql_fetch_array( $result1 );
		$row2 = mysql_fetch_array( $result2 );
		if ( $row1['credit'] == 1 ) {
			if ( $row2['public'] == "2" || $row2['public'] == "3" ) {
				if ( $version == "C" )
					$message = "解答已公佈，禁止上傳";
				else
					$message = "Ans was publiced";
				show_page_d( );
			}
			else {
				if ( $row1[0] != NULL )
					$name = $row1[0];
				else
					$name = $user_id;
				upload ( "stu" );
			}
		}
		else
			show_page_d();
	}
	elseif ( $action == "del" ) {
	  	if(strlen($filename) == 0) {
			filelist ( "mywork" );
			exit;
		}
		$_target = realpath( "../../$course_id/homework/$work_id/$user_id/$filename" );
		$doc_root = "/$course_id/homework/$work_id/$user_id/";
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
		filelist ( "mywork" );
	}
	elseif($action == "uploadstuwork")
	{
		$success = 0;
		if($uploadfile1 != "none")
		{
			if ( !is_dir( "../../$course_id/homework/$work_id/$user_id" ) ) 
			{
				mkdir( "../../$course_id/homework/$work_id/$user_id", 0771 );
				chmod( "../../$course_id/homework/$work_id/$user_id", 0771 );
			}
			$ext = strrchr( $uploadfile1_name, '.' );
			$filename=$user_id.$ext;
			$location="../../$course_id/homework/$work_id/$user_id";
			if ( fileupload ( $uploadfile1, $location, $filename ) ) {
				$Q1 = "SELECT a_id FROM user WHERE id='$user_id'";
				if ( !($result1 = mysql_db_query( $DB, $Q1 ) ) ) {
					show_page( "not_access.tpl" ,"資料庫更新錯誤!!" );
				}
				$row1 = mysql_fetch_array( $result1 );
	
				$handin_time=date("Y-m-d");
				$location2="/$course_id/homework/$work_id/$user_id/$filename";
				$Q2 = "UPDATE handin_homework SET work='<a href=$location2>$filename</a>',upload='1',handin_time='$handin_time' WHERE homework_id='$work_id' AND student_id='$row1[0]'";
				if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
					show_page( "not_access.tpl" ,"資料庫更新錯誤!!" );
				}
				$success = 1;
			}
		}
		if ( $success ) {
			if($version == "C")
				$message = "檔案上傳成功!";
			else
				$message = "Upload file completely !";
		}
		else
		{
			if($version == "C")
				$message = "檔案上傳失敗!";
			else
				$message = "Failed to upload file !";
		}
		show_page_d();
	}
	elseif($action == "uploadothers")
	{
		upload ( "others" );
	}
	elseif($action == "uploadotherwork")
	{
		$success=0;
		if ( !is_dir( "../../$course_id/homework/$work_id/$user_id" ) ) 
		{
			mkdir( "../../$course_id/homework/$work_id/$user_id", 0771 );
			chmod( "../../$course_id/homework/$work_id/$user_id", 0771 );
		}
		$location="../../$course_id/homework/$work_id/$user_id";
		for ( $i = 0 ; $i <= 9 ; $i ++ ) {
			$uploadfile = "uploadfile".$i;
			$uploadfilename = "uploadfile".$i."_name";
			if($$uploadfile != "none" && $$uploadfile != "")
			{
				if ( fileupload ( $$uploadfile, $location, $$uploadfilename ) ) {
					$Q1 = "SELECT a_id FROM user WHERE id='$user_id'";
					if ( !($result1 = mysql_db_query( $DB, $Q1 ) ) ) {
						show_page( "not_access.tpl" ,"資料庫更新錯誤!!" );
					}
					$row1 = mysql_fetch_array( $result1 );
		
					$handin_time=date("Y-m-d");
					$Q2 = "UPDATE handin_homework SET upload='1',handin_time='$handin_time' WHERE homework_id='$work_id' AND student_id='$row1[0]'";
					if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
						show_page( "not_access.tpl" ,"資料庫更新錯誤!!" );
					}
					$success = 1;
				}
			}
		}
		if($success == 1)
		{
			if($version == "C")
				$message = "作業 $rows[0] 檔案上傳成功!";
			else
				$message = "Homework $rows[0] File Upload successfully!";
		}
		else
		{
			if($version == "C")
				$message = "作業 $rows[0] 檔案上傳失敗!";
			else
				$message = "Homework $rows[0] File Upload Unsuccessfully!";
		}
		show_page_d();
	}
	elseif($action == "seemywork")
	{
		$Q1 = "SELECT u.a_id , tc.credit FROM user u, take_course tc WHERE id='$user_id' and tc.student_id = u.a_id and tc.course_id='$course_id' and tc.year='$course_year' and tc.term = '$course_term'";
		if ( !($result1 = mysql_db_query( $DB, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫更新錯誤!!" );
		}
		$row1 = mysql_fetch_array( $result1 );
		if ( $row1['credit'] == 1 ) {
			$Q2 = "SELECT work, upload FROM handin_homework WHERE homework_id='$work_id' AND student_id='$row1[0]'";
	
			if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
				show_page( "not_access.tpl" ,"資料庫更新錯誤!!" );
			}
			$row2 = mysql_fetch_array( $result2 );
			if ( $row2['upload'] == "0" ) {
				echo "<HTML>";
				echo "<head><link rel='stylesheet' type='text/css' href='./default.css'></head>";
				echo $row2[0];
				echo "</HTML>";
			}
			else {
				filelist( "mywork" );
			}
		}
		else
			show_page_d();
	}
	elseif($action == "seegoodwork")
	{
		$Q1 = "SELECT h.name, hh.student_id FROM homework h,handin_homework hh WHERE h.a_id='$work_id' AND h.a_id=hh.homework_id AND hh.public='1'";
		if ( !($result1 = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫寫入錯誤!!" );
		}
		
		if ( mysql_num_rows($result1) != 0 ) {
			include("class.FastTemplate.php3");
			$tpl=new FastTemplate("./templates");
			if ( $version == "C" )
				$tpl->define(array(main=>"goodworklist.tpl"));
			else
				$tpl->define(array(main=>"goodworklist_E.tpl"));
			$tpl->define_dynamic("row","main");
			$tpl->assign( SKINNUM , $skinnum );
			while ( $rows1 = mysql_fetch_array($result1) )
			{
				$Q2 = "Select id, name from user where a_id = '".$rows1['student_id']."'";
				if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) ) {
					 echo ( "資料庫讀取錯誤!!" );
					 exit;
				}
				$rows2 = mysql_fetch_array( $result2 );
				if ( $rows2['name'] != NULL )
					$name = $rows2['name'];
				else
					$name = $rows2['id'];
				$tpl->assign(WORKNAME,$rows1[0]);
				$tpl->assign(SNAME,$name);
				$tpl->assign(SNO,$rows1['student_id']);
				$tpl->assign(WORKID,$work_id);
				$tpl->parse(ROW,".row");
			}
			$tpl->parse(BODY,"main");
			$tpl->FastPrint("BODY");
		}
		else
		{
			if( $version=="C" )
				$message = "目前沒有優良作業可供觀看!";
			else
				$message = "There is no Good Homework for reference!!";
			show_page_d();
		}
	}
	elseif($action == "showgoodwork")
	{
		$Q1 = "SELECT work,upload FROM handin_homework WHERE homework_id='$work_id' AND public='1' AND student_id ='$sid'";
		if ( !($result1 = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫寫入錯誤!!" );
		}
		$row1 = mysql_fetch_array( $result1 );
		if ( $row1['upload'] == "0" ) {
			echo "<HTML>";
			echo "<head><link rel='stylesheet' type='text/css' href='./default.css'></head>";
			echo $row1[0];
			echo "</HTML>";
		}
		else {
			filelist( "goodwork" );
		}
	}
	else
	{
		show_page_d ();
	}
}
else
{
	if( $version=="C" )
		show_page( "not_access.tpl" ,"你沒有權限使用此功能");
	else
		show_page( "not_access.tpl" ,"You have No Permission!!");
}

function show_page_d () {
  	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $version, $message, $course_id, $PHPSESSID, $skinnum, $chap;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}
	$Q1 = "SELECT name,percentage,due,public,a_id FROM homework WHERE (public='1' OR public='3') AND chap_num ='$chap' ORDER BY a_id";
	if ( !($result1 = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
		show_page( "not_access.tpl" ,"資料庫寫入錯誤!!" );
	}
	
	if(mysql_num_rows($result1) != 0) {
		include("class.FastTemplate.php3");
		$tpl=new FastTemplate("./templates");
		if ( $version == "C" )
			$tpl->define(array(main=>"show_allwork.tpl"));
		else
			$tpl->define(array(main=>"show_allwork_E.tpl"));
		$tpl->define_dynamic("row","main");
		$tpl->assign( SKINNUM , $skinnum );
		$tpl->assign(MESSAGE,$message);
		$color == "#F0FFEE";
		while ( $row1 = mysql_fetch_array($result1) )
		{
			if ( $color == "#F0FFEE" )
				$color = "#E6FFFC";
			else
				$color = "#F0FFEE";
			$tpl->assign( COLOR , $color );
			$tpl->assign(CHAP_NUM,$chap);	
			$tpl->assign(WORKNAME,$row1[0]);
			$tpl->assign(WORKRATIO,$row1[1]);
			$tpl->assign(WORKDUE,$row1[2]);
			$tpl->assign(WORKID,$row1[4]);
			$tpl->assign(PHPID,$PHPSESSID);
			if($row1[3] == "1")
			{
				$tpl->assign(SEEANS,"未公佈");
			}
			elseif($row1[3] == "3")
			{
				$tpl->assign(SEEANS,"答案");
			}
			$tpl->parse(ROW,".row");
		}
		$tpl->parse(BODY,"main");
		$tpl->FastPrint("BODY");  
	}
	else
	{
		if( $version=="C" )
			show_page( "not_access.tpl" ,"目前沒有任何作業!");
		else
			show_page( "not_access.tpl" ,"There ia No Homework!!");
	}
}

function upload ( $type ) {
	global $version, $work_id, $user_id, $course_id, $skinnum;
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate("./templates");
	if($version == "C") {
		if ( $type == "others" )
			$tpl->define(array(main=>"uploadothers.tpl"));
		else
			$tpl->define(array(main=>"uploadstuwork.tpl"));
	}
	else {
		if ( $type == "others" )
			$tpl->define(array(main=>"uploadothers_E.tpl"));
		else
			$tpl->define(array(main=>"uploadstuwork_E.tpl"));
	}
	$tpl->assign( SKINNUM , $skinnum );
	$tpl->assign(GOTOURL,"show_allwork.php");
	$tpl->assign(WORKID,$work_id);
	$tpl->assign(IMG,"b41.gif");
	$tpl->parse(BODY,"main");
	$tpl->FastPrint("BODY");
}

function filelist ( $type ) {
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $work_id, $course_id, $version, $sid, $user_id;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}
	if ( isset( $sid ) && $type == "goodwork" ) {
		$Q1 = "SELECT id FROM user WHERE a_id = '$sid'";	
		if ( !($result1 = mysql_db_query( $DB, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
		}
		$rows = mysql_fetch_array($result1);
		$id = $rows['id'];
	}
	else {
		$Q1 = "SELECT a_id FROM user WHERE id = '$user_id'";	
		if ( !($result1 = mysql_db_query( $DB, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
		}
		$rows = mysql_fetch_array($result1);
		$id = $user_id;
		$sid = $rows['a_id'];
	}
	if ( mysql_num_rows($result1) != 0 && $id != "" ) {
		$Q1 = "SELECT public FROM handin_homework WHERE homework_id='$work_id' AND student_id ='$sid'";
		if ( !($result1 = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫寫入錯誤!!" );
		}
		$row1 = mysql_fetch_array( $result1 );
		if ( ($row1['public'] == "1" && $type == "goodwork") || $type == "mywork" ) {
			include("class.FastTemplate.php3");
			$tpl = new FastTemplate("./templates");
			if ( $version == "C" )
				$tpl->define(array(main=>"workfilelist.tpl"));
			else
				$tpl->define(array(main=>"workfilelist_E.tpl"));
			$tpl->define_dynamic("file_list", "main");
			$rows = mysql_fetch_array($result1);
			$work_dir = "../../$course_id/homework/$work_id/$id";
			if ( is_dir( $work_dir ) ) {
				$handle = dir($work_dir);
				$i=false;
				while (( $file = $handle->read() ) ) {
					if(strcmp($file,".") !=0 && strcmp($file,"..")) {   
					// 除了 '.' '..'之外的檔案輸出
						$tpl->assign("FILE_N", $file);
						$tpl->assign("FILE_LINK", $work_dir."/".$file);
						$tpl->assign("FILE_SIZE", filesize($work_dir."/".stripslashes($file)));
						if ( $type == "mywork" ) {
							if ( $version == "C" ) {
								$tpl->assign("FILE_DATE", date("Y-m-d H:i:s",filemtime($work_dir."/".$file))."<td><a href=\"./show_allwork.php?action=del&filename=$file&work_id=$work_id\" onclick=\"return confirm('你確定要刪除這個檔案嗎?');\">刪除這個檔案</a>" );
							}
							else {
								$tpl->assign("FILE_DATE", date("Y-m-d H:i:s",filemtime($work_dir."/".$file))."<td><a href=\"./show_allwork.php?action=del&filename=$file&work_id=$work_id\" onclick=\"return confirm('Suer to Delete?');\">Delete</a>" );
							}
						}
						else
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
				$handle->close();
			}
			if($set_file==0) {
				$tpl->assign("FILE_N", "");
				$tpl->assign("FILE_SIZE", "");
				$tpl->assign("FILE_DATE", "");
				$tpl->assign("F_COLOR", "#edf3fa");
			}
			if ( $type == "mywork" ) {
				if ( $version == "C" )
					$tpl->assign(DELETE , "<td><font color=#ffffff>刪除檔案</font>" );
				else
					$tpl->assign(DELETE , "<td><font color=#ffffff>Delete File</font>" );
			}
			else
				$tpl->assign(DELETE , "" );
			$tpl->assign(WORKID,$work_id);
			$tpl->assign(STUWORK,"");
			
			$tpl->parse(BODY,"main");
			$tpl->FastPrint("BODY");
		}
		else {
			if( $version=="C" )
				show_page( "not_access.tpl" ,"此作業未公開!!");
			else
				show_page( "not_access.tpl" ,"Not a Public Work!!");
		}
	}
	else {
		if( $version=="C" )
			show_page( "not_access.tpl" ,"學生不存在!");
		else
			show_page( "not_access.tpl" ,"No This Student!!");
	}
}
?>
