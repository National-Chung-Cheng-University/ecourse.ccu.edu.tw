<?php
require 'fadmin.php';
update_status ("觀看專案");
session_unregister("coopgroup");
session_register("coopgroup");
session_unregister("coopcaseid");
session_register("coopcaseid");
session_unregister("cooptime");
session_register("cooptime");
$cooptime = date("U");
$coopcaseid = $case_id;
$coopgroup = $groupnum;

if( isset($PHPSESSID) && session_check_teach($PHPSESSID) )
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}
	elseif($action == "showtopic")
	{
		showtopic ();
	}
	elseif($action == "check")
	{
		check ();
	}
	else {
		show_page_d ();
	}
}
else
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

function show_page_d () {
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $course_id, $version, $skinnum, $teacher, $user_id;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}
	if ( $teacher == 1 ) {
		$Q1 = "SELECT name, percentage, a_id, end_time, private FROM coop ORDER BY a_id";
	}
	else {
		$Q1 = "SELECT name, percentage, a_id, end_time, private FROM coop where public = '1' ORDER BY a_id";
	}
	$result1 = mysql_db_query($DBC.$course_id, $Q1);

	if ( mysql_num_rows ( $result1 ) ) {
		
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate("./templates");
		if($version == "C")
			$tpl->define(array(main=>"check_allcase.tpl"));
		else
			$tpl->define(array(main=>"check_allcase_E.tpl"));
		$tpl->define_dynamic("row","main");
		$tpl->assign( SKINNUM , $skinnum );
		$color = "#F0FFEE";
		while ( $rows = mysql_fetch_array( $result1 ) )
		{
			if ( $color == "#F0FFEE" )
				$color = "#E6FFFC";
			else
				$color = "#F0FFEE";

			if ( $teacher == 1 || $rows['private'] != 1 ) {
				$Q2 = "SELECT group_num FROM coop_".$rows[2]."_group group by group_num";
			}
			else {
				$Q2 = "SELECT group_num FROM coop_".$rows[2]."_group where student_id = '$user_id'";
			}
			$result2 = mysql_db_query($DBC.$course_id, $Q2);
			while ( $row2 = mysql_fetch_array ( $result2 ) ) {
				if ( $row2["group_num"] == -1 ) {
					$group .= "<a href=check_allcase.php?case_id=CASEID&action=check&groupnum=-1 target=\"_blank\">未分組</a> ";
				}
				else {
					$group .= "<a href=check_allcase.php?case_id=CASEID&action=check&groupnum=".$row2["group_num"]." target=\"_blank\">".$row2["group_num"]."</a> ";
				}
			}

			$tpl->assign( VIEW , $group );
			$tpl->assign( COLOR , $color );
			$tpl->assign(CASENAME,$rows[0]);
			$tpl->assign(CASEDUE, substr($rows[3],0,4)."-".substr($rows[3],4,2)."-".substr($rows[3],6,2) );
			$tpl->assign(CASERATIO,$rows[1]);
			$tpl->assign(CASEID,$rows[2]);
			$group = "";
			$tpl->parse(ROWS,".row");
		}
		$tpl->parse(BODY,"main");
		$tpl->FastPrint("BODY");
	}
	else {
		if( $version=="C" )
			show_page( "not_access.tpl" ,"目前沒有任何專案可供修改!");
		else
			show_page( "not_access.tpl" ,"There is no Case for Check!!");
	}
}

function showtopic () {
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $case_id, $course_id, $version, $skinnum;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}
	$Q1 = "SELECT name,topic FROM coop WHERE a_id='$case_id'";
	if ( !($result1 = mysql_db_query( $DBC.$course_id, $Q1 ) ) ) {
		show_page( "not_access.tpl" ,"資料庫寫入錯誤!!" );
	}
	$rows = mysql_fetch_array( $result1 );
	
	
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate("./templates");
	$tpl->assign( SKINNUM , $skinnum );
	if($version == "C") {
		$tpl->define(array(main=>"showcase.tpl"));
		$tpl->assign(SHOWTYPE,"專題主題");
	}
	else {
		$tpl->define(array(main=>"showcase_E.tpl"));
		$tpl->assign(SHOWTYPE,"Case Topic");
	}
	$content = $rows['topic'];
	if ( stristr($content,"<html>") == NULL ) {
		$content=htmlspecialchars( $content );
		$content=ereg_replace("\n","<BR>\n",$content);
	}
	$tpl->assign(TOPIC,$content);
	$tpl->assign(CASENAME,$rows['name']);

	$tpl->parse(BODY,"main");
	$tpl->FastPrint("BODY");
}

function check () {
	global $case_id, $course_id, $version, $skinnum, $groupnum, $PHPSESSID, $guest, $teacher, $user_id;
	
	if ( check_group( $course_id, $groupnum, $case_id ) ) {
		if( $teacher != 1 && (strcmp($guest,"1") != 0) && (session_check_teach($PHPSESSID) != 3)) {
			add_log_coop( 1, $user_id, "", $course_id, "", "", $groupnum, $case_id );
		}
		include("class.FastTemplate.php3");
		$tpl=new FastTemplate("./templates");
		$tpl->define(array(main=>"coop_env.tpl"));
		$tpl->assign ( PHPSID, $PHPSESSID);
		$tpl->parse(BODY,"main");
		$tpl->FastPrint("BODY");
	}
	else {
		if( $version=="C" ) {
			show_page( "not_access.tpl" ,"你沒有權限使用此功能");
			exit;
		}
		else {
			show_page( "not_access.tpl" ,"You have No Permission!!");
			exit;
		}
	}
}

?>