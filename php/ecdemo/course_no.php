<?php

require 'fadmin.php';
?>
<html>
<head>
<title>�P�B�ҵ{�s��</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
</head>
<body background = "/images/img/bg.gif"><center>
<?
// �s��mysql
if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
	$error = "��Ʈw�s�����~!!";
	return $error;
}
$Q1="select course_id, course_no from course_no";
if ($result = mysql_db_query($DB,$Q1)){			
	while($row = mysql_fetch_array($result)) {
		$Q2="update course set course_no='$row[course_no]' where a_id=$row[course_id]";
		if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) ) {
			$error = "��Ʈw�g�J���~!!".$Q2;
			return $error;
		}
	}
}
echo " course_no��s�����I<BR>";
echo "<br><a href=../check_admin.php>�^�t�κ޲z����</a>";
?>
</div>
</center>
</body>
</html>