<HTML>
<head>
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=big5">
</head>
STUWORK
<div align="center">
<form target="target" method=POST action=check_allwork.php>
<input type=hidden name=work_id value=WORKID>
<input type=hidden name=sid value=SNO>
<input type=hidden name=action value=updateg>
<font color=#ff0000>Input Score=></font><input type=text name=wgrade size=3>point　
<input type=submit value=Sure>
</form>
<table border="1">
<caption> File list </caption>
<tr bgcolor="#4d6be2"><td><font color=#ffffff>File Name</font><td><font color=#ffffff>Size</font>
                      <td><font color=#ffffff>Modify Date</font><td><font color=#ffffff>Delete File</font>
<!-- BEGIN DYNAMIC BLOCK: file_list -->
<tr bgcolor=F_COLOR><td><a href="FILE_LINK" target="_blank">FILE_N</a> <td>FILE_SIZE <td>FILE_DATE <td><a href="./show_allwork.php?action=del&filename=FILE_N&work_id=WORKID" onclick="return confirm('Sure to Delete?');">Delete</a>
<!-- END DYNAMIC BLOCK: file_list -->
</table>
</div>
</BODY>
</HTML>
