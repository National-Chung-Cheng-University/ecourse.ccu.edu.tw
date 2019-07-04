<html>
<head>
<title>Student Information Input</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css">
<script language="JavaScript">
var flag = 0
function MsgWin(){
msg=open('','','toolbar=no,directories=no,menubar=no,width=300,height=30')
msg.document.write('<BODY bgcolor="#EFFBF9"><center><h4>Update data, please wait.....</h4></center></BODY>'); 
flag = 1
}
function MsgWinC(){
if(flag == 1) {
msg.close()
flag = 0 }
}

function Check() {
	if ( ssmodify.year.value == "" || ssmodify.month.value == "" || ssmodify.day.value == "" ) {
		alert("Incorrect Birthday!");
		return false;
	}
	if ( ssmodify.tel.value == "" ) {
		alert("Please input your Tel!");
		return false;
	}
	if ( ssmodify.email.value == "" || ssmodify.email.value.indexOf("@") == "-1" || ssmodify.email.value.indexOf(".") == "-1" ) {
			alert("Incorrect Email Address!");
			return false;
	}
	return true;
}
</script>
</HEAD><BODY onunload=MsgWinC() background="/images/img_E/bg.gif">
<form name=ssmodify method="POST" action="./SSModifyFrame1.php">
<table border="0" width="80%" height="375">
<tr>
<td colspan=3 height="18" align="center" nowrap bordercolor="#2FA89C"><font color="#ff0000">MES</font></td>
</tr>
<tr>
<td width="33%" align="left" nowrap height="18" bordercolor="#2FA89C">Data of Homepage</td>
<td width="17%" align="left" nowrap height="18" bordercolor="#2FA89C">Persion Information</td>
<td width="17%" align="right" nowrap bordercolor="#2FA89C" rowspan="10" valign="bottom">
<input type="submit" value="Submit" name="btn" OnClick="return Check();">　<input type="reset" value="Reset" name="reset">　<input type="submit" value="PREVIEW" name="btn" OnClick="return Check();"> 
</td>
</tr>
<tr>
<td width="33%" align="left" nowrap height="18" bordercolor="#2FA89C">Blood Type:<select size="1" name="blood" style="font-family: Comic Sans MS">
<option value="1" BL1>O</option>
<option value="2" BL2>A</option>
<option value="3" BL3>B</option>
<option value="4" BL4>AB</option>
</select></td>
<td width="17%" align="left" nowrap height="18" bordercolor="#2FA89C">Name: NAME<input type="hidden" name="name" value="NAME"></td>
</tr>
<tr>
<td width="33%" align="left" nowrap height="18" bordercolor="#2FA89C">Star:<select size="1" name="star" style="font-family: Courier">
<option value="01" ST01>牡羊座</option>
<option value="02" ST02>金牛座</option>
<option value="03" ST03>雙子座</option>
<option value="04" ST04>巨蟹座</option>
<option value="05" ST05>獅子座</option>
<option value="06" ST06>處女座</option>
<option value="07" ST07>天秤座</option>
<option value="08" ST08>天蠍座</option>
<option value="09" ST09>射手座</option>
<option value="10" ST10>魔羯座</option>
<option value="11" ST11>水瓶座</option>
<option value="12" ST12>雙魚座</option>
</select></td>
<td width="17%" align="left" nowrap height="18" bordercolor="#2FA89C">Id:ID Nickname:<input type="text" name="nickname" size="20" maxlength="16" value="NICK"></td>
</tr>
<tr>
<td width="33%" align="left" nowrap height="25" bordercolor="#2FA89C">Interest:<input type="text" name="interest" size="20" value="INTEREST"></td>
<td width="17%" align="left" nowrap height="18" bordercolor="#2FA89C">Sex:<select size="1" name="sex">
<option value="1" SEX1>Male</option>
<option value="0" SEX0>Female</option>
</select></td>
</tr>
<tr>
<td width="33%" align="left" nowrap height="25" bordercolor="#2FA89C">Skill:<input type="text" name="skill" size="20" value="SKILL"></td>
<td width="17%" align="left" nowrap height="18" bordercolor="#2FA89C"><font color=ff0000>*</font>Birthday:DC<input type="text" name="year" size="3" value="YEAR">Year<select size="1" name="month">
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
</select>Day</td>
</tr>
<tr>
<td width="33%" align="left" nowrap rowspan="5" height="144" valign="top" bordercolor="#2FA89C">Introduction<br>
<textarea rows="6" name="intro" cols="23">INTRO</textarea>
<br><br><center><input type="button" value="作心理測驗" onClick="location.href='make_color1.php'"></center>
</td>
<td width="17%" align="left" nowrap height="18" bordercolor="#2FA89C">Department: JOB<input type="hidden" name="job" value="JOB"></td>
</tr>
<tr>
<td width="17%" align="left" nowrap height="18" bordercolor="#2FA89C"><font color=ff0000>*</font>Tel:<input type="text" name="tel" size="20" maxlength="16" value="TEL"></td>
</tr>
<tr>
<td width="17%" align="left" nowrap height="18" bordercolor="#2FA89C">Address:<input type="text" name="addr" size="25" maxlength="40" value="ADDR"></td>
</tr>
<tr>
<td width="17%" align="left" nowrap height="18" bordercolor="#2FA89C"><font color=ff0000>*</font>E-mail:<input type="text" name="email" size="16" maxlength="40" value="EMAIL"></td>
</tr>
<tr>
<td width="17%" align="left" nowrap rowspan="2" height="36" bordercolor="#2FA89C">Homepage<br>
<input type="radio" value="1" name="pageKind" P1>supply by System<br>
<input type="radio" name="pageKind" value="2" P2>Custom<input type="text" name="uurl" size="20" value="URL" onFocus="ssmodify.pageKind[1].checked=true"></td>
</tr>
</table>
</form>
<font color=ff0000>* Require</font><Br>
<hr>
(圖檔的名稱為自己的學號,圖檔的型態為gif,寬:104 pixel,高:128 pixel)<br>
(若已經上傳過照片,想要上傳新的照片前請先按"刪除檔案"刪掉舊的照片,才能成功上傳)<br>
<FORM ENCTYPE="multipart/form-data" method=POST ACTION="./SSModifyFrame1.php">

<input type=hidden name=pic value="ID.gif">
<input type=hidden name=version value="VERSION">
<font color=#006600>
My Picture：<br>
<INPUT TYPE="FILE" name="pic_file" SIZE="20"></font>　
<INPUT TYPE="SUBMIT" VALUE="UPLOAD" name="btn" onclick="MsgWin()">　<INPUT TYPE="RESET" VALUE="Reset">　<INPUT TYPE="SUBMIT" VALUE="Delete" name="btn" onclick="MsgWin()">
</Form>
</body>
</html>
