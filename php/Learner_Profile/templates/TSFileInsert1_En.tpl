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
<img src="/images/img_E/a61.gif"> <p>
<center>
<font color=#ff0000>MESSAGE</font>
<br>
<table border=0><tr><td>
<pre>
Pay attention:
i.The file type is txt, filename doesn't restrict
ii.Only one student data per line, the format is:
Name,ID#
iii.No space in Name and ID
iv.No space left in front of and after ',' and '#'
After '#' ,please press Enter to move to the next line
v.ID is composed by character and number
</pre>
<br>
<FORM ENCTYPE="multipart/form-data" method=POST ACTION="TSFileInsert1.php">
<font color=#006600>
Upload Area：<br>
<INPUT TYPE="FILE" NAME="upfile" SIZE="20"></font><br>
<br>
<INPUT TYPE="SUBMIT" VALUE="Upload">
<INPUT TYPE="RESET" VALUE="Reset">
</Form>
</td></tr></table>
<p><a href="./TSInsertMS.php">Back to New Management</a>
</center>
</BODY>
</HTML>
