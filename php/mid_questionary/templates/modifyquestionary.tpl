<form method=POST name=selectop action=modify_test.php>
　<font color="#FF0000">MESSAGE</font><br>
　請選擇題號:
<select name=a_id size="1" onChange="Change();">
<!-- BEGIN DYNAMIC BLOCK: select -->
<option value=QNO>QNN</option>
<!-- END DYNAMIC BLOCK: select -->
</select><br>
<input type=hidden name=exam_id value="EXAMID">
<input type=hidden name=action value="modifytest">
　<b>第NUMBER題</b>
</form>
