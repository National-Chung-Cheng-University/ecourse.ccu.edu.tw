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
		alert("�п�J�s���D�D���D");
		return false;
	}
	else
		return true;
}

function check_del_item( item_id ) {
	var linktarget;
	linktarget = "editor_main.php?block_id=BLOCK_ID&item_id=" + item_id + "&q_id=" + QUESID + "&action=delitem&PHPSESSID=PHP_ID";
	if( confirm('�o�N�|�R���Ҧ����󦹸`�������P�s������, �O�_�~��?') ) {
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
�A�ثe�ҽs�誺�O���ҵ{��&nbsp;&lt;�� BLOCK_NUM �D�D&gt;&nbsp;.</font>
<form action="editor_main.php" method="post" name="changeblock" onSubmit="return check_chgblock();">

<table border=1 cellpadding="3">
<tr bgcolor=#cdeffc>
	<td><div align="center">�p�����&nbsp;&lt;��BLOCK_NUM�D�D&gt;&nbsp;���D�п�J�s���D�G<br>
          <input type="text" name="block_title" size=30><br>
	      <input type="submit" name="submit" value="�T�w">
          <input type="reset" name="reset" value="���s��J">
          <input type="hidden" name="action" value=updateblock>
          <input type="hidden" name="block_id2" value="BLOCK_ID">
          <input type="hidden" name="q_id2" value="QUESID">
          <input type="hidden" name="bno2" value="BLOCK_NUM">    
        </div></td></tr>
</table>

</form>

<hr width="300" noshade>
<br>
�ݨ��D�ئC��
�G
<table border=1>
<!-- BEGIN DYNAMIC BLOCK: item_list -->
<tr bgcolor=ED_COLOR>
	<td>ITEM_NUM</td>
	<td>ITEM_TYPE</td>
	<td>ITEM_BUTTOM</td>
</tr>
<!-- END DYNAMIC BLOCK: item_list -->
</table>

<br>
<hr width="300" noshade>
<p>�s�W�D�ءG<b>��QNO�D</b></p>
<form method=POST action=editor_main.php name=qtype>
���D�D��:<select size=1 name=type onChange="Type();">
<option value=0 TP0>�п���D��</option>
<option value=1 TP1>����D</option>
<option value=2 TP2>²���D</option>
</select>
<input type=hidden name=q_id value="QUESID">
<input type=hidden name=block_id value="BLOCK_ID">
<input type=hidden name=bno value="BLOCK_NUM">
</form>
ENDLINE