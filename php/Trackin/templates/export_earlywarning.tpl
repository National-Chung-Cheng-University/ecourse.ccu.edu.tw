<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<script language="javascript" >
function returnAdminPage(){
	window.history.back();
}

function changeStyle(obj, type){
	var row = obj.parentNode.parentNode;
	//�����Ҧ��\��
	var tmp_i =  obj.parentNode.parentNode.parentNode.getElementsByTagName('input');
	for(var i=0; i<tmp_i.length; i++)
		if(tmp_i.item(i).type == 'text')
			tmp_i.item(i).disabled = true;
	var tmp_s =  obj.parentNode.parentNode.parentNode.getElementsByTagName('select');
	for(var i=0; i<tmp_s.length; i++)	tmp_s.item(i).disabled = true;	
	//���N�쥻�C��O #CCFFCC �٭�
	var tmp = document.getElementsByTagName('tr');
	for(var i=1; i<tmp.length; i++)
		tmp.item(i).style.backgroundColor = "";	
	//�N��ܪ��ܦ�
	row.style.backgroundColor ="#CCFFCC";	
	//�N�I�諸 �\��}��
	switch(type){
		case '1': case '2':
			var input = row.getElementsByTagName('input');
			input.item(1).disabled = false;
			input.item(2).disabled = false;
			break;
		case '3': case '4':
			var input = row.getElementsByTagName('select');
			input.item(0).disabled = false;
			break;
		case '5':
			break;
		default:
			break;
	}			
}
</script>
<title>�ץX�ǥ͹wĵ�W��(Excel�榡)</title>
</head>
<body background = "../images/img/bg.gif">
	<div>
	</div>
<br />
<font color="#0000FF">�ǥ͹wĵ�W��U��</font>
<br />
<form method="post" action="export_earlywarning.php?action=select">
<table border="1" bordercolor="#006666">
<tr  bgcolor="#588ccc" >
	<td width="36"><font color="#ffffff"> ��� </font></td>
	<td width="142"><font color="#ffffff"> ���O </font></td>
	<td width="398"><font color="#ffffff"> ��J </font></td>
</tr> 
<tr>
	<td><input type="radio" name="type" value="1" onClick="changeStyle(this, this.value);" /></td>
	<td>�Ǧ~ / �Ǵ�</td>
	<td>
		�Ǧ~ <input type="text" name="year" size="5" disabled /> / 
		�Ǵ� <input type="text" name="term" size="5" disabled />
	</td>
</tr>
<tr>
	<td><input type="radio" name="type" value="2" onClick="changeStyle(this, this.value);" /></td>
	<td>��ئW�� / ��إN�X</td>
	<td>
		��ئW�� <input type="text" name="course_name" size="15" disabled /> / 
		��إN�X <input type="text" name="course_no" size="15" disabled />
	</td>
</tr>
<tr>
	<td><input type="radio" name="type"  value="3" onClick="changeStyle(this, this.value);" /></td>
	<td>�t�� (�t�ҥN�X)</td>
	<td>
		<select name="group"  disabled >
		GROUP
		</select>
	</td>
</tr>
<tr>
	<td><input type="radio" name="type"  value="4" onClick="changeStyle(this ,this.value);" /></td>
	<td>��]</td>
	<td>
		<select name="reason"  disabled >
			<option value='0'>�ݥ[�j��]</option>
			<option value='1'>���Z����</option>
			<option value='2'>�ʽ�</option>
			<option value='3'>���Z���ΥB�ʽ�</option>
		</select>
	</td>
</tr>
<tr style="background-color:#CCFFCC;">
	<td><input type="radio" name="type"  value="5" onClick="changeStyle(this, this.value);" checked /></td>
	<td colspan="2">�����ץX</td>
</tr>
</table>
<input type="submit" name="submit" value="�T�w�e�X" />
</form>
<table border="1" bordercolor="#006666">
<tr  bgcolor="#588ccc">
	<td><font color="#FFFFFF">�o�O</font><font color="#FF0000">OUTPUT_TYPE</font><font color="#FFFFFF">���ץX�ɮ�</font></td>
	<td><font color="#FFFFFF"><a href="FILE_PATH">�U��</a></font></td>
</tr>	
</table>
<br /><br />
<a href="../check_admin.php">��^�޲z����</a>		
</body>
</html>
