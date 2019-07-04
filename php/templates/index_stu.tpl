<html>
<head>
<title>學生登入</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<meta http-equiv="Page-Enter" content="BlendTrans(Duration=0.4)">
</head>
<body background=/images/img/bg.gif>
<div align="center"><img src="/Chinese/stud_log/title2.jpg" width="422" height="117"><br>
<img src="/Chinese/stud_log/t2_1.gif" width="217" height="38"><br>
<SCRIPT LANGUAGE="JavaScript">
	if (navigator.appName == "Netscape"){
		document.write("<font color=#ff0000>請使用IE5.5以上之瀏覽器</font>" );
	}
	else if (navigator.appName == "Microsoft Internet Explorer"){
		var bVer = navigator.appVersion.split(";");
		var Ver = bVer[1].split(" ");
		if ( Ver[2] < 5.5 ) {
			document.write("<font color=#ff0000>請使用IE 5.5以上版本之瀏覽器</font>");
		}else {
			document.write("<BR><BR>");
		}
	}else {
		document.write("<BR><BR>");
	}
</SCRIPT>
<font color="#ff0000">MES</font>
<form action="./student.php" method="post">
帳號：<input style="border-color: #EFFAF8 #EFFAF8 #000000; border-style: solid; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px; background-color: #EFFAF8" type="text" name="id" size="17"><br><br>
密碼：<input style="border-color: #EFFAF8 #EFFAF8 #000000; border-style: solid; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px; background-color: #EFFAF8" type="password" name="pass" size="17" ><br><br>
<input type=hidden name=ver value=C>
<input style="border-color: #339933; border-style: solid; border-top-width: 2px; border-right-width: 2px; border-bottom-width: 2px; border-left-width: 2px; background-color: #EFFAF8; font-weight: bolder; cursor: hand" type="submit" value="登入"> <input style="border-color: #339933; border-style: solid; border-top-width: 2px; border-right-width: 2px; border-bottom-width: 2px; border-left-width: 2px; background-color: #EFFAF8; font-weight: bolder; cursor: hand" type="reset" value="清除"><br><br>
<a href="./Learner_Profile/lost_pass.php?version=C">密碼遺失</a>
</form>
<hr align="center">
<img src="/Chinese/stud_log/t2_3.gif" width="189" height="38">
<form action="./Courses_Admin/guest.php" method="post">
<input type=hidden name=ver value=C >
<input type=hidden name=id  value="guest">
<input style="border-color: #339933; border-style: solid; border-top-width: 2px; border-right-width: 2px; border-bottom-width: 2px; border-left-width: 2px; background-color: #EFFAF8; font-weight: bolder; cursor: hand" type="submit" value="GUEST">
</form>
有任何問題請與該課程助教或老師聯絡 謝謝!!
</div>
</body>
</html>