<html>
<head>
<title> TITLE </title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<STYLE type=text/css>
 body { SCROLLBAR-FACE-COLOR: #4d6eb2; SCROLLBAR-ARROW-COLOR: #FFFFFF; SCROLLBAR-TRACK-COLOR: #DDDDFF; SCROLLBAR-SHADOW-COLOR: #054878; SCROLLBAR-3DLIGHT-COLOR: black; }
</STYLE>
<SCRIPT LANGUAGE="JavaScript">
<!--
function check_new_chapter( ) {
	var error = true;
	var msg = '�п�J';
	if( (newchap.chap_num.value.length==0) || ( isNaN(parseInt(newchap.chap_num.value) ) ) ) {
		error = false;
		msg = msg + ' ���s��';
	}

	if(newchap.chap_title.value.length==0) {
		error = false;
		msg = msg + ' ���W��';
	}
	
	if(!error) {
		alert(msg);
	}
	return error;
}

function check_del_chapter( chap_num ) {
	if( confirm('�o�N�|�R���Ҧ����󦹳�������(�]�A�U�`���e, �s������), �O�_�~��?') ) {
		location.href = "chap.php?action=2&chap_num=" + chap_num + "&PHPSESSID=PHP_ID";
		return true;
	}
	else
		return false;
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
�A�ثe�ҽs�誺�O���ҵ{��&nbsp;&lt;�ɽ�&gt;&nbsp;</font>
<form action="chap.php" method="post" name="newchap" onSubmit="return check_new_chapter();">

<table border=1>
<caption>���s��U�����e�e, �Х��󦹿�J&nbsp;&lt;�����D&gt;&nbsp;.
			<br>����s�����D, �i��J�w�g�s�b��&nbsp;&lt;���s��&gt;�P&nbsp;&lt;�s���D&gt;</caption>
<tr bgcolor=#4d6be2>
	<td><font color=#ffffff>����</font>
	<td><font color=#ffffff>�����D</font>
	<td></td></tr>
<!-- BEGIN DYNAMIC BLOCK: chap_list -->
<tr bgcolor=ED_COLOR>
	<td>CHAP_NUM
	<td>CHAP_TITLE
	<td><input type="button" value="�R��" OnClick="return check_del_chapter('CHAP_NUM');"></td></tr>
<!-- END DYNAMIC BLOCK: chap_list -->
<tr bgcolor=#cdeffc>
	<td>����<input type="text" name="chap_num" size=4>
	<td>���D<input type="text" name="chap_title" size=50>
	<td></td></tr>
</table>

<input type="hidden" name="action" value=1>
<input type="submit" name="submit" value="�s�W/��s�����D"><input type="reset" name="reset" value="���s��J">
</form>
<hr>
<form name="htmlcontent" action="htmlgen.php" method="post" onsubmit="return confirm('�o�N�|��s��Ӫ��������e, �O�_�~��?');">

<table border=1 bgcolor=#cdeffc>
<tr>
	<td>�п�J����ت�&nbsp;&lt;�ɽ�&gt;&nbsp;���e.</td></tr>
<tr>
	<td><textarea cols=70 rows=6 name="content" wrap="virtual">
HTML_CONTENT</textarea></td></tr>
</table>

<input type="hidden" name="html_type" value=1>
<input type="submit" name="submit" value="��s�ثe�������e">
<input type="reset" name="reset" value="���s��J">
</form>
<form action="preview.php" name="preview" method="post" onsubmit="preview.content.value=htmlcontent.content.value;">
<input type="hidden" name="basehref" value=BASEHREF>
<input type="hidden" name="content" value="">
<input type="submit" value="�s�赲�G�w��" onClick="return confirm('�Х������������e��s�A�ϥΦ��\��');">
</form>
<hr>
<form action="validated.php" name="validated" method="post">

<table border="1" bgcolor=#cdeffc>
<input type="hidden" name="validated" value="VAL_VALUE">
<caption>�ҵ{�O�_�}���ť</caption>
<tr bgcolor=#4d6be2>
	<td><font color="white">�ثe���A</font>
	<td><font color="white">������s</font></td></tr>
<tr>
	<td>VAL_STATUS
	<td><input type="submit" name="submit" value="�}��/���� �ҵ{��ť"></td></tr>
</table>

</form>
<hr>
<form action="file.php" method="post" enctype="multipart/form-data">

<table border=1" bgcolor=#cdeffc>
<tr>
	<td>�W�� [�ҵ{�ɽ�] �����ɮר� [&nbsp;/�Ч��ؿ�/&nbsp;].<br>
�p�n�л\�������e, �ФW���ɦW�� [index.html] ���ɮ�.<br>
<span class="style1">**�`�N�G�W�Ǫ��ɮצW�٤����O���n���ťաI�I**</span></td>
</tr>
<tr>
	<td><input type="file" name="newfile">
<input type="hidden" name="editor" value="1">
<input type="hidden" name="action" value="upload">
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