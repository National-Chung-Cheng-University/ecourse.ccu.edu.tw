<br>�@��J���T����:<br>�@<textarea name="answer_qa" rows="5" cols="60">ANSWER_QA</textarea><br>
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
	if ( testcont.answer_qa.value == "" ) {
		alert("�A�S����J���D!");
		return false;
	}
}
//--> 
</script>
</body>
</html>