<html>
<head>
<title> TITLE </title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<STYLE type=text/css>
 body { SCROLLBAR-FACE-COLOR: #4d6eb2; SCROLLBAR-ARROW-COLOR: #FFFFFF; SCROLLBAR-TRACK-COLOR: #DDDDFF; SCROLLBAR-SHADOW-COLOR: #054878; SCROLLBAR-3DLIGHT-COLOR: black; }
</STYLE> 
<link rel="stylesheet" href="/images/skin1/css/main-body.css" type="text/css">
<script language="javascript">
function checkinput( ) {
	var flag = true;
	var message = '請輸入';
	
	if( create_dis.discuss_name.value.length > 0) {
		flag = true && flag;
	}
	else {
		flag = false && flag;
		message = message + ' 討論區名稱';
	}

	if(create_dis.comment.value.length > 0) {
		flag = true && flag;
	}
	else {
		flag = false && flag;
		message = message + ' 討論區主旨';
	}

	if(create_dis.isgroup[0].checked) {
		flag = true && flag;
	}
	else {
		if(isNaN(parseInt(create_dis.group_num.value))) {
			flag = false && flag;
			message = message + ' 正確組別';
		}
		else
			flag = true && flag;			
	}

	if(!flag) {
		alert(message);
	}
	return flag;
}

function checkinput2( ) {
	var flag = true;
	var message = '請輸入';
	
	if(isNaN(parseInt(create_batch.amount.value))) {
		flag = false && flag;
		message = message + ' 正確數目';
	}
	else {
		flag = true && flag;
	}

	if( create_batch.discuss_name.value.length > 0) {
		flag = true && flag;
	}
	else {
		flag = false && flag;
		message = message + ' 討論區名稱';
	}

	if( create_batch.discuss_name.value.indexOf("%d") == -1 ) {
		flag = false && flag;
		message = message + ' \%d';	
	}

	if( create_batch.comment.value.length > 0) {
		flag = true && flag;
	}
	else {
		flag = false && flag;
		message = message + ' 討論區主旨';
	}

	if(!flag) {
		alert(message);
	}
	return flag;
}
</script>
</head>
<body background="/images/img/bg.gif">
<IMG SRC="/images/img/b52.gif">
<center>
<form action="cre_discuss.php" method="post" name="create_dis" onsubmit="return checkinput();">
<input type="hidden" name="amount" value="1">
<table border=2 width=75%>
<caption>建立單一討論區</caption>
<tr bgcolor=#edf3fa>
	<td>討論區名稱
	<td bgcolor=#cdeffc><input type="text" name="discuss_name" size=30 maxlength=100>
<tr bgcolor=#edf3fa>
	<td>討論區主旨
	<td bgcolor=#cdeffc><input type="comment" name="comment" size=30 maxlength=100>
<tr bgcolor=#edf3fa><td>是否為小組討論區
    <td bgcolor=#cdeffc>
		<input type="radio" name="isgroup" value=0 checked>否
	    <input type="radio" name="isgroup" value=1>是第<input type="text" name="group_num" size=2 maxlength=2 onFocus="create_dis.isgroup[1].checked = true;">組
<tr bgcolor=#edf3fa><td>討論區瀏覽權限
    <td bgcolor=#cdeffc><input type="radio" name="access" value="0" checked>公開
    <input type="radio" name="access" value="1">私人(只有小組成員可以看)
</table>
<input type="submit" name="submit" value="確定新增"><input type="reset" name="reset">
</form>
<hr>
<form action="cre_discuss.php" method="post" name="create_batch" onSubmit="return checkinput2();">
<table border=2 width=75%>
<caption>批次建立討論區</caption>
<tr bgcolor=#edf3fa>
	<td>欲建立討論區數目
	<td bgcolor=#cdeffc><input type="text" name="amount" size=5 maxlength=8>
<tr bgcolor=#edf3fa>
	<td>討論區名稱(請將要用數字取代的地方用 %d 代替)
	<td bgcolor=#cdeffc><input type="text" name="discuss_name" size=30 maxlength=100>
<tr bgcolor=#edf3fa>
	<td>討論區主旨
	<td bgcolor=#cdeffc><input type="comment" name="comment" size=30 maxlength=100>
<tr bgcolor=#edf3fa><td>是否為小組討論區
    <td bgcolor=#cdeffc>
		<input type="radio" name="isgroup" value=0 checked>否
	    <input type="radio" name="isgroup" value=1>是
<tr bgcolor=#edf3fa><td>討論區瀏覽權限
    <td bgcolor=#cdeffc><input type="radio" name="access" value="0" checked>公開
    <input type="radio" name="access" value="1">私人(只有小組成員可以看)
</table>
<input type="submit" name="submit" value="確定新增"><input type="reset" name="reset">
</form>
<hr>
<input type="button" value="回討論區一覽" onClick="location.href='dis_list.php?PHPSESSID=PHP_ID'">
</center>
</body>
</html>