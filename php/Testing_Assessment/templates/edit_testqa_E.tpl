<br>¡@Input the correct answer:<br>¡@<textarea name="answer_qa" rows="5" cols="60">ANSWER_QA</textarea><br>
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
	if ( testcont.answer_qa.value == "" ) {
		alert("Please Input Correct Answer!");
		return false;
	}
}
//--> 
</script>
</body>
</html>