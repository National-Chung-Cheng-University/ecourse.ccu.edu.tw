<HTML>
<HEAD>
<title>TITLE</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css">
<script language="JavaScript">

function Change() {
	Shcedule.flag.value=0;
	Shcedule.submit();
}

function Check() {
	var msg;
	if(Shcedule.subject.value == "" && Shcedule.AddKind[0].checked == true) {
		msg = "確定要刪除\n第" + Shcedule.week.value + Shcedule.unit.value + "\n的課程安排嗎?";
		return confirm(msg);
	}
	if(Shcedule.subject.value == "" && Shcedule.AddKind[1].checked == true ) {
		alert("請輸入標題!");
		return false;
	}
	if(Shcedule.inswk.value == "0" && Shcedule.AddKind[1].checked == true ) {
		alert("請選擇插入點!");
		return false;
	}
	return true;
}
</script>
</HEAD>
<BODY background=/images/skinSKINNUM/bbg.gif>
<div align="center">TITLE</div>
<center>
<font color="#ff0000">MES</font><BR>
<table border="0" align="center" cellpadding="0" cellspacing="0" width="80%">
    <tr> 
      <td> 
        <div align="right"><img src="/images/skinSKINNUM/bor/bor_01.GIF" width="12" height="11"></div>
      </td>
      <td> 
        <div align="center"><img src="/images/skinSKINNUM/bor/bor_02.GIF" width="100%" height="11"></div>
      </td>
      <td> 
        <div align="left"><img src="/images/skinSKINNUM/bor/bor_03.GIF" width="17" height="11"></div>
      </td>
    </tr>
    <tr> 
      <td height=10> 
        <div align="right"><img src="/images/skinSKINNUM/bor/bor_04.GIF" width="12" height="100%"></div>
      </td>
      <td bgcolor="#CCCCCC"> 
        <table border=0 cellpadding="3" cellspacing="1" width="100%">
<!-- BEGIN DYNAMIC BLOCK: sch_list -->
<TR bgcolor="COLOR" align=center>
<Td><font size=2>WEEK</font></Td>
<Td><font size=2>DAY</font></Td>
<Td><font size=2>SUBJECT</font></Td>
</TR>
<!-- END DYNAMIC BLOCK: sch_list -->
</table>
      </td>
      <td height = 10> 
        <div align="left"><img src="/images/skinSKINNUM/bor/bor_06.GIF" width="17" height="100%"></div>
      </td>
    </tr>
    <tr> 
      <td> 
        <div align="right"><img src="/images/skinSKINNUM/bor/bor_07.GIF" width="12" height="17"></div>
      </td>
      <td> 
        <div align="center"><img src="/images/skinSKINNUM/bor/bor_08.GIF" width="100%" height="17"></div>
      </td>
      <td> 
        <div align="left"><img src="/images/skinSKINNUM/bor/bor_09.GIF" width="17" height="17"></div>
      </td>
    </tr>
  </table>
ENDLINE