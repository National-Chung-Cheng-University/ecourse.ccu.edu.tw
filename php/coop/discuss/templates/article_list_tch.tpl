<html>
<head>
<title> TITLE </title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css">
<script language="Javascript">
var selected = false;
function check(selected) {
   if(selected == true) {
     return confirm('�o�N�|�R���A�ҿ諸�Q�ץD�D�P��Ҧ������Q�פ峹!!');
   }
   else
     return false;
}
</script>
</head>
<body>
<IMG SRC="/images/img/b52.gif">
<table border=0>
<tr><td><a href="dis_list.php">�Q�װϤ@��</a>&nbsp;|&nbsp;
    <td><a href="post_article.php?discuss_id=DIS_ID&page=PAGE_NOW">�o��s�D�D</a>
	<td>�ثe���� <font color=red>PAGE_NUM</font> ��, �@�� <font color=red>PAGE_TOTAL </font>��, <font color=red>ART_TOTAL</font> �g�Q�פ峹.
</table>
<form method="post" action="del_article.php" onSubmit="return check(selected);">
<table border=1 width=100%>
<tr bgcolor=#cdeffc>
  <td>�D�D
  <td><font color=#ffffff><a href='article_list.php?discuss_id=DIS_ID&sortby=1&group_num=GROUP_NUM'>�i�K��</a></font>
  <td><font color=#ffffff><a href='article_list.php?discuss_id=DIS_ID&group_num=GROUP_NUM'>�i�K���</a></font>
  <td><font color=#ffffff><a href='article_list.php?discuss_id=DIS_ID&sortby=2&group_num=GROUP_NUM'>�̪�^�Ф��</a></font>
  <td><font color=#ffffff><a href='article_list.php?discuss_id=DIS_ID&sortby=3&group_num=GROUP_NUM'>�I�\����</a></font>
  <td>�^�Ц���
  <td>�R�����峹
<!-- BEGIN DYNAMIC BLOCK: article_list -->
<tr bgcolor=ARCOLOR><td>ITEMA<td>ITEMB<td>ITEMC<td>ITEMD<td>ITEME<td>CHILDS<td>DELETE
<!-- END DYNAMIC BLOCK: article_list -->
</table>
<table border=0 width=100%>
<tr><td align="right">
<input type="hidden" name="discuss_id" value="DIS_ID">
<input type="submit" name="submit" value="�R���峹"><input type="reset" name="reset" value="���s���">
</table>
<table border=0>
<tr><td><a href="dis_list.php">�Q�װϤ@��</a>&nbsp;|&nbsp;
    <td><a href="post_article.php?discuss_id=DIS_ID&page=PAGE_NOW">�o��s�D�D</a>
	<td>�ثe���� <font color=red>PAGE_NUM</font> ��, �@�� <font color=red>PAGE_TOTAL </font>��, <font color=red>ART_TOTAL</font> �g�Q�פ峹.
</table>
<hr>
<center>
PREV_PAGE  PAGE_LINK  NEXT_PAGE
</center>
</body>
</html>