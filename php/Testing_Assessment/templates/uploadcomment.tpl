<HTML>
<head>
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=big5">
</head>
<body background=/images/skinSKINNUM/bbg.gif>
<p><img src="/images/img/a312.gif">
</p>
<BR>
<center>
<form ENCTYPE=multipart/form-data method=POST action=post_comment.php>
<input type=hidden name=work_id value=WORKID>
<input type=hidden name=sid value=SNO>
<input type=hidden name=action value=uploadstucomment>
<font color="#0000FF"><b>�W���ɮסG</b></font><BR>
file 1: <INPUT TYPE=FILE NAME=file SIZE=20><br>
<INPUT TYPE=SUBMIT VALUE=�W���ɮ�>
<INPUT TYPE=RESET VALUE=�M��>
</form><BR>
<form ENCTYPE=multipart/form-data method=POST action=post_comment.php>
<input type=hidden name=work_id value=WORKID>
<input type=hidden name=sid value=SNO>
<input type=hidden name=action value=upload_comment_others>
<INPUT TYPE=SUBMIT VALUE=�W�ǧ�h�����ɮ�>
</form>
<table border="1">
<caption> �{���ɮ� </caption>
<tr bgcolor="#4d6be2"><td><font color=#ffffff>�ɦW</font><td><font color=#ffffff>�ɮפj�p</font>
                      <td><font color=#ffffff>�̫�ק���</font><td><font color=#ffffff>�R��</font>
<!-- BEGIN DYNAMIC BLOCK: file_list -->
<tr bgcolor=F_COLOR><td><a href="FILE_LINK">FILE_N</a> <td>FILE_SIZE <td>FILE_DATE <td>DELETE 
<!-- END DYNAMIC BLOCK: file_list -->
</table>
<br>
<input type="button" value="�W�@��" onclick='location.href="check_allwork.php?work_id=WORKID&action=checkstudent&PHPSESSID=PSSID&anchor=DEST";'>
</center>
</BODY>
</HTML>
