<?php
require_once('fadmin.php');

if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) )
{
	echo ( "資料庫連結錯誤!!" );
	return;
}

$course_id = $_SESSION["course_id"];
$course_year = $_SESSION["course_year"]?$_SESSION["course_year"]:$_SESSION["hist_year"];
$course_term = $_SESSION["course_term"]?$_SESSION["course_term"]:$_SESSION["hist_term"];
if($_SESSION["teacher"] == 1) 
{
	$Q1 = "Select u.id from user u, take_course tc 
			Where tc.course_id='$course_id' 
			and tc.year = '$course_year' 
			and tc.term = '$course_term' 
			and tc.student_id = u.a_id ";
//echo $Q1;
	if ( !($resultOBJ = mysql_db_query( $DB, $Q1 ) ) ) {
		echo ( "資料庫讀取錯誤!!" );
		return;
	}
	if(!$resnum = mysql_num_rows($resultOBJ))
	{
		echo "沒有修課學生"; 
	}
	else
	{
		$execution = "tar -zcf ../../$course_id/pic.tar.gz";
		while( $row = mysql_fetch_array ( $resultOBJ ) )
		{
		  $attachment_location = "../../S0t1u2_3P4h5o6t7o8/".$row['id'].".jpg";
		  //$files[] = "../../S0t1u2_3P4h5o6t7o8/".$row['id'].".jpg";
		  $execution .= " ../../S0t1u2_3P4h5o6t7o8/".$row['id'].".jpg";
		  //$spic = "<img src=\"$attachment_location\" width=\"103\" height=\"133\" alt=\"沒有學生照片\">";
		  //echo $spic . "<br/>";  
		}
		exec($execution);
		header("Location: ../../$course_id/pic.tar.gz"); 
		
	}	
}
else
{
echo "權限錯誤";
}

?>
