<HTML>
<BODY background="/images/img/bg.gif">
<p>
&gt;&gt;&gt; 修改期中問卷
</p>
<center>
<BR>
<font color="#FF0000">MESSAGE</font>
<Table border=1 bordercolor=#9FAE9D><tr><td>
<table border=0 cellpadding="3">
<tr bgcolor="#4d6eb2">
<td><font color="#FFFFFF">學年度</font></td>
<td><font color="#FFFFFF">學期</font></td>
<td><font color="#FFFFFF">問卷名稱</font></td>
<td><font color="#FFFFFF">試填</font></td>
<td><font color="#FFFFFF">發佈問卷</font></td>
<td><font color="#FFFFFF">統計</font></td>
<td bgcolor="#999999"><font color="#FFFFFF">修改名稱與屬性</font></td>
<td bgcolor="#999999"><font color="#FFFFFF">修改題目</font></td>
<td bgcolor="#999999"><font color="#FFFFFF">刪除問卷</font></td>
</tr>
<!-- BEGIN DYNAMIC BLOCK: row -->
<tr bgcolor="COLOR">
<td><div align="center">QUESYEAR</div></td>
<td><div align="center">QUESTERM</div></td>
<td><div align="center">QUESNAME</div></td>
<td><a href=modify_questionary.php?q_id=QUESID&action=showquestionary>觀看問卷</a></td>
<td><a href=modify_questionary.php?q_id=QUESID&action=pubquestionary>問卷發佈設定</a>
<!--td><a href=statistic2.php?q_id=QUESID&year=QUESYEAR&term=QUESTERM>觀看問卷統計</a>(停用－滿意度統計)</td-->
<td><a href=statistic2.php?q_id=QUESID&year=QUESYEAR&term=QUESTERM>觀看問卷統計</a></td>
<td><a href=modify_questionary.php?q_id=QUESID&action=modifynr>修改名稱與屬性</a></td>
<td><a href=modify_questionary.php?q_id=QUESID&action=modifyquestionary target="_blank">修改問卷</a></td>

<form method=post action=modify_questionary.php>
<td><input type=hidden name=q_id value=QUESID>
<input type=hidden name=action value=deletequestionary>
<input type=submit name=submit value=刪除 onclick="return confirm('確定要刪除此問卷嗎?')">
</td></form>
</tr>
<!-- END DYNAMIC BLOCK: row -->
</table></td></tr></Table><BR>
</center>
</BODY></html>
