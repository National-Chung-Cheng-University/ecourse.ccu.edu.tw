<html>
<head>
<title> TITLE </title>
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css">
<script language="Javascript">
var selected = false;
function check(selected,type) {
	
	switch (type) {
		case 1:
			if(selected) {
				return confirm('All selected discuss group will be deleted!\nAre you sure to do this?');
			}
		case 2:
			if(selected) {
				return true;
			}
		case 3:
			if(selected) {
				return alert('Selected discuss group will be dumped\nYou can download it from hyper link above.');
			}
		default:
			return false;
	}

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
<tr bgcolor=#cdeffc><td width=50>Select Discuss<td>Discussion Group Name<td>Comment<td width=120>Type<td width=50>Modify Discuss<td>Subscribe
<!-- BEGIN DYNAMIC BLOCK: discuss_list -->
<tr bgcolor=DISCOLOR><td><a href="ART_LIST" LOG_PRG>DIS_NAME</a>
<td width=50><input type="checkbox" name="DEL_NAME" value="DIS_ID" onClick="selected=(selected||this.checked);">
<td>DIS_COMMENT
<td width=120>DIS_TYPE
<td width=50><a href="modify_discuss.php?discuss_id=DIS_ID&PHPSESSID=PHP_SESS">Start Modify</a>
<td width=80>SUB_STATUS
<!-- END DYNAMIC BLOCK: discuss_list -->
</table>
<img src="/images/arrow_ltr.gif" border=0 width="38" height="22" alt="With Selected" >
<input type="submit" name="submit" value="Delete group" onClick="return check(selected,1);">
<input type="submit" name="submit" value="Subscribe" onClick="return check(selected,2);">
<input type="submit" name="submit" value="StopSub" onClick="return check(selected,2);">
<p align="left">
<input type="button" value="Create new group" onclick="location.href='cre_discuss.php?PHPSESSID=PHP_SESS'">
</p>
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
</form>
</body>
</html>