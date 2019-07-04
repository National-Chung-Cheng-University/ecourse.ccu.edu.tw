<HTML>
<head>
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css">
<title>Delete Grade</title>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=big5">
</head>
<BODY background="/images/skinSKINNUM/bbg.gif">
<p align="center">Delete Grade</p>
<center>
<font color="#FF0000">MESSAGE</font>
<form method="POST" action="./TGDeleteFrame1.php">
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
<table border=0 cellspacing=1 cellpadding=3 width="100%">
<tr bgcolor="#000066"> 
<th> 
<div align="center"><font color="#FFFFFF" size="2">Choice</font></div>
</th>
<th> 
<div align="center"><font color="#FFFFFF" size="2">Test Name</font></div>
</th>
<th> 
<div align="center"><font color="#FFFFFF" size="2">Ratio</font></div>
</th>
</tr>
<!-- BEGIN DYNAMIC BLOCK: row -->
<tr bgcolor=COLOR>
<td align=center>
<input type=checkbox name=check_NUM value=TESTAID>
</td>
<td>TESTNAME</td>
<td>TESTRATIO</td>
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
<td nowrap align=center colspan=3>
<input type=hidden name=action value="ACT">
<input type=hidden name=total value="TOTAL">
<input type=submit name=Delete value=Delete>　　<input type=reset name=Reset value=Reset>
</form>
</center>
</BODY>
</HTML>
