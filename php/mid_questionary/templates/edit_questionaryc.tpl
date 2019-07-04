　單選或複選:<select size=1 name=cho>
　<option value=0 CHO0>單選</option>
　<option value=1 CHO1>複選</option>
　</select><br><br>
<!-- BEGIN DYNAMIC BLOCK: row -->
　選項 ORDER：<input type=text name=selectionNUM size=30 value="VALUE"><br>
<!-- END DYNAMIC BLOCK: row -->
　<input type=submit name=submit value="SUBMIT" OnClick="return Check();"><br>
　<input type=reset name=reset value=重新編輯><br>
  <input name="submit" type=submit value=" 修 改 問 卷 " OnClick="eturn Check();">
  </form>

<script language="JavaScript">
<!--
function Check() {
	if ( testcont.qtext.value == "" ) {
		alert("請輸入問題!");
		return false;
	}
}
//-->
</script>
</body>
</html>