<HTML>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
</head>
<BODY background="/images/img_E/bg.gif">
<p>
<img src="/images/img_E/a332.gif">
</p>
<center>
<BR><BR>
<font color="#FF0000">MESSAGE</font>
<table border=1 bordercolor=#9FAE9D><tr><td>
<Table border=0>
<tr bgcolor="#4d6eb2"><td><font color="#FFFFFF">Chapter</font></font></td> <td><font color="#FFFFFF">Name</font></td><td><font color="#FFFFFF">Type</font></td><td><font color="#FFFFFF">Ratio</font></td><td><font color="#FFFFFF">Modify name and ratio</font></td><td><font color="#FFFFFF">Test</font></td><td><font color="#FFFFFF">Modify</font></td><td><font color="#FFFFFF">Score</font></td><td><font color="#FFFFFF">Public Test</font></td><td><font color="#FFFFFF">Export Test</font><td><font color="#FFFFFF">Delete Test</font></td><td><font color="#FFFFFF">Public Grades</font></td>
<td>
<div align="center"><font color="#FFFFFF">Score Statistics</font></div>
</td>
<td>
<div align="center"><font color="#FFFFFF">Score Reset</font></div>
</td>
</tr>
<!-- BEGIN DYNAMIC BLOCK: row -->
<tr bgcolor="COLOR">
<td>CHAP_NUM</td><td>TESTNAME</td><td>TESTTYPE</td><td>RATIO %</td>
<td><a href=modify_test.php?exam_id=EXAMID&action=modifynr>Modify name and ratio</a></td>
<td><a href=modify_test.php?exam_id=EXAMID&action=showtest>See question</a></td>
<td><a href=modify_test.php?exam_id=EXAMID&action=modifytest>Modify question</a></td>
<td><a href=modify_test.php?exam_id=EXAMID&action=showgrade>See student's score</a></td>
<td><a href=modify_test.php?exam_id=EXAMID&action=pubtest>Set_Public_Test</a></td>
<td><a href=import_test.php?exam_id=EXAMID&action=export>Export</a></td>
<form method=post action=modify_test.php>
<td><input type=hidden name=exam_id value=EXAMID><input type=hidden name=action value=deletetest><input type=submit name=submit value=Delete onclick="return confirm('Sure to Delete this exam?')"></td>
</form>
<!--added by devon-->
<form metho=post action=modify_test.php>
<td><div align="center"><input type=hidden name=exam_id value=EXAMID><input type=hidden name=action value=pubgrade><input type=submit name=submit value=ISPUBGRADE></div></td>
</form>
<td><div align="center"><a href=exam_statistics.php?exam_id=EXAMID>Statistics</a></div></td>
<td><div align="center"><a href=re_cacu_exam_grade.php?exam_id=EXAMID>Reset</a></div></td>
</tr>
<!-- END DYNAMIC BLOCK: row -->
</table></td></tr></table><BR>
<font color=#ff0000>Enter the "Set_Public_Test", and set beginning time to let students take exam!</font>
</center>
</BODY></html>
