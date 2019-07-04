<?php
/*
  程式名稱:NoLoginQuery.php
  程式功能: 根據教師輸入的天數(n),查詢並顯示哪些人超過n天沒上線
*/

require 'fadmin.php';
update_status ("查詢未上線者");
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
if( $days <= 0 )
	header("Location:StudentRank1.php?PHPSESSID=$PHPSESSID");

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
		$tpl->define(array(nologin_list => "NoLoginQuery_Ch.tpl"));
	else
		$tpl->define(array(nologin_list => "NoLoginQuery_En.tpl"));
	
	$tpl->define_dynamic("row", "nologin_list");
	while($row = mysql_fetch_array($resultOBJ))
	{
		$SQL1="Select mtime From log Where user_id='".$row['a_id']."' AND event_id='2'";
		$result1 = mysql_db_query( $DB.$course_id, $SQL1);
		$row1 = mysql_fetch_array($result1);
		if(mysql_num_rows( $result1 ) != 0)
		{
			$tempDate = array(substr($row1[mtime],0,4),substr($row1[mtime],4,2),substr($row1[mtime],6,2));
			$tempTime = array(substr($row1[mtime],8,2),substr($row1[mtime],10,2),substr($row1[mtime],12,2));
			$date = implode("-",$tempDate)." ".implode(":",$tempTime);
			$timestamp = mktime($tempTime[0],$tempTime[1],$tempTime[2],$tempDate[1],$tempDate[2],$tempDate[0]);
			$daysec = 86400;
			$nologinday = (time() - $timestamp) / $daysec;
			if($nologinday >= $days)
			{
				$id[$row[a_id]]=$row[id];
				$name[$row[a_id]]=$row[name];
				$temp[$row[a_id]]=$timestamp;
				$record[$row[a_id]]=$date;
				$email[$row[a_id]]=$row[email];
			}
		}
		else
		{
			$id[$row[a_id]]=$row[id];
			$name[$row[a_id]]=$row[name];
			$temp[$row[a_id]]=0;
			$record[$row[a_id]]="尚未登入過";
			$email[$row[a_id]]=$row[email];
		}
	}
	if(isset($temp)){
		asort($temp);
		$count=0;
		$tpl->assign(DAYS, $days);
		$color = "#BFCEBD";
		for(reset($temp);$key=key($temp);next($temp))
		{
			if ( $color == "#BFCEBD" )
				$color = "#D0DFE3";
			else
				$color = "#BFCEBD";
			$tpl->assign(COLOR, $color);
			if($email[$key]!=NULL)
			{
				$mail_array[]='\''.$email[$key].'\'';
				$tpl->assign(NOTIFY, "<INPUT TYPE=CHECKBOX NAME=COUNT>");
				$tpl->assign(STUDENT_NAME, "<A HREF=mailto:".$email[$key].">".$name[$key]."</A>");
			}
			else
			{
				$tpl->assign(NOTIFY, NULL);
				$tpl->assign(STUDENT_NAME, $name[$key]);
			}
			$tpl->assign(ORDER, ++$count);
			$tpl->assign(STUDENT_ID, $id[$key]);
			$tpl->assign(RECORD, $record[$key]);
			$tpl->parse(ROWS, ".row");
		}
	}
	else{
		$tpl->assign(DAYS, $days);
		$color = "#BFCEBD";
		$tpl->assign(COLOR, $color);
		$tpl->assign(NOTIFY, NULL);
		$tpl->assign(STUDENT_NAME, NULL);
		$tpl->assign(ORDER, NULL);
		$tpl->assign(STUDENT_ID, NULL);
		$tpl->assign(RECORD, NULL);
		$tpl->parse(ROWS, ".row");
	}
}

if(count($mail_array)!=0)
{
	$mail_list=implode(",",$mail_array);
	$tpl->assign(MAIL_LIST, $mail_list);
}
else
	$tpl->assign(MAIL_LIST, NULL);


$tpl->assign(EXTENSION ,"Limit：<INPUT TYPE=TEXT MAXLENGTH=4 SIZE=4 NAME=quantity VALUE=10><BR>\n<INPUT TYPE=RADIO CHECKED NAME=order VALUE=TOP>From Top\n<INPUT TYPE=RADIO NAME=order VALUE=BUTTOM>From Buttom");
$tpl->assign(NOLOGINDAY ,$days);
$tpl->parse(BODY, "nologin_list");
$tpl->FastPrint("BODY");
?>
