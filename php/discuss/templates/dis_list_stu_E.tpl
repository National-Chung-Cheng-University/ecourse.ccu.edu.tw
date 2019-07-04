<html>
<head>
<title> TITLE </title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<STYLE type=text/css>
 body { SCROLLBAR-FACE-COLOR: #4d6eb2; SCROLLBAR-ARROW-COLOR: #FFFFFF; SCROLLBAR-TRACK-COLOR: #DDDDFF; SCROLLBAR-SHADOW-COLOR: #054878; SCROLLBAR-3DLIGHT-COLOR: black; }
</STYLE>
<link rel="stylesheet" href="/images/skin1/css/main-body.css" type="text/css">
<script language="Javascript">
var selected = false;
function check(selected) {
     if(selected == false)
	  return false;
}
</script>
</head>
<body background="/images/img_E/bg.gif">
<table width="100%" border="0">
  <tr>
    <td align="left"><IMG SRC="/images/img/b52.gif"></td>
    <td align="right">
        <form action="recentPosts.php" method="get">
        Recent posts search：
        <select name="d">
                <!--option value="3">3 days</option>
                <option value="5" selected="selected">5 days</option>
                <option value="10">10 days</option>
                <option value="15">15 days</option>
                <option value="30">One month</option>
                <option value="60">Two months</option-->
		<option value="1">1 day</option>
                <option value="3" selected="selected">3 days</option>
                <option value="7">1 week</option>
                <option value="14">2 weeks</option>
                <option value="30">One month</option>
                <option value="60">Two months</option>
        </select>
        <input type="submit" value="Query" />
        </form>
    </td>
  </tr>
</table>
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
