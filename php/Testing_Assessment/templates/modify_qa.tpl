<html>
<head>
<link rel="stylesheet" href="/images/skin1/css/main-body.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=big5">
</head>
<body bgcolor="#FFFFFF" text="#000000" link="#000066" vlink="#666666" alink="#CC0000" topmargin="0" leftmargin="0" marginwidth="0" marginheight="0">
<p align="center">批改問答題</p>
<table border="0" align="center" >
<td><p align="left">=====================================================================<br>學號：STUID<br>姓名：STUNAME </p></td>
<tr><td>=====================================================================</td></tr>
<tr><td align="center"><form method="post" action="modify_qa.php">
<!-- BEGIN DYNAMIC BLOCK: qa_list -->
<font color="#FF0000">(POINTS分) </font><font color="blue">QANAME</font><br><br>
<textarea name=qatextarea  rows="5" cols="60">QATEXT</textarea><br>
給分：
<input type="text" name="ITEMGRADE" size="3">
<input type="hidden" name="action" value="insertgrade">
<input type="hidden" name="ITEM" value="ITEM_ID">
<input type="hidden" name="exam_id" value="EXAM_ID">
<input type="hidden" name="student_a_id" value="STUAID">
<br><br>
<!-- END DYNAMIC BLOCK: qa_list -->
<br><input type="submit" name="submit" value="送出">

</form>
</td>
</tr>
</table>
</body>
</html>
