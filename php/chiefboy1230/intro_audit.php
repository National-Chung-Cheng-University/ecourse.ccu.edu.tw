<?php
	require_once('fadmin.php');
	
	if (!($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)))
	{
		echo "��Ʈw�s�����~!!";
		exit;
	}

	if (!(isset($PHPSESSID) && ($check = session_check_teach($PHPSESSID))))
	{
		show_page( "not_access.tpl" ,"�v�����~�A�A��IP�w�g�Q�O���U�ӡA�ФūD�k�i�J");
	}
	
	if($_GET[course_no])
	{
		if ($_GET[course_no] == 'All')
		{
			$Q1 = "SELECT u.id, u.name user_name, l.tag1 ip, l.tag2 course_no, c.name course_name, l.mtime
				FROM `log` as l, `user` as u, `course` as c
				WHERE l.`event_id` =26 
				AND l.user_id = u.a_id 
				AND l.tag2 = c.course_no 
				GROUP BY u.id 
				ORDER BY l.mtime DESC 
				";
			$Q2 = "SELECT u.id, u.name user_name, l.tag1 ip, l.tag2 course_no, c.name course_name, l.mtime
				FROM `log` as l, `user` as u, `course` as c
				WHERE l.`event_id` =27 
				AND l.user_id = u.a_id 
				AND l.tag2 = c.course_no 
				GROUP BY u.id			
				ORDER BY l.mtime DESC 
				";
		}
		else
		{
			$Q1 = "SELECT u.id, u.name user_name, l.tag1 ip, l.tag2 course_no, c.name course_name, l.mtime
				FROM `log` as l, `user` as u, `course` as c
				WHERE l.`event_id` =26 
				AND l.tag2 = '$_GET[course_no]'
				AND l.user_id = u.a_id 
				AND l.tag2 = c.course_no 
				GROUP BY u.id 
				ORDER BY l.mtime  DESC 
				";
			$Q2 = "SELECT u.id, u.name user_name, l.tag1 ip, l.tag2 course_no, c.name course_name, l.mtime
				FROM `log` as l, `user` as u, `course` as c
				WHERE l.`event_id` =27 
				AND l.tag2 = '$_GET[course_no]'
				AND l.user_id = u.a_id 
				AND l.tag2 = c.course_no 
				GROUP BY u.id			
				ORDER BY l.mtime DESC 
				";
		}
		
		if (!($result = mysql_db_query($DB, $Q1)))
		{
			echo( "��ƮwŪ�����~!!$Q1" );
			exit;
		}
		
		if(mysql_num_rows($result) != 0)
		{
			echo "<table border='1' width='90%' align='center'>";
			echo "<tr><th colspan='6'>�R���ҵ{�j���O��</th></tr>
					<tr>
					<td>�b��</td>
					<td>�m�W</td>
					<td>IP��}</td>
					<td>�ҵ{�N�X</td>
					<td>�ҵ{�W��</td>
					<td>�R������P�ɶ�</td>
					</tr>
					";
			while($row = mysql_fetch_assoc($result))
			{
				$user_name = $row[user_name] ? $row[user_name]: "&nbsp;";
				
				$modify_time = $row[mtime]; 
				echo "<tr>
						<td>$row[id]</td>
						<td>$user_name</td>
						<td>$row[ip]</td>
						<td>$row[course_no]</td>
						<td>$row[course_name]</td>
						<td>$modify_time</td>
						</tr>
						";
					
			}
			echo "</table>";
		}
		else
		{
			echo "<center>�L����R���O��</center>";
		}
		
		
		if (!($result = mysql_db_query($DB, $Q2)))
		{
			echo( "��ƮwŪ�����~!!$Q2" );
			exit;
		}
		
		if(mysql_num_rows($result) != 0)
		{
			echo "<table border='1' width='90%' align='center'>";
			echo "<tr><th colspan='6'>�W�ǽҵ{�j���O��</th></tr>
					<tr>
					<td>�b��</td>
					<td>�m�W</td>
					<td>IP��}</td>
					<td>�ҵ{�N�X</td>
					<td>�ҵ{�W��</td>
					<td>�W�Ǥ���P�ɶ�</td>
					</tr>
					";
			while($row = mysql_fetch_assoc($result))
			{
				$user_name = $row[user_name] ? $row[user_name]: "&nbsp;";
				
				$modify_time = $row[mtime]; 
				echo "<tr>
						<td>$row[id]</td>
						<td>$user_name</td>
						<td>$row[ip]</td>
						<td>$row[course_no]</td>
						<td>$row[course_name]</td>
						<td>$modify_time</td>
						</tr>
						";
					
			}
			echo "</table>";
		}
		else
		{
			echo "<center>�L����W�ǰO��</center>";
		}
		
		
		echo "<center><a href='http://ecourse.elearning.ccu.edu.tw/php/check_admin.php'>�^�t�κ޲z�̤���</a></center>";
	}
	else
	{
		echo "<html>
				<head><title>�ҵ{�j���]�ֵ{��</title></head>
				<body>
					<center><a href='http://ecourse.elearning.ccu.edu.tw/php/check_admin.php'>�^�t�κ޲z�̤���</a></center>
					<table border='0' width='250' align='center'>
					<tr>
					<form action='intro_audit.php' method='GET' target='_top'>
						<td width='200'>�ҵ{�N�X(�Ҧp4156130_01):<input type=text name=course_no size=10 /></td>
						<td><input type=submit value='�d��' /></td>
						<td><input type=reset value='�M��' /></td>
					</form>
					</tr>
					<tr>
					<form action='intro_audit.php' method='GET' target='_top'>
						<input type=hidden name=course_no value='All' />
						<td colspan='3' align='center'><input type=submit value='�d�ߩҦ��O��' /></td>
					</form>
					</tr>
					</table>
					
				</body>
				</html>
				
			";
	}


?>
