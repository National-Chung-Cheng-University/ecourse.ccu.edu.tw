<html>
<head>
<title> ­ק﯑½װϤº®e </title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css">
<script language="javascript">
function checkinput() {
	var flag = true;
	var message = 'Please input';
	if(modify_dis.discuss_name.value.length > 0) {
		flag = true && flag;
	}
	else {
		flag = false && flag;
		message = message + " 'discuss group name'";
	}

	if(modify_dis.comment.value.length > 0) {
		flag = true && flag;
	}
	else {
		flag = false && flag;
		message = message +  " 'comment'";
	}

	if(modify_dis.isgroup[0].checked) {
		flag = true && flag;
	}
	else {
		if(isNaN(parseInt(modify_dis.group_num.value))) {
			flag = false && flag;
			message = message + " 'team number'";
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
<body background="/images/img_E/bg.gif">
<IMG SRC="/images/img_E/b52.gif">
<center>
<form action="modify_discuss.php" method="post" name="modify_dis" onsubmit="return checkinput();">
<table border=2 width=75%>
<tr bgcolor=#edf3fa><td>Name of this discuss group<td bgcolor=#cdeffc><input type="text" name="discuss_name" size=30 maxlength=100 value="NAME">
<tr bgcolor=#edf3fa><td>Comment<td bgcolor=#cdeffc><input type="comment" name="comment" size=30 maxlength=100 value="COMMENT">
<tr bgcolor=#edf3fa><td>Is it a team discuss group?
    <td bgcolor=#cdeffc><input type="radio" name="isgroup" value=0 NOT_GROUP>No
	    <input type="radio" name="isgroup" value=1 IS_GROUP>Team<input type="text" name="group_num" size=2 maxlength=2 value=GROUP_NO>
<tr bgcolor=#edf3fa><td>Discuss group read permission
    <td bgcolor=#cdeffc><input type="radio" name="access" value="0" VIEW_PUBLIC>Public
    <input type="radio" name="access" value="1" VIEW_PRIVATE>Private(only team members can enter)
</table><br>
<font color=#ff0000>If Change Group Num, please Reset Group data¡C</font><br>
<input type="hidden" name="discuss_id" value="DISCUSS_ID">
<input type="submit" name="submit" value="Create New Discuss Group"><input type="reset" name="reset">
<input type="button" value="Back to Discuss Group List" onClick="location.href='dis_list.php?PHPSESSID=PHP_ID'">
</form>
</center>
</body>
</html>