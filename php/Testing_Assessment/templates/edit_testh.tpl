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
　<b>第QNO題</b>
<!--
  <font color="#FF0000">文字輸入請不要使用雙引號</font><br>
  <font color="#FF0000">請注意英文答案大小寫，標點符號及空格</font><br>
//-->  
　<form method=POST action=ACT1 name=testtype>
　此題題型:<select size=1 name=type onChange="Type();">
<option value=0 TP0>請選擇題型</option>
<option value=1 TP1>選擇題</option>
<option value=2 TP2>是非題</option>
<option value=3 TP3>填充題</option>
<option value=4 TP4>問答題</option>
</select>
<input type=hidden name=action value=ACT2>
<input type=hidden name=exam_id value="EXAMID">
</form>
</center>
ENDLINE
