<?
// �Q�װϭq�\�{��
// param           : action       (SELF, decides go to part 1 or 2)
//       (at part1): nothing.
//                   all param is same as dis_list.php
//       (ar part2): discuss_id[] (SELF , �n�q�\���Q�װϽs��)
//                   user_id      (session)
//                   course_id    (session)

	require 'fadmin.php';

	session_id($PHPSESSID);
	session_start();

	if ( $guest == "1" ) {
			show_page( "not_access.tpl" ,"�藍�_�I�z�S���v���ϥΦ��\��I�I\n<br>Sorry, guest user cannot use this function.");
			exit;
	}
	
	mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("��Ʈw�s�����~");

	if(empty($action)) {
		// ��X���ϥΪ̿�ܪ��e��
		// �򥻤W�Mdis_list.php�̪��O�@�˪�, ���F�Y�ǭ�]�~�t�~�g�@��...

		// �p�G�ϥΪ̥��n��e-mail, �h�n�D�ϥΪ̥��n��.

		$sql = "select email from user where id='$user_id'";
		$result = mysql_db_query($DB, $sql) or die("��Ʈw�d�߿��~, $sql");
		if(mysql_num_rows($result) > 0) {
			$row = mysql_fetch_array($result);
			if(strlen($row["email"]) == 0) {
				show_page("not_access.tpl", "�z�|����t�Τ���Je-mail���.\n<br>You have not entered your e-mail account at our system.");
				exit();
			}
		}

		// �P�_�Ѯv/�ǥ�, �p�G�ϥΪ̬��ǥ�, �ݧP�_���հQ�װϬO�_���}, �H�Φ��ǥͬO�_�ݩ󦹲ղխ�.
		if(session_check_teach($PHPSESSID)==2) {
			// �Ѯv��   �����Q�װϳ��i�H��.
			$sql = "select * from discuss_info order by group_num,a_id";
		}
		else {
			// �ǥͪ�
			// ���qdiscuss_group �d�X���ϥΪ̪��էO.
			$sql = "select group_num from discuss_group where student_id='$user_id'";
			$result = mysql_db_query($DB.$course_id, $sql) or die("��Ʈw�d�߿��~, $sql");

			// �ҥ~�B�z. �B�z�Ѯv�|�����ժ����~���p
			if(mysql_num_rows($result) > 0)  {
				$row = mysql_fetch_array($result);
				$grp_num = $row["group_num"];
				$sql = "select * from discuss_info where access=0 or group_num=$grp_num order by group_num,a_id";
			}
			else {
				$sql = "select * from discuss_info where access=0 order by group_num,a_id";
			}
		}

		// �d�߸�Ʈw
		$result = mysql_db_query($DB.$course_id, $sql) or die("��Ʈw�d�߿��~, $sql");

		if(mysql_num_rows($result) > 0) {    // �ˬd�O�_���Q�װϦs�b
			include("class.FastTemplate.php3");
			$tpl = new FastTemplate("./templates");
			if($version == 'C') {
				$tpl->define(array(main => "subscribe.tpl"));
			}
			else {
				$tpl->define(array(main => "subscribe_E.tpl"));
			}
			$tpl->define_dynamic("discuss_list","main");
			// �ΨӰ��ܼƪ�...
			$counter = 0;

			// ��X�Q�װϤ@��.
			while($row = mysql_fetch_array($result)) {
				$tpl->assign("DIS_ID", $row["a_id"]);		  
				$tpl->assign("DIS_NAME", $row["discuss_name"]);
				$tpl->assign("DIS_COMMENT", $row["comment"]);

				$tpl->assign("SUB_NAMEA", "subscribed[$counter]");
				$tpl->assign("SUB_NAMEB", "discuss_id[$counter]");
				$tpl->assign("SUB_NAMEC", "status[$counter]");

				// �P�_���Q�װϹ�ثe�ϥΪ̪��q�\���p. not implemented at 7/26
				$sql2 = "select * from discuss_subscribe where user_id='$user_id' and discuss_id=".$row["a_id"];
				$result2 = mysql_db_query($DB.$course_id, $sql2) or die("��Ʈw�d�߿��~, $sql2");
				if(mysql_num_rows($result2) > 0) {
					$tpl->assign("SUB_CHECKED", "checked");
					$tpl->assign("SUB_STAT", "1");
				}
				else {
					$tpl->assign("SUB_CHECKED", "");
					$tpl->assign("SUB_STAT", "0");
				}

				// �P�_�O�_�����հQ�װ�.
				if($row["group_num"] == 0) {
					if($version == "C") {
						$tpl->assign("DIS_TYPE", "�@��Q�װ�");
					}
					else {
						$tpl->assign("DIS_TYPE", "Normal");			     
					}
				}
				else {
					if($version == "C") {
						$tpl->assign("DIS_TYPE", "��".$row["group_num"]."�p�հQ�װ�");
					}
					else {
						$tpl->assign("DIS_TYPE", "Team ".$row["group_num"]." discussion group");			  
					}
				}

				// �C�ⱱ��.
				if($counter%2 == 0) 
					$tpl->assign("SUBCOLOR", "#ffffff");
				else
					$tpl->assign("SUBCOLOR", "#edf3fa");

				$tpl->parse(ROWL, ".discuss_list");
				$counter++;
			}
			$tpl->assign("PHP_ID", $PHPSESSID);
			$tpl->parse(BODY, "main");

			$tpl->FastPrint(BODY);
		}
		else {
			show_page("not_access.tpl", "�ثe�S������Q�װϦs�b.<br>\nNO discussion group exists now.", "", "<a href='dis_list.php'>Back</a>");
		}
	}
	else {
		// ��ڳB�z�g�J��Ʈw������.

		// ���ͱ��ϥΪ�sql�y�k. (insert/delete)
		for($i=0;$i<sizeof($status);$i++) {
			if( ($status[$i] == 1) && ($subscribed[$i] == 1) ) {
				// �w�q�\�����q
			}
			else if( ($status[$i] == 0) && ($subscribed[$i] == 1) ) {
				// �s�q�\���Q�װ�.
				$sql = "insert into discuss_subscribe(user_id,discuss_id) values('$user_id',".$discuss_id[$i].")";

				mysql_db_query($DB.$course_id, $sql) or die("��Ʈw�d�߿��~, $sql");
				if( mysql_affected_rows() == 0 ) {
					show_page("not_access.tpl", "��Ʈw�g�J����.", "", "<a href='dis_list.php'>Back</a>");
				}
			}
			else if( ($status[$i] == 1) && ($subscribed[$i] == 0) ) {
				// �h�q�Q�װ�.
				$sql = "delete from discuss_subscribe where user_id='$user_id' and discuss_id=".$discuss_id[$i];
				mysql_db_query($DB.$course_id, $sql)  or die("��Ʈw�d�߿��~, $sql");
				if( mysql_affected_rows() == 0 ) {
					show_page("not_access.tpl", "��Ʈw��s����.", "", "<a href='dis_list.php'>Back</a>");
				}
			}
			else {
				// �쥻���q�\, �P�ɵL�N�q�\���Q�װ�
			}
		}
		
		header("Location: dis_list.php?PHPSESSID=$PHPSESSID");
	}
?>