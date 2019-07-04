<HTML> 
<head>
<title>Name Chart</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<STYLE type=text/css>
 body { scrollbar-face-color: #4d6eb2; scrollbar-arrow-color: #FFFFFF; scrollbar-track-color: #DDDDFF; scrollbar-shadow-color: #054878; scrollbar-3dlight-color: black; }
</STYLE> 
</head>
<body background=/images/img/bg.gif>
<IMG SRC=/images/img/b62.gif>
<CENTER>
<FORM NAME="table">
<CAPTION>超過DAYS天未上線列表</CAPTION>
<table border="1" bordercolor="#9FAE9D"><tr><td>
<TABLE BORDER=0>
<TR bgcolor="#4d6eb2"><TH><font color=#ffffff>通知?</font></TH><TH><font color=#ffffff>排名</font></TH><TH><font color=#ffffff>學號</font></TH><TH><font color=#ffffff>姓名</font></TH><TH><font color=#ffffff>上次上線時間</font></TH></TR>
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
			alert("比數輸入錯誤!");
			return false;
	}
	return true;
}

function Check2() {
	if ( nologin.days.value <= 0 ) {
			alert("天數輸入錯誤!");
			return false;
	}
	return true;
}
</SCRIPT>
<A HREF=mailto:^_^ OnClick ="this.href = 'mailto:' + getEmailAddress() + '?subject=通知信'">送出通知信件</A> 
<BR> 
<FORM ACTION=StudentRank1.php METHOD=POST name="rank"> 
類型：<SELECT NAME=rank_type> 
<OPTION SELECTED VALUE=LoginRank>登入次數排行 
<OPTION VALUE=TimeRank>使用時數排行 
<OPTION VALUE=PostRank>發表文章次數排行 
<OPTION VALUE=TalkRank>聊天次數排行 
<OPTION VALUE=PageRank>學生瀏覽教材次數排行 
<OPTION VALUE=TextRank>學生瀏覽教材總時數排行 
</SELECT>
<BR> 
最大比數：<INPUT TYPE=TEXT MAXLENGTH=4 SIZE=4 NAME=quantity VALUE=10><BR>
<INPUT TYPE=RADIO CHECKED NAME=order VALUE=TOP>From Top
<INPUT TYPE=RADIO NAME=order VALUE=BUTTOM>From Buttom<br>
<INPUT TYPE=RADIO CHECKED NAME=credit VALUE=1>只顯示正修生
<INPUT TYPE=RADIO NAME=credit VALUE=0>所有學生
<BR>
<INPUT TYPE=SUBMIT VALUE=OK OnClick="return Check();"> 
</FORM>
<HR>
查詢超過 n天未登入學生列表<BR>
<FORM ACTION=NoLoginQuery.php METHOD=POST name=nologin>
天數：<INPUT TYPE=TEXT MAXLENGTH=4 SIZE=4 NAME=days VALUE="NOLOGINDAY"><BR>
<INPUT TYPE=SUBMIT VALUE=OK OnClick="return Check2();">
</FORM>
<BR> 
</CENTER> 
</BODY> 
</HTML>
