<?
//   param  : $discuss_id (dis_list.php / SELF, required.)
//            $group_num  (dis_list.php / SELF, required.)
//            $course_id  (session)
//            $user_id    (session, used for check group_num)
//            $sortby  (href,optional,  1=>poster | 2=>replied | 3=>title )
//            $page    (SELF,optional)
//            $errno   (post_article.php, optional) (unused now)
//   log is required.

	session_id($PHPSESSID);
	session_start();

	require 'fadmin.php';
	update_status ("���s�峹");

	function GetUserName($user_id) {

		global $DB;

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

	$tablename = "discuss_".$discuss_id;

	// �ˬd���հQ�װϪ�Ū���v��
	if($group_num != 0 && session_check_teach($PHPSESSID)!=2) {
		$sql = "select * from discuss_info where a_id=$discuss_id";
		$result = mysql_db_query($DB.$course_id, $sql) or die("��Ʈw�d�߿��~, $sql");
		if( $row = mysql_fetch_array($result) ) {
			if($row["access"] != 0) {  // �p�H�Q�װ�
				$sql = "select * from discuss_group where group_num=$group_num and student_id='$user_id'";
				$result = mysql_db_query($DB.$course_id ,$sql)  or die("��Ʈw�d�߿��~, $sql");
				if(mysql_num_rows($result) == 0)  {
					show_page("not_access.tpl", "���Q�װϬ��p�H(�p��)�Q�װ�, �A�ä��O�o�Ӥp�ժ��խ�.\n<br>This is a PRIVATE discussion group, and you are not members of the group.");
					exit();
				}
			}
		}
	}

	if((session_check_teach($PHPSESSID) != 0) && (strcmp($guest,"1") != 0) && (session_check_teach($PHPSESSID) != 3)) {
		if( isset($log) ) {
			add_log( 5, $user_id, "", $course_id);
		}
	}

	include("class.FastTemplate.php3");

	$tpl = new FastTemplate("./templates");

	// ���^�媩 + �Ѯv/�ǥ� ���P�_.
	if($version == "C") {
		if(session_check_teach($PHPSESSID)==2) {
			$tpl->define(array(main => "article_list_tch.tpl"));
		}
		else {
			$tpl->define(array(main => "article_list_stu.tpl"));	       
		}
	}
	else {
		if(session_check_teach($PHPSESSID)==2) {
			$tpl->define(array(main => "article_list_tch_E.tpl"));
		}
		else  {
			$tpl->define(array(main => "article_list_stu_E.tpl"));	   
		}
	}

	$tpl->define_dynamic("article_list", "main");

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
	$totalRows = mysql_db_query($DB.$course_id, "select COUNT(*) from $tablename where parent=0") or die("��Ʈw�d�߿��~");
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

	$result = mysql_db_query($DB.$course_id, $sql) or die("��Ʈw�d�߿��~, $sql");

	//zqq-----
	$tag1['course_id'] = $course_id;
	$tag1['discuss_id'] =$discuss_id;
	add_log(23, $user_id, $tag1);
	//------

	// ���o�w�\Ū�L���峹�s��
	global $read;
	query_read($user_id, $discuss_id);

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

			// �P�_�Y�@�Q�ת��D�D�O�_��Ū�L
			$is_read_all = is_read_all($discuss_id, $article_id);
			if($is_read_all==0)
				$tpl->assign("ITEMA", "<font color='#F6358A'>+ </font>".$title);
			else
				$tpl->assign("ITEMA", $title);
			$tpl->assign("ITEMB", $poster);
			$tpl->assign("ITEMC", $created);
			$tpl->assign("ITEMD", $replied);
			$tpl->assign("ITEME", $viewed);

			// Total reply atircle number.
			$sql2 = "select count(*) from $tablename where parent=$article_id";
			$result2 = mysql_db_query($DB.$course_id, $sql2) or die("��Ʈw�d�߿��~, $sql2");
			if( $row2 = mysql_fetch_array($result2) ) {
				$tpl->assign("CHILDS", $row2[0]);
			}
			else {
				$tpl->assign("CHILDS", 0);
			}

			// �Ѯv�i�H�R���峹, �ǥͧR���峹�����b show_article.php
			if(session_check_teach($PHPSESSID) == 2)
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


	function query_read($user_id, $discuss_id){
		global $DB, $course_id, $read;

/*		
		$q = "UPDATE user_profile SET discuss_1='NULL' WHERE student_id='E120036412'";
		if (!(mysql_db_query($DB.$course_id,$q)))
			die($q . " ��Ʈw�d�߿��~");
*/		

		// ��query�X��Ʈw����� �Юv��id�]�|�s�bstudent_id�̭�
		$q = "SELECT * FROM user_profile WHERE student_id='".$user_id."'";
		if (!($res = mysql_db_query($DB.$course_id,$q)))
			die($q . " ��Ʈw�d�߿��~");

		// �p�G�S���ۤv����Ʈ� �h�s�W�@��
		if(mysql_num_rows($res)==0){
			$q = "INSERT INTO user_profile SET student_id='".$user_id."'";
			if (!($res = mysql_db_query($DB.$course_id,$q)))
				die($q . " ��Ʈw�d�߿��~");
			return ;
		}

		$row = mysql_fetch_array($res);
		$dis_no = "discuss_".$discuss_id;
		$number_string = $row[$dis_no];
		$num_array = split(",", $number_string);
		for($i=0; $i<count($num_array); $i++)
			$read[$num_array[$i]]=1;  // $read[x] ��id=x���峹�OŪ�L��

	}

	function is_read_all($discuss_id, $article_id){
		global $DB, $course_id, $read;

		// �P�_�Y�@�Q�ץD�D���t�C�峹�O�_����Ū�L �Y�L�^��0 ��Ū�L�^��1
		if($read[$article_id]!=1) //���t�C�峹�����g��Ū
			return 0;
 
		$q = "SELECT a_id FROM discuss_".$discuss_id." WHERE parent=".$article_id." ORDER BY a_id ASC";
		if (!($res = mysql_db_query($DB.$course_id,$q)))
			die($q . " ��Ʈw�d�߿��~");

		while($row = mysql_fetch_array($res)){
			if($read[$row['a_id']]!=1)
				return 0;
		}

		return 1;
	}
?>
