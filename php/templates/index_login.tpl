<html>
<head>
<title>�n�J��</title>
<meta content="text/html; charset=big5" http-equiv="Content-Type">
<meta http-equiv="Page-Enter" content="BlendTrans(Duration=0.4)">
<style TYPE="text/css">
<!--
#mytable { background-image: url(http://SERVER/images/index/inc.gif)}
-->
</style>
<SCRIPT LANGUAGE="JavaScript">
	
	function Lang ( version ) {
		guestform.ver.value = version;
		var ns = navigator.appName == "Netscape";
		var ns4 = (ns && parseInt(navigator.appVersion) == 4);
		var ns5 = (ns && parseInt(navigator.appVersion) > 4);
		if ( version == "C" ) {
			profile.submit.value="�n�J";
			profile.reset.value="�M��";
			profile.guest.value="���[��";
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
<input type=hidden name=ver value=C >
<input type="text" name="id" size="15" value=""><br>
<input type="password" name="pass" size="15"><br>
<br>
<input type="submit" value="�n�J" name="submit">
<input type="reset" value="�M��" name="reset">
<input type="button" value="���[��" name="guest" OnClick="guestform.submit();">
<select name="ver" onChange="Lang( this.options[this.selectedIndex].value );">
<option value=C >���媩</option>
<option value=E >English</option>
</select>
</form>
<font color=#FF0000>MES</font>
RET
<form name="guestform" action="./Courses_Admin/guest.php" method="post" target="_top">
<input type=hidden name=ver value=C >
<input type=hidden name=id  value="guest">
</form>
</td>
</table>
<br>
<font size="1">�������v�Ҧ��A�ФŨp����� (c) 2002 �����j�Ǻ����оǨt��<br>
(c) 2002-2004 ��ߤ����j�� National Chung Cheng University. All Rights Reserved. <br>
<font color=#FF0000>���t�γq�L����꨾���̷s�ǲ����ҼзǴ��� �Q�{�w���X�ų̰����Ť��з�</font></font>
</center>
</body>
</html>