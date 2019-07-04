<html>
<head>
<title> TITLE </title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css">
<script language="Javascript">
var selected = false;
function check(selected) {
   if(selected == true) {
     return confirm('這將會刪除你所選的討論主題與其所有相關討論文章!!');
   }
   else
     return false;
}
</script>
</head>
<body>
<IMG SRC="/images/img/b52.gif">
<table border=0>
<tr><td><a href="dis_list.php">討論區一覽</a>&nbsp;|&nbsp;
    <td><a href="post_article.php?discuss_id=DIS_ID&page=PAGE_NOW">發表新主題</a>
	<td>目前為第 <font color=red>PAGE_NUM</font> 頁, 共有 <font color=red>PAGE_TOTAL </font>頁, <font color=red>ART_TOTAL</font> 篇討論文章.
</table>
<form method="post" action="del_article.php" onSubmit="return check(selected);">
<table border=1 width=100%>
<tr bgcolor=#cdeffc>
  <td>主題
  <td><font color=#ffffff><a href='article_list.php?discuss_id=DIS_ID&sortby=1&group_num=GROUP_NUM'>張貼者</a></font>
  <td><font color=#ffffff><a href='article_list.php?discuss_id=DIS_ID&group_num=GROUP_NUM'>張貼日期</a></font>
  <td><font color=#ffffff><a href='article_list.php?discuss_id=DIS_ID&sortby=2&group_num=GROUP_NUM'>最近回覆日期</a></font>
  <td><font color=#ffffff><a href='article_list.php?discuss_id=DIS_ID&sortby=3&group_num=GROUP_NUM'>點閱次數</a></font>
  <td>回覆次數
  <td>刪除此文章
<!-- BEGIN DYNAMIC BLOCK: article_list -->
<tr bgcolor=ARCOLOR><td>ITEMA<td>ITEMB<td>ITEMC<td>ITEMD<td>ITEME<td>CHILDS<td>DELETE
<!-- END DYNAMIC BLOCK: article_list -->
</table>
<table border=0 width=100%>
<tr><td align="right">
<input type="hidden" name="discuss_id" value="DIS_ID">
<input type="submit" name="submit" value="刪除文章"><input type="reset" name="reset" value="重新選擇">
</table>
<table border=0>
<tr><td><a href="dis_list.php">討論區一覽</a>&nbsp;|&nbsp;
    <td><a href="post_article.php?discuss_id=DIS_ID&page=PAGE_NOW">發表新主題</a>
	<td>目前為第 <font color=red>PAGE_NUM</font> 頁, 共有 <font color=red>PAGE_TOTAL </font>頁, <font color=red>ART_TOTAL</font> 篇討論文章.
</table>
<hr>
<center>
PREV_PAGE  PAGE_LINK  NEXT_PAGE
</center>
</body>
</html>