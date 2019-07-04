<?php
/**************************/
/*檔名:TSModifyFrame1.php*/
/*說明:學生資料修改*/
/*相關檔案:*/
/*TSModifyFrame2.php*/
/*************************/
require 'fadmin.php';
update_status ("修改學生名單");


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
if( isset($id) && $id != NULL ) {
	for(reset($id),reset($name);$key=key($id);next($id),next($name))
	{
		if($id[$key]==NULL || $name[$key]==NULL)
		{
			if($version=="C")
				$message = "姓名或學號的欄位有一筆以上是空的";
			else
				$message = "More than one field is empty!!";
			show_page_d ( $message );
			exit;
		}
	}
	modify_stu ();
}
else
	show_page_d ( );

function modify_stu () {
	global $id, $name, $version, $course_id;

	for(reset($id),reset($name);$key=key($id);next($id),next($name))
	{
		global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$message = "資料庫連結錯誤!!";
			show_page_d ( $message );
			return;
		}

		$Q1 = "Select name, id from user where a_id='$key'";
		if ( $resultOBJ = mysql_db_query( $DB, $Q1 ) ) {
			$Q2 = "Update user Set name='$name[$key]',id='$id[$key]' Where a_id=$key";
			if( !$result = mysql_db_query( $DB, $Q2 ) )
			{
				if($version=="C")
					$message = "學號$id[$key]重複，該筆資料已存在";
				else
					$message = "ID overlaps the record exists";
				show_page_d ( $message );
				exit;
			}
			$row = mysql_fetch_array ( $resultOBJ );
			if ( $row['id'] != $id[$key] ) {
				$Q3 = "update discuss_subscribe set user_id = '$id[$key]' Where user_id = '".$row['id']."'";
				$result3 = mysql_db_query( $DB.$course_id, $Q3 );
				$Q4 = "select a_id from coop";
				$result4 = mysql_db_query( $DBC.$course_id, $Q4 ) or die ( "資料庫讀取錯誤" );
				while ( $row4 = mysql_fetch_array ( $result4 ) ) {
					$Q5 = "update discuss_".$row4['a_id']."_subscribe set user_id = '$id[$key]' Where user_id = '".$row['id']."'";
					$result5 = mysql_db_query( $DBC.$course_id, $Q5 );
					$Q6 = "update coop_".$row4['a_id']."_group set student_id = '$id[$key]' Where student_id = '".$row['id']."'";
					$result6 = mysql_db_query( $DBC.$course_id, $Q6 );
				}
				if ( $resultOBJ2 = mysql_db_query( $DB.$course_id, "Select a_id, name From homework") ) {
					while($row2 = mysql_fetch_array ( $resultOBJ2 )) {
						$target1 = "../../$course_id/homework/".$row2['a_id']."/".$row['id'];
						$target2 = "../../$course_id/homework/".$row2['a_id']."/".$id[$key];
						if ( is_dir($target1) )
							rename ( $target1, $target2 );
						if ($resultOBJ3 = mysql_db_query( $DB.$course_id, "Select upload, work From handin_homework Where homework_id=".$row2['a_id']." and student_id='$key'" ) )
							$row3 = mysql_fetch_array ( $resultOBJ3 );
						if ( $row3['upload'] == 1 ) {
							$target5 = ereg_replace("<a href=/$course_id/homework/".$row2['a_id']."/".$row['id']."/".$row['id'].".(.*)>".$row['id']."..*</a>","<a href=/$course_id/homework/".$row2['a_id']."/".$id[$key]."/".$id[$key].".\\1>".$id[$key].".\\1</a>",$row3['work']);
							$target3 = ereg_replace("<a href=/$course_id/homework/".$row2['a_id']."/".$row['id']."/".$row['id'].".(.*)>".$row['id']."..*</a>","../../$course_id/homework/".$row2['a_id']."/".$id[$key]."/".$row['id'].".\\1",$row3['work']);
							$target4 = ereg_replace("<a href=/$course_id/homework/".$row2['a_id']."/".$row['id']."/".$row['id'].".(.*)>".$row['id']."..*</a>","../../$course_id/homework/".$row2['a_id']."/".$id[$key]."/".$id[$key].".\\1",$row3['work']);
							if ( is_file( $target3 ) )
								rename ( $target3, $target4 );
							mysql_db_query( $DB.$course_id, "update handin_homework set work='$target5' Where homework_id=".$row2['a_id']." and student_id='$key'" );
						}
		
					}
				}			
			}
		}
	}
	
	include("Generate_studinfo.php");
	if($version=="C")
		$message = "資料已更新";
	else
		$message = "Data updated!!";
	show_page_d ( $message );
}

function show_page_d ( $message = "" ) {
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $version, $course_id, $skinnum, $course_year, $course_term;
	$Q1 = "Select student_id From take_course Where course_id='$course_id' and credit = '1' and year='$course_year' and term = '$course_term' Order By student_id ASC";
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		echo ( "資料庫連結錯誤!!" );
		return;
	}
	if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
		echo ( "資料庫讀取錯誤!!" );
		return;
	}
	else
	{
		if( mysql_num_rows( $result ) == 0 )
		{
			if( $version=="C" )
				show_page( "not_access.tpl" ,"目前無任何學生資料");
			else
				show_page( "not_access.tpl" ,"No data now!!");
		}
		else
		{
			include("class.FastTemplate.php3");
			$tpl = new FastTemplate("./templates");
			if($version=="C")
				$tpl->define(array(modify_list => "TSModifyFrame1_Ch.tpl"));
			else
				$tpl->define(array(modify_list => "TSModifyFrame1_En.tpl"));
			$tpl->define_dynamic("row", "modify_list");
			$tpl->assign( SKINNUM , $skinnum );
			$color == "#F0FFEE";
			while ( $row = mysql_fetch_array($result) )
			{
				if ( $color == "#F0FFEE" )
					$color = "#E6FFFC";
				else
					$color = "#F0FFEE";
				$tpl->assign( COLOR , $color );
				$Q2 = "Select * From user Where a_id='".$row['student_id']."'";
				if ( !($result1 = mysql_db_query( $DB, $Q2 ) ) ) {
					echo ( "資料庫讀取錯誤!!" );
					return;
				}
				$row1 = mysql_fetch_array($result1);
				$tpl->assign(A_ID, $row1[0]);
				$tpl->assign(STUDENT_NAME, $row1[6]);
				$tpl->assign(STUDENT_ID, $row1[1]);
				$tpl->parse(ROWS, ".row");
			}
			$tpl->assign(MESSAGE, $message);
			$tpl->parse(BODY, "modify_list");
			$tpl->FastPrint("BODY");
		}
	}
}
?>
