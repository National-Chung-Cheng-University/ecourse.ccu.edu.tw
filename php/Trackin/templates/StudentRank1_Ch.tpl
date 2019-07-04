<HTML>
<head>
<title>各項排名</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css"> 
<script language="JavaScript">

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
</script>
</head>
<body background=/images/img/bg.gif>
<CENTER>
<FORM ACTION="./StudentRank1.php" METHOD=POST name="rank">
類型：<SELECT NAME=rank_type>
<OPTION SELECTED VALUE=LoginRank>登入次數排行
<OPTION VALUE=TimeRank>使用時數排行
<OPTION VALUE=PostRank>發表文章次數排行
<!-- modify by w60292 @ 20091006 將"聊天次數"改為"參與線上同步教學次數" -->
<OPTION VALUE=TalkRank>參與線上同步教學次數排行
<OPTION VALUE=PageRank>學生瀏覽教材次數排行
<OPTION VALUE=TextRank>學生瀏覽教材總時數排行 
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
