<html>
<head>
<title>Distributed Chat Client</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<script language="javascript">
	var textmsgwin = new makeArray (32768);

	function SendTextMsg (form) {
		DistributedChatClient.SendTextMessage (form.peer_id.value, form.textmessage.value);
		if (form.peer_id.value != "32767") form.pastmessage.value = form.pastmessage.value + form.myname.value + ": " + form.textmessage.value + "\n";
		else form.pastmessage.value = form.pastmessage.value + "\n" + form.myname.value + "�i�D�j�a: " + form.textmessage.value + "\n\n";
		}
	
	function makeArray (len) {
		for (var i=0; i<len; i++) this[i] = null;
		this.length = len;
		}
	
	function SetNull (a) {
		textmsgwin[a] = null;
		}
		
	
	
</script>

<script language="javascript" for="DistributedChatClient" event="CreateNewWindow (peer_id, peer_name, myname)">
	peer_id = peer_id.replace ( " ", "" );
	textmsgwin[peer_id] = window.open("./message.php?PHPSESSID=PHPSID&PerN="+peer_name+"&PerI=" + peer_id + "&MyN=" + myname + "", '', 'resizable=1,scrollbars=1,width=350,height=290');
	textmsgwin[peer_id].document.close ();
</script>

<script language="javascript" for="DistributedChatClient" event="ReceiveTextMessage (peer_id, peer_name, myname, text)">
	peer_id = peer_id.replace ( " ", "" );
	alert ("�z���@�ӰT���ǰe�L��...");
	if (textmsgwin[peer_id] == null) {
		textmsgwin[peer_id] = window.open("./message.php?PHPSESSID=PHPSID&PerN="+peer_name+"&PerI=" + peer_id + "&MyN=" + myname + "&text=" + text, '', 'resizable=1,scrollbars=1,width=350,height=290');
		textmsgwin[peer_id].document.close ();
		}
	if (peer_id != "32767") textmsgwin[peer_id].document.theform.pastmessage.value = textmsgwin[peer_id].document.theform.pastmessage.value + text + "\n";
	else textmsgwin[peer_id].document.theform.pastmessage.value = textmsgwin[peer_id].document.theform.pastmessage.value + "\n�s���T��...\n" + text + "\n\n";
</script>

</head>
<body>
<center>
<OBJECT ID="DistributedChatClient" CODEBASE="/learn/talk_voc/client/DistributedChatClient.cab" CLASSID="CLSID:C179772F-36D1-4BE6-B38E-039B8409771C" HEIGHT="500" WIDTH="500">
<PARAM NAME="ClassID" VALUE="AID">
<PARAM NAME="ClassName" VALUE="CNAME">
<PARAM NAME="ServerIP" VALUE="SIP">
<PARAM NAME="ClassNo" VALUE="CID">
<PARAM NAME="UserName" VALUE="UNAME">
</OBJECT>
</center>
</body>
</html>

