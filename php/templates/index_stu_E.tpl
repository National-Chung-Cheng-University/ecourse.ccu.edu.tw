<html>
<head>
<title>STUDENT LOGIN</title>
<meta http-equiv="Page-Enter" content="BlendTrans(Duration=0.4)">
</head>
<body>
<center><img src="/English/stud_log/title.jpg" width="637" height="70">
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
<form method="POST" action="https://SERVER/php/student.php">
ID¡G<input style="border-color: #FFFFFF #FFFFFF #000000; border-style: solid; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px; background-color: #FFFFFF" type="text" name="id" size="17" value="UID">
<br><br>
Password¡G<input style="border-color: #FFFFFF #FFFFFF #000000; border-style: solid; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px; background-color: #FFFFFF" type="password" name="pass" size="11">
<br><BR>
<input type=hidden name=ver value=E>
<input type="submit" value="Login"> <input type="reset" value="Reset"> </p>
</form>
<center><a href="https://SERVER/php/Learner_Profile/lost_pass.php">Lost Your Password</a></center>
<form action="https://SERVER/php/Courses_Admin/guest.php" method="post">
  <input type=hidden name=ver value=E >
  <input type=hidden name=id  value="guest">
  <div align="center"><center><p><input type="submit" value="GUEST"> </p>
  </center></div>
</form>
<img src="/images/img_E/line3.gif" border=0><br><br>
<img src="bar.jpg" width="400" height="8"><br>
<font size="4" color="#0000FF"><b>System Announcement</b></font>

<b>
<font color="#00aa00" size="+1">Recommended Resolution: 1024*768 </font><br><br>
<font color="#ff0000" size="2">If you have any questions, please contact with your teacher</font>
<br>
<font size="3" color="#0000ff">Please read system documents first before submitting your questions.¡C</font> 
</b>
</center>
</body> 
</html> 