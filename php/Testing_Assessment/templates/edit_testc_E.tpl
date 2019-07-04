¡@Single-select or Multi-select:<select size=1 name=cho>
¡@<option value=0 CHO0>Single-select</option>
¡@<option value=1 CHO1>Multi-select</option>
¡@</select><br><br>
¡@First answer¡G<input type=text name=selection1 size=30 value="SEL1"><br>
¡@Second answer¡G<input type=text name=selection2 size=30 value="SEL2"><br>
¡@Third answer¡G<input type=text name=selection3 size=30 value="SEL3"><br>
¡@Fourth answer¡G<input type=text name=selection4 size=30 value="SEL4"><br>
¡@Check correct answer:1.<input type=checkbox name=check_1 value=1 CHECK1>
	   2.<input type=checkbox name=check_2 value=2 CHECK2>
	   3.<input type=checkbox name=check_3 value=3 CHECK3>
	   4.<input type=checkbox name=check_4 value=4 CHECK4><br>
¡@Input related answer link(option):<input type=text name=ans_link size=30 value="ANS_LINK"><br>
BUTTON
¡@<input type=submit name=submit value="SUBMIT" OnClick="return Check();"><br>
¡@<input type=reset name=reset value=Reset><br>
</form>
<script language="JavaScript">
<!--
function Check() {
	count = 0;
	if ( testcont.check_1.checked )
		count ++;
	if ( testcont.check_2.checked )
		count ++;
	if ( testcont.check_3.checked )
		count ++;
	if ( testcont.check_4.checked )
		count ++;
	if ( testcont.cho.value == "0" ) {
		if ( count > 1 ) {
			alert("This is Single-select question!");
			return false;
		}
	}
	if ( count == 0 ) {
		alert("Please Choise Answers!");
		return false;
	}
	if ( testcont.qtext.value == "" ) {
		alert("Please Input Question!");
		return false;
	}
	if ( testcont.qgrade.value == "" ) {
		alert("Please Input Score!");
		return false;
	}
}
//--> 
</script>
</body>
</html>