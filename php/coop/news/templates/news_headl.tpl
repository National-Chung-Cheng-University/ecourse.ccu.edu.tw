<html>
<head>
<title>�̷s����</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css">
</head>
<body onLoad="parent.cwin();">
<a name="newst"></a>
<script language="JavaScript">
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}

function Vlimit()
{
	parent.msgwin('��ƳB�z��, �еy��..');
	document.vlimit.submit();
}

function check_post() {
	var error = true;
	var msg = "�п�J";
	if(post_art.subject.value == "") {
		error = false;
		msg = msg + " ���D";
	}

	if(post_art.news.value == "") {
		error = false;
		msg = msg + " ���e";
	}
	
	if(!error)
		alert(msg);
	return error;
}

</script>
<BR>
<center>
<p>�̷s����<p>
<H2>MES</H2><BR>
<table border = 0 >
<form name=vlimit method=POST action=./news.php>
<tr><td>
<select name="views" onChange="Vlimit();">
<option value="0" V0>�Ҧ������i</option>
<option value="1" V1>�̪�@�Ӥ몺���i</option>
<option value="2" V2>�ܤ��o�������i</option>
OPTION1
OPTION2
</select>
</td></tr>
</form>
</table>
POST_LINE
<table border="0" align="center" cellpadding="0" cellspacing="0" width="80%">
<tr> 
<td> 
<div align="right"><img src="/images/skinSKINNUM/bor/bor_01.GIF" width="12" height="11"></div>
</td>
<td> 
<div align="center"><img src="/images/skinSKINNUM/bor/bor_02.GIF" width="100%" height="11"></div>
</td>
<td> 
<div align="left"><img src="/images/skinSKINNUM/bor/bor_03.GIF" width="17" height="11"></div>
</td>
</tr>
<tr> 
<td height=10> 
<div align="right"><img src="/images/skinSKINNUM/bor/bor_04.GIF" width="12" height="100%"></div>
</td>
<td bgcolor="#CCCCCC"> 
<table cellpadding=3 align=center border=0 bordercolorlight="#666666" bordercolordark="#FFFFFF" width="100%" cellspacing="1">
<tr bgcolor=#000066 align=center>
DELETE
<TD> 
<div align="center"><a href="./news.php?views=VIEWS&order=1"><FONT color=#ffffff size=2>���</FONT></a></div></TD>
<td>
<div align="center"><a href="./news.php?views=VIEWS&order=3"><FONT color=#ffffff size=2>���i���n����</font></a></div></td>
<TD> 
<div align="center"><FONT color=#ffffff size=2>���D�θԤ�s��</FONT></div></TD>
<TD COLSPAN=2> 
<div align="center"><a href="./news.php?views=VIEWS&order=7"><FONT color=#ffffff size=2>�s������</FONT></a></div></TD>
</TR>
