¡@Single-select or Multi-select:<select size=1 name=cho>
¡@<option value=0 CHO0>Single-select</option>
¡@<option value=1 CHO1>Multi-select</option>
¡@</select><br><br>
<!-- BEGIN DYNAMIC BLOCK: row -->
¡@ ORDER.¡G<input type=text name=selectionNUM size=30 value="VALUE"><br>
<!-- END DYNAMIC BLOCK: row -->
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
}
//-->
</script>
</body>
</html>