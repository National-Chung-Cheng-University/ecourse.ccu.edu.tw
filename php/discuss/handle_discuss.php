<?
   // param:   $course_id  (session)
   //          $discuss_id   (form in dis_list.php, array)
   // Last update: 2002/02/27
   // last Update: 2002/07/30 by Autumn. �W�[���պ޲z�\��

	require 'fadmin.php';

	if($check = session_check_teach($PHPSESSID)!=2 && ( $submit == "�R���Q�װ�" || $submit == "Delete group" )) {
		show_page("not_access.tpl", "�A�S���v�����榹�\��.<br>\nYou have no permission to perform this function.");
		exit();
	}

	mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("��Ʈw�s�����~");

	if (( $submit == "�R���Q�װ�" || $submit == "Delete group" )&& sizeof($discuss_id)!= 0 ) {
		while(list($key,$value) = each($discuss_id)) {
			// �N�Q�װϪ����table�R��
			$tablename = "discuss_".$value;
			$sql = "drop table $tablename;";
			mysql_db_query($DB.$course_id, $sql) or die("��Ʈw�d�߿��~, $sql");
	
			//Autumn�ק諸����
			$sql = "delete from discuss_subscribe where discuss_id='".$value."'";
			mysql_DB_query($DB.$course_id, $sql) or die("��Ʈw�d�߿��~, $sql");
			
			//�R��discuss_group_map
			$sql = "delete from discuss_group_map where discuss_id='".$value."'";
			mysql_DB_query($DB.$course_id, $sql) or die("��Ʈw�d�߿��~, $sql");
	
			// �N�Q�װϪ���ƧR��
			$sql = "delete from discuss_info where a_id=".$value;
			mysql_DB_query($DB.$course_id, $sql) or die("��Ʈw�d�߿��~, $sql");
	
			// �N�Q�װϪ��W���ɮקR��
			$dir = "../../$course_id/board/".$value;
			deldir($dir);

			// �N�vŪ���O����R��
			$Q2 = "alter table user_profile DROP discuss_".$value;
			if (!mysql_db_query($DB.$course_id,$Q2))
				die("��Ʈw�d�߿��~, $Q2");

		}
	}
	else if ( ($submit == "�q�\\\\" || $submit == "Subscribe")&& sizeof($discuss_id)!= 0  ) {
		while(list($key,$value) = each($discuss_id)) {
			$sql1 = "select * from discuss_subscribe where user_id='$user_id' and discuss_id = '".$value."'";
			if ( !($result = mysql_db_query( $DB.$course_id, $sql1 ) ) ) {
				show_page("not_access.tpl", "��ƮwŪ������.", "", "<a href='dis_list.php'>Back</a>");
				exit;
			}
			else if ( mysql_num_rows($result) == 0 ) { 
				$sql = "insert into discuss_subscribe(user_id,discuss_id) values('$user_id',".$value.")";
				mysql_db_query($DB.$course_id, $sql) or die("��Ʈw�d�߿��~, $sql");
				if( mysql_affected_rows() == 0 ) {
					show_page("not_access.tpl", "��Ʈw�g�J����.", "", "<a href='dis_list.php'>Back</a>");
				}
			}
		}
	}
	else if ( ( $submit == "���q" || $submit == "StopSub" )&& sizeof($discuss_id)!= 0  ) {
		while(list($key,$value) = each($discuss_id)) {
			$sql = "delete from discuss_subscribe where user_id='$user_id' and discuss_id='".$value."'";
			mysql_db_query($DB.$course_id, $sql)  or die("��Ʈw�d�߿��~, $sql");
		}
	}
	else if( ( ( $submit == "��X�ƥ�" ) || ( $submit == "Backup" ) ) && sizeof($discuss_id)!=0 ) {

		// �ˬd�ؿ��O�_�s�b
		// �Ȧs�ؿ�
		if( !is_dir( "../../$course_id/textbook/tmp" ) ) {
			mkdir("../../$course_id/textbook/tmp", 0751);
		}
		// �ƥ��ɮצs��ؿ�
		if( !is_dir( "../../$course_id/textbook/misc" ) ) {
			mkdir("../../$course_id/textbook/misc", 0751);
		}

		while( list($key,$value) = each( $discuss_id ) ) {

			// ��X�Q�װϦW��
			$sql = "select discuss_name from discuss_info where a_id=$value";
			$result = mysql_db_query( $DB.$course_id, $sql ) or die("��Ʈw�d�߿��~, $sql");
			$row = mysql_fetch_array( $result );
			$disname = addslashes($row[0]);

			// �إߥؿ� for �Q�װ�
			$disdir = "../../$course_id/textbook/tmp/".$disname."_".$value;
			mkdir( $disdir, 0755 );

			// ��X�Q�צ�W�ٻP�s��
			$sql = "select a_id,title from discuss_$value where parent=0";
			$result = mysql_db_query( $DB.$course_id, $sql ) or die("��Ʈw�d�߿��~, $sql");

			while ( $row = mysql_fetch_array( $result ) ) {
				$parentid = $row["a_id"];
				$ptitle = addslashes($row["title"]);

				// �إߥؿ� for �Q�צ�
				$listdir = $disdir."/".$ptitle."_".$parentid;
				mkdir( $listdir, 0755 );
				
				// ��X�Q�צꪺ�Ҧ��峹
				$sql = "select * from discuss_$value where parent=$parentid or a_id=$parentid order by a_id";
				$result2 = mysql_db_query( $DB.$course_id, $sql ) or die("��Ʈw�d�߿��~, $sql");
				
				while( $row2 = mysql_fetch_array($result2) ) {
					
					$a_id = $row2["a_id"];
					$title = addslashes( str_replace( ":",  " ", $row2["title"]) );
					$poster = addslashes($row2["poster"]);
					$created = $row2["created"];
					$body = addslashes( str_replace( "\n", "\r\n", $row2["body"]) );

					$fname = $listdir."/".$a_id."-".$title.".txt";
					$fp = fopen( $fname, "w" );

					$content =	"�峹�s��: $a_id\r\n".
								"�D�D: $title\r\n".
								"�i�K��: $poster\t�i�K���: $created\r\n".
								"�峹���e:\r\n$body";

					// �N�峹��X���ɮ�
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
