<?
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

	// initial time logging variables.
	include "class.FastTemplate.php3";
	$tpl = new FastTemplate("./templates");

	if($version == "C") {
		$tpl->define(array(main => "scorm_menu.tpl"));
	}
	else {
		$tpl->define(array(main => "scorm_menu_E.tpl"));
	}
	$tpl->define_dynamic("row", "main");
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		echo( "資料庫連結錯誤!!" );
		exit;
	}

	$Q1 = "select lesson_id, sco_name, sco_id from sco_register order by sequence";
	$result = mysql_db_query($DB.$course_id, $Q1) or die("資料庫查詢錯誤, $Q1");
	if(mysql_num_rows($result) != 0) {
		$Q2 = "select MAX(level) as max from lesson";
		$result2 = mysql_db_query($DB.$course_id, $Q2 ) or die("資料庫查詢錯誤, $Q2 ");
		if( !($row2 = mysql_fetch_array($result2)) ) {
			echo( "資料庫讀取錯誤!!" );
			exit;
		}
		if ( $row2['max'] != null ) {
			for ( $level = $row2['max'] ; $level >= 1 ; $level -- ) {
				$Q3 = "select a_id, title, location, lesson_id, parent_id from lesson where level='$level' order by a_id";
				$result3 = mysql_db_query($DB.$course_id, $Q3 ) or die("資料庫查詢錯誤, $Q3 ");
				while ( $row3 = mysql_fetch_array($result3) ) {
					$children = "";
					$href = "";
					// 產生章的選項
					$chap = $row3['a_id'];
					$lesson_id = $row3['lesson_id'];
					$name = $row3['title'];
					$name = htmlspecialchars( $name );
					$name = addslashes( $name );
					$children = "";
					if ( $row3['location'] != null ) {
						if ( stristr($row3['location'],"http://") == NULL ) {
							$href = "<a href='/$course_id/scorm/".$row3['location']."' onClick='top.setscovalue( $chap, null, null );' target='html'>";
						}
						else {
							$href = "<a href='".$row3['location']."' onClick='top.setscovalue( $chap, null, null );' target='html'>";
						}
						$parent = "window.JTree$chap = new Tree(\"$href$name</a>\");";
					}
					else {
						$href = "<a href='./scorm_lesson.php?lessonid=$chap' onClick='top.setscovalue( $chap, null, null );' target='html'>";
						$parent = "window.JTree$chap = new Tree(\"$href$name</a>\");";
					}
					$tpl->assign(PARENT, $parent);
					$image = "JTree$chap.folderIcons = new Array(\"/images/coursefolder.gif\",\"/images/coursefolder_h.gif\",\"/images/coursefolder_s.gif\",\"/images/coursefolder_s.gif\");\n".
						"JTree$chap.itemIcons = new Array(\"/images/courseitem.gif\",\"/images/courseitem_h.gif\",\"/images/courseitem.gif\",\"/images/courseitem_h.gif\");";
					$tpl->assign(IMAGE, $image);
					// 產生節的選項
					$Q4 = "select sr.a_id, sr.sco_name, sr.location, l.a_id as lid from sco_register sr, lesson l where sr.parent_id = '$lesson_id' and sr.lesson_id = l.lesson_id order by a_id";
					$result4 = mysql_db_query($DB.$course_id, $Q4 ) or die("資料庫查詢錯誤, $Q4 ");
					$presec = 0;
					if ( mysql_num_rows( $result4 ) != 0 ) {
						while ( $row4 = mysql_fetch_array($result4) ) {
							$sect_id = $row4['a_id'];
							if ( $begin_end["$chap"][0] == null || $begin_end["$chap"][0] == "")
								$begin_end["$chap"][0] = $sect_id;
							
							$sect_title = $row4['sco_name'];
							$sect_title = htmlspecialchars( $sect_title );
							$sect_title = addslashes( $sect_title );
							$href = "<a href='' onClick='top.setscovalue(".$row4['lid'].", $sect_id, null);return false;' target=html>";
							$Q5 = "select a_id from lesson where parent_id = '$lesson_id' order by a_id";
							$result5 = mysql_db_query($DB.$course_id, $Q5 ) or die("資料庫查詢錯誤, $Q5 ");
							while ( $row5 = mysql_fetch_array($result5) ) {
								if ( $begin_end[$row5['a_id']][0] > $presec && $begin_end[$row5['a_id']][1] < $sect_id ) {
									$children .= "JTree$chap.addTreeItem( JTree".$row5['a_id']." );\n";
									$presec = $begin_end[$row5['a_id']][1];
								}
							}
							$presec = $sect_id;
							$children .= "JTree$chap.addTreeItem( \"$href$sect_title</a>\" );\n";
						}
						$begin_end["$chap"][1] = $sect_id;
					}
					else {
						$Q5 = "select a_id from lesson where parent_id = '$lesson_id' order by a_id";
						$result5 = mysql_db_query($DB.$course_id, $Q5 ) or die("資料庫查詢錯誤, $Q5 ");
						while ( $row5 = mysql_fetch_array($result5) ) {
							$children .= "JTree$chap.addTreeItem( JTree".$row5['a_id']." );\n";
						}
					}
					$children .= "JTree$chap.protoTree = JTree$chap;\n";
					$tpl->assign(CHILDREN, $children);
					$tpl->parse(ROWS, ".row");
					if ( $level == 1 ) {
						$showtree .="tRoot.addTreeItem(JTree".$chap.");\n";
					}
				}
			}
		}
		else {
			$Q3 = "select a_id, sco_name, location from sco_register order by a_id";
			$result3 = mysql_db_query($DB.$course_id, $Q3 ) or die("資料庫查詢錯誤, $Q3 ");
			while ( $row3 = mysql_fetch_array($result3) ) {
				$sect_id = $row3['a_id'];
				$sect_title = $row3['sco_name'];
				$href = "<a href=# onClick='top.setscovalue(null, $sect_id, null);return false;' target=html>";
				$showtree .="tRoot.addTreeItem( \"$href$sect_title</a>\" );\n";
			}
		}
		$tpl->assign("SHOWTREE", $showtree);
		$tpl->assign("PARENT", "");
		$tpl->assign("IMAGE", "");
		$tpl->assign("CHILDREN", "");
		$tpl->parse(ROWS, ".row");
	}
	else {
		$tpl->assign("PARENT", "");
		$tpl->assign("IMAGE", "");
		$tpl->assign("CHILDREN", "");
		$tpl->assign("SHOWTREE", "");
		$tpl->parse(ROWS, ".row");
	}
	if ( $version == "C" )
		$tpl->assign( LLIST, "課程清單" );
	else
		$tpl->assign( LLIST, "Lesson List" );
	$tpl->assign( PHPSID, $PHPSESSID );
	$tpl->parse(BODY, "main");

	$tpl->FastPrint(BODY);
?>