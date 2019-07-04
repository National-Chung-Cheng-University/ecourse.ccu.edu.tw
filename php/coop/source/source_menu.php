<?
	require "fadmin.php";
	if ( !(isset($PHPSESSID) && session_check_teach($PHPSESSID) && ($check = check_group ( $course_id, $coopgroup, $coopcaseid )) != 0 ) ) {
		show_page( "not_access.tpl" ,"權限錯誤");
	}

	include("class.FastTemplate.php3");
	$tpl = new FastTemplate("./templates");

	$tpl->define(array(main => "source_menu.tpl"));

	$tpl->define_dynamic("row", "main");
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		echo( "資料庫連結錯誤!!" );
		exit();
	}

				
	//由level最大的lesson開始建立找起
	$Q1 = "select * from share_group_$coopcaseid where group_num = '$coopgroup' order by a_id";
	$result1 = mysql_db_query($DBC.$course_id, $Q1 ) or die("資料庫查詢錯誤, $Q1");
	$Q2 = "select sg.* from share_group_$coopcaseid sg, share_$coopcaseid s where s.group_num != '$coopgroup' and s.share = '2' and s.type = sg.a_id group by sg.a_id order by sg.group_num";
	$result2 = mysql_db_query($DBC.$course_id, $Q2 ) or die("資料庫查詢錯誤, $Q2");
	
	while ( $row1 = mysql_fetch_array($result1)) {
		$href = "";
		$a_id = $row1['a_id'];
		$parent_id = $row1['parent_id'];
		$name = $row1['name'];
		$name = addslashes( $name );
		$href = "<a href='./source.php?group_id=$a_id' target='source'>";
		$href2 = "<a href='./source_mag.php?group_id=$a_id' target='source'>";
		$parent = "window.JTree$a_id = new Tree(\"$href$name</a>\");";
			
		//建立樹枝
		$tpl->assign(PARENT, $parent);
		$image = "JTree$a_id.folderIcons = new Array(\"/images/coursefolder.gif\",\"/images/coursefolder_h.gif\",\"/images/coursefolder_s.gif\",\"/images/coursefolder_s.gif\");\n".
			"JTree$a_id.itemIcons = new Array(\"/images/courseitem.gif\",\"/images/courseitem_h.gif\",\"/images/courseitem.gif\",\"/images/courseitem_h.gif\");";
		$tpl->assign(IMAGE, $image);
		$prototree = "JTree$a_id.protoTree = JTree$a_id;\n";
		$tpl->assign(PROTOTREE, $prototree);
		$tpl->parse(ROWS, ".row");
		
		if ( $parent_id == "-1" ) {
			$root_aid = $a_id;
			$tpl->assign(SHOWTREE , "tRoot.addTreeItem( JTree$a_id );\n" );
			$children .= "JTree$a_id.addTreeItem( \"$href 顯示 </a>\" );\n";
			if ( $version == "C" ) {
				$href = "<a href='../note/note.php?action=share_note' target='source'>筆記本分享</a>";
			}
			else {
				$href = "<a href='../note/note.php?action=share_note' target='source'>Note Share</a>";
			}
			$children .= "JTree$a_id.addTreeItem( \"$href\" );\n";
			if ( $check > 1 ) {
				if ( $version == "C" ) {
					$children .= "JTree$a_id.addTreeItem( \"$href2 資料管理 </a>\" );\n";
				}
				else {
					$children .= "JTree$a_id.addTreeItem( \"$href2 Data Mag. </a>\" );\n";
				}
			}
		}
		else {
			$children .= "JTree$parent_id.addTreeItem( JTree$a_id );\n";
			$children .= "JTree$a_id.addTreeItem( \"$href 顯示 </a>\" );\n";
			if ( $check != 1 ) {
				$children .= "JTree$a_id.addTreeItem( \"$href2 資料管理 </a>\" );\n";
			}
		}
	}
	
	if ( mysql_num_rows ( $result2 ) != 0 ) {
		if ( $version == "C" ) {
			$name = "他組分享";
		}
		else {
			$name = "group share";
		}
		$href = "<a href='./source.php?group_id=$root_aid' target='source'>";
		$parent = "window.JTreegroup = new Tree(\"$href$name</a>\");";
			
		//建立樹枝
		$tpl->assign(PARENT, $parent);
		$image = "JTreegroup.folderIcons = new Array(\"/images/coursefolder.gif\",\"/images/coursefolder_h.gif\",\"/images/coursefolder_s.gif\",\"/images/coursefolder_s.gif\");\n".
			"JTreegroup.itemIcons = new Array(\"/images/courseitem.gif\",\"/images/courseitem_h.gif\",\"/images/courseitem.gif\",\"/images/courseitem_h.gif\");";
		$tpl->assign(IMAGE, $image);
		$prototree = "JTreegroup.protoTree = JTreegroup;\n";
		$tpl->assign(PROTOTREE, $prototree);
		$tpl->parse(ROWS, ".row");
		$children .= "JTree$root_aid.addTreeItem( JTreegroup );\n";
	}
	$tree_array; //儲存已存在的樹節點 
	while ( $row1 = mysql_fetch_array($result2)) {
		$href = "";
		$a_id = $row1['a_id'];
		$parent_id = $row1['parent_id'];
		$name = $row1['name'];
		$name = addslashes( $name );
		$href = "<a href='./source.php?group_id=$a_id' target='source'>";
		$parent = "window.JTree$a_id = new Tree(\"$href$name</a>\");";
			
		//建立樹枝
		$tpl->assign(PARENT, $parent);
		$image = "JTree$a_id.folderIcons = new Array(\"/images/coursefolder.gif\",\"/images/coursefolder_h.gif\",\"/images/coursefolder_s.gif\",\"/images/coursefolder_s.gif\");\n".
			"JTree$a_id.itemIcons = new Array(\"/images/courseitem.gif\",\"/images/courseitem_h.gif\",\"/images/courseitem.gif\",\"/images/courseitem_h.gif\");";
		$tpl->assign(IMAGE, $image);
		$prototree = "JTree$a_id.protoTree = JTree$a_id;\n";
		$tpl->assign(PROTOTREE, $prototree);
		$tpl->parse(ROWS, ".row");
		
		$tree_array[$a_id] = 1;
		if ( $tree_array[$parent_id] != 1  ) {
			$children .= "JTreegroup.addTreeItem( JTree$a_id );\n";
			$children .= "JTree$a_id.addTreeItem( \"$href 顯示 </a>\" );\n";
		}
		else {
			$children .= "JTree$parent_id.addTreeItem( JTree$a_id );\n";
			$children .= "JTree$a_id.addTreeItem( \"$href 顯示 </a>\" );\n";
		}
	}
	$tpl->assign("CHILDREN", $children);

	$tpl->assign( PHPSID, $PHPSESSID );

	$tpl->parse(BODY, "main");

	$tpl->FastPrint(BODY);
?>