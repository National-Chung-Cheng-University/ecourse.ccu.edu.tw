<html>
<head>
<meta http-equiv="Content-Language" content="zh-tw">
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<title>檔案列表</title>
<script language="JavaScript">
function OnPreview()
{
	var fname = list.filename;
	if( fname == '' )
		alert( '請選取檔案' );
	else
	{
		preview.src = fname;
	}
}

function OnRefresh()
{
	list.location.reload();
}

function OnUpload()
{
	var top = (window.screen.availHeight - 100) / 2;
	var left = (window.screen.availWidth - 400 ) / 2;
	var child = window.open( "image.php?PHPSESSID=PHPSD&action=uploadpage", "upload", "height=100,width=400,top="+top+",left="+left+",toolbar=no,status=no,menubar=no,location=no" );
}

function OnOK()
{
	var fname = list.filename;
	if( fname == '' )
	{
		alert( '請選取圖檔' );
		return false;
	}
	if( window.name == 'image' )
	{
		window.opener.Image_Paste( 'upload/' + fname );
		window.close();
	}
}
</script>
</head>
<body>

<table border="0">
<tr>
<th nowrap>檔案列表</th>
<th nowrap>預覽區</th>
</tr>
<tr>
<td valign="top">
<iframe id="list" width="325" height="210" src="list_img.php"></iframe>
<br>
<input type="button" value="更新列表" onclick="OnRefresh();"> 
<input type="button" value="上傳圖片" onclick="OnUpload();">
<input type="button" value="[ 確定 ]" onclick="OnOK();">
<input type="button" value=" 取消 " onclick="window.close();">
</td>
<td valign="top">
<input type="button" value="預覽圖片" onclick="OnPreview();">
<p><img id="preview" src="/images/p1.gif"></p>
</td>
</tr>
</table>
</body>
</html>