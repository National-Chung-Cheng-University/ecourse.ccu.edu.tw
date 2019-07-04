<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<title>E-Board</title>
<style>
<!--
.ming9       { font-family: 新細明體; font-size: 9pt }
-->
</style>
<script language="JavaScript">
function OnLoad()
{
	if( navigator.userAgent.search( /MSIE [56]/i ) == -1 )
	{
		alert( '您使用的是 ' + navigator.userAgent + '\n本程式僅適用於 Microsoft Internet Explorer 5.x/6.x' );
		document.location.href = 'http://www.microsoft.com/windows/ie/';
	}

	UpdateRoomList();
}

function OnOK()
{
	if( form.room.value == '' )
	{
		alert( '請選擇討論室或者輸入討論室名稱' );
		return;
	}
	else if( form.username.value == '' )
	{
		alert( '請輸入使用者名稱' );
		return;
	}
	form.submit();
}

function SelectRoom()
{
	form.room.value = form.list.value;
}

function UpdateRoomList()
{
	try {
		var r = lister.getServerRooms();
	}
	catch( e )
	{
		alert( "socket error" ); 
		return;
	}
	if( r == '' )
		return;

	var ss = r.split( "\t" );
	for( i = 0; i < form.list.length; i++ )
		form.list.remove(0);
	
	for( i =0; i < ss.length; i++ )
	{
		if( ss[i] != '' )
		{
			var o = new Option;
			o.value = o.text = ss[i];
			form.list.add( o );
		}
	}
}
</script>
</head>

<body onload="OnLoad();">

<p align="center"><img border="0" src="/learn/eboard/images/eboard.gif" width="378" height="104"></p>
<hr noshade size="1" color="#000080">
<form name="form" method="POST" action="./eboard.php">
	<div align="center">
		<table border="0">
			<tr>
				<td rowspan="2" valign="top" bgcolor="#CACAFF">討論室列表<br>
					&nbsp; <select style="width:150" size="10" name="list" class="ming9" onchange="SelectRoom()">
					</select>&nbsp;<br>
					&nbsp;</td>
				<td valign="top" bgcolor="#FFCECE">建立新討論室<br>
					<!--webbot bot="Validation" s-data-type="String"
					b-allow-letters="TRUE" b-allow-digits="TRUE"
					b-value-required="TRUE" i-minimum-length="4"
					i-maximum-length="20" --><input type="text" name="room" size="25" maxlength="20" class="ming9">
					<p class="ming9"><font color="#FF0000">若名稱與已經存在的討論室名稱雷<br>
					同, 則將直接加入該討論室而不新<br>
					建另外的討論室</font></td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#C8FFC8">
				帳號:USER_NAME<input type="hidden" name="username" id="username" class="ming9" value="USER_NAME"><br>
				<p align="center"><input onclick="UpdateRoomList()" type="button" value=" 更新列表 ">
				<input type="button" value=" &gt; 確定 &lt;" onclick="OnOK()"></p>
				</td>
			</tr>
		</table>
	</div>
</form>
<hr noshade size="1" color="#000080">
<p align="right"><font face="Verdana" size="1">&copy;2001 High Speed Network 
Group Lab. All Rights Reserved.<br>
Computer Science Dept. National Chung Cheng University.<br>
Written by Jian-cheng Lin. mail to <a href="mailto:andrel@mail2000.com.tw">me</a>.</font></p>

<applet name="lister" code="RoomListerApplet.class" codebase="http://SERVERNAME/learn/eboard" width="1" height="1">
<param name="port" value="7798">
<param name="server" value="SERVERNAME">
</applet>
</body>

</html>
