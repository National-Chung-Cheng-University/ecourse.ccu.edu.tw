<HTML>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
</head>
<BODY background="/images/img_E/bg.gif">
<p><img src="/images/img_E/a322.gif">
</p>
<BR>
<center>
<form ENCTYPE=multipart/form-data method=POST action=modify_work.php>
<input type=hidden name=work_id value=WORKID>
<input type=hidden name=action value=uploadstuwork>
<font color="#0000FF"><b>File Upload：</b></font><font size="2" color="#FF0000">File 1 will be the main page of the homework question.<BR>
　　　　　　　File 2 will be the main page of the homework answer.</font><BR>
file 1: <INPUT TYPE=FILE NAME=uploadfile1 SIZE=20><br>
file 2: <INPUT TYPE=FILE NAME=uploadfile2 SIZE=20><br>
<INPUT TYPE=SUBMIT VALUE=Upload_File>
<INPUT TYPE=RESET VALUE=Reset>
</form><BR>
<form ENCTYPE=multipart/form-data method=POST action=modify_work.php>
<input type=hidden name=work_id value=WORKID>
<input type=hidden name=action value=uploadothers>
<INPUT TYPE=SUBMIT VALUE=upload_more_files>
</form>
<table border="1">
<caption> File list </caption>
<tr bgcolor="#4d6be2"><td><font color=#ffffff>File Name</font><td><font color=#ffffff>Size</font>
                      <td><font color=#ffffff>Modify Date</font><td><font color=#ffffff>DELETE</font>
<!-- BEGIN DYNAMIC BLOCK: file_list -->
<tr bgcolor=F_COLOR><td><a href="FILE_LINK">FILE_N</a> <td>FILE_SIZE <td>FILE_DATE <td>DELETE
<!-- END DYNAMIC BLOCK: file_list -->
</table>
</center>
</BODY>
</HTML>
