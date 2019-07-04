<?
// param : $course_id (session)
//         $html_type (form in editor_root/chap/sect.tpl), 辨別欲修改/新增的網頁.
//         $chap_num  (form in editor_chap/sect.tpl)
//         $sect_num  (form in editor_sect.tpl)

session_id($PHPSESSID);
session_start();


// 8/15 停用
// 用來將<body></body>之間的內容取出.
function parse_html($type, $chap_num, $sect_num) {
	global $course_id;

    // 判斷檔名與讀取檔案.
	$filename = "../../$course_id/textbook";
	switch($type) {
		case 1:   // 課程導論
			$filename = $filename."/intro.html";
			break;
		case 2:   // 某章的首頁
			$filename = $filename."/$chap_num/index.html";
			break;
		case 3:   // 某節的首頁
   			$filename = $filename."/$chap_num/$sect_num/index.html";
			break;
	}

	if(is_file($filename)) {
		$fp = fopen($filename, "r");
		while ($buffer = fgets($fp, 4096)) {
			$content = $content.$buffer;
		}

		// 將html分成三段, [0]->body前段 ,[1]->body, [2]->body後(應該只有</html>)
		$content = spliti("(<body|</body>)", $content);

		$content[1] = explode(">", $content[1]);
		$content[1] = implode(">", array_slice($content[1], 1));

		return $content[1];
	}
	else
		return "";
}


// 用來將<body></body>之間的內容寫回去.
function write_html($newcontent, $type, $chap_num, $sect_num) {
	global $course_id;
	global $PHPSESSID;
	$html_head = "<html>\n<body background='/learn/img/bg.gif'";
	$html_foot = "</body></html>";

	$filename = "../../$course_id/textbook";
	switch($type) {
		case 1:
			$filename = $filename."/index.html";

			// 與log相關的程式碼. 目前導論部分不記入資料庫.
			$script = ">";
			$html_head = $html_head.$script;
			
			$fp = fopen($filename, "w");
			fputs($fp, $newcontent);
			fclose($fp);

			/*  OLD method. 目前未使用.
			// 檔案存在, 表示index.html已被修改成分割視窗
			if(is_file($filename)) {
				$fp = fopen($filename, "w");
				fputs($fp, $html_head.$newcontent.$html_foot);
				fclose($fp);
			}
			else {
				//  先修改index.php 成為分割視窗
				$fp = fopen("../$course_id/textbook/index.php", "w");
				fputs($fp, $html_index);
				fclose($fp);

				// 建立intro.html
				$fp = fopen($filename, "w");
				fputs($fp, $html_head.$newcontent.$html_foot);
				fclose($fp);
			}
			*/

			header("Location: editor_main.php?errno=6&PHPSESSID=$PHPSESSID");
			break;
		case 2:
			$filename = $filename."/$chap_num/index.html";

			// 只寫章標號, 節編號填0   (於 7/5 刪除不記, 只記某章某節.)
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

			// 紀錄時間換成按下節的hyperlink
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


// 只有在確定更新網頁時才會執行的部分.
if(isset($html_type))  {
		write_html(stripslashes($content), $html_type, $chap_num, $sect_num);
}
?>