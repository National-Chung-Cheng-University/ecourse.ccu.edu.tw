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
msg.document.write('<BODY><center><h4>資料處理中，請稍候...</h4></center></BODY>'); 
flag = 1
}
function MsgWinC(){
if(flag == 1) {
msg.close()
flag = 0 }
}
</script>
</head>
<body onunload=MsgWinC() background="/images/img/bg.gif">
<p>
<img src="/images/img/a61.gif">
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
<font color = "#FFFFFF">
<pre>
注意事項:
i.一筆學生資料放一行,學生資料的格式為:
姓名,學號#
ii.姓名或學號中間不可以有空白
iii.逗號或#字符號前後不可有空白,
#字符號後面直接按Enter換行
iv.學號只能由數字與英文字母組合而成
</pre></font>
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
<input type=submit name=Submit value=新增 onclick=MsgWin()>
<input type=reset name=Reset value=清除>
</form>
<p><a href="./TSInsertMS.php">回學生新增</a>
</center>
</BODY>
</HTML>
