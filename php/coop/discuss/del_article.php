<?
//   param  : $discuss_id (article_list.php / show_article.php)
//            $course_id  (session)
//            $del_id (article_list.php / show_article.php; is an array)
//            all required.

	session_id($PHPSESSID);
	session_start();

	require 'fadmin.php';

	mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("��Ʈw�s�����~");

   	$tablename = "discuss_".$coopcaseid."_".$discuss_id;

	if(is_array($del_id)) {
		$del_id = implode(" ",$del_id);
		$del_id = explode(" ",$del_id);
	}

	if( session_check_teach($PHPSESSID)==1 ) {
		$sql = "select poster,created,replied,parent,TO_DAYS(created),TO_DAYS(NOW()) from $tablename where a_id=".$del_id[0];
		$result = mysql_db_query($DBC.$course_id, $sql)  or die("��Ʈw�d�߿��~, $sql");
		if( $row = mysql_fetch_array($result) ) {
			if( strcmp($user_id,$row[0])!=0 ) {
				show_page("not_access.tpl", "�u����o��̤~�i�R���峹.\n<br>Only the original author can delete the article.");
				exit();
			}

			if( ( $row[3]==0 ) && ( strcmp($row[1],$row[2]) != 0 ) ) {
				show_page("not_access.tpl", "���峹�L�k�Q�R��, �]���D�D�w���^�Ф峹.\n<br>This article cannot be deleted because it has replies with it.");
				exit();
			}

			if( $row[4] != $row[5] ) {
				show_page("not_access.tpl", "���峹�L�k�Q�R��, �]���D�D�w�W�L�R������.(�@��)\n<br>This article cannot be deleted because it has already expired.(one day)");
				exit();
			}

		}
		else {
			show_page("not_access.tpl", "���R�����峹���s�b.\n<br>The article to delete does not exist.");
			exit();
		}
	}


	for($i=0;$i<sizeof($del_id);$i++) {
		$article_id = $del_id[$i];
		// ��X�����峹�s���H�ˬd�O�_������, �����ܭn�R��
		$sql = "select * from $tablename where (a_id=$article_id or parent=$article_id)";
		$result = mysql_db_query($DBC.$course_id, $sql) or die("��Ʈw�d�߿��~, $sql");

		if ( mysql_num_rows($result) > 0 ) {
			while($row = mysql_fetch_array($result)) {
				if ( $row["parent"] != NULL )
					$parent = $row["parent"];
				if ( $row["type"] != NULL ) {
					if($row["type"] != "/") {
						unlink("../../../$course_id/coop/$coopcaseid/$coopgroup/$discuss_id/".$row["a_id"].".".$row["type"]);
					}
					else {
						unlink("../../../$course_id/coop/$coopcaseid/$coopgroup/$discuss_id/".$row["a_id"]);
					}
				}
			}
			// ��ڧR���峹.
			$sql = "delete from $tablename where a_id=$article_id or parent=$article_id";
			mysql_db_query($DBC.$course_id, $sql) or die("��Ʈw�d�߿��~, $sql");
		}


		//Change replied time.
		if ( $parent != 0 ) {
			$sql1 = "select created from $tablename where parent='$parent' order by a_id DESC";
			$result1 = mysql_db_query($DBC.$course_id, $sql1)  or die("��Ʈw�d�߿��~, $sql1");

			if ( mysql_num_rows ( $result1 ) != 0 ) {
				$row1 = mysql_fetch_array( $result1 );
				$sql2 = "update $tablename set replied = '".$row1['created']."' where a_id='$parent'";
				mysql_db_query($DBC.$course_id, $sql2) or die("��Ʈw�d�߿��~, $sql2");
			}
			else {
				$sql1 = "select created from $tablename where a_id='$parent'";
				$result1 = mysql_db_query($DBC.$course_id, $sql1) or die("��Ʈw�d�߿��~, $sql1");
				if ( mysql_num_rows ( $result1 ) != 0 ) {
					$sql2 = "update $tablename set replied = created where a_id='$parent'";
					mysql_db_query($DBC.$course_id, $sql2) or die("��Ʈw�d�߿��~, $sql2");
				}
			}
		}
	}
	// end FOR Loop.

	header("Location: article_list.php?discuss_id=$discuss_id&PHPSESSID=$PHPSESSID");
?>