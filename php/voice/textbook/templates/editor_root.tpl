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
	var msg = '請輸入';
	if( (newchap.chap_num.value.length==0) || ( isNaN(parseInt(newchap.chap_num.value) ) ) ) {
		error = false;
		msg = msg + ' 章編號';
	}

	if(newchap.chap_title.value.length==0) {
		error = false;
		msg = msg + ' 章名稱';
	}
	
	if(!error) {
		alert(msg);
	}
	return error;
}

function check_del_chapter( chap_num ) {
	if( confirm('這將會刪除所有關於此章的網頁(包括各節內容, 瀏覽紀錄), 是否繼續?') ) {
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
你目前所編輯的是本課程的&nbsp;&lt;導論&gt;&nbsp;</font>
<form action="chap.php" method="post" name="newchap" onSubmit="return check_new_chapter();">

<table border=1>
<caption>欲編輯各章內容前, 請先於此輸入&nbsp;&lt;章標題&gt;&nbsp;.
			<br>欲更新章標題, 可輸入已經存在的&nbsp;&lt;章編號&gt;與&nbsp;&lt;新標題&gt;</caption>
<tr bgcolor=#4d6be2>
	<td><font color=#ffffff>章數</font>
	<td><font color=#ffffff>章標題</font>
	<td></td></tr>
<!-- BEGIN DYNAMIC BLOCK: chap_list -->
<tr bgcolor=ED_COLOR>
	<td>CHAP_NUM
	<td>CHAP_TITLE
	<td><input type="button" value="刪除" OnClick="return check_del_chapter('CHAP_NUM');"></td></tr>
<!-- END DYNAMIC BLOCK: chap_list -->
<tr bgcolor=#cdeffc>
	<td>章數<input type="text" name="chap_num" size=4>
	<td>標題<input type="text" name="chap_title" size=50>
	<td></td></tr>
</table>

<input type="hidden" name="action" value=1>
<input type="submit" name="submit" value="新增/更新章標題"><input type="reset" name="reset" value="重新輸入">
</form>
<hr>
<form name="htmlcontent" action="htmlgen.php" method="post" onsubmit="return confirm('這將會更新原來的網頁內容, 是否繼續?');">

<table border=1 bgcolor=#cdeffc>
<tr>
	<td>請輸入本科目的&nbsp;&lt;導論&gt;&nbsp;內容.</td></tr>
<tr>
	<td><textarea cols=70 rows=6 name="content" wrap="virtual">
HTML_CONTENT</textarea></td></tr>
</table>

<input type="hidden" name="html_type" value=1>
<input type="submit" name="submit" value="更新目前網頁內容">
<input type="reset" name="reset" value="重新輸入">
</form>
<form action="preview.php" name="preview" method="post" onsubmit="preview.content.value=htmlcontent.content.value;">
<input type="hidden" name="basehref" value=BASEHREF>
<input type="hidden" name="content" value="">
<input type="submit" value="編輯結果預覽" onClick="return confirm('請先完成網頁內容更新再使用此功能');">
</form>
<hr>
<form action="validated.php" name="validated" method="post">

<table border="1" bgcolor=#cdeffc>
<input type="hidden" name="validated" value="VAL_VALUE">
<caption>課程是否開放旁聽</caption>
<tr bgcolor=#4d6be2>
	<td><font color="white">目前狀態</font>
	<td><font color="white">控制按鈕</font></td></tr>
<tr>
	<td>VAL_STATUS
	<td><input type="submit" name="submit" value="開啟/關閉 課程旁聽"></td></tr>
</table>

</form>
<hr>
<form action="file.php" method="post" enctype="multipart/form-data">

<table border=1" bgcolor=#cdeffc>
<tr>
	<td>上傳 [課程導論] 相關檔案到 [&nbsp;/教材目錄/&nbsp;].<br>
如要覆蓋首頁內容, 請上傳檔名為 [index.html] 的檔案.<br>
<span class="style1">**注意：上傳的檔案名稱中切記不要有空白！！**</span></td>
</tr>
<tr>
	<td><input type="file" name="newfile">
<input type="hidden" name="editor" value="1">
<input type="hidden" name="action" value="upload">
<input type="submit" name="submit" value="開始上傳"></td></tr>
</table>

</form>

<table border="1">
<caption> 此目錄下的檔案 </caption>
<tr bgcolor="#4d6be2">
	<td><font color=#ffffff>檔名</font>
	<td><font color=#ffffff>檔案大小</font>
	<td><font color=#ffffff>最後修改日期</font>
	<td><font color=#ffffff>刪除檔案</font></td></tr>
<!-- BEGIN DYNAMIC BLOCK: file_list -->
<tr bgcolor=F_COLOR>
	<td><a href="FILE_LINK">FILE_N</a> 
	<td>FILE_SIZE 
	<td>FILE_DATE 
	<td><a href="file.php?action=delete&filename=FILE_DEL" onclick="return confirm('你確定要刪除這個檔案嗎?');">刪除這個檔案</a></td></tr>
<!-- END DYNAMIC BLOCK: file_list -->
</table>

</center>
</body>
</html>