<HTML>
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<script language="JavaScript">
<!--
function Type(){
	document.testtype.submit()
}
//-->
</script>
</head>
<BODY background="/images/img/bg.gif">

　<font color="#FF0000">MESSAGE</font><br>
　<b>No. QNO</b>
　<form method=POST action=ACT1 name=testtype>
　Type:<select size=1 name=type onChange="Type();">
<option value=0 TP0>Choise Type</option>
<option value=1 TP1>Selection</option>
<option value=2 TP2>Yes & No</option>
<option value=3 TP3>Fill out</option>
<option value=4 TP4>Q & A</option>
</select>
<input type=hidden name=action value=ACT2>
<input type=hidden name=exam_id value="EXAMID">
</form>
</center>
ENDLINE
