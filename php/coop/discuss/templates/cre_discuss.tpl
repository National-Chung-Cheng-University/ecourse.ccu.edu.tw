<html>
<head>
<title> TITLE </title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css">
<script language="javascript">
function checkinput( ) {
	var flag = true;
	var message = '請輸入';
	
	if( create_dis.discuss_name.value.length > 0) {
		flag = true && flag;
	}
	else {
		flag = false && flag;
		message = message + ' 討論區名稱';
	}

	if(create_dis.comment.value.length > 0) {
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
<body >
<IMG SRC="/images/img/b52.gif">
<center>
<form action="cre_discuss.php" method="post" name="create_dis" onsubmit="return checkinput();">
<input type="hidden" name="amount" value="1">
<table border=2 width=75%>
<caption>建立單一討論區</caption>
<tr bgcolor=#edf3fa>
<td>討論區名稱</td>
<td bgcolor=#cdeffc><input type="text" name="discuss_name" size=30 maxlength=100></td>
</tr>
<tr bgcolor=#edf3fa>
<td>討論區主旨</td>
<td bgcolor=#cdeffc><input type="comment" name="comment" size=30 maxlength=100><input type="hidden" name="isgroup" value=1 ><input type="hidden" name="access" value="1"></td>
<tr bgcolor=#edf3fa><td>討論區瀏覽權限</tr>
<td bgcolor=#cdeffc><input type="radio" name="access" value="0" checked>公開
<input type="radio" name="access" value="1">私人(只有小組成員可以看)</td>
</tr>
</table>
<input type="submit" name="submit" value="確定新增"><input type="reset" name="reset">
</form>
<input type="button" value="回討論區一覽" onClick="location.href='dis_list.php?PHPSESSID=PHP_ID'">
</center>
</body>
</html>