<html>
<head>
<title>Central Chat Server</title>

<script language="javascript">
	var textmsgwin = new makeArray (32768);

	function SendTextMsg (form) {
		CentralChatServer.SendTextMessage (form.peer_id.value, form.textmessage.value);
		if (form.peer_id.value != "32767") form.pastmessage.value = form.pastmessage.value + form.myname.value + ": " + form.textmessage.value + "\n";
		else form.pastmessage.value = form.pastmessage.value + "\n" + form.myname.value + "告訴大家: " + form.textmessage.value + "\n\n";
	}
	
	function makeArray (len) {
		for (var i=0; i<len; i++) this[i] = null;
		this.length = len;
	}
	
	function SetNull (a) {
		textmsgwin[a] = null;
	}

</script>

<script language="javascript" for="CentralChatServer" event="CreateNewWindow (peer_id, peer_name, myname)">
	peer_id = peer_id.replace ( " ", "" );
	textmsgwin[peer_id] = window.open("./message.php?PHPSESSID=PHPSID&PerN="+peer_name+"&PerI=" + peer_id + "&MyN=" + myname + "", '', 'resizable=1,scrollbars=1,width=350,height=290');
	textmsgwin[peer_id].document.close ();
</script>

<script language="javascript" for="CentralChatServer" event="ReceiveTextMessage (peer_id, peer_name, myname, text)">
	peer_id = peer_id.replace ( " ", "" );
	alert ("您有一個訊息傳送過來...");
	if (textmsgwin[peer_id] == null) {
		textmsgwin[peer_id] = window.open("./message.php?PHPSESSID=PHPSID&PerN="+peer_name+"&PerI=" + peer_id + "&MyN=" + myname + "&text=" + text, '', 'resizable=1,scrollbars=1,width=350,height=290');
		textmsgwin[peer_id].document.close ();
	}
	textmsgwin[peer_id].document.theform.pastmessage.value = textmsgwin[peer_id].document.theform.pastmessage.value + text + "\n";
</script>

</head>
<body>
<center>
<OBJECT ID="CentralChatServer" CODEBASE="/learn/talk_int/server/CentralChatServer.cab" CLASSID="CLSID:69CBD9DC-A4C9-4087-98E5-1208598DD0DC" HEIGHT="500" WIDTH="500">
<PARAM NAME="ClassID" VALUE="AID">
<PARAM NAME="ClassName" VALUE="CNAME">
<PARAM NAME="ClassNo" VALUE="CID">
<PARAM NAME="TeacherName" VALUE="UNAME">

</OBJECT>
</center>
</body>
</html>

