<?

	session_id($PHPSESSID);
	session_start();

	require 'fadmin.php';
	update_status ("coop���s�峹");

	if(session_check_teach($PHPSESSID) < 1 || check_group ( $course_id, $coopgroup, $coopcaseid ) == 0 ) {
		show_page("not_access.tpl", "�A�S���v�����榹�\��.\nYou have no permission to perform this function.");
		exit();
	}

	function GetUserName($user_id) {

		global $DBC, $DB;

		$sql = "select name,nickname from user where id='$user_id'";
		$result = mysql_db_query($DB, $sql) or die("��Ʈw�d�߿��~, $sql");

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

	mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("��Ʈw�s�����~");

	$tablename = "discuss_".$coopcaseid."_".$discuss_id;

	if( (check_group ( $course_id, $coopgroup, $coopcaseid ) >= 2) && (session_check_teach($PHPSESSID) != 0) && (strcmp($guest,"1") != 0) && (session_check_teach($PHPSESSID) != 3)) {
		if( isset($log) ) {
			add_log_coop( 5, $user_id, "", $course_id, "", "", $coopgroup, $coopcaseid );
		}
	}

	include("class.FastTemplate.php3");

	$tpl = new FastTemplate("./templates");

	// ���^�媩 + �Ѯv/�ǥ� ���P�_.
	if($version == "C") {
		if(check_group ( $course_id, $coopgroup, $coopcaseid ) == 2) {
			$tpl->define(array(main => "article_list_tch.tpl"));
		}
		else {
			$tpl->define(array(main => "article_list_stu.tpl"));	       
		}
	}
	else {
		if(check_group ( $course_id, $coopgroup, $coopcaseid ) == 2) {
			$tpl->define(array(main => "article_list_tch_E.tpl"));
		}
		else  {
			$tpl->define(array(main => "article_list_stu_E.tpl"));	   
		}
	}

	$tpl->define_dynamic("article_list", "main");
	
	$tpl->assign("SKINNUM", $skinnum);
	$tpl->assign("TITLE", "�{�����Q�ץD�D");
	$tpl->assign("DIS_ID", $discuss_id);
	$tpl->assign("GROUP_NUM", $group_num);


	// �ﭶ�Ƶ��ܼư���l��.
	if(empty($parent)) $parent = 0;
	if(empty($page)) $page = 0;
	$current_id = $page*15;

	// �ثe�Ҧb����, �@��15�g�峹.
	$tpl->assign("PAGE_NUM", $page+1);
	$tpl->assign("PAGE_NOW", $page);

	// �`����
	$totalRows = mysql_db_query($DBC.$course_id, "select COUNT(*) from $tablename where parent=0") or die("��Ʈw�d�߿��~");
	$totalRows = mysql_fetch_array($totalRows);
	$totalRows = $totalRows[0];
	$tpl->assign("PAGE_TOTAL",ceil($totalRows/15));
	$tpl->assign("ART_TOTAL", $totalRows);

	// assign SQL query.
	$sql = "select * from $tablename where parent=0";
	switch($sortby) {
		case 1:   // by poster
			$sql = $sql." order by poster";
			break;
		case 2:   // by replied
			$sql = $sql." order by replied desc";
			break;
		case 3:   // by viewed
			$sql = $sql." order by viewed desc";
			break;
		default:  // default by date = by a_id.
			$sql = $sql." order by a_id desc";
	}
	$sql = $sql." limit $current_id,15;";

	$result = mysql_db_query($DBC.$course_id, $sql) or die("��Ʈw�d�߿��~, $sql");


	// ����article_list
	if($totalRows > 0) {
		$counter = 0;
		while($row = mysql_fetch_array($result))
		{
			$article_id = $row["a_id"];
			$title = "<a href='show_article.php?discuss_id=$discuss_id&article_id=$article_id&page=$page&PHPSESSID=$PHPSESSID'>".$row["title"]."";
			$poster = GetUserName($row["poster"]);
			$created = $row["created"];
			$replied = $row["replied"];
			$parent = $row["parent"];
			$body = $row["body"];
			$viewed = $row["viewed"];

			$tpl->assign("ITEMA", $title);
			$tpl->assign("ITEMB", $poster);
			$tpl->assign("ITEMC", $created);
			$tpl->assign("ITEMD", $replied);
			$tpl->assign("ITEME", $viewed);

			// Total reply atircle number.
			$sql2 = "select count(*) from $tablename where parent=$article_id";
			$result2 = mysql_db_query($DBC.$course_id, $sql2) or die("��Ʈw�d�߿��~, $sql2");
			if( $row2 = mysql_fetch_array($result2) ) {
				$tpl->assign("CHILDS", $row2[0]);
			}
			else {
				$tpl->assign("CHILDS", 0);
			}

			// �Ѯv�i�H�R���峹, �ǥͧR���峹�����b show_article.php
			if(check_group ( $course_id, $coopgroup, $coopcaseid ) == 2)
				$tpl->assign("DELETE", "<input type='checkbox' name='del_id[$counter]' value='$article_id' onClick='selected=(selected||this.checked);'>\n");

			if($counter%2 == 0) 
				$tpl->assign("ARCOLOR", "#ffffff");
			else
				$tpl->assign("ARCOLOR", "#edf3fa");

          
			$tpl->parse(ROWA, ".article_list");
			$counter++;
		}

		// ���ͤW/�U�@�����s��.
		if($page+1 < ceil($totalRows/15)) {
			if($version == "C") {
				$tpl->assign("NEXT_PAGE","&nbsp;|&nbsp;<a href='$PHP_SELF?discuss_id=$discuss_id&page=".($page+1)."&sortby=$sortby'>�U�@��</a>");
			}
			else {
				$tpl->assign("NEXT_PAGE","&nbsp;|&nbsp;<a href='$PHP_SELF?discuss_id=$discuss_id&page=".($page+1)."&sortby=$sortby'>Next_Page</a>");
			}
		}
		else
			$tpl->assign("NEXT_PAGE", "");

		if($page > 0) {
			if($version == "C") {
				$tpl->assign("PREV_PAGE","<a href='$PHP_SELF?discuss_id=$discuss_id&page=".($page-1)."&sortby=$sortby'>�W�@��</a>&nbsp;|&nbsp;");
			}
			else {
				$tpl->assign("PREV_PAGE","<a href='$PHP_SELF?discuss_id=$discuss_id&page=".($page-1)."&sortby=$sortby'>Previous_page</a>&nbsp;|&nbsp;");
			}
		}
		else
			$tpl->assign("PREV_PAGE", "");

		// ���ͦU�����s��.
		$pagelink = NULL;
		for( $i=0;$i<ceil($totalRows/15);$i++ ) {
			if($page == $i) {
				$pagelink .= "&nbsp;<font color='red'>".($i+1)."</font>&nbsp;";
			}
			else {
				$pagelink .= "&nbsp;<a href='$PHP_SELF?discuss_id=$discuss_id&page=".$i."&sortby=$sortby'>".($i+1)."</a>&nbsp;";			
			}
		}
		$tpl->assign("PAGE_LINK", $pagelink);
	}
	else {   // �ҥ~�B�z
		$tpl->assign("ITEMA", ""); 
 		$tpl->assign("ITEMB", "");
		$tpl->assign("ITEMC", "");
		$tpl->assign("ITEMD", "");
		$tpl->assign("ITEME" ,"");
		$tpl->assign("CHILDS", "0");
		$tpl->assign("DELETE", "");
		$tpl->assign("PREV_PAGE", "");
		$tpl->assign("NEXT_PAGE", "");
		$tpl->assign("PAGE_LINK", "");
	}
   
	$tpl->parse(BODY, "main");

	$tpl->FastPrint(BODY);
?>