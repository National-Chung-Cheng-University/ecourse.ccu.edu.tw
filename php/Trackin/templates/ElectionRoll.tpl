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
                alert("�п�J����I");
                form1.date.focus();
                return (false);
        }
}
</script>
</head>

<body background=/images/IMG/bg.gif onLoad="parent.options.cwin();">
<center>
<form name="form1" method="post" enctype="multipart/form-data" action="ElectionRoll.php" onsubmit="return validate()">
<font color="#0000FF"><b>�W���ɮסG</b></font><font size="2" color="#FF0000">���ɱN�@���@���I�W����</font><p></p>
(����榡 yyyy-mm-dd <font color=#FF0000><b>ex : 2008-12-30</b></font>)<br>
�п�J����G<input name="date" type="text"><p></p>
�I�W������ : <input type="file" name="userfile" size="25"><br>
<input type=submit value="�W���ɮ�">
<input type=reset value="�M��">
<input type=hidden name="action" value="upload">
</form>
</body>
</html>
