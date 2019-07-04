<html>
<head>
<title> TITLE </title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<STYLE type=text/css>
 body { SCROLLBAR-FACE-COLOR: #4d6eb2; SCROLLBAR-ARROW-COLOR: #FFFFFF; SCROLLBAR-TRACK-COLOR: #DDDDFF; SCROLLBAR-SHADOW-COLOR: #054878; SCROLLBAR-3DLIGHT-COLOR: black; }
</STYLE> 
<link rel="stylesheet" href="/images/skin1/css/main-body.css" type="text/css">
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

	if(create_dis.isgroup[0].checked) {
		flag = true && flag;
	}
	else {
		if(isNaN(parseInt(create_dis.group_num.value))) {
			flag = false && flag;
			message = message + " 'team number\n'";
		}
		else
			flag = true && flag;			
	}

	if(!flag) {
		alert(message);
	}
	return flag;
}

function checkinput2( ) {
	var flag = true;
	var message = 'Please Input';
	
	if(isNaN(parseInt(create_batch.amount.value))) {
		flag = false && flag;
		message = message + ' exact amount\n';
	}
	else {
		flag = true && flag;
	}

	if( create_batch.discuss_name.value.length > 0) {
		flag = true && flag;
	}
	else {
		flag = false && flag;
		message = message + ' discuss name\n';
	}

	if( create_batch.discuss_name.value.indexOf("%d") == -1 ) {
		flag = false && flag;
		message = message + ' \%d\n';	
	}

	if( create_batch.comment.value.length > 0) {
		flag = true && flag;
	}
	else {
		flag = false && flag;
		message = message + ' comment\n';
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
<form action="cre_discuss.php" method="post" name="create_dis" onsubmit="return checkinput();">
<input type="hidden" name="amount" value="1">
<table border=2 width=75%>
<caption>Create single discuss group</caption>
<tr bgcolor=#edf3fa>
	<td>Discuss group name
	<td bgcolor=#cdeffc><input type="text" name="discuss_name" size=30 maxlength=100>
<tr bgcolor=#edf3fa>
	<td>Comment
	<td bgcolor=#cdeffc><input type="comment" name="comment" size=30 maxlength=100>
<tr bgcolor=#edf3fa><td>Is it a team discuss group?
    <td bgcolor=#cdeffc>
		<input type="radio" name="isgroup" value=0 checked>No
	    <input type="radio" name="isgroup" value=1>Yes, Team<input type="text" name="group_num" size=2 maxlength=2 onFocus="create_dis.isgroup[1].checked = true;">
<tr bgcolor=#edf3fa><td>Dicsuss group read permission
    <td bgcolor=#cdeffc><input type="radio" name="access" value="0" checked>Public
    <input type="radio" name="access" value="1">Private(only team members can read)
</table>
<input type="submit" name="submit" value="Create new group"><input type="reset" name="reset">
</form>
<hr>
<form action="cre_discuss.php" method="post" name="create_batch" onSubmit="return checkinput2();">
<table border=2 width=75%>
<caption>Create multiple discuss groups</caption>
<tr bgcolor=#edf3fa>
	<td>Number of groups to be created
	<td bgcolor=#cdeffc><input type="text" name="amount" size=5 maxlength=8>
<tr bgcolor=#edf3fa>
	<td>Discuss group name(Please use %d to indicate the place using numeric)
	<td bgcolor=#cdeffc><input type="text" name="discuss_name" size=30 maxlength=100>
<tr bgcolor=#edf3fa>
	<td>Comment
	<td bgcolor=#cdeffc><input type="comment" name="comment" size=30 maxlength=100>
<tr bgcolor=#edf3fa><td>Are they team discuss groups?
    <td bgcolor=#cdeffc>
		<input type="radio" name="isgroup" value=0 checked>No
	    <input type="radio" name="isgroup" value=1>Yes
<tr bgcolor=#edf3fa><td>Dicsuss group read permission
    <td bgcolor=#cdeffc><input type="radio" name="access" value="0" checked>Public
    <input type="radio" name="access" value="1">Private(only team members can reads)
</table>
<input type="submit" name="submit" value="確定新增"><input type="reset" name="reset">
</form>
<hr>
<input type="button" value="Back" onClick="location.href='dis_list.php?PHPSESSID=PHP_ID'">
</center>
</body>
</html>
