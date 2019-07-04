<HTML>
<BODY background="/images/img/bg.gif">
<!--<p>
<img src="/images/img/a332.gif">
</p>
-->
<center>
<BR>
<p align="center"> <font color="#000000">修改問卷</font></p>
<BR>
<font color="#FF0000">MESSAGE</font>
<table border=1 bordercolor=#9FAE9D><tr><td>
<Table border=0>
<tr bgcolor="#4d6eb2">
<td><font color="#FFFFFF">名稱</font></td>
<td><font color="#FFFFFF">類型</font></td>
<td><font color="#FFFFFF">限制</font></td>
<td><font color="#FFFFFF">修改名稱與屬性</font></td>
<td><font color="#FFFFFF">試填</font></td>
<td><font color="#FFFFFF">修改</font></td>
<td><font color="#FFFFFF">統計</font></td>
<td><font color="#FFFFFF">發佈問卷</font></td>
<td><font color="#FFFFFF">問卷匯出</font></td>
<td><font color="#FFFFFF">刪除問卷</font></td></tr>
<!-- BEGIN DYNAMIC BLOCK: row -->
<tr bgcolor="COLOR"><td>QUESNAME</td><td>QUESTYPE</td><td>QUESLIMIT</td>
<td><a href=modify_questionary.php?q_id=QUESID&action=modifynr>修改名稱與屬性</a></td>
<td><a href=modify_questionary.php?q_id=QUESID&action=showquestionary>觀看問卷</a></td>
<td><a href=modify_questionary.php?q_id=QUESID&action=modifyquestionary>修改問卷</a></td>
<td><a href=modify_questionary.php?q_id=QUESID&action=showtotal>觀看問卷統計</a></td>
<td><a href=modify_questionary.php?q_id=QUESID&action=pubquestionary>問卷發佈設定</a></td>
<td><a href=modify_questionary.php?q_id=QUESID&course_id=COURSE_ID&action=export_questionary>匯出</a></td>

<form method=post action=modify_questionary.php>
<td><input type=hidden name=q_id value=QUESID><input type=hidden name=action value=deletequestionary><input type=submit name=submit value=刪除 onclick="return confirm('確定要刪除此問卷嗎?')"></td>
</form>
</tr>
<!-- END DYNAMIC BLOCK: row -->
</table></td></tr></table><BR>
<font color=#ff0000>請進入"問卷發佈設定"選項中,設定問卷時間與長度,以便讓學生看到這次問卷!</font></br>
<a href =in_ex_questionary.php?course_id=COURSE_ID>問卷匯入</a>
</center>
</BODY></html>
