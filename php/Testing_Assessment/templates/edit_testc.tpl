　單選或複選:<select size=1 name=cho>
　<option value=0 CHO0>單選</option>
　<option value=1 CHO1>複選</option>
　</select><br><br>
　請輸入第一選項：<input type=text name=selection1 size=30 value="SEL1"><br>
　請輸入第二選項：<input type=text name=selection2 size=30 value="SEL2"><br>
　請輸入第三選項：<input type=text name=selection3 size=30 value="SEL3"><br>
　請輸入第四選項：<input type=text name=selection4 size=30 value="SEL4"><br>
　請選擇答案:1.<input type=checkbox name=check_1 value=1 CHECK1>
	   2.<input type=checkbox name=check_2 value=2 CHECK2>
	   3.<input type=checkbox name=check_3 value=3 CHECK3>
	   4.<input type=checkbox name=check_4 value=4 CHECK4><br>
　輸入答案的link位置(option):<input type=text name=ans_link size=30 value="ANS_LINK"><br>
BUTTON
　<input type=submit name=submit value="SUBMIT" OnClick="return Check();"><br>
　<input type=reset name=reset value=重新編輯><br>
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
			alert("此題為單選,請勿勾選多項答案!");
			return false;
		}
	}
	if ( count == 0 ) {
		alert("答案不可以沒選!");
		return false;
	}
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