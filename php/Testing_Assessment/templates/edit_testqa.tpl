<br>　輸入正確答案:<br>　<textarea name="answer_qa" rows="5" cols="60">ANSWER_QA</textarea><br>
　輸入答案的link位置(option):<input type=text name=ans_link size=30 value="ANS_LINK"><br>
BUTTON
　<input type=submit name=submit value="SUBMIT" OnClick="return Check();"><br>
　<input type=reset name=reset value=重新編輯><br>
</form>
<script language="JavaScript">
<!--
function Check() {
	if ( testcont.qtext.value == "" ) {
		alert("你沒有輸入題目!");
		return false;
	}
	if ( testcont.qgrade.value == "" ) {
		alert("你沒有輸入分數!");
		return false;
	}
	if ( testcont.answer_qa.value == "" ) {
		alert("你沒有輸入答題!");
		return false;
	}
}
//--> 
</script>
</body>
</html>