<HTML>
<HEAD>
<TITLE>建立課程編號</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
</HEAD>
<BODY background = "/images/img/bg.gif">
<center>
<BR>
<H2>建立課程編號</H2>
<form method=POST action=./add_course_no.php>
<font color="#ff0000">MES</font><br>
<a href=../check_admin.php>回系統管理介面</a>
<table border=1>
<tr>
<td>課程名稱、類別</td>
<td>課程編號</td>
</tr>
<tr>
<td>
<select NAME=cid>

<!-- BEGIN DYNAMIC BLOCK: course_list -->
<option value="CVD">CID
<!-- END DYNAMIC BLOCK: course_list -->

</select>
</td>
<td>
<input type=text name=cno maxlength=15 value="CNO">
</td>
</tr>
</table><BR>
<input type=submit value=加入>
<input type=reset value=清除><br>
</form></center></body></html>