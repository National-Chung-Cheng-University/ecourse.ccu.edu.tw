<html>
<head>
<title> TITLE </title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css">
<SCRIPT LANGUAGE="JavaScript">
<!--
function check_post() {
	var error = true;
	var msg = "�п�J";
	if(post_art.title.value.length==0) {
		error = false;
		msg = msg + " ���D";
	}

	if(post_art.body.value.length==0) {
		error = false;
		msg = msg + " ���e";
	}

	if((post_art.attach.value.length > 0) && (post_art.attach.value.indexOf(".") == -1)) {
		error = false;
		msg = msg + " �ɮת����ɦW";
	}
	
	if(!error)
		alert(msg);
	return error;
}
//-->
</SCRIPT>
</head>
<body background="/images/img/bg.gif">
<IMG SRC="/images/img/b52.gif">
<center>
<form name="post_art" action="post_article.php" method="post" enctype="multipart/form-data" onSubmit="return check_post();">
<table border=1>
<tr><td bgcolor=#4d6be2><font color=#ffffff>���D</font><td bgcolor=#cdeffc><input type="text" name="title" value="ART_T" size=40 maxlength=64>
<tr><td bgcolor=#4d6be2><font color=#ffffff>���e</font><td bgcolor=#cdeffc>
<textarea name="body" cols=80 rows=7>
ART_BODY</textarea>
<tr><td bgcolor=#4d6be2><font color=#ffffff>���[�ɮ�</font><td bgcolor=#cdeffc><input type="file" name="attach">
<tr><td bgcolor=#4d6be2><font color=#ffffff>���[�y���ɮ�&nbsp;(�Х����k��[</font><font color=red>����</font><font color=#ffffff>])</font>
    <td bgcolor=#cdeffc>RECORDER_CODE
</table>
<input type="hidden" name="discuss_id" value="DIS_ID">
<input type="hidden" name="parent" value="PARENT">
<input type="hidden" name="action" value="insert">
<input type="hidden" name="sound" value="SOUNDNAME">
<input type="submit" name="submit" value="�o��/�^�Ф峹">  <input type="reset" name="reset" value="�M��/���s��J">
<input type="button" value="�峹�@��" onClick="location.href='article_list.php?discuss_id=DIS_ID&page=PAGE&PHPSESSID=PHP_ID'">
</form>
</center>
</body>
</html>