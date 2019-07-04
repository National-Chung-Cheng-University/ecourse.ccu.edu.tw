<html>
<head>
<title> TITLE </title>
<STYLE type=text/css>
 body { SCROLLBAR-FACE-COLOR: #4d6eb2; SCROLLBAR-ARROW-COLOR: #FFFFFF; SCROLLBAR-TRACK-COLOR: #DDDDFF; SCROLLBAR-SHADOW-COLOR: #054878; SCROLLBAR-3DLIGHT-COLOR: black; }
</STYLE> 
<SCRIPT LANGUAGE="JavaScript">
<!--
function check_new_section() {
	var error = true;
	var msg = 'Please input';
	if( (newsection.sect_num.value.length==0) || ( isNaN( parseInt(newsection.sect_num.value) ) ) ) {
		error = false;
		msg = msg + " 'section number'";
	}

	if(newsection.sect_title.value.length==0)	{
		error = false;
		msg = msg + " 'section title'";
	}
	
	if(!error) {
		alert(msg);
	}
	return error;
}

function check_chgchap() {
	if(changechap.chap_title.value.length==0)	{
		alert("Please input new chapter name.");
		return false;
	}
	else
		return true;
}

function check_del_section( chap_num, sect_num ) {
	if( confirm('This action will delete everything about this section(htmlpages and browsing records).\nDo you wish to continue?') ) {
		location.href = "section.php?sect_num=" + sect_num+ "&chap_num=" + chap_num + "&action=2&PHPSESSID=PHP_ID";
		return true;
	}
	else
		return false;
}
//-->
</SCRIPT>
<link rel="stylesheet" href="/images/skin1/css/main-body.css" type="text/css">
</head>
<body background="/images/img_E/bg.gif"RELOAD_CTRL>
<IMG SRC="/images/img_E/a313.gif">
<center>
<font color="red">ERROR_MSG<br>
You are editing &nbsp;&lt;Chapter CHAP_NUM&gt;&nbsp;of this course now.</font>
<form action="chap.php" method="post" name="changechap" onSubmit="return check_chgchap();">

<table border=1>
<caption>Change &nbsp;&lt;Chapter CHAP_NUM's&gt;&nbsp; Title to</caption>
<tr bgcolor=#cdeffc>
	<td><input type="text" name="chap_title" size=30></td></tr>
</table>

<input type="submit" name="submit"><input type="reset" name="reset">
<input type="hidden" name="action" value=1>
<input type="hidden" name="PHPSESSID" value="PHP_SID">
<input type="hidden" name="chap_num" value="CHAP_NUM">
<input type="hidden" name="return_type" value="1">
</form>
<hr>
<form action="section.php" method="post" name="newsection" onSubmit="return check_new_section();">

<table border=1>
<caption>Before edit content of any section, please input title of any section here.
		<br>Input a existing section number and title will update current data.</caption>
<tr bgcolor=#4d6be2>
	<td><font color=#ffffff>Section Number</font>
	<td><font color=#ffffff>Section Title</font>
	<td></td></tr>
<!-- BEGIN DYNAMIC BLOCK: sect_list -->
<tr bgcolor=ED_COLOR>
	<td>SECT_NUM
	<td>SECT_TITLE
	<td><input type="button" value="§R°£" OnClick="return check_del_section('CHAP_NUM','SECT_NUM');"></td></tr>
<!-- END DYNAMIC BLOCK: sect_list -->
<tr bgcolor=#cdeffc>
	<td>Section Number<input type="text" name="sect_num" size=4>
	<td>Section Title<input type="text" name="sect_title" size=50>
	<td></td></tr>
</table>

<input type="hidden" name="action" value=1>
<input type="hidden" name="PHPSESSID" value="PHP_SID">
<input type="hidden" name="chap_num" value="CHAP_NUM">
<input type="submit" name="submit" value="add/update title">
<input type="reset" name="reset" value="reset">
</form>
<hr>
<form name="htmlcontent" action="htmlgen.php" method="post" onsubmit="return confirm('This action will replace current content.\nDo you wish to continue?');">

<table border=1 bgcolor=#cdeffc>
<tr>
	<td>Please input content of this chapter's main page.</td></tr>
<tr>
	<td><textarea cols=70 rows=6 name="content" wrap="virtual"></td></tr>
HTML_CONTENT</textarea>
</table>

<input type="hidden" name="html_type" value=2>
<input type="hidden" name="chap_num" value=CHAP_NUM>
<input type="hidden" name="PHPSESSID" value="PHP_SID">
<input type="submit" name="submit" value="update page's content">
<input type="reset" name="reset" value="reset">
</form>
<form action="preview.php" name="preview" method="post" onsubmit="preview.content.value=htmlcontent.content.value;">
<input type="hidden" name="basehref" value=BASEHREF>
<input type="hidden" name="content" value="">
<input type="submit" value="result preview" onClick="return confirm('Please finish updating the page first before use this function.');">
</form>
<form action="chap.php" method="post" onsubmit="return confirm('This action will delete everything about this chapter(htmlpages, every section of it, and browsing records).\nDo you wish to continue?');">
<input type="hidden" name="action" value=2>
<input type="hidden" name="chap_num" value="CHAP_NUM">
<input type="submit" value="Delete this chapter">
</form>
<hr>
<form action="file.php" method="post" enctype="multipart/form-data">

<table border=1" bgcolor=#cdeffc>
<tr>
	<td>Upload file which has relationship with [Chapter CHAP_NUM] to [/textbook/CHAP_NUM] .<br>
If you want to replace main page, please upload file named [index.html].</td></tr>
<tr>
	<td><input type="file" name="newfile">
<input type="hidden" name="editor" value="1">
<input type="hidden" name="action" value="upload">
<input type="hidden" name="chap_num" value="CHAP_NUM">
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
	<td><a href="file.php?action=delete&filename=FILE_DEL" onclick="return confirm('Are u sure to delete this file?');">Delete file</a></td></tr>
<!-- END DYNAMIC BLOCK: file_list -->
</table>

</center>
</body>
</html>