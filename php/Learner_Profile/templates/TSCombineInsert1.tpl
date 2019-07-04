<HTML>
<head>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=big5">
</head>
<BODY background="/images/img/bg.gif">
<FORM method=POST action="./TSCombineInsert1.php?action=insert">
<INPUT type="hidden" name="PHPSESSID" value="PHPSESSIONID">
<INPUT type="hidden" name="importcid" value="IMCID">
<CENTER>
<H1>匯入 CNAME 學生資料</H1>
<table border=2 cellspacing=4 cellpadding=4 bgcolor=#00a0a0>
<TH bgcolor=#008080>學號&nbsp;&nbsp;姓名</TH><TH bgcolor=#008080>學號&nbsp;&nbsp;姓名</TH><TH bgcolor=#008080>學號&nbsp;&nbsp;姓名</TH><TH bgcolor=#008080>學號&nbsp;&nbsp;姓名</TH><TH bgcolor=#008080>學號&nbsp;&nbsp;姓名</TH>
<!-- BEGIN DYNAMIC BLOCK: student_list -->
LINE
<TD><FONT size=2>STUID STUNAME</TD>
<INPUT type="hidden" name="aid[INDEX]" value="STUAID">
<INPUT type="hidden" name="id[INDEX]" value="STUID">
<INPUT type="hidden" name="name[INDEX]" value="STUNAME">
<!-- END DYNAMIC BLOCK: student_list -->
</TABLE>
<P>
<INPUT type=submit value="確認匯入">
</FORM>
Total: STUNUM students
<BR>
<BR>
<a href="./TSCombineInsert1.php"><strong>回學生新增(併班上課)</strong></a>
</BODY>
