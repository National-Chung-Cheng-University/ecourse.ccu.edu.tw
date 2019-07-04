<?
// 訂閱寄信處理程式.
// 此程式於需執行時, 會經由系統的crontab自動執行.

// 2002/10/24
// 增加一個 DeBug Switch. 用來測試各個 User/E-mail 是否可收到信.

	include("mail.php");
	require 'common.php';

	$debug = false;
	//$debug = true;
	$debug_user = Array();
	$debug_email = Array();
	$debug_did = Array();
	$debug_course_id = 41495;
	$debug_user[0] = "kof9x";
	$debug_email[0] = "services@mail.elearning.ccu.edu.tw";
	$debug_did[0] = 2;
	$SERVER_NAME = "ecourse.elearning.ccu.edu.tw"; 
	/*
	$debug_user[1] = "kof9x";
	$debug_email[1] = "kof9x1@yahoo.com.tw";
	$debug_did[1] = 133;*/


/*--------------------------------------------------------------------------------------*/
	function GetUserName($user_id) {

		global $DB;

		$sql = "select name,nickname from user where id='$user_id'";
		$result = mysql_db_query($DB, $sql);

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
/*--------------------------------------------------------------------------------------------------*/
	mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("資料庫連結錯誤");
	
	if( !$debug ) {
		$Q1 = "select * from course";
	}
	else {
		$Q1 = "select * from course where a_id=".$debug_course_id;
	}
	$R1 = mysql_db_query($DB, $Q1) or die("資料庫查詢錯誤, $Q1");

	// 整個程式的主要 while loop. Course by Course looping.

	while ( $row0 = mysql_fetch_array($R1) ) {

		$tablename = array();
		$user = array();				// User Name
		$did = array();					// Discuss ID.
		$disname = array();				// 每次都清空，不然會出現文不對題
		$email = array();				// User E-mail, 和 User Name 有一對一的關係
		$course_id = $row0["a_id"];
		$coursename = $row0["name"];
		echo "<hr>Course ".$course_id." ".$coursename."<br>\n";
		// 由 discuss_subscribe 讀出訂閱者資料.
		$sql = "select * from discuss_subscribe order by discuss_id";
		$result = mysql_db_query($DB.$course_id, $sql) or die("資料庫查詢錯誤, $sql, ".$DB.$course_id);

		if(mysql_num_rows($result) > 0) {
			// 先將需要資料放進陣列中.
			// 查詢使用者的 email 和其訂閱的 討論區編號.

			if( !$debug ) {
				$i=0;
				while($row = mysql_fetch_array($result)) {
					/*$cid[$i] = $row["course_id"];*/
					$user[$i] = $row["user_id"];
					$did[$i] = $row["discuss_id"];

					$sql = "select email from user where id='".$user[$i]."'";
					$result2 = mysql_db_query($DB, $sql) or die("資料庫查詢錯誤, $sql");
					list($email[$i]) = mysql_fetch_array($result2);
					$i++;
				}
			}
			else {

				$user = $debug_user;
				$email = $debug_email;
				$did = $debug_did;
			}
					
			// 準備查詢新文章
			// 先把要查詢的tablename準備好.
			for($i=0;$i<sizeof($did);$i++) {
				$tablename[$i] = "discuss_".$did[$i];
			}
			array_unique($tablename);

			// 討論區標題.
			for($i=0;$i<sizeof($did);$i++) {
				if(empty($disname[(string)$did[$i]])) {
					$sql = "select discuss_name from discuss_info where a_id=".$did[$i];
					$result = mysql_db_query($DB.$course_id, $sql) or die("資料庫查詢錯誤, $sql");
					$row = mysql_fetch_array($result);
					$disname[(string)$did[$i]] = $row["discuss_name"];
				}
			}

			// 查詢前一天的新文章. 之前的準備動作是避免重複查詢.
			// 此動作會將所有新文章的result object存放到 $newart[] 中.
			for($i=0;$i<sizeof($tablename);$i++) {	
				$sql = "select * from ".$tablename[$i]." where TO_DAYS(NOW())-TO_DAYS(created)<=1 order by parent";
				$newart[$tablename[$i]] = mysql_db_query($DB.$course_id, $sql) or die("資料庫查詢錯誤 $course_id, $sql");
			}
			

			// 寄信.
			for($i=0;$i<sizeof($did);$i++) {
				$d_name = "discuss_".$did[$i];
				$mailbody = array();
				$counter = 0;
				// 檢查是否存在新文章.
				if(mysql_num_rows($newart["$d_name"]) > 0) {
					while($row = mysql_fetch_array($newart[ $d_name ])) {
						$a_id = $row["a_id"];
						$title = $row["title"];
						$poster = GetUserName($row["poster"]);
						$created = $row["created"];
						$type = $row["type"];
						$sound = $row["sound"];
						$parent = $row["parent"];

						// 改變回文文章顏色
						$body = explode("\n", htmlspecialchars($row["body"]) );
						$flag = false;
						for( $j=0; $j<sizeof($body); $j++) {
							$pos = strpos($body[$j], "&gt");
							if(  ($pos == 0) && ($pos !== false)  && ( !$flag ) ) {
								$flag = true;
								$body[$j] = "<font style='background-color: #D0E0D0' size='-1' color = '#005500'>".$body[$j];
							}
							else if ( $flag && ($pos === false) ) {
								$body[$j] = "</font>".$body[$j];
								$flag = false;
							}
						}

						$body = ereg_replace("\n", "<br>\n", implode("\n", $body));
						$body = ereg_replace("  ", "　", $body);
						//end change color part.

						// get parent title.
						if( $parent!=0 )
						{
							$sqlo = "select title from $d_name where a_id=$parent";
							$resulto = mysql_db_query($DB.$course_id, $sqlo) or die("資料庫查詢錯誤, $sqlo");
							$rowo = mysql_fetch_array($resulto);
							$ptitle = $rowo["title"];
						}
						else
						{
							$ptitle = $title;
						}

						if( $type!=NULL ) 
						{
							if( $type!="/" ) {
								$filelink = "<td colspan=3>".
											"<a href='http://$SERVER_NAME/".$course_id."/board/".
											$did[$i]."/$a_id.$type'>$a_id.$type</a>";
							}
							else {
								$filelink = "<td colspan=3>".
											"<a 	href='http://$SERVER_NAME/".$course_id."/board/".
											$did[$i]."/$a_id.$type'>$a_id</a>";						
							}
						}
						else 
						{
							$filelink = "<td colspan=3>(NULL)";
						}
						//end.
						
						// generate sound link.
						if( $sound!=NULL ) 
						{
							$objectcode = "<td colspan=3>".
							"<object classid=\"clsid:A809FC66-1FEB-11D5-A00F-00D0B74E04B7\" id=\"AudioBoard1\" ".
							"width=\"85\" height=\"33\" ".
							"codebase=\"http://$SERVER_NAME/learn/packages/audioboard.cab#version=1,0,0,1\" ".
							"standby=\"Loading AudioBoard Components\" ".
							"type=\"application/x-oleobject\">\n";

							// server name.
							$param[0] = "<param name=\"Server\" value=\"$SERVER_NAME\">";
							// voice file URL.
							$param[1] = "<param name=\"Url\" value=\"/discuss/attach/".$row["sound"]."\">";
							// download  voice filename.
							$param[2] = "<param name=\"FilePath\" value=\"c:\_download.gsm\">";
							// fixed param.
							$param[3] = "<param name=\"SystemMode\" value=\"101\">";
							$param[4] = "<param name=\"Codec\" value=\"1\">";
							$objectend = "</object>";
							$objectcode = $objectcode.implode("\n",$param).$objectend;
						}
						else 
						{
							$objectcode = "<td colspan=3>(NULL)";
						}
						//end.
						
						$message = 	"<table border=2 width=100%>".
									"<tr><td bgcolor=#4d6be2><font color=#ffffff>課程名稱 [Coursename]</font>\r\n".
									"<td>".$coursename.
									"<td bgcolor=#4d6be2><font color=#ffffff>討論區名稱 [Discussname]</font>\r\n".
									"<td>".$disname[(string)$did[$i]].
									"<tr><td bgcolor=#4d6be2><font color=#ffffff>張貼者 [Poster]</font><td>$poster\r\n".
									"<td bgcolor=#4d6be2><font color=#ffffff>張貼日期 [Time]</font><td>$created\r\n".
									"<tr><td bgcolor=#4d6be2><font color=#ffffff> 文章標題 <b>&lt;原討論主題&gt;</b> [Title | <b>Original Title</b>]</font>\r\n".
									"<td colspan=3>$title &lt;<b>$ptitle</b>&gt;\r\n".
									"<tr><td colspan=4>".$body.
									"\r\n<tr><td bgcolor=#4d6be2><font color=#ffffff>相關檔案 [Related File]</font>".$filelink.
									"\r\n<tr><td bgcolor=#4d6be2><font color=#ffffff>附加語音檔案 [Voice File]".$objectcode.
									"</table>\r\n";

						if( $parent==0 ) {
							$mailbody[$a_id] = $mailbody[$a_id].$message;
						}
						else {
							$mailbody[$parent] = $mailbody[$parent].$message;						
						}
						$counter++;

					}// end while loop.
					
					// 參考mail.php後面的說明.
					$mail = new mime_mail();
					$mail->from = "study@".$SERVER_NAME;
					$mail->headers = "Errors-To:kof2k@seed.net.tw\n";
					$mail->headers .= "Reply-To:study@".$SERVER_NAME;
					$mail->to = $email[$i];
					$mail->subject = $coursename." ".$disname[(string)$did[$i]]." ($counter)";

					$mail->body = "<html><body>".implode("\r\n<br><hr><br>", $mailbody).
									"<hr>欲回覆文章的話, 請登入系統 <a href='http://$SERVER_NAME/'> http://$SERVER_NAME </a><br>\r\n".
									"Please login if you wish to reply this article <a href='http://$SERVER_NAME/'> http://$SERVER_NAME </a><br>\r\n</body></html>";
					
					echo "User:\t";
					var_dump($user[$i]);
					echo "<br>\n";

					echo "Discuss ID:\t";
					var_dump($did[$i]);
					echo "<br>\n";

					echo "E-Mail:\t";
					var_dump($email[$i]);
					echo "<br>\n";

					echo "Discuss Name:\t";
					var_dump($disname[$i]);
					echo "<br>\n";
					
					echo "Loop NO.".$i."<hr width=70%>\n";
					$mail->send();

					// 將 result set 的 data pointer調回原來狀態.
					mysql_data_seek($newart["$d_name"], 0);
				}
				else {

					echo "No New Articles.";
				}
			}
		}
	} // end of big while
?>
