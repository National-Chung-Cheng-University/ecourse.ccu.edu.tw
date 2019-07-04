　空格順序:<select size=1 name=cho>
　<option value=0 CHO0>不依序</option>
　<option value=1 CHO1>依序</option>
　</select><br><br>
<!-- BEGIN DYNAMIC BLOCK: row -->
　第ORDER格：<input type=text name=selectionNUM size=30 value="VALUE"><br>
<!-- END DYNAMIC BLOCK: row -->
　輸入答案的link位置(option):<input type=text name=ans_link size=30 value="ANS_LINK"><br>
BUTTON
　<input type=submit name=submit value="SUBMIT" OnClick="return Check();"><br>
　<input type=reset name=reset value=重新編輯><br>
</form>
<script language="JavaScript">
<!--
function Check() {
	if ( testcont.qtext.value == "" ) {
		alert("請輸入問題!");
		return false;
	}
	if ( testcont.qgrade.value == "" ) {
		alert("請輸入配分!");
		return false;
	}
}
//-->
</script>
</body>
</html>