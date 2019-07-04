<html>
<?
        mysql_pconnect("localhost" , "study" ,"2720411" ) ;
	$have=0;
	$tag=0;
	$no_have=0;
	$all=0;
	$Q1="select a_id ,name from course_group where is_leaf ='1' order by a_id";
$result1 = mysql_db_query("study",$Q1); // gid 部分
while($row = mysql_fetch_array( $result1)){  // gid
  $groupid = $row['a_id'];

        $sqlstr = "SELECT DISTINCT course.a_id course_id, teach_course.teacher_id, course.introduction, course_group.a_id, course_group.name gname, course.name cname, course.course_no FROM course, course_group, teach_course, this_semester WHERE course.group_id =$groupid AND course_group.a_id = course.group_id AND course.a_id = teach_course.course_id AND teach_course.year = this_semester.year AND teach_course.term = this_semester.term ORDER BY course.group_id, course.course_no" ;

        $result = mysql_db_query("study", $sqlstr) or die ($sqlstr);
        while($row2 = mysql_fetch_array($result)){
		$all++;
        	if( $row2[introduction] != "" || is_file("../".$row2[course_id]."/intro/index.html") || is_file("../".$row2[course_id]."/intro/index.htm") || is_file("../".$row2[course_id]."/intro/index.doc") || is_file("../".$row2[course_id]."/intro/index.pdf") ){

		//echo "teacher_id: $row2[teacher_id] <br>";
		$have++;
		}
		else{   // 沒課程大網者

		$q2="SELECT teach_course.*,course.name FROM `teach_course`,`course` WHERE teach_course.course_id=course.a_id and teach_course.teacher_id=$row2[teacher_id] and name=\"$row2[cname]\" and year=94 and term=1";
		$rt=mysql_db_query("study",$q2) or die ($q2);
		while($row1=mysql_fetch_array($rt)){
		  if($row2[course_id]!=$row1[course_id])
		  {
	           echo "$row2[gname] teacher_id : $row2[teacher_id] course_id.942: $row2[course_id] course_id.941: $row1[course_id] name: $row1[name] ";
		   $q3="SELECT * FROM `course` WHERE a_id =$row1[course_id]";
		   $rt1=mysql_db_query("study",$q3) or die ($q3);
		   $row3=mysql_fetch_array($rt1);
		   if($row3[introduction]!="" || is_file("../".$row1[course_id]."/intro/index.html") || is_file("../".$row1[course_id]."/intro/index.htm") || is_file("../".$row1[course_id]."/intro/index.doc") || is_file("../".$row1[course_id]."/intro/index.pdf" ))
		   echo "================可~";
		   echo "<br>";
		   $tag++;
		  }
		}

		       
		    
		  $no_have++;

		 
		}
		        

	
	}
}
	echo "$no_have/$have/$all<br>";
	echo "tag: $tag";
?>
</html>
