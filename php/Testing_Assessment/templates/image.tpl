<html>
<head>
<meta http-equiv="Content-Language" content="zh-tw">
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<title>�ɮצC��</title>
<script language="JavaScript">
function OnPreview()
{
	var fname = list.filename;
	if( fname == '' )
		alert( '�п���ɮ�' );
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
		alert( '�п������' );
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
<th nowrap>�ɮצC��</th>
<th nowrap>�w����</th>
</tr>
<tr>
<td valign="top">
<iframe id="list" width="325" height="210" src="list_img.php"></iframe>
<br>
<input type="button" value="��s�C��" onclick="OnRefresh();"> 
<input type="button" value="�W�ǹϤ�" onclick="OnUpload();">
<input type="button" value="[ �T�w ]" onclick="OnOK();">
<input type="button" value=" ���� " onclick="window.close();">
</td>
<td valign="top">
<input type="button" value="�w���Ϥ�" onclick="OnPreview();">
<p><img id="preview" src="/images/p1.gif"></p>
</td>
</tr>
</table>
</body>
</html>