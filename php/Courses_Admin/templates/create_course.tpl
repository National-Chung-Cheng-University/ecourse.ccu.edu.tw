<HTML>
<HEAD>
<TITLE>新增課程</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
</HEAD>
<BODY background = "/images/img/bg.gif">
<center>
<BR>
<H2>新增課程</H2>
<form method=POST action=./create_course.php>
<font color="#ff0000">MES</font><br>
<a href=../check_admin.php>回系統管理介面</a>
<table border=1>
<tr><td>課程名稱</td><td>
<input type=text name=course_name maxlength=16 value=CNAME></td></tr>
<tr><td>課程代號</td><td>
<input type=text name=course_id maxlength=15 value=CID></td></tr>

<tr><td>課程類別</td><td>
<select NAME=group>

<!-- BEGIN DYNAMIC BLOCK: group_list -->
<option value=GVD>GID
<!-- END DYNAMIC BLOCK: group_list -->

</select></td></tr>
</table><BR>
<input type=submit value=加入>
<input type=reset value=清除><br>
</form></center></body></html>