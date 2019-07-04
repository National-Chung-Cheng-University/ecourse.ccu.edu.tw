<?
// param : course_id (session)
	include("class.FastTemplate.php3");
	require 'common.php';
	session_id($PHPSESSID);
	session_start();

	$tpl = new FastTemplate("./templates");

	if($version == "C") {
		$tpl->define(array(main => "editor_menu.tpl"));
	}
	else {
		$tpl->define(array(main => "editor_menu_E.tpl"));
	}

	$tpl->define_dynamic("tree_list", "main");


	$tpl->assign(array("TREE" => "/js/tree.js"));

	mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("資料庫連結錯誤");

	$sql = "select * from chap_title where sect_num=0 order by chap_num";
	$result = mysql_db_query($DB.$course_id, $sql) or die("資料庫查詢錯誤, $sql");

	if(mysql_num_rows($result) > 0) {
		while($row = mysql_fetch_array($result)) {
			$chap_num = $row["chap_num"];

			// 產生章的選項
			$_id = "el".($chap_num+1)."Parent";
			$_name = $row["chap_title"];
			$_href = "editor_main.php?chap=$chap_num";
			$tpl->assign("IMAGE", "<a class='item' target='editor' href='$_href' ".
					"onClick=\"expandIt('el".($chap_num+1)."'); return false;\">".
					"<img NAME='imEx' SRC='/images/plus.gif' BORDER=0 ALT='+' width=9 height=9 ".
					"ID='el".($chap_num+1)."Img'></a>");
			$tpl->assign("ITEM_ID", $_id);
			$tpl->assign("ITEM_CLASS", "parent");
			$tpl->assign("TEXT", "<a class='item' target='editor' href='$_href' ".
					"onClick=\"expandIt('el".($chap_num+1)."');\">".
					"<font color='black' class='heada'>".$_name."</font></a>");

			$tpl->parse(ROWT, ".tree_list");

			//產生節的選項
			$sql2 = "select * from chap_title where chap_num=$chap_num and sect_num!=0 order by sect_num";
			$result2 = mysql_db_query($DB.$course_id, $sql2) or die("資料庫查詢錯誤, $sql2");
			$sql3 = "select * FROM homework WHERE chap_num='$chap_num'";
			$result3 = mysql_db_query($DB.$course_id, $sql3) or die("資料庫查詢錯誤, $sql3");
			$sql4 = "select * FROM exam WHERE chap_num='$chap_num' and is_online != 0";
			$result4 = mysql_db_query($DB.$course_id, $sql4) or die("資料庫查詢錯誤, $sql4");
			$sql5 = "select * from discuss_list where chap_num='$chap_num'";
			$result5 = mysql_db_query($DB.$course_id, $sql5) or die("資料庫查詢錯誤, $sql5");

			$_text = "<script language='JavaScript1.2'>";
			if(mysql_num_rows($result2) != 0) {
  				$_id = "el".($chap_num+1)."Child";
				$tpl->assign("IMAGE","");
				$tpl->assign("ITEM_ID", $_id);
				$tpl->assign("ITEM_CLASS", "child");

 				$_text = $_text."\n";

				while($row2 = mysql_fetch_array($result2)) {
					$sect_num = $row2["sect_num"];
					$sect_title = $row2["sect_title"];

					$_href = "editor_main.php?section=$sect_num&chap=$chap_num";
					$_text = $_text."document.write(\"&nbsp&nbsp&nbsp&nbsp<img src='/images/browse.gif'>".
						"<a class='item' target='editor' "."href='$_href'>&nbsp".$sect_title."</a><br>\");\n";
				}
			}
			else {
				$_id = "el".($chap_num+1)."Child";   
				$tpl->assign("IMAGE","");
				$tpl->assign("ITEM_ID", $_id);
				$tpl->assign("ITEM_CLASS", "child");
			}
			if(mysql_num_rows($result3) != 0){
				$_text = $_text."document.write(\"&nbsp&nbsp&nbsp&nbsp<img src='/images/browse.gif'>".
						"<a class='item' target='editor' "."href='../Testing_Assessment/modify_partwork.php?chap=$chap_num'>&nbsp本章作業</a><br>\");\n";
			}
			if(mysql_num_rows($result4) != 0){
				$_text = $_text."document.write(\"&nbsp&nbsp&nbsp&nbsp<img src='/images/browse.gif'>".
						"<a class='item' target='editor' "."href='../Testing_Assessment/modify_parttest.php?chap=$chap_num'>&nbsp本章測驗</a><br>\");\n";
			}
			if(mysql_num_rows($result5) != 0){
				$row = mysql_fetch_array($result5);
				$discuss_id = $row["discuss_id"];
				$Qg = "select group_num from discuss_info where a_id = $discuss_id";
				$resultg = mysql_db_query($DB.$course_id, $Qg) or die("資料庫查詢錯誤, $Qg");
				$group_num = $resultg["group_num"];
				$_text = $_text."document.write(\"&nbsp&nbsp&nbsp&nbsp<img src='/images/browse.gif'>".
						"<a class='item' target='editor' "."href='../discuss/article_list.php?discuss_id=$discuss_id&group_num=$group_num&log=1&PHPSESSID=$PHPSESSID'>&nbsp本章討論區</a><br>\");\n";
			}
			$_text = $_text."</script>\n";
			$tpl->assign("TEXT", $_text);
			$tpl->parse(ROWT, ".tree_list");
		}
	}
	else {	// no data exists in DBMS.
		$tpl->assign("IMAGE", "");
		$tpl->assign("ITEM_ID", "");
		$tpl->assign("ITEM_CLASS", "");
		$tpl->assign("TEXT", "");
		$tpl->parse(ROWT, ".tree_list");
	} 
	
	//讓系所助理有回上一頁的功能
	$sqlqoo = "select authorization from user where id = '$user_id'";
	$resultqoo = mysql_db_query($DB, $sqlqoo);
	$rowqoo = mysql_fetch_array($resultqoo);
	if($rowqoo[authorization] != 4) {
		$tpl->assign("BACK", "");
	}else if($rowqoo[authorization] = 4){
		$tpl->assign("BACK", "<a href=\"/php/Courses_Admin/upload_intro.php?PHPSESSID=$PHPSESSID\" target=\"_parent\">回上一頁</a>");
	}
	
	$tpl->parse(BODY, "main");

	$tpl->FastPrint(BODY);
?>