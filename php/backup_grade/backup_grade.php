<?php
require 'fadmin.php';
?>
<html>
<head>
<title>備分當年度成績資料</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
</head>
<body background = "/images/img/bg.gif"><center>
<div><table>
    <tr> 
      <td> 
        <div>
          <font color=#000000>備分當年度成績資料!</font>
        </div>
      </td>
    </tr>
</table>
<div id="progress1">
</div>
<BR>
<div id="progress">
</div>
<?php
//選出今年有開課的所有的課程 course_id , year , term
if (!(isset($PHPSESSID) && session_check_admin($PHPSESSID)) ) {
		show_page( "not_access.tpl" ,"權限錯誤");
}	
if(backup_grade()){
	echo "備分當年度成績完畢!<br>";
}
else{
	echo "$error<br>";
}

echo "<a href=../check_admin.php>回系統管理介面</a>";


//把handin_homework 導去 bugrade->handin_homework
function backup_grade(){
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	$buDB = "bugrade";
	
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) )
	{
		$error = "資料庫連結錯誤!!";
		return $error;
	}
	$Q0 = "SELECT * FROM this_semester";
	$result0 = mysql_db_query($DB, $Q0);
	if(!$result0)
	{
		$error = "資料庫讀取錯誤!! $Q0";
		return $error;
	}
	$array=mysql_fetch_array($result0);
	if(!$array)
	{
		$error = "沒有學期資料";
		return $error;
	}
	$year = $array['year'];
	$term = $array['term'];
	
	//先備份Takcih
	$Q1 = "SELECT * FROM take_course WHERE year='".$year."' and term = '".$term."'";
	$result1 = mysql_db_query($DB, $Q1);
	if(!$result1)
	{
		$error = "資料庫讀取錯誤!! $Q1";
		return $error;
	}
	$count = 0;
	$temp = -1;
	$total = mysql_num_rows($result1);
	ob_end_flush();
	ob_implicit_flush(1);
	while($row1=mysql_fetch_array($result1))
	{
		$count++;
		$p = number_format((100*$count)/$total, 2);
		if($p>$temp){
			echo "<script language=\"JavaScript\" type=\"text/JavaScript\">
					document.all.progress1.innerHTML = \"備份修課資料更新中，請稍侯 $p%\" ; </script>";
		}
		$temp = $p;
		$Qs = "Select * From take_course Where group_id='$row1[group_id]' and course_id='$row1[course_id]' and student_id='$row1[student_id]' and year='$year' and term='$term'";
		$result11 = mysql_db_query($buDB, $Qs);
		//沒有這筆資料
		if ( mysql_num_rows( $result11) ==0){
			$Qu="Insert into take_course values('$row1[group_id]', '$row1[course_id]', '$row1[student_id]' ,'$year', '$term', '$row1[validated]', '$row1[note]', '$row1[credit]', '$row1[mtime]')";
		}
		//有資料
	    else{ 
			$Qu="Update take_course Set group_id='$row1[group_id]', course_id='$row1[course_id]', student_id='$row1[student_id]', year='$year', term='$term', validated='$row1[validated]', note='$row1[note]', credit='$row1[credit]', mtime='$row1[mtime]' WHERE group_id='$row1[group_id]' AND course_id='$row1[course_id]' AND student_id='$row1[student_id]' AND year='$year' AND term='$term'";		
		}
		if(!($result12= mysql_db_query($buDB, $Qu))){
			echo"資料庫讀取錯誤!!!$Qu";
			exit;
		}
	}
	//
	$Q2 = "SELECT distinct course_id FROM teach_course WHERE year=".$array['year']." AND term=".$array['term'];
	$result2 = mysql_db_query($DB, $Q2);
	if(!$result2)
	{
		$error = "資料庫讀取錯誤!! $Q2";
		return $error;
	}

	$count = 0;
	$temp = -1;
	$total = mysql_num_rows($result2);
	echo "總共 $total 門課<br>";
	ob_end_flush();
	ob_implicit_flush(1);
	while($row2=mysql_fetch_array($result2))
	{
		$count++;
		$p = number_format((100*$count)/$total, 2);
		if($p>$temp){
			echo "<script language=\"JavaScript\" type=\"text/JavaScript\">
					document.all.progress.innerHTML = \"備份成績資料更新中，請稍侯 $p%\" ; </script>";
		}
		$temp = $p;
		if(!is_dir("../../old_grade/".$year))
		{
			mkdir("../../old_grade/".$year, 0700);//建立目錄
		}
		//學期資料夾 1,2
		if(!is_dir("../../old_grade/".$year."/".$term))
		{
			mkdir("../../old_grade/".$year."/".$term, 0700);//建立目錄				
		}	
		//課程資料夾 course_id
		if(!is_dir("../../old_grade/".$year."/".$term."/".$course_id))
		{
			mkdir("../../old_grade/".$year."/".$term."/".$course_id, 0700);//建立目錄
		}
		$course_id = $row2['course_id'];
		buExam($course_id, $year, $term, $buDB);
		buTake_exam($course_id, $year, $term, $buDB);
		buHomework($course_id, $year, $term, $buDB);
		buHandin_homework($course_id, $year, $term, $buDB);
	}
	return 1;
}


//把Exam 導去 bugrade->exam
function buExam($course_id,$year,$term,$buDB){
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) )
	{
		$error = "資料庫連結錯誤!!";
		return $error;
	}
	
	$Qs="Select * From exam ";
	if(!($results= mysql_db_query($DB.$course_id, $Qs))){
		echo"資料庫讀取錯誤!!!";
		exit;
	}
	
	while($rows=mysql_fetch_array($results)){
		$rows[chap_num] = addslashes($rows[chap_num]);
		$rows[name] = addslashes($rows[name]);
	    $Qs = "Select * From exam Where a_id='$rows[a_id]' and course_id='$course_id' and year='$year' and term='$term'";
		$result1 = mysql_db_query($buDB, $Qs);
		//沒有這筆資料
		if ( mysql_num_rows( $result1) ==0){
			$Qu="Insert into exam values('$rows[a_id]', '$course_id', '$year', '$term', '$rows[chap_num]', '$rows[name]', '$rows[is_online]', '$rows[beg_time]', '$rows[end_time]', '$rows[public]', '$rows[percentage]', '$rows[mtime]')";
		}
		//有資料
	    else{ 
			$Qu="Update exam Set a_id='$rows[a_id]', course_id='$course_id', year='$year', term='$term', chap_num='$rows[chap_num]', name='$rows[name]', is_online='$rows[is_online]', beg_time='$rows[beg_time]', end_time='$rows[end_time]', public='$rows[public]', percentage='$rows[percentage]', mtime='$rows[mtime]' Where a_id='$rows[a_id]' and course_id='$course_id' and year='$year' and term='$term'";		
		}
		if(!($result2= mysql_db_query($buDB, $Qu))){
			echo"資料庫讀取錯誤!!!$Qu";
			exit;
		}
	}
}

//把homework 導去 bugrade->homework
function buHomework($course_id,$year,$term,$buDB){
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) )
	{
		$error = "資料庫連結錯誤!!";
		return $error;
	}
	
	$Qs="Select * From homework ";
	if(!($results= mysql_db_query($DB.$course_id, $Qs))){
		echo"資料庫讀取錯誤!!!";
		exit;
	}
	
	while($rows=mysql_fetch_array($results)){
		$rows[chap_num] = addslashes($rows[chap_num]);
		$rows[name] = addslashes($rows[name]);
	    $Qs = "Select * From homework Where a_id='$rows[a_id]' and course_id='$course_id' and year='$year' and term='$term'";
		$result1 = mysql_db_query($buDB, $Qs);
		//沒有這筆資料
		if ( mysql_num_rows( $result1) ==0){
			$Qu="Insert into homework values('$rows[a_id]', '$course_id', '$year', '$term', '$rows[chap_num]', '$rows[name]', '$rows[public]', '$rows[percentage]', '$rows[due]', '$rows[mtime]')";
		}
		//有資料
	    else{ 
			$Qu="Update homework Set a_id='$rows[a_id]', course_id='$course_id', year='$year', term='$term', chap_num='$rows[chap_num]', name='$rows[name]', public='$rows[public]', percentage='$rows[percentage]', due='$rows[due]', mtime='$rows[mtime]' Where a_id='$rows[a_id]' and course_id='$course_id' and year='$year' and term='$term'";		
		}
		if(!($result2= mysql_db_query($buDB, $Qu))){
			echo"資料庫讀取錯誤!!!$Qu";
			exit;
		}
	}

}

//把take_exam 導去 bugrade->take_exam
function buTake_exam($course_id,$year,$term,$buDB){
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) )
	{
		$error = "資料庫連結錯誤!!";
		return $error;
	}
	
	$Qs="Select * From take_exam ";
	if(!($results= mysql_db_query($DB.$course_id, $Qs))){
		echo"資料庫讀取錯誤!!!";
		exit;
	}
	
	while($rows=mysql_fetch_array($results)){
	    $Qs = "Select * From take_exam Where exam_id='$rows[exam_id]' and student_id='$rows[student_id]' and course_id='$course_id' and year='$year' and term='$term'";
		$result1 = mysql_db_query($buDB, $Qs);
		//沒有這筆資料
		if ( mysql_num_rows( $result1) ==0){
			if($rows[grade] != NULL){
				$Qu="Insert into take_exam values( '$rows[exam_id]', '$rows[student_id]','$course_id', '$year', '$term', '$rows[grade]', '$rows[nonqa_grade]', '$rows[mtime]', '$rows[public]')";
			}
			else
			{
				$Qu="Insert into take_exam values( '$rows[exam_id]', '$rows[student_id]','$course_id', '$year', '$term', NULL, '$rows[nonqa_grade]', '$rows[mtime]', '$rows[public]')";
			}
		}
		//有資料
	    else{
			if($rows[grade] != NULL){
				$Qu="Update take_exam Set exam_id='$rows[exam_id]', student_id='$rows[student_id]', course_id='$course_id', year='$year', term='$term', grade='$rows[grade]', nonqa_grade='$rows[nonqa_grade]',  mtime='$rows[mtime]', public='$rows[public]'  Where exam_id='$rows[exam_id]' and student_id='$rows[student_id]' and course_id='$course_id' and year='$year' and term='$term'";		
			}else{
				$Qu="Update take_exam Set exam_id='$rows[exam_id]', student_id='$rows[student_id]', course_id='$course_id', year='$year', term='$term', grade=NULL, nonqa_grade='$rows[nonqa_grade]',  mtime='$rows[mtime]', public='$rows[public]'  Where exam_id='$rows[exam_id]' and student_id='$rows[student_id]' and course_id='$course_id' and year='$year' and term='$term'";		
			}
		}
		if(!($result2= mysql_db_query($buDB, $Qu))){
			echo"資料庫讀取錯誤!!!$Qu";
			exit;
		}
	}
}

//把handin_homework 導去 bugrade->handin_homework
function buHandin_homework($course_id,$year,$term,$buDB){
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) )
	{
		$error = "資料庫連結錯誤!!";
		return $error;
	}
	
	$Qs="Select * From handin_homework ";
	if(!($results= mysql_db_query($DB.$course_id, $Qs))){
		echo"資料庫讀取錯誤!!!";
		exit;
	}
	
	while($rows=mysql_fetch_array($results)){
	    $Qs = "Select * From handin_homework Where homework_id='$rows[homework_id]' and student_id='$rows[student_id]' and course_id='$course_id' and year='$year' and term='$term'";
		$result1 = mysql_db_query($buDB, $Qs);
		//沒有這筆資料
		if ( mysql_num_rows( $result1) ==0){
			if($rows[grade] != NULL){
				$Qu="Insert into handin_homework values('$rows[homework_id]', '$rows[student_id]', '$course_id', '$year', '$term', '$rows[grade]', '$rows[public]', '$rows[handin_time]', '$rows[mtime]')";
			}else{
				$Qu="Insert into handin_homework values('$rows[homework_id]', '$rows[student_id]', '$course_id', '$year', '$term', NULL, '$rows[public]', '$rows[handin_time]', '$rows[mtime]')";
			}	
		}
		//有資料
	    else{ 
			if($rows[grade] != NULL){
				$Qu="Update handin_homework Set homework_id='$rows[homework_id]', student_id='$rows[student_id]', course_id='$course_id', year='$year', term='$term', grade='$rows[grade]', public='$rows[public]', handin_time='$rows[handin_time]', mtime='$rows[mtime]' Where homework_id='$rows[homework_id]' and student_id='$rows[student_id]' and course_id='$course_id' and year='$year' and term='$term'";		
			}else{
				$Qu="Update handin_homework Set homework_id='$rows[homework_id]', student_id='$rows[student_id]', course_id='$course_id', year='$year', term='$term', grade=NULL, public='$rows[public]', handin_time='$rows[handin_time]', mtime='$rows[mtime]' Where homework_id='$rows[homework_id]' and student_id='$rows[student_id]' and course_id='$course_id' and year='$year' and term='$term'";
			}
		}
		if(!($result2= mysql_db_query($buDB, $Qu))){
			echo"資料庫讀取錯誤!!!$Qu";
			exit;
		}
	}
}


?>
