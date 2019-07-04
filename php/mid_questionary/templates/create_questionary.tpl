<HTML>

<BODY background="/images/img/bg.gif">
<p>
<img src="/images/img/IMG">
</p>
<center>
<font color="#FF0000">MESSAGE</font>
<p><form method=POST action=ACT1>
學年度：<input type=text name=year size=2 value="YEAR"><br><br>
學期： <input name="term" type="radio" value="1" TERM1> 1  
 	   <input name="term" type="radio" value="2" TERM2> 2
<br><br>
問卷名稱：<input type=text name=questionary_name size=20 value="QUES_NAME"><br>
(EX:期中教學意見調查表)<br><br>
<!--請選擇是否記名：<select size=1 name=is_named>
		<option value=1 REM_NAME>記名</option>
		<option value=0 NRM_NAME>不記名</option>
		</select><br><br>
每人可填寫的份數 : <select size=1 name=is_once>
		<option value=1 R01>1份</option>
		<option value=2 R02>2份</option>
		<option value=3 R03>3份</option>
		<option value=4 R04>4份</option>
		<option value=5 R05>5份</option>
		<option value=6 R06>6份</option>
		<option value=7 R07>7份</option>
		<option value=8 R08>8份</option>
		<option value=9 R09>9份</option>
		<option value=10 R10>10份</option>
		<option value=11 R11>11份</option>
		<option value=12 R12>12份</option>
		<option value=13 R13>13份</option>
		<option value=14 R14>14份</option>
		<option value=15 R15>15份</option>
		</select><br><br>
 -->
<input type=submit name=submit1 value=BUTTON>
<input type=reset name=reset1 value=重新輸入></p><br>
<input type=hidden name=q_id value="QUESID">
<input type=hidden name=action value=ACT2>
</form>
</center>
</BODY>

</HTML>
