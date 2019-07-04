<?php

require 'fadmin.php';
?>
<html>
<head>
<title>同步課程編號</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
</head>
<body background = "/images/img/bg.gif"><center>
<?
// 連結mysql
if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
	$error = "資料庫連結錯誤!!";
	return $error;
}
$Q1="select course_id, course_no from course_no";
if ($result = mysql_db_query($DB,$Q1)){			
	while($row = mysql_fetch_array($result)) {
		$Q2="update course set course_no='$row[course_no]' where a_id=$row[course_id]";
		if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) ) {
			$error = "資料庫寫入錯誤!!".$Q2;
			return $error;
		}
	}
}
echo " course_no更新完成！<BR>";
echo "<br><a href=../check_admin.php>回系統管理介面</a>";
?>
</div>
</center>
</body>
</html>