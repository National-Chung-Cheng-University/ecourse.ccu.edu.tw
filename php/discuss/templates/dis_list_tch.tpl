<html>
<head>
<title> TITLE </title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<STYLE type=text/css>
 body { SCROLLBAR-FACE-COLOR: #4d6eb2; SCROLLBAR-ARROW-COLOR: #FFFFFF; SCROLLBAR-TRACK-COLOR: #DDDDFF; SCROLLBAR-SHADOW-COLOR: #054878; SCROLLBAR-3DLIGHT-COLOR: black; }
</STYLE>
<link rel="stylesheet" href="/images/skin1/css/main-body.css" type="text/css">
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
				return true;
				//return alert('�Q�װϸ�ƱN��X�� /�Ч��ؿ�/misc/backup.tar.gz\n�i�� �Ч��s�@->�W���ɮ� �B�U��');
			}
		default:
			return false;
	}

	return false;
}
</script>
</head>
<body background="/images/img/bg.gif">
<table width="100%" border="0">
  <tr>
    <td align="left"><IMG SRC="/images/img/b52.gif"></td>
    <td align="right">
        <form action="recentPosts.php" method="get">
	<!-- modify by w60292@20090218 �̦���n�D�ק�d�ߤ峹���Ѽ� -->
        �̷s�峹�d�ߡG
        <select name="d">
                <!--option value="3">3�Ѥ�</option>
                <option value="5" selected="selected">5�Ѥ�</option>
                <option value="10">10�Ѥ�</option>
                <option value="15">15�Ѥ�</option>
                <option value="30">�@�Ӥ뤺</option>
                <option value="60">��Ӥ뤺</option-->
		<option value="1">1�Ѥ�</option>
                <option value="3" selected="selected">3�Ѥ�</option>
                <option value="7">1�g��</option>
                <option value="14">2�g��</option>
                <option value="30">�@�Ӥ뤺</option>
                <option value="60">��Ӥ뤺</option>
        </select>
        <input type="submit" value="�d��" />
        </form>
    </td>
  </tr>
</table>
<center>
<font color="red">ERROR_MSG</font>
</center>
<form name="handle" action="handle_discuss.php" method="post">
<table border=1 width=100%>
<tr bgcolor=#cdeffc><td width=50>��� �Q�װ�<td><a href="discuss.php?field=discuss_name&sort=SORT">�Q�װϼ��D</a><td>�Q�װϥD��<td width=120>����<td width=50>�ק� �Q�װ�<td>�q�\���p
<!-- BEGIN DYNAMIC BLOCK: discuss_list -->
<tr bgcolor=DISCOLOR>
<td width=50><input type="checkbox" name="DEL_NAME" value="DIS_ID" onClick="selected=(selected||this.checked);">
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
<p align="right">
<!-- modify by w60292 @ 20090326 �s�W �����峹���� �H�� �|���^�����峹���� -->
<input type="button" value="�����峹" onclick="location.href='hot_article.php?PHPSESSID=PHP_SESS'">
<input type="button" value="�|���^�����峹" onclick="location.href='check_reply.php?PHPSESSID=PHP_SESS'">
<input type="button" value="�s�W�Q�װ�" onclick="location.href='cre_discuss.php?PHPSESSID=PHP_SESS'">
<input type="button" value="�p�ղխ��޲z" onclick="location.href='group_admin.php?PHPSESSID=PHP_SESS'">
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
