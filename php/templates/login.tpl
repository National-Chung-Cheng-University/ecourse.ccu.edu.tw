<Html><Head>
<Title>TITLE</Title>
RSSLINK
<SCRIPT LANGUAGE ="JAVASCRIPT" >
var API = null;
var lesson_id = null;
var a_id = null;
var list = null;
                                                                                
function initAPI()
{
   API = top.frames["api"].document.applets["APIAdapter"];
}

function setscovalue ( lessonid , aid, list ) {
	lesson_id = lessonid;
	a_id = aid;
	list = list;
	if ( a_id != null )
		changeSCOContent();
}

function changeSCOContent()
{
	var scoWinType = typeof(window.frames["target"].frames["html"].scoWindow);
	var theDate = new Date();
	var foo = theDate.getTime();
	var theURL = "/php/textbook/scorm_lesson.php?PHPSESSID=PHPID&list="+list+"&lessonid="+lesson_id+"&aid="+a_id+"&foo="+foo;
	a_id = null;
	if (scoWinType != "undefined" && scoWinType != "unknown")
	{
		if (window.frames["target"].frames["html"].scoWindow != null)
		{
		 	// there is a child content window so display the sco there.
			window.frames["target"].frames["html"].scoWindow.document.location.href = theURL;
		}
		else
		{
			window.frames["target"].frames["html"].document.location.href = theURL;
		}
	}
	else
	{
		window.frames["target"].frames["html"].document.location.href = theURL;
		//  scoWindow is undefined which means that the content frame
		//  does not contain the lesson menu at this time.
	}
}
</SCRIPT>
</Head>

<frameset rows="62,*" frameborder="NO" border="0" framespacing="0" cols="*" LOAD> 
  <frameset cols="150,*" rows="*" border="0" framespacing="0" frameborder="NO"> 
    <frameset cols=*,150 frameborder=0 frameborder="NO">
      <Frame Src="APIPAGE" Name=api scrolling=no frameborder="NO" >
      <Frame Src="APPFILE" Name=Message scrolling=no frameborder="NO">
    </FrameSet>
    <frame name="options" scrolling="YES" noresize src="BARFILE" >
  </frameset>
  <frame name="target" >  
</frameset>

<noframes> 
<body bgcolor="#FFFFFF" text="#000000">
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-2043022-1";
urchinTracker();
</script>
</body>
</noframes> 
</html>
