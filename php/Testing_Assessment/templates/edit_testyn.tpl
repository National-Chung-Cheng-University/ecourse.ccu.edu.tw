�@����:<select size=1 name=cho>
�@<option value=0 CHO0>�D</option>
�@<option value=1 CHO1>�O</option>
�@</select><br>
�@��J���ת�link��m(option):<input type=text name=ans_link size=30 value="ANS_LINK"><br>
BUTTON
�@<input type=submit name=submit value="SUBMIT" OnClick="return Check();"><br>
�@<input type=reset name=reset value=���s�s��><br>
</form>
<script language="JavaScript">
<!--
function Check() {
	if ( testcont.qtext.value == "" ) {
		alert("�A�S����J�D��!");
		return false;
	}
	if ( testcont.qgrade.value == "" ) {
		alert("�A�S����J����!");
		return false;
	}
}
//-->
</script>
</body>
</html>