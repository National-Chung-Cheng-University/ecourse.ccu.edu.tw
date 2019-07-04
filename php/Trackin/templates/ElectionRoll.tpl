<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css">
<script language=JavaScript>
function validate()
{
        if ( document.all.date.value == "" )
        {
                alert("請輸入日期！");
                form1.date.focus();
                return (false);
        }
}
</script>
</head>

<body background=/images/IMG/bg.gif onLoad="parent.options.cwin();">
<center>
<form name="form1" method="post" enctype="multipart/form-data" action="ElectionRoll.php" onsubmit="return validate()">
<font color="#0000FF"><b>上傳檔案：</b></font><font size="2" color="#FF0000">此檔將作為一次點名紀錄</font><p></p>
(日期格式 yyyy-mm-dd <font color=#FF0000><b>ex : 2008-12-30</b></font>)<br>
請輸入日期：<input name="date" type="text"><p></p>
點名紀錄檔 : <input type="file" name="userfile" size="25"><br>
<input type=submit value="上傳檔案">
<input type=reset value="清除">
<input type=hidden name="action" value="upload">
</form>
</body>
</html>
