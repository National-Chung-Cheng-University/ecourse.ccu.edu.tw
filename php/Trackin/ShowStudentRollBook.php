<?php
require 'fadmin.php';
update_status ("學生完整使用記錄");

if( !(isset($PHPSESSID) && ($check = session_check_teach($PHPSESSID)) ) && !(session_is_registered("admin") && $admin == 1) )
{
	show_page( "not_access.tpl" ,"權限錯誤");
	exit;
}



global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $skinnum;
$Q1 = "Select a_id,name,nickname From user where id = '$user_id'";


if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$error = "資料庫連結錯誤!!";
		show_page ( "not_access.tpl", $error );
		exit;
}else if ( !($result = mysql_db_query( $DB, $Q1  )) ) {
		$error = "資料庫讀取錯誤!!";
		show_page ( "not_access.tpl", $error );
		exit;
}else if ( mysql_num_rows($result) == 0 ) {
		$error = "使用者不存在!!";
		show_page ( "not_access.tpl", $error );
		exit;
}
else if ( !($row = mysql_fetch_array($result)) ) {
		$error = "資料讀取錯誤!!";
		show_page ( "not_access.tpl", $error );
		exit;
}
else
{
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate("./templates");
		
		$tpl->define(array(student_list => "ShowStudentRollBook.tpl"));

		$tpl->define_dynamic("row", "student_list");
		$tpl->assign( SKINNUM , $skinnum );
		
		$color = "#000066";
		$tpl->assign( COLOR , $color );
		
		if ( $row['name'] != "" ) 
			$tpl->assign ( STUDENT_NAME, $row['name'] );
		else if ( $row['nickname'] != "" )
			$tpl->assign ( STUDENT_NAME, $row['nickname'] );
		else
			$tpl->assign ( STUDENT_NAME, "N/A" );
			
		$tpl->assign ( STUDENT_ID, $user_id );
		
		if ( $version == "C" ) {
			$tpl->assign( TID, "帳號" );
			$tpl->assign( TNAME, "姓名" );
		}
		else {
			$tpl->assign( TID, "ID" );
			$tpl->assign( TNAME, "Name" );
		}
		
		$user_a_id = $row['a_id'];
		$Q2 = "Select roll_date, state, note  From roll_book where user_id = '$user_a_id' Order By roll_id ASC";		 	
		$result2 = mysql_db_query( $DB.$course_id, $Q2 );
		
			
			$count = array(0,0,0,0,0,0);
			while ( $row2 = mysql_fetch_array($result2) )			
			{		
					
					if ( $color == "#F0FFEE" )
						$color = "#E6FFFC";
					else
						$color = "#F0FFEE";
					$tpl->assign( COLOR , $color );
			        $tpl->assign( DATE , $row2['roll_date'] );
							
					switch ($row2['state'] )
					{
							case '0':
								$count[0]++;
								$tpl->assign( RECORD, "出席" );
								break;
							case '1':
								$count[1]++;
								$tpl->assign( RECORD, "缺席" );
								break;
							case '2':
								$count[2]++;
								$tpl->assign( RECORD, "遲到" );
								break;
							case '3':
								$count[3]++;
								$tpl->assign( RECORD, "早退" );		
								break;
							case '4':
								$count[4]++;
								$tpl->assign( RECORD, "請假" );
								break;
							case '5':
								$count[5]++;
								$tpl->assign( RECORD, $row2['note'] );
								break;
										
					}
					$tpl->parse(ROWS, ".row");
										
			}
			
			$count_html="";
			
			if($count[0]>0)
			{
				$count_html = $count_html."出席:".$count[0]." ";
			}
			if($count[1]>0)
			{
				$count_html = $count_html."缺席:".$count[1]." ";
			}			
			if($count[2]>0)
			{
				$count_html = $count_html."遲到:".$count[2]." ";
			}			
			if($count[3]>0)
			{
				$count_html = $count_html."早退:".$count[3]." ";
			}			
			if($count[4]>0)
			{
				$count_html = $count_html."請假:".$count[4]." ";
			}			
			if($count[5]>0)
			{
				$count_html = $count_html."其他:".$count[5]." ";
			}		
			
			$color = "#000066";	
					
			$tpl->assign( COLOR , $color );
			
			if ( $version == "C" )			
				$tpl->assign( DATE, "<b><font color=#FFFFFF>統計</font></b>" );
			else 
				$tpl->assign( DATE, "<b><font color=#FFFFFF>Conut</font></b>" );			
			
			$tpl->assign( RECORD, "<b><font color=#FFFFFF>$count_html</font></b>" );
			$tpl->parse(ROWS, ".row");
		
		
		$tpl->assign(NOCREDIT, $nocredit);
		$tpl->assign(CID.$nocredit, "selected");
		$tpl->parse(BODY, "student_list");
		$tpl->FastPrint("BODY");
	
}
?>
