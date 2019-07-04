<html>
<head>
<title>Student</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<link rel="stylesheet" href="/images/skinSKINNUM/css/a-rt.css" type="text/css">
<script language="JavaScript">
<!--

function shownews () {

  parent.target.window.location='./news/news.php';
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_showHideLayers() { //v3.0
  var i,p,v,obj,args=MM_showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3) if ((obj=MM_findObj(args[i]))!=null) { v=args[i+2];
    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v='hide')?'hidden':v; }
    obj.visibility=v; }
}

function MM_timelinePlay(tmLnName, myID) { //v1.2
  //Copyright 1997 Macromedia, Inc. All rights reserved.
  var i,j,tmLn,props,keyFrm,sprite,numKeyFr,firstKeyFr,propNum,theObj,firstTime=false;
  if (document.MM_Time == null) MM_initTimelines(); //if *very* 1st time
  tmLn = document.MM_Time[tmLnName];
  if (myID == null) { myID = ++tmLn.ID; firstTime=true;}//if new call, incr ID
  if (myID == tmLn.ID) { //if Im newest
    setTimeout('MM_timelinePlay("'+tmLnName+'",'+myID+')',tmLn.delay);
    fNew = ++tmLn.curFrame;
    for (i=0; i<tmLn.length; i++) {
      sprite = tmLn[i];
      if (sprite.charAt(0) == 's') {
        if (sprite.obj) {
          numKeyFr = sprite.keyFrames.length; firstKeyFr = sprite.keyFrames[0];
          if (fNew >= firstKeyFr && fNew <= sprite.keyFrames[numKeyFr-1]) {//in range
            keyFrm=1;
            for (j=0; j<sprite.values.length; j++) {
              props = sprite.values[j]; 
              if (numKeyFr != props.length) {
                if (props.prop2 == null) sprite.obj[props.prop] = props[fNew-firstKeyFr];
                else        sprite.obj[props.prop2][props.prop] = props[fNew-firstKeyFr];
              } else {
                while (keyFrm<numKeyFr && fNew>=sprite.keyFrames[keyFrm]) keyFrm++;
                if (firstTime || fNew==sprite.keyFrames[keyFrm-1]) {
                  if (props.prop2 == null) sprite.obj[props.prop] = props[keyFrm-1];
                  else        sprite.obj[props.prop2][props.prop] = props[keyFrm-1];
        } } } } }
      } else if (sprite.charAt(0)=='b' && fNew == sprite.frame) eval(sprite.value);
      if (fNew > tmLn.lastFrame) tmLn.ID = 0;
  } }
}

function MM_initTimelines() { //v4.0
    //MM_initTimelines() Copyright 1997 Macromedia, Inc. All rights reserved.
    var ns = navigator.appName == "Netscape";
    var ns4 = (ns && parseInt(navigator.appVersion) == 4);
    var ns5 = (ns && parseInt(navigator.appVersion) > 4);
    document.MM_Time = new Array(1);
    document.MM_Time[0] = new Array(LAYERNUM);
    document.MM_Time["Timeline1"] = document.MM_Time[0];
    document.MM_Time[0].MM_Name = "Timeline1";
    document.MM_Time[0].fps = 15;
    
    <!-- BEGIN DYNAMIC BLOCK: layer_time -->
    document.MM_Time[0][ORDER1] = new String("sprite");
    document.MM_Time[0][ORDER1].slot = ORDER2;
    if (ns4)
        document.MM_Time[0][ORDER1].obj = document["LayerORDER2"];
    else if (ns5)
        document.MM_Time[0][ORDER1].obj = document.getElementById("LayerORDER2");
    else
        document.MM_Time[0][ORDER1].obj = document.all ? document.all["LayerORDER2"] : null;
    document.MM_Time[0][ORDER1].keyFrames = new Array(VALUE1, VALUE2, VALUE3);
    document.MM_Time[0][ORDER1].values = new Array(3);
    if (ns5)
        document.MM_Time[0][ORDER1].values[0] = new Array("VALUE4px", "VALUE4px", "VALUE4px", "VALUE4px", "VALUE4px", "VALUE4px", "VALUE4px", "VALUE4px", "VALUE4px", "VALUE4px", "VALUE4px", "VALUE4px");
    else
        document.MM_Time[0][ORDER1].values[0] = new Array(VALUE4,VALUE4,VALUE4,VALUE4,VALUE4,VALUE4,VALUE4,VALUE4,VALUE4,VALUE4,VALUE4,VALUE4);
    document.MM_Time[0][ORDER1].values[0].prop = "left";
    if (ns5)
        document.MM_Time[0][ORDER1].values[1] = new Array("100px", "65px", "33px", "9px", "6px", "4px", "3px", "1px", "0px", "1px", "2px", "3px");
    else
        document.MM_Time[0][ORDER1].values[1] = new Array(0,0,0,0,0,0,0,0,0,0,0,0);
    document.MM_Time[0][ORDER1].values[1].prop = "top";
    if (!ns4) {
        document.MM_Time[0][ORDER1].values[0].prop2 = "style";
        document.MM_Time[0][ORDER1].values[1].prop2 = "style";
    }
    if (ns5)
        document.MM_Time[0][ORDER1].values[2] = new Array("60px", "60px", "60px", "60px", "60px", "60px", "60px", "60px", "60px", "60px", "60px", "60px");
    else
        document.MM_Time[0][ORDER1].values[2] = new Array(60,60,60,60,60,60,60,60,60,60,60,60);
    document.MM_Time[0][ORDER1].values[2].prop = "width";
    if (!ns4)
        document.MM_Time[0][ORDER1].values[2].prop2 = "style";
    <!-- END DYNAMIC BLOCK: layer_time -->

    document.MM_Time[0].lastFrame = 18 + LAYERNUM*3;
    for (i=0; i<document.MM_Time.length; i++) {
        document.MM_Time[i].ID = null;
        document.MM_Time[i].curFrame = 0;
        document.MM_Time[i].delay = 1/document.MM_Time[i].fps;
    }
}

function msgwin(message) {
  msg=window.open('','','toolbar=no,directories=no,menubar=no,width=300,height=30');
  msg.document.write('<html><head><title>資料處理中</title></head><body bgcolor="#EFFBF9"><center><h4>'+ message +'</h4></center></body></html>');
}

function cwin() {
  msg.close();
}

function Login(){
	document.login.submit()
}
//-->
</script>
</head>

<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" marginwidth="0" marginheight="5" link="#FFFFFF" topmargin="5" vlink="#CCCCCC" alink="#FF9900" background="/images/skinSKINNUM/bbg.gif" onLoad="shownews();MM_timelinePlay('Timeline1')">
<table border = 0 cellpadding="0" cellspacing="0" height="50" >
  <form action=./LOGIN name=login method=get target="_top">
    <tr> 
      <td> 
        <div align="center"><a href="./logout.php" ><b><font color="#000000" size="2">Logout/Home</font></b></a></div>
      </td>
    </tr>
    <tr> 
      <td> 
        <div align="left"> <font size="-1"><b>
          <select name = courseid onChange="Login();">
          <!-- BEGIN DYNAMIC BLOCK: course_group -->
		  <OPTGROUP label="CGROUP">
          <!-- BEGIN DYNAMIC BLOCK: course_list -->
          <option value=CID >CNAME</option>
          <!-- END DYNAMIC BLOCK: course_list -->
		  </OPTGROUP>
		  <!-- END DYNAMIC BLOCK: course_group -->
          </select>
          </b></font></div>
      </td>
    </tr>
  </form>
</table>

<!-- BEGIN DYNAMIC BLOCK: layer_show -->
<div id="LayerORDER2" style="position:absolute; width:VALUE5; height:21; z-index:10; left: VALUE4; top: 100px"> 
  <table border="0" width="VALUE5" bgcolor="#000066">
    <tr> 
      <td> 
        <div align="center"><font color="#FFFFFF" size="2"><b><font size="2"><a href="#" onClick="MM_showHideLayers(STATUS)">LSHOW</a></font></b></font></div>
      </td>
    </tr>
  </table>
</div>
<!-- END DYNAMIC BLOCK: layer_show -->

<div id="Layer11" style="position:absolute; left:120; top:30;  height:21px; z-index:9; visibility: hidden"> 
  <table border="0" cellspacing="1" cellpadding="3">
    <tr bgcolor="#33CCCC"> 
      <td width="70"> 
        <div align="center"><font size="2"><a href=./news/news.php target=target onClick="msgwin('Data Querying, Please waiting..');"><font size="2">●<font color="#000000">News</font></font></a></font></div>
      </td>
    </tr>
  </table>
</div>
<div id="Layer21" style="position:absolute; left:120; top:30;  height:21px; z-index:9; visibility: hidden"> 
  <table border="0" cellspacing="1" cellpadding="3">
    <tr bgcolor="#33CCCC"> 
      <td>
        <div align="center"><font size="2"><a href=./Courses_Admin/intro.php target=target><font size="2">●<font color="#000000">Syllabus</font></font></a></font></div>
      </td>
	  SCHED
      <td>
        <div align="center"><font size="2"><a href=./Trackin/SGQueryFrame1.php target=target><font size="2">●</font><font color="#000000">Score Query</font></a></font></div>
      </td>
	  <td width="70"> 
        <div align="center"><font size="2"><a href=./Trackin/ShowStudentRollBook.php target=target><font size="2">●</font><font color="#000000">Roll Book</font></a></font></div>
      </td>
	  SSQUERY
	  EMAIL
    </tr>
  </table>
</div>
<div id="Layer31" style="position:absolute; left:120; top:30;  height:21; z-index:8; visibility: hidden"> 
  <table border="0" cellspacing="1" cellpadding="3">
    <tr bgcolor="#33CCCC"> 
      <td>
        <div align="center"><font size="2"><a href=./textbook/material.php target=target><font size="2">●</font><font color="#000000">Web Courseware</font></a></font></div>
      </td>
	  ONLINE
    </tr>
  </table>
</div>
<div id="Layer41" style="position:absolute; left:120; top:30;  height:21; z-index:7; visibility: hidden"> 
  <table border="0" cellspacing="1" cellpadding="3">
    <tr bgcolor="#33CCCC"> 
      <td>
        <div align="center"><font size="2"><a href=./Testing_Assessment/show_allwork.php target=target><font size="2">●</font><font color="#000000">Homework</font></a></font></div>
      </td>
      <td>
        <div align="center"><font size="2"><a href=./Testing_Assessment/show_alltest.php target=target><font size="2">●</font><font color="#000000">Quiz</font></a></font></div>
      </td>
	  SHOW_QS
    </tr>
  </table>
</div>
<div id="Layer51" style="position:absolute; left:120; top:30;  height:21; z-index:6; visibility: hidden"> 
  <table border="0" cellspacing="1" cellpadding="3">
    <tr bgcolor="#33CCCC"> 
      <td> 
        <div align="center"><a href=./discuss/discuss.php target=target><font size="2">●</font><font size="2"><font color="#000000">Bulletin Board</font></font></a></div>
      </td>
	  CHAT
	  TALK_VOC
	  TALK_INT
	  EBOARD
    </tr>
  </table>
</div>
<div id="Layer61" style="position:absolute; left:120; top:30;  height:21; z-index:5; visibility: hidden"> 
  <table border="0" cellspacing="1" cellpadding="3">
    <tr bgcolor="#33CCCC"> 
	  SEARCH
	  STINFO
	  PSSWD
	  STRANK
      <td width="100"> 
        <div align="center"><font size="2"><a href=./Learner_Profile/SSModifyFrame1.php target=target><font size="2">●</font><font color="#000000">Edit Profile</font></a></font></div>
      </td>
      <td width="100"> 
        <div align="center"><font size="2"><a href=./function_list2.php target=target><font size="2">●</font><font color="#000000">System Config</font></a></font></div>
      </td>
    </tr>
  </table>
</div>
<div id="Layer71" style="position:absolute; left:120; top:30;  height:21px; z-index:4; visibility: hidden"> 
  <table border="0" cellspacing="1" cellpadding="3">
    <tr bgcolor="#33CCCC"> 
      <td width="70" bgcolor="#33CCCC"> 
        <div align="center"><font size="2"><a href=./questionary/show_allquestionary.php target=target><font size="2">●</font><font color="#000000">questionary</font></a></font></div>
      </td>
    </tr>
  </table>
</div>
</body>
</html>
<script language="JavaScript">
<!--

var ns = navigator.appName == "Netscape";
var ns4 = (ns && parseInt(navigator.appVersion) == 4);
var ns5 = (ns && parseInt(navigator.appVersion) > 4);

<!-- BEGIN DYNAMIC BLOCK: option_show -->
if (ns4) {
    document["LayerORDER21"].style.left=VALUE6;
}else if (ns5) {
    document.getElementById("LayerORDER21").style.left="VALUE6px";
}else {
    document.all.LayerORDER21.style.left=VALUE6;
}
<!-- END DYNAMIC BLOCK: option_show -->

//-->
</script>
