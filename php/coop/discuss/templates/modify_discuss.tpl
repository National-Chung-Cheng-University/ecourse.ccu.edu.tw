<html>
<head>
<title> �ק�Q�װϤ��e </title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css">
<script language="javascript">
function checkinput() {
	var flag = true;
	var message = '�п�J';

	if(modify_dis.discuss_name.value.length > 0) {
		flag = true && flag;
	}
	else {
		flag = false && flag;
		message = message + ' �Q�װϦW��';
	}

	if(modify_dis.comment.value.length > 0) {
		flag = true && flag;
	}
	else {
		flag = false && flag;
		message = message + ' �Q�װϥD��';
	}

	if(!flag) {
		alert(message);
	}
	return flag;
}
</script>
</head>
<body>
<IMG SRC="/images/img/b52.gif">
<center>
<form action="modify_discuss.php" method="post" name="modify_dis" onsubmit="return checkinput();">
<table border=2 width=75%>
<tr bgcolor=#edf3fa><td>�Q�װϦW��</td><td bgcolor=#cdeffc><input type="text" name="discuss_name" size=30 maxlength=100 value="NAME"></td></tr>
<tr bgcolor=#edf3fa><td>�Q�װϥD��</td><td bgcolor=#cdeffc><input type="comment" name="comment" size=30 maxlength=100 value="COMMENT"></td></tr>
<tr bgcolor=#edf3fa><td>�Q�װ��s���v��</td>
<td bgcolor=#cdeffc><input type="radio" name="access" value="0" VIEW_PUBLIC>���}
<input type="radio" name="access" value="1" VIEW_PRIVATE>�p�H(�u���p�զ����i�H��)</td>
</tr>
</table><br>
<input type="hidden" name="discuss_id" value="DISCUSS_ID">
<input type="submit" name="submit" value="�T�w�ק�"><input type="reset" name="reset">
<input type="button" value="�^�Q�װϤ@��" onClick="location.href='dis_list.php?PHPSESSID=PHP_ID'">
</form>
</center>
</body>
</html>