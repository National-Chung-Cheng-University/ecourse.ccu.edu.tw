<HTML>
<head>
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=big5">
</head>
<BODY background="/images/img/bg.gif">
<p><img src="/images/img/b41.gif">
<p>
<BR>
<center>
<form ENCTYPE=multipart/form-data method=POST action=show_allwork.php>
<input type=hidden name=work_id value=WORKID>
<input type=hidden name=action value=uploadstuwork>
<font color="#0000FF"><b>上傳檔案：</b></font><font size="2" color="#FF0000">此檔將作為您作業解答的首頁.上傳後檔名會自動轉換成「學號_亂數(6碼).副檔名」，例如：987654321_6d274f.txt</font><BR>
file : <INPUT TYPE=FILE NAME=uploadfile1 SIZE=20><br>
<INPUT TYPE=SUBMIT VALUE=上傳檔案>
<INPUT TYPE=RESET VALUE=清除>
</form><BR>
<form ENCTYPE=multipart/form-data method=POST action=show_allwork.php>
<input type=hidden name=work_id value=WORKID>
<input type=hidden name=action value=uploadothers>
<INPUT TYPE=SUBMIT VALUE=上傳更多相關檔案>
</form>
</BODY>
</HTML>
