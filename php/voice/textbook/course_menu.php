<?
// param : course_id (session), $list(control)
//		chap , sect for redirect
	include "class.FastTemplate.php3";
	require "fadmin.php";

	if($guest == "1") {
		$Q1 = "SELECT validated FROM course where a_id = '$course_id'";
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "資料庫連結錯誤!!";
		}
		else if ( !($result = mysql_db_query( $DB, $Q1  )) ) {
			$error = "資料庫讀取錯誤!!";
		}
		else if ( mysql_num_rows( $result ) == 0 ) {
			$error = "資料庫錯誤!!";
			show_page ( "not_access.tpl", $error );
		}
		else {
			$row = mysql_fetch_array($result);
		}

		if( ($row["validated"]%2 == 1) ) {
			if ( $version == "C" )
				show_page( "not_access.tpl" ,"教材不開放參觀");
			else
				show_page( "not_access.tpl" ,"Access Denied.");
			exit();
		}
	}

	if( isset($list) ) { // list course menu.
		// initial time logging variables.
		if( !session_is_registered( "texttime" ) ) {
			session_register( "texttime" );
		}

		if( !session_is_registered( "prevchapter" ) ) {
			session_register( "prevchapter" );
		}

		if( !session_is_registered( "prevsection" ) ) {
			session_register( "prevsection" );
		}
		$texttime = date("U");
		$prevchapter = 0;
		$prevsection = 0;

		$tpl = new FastTemplate("./templates");

		if($version == "C") {
			$tpl->define(array(main => "course_menu.tpl"));
		}
		else {
			$tpl->define(array(main => "course_menu_E.tpl"));
		}

		$tpl->define_dynamic("tree_list", "main");


		$tpl->assign("TREE", "/js/tree.js");
		$tpl->assign("COURSE_ID", $course_id);

		mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("資料庫連結錯誤");

		$sql = "select * from chap_title where sect_num=0 order by chap_num";
		$result = mysql_db_query($DB.$course_id, $sql) or die("資料庫查詢錯誤, $sql");

		if(mysql_num_rows($result) != 0) {
			while($row = mysql_fetch_array($result)) {
				$chap = $row["chap_num"];

				// 產生章的選項
				$_id = "el".($chap+1)."Parent";
				$_name = $row["chap_title"];
				$_href = "course_menu.php?chap=$chap";
				$tpl->assign("IMAGE", "<a class='item' target='html' href='$_href' ".
						"onClick=\"expandIt('el".($chap+1)."'); return false;\">".
						"<img NAME='imEx' SRC='/images/plus.gif' BORDER=0 ALT='+' width=9 height=9 ".
						"ID='el".($chap+1)."Img'></a>");
				$tpl->assign("ITEM_ID", $_id);
				$tpl->assign("ITEM_CLASS", "parent");
				$tpl->assign("TEXT", "<a class='item' target='html' href='$_href' ".
							"onClick=\"expandIt('el".($chap+1)."');\">".
							"<font color='black' class='heada'>".$_name."</font></a>");

				$tpl->parse(ROWT, ".tree_list");

				//產生節的選項
				$sql2 = "select * from chap_title where chap_num=$chap and sect_num!=0 order by sect_num";
				$result2 = mysql_db_query($DB.$course_id, $sql2) or die("資料庫查詢錯誤, $sql2");
				$sql3 = "select * FROM homework WHERE chap_num='$chap'";
				$result3 = mysql_db_query($DB.$course_id, $sql3) or die("資料庫查詢錯誤, $sql3");
				$sql4 = "select * FROM exam WHERE chap_num='$chap' and is_online!=0";
				$result4 = mysql_db_query($DB.$course_id, $sql4) or die("資料庫查詢錯誤, $sql4");
				$sql5 = "select * from discuss_list where chap_num='$chap'";
				$result5 = mysql_db_query($DB.$course_id, $sql5) or die("資料庫查詢錯誤, $sql5");

				$_text = "<script language='JavaScript1.2'>";
				if(mysql_num_rows($result2) != 0) {
					$_id = "el".($chap+1)."Child";
					$tpl->assign("IMAGE","");
					$tpl->assign("ITEM_ID", $_id);
					$tpl->assign("ITEM_CLASS", "child");

					$_text = $_text."\n";

					while($row2 = mysql_fetch_array($result2)) {
						$sect = $row2["sect_num"];
						$sect_title = $row2["sect_title"];

						$_href = "course_menu.php?sect=$sect&chap=$chap";
						$_text = $_text."document.write(\"&nbsp&nbsp&nbsp&nbsp<img src='/images/browse.gif'><a class='item' target='html' href='$_href'>&nbsp".$sect_title."</a><br>\");\n";
					}
				}
				else {
					$_id = "el".($chap+1)."Child";   
					$tpl->assign("IMAGE","");
					$tpl->assign("ITEM_ID", $_id);
					$tpl->assign("ITEM_CLASS", "child");
				}
				if(mysql_num_rows($result3) != 0){
					$_text = $_text."document.write(\"&nbsp&nbsp&nbsp&nbsp<img src='/images/browse.gif'><a class='item' target='html' href='../Testing_Assessment/show_partwork.php?chap=$chap'>&nbsp本章作業</a><br>\");\n";
				}
				if(mysql_num_rows($result4) != 0){
					$_text = $_text."document.write(\"&nbsp&nbsp&nbsp&nbsp<img src='/images/browse.gif'><a class='item' target='html' href='../Testing_Assessment/show_parttest.php?chap=$chap'>&nbsp本章測驗</a><br>\");\n";
				}
				if(mysql_num_rows($result5) != 0){
					$row = mysql_fetch_array($result5);
					$discuss_id = $row["discuss_id"];
					$Qg = "select group_num from discuss_info where a_id = $discuss_id";
					$resultg = mysql_db_query($DB.$course_id, $Qg) or die("資料庫查詢錯誤, $Qg");
					$group_num = $resultg["group_num"];
					$_text = $_text."document.write(\"&nbsp&nbsp&nbsp&nbsp<img src='/images/browse.gif'>".
						"<a class='item' target='html' "."href='../discuss/article_list.php?discuss_id=$discuss_id&group_num=$group_num&log=1&PHPSESSID=$PHPSESSID'>&nbsp本章討論區</a><br>\");\n";
				}
				$_text = $_text."</script>\n";
				$tpl->assign("TEXT", $_text);
				$tpl->parse(ROWT, ".tree_list");
			}
		}
		else {
			$tpl->assign("IMAGE", "");
			$tpl->assign("ITEM_ID", "");
			$tpl->assign("ITEM_CLASS", "");
			$tpl->assign("TEXT", "");
			$tpl->parse(ROWT, ".tree_list");
		} 


		$tpl->parse(BODY, "main");

		$tpl->FastPrint(BODY);
	}
	else { //logging and redirect.

		$period = date("U") - $texttime;

		if( isset($sect) ) {  // some section of chapter
			add_log(3, $user_id, $chap, $course_id, 1, $sect);
			add_log(11, $user_id, $prevchapter, $course_id, $period, $prevsection);

			$texttime = date("U");
			$prevchapter = $chap;
			$prevsection = $sect;
			if( is_file("../../$course_id/textbook/$chap/$sect/index.html") 
				|| is_file("../../$course_id/textbook/$chap/$sect/index.htm") ) {
				header("Location: /$course_id/textbook/$chap/$sect/");
			}
			else {
				$dir_name = "../../$course_id/textbook/$chap/$sect";
				show_file_list($dir_name);
			}
		}
		else if( isset( $chap )) { // some chapter
			add_log(3, $user_id, $chap, $course_id, 1, 0);
			add_log(11, $user_id, $prevchapter, $course_id, $period, $prevsection);

			$texttime = date("U");
			$prevchapter = $chap;
			$prevsection = 0;
			if( is_file("../../$course_id/textbook/$chap/index.html") 
				|| is_file("../../$course_id/textbook/$chap/index.htm") ) {
				header("Location: /$course_id/textbook/$chap/");
			}
			else {
				$dir_name = "../../$course_id/textbook/$chap";
				show_file_list($dir_name);
			}
		}
		else { // root ( index )
			add_log(3, $user_id, 0, $course_id, 1, 0);
			add_log(11, $user_id, $prevchapter, $course_id, $period, $prevsection);

			$texttime = date("U");
			$prevchapter = 0;
			$prevsection = 0;
			if( is_file("../../$course_id/textbook/index.html") 
				|| is_file("../../$course_id/textbook/index.htm") ) {
				header("Location: /$course_id/textbook/");
			}
			else {
				$dir_name = "../../$course_id/textbook";
				show_file_list($dir_name);
			}
		}
	}
	
	function show_file_list( $dir_name ){
		$tpl = new FastTemplate("./templates");

		if($version == "C") {
			$tpl->define(array(main => "file_list.tpl"));
		}
		else {
			$tpl->define(array(main => "file_list.tpl"));
		}

		$tpl->define_dynamic("file_list", "main");
		
		$handle = dir($dir_name);
		$i=false;
		while (false !== ( $file = $handle->read() ) ) {
			if( ( strcmp($file,".")!=0 ) && ( strcmp($file,"..")!=0 ) && !is_dir($dir_name."/".$file) ) {
				// 除了 '.', '..', 目錄外的檔案輸出
				$tpl->assign("FILE_N", $file);
				$tpl->assign("FILE_LINK", $dir_name."/".urlencode($file));
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
		$handle->close();

		// exception handling : no file exists.
		if($set_file==0) {
			$tpl->assign("FILE_N", "");
			$tpl->assign("FILE_SIZE", "");
			$tpl->assign("FILE_DATE", "");
		}
	
		$tpl->parse(BODY, "main");
		$tpl->FastPrint(BODY);
	}
?>