<?php
require 'fadmin.php';
global $DB;

$classnumber=0;			//各課程瀏覽人次
$i=0;					//為了方便加總各系所學院的總瀏覽次數所設的變數i
$classnumbers=array();	//各系所總瀏覽次數

if (!(isset($PHPSESSID) && session_check_admin($PHPSESSID)) )
{
	show_page( "not_access.tpl" ,"權限錯誤");
}

include("class.FastTemplate.php3");
$tpl = new FastTemplate ( "./templates" );
$tpl->define ( array ( body => "statistics.tpl" ) );

$tpl->define_dynamic ( "class_list" , "body" );
$tpl->define_dynamic ( "depart_list", "body" );
$tpl->define_dynamic ( "institude_list", "body" );
$color = "#000066";
$tpl->assign( COLOR , $color );
$tpl->assign( SERIES, "流水號(系所名稱)" );
$tpl->assign( CNAME, "課程名稱" );
$tpl->assign( CLASSNUMBER, "各課程瀏覽人次" );
$tpl->assign( CLASSSTNUMBER, "修課學生人數" );

$tpl->assign( DEPARTNAME, "系所名稱" );
$tpl->assign( DEPARTNUMBER, "各系所瀏覽人次" );

$tpl->assign( INSTITUDENAME, "學院名稱" );
$tpl->assign( INSTITUDENUMBER, "各學院瀏覽人次" );

$tpl->assign( FONTCOL, "#FFFFFF" );
$tpl->parse( CLASS_LIST, ".class_list" );
$tpl->parse( DEPART_LIST, ".depart_list" );
$tpl->parse( INSTITUDE_LIST, ".institude_list" );

//$Q1 = "select name, a_id, parent_id from course_group where a_id >1 and a_id < 11 order by a_id";

if( $action==3 )
	$Q1 = "select name, a_id, parent_id from course_group where a_id=3 order by a_id";
elseif( $action==4 )
	$Q1 = "select name, a_id, parent_id from course_group where a_id=4 order by a_id";
elseif( $action==5 )
	$Q1 = "select name, a_id, parent_id from course_group where a_id=5 order by a_id";
elseif( $action==6 )
	$Q1 = "select name, a_id, parent_id from course_group where a_id=6 order by a_id";
elseif( $action==7 )
	$Q1 = "select name, a_id, parent_id from course_group where a_id=7 order by a_id";
elseif( $action==8 )
	$Q1 = "select name, a_id, parent_id from course_group where a_id=8 order by a_id";
elseif( $action==9 )
	$Q1 = "select name, a_id, parent_id from course_group where a_id=9 order by a_id";
elseif( $action==10 )
	$Q1 = "select name, a_id, parent_id from course_group where a_id=10 order by a_id";

$result1 = mysql_db_query( $DB, $Q1 );
$color = "#F0FFEE";
while( $row1 = mysql_fetch_array($result1) )
{
	$institude=0;
	//選系所名稱
	$Q2 = "select cg.name, cg.a_id from course_group cg where cg.parent_id='".$row1[a_id]."'";
	$result2 = mysql_db_query( $DB, $Q2 );
	while( $row2 = mysql_fetch_array($result2))
	{
		$depart=0;
		$seriesnumber=0;
		//選課程名稱
		$Q3 = "select c.name, c.a_id, c.group_id from course c, teach_course tc, this_semester ts where ts.year=tc.year and ts.term=tc.term and c.a_id=tc.course_id and c.group_id='".$row2[a_id]."' group by tc.course_id, c.name order by c.group_id, c.name";
		$result3 = mysql_db_query( $DB, $Q3 );
		while($row3 = mysql_fetch_array($result3))
		{
			$count=0;
			//選出有修該課程的學生
			$Q4 = "select student_id from take_course, this_semester where take_course.course_id='".$row3[a_id]."' and take_course.year=this_semester.year and take_course.term=this_semester.term";
			$result4 = mysql_db_query( $DB, $Q4 );
			$classnumber=0;
			while( $row4 = mysql_fetch_array($result4) )
			{
				//選出課程被瀏覽次數
				$Q5 = "select tag3 from log where user_id='".$row4[student_id]."'";
				$result5 = mysql_db_query( $DB, $Q5 );
				$row5 = mysql_fetch_array( $result5 );
				$count++;
				$classnumber += $row5[tag3];
			}
			$classnumbers[$row3[group_id]][$i] = $classnumber;
			
			$tpl->assign( COLOR, $color );
			$tpl->assign( FONTCOL, "#000000" );
			$tpl->assign( SERIES, $seriesnumber."(".$row2[name].")" );
			$tpl->assign( CNAME, $row3[name]);
			$tpl->assign( CLASSNUMBER, $classnumbers[$row3[group_id]][$i] );
			$tpl->assign( CLASSSTNUMBER, $count );
			$tpl->parse( CLASS_LIST, ".class_list" );
			
			$seriesnumber++;
			
			$j=$i;
			if( $j>=0 ){
				$depart += $classnumbers[$row3[group_id]][$j];
				$j--;
			}
		}
		
		$i++;
		$tpl->assign( DEPARTNAME, $row2[name] );
		$tpl->assign( DEPARTNUMBER, $depart );
		$tpl->parse( DEPART_LIST, ".depart_list" );
		
		$institude += $depart;
	}
	$tpl->assign( INSTITUDENAME, $row1[name] );
	$tpl->assign( INSTITUDENUMBER, $institude );
	$tpl->parse( INSTITUDE_LIST, ".institude_list" );
}

$tpl->parse( BODY, "body" );
$tpl->FastPrint("BODY");

?>