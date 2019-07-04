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
		alert("請輸入新的節名稱");
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
你目前所編輯的是本課程的&nbsp;&lt;第 CHAP_NUM 章&gt;&nbsp;,&nbsp;&lt;第 SECT_NUM 節&gt;&nbsp;.</font>
<form action="section.php" method="post" name="changesect" onSubmit="return check_chgsect();">
更改&nbsp;&lt;第CHAP_NUM章&gt;&nbsp;&nbsp;&lt;第SECT_NUM節&gt;&nbsp;標題為

<table border=1>
<tr bgcolor=#cdeffc>
	<td><input type="text" name="sect_title" size=30></td></tr>
</table>

<input type="submit" name="submit" value="更新節標題">
<input type="reset" name="reset" value="重新輸入">
<input type="hidden" name="action" value="1">
<input type="hidden" name="chap_num" value="CHAP_NUM">
<input type="hidden" name="sect_num" value="SECT_NUM">
<input type="hidden" name="return_type" value="1">
</form>
<hr>
<form name="htmlcontent" action="htmlgen.php" method="post" onsubmit="return confirm('這將會更新原來的網頁內容, 是否繼續?');">

<table border=1 bgcolor=#cdeffc>
<tr>
	<td>請輸入本節&nbsp;&lt;首頁&gt;&nbsp;之內容.</td></tr>
<tr>
	<td><textarea cols=70 rows=6 name="content" wrap="virtual">
HTML_CONTENT</textarea></td></tr>
</table>

<input type="hidden" name="html_type" value=3>
<input type="hidden" name="chap_num" value="CHAP_NUM">
<input type="hidden" name="sect_num" value="SECT_NUM">
<input type="submit" name="submit" value="更新目前網頁內容">
<input type="reset" name="reset" value="重新輸入">
</form>
<form action="preview.php" name="preview" method="post" onsubmit="preview.content.value=htmlcontent.content.value;">
<input type="hidden" name="basehref" value=BASEHREF>
<input type="hidden" name="content" value="">
<input type="submit" value="編輯結果預覽" onClick="return confirm('請先完成網頁內容更新再使用此功能');">
</form>
<form action="section.php" method="post" onsubmit="return confirm('這將會刪除所有關於此節的網頁與瀏覽紀錄, 是否繼續?');">
<input type="hidden" name="action" value="2">
<input type="hidden" name="chap_num" value="CHAP_NUM">
<input type="hidden" name="sect_num" value="SECT_NUM">
<input type="submit" value="刪除本節">
</form>
<hr>
<form action="file.php" method="post" enctype="multipart/form-data">

<table border=1" bgcolor=#cdeffc>
<tr>
	<td>上傳 [第CHAP_NUM章第SECT_NUM節] 相關檔案到 [/textbook/CHAP_NUM/SECT_NUM/] .<br>
如要覆蓋首頁內容, 請上傳檔名為 [index.html] 的檔案.<br>
<span class="style1"><span class="style1">**注意：上傳的檔案名稱中切記不要有空白！！**</span></td>
</tr>
<tr>
	<td><input type="file" name="newfile">
<input type="hidden" name="editor" value="1">
<input type="hidden" name="action" value="upload">
<input type="hidden" name="chap_num" value="CHAP_NUM">
<input type="hidden" name="sect_num" value="SECT_NUM">
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