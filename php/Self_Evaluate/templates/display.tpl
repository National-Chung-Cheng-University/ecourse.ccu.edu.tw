<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<title>�Юv�۵���</title>
</head>

<body background=/images/skin1/bbg.gif>
	<center>
	<font point-size="24pt" color="#000000" >�����ҵ{���q��<br>
	<font point-size="14pt" color="#000000" >(GROUP YEAR�Ǧ~ ��TERM�Ǵ�)&nbsp;&nbsp; </font><br><br>
	<!--<font point-size="14pt" color="#000000" >�ǥ֤ͮ߯�O���q�H�����O�_�F�����t�Ш|�ؼбШ|�ؼ�</font><br>-->
	���G���½Ҧ۵������ǲߥؼХѱ½Ҥj�����^���A�Y�L��ƽХ��s��½Ҥj�������ؤu�{�{�Ү榡�C
	<br><br>
	<font point-size="16pt" color="#000000">�ҵ{�W�١GCNAME</font><br>
	<font point-size="16pt" color="#000000">�ҵ{�N�X�GCNO</font><br>
	<font point-size="16pt" color="#000000">���H�GUSER_NAME</font><br><br>
	<font point-size="16pt" color="#000000">�ǥ֤ͮ߯�O���q�H�����O�_�F�����t�Ш|�ؼ�</font>

	<table border=1>
	<tr bgcolor="#6699FF">
		<td rowspan="2" colspan="2" align="center"><font color="#000000" point-size="16pt">�ҵ{�����i���֤߯�O</font></td>
		<td rowspan=2 width=300 align="center"><font color="#000000" point-size="16pt">�������ҵ{�椸</font></td>
		<td rowspan=2 width=150 align="center"><font color="#000000" point-size="16pt">���q�覡</font></td>
		<td rowspan=2 colspan=1 align="center"><font color="#000000" point-size="16pt">�ǥ֤ͮ߯�O�۵�</font></td>
		
		<th rowspan=1 colspan="3" width=200 align="center"><font color="#000000" point-size="16pt" face="�з���">�ǥ͵��q����</td>
	</tr>
	<tr>
		<th><font color="#000000" point-size="16pt">����%</font></th>
		<th><font color="#000000" point-size="16pt">�̰�%</font></th>
		<th><font color="#000000" point-size="16pt">�ή�%</font></th>
	</tr>
	
	<form method ="POST" action="self_evaluate.php">
	<!-- BEGIN DYNAMIC BLOCK: GoalList -->
	<tr>
		<td align="center">Index</td>
		<td align="center">Content</td>
		<td align="center"><textarea name=CA rows="4" cols="25" disabled>ClassTopicList</textarea></td>
		<td align="center"><textarea name=ET rows="4" cols="18" disabled>RefList</textarea></td>
		<td align="center"><input type="text" name=ET size="2" value=StudentEvaluate disabled></td>
		<td align="center"><input type="text" name=AvS[] id=AvS[] size="2" value=AverageScore >%</td>
		<td align="center"><input type="text" name=TS size="2" value=TopScore disabled>%</td>
		<td align="center"><input type="text" name=PS size="2" value=PassScore disabled>%</td>
	</tr>  
	<!-- END DYNAMIC BLOCK: GoalList -->
	</table>
	<br>
	<table>
	<tr><td><font color="blue" point-size="16pt">�ǥͪ��߱o�P��ĳ</font></td></tr>
	<tr><td><textarea name="student_suggest" rows="10" cols="70">STUDENT_SUGGEST</textarea><td><tr>
	</table>
	<br>
	<font color="#ff0000" point-size="16pt">�ǥͯ�O�۵�����1~5  5���̰�</font><br><br>
	<font color="#000000" point-size="16pt">�оǤϫ�P��ĳ�G</font><br>
	<font color="#333333" point-size="14pt">(�Ш̾ڥ��t�֤߯�O�����q��k���X�A�ʤιF�����Ъ����ΡA�������ǽҵ{�ǲߥؼХ����n�[�j)</font><br>
	<textarea name="suggest" rows="10" cols="70">SUGGEST</textarea><br><br>
	<input type = "submit" name = "submit_over" value = "�O���оǤϫ�P��ĳ" >
	<input type = "submit" name = "submit_edit" value = "�ק�ǥ͵��q��������" >
	<input type="hidden" name="update" value="update_content">
	<!--<input type = "reset" name = "reset" value = "�M������">-->
	</form>
	<!--
	<a href="./result_display.php" target="_blank">�w���½Ҧ۵���</a>
	<form ENCTYPE=multipart/form-data method=POST action=upload.php name=form1>
	<input type=hidden name=action value=upload>
	<input type=hidden name=location value=".$location.">
	<hr>
	<BR>
	�W�Ǥ��ɮת����ɦW�ݬ�<font color="#FF0000">"htm"</font>�B<font color="#FF0000">"html"</font>�B<font color="#FF0000">"doc"</font>�B<font color="#FF0000">"pdf"</font>�B<font color="#FF0000">"ppt"</font>�o�X�خ榡�A��i��ܡC<br>
	<br>
	�W���ɮ� : 
	<INPUT TYPE=FILE NAME=uploadfile1 SIZE=20><br>
	<INPUT TYPE=SUBMIT VALUE=�W���ɮ� Onclick=\"return check();\">
	<INPUT TYPE=RESET VALUE=�M��>
	<input type=hidden name=year value=$year>
	<input type=hidden name=term value=$term>
	</form><BR>
	
	<table border="1">
		<tr bgcolor="#4d6be2">
			<td><font color=#ffffff>�ɦW</font>
			<td><font color=#ffffff>�ɮפj�p</font>
			<td><font color=#ffffff>�̫�ק���</font>
			<td><font color=#ffffff>�R���ɮ�</font>
			</tr>
			<tr bgcolor=#edf3fa>						
			<td>".$file."
			<td>".$size."KB
			<td>".$date."
			<td><a href=\"upload_old_intro.php?year=$year&term=$term&action=delete&filename=".$file."&path=".$location."\" onclick=\"return confirm('�A�T�w�n�R���o���ɮ׶�?');\">�R���o���ɮ�</a>
		</tr>
	</table>
	-->
	</center>
</body>
</html>
