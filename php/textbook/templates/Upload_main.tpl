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
	
	var newfile = prompt("請輸入新檔案的檔名", oldfile);

	if( newfile == null ) {
		return false;
	}
	else if( (newfile.indexOf("/")!=-1) || (newfile.indexOf("\\")!=-1) ) {

		alert("檔名錯誤");
		return false;
	}
	else if( (newfile == "") || (oldfile==newfile) ) {

		alert("檔名維持不變");
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
如果是&nbsp;[第1章]&nbsp;的檔案或網頁, 請上傳到 [&nbsp;/教材目錄/1/&nbsp;] 之下.<br>
如果是&nbsp;[第1章第1節]&nbsp;的檔案或網頁, 請上傳到 [&nbsp;/教材目錄/1/1&nbsp;] 之下.<br>
自行上傳課程網頁時&nbsp;請務必將課程主網頁檔名設為&nbsp;<font color='red'>index.html&nbsp;或&nbsp;index.htm</font>,<br>
並放在 [&nbsp;/教材目錄/&nbsp;] 下.<br>
"註：檔案命名建議最好不要使用「<font color="red">中文</font>」以及「<font color="red">空白</font>」"<br>
<form method="post" name="fup" action="file.php" enctype="multipart/form-data">
<input type='hidden' name='action' value='upload'>
<table border="1">
<caption> 目前所在目錄為 [&nbsp;<font color='red'>CURRENT_DIR</font>&nbsp;] .</caption>
<tr bgcolor=#cdeffc>
	<td colspan="3">上傳新檔案</tr>
<tr bgcolor=#bcd2ee>
	<td>選擇檔案
	<td><input type="file" name="newfile">
	<td><input type="submit" name="submit" value="開始上傳"></tr>
</form>
<form method="post" action="file.php" onSubmit="return !(newdir.value.length==0);">
<input type='hidden' name='action' value='credir'>
<tr bgcolor=#cdeffc>
	<td colspan="3">新增目錄</tr>
<tr bgcolor=#bcd2ee>
	<td>目錄名稱
	<td><input type="text" name="newdir">
	<td><input type="submit" name="submit" value="新增目錄"></tr>
</form>
<form method="post" action="file.php">
<input type='hidden' name='action' value='chgdir'>
<tr bgcolor=#cdeffc>
	<td colspan="3">更換目前工作目錄</tr>
<tr bgcolor=#bcd2ee>
	<td>選擇目錄
    <td><select name="chgdir">
<!-- BEGIN DYNAMIC BLOCK: directory_lista -->
    <option value="NAMEA">DIRNA</option>
<!-- END DYNAMIC BLOCK: directory_lista -->
    <td><input type="submit" name="submit" value="更換目錄"></tr>
</form>
<form method="post" action="file.php" onsubmit="return confirm('你確定要刪除這個目錄嗎?');">
<input type='hidden' name='action' value='deldir'>
<tr bgcolor=#cdeffc>
	<td colspan="3">刪除目錄</tr>
<tr bgcolor=#bcd2ee>
	<td>選擇目錄
    <td><select name="deldir">
<!-- BEGIN DYNAMIC BLOCK: directory_listb -->
    <option value="NAMEB">DIRNB</option>
<!-- END DYNAMIC BLOCK: directory_listb -->
    <td><input type="submit" name="submit" value="刪除目錄"></tr>
</form>
</table>
<hr>
<table border="1">
<caption> 此目錄下的檔案 </caption>
<tr bgcolor="#4d6be2">
	<td><font color=#ffffff>檔名</font>
	<td><font color=#ffffff>檔案大小</font>
	<td><font color=#ffffff>最後修改日期</font>
	<td><font color=#ffffff>刪除檔案</font>
	<td><font color=#ffffff>更改檔名</font>
<!-- BEGIN DYNAMIC BLOCK: file_list -->
<tr bgcolor=F_COLOR>
	<td><a href="FILE_LINK">FILE_N</a> 
	<td>FILE_SIZE 
	<td>FILE_DATE 
	<td><a href="file.php?action=delete&filename=FILE_DEL" onclick="return confirm('你確定要刪除這個檔案嗎?');">刪除這個檔案</a>
	<td><input type="button" value="更改檔名" onClick="return check_rename( 'FILE_N', 'PHP_ID' );">
<!-- END DYNAMIC BLOCK: file_list -->
</table>
</center>
</body>
</html>
