<html>
<head>
<title> TITLE </title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<STYLE type=text/css>
 body { SCROLLBAR-FACE-COLOR: #4d6eb2; SCROLLBAR-ARROW-COLOR: #FFFFFF; SCROLLBAR-TRACK-COLOR: #DDDDFF; SCROLLBAR-SHADOW-COLOR: #054878; SCROLLBAR-3DLIGHT-COLOR: black; }
</STYLE> 
<link rel="stylesheet" href="/images/skin1/css/main-body.css" type="text/css">

<script language="JavaScript" type="text/JavaScript">
<!--
function showPrint(){
window.print();
}
//-->
</script>

</head>
<body background="/images/img_E/bg.gif">
<IMG SRC="/images/img_E/b52.gif">
<table border=0>
<tr><td><a href="article_list.php?discuss_id=DIS_ID&page=PAGE">Article List</a>
    <td>&nbsp;|&nbsp;<a href="post_article.php?discuss_id=DIS_ID&parent=PRT_ID&reply_id=ART_ID&page=PAGE">Reply This Article</a>
	<td>&nbsp;|&nbsp;<a href="post_article.php?discuss_id=DIS_ID&page=PAGE">Post New Article</a>
	<td>&nbsp;|&nbsp;<a href="del_article.php?discuss_id=DIS_ID&del_id[0]=ART_ID&page=PAGE" onclick="return confirm('This action will delete the article and its related file.Are you sure?\nNotice: If this article is the first one, all article which is replied to it will be deleted too.')">Delete this Article</a>
	<td>&nbsp;|&nbsp;<a href="show_article.php?GET_VARS&page=PAGE">Change sort method</a>
	<td>&nbsp;|&nbsp;<input type="button" value="Print this page" onclick="showPrint();" />
</table>
<center>
<table border=1 width=100%>
<tr><td bgcolor=#4d6be2><font color=#ffffff>Poster</font><td>POSTER
    <td bgcolor=#4d6be2><font color=#ffffff>Date</font><td>CREATED
<tr><td bgcolor=#4d6be2><font color=#ffffff>Title</font><td colspan=3>ART_T
<tr><td colspan=4>
ART_BODY
<tr><td bgcolor=#4d6be2><font color=#ffffff>Related file</font>
<td colspan=3> FILELINK  ( FILESIZE bytes )
<tr><td bgcolor=#4d6be2><font color=#ffffff>Attach voice file</font>
    <td colspan=3>PLAYER_CODE
</table>
</center>
<table border=0>
<tr><td><a href="article_list.php?discuss_id=DIS_ID&page=PAGE">Article List</a>
    <td>&nbsp;|&nbsp;<a href="post_article.php?discuss_id=DIS_ID&parent=PRT_ID&reply_id=ART_ID&page=PAGE">Reply This Article</a>
	<td>&nbsp;|&nbsp;<a href="post_article.php?discuss_id=DIS_ID&page=PAGE">Post New Article</a>
	<td>&nbsp;|&nbsp;<a href="del_article.php?discuss_id=DIS_ID&del_id[0]=ART_ID&page=PAGE" onclick="return confirm('This action will delete the article and its related file.Are you sure?\nNotice: If this article is the first one, all article which is replied to it will be deleted too.')">Delete this Article</a>
	<td>&nbsp;|&nbsp;<a href="show_article.php?GET_VARS&page=PAGE">Change sort method</a>
	<td>&nbsp;|&nbsp;<input type="button" value="Print this page" onclick="showPrint();" />
</table>
<hr>
Related Article
<ul>
<li><a href="PARENT">PAR_T</a> | PAR_P | PAR_C<br>
    <ul>
<!-- BEGIN DYNAMIC BLOCK: reply_list -->
    <li>THIS_POINTER<a href="REPLY">REP_T</a>  REPLIER  REPLIED<br>
<!-- END DYNAMIC BLOCK: reply_list -->
    </ul>
</ul>
</body>
</html>
