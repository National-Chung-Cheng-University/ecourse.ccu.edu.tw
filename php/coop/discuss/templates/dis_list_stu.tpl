<html>
<head>
<title> TITLE </title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css">
<script language="Javascript">
var selected = false;
function check(selected) {
     if(selected == false)
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
<tr bgcolor=#cdeffc><td width=50>��� �Q�װ�<td>�Q�װϼ��D<td>�Q�װϥD��<td width=120>����<td>�q�\���p
<!-- BEGIN DYNAMIC BLOCK: discuss_list -->
<tr bgcolor=DISCOLOR>
<td width=50><input type="checkbox" name="DEL_NAME" value="DIS_ID" onClick="selected=(selected||this.checked);">
<td><a href="ART_LIST" LOG_PRG>DIS_NAME</a>
<td>DIS_COMMENT
<td width=120>DIS_TYPE
<td width=80>SUB_STATUS
<!-- END DYNAMIC BLOCK: discuss_list -->
</table>
<img src="/images/arrow_ltr.gif" border=0 width="38" height="22" alt="��ܪ��ʧ@" >
<input type="submit" name="submit" value="�q�\" onClick="return check(selected);">
<input type="submit" name="submit" value="���q" onClick="return check(selected);">
</form>
<hr>
<p align="right">
<form action="search_discuss.php" method="post">
<table border=1 width=50%>
<caption>�Q�װϤ峹�j�M��</caption>
<tr><td bgcolor="#edf3fa">�j�M�r��<td bgcolor=#cdeffc><input type="text" name="keyword"><br>&nbsp;�i��J�h�Ӭd�ߦr(�H�ť���j�}),&nbsp;�d�߱���&nbsp;"<I>�P(AND)</I>"
<tr><td bgcolor="#edf3fa">�j�M�ؼ�<td bgcolor=#cdeffc><input type="radio" name="type" value="0" checked>�峹���D
<input type="radio" name="type" value="1">�@��
<input type="radio" name="type" value="2">�峹���e
</table>
<input type="submit" value="�}�l�j�M">
<input type="reset" value="���s��J">
</p>
</form>
</body>
</html>