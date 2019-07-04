<?php
	require_once('fadmin.php');
	
	if (!($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)))
	{
		echo "資料庫連結錯誤!!";
		exit;
	}

	if (!(isset($PHPSESSID) && ($check = session_check_teach($PHPSESSID))))
	{
		show_page( "not_access.tpl" ,"權限錯誤，你的IP已經被記錄下來，請勿非法進入");
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
			echo( "資料庫讀取錯誤!!$Q1" );
			exit;
		}
		
		if(mysql_num_rows($result) != 0)
		{
			echo "<table border='1' width='90%' align='center'>";
			echo "<tr><th colspan='6'>刪除課程大綱記錄</th></tr>
					<tr>
					<td>帳號</td>
					<td>姓名</td>
					<td>IP位址</td>
					<td>課程代碼</td>
					<td>課程名稱</td>
					<td>刪除日期與時間</td>
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
			echo "<center>無任何刪除記錄</center>";
		}
		
		
		if (!($result = mysql_db_query($DB, $Q2)))
		{
			echo( "資料庫讀取錯誤!!$Q2" );
			exit;
		}
		
		if(mysql_num_rows($result) != 0)
		{
			echo "<table border='1' width='90%' align='center'>";
			echo "<tr><th colspan='6'>上傳課程大綱記錄</th></tr>
					<tr>
					<td>帳號</td>
					<td>姓名</td>
					<td>IP位址</td>
					<td>課程代碼</td>
					<td>課程名稱</td>
					<td>上傳日期與時間</td>
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
			echo "<center>無任何上傳記錄</center>";
		}
		
		
		echo "<center><a href='http://ecourse.elearning.ccu.edu.tw/php/check_admin.php'>回系統管理者介面</a></center>";
	}
	else
	{
		echo "<html>
				<head><title>課程大綱稽核程式</title></head>
				<body>
					<center><a href='http://ecourse.elearning.ccu.edu.tw/php/check_admin.php'>回系統管理者介面</a></center>
					<table border='0' width='250' align='center'>
					<tr>
					<form action='intro_audit.php' method='GET' target='_top'>
						<td width='200'>課程代碼(例如4156130_01):<input type=text name=course_no size=10 /></td>
						<td><input type=submit value='查詢' /></td>
						<td><input type=reset value='清除' /></td>
					</form>
					</tr>
					<tr>
					<form action='intro_audit.php' method='GET' target='_top'>
						<input type=hidden name=course_no value='All' />
						<td colspan='3' align='center'><input type=submit value='查詢所有記錄' /></td>
					</form>
					</tr>
					</table>
					
				</body>
				</html>
				
			";
	}


?>
