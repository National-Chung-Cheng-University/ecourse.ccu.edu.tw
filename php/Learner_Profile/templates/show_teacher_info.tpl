<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<title>觀看教師資訊</title>
</head>

<body background = "../images/img/bg.gif">
<center>

<br />
<form action=./show_teacher_info.php name=change method=get>
        <select name=teacher_num onChange="Show();">
                <!-- BEGIN DYNAMIC BLOCK: teacher_list -->
                <option value="NUM" SELD>TEACHER_NAME</option>
                <!-- END DYNAMIC BLOCK: teacher_list -->
        </select>
</form>
<br />

<table border="2" cellspacing="4" width="90%">
<tbody><tr>
<td width="16%"><b><font color="#0000ff">姓名 :&nbsp;</font></b></td>
<td width="64%">NAME&nbsp;</td>
</tr>
<tr>
<td width="16%"><font color="#33cc33"><b>電話 :&nbsp;</b></font></td>
<td width="64%">TEL&nbsp;</td>
</tr>
<tr>
<td width="16%"><b><font color="#0000ff">住址 :&nbsp;</font></b></td>
<td width="64%">ADDR&nbsp;</td>
</tr>
<tr>
<td width="16%"><b><font color="#33cc33">首頁 :&nbsp;</font></b></td>
<td width="64%">PAGE&nbsp;</td>
</tr>
<tr>
<td width="16%"><b><font color="#0000ff">e-mail :&nbsp;</font></b></td>
<td width="64%">EMAIL&nbsp;</td>
</tr>
<tr>
<td width="16%"><b><font color="#33cc33">興趣 :&nbsp;</font></b></td>
<td width="64%">INTEREST&nbsp;</td>
</tr>
<tr>
<td width="16%"><b><font color="#0000ff">專長 :</font></b></td>
<td width="64%">SKILL&nbsp;</td>
</tr>
<tr>
<td width="16%"><b><font color="#33cc33">簡介 :</font></b></td>
<td width="64%">INTRO&nbsp;</td>
</tr>
<tr>
<td width="16%"><b><font color="#0000ff">經歷 :</font></b></td>
<td width="64%">EXPER&nbsp;</td>
</tr>
</tbody>
</table>
</center>
</body>
</html>
