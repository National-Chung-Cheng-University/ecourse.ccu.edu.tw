<?
   // param:   $course_id  (session)
   //          $discuss_id   (form in dis_list.php, array)
   // Last update: 2002/02/27
   // last Update: 2002/07/30 by Autumn. 增加分組管理功能

	require 'fadmin.php';

	if($check = session_check_teach($PHPSESSID)!=2 && ( $submit == "刪除討論區" || $submit == "Delete group" )) {
		show_page("not_access.tpl", "你沒有權限執行此功能.<br>\nYou have no permission to perform this function.");
		exit();
	}

	mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("資料庫連結錯誤");

	if (( $submit == "刪除討論區" || $submit == "Delete group" )&& sizeof($discuss_id)!= 0 ) {
		while(list($key,$value) = each($discuss_id)) {
			// 將討論區的整個table刪除
			$tablename = "discuss_".$value;
			$sql = "drop table $tablename;";
			mysql_db_query($DB.$course_id, $sql) or die("資料庫查詢錯誤, $sql");
	
			//Autumn修改的部分
			$sql = "delete from discuss_subscribe where discuss_id='".$value."'";
			mysql_DB_query($DB.$course_id, $sql) or die("資料庫查詢錯誤, $sql");
			
			//刪除discuss_group_map
			$sql = "delete from discuss_group_map where discuss_id='".$value."'";
			mysql_DB_query($DB.$course_id, $sql) or die("資料庫查詢錯誤, $sql");
	
			// 將討論區的資料刪除
			$sql = "delete from discuss_info where a_id=".$value;
			mysql_DB_query($DB.$course_id, $sql) or die("資料庫查詢錯誤, $sql");
	
			// 將討論區的上傳檔案刪除
			$dir = "../../$course_id/board/".$value;
			deldir($dir);

			// 將己讀的記錄欄刪掉
			$Q2 = "alter table user_profile DROP discuss_".$value;
			if (!mysql_db_query($DB.$course_id,$Q2))
				die("資料庫查詢錯誤, $Q2");

		}
	}
	else if ( ($submit == "訂閱\\\" || $submit == "Subscribe")&& sizeof($discuss_id)!= 0  ) {
		while(list($key,$value) = each($discuss_id)) {
			$sql1 = "select * from discuss_subscribe where user_id='$user_id' and discuss_id = '".$value."'";
			if ( !($result = mysql_db_query( $DB.$course_id, $sql1 ) ) ) {
				show_page("not_access.tpl", "資料庫讀取失敗.", "", "<a href='dis_list.php'>Back</a>");
				exit;
			}
			else if ( mysql_num_rows($result) == 0 ) { 
				$sql = "insert into discuss_subscribe(user_id,discuss_id) values('$user_id',".$value.")";
				mysql_db_query($DB.$course_id, $sql) or die("資料庫查詢錯誤, $sql");
				if( mysql_affected_rows() == 0 ) {
					show_page("not_access.tpl", "資料庫寫入失敗.", "", "<a href='dis_list.php'>Back</a>");
				}
			}
		}
	}
	else if ( ( $submit == "停訂" || $submit == "StopSub" )&& sizeof($discuss_id)!= 0  ) {
		while(list($key,$value) = each($discuss_id)) {
			$sql = "delete from discuss_subscribe where user_id='$user_id' and discuss_id='".$value."'";
			mysql_db_query($DB.$course_id, $sql)  or die("資料庫查詢錯誤, $sql");
		}
	}
	else if( ( ( $submit == "輸出備份" ) || ( $submit == "Backup" ) ) && sizeof($discuss_id)!=0 ) {

		// 檢查目錄是否存在
		// 暫存目錄
		if( !is_dir( "../../$course_id/textbook/tmp" ) ) {
			mkdir("../../$course_id/textbook/tmp", 0751);
		}
		// 備份檔案存放目錄
		if( !is_dir( "../../$course_id/textbook/misc" ) ) {
			mkdir("../../$course_id/textbook/misc", 0751);
		}

		while( list($key,$value) = each( $discuss_id ) ) {

			// 找出討論區名稱
			$sql = "select discuss_name from discuss_info where a_id=$value";
			$result = mysql_db_query( $DB.$course_id, $sql ) or die("資料庫查詢錯誤, $sql");
			$row = mysql_fetch_array( $result );
			$disname = addslashes($row[0]);

			// 建立目錄 for 討論區
			$disdir = "../../$course_id/textbook/tmp/".$disname."_".$value;
			mkdir( $disdir, 0755 );

			// 找出討論串名稱與編號
			$sql = "select a_id,title from discuss_$value where parent=0";
			$result = mysql_db_query( $DB.$course_id, $sql ) or die("資料庫查詢錯誤, $sql");

			while ( $row = mysql_fetch_array( $result ) ) {
				$parentid = $row["a_id"];
				$ptitle = addslashes($row["title"]);

				// 建立目錄 for 討論串
				$listdir = $disdir."/".$ptitle."_".$parentid;
				mkdir( $listdir, 0755 );
				
				// 選出討論串的所有文章
				$sql = "select * from discuss_$value where parent=$parentid or a_id=$parentid order by a_id";
				$result2 = mysql_db_query( $DB.$course_id, $sql ) or die("資料庫查詢錯誤, $sql");
				
				while( $row2 = mysql_fetch_array($result2) ) {
					
					$a_id = $row2["a_id"];
					$title = addslashes( str_replace( ":",  " ", $row2["title"]) );
					$poster = addslashes($row2["poster"]);
					$created = $row2["created"];
					$body = addslashes( str_replace( "\n", "\r\n", $row2["body"]) );

					$fname = $listdir."/".$a_id."-".$title.".txt";
					$fp = fopen( $fname, "w" );

					$content =	"文章編號: $a_id\r\n".
								"主題: $title\r\n".
								"張貼者: $poster\t張貼日期: $created\r\n".
								"文章內容:\r\n$body";

					// 將文章輸出到檔案
					if( fwrite( $fp, $content ) == -1 ) {
						show_page("not_access.tpl", "Write error at $fname");
					}

				} // end $row2 while.
			} // end $row while.
		} // end list() while.

		exec( "cd ../../$course_id/textbook/tmp;tar -zcvf ../misc/backup.tar.gz *" );
		if( is_file("../../$course_id/textbook/misc/backup.tar.gz") ) {
			$errno = 5;
		}
		else {
			$errno = 6;
		}
		deldir( "../../$course_id/textbook/tmp" );
	}

	header("Location: dis_list.php?PHPSESSID=$PHPSESSID&errno=$errno");
?>
