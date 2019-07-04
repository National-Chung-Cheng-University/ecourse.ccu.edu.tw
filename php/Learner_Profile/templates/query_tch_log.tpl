<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>教師使用紀錄查詢結果</title>
<style type="text/css">
<!--
body {
        background-image: url(../../images/img/bg.gif);
}
.style1 {color: #FFFFFF}
-->
</style>
</head>
<body>
<p align="center">搜尋依據：#SEARCH_KEYWORD#</p>
<table align="center" width="692" border="1">
  <tr>
    <td><table width="100%" border="0">
      <tr>
        <td width="19%" bgcolor="#000000"><span class="style1">身份證字號</span></td>
        <td width="21%" bgcolor="#000000"><span class="style1">科目編碼及班別</span></td>
        <td width="18%" bgcolor="#000000"><span class="style1">IP</span></td>
        <td width="28%" bgcolor="#000000"><span class="style1">時間</span></td>
        <td width="14%" bgcolor="#000000"><span class="style1">執行動作</span></td>
      </tr>
      <!-- BEGIN DYNAMIC BLOCK: record -->
      <tr>
        <td>#ID#</td>
        <td>#COURSE_ID#</td>
        <td>#IP#</td>
        <td>#TIME#</td>
        <td>#ACTION#</td>
      </tr>
      <!-- END DYNAMIC BLOCK: record -->
    </table></td>
  </tr>
</table>
<br/>
<p align="center"><a href="/php/Learner_Profile/query_tch_log_index.php">重新搜尋</a></p>
<p align="center"><a href="/php/check_admin.php">回系統管理介面</a></p>
</body>
</html>
