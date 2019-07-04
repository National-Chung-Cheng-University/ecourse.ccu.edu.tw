<HTML>
<head>
<script language="JavaScript">
<!--
function Type(){
	document.qtype.submit()
}
//-->
</script>
</head>
<BODY background="/images/img/bg.gif">
<p>
<img src="/images/img/IMG">
</p>
¡@<font color="#FF0000">MESSAGE</font><br>
¡@<b>No. QNO</b>
¡@<form method=POST action=ACT1 name=qtype>
¡@Type:<select size=1 name=type onChange="Type();">
<option value=0 TP0>Choise Type</option>
<option value=1 TP1>Selection</option>
<option value=2 TP2>Q&A</option>
<option value=3 TP3>Separate</option>
</select>
<input type=hidden name=action value=ACT2>
<input type=hidden name=questionary_id value="QUESID">
</form>
</center>
ENDLINE