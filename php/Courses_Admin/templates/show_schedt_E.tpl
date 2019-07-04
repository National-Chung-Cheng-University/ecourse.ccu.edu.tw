<br><hr><br>
<font color="#FF0000">MEG</font>
<form method=POST action=./show_sched.php name=Shcedule>
<input type=hidden name=flag value="1">
<table border=0 cellpadding="5" cellspacing="1" width="60%">
<TR> 
<TD bgcolor="#000066"> <font color="#FFFFFF" size="2"> 
<input type="radio" value="1" name="AddKind" checked>Number:
<select NAME=week onChange="Change();">
<!-- BEGIN DYNAMIC BLOCK: week_list -->
<option value=WEV >WED
<!-- END DYNAMIC BLOCK: week_list -->
</select>
<input type="radio" value="0" name="AddKind">Insert Into:
<select NAME=inswk onChange="Shcedule.AddKind[1].checked=true">
<!-- BEGIN DYNAMIC BLOCK: ins_list -->
<option value=INSV >INSD
<!-- END DYNAMIC BLOCK: ins_list -->
</select>
</TD></TR>
<TR><TD bgcolor="#E6FFFC">
Unit:
<select NAME=unit>
<option value="¤ë" U1>month</option>
<option value="¶g" U2>week</option>
<option value="¤Ñ" U3>day</option>
<option value="¦¸" U4>time</option>
</select>
Date:
<select NAME=year>
<!-- BEGIN DYNAMIC BLOCK: year_list -->
<option value=YEV >YED
<!-- END DYNAMIC BLOCK: year_list -->
</select>Year
<select NAME=month>
<!-- BEGIN DYNAMIC BLOCK: m_list -->
<option value=MOV >MOD
<!-- END DYNAMIC BLOCK: m_list -->
</select>Month
<select NAME=day>
<!-- BEGIN DYNAMIC BLOCK: d_list -->
<option value=DAV >DAD
<!-- END DYNAMIC BLOCK: d_list -->
</select>Day</TD></TR>
<TR> 
<TD> 
<div align="center"><font size="2">Subject: <br>
<br>
<input type="text" name="subject" value="SUJT" maxlength=100 size="60">
</font></div>
</TD>
</TR>
<TR> 
<TD> 
<div align="center"> <font size="2"> 
<input type="submit" value="Submit" OnClick="return Check();">
<input type="reset" value="Clear">
</font></div>
</TD>
</TR>
</table>
</form>
<font color="#FF0000" size="2">Let Subject be while to delete the schedule!!!</font><font size="2"><br>
<font color=#FF0000>Let Number been the same to update schedule!!!</font></font> 
</center></BODY>
</HTML>
