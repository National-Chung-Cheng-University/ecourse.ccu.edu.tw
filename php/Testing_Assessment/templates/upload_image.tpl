<html>
<head>
<title>�ɮפW��</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<script language="JavaScript">
	var first = new Boolean( false );

	function OnFocus()
	{
		if( first == false )
		{
			first = true;
			form.comment.value = '';
			form.comment.focus();
		}
	}
			
	function OnSubmit()
	{
		if( form.upfile.value != '' )
		{
			var re = /.*\\/gi;
			var re1 = /.*\//gi;
			form.upname.value = form.upfile.value.replace( re, '' );
			form.upname.value = form.upname.value.replace( re1, '' );
			return true;
		}
		alert( '�Ы��w�@���ɮ�' );
		return false;
	}
</script>
</head>
<body>
<center><font color="#ff0000">MESSAGE</font></center>
<form name="form" action="image.php" method="POST" enctype="multipart/form-data">
<input type="hidden" name="upname">
<input type="hidden" name="action" value="upload">
<input type="file" size="30" name="upfile"><br>
<input type="text" size="30" name="comment" value="(�Ϥ�����)" onfocus="OnFocus();">
<input type="submit" value=" �W�� " onclick="return OnSubmit();">
<input type="button" value=" ����/���� " onclick="window.close();">
</form>
</body>
</html>
