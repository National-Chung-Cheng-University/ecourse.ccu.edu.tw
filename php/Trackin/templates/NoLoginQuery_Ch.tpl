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
<CAPTION>�W�LDAYS�ѥ��W�u�C��</CAPTION>
<table border="1" bordercolor="#9FAE9D"><tr><td>
<TABLE BORDER=0>
<TR bgcolor="#4d6eb2"><TH><font color=#ffffff>�q��?</font></TH><TH><font color=#ffffff>�ƦW</font></TH><TH><font color=#ffffff>�Ǹ�</font></TH><TH><font color=#ffffff>�m�W</font></TH><TH><font color=#ffffff>�W���W�u�ɶ�</font></TH></TR>
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
<A HREF=mailto:^_^ OnClick ="this.href = 'mailto:' + getEmailAddress() + '?subject=�q���H'">�e�X�q���H��</A> 
<BR> 
<FORM ACTION=StudentRank1.php METHOD=POST name="rank"> 
�����G<SELECT NAME=rank_type> 
<OPTION SELECTED VALUE=LoginRank>�n�J���ƱƦ� 
<OPTION VALUE=TimeRank>�ϥήɼƱƦ� 
<OPTION VALUE=PostRank>�o��峹���ƱƦ� 
<OPTION VALUE=TalkRank>��Ѧ��ƱƦ� 
<OPTION VALUE=PageRank>�ǥ��s���Ч����ƱƦ� 
<OPTION VALUE=TextRank>�ǥ��s���Ч��`�ɼƱƦ� 
</SELECT>
<BR> 
�̤j��ơG<INPUT TYPE=TEXT MAXLENGTH=4 SIZE=4 NAME=quantity VALUE=10><BR>
<INPUT TYPE=RADIO CHECKED NAME=order VALUE=TOP>From Top
<INPUT TYPE=RADIO NAME=order VALUE=BUTTOM>From Buttom<br>
<INPUT TYPE=RADIO CHECKED NAME=credit VALUE=1>�u��ܥ��ץ�
<INPUT TYPE=RADIO NAME=credit VALUE=0>�Ҧ��ǥ�
<BR>
<INPUT TYPE=SUBMIT VALUE=OK OnClick="return Check();"> 
</FORM>
<HR>
�d�߶W�L n�ѥ��n�J�ǥͦC��<BR>
<FORM ACTION=NoLoginQuery.php METHOD=POST name=nologin>
�ѼơG<INPUT TYPE=TEXT MAXLENGTH=4 SIZE=4 NAME=days VALUE="NOLOGINDAY"><BR>
<INPUT TYPE=SUBMIT VALUE=OK OnClick="return Check2();">
</FORM>
<BR> 
</CENTER> 
</BODY> 
</HTML>
