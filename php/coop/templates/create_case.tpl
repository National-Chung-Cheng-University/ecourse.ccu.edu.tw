<HTML>
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css">

<title>TITLE</title>
<p align="center"> <font size="2"><font color="#000000">TITLE</font></font></p>
<center>
<font color="#FF0000">MESSAGE</font>
<p><form method="POST" action="ACT1">
<table border="0" align="center" cellpadding="0" cellspacing="0" width="50%">
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
<td bgcolor="#E6FFFC" > 
<div align="center">
請輸入專案名稱：
<input type="text" name="case_name" size="10" value="CASE_NAME">
</div>
</td>
</tr>
<tr bgcolor="#F0FFEE"> 
<td align=left> 
<div align="center">請選擇專案類型: 
<select size=1 name=case_type>
<option value=self_case SELF_CASE>不計分</option>
<option value=real_case REAL_CASE>計分</option>
</select>
</div>
</td>
</tr>
<tr bgcolor="#E6FFFC"> 
<td align=left bgcolor="#E6FFFC">
<div align="center">請選擇環境類型: 
<select size=1 name=case_private>
<option value=pri_case PRI_CASE>不公開(僅小組)</option>
<option value=pub_case PUB_CASE>公開</option>
</select>
</div>
</td>
</tr>
<tr bgcolor="#F0FFEE"> 
<td align=left> 
<div align="center">請輸入配分：
<input type="text" name="case_ratio" size="3" value="CASE_RATIO">
%</div>
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
<input type=hidden name=case_id value="CASEID">
<input type=hidden name=action value=ACT2>
<input type="submit" name="submit1" value="BUTTON">
<input type="reset" name="reset1" value="重新輸入">
</form>
</center>
</BODY>

</HTML>
