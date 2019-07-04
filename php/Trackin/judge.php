<?php
	require 'fadmin.php';

	if (isset($PHPSESSID) && session_check_admin($PHPSESSID)) {
		show_page_d();
	} else
		show_page("index_ad.tpl","�A���v�����~�A�Э��s�n�J!!");

	function show_page_d ($message="") {
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;

		//�O�_�@���C�X�����ҵ{
		if (isset($_GET['viewall']))
			$viewall = 1;
		else
			$viewall = 0;

                $curr_page = $_GET['p']; //�ثe����
                if (!isset($curr_page)) $curr_page = 1;

                $limit = $_GET['s']; //�C����ܵ���
                if (!isset($limit)) $limit = 100;

		$sql_start_p = ($curr_page - 1) * $limit;	

		$Q_total = "select u.name, u.id, c.group_id, cg.name AS gname, tc.course_id, c.name AS cname, c.course_no  FROM course c, course_group cg, teach_course tc , user u where tc.teacher_id = u.a_id and c.a_id = tc.course_id and cg.a_id = c.group_id";
		if ($viewall == 0) {
			$Q1 = "select u.name, u.id, c.group_id, cg.name AS gname, tc.course_id, c.name AS cname, c.course_no  FROM course c, course_group cg, teach_course tc , user u where tc.teacher_id = u.a_id and c.a_id = tc.course_id and cg.a_id = c.group_id LIMIT " . $sql_start_p . "," . $limit;
		}

		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$message = "$message - ��Ʈw�s�����~!!";
		}
		if ( !($result_total = mysql_db_query( $DB, $Q_total ) ) ) {
			$message = "$message - ��ƮwŪ�����~!!";
		}
		if ($viewall == 0) {
			if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
				$message = "$message - ��ƮwŪ�����~!!";
			}
		} else
			$result = $result_total;

		printHTMLheader($viewall);
		if ($viewall == 0) printPageList($curr_page,$limit,mysql_num_rows($result_total),"pagelist_up");
		printTABLEheader();

		while ($row = mysql_fetch_array($result)) {
			if ($row['name'] == "")
				$tmp_name = "";
			else
				$tmp_name =  $row['name'];
			$tmp_id = $row['id'];
			$tmp_course = $row['gname'] . "/(" . $row['course_no'] . ")" . $row['cname'];
			$tmp_button = "<input type=hidden name=courseid value=" . $row['course_id'] . "><input type=hidden name=cname value=(" . $row['course_no'] . ")" . $row['cname'] . "><input type=hidden name=userid value=" . $row['id'] . "><input type=submit value=�[��>";

			printRecord($tmp_name,$tmp_id,$tmp_course,$tmp_button);
		}

		printTABLEfooter();
		if ($viewall == 0) printPageList($curr_page,$limit,mysql_num_rows($result_total),"pagelist_bottom");
		printHTMLfooter();
	}

	function printHTMLheader($viewall) {
		echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
		echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
		echo "<head>\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=big5\" />\n";
		echo "<title>�ҵ{��Ų-�ҵ{�C��</title>\n";
		echo "<style type=\"text/css\">\n";
		echo ".style1 {color: #FFFFFF}\n";
		echo "</style>\n</head>\n<body>\n";

		if ($viewall == 0)
			echo "<a href=\"/php/Trackin/judge.php?viewall=1\">��ܥ����ҵ{</a><br/>\n";
	}

	function printHTMLfooter() {
		echo "</body>\n</html>";
	}

	function printTABLEheader() {
		echo "<table width=\"100%\" border=\"1\">\n";
		echo "  <tr bgcolor=\"#000000\">\n";
		echo "    <td width=\"125\"><div align=\"center\" class=\"style1\">�Юv</div></td>\n";
		echo "    <td width=\"120\"><div align=\"center\" class=\"style1\">�ЮvID</div></td>\n";
		echo "    <td width=\"70%\"><div align=\"center\" class=\"style1\">�ҵ{�W��</div></td>\n";
		echo "    <td width=\"76\"><div align=\"center\" class=\"style1\">�[��</div></td>\n";
		echo "  </tr>\n";
	}

	function printTABLEfooter() {
		echo "</table>\n<br/>\n";
	}

	function printRecord($name,$id,$course,$button) {
		echo "<form method=\"post\" action=\"/php/Trackin/course_info.php\">\n";
		echo "<tr>\n";
		echo "<td>" . $name . "</td>\n";
		echo "<td>" . $id . "</td>\n";
		echo "<td>" . $course . "</td>\n";
		echo "<td>" . $button . "</td>\n";
		echo "</tr>\n";
		echo "</form>\n";
	}

	function printPageList($curr_page,$limit,$record_count,$formid) {
		$pages = ((int) ($record_count / $limit)) + 1; //�`����

		echo "<form method=\"GET\" id=\"" . $formid . "\" action=\"/php/Trackin/judge.php\">\n";
		echo "���ܦ����G \n";
		echo "<input type=\"hidden\" name=\"s\" value=\"" . $limit . "\" />\n";
		echo "<select name=\"p\" onchange=\"javascript: document.getElementById('" . $formid . "').submit();\">\n";

		for ($i=1;$i<=$pages;$i++) {
			if ($i != $curr_page)
				echo "<option value=\"" . $i . "\">" . $i . "</option>\n";
			else
				echo "<option value=\"" . $i . "\" selected=\"selected\">" . $i . "</option>\n";
		}

		echo "</select>\n";
		echo "</form>\n<br/>\n";
	}
?>
