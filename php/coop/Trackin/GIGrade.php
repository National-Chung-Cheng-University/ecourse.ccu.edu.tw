<?php
require 'fadmin.php';
update_status ("組內自評");

if(!(isset($PHPSESSID) && session_check_teach($PHPSESSID) && check_group ( $course_id, $coopgroup, $coopcaseid ) >= 2 && $teacher != 1) )
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
if ( $action == "update" && $teacher != 1 ) {
	update();
}
show_page_d ();

function update () {
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $coopcaseid, $coopgroup, $course_id, $user_id, $version, $grade, $aid, $judge;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$message = "資料庫連結錯誤!!";
		show_page( "not_access.tpl" , $message);
		return;
	}
	while(list($key,$value) = each($grade)) {
		$Q1 = "select * from grade_".$coopcaseid." where give_id = '".GetUserAID($user_id)."' and gived_id = '".$aid[$key]."' and grade_type = '0'";
		($result = mysql_db_query( $DBC.$course_id, $Q1 )) or die (  "資料庫讀取錯誤$Q1!!" );
		if ( mysql_num_rows( $result ) != 0 ) {
			$Q1 = "update grade_".$coopcaseid." set grade = '".$grade[$key]."', judge = '".$judge[$key]."' where give_id = '".GetUserAID($user_id)."' and gived_id = '".$aid[$key]."' and grade_type='0'";
			mysql_db_query( $DBC.$course_id, $Q1 ) or die (  "資料庫更新錯誤!!" );
		}
		else {
			$Q1 = "insert into grade_".$coopcaseid." ( give_id, gived_id, grade, judge, grade_type ) values ( '".GetUserAID($user_id)."', '".$aid[$key]."', '".$grade[$key]."', '".$judge[$key]."', '0' )";
			mysql_db_query( $DBC.$course_id, $Q1 ) or die (  "資料庫加入錯誤!!" );
		}
	}

}

function show_page_d ( ) {
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $coopcaseid, $coopgroup, $course_id, $user_id, $version, $teacher, $skinnum;
	$Q1 = "Select student_id From coop_".$coopcaseid."_group Where group_num = '$coopgroup'";
	$Q4 = "Select grade, judge From grade_".$coopcaseid." Where gived_id = '".GetUserAID($user_id)."' and grade_type='0'";
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$message = "資料庫連結錯誤!!";
		show_page( "not_access.tpl" , $message);
		return;
	}
	else if ( !($resultOBJ = mysql_db_query( $DBC.$course_id, $Q1 ) ) ) {
		$message = "資料庫讀取錯誤1!!";
		show_page( "not_access.tpl" , $message);
		return;
	}
	else if ( mysql_num_rows ( $resultOBJ ) != 0 ) {
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		if ( $version == "C" ) {
			$tpl->define ( array ( body => "GIGrade.tpl") );
		}
		else {
			$tpl->define ( array ( body => "GIGrade.tpl") );
		}
		
		$tpl->define_dynamic("user_list", "body");
		$tpl->assign( SKINNUM, $skinnum);
		if ( check_group ( $course_id, $coopgroup, $coopcaseid ) >= 2 && $teacher != 1 ) {
			$tpl->assign( BOTTON , "<input type=submit value=更新> <input type=reset value=清除>" );
		}
		else {
			$tpl->assign( BOTTON, "" );
		}
		$i = 0;
		$color = "#CCCCCC";
		($resultOBJ4 = mysql_db_query( $DBC.$course_id, $Q4 ) ) or die ("資料庫讀取錯誤4!!");
		$total = 0;
		$total_grade = 0;
		while ( $row4 = mysql_fetch_array ( $resultOBJ4 ) ) {
			if ( $row4['grade'] != NULL && $row4['grade'] != "" ) {
				$total_grade += $row4['grade'];
				$total ++;
			}
		}
		if ( $total == 0 ) {
			$avg = 0;
		}else {
			$avg = $total_grade/$total;
		}
		$tpl->assign( TOTAL , $avg );
		while ( $row = mysql_fetch_array ( $resultOBJ ) ) {
			$Q2 = "Select * From user Where id = '".$row['student_id']."'";
			if ( !($resultOBJ2 = mysql_db_query( $DB, $Q2 ) ) ) {
				$message .= "-- 資料庫讀取錯誤2!!";
			}
			$row2 = mysql_fetch_array ( $resultOBJ2 );
			
			$Q3 = "Select * From grade_".$coopcaseid." Where give_id = '".GetUserAID($user_id)."' and gived_id = '".$row2['a_id']."' and grade_type='0'";
			($resultOBJ3 = mysql_db_query( $DBC.$course_id, $Q3 ) ) or die ( "資料庫讀取錯誤$Q3!!" );
			$row3 = mysql_fetch_array ( $resultOBJ3 );
			
			$i ++;
			if ( $color == "#CCCCCC" )
				$color = "#F0FFEE";
			else
				$color = "#CCCCCC";
			
			$tpl->assign( GRADE , $row3['grade'] );
			$tpl->assign( JUDGE , $row3['judge'] );

			$tpl->assign( BCOLOR , $color );
			$tpl->assign( "NO", $i);
			$tpl->assign("AID", $row2['a_id']);
			$tpl->assign("NICKN", $row2['nickname']);
			$tpl->assign("NAME", $row2['name']);
			
			$sexIndex = $row2['color'];
			if($sexIndex == "0")
			{
				if ( $version == "C" ) {
			    		$sexStr = "女";
				}
				else {
			    		$sexStr = "F";
				}
			}
			else if($sexIndex == "1")
			{
				if ( $version == "C" ) {
			    		$sexStr = "男";
				}
				else {
			    		$sexStr = "M";
				}
			}
	
			$tpl->assign("SEX", $sexStr);
			if ( $version == "C" ) {
				if($row2['color'] == 1)
					$scolor = "橘色";
				else if($row2['color'] == 2)
					$scolor = "金色";
				else if($row2['color'] == 3)
					$scolor = "藍色";
				else if($row2['color'] == 4)
					$scolor = "綠色";
				else
					$scolor = "彩虹";
			}else {
				if($row2['color'] == 1)
					$scolor = "Orange";
				else if($row2['color'] == 2)
					$scolor = "Gold";
				else if($row2['color'] == 3)
					$scolor = "Blue";
				else if($row2['color'] == 4)
					$scolor = "Green";
				else
					$scolor = "Rainbow";
			}
			$tpl->assign("SCOLOR", $scolor);
			$tpl->assign("JOB", $row2['job']);
			if ( $row2['php'] == "" || $row2['php'] == NULL ) {
				$tpl->assign("ID", $row2['id']);
			}
			else {
				$tpl->assign("ID", "<a href=".$row2['php']." target=_blank>".$row2['id']."</a>");
			}
			$tpl->assign("EMAIL", $row2['email']);
			$tpl->parse(ROW, ".user_list");
		}
		$tpl->parse(BODY,"body");
		$tpl->FastPrint("BODY");
		
	}
	else {
		if( $version=="C" )
			show_page( "not_access.tpl" ,"目前無任何組員資料");
		else
			show_page( "not_access.tpl" ,"No data now!!");
	}
}

function GetUserAID($user_id) {

	global $DB;

	$sql = "select a_id from user where id='$user_id'";
	$result = mysql_db_query($DB, $sql) or die("資料庫查詢錯誤, $sql");

	// check name field. if exists, use it as poster name.
	$row = mysql_fetch_array( $result );
	
	return $row['a_id'];
}
?>
