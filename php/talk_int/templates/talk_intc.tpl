<html>
<head>
<title>Central Chat Client</title>

<script language="javascript">
	var textmsgwin = new makeArray (32768);

	function SendTextMsg (form) {
		CentralChatClient.SendTextMessage (form.peer_id.value, form.textmessage.value);
		form.pastmessage.value = form.pastmessage.value + form.myname.value + ": " + form.textmessage.value + "\n";
	}
	
	function makeArray (len) {
		for (var i=0; i<len; i++) this[i] = null;
		this.length = len;
	}
	
	function SetNull (a) {
		textmsgwin[a] = null;
	}

</script>

<script language="javascript" for="CentralChatClient" event="CreateNewWindow (peer_id, peer_name, myname)">
	peer_id = peer_id.replace ( " ", "" );
	textmsgwin[peer_id] = window.open("./message.php?PHPSESSID=PHPSID&PerN="+peer_name+"&PerI=" + peer_id + "&MyN=" + myname + "", '', 'resizable=1,scrollbars=1,width=350,height=290');
	textmsgwin[peer_id].document.close ();
</script>

<script language="javascript" for="CentralChatClient" event="ReceiveTextMessage (peer_id, peer_name, myname, text)">
	peer_id = peer_id.replace ( " ", "" );
	alert ("您有一個訊息傳送過來...");
	if (textmsgwin[peer_id] == null) {
		textmsgwin[peer_id] = window.open("./message.php?PHPSESSID=PHPSID&PerN="+peer_name+"&PerI=" + peer_id + "&MyN=" + myname + "&text=" + text, '', 'resizable=1,scrollbars=1,width=350,height=290');
		textmsgwin[peer_id].document.close ();
	}
	if (peer_id != "32767") textmsgwin[peer_id].document.theform.pastmessage.value = textmsgwin[peer_id].document.theform.pastmessage.value + text + "\n";
	else textmsgwin[peer_id].document.theform.pastmessage.value = textmsgwin[peer_id].document.theform.pastmessage.value + "\n廣播訊息...\n" + text + "\n\n";
</script>

</head>
<body>
<center>
<OBJECT ID="CentralChatClient" CODEBASE="/learn/talk_int/client/CentralChatClient.cab" CLASSID="CLSID:DBC7ED73-5376-46FE-9566-9580B27025F7" HEIGHT="500" WIDTH="500">
<PARAM NAME="ClassID" VALUE="AID">
<PARAM NAME="ClassName" VALUE="CNAME">
<PARAM NAME="ClassNo" VALUE="CID">
<PARAM NAME="TeacherName" VALUE="TNAME">
<PARAM NAME="UserName" VALUE="UNAME">
<PARAM NAME="ServerIP" VALUE="SERVER">
</OBJECT>
</center>
</body>
</html>

