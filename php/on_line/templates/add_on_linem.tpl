<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<title>隨選視訊</title>
<STYLE type=text/css>
	body { SCROLLBAR-FACE-COLOR: #4d6eb2; SCROLLBAR-ARROW-COLOR: #FFFFFF; SCROLLBAR-TRACK-COLOR: #DDDDFF; SCROLLBAR-SHADOW-COLOR: #054878; SCROLLBAR-3DLIGHT-COLOR: black; }
</STYLE>
</head>
<body background=/images/img/bg.gif>
<br><center>
<font size=5 color=#0000ff><b><i>隨選視訊</i></b></font>
<form name=addonline method="POST" action="./on_line.php" enctype="multipart/form-data">
<font size="4" color="#0066FF"><b>上課日期：</b><input type="text" name="date" size="40" value="DATE"></font><br>
<font color="#777777">（例如2001年7月8日，則輸入2001-07-08)</font><br><br>
<font color="#0066FF" size="4"><b>課程內容：<input type="text" name="subject" size="40" value="SUBJ"></b></font><br><br>
<font size="4" color="#0066FF"><b>課程連結：</b><input type="radio" name="style" value="link"><input type="text" name="url" size="37" value="LINK" onFocus="addonline.style[0].checked=true"></font><br>
<font size="4" color="#0066FF"><b>影像ASF檔：</b><input type="radio" name="style" value="upload"><INPUT TYPE="FILE" NAME="file" SIZE="27" onFocus="addonline.style[1].checked=true"></font><br>
<font color="#777777">請選擇欲上傳的隨選視訊檔案(asf檔)</font><br><br>
<font size="4" color="#0066FF"><b>影像URL：</b><input type="radio" name="style" value="filelink"><input type="text" name="rfile" size="37" value="RF" onFocus="addonline.style[2].checked=true"></font><br>
<font color="#777777">請寫出完整的asf檔路徑(如mms://myserver/abc.asf)</font><br><br>
<font size="4" color="#0066FF"><b>發佈影像：</b><select name="on_air"><option value="1">是</option><option value="0">否</option></select><br><br>
<input type="hidden" name="player" value="PLAYER">
<input type="submit" value="送出視訊" name="submit">　　<input type="reset" value="清除" name="Clear"><br><br>
</form>
</center>
</body>
</html>
