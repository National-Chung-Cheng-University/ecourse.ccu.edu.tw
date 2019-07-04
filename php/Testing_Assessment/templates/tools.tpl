<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<meta http-equiv="Content-Language" content="zh-tw">
<base target="main">
<script language="JavaScript" src="tools2.js">
</SCRIPT>
</head>

<body topmargin="0" leftmargin="0" bgcolor="#C0C0C0" text="#000000" onload="parent.bTool = true;">
<table cellpadding="0" cellspacing="0">
<tr>
<td>
<table border="1" cellpadding="0" cellspacing="1" height="100%">
<tr>
<td bgcolor="#C0C0C0" bordercolor="#C0C0C0" bordercolorlight="#C0C0C0" bordercolordark="#C0C0C0"><img border="0" src="/images/img/toolbar.gif" width="6" height="22"></td>
<td bgcolor="#C0C0C0" bordercolor="#C0C0C0" bordercolorlight="#C0C0C0" bordercolordark="#C0C0C0" style="font-family: 新細明體; font-size: 9pt" nowrap>工具</td>
<td id="tool" tool="cursor" bordercolorlight="#FFFFFF" bordercolordark="#808080" bgcolor="#C0C0C0"><img border="0" src="/images/img/cursor.gif" width="22" height="22"></td>
<td bgcolor="#C0C0C0" bordercolorlight="#C0C0C0" bordercolordark="#C0C0C0">　</td>
<td id="tool" tool="right" bordercolorlight="#808080" bordercolordark="#FFFFFF" bgcolor="#C0C0C0"><img border="0" src="/images/img/right.gif" width="22" height="22"></td>
<td id="tool" tool="wrong" bordercolorlight="#808080" bordercolordark="#FFFFFF" bgcolor="#C0C0C0"><img border="0" src="/images/img/wrong.gif" width="22" height="22"></td>
<td id="tool" tool="quest" bordercolorlight="#808080" bordercolordark="#FFFFFF" bgcolor="#C0C0C0"><img border="0" src="/images/img/quest.gif" width="22" height="22"></td>
<td bgcolor="#C0C0C0" bordercolorlight="#C0C0C0" bordercolordark="#C0C0C0">　</td>
<td id="tool" tool="freehand" bordercolorlight="#808080" bordercolordark="#FFFFFF" bgcolor="#C0C0C0"><img border="0" src="/images/img/freehand.gif" width="22" height="22"></td>
<td id="tool" tool="line" bordercolorlight="#808080" bordercolordark="#FFFFFF" bgcolor="#C0C0C0"><img border="0" src="/images/img/line.gif" width="22" height="22"></td>
<td id="tool" tool="arrow" bordercolorlight="#808080" bordercolordark="#FFFFFF" bgcolor="#C0C0C0"><img border="0" src="/images/img/arrow.gif" width="22" height="22"></td>
<td id="tool" tool="rect" bordercolorlight="#808080" bordercolordark="#FFFFFF" bgcolor="#C0C0C0"><img border="0" src="/images/img/rect.gif" width="22" height="22"></td>
<td id="tool" tool="circle" bordercolorlight="#808080" bordercolordark="#FFFFFF" bgcolor="#C0C0C0"><img border="0" src="/images/img/circle.gif" width="22" height="22"></td>
<td id="tool" tool="rrect" bordercolorlight="#808080" bordercolordark="#FFFFFF" bgcolor="#C0C0C0"><img border="0" src="/images/img/roundrect.gif" width="22" height="22"></td>
<td id="tool" tool="font" bordercolorlight="#808080" bordercolordark="#FFFFFF" bgcolor="#C0C0C0"><img border="0" src="/images/img/font.gif" width="22" height="22"></td>
<td id="tool" tool="image" bordercolorlight="#808080" bordercolordark="#FFFFFF" bgcolor="#C0C0C0"><img border="0" src="/images/img/image.gif" width="22" height="22" ></td>
<td bgcolor="#C0C0C0" bordercolorlight="#C0C0C0" bordercolordark="#C0C0C0">　</td>
</tr>
</table>
</td>
<td>
<table border="1" cellpadding="0" cellspacing="1" style="font-family: 新細明體; font-size: 9pt">
<tr>
<td bgcolor="#C0C0C0" bordercolor="#C0C0C0" bordercolorlight="#C0C0C0" bordercolordark="#C0C0C0"><img border="0" src="/images/img/toolbar.gif" width="6" height="22"></td>
<td bgcolor="#C0C0C0" bordercolorlight="#C0C0C0" bordercolordark="#C0C0C0" style="font-family: 新細明體; font-size: 9pt" nowrap>線條</td>
<td bgcolor="#C0C0C0" bordercolorlight="#C0C0C0" bordercolordark="#C0C0C0" nowrap><select size="1" onchange="changeStrokeWeight( this.value );">
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option selected value="4">4</option>
<option value="5">5</option>
<option value="6">6</option>
<option value="7">7</option>
<option value="8">8</option>
<option value="9">9</option>
<option value="10">10</option>
</select></td>
<td bgcolor="#C0C0C0" bordercolorlight="#C0C0C0" bordercolordark="#C0C0C0" nowrap><select size="1" onchange="changeStrokeColor( this.value );">
<option value="black" style="background:black">黑色</option>
<option selected value="red" style="background:red">紅色</option>
<option value="green" style="background:green">綠色</option>
<option value="yellow" style="background:yellow">黃色</option>
<option value="gray" style="background:gray">灰色</option>
</select></td>
<td bgcolor="#C0C0C0" bordercolorlight="#C0C0C0" bordercolordark="#C0C0C0">　</td>
<td bgcolor="#C0C0C0" bordercolorlight="#C0C0C0" bordercolordark="#C0C0C0" nowrap>填滿</td>
<td bgcolor="#C0C0C0" bordercolorlight="#C0C0C0" bordercolordark="#C0C0C0" nowrap><select size="1" onchange="changeFilled( this.value );">
<option selected value="0">否</option>
<option value="1">是</option>
</select></td>
<td bgcolor="#C0C0C0" bordercolorlight="#C0C0C0" bordercolordark="#C0C0C0" nowrap><select size="1" onchange="changeFillColor( this.value );">
<option selected value="black" style="background:black">黑色</option>
<option value="red" style="background:red">紅色</option>
<option value="green" style="background:green">綠色</option>
<option value="yellow" style="background:yellow">黃色</option>
<option value="gray" style="background:gray">灰色</option>
</select></td>
<td bgcolor="#C0C0C0" bordercolor="#C0C0C0" bordercolorlight="#C0C0C0" bordercolordark="#C0C0C0"><img border="0" src="/images/img/toolbar.gif" width="6" height="22"></td>
<form target="target" id="vml_form" action="check_allwork.php" method="POST">
<td>分數<input name="vml" type="hidden"><input name="grade" size="4" style="font-family: 新細明體; font-size: 9pt"></td>
<td><input type="button" value="批閱完畢" onclick="Tool_OnSubmit();" style="font-family: 新細明體; font-size: 9pt"></td><input type=hidden name=action value=sendresult><input type=hidden name=sid value=SNO><input type=hidden name=work_id value=WORKID>
</form>
</tr>
</table>
</td>
</tr>
</table>
</body>
</html>
