<html>
<head>
<title>已上傳課程大綱列表</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<link rel="stylesheet" href="/images/skinSKINNUM/css/01.css" type="text/css">
<script language="JavaScript">
<!--
//SYSwindow.open('../sysnews.php?PHPSESSID=PHPSID','','resizable=0,scrollbars=1,width=290,height=420');
// -->
function Show(){
	document.show.submit()
}
</script>
<style type="text/css">
<!--
.style1 {
	font-family: "標楷體";
	font-size: x-large;
}
.style2 {color: #E6FFFC}
.style3 {color: #F0FFEE}
.style4 {font-size: medium}
-->
</style>
</head>
<body bgcolor="#FFFFFF" text="#000000" link="#000066" vlink="#666666" alink="#CC0000" topmargin="0" leftmargin="0" marginwidth="0" marginheight="0">
<center>
<br>
<h1>YEAR學年度第TERM學期 STATUS列表</h1>
總共有 TOTAL 門課<br>
STATUS的有 <font color=red>TEXTBOOK</font> 門<br>
PERCENT
<form action=./no_textbook.php name=show method=get target="_top">
<select name=status onChange="Show();">
<option value="1" SELE1>已上傳課程</option>
<option value="2" SELE2>未上傳課程</option>
</select>
</form>
<a href=../check_admin.php>回系統管理介面</a>
<table border="0" align="center" cellpadding="0" cellspacing="0" width="95%">
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
  <tr height=10> 
    <td>
      <div align="right"><img src="/images/skinSKINNUM/bor/bor_04.GIF" width="12" height="100%"></div>
    </td>
    <td bgcolor="#CCCCCC"> 
<table border = 0 width =100% cellpadding="0" cellspacing="1">
<!-- BEGIN DYNAMIC BLOCK: no_textbook_list -->
<tr bgcolor="COLOR" align="center"><td width="15%"><font size=2>GNAME</font></td><td width="10%" ><font size=2 >CNO</font></td><td width=25%><font size=2>CNAME</font></td><td width=25%><font size=2>TNAME</font></td></tr>
SELF
<!-- END DYNAMIC BLOCK: no_textbook_list -->
</table>
</td>
    <td>
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
</center></body>
</html>