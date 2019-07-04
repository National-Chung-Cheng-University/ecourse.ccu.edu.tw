<HTML>
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css">
<BODY background="/images/img/bg.gif">
<BR>
<center>
<h2>本組目前分數 : TOTAL </h2>
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
<td><font color="#FFFFFF">組別</font></td>
<td><font color="#FFFFFF">小組成果</font></td>
<td><font color="#FFFFFF">給定成績</font></td>
<td><font color="#FFFFFF">評語</font></td>
<!-- BEGIN DYNAMIC BLOCK: group_list -->
<tr bgcolor="COLOR">
<td>GID</td>
<td><a href=../result/result.php?group_id=GID&case_id=CASEID target="_blank">小組成果</a></td>
<form method=POST action=GBGrade.php>
<td>
<input type=hidden name=case_id value=CASEID>
<input type=hidden name=group_id value=GID>
<input type=hidden name=action value=update>
<input type=text name=grade size=3 value="GRADE">
</td>
<td nowrap>
<input type=text size=40 name=judge value="JUDGE">
<input type=submit value=確定>
</td>
</form>
</tr>
<!-- END DYNAMIC BLOCK: group_list -->
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
</BODY>

</HTML>
