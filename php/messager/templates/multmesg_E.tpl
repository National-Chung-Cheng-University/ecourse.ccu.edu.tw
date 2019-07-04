<html>
<head>
<title>Multi Message</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<STYLE type=text/css>
 body { scrollbar-face-color: #4d6eb2; scrollbar-arrow-color: #FFFFFF; scrollbar-track-color: #DDDDFF; scrollbar-shadow-color: #054878; scrollbar-3dlight-color: black; }
</STYLE>
<script language="JavaScript">
CLOSEself.close();
</script>
</head>
<body background="/images/img_E/bg.gif">
<center>
<form action=./multmesg.php method = post>
<table width=90%><tr><td width=20%><hr></td><td align="center"><font color="#005500" size=-1>Friend online</font></td><td width=20%><hr></td></tr></table>
<table border=0 width=90%>
<!-- BEGIN DYNAMIC BLOCK: friend_list -->
<TR><TD align="center"><input type=checkbox name=userNUM3 value='AID3'></TD><TD align="center"><font color='#005500'>USER_NAME3</font></TD></TR>
<!-- END DYNAMIC BLOCK: friend_list -->
</table>
<Br>
<table width=90%><tr><td width=20%><hr></td><td align="center"><font color="#AAAA22" size=-1>Friend offline</font></td><td width=20%><hr></td></tr></table>
<table border=0 width=90%>
<!-- BEGIN DYNAMIC BLOCK: friend_oist -->
<TR><TD align="center"><input type=checkbox name=userNUM4 value='AID4'></TD><TD align="center"><font color='#AAAA22'>USER_NAME4</font></TD></TR>
<!-- END DYNAMIC BLOCK: friend_oist -->
</table>
<Br>
<form action=./multmesg.php method = post>
<table width=90%><tr><td width=40%><hr></td><td align="center"><font color="#0000FF" size=-1>Classmates</font></td><td width=40%><hr></td></tr></table>
<table border=0 width=90%>
<!-- BEGIN DYNAMIC BLOCK: course_list -->
<TR><TD align="center"><input type=checkbox name=userNUM1 value=AID1></TD><TD align="center"><font color='#0000FF'>USER_NAME1</font></TD></TR>
<!-- END DYNAMIC BLOCK: course_list -->
</table>
<Br>
<table width=90%><tr><td width=40%><hr></td><td align="center"><font color="#FF0000" size=-1>System</font></td><td width=40%><hr></td></tr></table>
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
<input type=submit value=Send name="submit">
<input type=reset value=Clear name="reset">
<input type=button value="Close" OnClick="self.close();">
</table>
</form>
</center>
</body>
</html>
