�@���νƿ�:<select size=1 name=cho>
�@<option value=0 CHO0>���</option>
�@<option value=1 CHO1>�ƿ�</option>
�@</select><br><br>
�@�п�J�Ĥ@�ﶵ�G<input type=text name=selection1 size=30 value="SEL1"><br>
�@�п�J�ĤG�ﶵ�G<input type=text name=selection2 size=30 value="SEL2"><br>
�@�п�J�ĤT�ﶵ�G<input type=text name=selection3 size=30 value="SEL3"><br>
�@�п�J�ĥ|�ﶵ�G<input type=text name=selection4 size=30 value="SEL4"><br>
�@�п�ܵ���:1.<input type=checkbox name=check_1 value=1 CHECK1>
	   2.<input type=checkbox name=check_2 value=2 CHECK2>
	   3.<input type=checkbox name=check_3 value=3 CHECK3>
	   4.<input type=checkbox name=check_4 value=4 CHECK4><br>
�@��J���ת�link��m(option):<input type=text name=ans_link size=30 value="ANS_LINK"><br>
BUTTON
�@<input type=submit name=submit value="SUBMIT" OnClick="return Check();"><br>
�@<input type=reset name=reset value=���s�s��><br>
</form>
<script language="JavaScript">
<!--
function Check() {
	count = 0;
	if ( testcont.check_1.checked )
		count ++;
	if ( testcont.check_2.checked )
		count ++;
	if ( testcont.check_3.checked )
		count ++;
	if ( testcont.check_4.checked )
		count ++;
	if ( testcont.cho.value == "0" ) {
		if ( count > 1 ) {
			alert("���D�����,�ФŤĿ�h������!");
			return false;
		}
	}
	if ( count == 0 ) {
		alert("���פ��i�H�S��!");
		return false;
	}
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