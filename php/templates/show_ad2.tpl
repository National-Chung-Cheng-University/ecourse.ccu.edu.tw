<html>
<head>
<title>管理者功能頁</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<style type="text/css">
<!--
.style1 {color: #003366}
.style2 {color: #330066}
-->
</style>
</head>
<body background = "../images/img/bg.gif"><center>
<H1>系統管理</H1>
依照按鈕順序來使用，即可更新為正確的資料。
<table border=0>
<tr>
  <td>step01：更新教師名單</td>
</tr>
<tr>
  <td>step02：更新學生名單</td>
</tr>
<tr>
  <td>step03：清除上學期開課資料<font color=red>(如不做可跳過)</font></td>
</tr>
<tr>
  <td>step04：更新本學期開課資料</td>
</tr>
<tr>
  <td>step05：清除上學期選課名單<font color=red>(已做過可跳過)</font></td>
</tr>
<tr>
  <td>step06：清除暫時性選課名單</td>
</tr>
<tr>
  <td>step07：更新暫時性選課名單</td>
</tr>
<tr>
  <td>step08：更新本學期選課名單</td>
</tr>
<tr>
  <td>step09：清除退選的學生名單</td>
</tr>
<tr>
  <td>step10：清除未開成功之課程</td>
</tr>
</table>
<hr>
<table border=1 bordercolor="#006666">
<tr><td><div align="center" class="style1">系統功能</div></td><td><div align="center" class="style2">使用說明</div></td>
<tr><td>
<form method=post action="./Learner_Profile/update_tch.php">
<input type=submit value="　更 新 教 師 名 單　">
</form></td>
<td> 當如果有新教師進來學校或是教師離開學校的話請按此按鈕同步教師名單。
<tr><td>
<form method=post action="./Learner_Profile/update_stu.php">
  <input name="submit2" type=submit value="　更 新 學 生 名 單　">
</form>
</td>
<td> 當學生名單有異動(如：入、休、退學等)時，請按此按鈕同步學生名單。
<tr><td>
<form method=post action="./Courses_Admin/update_course_del.php">
  <input name="submit" type=submit value="清除上學期開課資料" disabled>
</form>
</td>
<td> 因為會有過渡時期，所以請再確定該學期結束之後再使用此按鈕<font color=red>(如不清除則跳過此步驟)</font>。
<tr><td>
<form method=post action="./Courses_Admin/update_course.php">
<input type=submit value="更新本學期開課資料">
</form></td>
<td> 當教師的開課資料設好之後，開放學生選課之前使用。
<tr><td>
<form method=post action="./Courses_Admin/update_takecourse_del.php">
  <input name="submit" type=submit value="清除上學期選課名單">
</form>
</td>
<td>清除學生上學期的選課資料。
<tr><td>
<form method="post" action="./Courses_Admin/del_temp_takecourse.php">
  <input name="submit" type="submit" value="清除暫時性選課名單">
</form></td>
<td> 因為在選課階段中可能會更新好幾次暫時性名單，所以在每次更新前先清除，確保資料正確性。</td>
<tr><td>
<form method=post action="./Courses_Admin/update_temptakecourse.php">
  <input name="submit" type=submit value="更新暫時性選課名單">
</form>
</td>
<td> 此名單為<font color=red>暫時性</font>的學生選課名單，等待選課篩選結束後，再執行<strong>更新本學期選課名單</strong>。
<tr><td>
<form method=post action="./Courses_Admin/update_takecourse.php">
  <input name="submit" type=submit value="更新本學期選課名單">
</form></td>
<td> 此為<font color=red>正確</font>之選課名單，可以在每個篩選階段完後按。
<tr><td>
<form method=post action="./Courses_Admin/del_dropped_stu.php">
  <input name="submit" type=submit value="清除退選的學生名單">
</form></td>
<td> 將原本有選課但後來退選的學生名單清除，等更新完<font color=red>正確</font>選課名單後使用。</td></tr>
<tr><td valign="middle">
<form method=post action="./Courses_Admin/del_canceled_teachcourse.php">
  <input name="submit" type=submit value="清除未開成功之課程">
</form></td>
<td> 將<font color=red>未開成功</font>之課程清除。</td>
</tr>
<tr><td>
<form method=post action="./Courses_Admin/deadline_setup.php">
  <input name="submit" type=submit value="設定成績送交截止日">
</form>
</td>
<td> 設定教師上傳成績的時間。
<tr><td>
<form method=post action="./Learner_Profile/search_teacher_info.php">
<input type=submit value="　搜 尋 教 師 資 訊　">
</form>
</td>
<td> 查尋教師的帳號、密碼。
<tr><td>
<form method=post action="./Courses_Admin/no_intro.php">
  <input name="submit" type=submit value="已上傳課程大綱列表">
</form>
</td>
<td> 找出有上傳課程大網的課程。
<tr><td>
<form method=post action="./sysnews.php">
<input type=submit value="　發 布 系 統 公 告　">
</form>
</td>
<td> 欲發佈新的公告時使用。
<tr><td>
<form method=post action="./Courses_Admin/templates/select.php">
  <input name="submit" type=submit value="　瀏 覽 次 數 統 計　">
</form>
</td>
<td>查看該學期各課程、各系所、各學院學生瀏覽教材次數。</td>
<tr><td>
<form method=post action="./mid_questionary/onoff_questionary.php">
  <input name="submit" type=submit value="　期 中 問 卷 編 輯　">
</form>
</td>
<td>新增、編輯、開啟或關閉該學期期中問卷。</td>
<tr><td><form method=post action="./Courses_Admin/backup_intro.php">
  <input name="submit" type=submit value="備份當學期課程大綱">
</form>
</td>
<td>把當學期的課程大綱被份，在<font color=red>更換學期之前</font>需要備份。</td>
<tr><td>
<form method=post action="./Courses_Admin/set_semester.php">
<input type=submit value="設 定 當 下 學年 學期">
</form>
</td>
<td>更改目前所屬的學期年度之學年及學期值</td>
</table>
<br>
<a href="./Learner_Profile/chang_pass_admin.php">修改密碼</a>
</center></body></html>
