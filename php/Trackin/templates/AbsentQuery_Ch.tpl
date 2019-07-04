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
<CAPTION>超過TIMES次缺席者列表</CAPTION>
<table border="1" bordercolor="#9FAE9D"><tr><td>
<TABLE BORDER=0>
<TR bgcolor="#4d6eb2"><TH><font color=#ffffff>學號</font></TH><TH><font color=#ffffff>姓名</font></TH><TH><font color=#ffffff>缺席次數</font></TH></TR>
<!-- BEGIN DYNAMIC BLOCK: row -->
<TR bgcolor="#D0DFE3"><TD align="center">STUDENT_ID</TD><TD align="center">STUDENT_NAME</TD><TD align="center">RECORD</TD></TR>
<!-- END DYNAMIC BLOCK: row -->
</TABLE>
</td></tr></table>
</FORM>
<SCRIPT> 

function Check2() {
	if ( absent.times.value <= 0 ) {
			alert("次數輸入錯誤!");
			return false;
	}
	return true;
}
</SCRIPT>
MAIL 

<HR>
查詢超過 n次缺席學生列表<BR>
<FORM ACTION=AbsentQuery.php METHOD=POST name=absent>
次數：<INPUT TYPE=TEXT MAXLENGTH=4 SIZE=4 NAME=times VALUE="ABSENT"><BR>
<INPUT TYPE=SUBMIT VALUE=OK OnClick="return Check2();">
</FORM>
<BR> 
</CENTER> 
</BODY> 
</HTML>
