<HTML>
<head>
<META HTTP-EQUIV="Expires" CONTENT=0>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=big5">

<script language="JavaScript">
function select_all() 
{  
	for(var i=0;i<document.all.form1.elements.length;i++)
	{
		var e=document.all.form1.elements[i]; 
		if( (e.name != 'state') && e.name.indexOf('state', 0) == 0 )
		{
			 var a = document.getElementsByName(e.name);	
			 
			 if(document.all.state[0].checked) 	 
				  a[0].checked = true; 	
			 else if(document.all.state[1].checked)
				  a[1].checked = true; 	
		}
	}
}
function preview(){
	//alert(document.all.grade692410079.value);
	
	for(var i=0;i<document.all.form1.elements.length;i++)
	{
		var e=document.all.form1.elements[i];
		if( (e.name != 'grade') && e.name.indexOf('grade', 0) == 0 )
		{
			 var a = document.getElementsByName(e.name);	
			 
			 if(a[0].value!=''){
			 	alert(e.name.substring(5, e.name.length) + ":" + a[0].value);
			 }
		}
	}
	
}

function doSubmit( str ){
	document.form1.action.value = str;
	document.form1.submit();
}

</script>
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css">
<title>成績查詢</title><style type="text/css">
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
<BODY background="/images/skinSKINNUM/bbg.gif">
<center>
<table><tr>
<td>
<form>
<input type="button" name="download_excel" value="下載修課學生列表" onClick="location.href='../../DOWNLOAD_F';">
</form>
</td>
<td>
<form method=POST action=uploadgrade.php>
<input type=hidden  name=course_id value=COURSE_ID>
<input type=hidden  name=year value=YEAR>
<input type=hidden  name=term value=TERM>
<input type=hidden  name=action value=upload_excel>
<input type=submit  value="上傳期末總成績Excel檔">
</form>
</td></tr>
</table>
<form name="form1" action="BGUpload.php" method="post" target="_blank">
<input type=hidden  name=course_id value=COURSE_ID>
<input type=hidden  name=year value=YEAR>
<input type=hidden  name=term value=TERM>
<p align="center" class="style4">學期成績列表</p>
<a href="../../php/Courses_Admin/teach_course.php">回到開課列表</a>
<table width="536" border="0">
  <tr>
    <td width="150">YEAR學年　第TERM學期 </td>
    <td width="199">GNAME</td>
    <td width="173">列表日期：DATE</td>
    </tr>
  <tr>
    <td>科目：CID</td>
    <td>CNAME</td>
    <td>班別：GID　學分數：CREDIT<div align="left"></div></td>
    </tr>
  <tr>
    <td colspan="3">教師：TEACHER</td>
    </tr>
</table>
<BR>

<p><span class="style1">注意！</span><span class="style1"><span class="style5">成績上傳前請留意右方所選擇的上傳狀態是否正確，</span><br>標題列內之選項可切換全部學生的上傳狀態</span></p>

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
<div align="center"><font color="#FFFFFF" size="2">是否上傳成績<br>
    <input type='radio' name='state' value='1' onClick='select_all();' checked>是 <input type='radio' name='state' value='0' onClick='select_all();' >
否</font></div>
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
<input type="button" name="preview" value="預覽列印" onClick="doSubmit('preview');">
<input type="button" name="upload" value="成績上傳及列印學期成績單" onClick="doSubmit('upload');">
<p><span class="style1">注意！</span><span class="style1"><span class="style5">成績可分批次上傳但每位學生成績資料僅限上傳一次</span>，</span><span class="style1"><span class="style5">一經上傳不得更改，</span><br>
      若該科目為二位以上老師合開課程，請老師計算成績後由一位教師登錄學期總成績。</span></p>
</form>

</center>
</BODY>
</HTML>