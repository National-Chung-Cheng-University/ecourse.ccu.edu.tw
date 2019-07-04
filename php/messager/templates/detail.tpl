<html>
<head>
<title>同學清單</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<meta http-equiv="refresh" content="60;detail.php"> 
<STYLE type=text/css>
 body { scrollbar-face-color: #4d6eb2; scrollbar-arrow-color: #FFFFFF; scrollbar-track-color: #DDDDFF; scrollbar-shadow-color: #054878; scrollbar-3dlight-color: black; }
</STYLE>
<script LANGUAGE=JavaScript>
<!--
CLOSEclose();
HAVEwindow.open("","MID","resizable=1,scrollbars=1,width=350,height=400");
//-->
</script>
</head>
<body background=/images/img/bg.gif>
<form name=income method=post action="./messager.php" target="MID">
<input type=hidden name=user value="USER">
<input type=hidden name=posttime value="TIME">
<input type=hidden name=multe value="MULTI">
<input type=hidden name=back value="MESSAGE">
</form>
<script LANGUAGE=JavaScript>
<!--
HAVEdocument.income.submit();
//-->
</script>
<table width=90%>
<tr><td>
<a href="#" onClick="window.open('./multmesg.php?PHPSESSID=PHPID','','resizable=1,scrollbars=1,width=200,height=400')"><font size=-1>多人訊息傳送</font></a><br><a href="#" onClick="window.open('./gbfriend.php?PHPSESSID=PHPID','','resizable=1,scrollbars=1,width=350,height=450')"><font size=-1>好友名單</font></a>
</td><td>
<font color="#0000FF"><b>風#：</b></font>UID
</td>
</tr>
</table>
<center>
<table border=0 width=90%>
<tr><td COLSPAN=6>
<table width=100%><tr><td width=30%><hr></td><td align="center"><font color="#005500" size=-1>線上朋友</font></td><td width=30%><hr></td></tr></table>
</td></tr>
<!-- BEGIN DYNAMIC BLOCK: friend_list -->
<TR><TD align="center" ><img src="/images/pmoing.gif" width = 10 alt="COURSE_ID3"></TD><TD align="center" >HOME3</TD><TD align="center" width="10%">MAIL3</TD><!--<TD align="center" width="10%"><font color='#005500'>USER_ID3</font></TD>--><TD align="center" ><a href="#" onClick="window.open('./messager.php?user=AID3&PHPSESSID=PHPID','','resizable=1,scrollbars=1,width=350,height=290')"><font color='#005500' size=-1>USER_NAME3</font></a></TD><TD align="left" ><font size=-1>USER_NICK3</font></TD><TD align="left" ><font size=-1>IDLE3</font></TD></TR>
<!-- END DYNAMIC BLOCK: friend_list -->

<tr><td COLSPAN=6>
<table width=100%><tr><td width=30%><hr></td><td align="center"><font color="#AAAA22" size=-1>離線朋友</font></td><td width=30%><hr></td></tr></table>
</td></tr>
<!-- BEGIN DYNAMIC BLOCK: friend_oist -->
<TR><TD align="center" ><img src="/images/pmoing.gif" width = 10 alt="COURSE_ID4"></TD><TD align="center" >HOME4</TD><TD align="center" width="10%">MAIL4</TD><!--<TD align="center" width="10%"><font color='#AAAA22'>USER_ID4</font></TD>--><TD align="center"><a href="#" onClick="window.open('./messager.php?user=AID4&PHPSESSID=PHPID','','resizable=1,scrollbars=1,width=350,height=290')"><font color='#AAAA22' size=-1>USER_NAME4</font></a></TD><TD align="left" ><font size=-1>USER_NICK4</font></TD><TD align="left" ><font size=-1>IDLE4</font></TD></TR>
<!-- END DYNAMIC BLOCK: friend_oist -->

<tr><td COLSPAN=6>
<table width=90%><tr><td width=40%><hr></td><td align="center"><font color="#0000FF" size=-1>同學</font></td><td width=40%><hr></td></tr></table>
</td></tr>
<!-- BEGIN DYNAMIC BLOCK: course_list -->
<TR><TD align="center"><img src="/images/pmoing.gif" width = 10 alt="COURSE_ID1"></TD><TD align="center">HOME1</TD><TD align="center">MAIL1</TD><!--<TD align="center"><font color='#0000FF'>USER_ID1</font></TD>--><TD align="center" COLSPAN=2><a href="#" onClick="window.open('./messager.php?user=AID1&PHPSESSID=PHPID','','resizable=1,scrollbars=1,width=350,height=290')"><font color='#0000FF' size=-1>USER_NAME1</font></a></TD><TD align="left" ><font size=-1>IDLE1</font></TD></TR>
<!-- END DYNAMIC BLOCK: course_list -->

<tr><td COLSPAN=6>
<table width=90%><tr><td width=40%><hr></td><td align="center"><font color="#FF0000" size=-1>系統</font></td><td width=40%><hr></td></tr></table>
</td></tr>
<!-- BEGIN DYNAMIC BLOCK: system_list -->
<TR><TD align="center"><img src="/images/paftern.gif" width = 10 alt="COURSE_ID2"></TD><TD align="center">HOME2</TD><TD align="center">MAIL2</TD><!--<TD align="center"><font color='#FF0000'>USER_ID2</font></TD>--><TD align="center" COLSPAN=2><a href="#" onClick="window.open('./messager.php?user=AID2&PHPSESSID=PHPID','','resizable=1,scrollbars=1,width=350,height=290')"><font color='#FF0000' size=-1>USER_NAME2</font></a></TD><TD align="left" ><font size=-1>IDLE2</font></TD></TR>
<!-- END DYNAMIC BLOCK: system_list -->
</table>
</center>
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-2043022-1";
urchinTracker();
</script>
</body>
</html>