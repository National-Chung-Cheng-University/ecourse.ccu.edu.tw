<HTML>
<head>
<title>�U���ƦW</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css"> 
<script language="JavaScript">

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
</script>
</head>
<body background=/images/img/bg.gif>
<CENTER>
<FORM ACTION="./StudentRank1.php" METHOD=POST name="rank">
�����G<SELECT NAME=rank_type>
<OPTION SELECTED VALUE=LoginRank>�n�J���ƱƦ�
<OPTION VALUE=TimeRank>�ϥήɼƱƦ�
<OPTION VALUE=PostRank>�o��峹���ƱƦ�
<!-- modify by w60292 @ 20091006 �N"��Ѧ���"�אּ"�ѻP�u�W�P�B�оǦ���" -->
<OPTION VALUE=TalkRank>�ѻP�u�W�P�B�оǦ��ƱƦ�
<OPTION VALUE=PageRank>�ǥ��s���Ч����ƱƦ�
<OPTION VALUE=TextRank>�ǥ��s���Ч��`�ɼƱƦ� 
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
