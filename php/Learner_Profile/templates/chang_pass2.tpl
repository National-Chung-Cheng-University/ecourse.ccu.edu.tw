<HTML>
<HEAD>
<title>TITLE</title>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=big5">
<STYLE type=text/css>
	BODY { SCROLLBAR-FACE-COLOR: #4d6eb2; SCROLLBAR-ARROW-COLOR: #FFFFFF; SCROLLBAR-TRACK-COLOR: #DDDDFF; SCROLLBAR-SHADOW-COLOR: #054878; SCROLLBAR-3DLIGHT-COLOR: black; }
</STYLE>
<script language="JavaScript">

function Check() {
	if ( input.old.value == "" ) {
		if ( input.newpass.value != input.check.value ) {
			alert("New Password Not The Same!");
			return false;
		}
		else if ( input.newpass.value == "" ) {
			alert("Password is too simple!");
			return false;
		}
	}
	if ( input.email.value == "" || input.email.value.indexOf("@") == "-1" || input.email.value.indexOf(".") == "-1" ) {
			alert("Not Correct Email!");
			return false;
	}
	return true;
}
</script>
</HEAD>
<BODY background=/images/PATH/bg.gif>
<BR>
<center>
<h1>TITLE</h1><br>
<font color="#FF0000">MES</font>
<form method=POST name=input action=./chang_pass2.php>
<table border=1>
PASS
EMAIL
</table>
<br>
<input type=submit value=SUBM OnClick="return Check();">
<input type=reset value=CLEA>
</form>
</center>
</BODY>
</HTML>
