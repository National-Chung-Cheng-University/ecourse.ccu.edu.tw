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
	var msg = '�п�J';

	if(newblock.block_title.value.length==0) {
		error = false;
		msg = msg + ' �D�D���D';
	}
	
	if(!error) {
		alert(msg);
	}
	return error;
}

function check_del_block( block_id ) {
	if( confirm('�o�N�|�R���Ҧ����󦹥D�D�����e(�]�A�U�ݨ����e), �O�_�~��?') ) {
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
�A�ثe�ҽs�誺�O���ݨ���&nbsp;&quot;�D�D���D&quot;&nbsp;</font>
<form action="editor_main.php" method="post" name="newblock" onSubmit="return check_new_block();">

<table border=1>
<caption>
&quot;�D�D���D&quot;���ݨ����j�D�����A<br>
�Y���ݤ����A�ЦA����J&quot;�ݨ��W��&quot;�Y�i<br>
</caption>
			
<!-- BEGIN DYNAMIC BLOCK: block_list -->
<tr bgcolor=ED_COLOR>
	<td>BLOCK_TITLE</td>
	<td>BLOCK_BUTTOM</td>
	<td>BLOCK_EXIT</td>
</tr>
<!-- END DYNAMIC BLOCK: block_list -->
<tr>
	<td height="84" colspan=2>
	  <table border="0">
        <tr>
          <td bgcolor="#cdeffc"><div align="center">�s�W�D�D���D�G
                <input type="text" name="block_title" size=30>
                <br>
                <input type="hidden" name="action" value="newblock">
                <input type="hidden" name="q_id" value="QUESID">
                <input type="submit" name="submit" value="�s�W">
                <input type="reset" name="reset" value="���s��J">
          </div></td>
        </tr>
      </table>
	</td>
</tr>
</table>

<p>&nbsp;</p>
</form>

<p>�s�W���D�D���D��A�Цb����𪬹��I�@�U�A��s�W��&quot;�D�D���D&quot;�A��i�s���D��
</p>
<p>�����G����𪬹ϬҬ��s��</p>
</center>
</body>
</html>