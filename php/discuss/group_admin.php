<?
	// param : $course_id (session)
	//         $group_num (SELF)
	//         $student_id (SELF)
	//         $exists[$i] (SELF, �ΨӧP�_�O�nupdate or insert)
	// last Update: 2002/07/30 by Autumn. �W�[���պ޲z�\��

	session_id($PHPSESSID);
    session_start();

	require 'fadmin.php';

    // �ˬd�ϥ��v��.
    if(session_check_teach($PHPSESSID) != 2) {
		show_page("not_access.tpl", "�A�S���v�����榹�\��.\n<br>You have no permission to perform this function.");
		exit();
    }

	function GetUserName($user_id) {

		global $DB;

		$sql = "select name,nickname from user where id='$user_id'";
		$result = mysql_db_query($DB, $sql) or die("��Ʈw�d�߿��~, $sql");

		// check name field. if exists, use it as poster name.
		if(mysql_num_rows($result) > 0) {
			$row = mysql_fetch_array($result);
			//if( strcmp($row["nickname"], "" )!=0) {
			//	$poster = $row["nickname"];
			//}
			if(strcmp($row["name"], "" ) !=0 ) {
				$poster = $row["name"];
			}
			else {
				$poster = "";
			}
		}
		else {
			// Default.
			$poster = "";
		}

		return $poster;
	}

    mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("��Ʈw�s�����~");

	if(isset($group_num) && $action == "update") {   // �s�W/�ק�discuss_group�������
		for($i=0;$i<sizeof($group_num);$i++) {
			if($exists[$i] == 1) {
				$sql = "update discuss_group set group_num=".$group_num[$i]." where student_id='".$student_id[$i]."'";
			}
			else {
				$sql = "insert discuss_group(group_num,student_id) values(".$group_num[$i].",'".$student_id[$i]."')";
		     }
			 mysql_db_query($DB.$course_id, $sql) or die("��Ʈw�d�߿��~, $sql");
		}
		
		  header("Location:$PHP_SELF?PHPSESSID=$PHPSESSID");
    }
    else if ( $action == "set" ) {
	    	while(list($key,$value) = each($discuss_id)) {
	    		$Q1 = "delete from discuss_group_map where discuss_id = '$value'";
	    		$Q2 = "select group_num from discuss_info where a_id='$value'";

			if( !($result1 = mysql_db_query($DB.$course_id, $Q1)) ) {
			 	echo ("��Ʈw�R�����~, $Q1");	
			}
			else if( !($result2 = mysql_db_query($DB.$course_id, $Q2)) ) {
			 	echo ("��Ʈw�d�߿��~, $Q2");	
			}
			else {
				$row2 = mysql_fetch_array ( $result2 );
				$Q3 = "select student_id from discuss_group where group_num = '".$row2["group_num"]."'";
			}
			if( !($result3 = mysql_db_query($DB.$course_id, $Q3)) ) {
			 	echo ("��Ʈw�R�����~, $Q3");	
			}
			while ( $row3 = mysql_fetch_array($result3) ) {
				$Q4 = "select a_id from user where id ='".$row3["student_id"]."'";

				if( !($result4 = mysql_db_query($DB, $Q4)) ) {
					echo ("��Ʈw�d�߿��~, $Q4");
				}
				$row4 = mysql_fetch_array($result4);
				$student_id = $row4['a_id'];
				//�s�W��discuss_group_map
				$Q5 = "insert into discuss_group_map ( discuss_id,student_id ) values ('$value','$student_id')";

				if( !($result5 = mysql_db_query($DB.$course_id, $Q5)) ) {
					echo ("��Ʈw�g�J���~, $Q5");
				}
			}
	    	}
   		header("Location:$PHP_SELF?PHPSESSID=$PHPSESSID");
	}
	else {                    // ��ܿ�J�e��
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate("./templates");

		if($version == "C") {
			$tpl->define(array(main => "group_admin.tpl"));
		}
		else {
			$tpl->define(array(main => "group_admin_E.tpl"));
		}

		$tpl->define_dynamic("user_list","main");
		$tpl->define_dynamic("discuss_list","main");
		
		$tpl->assign("SKINNUM", $skinnum);
		$tpl->assign("TITLE", "�p�ղխ��޲z");
		$tpl->assign("GRP_ADM" ,"group_admin.php");
    
		// �����o�צ����Ҫ��ǥ;Ǹ��@����
		$sql = "select user.id from user,take_course where take_course.course_id=$course_id and take_course.year='$course_year' and take_course.term='$course_term' and user.a_id=take_course.student_id and take_course.credit='1' order by user.id";

		$result = mysql_db_query($DB, $sql)  or die("��Ʈw�d�߿��~, $sql");

		$counter=0;

		while($row = mysql_fetch_array($result)) {
			$tpl->assign("STU_ID", $row["id"]);
			$tpl->assign("STU_NAME", GetUserName($row["id"]));
			$tpl->assign("GRP_INPUT", "group_num[$counter]");
			$tpl->assign("SID_INPUT", "student_id[$counter]");
			$tpl->assign("SID_STAT", "exists[$counter]");

			// ���o�ثe�����ժ��p
			$sql2 = "select group_num from discuss_group where student_id='".$row["id"]."'";
			$result2 = mysql_db_query($DB.$course_id, $sql2) or die("��Ʈw�d�߿��~, $sql2");

			if(mysql_num_rows($result2) > 0) {
				$row2 = mysql_fetch_array($result2);
				if($row2["group_num"] > 0 || $row2["group_num"] == -1) {   // ��Ƥw�g�s�b
					$tpl->assign("GRP_NUM", $row2["group_num"]);
					$tpl->assign("STATUS", "1");
				}
			}
			else {         // ��Ʃ|���s�b
				$tpl->assign("GRP_NUM", "-1");
				$tpl->assign("STATUS", "0");
			}
	
			// �C�ⱱ��.
			if($counter%2 == 0) 
				$tpl->assign("GRCOLOR", "#ffffff");
			else
				$tpl->assign("GRCOLOR", "#edf3fa");

			$tpl->parse(ROWU, ".user_list");
			$counter++;
		}

		// �d�߸�Ʈw
		$sql = "select * from discuss_info order by group_num,a_id";
		$result = mysql_db_query($DB.$course_id, $sql) or die("��Ʈw�d�߿��~, $sql");
	
		if(mysql_num_rows($result) > 0) {    // �ˬd�O�_���Q�װϦs�b
			$counter2 = 0;
	
			// ��X�Q�װϤ@��.
			while($row = mysql_fetch_array($result)) {
				$tpl->assign("DIS_ID", $row["a_id"]);
				$tpl->assign("DEL_NAME", "discuss_id[$counter2]");
			  
				$tpl->assign("DIS_NAME", $row["discuss_name"]);
				$tpl->assign("DIS_COMMENT", $row["comment"]);
	
				// �P�_�O�_�����հQ�װ�.
				if($row["group_num"] == 0) {
					continue;
				}
				else {
					if($version == "C") {
						$tpl->assign("DIS_TYPE", "��".$row["group_num"]."�p�հQ�װ�");
					}
					else {
						$tpl->assign("DIS_TYPE", "Team ".$row["group_num"]." discussion group");			  
					}
				}

				$tpl->assign("LOG_PRG", "");
	
				// �C�ⱱ��.
				if($counter2%2 == 0) 
					$tpl->assign("DISCOLOR", "#ffffff");
				else
					$tpl->assign("DISCOLOR", "#edf3fa");
	
				$tpl->parse(ROWL, ".discuss_list");
				$counter2++;
			}
		}
		if ($counter2 == 0 ) {
			$tpl->assign("DIS_ID", "");
			$tpl->assign("DEL_NAME", "");
			$tpl->assign("DIS_TYPE", "");
			$tpl->assign("ART_LIST", "");
			$tpl->assign("DIS_NAME", "");
			$tpl->assign("DIS_COMMENT", "");
			$tpl->assign("LOG_PRG", "");
			$tpl->assign("SUB_STATUS", "");
	
			$tpl->parse(ROWL, ".discuss_list");
		}
		
		$tpl->assign("PHP_ID", $PHPSESSID);
		$tpl->parse(BODY, "main");
		$tpl->FastPrint(BODY);
	} // end if [��ܿ�J�e��].
?>