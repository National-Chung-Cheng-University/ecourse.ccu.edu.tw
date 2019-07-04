<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<title>觀看教師辦公室時間</title>
</head>
<script language="JavaScript">
//紀錄需要改變的欄位
var needChange;
	function Show(){
		document.change.submit();
	}
	
	function getChoose(obj){
		var tmp, i, j, len;
	
		tmp = obj.innerHTML;
		if(tmp != ""){
			i = tmp.indexOf("<BR>",0);
			j = tmp.indexOf("<BR>",i+4);
			
			len = i;
			needChange = tmp.substring(0,len);	
			document.getElementById('color_'+needChange).style.backgroundColor ="#FFFF33";
			document.getElementById('C_'+needChange).style.backgroundColor ="#FFFF33";
			
			len = j - i - 4;
			if (len > 0) {
				needChange = tmp.substring(i + 4,i + 4 + len);
				document.getElementById('color_'+needChange).style.backgroundColor ="#FFFF33";
				document.getElementById('C_'+needChange).style.backgroundColor ="#FFFF33";
			}
			
			len = tmp.length - j - 8;
			if (len > 0) {
				needChange = tmp.substring(j + 4,j + 4 + len);
				document.getElementById('color_'+needChange).style.backgroundColor ="#FFFF33";
				document.getElementById('C_'+needChange).style.backgroundColor ="#FFFF33";
			}
		}
	}
	
	function resetColor(obj){
		var tmp, i, j, len;
	
		tmp = obj.innerHTML;
		if(tmp != ""){
			i = tmp.indexOf("<BR>",0);
			j = tmp.indexOf("<BR>",i+4);
	
			len = i;
			needChange = tmp.substring(0,len);	
			document.getElementById('color_'+needChange).style.backgroundColor ="#66CC99";
			document.getElementById('C_'+needChange).style.backgroundColor ="#99CCFF";
			
			len = j - i - 4;
			if (len > 0) {
				needChange = tmp.substring(i + 4,i + 4 + len);
				document.getElementById('color_'+needChange).style.backgroundColor ="#66CC99";
				document.getElementById('C_'+needChange).style.backgroundColor ="#99CCFF";
			}
			
			len = tmp.length - j - 8;
			if (len > 0) {
				needChange = tmp.substring(j + 4,j + 4 + len);
				document.getElementById('color_'+needChange).style.backgroundColor ="#66CC99";
				document.getElementById('C_'+needChange).style.backgroundColor ="#99CCFF";	
			}
		}
	}
</script>
<body background = "../images/img/bg.gif">
<center>

<br />
<form action=./show_office_time_student.php name=change method=get>
	<select name=teacher_num onChange="Show();">
		<!-- BEGIN DYNAMIC BLOCK: teacher_list -->
		<option value="NUM" SELD>TEACHER_NAME</option>
		<!-- END DYNAMIC BLOCK: teacher_list -->
	</select>
</form>
<br />
<font size="+2">TEACHER_NAME辦公室時間</font>
<table  border=1 bordercolor="#006666">
     <tr bgcolor="#588ccc"><font color="#ffffff">
        <th>星期/<BR>區段</th>
        <th>&nbsp;&nbsp;&nbsp;&nbsp;一&nbsp;&nbsp;&nbsp;&nbsp;</th>
        <th>&nbsp;&nbsp;&nbsp;&nbsp;二&nbsp;&nbsp;&nbsp;&nbsp;</th>
        <th>&nbsp;&nbsp;&nbsp;&nbsp;三&nbsp;&nbsp;&nbsp;&nbsp;</th>
        <th>&nbsp;&nbsp;&nbsp;&nbsp;四&nbsp;&nbsp;&nbsp;&nbsp;</th>
        <th>&nbsp;&nbsp;&nbsp;&nbsp;五&nbsp;&nbsp;&nbsp;&nbsp;</th>
		</font>
      </tr>
	  <!-- BEGIN DYNAMIC BLOCK: time_list -->
	  DATA
	  <!-- END DYNAMIC BLOCK: time_list -->
	  <tr>
	  	<td bgcolor="#588ccc">學期學年</td><td colspan="5">第YEAR學年 第TERM學期</td>
	  </tr>
	  <tr>	
		<td bgcolor="#588ccc">辦公室地點</td><td colspan="5">LOCATION</td>
	  </tr>
	  <tr >	
		<td bgcolor="#588ccc">連絡電話</td><td colspan="5">TEL</td>
	  <tr>	
		<td bgcolor="#588ccc">E-MAIL</td><td colspan="5">EMAIL</td></td>
	  </tr>
	  <tr >	
		<td bgcolor="#588ccc" >說明</td><td colspan="5">COMMENT</td>
	  </tr>
</table>
</center>
</body>
</html>
