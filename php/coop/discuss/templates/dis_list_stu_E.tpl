<html>
<head>
<title> TITLE </title>
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css">
<script language="Javascript">
var selected = false;
function check(selected) {
     if(selected == false)
	  return false;
}
</script>
</head>
<body>
<IMG SRC="/images/img_E/b52.gif">
<center>
<font color="red">ERROR_MSG</font>
</center>
<form name="handle" action="handle_discuss.php" method="post">
<table border=1 width=100%>
<tr bgcolor=#cdeffc><td width=50>Select Discuss<td>Discussion Group Name<td>Comment<td width=120>Type<td>Subscribe
<!-- BEGIN DYNAMIC BLOCK: discuss_list -->
<tr bgcolor=DISCOLOR>
<td width=50><input type="checkbox" name="DEL_NAME" value="DIS_ID" onClick="selected=(selected||this.checked);">
<td><a href="ART_LIST" LOG_PRG>DIS_NAME</a>
<td>DIS_COMMENT
<td width=120>DIS_TYPE
<td width=80>SUB_STATUS
<!-- END DYNAMIC BLOCK: discuss_list -->
</table>
<img src="/images/arrow_ltr.gif" border=0 width="38" height="22" alt="With Selected" >
<input type="submit" name="submit" value="Subscribe" onClick="return check(selected);">
<input type="submit" name="submit" value="StopSub" onClick="return check(selected);">
</form>
<hr>
<p align="right">
<form action="search_discuss.php" method="post">
<table border=1 width=50%>
<caption>Discuss group article Search</caption>
<tr><td bgcolor="#edf3fa">Search String<td bgcolor=#cdeffc><input type="text" name="keyword">
	<br>Multiple key words are accectable. All words entered are searched by "<I>AND</I>".
<tr><td bgcolor="#edf3fa">Search By<td bgcolor=#cdeffc><input type="radio" name="type" value="0" checked>Title
<input type="radio" name="type" value="1">Poster
<input type="radio" name="type" value="2">Content
</table>
<input type="submit" value="Search">
<input type="reset" value="Reset">
</p>
</body>
</html>