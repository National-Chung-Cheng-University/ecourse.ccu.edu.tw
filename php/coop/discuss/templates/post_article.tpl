<html>
<head>
<title> TITLE </title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css">
<SCRIPT LANGUAGE="JavaScript">
<!--
function check_post() {
	var error = true;
	var msg = "請輸入";
	if(post_art.title.value.length==0) {
		error = false;
		msg = msg + " 標題";
	}

	if(post_art.body.value.length==0) {
		error = false;
		msg = msg + " 內容";
	}

	if((post_art.attach.value.length > 0) && (post_art.attach.value.indexOf(".") == -1)) {
		error = false;
		msg = msg + " 檔案的副檔名";
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
<tr><td bgcolor=#4d6be2><font color=#ffffff>標題</font><td bgcolor=#cdeffc><input type="text" name="title" value="ART_T" size=40 maxlength=64>
<tr><td bgcolor=#4d6be2><font color=#ffffff>內容</font><td bgcolor=#cdeffc>
<textarea name="body" cols=80 rows=7>
ART_BODY</textarea>
<tr><td bgcolor=#4d6be2><font color=#ffffff>附加檔案</font><td bgcolor=#cdeffc><input type="file" name="attach">
<tr><td bgcolor=#4d6be2><font color=#ffffff>附加語音檔案&nbsp;(請先按右邊[</font><font color=red>錄音</font><font color=#ffffff>])</font>
    <td bgcolor=#cdeffc>RECORDER_CODE
</table>
<input type="hidden" name="discuss_id" value="DIS_ID">
<input type="hidden" name="parent" value="PARENT">
<input type="hidden" name="action" value="insert">
<input type="hidden" name="sound" value="SOUNDNAME">
<input type="submit" name="submit" value="發表/回覆文章">  <input type="reset" name="reset" value="清除/重新輸入">
<input type="button" value="文章一覽" onClick="location.href='article_list.php?discuss_id=DIS_ID&page=PAGE&PHPSESSID=PHP_ID'">
</form>
</center>
</body>
</html>