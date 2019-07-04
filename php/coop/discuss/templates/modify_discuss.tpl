<html>
<head>
<title> 修改討論區內容 </title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css">
<script language="javascript">
function checkinput() {
	var flag = true;
	var message = '請輸入';

	if(modify_dis.discuss_name.value.length > 0) {
		flag = true && flag;
	}
	else {
		flag = false && flag;
		message = message + ' 討論區名稱';
	}

	if(modify_dis.comment.value.length > 0) {
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
<body>
<IMG SRC="/images/img/b52.gif">
<center>
<form action="modify_discuss.php" method="post" name="modify_dis" onsubmit="return checkinput();">
<table border=2 width=75%>
<tr bgcolor=#edf3fa><td>討論區名稱</td><td bgcolor=#cdeffc><input type="text" name="discuss_name" size=30 maxlength=100 value="NAME"></td></tr>
<tr bgcolor=#edf3fa><td>討論區主旨</td><td bgcolor=#cdeffc><input type="comment" name="comment" size=30 maxlength=100 value="COMMENT"></td></tr>
<tr bgcolor=#edf3fa><td>討論區瀏覽權限</td>
<td bgcolor=#cdeffc><input type="radio" name="access" value="0" VIEW_PUBLIC>公開
<input type="radio" name="access" value="1" VIEW_PRIVATE>私人(只有小組成員可以看)</td>
</tr>
</table><br>
<input type="hidden" name="discuss_id" value="DISCUSS_ID">
<input type="submit" name="submit" value="確定修改"><input type="reset" name="reset">
<input type="button" value="回討論區一覽" onClick="location.href='dis_list.php?PHPSESSID=PHP_ID'">
</form>
</center>
</body>
</html>