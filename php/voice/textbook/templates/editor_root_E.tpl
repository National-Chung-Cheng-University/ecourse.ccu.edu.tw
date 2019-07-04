<html>
<head>
<title> TITLE </title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<STYLE type=text/css>
 body { SCROLLBAR-FACE-COLOR: #4d6eb2; SCROLLBAR-ARROW-COLOR: #FFFFFF; SCROLLBAR-TRACK-COLOR: #DDDDFF; SCROLLBAR-SHADOW-COLOR: #054878; SCROLLBAR-3DLIGHT-COLOR: black; }
</STYLE> 
<SCRIPT LANGUAGE="JavaScript">
<!--
function check_new_chapter() {
	var error = true;
	var msg = 'Please input';
	if( (newchap.chap_num.value.length==0) || ( isNaN(parseInt(newchap.chap_num.value) ) ) ) {
		error = false;
		msg = msg + " 'chapter number'";
	}

	if(newchap.chap_title.value.length==0) {
		error = false;
		msg = msg + " 'chapter title'";
	}
	
	if(!error) {
		alert(msg);
	}
	return error;
}

function check_del_chapter( chap_num ) {
	if( confirm('This action will replace current content.\nDo you wish to continue?') ) {
		location.href = "chap.php?action=2&chap_num=" + chap_num + "&PHPSESSID=PHP_ID";
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
You are editing &nbsp;&lt;introduce&gt;&nbsp; of this course now.
</font>
<form action="chap.php" method="post" name="newchap" onSubmit="return check_new_chapter();">

<table border=1>
<caption>Before edit content of any chapter, please input &nbsp;&lt;chapter's title&gt;&nbsp; here.
		<br>Input a existing chapter number and title will update current data.</caption>
<tr bgcolor=#4d6be2>
	<td><font color=#ffffff>Chapter Number</font>
	<td><font color=#ffffff>Chapter Title</font>
	<td></td></tr>
<!-- BEGIN DYNAMIC BLOCK: chap_list -->
<tr bgcolor=ED_COLOR>
	<td>CHAP_NUM
	<td>CHAP_TITLE
	<td><input type="button" value="Delete" OnClick="return check_del_chapter('CHAP_NUM');"></td></tr>
<!-- END DYNAMIC BLOCK: chap_list -->
<tr bgcolor=#cdeffc>
	<td>Chapter Number<input type="text" name="chap_num" size=4>
	<td>Chapter Title<input type="text" name="chap_title" size=50>
	<td></td></tr>
</table>

<input type="hidden" name="action" value=1>
<input type="submit" name="submit" value="add/update title"><input type="reset" name="reset" value="reset">
</form>
<hr>
<form name="htmlcontent" action="htmlgen.php" method="post" onsubmit="return confirm('This action will replace current content. Do you wish to continue?');">

<table border=1 bgcolor=#cdeffc>
<tr>
	<td>Please input the introduce of this course.</td></tr>
<tr>
	<td><textarea cols=70 rows=6 name="content" wrap="virtual">
HTML_CONTENT</textarea></td></tr>
</table>

<input type="hidden" name="html_type" value=1>
<input type="submit" name="submit" value="update page's content">
<input type="reset" name="reset" value="reset">
</form>
<form action="preview.php" name="preview" method="post" onsubmit="preview.content.value=htmlcontent.content.value;">
<input type="hidden" name="basehref" value=BASEHREF>
<input type="hidden" name="content" value="">
<input type="submit" value="result preview" onClick="return confirm('Please finish updating the page first before use this function.');">
</form>
<hr>
<form action="validated.php" name="validated" method="post">

<table border="1" bgcolor=#cdeffc>
<input type="hidden" name="validated" value="VAL_VALUE">
<caption>Guest user audit permission</caption>
<tr bgcolor=#4d6be2>
	<td><font color="white">Current Status</font>
	<td><font color="white">Control</font></td></tr>
<tr>
	<td>VAL_STATUS
	<td><input type="submit" name="submit" value="Enable/Disable Guest user audit permission"></td></tr>
</table>

</form>
<hr>
<form action="file.php" method="post" enctype="multipart/form-data">

<table border=1" bgcolor=#cdeffc>
<tr>
	<td>Upload file which has relationship with [introduce] to [/textbook/] .<br>
If you want to replace main page, please upload file named [index.html].</td></tr>
<tr>
	<td><input type="file" name="newfile">
<input type="hidden" name="editor" value="1">
<input type="hidden" name="action" value="upload">
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
