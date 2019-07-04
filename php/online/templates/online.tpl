<html>
<head>
<title>線上人數統計</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<meta http-equiv="refresh" content="300;online.php?PHPSESSID=PHPID"> 

</head>
<body bgcolor="#FFFFFF" text="#000000" background="/images/skinSKINNUM/bbg.gif" leftmargin="0" topmargin="5" marginwidth="0" marginheight="0">
<form name=income method=post action="../messager/messager.php" target="MID">
<input type=hidden name=user value="USER">
<input type=hidden name=posttime value="TIME">
<input type=hidden name=multe value="MULTI">
<input type=hidden name=back value="MESSAGE">
</form>
<form name=move method=post action="./online.php" target="Message">
<input type=hidden name=move value="1">
<input type=hidden name=respone value="RESPONE">
</form>
<table border="0" cellspacing="0" cellpadding="0" align="center" height="50">
<tr> 
<td> 
<div align="left"><a href="#" onClick="window.open('../messager/detail.php?PHPSESSID=PHPID','PHPID','resizable=1,scrollbars=1,width=300,height=400');"><font color="#000000" size="2">系統</font></a> COUNTNOW <font size="2">人</font></div>
</td>
</tr>
<tr> 
<td> 
<div align="left"><a href="#" onClick="window.open('../messager/detail.php?PHPSESSID=PHPID','PHPID','resizable=1,scrollbars=1,width=300,height=400');"><font color="#000000" size="2">同學</font></a> COUNTCOURSE <font size="2">人</font></div>
</td>
</tr>
</table>
<script LANGUAGE=JavaScript>
<!--
SYSalert("SYM");
HAVEwindow.open("","MID","resizable=1,scrollbars=1,width=350,height=400");
CLOSEparent.close();
HAVEdocument.income.submit();
MOVEdocument.move.submit();
//-->
</script>
</body>
</html>
