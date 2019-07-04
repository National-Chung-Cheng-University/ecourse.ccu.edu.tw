<?PHP
/**
 */
require 'fadmin.php';
?>
<html>
<head>
<title>測試刪除開課</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
</head>
<body background = "/images/img/bg.gif"><center>

<?PHP
if ( isset($PHPSESSID) && session_check_admin($PHPSESSID) ) {
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$error = "資料庫連結錯誤1!!";
	}
	for($course_aid=23105;$course_aid<=23176;$course_aid++)
	{
		$Q1 = "drop database study$course_aid";
		if ( !( mysql_query( $Q1 , $link ) ) ) {
			$error = "資料庫刪除錯誤study$course_aid!!";
			echo "$error<BR>";exit;
		}
		else{
			echo "成功刪除study$course_aid<BR>";
		}
		$target = "../../".$course_aid;
		if ( is_dir ( $target ) )
			deldir ( $target );
		$target = "/backup/".$course_aid;
		if ( is_dir ( $target ) )
			deldir ( $target );
	}
	echo "<br><a href=../check_admin.php>回系統管理介面</a>";
}
?>