<html>
<head>
<title>Announcement</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css">
</head>
<body background=/images/skinSKINNUM/bbg.gif onLoad="parent.options.cwin();">
<a name="newst"></a>
<script language="JavaScript">
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}

function Vlimit()
{
	parent.options.msgwin('Querying data, please wait.....');
	document.vlimit.submit()
}
function Vdate()
{
	parent.options.msgwin('Querying data, please wait.....');
	document.vdate.submit()
}
function check_post() {
	var error = true;
	var msg = "Please Full On";
	if(post_art.subject.value == "") {
		error = false;
		msg = msg + " Subject";
	}

	if(post_art.news.value == "") {
		error = false;
		msg = msg + " Content";
	}
	
	if(!error)
		alert(msg);
	return error;
}
</script>
<BR>
<center>
DISPLAY
<p>Announcement<p>
<H2>MES</H2>
<table border=0>
<form name=vdate method=POST action=./news.php >
<tr><td>
Date：<select name="year" onChange="Vdate();">
<!-- BEGIN DYNAMIC BLOCK: year_list -->
<option value=YVD>YID</option>
<!-- END DYNAMIC BLOCK: year_list -->
</select>Year
<select name="month" onChange="Vdate();">
<option value="01" LM01>1</option>
<option value="02" LM02>2</option>
<option value="03" LM03>3</option>
<option value="04" LM04>4</option>
<option value="05" LM05>5</option>
<option value="06" LM06>6</option>
<option value="07" LM07>7</option>
<option value="08" LM08>8</option>
<option value="09" LM09>9</option>
<option value="10" LM10>10</option>
<option value="11" LM11>11</option>
<option value="12" LM12>12</option>
</select>Month
</td></tr>
HVLIM
</form><form name=vlimit method=POST action=./news.php>
<tr><td align=center>
<select name="views" onChange="Vlimit();">
<option value="0" V0>All News</option>
<option value="1" V1>Close Month</option>
<option value="2" V2>Arrich Now</option>
OPTION1
OPTION2
</select>
</td></tr>
</form>
</table>
POST_LINE
<table border="0" align="center" cellpadding="0" cellspacing="0" width="80%">
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
<table cellpadding=3 align=center border=0 bordercolorlight="#666666" bordercolordark="#FFFFFF" width="100%" cellspacing="1">
<tr bgcolor=#000066 align=center>
DELETE
<TD> 
<div align="center"><a href="./news.php?views=2&order=1&month=MONT&year=YEARV"><FONT color=#ffffff size=2>Date</FONT></a></div></TD>
<td>
<div align="center"><a href="./news.php?views=2&order=3&month=MONT&year=YEARV"><FONT color=#ffffff size=2>Priority</font></a></div></td>
<TD> 
<div align="center"><FONT color=#ffffff size=2>Subject&Detail Link</FONT></div></TD>
<TD COLSPAN=2> 
<div align="center"><a href="./news.php?views=2&order=7&month=MONT&year=YEARV"><FONT color=#ffffff size=2>Read Times</FONT></a></div></TD>
</TR>