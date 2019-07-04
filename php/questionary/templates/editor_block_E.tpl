<html>
<head>
<title> TITLE </title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<STYLE type=text/css>
 body { SCROLLBAR-FACE-COLOR: #4d6eb2; SCROLLBAR-ARROW-COLOR: #FFFFFF; SCROLLBAR-TRACK-COLOR: #DDDDFF; SCROLLBAR-SHADOW-COLOR: #054878; SCROLLBAR-3DLIGHT-COLOR: black; }
</STYLE>
<SCRIPT LANGUAGE="JavaScript">
<!--

function check_chgblock() {
	if(changeblock.block_title.value.length==0)	{
		alert("Please Input Subject Title");
		return false;
	}
	else
		return true;
}

function check_del_item( item_id ) {
	var linktarget;
	linktarget = "editor_main.php?block_id=BLOCK_ID&item_id=" + item_id + "&q_id=" + QUESID + "&action=delitem&PHPSESSID=PHP_ID";
	if( confirm('This will delete content of this Question.\nDo you wish to continue?') ) {
		location.href = linktarget;
		return true;
	}
	else
		return false;
}

function Type(){
	document.qtype.submit()
}
//-->
</SCRIPT></head>
<body background="/images/img/bg.gif"RELOAD_CTRL>
<IMG SRC="/images/img/a313.gif">
<center>
<font color="red">ERROR_MSG<br>
You are editing &nbsp;&lt;The BLOCK_NUM Subject&gt;&nbsp;.</font>
<form action="editor_main.php" method="post" name="changeblock" onSubmit="return check_chgblock();">

<table border=1>
<caption>Modify&nbsp;&lt;The BLOCK_NUM Subject&gt;&nbsp;Title to</caption>
<tr bgcolor=#cdeffc>
	<td><input type="text" name="block_title" size=30></td></tr>
</table>

<input type="submit" name="submit" value="Update Subject Title"><input type="reset" name="reset" value="Reset">
<input type="hidden" name="action" value=updateblock>
<input type="hidden" name="block_id" value="BLOCK_ID">
<input type="hidden" name="q_id" value="QUESID">
<input type="hidden" name="bno" value="BLOCK_NUM">
</form>

<hr>

<table border=1>
<!-- BEGIN DYNAMIC BLOCK: item_list -->
<tr bgcolor=ED_COLOR>
	<td>ITEM_NUM</td>
	<td>ITEM_TYPE</td>
	<td>ITEM_BUTTOM</td>
</tr>
<!-- END DYNAMIC BLOCK: item_list -->
</table>

<hr>
<b>Number QNO.</b>
<form method=POST action=editor_main.php name=qtype>
Type:<select size=1 name=type onChange="Type();">
<option value=0 TP0>Choice the Type</option>
<option value=1 TP1>Choice</option>
<option value=2 TP2>Q&A</option>
</select>
<input type=hidden name=q_id value="QUESID">
<input type=hidden name=block_id value="BLOCK_ID">
<input type=hidden name=bno value="BLOCK_NUM">
</form>
ENDLINE