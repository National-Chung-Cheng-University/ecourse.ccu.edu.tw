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
	//�ˬd��J�����Z�O�_���T	
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
		alert ("��J�����Z���~�I\n���ˬd���Z�����Ʀr�B�b0~100����ơC");
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
	alert("�o���ҤW���w���Ȧs���Z�A�p�n���J�W���x�s�����G�A�Ы� [���J�Ȧs�����Z] ���s�I");
}

</script>
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css">
<title>���Z�d��</title><style type="text/css">
<!--
.style1 {color: #FF0000}
.style4 {
	font-family: "�з���";
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
<input type="button" name="download_excel" value="�U���׽ҾǥͦC��" onClick="location.href='../../DOWNLOAD_F';">
</form>
</td>
<td>
<form method=POST action=uploadgrade.php>
<input type=hidden  name=action value=upload_excel>
<input type=submit  value="�W�Ǵ����`���ZExcel��">
</form>
</td></tr>
</table>
<form name="form1" action="TGUpload.php" method="post" target="_blank">
<p align="center" class="style4">�Ǵ����Z�C��</p>
<table width="536" border="0">
  <tr>
    <td width="150">YEAR�Ǧ~�@��TERM�Ǵ� </td>
    <td width="199">GNAME</td>
    <td width="173">�C�����GDATE</td>
    </tr>
  <tr>
    <td>��ءGCID</td>
    <td>CNAME</td>
    <td>�Z�O�GGID�@�Ǥ��ơGCREDIT<div align="left"></div></td>
    </tr>
  <tr>
    <td colspan="3">�Юv�GTEACHER</td>
    </tr>
</table>
<BR>

<p><span class="style1">�`�N�I</span><span class="style1"><span class="style5">���Z�W�ǫe�Яd�N�k��ҿ�ܪ��W�Ǫ��A�O�_���T�A</span><br>���D�C�����ﶵ�i���������ǥͪ��W�Ǫ��A</span></p>

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
<div align="center"><font color="#FFFFFF" size="2">�t��</font></div>
</th>
<th width="19%"> 
<div align="center"><font color="#FFFFFF" size="2">�Ǹ�</font></div>
</th>
<th width="20%"> 
<div align="center"><font color="#FFFFFF" size="2">�m�W</font></div>
</th>
<th width="15%"><font color = "#FFFFFF" size="2">�`���Z</font></th>
<th width="22%"> 
<div align="center"><font color="#FFFFFF" size="2">�O�_�W�Ǧ��Z<br>
    <input type='radio' name='state' value='1' onClick='select_all();' checked>�O <input type='radio' name='state' value='0' onClick='select_all();' >
�_</font></div>
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
<input type="button" name="savescore" value="�Ȧs���Z" onClick="doSubmit('savescore');">
<input type="button" name="loadsavedscore" value="���J�Ȧs�����Z" onClick="javascript:loadSavedScore();">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" name="preview" value="�w���C�L" onClick="doSubmit('preview');">
<!-- <input type="button" name="upload" value="���Z�W�ǤΦC�L�Ǵ����Z��" onClick="doSubmit('upload');"> -->
<input type="button" name="upload_preview" value="���Z�W�ǤΦC�L�Ǵ����Z��" onClick="doSubmit('upload_preview');">
<p><span class="style1">�`�N�I</span><span class="style1"><span class="style5">���Z�i���妸�W�Ǧ��C��ǥͦ��Z��ƶȭ��W�Ǥ@��</span>�A</span><span class="style1"><span class="style5">�@�g�W�Ǥ��o���A</span><br>
      �Y�Ӭ�ج��G��H�W�Ѯv�X�}�ҵ{�A�ЦѮv�p�⦨�Z��Ѥ@��Юv�n���Ǵ��`���Z�C</span></p>
</form>

</center>

<script language="javascript">#!COMMAND!#</script>

</BODY>
</HTML>
