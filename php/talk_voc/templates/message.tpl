<HEAD>
<TITLE>訊息視窗</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
</HEAD>
<BODY OnUnload="window.opener.SetNull (PERID);">
<BR>送訊息給:
<FONT color="#0000FF"><B>PEERNAME</B></FONT><BR>
<FORM NAME=theform>
<INPUT type=hidden name=peer_id value=PERID>
<INPUT type=hidden name=myname value=MYNAME>
<TEXTAREA name="pastmessage" rows=10 cols=35>TEXTC</TEXTAREA>
<BR><INPUT type=text name="textmessage"><BR>
<A href='javascript:window.opener.SendTextMsg (theform)' >傳送</A>
</FORM>
</BODY>
