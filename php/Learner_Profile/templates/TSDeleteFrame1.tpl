<HTML>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css"> 
<script language="JavaScript">
var flag = 0
function MsgWin(){
msg=open('','','toolbar=no,directories=no,menubar=no,width=300,height=30')
msg.document.write('<BODY><center><h4>DATAQUERY</h4></center></BODY>'); 
flag = 1
}
function MsgWinC(){
if(flag == 1) {
msg.close()
flag = 0 }
}

function Credit(){
	document.credit.submit()
}
</script>
</head>
<body onunload=MsgWinC() background="/images/IMG/bg.gif">
 <p>
<img src="/images/IMG/a62.gif">
 <p>
<center>
<form action=./TSDeleteFrame1.php name=credit method=get>
<select name = nocredit onChange="Credit();">
<!--option value=0 CID0>CNAME0</option-->
<option value=1 CID1>CNAME1</option>
<option value=2 CID2>CNAME2</option>
</select>
</form>
<form method=POST action=TSDeleteFrame1.php>
<table border="0" align="center" cellpadding="0" cellspacing="0" width="40%">
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
<!-- BEGIN DYNAMIC BLOCK: row -->
<tr bgcolor=COLOR>
<td>CHOICE</td>
<td>STUDENT_NAME</td>
<td>STUDENT_ID</td>
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
<input type=hidden name=nocredit value="NOCREDIT">
<input type=submit value=DELETE onclick="MsgWin()">　　<input type=reset value=CLEAR>
</form>
</center>
</BODY>
</HTML>
