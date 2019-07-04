<html>
<head>
<title>TITLE</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<STYLE type=text/css>
 body { SCROLLBAR-FACE-COLOR: #4d6eb2; SCROLLBAR-ARROW-COLOR: #FFFFFF; SCROLLBAR-TRACK-COLOR: #DDDDFF; SCROLLBAR-SHADOW-COLOR: #054878; SCROLLBAR-3DLIGHT-COLOR: black; }
</STYLE> 
<link rel="stylesheet" href="/images/skin1/css/main-body.css" type="text/css">
</head>
<body background="/images/img/bg.gif" RELOAD_CTRL>
<script language="JavaScript1.2">
function goal_sec_delete( goal, number, group_id ) {
	if( confirm('這將會刪除所有關於此教學目標或教學核心能力，是否繼續？') ) {
		location.href = "delete.php?goal=" + goal + "&number=" + number+ "&group_id=" + group_id + "&PHPSESSID=827537d2fb7cfd8988031394c80fb467";
		return true;
	}
	else
		return false;
}
</script>
	<center>
	<font color="red"><br>你目前所編輯的是_GROUP的&nbsp;&lt;EDITOR&gt;&nbsp;</font>
	<table border=1>
	<tr bgcolor=#4d6be2>
		<td width=100><font color="#ffffff">索引</font></td>
		<td width=300><font color="#ffffff">STR_TIT</font></td><td rowspan=2 width=60></td></tr>
	<tr bgcolor=#4d6be2><td colspan=2><font color="#ffffff">STR_CON</font></td></tr>
<!-- BEGIN DYNAMIC BLOCK: goal_list -->
	<tr><td width=100>NUM</td><td witdh=300>TITLE</td>
		<td rowspan="2" width="60" align="center"><input type="button" value="刪除" OnClick="return goal_sec_delete(CURR, SEC, AID)"></td></tr>
	<tr><td colspan="2">CONTENT</td></tr>
<!-- END DYNAMIC BLOCK: goal_list -->
	</table>
	<hr>
	ERROR
	<form method="post" action="input.php" >
	<table border=1 width="700">
	<tr bgcolor="#cdeffc">
		<td><input type="radio" name="addkind" value="append" checked/>新增至最後</td>
		<td><input type="radio" name="addkind" value="insert">選擇插入點：<br/>INSERT</td>
		<td><input type="radio" name="addkind" value="replace">修改索引值：<br/>REPLACE</td>
	</tr>
	TIT_GOAL
	<tr bgcolor="#cdeffc"><td colspan="3" align="center">STR_CON<br><textarea rows="5" cols="50" name="content"></textarea></td></tr>
	</table>
	<br>
	<input type="hidden" name="group_id" value="GROUPID">
	<input type="hidden" name="goal" value="CURRENT">
	<input type=submit value="新增/修改"><input type=reset value="重新輸入">
	</form>
	</center>
</body>
</html>
