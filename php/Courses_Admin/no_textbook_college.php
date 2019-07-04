<?php

// 查看已上傳及未上傳課程教材之系所

require 'fadmin.php';
global $DB, $version,$skinnum;
$count = 0;			//總課程數
$has_texbook = 0;		//有大綱的課程數
$no_textbook = 0;		//無大綱的課程數

$status = 1;

if (!(isset($PHPSESSID) && session_check_admin($PHPSESSID)) )
{
	show_page( "not_access.tpl" ,"權限錯誤");
}

$Q0 = "select * from this_semester";
$result0 = mysql_db_query($DB, $Q0);
$row0 = mysql_fetch_array($result0);

$Q1 = "truncate table temp_textbook";
if ( !($result1 = mysql_db_query( $DB, $Q1 ) ) )
{
	$message = "$message - 資料庫讀取錯誤1!!";
}

/*
include("class.FastTemplate.php3");
$tpl = new FastTemplate ( "./templates" );
$tpl->define ( array ( body => "no_textbook.tpl" ) );
$tpl->define_dynamic ( "no_textbook_list" , "body" );
$tpl->assign( SKINNUM , $skinnum );
$tpl->assign( TYPE , "colspan=2" );
*/

echo "<center>";
echo "<font color='blue' size='5'>".$row0['year']."學年第".$row0['term']."學期已上傳課程教材統計</font><br>";
echo "<br><a href=../check_admin.php>回系統管理介面</a><br><br>";

if($status == 1)
{
	//依順序選出系所名稱
	$Q2 = "select name, a_id from course_group where is_leaf='1' order by a_id";
	if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) )
	{
		$message = "$message - 資料庫讀取錯誤2!!";
	}
	/*
	$tpl->assign(STATUS, "已上傳課程教材列表");
	$tpl->assign(SELE1, "selected");
	$tpl->assign(SELE2, "");
	*/
	
	while($row2 = mysql_fetch_array($result2))
	{
		//選出該系所以下的所有課程
		//$Q3 = "select distinct course.a_id course_id, course.name cname, course.course_no from course, teach_course where teach_course.year='".$row0[year]."' and teach_course.term='".$row0[term]."' and teach_course.course_id=course.a_id and course.group_id='".$row2[a_id]."' order by course.group_id, course.course_no";
		$Q3 = "select course.a_id course_id, course.name cname, course.course_no, user.name tname from course, teach_course, user where teach_course.year='".$row0[year]."' and teach_course.term='".$row0[term]."' and teach_course.course_id=course.a_id and course.group_id='".$row2[a_id]."'and teach_course.teacher_id=user.a_id and user.authorization =1 order by course.group_id, course.course_no";
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
			//若目錄下除了misc外沒有其他檔案 就是沒上傳
			$dir = "../../$row3[course_id]/textbook";
			$handle = opendir($dir);
			$own_text = 0; //判斷是否無其他檔案
			
			//體育中心當作全部已上傳 960425
			if($row2['a_id']=="88"){
				$own_text = 1;
			}
			else{
				while (false !== ($file = readdir($handle))) {  //jp.960326
					if($file!="misc" && $file!="." && $file!=".."){
						$own_text = 1;
						break;
					}
				}
			}
			if($own_text == "1") {
				if( $course_no != $row3["course_no"]){
					$has_textbook++;
					$self_count++;
					
					$answer[$self_count][name]=$row2[name];				//系所名稱
					$answer[$self_count][cname]=$row3[cname];			//課程名稱
					$answer[$self_count][course_no]=$row3[course_no];	//課程編號
					$answer[$self_count][tname]=$row3[tname];			//--9604增加教師名稱欄位
				}else {
					$answer[$self_count][tname].="、".$row3[tname];		//--9604增加教師名稱欄位
				}
				
			}
			$course_no=$row3["course_no"];
			//
			/*若大綱不為空、大綱是.html、大綱是.htm、大綱是.doc、大綱是.pdf、大綱是.ppt
			if( $row3[introduction] != "" || is_file("../../$row3[course_id]/intro/index.html") || is_file("../../$row3[course_id]/intro/index.htm") || is_file("../../$row3[course_id]/intro/index.doc") || is_file("../../$row3[course_id]/intro/index.pdf") || is_file("../../$row3[course_id]/intro/index.ppt"))
			{
				$has_intro++;
				$self_count++;
				
				$answer[$self_count][name]=$row2[name];				//系所名稱
				$answer[$self_count][cname]=$row3[cname];			//課程名稱
				$answer[$self_count][course_no]=$row3[course_no];	//課程編號
			}
			*/
		}
		if($self_count!=0)
		{
			$Q9 = "INSERT INTO temp_textbook VALUES ( '$row2[a_id]', '$row2[name]', '$dept_total', '$self_count', '1', 0, '' )";
			if ( !($result9 = mysql_db_query( $DB, $Q9 ) ) )
			{
				$message = "$message - 資料庫讀取錯誤9!!";
			}
			//echo $Q9."<br>";
			/*
			$color = "#000066";
			$tpl->assign( COLOR , $color );
			$tpl->assign( YEAR , $row0[year] );
			$tpl->assign( TERM, $row0[term] );
			$tpl->assign( GNAME , "<font color =#FFFFFF>開課單位</font>" );
			$tpl->assign( CNO , "<font color =#FFFFFF>課程編號</font>" );
			$tpl->assign( CNAME , "<font color =#FFFFFF>課程名稱</font>" );
			$tpl->assign( TNAME , "<font color =#FFFFFF>任課教師</font>" );
			$tpl->assign(SELF, "");
			$tpl->parse( NO_TEXTBOOK_LIST, ".no_textbook_list" );
		
			$color = "#F0FFEE";
			for($i=1;$i<=$self_count;$i++)
			{
				$tpl->assign( COLOR ,  $color );
				$tpl->assign( GNAME , $answer[$i][name] );			//系所名稱
				$tpl->assign( CNAME , $answer[$i][cname] );			//課程名稱
				$tpl->assign( CNO , $answer[$i][course_no] );		//課程編號
				$tpl->assign( TNAME , $answer[$i][tname] );			//任課教師
				if($i==$self_count)
					$tpl->assign(SELF, "<tr bgcolor=\"white\" align=\"center\"><td><font color=\"green\">共計".$dept_total."門課</font></td><td><font color=\"green\">已上傳".$self_count."門</font></td><td colspan=2><font color=\"red\">".sprintf("%0.2f",($self_count/$dept_total)*100)."%</font></td></tr>");
				else
					$tpl->assign(SELF, "");
				$tpl->parse ( NO_TEXTBOOK_LIST, ".no_textbook_list" );
			}
			*/
		}
	}

	$Q8 = "update course_group, temp_textbook set temp_textbook.parent_id = course_group.parent_id where course_group.a_id = temp_textbook.dep_id and temp_textbook.is_upload = '1' ";
	if ( !($result8 = mysql_db_query( $DB, $Q8 ) ) )
	{
		$message = "$message - 資料庫讀取錯誤8!!";
	}

	$Q81 = "update course_group, temp_textbook set temp_textbook.parent_name = course_group.name where course_group.a_id = temp_textbook.parent_id and temp_textbook.is_upload = '1' ";
	if ( !($result81 = mysql_db_query( $DB, $Q81 ) ) )
	{
		$message = "$message - 資料庫讀取錯誤81!!";
	}	
	
	$percent = sprintf("%.2f", ($has_textbook/$count)*100);
	/*
	$tpl->assign( PERCENT, "已上傳比率 <font color=red>".$percent."</font>%<br><br>" );
	$tpl->assign( TOTAL, $count );
	$tpl->assign( TEXTBOOK, $has_textbook );
	$tpl->parse( BODY, "body" );
	$tpl->FastPrint("BODY");
	*/
	echo "<font color='red'>已上傳比率：".$percent."%</font><br>";
}
else
{
	//依順序選出系所名稱
	$Q2 = "select name, a_id from course_group where is_leaf='1' order by a_id";
	if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) )
	{
		$message = "$message - 資料庫讀取錯誤2!!";
	}
	/*
	$tpl->assign(STATUS, "未上傳課程教材列表");
	$tpl->assign(SELE2, "selected");
	$tpl->assign(SELE1, "");
	*/
	
	while($row2 = mysql_fetch_array($result2))
	{
		//選出該系所以下的所有課程
		//$Q3 = "select distinct course.a_id course_id, course.name cname, course.course_no from course, teach_course where teach_course.year='".$row0[year]."' and teach_course.term='".$row0[term]."' and teach_course.course_id=course.a_id and course.group_id='".$row2[a_id]."' order by course.group_id, course.course_no";
		$Q3 = "select course.a_id course_id, course.name cname, course.course_no, user.name tname from course, teach_course, user where teach_course.year='".$row0[year]."' and teach_course.term='".$row0[term]."' and teach_course.course_id=course.a_id and course.group_id='".$row2[a_id]."'and teach_course.teacher_id=user.a_id and user.authorization =1 order by course.group_id, course.course_no";
		if ( !($result3 = mysql_db_query( $DB, $Q3 ) ) )
		{
			$message = "$message - 資料庫讀取錯誤3!!";
		}
		//該系所　所有的課程總數
		$dept_total=mysql_num_rows($result3);	
		$dept_total=0;	
		//看有幾門課是未上傳
		$self_count=0;
		$course_no="";
		while($row3 = mysql_fetch_array($result3))
		{
			if( $course_no != $row3["course_no"]){
				$count++;
				$dept_total++;
			}
			//若目錄下除了misc外沒有其他檔案 就是沒上傳
			$dir = "../../$row3[course_id]/textbook";
			$handle = opendir($dir);
			$own_text = 0; //判斷是否無其他檔案
			//體育中心當作全部已上傳 960425
			if($row2['a_id']=="88"){
				$own_text = 1;
			}
			else{
				while (false !== ($file = readdir($handle))) {  //jp.960326
					if($file!="misc" && $file!="." && $file!=".."){
						$own_text = 1;
						break;
					}
				}
			}
			if($own_text == "0"){
				if( $course_no != $row3["course_no"]){
					$no_textbook++;
					$self_count++;
					
					$answer[$self_count][name]=$row2[name];				//系所名稱
					$answer[$self_count][cname]=$row3[cname];			//課程名稱
					$answer[$self_count][course_no]=$row3[course_no];	//課程編號
					$answer[$self_count][tname]=$row3[tname];			//--9604增加教師名稱欄位
				}else {
					$answer[$self_count][tname].="、".$row3[tname];		//--9604增加教師名稱欄位
				}
				
			}
			$course_no=$row3["course_no"];
			//
			/*若大綱為空、且大綱不是.html、大綱不是.htm、大綱不是.doc、大綱不是.pdf、大綱不是.ppt
			if( $row3[introduction] =="" && !(is_file("../../$row3[course_id]/intro/index.html")) && !(is_file("../../$row3[course_id]/intro/index.htm")) && !(is_file("../../$row3[course_id]/intro/index.doc")) && !(is_file("../../$row3[course_id]/intro/index.pdf")) && !(is_file("../../$row3[course_id]/intro/index.ppt")))
			{
				$no_intro++;
				$self_count++;
				
				$answer[$self_count][name]=$row2[name];				//系所名稱
				$answer[$self_count][cname]=$row3[cname];			//課程名稱
				$answer[$self_count][course_no]=$row3[course_no];	//課程編號
			}
			*/
		}
		if($self_count!=0)
		{
			$Q9 = "INSERT INTO temp_textbook VALUES ( '$row2[a_id]', '$row2[name]', '$dept_total', '$self_count', '0', 0, '' )";
			if ( !($result9 = mysql_db_query( $DB, $Q9 ) ) )
			{
				$message = "$message - 資料庫讀取錯誤9!!";
			}
			
			/*
			$color = "#000066";
			$tpl->assign( COLOR , $color );
			$tpl->assign( YEAR , $row0[year] );
			$tpl->assign( TERM, $row0[term] );
			$tpl->assign( GNAME , "<font color =#FFFFFF>開課單位</font>" );
			$tpl->assign( CNO , "<font color =#FFFFFF>課程編號</font>" );
			$tpl->assign( CNAME , "<font color =#FFFFFF>課程名稱</font>" );
			$tpl->assign( TNAME , "<font color =#FFFFFF>任課教師</font>" );
			$tpl->assign( SELF, "" );
			$tpl->parse( NO_TEXTBOOK_LIST, ".no_textbook_list" );
		
			$color = "#F0FFEE";
			for($i=1;$i<=$self_count;$i++)
			{
				$tpl->assign( COLOR ,  $color );
				$tpl->assign( GNAME , $answer[$i][name] );			//系所名稱
				$tpl->assign( CNAME , $answer[$i][cname] );			//課程名稱
				$tpl->assign( CNO , $answer[$i][course_no] );		//課程編號
				$tpl->assign( TNAME , $answer[$i][tname] );			//任課教師
				if($i==$self_count)
					$tpl->assign(SELF, "<tr bgcolor=\"white\" align=\"center\"><td><font color=\"green\">共計".$dept_total."門課</font></td><td><font color=\"green\">未上傳".$self_count."門</font></td><td colspan=2><font color=\"red\">".sprintf("%0.2f",($self_count/$dept_total)*100)."%</font></td></tr>");
				else
					$tpl->assign(SELF, "");
				$tpl->parse ( NO_TEXTBOOK_LIST, ".no_textbook_list" );
			}
			*/
		}
	}

	$percent = sprintf("%.2f", ($no_textbook/$count)*100);
	/*
	$tpl->assign( PERCENT, "未上傳比率 <font color=red>".$percent."</font>%<br><br>" );
	$tpl->assign( TOTAL, $count );
	$tpl->assign( TEXTBOOK, $no_textbook );
	$tpl->parse( BODY, "body" );
	$tpl->FastPrint("BODY");
	*/
	echo "未上傳比率：".$percent."<br>";
	
}

echo "<table border='1'>";
echo "<tr bgcolor='yellow'><td>開課單位</td><td>開課</td><td>已上傳</td><td>上傳率(%)</td></tr>";

$Q5 = "SELECT distinct parent_id, parent_name from temp_textbook where is_upload='1' order by parent_id";
if ( !($result5 = mysql_db_query( $DB, $Q5 ) ) )
{
	$message = "$message - 資料庫讀取錯誤5!!";
}

while($row5 = mysql_fetch_array($result5))
{
	$parent_id = $row5[parent_id];
	$parent_name = $row5[parent_name];
	
	$Q12 = "SELECT sum(dep_total) dep_sum, sum(self_count) self_sum FROM temp_textbook WHERE parent_id= $parent_id and is_upload='1' ";
	if ( !($result12 = mysql_db_query( $DB, $Q12 ) ) )
	{
		$message = "$message - 資料庫讀取錯誤12!!";
	}
	
	$row12 = mysql_fetch_array($result12);
	
	$dep_sum = $row12['dep_sum'];
	$self_sum = $row12['self_sum'];
	$percent2 = sprintf("%.2f", ($self_sum/$dep_sum)*100);
	
	if( $parent_id == 10)
		echo "<tr bgcolor='green'><td>中心</td><td>$dep_sum</td><td>$self_sum</td><td>$percent2</td></tr>";
	else
		echo "<tr bgcolor='green'><td>$parent_name</td><td>$dep_sum</td><td>$self_sum</td><td>$percent2</td></tr>";
	
	$Q13 = "SELECT dep_name, dep_total, self_count FROM temp_textbook WHERE parent_id = $parent_id and is_upload='1' ORDER BY dep_id";
	if ( !($result13 = mysql_db_query( $DB, $Q13 ) ) )
	{
		$message = "$message - 資料庫讀取錯誤13!!";
	}
		
	while($row13 = mysql_fetch_array($result13))
	{
		$dep_name = $row13['dep_name'];
		$dep_total = $row13['dep_total'];
		$self_count = $row13['self_count'];
		
		$percent_cal = sprintf("%.2f", ($self_count/$dep_total)*100);
		
		echo "<tr><td>$dep_name</td><td>$dep_total</td><td>$self_count</td><td>$percent_cal</td></tr>";
		
	}
}

echo "</table>";

echo "<br><a href=../check_admin.php>回系統管理介面</a>";

echo "</center>";
?>