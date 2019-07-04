<html>
<head>
<title>管理者功能頁</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
</head>
<body background = "../images/img/bg.gif"><center>
<H1>系統管理</H1><BR><BR>
<table border=0>
<tr><td>
<form method=post action=./Learner_Profile/new_user.php>
<input type=submit value="新　增　教　師　">
</form></td><td>
<form method=post action=./Learner_Profile/del_user.php>
<input type=submit value="刪　除　教　師　">
</form></td></tr><tr><td>
<form method=post action=./Courses_Admin/create_course.php>
<input type=submit value="建　立　課　程　">
</form></td><td>
<form method=post action=./Courses_Admin/del_course.php>
<input type=submit value="刪　除　課　程　">
</form></td></tr><tr><td>
<form method=post action=./Courses_Admin/add_course_no.php>
<input type=submit value="　建立課程編號　">
</form></td><td>
<form method=post action=./Courses_Admin/del_course_no.php>
<input type=submit value="　刪除課程編號　">
</form></td></tr><tr><td>
<form method=post action=./Courses_Admin/create_group.php>
<input type=submit value="　建立課程類別　">
</form></td><td>
<form method=post action=./Courses_Admin/del_group.php>
<input type=submit value="　刪除課程類別　">
</form></td></tr><tr><td>
<form method=post action=./Courses_Admin/add_teach_course.php>
<input type=submit value="建立教師課程關係">
</form></td><td>
<form method=post action=./Courses_Admin/del_teach_course.php>
<input type=submit value="刪除教師課程關係">
</form></td></tr>
<tr><td>
<form method=post action=./hotline.php>
<input type=submit value="發布系統及時公告">
</form></td><td>
<form method=post action=./sysnews.php>
<input type=submit value="　發布系統公告　">
</form></td></tr>
<tr><td>
<form method=post action=./Trackin/judge.php>
<input type=submit value="課　程　評　鑑　">
</form></td><td>
<form method=post action=./Trackin/ShowTeacherListForTraceInfo.php>
<input type=submit value="　教師使用紀錄　">
</form>
</td></tr>
<tr><td>
<form method=post action=./backup.php>
<input type=submit value="　系統備份設定　">
</form></td><td>
<form>
<input type=button value="　系統效能分析　" OnClick="parent.location='/mrtg/';">
</form>
</td></tr>
<tr><td>
<form>
<input type=button value="　資 料 庫 管 理　" OnClick="parent.location='./MysqlAdmin/index.php?PHPSESSID=PHPSD';">
</form>
</td><td>
<form method=post action=./Learner_Profile/search_teacher_info.php>
<input type=submit value="  搜 尋 教 師 資 訊 ">
</form>
</td></tr>
<tr><td>
<form method=post action=./Learner_Profile/update_tch.php>
<input type=submit value="　更新教師名單　">
</form></td><td>
<form method=post action=./Learner_Profile/update_stu.php>
<input type=submit value="　更新學生名單　">
</form>
</td></tr>
<tr><td>
<form method=post action=./Courses_Admin/update_course.php>
<input type=submit value="更新本學期開課資料">
</form></td><td>
<form method=post action=./Courses_Admin/update_course_del.php>
  <input name="submit" type=submit value="清除上學期開課資料">
</form>
</td></tr>
<tr><td>
<form method=post action=./Courses_Admin/update_takecourse.php>
  <input name="submit" type=submit value="更新本學期選課名單">
</form></td><td>
<form method=post action=./Courses_Admin/update_takecourse_del.php>
  <input name="submit" type=submit value="清除上學期選課名單">
</form>
</td></tr>
</table>
</center></body></html>
