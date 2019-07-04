<html>
<head>
<title>Login</title>
<meta http-equiv="Page-Enter" content="BlendTrans(Duration=0.4)">
<style TYPE="text/css">
<!--
#mytable { background-image: url(http://SERVER/images/index/ine.gif)}
-->
</style>
<SCRIPT LANGUAGE="JavaScript">
	
	function Lang ( version ) {
		guestform.ver.value = version;
		var ns = navigator.appName == "Netscape";
		var ns4 = (ns && parseInt(navigator.appVersion) == 4);
		var ns5 = (ns && parseInt(navigator.appVersion) > 4);
		if ( version == "C" ) {
			profile.submit.value="登入";
			profile.reset.value="清除";
			profile.guest.value="參觀者";
			if ( ns4 ){ 
				document["mytable"].style.background = "url(http://SERVER/images/index/inc.gif)";
			}
			else if ( ns5 ) {
				document.getElementById("Layer1").style.background = "url(http://SERVER/images/index/inc.gif)";
			}
			else
				document.all.mytable.style.background = "url(http://SERVER/images/index/inc.gif)";
		}
		else {
			profile.submit.value="Login";
			profile.reset.value="Clear";
			profile.guest.value="Guest";
			if ( ns4 ){ 
				document["mytable"].style.background = "url(http://SERVER/images/index/ine.gif)";
			}
			else if ( ns5 ) {
				document.getElementById("Layer1").style.background = "url(http://SERVER/images/index/ine.gif)";
			}
			else
				document.all.mytable.style.background = "url(http://SERVER/images/index/ine.gif)";
		}
	}
</SCRIPT>
</head>
<body bgcolor="#FFFFFF" text="#777777">
<center>
<table ID="mytable" border="0" width="640" height="431">
<tr>
<td align="right" valign="bottom" height="162"></td>
</tr>
<tr>
<td align=right valign="top" height="269"> 
<form name=profile method="post" action="./index_login.php" target="_top">
<input type=hidden name=ver value=E >
<input type="text" name="id" size="15" value=""><br>
<input type="password" name="pass" size="15"><br>
<br>
<input type="submit" value="Login" name="submit">
<input type="reset" value="Clear" name="reset">
<input type="button" value="Guest" name="guest" OnClick="guestform.submit();">
<select name="ver" onChange="Lang( this.options[this.selectedIndex].value );">
<option value=E >English</option>
<option value=C >中文版</option>
</select>
</form>
<font color=#FF0000>MES</font>
RET
<form name="guestform" action="./Courses_Admin/guest.php" method="post" target="_top">
<input type=hidden name=ver value=E >
<input type=hidden name=id  value="guest">
</form>
</td>
</table>
  <br>
  <font size="1">網站版權所有，請勿私自轉載 (c) 2002 中正大學網路教學系統<br>
  (c) 2002-2004 國立中正大學 National Chung Cheng University. All Rights Reserved. <br>
<font color=#FF0000>This System is accepted by SCORM v1.2.2 standard test</font></font>
</center>
</body>
</html>