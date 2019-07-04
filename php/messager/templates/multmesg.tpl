<html>
<head>
<title>多人訊息</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<STYLE type=text/css>
 body { scrollbar-face-color: #4d6eb2; scrollbar-arrow-color: #FFFFFF; scrollbar-track-color: #DDDDFF; scrollbar-shadow-color: #054878; scrollbar-3dlight-color: black; }
</STYLE>
<script language="JavaScript">
CLOSEself.close();
</script>
</head>
<body background=/images/img/bg.gif>
<center>
<form action=./multmesg.php method = post>
<table width=90%><tr><td width=30%><hr></td><td align="center"><font color="#005500" size=-1>線上朋友</font></td><td width=30%><hr></td></tr></table>
<table border=0 width=90%>
<!-- BEGIN DYNAMIC BLOCK: friend_list -->
<TR><TD align="center"><input type=checkbox name=userNUM3 value='AID3'></TD><TD align="center"><font color='#005500'>USER_NAME3</font></TD></TR>
<!-- END DYNAMIC BLOCK: friend_list -->
</table>
<Br>
<table width=90%><tr><td width=30%><hr></td><td align="center"><font color="#AAAA22" size=-1>離線朋友</font></td><td width=30%><hr></td></tr></table>
<table border=0 width=90%>
<!-- BEGIN DYNAMIC BLOCK: friend_oist -->
<TR><TD align="center"><input type=checkbox name=userNUM4 value='AID4'></TD><TD align="center"><font color='#AAAA22'>USER_NAME4</font></TD></TR>
<!-- END DYNAMIC BLOCK: friend_oist -->
</table>
<Br>
<form action=./multmesg.php method = post>
<table width=90%><tr><td width=40%><hr></td><td align="center"><font color="#0000FF" size=-1>同學</font></td><td width=40%><hr></td></tr></table>
<table border=0 width=90%>
<!-- BEGIN DYNAMIC BLOCK: course_list -->
<TR><TD align="center"><input type=checkbox name=userNUM1 value=AID1></TD><TD align="center"><font color='#0000FF'>USER_NAME1</font></TD></TR>
<!-- END DYNAMIC BLOCK: course_list -->
</table>
<Br>
<table width=90%><tr><td width=40%><hr></td><td align="center"><font color="#FF0000" size=-1>系統</font></td><td width=40%><hr></td></tr></table>
<table border=0 width=90%>
<!-- BEGIN DYNAMIC BLOCK: system_list -->
<TR><TD align="center"><input type=checkbox name=userNUM2 value=AID2></TD><TD align="center"><font color='#FF0000'>USER_NAME2</font></TD></TR>
<!-- END DYNAMIC BLOCK: system_list -->
</table>
<table>
<table width=90%>
<tr><td><textarea name=message rows=10 cols=19 >MESSAGE
</textarea></td></tr>
</table>
<input type=hidden value=NUM3 name=num>
<input type=submit value=發送 name="submit">
<input type=reset value=清除 name="reset">
<input type=button value="關閉" OnClick="self.close();">
</table>
</form>
</center>
</body>
</html>