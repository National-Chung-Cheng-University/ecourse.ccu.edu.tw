<html>
<head>
<title> TITLE </title>
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css">
</head>
<body >
<IMG SRC="/images/img_E/b52.gif">
<table border=0>
<tr><td><a href="article_list.php?discuss_id=DIS_ID&page=PAGE">Article List</a>
    <td>&nbsp;|&nbsp;<a href="post_article.php?discuss_id=DIS_ID&parent=PRT_ID&reply_id=ART_ID&page=PAGE">Reply This Article</a>
	<td>&nbsp;|&nbsp;<a href="post_article.php?discuss_id=DIS_ID&page=PAGE">Post New Article</a>
	<td>&nbsp;|&nbsp;<a href="show_article.php?GET_VARS&page=PAGE">Change sort method</a>
	<td>DELETELINK
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
	<td>&nbsp;|&nbsp;<a href="show_article.php?GET_VARS&page=PAGE">Change sort method</a>
	<td>DELETELINK
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