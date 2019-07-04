<HTML>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
</head>
<BODY background="/images/img/bg.gif">
<p><img src="/images/img/a322.gif">
</p>
<BR>
<center>
<form ENCTYPE=multipart/form-data method=POST action=modify_work.php>
<input type=hidden name=work_id value=WORKID>
<input type=hidden name=action value=uploadstuwork>
<font color="#0000FF"><b>上傳檔案：</b></font><font size="2" color="#FF0000">file 1將作為作業題目的首頁, file 2將作為作業解答的首頁.</font><BR>
file 1: <INPUT TYPE=FILE NAME=uploadfile1 SIZE=20><br>
file 2: <INPUT TYPE=FILE NAME=uploadfile2 SIZE=20><br>
<INPUT TYPE=SUBMIT VALUE=上傳檔案>
<INPUT TYPE=RESET VALUE=清除>
</form><BR>
<form ENCTYPE=multipart/form-data method=POST action=modify_work.php>
<input type=hidden name=work_id value=WORKID>
<input type=hidden name=action value=uploadothers>
<INPUT TYPE=SUBMIT VALUE=上傳更多相關檔案>
</form>
<table border="1">
<caption> 現有檔案 </caption>
<tr bgcolor="#4d6be2"><td><font color=#ffffff>檔名</font><td><font color=#ffffff>檔案大小</font>
                      <td><font color=#ffffff>最後修改日期</font><td><font color=#ffffff>刪除</font>
<!-- BEGIN DYNAMIC BLOCK: file_list -->
<tr bgcolor=F_COLOR><td><a href="FILE_LINK">FILE_N</a> <td>FILE_SIZE <td>FILE_DATE <td>DELETE 
<!-- END DYNAMIC BLOCK: file_list -->
</table>
</center>
</BODY>
</HTML>
