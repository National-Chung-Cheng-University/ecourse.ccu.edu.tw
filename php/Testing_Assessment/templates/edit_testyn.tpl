　答案:<select size=1 name=cho>
　<option value=0 CHO0>非</option>
　<option value=1 CHO1>是</option>
　</select><br>
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
}
//-->
</script>
</body>
</html>