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

	if(modify_dis.isgroup[0].checked) {
		flag = true && flag;
	}
	else {
		if(isNaN(parseInt(modify_dis.group_num.value))) {
			flag = false && flag;
			message = message + ' ���T�էO';
		}
		else
			flag = true && flag;			
	}

	if(!flag) {
		alert(message);
	}
	return flag;
}
</script>
</head>
<body background="/images/img/bg.gif">
<IMG SRC="/images/img/b52.gif">
<center>
<form action="modify_discuss.php" method="post" name="modify_dis" onsubmit="return checkinput();">
<table border=2 width=75%>
<tr bgcolor=#edf3fa><td>�Q�װϦW��<td bgcolor=#cdeffc><input type="text" name="discuss_name" size=30 maxlength=100 value="NAME">
<tr bgcolor=#edf3fa><td>�Q�װϥD��<td bgcolor=#cdeffc><input type="comment" name="comment" size=30 maxlength=100 value="COMMENT">
<tr bgcolor=#edf3fa><td>�O�_���p�հQ�װ�
    <td bgcolor=#cdeffc><input type="radio" name="isgroup" value=0 NOT_GROUP>�_
	    <input type="radio" name="isgroup" value=1 IS_GROUP>�O��<input type="text" name="group_num" size=2 maxlength=2 value=GROUP_NO>��
<tr bgcolor=#edf3fa><td>�Q�װ��s���v��
    <td bgcolor=#cdeffc><input type="radio" name="access" value="0" VIEW_PUBLIC>���}
    <input type="radio" name="access" value="1" VIEW_PRIVATE>�p�H(�u���p�զ����i�H��)
</table><br>
<font color=#ff0000>�p�ק�էO�A�аO�o���s�M�Τ��զW��C</font><br>
<input type="hidden" name="discuss_id" value="DISCUSS_ID">
<input type="submit" name="submit" value="�T�w�ק�"><input type="reset" name="reset">
<input type="button" value="�^�Q�װϤ@��" onClick="location.href='dis_list.php?PHPSESSID=PHP_ID'">
</form>
</center>
</body>
</html>