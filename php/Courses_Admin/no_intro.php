<?php
// devon 2006-03-23 定案
// 查看已上傳及未上傳課程大綱之系所

require 'fadmin.php';
global $DB, $version,$skinnum;
$count = 0;			//總課程數
$has_intro = 0;		//有大綱的課程數
$no_intro = 0;		//無大綱的課程數

if (!(isset($PHPSESSID) && session_check_admin($PHPSESSID)) )
{
	show_page( "not_access.tpl" ,"權限錯誤");
}

$Q0 = "select * from this_semester";
$result0 = mysql_db_query($DB, $Q0);
$row0 = mysql_fetch_array($result0);

include("class.FastTemplate.php3");
$tpl = new FastTemplate ( "./templates" );
$tpl->define ( array ( body => "no_intro.tpl" ) );
$tpl->define_dynamic ( "no_intro_list" , "body" );
$tpl->assign( SKINNUM , $skinnum );
$tpl->assign( TYPE , "colspan=2" );

if($status != 2)
{
	//依順序選出系所名稱
	$Q2 = "select name, a_id from course_group where is_leaf='1' order by a_id";
	if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) )
	{
		$message = "$message - 資料庫讀取錯誤2!!";
	}
	$tpl->assign(STATUS, "已上傳課程大綱列表");
	$tpl->assign(SELE1, "selected");
	$tpl->assign(SELE2, "");
	while($row2 = mysql_fetch_array($result2))
	{
		//選出該系所以下的所有課程
		//$Q3 = "select distinct course.introduction, course.a_id course_id, course.name cname, course.course_no from course, teach_course where teach_course.year='".$row0[year]."' and teach_course.term='".$row0[term]."' and teach_course.course_id=course.a_id and course.group_id='".$row2[a_id]."' order by course.group_id, course.course_no";
		$Q3 = "select course.introduction, course.a_id course_id, course.name cname, course.course_no, user.name tname from course, teach_course, user where teach_course.year='".$row0[year]."' and teach_course.term='".$row0[term]."' and teach_course.course_id=course.a_id and course.group_id='".$row2[a_id]."'and teach_course.teacher_id=user.a_id and user.authorization =1 order by course.group_id, course.course_no";
		if ( !($result3 = mysql_db_query( $DB, $Q3 ) ) )
		{
			$message = "$message - 資料庫讀取錯誤3!!";
		}
		//該系所　所有的課程總數
		//$dept_total=mysql_num_rows($result3);
		$dept_total=0;
		//看有幾門課是已上傳
		$self_count=0;
		$course_no="";
		while($row3 = mysql_fetch_array($result3))
		{
			if( $course_no != $row3["course_no"]){
				$count++;
				$dept_total++;
			}
			//chiefboy1230@20120214，加入docx及pptx支援office 2007以上格式
			//若大綱不為空、大綱是.html、大綱是.htm、大綱是.doc、大綱是.pdf、大綱是.ppt
			//if( $row3[introduction] != "" || is_file("../../$row3[course_id]/intro/index.html") || is_file("../../$row3[course_id]/intro/index.htm") || is_file("../../$row3[course_id]/intro/index.doc") || is_file("../../$row3[course_id]/intro/index.pdf") || is_file("../../$row3[course_id]/intro/index.ppt"))
			
			//若大綱不為空、大綱是.html、大綱是.htm、大綱是.doc、大綱是.pdf、大綱是.ppt、大綱是docx、大綱是pptx
			if( $row3[introduction] != "" || is_file("../../$row3[course_id]/intro/index.html") || is_file("../../$row3[course_id]/intro/index.htm") || is_file("../../$row3[course_id]/intro/index.doc") || is_file("../../$row3[course_id]/intro/index.pdf") || is_file("../../$row3[course_id]/intro/index.ppt") || is_file("../../$row3[course_id]/intro/index.docx") || is_file("../../$row3[course_id]/intro/index.pptx"))
			{
				if( $course_no != $row3["course_no"]){
					$has_intro++;
					$self_count++;
					
					$answer[$self_count][name]=$row2[name];				//系所名稱
					$answer[$self_count][cname]=$row3[cname];			//課程名稱
					$answer[$self_count][course_no]=$row3[course_no];	//課程編號
					$answer[$self_count][tname]=$row3[tname];	//--9604增加教師名稱欄位
				}else {
					$answer[$self_count][tname].="、".$row3[tname];	//--9604增加教師名稱欄位
				}				
			}			
			$course_no=$row3["course_no"];
		}
		if($self_count!=0)
		{
			$color = "#000066";
			$tpl->assign( COLOR , $color );
			$tpl->assign( YEAR , $row0[year] );
			$tpl->assign( TERM, $row0[term] );
			$tpl->assign( GNAME , "<font color =#FFFFFF>開課單位</font>" );
			$tpl->assign( CNO , "<font color =#FFFFFF>課程編號</font>" );
			$tpl->assign( CNAME , "<font color =#FFFFFF>課程名稱</font>" );
			$tpl->assign( TNAME , "<font color =#FFFFFF>任課教師</font>" );
			$tpl->assign(SELF, "");
			$tpl->parse( NO_INTRO_LIST, ".no_intro_list" );
		
			$color = "#F0FFEE";
			for($i=1;$i<=$self_count;$i++)
			{
				$tpl->assign( COLOR ,  $color );
				$tpl->assign( GNAME , $answer[$i][name] );			//系所名稱
				$tpl->assign( CNAME , $answer[$i][cname] );			//課程名稱
				$tpl->assign( CNO , $answer[$i][course_no] );		//課程編號
				$tpl->assign( TNAME , $answer[$i][tname] );		//任課教師
				if($i==$self_count)
					$tpl->assign(SELF, "<tr bgcolor=\"white\" align=\"center\"><td><font color=\"green\">共計".$dept_total."門課</font></td><td><font color=\"green\">已上傳".$self_count."門</font></td><td colspan=2><font color=\"red\">".sprintf("%0.2f",($self_count/$dept_total)*100)."%</font></td></tr>");
				else
					$tpl->assign(SELF, "");
				$tpl->parse ( NO_INTRO_LIST, ".no_intro_list" );
			}
		}
	}

	$percent = sprintf("%.2f", ($has_intro/$count)*100);
	$tpl->assign( PERCENT, "已上傳比率 <font color=red>".$percent."</font>%<br><br>" );
	$tpl->assign( TOTAL, $count );
	$tpl->assign( INTRO, $has_intro );
	$tpl->parse( BODY, "body" );
	$tpl->FastPrint("BODY");
}
else
{
	//依順序選出系所名稱
	$Q2 = "select name, a_id from course_group where is_leaf='1' order by a_id";
	if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) )
	{
		$message = "$message - 資料庫讀取錯誤2!!";
	}
	$tpl->assign(STATUS, "未上傳課程大綱列表");
	$tpl->assign(SELE2, "selected");
	$tpl->assign(SELE1, "");
	while($row2 = mysql_fetch_array($result2))
	{
		//選出該系所以下的所有課程
		//$Q3 = "select distinct course.introduction, course.a_id course_id, course.name cname, course.course_no from course, teach_course where teach_course.year='".$row0[year]."' and teach_course.term='".$row0[term]."' and teach_course.course_id=course.a_id and course.group_id='".$row2[a_id]."' order by course.group_id, course.course_no";
		$Q3 = "select course.introduction, course.a_id course_id, course.name cname, course.course_no, user.name tname from course, teach_course, user where teach_course.year='".$row0[year]."' and teach_course.term='".$row0[term]."' and teach_course.course_id=course.a_id and course.group_id='".$row2[a_id]."'and teach_course.teacher_id=user.a_id and user.authorization =1 order by course.group_id, course.course_no";
		if ( !($result3 = mysql_db_query( $DB, $Q3 ) ) )
		{
			$message = "$message - 資料庫讀取錯誤3!!";
		}
		//該系所　所有的課程總數
		//$dept_total=mysql_num_rows($result3);		
		$dept_total=0;
		//看有幾門課是未上傳
		$self_count=0;
		$course_no="";
		while($row3 = mysql_fetch_array($result3))
		{
			if( $course_no != $row3["course_no"]){
				$dept_total++;
				$count++;
			}
			
			//chiefboy1230@20120214，加入docx及pptx支援office 2007以上格式
			//若大綱為空、且大綱不是.html、大綱不是.htm、大綱不是.doc、大綱不是.pdf、大綱不是.ppt
			//if( $row3[introduction] =="" && !(is_file("../../$row3[course_id]/intro/index.html")) && !(is_file("../../$row3[course_id]/intro/index.htm")) && !(is_file("../../$row3[course_id]/intro/index.doc")) && !(is_file("../../$row3[course_id]/intro/index.pdf")) && !(is_file("../../$row3[course_id]/intro/index.ppt")))
			//若大綱為空、且大綱不是.html、大綱不是.htm、大綱不是.doc、大綱不是.pdf、大綱不是.ppt、大綱不是.docx、大綱不是.pptx
			if( $row3[introduction] =="" && !(is_file("../../$row3[course_id]/intro/index.html")) && !(is_file("../../$row3[course_id]/intro/index.htm")) && !(is_file("../../$row3[course_id]/intro/index.doc")) && !(is_file("../../$row3[course_id]/intro/index.pdf")) && !(is_file("../../$row3[course_id]/intro/index.ppt")) && !(is_file("../../$row3[course_id]/intro/index.docx")) && !(is_file("../../$row3[course_id]/intro/index.pptx")))
			{
				if( $course_no != $row3["course_no"]){
					$no_intro++;
					$self_count++;
					
					$answer[$self_count][name]=$row2[name];				//系所名稱
					$answer[$self_count][cname]=$row3[cname];			//課程名稱
					$answer[$self_count][course_no]=$row3[course_no];	//課程編號
					$answer[$self_count][tname]=$row3[tname];	//--9604增加教師名稱欄位
				}else {
						$answer[$self_count][tname].="、".$row3[tname];	//--9604增加教師名稱欄位
				}
			}					
			$course_no=$row3["course_no"];
		}
		if($self_count!=0)
		{
			$color = "#000066";
			$tpl->assign( COLOR , $color );
			$tpl->assign( YEAR , $row0[year] );
			$tpl->assign( TERM, $row0[term] );
			$tpl->assign( GNAME , "<font color =#FFFFFF>開課單位</font>" );
			$tpl->assign( CNO , "<font color =#FFFFFF>課程編號</font>" );
			$tpl->assign( CNAME , "<font color =#FFFFFF>課程名稱</font>" );
			$tpl->assign( TNAME , "<font color =#FFFFFF>任課教師</font>" );
			$tpl->assign( SELF, "" );
			$tpl->parse( NO_INTRO_LIST, ".no_intro_list" );
		
			$color = "#F0FFEE";
			for($i=1;$i<=$self_count;$i++)
			{
				$tpl->assign( COLOR ,  $color );
				$tpl->assign( GNAME , $answer[$i][name] );			//系所名稱
				$tpl->assign( CNAME , $answer[$i][cname] );			//課程名稱
				$tpl->assign( CNO , $answer[$i][course_no] );		//課程編號
				$tpl->assign( TNAME , $answer[$i][tname] );		//任課教師
				if($i==$self_count)
					$tpl->assign(SELF, "<tr bgcolor=\"white\" align=\"center\"><td><font color=\"green\">共計".$dept_total."門課</font></td><td><font color=\"green\">未上傳".$self_count."門</font></td><td colspan=2><font color=\"red\">".sprintf("%0.2f",($self_count/$dept_total)*100)."%</font></td></tr>");
				else
					$tpl->assign(SELF, "");
				$tpl->parse ( NO_INTRO_LIST, ".no_intro_list" );
			}
		}
	}

	$percent = sprintf("%.2f", ($no_intro/$count)*100);
	$tpl->assign( PERCENT, "未上傳比率 <font color=red>".$percent."</font>%<br><br>" );
	$tpl->assign( TOTAL, $count );
	$tpl->assign( INTRO, $no_intro );
	$tpl->parse( BODY, "body" );
	$tpl->FastPrint("BODY");
}
?>