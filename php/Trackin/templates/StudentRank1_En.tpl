<HTML>
<head>
<title>|U?μ±?W</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css">
<script language="JavaScript">

function Check() {
	if ( rank.quantity != null && rank.quantity.value <= 0 ) {
			alert("Not Correct Number for List!");
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
</script>
</head>
<body background=/images/img_E/bg.gif>
<CENTER>
<FORM ACTION="./StudentRank1.php" METHOD=POST name="rank">
Category<SELECT NAME=rank_type>
<OPTION SELECTED VALUE=LoginRank>Logins
<OPTION VALUE=TimeRank>Usage
<OPTION VALUE=PostRank>Posts
<OPTION VALUE=TalkRank>Chats
<OPTION VALUE=PageRank>Courseware browsing
<OPTION VALUE=TextRank>Courseware browsing(Time)
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
