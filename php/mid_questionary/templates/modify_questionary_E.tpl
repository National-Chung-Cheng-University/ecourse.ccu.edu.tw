<HTML>
<BODY background="/images/img/bg.gif">
<p>
<img src="/images/img/a332.gif">
</p>
<center>
<BR><BR>
<font color="#FF0000">MESSAGE</font>
<table border=1 bordercolor=#9FAE9D><tr><td>
<Table border=0>
<tr bgcolor="#4d6eb2"><td><font color="#FFFFFF">Name</font></td><td><font color="#FFFFFF">Type</font></td><td><font color="#FFFFFF">Limit</font></td><td><font color="#FFFFFF">Modify Name and Type</font></td><td><font color="#FFFFFF">Preview</font></td><td><font color="#FFFFFF">Modify</font></td><td><font color="#FFFFFF">Total</font></td><td><font color="#FFFFFF">Public</font></td><td><font color="#FFFFFF">Delete</font></td></tr>
<!-- BEGIN DYNAMIC BLOCK: row -->
<tr bgcolor="COLOR"><td>QUESNAME</td><td>QUESTYPE</td><td>QUESLIMIT</td>
<td><a href=modify_questionary.php?q_id=QUESID&action=modifynr>Modify Name and Type</a></td>
<td><a href=modify_questionary.php?q_id=QUESID&action=showquestionary>Preview</a></td>
<td><a href=modify_questionary.php?q_id=QUESID&action=modifyquestionary>Modify</a></td>
<td><a href=modify_questionary.php?q_id=QUESID&action=showtotal>Total</a></td>
<td><a href=modify_questionary.php?q_id=QUESID&action=pubquestionary>Set_Public_Test</a>
</td>
<form method=post action=modify_questionary.php>
<td><input type=hidden name=q_id value=QUESID><input type=hidden name=action value=deletequestionary><input type=submit name=submit value=刪除 onclick="return confirm('確定要刪除此問卷嗎?')"></td>
</form>
</tr>
<!-- END DYNAMIC BLOCK: row -->
</table></td></tr></table><BR>
<font color=#ff0000>Enter the "Set_Public_Test", and set beginning time to let students take Questionary! </font>
</center>
</BODY></html>
