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
<!--<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css">-->
<title>學生預警系統</title></head>
<BODY background="/images/skinSKINNUM/bbg.gif">

<center>

<p align="center"><font color="#0033FF" size="+3">Student Caution System</font></p>
<BR>
<form name="total_form" action="TGCaution.php" method="post">
<p align="center" >學期成績列表</p>
<table width="536" border="0">
  <tr>
    <td width="150">第YEAR學年　第TERM學期 </td>
    <td width="199">GNAME</td>
    <td width="173">列表日期：DATE</td>
    </tr>
  <tr>
    <td>科目編碼：CID</td>
    <td>CNAME</td>
    <td>班別：GID　學分數：CREDIT<div align="left"></div></td>
    </tr>
  <tr>
    <td colspan="3">教師：TEACHER</td>
    </tr>
	<input type="hidden" name="year" value="YEAR">
	<input type="hidden" name="term" value="TERM">
	<input type="hidden" name="gname" value="GNAME">
	<input type="hidden" name="date" value="DATE">
	<input type="hidden" name="cid" value="CID">
	<input type="hidden" name="cname" value="CNAME">	
	<input type="hidden" name="gid" value="GID">
	<input type="hidden" name="credit" value="CREDIT">
	<input type="hidden" name="teacher" value="TEACHER">			
</table>

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
	<!-- BEGIN DYNAMIC BLOCK: row -->
	DATA
	<!-- END DYNAMIC BLOCK: row -->
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
<input type="hidden" name="update" value="yes"> 
<input type="hidden" name="c_year" value="YEAR">
<input type="hidden" name="c_term" value="TERM">
<input type="hidden" name="c_id" value="C_ID">
<input type="hidden" name="loop_count" value="LOOP">
<input type="submit" value="Submit Caution List">
<a href="PRINT_" target="_blank">Print Caution List</a>
<a href="/LOCATION"><font color="#000099">Download Caution List</font></a>
</form>
</center>
</BODY>
</HTML>
