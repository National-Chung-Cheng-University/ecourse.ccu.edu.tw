<HTML>
<HEAD>
<title>及時訊息</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
</HEAD>
<BODY background = "/images/img/bg.gif">
<center><br>
<h2>及時訊息</h2>
<form method=POST action=./hotline.php>
<font color="#ff0000">MES</font><br>
<a href=./check_admin.php>回系統管理介面</a>
<table border=1>
<tr><td>訊息內容</td><td>
<input type=text name=msg size=40></td></tr>
<tr><td>關閉視窗</td><td>
<input type=checkbox name=close value=1></td></tr>
<tr><td>進入系統維護</td><td>
<input type=checkbox name=mantain value=1></td></tr>
</table>
<input type=submit value=送出>
<input type=reset value=清除>
</form>
<table border = 0>
<tr>
<form method=POST action=./hotline.php>
<td>
<input type=hidden name=delman value=1>
<input type=submit value=離開系統維護>
</td>
</form>
</tr></table>
</center>
</body></html>