<Html>
<Head>
<Title>TITLE</Title>
<STYLE type=text/css>
	BODY { SCROLLBAR-FACE-COLOR: #4d6eb2; SCROLLBAR-ARROW-COLOR: #FFFFFF; SCROLLBAR-TRACK-COLOR: #DDDDFF; SCROLLBAR-SHADOW-COLOR: #054878; SCROLLBAR-3DLIGHT-COLOR: black; }
</STYLE><meta http-equiv="Content-Type" content="text/html; charset=big5"> 
HEAD
</Head>
<body background="/images/img_E/bg.gif">
<br><center>Content

TPL_REPLACE_Form_FormType

TPL_REPLACE_Form_Content

<!--
<form action="./intro.php" method=post>
<textarea name=intro  rows="10" cols="60">MES</textarea><br>
<input type=submit value=Submit><input type=reset value=Clear>
</form>
-->
<hr>

<table width = 75% border=0><tr><td>
The Upload File's Type could be as <font color=#ff0000>"htm"</font>、<font color=#ff0000>"html"</font>、<font color=#ff0000>"doc"</font>、<font color=#ff0000>"pdf"</font>，
If you want to use the style of this system，Please <font color=#ff0000>Delete index.html </font>in this directory，
and input data into <font color=#ff0000>Content</font>
</tr></td>
</table>
<form method=post action="./intro.php" enctype="multipart/form-data">
<INPUT TYPE="FILE" NAME="file" SIZE="32">
<input type=hidden name="upload"  value="1">
<input type=submit value=Upload ><input type=reset value=Clear>
</form>
<table border="1">
<caption> File List </caption>
<tr bgcolor="#4d6be2"><td><font color=#ffffff>File Name</font><td><font color=#ffffff>File Size</font>
                      <td><font color=#ffffff>Modify Date</font><td><font color=#ffffff>Del File</font>
<!-- BEGIN DYNAMIC BLOCK: file_list -->
<tr bgcolor=F_COLOR><td><a href="FILE_LINK">FILE_N</a> <td>FILE_SIZE <td>FILE_DATE <td>DELETE
<!-- END DYNAMIC BLOCK: file_list -->
</table>
</center>
</body></html>
