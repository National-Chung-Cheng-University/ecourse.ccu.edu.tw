<html>
<head>
<title>Friend List</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<STYLE type=text/css>
 body { scrollbar-face-color: #4d6eb2; scrollbar-arrow-color: #FFFFFF; scrollbar-track-color: #DDDDFF; scrollbar-shadow-color: #054878; scrollbar-3dlight-color: black; }
</STYLE>
</head>

<body background="/images/img_E/bg.gif" text="#0000FF">
<font color="#0000FF" size=-1>Please Input the"<font color="#FF0000" size=-1><b>PHO#</b></font>"<br>
You only can add LIMIT Friend</font>
<center>
<form method="post" action="./gbfriend.php">
<table width=100%>
<tr><td>Num</td><td>online alarm</td><td>PHO#</td><td>Name</td></tr>
<!-- BEGIN DYNAMIC BLOCK: friend_list -->
<tr><td>NUM.</td><td><input type="checkbox" name="onlineNUM" value="1" CHECK></td><td><input type="text" name="friendNUM" value="FRIEND" size=10></td><td>NAME</td></tr>
<!-- END DYNAMIC BLOCK: friend_list -->
</table>
<input type="submit" name="Submit" value="Update">
<input type="reset" name="Submit2" value="Clear">
<input type=button value=Close OnClick="self.close();">
</form>
</center>
</body>
</html>