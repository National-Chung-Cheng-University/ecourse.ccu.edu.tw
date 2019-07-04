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
	//關閉所有功能
	var tmp_i =  obj.parentNode.parentNode.parentNode.getElementsByTagName('input');
	for(var i=0; i<tmp_i.length; i++)
		if(tmp_i.item(i).type == 'text')
			tmp_i.item(i).disabled = true;
	var tmp_s =  obj.parentNode.parentNode.parentNode.getElementsByTagName('select');
	for(var i=0; i<tmp_s.length; i++)	tmp_s.item(i).disabled = true;	
	//先將原本顏色是 #CCFFCC 還原
	var tmp = document.getElementsByTagName('tr');
	for(var i=1; i<tmp.length; i++)
		tmp.item(i).style.backgroundColor = "";	
	//將選擇的變色
	row.style.backgroundColor ="#CCFFCC";	
	//將點選的 功能開啟
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
<title>匯出學生預警名單(Excel格式)</title>
</head>
<body background = "../images/img/bg.gif">
	<div>
	</div>
<br />
<font color="#0000FF">學生預警名單下載</font>
<br />
<form method="post" action="export_earlywarning.php?action=select">
<table border="1" bordercolor="#006666">
<tr  bgcolor="#588ccc" >
	<td width="36"><font color="#ffffff"> 選擇 </font></td>
	<td width="142"><font color="#ffffff"> 類別 </font></td>
	<td width="398"><font color="#ffffff"> 輸入 </font></td>
</tr> 
<tr>
	<td><input type="radio" name="type" value="1" onClick="changeStyle(this, this.value);" /></td>
	<td>學年 / 學期</td>
	<td>
		學年 <input type="text" name="year" size="5" disabled /> / 
		學期 <input type="text" name="term" size="5" disabled />
	</td>
</tr>
<tr>
	<td><input type="radio" name="type" value="2" onClick="changeStyle(this, this.value);" /></td>
	<td>科目名稱 / 科目代碼</td>
	<td>
		科目名稱 <input type="text" name="course_name" size="15" disabled /> / 
		科目代碼 <input type="text" name="course_no" size="15" disabled />
	</td>
</tr>
<tr>
	<td><input type="radio" name="type"  value="3" onClick="changeStyle(this, this.value);" /></td>
	<td>系所 (系所代碼)</td>
	<td>
		<select name="group"  disabled >
		GROUP
		</select>
	</td>
</tr>
<tr>
	<td><input type="radio" name="type"  value="4" onClick="changeStyle(this ,this.value);" /></td>
	<td>原因</td>
	<td>
		<select name="reason"  disabled >
			<option value='0'>需加強原因</option>
			<option value='1'>成績不佳</option>
			<option value='2'>缺課</option>
			<option value='3'>成績不佳且缺課</option>
		</select>
	</td>
</tr>
<tr style="background-color:#CCFFCC;">
	<td><input type="radio" name="type"  value="5" onClick="changeStyle(this, this.value);" checked /></td>
	<td colspan="2">全部匯出</td>
</tr>
</table>
<input type="submit" name="submit" value="確定送出" />
</form>
<table border="1" bordercolor="#006666">
<tr  bgcolor="#588ccc">
	<td><font color="#FFFFFF">這是</font><font color="#FF0000">OUTPUT_TYPE</font><font color="#FFFFFF">的匯出檔案</font></td>
	<td><font color="#FFFFFF"><a href="FILE_PATH">下載</a></font></td>
</tr>	
</table>
<br /><br />
<a href="../check_admin.php">返回管理介面</a>		
</body>
</html>
