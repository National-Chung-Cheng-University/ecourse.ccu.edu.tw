<html>
<head>
<title> TITLE </title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css">
<script language="javascript">
function checkinput( ) {
	var flag = true;
	var message = '�п�J';
	
	if( create_dis.discuss_name.value.length > 0) {
		flag = true && flag;
	}
	else {
		flag = false && flag;
		message = message + ' �Q�װϦW��';
	}

	if(create_dis.comment.value.length > 0) {
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
<body >
<IMG SRC="/images/img/b52.gif">
<center>
<form action="cre_discuss.php" method="post" name="create_dis" onsubmit="return checkinput();">
<input type="hidden" name="amount" value="1">
<table border=2 width=75%>
<caption>�إ߳�@�Q�װ�</caption>
<tr bgcolor=#edf3fa>
<td>�Q�װϦW��</td>
<td bgcolor=#cdeffc><input type="text" name="discuss_name" size=30 maxlength=100></td>
</tr>
<tr bgcolor=#edf3fa>
<td>�Q�װϥD��</td>
<td bgcolor=#cdeffc><input type="comment" name="comment" size=30 maxlength=100><input type="hidden" name="isgroup" value=1 ><input type="hidden" name="access" value="1"></td>
<tr bgcolor=#edf3fa><td>�Q�װ��s���v��</tr>
<td bgcolor=#cdeffc><input type="radio" name="access" value="0" checked>���}
<input type="radio" name="access" value="1">�p�H(�u���p�զ����i�H��)</td>
</tr>
</table>
<input type="submit" name="submit" value="�T�w�s�W"><input type="reset" name="reset">
</form>
<input type="button" value="�^�Q�װϤ@��" onClick="location.href='dis_list.php?PHPSESSID=PHP_ID'">
</center>
</body>
</html>