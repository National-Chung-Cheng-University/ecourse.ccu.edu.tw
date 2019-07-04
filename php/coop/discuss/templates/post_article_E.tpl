<html>
<head>
<title> TITLE </title>
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css"> 
<SCRIPT LANGUAGE="JavaScript">
<!--
function check_post() {
	var error = true;
	var msg = "Please input";
	if(post_art.title.value.length==0) {
		error = false;
		msg = msg + " 'title'";
	}

	if(post_art.body.value.length==0) {
		error = false;
		msg = msg + " 'message body'";
	}

	if((post_art.attach.value.length > 0) && (post_art.attach.value.indexOf(".") == -1)) {
		error = false;
		msg = msg + " 'extension of file'";
	}

	if(!error)
		alert(msg);

	return error;
}
//-->
</SCRIPT>
</head>
<body background="/images/img_E/bg.gif">
<IMG SRC="/images/img_E/b52.gif">
<center>
<form name="post_art" action="post_article.php" method="post" enctype="multipart/form-data" onSubmit="return check_post();">
<table border=1>
<tr><td bgcolor=#4d6be2><font color=#ffffff>title</font><td bgcolor=#cdeffc><input type="text" name="title" value="ART_T" size=40 maxlength=64>
<tr><td bgcolor=#4d6be2><font color=#ffffff>message</font><td bgcolor=#cdeffc>
<textarea name="body" cols=80 rows=7>
ART_BODY</textarea>
<tr><td bgcolor=#4d6be2><font color=#ffffff>related file</font><td bgcolor=#cdeffc><input type="file" name="attach">
<tr><td bgcolor=#4d6be2><font color=#ffffff>attached voice file&nbsp;(please press[</font><font color=red>¿ý­µ</font><font color=#ffffff>])</font>
    <td bgcolor=#cdeffc>RECORDER_CODE
</table>
<input type="hidden" name="discuss_id" value="DIS_ID">
<input type="hidden" name="parent" value="PARENT">
<input type="hidden" name="action" value="insert">
<input type="hidden" name="sound" value="SOUNDNAME">
<input type="submit" name="submit" value="Post/Reply Atricle">  <input type="reset" name="reset" value="Clear/Reset Input">
<input type="button" value="Back to article list" onClick="location.href='article_list.php?discuss_id=DIS_ID&page=PAGE&PHPSESSID=PHP_ID'">
</form>
</center>
</body>
</html>