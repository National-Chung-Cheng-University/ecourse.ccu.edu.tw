<HTML>
<head> 
<title>新增學生部分</title> 
<meta http-equiv="Content-Type" content="text/html; charset=big5"> 
<STYLE type=text/css> 
 body { scrollbar-face-color: #4d6eb2; scrollbar-arrow-color: #FFFFFF; scrollbar-track-color: #DDDDFF; scrollbar-shadow-color: #054878; scrollbar-3dlight-color: black; } 
</STYLE>  
<script language="JavaScript">
var flag = 0
function MsgWin(){
msg=open('','','toolbar=no,directories=no,menubar=no,width=300,height=30')
msg.document.write('<BODY><center><h4>Data processing,please wait a moment...</h4></center></BODY>'); 
flag = 1
}
function MsgWinC(){
if(flag == 1) {
msg.close()
flag = 0 }
}
</script>
</head>
<body onunload=MsgWinC() background="/images/img_E/bg.gif">
<p>
<img src="/images/img_E/a61.gif">
<p>
<center>
<font color=#ff0000>MESSAGE</font>
<form method=POST action=TSAreaInsert1.php>
<table border="0" align="center" cellpadding="0" cellspacing="0" >
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
<tr>
<td bgcolor=#4d6eb2>
<font color = "#FFFFFF"><pre>
Pay attention:
i.Only one student data per line, the format is:
Name,ID#
ii.No space in Name and ID
iii.No space left in front of and after ',' and '#'
After '#' ,please press Enter to move to the next line
iv.ID is composed by character and number
</pre>
</td>
</tr>
<tr>
<td>
<textarea name=stdlist rows=20 cols=35>VALUE</textarea>
</td>
</tr>
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
<input type=submit name=Submit value=New onclick=MsgWin()>
<input type=reset name=Reset value=Reset>
</form>
<p><a href="./TSInsertMS.php">Back to New Management</a>
</center>
</BODY>
</HTML>
