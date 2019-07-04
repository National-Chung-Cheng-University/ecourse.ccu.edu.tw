<HTML>
<head>
<META HTTP-EQUIV="Expires" CONTENT=0>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=big5">

<script language="JavaScript">

function doSubmit( str ){
	document.form1.action.value = str;
	document.form1.submit();
}

</script>
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css">
<title>學期成績上傳預覽</title><style type="text/css">
<!--
.style1 {color: #FF0000}
.style4 {
	font-family: "標楷體";
	font-weight: bold;
	font-size: large;
}
.style5 {
	font-size: large;
	font-weight: bold;
}
-->
</style></head>
<BODY background="/images/skinSKINNUM/bbg.gif"  onload="javascript:alert('此頁面為預覽，要在按下「確定上傳,列印學>期成績單」鈕時才會真正上傳成績')" >
<center>
<form name="form1" action="TGUpload2.php" method="post">
<BR>

<p align="center" class="style4">學期成績上傳預覽</p>
<table width="536" border="0">
  <tr>
    <td width="150">YEAR學年　第TERM學期 </td>
    <td width="199">GNAME</td>
    <td width="173">列表日期：DATE</td>
    </tr>
  <tr>
    <td>科目：CID </td>
    <td>CNAME SDB</td>
    <td>班別：GID　學分數：CREDIT<div align="left"></div></td>
    </tr>
  <tr>
    <td colspan="3">教師：TEACHER</td>
    </tr>
  <tr>
    <!--td colspan="3">修課人數共計： STD人, 已上傳 UPLOADED人, 尚未上傳 YETUPLOAD人, 本次上傳 <font color='red'>UPLOADING</font>人 </td-->
    <td colspan="3">修課人數共計： STD人, 已上傳 UPLOADED人, 本次上傳 <font color='red'>UPLOADING</font>人 </td>
    </tr>
</table>

<p><span class="style1">請注意！請詳細檢查下列成績資料, 然後按「確定上傳,列印學期成績單」上傳成績資料﹐確定後即不可再修改成績！！</span>

<div align="center"><input type="button" name="upload" value=" 確定上傳,列印學期成績單 " onClick="javascript:if(confirm('您確認要上傳成績資料？\n按下「確定」後﹐即無法再修改。'))doSubmit('upload');">&nbsp;&nbsp;
<input type="button" name="upload" value="  取 消  " onClick="javascript:window.close();"></div><br>

<table border="0" align="center" cellpadding="0" cellspacing="0" width="86%">
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
<table border=0 cellspacing=1 cellpadding=3 width="100%" >
<tr bgcolor="#000066" >
<th width="24%"> 
<div align="center"><font color="#FFFFFF" size="2">系所</font></div>
</th>
<th width="19%"> 
<div align="center"><font color="#FFFFFF" size="2">學號</font></div>
</th>
<th width="20%"> 
<div align="center"><font color="#FFFFFF" size="2">姓名</font></div>
</th>
<th width="15%"><font color = "#FFFFFF" size="2">總成績</font></th>
<th width="22%"> 
<div align="center"><font color="#FFFFFF" size="2">是否上傳成績<br></div>
</th> 
</tr>
<!-- BEGIN DYNAMIC BLOCK: grade -->
DATA
<!-- END DYNAMIC BLOCK: grade -->
</table>
</td>
<td height=10> 
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
<br>
<input type="hidden" name="action" value="">
<input type="hidden" name="course_id" value="COURSEID">
<input type="hidden" name="year" value="#!YEAR!#">
<input type="hidden" name="term" value="#!TERM!#">
<div align="center"><input type="button" name="upload" value=" 確定上傳,列印學期成績單 " onClick="javascript:if(confirm('您確認要上傳成績資料？\n按下「確定」後﹐即無法再修改。'))doSubmit('upload');">&nbsp;&nbsp;
<input type="button" name="upload" value="  取 消  " onClick="javascript:window.close();"></div>
</form>
</center>


</BODY>
</HTML>
