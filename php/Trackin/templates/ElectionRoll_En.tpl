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
                alert("Please Input The Date！");
                form1.date.focus();
                return (false);
        }
}
</script>
</head>

<body background=/images/IMG/bg.gif onLoad="parent.options.cwin();">
<center>
<form name="form1" method="post" enctype="multipart/form-data" action="ElectionRoll.php" onsubmit="return validate()">
<font color="#0000FF"><b>Upload The File：</b></font><font size="2" color="#FF0000">This file will be a new roll call record.</font><p></p>
(Date Format yyyy-mm-dd <font color=#FF0000><b>ex : 2008-12-30</b></font>)<br>
Date：<input name="date" type="text"><p></p>
File : <input type="file" name="userfile" size="25"><br>
<input type=submit value="Upload">
<input type=reset value="Clear">
<input type=hidden name="action" value="upload">
</form>
</body>
</html>
