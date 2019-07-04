<html>
<head>
<title> TITLE </title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<STYLE type=text/css>
 body { SCROLLBAR-FACE-COLOR: #4d6eb2; SCROLLBAR-ARROW-COLOR: #FFFFFF; SCROLLBAR-TRACK-COLOR: #DDDDFF; SCROLLBAR-SHADOW-COLOR: #054878; SCROLLBAR-3DLIGHT-COLOR: black; }
</STYLE>
<SCRIPT LANGUAGE="JavaScript">
<!--
function check_new_block( ) {
	var error = true;
	var msg = 'Please Input';

	if(newblock.block_title.value.length==0) {
		error = false;
		msg = msg + ' Subject Title';
	}
	
	if(!error) {
		alert(msg);
	}
	return error;
}

function check_del_block( block_id ) {
	if( confirm('This action will replace current content.\nDo you wish to continue?') ) {
		location.href = "editor_main.php?action=delblock&q_id=QUESID&block_id=" + block_id + "&PHPSESSID=PHP_ID";
		return true;
	}
	else
		return false;
}
//-->
</SCRIPT>
</head>
<body background="/images/img/bg.gif"RELOAD_CTRL>
<IMG SRC="/images/img/a313.gif">
<center>
<font color="red">ERROR_MSG<br>
You are editing &nbsp;&lt;introduce&gt;&nbsp; of this Questionary now</font>
<form action="editor_main.php" method="post" name="newblock" onSubmit="return check_new_block();">

<table border=1>
<caption>Before edit content of any Subject, please input &nbsp;&lt;Subject Title&gt;&nbsp;.
			<br>Input a existing Subject number and title will update current data</caption>
			
<!-- BEGIN DYNAMIC BLOCK: block_list -->
<tr bgcolor=ED_COLOR>
	<td>BLOCK_TITLE</td>
	<td>BLOCK_BUTTOM</td>
</tr>
<!-- END DYNAMIC BLOCK: block_list -->
<tr bgcolor=#cdeffc>
	<td colspan=2>Subject Title<input type="text" name="block_title" size=50></td>
</tr>
</table>

<input type="hidden" name="action" value="newblock">
<input type="hidden" name="q_id" value="QUESID">
<input type="submit" name="submit" value="add/update title"><input type="reset" name="reset" value="Reset">
</form>

</center>
</body>
</html>