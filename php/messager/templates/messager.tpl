<html>
<head>
<title>訊息欄</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<STYLE type=text/css>
 body { scrollbar-face-color: #4d6eb2; scrollbar-arrow-color: #FFFFFF; scrollbar-track-color: #DDDDFF; scrollbar-shadow-color: #054878; scrollbar-3dlight-color: black; }
</STYLE>
<script language="JavaScript">
function chface ( path ) {
	document.all.face.src = "/images/face/"+path;
}
HAVEalert("您有訊息進入!!");
CLOSEself.close();
</script>
</head>
<body background=/images/img/bg.gif>
<center>
<table width=90%>
<tr><td align=left>
BACK
</td></tr>
</table>

<form action=./messager.php method = post>
<input type=hidden name=user value="AID">
<table width=90%>
<tr><td>送訊息給:<font color="#0000FF"><b>USER_NAME</b></font></td></tr>
<tr><td><input type=submit value=傳送 name="submit"><input type=reset value=清除 name="reset"><input type=button value=關閉 OnClick="self.close();">
表情符號<select onChange="chface( this.options[this.selectedIndex].value );">
<option value="1.gif" >:)</option>
<option value="2.gif" >:d</option>
<option value="3.gif" >:o</option>
<option value="4.gif" >:p</option>
<option value="5.gif" >:@</option>
<option value="6.gif" >:s</option>
<option value="7.gif" >:$</option>
<option value="8.gif" >:(</option>
<option value="9.gif" >:'(</option>
<option value="10.gif" >:|</option>
<option value="11.gif" >(i)</option>
<option value="12.gif" >(l)</option>
<option value="13.gif" >(k)</option>
<option value="14.gif" >(ll)</option>
</select>：<img name=face src="/images/face/1.gif" >
</td></tr>
<tr><td><textarea name=message rows=10 cols=35 >MESSAGE
</textarea></td></tr>
</table>
</form>
</center>
</body>
</html>