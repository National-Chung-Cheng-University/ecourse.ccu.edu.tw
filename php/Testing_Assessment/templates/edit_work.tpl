<HTML>
<head>
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=big5">
</head>
<center><font color="#FF0000">MESSAGE</font><p></center>
<form method="POST" action="ACT1">
<center>
作業名稱：WORKNAME<input type="hidden" name="work_name" value="WORKNAME"><br>
請輸入繳交期限：<font color=#000080>WORKDUE</font><input type="hidden" name="work_due" value="WORKDUE"><br> 
配分：WORKRATIO<input type="hidden" name="work_ratio" value="WORKRATIO"><br>
<br>
</center>

<h3 align="center"><b>TOPIC</b></h3>
<center>
<textarea NAME="content" ROWS="12" COLS="55">CONTENT</textarea>
<br>
<input type="submit" name="submit" value="確定">
<input type="reset" name="reset" value="重新輸入">
<a href="modify_work.php?work_id=WORKID&action=upload">上傳檔案</a>
<p></p><br>
<input type="hidden" name="work_id" value="WORKID">
<input type="hidden" name="action" value="ACT2">
</center>
</form>
    
</BODY>
</HTML>
