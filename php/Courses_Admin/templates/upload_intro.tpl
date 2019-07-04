<HTML>
<HEAD>
<TITLE>授課清單</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<link rel="stylesheet" href="/images/skinSKINNUM/css/01.css" type="text/css">

<script language="JavaScript">
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
.style5 {
	font-size: medium;
	color:#FF0000;
	font-family: "標楷體";
}
-->
</style>
</HEAD>
<body bgcolor="#FFFFFF" text="#000000" link="#000066" vlink="#666666" alink="#CC0000" topmargin="0" leftmargin="0" marginwidth="0" marginheight="0">
<center>
<p align="center">&nbsp;</p>
 <div align="center"><a href="../logout.php" ><b><font color="#000000" size="2">登出/回首頁
</font></b></a></div>
<p align="center" class="style1">課程大綱及授課教材編輯</p>
<p align="center">己經有大綱：<span class="style2">███</span>　　　尚未有大綱：<font color="red"><!--<span class="style3">-->███</font><!--</span>--></p>

<p class="style5">GNAME NOW</p>
<form action=./upload_intro.php name=show method=get target="_top">
<select name=year_term onChange="Show();">
<!-- BEGIN DYNAMIC BLOCK: year_list -->
<option value=Y_M >YEAR_TERM</option>
<!-- END DYNAMIC BLOCK: year_list -->
</select>
</form>
<table border=1 align="center" cellpadding="0" cellspacing="0">
	<tr bgcolor=#4d6be2 align="center">
		<td width=40% colspan="2"><font size=2 color="#ffffff">IEET專區</font></td>
	</tr>
	<tr align="center">
		<td width=20%><a href="../IEET/index.php" target=_blank>編輯教育目標</a></td>
		<td width=20%><a href="../questionary/assistantpub.php" target=_blank>問卷發佈</a></td>
	</tr>
</table>
<BR>
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
<!-- BEGIN DYNAMIC BLOCK: course_list -->
<tr bgcolor="COLOR" align="center"><td width="10%"><font size=2 color=FONTCOL>GNAME</font></td>
<td width="10%" ><font size=2 color=FONTCOL>YEAR</font></td><td width="8%" ><font size=2 color=FONTCOL>CNO</font></td><td width=18%><font size=2 color=FONTCOL>CNAME</font></td><td width=7%><font size=2 color=FONTCOL>EWSTAT</font></td><td width=8%><font size=2 color=FONTCOL>FILE_DATE</font></td><td width=15%><font size=2 color=FONTCOL>CMATERIAL</font></td><td width="10%"><font size=2 color=FONTCOL>CTEACH</font></td><td width="8%"><font size=2 color=FONTCOL>EMAIL</font></td><td width="15%"><font size=2 color=FONTCOL>QUESTIONARY&nbsp;SELFEVALUATE</font></td>
</tr>
<!-- END DYNAMIC BLOCK: course_list -->
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
<!-- END DYNAMIC BLOCK: table_list -->
<a href="../Learner_Profile/chang_pass.php">修改密碼</a>
</center>
</BODY>
</HTML>
