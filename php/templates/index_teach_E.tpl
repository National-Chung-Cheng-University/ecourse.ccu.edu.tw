<html>
<head>
<title>Teacher Login</title>
<meta http-equiv="Page-Enter" content="BlendTrans(Duration=0.4)">
</head>
<body>
<center><img src="/English/tch_login/title.jpg" width="422" height="117">
<br>
<SCRIPT LANGUAGE="JavaScript">
	if (navigator.appName == "Netscape"){
		document.write("<font color=#ff0000>Please use IE version 5.5 or later</font>" );
	}
	else if (navigator.appName == "Microsoft Internet Explorer"){
		var bVer = navigator.appVersion.split(";");
		var Ver = bVer[1].split(" ");
		if ( Ver[2] < 5.5 ) {
			document.write("<font color=#ff0000>Please use IE version 5.5 or later</font>");
		}else {
			document.write("<BR><BR>");
		}
	}else {
		document.write("<BR><BR>");
	}
</SCRIPT>
<font color="#ff0000">MES</font>
<form action="https://SERVER/php/teacher.php" method="post">
ID¡G<input style="border-color: #FFFFFF #FFFFFF #000000; border-style: solid; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px; background-color: #FFFFFF" name="id" size="17" value="UID">
<br><br>
Password¡G<input style="border-color: #FFFFFF #FFFFFF #000000; border-style: solid; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px; background-color: #FFFFFF" name="pass" size="11" type="password">
<br><br>
<input type=hidden name=ver value=E>
<input type="submit" value="Login"> <input type="reset" value="Clear">
</form>
<a href="https://SERVER/php/Learner_Profile/lost_pass.php">Lost Your Password</a>
<hr>
Have Any Problem Please Contact With<a href=mailto:mexwell@exodus.cs.ccu.edu.tw>Mexwell</a>
</center>
</body>
</html>