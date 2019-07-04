<HTML>
<HEAD>
<TITLE>新增類別</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
</HEAD>
<BODY background = "/images/img/bg.gif">
<center>
<BR>
<H2>新增類別</H2>
<form method=POST action=./create_group.php>
<font color="#ff0000">MES</font><br>
<a href=../check_admin.php>回系統管理介面</a>
<table border=1>
<tr><td>類別名稱</td><td>
<input type=text name=name maxlength=16 value=NAME></td></tr>

<tr><td>繼承類別</td><td>
<select name=p_id>

<!-- BEGIN DYNAMIC BLOCK: p_list -->
<option value=GVD>GID
<!-- END DYNAMIC BLOCK: p_list -->

</select></td></tr>
</table><BR>
<input type=submit value=加入>
<input type=reset value=清除><br>
</form></center></body></html>