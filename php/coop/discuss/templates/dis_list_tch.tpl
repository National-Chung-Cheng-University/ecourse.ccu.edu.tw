<html>
<head>
<title> TITLE </title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css">
<script language="Javascript">
var selected = false;
function check(selected,type) {
	
	switch (type) {
		case 1:
			if(selected) {
				return confirm('�o�N�|�R���A�ҿ諸�Ҧ��Q�װ�!!');
			}
		case 2:
			if(selected) {
				return true;
			}
		case 3:
			if(selected) {
				return alert('�Ч���X��\n���I��W�誺�s���U��');
			}
		default:
			return false;
	}

	return false;
}
</script>
</head>
<body>
<IMG SRC="/images/img/b52.gif">
<center>
<font color="red">ERROR_MSG</font>
</center>
<form name="handle" action="handle_discuss.php" method="post">
<table border=1 width=100%>
<tr bgcolor=#cdeffc><td width=50>��� �Q�װ�<td>�Q�װϼ��D<td>�Q�װϥD��<td width=120>����<td width=50>�ק� �Q�װ�<td>�q�\���p
<!-- BEGIN DYNAMIC BLOCK: discuss_list -->
<tr bgcolor=DISCOLOR>
<td width=50><input type="checkbox" name="DEL_NAME" value="DIS_ID" onBlur="selected=(selected||this.checked);">
<td><a href="ART_LIST" LOG_PRG>DIS_NAME</a>
<td>DIS_COMMENT
<td width=120>DIS_TYPE
<td width=50><a href="modify_discuss.php?discuss_id=DIS_ID&PHPSESSID=PHP_SESS">�}�l �ק�</a>
<td width=80>SUB_STATUS
<!-- END DYNAMIC BLOCK: discuss_list -->
</table>
<img src="/images/arrow_ltr.gif" border=0 width="38" height="22" alt="��ܪ��ʧ@" >
<input type="submit" name="submit" value="�R���Q�װ�" onClick="return check(selected,1);">
<input type="submit" name="submit" value="�q�\" onClick="return check(selected,2);">
<input type="submit" name="submit" value="���q" onClick="return check(selected,2);">
<input type="submit" name="submit" value="��X�ƥ�" onClick="return check(selected,3);">
<p align="left">
<input type="button" value="�s�W�Q�װ�" onclick="location.href='cre_discuss.php?PHPSESSID=PHP_SESS'">
</form>
</p>
<hr>
<p align="right">
<form action="search_discuss.php" method="post">
<table border=1 width=50%>
<caption>�Q�װϤ峹�j�M��</caption>
<tr><td bgcolor="#edf3fa">�j�M�r��<td bgcolor=#cdeffc><input type="text" name="keyword" size="30"><br>&nbsp;�i��J�h�Ӭd�ߦr(�H�ť���j�}),&nbsp;�d�߱���&nbsp;"<I>�P(AND)</I>"
<tr><td bgcolor="#edf3fa">�j�M�ؼ�<td bgcolor=#cdeffc><input type="radio" name="type" value="0" checked>�峹���D
<input type="radio" name="type" value="1">�@��
<input type="radio" name="type" value="2">�峹���e
</table>
<input type="submit" value="�}�l�j�M">
<input type="reset" value="���s��J">
</p>
</body>
</html>