<HTML>
<head>
<META HTTP-EQUIV="Expires" CONTENT=0>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=big5">
<title>學期成績</title>
<style type="text/css">
<!--
.style4 {font-family: "標楷體";
	font-weight: bold;
	font-size: x-large;
}
.style5 {font-family: "標楷體"}
-->
</style>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}

function printPage() {
	if (window.print) {
		agree = confirm('本表共TOTAL 頁，請按確定後開始列印本頁，\n\n若資料超過1頁者，系統在列印時將會自動分頁。');
		if (agree) window.print(); 
	}
}

//-->
</script>
</head>
<Body OnLoad="printPage()">
<center>
<!--<form name="form1" method="post" action="">-->
<!-- BEGIN DYNAMIC BLOCK: comment -->
  <table width="642" border="0">
    <tr>
      <td width="642" rowspan="2"><div align="center"><span class="style4">國立中正大學 學期成績登記表</span></div></td>
      <!--<td width="230"><div align="right">在校生送交截止日：SDLINE</div></td>-->
    </tr>
    <tr>
      <!--<td><div align="right">畢業班送交截止日：GDLINE</div></td>-->
    </tr>
  </table>
  <table width="642" border="0">
    <tr>
      <td width="180"><div align="left">YEAR學年　第TERM學期 </div></td>
      <td colspan="2">GNAME</td>
      <td width="164"><div align="right">印製日期：DATE</div></td>
    </tr>
    <tr>
      <td>科目：CID</td>
      <td width="331">CNAME</td>
      <td colspan="2">班別：GID　學分數：CREDIT</td>
    </tr>
    <tr>
      <td colspan="3">教師：TEACHER</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <table width="642" border="0">
    <tr>
      <td width="482">(修課人數：STD　)</td>
      <td width="224">本表共TOTAL頁　第
      <!--    <select name="menu1" onChange="MM_jumpMenu('self',this,0)">-->
		  	PLIST
      <!--    </select> -->
	  頁</td>
      <td width="140"><span class="style5">教師簽章：</span></td>
    </tr>
  </table>
  <table border="1" cellspacing="0" bordercolor="#000000">
    <tr height="30">
      <td width="140"><div align="center">系所班級</div></td>
      <td width="80"><div align="center">學號</div></td>
      <td width="70"><div align="center">姓名</div></td>
      <td width="40"><div align="center">成績</div></td>
      <td width="140"><div align="center">系所班級</div></td>
      <td width="80"><div align="center">學號</div></td>
      <td width="70"><div align="center">姓名</div></td>
      <td width="40"><div align="center">成績</div></td>
    </tr>
	<!-- BEGIN DYNAMIC BLOCK: grade -->
      DATA
	<!-- END DYNAMIC BLOCK: grade -->
  </table>
   DIVIDEPAPER
<!-- END DYNAMIC BLOCK: comment -->
<!--</form>-->
</center>
</body>
</html>
