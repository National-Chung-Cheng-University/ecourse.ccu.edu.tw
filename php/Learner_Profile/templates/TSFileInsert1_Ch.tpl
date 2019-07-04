<HTML>
<head> 
<title></title> 
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
<img src="/images/img/a61.gif"> <p>
<center>
<font color=#ff0000>MESSAGE</font>
<br>
<table border=0><tr><td>
<pre>
注意事項:
i.上傳的檔案需為文字檔,不限制檔名
ii.一筆學生資料放一行,學生資料的格式為:
姓名,學號#
iii.姓名或學號中間不可以有空白
iv.逗號或#字符號前後不可有空白,
#字符號後面直接按Enter換行
v.學號只能由數字與英文字母組合而成
</pre>
<br>
<FORM ENCTYPE="multipart/form-data" method=POST ACTION="TSFileInsert1.php">
<font color=#006600>
上傳區:<br>
<INPUT TYPE="FILE" NAME="upfile" SIZE="20"></font><br>
<br>
<INPUT TYPE="SUBMIT" VALUE="上傳檔案">
<INPUT TYPE="RESET" VALUE="清除欄位">
</Form>
</td></tr></table>
<p><a href="./TSInsertMS.php">回學生新增</a>
</center>
</BODY>
</HTML>
