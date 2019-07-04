<HTML>
<head>
<title>Name Chart</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css">
</head>
<body background=/images/img/bg.gif>
<CENTER>
<FORM NAME="table">
<CAPTION>RANKING_TYPE</CAPTION>
<table border="0" align="center" cellpadding="0" cellspacing="0" >
<tr> 
<td> 
<div align="right"><img src="/images/skinSKINNUM/bor/bor_01.GIF" width="12" height="11"></div>
</td>
<td> 
<div align="center"><img src="/images/skinSKINNUM/bor/bor_02.GIF" width="100%" height="11"></div>
</td>
<td> 
<div align="left"><img src="/images/skinSKINNUM/bor/bor_03.GIF" width="17" height="11"></div>
</td>
</tr>
<tr> 
<td height=10> 
<div align="right"><img src="/images/skinSKINNUM/bor/bor_04.GIF" width="12" height="100%"></div>
</td>
<td bgcolor="#CCCCCC"> 
<table border=0 align="center" width="100%" cellpadding="3" cellspacing="1">
<!-- BEGIN DYNAMIC BLOCK: row -->
<TR bgcolor="COLOR">NOTIFY<TD>ORDER</TD><TD>STUDENT_ID</TD><TD>STUDENT_NAME</TD><TD>RECORD</TD></TR>
<!-- END DYNAMIC BLOCK: row -->
MESSAGE
</table>
</td>
<td height=10> 
<div align="left"><img src="/images/skinSKINNUM/bor/bor_06.GIF" width="17" height="100%"></div>
</td>
</tr>
<tr> 
<td> 
<div align="right"><img src="/images/skinSKINNUM/bor/bor_07.GIF" width="12" height="17"></div>
</td>
<td> 
<div align="center"><img src="/images/skinSKINNUM/bor/bor_08.GIF" width="100%" height="17"></div>
</td>
<td> 
<div align="left"><img src="/images/skinSKINNUM/bor/bor_09.GIF" width="17" height="17"></div>
</td>
</tr>
</table>
</FORM>
<SCRIPT>
var emails = [MAIL_LIST];
function getEmailAddress() { 
  var dt = document.table; 
  var receiver = ''; 
  for(var i = 0 ; i < dt.elements.length ; i++ ) { 
    if( dt.elements[i].checked ) {
      if( receiver == '' ) 
        receiver = emails[i-1]; 
      else  
        receiver += ',' + emails[i-1]; 
    }
  }
  return receiver; 
}
function Check() {
	if ( rank.quantity != null && rank.quantity.value <= 0 ) {
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
MAIL_LINK
<BR>
<FORM ACTION="./StudentRank1.php" METHOD=POST name="rank">
類型：<SELECT NAME=rank_type>
<OPTION SELECTED VALUE=LoginRank CH0>登入次數排行
<OPTION VALUE=TimeRank CH1>使用時數排行
<OPTION VALUE=PostRank CH2>發表文章次數排行
<!-- modify by w60292 @ 20091006 將聊天次數改為參與線上同步教學次數-->
<!-- OPTION VALUE=TalkRank CH3>參與聊天次數排行 -->
<OPTION VALUE=TalkRank CH3>參與線上同步教學次數排行
<OPTION VALUE=PageRank CH4>學生瀏覽教材次數排行
<OPTION VALUE=TextRank CH5>學生瀏覽教材總時數排行 
</SELECT>
<BR>
EXTENSION
<BR>
<INPUT TYPE=SUBMIT VALUE=OK OnClick="return Check();">
</FORM>
NOLOGINQUERY
<BR>
</CENTER>
</BODY>
</HTML>
