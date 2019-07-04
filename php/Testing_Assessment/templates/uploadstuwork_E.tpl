<HTML>
<head>
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=big5">
</head>
<BODY background="/images/img_E/bg.gif">
<p><img src="/images/img_E/b41.gif">
<p>
<BR>
<center>
<form ENCTYPE=multipart/form-data method=POST action=show_allwork.php>
<input type=hidden name=work_id value=WORKID>
<input type=hidden name=action value=uploadstuwork>
<font color="#0000FF"><b>File Upload：</b></font><font size="2" color="#FF0000">This file will be the main page of your homework answer.</font><BR>
file : <INPUT TYPE=FILE NAME=uploadfile1 SIZE=20><br>
<INPUT TYPE=SUBMIT VALUE=Upload_File>
<INPUT TYPE=RESET VALUE=Reset>
</form><BR>
<form ENCTYPE=multipart/form-data method=POST action=show_allwork.php>
<input type=hidden name=work_id value=WORKID>
<input type=hidden name=action value=uploadothers>
<INPUT TYPE=SUBMIT VALUE=Upload_More_Files>
</form>
</BODY>
</HTML>
