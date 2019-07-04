<html>
<head>
<title> TITLE </title>
<head>
<title>最新消息</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<STYLE type=text/css>
 body { SCROLLBAR-FACE-COLOR: #4d6eb2; SCROLLBAR-ARROW-COLOR: #FFFFFF; SCROLLBAR-TRACK-COLOR: #DDDDFF; SCROLLBAR-SHADOW-COLOR: #054878; SCROLLBAR-3DLIGHT-COLOR: black; }
</STYLE> 
<link rel="stylesheet" href="/images/skin1/css/main-body.css" type="text/css">
<script language="Javascript">
var selected = false;
function check(selected) {
   if(selected == true) {
     return confirm('Every selected article and its related article(s) will be deleted!! Are you Sure?');
   }
   else
     return false;
}
</script>
</head>
<body background="/images/img_E/bg.gif">
<IMG SRC="/images/img_E/b52.gif">
<table border=0>
<tr><td><a href="dis_list.php">Discussion Group List</a>&nbsp;|&nbsp;
    <td><a href="post_article.php?discuss_id=DIS_ID&page=PAGE_NOW">Post New Article</a>
	<td>Current is at page <font color=red>PAGE_NUM</font>, there are <font color=red>PAGE_TOTAL </font>pages total, <font color=red>ART_TOTAL</font> articles.
</table>
<form method="post" action="del_article.php" onSubmit="return check(selected);">
<table border=1 width=100%>
<tr bgcolor=#cdeffc>
  <td>Title
  <td><font color=#ffffff><a href='article_list.php?discuss_id=DIS_ID&sortby=1&group_num=GROUP_NUM'>Poster</a></font>
  <td><font color=#ffffff><a href='article_list.php?discuss_id=DIS_ID&group_num=GROUP_NUM'>Post Date</a></font>
  <td><font color=#ffffff><a href='article_list.php?discuss_id=DIS_ID&sortby=2&group_num=GROUP_NUM'>Recently Reply Date</a></font>
  <td><font color=#ffffff><a href='article_list.php?discuss_id=DIS_ID&sortby=3&group_num=GROUP_NUM'>Viewed</a></font>
  <td>Replied
  <td>Delete
<!-- BEGIN DYNAMIC BLOCK: article_list -->
<tr bgcolor=ARCOLOR><td>ITEMA<td>ITEMB<td>ITEMC<td>ITEMD<td>ITEME<td>CHILDS<td>DELETE
<!-- END DYNAMIC BLOCK: article_list -->
</table>
<table border=0 width=100%>
<tr><td align="right">
<input type="hidden" name="discuss_id" value="DIS_ID">
<input type="submit" name="submit"><input type="reset" name="reset">
</table>
<table border=0>
<tr><td><a href="dis_list.php">Discussion Group List</a>&nbsp;|&nbsp;
    <td><a href="post_article.php?discuss_id=DIS_ID&page=PAGE_NOW">Post New Article</a>
	<td>Current is at page <font color=red>PAGE_NUM</font>, there are <font color=red>PAGE_TOTAL </font>pages total, <font color=red>ART_TOTAL</font> articles.
</table>
<hr>
<center>
PREV_PAGE  PAGE_LINK  NEXT_PAGE
</center>
</body>
</html>
