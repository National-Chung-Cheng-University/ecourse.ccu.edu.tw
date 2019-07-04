<html>
<head>
<title>教師登入頁</title>
<meta content="text/html; charset=big5" http-equiv="Content-Type">
<meta http-equiv="Page-Enter" content="BlendTrans(Duration=0.4)">
</head>
<body background=/images/img/bg.gif>
<center><img src="/Chinese/tch_login/title.jpg" width="422" height="117">
<BR>
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
function mailcheck () {
	if ( confirm('您是否看過說明書了呢?') ) {
		if ( confirm('您有搜尋說明書中的關鍵字嗎?') ) {
			if ( confirm('有找到您要的說明嗎?') ) {
				if ( confirm('說明清楚嗎?') ) {
					alert('那您的問題應該已獲得解決!!!');
					return false;
				}
				else {
					alert('來信請附上帳號、密碼、分機謝謝!!!');
					return true;
				}
			}
			else{
				alert('來信請附上帳號、密碼、分機謝謝!!!');
				return true;
			}
		}
		else {
			alert('請先用WORD的搜尋功能，幫您閱讀說明書!!!');
			return false;
		}
	}
	else {
		alert('請先看完說明書，再寄信!!!');
		return false;
	}
}
</SCRIPT>
<font color="#ff0000">MES</font>
<form action="./teacher.php" method="post">
帳號：<input style="border-color: #EFFAF8 #EFFAF8 #000000; border-style: solid; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px; background-color: #EFFAF8" type=text name="id" size="17">
<br><br>
密碼：<input style="border-color: #EFFAF8 #EFFAF8 #000000; border-style: solid; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px; background-color: #EFFAF8" name="pass" size="17" type="password">
<br><br>
<input type=hidden name=ver value=C>
<input style="border-color: #339933; border-style: solid; border-top-width: 2px; border-right-width: 2px; border-bottom-width: 2px; border-left-width: 2px; background-color: #EFFAF8; font-weight: bolder; cursor: hand" type="submit" value="登入"> <input style="border-color: #339933; border-style: solid; border-top-width: 2px; border-right-width: 2px; border-bottom-width: 2px; border-left-width: 2px; background-color: #EFFAF8; font-weight: bolder; cursor: hand" type="reset" value="清除">
</form>
<a href="./Learner_Profile/lost_pass.php?version=C">密碼遺失</a>
<hr>
有任何問題請與<a href=mailto:mexwell@exodus.cs.ccu.edu.tw onClick="return mailcheck();">林傳穎</a>聯絡
</center>
</body>
</html>