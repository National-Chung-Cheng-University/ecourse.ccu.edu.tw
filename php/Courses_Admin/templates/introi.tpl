<Html>
<Head>
<Title>TITLE</Title>
<STYLE type=text/css>
	BODY { SCROLLBAR-FACE-COLOR: #4d6eb2; SCROLLBAR-ARROW-COLOR: #FFFFFF; SCROLLBAR-TRACK-COLOR: #DDDDFF; SCROLLBAR-SHADOW-COLOR: #054878; SCROLLBAR-3DLIGHT-COLOR: black; }
.style1 {color: #FF0000}
</STYLE><meta http-equiv="Content-Type" content="text/html; charset=big5">

HEAD

</Head>
<body background=/images/skin1/bbg.gif>


<br><center>輸入區

TPL_REPLACE_Form_FormType

TPL_REPLACE_Form_Content

<hr>

<table width = 75% border=0><tr>
  <td>
上傳之檔案的附檔名需為<font color="#FF0000">"htm"</font>、<font color="#FF0000">"html"</font>、<font color="#FF0000">"doc"</font>、<font color="#FF0000">"pdf"</font>、<font color="#FF0000">"ppt"</font>這幾種格式，方可顯示。<br>
如欲使用本系統提供之格式，請將目錄中之<font color=#ff0000> index.html"刪除" </font>，
並將資料直接輸入於<font color=#ff0000>輸入區。<br>
(請尊重智慧財產權，不得非法影印教師指定之教科書籍)</font>
</tr></td>
</table>

<form method=post action="./intro.php" enctype="multipart/form-data">
<INPUT TYPE="FILE" NAME="file" SIZE="32">
<input type=hidden name="upload"  value="1">
<input type=submit value=上傳檔案 ><input type=reset value=清除>
</form>

<table border="1">
<caption> 現有檔案 </caption>
<tr bgcolor="#4d6be2"><td><font color=#ffffff>檔名</font><td><font color=#ffffff>檔案大小</font>
                      <td><font color=#ffffff>最後修改日期</font><td><font color=#ffffff>刪除檔案</font>

<!-- BEGIN DYNAMIC BLOCK: file_list -->
<tr bgcolor=F_COLOR><td><a href="FILE_LINK">FILE_N</a> <td>FILE_SIZE <td>FILE_DATE <td>DELETE
<!-- END DYNAMIC BLOCK: file_list -->
</table>
</center>


</body></html>
