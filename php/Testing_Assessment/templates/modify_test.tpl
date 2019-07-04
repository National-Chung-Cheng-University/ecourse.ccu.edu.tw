<HTML>
<head>
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css">

<title>修改測驗</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
</head>
<p align="center"> <font size="2"><font color="#000000">修改測驗</font></font></p>
<center>
<font color="#FF0000">!!!!成績系統預設是</font><font color="#0033CC">公佈</font><font color="#FF0000">，如果老師不想讓學生看到某次成績，請按&quot;</font><font color="#0000FF">不公佈</font><font color="#FF0000">&quot;!!!!</font><BR>
<BR>
<font color="#FF0000"></font> 
<table border="0" align="center" cellpadding="0" cellspacing="0" width="90%">
<tr> 
<td> 
<div align="right"><img src="/images/skinSKINNUM/bor/bor_01.GIF" width="12" height="11"></div>
</td>
<td> 
<div align="center"><img src="/images/skinSKINNUM/bor/bor_02.GIF" width="100%" height="11"></div>
</td>
<td> 
<div align="left"><img src="/images/skinSKINNUM/bor/bor_03.GIF" width="17" height="11"></div>
</td>
</tr>
<tr> 
<td height=10> 
<div align="right"><img src="/images/skinSKINNUM/bor/bor_04.GIF" width="12" height="100%"></div>
</td>
<td bgcolor="#CCCCCC"> 
<table border=0 align="center" cellpadding="3" width="100%" cellspacing="1">
<tr bgcolor="#000066">
  <td><div align="center"><font color="#FFFFFF">章別</font></div></td> 
<td> 
<div align="center"><font color="#FFFFFF">名稱</font></div>
</td>
<td> 
<div align="center"><font color="#FFFFFF">類型</font></div>
</td>
<td> 
<div align="center"><font color="#FFFFFF">配分</font></div>
</td>
<td> 
<div align="center"><font color="#FFFFFF">修改名稱與比例</font></div>
</td>
<td> 
<div align="center"><font color="#FFFFFF">測驗</font></div>
</td>
<td> 
<div align="center"><font color="#FFFFFF">修改</font></div>
</td>
<td> 
<div align="center"><font color="#FFFFFF">成績</font></div>
</td>
<td> 
<div align="center"><font color="#FFFFFF">發佈考試</font></div>
</td>
<td> 
<div align="center"><font color="#FFFFFF">匯出考試題目</font></div>
<td> 
<div align="center"><font color="#FFFFFF">刪除考試</font></div>
</td>
<td>
<div align="center"><font color="#FFFFFF">公佈成績</font></div>
</td>
<td>
<div align="center"><font color="#FFFFFF">統計資料</font></div>
</td>
<td>
<div align="center"><font color="#FFFFFF">成績重算</font></div>
</td>
</tr>
<!-- BEGIN DYNAMIC BLOCK: row -->
<tr bgcolor="COLOR">
  <td>CHAP_NUM</td>
  <td><div align="center">TESTNAME</div></td><td><div align="center">TESTTYPE</div></td><td><div align="center">RATIO %</div></td>
<td><div align="center"><a href=modify_test.php?exam_id=EXAMID&action=modifynr>修改名稱與比例</a></div></td>
<td><div align="center"><a href=modify_test.php?exam_id=EXAMID&action=showtest>觀看試題</a></div></td>
<td><div align="center"><a href=modify_test.php?exam_id=EXAMID&action=modifytest>修改試題</a></div></td>
<td><div align="center"><a href=modify_test.php?exam_id=EXAMID&action=showgrade>觀看學生成績</a></div></td>
<td><div align="center"><a href=modify_test.php?exam_id=EXAMID&action=pubtest>考試發佈設定</a></div></td>
<td><div align="center"><a href=import_test.php?exam_id=EXAMID&action=export>匯出</a></div></td>
<form method=post action=modify_test.php>
<td><div align="center"><input type=hidden name=exam_id value=EXAMID><input type=hidden name=action value=deletetest><input type=submit name=submit value=刪除 onclick="return confirm('確定要刪除此測驗嗎?')"></div></td>
</form>
<!--added by devon-->
<form metho=post action=modify_test.php>
<td><div align="center"><input type=hidden name=exam_id value=EXAMID><input type=hidden name=action value=pubgrade><input type=submit name=submit value=ISPUBGRADE></div></td>
</form>
<td><div align="center"><a href=exam_statistics.php?exam_id=EXAMID>測驗統計</a></div></td>
<td><div align="center"><a href=re_cacu_exam_grade.php?exam_id=EXAMID>成績重算</a></div></td>
</tr>
<!-- END DYNAMIC BLOCK: row -->
</table>
</td>
<td height=10> 
<div align="left"><img src="/images/skinSKINNUM/bor/bor_06.GIF" width="17" height="100%"></div>
</td>
</tr>
<tr> 
<td> 
<div align="right"><img src="/images/skinSKINNUM/bor/bor_07.GIF" width="12" height="17"></div>
</td>
<td> 
<div align="center"><img src="/images/skinSKINNUM/bor/bor_08.GIF" width="100%" height="17"></div>
</td>
<td> 
<div align="left"><img src="/images/skinSKINNUM/bor/bor_09.GIF" width="17" height="17"></div>
</td>
</tr>
</table>
<p><font color=#ff0000>請進入"考試發佈設定"選項中,設定考試時間與長度,以便讓學生看到這次考試!</font></p>
</center>
</BODY></html>
