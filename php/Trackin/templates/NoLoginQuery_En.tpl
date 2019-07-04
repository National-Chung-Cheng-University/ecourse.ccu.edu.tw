<HTML> 
<head>
<title>Name Chart</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<STYLE type=text/css>
 body { scrollbar-face-color: #4d6eb2; scrollbar-arrow-color: #FFFFFF; scrollbar-track-color: #DDDDFF; scrollbar-shadow-color: #054878; scrollbar-3dlight-color: black; }
</STYLE> 
</head>
<body background=/images/img_E/bg.gif>
<IMG SRC=/images/img_E/b62.gif>
<CENTER>
<FORM NAME="table">
<CAPTION>No Login for DAYS day(s)</CAPTION>
<table border="1" bordercolor="#9FAE9D"><tr><td>
<TABLE BORDER=0>
<TR bgcolor="#4d6eb2"><TH><font color=#ffffff>Inform?</font></TH><TH><font color=#ffffff>Name Chart</font></TH><TH><font color=#ffffff>No.</font></TH><TH><font color=#ffffff>Name</font></TH><TH><font color=#ffffff>No. of Login</font></TH></TR>
<!-- BEGIN DYNAMIC BLOCK: row -->
<TR bgcolor="COLOR"><TD>NOTIFY</TD><TD>ORDER</TD><TD>STUDENT_ID</TD><TD>STUDENT_NAME</TD><TD>RECORD</TD></TR>
<!-- END DYNAMIC BLOCK: row -->
</TABLE>
</td></tr></table>
</FORM>
<SCRIPT> 
var emails = [MAIL_LIST]; 
function getEmailAddress() {  
  var dt = document.table;  
  var receiver = '';  
  for(var i = 0 ; i < dt.elements.length ; i++ ) {  
    if( dt.elements[i].checked ) { 
      if( receiver == '' )  
        receiver = emails[i];  
      else   
        receiver += ',' + emails[i];  
    } 
  } 
  return receiver;  
}

function Check() {
	if ( rank.quantity.value <= 0 ) {
			alert("Number of List Error!");
			return false;
	}
	return true;
}

function Check2() {
	if ( nologin.days.value <= 0 ) {
			alert("Not Correct Day for Querying!");
			return false;
	}
	return true;
}
</SCRIPT>
<A HREF=mailto:^_^ OnClick ="this.href = 'mailto:' + getEmailAddress() + '?subject=Notify Mail'">Send Notify Mail</A> 
<BR> 
<FORM ACTION=StudentRank1.php METHOD=POST name="rank"> 
Catagory：<SELECT NAME=rank_type> 
<OPTION SELECTED VALUE=LoginRank>Logins 
<OPTION VALUE=TimeRank>Usage 
<OPTION VALUE=PostRank>Posts 
<OPTION VALUE=TalkRank>Chats 
<OPTION VALUE=PageRank>Courseware browsing 
<OPTION VALUE=TextRank>Courseware browsing(Time)
</SELECT> 
<BR>
Limit：<INPUT TYPE=TEXT MAXLENGTH=4 SIZE=4 NAME=quantity VALUE=10><BR>
<INPUT TYPE=RADIO CHECKED NAME=order VALUE=TOP>From Top
<INPUT TYPE=RADIO NAME=order VALUE=BUTTOM>From Buttom<br>
<INPUT TYPE=RADIO CHECKED NAME=credit VALUE=1>Credit Stu.
<INPUT TYPE=RADIO NAME=credit VALUE=0>All Stu.
<BR>
<INPUT TYPE=SUBMIT VALUE=OK OnClick="return Check();"> 
</FORM>
<HR>
Stu. List of n Day Not Login<BR>
<FORM ACTION=NoLoginQuery.php METHOD=POST name=nologin>
Days：<INPUT TYPE=TEXT MAXLENGTH=4 SIZE=4 NAME=days VALUE="NOLOGINDAY"><BR>
<INPUT TYPE=SUBMIT VALUE=OK OnClick="return Check2();">
</FORM> 
<BR> 
</CENTER> 
</BODY> 
</HTML>
