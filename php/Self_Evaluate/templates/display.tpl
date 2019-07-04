<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<title>教師自評表</title>
</head>

<body background=/images/skin1/bbg.gif>
	<center>
	<font point-size="24pt" color="#000000" >期末課程評量表<br>
	<font point-size="14pt" color="#000000" >(GROUP YEAR學年 第TERM學期)&nbsp;&nbsp; </font><br><br>
	<!--<font point-size="14pt" color="#000000" >學生核心能力評量以証明是否達成本系教育目標教育目標</font><br>-->
	註：本授課自評表中的學習目標由授課大綱中擷取，若無資料請先編輯授課大綱的中華工程認證格式。
	<br><br>
	<font point-size="16pt" color="#000000">課程名稱：CNAME</font><br>
	<font point-size="16pt" color="#000000">課程代碼：CNO</font><br>
	<font point-size="16pt" color="#000000">填表人：USER_NAME</font><br><br>
	<font point-size="16pt" color="#000000">學生核心能力評量以証明是否達成本系教育目標</font>

	<table border=1>
	<tr bgcolor="#6699FF">
		<td rowspan="2" colspan="2" align="center"><font color="#000000" point-size="16pt">課程欲培養之核心能力</font></td>
		<td rowspan=2 width=300 align="center"><font color="#000000" point-size="16pt">對應之課程單元</font></td>
		<td rowspan=2 width=150 align="center"><font color="#000000" point-size="16pt">評量方式</font></td>
		<td rowspan=2 colspan=1 align="center"><font color="#000000" point-size="16pt">學生核心能力自評</font></td>
		
		<th rowspan=1 colspan="3" width=200 align="center"><font color="#000000" point-size="16pt" face="標楷體">學生評量分數</td>
	</tr>
	<tr>
		<th><font color="#000000" point-size="16pt">平均%</font></th>
		<th><font color="#000000" point-size="16pt">最高%</font></th>
		<th><font color="#000000" point-size="16pt">及格%</font></th>
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
	<tr><td><font color="blue" point-size="16pt">學生的心得與建議</font></td></tr>
	<tr><td><textarea name="student_suggest" rows="10" cols="70">STUDENT_SUGGEST</textarea><td><tr>
	</table>
	<br>
	<font color="#ff0000" point-size="16pt">學生能力自評介於1~5  5為最高</font><br><br>
	<font color="#000000" point-size="16pt">教學反思與建議：</font><br>
	<font color="#333333" point-size="14pt">(請依據本系核心能力之評量方法的合適性及達成指標的情形，註明那些課程學習目標必須要加強)</font><br>
	<textarea name="suggest" rows="10" cols="70">SUGGEST</textarea><br><br>
	<input type = "submit" name = "submit_over" value = "記錄教學反思與建議" >
	<input type = "submit" name = "submit_edit" value = "修改學生評量平均分數" >
	<input type="hidden" name="update" value="update_content">
	<!--<input type = "reset" name = "reset" value = "清除全部">-->
	</form>
	<!--
	<a href="./result_display.php" target="_blank">預覽授課自評表</a>
	<form ENCTYPE=multipart/form-data method=POST action=upload.php name=form1>
	<input type=hidden name=action value=upload>
	<input type=hidden name=location value=".$location.">
	<hr>
	<BR>
	上傳之檔案的附檔名需為<font color="#FF0000">"htm"</font>、<font color="#FF0000">"html"</font>、<font color="#FF0000">"doc"</font>、<font color="#FF0000">"pdf"</font>、<font color="#FF0000">"ppt"</font>這幾種格式，方可顯示。<br>
	<br>
	上傳檔案 : 
	<INPUT TYPE=FILE NAME=uploadfile1 SIZE=20><br>
	<INPUT TYPE=SUBMIT VALUE=上傳檔案 Onclick=\"return check();\">
	<INPUT TYPE=RESET VALUE=清除>
	<input type=hidden name=year value=$year>
	<input type=hidden name=term value=$term>
	</form><BR>
	
	<table border="1">
		<tr bgcolor="#4d6be2">
			<td><font color=#ffffff>檔名</font>
			<td><font color=#ffffff>檔案大小</font>
			<td><font color=#ffffff>最後修改日期</font>
			<td><font color=#ffffff>刪除檔案</font>
			</tr>
			<tr bgcolor=#edf3fa>						
			<td>".$file."
			<td>".$size."KB
			<td>".$date."
			<td><a href=\"upload_old_intro.php?year=$year&term=$term&action=delete&filename=".$file."&path=".$location."\" onclick=\"return confirm('你確定要刪除這個檔案嗎?');\">刪除這個檔案</a>
		</tr>
	</table>
	-->
	</center>
</body>
</html>
