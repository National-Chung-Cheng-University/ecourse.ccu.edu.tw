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
			alert("��ƿ�J���~!");
			return false;
	}
	return true;
}

function Check2() {
	if ( nologin.days.value <= 0 ) {
			alert("�Ѽƿ�J���~!");
			return false;
	}
	return true;
}
</SCRIPT>
MAIL_LINK
<BR>
<FORM ACTION="./StudentRank1.php" METHOD=POST name="rank">
�����G<SELECT NAME=rank_type>
<OPTION SELECTED VALUE=LoginRank CH0>�n�J���ƱƦ�
<OPTION VALUE=TimeRank CH1>�ϥήɼƱƦ�
<OPTION VALUE=PostRank CH2>�o��峹���ƱƦ�
<!-- modify by w60292 @ 20091006 �N��Ѧ��Ƨאּ�ѻP�u�W�P�B�оǦ���-->
<!-- OPTION VALUE=TalkRank CH3>�ѻP��Ѧ��ƱƦ� -->
<OPTION VALUE=TalkRank CH3>�ѻP�u�W�P�B�оǦ��ƱƦ�
<OPTION VALUE=PageRank CH4>�ǥ��s���Ч����ƱƦ�
<OPTION VALUE=TextRank CH5>�ǥ��s���Ч��`�ɼƱƦ� 
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
