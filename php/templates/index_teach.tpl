<html>
<head>
<title>�Юv�n�J��</title>
<meta content="text/html; charset=big5" http-equiv="Content-Type">
<meta http-equiv="Page-Enter" content="BlendTrans(Duration=0.4)">
</head>
<body background=/images/img/bg.gif>
<center><img src="/Chinese/tch_login/title.jpg" width="422" height="117">
<BR>
<SCRIPT LANGUAGE="JavaScript">
	if (navigator.appName == "Netscape"){
		document.write("<font color=#ff0000>�Шϥ�IE5.5�H�W���s����</font>" );
	}
	else if (navigator.appName == "Microsoft Internet Explorer"){
		var bVer = navigator.appVersion.split(";");
		var Ver = bVer[1].split(" ");
		if ( Ver[2] < 5.5 ) {
			document.write("<font color=#ff0000>�Шϥ�IE 5.5�H�W�������s����</font>");
		}else {
			document.write("<BR><BR>");
		}
	}else {
		document.write("<BR><BR>");
	}
function mailcheck () {
	if ( confirm('�z�O�_�ݹL�����ѤF�O?') ) {
		if ( confirm('�z���j�M�����Ѥ�������r��?') ) {
			if ( confirm('�����z�n��������?') ) {
				if ( confirm('�����M����?') ) {
					alert('���z�����D���Ӥw��o�ѨM!!!');
					return false;
				}
				else {
					alert('�ӫH�Ъ��W�b���B�K�X�B��������!!!');
					return true;
				}
			}
			else{
				alert('�ӫH�Ъ��W�b���B�K�X�B��������!!!');
				return true;
			}
		}
		else {
			alert('�Х���WORD���j�M�\��A���z�\Ū������!!!');
			return false;
		}
	}
	else {
		alert('�Х��ݧ������ѡA�A�H�H!!!');
		return false;
	}
}
</SCRIPT>
<font color="#ff0000">MES</font>
<form action="./teacher.php" method="post">
�b���G<input style="border-color: #EFFAF8 #EFFAF8 #000000; border-style: solid; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px; background-color: #EFFAF8" type=text name="id" size="17">
<br><br>
�K�X�G<input style="border-color: #EFFAF8 #EFFAF8 #000000; border-style: solid; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px; background-color: #EFFAF8" name="pass" size="17" type="password">
<br><br>
<input type=hidden name=ver value=C>
<input style="border-color: #339933; border-style: solid; border-top-width: 2px; border-right-width: 2px; border-bottom-width: 2px; border-left-width: 2px; background-color: #EFFAF8; font-weight: bolder; cursor: hand" type="submit" value="�n�J"> <input style="border-color: #339933; border-style: solid; border-top-width: 2px; border-right-width: 2px; border-bottom-width: 2px; border-left-width: 2px; background-color: #EFFAF8; font-weight: bolder; cursor: hand" type="reset" value="�M��">
</form>
<a href="./Learner_Profile/lost_pass.php?version=C">�K�X��</a>
<hr>
��������D�лP<a href=mailto:mexwell@exodus.cs.ccu.edu.tw onClick="return mailcheck();">�L�ǿo</a>�p��
</center>
</body>
</html>