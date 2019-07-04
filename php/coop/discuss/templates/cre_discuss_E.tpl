<html>
<head>
<title> TITLE </title>
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css">
<script language="javascript">
function checkinput() {
	var flag = true;
	var message = 'Please input';
	if(create_dis.discuss_name.value.length > 0) {
		flag = true && flag;
	}
	else {
		flag = false && flag;
		message = message + " 'discuss group name\n'";
	}

	if(create_dis.comment.value.length > 0) {
		flag = true && flag;
	}
	else {
		flag = false && flag;
		message = message + " 'comment\n'";
	}

	if(!flag) {
		alert(message);
	}
	return flag;
}
</script>
</head>
<body >
<IMG SRC="/images/img_E/b52.gif">
<center>
<form action="cre_discuss.php" method="post" name="create_dis" onsubmit="return checkinput();">
<input type="hidden" name="amount" value="1">
<table border=2 width=75%>
<caption>Create single discuss group</caption>
<tr bgcolor=#edf3fa>
<td>Discuss name</td>
<td bgcolor=#cdeffc><input type="text" name="discuss_name" size=30 maxlength=100></td>
</tr>
<tr bgcolor=#edf3fa>
<td>Comment</td>
<td bgcolor=#cdeffc><input type="comment" name="comment" size=30 maxlength=100><input type="hidden" name="isgroup" value=1 ><input type="hidden" name="access" value="1"></td>
<tr bgcolor=#edf3fa><td>Access</tr>
<td bgcolor=#cdeffc><input type="radio" name="access" value="0" checked>public
<input type="radio" name="access" value="1">Private(Only Group staff)</td>
</tr>
</table>
<input type="submit" name="submit" value="Create new group"><input type="reset" name="reset">
</form>
<input type="button" value="Back" onClick="location.href='dis_list.php?PHPSESSID=PHP_ID'">
</center>
</body>
</html>