<?php 
require 'fadmin.php';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<title>期中問卷編輯</title>
<style type="text/css">
<!--
.style1 {
	font-size: 18px;
	font-weight: bold;
}
-->
</style>
</head>
<body>
<?php
	if(session_check_teach($PHPSESSID) != 2) {
		show_page("not_access.tpl", "你沒有權限執行此功能.<br>\nYou have no permission to perform this function.");
		exit();
	}

?>
<table border="0" align="center" cellpadding="6">
  <tr>
    <td colspan="2"><div align="center"><h3>期中問卷編輯</h3></div></td>
  </tr>
  <tr>
    <td colspan="2"><div align="center"> <a href=../check_admin.php>回系統管理介面</a> </div></td>
  </tr>
  <tr bgcolor="#FFFFCC">
	<form method=post action=./create_questionary.php>
	 <td>
  		<input name="submit" type=submit value=" 新 增 問 卷 ">
	</td></form>
    <td>&nbsp;步驟１：新增該學期期中問卷及設定題目，每學期皆需新增一次</td>
  </tr>
  <tr bgcolor="#FFFFCC">
	<form method=post action=./modify_questionary.php>
	<td>
  		<input name="submit" type=submit value=" 修 改 問 卷 ">
	</td></form>
    <td>步驟２：試填、發佈問卷(<font color=red>新增問卷後，須設定發佈日期</font>)、統計、修改名稱與屬性、 修改題目、刪除問卷</td>
  </tr>
  <tr bgcolor="#FFFFCC"><form method=post action="./run_statistics2.php">
  	<td><input name="submit" type="submit" value=" 統 整 問 卷 ">
  	</td></form>
  	<td>期中問卷填寫日結束後，統整期中問卷的結果，並將結果(Excel檔)下載。(95.12更新)
  	</td>
  </tr>
  <tr bgcolor="#FFFFCC"><!--form method=post action="./run_statistics.php">
  	<td><input name="submit" type="submit" value=" 統 整 問 卷 ">
  	</td></form-->
  	<td><input type="button" value=" 統 整 問 卷 ">(已停用)</td>
  	<td>期中問卷填寫日結束後，統整期中問卷的結果(滿意度)，並將結果(Excel檔)下載。
  	</td>
  </tr>
</table>
<table border="0" align="center" cellpadding="6">
  <tr>
    <td width="432" colspan="3">&nbsp;</td>
  </tr>
  <tr bgcolor="#FFFFCC">
    <td>A.</td>
    <form method="post" action="./onoff_questionary.php"><td>
      <input type="hidden" name="questionary" value="1">
      <input type="submit" name="open" value=" 開 啟 學 生 問 卷 " >
        </td></form>
    <td>&nbsp;開啟學生&quot;問卷調查&quot; button，此時亦關閉老師 &quot;觀看問卷統計&quot; button</td>
  </tr>
  <tr bgcolor="#FFFFCC">
    <td>B.</td>
    <form method="post" action="./onoff_questionary.php"><td>
      <input type="hidden" name="questionary" value="0">
      <input type="submit" name="close" value=" 關 閉 學 生 問 卷 " >    
        </td></form>
    <td bgcolor="#FFFFCC">關閉學生&quot;問卷調查&quot; button，此時亦開啟老師 &quot;觀看問卷統計&quot; button</td>
  </tr>
  <tr bgcolor="#FFFFCC">
    <td>C.</td>
    <form method="post" action="./onoff_questionary.php"><td>
      <input type="hidden" name="questionary" value="2">
      <input type="submit" name="close" value=" 關閉 老師學生 問卷 " >    
        </td></form>
    <td bgcolor="#FFFFCC">關閉學生&quot;問卷調查&quot; button，此時亦關閉老師 &quot;觀看問卷統計&quot; button</td>
  </tr>
  <tr>
    <td colspan="3"><div align="center">
    <?php
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $skinnum;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$error = "資料庫連結錯誤!!";
		show_page ( "not_access.tpl", $error );
	}
	if(isset($questionary))
	{
		$Q1 = "update function set que =".$questionary;
		mysql_db_query($DB, $Q1);
	}
	$Q1 = "select que from function";
	$result = mysql_db_query($DB, $Q1);
	$row = mysql_fetch_array($result);
	
	if ($row['que'] == 1 )   //此que值用來控制bar.php及bar1.tpl及bar2.tpl的顯示。
		echo "目前狀態為 A ."; 
	else if ($row['que'] == 0 ) 
		echo "目前狀態為 B .";
	else
		echo "目前狀態為 C .";
	?></div></td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
</table>
<table border="1" align="center" cellpadding="15" bordercolor="#CCFFFF">
  <tr>
    <td bgcolor="#DBFFFF">新增問卷流程：<br>
      1.新增問卷<br>
      2.編輯題目<br>
      3.試填 (若有輸入錯誤再修改)<br>
      4.發佈問卷：設定問卷起始與結束時間<br>
    5.開啟問卷button</td>
  </tr>
</table>

</body>
</html>