<html>
<head>
<title> TITLE </title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<STYLE type=text/css>
 body { SCROLLBAR-FACE-COLOR: #4d6eb2; SCROLLBAR-ARROW-COLOR: #FFFFFF; SCROLLBAR-TRACK-COLOR: #DDDDFF; SCROLLBAR-SHADOW-COLOR: #054878; SCROLLBAR-3DLIGHT-COLOR: black; }
</STYLE> 
<SCRIPT LANGUAGE="JavaScript">
<!--
function check_chgsect() {
	if(changesect.sect_title.value.length==0) {
		alert("�п�J�s���`�W��");
		return false;
	}
	else
		return true;
}
//-->
</SCRIPT>
<link rel="stylesheet" href="/images/skin1/css/main-body.css" type="text/css">
<style type="text/css">
<!--
.style1 {
	color: #FF0000;
	font-style: italic;
}
-->
</style>
</head>
<body background="/images/img/bg.gif"RELOAD_CTRL>
<IMG SRC="/images/img/a313.gif">
<center>
<font color="red">ERROR_MSG<br>
�A�ثe�ҽs�誺�O���ҵ{��&nbsp;&lt;�� CHAP_NUM ��&gt;&nbsp;,&nbsp;&lt;�� SECT_NUM �`&gt;&nbsp;.</font>
<form action="section.php" method="post" name="changesect" onSubmit="return check_chgsect();">
���&nbsp;&lt;��CHAP_NUM��&gt;&nbsp;&nbsp;&lt;��SECT_NUM�`&gt;&nbsp;���D��

<table border=1>
<tr bgcolor=#cdeffc>
	<td><input type="text" name="sect_title" size=30></td></tr>
</table>

<input type="submit" name="submit" value="��s�`���D">
<input type="reset" name="reset" value="���s��J">
<input type="hidden" name="action" value="1">
<input type="hidden" name="chap_num" value="CHAP_NUM">
<input type="hidden" name="sect_num" value="SECT_NUM">
<input type="hidden" name="return_type" value="1">
</form>
<hr>
<form name="htmlcontent" action="htmlgen.php" method="post" onsubmit="return confirm('�o�N�|��s��Ӫ��������e, �O�_�~��?');">

<table border=1 bgcolor=#cdeffc>
<tr>
	<td>�п�J���`&nbsp;&lt;����&gt;&nbsp;�����e.</td></tr>
<tr>
	<td><textarea cols=70 rows=6 name="content" wrap="virtual">
HTML_CONTENT</textarea></td></tr>
</table>

<input type="hidden" name="html_type" value=3>
<input type="hidden" name="chap_num" value="CHAP_NUM">
<input type="hidden" name="sect_num" value="SECT_NUM">
<input type="submit" name="submit" value="��s�ثe�������e">
<input type="reset" name="reset" value="���s��J">
</form>
<form action="preview.php" name="preview" method="post" onsubmit="preview.content.value=htmlcontent.content.value;">
<input type="hidden" name="basehref" value=BASEHREF>
<input type="hidden" name="content" value="">
<input type="submit" value="�s�赲�G�w��" onClick="return confirm('�Х������������e��s�A�ϥΦ��\��');">
</form>
<form action="section.php" method="post" onsubmit="return confirm('�o�N�|�R���Ҧ����󦹸`�������P�s������, �O�_�~��?');">
<input type="hidden" name="action" value="2">
<input type="hidden" name="chap_num" value="CHAP_NUM">
<input type="hidden" name="sect_num" value="SECT_NUM">
<input type="submit" value="�R�����`">
</form>
<hr>
<form action="file.php" method="post" enctype="multipart/form-data">

<table border=1" bgcolor=#cdeffc>
<tr>
	<td>�W�� [��CHAP_NUM����SECT_NUM�`] �����ɮר� [/textbook/CHAP_NUM/SECT_NUM/] .<br>
�p�n�л\�������e, �ФW���ɦW�� [index.html] ���ɮ�.<br>
<span class="style1"><span class="style1">**�`�N�G�W�Ǫ��ɮצW�٤����O���n���ťաI�I**</span></td>
</tr>
<tr>
	<td><input type="file" name="newfile">
<input type="hidden" name="editor" value="1">
<input type="hidden" name="action" value="upload">
<input type="hidden" name="chap_num" value="CHAP_NUM">
<input type="hidden" name="sect_num" value="SECT_NUM">
<input type="submit" name="submit" value="�}�l�W��"></td></tr>
</table>

</form>

<table border="1">
<caption> ���ؿ��U���ɮ� </caption>
<tr bgcolor="#4d6be2">
	<td><font color=#ffffff>�ɦW</font>
	<td><font color=#ffffff>�ɮפj�p</font>
	<td><font color=#ffffff>�̫�ק���</font>
	<td><font color=#ffffff>�R���ɮ�</font></td></tr>
<!-- BEGIN DYNAMIC BLOCK: file_list -->
<tr bgcolor=F_COLOR>
	<td><a href="FILE_LINK">FILE_N</a> 
	<td>FILE_SIZE 
	<td>FILE_DATE
	<td><a href="file.php?action=delete&filename=FILE_DEL" onclick="return confirm('�A�T�w�n�R���o���ɮ׶�?');">�R���o���ɮ�</a></td></tr>
<!-- END DYNAMIC BLOCK: file_list -->
</table>

</center>
</body>
</html>