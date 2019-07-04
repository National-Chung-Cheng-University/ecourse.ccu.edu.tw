<HTML>
<HEAD>
<TITLE>搜尋教師資訊</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
</HEAD>
<BODY background = "/images/img/bg.gif">
<center>
<BR>
<H2>搜尋使用者資訊</H2>
<BR>
<font color="#ff0000">MES</font><br>
<a href=../check_admin.php>回系統管理介面</a>
<table>
<tr>
<td>
<form action="search_teacher_info.php" method="post">
 帳 號 :
  <input name="teacher_id" type="text">
  <input name="condition" type="hidden" value="0">
<input name="" type="submit" value="搜尋">
</form></td>
</tr>
<tr>
<td>
<form action="search_teacher_info.php" method="post">
老 師 名 :
  <input name="teacher_name" type="text">
  <input name="condition" type="hidden" value="1">
<input name="" type="submit" value="搜尋">
</form></td>
</tr>
<tr>
<td>
<form action="search_teacher_info.php" method="post">
課程代號:<input name="course_no" type="text">
<input name="condition" type="hidden" value="2">
<input name="" type="submit" value="搜尋">
</form></td>
</tr>
<tr>
<td>
<form action="search_teacher_info.php" method="post">
課程名稱:<input name="course_name" type="text">
<input name="condition" type="hidden" value="3">
<input name="" type="submit" value="搜尋">
</form></td>
</tr>
<tr>
<td>
<form action="search_teacher_info.php" method="post">
E-mail:<input name="email" type="text">
<input name="condition" type="hidden" value="4">
<input name="" type="submit" value="搜尋">
</form></td>
</tr>
</table>

<p>&nbsp;</p>
<Table border=1>

<!-- BEGIN DYNAMIC BLOCK: tech_list -->

<tr>
<td>TYPE</td>
<td>NAME</td>
<td>UID</td>
<td>PASS</td>
<td>EMAIL</td>
</tr>

<!-- END DYNAMIC BLOCK: tech_list -->

</table>
</center>
</body></html>
