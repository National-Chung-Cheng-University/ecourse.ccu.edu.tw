<HTML>
<head>
<META HTTP-EQUIV="Expires" CONTENT=0>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=big5">

<script language="JavaScript">
changed = new Array();
object = new Array();
var start;
var end;

function set_obj ( obj, order ) {
	object[order] = obj;
}

function rang_bg(ch, order) {
	var i;
	if ( ch == "start" )
		start = order;
	else {
		if ( order < start ) {
			end = start;
			start = order;
		}
		else {
			end = order;
		}
		for ( i = start ; i <= end ; i++ ) {
			chg_bg( '#EEEEEE', i );
		}
	}
}

function chg_bg( toColor, order ){
	if( changed[order] != 1){
		if (! object[order].contains(event.fromElement)) {
			object[order].bgColor = toColor;
			changed[order] = 1;
		}
	}else {
		if (! object[order].contains(event.toElement)) {
			if ( order % 2 == 0 ) {
				object[order].bgColor = '#F0FFEE';
			}
			else {
				object[order].bgColor = '#E6FFFC';
			}
			changed[order] = 0;
		}
	}
}
</script>
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css">
<title>Grade List</title></head>
<BODY background="/images/skinSKINNUM/bbg.gif">
<p align="center"><font color="#000000">Grade List</font> </p>
<center>
<a href="../../php/Courses_Admin/teach_course.php">Go Back</a>
<BR>
<BR>
<a href="/LOCATION"><font color="#000099">Table Download</font></a>
<table border="0" align="center" cellpadding="0" cellspacing="0" width="95%">
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
<table border=0 cellspacing=1 cellpadding=3 width="100%" >
<tr bgcolor="#000066" > 
<th> 
<div align="center"><font color="#FFFFFF" size="2">Order</font></div>
</th>
<th> 
<div align="center"><a href="./BGQueryFrame1.php?year=YR&term=TM&course_id=CID"><font color="#FFFFFF" size="2">Student ID</font></a></div>
</th>
<th> 
<div align="center"><a href="./BGQueryFrame1.php?sel=name&year=YR&term=TM&course_id=CID"><font color="#FFFFFF" size="2">Name</font></a></div>
</th>
<!-- BEGIN DYNAMIC BLOCK: row -->
<th><font color = "#FFFFFF" size="2">TESTNAME (RATIO%)</font></th>
<!-- END DYNAMIC BLOCK: row -->
<th><a href=BGQueryFrame1.php?sel=Total&year=YR&term=TM&course_id=CID><font color = "#FFFFFF" size="2">Total Score</font></a></th></tr>
<!-- BEGIN DYNAMIC BLOCK: grade -->
DATA
<!-- END DYNAMIC BLOCK: grade -->
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
</center>
</BODY>
</HTML>