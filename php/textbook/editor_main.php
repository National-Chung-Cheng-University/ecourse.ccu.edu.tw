<?
	// 在不同的狀況下, 使用不同的template.
	// param: errno (chap.php / section.php / file_upload.php)
	//        course_id (session)
	//        chap  (editor_main.php / editor_chap/sect )
	//        section   (editor_main.php / editor_chap/sect )
	//        reload    (editor_main.php / chap / sect)  當新增章節後的reload控制.
	// 02/03/12, 1:00 am
	// 新增有關server5, scorm, 105.20的bug. (function rem_sesid)
	// 02/03/20, 10:00am
	// 修改小部份訊息

	include("class.FastTemplate.php3");
	include("htmlgen.php");
	require 'fadmin.php';

	function rem_sesid( $str ) {
		
		$str2 = strstr($str, "PHPSESSID");

		if( ($str2 != "") && ($str2 != null) ) {
			
			$posa = strpos( $str, "href" );
			$posb = strpos( $str, "PHPSESSID" );
			$link = substr( $str, $posa+4, $posb-$posa-4 );

			if ( $link{strlen ( $link )-1} == "?" ) {
				$link = substr( $link, 0, strlen ( $link )-1 ); 
			}else if ( $link{strlen ( $link )-1} == "&" ) {
				$link = substr( $link, 0, strlen ( $link )-1 ); 
			}

			if ( strstr ( $link, "\"" ) != null ) {
				$link.= "\"";
			}
			else if ( strstr ( $link, "'" ) != null ) {
				$link .= "'";
			}
			$parta = substr( $str, 0, $posa+4 );
			$partb = strstr( $str2, ">" );
			return rem_sesid($parta.$link.$partb);
		}
		else
			return $str;
	}

	if($version == "C") {
		$error_msg[0] = "新增 &lt;章&gt; <font color='blue'>成功</font>";
		$error_msg[1] = "更新 &lt;章&gt; <font color='blue'>成功</font>";
		$error_msg[2] = "新增/更新 &lt;章&gt; 失敗";
		$error_msg[3] = "新增 &lt;節&gt; <font color='blue'>成功</font>";
		$error_msg[4] = "更新 &lt;節&gt; <font color='blue'>成功</font>";
		$error_msg[5] = "新增/更新 &lt;節&gt; 失敗";
		$error_msg[6] = "課程導論首頁內容 <font color='blue'>已更新</font>";
		$error_msg[7] = "本章首頁內容 <font color='blue'>已更新</font>";
		$error_msg[8] = "本節首頁內容 <font color='blue'>已更新</font>";
		$error_msg[9] = "檔案上傳 <font color='blue'>成功</font>";
		$error_msg[10] = "檔案上傳 失敗";
		$error_msg[11] = "權限修改 <font color='blue'>成功</font>";
		$error_msg[12] = "權限修改 失敗";
		$error_msg[13] = "檔案刪除 <font color='blue'>成功</font>";
		$error_msg[14] = "檔案刪除 失敗";
		$error_msg[15] = "更新討論區連結 <font color='blue'>成功</font>";
		$error_msg[16] = "更新討論區連結 失敗";
	}
	else {
		$error_msg[0] = "Chapter title Insert ok.";
		$error_msg[1] = "Chapter title Update ok.";
		$error_msg[2] = "Chapter title Insert/Update error.";
		$error_msg[3] = "Section title Insert ok.";
		$error_msg[4] = "Section title Update ok.";
		$error_msg[5] = "Section title Insert/Update error.";
		$error_msg[6] = "Introduce page of this course Updated.";
		$error_msg[7] = "Chapter page Updated.";
		$error_msg[8] = "Section page Updated.";
		$error_msg[9] = "New File Upload Succeed.";
		$error_msg[10] = "New File Upload Failed.";
		$error_msg[11] = "Permission Change Succeed.";
		$error_msg[12] = "Permission Change Falied.";
		$error_msg[13] = "File delete succeed.";
		$error_msg[14] = "File delete failed.";
		$error_msg[15] = "Update discuss_board succeed";
		$error_msg[16] = "Update discuss_board failed";	
	}

	
	// 檢查使用權限.
	if(session_check_teach($PHPSESSID) != 2) {
		show_page("not_access.tpl", "你沒有權限執行此功能.<br>\nYou have no permission to perform this function.");
		exit();
	}

	mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("資料庫連結錯誤");

	$tpl = new FastTemplate("./templates");

	// 判斷使用何種template. 注意中英文.
	if(isset($chap) && empty($section)) {       // editor_chap.tpl   章編輯網頁
		if($version == "C") {
			$tpl->define(array(main => "editor_chap.tpl"));
		}
		else {
			$tpl->define(array(main => "editor_chap_E.tpl"));
		}
	}
	else if(isset($chap) && isset($section)) {   // editor_sect.tpl   節編輯網頁
		if($version == "C") {
			$tpl->define(array(main => "editor_sect.tpl"));	  
		}
		else {
			$tpl->define(array(main => "editor_sect_E.tpl"));	  
		}
	}
	else {                                          // editor_root.tpl  課程/導論編輯網頁
		if($version == "C") {
			$tpl->define(array(main => "editor_root.tpl"));	  
		}
		else {
			$tpl->define(array(main => "editor_root_E.tpl"));	  
		}
	}

 
	// 輸出錯誤訊息.
	if(isset($errno)) 
		$tpl->assign("ERROR_MSG", $error_msg[$errno]);
	else
		$tpl->assign("ERROR_MSG", "");


	// 關於新增後畫面更新的控制.
	if(isset($reload)) 
		$tpl->assign("RELOAD_CTRL", " onLoad=\"parent.left.location.reload();\"");
	else
		$tpl->assign("RELOAD_CTRL", "");

	// 關於各種輸出的處理程式.
	if(isset($chap) && empty($section)) {         
		// editor_chap.tpl  各章編輯畫面
		$dir_name = "../../$course_id/textbook/$chap";
		$tpl->define_dynamic("sect_list", "main");

		$tpl->assign("CHAP_NUM", $chap);
		
		$tpl->assign("SECT_NUM", "");
		$tpl->assign("SECT_TITLE", "");

		$sql = "select * from chap_title where chap_num=$chap and sect_num!=0 order by sect_num";
		$result = mysql_db_query($DB.$course_id, $sql) or die("資料庫查詢錯誤, $sql");

		// color control.
		$i = false;
		while($row = mysql_fetch_array($result)) {
            $tpl->assign("SECT_NUM", $row["sect_num"]);
			$tpl->assign("SECT_TITLE", $row["sect_title"]);
    
	        if($i) 
				$tpl->assign("ED_COLOR", "#ffffff");
			else
				$tpl->assign("ED_COLOR", "#edf3fa");

			$i = !$i;
			$tpl->parse(ROWS, ".sect_list");
	    }

		//選擇討論區
		$Qt = "SELECT * from discuss_info";
		if ( !($result_t = mysql_db_query( $DB.$course_id, $Qt ) ) ) {
			show_page( "not_access.tpl" ,"請先建立討論區群組" );
		}
		$total_topic = mysql_num_rows($result_t);
		if(empty($select_topic_num)){
			$sql = "select discuss_id from discuss_list where chap_num=$chap";
			$result_s = mysql_db_query($DB.$course_id, $sql) or die("資料庫查詢錯誤, $sql");
			$row_s = mysql_fetch_array($result_s);
			$select_topic = $select_topic."<option value="."0"." >"."  "."</option>";
			for($i=1; $i<=$total_topic; $i++){
				$rowt = mysql_fetch_array($result_t);
				$topic_id = $rowt["a_id"];
				$topic_name = $rowt["discuss_name"];
				if($topic_id==$row_s["discuss_id"]){
					$select_topic = $select_topic."<option value=".$topic_id." selected>".$topic_name."</option>";
				}else{
					$select_topic = $select_topic."<option value=".$topic_id." >".$topic_name."</option>";
				}
			}
		}else{
			$select_topic = $select_topic."<option value=".$select_topic_num." >".$select_topic_num."</option>";
		}
	
		$tpl->assign(SELECT_TOPIC,$select_topic);	

		if(is_file("$dir_name/index.html"))	{
			$fp = fopen("$dir_name/index.html", "r");
			$content = fread($fp , filesize("$dir_name/index.html"));
			$tpl->assign("HTML_CONTENT", rem_sesid($content));
			fclose($fp);
		}
		else
			$tpl->assign("HTML_CONTENT", "");
	}
	else if(isset($chap) && isset($section)) {    
		// editor_sect.tpl  各節畫面
		$dir_name = "../../$course_id/textbook/$chap/$section";
		$tpl->assign("CHAP_NUM", $chap);
		$tpl->assign("SECT_NUM", $section);

  		// 關於 /textbook/$chap_num/$sect_num/index.html 的內容.
		if(is_file("$dir_name/index.html"))	{
			$fp = fopen("$dir_name/index.html", "r");
			$content =  fread( $fp , filesize("$dir_name/index.html") );
			$tpl->assign("HTML_CONTENT", rem_sesid($content) );
			fclose($fp);
		}
		else
			$tpl->assign("HTML_CONTENT", "");
	}
	else {                                           
		// editor_root.tpl  基本畫面
		$dir_name = "../../$course_id/textbook";
		$tpl->define_dynamic("chap_list", "main");

		$tpl->assign("CHAP_NUM", "");
   		$tpl->assign("CHAP_TITLE", "");

		// Guest permission change part.
		// query validated value from DBMS.
		$sql = "select validated from course where a_id=$course_id";
		$result = mysql_db_query($DB, $sql) or die("資料庫查詢錯誤, $sql");
		if( $row = mysql_fetch_array($result) ) {
			$validated = $row["validated"];
		}
		else {
			show_page("not_access.tpl", "發生不明錯誤, 請稍後再試.<br>\nUndefined error occoured, please try later.");
		}
		if($validated % 2 == 0) {
			// textbook is public.
			if($version == "C") {
				$tpl->assign("VAL_STATUS", "可旁聽");
			}
			else {
				$tpl->assign("VAL_STATUS", "Yes");
			}
		}
		else {
			// textbook is private.
			if($version == "C") {
				$tpl->assign("VAL_STATUS", "<font color=red>不可旁聽</font>");
			}
			else {
				$tpl->assign("VAL_STATUS", "No");
			}
		}
		$tpl->assign("VAL_VALUE", $validated);

		$sql = "select * from chap_title where sect_num=0 order by chap_num";
		$result = mysql_db_query($DB.$course_id, $sql) or die("資料庫查詢錯誤, $sql");

		$i = false;
		while($row = mysql_fetch_array($result)) {
			$tpl->assign("CHAP_NUM", $row["chap_num"]);
			$tpl->assign("CHAP_TITLE", $row["chap_title"]);

			if($i) 
				$tpl->assign("ED_COLOR", "#ffffff");
			else
				$tpl->assign("ED_COLOR", "#edf3fa");

			$i = !$i;

			$tpl->parse(ROWC, ".chap_list");
		}

		if(is_file("$dir_name/index.html"))	{
			$fp = fopen("$dir_name/index.html", "r");
			$content = fread($fp , filesize("$dir_name/index.html"));
			$tpl->assign("HTML_CONTENT", rem_sesid($content) );
			fclose($fp);
		}
		else
			$tpl->assign("HTML_CONTENT", "");
	}

	$tpl->assign("BASEHREF", $dir_name);


	$tpl->define_dynamic("file_list", "main");
	// Process file list under this directory.
	// directory name is already initialed.  ($dir_name)
	$files = sort_file_list($dir_name);
	foreach($files as $file) {
		if( ( strcmp($file,".")!=0 ) && ( strcmp($file,"..")!=0 ) && !is_dir($dir_name."/".$file) ) {   

			// some control variable with del_file.php
			if(isset($chap) && isset($section)) {
				$var = "&section=$section&editor=3&chap=$chap";
			}
			else if(isset($chap) && empty($section)) {
				$var = "&editor=2&chap=$chap";				
			}
			else {
				$var = "&editor=1";				
			}
			$calais = rawurlencode("&");
			$file2 = str_replace ( "&", $calais , $file );
			$tpl->assign("FILE_DEL", $dir_name."/".urlencode($file2).$var);

			// 除了 '.', '..', 目錄外的檔案輸出
			$tpl->assign("FILE_N", $file);
			//$tpl->assign("FILE_LINK", $dir_name."/".$file);
			$tpl->assign("FILE_LINK", $dir_name."/". urlencode($file));
			$tpl->assign("FILE_SIZE", filesize($dir_name."/".stripslashes($file)));
			$tpl->assign("FILE_DATE", date("Y-m-d H:i:s",filemtime($dir_name."/".$file)));

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
	

	// exception handling : no file exists.
	if($set_file==0) {
		$tpl->assign("FILE_N", "");
		$tpl->assign("FILE_SIZE", "");
		$tpl->assign("FILE_DATE", "");
	}

	$tpl->assign("PHP_ID", $PHPSESSID);

	$tpl->parse(BODY, "main");

	$tpl->FastPrint(BODY);
	
	
function sort_file_list($dir_name)
{
	$handle = dir($dir_name);
    $i=false;
	$files = array();
	
	while (false !== ( $file = $handle->read() ) ) 
	{
		if( ( strcmp($file,".")!=0 ) && ( strcmp($file,"..")!=0 ) && !is_dir($dir_name."/".$file) ) 
		{
			$files[filemtime($dir_name."/".$file)] = $file;
		}
	}
	ksort($files);	//依日期排序
	$handle->close();
	return $files;    
}
?>
