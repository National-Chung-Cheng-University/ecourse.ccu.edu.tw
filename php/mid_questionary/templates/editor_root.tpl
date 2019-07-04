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
	var msg = '請輸入';

	if(newblock.block_title.value.length==0) {
		error = false;
		msg = msg + ' 主題標題';
	}
	
	if(!error) {
		alert(msg);
	}
	return error;
}

function check_del_block( block_id ) {
	if( confirm('這將會刪除所有關於此主題的內容(包括各問卷內容), 是否繼續?') ) {
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
你目前所編輯的是本問卷的&nbsp;&quot;主題標題&quot;&nbsp;</font>
<form action="editor_main.php" method="post" name="newblock" onSubmit="return check_new_block();">

<table border=1>
<caption>
&quot;主題標題&quot;為問卷的大題分類，<br>
若不需分類，請再次輸入&quot;問卷名稱&quot;即可<br>
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
          <td bgcolor="#cdeffc"><div align="center">新增主題標題：
                <input type="text" name="block_title" size=30>
                <br>
                <input type="hidden" name="action" value="newblock">
                <input type="hidden" name="q_id" value="QUESID">
                <input type="submit" name="submit" value="新增">
                <input type="reset" name="reset" value="重新輸入">
          </div></td>
        </tr>
      </table>
	</td>
</tr>
</table>

<p>&nbsp;</p>
</form>

<p>新增完主題標題後，請在左邊樹狀圖點一下你剛新增的&quot;主題標題&quot;，方可編輯題目
</p>
<p>說明：左邊樹狀圖皆為連結</p>
</center>
</body>
</html>