<?php
	require 'fadmin.php';
	/**********************************
	20090929
	sync_data.php
	確認及更新修課學生作業測驗問卷
	**********************************/
?>
<html>
<head>
<title>同步資料</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
</head>
<body background = "/images/img/bg.gif"><center>
<div><table>
    <tr> 
      <td> 
        <div>
          <font color=#000000>確認及更新修課學生作業,測驗,問卷!!</font>
        </div>
      </td>
    </tr>
</table>
<div id="progress">	　
</div>
<?php
/*	
if ( isset($PHPSESSID) && session_check_admin($PHPSESSID) )
{
	*/
		if(($error = sync_data()) == -1){
			echo "<br>確認及更新完畢!!<br>";
		}
		else{
			echo "$error<br>";
		}
		echo "<br><a href=../check_admin.php>回系統管理介面</a>";
	/*
}
else
	show_page( "index_ad.tpl", "你的權限錯誤，請重新登入!!");
	*/
//同步作業,測驗,問卷
function sync_data()
{
	global $DB_SERVER, $DB_LOGIN, $DB,$DB_PASSWORD;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "資料庫連結錯誤!!";
			return $error;
	}

	//取得所有課程a_id
	//$Q0 = "SELECT * FROM course";
	
	
	//取得當學期所有課程
	$Q0 = "SELECT DISTINCT c.* FROM course as c, teach_course as tc, this_semester as ts 
	       WHERE tc.course_id = c.a_id 
	       AND tc.year=ts.year AND tc.term=ts.term";

	//指定單一課程a_id
	$Q0 = "SELECT * FROM course where a_id='35191'";

	if(!($rs0 = mysql_db_query($DB,$Q0)))
	{
		$error = "mysql資料庫讀取錯誤!!";
		return "$error $Q0<br>";
	}
	$realcount=0;
	$count = 0;
	$temp = -1;
	$total = mysql_num_rows($rs0);
	echo "總共 $total 門課<br>";
	ob_end_flush();
	ob_implicit_flush(1);
	
	while($rows0 = mysql_fetch_array($rs0))
	{
		$count++;	
		$p = number_format((100*$count)/$total, 2);
		if($p>$temp){
			echo "<script language=\"JavaScript\" type=\"text/JavaScript\">
					document.all.progress.innerHTML = \"確認及更新中，請稍侯 $p%\" ; </script>";
		}
		$temp = $p;
		
		echo "<br>課程ID: ".$rows0['a_id']." 名稱: ".$rows0['name']."<br>";
		//取得當學期，每個課程的學生修課資料, 加入正修生(作業、測驗)，問卷尚需確認
		$Q1 = "SELECT tc.student_id FROM take_course as tc, this_semester as ts WHERE tc.course_id = '".$rows0['a_id']."' and tc.year = ts.year and tc.term = ts.term and tc.credit=1";
		if(!($rs1 = mysql_db_query($DB,$Q1)))
		{
			$error = "mysql資料庫讀取錯誤!!";
			return "$error $Q1<br>";
		}
		
		$is_add_homework=0;
		$is_add_exam=0;
		$is_add_questionary=0;
		while($rows1 = mysql_fetch_array($rs1)){
	
			//取得課程的作業		
			$Q2 = "select * from homework";
			if(!($rs2 = mysql_db_query($DB.$rows0['a_id'],$Q2)))
			{
				$error = "mysql資料庫讀取錯誤!!";
				return "$error $Q2<br>";
			}
			while($rows2 = mysql_fetch_array($rs2)){
				if(!isUserInHandin_Homework($rows0['a_id'], $rows2['a_id'], $rows1['student_id']) ){						
						$Q21 = "insert into handin_homework ( homework_id, student_id ) values ('".$rows2['a_id']."', '".$rows1['student_id']."')";
						if ( !($result21 = mysql_db_query( $DB.$rows0['a_id'], $Q21 ) ) ) 
						{									
							echo "新增失敗 $Q21<br>";					
						}
						$is_add_homework++;
				}				
			}
			
			//取得課程的測驗
			$Q2 = "select * from exam";
			if(!($rs2 = mysql_db_query($DB.$rows0['a_id'],$Q2)))
			{
				$error = "mysql資料庫讀取錯誤!!";
				return "$error $Q2<br>";
			}
			while($rows2 = mysql_fetch_array($rs2)){
				if(!isUserInTakeExam($rows0['a_id'], $rows2['a_id'], $rows1['student_id']) ){						
						$Q21 = "Insert Into take_exam (exam_id,student_id,grade) values ('".$rows2['a_id']."', '".$rows1['student_id']."','-1')";
						if ( !($result21 = mysql_db_query( $DB.$rows0['a_id'], $Q21 ) ) ) 
						{		
							echo "新增失敗 $Q21<br>";						
						}
						$is_add_exam++;
				}				
			}
			
			//取得課程的問卷
			$Q2 = "select * from questionary";
			if(!($rs2 = mysql_db_query($DB.$rows0['a_id'],$Q2)))
			{
				$error = "mysql資料庫讀取錯誤!!";
				return "$error $Q2<br>";
			}
			while($rows2 = mysql_fetch_array($rs2)){
				if(!isUserInTakeQuestionary($rows0['a_id'], $rows2['a_id'], $rows1['student_id']) ){						
						$Q21 = "insert into take_questionary (q_id,student_id) values ('".$rows2['a_id']."', '".$rows1['student_id']."')";
						if ( !($result21 = mysql_db_query( $DB.$rows0['a_id'], $Q21 ) ) ) 
						{		
							echo "新增失敗 $Q21<br>";						
						}
						$is_add_questionary++;
				}				
			}
			
		}
		if($is_add_homework!=0 || $is_add_exam!=0 || $is_add_questionary!=0){
			$realcount++;
			echo "修課人數: ".mysql_num_rows($rs1).",  更新情況 = 作業: $is_add_homework  測驗: $is_add_exam  問卷: $is_add_questionary <br>";
		}
					
	}
	echo "<BR>實際更新 $realcount 堂課<BR>";
	return -1;
}


function isUserInCourse($course_id , $student_id){
  global $DB_SERVER, $DB_LOGIN, $DB,$DB_PASSWORD;
  $Q1 = "SELECT homework_id FROM handin_homework WHERE student_id='".$student_id."'";
  $rs = mysql_db_query($DB.$course_id, $Q1);
   if(mysql_num_rows($rs) == 0 ){
        return false;
   }
   else{
        return true;
   }
}
		
function isUserInHandin_Homework($course_id , $homework_id , $student_id){
  global $DB_SERVER, $DB_LOGIN, $DB,$DB_PASSWORD;
  $Q1 = "SELECT homework_id FROM handin_homework WHERE homework_id='".$homework_id."' AND student_id='".$student_id."'";
  $rs = mysql_db_query($DB.$course_id, $Q1);
   if(mysql_num_rows($rs) == 0 ){
        return false;
   }
   else{
        return true;
   }
}

function isUserInTakeExam($course_id , $exam_id , $student_id){
  global $DB_SERVER, $DB_LOGIN, $DB,$DB_PASSWORD;
  $Q1 = "SELECT exam_id FROM take_exam WHERE exam_id='".$exam_id."' AND student_id='".$student_id."'";
  $rs = mysql_db_query($DB.$course_id, $Q1);
   if(mysql_num_rows($rs) == 0 ){
        return false;
   }
   else{
        return true;
   }
}  

function isUserInTakeQuestionary($course_id , $q_id , $student_id){
  global $DB_SERVER, $DB_LOGIN, $DB,$DB_PASSWORD;
  $Q1 = "SELECT q_id FROM take_questionary WHERE q_id='".$q_id."' AND student_id='".$student_id."'";
  $rs = mysql_db_query($DB.$course_id, $Q1);
   if(mysql_num_rows($rs) == 0 ){
        return false;
   }
   else{
        return true;
   }
}

?>
