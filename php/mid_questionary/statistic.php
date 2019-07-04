<?php
require 'fadmin.php';
require './templates/top.tpl';
//if( isset($PHPSESSID) && (session_check_teach($PHPSESSID) >= 2) )
//{
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $version, $q_id;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	} 
	//select所有學院
	$Q1 = "SELECT a_id, name FROM course_group WHERE parent_id=1 AND a_id!=98";
	if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
		show_page( "not_access.tpl" ,"資料庫讀取錯誤1!!" );
	}		
	if( mysql_num_rows($result) != 0)
	{
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate("./templates");
		$tpl->define(array(main=>"statistic_college.tpl"));
		$tpl->define_dynamic("row","main");
		while ( $rows = mysql_fetch_array( $result ) ) {
			$tpl->assign(COLLEGE,$rows['name']);
			$tpl->assign(CID,$rows['a_id']);
			$tpl->assign(QUESYEAR,$year);
			$tpl->assign(QUESTERM,$term);
			$tpl->assign(QID, $q_id);
			$tpl->parse(ROWS,".row");
		}
	}
	$tpl->parse(BODY,"main");
 	$tpl->FastPrint("BODY");
	
	if ($c_id!="") //select該學院的系所
	{
		$Q2 = "SELECT a_id, name FROM course_group WHERE parent_id = $c_id and a_id!=92 order by name";
		if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫讀取錯誤2!!" );
		}
		if( mysql_num_rows($result2) != 0)
		{
			$tpl = new FastTemplate("./templates");
			$tpl->define(array(main=>"statistic_department.tpl"));
			$tpl->define_dynamic("row","main");
			while ( $rows2 = mysql_fetch_array( $result2 ) ) {
				$tpl->assign(DEPART,$rows2['name']);
				$tpl->assign(DID,$rows2['a_id']);
				$tpl->assign(QUESYEAR,$year);
				$tpl->assign(QUESTERM,$term);
				$tpl->assign(QID, $q_id);
				$tpl->parse(ROWS,".row");
			}
		}
		$tpl->parse(BODY,"main");
 		$tpl->FastPrint("BODY");
	}
	
	if ($d_id!="")  //select該系所的課程
	{
		
		$Q3 = "SELECT course.name as cname, course.course_no, course.a_id, course_group.name as gname 
			FROM course_group, teach_course, course, user
			WHERE teach_course.year = $year 
			AND teach_course.term = $term
			AND course.a_id = teach_course.course_id 
			AND course.group_id = $d_id 
			AND teach_course.teacher_id = user.a_id 
			AND course_group.a_id = $d_id
			AND user.authorization = 1 Group by course_no";
		if ( !($result3 = mysql_db_query( $DB, $Q3 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫讀取錯誤3!!" );
		}
		
		
		//將結果寫入檔案以供下載
//		$QQ = "select name from course_group where a_id = $d_id ";
//		$rowsQQ = mysql_fetch_row((mysql_db_query($DB, $QQ)));
//		$file_name2="./download/".$year."_0".$term."_".$rowsQQ[0].".xls";
//		if(file_exists($file_name2))
// 			unlink($file_name2);
//	 	$file2=fopen("$file_name2","w");
//		fwrite($file2,"系所\t課程編號\t課程名稱\t授課教師\t學分\t課程滿意度\t課程滿意度百分比\t修課人數\t填寫人數\t填寫率\n");
		
		if( mysql_num_rows($result3) != 0)
		{
			$tpl = new FastTemplate("./templates");
			$tpl->define(array(main=>"statistic_course.tpl"));
			$tpl->define_dynamic("row","main");
			 
			while ( $rows3 = mysql_fetch_array( $result3 ) ) {
				if ( $color == "#BFCEBD" )
					$color = "#D0DFE3";
				else $color = "#BFCEBD";
				
				//寫入檔案用的系所名稱變數:$departname
//				$departname = $rows3['gname'];
				//寫入檔案用的課程編號變數:$courseno
//				$courseno = $rows3['course_no'];
				
				//寫入檔案用的課程名稱變數:$coursename
//				$coursename = $rows3['cname'];
				
				//選出授課老師的名稱
				$Q5 = "select distinct u.name FROM user u , teach_course tc where tc.course_id = '".$rows3['a_id']."' and tc.teacher_id = u.a_id and u.authorization='1' and tc.year='$year' and tc.term='$term'";
				if ( !($result5 = mysql_db_query( $DB, $Q5 ) ) ) {
					$message = "$message - 資料庫讀取錯誤4!!";
				}
				$name = "";
//				$nameforsave = "";
				while ( $row5 = mysql_fetch_array( $result5 ) )
				{
					if ( $row5['name'] != NULL )
					{
						//寫入檔案用的授課教師變數:$nameforsave
						$name = $name.$row5['name']."<br>";
//						$nameforsave = $nameforsave.$row5['name']." ";
					}
				}
				
				$tpl->assign( DEPART,$rows3['gname']);
				$tpl->assign( COLOR , $color );
				$tpl->assign( CNO,$rows3['course_no'] );
				$tpl->assign( COURSE,$rows3['cname'] );
				$tpl->assign( TEACHER, $name );
//				$course_no = explode("_",$rows3['course_no']);
//				$cour_cd = $course_no[0];
				
				// 連結選課系統資料庫
//				if( !($cnx = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) ){
//					Error_handler( "在 sybase_connect 有錯誤發生" , $cnx );
//				}
				
//				if( substr($cour_cd, 3,1) == A || substr($cour_cd, 3,1) == B || substr($cour_cd, 3,1) == C )
//					$SDB = "academic_gra";
//				else
//					$SDB = "academic";
				
//				$csd = @sybase_select_db($SDB, $cnx);
				
				// 取得學分數
//				$Qs = "select credit from a30vcourse_tea where course_no = '$cour_cd'";
//				$cur = sybase_query($Qs, $cnx);
//				if(!$cur) {
//					Error_handler( "在 sybase_exec 有錯誤發生( 沒有指標傳回 ) " , $cnx );
//				}
//				if( ( $array=sybase_fetch_array($cur) ) != 0 )
//					$tpl->assign( CREDIT, $array['credit'] );
//				else
//					$tpl->assign( CREDIT,"" );
				//寫入檔案用的學分數變數:$credit
//				$credit = $array['credit'];
				$Q4 = "SELECT * FROM mid_statistic WHERE course_no = '".$rows3['a_id']."' and q_id='$q_id'";
				if ( !($result4 = mysql_db_query( $DB, $Q4 ) ) ) {
					show_page( "not_access.tpl" ,"資料庫讀取錯誤5!!" );
				}
				$rows4 = mysql_fetch_array( $result4 );
				if ($rows4['satisfy'] == null || $rows4['satisfy'] == "")
				{
					$tpl->assign( SATISFY, "學生未填寫" );
					//寫入檔案用的課程滿意度變數:$satisfy
//					$satisfy = "學生未填寫";
					$tpl->assign( PERCENT, "未有百分比" );
					//寫入檔案用的課程滿意度百分比變數:$percentsec
//					$percentsec = "未有百分比";
				}
				else
				{
					$tpl->assign( SATISFY, "<font color=\"red\">".$rows4['satisfy']."</font>" );
					//寫入檔案用的課程滿意度變數:$satisfy
//					$satisfy = $rows4['satisfy'];
					
					$percent = ( $rows4['satisfy'] / 5 ) * 100;
					//寫入檔案用的課程滿意度百分比變數:$percentsec
					$percentsec = number_format($percent, 2);
					$tpl->assign( PERCENT, "<font color=\"red\">$percentsec</font>%" );
				}
				$Q5 = "select * from take_course where course_id='".$rows3['a_id']."' and credit='1' and year='".$year."' and term='".$term."'";
				$result5 = mysql_db_query($DB, $Q5);
				//寫入檔案用的修課人數變數:$nums
				$nums = mysql_num_rows($result5);
				$tpl->assign( COUNT, $nums."人" );
				
				if($rows4['fill_count'] == "" || $rows4['fill_count'] == "NULL" )
				{
					$tpl->assign( FILLED, "0人" );
					//寫入檔案用的填寫人數變數:$filled
//					$filled = "0";
					$tpl->assign( FIPER, "未有人填寫" );
					//寫入檔案用的填寫率變數:$filledpercentsec
//					$filledpercentsec = "未有人填寫";
				}
				else
				{
					$tpl->assign( FILLED,$rows4['fill_count']."人" );
					//寫入檔案用的填寫人數變數:$filled
//					$filled = $rows4['fill_count'];
					$filled_percent = ( $rows4['fill_count'] / $nums ) * 100;
					//寫入檔案用的填寫率變數:$filperpercentsec
					$filledpercentsec = number_format($filled_percent,2);
					$tpl->assign( FIPER, $filledpercentsec."%" );
				}
//				fwrite($file2,"$departname\t$courseno\t$coursename\t$nameforsave\t$credit\t$satisfy\t$percentsec\t$nums\t$filled\t$filledpercentsec\n");
				$tpl->parse(ROWS,".row");
			} //end while
//			fclose($file2);
			//$tpl->assign(HREF, $file_name2);
			$tpl->parse(BODY,"main");
 			$tpl->FastPrint("BODY");
		}
		else
		{
			echo "<br><center><font color=\"red\">目前沒有任何課程!!</font></center>";
		}
	}

?>


