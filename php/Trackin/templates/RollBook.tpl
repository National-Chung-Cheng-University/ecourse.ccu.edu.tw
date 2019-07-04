<HTML>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css">


</head>
<body background=/images/IMG/bg.gif onLoad="parent.options.cwin();">
<CENTER>

<!--
<form action="addRollRecord.php" method="post">
<input type="submit" value="新增點名記錄" >
</form>
<form action="ElectionRoll.php" method="post">
<input type="submit" value="上傳點名記錄" >
</form>
<form action="../my_convert_rollbook.php" method="post">
<input type="submit" value="轉換網路辦公室點名記錄" >
</form>
-->

ADD_ROLL
ELECTION_ROLL

<table border="0" align="center" cellpadding="0" cellspacing="0" width="90%">
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
<table border=0 align="center" width="100%" cellpadding="3" cellspacing="1">

<!-- BEGIN DYNAMIC BLOCK: row -->
<TR bgcolor="COLOR">
<TD width="15%">STUDENT_ID</TD>
<TD width="15%">STUDENT_NAME</TD>
<TD width="103">STUDENT_PIC</TD>
DATE
<TD>COUNT</TD>
</TR>
<!-- END DYNAMIC BLOCK: row -->

<TR bgcolor="#F0FFEE">
<TD></TD>
<TD></TD>
<TD></TD>
MODIFY_BTN
<TD></TD>
</TR>
<TR bgcolor="#E6FFFC">
<TD></TD>
<TD></TD>
<TD></TD>
DEL_BTN
<TD></TD>
</TR>
<TR bgcolor="#F0FFEE">
<TD></TD>
<TD></TD>
<TD></TD>
MAIL_TO_BTN
<TD></TD>
</TR>

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
<hr>
<SCRIPT>
function Check2() {
	if ( absent.times.value <= 0 ) {
			alert("次數輸入錯誤!");
			return false;
	}
	return true;
}
</SCRIPT>
<br>查詢超過 n次缺席學生列表<br>
<FORM ACTION=AbsentQuery.php METHOD=POST name=absent>
次數：<INPUT TYPE=TEXT MAXLENGTH=4 SIZE=4 NAME=times VALUE="3"><BR>
<INPUT TYPE=SUBMIT VALUE=OK OnClick="return Check2();">
</FORM>
</CENTER>
</BODY>
</HTML>
