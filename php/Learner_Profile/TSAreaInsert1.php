<?php
/**************************/
/*檔名:TSAreaInsert1.php*/
/*說明:多筆學生資料輸入*/
/*相關檔案:*/
/*TSAreaInsert2.php*/
/*************************/
require 'fadmin.php';
if(!(isset($PHPSESSID) && $check = session_check_teach($PHPSESSID)) )
{
	show_page( "not_access.tpl" ,"權限錯誤");
	exit;
}
if($check != 2)
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
if( isset($stdlist) )
{
	if ( $stdlist == "" ) {
		if($version=="C")
			$message = "無任何資料!!!";
		else
			$message = "No data!!!";
		show_page_d ( $message );
	}
	else
		handl_data();
}
else
	show_page_d ( );

function handl_data() {
	global $stdlist, $version, $course_year, $course_term;
	$student = explode("\r\n",$stdlist);

	//檢查輸入格式是否正確
	$testExpression = true;
	$testEmpty = false;

	for( $i = 0 ; $i < count($student) ; $i++ )
	{
		$expression=ereg(",[[:alnum:]]+#$",$student[$i]) || $student[$i]=="";
		$commaCount=substr_count($student[$i],",");
		if($student[$i]=="")
			$commaPos=-1;
		else
			$commaPos=strpos($student[$i],",");
		$wellCount=substr_count($student[$i],"#");
		if(!$expression || $commaCount>1 || $commaPos==0 || $wellCount>1)
			$testExpression=false;
		if(!$student[$i]=="")
			$testEmpty=true;
	}
	if(!$testExpression || !$testEmpty)
	{
		if($version=="C")
			$message = "格式可能有錯誤,請檢查!!!";
		else
			$message = "The format may have errors,please check!!!";
		show_page_d ( $message );
	}
	else
	{
		$temp1=implode("",$student);
		$temp2=explode("#",$temp1);
		$recordCount=0;
		for($i=0;$i<count($temp2)-1;$i++)
		{
			$temp3=explode(",",$temp2[$i]);
			$stud_name[]=addslashes($temp3[0]);
			$stud_id[]=$temp3[1];
			$recordCount++;
		}

		//檢查使用者輸入的學號中是否有重複
		$repeat_flag = false;
		$kk = "";
		$repeat=array_count_values($stud_id);
		for(reset($repeat);$key=key($repeat);next($repeat))
		{
			if($repeat[$key]>1)
			{
				$repeat_flag = true;
				if($version=="C")
					$kk .= "你所輸入的學生學號:$key 可能有重複,請檢查!!!<br>";
				else
					$kk .= "ID:$key that you input overlaps,please check!!!<br>";
			}
		}
		if($repeat_flag)
			show_page_d ( $kk );
		else {			
			global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id;
			if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
				$message = "資料庫連結錯誤!!";
				show_page_d ( $message );
				return;
			}
			for( $i=0; $i< $recordCount; $i++ )
			{
				$Q1 = "Select * From user u,take_course tc Where u.a_id=tc.student_id And tc.course_id='$course_id' And tc.year = '$course_year' And tc.term = '$course_term' And u.id='$stud_id[$i]' and tc.credit='1'";
				if ( !($resultOBJ = mysql_db_query( $DB, $Q1 ) ) ) {
					$message = "資料庫讀取錯誤!!";
					show_page_d ( $message );
					return;
				}
				if(mysql_num_rows($resultOBJ) != 0)
				{
					$existed = true;
					if($version=="C")
						$kk .= "學號:$stud_id[$i] 重複, 該筆資料已存在!!!<br>";
					else
						$kk .= "ID:$stud_id[$i] overlaps, the record exists!!!<br>";
				}
				else
				{
					add_one_stu($stud_id[$i], $stud_name[$i]);
					$kk .= "學號:$stud_id[$i] 新增完畢!!!<br>";
					
				}
			}
			show_page_d ( $kk );
			/*
			if($existed)
				show_page_d ( $kk );
			else
			{	
				//已由上面的add_one_stu新增完畢，故add_stu用不到
				//add_stu( $recordCount, $stud_id, $stud_name );
			}
			*/
		}
			
	}
}

//新增單筆學生function
function add_one_stu ($id, $name) {
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $course_id, $version, $stdlist, $course_year, $course_term;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$message = "資料庫連結錯誤!!";
		show_page_d ( $message );
		return;
	}

	
	$Q1 = "Select id,name,authorization From user Where id='".$id."'";
	if ( !$resultOBJ = mysql_db_query( $DB, $Q1) )
	{
		$message = "資料庫讀取錯誤!!";
		show_page_d ( $message );
		return;
	}
	
	if(mysql_num_rows($resultOBJ) == 0 )
	{
    	$Q2 = "Insert Into user (name,id,authorization, forbear) values ('$name','$id','3', '1800')";
		if ( !$resultOBJ = mysql_db_query( $DB, $Q2 ) )
		{
			$message = "資料庫寫入錯誤1!!";
			show_page_d ( $message );
			return;
		}
		$a_id = mysql_insert_id();
		$Q3 = "Select group_id From course Where a_id='$course_id'";
		if ( !$resultOBJ = mysql_db_query( $DB, $Q3) )
		{
			$message = "資料庫讀取錯誤1!!";
			show_page_d ( $message );
			return;
		}
		$row2 = mysql_fetch_array($resultOBJ);
		$Q4 = "Insert Into take_course (group_id,course_id,student_id,validated, credit, year, term) values ('".$row2['group_id']."','$course_id','".$a_id."','1','1','$course_year','$course_term')";
		if ( !$resultOBJ = mysql_db_query( $DB, $Q4) )
		{
			$message = "資料庫寫入錯誤2!!";
			show_page_d ( $message );
			return;
		}
		$Q5 = "Select a_id From homework";
		if ( !$resultOBJ = mysql_db_query( $DB.$course_id, $Q5 ) )
		{
			$message = "資料庫讀取錯誤2!!";
			show_page_d ( $message );
			return;
		}
		while($row3 = mysql_fetch_array($resultOBJ))
		{
			$Q6 = "Insert Into handin_homework (homework_id,student_id) values ('".$row3['a_id']."','".$a_id."')";
			if ( !mysql_db_query( $DB.$course_id, $Q6 ) )
			{
				$message = "資料庫寫入錯誤3!!";
				show_page_d ( $message );
				return;
			}
		}
		$Q7 = "Select a_id From exam";
		if ( !$resultOBJ = mysql_db_query( $DB.$course_id, $Q7 ) )
		{
			$message = "資料庫讀取錯誤3!!";
			show_page_d ( $message );
			return;
		}
		while($row4 = mysql_fetch_array($resultOBJ))
		{
			$Q8 = "Insert Into take_exam (exam_id,student_id,grade) values ('".$row4['a_id']."','".$a_id."','-1')";
			if ( !mysql_db_query( $DB.$course_id, $Q8) )
			{
				$message = "資料庫寫入錯誤4!!";
				show_page_d ( $message );
				return;
			}
		}
/*		
		//coop
		$resultcoop = mysql_db_query( $DBC.$course_id, "Select a_id From coop");
		while($row_coop = mysql_fetch_array ( $resultcoop )) {
			mysql_db_query( $DBC.$course_id, "Insert Into coop_".$row_coop['a_id']."_group (group_num,student_id,duty) values ('-1','".$id[$i]."','0')");
			mysql_db_query( $DBC.$course_id, "Insert Into take_coop (case_id,student_id,grade) values ('".$row_coop['a_id']."','".$a_id."','-1')");
		}
*/		
	}
	else
	{
		$row = mysql_fetch_array($resultOBJ);
		if ( $row['authorization'] != '3' ) {
			continue;
		}
		if($row['id'] == $id && $row['name'] != $name )
		{
			$Q2 = "Update user Set name='".$name."' Where id='".$id."'";
			if ( !mysql_db_query( $DB, $Q2 ) )
			{
				$message = "資料庫更新錯誤1!!";
				show_page_d ( $message );
				return;
			}
		}
		
		$Q3 = "Select t.student_id, t.credit From take_course t,user u Where t.course_id='$course_id' and t.year = '$course_year' and t.term = '$course_term' and u.id = '".$id."' and t.student_id = u.a_id";
		if ( !$repeatOBJ = mysql_db_query( $DB, $Q3 ) )
		{
			$message = "資料庫讀取錯誤1!!";
			show_page_d ( $message );
			return;
		}
		
		$Q4 = "Select a_id From user Where id='".$id."'";
		if ( !$resultOBJ = mysql_db_query( $DB, $Q4 ) )
		{
			$message = "資料庫讀取錯誤2!!";
			show_page_d ( $message );
			return;
		}
		$row1 = mysql_fetch_array($resultOBJ);
		
		if(mysql_num_rows ( $repeatOBJ ) != 0) {
			if ( $rowrepeat = mysql_fetch_array( $repeatOBJ ) ) {
				if ( $rowrepeat['credit'] == "1" ) {
					continue;
				}
				else {
					$Q3 = "update take_course set credit = '1' where course_id='$course_id' and student_id = '".$rowrepeat['student_id']."' and year = '$course_year' and term = '$course_term'";
					if ( !$repeatOBJ2 = mysql_db_query( $DB, $Q3 ) )
					{
						$message = "資料庫更新錯誤!!";
						show_page_d ( $message );
						return;
					}
				}
			}
			else {
				continue;
			}
		}
		else {
			$Q5 = "Select group_id From course Where a_id='$course_id'";
			if ( !$resultOBJ = mysql_db_query( $DB, $Q5 ) )
			{
				$message = "資料庫讀取錯誤3!!";
				show_page_d ( $message );
				return;
			}
			$row2 = mysql_fetch_array($resultOBJ);
			
			$Q6 = "Insert Into take_course (group_id,course_id,student_id,validated, credit, year, term) values('".$row2['group_id']."','$course_id','".$row1['a_id']."','1','1','$course_year','$course_term')";
			if ( !$resultOBJ = mysql_db_query( $DB, $Q6 ) )
			{
				$message = "資料庫寫入錯誤1!!";
				show_page_d ( $message );
				return;
			}
		}
		
		$Q7 = "Select a_id From homework";
		if ( !$resultOBJ = mysql_db_query( $DB.$course_id, $Q7 ) )
		{
			$message = "資料庫讀取錯誤4!!";
			show_page_d ( $message );
			return;
		}
		while($row3 = mysql_fetch_array($resultOBJ))
		{
			$Q8 = "Insert Into handin_homework (homework_id,student_id) values('".$row3['a_id']."','".$row1['a_id']."')";
			if ( !mysql_db_query( $DB.$course_id, $Q8 ) )
			{
				$message = "資料庫寫入錯誤2!!";
				show_page_d ( $message );
				return;
			}
		}
		$Q9 = "Select a_id From exam";
		if ( !$resultOBJ = mysql_db_query( $DB.$course_id, $Q9 ) )
		{
			$message = "資料庫讀取錯誤5!!";
			show_page_d ( $message );
			return;
		}
		while($row4 = mysql_fetch_array($resultOBJ))
		{
			$Q10 = "Insert Into take_exam (exam_id,student_id,grade) values ('".$row4['a_id']."','".$row1['a_id']."','-1')";
			if ( !mysql_db_query( $DB.$course_id, $Q10 ) )
			{
				$message = "資料庫寫入錯誤3!!";
				show_page_d ( $message );
				return;
			}
		}
/*		
		//coop
		$resultcoop = mysql_db_query( $DBC.$course_id, "Select a_id From coop");
		while($row_coop = mysql_fetch_array ( $resultcoop )) {
			mysql_db_query( $DBC.$course_id, "Insert Into coop_".$row_coop['a_id']."_group (group_num,student_id,duty) values ('-1','".$id[$i]."','0')");
			mysql_db_query( $DBC.$course_id, "Insert Into take_coop (case_id,student_id,grade) values ('".$row_coop['a_id']."','".$row1['a_id']."','-1')");
		}*/
	}
	
	include("Generate_studinfo.php");
	if ( $version =="C" )
		$message = "學生已加入完成";
	else
		$message = "Students Add Complete!!!";
	include("Generate_studinfo.php");
	$stdlist = "";
	show_page_d ( $message );
}

function add_stu ( $recordCount, $id, $name ) {
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $course_id, $version, $stdlist, $course_year, $course_term;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$message = "資料庫連結錯誤!!";
		show_page_d ( $message );
		return;
	}

	for($i=0 ; $i < $recordCount ; $i++ )
	{
		$Q1 = "Select id,name,authorization From user Where id='".$id[$i]."'";
		if ( !$resultOBJ = mysql_db_query( $DB, $Q1) )
		{
			$message = "資料庫讀取錯誤!!";
			show_page_d ( $message );
			return;
		}
		
		if(mysql_num_rows($resultOBJ) == 0 )
		{
      			$Q2 = "Insert Into user (name,id,authorization, forbear) values ('$name[$i]','$id[$i]','3', '1800')";
			if ( !$resultOBJ = mysql_db_query( $DB, $Q2 ) )
			{
				$message = "資料庫寫入錯誤1!!";
				show_page_d ( $message );
				return;
			}
			$a_id = mysql_insert_id();
			$Q3 = "Select group_id From course Where a_id='$course_id'";
			if ( !$resultOBJ = mysql_db_query( $DB, $Q3) )
			{
				$message = "資料庫讀取錯誤1!!";
				show_page_d ( $message );
				return;
			}
			$row2 = mysql_fetch_array($resultOBJ);
			$Q4 = "Insert Into take_course (group_id,course_id,student_id,validated, credit, year, term) values ('".$row2['group_id']."','$course_id','".$a_id."','1','1','$course_year','$course_term')";
			if ( !$resultOBJ = mysql_db_query( $DB, $Q4) )
			{
				$message = "資料庫寫入錯誤2!!";
				show_page_d ( $message );
				return;
			}
			$Q5 = "Select a_id From homework";
			if ( !$resultOBJ = mysql_db_query( $DB.$course_id, $Q5 ) )
			{
				$message = "資料庫讀取錯誤2!!";
				show_page_d ( $message );
				return;
			}
			while($row3 = mysql_fetch_array($resultOBJ))
			{
				$Q6 = "Insert Into handin_homework (homework_id,student_id) values ('".$row3['a_id']."','".$a_id."')";
				if ( !mysql_db_query( $DB.$course_id, $Q6 ) )
				{
					$message = "資料庫寫入錯誤3!!";
					show_page_d ( $message );
					return;
				}
			}
			$Q7 = "Select a_id From exam";
			if ( !$resultOBJ = mysql_db_query( $DB.$course_id, $Q7 ) )
			{
				$message = "資料庫讀取錯誤3!!";
				show_page_d ( $message );
				return;
			}
			while($row4 = mysql_fetch_array($resultOBJ))
			{
				$Q8 = "Insert Into take_exam (exam_id,student_id,grade) values ('".$row4['a_id']."','".$a_id."','-1')";
				if ( !mysql_db_query( $DB.$course_id, $Q8) )
				{
					$message = "資料庫寫入錯誤4!!";
					show_page_d ( $message );
					return;
				}
			}
/*			
			//coop
			$resultcoop = mysql_db_query( $DBC.$course_id, "Select a_id From coop");
			while($row_coop = mysql_fetch_array ( $resultcoop )) {
				mysql_db_query( $DBC.$course_id, "Insert Into coop_".$row_coop['a_id']."_group (group_num,student_id,duty) values ('-1','".$id[$i]."','0')");
				mysql_db_query( $DBC.$course_id, "Insert Into take_coop (case_id,student_id,grade) values ('".$row_coop['a_id']."','".$a_id."','-1')");
			}
*/			
		}
		else
		{
			$row = mysql_fetch_array($resultOBJ);
			if ( $row['authorization'] != '3' ) {
				continue;
			}
			if($row['id'] == $id[$i] && $row['name'] != $name[$i] )
			{
				$Q2 = "Update user Set name='".$name[$i]."' Where id='".$id[$i]."'";
				if ( !mysql_db_query( $DB, $Q2 ) )
				{
					$message = "資料庫更新錯誤1!!";
					show_page_d ( $message );
					return;
				}
			}
			
			$Q3 = "Select t.student_id, t.credit From take_course t,user u Where t.course_id='$course_id' and t.year = '$course_year' and t.term = '$course_term' and u.id = '".$id[$i]."' and t.student_id = u.a_id";
			if ( !$repeatOBJ = mysql_db_query( $DB, $Q3 ) )
			{
				$message = "資料庫讀取錯誤1!!";
				show_page_d ( $message );
				return;
			}
			
			$Q4 = "Select a_id From user Where id='".$id[$i]."'";
			if ( !$resultOBJ = mysql_db_query( $DB, $Q4 ) )
			{
				$message = "資料庫讀取錯誤2!!";
				show_page_d ( $message );
				return;
			}
			$row1 = mysql_fetch_array($resultOBJ);
			
			if(mysql_num_rows ( $repeatOBJ ) != 0) {
				if ( $rowrepeat = mysql_fetch_array( $repeatOBJ ) ) {
					if ( $rowrepeat['credit'] == "1" ) {
						continue;
					}
					else {
						$Q3 = "update take_course set credit = '1' where course_id='$course_id' and student_id = '".$rowrepeat['student_id']."' and year = '$course_year' and term = '$course_term'";
						if ( !$repeatOBJ2 = mysql_db_query( $DB, $Q3 ) )
						{
							$message = "資料庫更新錯誤!!";
							show_page_d ( $message );
							return;
						}
					}
				}
				else {
					continue;
				}
			}
			else {
				$Q5 = "Select group_id From course Where a_id='$course_id'";
				if ( !$resultOBJ = mysql_db_query( $DB, $Q5 ) )
				{
					$message = "資料庫讀取錯誤3!!";
					show_page_d ( $message );
					return;
				}
				$row2 = mysql_fetch_array($resultOBJ);
				
				$Q6 = "Insert Into take_course (group_id,course_id,student_id,validated, credit, year, term) values('".$row2['group_id']."','$course_id','".$row1['a_id']."','1','1','$course_year','$course_term')";
				if ( !$resultOBJ = mysql_db_query( $DB, $Q6 ) )
				{
					$message = "資料庫寫入錯誤1!!";
					show_page_d ( $message );
					return;
				}
			}
			
			$Q7 = "Select a_id From homework";
			if ( !$resultOBJ = mysql_db_query( $DB.$course_id, $Q7 ) )
			{
				$message = "資料庫讀取錯誤4!!";
				show_page_d ( $message );
				return;
			}
			while($row3 = mysql_fetch_array($resultOBJ))
			{
				$Q8 = "Insert Into handin_homework (homework_id,student_id) values('".$row3['a_id']."','".$row1['a_id']."')";
				if ( !mysql_db_query( $DB.$course_id, $Q8 ) )
				{
					$message = "資料庫寫入錯誤2!!";
					show_page_d ( $message );
					return;
				}
			}
			$Q9 = "Select a_id From exam";
			if ( !$resultOBJ = mysql_db_query( $DB.$course_id, $Q9 ) )
			{
				$message = "資料庫讀取錯誤5!!";
				show_page_d ( $message );
				return;
			}
			while($row4 = mysql_fetch_array($resultOBJ))
			{
				$Q10 = "Insert Into take_exam (exam_id,student_id,grade) values ('".$row4['a_id']."','".$row1['a_id']."','-1')";
				if ( !mysql_db_query( $DB.$course_id, $Q10 ) )
				{
					$message = "資料庫寫入錯誤3!!";
					show_page_d ( $message );
					return;
				}
			}
/*			
			//coop
			$resultcoop = mysql_db_query( $DBC.$course_id, "Select a_id From coop");
			while($row_coop = mysql_fetch_array ( $resultcoop )) {
				mysql_db_query( $DBC.$course_id, "Insert Into coop_".$row_coop['a_id']."_group (group_num,student_id,duty) values ('-1','".$id[$i]."','0')");
				mysql_db_query( $DBC.$course_id, "Insert Into take_coop (case_id,student_id,grade) values ('".$row_coop['a_id']."','".$row1['a_id']."','-1')");
			}*/
		}
	}
	include("Generate_studinfo.php");
	if ( $version =="C" )
		$message = "學生已加入完成";
	else
		$message = "Students Add Complete!!!";
	include("Generate_studinfo.php");
	$stdlist = "";
	show_page_d ( $message );
}

function show_page_d ( $message = "" ) {
	global $version, $stdlist, $skinnum;
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate("./templates");
	
	if($version=="C")
		$tpl->define(array(main => "TSAreaInsert1_Ch.tpl"));
	else
		$tpl->define(array(main => "TSAreaInsert1_En.tpl"));
	$tpl->assign( SKINNUM , $skinnum );
	$tpl->assign(MESSAGE, $message);
	$tpl->assign(VALUE, $stdlist);
	$tpl->parse(BODY, "main");
	$tpl->FastPrint("BODY");
}

?>
