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
<input type=hidden  name=course_id value=COURSE_ID>
<input type=hidden  name=year value=YEAR>
<input type=hidden  name=term value=TERM>
<input type=hidden  name=action value=upload_excel>
<input type=submit  value="�W�Ǵ����`���ZExcel��">
</form>
</td></tr>
</table>
<form name="form1" action="BGUpload.php" method="post" target="_blank">
<input type=hidden  name=course_id value=COURSE_ID>
<input type=hidden  name=year value=YEAR>
<input type=hidden  name=term value=TERM>
<p align="center" class="style4">�Ǵ����Z�C��</p>
<a href="../../php/Courses_Admin/teach_course.php">�^��}�ҦC��</a>
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
<input type="button" name="preview" value="�w���C�L" onClick="doSubmit('preview');">
<input type="button" name="upload" value="���Z�W�ǤΦC�L�Ǵ����Z��" onClick="doSubmit('upload');">
<p><span class="style1">�`�N�I</span><span class="style1"><span class="style5">���Z�i���妸�W�Ǧ��C��ǥͦ��Z��ƶȭ��W�Ǥ@��</span>�A</span><span class="style1"><span class="style5">�@�g�W�Ǥ��o���A</span><br>
      �Y�Ӭ�ج��G��H�W�Ѯv�X�}�ҵ{�A�ЦѮv�p�⦨�Z��Ѥ@��Юv�n���Ǵ��`���Z�C</span></p>
</form>

</center>
</BODY>
</HTML>