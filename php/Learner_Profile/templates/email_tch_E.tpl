<html>
<head>
<title>教師資料</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<script language="JavaScript">
var flag = 0;
function MsgWin(){
msg=open('','','toolbar=no,directories=no,menubar=no,width=300,height=30');
msg.document.write('<BODY><center><h4>Data Querying, Please Waiting...</h4></center></BODY>'); 
flag = 1;
}
function MsgWinC(){
if(flag == 1) {
msg.close();
flag = 0; }
}

function Check() {
	if ( emailtch.name.value == "" ) {
			alert("No Name!");
			return false;
	}
	if ( emailtch.email.value == "" || emailtch.email.value.indexOf("@") == "-1" || emailtch.email.value.indexOf(".") == "-1" ) {
			alert("Email Not Correct!");
			return false;
	}
	return true;
}
</script>
<link rel="stylesheet" href="/images/skinSKINNUM/css/a.css" type="text/css">
<link rel="stylesheet" href="/images/skinSKINNUM/css/ahover.css" type="text/css">
<link rel="stylesheet" href="/images/skinSKINNUM/css/aiv.css" type="text/css">
<link rel="stylesheet" href="/images/skinSKINNUM/css/body.css" type="text/css">
</HEAD>
<BODY onunload=MsgWinC(); background="/images/skinSKINNUM/bbg.gif">
<center>
<font color="#ff0000">MES</font>
<form name=emailtch method="POST" action="./email_tch.php">
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
<td> 
<div align="right"><img src="/images/skinSKINNUM/bor/bor_04.GIF" width="12" height="379"></div>
</td>
<td bgcolor="#CCCCCC"> 
<table border="0" cellpadding="3" cellspacing="1" width="100%">
<tr> 
<td bgcolor="#000066" colspan="2">
<table border="0" cellspacing="1" cellpadding="3" align="center">
<tr> 
<td><font color="#FFFFFF" size="2">Persional Info</font> </td>
<td bgcolor="#CC0000"><font color="#FFFFFF">* mean that you must fill out it</font></td>
</tr>
</table>
</td>
</tr>
<tr bgcolor="#F0FFEE">
<td><font color="ff0000" size="2">*</font><font size="2">Name:<input type="text" name="name" size="20" maxlength="16" value="NAME" READ></font></td>
<td><font size="2">Id:ID Nickname:<input type="text" name="nickname" size="20" maxlength="16" value="NICK"></font></td>
</tr>
<tr bgcolor="#E6FFFC">
<td><font size="2">Interest:<input type="text" name="interest" size="20" value="INTEREST"></font></td>
<td><font size="2">Sex:<select size="1" name="sex">
<option value="1" SEX1>Man</option>
<option value="0" SEX0>Woman</option>
</select></font></td>
</tr>
<tr bgcolor="#F0FFEE">
<td><font size="2">Skill:<input type="text" name="skill" size="20" value="SKILL"></font></td>
<td><font size="2">Birthday:AD<input type="text" name="year" size="3" value="YEAR">Year<select size="1" name="month">
<option value="01" M01>1</option>
<option value="02" M02>2</option>
<option value="03" M03>3</option>
<option value="04" M04>4</option>
<option value="05" M05>5</option>
<option value="06" M06>6</option>
<option value="07" M07>7</option>
<option value="08" M08>8</option>
<option value="09" M09>9</option>
<option value="10" M10>10</option>
<option value="11" M11>11</option>
<option value="12" M12>12</option>
</select>Month<select size="1" name="day">
<option value="01" D01>1</option>
<option value="02" D02>2</option>
<option value="03" D03>3</option>
<option value="04" D04>4</option>
<option value="05" D05>5</option>
<option value="06" D06>6</option>
<option value="07" D07>7</option>
<option value="08" D08>8</option>
<option value="09" D09>9</option>
<option value="10" D10>10</option>
<option value="11" D11>11</option>
<option value="12" D12>12</option>
<option value="13" D13>13</option>
<option value="14" D14>14</option>
<option value="15" D15>15</option>
<option value="16" D16>16</option>
<option value="17" D17>17</option>
<option value="18" D18>18</option>
<option value="19" D19>19</option>
<option value="20" D20>20</option>
<option value="21" D21>21</option>
<option value="22" D22>22</option>
<option value="23" D23>23</option>
<option value="24" D24>24</option>
<option value="25" D25>25</option>
<option value="26" D26>26</option>
<option value="27" D27>27</option>
<option value="28" D28>28</option>
<option value="29" D29>29</option>
<option value="30" D30>30</option>
<option value="31" D31>31</option>
</select>Day</font></td>
</tr>
<tr bgcolor="#E6FFFC">
<td rowspan="7"><font size="2">Introduction<br>
<textarea rows="6" name="intro" cols="23">INTRO</textarea><br>Experience<br>
<textarea rows="6" name="exper" cols="23">EXPER</textarea>
</font></td>
<td><font size="2">Job:<select size="1" name="job">
<option value="01" JOB01>電子業</option>
<option value="02" JOB02>資訊業</option>
<option value="03" JOB03>服務業</option>
<option value="04" JOB04>自由業</option>
<option value="05" JOB05>傳播業</option>
<option value="06" JOB06>金融業</option>
<option value="07" JOB07>營建業</option>
<option value="08" JOB08>醫藥業</option>
<option value="09" JOB09>學術單位</option>
<option value="10" JOB10>政府單位</option>
<option value="11" JOB11>學生</option>
<option value="12" JOB12>其它</option>
</select></font></td>
</tr>
<tr bgcolor="#F0FFEE">
<td><font size="2">Tel:<input type="text" name="tel" size="20" maxlength="16" value="TEL"></font></td>
</tr>
<tr bgcolor="#E6FFFC">
<td><font size="2">Address:<input type="text" name="addr" size="25" maxlength="40" value="ADDR"></font></td>
</tr>
<tr bgcolor="#F0FFEE">
<td><font size="2"><font color=ff0000>*</font>E-mail:<input type="text" name="email" size="16" maxlength="40" value="EMAIL"></font></td>
</tr>
<tr bgcolor="#E6FFFC">
<td><font size="2">Home Page<br>
<input type="radio" value="1" name="pageKind" P1>System Default<br>
<input type="radio" name="pageKind" value="2" P2>User Define<input type="text" name="uurl" size="20" value="URL" onFocus="emailtch.pageKind[1].checked=true"></font></td>
</tr>
<tr bgcolor="#F0FFEE"><td>
<input type=hidden name=pic value="ID.gif">
<input type=hidden name=version value="VERSION">
<font color=#006600>
Picture：<br>
<INPUT TYPE="FILE" name="pic_file" SIZE="20"></font>　
<INPUT TYPE="SUBMIT" VALUE="Del Pic" name="btn" onclick="MsgWin()">
</td></tr>
<tr bgcolor="#E6FFFC">
<td><div align="center"><font size="2"> 
<input type="button" value="Edit Office Time" name="edit_office" OnClick="location='./office_time_teacher.php?SESSION'">    
<input type="button" value="System Config" name="function_list" OnClick="location='../function_list.php?SESSION'">
<input type="button" value="Change Password" name="change_pass" OnClick="location='./chang_pass.php?SESSION'">
<input type="submit" value="Send" name="btn" OnClick="return Check();">
<input type="reset" value="Reset" name="reset">
 </font></div></td>
</tr>
</table>
</td>
<td> 
<div align="left"><img src="/images/skinSKINNUM/bor/bor_06.GIF" width="17" height="379"></div>
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
</form>
<!--
<p><a href="../function_list.php">System Config</a>
</p>
<p><a href="./chang_pass.php">Change Password</a>
</p>
-->
</center>
</body>
</html>