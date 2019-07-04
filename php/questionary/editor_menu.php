<?
// param : course_id (session)
	include("class.FastTemplate.php3");
	require 'common.php';
	session_id($PHPSESSID);
	session_start();

	$tpl = new FastTemplate("./templates");
	$tpl->define(array(main => "editor_menu.tpl"));
	if($version == "C") {
		$tpl->assign("LIST", "課程問卷" );
	}
	else {
		$tpl->assign("LIST", "Questionary" );
	}

	$tpl->define_dynamic("tree_list", "main");

	$tpl->assign(array("TREE" => "/js/tree.js"));
	$tpl->assign(array("QUESID" => "$q_id"));

	mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("資料庫連結錯誤");

	$sql = "select * from qtiku where block_id = '0' and type = '3' and q_id ='$q_id' order by a_id";
	$result = mysql_db_query($DB.$course_id, $sql) or die("資料庫查詢錯誤, $sql");

	if(mysql_num_rows($result) > 0) {
		$qno = 0;
		$bno = 0;
		while($row = mysql_fetch_array($result)) {
			$block = $row["a_id"];
			$bno ++;
			// 產生章的選項
			$_id = "el".($block+1)."Parent";
			$_name = $row["question"];
			$_href = "editor_main.php?block_id=$block&q_id=$q_id&bno=$bno";
			$tpl->assign("IMAGE", "<a class='item' target='editor' href='$_href' ".
					"onClick=\"expandIt('el".($block+1)."'); return false;\">".
					"<img NAME='imEx' SRC='/images/plus.gif' BORDER=0 ALT='+' width=9 height=9 ".
					"ID='el".($block+1)."Img'></a>");
			$tpl->assign("ITEM_ID", $_id);
			$tpl->assign("ITEM_CLASS", "parent");
			$tpl->assign("TEXT", "<a class='item' target='editor' href='$_href' ".
					"onClick=\"expandIt('el".($block+1)."');\">".
					"<font color='black' class='heada'>".$_name."</font></a>");

			$tpl->parse(ROWT, ".tree_list");

			//產生節的選項
			$sql2 = "select * from qtiku where block_id = '$block' and type != 3 and q_id ='$q_id' order by a_id";
			$result2 = mysql_db_query($DB.$course_id, $sql2) or die("資料庫查詢錯誤, $sql2");

			if(mysql_num_rows($result2) != 0) {
  				$_id = "el".($block+1)."Child";
				$tpl->assign("IMAGE","");
				$tpl->assign("ITEM_ID", $_id);
				$tpl->assign("ITEM_CLASS", "child");

 				$_text = "<script language='JavaScript1.2'>\n";

				while($row2 = mysql_fetch_array($result2)) {
					$qno ++;
					$item_num = $qno;
					if ( $row2['type'] == 1 && $row2['ismultiple'] == 1) {
						if ( $version == "C" )
							$item_title = "選擇題(複選)";
						else
							$item_title = "Choise(multi)";
					}
					else if ( $row2['type'] == 1 && $row2['ismultiple'] == 0) {
						if ( $version == "C" )
							$item_title = "選擇題(單選)";
						else
							$item_title = "Choise(one)";
					}
					else {
						if ( $version == "C" )
							$item_title = "問答提";
						else
							$item_title = "Q&A";
					}

					$_href = "editor_main.php?block_id=$block&item=".$row2['a_id'];
					$_text = $_text."document.write(\"&nbsp&nbsp&nbsp&nbsp<img src='/images/browse.gif'>".
						"<a class='item' target='editor' "."href='$_href'>&nbsp".$item_title."</a><br>\");\n";
				}

				$_text = $_text."</script>";
 				$tpl->assign("TEXT", $_text);
				$tpl->parse(ROWT, ".tree_list");
			}
			else {
				$_id = "el".($block+1)."Child";   
				$tpl->assign("IMAGE","");
				$tpl->assign("ITEM_ID", $_id);
				$tpl->assign("ITEM_CLASS", "child");
				$_text = "<script language='JavaScript1.2'></script>\n";
				$tpl->assign("TEXT", $_text);
				$tpl->parse(ROWT, ".tree_list");
			}
		}
	}
	else {	// no data exists in DBMS.
		$tpl->assign("IMAGE", "");
		$tpl->assign("ITEM_ID", "");
		$tpl->assign("ITEM_CLASS", "");
		$tpl->assign("TEXT", "");
		$tpl->parse(ROWT, ".tree_list");
	} 

	$tpl->parse(BODY, "main");

	$tpl->FastPrint(BODY);
?>