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
<body background="/images/img/bg.gif">
<table width="100%" border="0">
  <tr>
    <td align="left"><IMG SRC="/images/img/b52.gif"></td>
    <td align="right">
        <form action="recentPosts.php" method="get">
        最新文章查詢：
        <select name="d">
                <!--option value="3">3天內</option>
                <option value="5" selected="selected">5天內</option>
                <option value="10">10天內</option>
                <option value="15">15天內</option>
                <option value="30">一個月內</option>
                <option value="60">兩個月內</option-->
		<option value="1">1天內</option>
                <option value="3" selected="selected">3天內</option>
                <option value="7">1週內</option>
                <option value="14">2週內</option>
                <option value="30">一個月內</option>
                <option value="60">兩個月內</option>
        </select>
        <input type="submit" value="查詢" />
        </form>
    </td>
  </tr>
</table>
<center>
<font color="red">ERROR_MSG</font>
</center>
<form name="handle" action="handle_discuss.php" method="post">
<table border=1 width=100%>
<tr bgcolor=#cdeffc><td width=50>選取 討論區<td>討論區標題<td>討論區主旨<td width=120>種類<td>訂閱狀況
<!-- BEGIN DYNAMIC BLOCK: discuss_list -->
<tr bgcolor=DISCOLOR>
<td width=50><input type="checkbox" name="DEL_NAME" value="DIS_ID" onClick="selected=(selected||this.checked);">
<td><a href="ART_LIST" LOG_PRG>DIS_NAME</a>
<td>DIS_COMMENT
<td width=120>DIS_TYPE
<td width=80>SUB_STATUS
<!-- END DYNAMIC BLOCK: discuss_list -->
</table>
<img src="/images/arrow_ltr.gif" border=0 width="38" height="22" alt="選擇的動作" >
<input type="submit" name="submit" value="訂閱" onClick="return check(selected);">
<input type="submit" name="submit" value="停訂" onClick="return check(selected);">
</form>
<hr>
<p align="right">
<form action="search_discuss.php" method="post">
<table border=1 width=50%>
<caption>討論區文章搜尋器</caption>
<tr><td bgcolor="#edf3fa">搜尋字串<td bgcolor=#cdeffc><input type="text" name="keyword"><br>&nbsp;可輸入多個查詢字(以空白鍵隔開),&nbsp;查詢條件為&nbsp;"<I>與(AND)</I>"
<tr><td bgcolor="#edf3fa">搜尋目標<td bgcolor=#cdeffc><input type="radio" name="type" value="0" checked>文章標題
<input type="radio" name="type" value="1">作者
<input type="radio" name="type" value="2">文章內容
</table>
<input type="submit" value="開始搜尋">
<input type="reset" value="重新輸入">
</p>
</form>
</body>
</html>
