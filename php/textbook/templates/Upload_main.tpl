<html>
<head>
<title> TITLE </title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<STYLE type=text/css>
 body { SCROLLBAR-FACE-COLOR: #4d6eb2; SCROLLBAR-ARROW-COLOR: #FFFFFF; SCROLLBAR-TRACK-COLOR: #DDDDFF; SCROLLBAR-SHADOW-COLOR: #054878; SCROLLBAR-3DLIGHT-COLOR: black; }
</STYLE>
<SCRIPT LANGUAGE="JavaScript">
<!--
function check_rename( oldfile, session_id  ) {
	
	var newfile = prompt("�п�J�s�ɮת��ɦW", oldfile);

	if( newfile == null ) {
		return false;
	}
	else if( (newfile.indexOf("/")!=-1) || (newfile.indexOf("\\")!=-1) ) {

		alert("�ɦW���~");
		return false;
	}
	else if( (newfile == "") || (oldfile==newfile) ) {

		alert("�ɦW��������");
		return false;
	}
	else {
		location.href = "file.php?PHPSESSID=" + session_id + "&oldfile=" + oldfile + "&newfile=" + newfile + "&action=rename";
	}
}
//-->
</SCRIPT>
<link rel="stylesheet" href="/images/skin1/css/main-body.css" type="text/css">
</head>
<body background="/images/img/bg.gif">
<IMG SRC="/images/img/a312.gif">
<center>
<font color="red">ERROR_MSG</font><br>
�p�G�O&nbsp;[��1��]&nbsp;���ɮשκ���, �ФW�Ǩ� [&nbsp;/�Ч��ؿ�/1/&nbsp;] ���U.<br>
�p�G�O&nbsp;[��1����1�`]&nbsp;���ɮשκ���, �ФW�Ǩ� [&nbsp;/�Ч��ؿ�/1/1&nbsp;] ���U.<br>
�ۦ�W�ǽҵ{������&nbsp;�аȥ��N�ҵ{�D�����ɦW�]��&nbsp;<font color='red'>index.html&nbsp;��&nbsp;index.htm</font>,<br>
�é�b [&nbsp;/�Ч��ؿ�/&nbsp;] �U.<br>
"���G�ɮשR�W��ĳ�̦n���n�ϥΡu<font color="red">����</font>�v�H�Ρu<font color="red">�ť�</font>�v"<br>
<form method="post" name="fup" action="file.php" enctype="multipart/form-data">
<input type='hidden' name='action' value='upload'>
<table border="1">
<caption> �ثe�Ҧb�ؿ��� [&nbsp;<font color='red'>CURRENT_DIR</font>&nbsp;] .</caption>
<tr bgcolor=#cdeffc>
	<td colspan="3">�W�Ƿs�ɮ�</tr>
<tr bgcolor=#bcd2ee>
	<td>����ɮ�
	<td><input type="file" name="newfile">
	<td><input type="submit" name="submit" value="�}�l�W��"></tr>
</form>
<form method="post" action="file.php" onSubmit="return !(newdir.value.length==0);">
<input type='hidden' name='action' value='credir'>
<tr bgcolor=#cdeffc>
	<td colspan="3">�s�W�ؿ�</tr>
<tr bgcolor=#bcd2ee>
	<td>�ؿ��W��
	<td><input type="text" name="newdir">
	<td><input type="submit" name="submit" value="�s�W�ؿ�"></tr>
</form>
<form method="post" action="file.php">
<input type='hidden' name='action' value='chgdir'>
<tr bgcolor=#cdeffc>
	<td colspan="3">�󴫥ثe�u�@�ؿ�</tr>
<tr bgcolor=#bcd2ee>
	<td>��ܥؿ�
    <td><select name="chgdir">
<!-- BEGIN DYNAMIC BLOCK: directory_lista -->
    <option value="NAMEA">DIRNA</option>
<!-- END DYNAMIC BLOCK: directory_lista -->
    <td><input type="submit" name="submit" value="�󴫥ؿ�"></tr>
</form>
<form method="post" action="file.php" onsubmit="return confirm('�A�T�w�n�R���o�ӥؿ���?');">
<input type='hidden' name='action' value='deldir'>
<tr bgcolor=#cdeffc>
	<td colspan="3">�R���ؿ�</tr>
<tr bgcolor=#bcd2ee>
	<td>��ܥؿ�
    <td><select name="deldir">
<!-- BEGIN DYNAMIC BLOCK: directory_listb -->
    <option value="NAMEB">DIRNB</option>
<!-- END DYNAMIC BLOCK: directory_listb -->
    <td><input type="submit" name="submit" value="�R���ؿ�"></tr>
</form>
</table>
<hr>
<table border="1">
<caption> ���ؿ��U���ɮ� </caption>
<tr bgcolor="#4d6be2">
	<td><font color=#ffffff>�ɦW</font>
	<td><font color=#ffffff>�ɮפj�p</font>
	<td><font color=#ffffff>�̫�ק���</font>
	<td><font color=#ffffff>�R���ɮ�</font>
	<td><font color=#ffffff>����ɦW</font>
<!-- BEGIN DYNAMIC BLOCK: file_list -->
<tr bgcolor=F_COLOR>
	<td><a href="FILE_LINK">FILE_N</a> 
	<td>FILE_SIZE 
	<td>FILE_DATE 
	<td><a href="file.php?action=delete&filename=FILE_DEL" onclick="return confirm('�A�T�w�n�R���o���ɮ׶�?');">�R���o���ɮ�</a>
	<td><input type="button" value="����ɦW" onClick="return check_rename( 'FILE_N', 'PHP_ID' );">
<!-- END DYNAMIC BLOCK: file_list -->
</table>
</center>
</body>
</html>
