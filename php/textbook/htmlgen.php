<?
// param : $course_id (session)
//         $html_type (form in editor_root/chap/sect.tpl), ��O���ק�/�s�W������.
//         $chap_num  (form in editor_chap/sect.tpl)
//         $sect_num  (form in editor_sect.tpl)

session_id($PHPSESSID);
session_start();


// 8/15 ����
// �ΨӱN<body></body>���������e���X.
function parse_html($type, $chap_num, $sect_num) {
	global $course_id;

    // �P�_�ɦW�PŪ���ɮ�.
	$filename = "../../$course_id/textbook";
	switch($type) {
		case 1:   // �ҵ{�ɽ�
			$filename = $filename."/intro.html";
			break;
		case 2:   // �Y��������
			$filename = $filename."/$chap_num/index.html";
			break;
		case 3:   // �Y�`������
   			$filename = $filename."/$chap_num/$sect_num/index.html";
			break;
	}

	if(is_file($filename)) {
		$fp = fopen($filename, "r");
		while ($buffer = fgets($fp, 4096)) {
			$content = $content.$buffer;
		}

		// �Nhtml�����T�q, [0]->body�e�q ,[1]->body, [2]->body��(���ӥu��</html>)
		$content = spliti("(<body|</body>)", $content);

		$content[1] = explode(">", $content[1]);
		$content[1] = implode(">", array_slice($content[1], 1));

		return $content[1];
	}
	else
		return "";
}


// �ΨӱN<body></body>���������e�g�^�h.
function write_html($newcontent, $type, $chap_num, $sect_num) {
	global $course_id;
	global $PHPSESSID;
	$html_head = "<html>\n<body background='/learn/img/bg.gif'";
	$html_foot = "</body></html>";

	$filename = "../../$course_id/textbook";
	switch($type) {
		case 1:
			$filename = $filename."/index.html";

			// �Plog�������{���X. �ثe�ɽ׳������O�J��Ʈw.
			$script = ">";
			$html_head = $html_head.$script;
			
			$fp = fopen($filename, "w");
			fputs($fp, $newcontent);
			fclose($fp);

			/*  OLD method. �ثe���ϥ�.
			// �ɮצs�b, ���index.html�w�Q�ק令���ε���
			if(is_file($filename)) {
				$fp = fopen($filename, "w");
				fputs($fp, $html_head.$newcontent.$html_foot);
				fclose($fp);
			}
			else {
				//  ���ק�index.php �������ε���
				$fp = fopen("../$course_id/textbook/index.php", "w");
				fputs($fp, $html_index);
				fclose($fp);

				// �إ�intro.html
				$fp = fopen($filename, "w");
				fputs($fp, $html_head.$newcontent.$html_foot);
				fclose($fp);
			}
			*/

			header("Location: editor_main.php?errno=6&PHPSESSID=$PHPSESSID");
			break;
		case 2:
			$filename = $filename."/$chap_num/index.html";

			// �u�g���и�, �`�s����0   (�� 7/5 �R�����O, �u�O�Y���Y�`.)
			/*$script = " onLoad=\"window.open('/php/log.php?event_id=3&ch_id=$chap_num&s_id=0','logging','toolbar=no');\">";
			*/
			$script = ">";
   			$html_head = $html_head.$script;

			$fp = fopen($filename, "w");
			fputs($fp, $newcontent);
			fclose($fp);

			header("Location: editor_main.php?chap=$chap_num&errno=7&PHPSESSID=$PHPSESSID");

			break;
		case 3:
   			$filename = $filename."/$chap_num/$sect_num/index.html";

			// �����ɶ��������U�`��hyperlink
			/*$script = " onLoad=\"window.open('/php/log.php?event_id=3&ch_id=$chap_num&s_id=$sect_num&PHPSESSID=$PHPSESSID','logging','toolbar=no');\">";*/
			$script = ">";
   			$html_head = $html_head.$script;

			$fp = fopen($filename, "w");
			fputs($fp, $newcontent);
			fclose($fp);

			header("Location: editor_main.php?chap=$chap_num&section=$sect_num&errno=8&PHPSESSID=$PHPSESSID");

			break;
	}
}


// �u���b�T�w��s�����ɤ~�|���檺����.
if(isset($html_type))  {
		write_html(stripslashes($content), $html_type, $chap_num, $sect_num);
}
?>