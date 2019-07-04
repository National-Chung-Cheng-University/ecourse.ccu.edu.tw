<html>
<head>
<title> TITLE </title>
<STYLE type=text/css>
 body { SCROLLBAR-FACE-COLOR: #4d6eb2; SCROLLBAR-ARROW-COLOR: #FFFFFF; SCROLLBAR-TRACK-COLOR: #DDDDFF; SCROLLBAR-SHADOW-COLOR: #054878; SCROLLBAR-3DLIGHT-COLOR: black; }
</STYLE>
<SCRIPT LANGUAGE="JavaScript">
<!--
function check_chgsect() {
	if(changesect.sect_title.value.length==0) {
		alert("Please input new section name.");
		return false;
	}
	else
		return true;
}
//-->
</SCRIPT>
<link rel="stylesheet" href="/images/skin1/css/main-body.css" type="text/css">
</head>
<body background="/images/img_E/bg.gif"RELOAD_CTRL>
<IMG SRC="/images/img_E/a313.gif">
<center>
<font color="red">ERROR_MSG<br>
You are editing Chapter CHAP_NUM,Section SECT_NUM now.</font>
<form action="section.php" method="post" name="changesect" onSubmit="return check_chgsect();">
Change Chapter CHAP_NUM,Section SECT_NUM's Title to

<table border=1>
<tr bgcolor=#cdeffc>
	<td><input type="text" name="sect_title" size=30></td></tr>
</table>

<input type="submit" name="submit">
<input type="reset" name="reset">
<input type="hidden" name="action" value="1">
<input type="hidden" name="chap_num" value="CHAP_NUM">
<input type="hidden" name="sect_num" value="SECT_NUM">
<input type="hidden" name="return_type" value="1">
</form>
<hr>
<form name="htmlcontent" action="htmlgen.php" method="post" onsubmit="return confirm('This action will replace current content. Do you wish to continue?');">

<table border=1 bgcolor=#cdeffc>
<tr>
	<td>Please input content of this section's main page.</td></tr>
<tr>
	<td><textarea cols=70 rows=6 name="content" wrap="virtual">
HTML_CONTENT</textarea></td></tr>
</table>

<input type="hidden" name="html_type" value=3>
<input type="hidden" name="chap_num" value=CHAP_NUM>
<input type="hidden" name="sect_num" value=SECT_NUM>
<input type="submit" name="submit" value="update page's content">
<input type="reset" name="reset" value="reset">
</form>
<form action="preview.php" name="preview" method="post" onsubmit="preview.content.value=htmlcontent.content.value;">
<input type="hidden" name="basehref" value=BASEHREF>
<input type="hidden" name="content" value="">
<input type="submit" value="result preview" onClick="return confirm('Please finish updating the page first before use this function.');">
</form>
<form action="section.php" method="post" onsubmit="return confirm('This action will delete everything about this section(htmlpages and browsing records).\nDo you wish to continue?');">
<input type="hidden" name="action" value="2">
<input type="hidden" name="chap_num" value="CHAP_NUM">
<input type="hidden" name="sect_num" value="SECT_NUM">
<input type="submit" value="Delete this section">
</form>
<hr>
<form action="file.php" method="post" enctype="multipart/form-data">

<table border=1" bgcolor=#cdeffc>
<tr>
	<td>Upload file which has relationship with [Chapter CHAP_NUM,Section SECT_NUM] to[/textbook/CHAP_NUM/SECT_NUM] .<br>If you want to replace main page, please upload file named [index.html].</td></tr>
<tr>
	<td><input type="file" name="newfile">
<input type="hidden" name="editor" value="1">
<input type="hidden" name="action" value="upload">
<input type="hidden" name="chap_num" value="CHAP_NUM">
<input type="hidden" name="sect_num" value="SECT_NUM">
<input type="submit" name="submit" value="Upload"></td></tr>
</table>

</form>

<table border="1">
<caption> File(s) under current directory </caption>
<tr bgcolor="#4d6be2">
	<td><font color=#ffffff>Filename</font>
	<td><font color=#ffffff>Filesize</font>
	<td><font color=#ffffff>Date</font>
	<td><font color=#ffffff>Delete</font></td></tr>
<!-- BEGIN DYNAMIC BLOCK: file_list -->
<tr bgcolor=F_COLOR>
	<td><a href="FILE_LINK">FILE_N</a> 
	<td>FILE_SIZE 
	<td>FILE_DATE 
	<td><a href="DEL_FILE?filename=FILE_DEL" onclick="return confirm('Are u sure to delete this file?');">Delete file</a></td></tr>
<!-- END DYNAMIC BLOCK: file_list -->
</table>
</center>
</body>
</html>