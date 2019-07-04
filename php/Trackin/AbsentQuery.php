<?php
/*
  devon 2006-03-20
  程式名稱:AbsentQuery.php
  程式功能: 根據教師輸入的次數(n),查詢並顯示哪些人超過n次缺席記錄
*/

require 'fadmin.php';
update_status ("查詢缺席者");
if(!(isset($PHPSESSID) && $check = session_check_teach($PHPSESSID)) )
{
	show_page( "not_access.tpl" ,"權限錯誤");
	exit;
}
if($check != 2)
{
	if( $version=="C" ) {
		show_page( "not_access.tpl" ,"你沒有權限使用此功能");
		exit;
	}
	else {
		show_page( "not_access.tpl" ,"You have No Permission!!");
		exit;
	}
}
if( $times <= 0 )
	header("Location:RollBook.php?PHPSESSID=$PHPSESSID");

global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_year, $course_term;
if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
	echo ( "資料庫連結錯誤!!" );
	return;
}

$SQL = "Select u.* From user u,take_course tc Where u.a_id=tc.student_id AND tc.course_id='$course_id' and tc.credit ='1' and tc.year='$course_year' and tc.term = '$course_term' Order By u.id";
$resultOBJ = mysql_db_query( $DB, $SQL);
if( mysql_num_rows( $resultOBJ ) == 0 )
{
	if( $version=="C" )
		show_page( "not_access.tpl" ,"此課程尚未有任何學生!");
	else
		show_page( "not_access.tpl" ,"There is no Student in this Class!!");
}
else
{
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate("./templates");
	if($version=="C")
		$tpl->define(array(nologin_list => "AbsentQuery_Ch.tpl"));
	else
		$tpl->define(array(nologin_list => "AbsentQuery_En.tpl"));
	
	$tpl->define_dynamic("row", "nologin_list");
	$mail_list = "";
	$count = 0;
	while($row = mysql_fetch_array($resultOBJ))
	{
		$SQL1 = "select count(*) as sum from roll_book where user_id='".$row['a_id']."' and state = '1'";
		$result1 = mysql_db_query( $DB.$course_id, $SQL1);
		$row1 = mysql_fetch_array($result1);
		if($row1['sum'] >= $times)
		{
			$tpl->assign(NOTIFY, "<INPUT TYPE=CHECKBOX NAME=COUNT>");
			$tpl->assign(STUDENT_ID, $row[id]);
			$tpl->assign(STUDENT_NAME, $row[name]);
			$tpl->assign(RECORD, $row1[sum]);
			$tpl->parse(ROWS, ".row");
			if($row[email]!=NULL || $row[email]!="")
				$mail_list = $mail_list.$row[email].",";
			$count++;
		}
	}
	if($count==0)
	{
		$tpl->assign(NOTIFY, "");
		$tpl->assign(STUDENT_ID, "");
		$tpl->assign(STUDENT_NAME, "");
		$tpl->assign(RECORD, "");
		$tpl->assign(MAIL, "<font color=red>此課程沒有超過".$times."次缺席的學生</font>");
		$tpl->parse(ROWS, ".row");
	}
	else
		$tpl->assign(MAIL, "<A HREF=mailto:?subject=Notice!!&bcc=$mail_list>送出通知信件</A>");
	$tpl->assign(TIMES, $times);
}

$tpl->assign(EXTENSION ,"Limit：<INPUT TYPE=TEXT MAXLENGTH=4 SIZE=4 NAME=quantity VALUE=10><BR>\n<INPUT TYPE=RADIO CHECKED NAME=order VALUE=TOP>From Top\n<INPUT TYPE=RADIO NAME=order VALUE=BUTTOM>From Buttom");
$tpl->assign(ABSENT ,$times);
$tpl->parse(BODY, "nologin_list");
$tpl->FastPrint("BODY");
?>
