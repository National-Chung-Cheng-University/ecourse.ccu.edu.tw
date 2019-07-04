<?php
require 'fadmin.php';
require '../CoreDescript.php';
update_status ("編輯作業");

if( isset($PHPSESSID) && (session_check_teach($PHPSESSID) == 2) )
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}

	if($action == "seeque")
	{
		seecontent ( "que" );
	}
	elseif($action == "seeans")
	{
		seecontent ( "ans" );
	}
	elseif($action == "modifynr")
	{
		$Q1 = "SELECT name,due,percentage,chap_num,CoreAbilities FROM homework WHERE a_id='$work_id'";
		if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			$message = "資料庫讀取錯誤!!";
		}
		$rows = mysql_fetch_array($result);
		$work_name = $rows[0];
		$work_due = $rows[1];
		$work_ratio = $rows[2];
		$chap_num = $rows[3];
		$CoreAbilities = $rows[4];
		show_modifynr();
	}
	elseif($action == "updatenr")
	{
		$Q1 = "SELECT name,a_id FROM homework WHERE name='$work_name'";
		if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
		}
		$rows = mysql_fetch_array($result);

		if(($work_name == $rows[0])&&($work_id != $rows[1]))
		{
			if( $version=="C" )
				$message = "名稱已存在,請更換作業名稱!";
			else
				$message = "This homework name exists, and please change the homework name!";
			show_modifynr();
		}
		else if(($work_name == "")||($work_ratio == "")||($work_due == ""))
		{
			if( $version=="C" )
				$message = "請輸入作業名稱、比例和期限!";
			else
				$message = "Please input homework name、ratio and due!";
			show_modifynr();
		}
		else if($work_ratio > 100 || $work_ratio < 0) {
			if( $version=="C" )
				$message = "比例須介於0~100之間";
			else
				$message = "Please input ratio between 0 and 100!";
			show_modifynr();
		}
		else
		{
			//devon@2006-01-09----加入chap_num="$chap_num'這個值-----
			//按下確定更新
			for($i=0; $i<count($CoreAbility); $i++){
				if(!isset($str)) $str = $CoreAbility[$i];
				else $str = $str . "," . $CoreAbility[$i];
			}

			$Q2 = "UPDATE homework SET name='$work_name', chap_num='$chap_num',percentage='$work_ratio',due='$work_due',CoreAbilities='$str' WHERE a_id='$work_id'";
			if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
				show_page( "not_access.tpl" ,"資料庫寫入錯誤!!" );
			}
			if($version == "C")
				$message = "作業名稱、比例和期限修改成功!";
			else
				$message = "Update Successfully !";
			show_page_d();
		}
	}
	elseif($action == "editwork")
	{
		$Q1 = "SELECT due, percentage, question, name FROM homework WHERE a_id='$work_id'";
		if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
		}
		$rows = mysql_fetch_array( $result );
		$work_name = $rows[3];
		$work_due = $rows[0];
		$work_ratio = $rows[1];
		$content = $rows[2];
		$workid = $work_id;
		show_content ( "que" );
	}
	elseif($action == "updatework")
	{
		if( $content == "" )
		{
			if($version == "C")
				$message = "未輸入題目!";
			else 
				$message = "Question is Null!";
			show_content( "que" );
		}
		else
		{
			$Q1 = "UPDATE homework SET question='$content' WHERE a_id='$work_id'";
			if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
				$message = "資料庫寫入錯誤!!";
			}
			else
				if($version == "C")
					$message = "題目更新完成!!";
				else 
					$message = "Question updated!";
			show_page_d( );
		}
	}
	elseif($action == "editans")
	{
		$Q1 = "SELECT due, percentage, answer, name FROM homework WHERE a_id='$work_id'";
		if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
		}
		$rows = mysql_fetch_array( $result );
		$work_name = $rows[3];
		$work_due = $rows[0];
		$work_ratio = $rows[1];
		$content = $rows[2];
		$workid = $work_id;
		show_content ( "ans" );
	}
	elseif($action == "updateans")
	{
		if( $content == "" )
		{
			if($version == "C") {
				$message = "未輸入答案!";
			}
			else {
				$message = "Answer is Null!";
			}
			show_content( "ans" );
		}
		else
		{
			$Q1 = "UPDATE homework SET answer='$content' WHERE a_id='$work_id'";
			if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
				$message = "資料庫寫入錯誤!!";
			}
			else
				if($version == "C")
					$message = "解答更新完成!!";
				else 
					$message = "Answer updated!";
			show_page_d ();
		}
	}
	elseif($action == "upload")
	{
		upload( "quention" );
	}
	elseif ( $action == "del" ) {
	  	if(strlen($filename) == 0) {
			upload( "quention" );
			exit;
		}
		$_target = realpath( "../../$course_id/homework/$work_id/teacher/$filename" );
		$doc_root = "/$course_id/homework/$work_id/teacher/";
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
		upload( "quention" );
	}
	elseif($action == "uploadstuwork")
	{
		$S1 = "select * from homework where a_id='$work_id'";
		if ( !($result1 = mysql_db_query($DB.$course_id, $S1)) ) {
			show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
		}
		$rows = mysql_fetch_array( $result1 );
		$success=0;
		$ext1 = strrchr( $uploadfile1_name, '.' );
		$ext2 = strrchr( $uploadfile2_name, '.' );
		$filename1=$work_name."Question".$ext1;
		$filename2=$work_name."Answer".$ext2;
		$location = "../../$course_id/homework/$work_id/teacher";
		if(($uploadfile1 != "none")&& fileupload ( $uploadfile1, $location, $filename1 ) )
		{
			if ( $ext1 != $rows['q_type'] ) {
				$q_old = "$location/Question".$rows['q_type'];
				if ( is_file( $q_old ) ) {
					unlink ( $q_old );
				}
			}
//			$Q1 = "UPDATE homework SET question='<a href=$location/$filename1>$filename1</a>', q_type='$ext1' WHERE a_id='$work_id'";
			$Q1 = "UPDATE homework SET question='', q_type='$ext1' WHERE a_id='$work_id'";
			$result1 = mysql_db_query($DB.$course_id, $Q1);
			$success=1;
		}
		if(($uploadfile2 != "none")&& fileupload ( $uploadfile2, $location, $filename2 ) )
		{
			if ( $ext2 != $rows['ans_type'] ) {
				$ans_old = "$location/Answer".$rows['ans_type'];
				if ( is_file( $ans_old ) ) {
					unlink ( $ans_old );
				}
			}
//			$Q2 = "UPDATE homework SET answer='<a href=$location/$filename2>$filename2</a>', ans_type='$ext2' WHERE a_id='$work_id'";
			$Q2 = "UPDATE homework SET answer='', ans_type='$ext2' WHERE a_id='$work_id'";			
			$result1=mysql_db_query($DB.$course_id, $Q2);
			$success=1;
		}
		$Q1 = "SELECT name FROM homework WHERE a_id='$work_id'";
		if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
		}
		$rows = mysql_fetch_array( $result );
		if($success == 1)
		{
			if($version == "C")
				$message = "檔案 $rows[0] 上傳成功!";
			else
				$message = "File $rows[0] Upload successfully!";
		}
		else
		{
			if($version == "C")
				$message = "檔案 $rows[0] 上傳失敗!";
			else
				$message = "File $rows[0] Upload Unsuccessfully!";
		}
		show_page_d();
	}
	elseif($action == "uploadothers")
	{
		upload( "others" );
	}
	elseif($action == "uploadotherwork")
	{
		$success=0;
		$location="../../$course_id/homework/$work_id/teacher";
		for ( $i = 0 ; $i <= 9 ; $i ++ ) {
			$uploadfile = "uploadfile".$i;
			$uploadfilename = "uploadfile".$i."_name";
			if( ($$uploadfile != "none" && $$uploadfile != "") && fileupload ( $$uploadfile, $location, $$uploadfilename ) ) {				
				$success = 1;
			}
		}
		$Q1 = "SELECT name FROM homework WHERE a_id='$work_id'";
		if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
		}
		$rows = mysql_fetch_array( $result );
		if($success == 1)
		{
			if($version == "C")
				$message = "檔案 $rows[0] 上傳成功!";
			else
				$message = "File $rows[0] Upload successfully!";
		}
		else
		{
			if($version == "C")
				$message = "檔案 $rows[0] 上傳失敗!";
			else
				$message = "File $rows[0] Upload Unsuccessfully!";
		}
		show_page_d();
	}
	elseif($action == "delete")
	{
		$Q1 = "SELECT name FROM homework WHERE a_id='$work_id'";
		if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
		}
		$rows = mysql_fetch_array( $result );
		$Q2 = "DELETE FROM homework WHERE a_id='$work_id'";
		if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
			show_page( "not_access.tpl" ,"資料刪除取錯誤!!" );
		}
		$Q3 = "DELETE FROM handin_homework WHERE homework_id='$work_id'";
		if ( !($result3 = mysql_db_query( $DB.$course_id, $Q3 ) ) ) {
			show_page( "not_access.tpl" ,"資料刪除取錯誤!!" );
		}
		deldir("../../$course_id/homework/$work_id");
		$message = "作業 $rows[0] 刪除完成!";
		show_page_d();
	}
	elseif($action == "chstatus")
	{
		$Q1 = "select validated from course where a_id='$course_id'";
		if ( !($result1 = mysql_db_query( $DB, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
		}
		if( $row1 = mysql_fetch_array($result1) ) {
			$validated = $row1["validated"];
		}
		if ( $validated > 1 )
			$newvalid = $validated - 2;
		else
			$newvalid = $validated + 2;
		$Q2 = "update course set validated = '$newvalid' where a_id='$course_id'";
		if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫更新錯誤!!" );
		}
		show_page_d();
	}
	else
		show_page_d();
}
else
{
	if( $version=="C" )
		show_page( "not_access.tpl" ,"你沒有權限使用此功能");
	else
		show_page( "not_access.tpl" ,"You have No Permission!!");
}

function show_page_d () {
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $version, $message, $course_id, $skinnum;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}
	$Q1 = "SELECT a_id,name,chap_num FROM homework ORDER BY chap_num, a_id";
	if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
		show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
	}
	$Q2 = "select validated from course where a_id='$course_id'";
	if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) ) {
		show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
	}
	if( $row2 = mysql_fetch_array($result2) ) {
		$validated = $row2["validated"];
	}
	
	if( mysql_num_rows($result) != 0)
	{
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate("./templates");
		if($version == "C")
			$tpl->define(array(main=>"modify_work.tpl"));
		else
			$tpl->define(array(main=>"modify_work_E.tpl"));
		$tpl->define_dynamic("row","main");
		$tpl->assign( SKINNUM , $skinnum );
		if( $validated <= 1 ) {
			if($version == "C") {
				$tpl->assign("STATUS", "可搜尋");
				$tpl->assign("BOTTOM", "禁止搜尋");
			}
			else {
				$tpl->assign("STATUS", "Yes");
				$tpl->assign("BOTTOM", "Disable");
			}
		}
		else {
			if($version == "C") {
				$tpl->assign("STATUS", "<font color=red>不可搜尋</font>");
				$tpl->assign("BOTTOM", "開放搜尋");
			}
			else {
				$tpl->assign("STATUS", "No");
				$tpl->assign("BOTTOM", "Permit");
			}
		}
		$color == "#F0FFEE";
		while ( $rows = mysql_fetch_array($result) )
		{
			if ( $color == "#F0FFEE" )
				$color = "#E6FFFC";
			else
				$color = "#F0FFEE";
			$tpl->assign( COLOR , $color );
			$tpl->assign(WORKID,$rows[0]);
			$tpl->assign(WORKNAME,$rows[1]);
			$tpl->assign(CHAP_NUM,$rows[2]);
			
			$tpl->parse(ROWS,".row");
		}
		$tpl->assign(MESSAGE,$message);
		$tpl->parse(BODY,"main");
 		$tpl->FastPrint("BODY");
	}
	else
	{
		if( $version=="C" )
			show_page( "not_access.tpl" ,"目前沒有任何作業可供修改!");
		else
			show_page( "not_access.tpl" ,"There is no work for modification!!");
	}
}

function upload ( $type ) {
	global $version, $work_id;
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate("./templates");
	if($version == "C")
		if ( $type == "others" )
			$tpl->define(array(main=>"uploadothers.tpl"));
		else
			$tpl->define(array(main=>"uploadquestion.tpl"));
	else
		if ( $type == "others" )
			$tpl->define(array(main=>"uploadothers_E.tpl"));
		else
			$tpl->define(array(main=>"uploadquestion_E.tpl"));
	if ( $type != "others" )
		filelist ( $tpl );
	$tpl->assign(GOTOURL,"modify_work.php");
	$tpl->assign(WORKID,$work_id);
	$tpl->assign(IMG,"a322.gif");
	$tpl->parse(BODY,"main");
	$tpl->FastPrint("BODY");
}

function filelist ( &$tpl ) {
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $work_id, $course_id, $version;
	$tpl->define_dynamic("file_list", "main");
	$work_dir = "../../$course_id/homework/$work_id/teacher";
	if ( is_dir( $work_dir ) ) {
		$handle = dir($work_dir);
		$i=false;
		while (( $file = $handle->read() ) ) {
			if(strcmp($file,".") !=0 && strcmp($file,"..")) {   
			// 除了 '.' '..'之外的檔案輸出
				$tpl->assign("FILE_N", $file);
				$tpl->assign("FILE_LINK", $work_dir."/".$file);
				$tpl->assign("FILE_SIZE", filesize($work_dir."/".stripslashes($file)));
				$tpl->assign("FILE_DATE", date("Y-m-d H:i:s",filemtime($work_dir."/".$file)) );
				if ( $version == "C" ) {
					$tpl->assign("DELETE", "<a href=\"./modify_work.php?action=del&filename=$file&work_id=$work_id\" onclick=\"return confirm('你確定要刪除這個檔案嗎?');\">刪除這個檔案</a>" );
				}
				else {
					$tpl->assign("DELETE", "<a href=\"./modify_work.php?action=del&filename=$file&work_id=$work_id\" onclick=\"return confirm('Suer to Delete?');\">Delete</a>" );
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

function seecontent ( $type ) {
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASS, $course_id, $work_id, $version, $skinnum;
	$Q1 = "SELECT name, due, percentage, question, q_type, answer, ans_type FROM homework WHERE a_id='$work_id'";
	if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
		show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
	}
	$rows = mysql_fetch_array($result);

	include("class.FastTemplate.php3");
	$tpl = new FastTemplate("./templates");
	if($version == "C")
		$tpl->define(array(main=>"show_content.tpl"));
	else
		$tpl->define(array(main=>"show_content_E.tpl"));
	$tpl->assign( SKINNUM , $skinnum );
	if ( $type == "ans" ) {
		$ans_file = "../../$course_id/homework/$work_id/teacher/Answer".$rows['ans_type'];
		if ( $rows['answer'] == "" && $rows['ans_type'] != "" && is_file ( $ans_file ) ) {
			header( "location: $ans_file" );
			exit;
		}
		else {
		
			if($version == "C") {
				$tpl->assign(TYPE,"作業答案");
				$tpl->assign(SUBMIT,"<a href=\"./modify_work.php?work_id=$work_id&action=editans\">修改答案</a>");
			}
			else {
				$tpl->assign(TYPE,"Answer");
				$tpl->assign(SUBMIT,"<a href=\"./modify_work.php?work_id=$work_id&action=editans\">Edit Ans</a>");
			}
			$content = $rows['answer'];
			if ( stristr($content,"<html>") == NULL ) {
				$content=htmlspecialchars( $content );
				$content=ereg_replace("\n","<BR>\n",$content);
			}
			$tpl->assign(CONTENT,$content);
		}
	}
	else {
		$q_file = "../../$course_id/homework/$work_id/teacher/Question".$rows['q_type'];
		if ( $rows['question'] == "" && $rows['q_type'] != "" && is_file ( $q_file ) ) {
			header( "location: $q_file" );
			exit;
		}
		else {

			if($version == "C") {
				$tpl->assign(TYPE,"作業題目");
				$tpl->assign(SUBMIT,"<a href=\"./modify_work.php?work_id=$work_id&action=editwork\">修改題目</a>");
			}
			else {
				$tpl->assign(TYPE,"Question");
				$tpl->assign(SUBMIT,"<a href=\"./modify_work.php?work_id=$work_id&action=editwork\">Edit Question</a>");
			}
			$content = $rows['question'];
			if ( stristr($content,"<html>") == NULL ) {
				$content=htmlspecialchars( $content );
				$content=ereg_replace("\n","<BR>\n",$content);
			}
			$tpl->assign(CONTENT,$content);
		}
	}
	$tpl->assign(IMG,"a322.gif");
	$tpl->assign(WORKNAME,$rows[0]);
	$tpl->assign(WORKDUE,$rows[1]);
	$tpl->assign(WORKRATIO,$rows[2]);
	$tpl->assign(WORKID,"");
	$tpl->assign(ACT1,"");
	$tpl->assign(ACT2,"");
	$tpl->parse(BODY,"main");
	$tpl->FastPrint("BODY");
}
//
function show_modifynr () {
	global $message, $work_name, $work_due, $work_ratio, $work_id, $version, $skinnum, $chap_num, $course_id, $DB, $CoreAbilities;
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate("./templates");
	if($version == "C") {
		$tpl->define( array(main=>"create_work.tpl") );
	}
	else {
		$tpl->define( array(main=>"create_work_E.tpl") );
	}
	$tpl->assign( SKINNUM , $skinnum );
	$tpl->assign(ACT1,"modify_work.php");
	$tpl->assign(ACT2,"updatenr");
	
//start------------devon@2006-01-09--修改作業時，可以更改作業所屬章節--------	
	$Q0 = "select chap_num from chap_title order by chap_num desc";
	$result0 = mysql_db_query($DB.$course_id, $Q0);
	$row0 = mysql_fetch_array($result0);
	$total_chap = $row0[0];
	for($i=0; $i<=$total_chap; $i++){
		if($i==$chap_num)
			$select_chap = $select_chap."<option value=".$i." selected>".$i."</option>";
		else
			$select_chap = $select_chap."<option value=".$i." >".$i."</option>";
	}
	$tpl->assign(SELECT_CHAP, $select_chap);
//	$tpl->assign(SELECT_CHAP, "<option value=".$chap_num." >".$chap_num."</option>"); //
//end------------devon@2006-01-09--修改作業時，可以更改作業所屬章節--------

	//判斷大學部or研究所 顯示資料庫裡原本勾選過的核心能力
	$group_id = Get_group_id($course_id);
	$ClassTopic_CoreAbilitiesTmp = split(",",$CoreAbilities);
	$CoreAbilities = "";

    if($group_id == 11){
		for($i=1; $i<=11; $i++){
			$checked = "";
			for($j=0; $j<count($ClassTopic_CoreAbilitiesTmp); $j++)
				if($i == $ClassTopic_CoreAbilitiesTmp[$j]) $checked = "checked";
			$CoreAbilities = $CoreAbilities . "<input type=\"checkbox\" name=\"CoreAbility[]\" value=\"".$i."\"".$checked.">".ceil($i/3).".".((($i-1)%3)+1);
		}
    }
    else if($group_id == 12){
		for($i=1; $i<=8; $i++){
			$checked = "";
			for($j=0; $j<count($ClassTopic_CoreAbilitiesTmp); $j++)
				if($i == $ClassTopic_CoreAbilitiesTmp[$j]) $checked = "checked";
			$CoreAbilities = $CoreAbilities . "<input type=\"checkbox\" name=\"CoreAbility[]\" value=\"".$i."\"".$checked.">A".$i;
		}
    }

	$tpl->assign(WORKNAME,$work_name);
	$tpl->assign(WORKDUE,$work_due);
	$tpl->assign(WORKRATIO,$work_ratio);
	$tpl->assign(WORKID,$work_id);
	$tpl->assign(MESSAGE,$message);
	$tpl->assign(CoreAbilities,$CoreAbilities);
	$tpl->parse(BODY,"main");
	$tpl->FastPrint("BODY");

	echo CADes($group_id);
}

function show_content ( $type ) {
	global $message, $work_name, $work_due, $work_ratio, $work_id, $content, $version, $skinnum;
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate("./templates");
	if($version == "C")
		$tpl->define(array(main=>"edit_work.tpl"));
	else
		$tpl->define(array(main=>"edit_work_E.tpl"));
	$tpl->assign( SKINNUM , $skinnum );
	if($version == "C") {
		if ( $type == "ans" ) {
			$tpl->assign(TOPIC,"修改作業答案");
			$tpl->assign(ACT2,"updateans");
		}
		else {
			$tpl->assign(TOPIC,"修改作業題目");
			$tpl->assign(ACT2,"updatework");
		}
	}
	else {
		if ( $type == "ans" ) {
			$tpl->assign(TOPIC,"Modify Answer");
			$tpl->assign(ACT2,"updateans");
		}
		else {
			$tpl->assign(TOPIC,"Modify Homework");
			$tpl->assign(ACT2,"updatework");
		}
	}
	$tpl->assign(WORKNAME,$work_name);
	$tpl->assign(WORKDUE,$work_due);
	$tpl->assign(WORKRATIO,$work_ratio);
	$tpl->assign(WORKID,$work_id);
	$tpl->assign(CONTENT,$content);
	$tpl->assign(MESSAGE,$message);
	$tpl->assign(IMG,"a322.gif");
	$tpl->assign(ACT1,"modify_work.php");
	$tpl->parse(BODY,"main");
	$tpl->FastPrint("BODY");
}

function Get_group_id($a_id){
        //SQL Server的資料
        global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;

        //從資料庫取得group_id
        $SQL_Select = "SELECT group_id FROM course WHERE a_id = '$a_id'";
        if ( !($result = mysql_db_query( $DB, $SQL_Select ) ) ) {
                $message = "function Get_group_id($a_id) 資料庫讀取錯誤!!<br>";
                echo $message;
        }  
        $row = mysql_fetch_array( $result );
		return $row['group_id'];  
}


?>
