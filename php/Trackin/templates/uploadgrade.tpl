<HTML>
<head>
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css">
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=big5">
</head>
<BODY background="/images/img/bg.gif">
<p>
<BODY>
<center>
<table border=0 bordercolor="#0000FF">
<tr><td><font color="#0000FF"><b>上傳成績步驟：</b></font>瀏覽檔案→上傳檔案→<font color="#FF0000">（請確認成績無誤後）</font>點選“成績上傳及列印學期成績單”</td></tr>
</table>
<form ENCTYPE=multipart/form-data method=POST action="GOPAGE">
<input type=hidden name=action value=uploadgrade>
<input type=hidden name=course value="CID">
<font color="#0000FF"><b>上傳檔案：</b></font><BR>
File : <INPUT TYPE=FILE NAME=uploadfile1 SIZE=20><br>
<INPUT TYPE=SUBMIT VALUE=上傳檔案>
<INPUT TYPE=RESET VALUE=清除>
</form><BR>
<table border=3 bordercolor=red>
<tr><td colspan=5><center><font size=5 color=red><b>注意事項</b></font></center></td></tr>
<tr><td colspan=5><font size=4 color=blue><b>1.	上傳的檔案必須是Excel檔案。</b></font><br>
				  <font size=4 color=blue><b>2.	Excel檔案的欄位必須符合下列格式。</b></font><br>
											   (1) 第一個欄位必須是"學號"<br/>
											   (2) 第二個欄位必須是"姓名"<br/>
											   (3) 最後一個欄位必須是"總成績"<br/>
				  
</td></tr>
</table>
<br>Excel檔案範例：<br/>
<table border=1 bordercolor="#666666">
<tr><td><font color=red>學號</font></td><td><font color=red>姓名</font></td><td>...</td><td>...</td><td>...</td><td><font color=red>總成績</font></td></tr>
<tr><td>6944100XX</td><td>XXX</td><td>...</td><td>...</td><td>...</td><td>90</td></tr>
<tr><td>6944100OO</td><td>OOO</td><td>...</td><td>...</td><td>...</td><td>100</td></tr>
<tr><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td></tr>
</table>
</BODY>
</HTML>
