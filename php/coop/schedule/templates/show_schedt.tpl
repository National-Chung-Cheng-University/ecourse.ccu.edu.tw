<br><hr><br>
<font color="#FF0000">MEG</font>
<form method=POST action=./show_sched.php name=Shcedule enctype="multipart/form-data">
<input type=hidden name=flag value="1">
<table border=0 cellpadding="5" cellspacing="1" width="60%">
<TR> 
<TD bgcolor="#000066"> <font color="#FFFFFF" size="2"> 
<input type="radio" value="1" name="AddKind" checked>��ܴ���:
<select NAME=week onChange="Shcedule.AddKind[0].checked=true">
<!-- BEGIN DYNAMIC BLOCK: week_list -->
<option value=WEV >WED
<!-- END DYNAMIC BLOCK: week_list -->
</select>
<input type="radio" value="0" name="AddKind">��ܴ��J�I:
<select NAME=inswk onChange="Shcedule.AddKind[1].checked=true">
<!-- BEGIN DYNAMIC BLOCK: ins_list -->
<option value=INSV >INSD
<!-- END DYNAMIC BLOCK: ins_list -->
</select>
</TD></TR>
<TR><TD bgcolor="#E6FFFC">
�p����:
<select NAME=unit>
<option value="��" U1>��</option>
<option value="�g" U2>�g</option>
<option value="��" U3>��</option>
<option value="��" U4>��</option>
</select>
�п�J���:
<select NAME=year>
<!-- BEGIN DYNAMIC BLOCK: year_list -->
<option value=YEV >YED
<!-- END DYNAMIC BLOCK: year_list -->
</select>�~
<select NAME=month>
<!-- BEGIN DYNAMIC BLOCK: m_list -->
<option value=MOV >MOD
<!-- END DYNAMIC BLOCK: m_list -->
</select>��
<select NAME=day>
<!-- BEGIN DYNAMIC BLOCK: d_list -->
<option value=DAV >DAD
<!-- END DYNAMIC BLOCK: d_list -->
</select>��</TD></TR>
<TR> 
<TD> 
<div align="center"><font size="2">�п�J���D: <br>
<input type="text" name="subject" maxlength=100 size="56" value=SUBJECT>
</font></div>
</TD>
</TR>
<TR> 
<TD> 
<div align="center"><font size="2">�п�J���e: <br>
<textarea name=content rows=12 cols=55 >TEXT</textarea>
</font></div>
</TD>
</TR>
<tr>
<td>
<div align="center"><font size="2">���ɡG<br>
<input type="FILE" name="file" size="49">
</td>
</tr>
<tr>
<TR>
<TD> 
<div align="center"> <font size="2"> 
<input type="submit" value="�T�w" OnClick="return Check();">
<input type="reset" value="�M��">
</font></div>
</TD>
</TR>
</table>
</form>
<font color="#FF0000" size="2">�p���R���ҵ{�w�ơA�п�ܴ��ƫ�A�N�ҵ{���e�d�հe�X�C</font><font size="2"><br>
<font color=#FF0000>�p����s�ҵ{�w�ơA�п�ܱ���s�����ƫ�A�N�ҵ{���e�e�X�C</font></font> 
</center></BODY>
</HTML>
