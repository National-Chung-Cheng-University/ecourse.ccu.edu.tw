<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<title>Vedio Material</title>
<STYLE type=text/css>
	body { SCROLLBAR-FACE-COLOR: #4d6eb2; SCROLLBAR-ARROW-COLOR: #FFFFFF; SCROLLBAR-TRACK-COLOR: #DDDDFF; SCROLLBAR-SHADOW-COLOR: #054878; SCROLLBAR-3DLIGHT-COLOR: black; }
</STYLE>
</head>
<body background="/images/img_E/bg.gif">
<br><center>
<font size=5 color=#0000ff><b><i>Vedio Material</i></b></font>
<form name=addonline method="POST" action="./on_line.php" enctype="multipart/form-data">
<font size="4" color="#0066FF"><b>　　　　Date：</b><input type="text" name="date" size="40" value="DATE"></b></font><br>
<font color="#777777">（Ex.2001/7/8/，Please input like this :2001-07-08)</font><br><br>
<font color="#0066FF" size="4"><b>　　　Content：<input type="text" name="subject" size="40" value="SUBJ"></b></font><br><br>
<font size="4" color="#0066FF"><b>　　　　 Link：<input type="radio" name="style" value="link"><input type="text" name="url" size="37" value="LINK" onFocus="addonline.style[0].checked=true"></b></font><br>
<font size="4" color="#0066FF"><b>RM Vedio File：</b><input type="radio" name="style" value="upload"><INPUT TYPE="FILE" NAME="file" SIZE="29" onFocus="addonline.style[1].checked=true"></font><br>
<font color="#777777">Please Choise the Vedio File(rm File)</font><br><br>
<font size="4" color="#0066FF"><b>Vedio URL Link：</b><input type="radio" name="style" value="filelink"><input type="text" name="rfile" size="36" value="RF" onFocus="addonline.style[2].checked=true"></font><br>
<font color="#777777">Please Input the Correct Path to The rm File on the Net(Ex.pnm://myserver/abc.rm)</font><br><br>
<font size="4" color="#0066FF"><b>ON AIR：</b><select name="on_air"><option value="1">Yes</option><option value="0">No</option></select><br><br>
<input type="hidden" name="player" value="PLAYER">
<input type="submit" value="Send" name="submit">　　<input type="reset" value="Clear" name="Clear"><br><br>
</form>
</center>
</body>
</html>
