<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<title>E-Board</title>
<style>
<!--
.ming9       { font-family: �s�ө���; font-size: 9pt }
-->
</style>
<script language="JavaScript">
function OnLoad()
{
	if( navigator.userAgent.search( /MSIE [56]/i ) == -1 )
	{
		alert( '�z�ϥΪ��O ' + navigator.userAgent + '\n���{���ȾA�Ω� Microsoft Internet Explorer 5.x/6.x' );
		document.location.href = 'http://www.microsoft.com/windows/ie/';
	}

	UpdateRoomList();
}

function OnOK()
{
	if( form.room.value == '' )
	{
		alert( '�п�ܰQ�׫ǩΪ̿�J�Q�׫ǦW��' );
		return;
	}
	else if( form.username.value == '' )
	{
		alert( '�п�J�ϥΪ̦W��' );
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
				<td rowspan="2" valign="top" bgcolor="#CACAFF">�Q�׫ǦC��<br>
					&nbsp; <select style="width:150" size="10" name="list" class="ming9" onchange="SelectRoom()">
					</select>&nbsp;<br>
					&nbsp;</td>
				<td valign="top" bgcolor="#FFCECE">�إ߷s�Q�׫�<br>
					<!--webbot bot="Validation" s-data-type="String"
					b-allow-letters="TRUE" b-allow-digits="TRUE"
					b-value-required="TRUE" i-minimum-length="4"
					i-maximum-length="20" --><input type="text" name="room" size="25" maxlength="20" class="ming9">
					<p class="ming9"><font color="#FF0000">�Y�W�ٻP�w�g�s�b���Q�׫ǦW�ٹp<br>
					�P, �h�N�����[�J�ӰQ�׫ǦӤ��s<br>
					�إt�~���Q�׫�</font></td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#C8FFC8">
				�b��:USER_NAME<input type="hidden" name="username" id="username" class="ming9" value="USER_NAME"><br>
				<p align="center"><input onclick="UpdateRoomList()" type="button" value=" ��s�C�� ">
				<input type="button" value=" &gt; �T�w &lt;" onclick="OnOK()"></p>
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
