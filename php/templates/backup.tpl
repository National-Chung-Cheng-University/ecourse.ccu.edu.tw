<HTML>
<HEAD>
<TITLE>系統備份設定</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
</HEAD>
<BODY background = "../images/img/bg.gif">
<center>
<BR>
<H2>系統備份設定</H2>
<a href=./check_admin.php>回系統管理介面</a><br>
MES<br>
<h3>目前備份時間:HH1:MM1</h3><br>
<h3><font color=#ff0000>系統將在每個星期日於你所設定的時間做系統的備份!!</font><h3><br>
<h3>目前上傳時間:HH2:MM2</h3><br>
<h3><font color=#ff0000>系統將在每個星期日於你所設定的時間將所備份的資料上傳到備份系統上。</font></h3><br>
<h3><font color=#ff0000>注意！！系統備份時間和上傳時間最好相隔30分鐘以上，以減輕系統的Loading</font></h3><br>
<h3><font color=#ff0000>如果想修改備份日期或備份系統位置，請洽系統研發者</font></h3><br>
<form method=post action=./backup.php>
<table border=1>
<tr><td>備份時間</td>
<td>
<select NAME=hh1>
<!-- BEGIN DYNAMIC BLOCK: backup_hh -->
<option value=HBV>HBD</option>
<!-- END DYNAMIC BLOCK: backup_hh -->
</select>
:
<select NAME=mm1>
<!-- BEGIN DYNAMIC BLOCK: backup_mm -->
<option value=MBV>MBD</option>
<!-- END DYNAMIC BLOCK: backup_mm -->
</select>
</td></tr>
<tr><td>上傳時間</td>
<td>
<select NAME=hh2>
<!-- BEGIN DYNAMIC BLOCK: backup_h2 -->
<option value=HUV>HUD</option>
<!-- END DYNAMIC BLOCK: backup_h2 -->
</select>
:
<select NAME=mm2>
<!-- BEGIN DYNAMIC BLOCK: backup_m2 -->
<option value=MUV>MUD</option>
<!-- END DYNAMIC BLOCK: backup_m2 -->
</select>
</td></tr>
</table>
<input type=submit value=確定>
<input type=reset value=清除>
</form></center>
</body></html>
