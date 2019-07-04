<html>
<head>
<title> TITLE </title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<STYLE type=text/css>
 body { SCROLLBAR-FACE-COLOR: #4d6eb2; SCROLLBAR-ARROW-COLOR: #FFFFFF; SCROLLBAR-TRACK-COLOR: #DDDDFF; SCROLLBAR-SHADOW-COLOR: #054878; SCROLLBAR-3DLIGHT-COLOR: black; }
</STYLE> 
<SCRIPT LANGUAGE="JavaScript">
<!--
function check_rename( oldfile, session_id  ) {
	
	var newfile = prompt("Please input new filename", oldfile);
	
	if( newfile == null ) {
		return false;
	}
	else if( (newfile.indexOf("/")!=-1) || (newfile.indexOf("\\")!=-1) ) {

		alert("Filename error.");
		return false;
	}
	else if( (newfile==null) || (oldfile==newfile) ) {

		alert("Filename unchanged.");
		return false;
	}
	else {
		location.href = "file.php?PHPSESSID=" + session_id + "&oldfile=" + oldfile + "&newfile=" + newfile + "&action=rename";
	}
}
//-->
</SCRIPT>
<link rel="stylesheet" href="/images/skin1/css/main-body.css" type="text/css">
</head>
<body background="/images/img_E/bg.gif">
<IMG SRC="/images/img_E/a312.gif">
<center>
<font color="red">ERROR_MSG</font><br>
If the file belongs to&nbsp;[Chapter 1]&nbsp;, Please upload to [&nbsp;/textbook/1/&nbsp;] .<br>
If the file belongs to&nbsp;[Chapter 1, Section 1]&nbsp;, Please upload to [&nbsp;/textbook/1/1/&nbsp;] .<br>
Please make sure your first page name of course is <font color='red'>index.html or index.htm</font>, and upload it under [&nbsp;/textbook/&nbsp;]
<form method="post" action="file.php" enctype="multipart/form-data">
<input type='hidden' name='action' value='upload'>
<table border="1">
<caption> Current Directory is [&nbsp;<font color='red'>CURRENT_DIR</font>&nbsp;] .</caption>
<tr bgcolor=#cdeffc><td colspan="3">Upload File
<tr bgcolor=#bcd2ee><td>Select File<td><input type="file" name="newfile"><td><input type="submit" name="submit" value="Upload"></tr>
</form>
<form method="post" action="file.php" onSubmit="return !(newdir.value.length==0);">
<input type='hidden' name='action' value='credir'>
<tr bgcolor=#cdeffc><td colspan="3">Create New Directory
<tr bgcolor=#bcd2ee><td>New Directory Name<td><input type="text" name="newdir"><td><input type="submit" name="submit" value="Create"></tr>
</form>
<form method="post" action="file.php">
<input type='hidden' name='action' value='chgdir'>
<tr bgcolor=#cdeffc><td colspan="3">Change Current Directory
<tr bgcolor=#bcd2ee><td>Select Directory
    <td><select name="chgdir">
<!-- BEGIN DYNAMIC BLOCK: directory_lista -->
    <option value="NAMEA">DIRNA</option>
<!-- END DYNAMIC BLOCK: directory_lista -->
    <td><input type="submit" name="submit" value="Change"></tr>
</form>
<form method="post" action="file.php" onsubmit="return confirm('Do you really want to delete this directory and everything under it?');">
<input type='hidden' name='action' value='deldir'>
<tr bgcolor=#cdeffc><td colspan="3">Delete Directory
<tr bgcolor=#bcd2ee><td>Select Directory
    <td><select name="deldir">
<!-- BEGIN DYNAMIC BLOCK: directory_listb -->
    <option value="NAMEB">DIRNB</option>
<!-- END DYNAMIC BLOCK: directory_listb -->
    <td><input type="submit" name="submit" value="Delete"></tr>
</form>
</table>
<hr>
<table border="1">
<caption> File(s) under current directory </caption>
<tr bgcolor="#4d6be2">
	<td><font color=#ffffff>Filename</font>
	<td><font color=#ffffff>Filesize</font>
	<td><font color=#ffffff>Date</font>
	<td><font color=#ffffff>Delete</font>
<!-- BEGIN DYNAMIC BLOCK: file_list -->
<tr bgcolor=F_COLOR>
	<td><a href="FILE_LINK">FILE_N</a>
	<td>FILE_SIZE
	<td>FILE_DATE
	<td><a href="file.php?action=delete&filename=FILE_N" onclick="return confirm('Are u sure to delete this file?');">Delete file</a>
	<td><input type="button" value="Rename" onClick="return check_rename( 'FILE_N', 'PHP_ID' );">
<!-- END DYNAMIC BLOCK: file_list -->
</table>
</center>
</body>
</html>
