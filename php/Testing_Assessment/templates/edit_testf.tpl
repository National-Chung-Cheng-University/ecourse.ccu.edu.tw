�@�Ů涶��:<select size=1 name=cho>
�@<option value=0 CHO0>���̧�</option>
�@<option value=1 CHO1>�̧�</option>
�@</select><br><br>
<!-- BEGIN DYNAMIC BLOCK: row -->
�@��ORDER��G<input type=text name=selectionNUM size=30 value="VALUE"><br>
<!-- END DYNAMIC BLOCK: row -->
�@��J���ת�link��m(option):<input type=text name=ans_link size=30 value="ANS_LINK"><br>
BUTTON
�@<input type=submit name=submit value="SUBMIT" OnClick="return Check();"><br>
�@<input type=reset name=reset value=���s�s��><br>
</form>
<script language="JavaScript">
<!--
function Check() {
	if ( testcont.qtext.value == "" ) {
		alert("�п�J���D!");
		return false;
	}
	if ( testcont.qgrade.value == "" ) {
		alert("�п�J�t��!");
		return false;
	}
}
//-->
</script>
</body>
</html>