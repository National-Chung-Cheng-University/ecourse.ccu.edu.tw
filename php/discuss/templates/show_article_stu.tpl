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
<body background="/images/img/bg.gif">
<IMG SRC="/images/img/b52.gif">
<table border=0>
<tr><td><a href="article_list.php?discuss_id=DIS_ID&page=PAGE">文章一覽</a>
    <td>&nbsp;|&nbsp;<a href="post_article.php?discuss_id=DIS_ID&parent=PRT_ID&reply_id=ART_ID&page=PAGE">回覆此文章</a>
	<td>&nbsp;|&nbsp;<a href="post_article.php?discuss_id=DIS_ID&page=PAGE">發表新文章</a>
	<td>&nbsp;|&nbsp;<a href="show_article.php?GET_VARS&page=PAGE">改變排序方法</a>
	<td>&nbsp;|&nbsp;<input type="button" value="友善列印" onclick="showPrint();" />
	<td>DELETELINK
</table>
<center>
<table border=1 width=100%>
<tr><td bgcolor=#4d6be2><font color=#ffffff>張貼者</font><td>POSTER
    <td bgcolor=#4d6be2><font color=#ffffff>張貼日期</font><td>CREATED
<tr><td bgcolor=#4d6be2><font color=#ffffff>主題</font><td colspan=3>ART_T
<tr><td colspan=4>
ART_BODY
<tr><td bgcolor=#4d6be2><font color=#ffffff>相關檔案</font>
<td colspan=3> FILELINK  ( FILESIZE bytes )
<tr><td bgcolor=#4d6be2><font color=#ffffff>附加語音檔案</font>
    <td colspan=3>PLAYER_CODE
</table>
</center>
<table border=0>
<tr><td><a href="article_list.php?discuss_id=DIS_ID&page=PAGE">文章一覽</a>
    <td>&nbsp;|&nbsp;<a href="post_article.php?discuss_id=DIS_ID&parent=PRT_ID&reply_id=ART_ID&page=PAGE">回覆此文章</a>
	<td>&nbsp;|&nbsp;<a href="post_article.php?discuss_id=DIS_ID&page=PAGE">發表新文章</a>
	<td>&nbsp;|&nbsp;<a href="show_article.php?GET_VARS&page=PAGE">改變排序方法</a>
	<td>&nbsp;|&nbsp;<input type="button" value="友善列印" onclick="showPrint();" />
	<td>DELETELINK
</table>
<hr>
相關討論文章
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
