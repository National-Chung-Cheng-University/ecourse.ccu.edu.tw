<HTML>
<SCRIPT LANGUAGE="JavaScript"> 
function note () {
	window.open('./note/note.php?PHPSESSID=62284a7bd11c0fcd5dd3da42e8ae0172','','resizable=1,scrollbars=1,width=400,height=400');
}
</script>
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css">
<BODY background="/images/img/bg.gif">
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
<tr bgcolor="#000066">
<td><font color="#FFFFFF">組別</font></td>
<td><font color="#FFFFFF">學號</font></td>
<td><font color="#FFFFFF">姓名</font></td>
<td><font color="#FFFFFF">參予歷程</font></td>
<td><font color="#FFFFFF">個人評語</font></td>
<td><font color="#FFFFFF">組內互評成績</font></td>
<td><font color="#FFFFFF">小組評語</font></td>
<td><font color="#FFFFFF">小組成果</font></td>
<td><font color="#FFFFFF">組間互評成果成績</font></td>
<td><font color="#FFFFFF">實際成績</font></td>
<td><font color="#FFFFFF">給定成績</font></td>
<!-- BEGIN DYNAMIC BLOCK: grade -->
<tr bgcolor="COLOR">
<td>GID</td>
<td>SNO</td>
<td>SNN</td>
<td><a href=# onClick="window.open('./Trackin/TraceInfo.php?case_id=CASEID&group_id=GID&aid=AID&PHPSESSID=PHPSID','','resizable=1,scrollbars=1,width=600,height=400');">參予歷程</a></td>
<td><a href=# onClick="window.open('./Mag_case.php?case_id=CASEID&aid=AID&type=0&action=judge&PHPSESSID=PHPSID','','resizable=1,scrollbars=1,width=400,height=400');">評語</a></td>
<td>GIN</td>
<td><a href=# onClick="window.open('./Mag_case.php?case_id=CASEID&group_id=GID&type=1&action=judge&PHPSESSID=PHPSID','','resizable=1,scrollbars=1,width=400,height=400');">評語</a></td>
<td><a href="./result/result.php?test=1&group_id=GID&case_id=CASEID" target="_blank">小組成果</a></td>
<td>GOUT</td>
<td>TOTALG</td>
<form method=POST action=Mag_case.php>
<td>
<input type=hidden name=case_id value=CASEID>
<input type=hidden name=sid value=AID>
<input type=hidden name=action value=updateg>
<input type=text name=grade size=3>
<input type=submit value=確定>
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
