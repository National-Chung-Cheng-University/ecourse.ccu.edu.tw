<HTML>
<HEAD>
<TITLE></TITLE>
<SCRIPT LANGUAGE = JavaScript>
function writeText(msg) {
	msg = msg.replace ( ":)", "<img src=/images/face/1.gif>" );
	msg = msg.replace ( ":d", "<img src=/images/face/2.gif>" );
	msg = msg.replace ( ":o", "<img src=/images/face/3.gif>" );
	msg = msg.replace ( ":p", "<img src=/images/face/4.gif>" );
	msg = msg.replace ( ":@", "<img src=/images/face/5.gif>" );
	msg = msg.replace ( ":s", "<img src=/images/face/6.gif>" );
	msg = msg.replace ( ":$", "<img src=/images/face/7.gif>" );
	msg = msg.replace ( ":(", "<img src=/images/face/8.gif>" );
	msg = msg.replace ( ":'(", "<img src=/images/face/9.gif>" );
	msg = msg.replace ( ":|", "<img src=/images/face/10.gif>" );
	msg = msg.replace ( "(i)", "<img src=/images/face/11.gif>" );
	msg = msg.replace ( "(l)", "<img src=/images/face/12.gif>" );
	msg = msg.replace ( "(k)", "<img src=/images/face/13.gif>" );
	msg = msg.replace ( "(ll)", "<img src=/images/face/14.gif>" );
	msg = msg.replace ( ":-)", "<img src=/images/face/1.gif>" );
	msg = msg.replace ( ":-D", "<img src=/images/face/2.gif>" );
	msg = msg.replace ( ":-O", "<img src=/images/face/3.gif>" );
	msg = msg.replace ( ":-P", "<img src=/images/face/4.gif>" );
	msg = msg.replace ( ":-@", "<img src=/images/face/5.gif>" );
	msg = msg.replace ( ":-S", "<img src=/images/face/6.gif>" );
	msg = msg.replace ( ":-$", "<img src=/images/face/7.gif>" );
	msg = msg.replace ( ":-(", "<img src=/images/face/8.gif>" );
	msg = msg.replace ( ":-|", "<img src=/images/face/10.gif>" );
	msg = msg.replace ( "(I)", "<img src=/images/face/11.gif>" );
	msg = msg.replace ( "(L)", "<img src=/images/face/12.gif>" );
	msg = msg.replace ( "(K)", "<img src=/images/face/13.gif>" );
	msg = msg.replace ( "(LL)", "<img src=/images/face/14.gif>" );
 parent.frames["output"].document.write(msg)
 parent.frames["output"].scroll(0,65535)
}
function writeField(area){
 parent.frames["field"].document.areaform.out.value = area
}
</SCRIPT>
</HEAD>
<frameset cols=*,365 border="0" frameborder=NO>
<frameset rows=45,* border="0" frameborder=NO>
    <frame src=/learn/pub_talk/field.htm name=field scrolling=no noresize border="0" frameborder="NO" >
    <frameset rows="*,45" border="0" frameborder=NO> 
      <frameset cols="20,*" border="0" frameborder=NO> 
        <frame src=/learn/pub_talk/border.htm scrolling=no noresize border="0" frameborder="NO">
        <frame src=/learn/pub_talk/output.htm name=output scrolling=auto noresize border="0" frameborder="NO">
      </frameset>
      <frame src=/learn/pub_talk/border.htm scrolling=no noresize border="0" frameborder="NO" >
    </frameset>
</frameset>
  <frame src="./chatr.php?nickname=NIC&colorNum=NC&port=PORT&PHPSESSID=PHPSID" name=input noresize border="0" frameborder="NO" >
</frameset><noframes></noframes>
</html>