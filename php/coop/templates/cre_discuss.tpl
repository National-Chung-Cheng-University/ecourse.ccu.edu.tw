<html>
<head>
<title> TITLE </title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css"> 
<script language="javascript">

function checkinput( ) {
	var flag = true;
	var message = '請輸入';
	
	if(isNaN(parseInt(create_batch.amount.value))) {
		flag = false && flag;
		message = message + ' 正確數目';
	}
	else {
		flag = true && flag;
	}

	if( create_batch.discuss_name.value.length > 0) {
		flag = true && flag;
	}
	else {
		flag = false && flag;
		message = message + ' 討論區名稱';
	}

	if( create_batch.discuss_name.value.indexOf("%d") == -1 ) {
		flag = false && flag;
		message = message + ' \%d';	
	}

	if( create_batch.comment.value.length > 0) {
		flag = true && flag;
	}
	else {
		flag = false && flag;
		message = message + ' 討論區主旨';
	}

	if(!flag) {
		alert(message);
	}
	return flag;
}
</script>
</head>
<body background="/images/img/bg.gif">
<IMG SRC="/images/img/b52.gif">
<center>
<BR><BR>
<font color="#FF0000">MESSAGE</font> 
<form action="Mag_case.php" method="post" name="create_batch" onSubmit="return checkinput();">
<table border="0" align="center" cellpadding="0" cellspacing="0" >
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
<caption>建立合作環境</caption>
<tr bgcolor=#E6FFFC>
	<td>合作環境數
	<td bgcolor=#F0FFEEc>NUMBER
<tr bgcolor=#E6FFFC>
	<td>討論區主旨
	<td bgcolor=#F0FFEE><input type="comment" name="comment" size=30 maxlength=100>
<tr bgcolor=#E6FFFC>
	<td>欲建立合作環境數目
	<td bgcolor=#F0FFEE><input type="text" name="amount" size=5 maxlength=8>
<tr bgcolor=#E6FFFC><td>合作環境瀏覽權限
    <td bgcolor=#F0FFEE><input type="radio" name="access" value="0" checked>公開
    <input type="radio" name="access" value="1">私人(只有小組成員可以看)
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
<input type="submit" name="submit" value="確定新增"><input type="reset" name="reset">
</form>
<hr>
<input type="button" value="回管理介面" onClick="location.href='Mag_case.php?PHPSESSID=PHP_ID'">
</center>
</body>
</html>