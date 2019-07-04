<html>
<head>
<title>TITLE</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<STYLE type=text/css></STYLE>
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css">
<script language="Javascript">
var selected = false;
function check(selected,type) {
	
	switch (type) {
		case 1:
			if(selected) {
				return confirm('Share?');
			}
		case 2:
			if(selected) {
				return confirm('Stop Share?');
			}
		case 3:
			if(selected) {
				return confirm('Delete?');
			}
		default:
			return false;
	}

	return false;
}
</script>
</head>
<body>
<br><center>
<font color=#00aa00><b><font color="#000000">MES</font></b>
</font>
<form method="post" action="./note.php">
<table border="0" align="center" cellpadding="0" cellspacing="0" width="100%">
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
<td> 
<table border="0" cellspacing="1" cellpadding="3" width = 100%>
<!-- BEGIN DYNAMIC BLOCK: course_list -->
<tr bgcolor="COLOR">
<td width="10%" align="center">CHECK</td>
<td width="20%" align="center">DATE</td>
<td width="38%"><p align="center">SUBJECT</td>
<td width="10%" align="center">STATUS</td>
<td width="10%" align="center">LINK</td>
<td width="10%" align="center">DOWNLOAD</td>
</tr>
<!-- END DYNAMIC BLOCK: course_list -->
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
</table></form>
ADD
</center></body></html>