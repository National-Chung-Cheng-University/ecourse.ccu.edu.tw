<Html>
<Head>
<Title>TITLE</Title>
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css">
</Head>
<body background=/images/img/bg.gif>
<br><center>輸入區<br>
<font color=#ff0000>ERR</font>
<form action="./result.php" method=post>
<input type=hidden name=action value=post>
<textarea name=info rows="10" cols="60">MES</textarea><br>
<input type=submit value=確定><input type=reset value=清除>
</form>
<hr>
<table width = 75% border=0><tr><td>
上傳之檔案名稱請使用 <font color=#ff0000>"index.html"</font> 取名方可顯示，
如欲使用本系統提供之格式，請將目錄中之<font color=#ff0000> index.html"刪除" </font>，
並將資料直接輸入於<font color=#ff0000>輸入區</font>
</tr></td>
</table>
<form method=post action="./result.php" enctype="multipart/form-data">
<INPUT TYPE="FILE" NAME="file" SIZE="32">
<input type=hidden name="action" value="upload">
<input type=submit value=上傳檔案 ><input type=reset value=清除>
</form>
<table border="1">
<tr bgcolor="#000066"><td><font color=#ffffff>現在狀態</font></td><td><font color=#ffffff>切換</font></td>
<tr><td>STATUS</td>
<form method=post action="./result.php">
<td>
<input type="hidden" NAME="action" value="share">
<input type=submit value=BUTTOM >
</td>
</form>
</tr>
</table><br>
<table border="1">
<caption> 現有檔案 </caption>
<tr bgcolor="#000066"><td><font color=#ffffff>檔名</font></td><td><font color=#ffffff>檔案大小</font></td>
                      <td><font color=#ffffff>最後修改日期</font></td><td><font color=#ffffff>刪除檔案</font></td>
</tr>
<!-- BEGIN DYNAMIC BLOCK: file_list -->
<tr bgcolor=F_COLOR><td><a href="FILE_LINK">FILE_N</a> <td>FILE_SIZE <td>FILE_DATE <td>DELETE
<!-- END DYNAMIC BLOCK: file_list -->
</table>
</center>
</body></html>