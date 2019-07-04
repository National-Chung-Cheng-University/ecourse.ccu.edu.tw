<br><hr><br>
<font color="#FF0000">MEG</font>
<form method=POST action=./show_sched.php name=Shcedule enctype="multipart/form-data">
<input type=hidden name=flag value="1">
<table border=0 cellpadding="5" cellspacing="1" width="60%">
<TR> 
<TD bgcolor="#000066"> <font color="#FFFFFF" size="2"> 
<input type="radio" value="1" name="AddKind" checked>選擇期數:
<select NAME=week onChange="Shcedule.AddKind[0].checked=true">
<!-- BEGIN DYNAMIC BLOCK: week_list -->
<option value=WEV >WED
<!-- END DYNAMIC BLOCK: week_list -->
</select>
<input type="radio" value="0" name="AddKind">選擇插入點:
<select NAME=inswk onChange="Shcedule.AddKind[1].checked=true">
<!-- BEGIN DYNAMIC BLOCK: ins_list -->
<option value=INSV >INSD
<!-- END DYNAMIC BLOCK: ins_list -->
</select>
</TD></TR>
<TR><TD bgcolor="#E6FFFC">
計算單位:
<select NAME=unit>
<option value="月" U1>月</option>
<option value="週" U2>週</option>
<option value="天" U3>天</option>
<option value="次" U4>次</option>
</select>
請輸入日期:
<select NAME=year>
<!-- BEGIN DYNAMIC BLOCK: year_list -->
<option value=YEV >YED
<!-- END DYNAMIC BLOCK: year_list -->
</select>年
<select NAME=month>
<!-- BEGIN DYNAMIC BLOCK: m_list -->
<option value=MOV >MOD
<!-- END DYNAMIC BLOCK: m_list -->
</select>月
<select NAME=day>
<!-- BEGIN DYNAMIC BLOCK: d_list -->
<option value=DAV >DAD
<!-- END DYNAMIC BLOCK: d_list -->
</select>日</TD></TR>
<TR> 
<TD> 
<div align="center"><font size="2">請輸入標題: <br>
<input type="text" name="subject" maxlength=100 size="56" value=SUBJECT>
</font></div>
</TD>
</TR>
<TR> 
<TD> 
<div align="center"><font size="2">請輸入內容: <br>
<textarea name=content rows=12 cols=55 >TEXT</textarea>
</font></div>
</TD>
</TR>
<tr>
<td>
<div align="center"><font size="2">夾檔：<br>
<input type="FILE" name="file" size="49">
</td>
</tr>
<tr>
<TR>
<TD> 
<div align="center"> <font size="2"> 
<input type="submit" value="確定" OnClick="return Check();">
<input type="reset" value="清除">
</font></div>
</TD>
</TR>
</table>
</form>
<font color="#FF0000" size="2">如欲刪除課程安排，請選擇期數後，將課程內容留白送出。</font><font size="2"><br>
<font color=#FF0000>如欲更新課程安排，請選擇欲更新之期數後，將課程內容送出。</font></font> 
</center></BODY>
</HTML>
