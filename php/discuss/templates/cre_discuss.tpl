<html>
<head>
<title> TITLE </title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<STYLE type=text/css>
 body { SCROLLBAR-FACE-COLOR: #4d6eb2; SCROLLBAR-ARROW-COLOR: #FFFFFF; SCROLLBAR-TRACK-COLOR: #DDDDFF; SCROLLBAR-SHADOW-COLOR: #054878; SCROLLBAR-3DLIGHT-COLOR: black; }
</STYLE> 
<link rel="stylesheet" href="/images/skin1/css/main-body.css" type="text/css">
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

	if(create_dis.isgroup[0].checked) {
		flag = true && flag;
	}
	else {
		if(isNaN(parseInt(create_dis.group_num.value))) {
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

function checkinput2( ) {
	var flag = true;
	var message = '�п�J';
	
	if(isNaN(parseInt(create_batch.amount.value))) {
		flag = false && flag;
		message = message + ' ���T�ƥ�';
	}
	else {
		flag = true && flag;
	}

	if( create_batch.discuss_name.value.length > 0) {
		flag = true && flag;
	}
	else {
		flag = false && flag;
		message = message + ' �Q�װϦW��';
	}

	if( create_batch.discuss_name.value.indexOf("%d") == -1 ) {
		flag = false && flag;
		message = message + ' \%d';	
	}

	if( create_batch.comment.value.length > 0) {
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
<body background="/images/img/bg.gif">
<IMG SRC="/images/img/b52.gif">
<center>
<form action="cre_discuss.php" method="post" name="create_dis" onsubmit="return checkinput();">
<input type="hidden" name="amount" value="1">
<table border=2 width=75%>
<caption>�إ߳�@�Q�װ�</caption>
<tr bgcolor=#edf3fa>
	<td>�Q�װϦW��
	<td bgcolor=#cdeffc><input type="text" name="discuss_name" size=30 maxlength=100>
<tr bgcolor=#edf3fa>
	<td>�Q�װϥD��
	<td bgcolor=#cdeffc><input type="comment" name="comment" size=30 maxlength=100>
<tr bgcolor=#edf3fa><td>�O�_���p�հQ�װ�
    <td bgcolor=#cdeffc>
		<input type="radio" name="isgroup" value=0 checked>�_
	    <input type="radio" name="isgroup" value=1>�O��<input type="text" name="group_num" size=2 maxlength=2 onFocus="create_dis.isgroup[1].checked = true;">��
<tr bgcolor=#edf3fa><td>�Q�װ��s���v��
    <td bgcolor=#cdeffc><input type="radio" name="access" value="0" checked>���}
    <input type="radio" name="access" value="1">�p�H(�u���p�զ����i�H��)
</table>
<input type="submit" name="submit" value="�T�w�s�W"><input type="reset" name="reset">
</form>
<hr>
<form action="cre_discuss.php" method="post" name="create_batch" onSubmit="return checkinput2();">
<table border=2 width=75%>
<caption>�妸�إ߰Q�װ�</caption>
<tr bgcolor=#edf3fa>
	<td>���إ߰Q�װϼƥ�
	<td bgcolor=#cdeffc><input type="text" name="amount" size=5 maxlength=8>
<tr bgcolor=#edf3fa>
	<td>�Q�װϦW��(�бN�n�μƦr���N���a��� %d �N��)
	<td bgcolor=#cdeffc><input type="text" name="discuss_name" size=30 maxlength=100>
<tr bgcolor=#edf3fa>
	<td>�Q�װϥD��
	<td bgcolor=#cdeffc><input type="comment" name="comment" size=30 maxlength=100>
<tr bgcolor=#edf3fa><td>�O�_���p�հQ�װ�
    <td bgcolor=#cdeffc>
		<input type="radio" name="isgroup" value=0 checked>�_
	    <input type="radio" name="isgroup" value=1>�O
<tr bgcolor=#edf3fa><td>�Q�װ��s���v��
    <td bgcolor=#cdeffc><input type="radio" name="access" value="0" checked>���}
    <input type="radio" name="access" value="1">�p�H(�u���p�զ����i�H��)
</table>
<input type="submit" name="submit" value="�T�w�s�W"><input type="reset" name="reset">
</form>
<hr>
<input type="button" value="�^�Q�װϤ@��" onClick="location.href='dis_list.php?PHPSESSID=PHP_ID'">
</center>
</body>
</html>