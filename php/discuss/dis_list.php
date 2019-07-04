<?
// param: $course_id (session)
//        $errno     ()
// Last Update: 2002/03/01 by kof9x. Add error message about discuss group backup.
// last Update: 2002/07/30 by Autumn. �W�[���պ޲z�\��

	require 'fadmin.php';
	include("class.FastTemplate.php3");
	update_status ("�Q�װϤ@����");
	session_id($PHPSESSID);
	session_start();

	if( $version == "C" ) {
		$error_msg[0] = "�s�Q�װϫإ� <font color='blue'>���\</font> ";
		$error_msg[1] = "�s�Q�װϫإ� <font color='blue'>����</font>";
		$error_msg[2] = "�Q�װϧR�� <font color='blue'>���\</font> ";
		$error_msg[3] = "�Q�װϸ�Ƨ�s <font color='blue'>���\</font> ";
		$error_msg[4] = "�Q�װϸ�Ƨ�s <font color='blue'>����</font> ";
		$error_msg[5] = "�Q�װϸ�Ƴƥ� <font color='blue'>���\</font><br>\n".
						"<a href='../../$course_id/textbook/misc/backup.tar.gz'>�I�惡�s���i�U���ƥ�</a>";
		$error_msg[6] = "�Q�װϸ�Ƴƥ� <font color='blue'>����</font> ";
	}
	else {
		$error_msg[0] = "New Discuss Group Created.";
		$error_msg[1] = "New Discuss Group Creation <font color='blue'>Failed</font>";
		$error_msg[2] = "Discuss Group Deleted.";
		$error_msg[3] = "Discuss Group information Updated.";
		$error_msg[4] = "Discuss Group information Update <font color='blue'>Failed</font>";	
		$error_msg[5] = "Discuss Group Data Dump succeed.\n".
						"<a href='../../$course_id/textbook/misc/backup.tar.gz'>Click here to download</a>";
		$error_msg[6] = "Discuss Group Data Dump <font color='blue'>Failed</font> ";
	}


	$tpl = new FastTemplate("./templates");



	//linsy
	if($_GET['sort'] == "")
		$sort = "DESC";
	elseif($sort == "ASC")
		$sort = "DESC";
	elseif($sort == "DESC")
		$sort = "ASC";
	$tpl->assign("SORT", $sort);
	$field = $_GET['field'];
	$tpl->assign("FIELD", $field);



	// ���^�� + �Ѯv/�ǥͧP�_
	if($version == 'C') {
		if(session_check_teach($PHPSESSID) >= 2 ) {
			$tpl->define(array(main => "dis_list_tch.tpl"));
		}
		else {
			$tpl->define(array(main => "dis_list_stu.tpl"));		 
		}
	}
	else {
		if(session_check_teach($PHPSESSID) >= 2 ) {	   
			$tpl->define(array(main => "dis_list_tch_E.tpl"));   
		}
		else {
			$tpl->define(array(main => "dis_list_stu_E.tpl"));
		}
	}

	$tpl->define_dynamic("discuss_list","main");
	$tpl->assign("SKINNUM", $skinnum);
	$tpl->assign("TITLE", "�Q�װϤ@��");

	if(isset($errno)) 
		$tpl->assign("ERROR_MSG", $error_msg[$errno]);
	else
		$tpl->assign("ERROR_MSG", "");


	$tpl->assign("PHP_SESS", $PHPSESSID);

	// �Ѹ�Ʈw��Ū�X�ݩ󦹬�ت��Q�װ�, �ÿ�X
	mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("��Ʈw�s�����~");

	//�ϥΪ̪�a_id
	$sql = "select a_id from user where id = '$user_id'";
	$result = mysql_db_query($DB, $sql) or die("��Ʈw�d�߿��~, $sql");
	$row = mysql_fetch_array($result);
	$a_id = $row['a_id'];
	
	// �d�߸�Ʈw
	//$sql = "select * from discuss_info order by group_num,a_id";
	//linsy@20140313, ���ϥΪ̥i�H�ھڰQ�װϼ��D�Ƨ�
	if($field != "")
		$sql = "select * from discuss_info order by $field $sort" . ",group_num,a_id";
	else
		$sql = "select * from discuss_info order by group_num,a_id";

	$result = mysql_db_query($DB.$course_id, $sql) or die("��Ʈw�d�߿��~, $sql");

	if(mysql_num_rows($result) > 0) {    // �ˬd�O�_���Q�װϦs�b
		$counter = 0;

		// ��X�Q�װϤ@��.
		while($row = mysql_fetch_array($result)) {
			// �P�_�Ѯv/�ǥ�, �p�G�ϥΪ̬��ǥ�, �ݧP�_���հQ�װϬO�_���}, �H�Φ��ǥͬO�_�ݩ󦹲ղխ�.
			if( session_check_teach($PHPSESSID) < 2 ) {
				//�ǥͪ�
				if ( $row['access'] != "0" ) {
					$sql = "select * from discuss_group_map where discuss_id='".$row['a_id']."' and student_id ='$a_id'";
					$result2 = mysql_db_query($DB.$course_id, $sql) or die("��Ʈw�d�߿��~, $sql");
					if(mysql_num_rows($result2) == 0){
						continue;
					}
				}
			}
			$tpl->assign("DIS_ID", $row["a_id"]);
			$tpl->assign("DEL_NAME", "discuss_id[$counter]");
		  
			$tpl->assign("DIS_NAME", $row["discuss_name"]);
			$tpl->assign("DIS_COMMENT", $row["comment"]);

			// �P�_���Q�װϹ�ثe�ϥΪ̪��q�\���p.
			$sql2 = "select * from discuss_subscribe where user_id='$user_id' and discuss_id=".$row["a_id"];
			$result2 = mysql_db_query($DB.$course_id, $sql2)  or die("��Ʈw�d�߿��~, $sql2");
			if(mysql_num_rows($result2) > 0) {
				if($version == 'C') {
					$tpl->assign("SUB_STATUS", "<font color='red'>�w�q</font>");
				}
				else {
					$tpl->assign("SUB_STATUS", "<font color='red'>Subscribed</font>");
				}
			}
			else {
				if($version == 'C') {
					$tpl->assign("SUB_STATUS", '���q');
				}
				else {
					$tpl->assign("SUB_STATUS", "Not Subscribed");
				}
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

			$tpl->assign("ART_LIST", "article_list.php?discuss_id=".$row["a_id"]."&group_num=".$row["group_num"]."&log=1&PHPSESSID=".$PHPSESSID);

			/* �����Q�װϪ�log.
			if((session_check_teach($PHPSESSID) != 0) && (strcmp($guest,"1") != 0) && (session_check_teach($PHPSESSID) != 3))
				$tpl->assign("LOG_PRG", "onClick=\"window.open('../log.php?event_id=5&PHPSESSID=$PHPSESSID','log5','width=1,height=1');\"");
			else
			*/
			$tpl->assign("LOG_PRG", "");

			// �C�ⱱ��.
			if($counter%2 == 0) 
				$tpl->assign("DISCOLOR", "#ffffff");
			else
				$tpl->assign("DISCOLOR", "#edf3fa");

			$tpl->parse(ROWL, ".discuss_list");
			$counter++;
		}
	}
	if ($counter == 0 ) {
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
   

	$tpl->parse(BODY, "main");

	$tpl->FastPrint(BODY);
?>
