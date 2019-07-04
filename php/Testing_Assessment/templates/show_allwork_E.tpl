<HTML>
<head>
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=big5">
</head>
<BODY background="/images/img_E/bg.gif">
<p>
<img src="/images/img_E/b41.gif">
</p>
<BR><BR>
<center>
<font color="#FF0000">MESSAGE</font>
<table border="0" align="center" cellpadding="0" cellspacing="0" >
<tr> 
<td> 
<div align="right"><img src="/images/skinSKINNUM/bor/bor_01.GIF" width="12" height="11"></div>
</td>
<td> 
<div align="center"><img src="/images/skinSKINNUM/bor/bor_02.GIF" width="100%" height="11"></div>
</td>
<td> 
<div align="left"><img src="/images/skinSKINNUM/bor/bor_03.GIF" width="17" height="11"></div>
</td>
</tr>
<tr> 
<td height=10> 
<div align="right"><img src="/images/skinSKINNUM/bor/bor_04.GIF" width="12" height="100%"></div>
</td>
<td bgcolor="#CCCCCC">
<table border=0 cellspacing=1 cellpadding=3 width="100%" >
<tr bgcolor="#000066" align=center><td><font color="#FFFFFF">Homework Name</font></td><td><font color="#FFFFFF">Topic</font></td><td><font color="#FFFFFF">Date Line</font></td><td><font color="#FFFFFF">Ratio</font></td><td><font color="#FFFFFF">Comment</fotn></td><td><font color="#FFFFFF">Answer</font></td><td><font color="#FFFFFF">Hnadin Homework</font></td><td><font color="#FFFFFF">File Upload</font></td><td><font color="#FFFFFF">See The Answer You've Handin</font></td><td><font color="#FFFFFF">See Perfect Homework</font></td></tr>
<!-- BEGIN DYNAMIC BLOCK: row -->
<tr bgcolor="COLOR">
<td>WORKNAME</td>
<td><a href="#" onClick="window.open('./show_allwork.php?work_id=WORKID&action=showwork&PHPSESSID=PHPID', '', 'width=800,height=600,resizable=1,scrollbars=1');">Topic</a></td>
<td>WORKDUE</td>
<td>WORKRATIO%</td>
<form method=POST action=show_allwork.php><td>
<input type=hidden name=action value=SEECOMMENT>
<input type=hidden name=work_id value=WORKID>
<input type=submit value=SEECOM>
</td></form>
<form method=POST action=show_allwork.php><td>
<input type=submit value=SEEANS>
<input type=hidden name=work_id value=WORKID>
<input type=hidden name=action value=seeans>
</td></form>
<form method=POST action=show_allwork.php><td>
<input type=hidden name=action value=editanswer>
<input type=hidden name=work_id value=WORKID>
<input type=submit value=Handin_Homework STATUS>
</td></form>
<form method=POST action=show_allwork.php><td>
<input type=hidden name=action value=uploadwork>
<input type=hidden name=work_id value=WORKID>
<input type=submit value=File_Upload STATUS>
</td></form>
<form method=POST action=show_allwork.php><td>
<input type=hidden name=action value=seemywork>
<input type=hidden name=work_id value=WORKID>
<input type=submit value=See_Handin_Answer>
</td></form>
<form method=get action=show_allwork.php><td>
<input type=hidden name=action value=seegoodwork>
<input type=hidden name=work_id value=WORKID>
<input type=submit value=See_Perfect_Answer>
</td></form>
</tr>
<!-- END DYNAMIC BLOCK: row -->
</table>
</td>
<td height=10> 
<div align="left"><img src="/images/skinSKINNUM/bor/bor_06.GIF" width="17" height="100%"></div>
</td>
</tr>
<tr> 
<td> 
<div align="right"><img src="/images/skinSKINNUM/bor/bor_07.GIF" width="12" height="17"></div>
</td>
<td> 
<div align="center"><img src="/images/skinSKINNUM/bor/bor_08.GIF" width="100%" height="17"></div>
</td>
<td> 
<div align="left"><img src="/images/skinSKINNUM/bor/bor_09.GIF" width="17" height="17"></div>
</td>
</tr>
</table><br>
</center>
Usage manual:<BR>
How to hand in homework<BR>
1.You can press "hand_in" button, and edit your answer in edit area.<BR>
2.Or you can press "Upload" button to upload your answer file. It is important that the "File link name" field can't be empty!<BR>
The text in File name field is the link that teacher to read your homework on Web.
</BODY>
</HTML>
