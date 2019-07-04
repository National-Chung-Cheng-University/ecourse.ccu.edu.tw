<?php
require 'fadmin.php';
update_status ("管理專案");

if( isset($PHPSESSID) && (session_check_teach($PHPSESSID) >= 2) )
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}
	if($action == "modifynr")
	{
		$Q1 = "SELECT name, percentage, private FROM coop WHERE a_id='$case_id'";
		if ( !($result = mysql_db_query( $DBC.$course_id, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
		}
		$rows = mysql_fetch_array($result);
		$case_name = $rows[0];
		$case_ratio = $rows[1];
		$case_pri = $rows[2];
		modifynr ();
	}
	else if($action == "modifycasenr")
	{
		$row = CheckError();
		if($row == "null")
		{
			if($version == "C")
				$message = "請輸入專案名稱和比例!";
			else
				$message = "Please input case name or ratio!";
			modifynr ();
		}
		elseif($row == "errorvalue")
		{
			if($version == "C")
				$message = "比例須介於0~100之間";
			else
				$message = "Please input ratio between 0 and 100!";
			modifynr ();
		}
		elseif($row == "exist")
		{
			if($version == "C")
				$message = $case_name."已存在,請更換專案名稱!";
			else
				$message = "This case name $case_name exist, and please change the case name!";
			modifynr ();
		}
		else
		{
			if ( $case_type == "self_case" ) {
				if ( $case_private == "pri_case" )
					$Q1 = "UPDATE coop SET name='$case_name',percentage='0', private='1' WHERE a_id='$case_id'";
				else
					$Q1 = "UPDATE coop SET name='$case_name',percentage='0', private='0' WHERE a_id='$case_id'";
			}else {
				if ( $case_private == "pri_case" )
					$Q1 = "UPDATE coop SET name='$case_name',percentage='$case_ratio', private='1' WHERE a_id='$case_id'";
				else
					$Q1 = "UPDATE coop SET name='$case_name',percentage='$case_ratio', private='0' WHERE a_id='$case_id'";
			}
			if ( !($result = mysql_db_query( $DBC.$course_id, $Q1 ) ) ) {
				show_page( "not_access.tpl" ,"資料庫寫入錯誤!!" );
			}
			if($version == "C")
				$message = "專案名稱和比例修改成功!";
			else
				$message = "Update Successfully !";
			show_page_d ();
		}
	}
	else if($action == "showgrade")
		showgrade();
	elseif($action == "edittopic")
	{
		$Q1 = "SELECT percentage, topic, name FROM coop WHERE a_id='$case_id'";
		if ( !($result = mysql_db_query( $DBC.$course_id, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
		}
		$rows = mysql_fetch_array( $result );
		$case_name = $rows["name"];
		$case_topic = $rows["topic"];
		$case_ratio = $rows["percentage"];
		$caseid = $case_id;
		show_content ( );
	}
	elseif($action == "updatetopic")
	{
		if( $case_topic == "" )
		{
			if($version == "C")
				$message = "未輸入主題!";
			else 
				$message = "Topic is Null!";
			show_content( );
		}
		else
		{
			$Q1 = "UPDATE coop SET topic='$case_topic' WHERE a_id='$case_id'";
			if ( !($result = mysql_db_query( $DBC.$course_id, $Q1 ) ) ) {
				$message = "資料庫寫入錯誤!!";
			}
			else
				if($version == "C")
					$message = "題目更新完成!!";
				else 
					$message = "Question updated!";
			show_page_d( );
		}
	}
	else if($action == "updateg") {
		if ( $grade == "" ) {
			$grade = "-1";
		}
		$Q1 = "update take_coop set grade = '$grade' where case_id = '$case_id' and student_id = '$sid'";
		if ( !($result = mysql_db_query( $DBC.$course_id, $Q1 ) ) ) {
			echo ("資料庫更新錯誤!!");
			exit;
		} 
		showgrade();
	}
	else if($action == "deletecase")
	{
		$Q0 = "Select name FROM coop WHERE a_id='$case_id'";
		if ( !($result = mysql_db_query( $DBC.$course_id, $Q0 ) ) ) {
			$message ="資料庫讀取錯誤!!";
		}
		$rows = mysql_fetch_array($result);
		$Q1 = "DELETE FROM coop WHERE a_id='$case_id'";
		if ( !($result1 = mysql_db_query( $DBC.$course_id, $Q1 ) ) ) {
			$message .="資料庫刪除錯誤!!";
		}
		$Q2 = "drop table coop_".$case_id."_group";
		if ( !($result2 = mysql_db_query( $DBC.$course_id, $Q2 ) ) ) {
			$message .="資料庫刪除錯誤!!";
		}
		$Q3 = "DELETE FROM take_coop WHERE case_id='$case_id'";
		if ( !($result3 = mysql_db_query( $DBC.$course_id, $Q3 ) ) ) {
			$message .="資料庫刪除錯誤!!";
			show_page_d();
			exit;
		}
		$Q4 = "Select a_id FROM discuss_".$case_id."_info";
		if ( !($result4 = mysql_db_query( $DBC.$course_id, $Q4 ) ) ) {
			$message ="資料庫讀取錯誤!!";
		}
		while ( $rows4 = mysql_fetch_array($result4) ) {
			$Q5 = "drop table discuss_".$case_id."_".$row4['a_id'];
			mysql_db_query( $DBC.$course_id, $Q5 ) or die ("$Q5");
		}
		$Q5 = "drop table discuss_".$case_id."_info";
		if ( !($result5 = mysql_db_query( $DBC.$course_id, $Q5 ) ) ) {
			$message .="資料庫刪除錯誤!!";
			show_page_d();
			exit;
		}
		$Q6 = "drop table discuss_".$case_id."_subscribe";
		if ( !($result6 = mysql_db_query( $DBC.$course_id, $Q6 ) ) ) {
			$message .="資料庫刪除錯誤!!";
			show_page_d();
			exit;
		}
		$Q7 = "drop table info_".$case_id;
		if ( !($result7 = mysql_db_query( $DBC.$course_id, $Q7 ) ) ) {
			$message .="資料庫刪除錯誤!!";
			show_page_d();
			exit;
		}
		$Q8 = "drop table log_".$case_id;
		if ( !($result8 = mysql_db_query( $DBC.$course_id, $Q8 ) ) ) {
			$message .="資料庫刪除錯誤!!";
			show_page_d();
			exit;
		}
		$Q9 = "drop table news_".$case_id;
		if ( !($result9 = mysql_db_query( $DBC.$course_id, $Q9 ) ) ) {
			$message .="資料庫刪除錯誤!!";
			show_page_d();
			exit;
		}
		$Q10 = "drop table guestbook_".$case_id;
		if ( !($result10 = mysql_db_query( $DBC.$course_id, $Q10 ) ) ) {
			$message .="資料庫刪除錯誤!!";
			show_page_d();
			exit;
		}
		$Q11 = "drop table schedule_".$case_id;
		if ( !($result11 = mysql_db_query( $DBC.$course_id, $Q11 ) ) ) {
			$message .="資料庫刪除錯誤!!";
			show_page_d();
			exit;
		}
		$Q12 = "drop table share_".$case_id;
		if ( !($result12 = mysql_db_query( $DBC.$course_id, $Q12 ) ) ) {
			$message .="資料庫刪除錯誤!!";
			show_page_d();
			exit;
		}
		$Q13 = "drop table share_group_".$case_id;
		if ( !($result13 = mysql_db_query( $DBC.$course_id, $Q13 ) ) ) {
			$message .="資料庫刪除錯誤!!";
			show_page_d();
			exit;
		}
		$Q14 = "drop table grade_".$case_id;
		if ( !($result14 = mysql_db_query( $DBC.$course_id, $Q14 ) ) ) {
			$message .="資料庫刪除錯誤!!";
			show_page_d();
			exit;
		}
		$Q15 = "drop table note_".$case_id;
		if ( !($result15 = mysql_db_query( $DBC.$course_id, $Q15 ) ) ) {
			$message .="資料庫刪除錯誤!!";
			show_page_d();
			exit;
		}
		
		if ( is_dir ( "../../$course_id/coop/$case_id" ) ) {
			deldir( "../../$course_id/coop/$case_id/" );
		}
		if ( $version == "C" )
			$message = $rows['name']." 刪除完成!";
		else
			$message = $rows['name']." Delete Complete";
		show_page_d();
	}
	else if($action == "pubcase")
		pubcase();
	else if($action == "group")
		group();
	else if($action == "modifypubcase") {
		if ( $pub == 1 ) {
			$beg_time=$sel_year.$sel_month.$sel_day.$sel_hour.$sel_minute."00";
			$end_time=$ed_year.$ed_month.$ed_day.date("His");
			$check_range = timecount($ed_year, $ed_month, $ed_day, date(H), date(i), $sel_year, $sel_month, $sel_day, $sel_hour, $sel_minute);
		}
		else {
			$beg_time=$sel_year.$sel_month.$sel_day.$sel_hour.$sel_minute."00";
			$year=(int) $sel_year;
			$month=(int) $sel_month;
			$day=(int) $sel_day;
			$hour=(int) $sel_hour;
			$minute=(int) $sel_minute;
			switch($range)
			{
				case 1: $hour=$hour + 1;
					break;
				case 2: $hour=$hour + 2;
					break;
				case 4: $hour=$hour + 4;
					break;
				case 8: $hour=$hour + 8;
					break;
			}
			if($hour > 23)
			{
				$hour=$hour - 24;
				$day++;
			}
			if($day > 31)
			{
				$day=$day - 31;
				$month++;
			}
			if($month > 12)
			{
				$month=$month - 12;
				$year++;
			}
			if($minute < 10)
				$minute="0".$minute;
			if($hour < 10)
				$hour="0".$hour;
			if($day < 10)
				$day="0".$day;
			if($month < 10)
				$month="0".$month;
			$second = "00";
			$end_time=$year.$month.$day.$hour.$minute.$second;
		}

		if ( !isset($range) && $sure != "取消發佈" && $sure != "Not Public" && $pub != "1") {		
			if ( $version == "C" )
				$message = "請選擇時間間隔!!";
			else
				$message = "Please Choise Rang!!!";
		}	
		else {
			if ( $sure == "取消發佈" || $sure == "Not Public" ) {
				$now=date("YmdHis");
				$Q1 = "UPDATE coop SET beg_time='$now',end_time='00000000000000',public='0' WHERE a_id='$case_id'";
			}
			else if($pub == 1)
			{
				if ( $check_range < 0 ) {
					if ( $version == "C" )
						$message = "設定錯誤!!";
					else
						$message = "Setup failure!!!";
					pubcase();
					exit;
				}	
				$Q1 = "UPDATE coop SET beg_time='$beg_time',end_time='$end_time',public='1' WHERE a_id='$case_id'";
			}
			else {
				$Q1 = "UPDATE coop SET beg_time='$beg_time',end_time='$end_time',public='3' WHERE a_id='$case_id'";
			}
			if ( !($result = mysql_db_query( $DBC.$course_id, $Q1 ) ) ) {
				show_page( "not_access.tpl" ,"資料庫更新錯誤!!" );
			}
			if ( $version == "C" )
				$message = "設定完成!!";
			else
				$message = "Setup Complete!!!";
		}
		pubcase();
	}
	else if(isset($group_num) && $action == "update") {
		for($i=0;$i<sizeof($group_num);$i++) {
			if ( !is_dir ( "../../$course_id/coop/$case_id/$group_num[$i]" ) ) {
				mkdir( "../../$course_id/coop/$case_id/$group_num[$i]", 0771 );
				chmod( "../../$course_id/coop/$case_id/$group_num[$i]", 0771 );
			}
			if ( !is_dir ( "../../$course_id/coop/$case_id/$group_num[$i]/info" ) ) {
				mkdir( "../../$course_id/coop/$case_id/$group_num[$i]/info", 0771 );
				chmod( "../../$course_id/coop/$case_id/$group_num[$i]/info", 0771 );
			}
			if ( !is_dir ( "../../$course_id/coop/$case_id/$group_num[$i]/result" ) ) {
				mkdir( "../../$course_id/coop/$case_id/$group_num[$i]/result", 0771 );
				chmod( "../../$course_id/coop/$case_id/$group_num[$i]/result", 0771 );
			}
			if ( !is_dir ( "../../$course_id/coop/$case_id/$group_num[$i]/schedule" ) ) {
				mkdir( "../../$course_id/coop/$case_id/$group_num[$i]/schedule", 0771 );
				chmod( "../../$course_id/coop/$case_id/$group_num[$i]/schedule", 0771 );
			}
			$sql1 = "select * from coop_".$case_id."_group where student_id='".$student_id[$i]."'";
			($result1 = mysql_db_query($DBC.$course_id, $sql1)) or die("資料庫查詢錯誤, $sql1");
			$row1 = mysql_fetch_array ( $result1 );
			
			$sql2 = "select * from user where id='".$student_id[$i]."'";
			($result2 = mysql_db_query($DB, $sql2)) or die("資料庫查詢錯誤, $sql2");
			$row2 = mysql_fetch_array ( $result2 );
			if($exists[$i] == 1) {
				$sql3 = "update coop_".$case_id."_group set group_num=".$group_num[$i]." where student_id='".$student_id[$i]."'";
				if ( $group_num[$i] != "-1" ) {
					$sql4 = "delete from log_".$case_id." where group_num='-1' and user_id='".$row2['a_id']."'";
					mysql_db_query($DBC.$course_id, $sql4) or die("資料庫查詢錯誤, $sql4");
				}
			}
			else {
				$sql3 = "insert coop_".$case_id."_group(group_num,student_id) values( '".$group_num[$i]."','".$student_id[$i]."')";
			}
			mysql_db_query($DBC.$course_id, $sql3) or die("資料庫查詢錯誤, $sql");
			$sq2 = "select * from share_group_".$case_id." where group_num = '".$group_num[$i]."' and parent_id = '-1'";
			($result2 = mysql_db_query($DBC.$course_id, $sq2)) or die("資料庫查詢錯誤, $sq2");
			if ( mysql_num_rows( $result2 ) == 0 ){
				$sql5 = "insert share_group_".$case_id." ( group_num, name, parent_id ) values ( '".$group_num[$i]."', 'Root', '-1' )";
				mysql_db_query($DBC.$course_id, $sql5) or die("資料庫查詢錯誤, $sql5");
			} 
		}			
		group();
	}
	else if( $action == "judge" ) {
		judge ();
	}
	else
		show_page_d();
}
else
{
	if( $version=="C" )
		show_page( "not_access.tpl" ,"你沒有權限使用此功能");
	else
		show_page( "not_access.tpl" ,"You have No Permission!!");
}
  
function show_page_d () {
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $version, $message, $course_id, $skinnum;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}
	$Q1 = "SELECT name, percentage, a_id, private FROM coop ORDER BY a_id";
	if ( !($result = mysql_db_query( $DBC.$course_id, $Q1 ) ) ) {
		show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
	}

	if( mysql_num_rows($result) != 0)
	{
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate("./templates");
		if($version == "C")
			$tpl->define(array(main=>"Mag_case.tpl"));
		else
			$tpl->define(array(main=>"Mag_case_E.tpl"));
		$tpl->define_dynamic("row","main");
		$tpl->assign( SKINNUM , $skinnum );
		$color == "#F0FFEE";
		while ( $rows = mysql_fetch_array( $result ) ) {
			if ( $color == "#F0FFEE" )
				$color = "#E6FFFC";
			else
				$color = "#F0FFEE";
			$tpl->assign( COLOR , $color );
			if($rows[1] == 0)
				if($version == "C")  
					$tpl->assign(CASETYPE,"不計分");
				else
					$tpl->assign(CASETYPE,"Self-case");
			else
				if($version == "C")
					$tpl->assign(CASETYPE,"計分");
				else
					$tpl->assign(CASETYPE,"Normal-case");
					
			if($rows[3] == 0)
				if($version == "C")  
					$tpl->assign(PRIVATE,"公開");
				else
					$tpl->assign(PRIVATE,"public");
			else
				if($version == "C")
					$tpl->assign(PRIVATE,"私人(僅小組)");
				else
					$tpl->assign(PRIVATE,"private");
			$tpl->assign(CASENAME,$rows[0]);
			$tpl->assign(RATIO,$rows[1]);
			$tpl->assign(CASEID,$rows[2]);
			$tpl->parse(ROWS,".row");
		}
		$tpl->assign(MESSAGE,$message);
		$tpl->parse(BODY,"main");
 		$tpl->FastPrint("BODY");
	}
	else
	{
		if( $version=="C" )
			show_page( "not_access.tpl" ,"目前沒有任何專案可供修改!");
		else
			show_page( "not_access.tpl" ,"There is no case for modification!!");
	}
}

function modifynr () {
	global $message, $case_name, $case_id, $case_ratio, $case_type, $case_private, $case_pri, $version, $skinnum;
	
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate("./templates");
	if($version == "C") {
		$tpl->define( array(main=>"create_case.tpl") );
		$tpl->assign(BUTTON,"修改");
		$tpl->assign(TITLE, "修改專案" );
	}
	else {
		$tpl->define( array(main=>"create_case_E.tpl") );
		$tpl->assign(BUTTON,"Modify");
		$tpl->assign(TITLE, "Modify Case" );
	}
	$tpl->assign( SKINNUM , $skinnum );
	$tpl->assign(IMG,"a332.gif");
	$tpl->assign(ACT1,"Mag_case.php");
	$tpl->assign(ACT2,"modifycasenr");
	$tpl->assign(CASE_NAME,$case_name);
	$tpl->assign(CASE_RATIO,$case_ratio);
	if ( !isset($case_type) ) {
		if ( $case_ratio != 0 )
			$tpl->assign( REAL_CASE, "selected");
		else
			$tpl->assign( SELF_CASE, "selected");
	}
	else {
		if ( $case_type != "self_case" )
			$tpl->assign( REAL_CASE, "selected");
		else
			$tpl->assign( SELF_CASE, "selected");
	}
	
	if ( !isset($case_private) ) {
		if ( $case_pri != 0 )
			$tpl->assign( PRI_CASE, "selected");
		else
			$tpl->assign( PUB_CASE, "selected");
	}
	else {
		if ( $case_private != "pri_case" )
			$tpl->assign( PUB_CASE, "selected");
		else
			$tpl->assign( PRI_CASE, "selected");
	}

	$tpl->assign(CASEID,$case_id);
	$tpl->assign(MESSAGE,$message);
	$tpl->parse(BODY,"main");
	$tpl->FastPrint("BODY");
}

function CheckError()
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $case_name, $case_ratio, $course_id, $case_type, $case_id;

	if($case_name == "" || ( $case_ratio == "" && $case_type != "self_case" ))
		return "null";
	elseif(($case_ratio > 100 || $case_ratio < 0) && $case_type != "self_case")
		return "errorvalue";
	else
	{
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
		}
		$Q1 = "SELECT a_id, name FROM coop WHERE name='$case_name'";
		if ( !($result = mysql_db_query( $DBC.$course_id, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
		}
		if ( mysql_num_rows($result) != 0 ) {
			$rows = mysql_fetch_array($result);
			if( $case_name == $rows['name'] && $case_id != $rows['a_id'] )
				return "exist";
			else
				return "ok";
		}
		else
			return "ok";
	}
}


function showgrade () {
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $case_id, $version, $course_id, $message, $skinnum, $PHPSESSID;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}
	$Q1 = "SELECT * from coop_".$case_id."_group order by group_num";
	if ( !($result1 = mysql_db_query( $DBC.$course_id, $Q1 ) ) ) {
		show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
	}
	if ( mysql_num_rows($result1) != 0 ) {
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate("./templates");
		if($version == "C")
			$tpl->define(array(main=>"showgrade.tpl"));
		else
			$tpl->define(array(main=>"showgrade_E.tpl"));
		$tpl->define_dynamic("grade","main");
		$tpl->assign( SKINNUM , $skinnum );
		$tpl->assign( PHPSID , $PHPSESSID );
		$color == "#F0FFEE";
		while ( $rows = mysql_fetch_array($result1) )
		{
			if ( $color == "#F0FFEE" )
				$color = "#E6FFFC";
			else
				$color = "#F0FFEE";
			$tpl->assign( COLOR , $color );
			$Q2 = "SELECT * from user where id = '".$rows['student_id']."'";
			($result2 = mysql_db_query( $DB, $Q2 ) ) or die ("資料庫讀取錯誤!!");
			($rows2 = mysql_fetch_array ( $result2 )) or die ("資料庫讀取錯誤!!");
			
			$Q3 = "SELECT grade FROM take_coop WHERE case_id = '$case_id' and student_id='".$rows2['a_id']."'";
			($result3 = mysql_db_query( $DBC.$course_id, $Q3 ) ) or die ("資料庫讀取錯誤!!");
		
			$rows3 = mysql_fetch_array($result3);
			
			
			$tpl->assign(GID,$rows['group_num']);
			$tpl->assign(SNO,$rows2['id']);
			$tpl->assign(SNN,$rows2['name']);
			
			$Q4 = "Select grade From grade_".$case_id." Where gived_id = '".$rows2['a_id']."' and grade_type='0'";
			$total = 0;
			$total_grade = 0;
			($resultOBJ4 = mysql_db_query( $DBC.$course_id, $Q4 ) ) or die ("資料庫讀取錯誤4!!");
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
			$tpl->assign( GIN, $avg );
			
			$Q5 = "Select grade From grade_".$case_id." Where gived_id = '".$rows['group_num']."' and grade_type='1'";
			$total = 0;
			$total_grade = 0;
			($resultOBJ5 = mysql_db_query( $DBC.$course_id, $Q5 ) ) or die ("資料庫讀取錯誤4!!");
			while ( $row5 = mysql_fetch_array ( $resultOBJ5 ) ) {
				if ( $row5['grade'] != NULL && $row5['grade'] != "" ) {
					$total_grade += $row5['grade'];
					$total ++;
				}
			}
			if ( $total == 0 ) {
				$avg = 0;
			}else {
				$avg = $total_grade/$total;
			}
			$tpl->assign( GOUT, $avg );
			
			if ( $rows3['grade'] != "-1" )
				$tpl->assign(TOTALG,$rows3['grade']);
			else {
				if ( $version == "C" )
					$tpl->assign(TOTALG,"未有成績");
				else
					$tpl->assign(TOTALG,"N/A");
			}
			$tpl->assign( AID, $rows2['a_id'] );
			$tpl->assign( CASEID, $case_id );
			$tpl->parse(GRADE,".grade");
		}
		$tpl->parse(BODY,"main");
		$tpl->FastPrint("BODY");
	}
	else {
		if( $version=="C" )
			show_page( "not_access.tpl" ,"此課程尚未有任何學生!!");
		else
			show_page( "not_access.tpl" ,"There is no student in this class!!");
	}
}

function group () {
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $case_id, $version, $course_id, $message, $skinnum, $PHPSESSID;

	include("class.FastTemplate.php3");
	$tpl = new FastTemplate("./templates");

	if($version == "C") {
		$tpl->define(array(main => "group_admin.tpl"));
	}
	else {
		$tpl->define(array(main => "group_admin_E.tpl"));
	}

	$tpl->define_dynamic("user_list","main");

	$tpl->assign("TITLE", "小組組員管理");
	$tpl->assign("GRP_ADM" ,"Mag_case.php");
	$tpl->assign("SKINNUM" ,"$skinnum");
	// 先取得修此門課的學生學號一覽表
	$sql = "select user.id from user,take_course where take_course.course_id=$course_id and user.a_id=take_course.student_id and take_course.credit='1' order by user.id";

	$result = mysql_db_query($DB, $sql)  or die("資料庫查詢錯誤, $sql");

	$counter=0;

	while($row = mysql_fetch_array($result)) {
		$tpl->assign("STU_ID", $row["id"]);
		$tpl->assign("STU_NAME", GetUserName($row["id"]));
		$tpl->assign("GRP_INPUT", "group_num[$counter]");
		$tpl->assign("SID_INPUT", "student_id[$counter]");
		$tpl->assign("SID_STAT", "exists[$counter]");

		// 取得目前的分組狀況
		$sql2 = "select group_num from coop_".$case_id."_group where student_id='".$row["id"]."'";
		$result2 = mysql_db_query($DBC.$course_id, $sql2) or die("資料庫查詢錯誤, $sql2");

		if(mysql_num_rows($result2) > 0) {
			$row2 = mysql_fetch_array($result2);
			if($row2["group_num"] > 0 || $row2["group_num"] == -1) {   // 資料已經存在
				$tpl->assign("GRP_NUM", $row2["group_num"]);
				$tpl->assign("STATUS", "1");
			}
		}
		else {         // 資料尚未存在
			$tpl->assign("GRP_NUM", "-1");
			$tpl->assign("STATUS", "0");
		}
		
		// 顏色控制.
		if($counter%2 == 0) 
			$tpl->assign("GRCOLOR", "#E6FFFC");
		else
			$tpl->assign("GRCOLOR", "#F0FFEE");

		$tpl->parse(ROWU, ".user_list");
		$counter++;
	}
	
	$tpl->assign("PHP_ID", $PHPSESSID);
	$tpl->assign("CASEID", $case_id );
	$tpl->parse(BODY, "main");
	$tpl->FastPrint(BODY);
} // end if [顯示輸入畫面].

function pubcase () {
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $case_id, $version, $course_id, $message, $skinnum;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}
	$Q1 = "SELECT name,beg_time,end_time,public FROM coop WHERE a_id='$case_id'";
	if ( !($result1 = mysql_db_query( $DBC.$course_id, $Q1 ) ) ) {
		show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
	}
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate("./templates");
	if ( $version == "C" )
		$tpl->define(array(main=>"pubcase.tpl"));
	else
		$tpl->define(array(main=>"pubcase_E.tpl"));
	$tpl->define_dynamic("yy","main");
	$tpl->define_dynamic("ye","main");
	$tpl->assign( SKINNUM , $skinnum );
	$row1 = mysql_fetch_row( $result1 );
	$tpl->assign(CASENAME,$row1[0]);
	$tpl->assign(CASEID,$case_id);
	if(($row1[2] == "00000000000000")&&($row1[3] == "0"))
	{  
		if($version == "C")  
			$tpl->assign(STATUS,"未發佈");
		else
			$tpl->assign(STATUS,"Never Public");
		$end_day = date("d");
		$end_month = date("m");
		$beg_day=date("d");
		$beg_month=date("m");
		$bh= date("H");
		$bm= date("i");
	}
	else
	{
		$beg_y = (int) substr($row1[1],0,4);
		$beg_mo = (int) substr($row1[1],4,2);
		$beg_d = (int) substr($row1[1],6,2);
		$beg_h = (int) substr($row1[1],8,2);
		$beg_m = (int) substr($row1[1],10,2);
		$end_y = (int) substr($row1[2],0,4);
		$end_mo = (int) substr($row1[2],4,2);
		$end_d = (int) substr($row1[2],6,2);
		$end_h = (int) substr($row1[2],8,2);
		$end_m = (int) substr($row1[2],10,2);
		$range = timecount($end_y, $end_mo, $end_d, $end_h, $end_m, $beg_y, $beg_mo, $beg_d, $beg_h, $beg_m);
		if ( $row1[3] != 1 ) {
			switch($range)
			{	
				case 1: $tpl->assign(CHECK1,"checked");
					$tpl->assign(CHECK2,"");
					$tpl->assign(CHECK3,"");
					$tpl->assign(CHECK4,"");
					break;
				case 2: $tpl->assign(CHECK1,"");
					$tpl->assign(CHECK2,"checked");
					$tpl->assign(CHECK3,"");
					$tpl->assign(CHECK4,"");
					break;
				case 4: $tpl->assign(CHECK1,"");
					$tpl->assign(CHECK2,"");
					$tpl->assign(CHECK3,"checked");
					$tpl->assign(CHECK4,"");
					break;
				case 8: $tpl->assign(CHECK1,"");
					$tpl->assign(CHECK2,"");
					$tpl->assign(CHECK3,"");
					$tpl->assign(CHECK4,"checked");
					break;
				default:
					if($version == "C")  
						$tpl->assign(STATUS,"未發佈");
					else
						$tpl->assign(STATUS,"Never Public");
					break;
			}
			$end_day = substr($row1[2],6,2);
			$end_month = substr($row1[2],4,2);
		}
		else {
			$end_day = substr($row1[2],6,2);
			$end_month = substr($row1[2],4,2);
		}
		if ( $range >= 0 ) {
			if ( $row1[3] != 1 ) {
				if($version == "C" )
					$tpl->assign(STATUS,date("Y-m-d H:i",mktime(substr($row1[1],8,2),substr($row1[1],10,2),0,substr($row1[1],4,2),substr($row1[1],6,2),substr($row1[1],0,4)))."發佈 維期 $range 小時");
				else
					$tpl->assign(STATUS,date("Y-m-d H:i",mktime(substr($row1[1],8,2),substr($row1[1],10,2),0,substr($row1[1],4,2),substr($row1[1],6,2),substr($row1[1],0,4)))."Public During $range Hourse");
			}
			else {
				if($version == "C" )
					$tpl->assign(STATUS,date("Y-m-d H:i",mktime(substr($row1[1],8,2),substr($row1[1],10,2),0,substr($row1[1],4,2),substr($row1[1],6,2),substr($row1[1],0,4)))."發佈 ".date("Y-m-d H:i",mktime(substr($row1[2],8,2),substr($row1[2],10,2),0,substr($row1[2],4,2),substr($row1[2],6,2),substr($row1[2],0,4)))."結束");
				else
					$tpl->assign(STATUS,date("Y-m-d H:i",mktime(substr($row1[1],8,2),substr($row1[1],10,2),0,substr($row1[1],4,2),substr($row1[1],6,2),substr($row1[1],0,4)))."Public, End at".date("Y-m-d H:i",mktime(substr($row1[2],8,2),substr($row1[2],10,2),0,substr($row1[2],4,2),substr($row1[2],6,2),substr($row1[2],0,4))));
			}
		}
		$beg_day=substr($row1[1],6,2);
		$beg_month=substr($row1[1],4,2);
		$bh= substr($row1[1],8,2);
		$bm= substr($row1[1],10,2);
	}
	for($j=0;$j < 3;$j++)
	{
		$year = date("Y")+$j;
		if ( $year == substr( $row1[1], 0, 4 ) )
			$tpl->assign(YEAV, "$year selected");
		else
			$tpl->assign(YEAV,$year);
		$tpl->assign(YEAR,$year);
		$tpl->parse(YY,".yy");
	}
	for($j=0;$j < 3;$j++)
	{
		$year = date("Y")+$j;
		if ( ($j == 2 && $row1[3] != 1 && $row1[3] != 3 ) || ($year == substr( $row1[2], 0, 4 ) && $row1[3] == 1)  )
			$tpl->assign(YEAEV, "$year selected");
		else
			$tpl->assign(YEAEV,$year);
		$tpl->assign(YEAED,$year);
		$tpl->parse(YE,".ye");
	} 
	
	$DV = "DA".$beg_day;
	$MV = "MA".$beg_month;
	$tpl->assign($DV, "selected");	
	$tpl->assign($MV , "selected");
	
	$HV = "HB".$bh;
	$BV = "MB".$bm;
	$tpl->assign($HV, "selected");	
	$tpl->assign($BV , "selected");
	
	$DEV = "DE".$end_day;
	$MDV = "MOE".$end_month;
	$tpl->assign($DEV, "selected");	
	$tpl->assign($MDV , "selected");
	if ( $row1[3] == 1 )
		$tpl->assign(PUB, "checked" );
	$tpl->assign(MESSAGE, $message);
	$tpl->parse(BODY,"main");
	$tpl->FastPrint("BODY");
}

function GetUserName($user_id) {

	global $DB, $DB_SERVER, $DB_LOGIN, $DB_PASSWORD;
	mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("資料庫連結錯誤");
	$sql = "select name,nickname from user where id='$user_id'";
	$result = mysql_db_query($DB, $sql) or die("資料庫查詢錯誤, $sql");
	
	if(mysql_num_rows($result) > 0) {
		$row = mysql_fetch_array($result);
		if(strcmp($row["name"], "" ) !=0 ) {
			$poster = $row["name"];
		}
		else {
			$poster = "";
		}
	}
	else {
		$poster = "";
	}
	
	return $poster;
}

function show_content ( ) {
	global $message, $case_name, $case_topic, $case_ratio, $case_id, $version, $skinnum;
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate("./templates");
	if($version == "C")
		$tpl->define(array(main=>"edit_case.tpl"));
	else
		$tpl->define(array(main=>"edit_case_E.tpl"));
	$tpl->assign( SKINNUM , $skinnum );
	if($version == "C") {
		$tpl->assign(TOPIC,"修改專案主題");
		$tpl->assign(ACT2,"updatetopic");
	}
	else {
		$tpl->assign(TOPIC,"Modify Topic");
		$tpl->assign(ACT2,"updatetopic");
	}
	$tpl->assign(CASENAME,$case_name);
	$tpl->assign(CASERATIO,$case_ratio);
	$tpl->assign(CASEID,$case_id);
	$tpl->assign(CASET,$case_topic);
	$tpl->assign(MESSAGE,$message);
	$tpl->assign(IMG,"a322.gif");
	$tpl->assign(ACT1,"Mag_case.php");
	$tpl->parse(BODY,"main");
	$tpl->FastPrint("BODY");
}

function judge ( ) {
	global $case_id, $group_id, $aid, $type, $version, $skinnum, $course_id;
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate("./templates");
	$tpl->define(array(main=>"show_judge.tpl"));
	$tpl->define_dynamic("judge","main");
	$tpl->assign( SKINNUM , $skinnum );
	
	global $DB, $DBC, $DB_SERVER, $DB_LOGIN, $DB_PASSWORD;
	mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("資料庫連結錯誤");
	
	if ( $type == 0 ) {
		$Q1 = "select * from user where a_id = '$aid'";
		$result1 = mysql_db_query($DB, $Q1) or die("資料庫查詢錯誤, $Q1");
		$row1 = mysql_fetch_array($result1);
		$message = $row1['id']." ".$row1['name'];
		$sql = "select judge from grade_".$case_id." where gived_id = '$aid' and grade_type = '$type'";
	}
	else {
		if ( $version == "C" ) {
			$message = "第 $group_id 組";
		}
		else {
			$message = "Group $group_id";
		}
		$sql = "select judge from grade_".$case_id." where gived_id = '$group_id' and grade_type = '$type'";
	}

	$result = mysql_db_query($DBC.$course_id, $sql) or die("資料庫查詢錯誤, $sql");
	if(mysql_num_rows($result) > 0) {
		$color == "#F0FFEE";
		while ( $rows = mysql_fetch_array($result) )
		{
			if ( $color == "#F0FFEE" )
				$color = "#E6FFFC";
			else
				$color = "#F0FFEE";
			$tpl->assign( COLOR , $color );
			$tpl->assign(JUDGE,$rows['judge']);
			$tpl->parse(JUD,".judge");
		}
	}
	else {
		if ( $version == "C" ) {
			$tpl->assign(JUDGE,"沒有評語");
		}
		else {
			$tpl->assign(JUDGE,"No Judge");
		}
		$tpl->parse(JUD,".judge");
	}
	$tpl->assign( MESSAGE , $message );
	$tpl->parse(BODY,"main");
	$tpl->FastPrint("BODY");
}
?>