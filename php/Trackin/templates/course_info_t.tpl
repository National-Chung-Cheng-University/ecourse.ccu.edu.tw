<HTML>
<HEAD>
<TITLE></TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=big5">
<script language="JavaScript">

msgwin('資料處理中, 請稍候..');
parent.target.window.location='../news/news.php?PHPSESSID=PHPID';

function msgwin(message) {
  msg=window.open('','','toolbar=no,directories=no,menubar=no,width=300,height=30');
  msg.document.write('<center><h4>'+ message +'</h4></center>');
}

function cwin() {
  msg.close();
}
//-->
</script>
</HEAD>
<BODY background=/images/img/bg.gif>
<div align="center">
<table border="1" width="100%">
<tr>
<td width="20%" align="center">CNAME</td>
<td width="8%" align="center"><form method=get action=../news/news.php target=target><input type=submit value=公告></form></td>
<td width="11%" align="center"><form method=get action=../Courses_Admin/show_sched.php target=target><input type=submit value=課程安排></form></td>
<td width="8%" align="center"><form method=get action=../Testing_Assessment/check_allwork.php target=target><input type=submit value=作業></form></td>
<td width="8%" align="center"><form method=get action=../Testing_Assessment/modify_test.php target=target><input type=submit value=測驗></form></td>
<td width="15%" align="center"><form method=get action=../discuss/dis_list.php target=target><input type=submit value=課程討論區></form></td>
<td width="15%" align="center"><form method=get action=./CompleteUsageList.php target=target><input type=submit value=學生記錄 onClick="msgwin('資料處理中, 請稍候..')"></form></td>
<td width="15%" align="center" rowspan=2>
<a href=./ChapterRank.php target=target>教材瀏覽記錄</a><br>
<a href=../textbook/material.php target=target>課程教材</a><br>
<a href=../on_line/on_line.php target=target>隨選視訊</a><br>
<a href=./judge.php target="_top">回上一頁</a></td>
</tr>
<tr>
<td width="20%" align="center">老師：UNAME</td>
<td width="8%" align="center">NEWS 篇</td>
<td width="11%" align="center">SCHED</td>
<td width="8%" align="center">WORK 次</td>
<td width="8%" align="center">TEST 次</td>
<td width="15%" align="center">BOARD 個(共 PAGE 篇)</td>
<td width="15%" align="center">學生USER人,共登入LOGIN次<br>使用HOUR小時MINU分鐘</td>
</tr>
</table>
<br/>
<table border="1" width="100%">
  <tr>
    <td width="4%" align="center">討論板</td>
    <td width="6%" align="center">發表文章總篇數</td>
    <td width="6%" align="center">教師發表次數</td>
    <td width="5%" align="center">教師回覆次數</td>
    <td width="6%" align="center">學生發表次數</td>
    <td width="7%" align="center">學生回覆次數</td>
    <td width="11%" align="center">學生平均每週發言次數</td>
    <td width="9%" align="center">平均每週發表議題個數</td>
    <td width="12%" align="center">教師或助教於二天內回覆比率</td>
    <td width="10%" align="center">教師或助教於一週內回覆比率</td>
    <td width="13%" align="center">教師或助教於一週後才回覆比率</td>
    <td width="11%" align="center">平均回覆時間</td>
  </tr>
  <tr>
    <td width="4%" align="center">#!STAT1!# 個</td>
    <td width="6%" align="center">#!STAT2!# 篇</td>
    <td width="6%" align="center">#!STAT3!# 次</td>
    <td width="5%" align="center">#!STAT4!# 次</td>
    <td width="6%" align="center">#!STAT5!# 次</td>
    <td width="7%" align="center">#!STAT6!# 次</td>
    <td width="11%" align="center">#!STAT7!# 次</td>
    <td width="9%" align="center">#!STAT8!# 篇</td>
    <td width="12%" align="center">#!STAT9!# %</td>
    <td width="10%" align="center">#!STAT10!# %</td>
    <td width="13%" align="center">#!STAT11!# %</td>
    <td width="11%" align="center">#!STAT12!# 天</td>
  </tr>
</table>
</div>
</body></html>
