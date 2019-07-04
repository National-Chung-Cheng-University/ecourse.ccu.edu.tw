<HTML>
<head>
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=big5">
</head>
<BODY background="/images/skinSKINNUM/bbg.gif">
<center>
<font color="#FF0000">MESSAGE</font>
<p><form method="POST" action="ACT1">
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
  <td bgcolor="#E6FFFC" >章別：第
    <select  name=chap_num>
	SELECT_CHAP
    </select>
章<br>
(0:表示此作業不屬於特定章別) </td>
</tr>
<tr bgcolor=#000066 align=center> 
<td bgcolor="#F0FFEE" > 
<div align="center">
作業名稱：
<input type="text" name="work_name" size="10" value="WORKNAME">
</div>
</td>
</tr>
<tr bgcolor="#F0FFEE"> 
<td align=left bgcolor="#E6FFFC"> 
<div align="center">請輸入繳交期限： 
<input type="text" name="work_due" size="15" value="WORKDUE">
<br>
<font color=#000080>(xxxx-xx-xx;年-月-日)</font></div>
</td>
</tr>
<tr bgcolor="#E6FFFC"> 
<td align=left bgcolor="#F0FFEE"> 
<div align="center">請輸入配分： 
<input type="text" name="work_ratio" size="3" value="WORKRATIO">
%</div>
</td>
</tr>
CoreAbilities
<tr bgcolor="#E6FFFC"> 
<td align=left bgcolor="#F0FFEE"> 
<div align="center">
<input type="submit" name="submit1" value="確定">
<input type="reset" name="reset1" value="重新輸入">
</div>
</td>
</tr>
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
<p></p><br>
<input type=hidden name=work_id value="WORKID">
<input type=hidden name=action value=ACT2>
</form>
</center>
</BODY>
</HTML>
