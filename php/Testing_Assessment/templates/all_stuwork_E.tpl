<html>
<head>
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=big5">
</head>
<center>
<table border="0" align="center" cellpadding="0" cellspacing="0" width="80%">
<tr> 
<td> 
<div align="right"><img src="/images/skinSKINNUM/bor/bor_01.GIF" width="12" height="11"></div>
</td>
<td> 
<div align="center"><img src="/images/skinSKINNUM/bor/bor_02.GIF" width="100%" height="11"></div>
</td>
<td> 
<div align="left"><img src="/images/skinSKINNUM/bor/bor_03.GIF" width="17" height="11"></div>
</td>
</tr>
<tr> 
<td height=10> 
<div align="right"><img src="/images/skinSKINNUM/bor/bor_04.GIF" width="12" height="100%"></div>
</td>
<td bgcolor="#CCCCCC"> 
<table border=0 align="center" width="100%" cellpadding="3" cellspacing="1">
<tr bgcolor="#000066">
<td><div align="center"><font color="#FFFFFF">Student ID</font></div></td>
<td><div align="center"><font color="#FFFFFF">Date</font></div></td>
<td><div align="center"><font color="#FFFFFF">Score</font></div></td>
<td><div align="center"><font color="#FFFFFF">See Student's Answer</font></div></td>
<td><div align="center"><font color="#FFFFFF">To Grade</font></div></td>
<td><div align="center"><font color="#FFFFFF">Comment Files</font></div></td>
<td><div align="center"><font color="#FFFFFF">Share To Other Student</font></div></td></tr>
<!-- BEGIN DYNAMIC BLOCK: row -->
<tr bgcolor="COLOR">
<td>USERID</td>
<td>SDATE</td>
<td>SGRADE</td>
<td>
<a href=./check_allwork.php?work_id=WORKID&sid=SNO&action=loadwork>See_Student's_Answer</a>
</td>
<form method=POST action=check_allwork.php>
<td>
<input type=hidden name=work_id value=WORKID>
<input type=hidden name=sid value=SNO>
<input type=hidden name=action value=updateg>
<input type=text name=wgrade size=3>
<input type=submit value=Sure>
</td>
</form>
<td>
<a href=./post_comment.php?work_id=WORKID&sid=SNO&action=upload_comment>Upload_Comment</a>
</td>
<td>
<a href=./check_allwork.php?work_id=WORKID&sid=SNO&action=pubstuwork&ispub=PUBWORK>ISPUB</a>
</td>
</tr>
<!-- END DYNAMIC BLOCK: row -->
</table>
</td>
<td height=10> 
<div align="left"><img src="/images/skinSKINNUM/bor/bor_06.GIF" width="17" height="100%"></div>
</td>
</tr>
<tr> 
<td> 
<div align="right"><img src="/images/skinSKINNUM/bor/bor_07.GIF" width="12" height="17"></div>
</td>
<td> 
<div align="center"><img src="/images/skinSKINNUM/bor/bor_08.GIF" width="100%" height="17"></div>
</td>
<td> 
<div align="left"><img src="/images/skinSKINNUM/bor/bor_09.GIF" width="17" height="17"></div>
</td>
</tr>
</table>
</center>
</body>
</html>
