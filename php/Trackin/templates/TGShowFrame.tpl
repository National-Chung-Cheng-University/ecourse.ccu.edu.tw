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

function IsNumeric(sText)
{
   //var ValidChars = "0123456789.";
   var ValidChars = "0123456789";
   var IsNumber=true;
   var Char;

 
   for (i = 0; i < sText.length && IsNumber == true; i++) 
      { 
      Char = sText.charAt(i); 
      if (ValidChars.indexOf(Char) == -1) 
         {
         IsNumber = false;
         }
      }
   return IsNumber;
   
   }

function rightTrim(sString) 
{
while (sString.substring(sString.length-1, sString.length) == ' ')
{
sString = sString.substring(0,sString.length-1);
}
return sString;
}

function checkGrd(){
	//檢查輸入之成績是否正確	
	for(var i=0;i<document.all.form1.elements.length;i++)
	{
		var e=document.all.form1.elements[i];
		if( (e.name != 'grade') && e.name.indexOf('grade', 0) == 0 )
		{
			 var a = document.getElementsByName(e.name);	
			 a[0].value=rightTrim(a[0].value);
			 if(a[0].value!=''){			 
			 	//alert(e.name.substring(5, e.name.length) + ":" + a[0].value);
			 	if ( a[0].value <0 || a[0].value >100 || !(IsNumeric(a[0].value)))
			 		return false;
			 }
		}
	}
	return true;
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
	if (!checkGrd()) {
		alert ("輸入之成績錯誤！\n請檢查成績須為數字且在0~100間整數。");
		return false;
	}
	if (str == "savescore") document.form1.target = "";
	document.form1.action.value = str;
	document.form1.submit();
}

function loadSavedScore() {
	window.location = "/php/Trackin/TGShowFrame.php?loadtemp=1";
}

function ShowHint() {
	alert("這門課上次已有暫存成績，如要載入上次儲存的結果，請按 [載入暫存的成績] 按鈕！");
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
<input type=hidden  name=action value=upload_excel>
<input type=submit  value="上傳期末總成績Excel檔">
</form>
</td></tr>
</table>
<form name="form1" action="TGUpload.php" method="post" target="_blank">
<p align="center" class="style4">學期成績列表</p>
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
<input type="hidden" name="course_id" value="COURSEID">
<input type="hidden" name="uploaded_count" value="UPLOADED">
<input type="hidden" name="year" value="#!YEAR!#">
<input type="hidden" name="term" value="#!TERM!#">
<input type="button" name="savescore" value="暫存成績" onClick="doSubmit('savescore');">
<input type="button" name="loadsavedscore" value="載入暫存的成績" onClick="javascript:loadSavedScore();">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" name="preview" value="預覽列印" onClick="doSubmit('preview');">
<!-- <input type="button" name="upload" value="成績上傳及列印學期成績單" onClick="doSubmit('upload');"> -->
<input type="button" name="upload_preview" value="成績上傳及列印學期成績單" onClick="doSubmit('upload_preview');">
<p><span class="style1">注意！</span><span class="style1"><span class="style5">成績可分批次上傳但每位學生成績資料僅限上傳一次</span>，</span><span class="style1"><span class="style5">一經上傳不得更改，</span><br>
      若該科目為二位以上老師合開課程，請老師計算成績後由一位教師登錄學期總成績。</span></p>
</form>

</center>

<script language="javascript">#!COMMAND!#</script>

</BODY>
</HTML>
