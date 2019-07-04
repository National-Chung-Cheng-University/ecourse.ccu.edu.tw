<?
	include "class.FastTemplate.php3";
	require "fadmin.php";
	
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
			$tpl->define(array(main => "hist_course_menu.tpl"));
		}
		else {
			$tpl->define(array(main => "hist_course_menu_E.tpl"));
		}

		$tpl->define_dynamic("tree_list", "main");


		$tpl->assign("TREE", "/js/tree.js");
		$tpl->assign("COURSE_ID", $course_id);

		mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("資料庫連結錯誤");

		$sql = "select * from hist_chaptitle where year = '$hist_year' AND term = '$hist_term' AND course_id = '$course_id' AND sec_num=0 order by chap_num";
		$result = mysql_db_query($DB, $sql) or die("資料庫查詢錯誤, $sql");

		if(mysql_num_rows($result) != 0) {
			while($row = mysql_fetch_array($result)) {
				$chap = $row["chap_num"];

				// 產生章的選項
				$_id = "el".($chap+1)."Parent";
				$_name = $row["chap_title"];
				$_href = "hist_course_menu.php?chap=$chap";
				//$tpl->assign("IMAGE", "<a class='item' target='html' href='$_href' ".
				$tpl->assign("IMAGE", "<a class='item' target='html' ".
						"onClick=\"expandIt('el".($chap+1)."'); return false;\">".
						"<img NAME='imEx' SRC='/images/plus.gif' BORDER=0 ALT='+' width=9 height=9 ".
						"ID='el".($chap+1)."Img'></a>");
				$tpl->assign("ITEM_ID", $_id);
				$tpl->assign("ITEM_CLASS", "parent");
				$tpl->assign("ITEM_STYLE", "");
				$tpl->assign("TEXT", "<a class='item' target='html' href='$_href' ".
							"onClick=\"expandIt('el".($chap+1)."');\">".
							"<font color='black' class='heada'>".$_name."</font></a>");

				$tpl->parse(ROWT, ".tree_list");

				//產生節的選項
				$sql2 = "select * from hist_chaptitle where year = '$hist_year' AND term = '$hist_term' AND course_id = '$course_id' AND chap_num=$chap and sec_num!=0 order by sec_num";
				$result2 = mysql_db_query($DB, $sql2) or die("資料庫查詢錯誤, $sql2");

				$_text = "<script language='JavaScript1.2'>";
				if(mysql_num_rows($result2) != 0) {
					$_id = "el".($chap+1)."Child";
					$tpl->assign("IMAGE","");
					$tpl->assign("ITEM_ID", $_id);
					$tpl->assign("ITEM_CLASS", "child");
					$tpl->assign("ITEM_STYLE", "style=\"display:none\"");

					$_text = $_text."\n";

					while($row2 = mysql_fetch_array($result2)) {
						$sect = $row2["sec_num"];
						$sect_title = $row2["sec_title"];

						$_href = "hist_course_menu.php?sect=$sect&chap=$chap";
						$_text = $_text."document.write(\"&nbsp&nbsp&nbsp&nbsp<img src='/images/browse.gif'><a class='item' target='html' href='$_href'>&nbsp".$sect_title."</a><br>\");\n";
					}
				}
				else {
					$_id = "el".($chap+1)."Child";   
					$tpl->assign("IMAGE","");
					$tpl->assign("ITEM_ID", $_id);
					$tpl->assign("ITEM_CLASS", "child");
					$tpl->assign("ITEM_STYLE", "style=\"display:none\"");
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
			$tpl->assign("ITEM_STYLE", "");
			$tpl->assign("TEXT", "");
			$tpl->parse(ROWT, ".tree_list");
		} 


		$tpl->parse(BODY, "main");

		$tpl->FastPrint(BODY);
	}
	else { //logging and redirect.
		
		$period = date("U") - $texttime;

		/*
		補漏洞, by 大師兄
		*/

		if(strstr($chap,"/")){
                        $chap="";
                }
                if(strstr($chap,"*")){
                        $chap="";
                }

		if( isset($sect) ) {  // some section of chapter
			add_log(3, $user_id, $chap, $course_id, 1, $sect);
			add_log(11, $user_id, $prevchapter, $course_id, $period, $prevsection);

			$texttime = date("U");
			$prevchapter = $chap;
			$prevsection = $sect;
			if( is_file("../../echistory/$hist_year/$hist_term/$course_id/textbook/$chap/$sect/index.html") 
				|| is_file("../../echistory/$hist_year/$hist_term/$course_id/textbook/$chap/$sect/index.htm") ) {
				header("Location: /echistory/$hist_year/$hist_term/$course_id/textbook/$chap/$sect/");
			}
			else {
				$dir_name = "../../echistory/$hist_year/$hist_term/$course_id/textbook/$chap/$sect";
				show_file_list($dir_name);
			}
		}
		else if( isset( $chap )) { // some chapter
			add_log(3, $user_id, $chap, $course_id, 1, 0);
			add_log(11, $user_id, $prevchapter, $course_id, $period, $prevsection);

			$texttime = date("U");
			$prevchapter = $chap;
			$prevsection = 0;
			if( is_file("../../echistory/$hist_year/$hist_term/$course_id/textbook/$chap/index.html") 
				|| is_file("../../echistory/$hist_year/$hist_term/$course_id/textbook/$chap/index.htm") ) {
				header("Location: /echistory/$hist_year/$hist_term/$course_id/textbook/$chap/");
			}
			else {
				$dir_name = "../../echistory/$hist_year/$hist_term/$course_id/textbook/$chap";
				show_file_list($dir_name);
			}
		}
		else { // root ( index )
			add_log(3, $user_id, 0, $course_id, 1, 0);
			add_log(11, $user_id, $prevchapter, $course_id, $period, $prevsection);

			$texttime = date("U");
			$prevchapter = 0;
			$prevsection = 0;
			if( is_file("../../echistory/$hist_year/$hist_term/$course_id/textbook/index.html") 
				|| is_file("../../echistory/$hist_year/$hist_term/$course_id/textbook/index.htm") ) {
				header("Location: /echistory/$hist_year/$hist_term/$course_id/textbook/");
			}
			else {
				$dir_name = "../../echistory/$hist_year/$hist_term/$course_id/textbook";
				show_file_list($dir_name);
			}
		}
	}
	
	function show_file_list( $dir_name ){
		$tpl = new FastTemplate("./templates");

		if($version == "C") {
			$tpl->define(array(main => "hist_file_list.tpl"));
		}
		else {
			$tpl->define(array(main => "hist_file_list.tpl"));
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
