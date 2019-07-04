
<?php
require 'common.php';
echo "<html>";
$link=mysql_pconnect("$DB_SERVER","$DB_LOGIN","$DB_PASSWORD");
$Q1="select a_id ,name from course_group where is_leaf ='1' order by a_id";
//$Q1 = "select course_group.a_id ,course_group.name gname, course_no.course_id, course.name cname, course_no.course_no from course, course_group, course_no, teach_course, this_semester where course.a_id=course_no.course_id and course_group.a_id=course.group_id and course_no.course_id=teach_course.course_id and teach_course.year=this_semester.year and teach_course.term=this_semester.term order by course.group_id, course_no.course_no";
if ( !($result = mysql_db_query( $DB, $Q1 ) ) )
{
	$message = "$message - 資料庫讀取錯誤!!";
}
$t_a=0;
$t_h=0;
$same_courseid;  // 同門課,不同老師, 解決重覆的問題
$result1 = mysql_db_query("study",$Q1);
 echo "98學年第一學期<br>";
 echo "<table border='1' cellpadding='0' cellspacing='0' style='border-\ 
collapse: collapse' bordercolor='#111111' id='AutoNumber1' width='500'>\n
  <tr>
    <td align='center' width='100'><font size='3'>系所</font></td>
    <td align='center' width='50'><font size='3'>開課數</font></td>
    <td align='center' width='50'><font size='3'>有上傳課程大網</font></td>
    <td align='center' width='50'><font size='3'>比率</font></td>
</font></td>
  </tr>";
while($row1 = mysql_fetch_array( $result1)){
	
	$groupid = $row1['a_id'];
	
	$all=0;
	$have=0;
	//$Q2 = "select course.introduction,course.name,course_no.course_id from course ,course_no where group_id='".$groupid."' and course.a_id=course_no.a_id";
	$Q2 = "select course.introduction ,course_group.a_id ,course_group.name gname,  course.a_id course_id, course.name cname, course.course_no from course, course_group, teach_course, this_semester where course.group_id=".$groupid." and course_group.a_id=course.group_id and course.a_id=teach_course.course_id and teach_course.year=this_semester.year and teach_course.term=this_semester.term order by course.group_id, course.course_no";
	$result2 = mysql_db_query("study",$Q2);	
	while($row2 = mysql_fetch_array($result2)){
		//echo ("----".$row2['name']."<br>");
		if($same_courseid==$row2[course_id]) continue; // 同一門課,跳下一門
		$same_courseid=$row2[course_id];
		$all++;
		if( $row2[introduction] != "" || is_file("../".$row2[course_id]."/intro/index.html") || is_file("../".$row2[course_id]."/intro/index.htm") || is_file("../".$row2[course_id]."/intro/index.doc") || is_file("../".$row2[course_id]."/intro/index.pdf") || is_file("../../$row[course_id]/intro/index.ppt")){
			$have++;
	}	}
	$t_a+=$all;
	$t_h+=$have;
	if($all!=0){

	$temp = sprintf("%-03d", ($have/$all)*100);
echo "<tr>
        <td align='center' width='300'><font size='3'>$row1[a_id] $row1[name]</font></td>
        <td align='left' width='50'><font size='3'>$all</font></td>
        <td align='left' width='50'><font size='3'>$have</font></td>
        <td align='center' width='50'><font size='3'>$temp %</font></td>
       </tr>";

      


	/*	$temp = sprintf("%-03d", ($have/$all)*100);
		echo ($row1[a_id]."/".$row1[name])." ";
		echo $all;
		echo $have;
		echo $temp."% ";
		echo "---------------------<br>";*/
	}	
}
$temp = sprintf("%-03d", ($t_h/$t_a)*100);
echo "</table>";
echo"+++++++++++++++++++++++++++++++++++++++<br>";
echo"共有".$t_a."門課<br>";
echo"有上傳課程大綱的有".$t_h."門課<br>";
echo"上傳的比率 ".$temp."%<br>";

echo "</html><p>";
?>
