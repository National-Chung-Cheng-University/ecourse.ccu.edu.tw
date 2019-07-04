<HTML>
<title>TITLE</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<STYLE type=text/css>
	BODY { SCROLLBAR-FACE-COLOR: #4d6eb2; SCROLLBAR-ARROW-COLOR: #FFFFFF; SCROLLBAR-TRACK-COLOR: #DDDDFF; SCROLLBAR-SHADOW-COLOR: #054878; SCROLLBAR-3DLIGHT-COLOR: black; }
</STYLE>

<script language="JavaScript">

function Check() {
	if ( newta.id.value == "" ) {
			alert("您沒有輸入ID!");
			return false;
	}

        //modify by chiefboy1230, e-mail include valid char, ex:「<、>、,、; 」
	//if ( newta.email.value == "" || newta.email.value.indexOf("@") == "-1" || newta.email.value.indexOf(".") == "-1" ) {
        if ( newta.email.value == "" || newta.email.value.indexOf("@") == "-1" || newta.email.value.indexOf(".") == "-1" || newta.email.value.indexOf("<") != "-1" || newta.email.value.indexOf(">") != "-1" || newta.email.value.indexOf(",") != "-1" || newta.email.value.indexOf(";") != "-1") {
			alert("你沒有輸入正確的Email!");
			return false;
	}
	return true;
}
</script>
<link rel="stylesheet" href="/images/skinSKINNUM/css/ahover.css" type="text/css">
<link rel="stylesheet" href="/images/skinSKINNUM/css/body.css" type="text/css">
<link rel="stylesheet" href="/images/skinSKINNUM/css/a.css" type="text/css">
<link rel="stylesheet" href="/images/skinSKINNUM/css/aiv.css" type="text/css">
<HEAD>
<BODY background="/images/skinSKINNUM/bbg.gif">
<br>
<p align="center">TITLE</p>
<center>
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
<table border=0 width="100%" cellpadding="3" cellspacing="1">
<!-- BEGIN DYNAMIC BLOCK: ta_list -->
FORMSTART
<tr bgcolor="COLOR" align=center><td><font size=2>UID</font></td>
<td><font size=2>EMAIL</font></td>
<td><font size=2>BUTTON</font></td></tr>
</form>
<!-- END DYNAMIC BLOCK: ta_list -->
</table>
</td>
<td height=10> 
<div align="left"><img src="/images/skinSKINNUM/bor/bor_06.GIF" width="17" height="100%"></div>
</td>
</tr>
<tr> 
<td> 
<div align="right"><img src="/images/skinSKINNUM/bor/bor_07.GIF" width="12" height="17"></div>
</td>
<td> 
<div align="center"><img src="/images/skinSKINNUM/bor/bor_08.GIF" width="100%" height="17"></div>
</td>
<td> 
<div align="left"><img src="/images/skinSKINNUM/bor/bor_09.GIF" width="17" height="17"></div>
</td>
</tr>
</table>
<br>
<!--<b>TITLE</b><br>-->
<font color="#ff0000">MES</font><br>
<!--TL1
<form method=POST action=./new_ta.php>
<select NAME=id>-->

<!-- BEGIN DYNAMIC BLOCK: tid_list -->
<!--option value="TVD">TID-->
<!-- END DYNAMIC BLOCK: tid_list -->

<!--/select>
<input type=hidden name=flag value = 2>
<input type=submit value="ADD">
<input type=reset value="CLEAR">
</form-->
TL2
<form method=POST action=./new_ta.php name=newta>
<input type=hidden name=flag value=1>
<table border="0" align="center" cellpadding="0" cellspacing="0"">
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
<td> 
<div align="right"><img src="/images/skinSKINNUM/bor/bor_04.GIF" width="12" height="97"></div>
</td>
<td bgcolor="#CCCCCC">
<table border=0 cellpadding="3" cellspacing="1" width="100%">
<tr> 
<td bgcolor="#000066"> 
<div align="center"><font size="2" color="#FFFFFF">UI2</font></div>
</td>
<td bgcolor="#E6FFFC"> 
<div align="center"><font size="2"> 
<input type="text" name="id" maxlength="12" value="VID">
</font></div>
</td>
</tr>
<tr> 
<td bgcolor="#000066"> 
<div align="center"><font size="2" color="#FFFFFF">PAS2</font></div>
</td>
<td bgcolor="#E6FFFC"> 
<div align="center"><font size="2"> 
<input type="text" name="pass">
</font></div>
</td>
</tr>
<tr> 
<td bgcolor="#000066"> 
<div align="center"><font size="2" color="#FFFFFF">e-Mail</font></div>
</td>
<td bgcolor="#E6FFFC"> 
<div align="center"><font size="2"> 
<input type="text" name="email" value="VMAIL">
</font></div>
</td>
</tr>
</table>
</td>
<td> 
<div align="left"><img src="/images/skinSKINNUM/bor/bor_06.GIF" width="17" height="97"></div>
</td>
</tr>
<tr> 
<td> 
<div align="right"><img src="/images/skinSKINNUM/bor/bor_07.GIF" width="12" height="17"></div>
</td>
<td> 
<div align="center"><img src="/images/skinSKINNUM/bor/bor_08.GIF" width="100%" height="17"></div>
</td>
<td> 
<div align="left"><img src="/images/skinSKINNUM/bor/bor_09.GIF" width="17" height="17"></div>
</td>
</tr>
</table>
<input type="submit" value="ADD" OnClick="return Check();">
<input type="reset" value="CLEAR">
</form>
</center>
</body></html>
