<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
</head>
<body onload="location.href='#DEST'">
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css">
<center>
<form method=POST action=check_allwork.php ENCTYPE="multipart/form-data" >
<BR><BR>
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
<td><div align="center"><font color="#FFFFFF">學號</font></div></td>
<td><div align="center"><font color="#FFFFFF">繳交日期</font></div></td>
<td><div align="center"><font color="#FFFFFF">得分</font></div></td>
<td><div align="center"><font color="#FFFFFF">觀看作業</font></div></td>
<td><div align="center"><font color="#FFFFFF">給分</font></div></td>
<td><div align="center"><font color="#FFFFFF">評語檔案</font></div></td>
<td><div align="center"><font color="#FFFFFF">讓其他學生觀賞</font></div></td>
<!-- BEGIN DYNAMIC BLOCK: row -->
<tr bgcolor="COLOR">
<td>USERID</td>
<td>SDATE</td>
<td>SGRADE</td>
<td><a href=./check_allwork.php?work_id=WORKID&sid=SNO&action=loadwork>看學生作業</a>
</td>
<td>
<input type=hidden name=work_id value=WORKID>
<input type=hidden name=sid value=SNO>
<input type=hidden name=ID value=SNO>
<a id=ANCHOR></a>
<input type=hidden name=action value=updateg>
<input type=text name=WGRADE size=3>
</td>
<td>
<a href=./post_comment.php?work_id=WORKID&sid=SNO&action=upload_comment&anchor=ANCHOR>上傳評語</a>
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
<input type="submit" value="確定">
</form>
</center>
</body>
</html>
