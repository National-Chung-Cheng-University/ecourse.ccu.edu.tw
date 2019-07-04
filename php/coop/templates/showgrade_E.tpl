<HTML>

<BODY background="/images/img_E/bg.gif">
<BR>
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
<td><font color="#FFFFFF">Group</font></td>
<td><font color="#FFFFFF">ID</font></td>
<td><font color="#FFFFFF">Name</font></td>
<td><font color="#FFFFFF">Trace Info</font></td>
<td><font color="#FFFFFF">Personal Judge</font></td>
<td><font color="#FFFFFF">In Group Grade</font></td>
<td><font color="#FFFFFF">Group Judge</font></td>
<td><font color="#FFFFFF">Result</font></td>
<td><font color="#FFFFFF">Group to Group Grade</font></td>
<td><font color="#FFFFFF">Real Grade</font></td>
<td><font color="#FFFFFF">Set Grade</font></td>
<!-- BEGIN DYNAMIC BLOCK: grade -->
<tr bgcolor="COLOR">
<td>GID</td>
<td>SNO</td>
<td>SNN</td>
<td><a href=# onClick="window.open('./Trackin/TraceInfo.php?case_id=CASEID&group_id=GID&aid=AID&PHPSESSID=PHPSID','','resizable=1,scrollbars=1,width=600,height=400');">Trace Info</a></td>
<td><a href=# onClick="window.open('./Mag_case.php?case_id=CASEID&aid=AID&type=0&action=judge&PHPSESSID=PHPSID','','resizable=1,scrollbars=1,width=400,height=400');">Judge</a></td>
<td>GIN</td>
<td><a href=# onClick="window.open('./Mag_case.php?case_id=CASEID&group_id=GID&type=1&action=judge&PHPSESSID=PHPSID','','resizable=1,scrollbars=1,width=400,height=400');">Judge</a></td>
<td><a href=./result/result.php?coopgroup=CASEID&coopcaseid=GID target="_blank">Result</a></td>
<td>GOUT</td>
<td>TOTALG</td>
<form method=POST action=Mag_case.php>
<td>
<input type=hidden name=case_id value=CASEID>
<input type=hidden name=sid value=AID>
<input type=hidden name=action value=updateg>
<input type=text name=grade size=3>
<input type=submit value=Set>
</td>
</form>
</tr>
<!-- END DYNAMIC BLOCK: grade -->
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
