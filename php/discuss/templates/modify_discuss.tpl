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

	if(modify_dis.isgroup[0].checked) {
		flag = true && flag;
	}
	else {
		if(isNaN(parseInt(modify_dis.group_num.value))) {
			flag = false && flag;
			message = message + ' 正確組別';
		}
		else
			flag = true && flag;			
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
<form action="modify_discuss.php" method="post" name="modify_dis" onsubmit="return checkinput();">
<table border=2 width=75%>
<tr bgcolor=#edf3fa><td>討論區名稱<td bgcolor=#cdeffc><input type="text" name="discuss_name" size=30 maxlength=100 value="NAME">
<tr bgcolor=#edf3fa><td>討論區主旨<td bgcolor=#cdeffc><input type="comment" name="comment" size=30 maxlength=100 value="COMMENT">
<tr bgcolor=#edf3fa><td>是否為小組討論區
    <td bgcolor=#cdeffc><input type="radio" name="isgroup" value=0 NOT_GROUP>否
	    <input type="radio" name="isgroup" value=1 IS_GROUP>是第<input type="text" name="group_num" size=2 maxlength=2 value=GROUP_NO>組
<tr bgcolor=#edf3fa><td>討論區瀏覽權限
    <td bgcolor=#cdeffc><input type="radio" name="access" value="0" VIEW_PUBLIC>公開
    <input type="radio" name="access" value="1" VIEW_PRIVATE>私人(只有小組成員可以看)
</table><br>
<font color=#ff0000>如修改組別，請記得重新套用分組名單。</font><br>
<input type="hidden" name="discuss_id" value="DISCUSS_ID">
<input type="submit" name="submit" value="確定修改"><input type="reset" name="reset">
<input type="button" value="回討論區一覽" onClick="location.href='dis_list.php?PHPSESSID=PHP_ID'">
</form>
</center>
</body>
</html>