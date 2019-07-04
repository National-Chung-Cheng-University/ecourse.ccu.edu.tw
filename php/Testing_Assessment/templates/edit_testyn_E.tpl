¡@Answer:<select size=1 name=cho>
¡@<option value=0 CHO0>No</option>
¡@<option value=1 CHO1>Yes</option>
¡@</select><br>
¡@Input related answer link(option):<input type=text name=ans_link size=30 value="ANS_LINK"><br>
BUTTON
¡@<input type=submit name=submit value="SUBMIT" OnClick="return Check();"><br>
¡@<input type=reset name=reset value=Reset><br>
</form>
<script language="JavaScript">
<!--
function Check() {
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