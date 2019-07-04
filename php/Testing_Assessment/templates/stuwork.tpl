<HTML>
<head>
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=big5">
</head>
STUWORK
<div align="center">
<form target="target" method=POST action=check_allwork.php>
<input type=hidden name=work_id value=WORKID>
<input type=hidden name=id0 value=SNO>
<input type=hidden name=action value=updateg>
<font color=#ff0000>請給分=></font><input type=text name=wgrade0 size=3>分　
<input type=submit value=確定>
</form>
<table border="1">
<caption> 作業檔案 </caption>
<tr bgcolor="#4d6be2"><td><font color=#ffffff>檔名</font><td><font color=#ffffff>檔案大小</font>
                      <td><font color=#ffffff>最後修改日期</font>
<!-- BEGIN DYNAMIC BLOCK: file_list -->
<tr bgcolor=F_COLOR><td><a href="FILE_LINK" target="_blank">FILE_N</a> <td>FILE_SIZE <td>FILE_DATE
<!-- END DYNAMIC BLOCK: file_list -->
</table>
</div>
</BODY>
</HTML>
