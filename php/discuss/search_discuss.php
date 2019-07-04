<?
// simple discuss search program.
// param : $keyword (dis_list. Each word is seperated by " ")
//         $type    (dis_list, 0 = title, 1 = poster, 2 = content)
// last Update: 2002/07/30 by Autumn. 增加分組管理功能

	require 'fadmin.php';
	include("class.FastTemplate.php3");

	function GetUserName($user_id) {

		global $DB;

		$sql = "select name,nickname from user where id='$user_id'";
		$result = mysql_db_query($DB, $sql) or die("資料庫查詢錯誤, $sql");

		// check name field. if exists, use it as poster name.
		if(mysql_num_rows($result) > 0) {
			$row = mysql_fetch_array($result);
			if( strcmp($row["nickname"], "" )!=0) {
				$poster = $row["nickname"];
			}
			elseif(strcmp($row["name"], "" ) !=0 ) {
				$poster = $row["name"];
			}
			else {
				$poster = $user_id;
			}
		}
		else {
			// Default.
			$poster = $user_id;
		}

		return $poster;
	}

	function GetUserID($name) {
		global $DB;

		$sql = "select id from user where name like '%$name%' or nickname like '%$name%' or id like '%$name'";
		$result = mysql_db_query($DB, $sql) or die("資料庫查詢錯誤, $sql");

		$id[0] = $name;

		if(mysql_num_rows($result) > 0) {
			$i = 1;
			while($row = mysql_fetch_array($result)) {
				$id[$i] = $row[0];
				$i++;
			}
		}

		return $id;
	}


	mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("資料庫連結錯誤");

	/* define template, dynamic block, and some initial value. */
	$tpl = new FastTemplate("./templates");

	if($version == "C") {
		$tpl->define(array(main => "search_discuss.tpl"));
		switch($type) {
			case 0:
				$types = "標題";
				break;
			case 1:
				$types = "作者";
				break;
			case 2:
				$types = "內容";
				break;			
		}
	}
	else {
		$tpl->define(array(main => "search_discuss_E.tpl"));
		switch($type) {
			case 0:
				$types = "Title";
				break;
			case 1:
				$types = "Poster";
				break;
			case 2:
				$types = "content";
				break;			
		}
	}

	$tpl->define_dynamic("result_list", "main");
	$tpl->assign("SKINNUM", $skinnum);
	$tpl->assign("TYPE", $types);
	$tpl->assign("KEYWORD", $keyword);

	$keyword = explode(" ",$keyword);

	// Error handle: NO target discuss group to be searched or NO article found.
	$tpl->assign("TITLE", "");
	$tpl->assign("ARTICLE_LINK", "");
	$tpl->assign("POSTER", "<font color=white>找不到任何文章符合搜尋條件.<br>\nNo article is founded.</font>");
	$tpl->assign("DISCUSS_NAME", "");
	
	/* Select data from table: discuss_info first, to decide which discuss group to be searched.*/
	// user check. that is due to some discuss group is private.
	
	$sql = "select a_id from user where id = '$user_id'";
	$result = mysql_db_query($DB, $sql) or die("資料庫查詢錯誤, $sql");
	$row = mysql_fetch_array($result);
	$a_id = $row['a_id'];
/*	if(session_check_teach($PHPSESSID)!=2) {
		// not teacher... some discuss group may not be searched.
		$sql = "select group_num from discuss_group where student_id='$user_id'";
		$result = mysql_db_query($DB.$course_id, $sql) or die("資料庫查詢錯誤, $sql");

		// 例外處理. 處理老師尚未分組的情況
		if(mysql_num_rows($result) > 0)  {
			$row = mysql_fetch_array($result);
			$grp_num = $row["group_num"];
			$sql = "select * from discuss_info where group_num=$grp_num or access=0 order by group_num,a_id";
		}
		else {
			$sql = "select * from discuss_info where access=0 order by group_num,a_id";
		}
	}
	else {*/
	
		$sql = "select * from discuss_info";
//	}
	$result = mysql_db_query($DB.$course_id, $sql) or die("資料庫查詢錯誤, $sql");
	
	// Total founded result counter.
	$total = 0;

	// Color control.
	$counter = 0;

	/* run while loop to search each discuss group.*/
	if(mysql_num_rows($result) > 0) {
		while($row = mysql_fetch_array($result)) {
			// get some info of the discuss group.
			$discuss_id = $row["a_id"];
			if( session_check_teach($PHPSESSID) < 2 ) {
				//學生的
				if ( $row['access'] != "0" ) {
					$sql = "select * from discuss_group_map where discuss_id='$discuss_id' and student_id ='$a_id'";
					$result2 = mysql_db_query($DB.$course_id, $sql) or die("資料庫查詢錯誤, $sql");
					if(mysql_num_rows($result2) == 0){
						continue;
					}
				}
			}
			$discuss_name = $row["discuss_name"];
			
			// search into the discuss group.
			// prepare for sql query.
			$tablename = "discuss_".$discuss_id;
			switch($type) {
				case 0:
					$sql2 = "select * from $tablename where title like '%".$keyword[0]."%'";
					for($i=1;$i<sizeof($keyword);$i++) {
						$sql2.= " and title like '%".$keyword[$i]."%'";
					}
					break;
				case 1:
					// because of user's input maybe poster's name/nickname, so program have to find user's id.
					$sql2 = "select * from $tablename where ";
					$id_list = array();

					for($i=0;$i<sizeof($keyword);$i++) {
						$id_list = array_merge($id_list, GetUserID($keyword[$i]));
					}

					$id_list = array_unique($id_list);
					for($i=0;$i<sizeof($id_list);$i++) {
						$id_list[$i] = "poster like '%".$id_list[$i]."%'";
					}

					$sql2 .= implode(" or ",$id_list);
					break;
				case 2:
					$sql2 = "select * from $tablename where body like '%".$keyword[0]."%'";
					for($i=1;$i<sizeof($keyword);$i++) {
						$sql2.= " and body like '%".$keyword[$i]."%'";
					}
					break;
			}
			$result2 = mysql_db_query($DB.$course_id, $sql2) or die("資料庫查詢錯誤, $sql2");

			// output result.
			if(mysql_num_rows($result2) > 0) {
				$total += mysql_num_rows($result2);
				while($row2 = mysql_fetch_array($result2)) {
					$title = $row2["title"];
					$poster = $row2["poster"];
					$article_id = $row2["a_id"];

					// Color control.
					if($counter%2 == 0) 
						$tpl->assign("BGCOLOR", "#ffffff");
					else
						$tpl->assign("BGCOLOR", "#edf3fa");
					$counter++;

					$tpl->assign("TITLE", $title);
					$tpl->assign("ARTICLE_LINK", "show_article.php?discuss_id=$discuss_id&article_id=$article_id");
					$tpl->assign("POSTER", GetUserName($poster));
					$tpl->assign("DISCUSS_NAME", $discuss_name);

					$tpl->parse(ROWA, ".result_list");
				}
			}
		}
	}

	$tpl->assign("TOTAL", $total);
	
	$tpl->parse(BODY, "main");

	$tpl->FastPrint(BODY);
?>