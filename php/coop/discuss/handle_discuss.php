<?
	require 'fadmin.php';

	if( session_check_teach($PHPSESSID)==0 || check_group ( $course_id, $coopgroup, $coopcaseid ) == 0 || ( check_group ( $course_id, $coopgroup, $coopcaseid ) != 2 && ( $submit == "�R���Q�װ�" || $submit == "Delete group" )) ) {
		show_page("not_access.tpl", "�A�S���v�����榹�\��.<br>\nYou have no permission to perform this function.");
		exit();
	}

	mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("��Ʈw�s�����~");

	if (( $submit == "�R���Q�װ�" || $submit == "Delete group" )&& sizeof($discuss_id)!= 0 ) {
		while(list($key,$value) = each($discuss_id)) {
			// �N�Q�װϪ����table�R��
			$tablename = "discuss_".$coopcaseid."_".$value;
			$sql = "drop table $tablename;";
			mysql_db_query($DBC.$course_id, $sql) or die("��Ʈw�d�߿��~, $sql");
	
			//Autumn�ק諸����
			$sql = "delete from discuss_".$coopcaseid."_subscribe where discuss_id='".$value."'";
			mysql_DB_query($DBC.$course_id, $sql) or die("��Ʈw�d�߿��~, $sql");
			
			// �N�Q�װϪ���ƧR��
			$sql = "delete from discuss_".$coopcaseid."_info where a_id=".$value;
			mysql_DB_query($DBC.$course_id, $sql) or die("��Ʈw�d�߿��~, $sql");
	
			// �N�Q�װϪ��W���ɮקR��
			$dir = "../../../$course_id/coop/$coopcaseid/$coopgroup/".$value;
			deldir($dir);
		}
	}
	else if ( ($submit == "�q�\\\\" || $submit == "Subscribe")&& sizeof($discuss_id)!= 0  ) {
		while(list($key,$value) = each($discuss_id)) {
			$sql1 = "select * from discuss_".$coopcaseid."_subscribe where user_id='$user_id' and discuss_id = '".$value."'";
			if ( !($result = mysql_db_query( $DBC.$course_id, $sql1 ) ) ) {
				show_page("not_access.tpl", "��ƮwŪ������.", "", "<a href='dis_list.php'>Back</a>");
				exit;
			}
			else if ( mysql_num_rows($result) == 0 ) { 
				$sql = "insert into discuss_".$coopcaseid."_subscribe(user_id,discuss_id) values('$user_id',".$value.")";
				mysql_db_query($DBC.$course_id, $sql) or die("��Ʈw�d�߿��~, $sql");
				if( mysql_affected_rows() == 0 ) {
					show_page("not_access.tpl", "��Ʈw�g�J����.", "", "<a href='dis_list.php'>Back</a>");
				}
			}
		}
	}
	else if ( ( $submit == "���q" || $submit == "StopSub" )&& sizeof($discuss_id)!= 0  ) {
		while(list($key,$value) = each($discuss_id)) {
			$sql = "delete from discuss_".$coopcaseid."_subscribe where user_id='$user_id' and discuss_id='".$value."'";
			mysql_db_query($DBC.$course_id, $sql)  or die("��Ʈw�d�߿��~, $sql");
		}
	}
	else if( ( ( $submit == "��X�ƥ�" ) || ( $submit == "Backup" ) ) && sizeof($discuss_id)!=0 ) {

		// �ˬd�ؿ��O�_�s�b
		// �Ȧs�ؿ�
		if( !is_dir( "../../../$course_id/coop/$coopcaseid/$coopgroup/tmp" ) ) {
			mkdir("../../../$course_id/coop/$coopcaseid/$coopgroup/tmp", 0751);
		}
		// �ƥ��ɮצs��ؿ�
		if( !is_dir( "../../../$course_id/coop/$coopcaseid/$coopgroup/misc" ) ) {
			mkdir("../../../$course_id/coop/$coopcaseid/$coopgroup/misc", 0751);
		}

		while( list($key,$value) = each( $discuss_id ) ) {

			// ��X�Q�װϦW��
			$sql = "select discuss_name from discuss_".$coopcaseid."_info where a_id=$value";
			$result = mysql_db_query( $DBC.$course_id, $sql ) or die("��Ʈw�d�߿��~, $sql");
			$row = mysql_fetch_array( $result );
			$disname = $row[0];
			//$disname = addslashes($disname);
			$disname = str_replace ( "/", "��", $disname );
			$disname = str_replace ( "?", "�H", $disname );
			//$disname = str_replace ( "\\", "�@", $disname );
			$disname = str_replace ( "*", "��", $disname );
			$disname = str_replace ( ":", "�G", $disname );
			$disname = str_replace ( ">", "��", $disname );
			$disname = str_replace ( "<", "��", $disname );
			$disname = str_replace ( "\"", "��", $disname );
			$disname = str_replace ( "|", "�U", $disname );
			while ( strpos ( $disname, '\\' ) ) {
				$point = strpos ( $disname, '\\' );
				$disname = substr ( $disname, 0 , $point-1 )."�@".substr( $disname, $point+1, strlen($disname)-$point );
			}
//Autumn			$disname = $row[0];

			// �إߥؿ� for �Q�װ�
			$disdir = "../../../$course_id/coop/$coopcaseid/$coopgroup/tmp/".$disname."_".$value;
			mkdir( $disdir, 0755 );

			// ��X�Q�צ�W�ٻP�s��
			$sql = "select a_id,title from discuss_".$coopcaseid."_$value where parent=0";
			$result = mysql_db_query( $DBC.$course_id, $sql ) or die("��Ʈw�d�߿��~, $sql");

			while ( $row = mysql_fetch_array( $result ) ) {
				$parentid = $row["a_id"];
				$ptitle = $row["title"];
				//$ptitle = addslashes($ptitle);
				$ptitle = str_replace ( "/", "��", $ptitle );
				$ptitle = str_replace ( "?", "�H", $ptitle );
				//$ptitle = str_replace ( "\\", "�@", $ptitle );
				$ptitle = str_replace ( "*", "��", $ptitle );
				$ptitle = str_replace ( ":", "�G", $ptitle );
				$ptitle = str_replace ( ">", "��", $ptitle );
				$ptitle = str_replace ( "<", "��", $ptitle );
				$ptitle = str_replace ( "\"", "��", $ptitle );
				$ptitle = str_replace ( "|", "�U", $ptitle );
				while ( strpos ( $ptitle, '\\' ) ) {
					$point = strpos ( $ptitle, '\\' );
					$ptitle = substr ( $ptitle, 0 , $point-1 )."�@".substr( $ptitle, $point+1, strlen($ptitle)-$point );
				}
//Autumn				$ptitle = $row["title"];

				// �إߥؿ� for �Q�צ�
				$listdir = $disdir."/".$ptitle."_".$parentid;

				mkdir( $listdir, 0755 );
				
				// ��X�Q�צꪺ�Ҧ��峹
				$sql = "select * from discuss_".$coopcaseid."_$value where parent=$parentid or a_id=$parentid order by a_id";
				$result2 = mysql_db_query( $DBC.$course_id, $sql ) or die("��Ʈw�d�߿��~, $sql");
				
				while( $row2 = mysql_fetch_array($result2) ) {
					
					$a_id = $row2["a_id"];
					$title = $row2["title"];
					//$title = addslashes( $title );
					$title = str_replace ( "/", "��", $title );
					$title = str_replace ( "?", "�H", $title );
					//$title = str_replace ( "\\", "!", $title );
					$title = str_replace ( "*", "��", $title );
					$title = str_replace ( ":", "�G", $title );
					$title = str_replace ( ">", "��", $title );
					$title = str_replace ( "<", "��", $title );
					$title = str_replace ( "\"", "��", $title );
					$title = str_replace ( "|", "�U", $title );
					while ( strpos ( $title, '\\' ) ) {
						$point = strpos ( $title, '\\' );
						$title = substr ( $title, 0 , $point-1 )."�@".substr( $title, $point+1, strlen($title)-$point );
					}
//Autumn					$title = str_replace( ":",  " ", $row2["title"]);
					$poster = addslashes($row2["poster"]);
					$created = $row2["created"];
					$body = str_replace( "\n", "\r\n", $row2["body"]);
//Autumn					$body = addslashes( $body );

					$fname = $listdir."/".$a_id."-".$title.".txt";
					$fp = fopen( $fname, "w" );
					$title2 = $row2["title"];
					$content =	"�峹�s��: $a_id\r\n".
								"�D�D: $title2\r\n".
								"�i�K��: $poster\t�i�K���: $created\r\n".
								"�峹���e:\r\n$body";

					// �N�峹��X���ɮ�
					if( fwrite( $fp, $content ) == -1 ) {
						show_page("not_access.tpl", "Write error at $fname");
					}

				} // end $row2 while.
			} // end $row while.
		} // end list() while.

		exec( "cd ../../../$course_id/coop/$coopcaseid/$coopgroup/tmp;tar -zcvf ../misc/backup.tar.gz *" );
		if( is_file("../../../$course_id/coop/$coopcaseid/$coopgroup/misc/backup.tar.gz") ) {
			$errno = 5;
		}
		else {
			$errno = 6;
		}
		deldir( "../../../$course_id/coop/$coopcaseid/$coopgroup/tmp" );
	}

	header("Location: dis_list.php?PHPSESSID=$PHPSESSID&errno=$errno");
?>