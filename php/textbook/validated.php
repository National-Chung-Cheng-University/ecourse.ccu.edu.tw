<?
// change textbook / homepage access abliity for Guest user.
// param : $validated (editor_root, value in DB now.)
// 2002/06/01

	require 'fadmin.php';

	// 檢查使用權限.
	if(session_check_teach($PHPSESSID) != 2) {
		show_page("not_access.tpl", "You have no permission to perform this function.");
		exit();
	}

	mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("資料庫連結錯誤");

	// if validated == 0/2 (public), change it to 1/3 (private), and vice verse.
	if($validated % 2 == 0) {
		$validated++;

		// delete guest user from this course.
		$sql = "delete from take_course where course_id=".$course_id." and year='$course_year' and term ='$course_term' and credit='0'";
		mysql_db_query($DB, $sql) or die("資料庫查詢錯誤, $sql");
	}
	else {
		$validated--;
	}

	$sql = "update course set validated=$validated where a_id=$course_id";
	mysql_db_query($DB, $sql) or die("資料庫查詢錯誤, $sql");

	if(mysql_affected_rows() == 1) {
		// query succeed.
		$errno = 11;
	}
	else {
		// falied.
		$errno = 12;
	}

	header("Location: editor_main.php?errno=$errno&PHPSESSID=$PHPSESSID");
?>