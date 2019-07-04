<html>
<head>
<title>好友設定</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<STYLE type=text/css>
 body { scrollbar-face-color: #4d6eb2; scrollbar-arrow-color: #FFFFFF; scrollbar-track-color: #DDDDFF; scrollbar-shadow-color: #054878; scrollbar-3dlight-color: black; }
</STYLE>
</head>

<body background="/images/img/bg.gif" text="#0000FF">
<font color="#0000FF" size=-1>請輸入好友的"<font color="#FF0000" size=-1><b>風#</b></font>"<br>
好友上限LIMIT人</font>
<center>
<form method="post" action="./gbfriend.php">
<table width=100%>
<tr><td>編號</td><td>上站通知</td><td>風#</td><td>名稱</td></tr>
<!-- BEGIN DYNAMIC BLOCK: friend_list -->
<tr><td>NUM.</td><td><input type="checkbox" name="onlineNUM" value="1" CHECK></td><td><input type="text" name="friendNUM" value="FRIEND" size=10></td><td>NAME</td></tr>
<!-- END DYNAMIC BLOCK: friend_list -->
</table>
<input type="submit" name="Submit" value="更新">
<input type="reset" name="Submit2" value="清除">
<input type=button value=關閉 OnClick="self.close();">
</form>
</center>
</body>
</html>
