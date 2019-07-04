<?php
require 'fadmin.php';
require './templates/top.tpl';

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
		$Q2 = "SELECT a_id, name FROM course_group WHERE parent_id = $c_id and a_id!=92 order by name"; //a_id!=92排除共同科
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
		
		
			
		if( mysql_num_rows($result3) != 0)
		{
			$tpl = new FastTemplate("./templates");
			$tpl->define(array(main=>"statistic_course2.tpl"));
			$tpl->define_dynamic("row","main");
			 
			while ( $rows3 = mysql_fetch_array( $result3 ) ) {
				if ( $color == "#BFCEBD" )
					$color = "#D0DFE3";
				else $color = "#BFCEBD";
				
				//選出授課老師的名稱
				$Q5 = "select distinct u.name FROM user u , teach_course tc where tc.course_id = '".$rows3['a_id']."' and tc.teacher_id = u.a_id and u.authorization='1' and tc.year='$year' and tc.term='$term'";
				if ( !($result5 = mysql_db_query( $DB, $Q5 ) ) ) {
					$message = "$message - 資料庫讀取錯誤4!!";
				}
				$name = "";
				while ( $row5 = mysql_fetch_array( $result5 ) )
				{
					if ( $row5['name'] != NULL )
					{
						$name = $name.$row5['name']."<br>";
					}
				}
				
				//修課人數
				$stu_no=0;
				$Q_tmp="select count(tc.student_id) as stu_no from take_course tc, user u where tc.student_id=u.a_id and u.disable='0' and tc.course_id=$rows3[a_id] and year='$year' and term='$term'";				
				if ( !($rs_temp = mysql_db_query( $DB, $Q_tmp ) ) ) {
					$message = "$message - 資料庫讀取錯誤-修課人數!!";
				}
				if($rw_tmp = mysql_fetch_array($rs_temp))
				{
					$stu_no = $rw_tmp['stu_no'];			
				}
				//填寫人數
				$join_no=0;
				$Q_tmp="select count(student_id) as join_no FROM mid_ans where year=$year and term='$term'";				
				if ( !($rs_temp = mysql_db_query( $DB.$rows3[a_id], $Q_tmp ) ) ) {
					$message = "$message - 資料庫讀取錯誤-填寫人數!!";
				}
				if($rw_tmp = mysql_fetch_array($rs_temp))
				{
					$join_no = $rw_tmp['join_no'];	
				}
				//填寫率
				$ratio1=0;
				if ($stu_no!=0)
					$ratio1=number_format((($join_no/$stu_no)*100),2);
					
				//讀取該課程的問卷結果，逐筆寫入excel檔案
				$Q10 = "SELECT q1,q2 FROM mid_ans where year=$year and term='$term'";
				
				if ( !($result10 = mysql_db_query( $DB.$rows3[a_id], $Q10 ) ) ) {
					show_page( "not_access.tpl" ,"資料庫讀取錯誤10!!" );
				}
				//----當該課程無人填寫問卷
				if(!mysql_num_rows($result10)) 
				{
					$tpl->assign( DEPART,$rows3['gname']);   	//系所
					$tpl->assign( COLOR , $color );
					$tpl->assign( CNO,$rows3['course_no'] ); 	//課程編號
					$tpl->assign( COURSE,$rows3['cname'] );  	//課程名稱
					$tpl->assign( TEACHER, $name );			  	//授課教師
					$tpl->assign( COUNT, $stu_no."人" );      //修課人數
					$tpl->assign( FILLED,$join_no."人" );  	//填寫人數
					$tpl->assign( FIPER, $ratio1."%" );      	//填寫率
					$tpl->assign( Q1, "" );							//問題一
					$tpl->assign( Q2, "" );							//問題二
					
					$tpl->parse(ROWS,".row");
				}	
				//----當該課程有問卷填寫資料
				else 
				{
					while($rows10 = mysql_fetch_array( $result10 ) )
					{
						$tpl->assign( DEPART,$rows3['gname']);   	//系所
						$tpl->assign( COLOR , $color );
						$tpl->assign( CNO,$rows3['course_no'] ); 	//課程編號
						$tpl->assign( COURSE,$rows3['cname'] );  	//課程名稱
						$tpl->assign( TEACHER, $name );			  	//授課教師
						$tpl->assign( COUNT, $stu_no."人" );      //修課人數
						$tpl->assign( FILLED,$join_no."人" );  	//填寫人數
						$tpl->assign( FIPER, $ratio1."%" );      	//填寫率
						$tpl->assign( Q1, $rows10[q1] );				//問題一
						$tpl->assign( Q2, $rows10[q2] );				//問題二
						
						$tpl->parse(ROWS,".row");
					}
				}
				
			} //end while
			$tpl->parse(BODY,"main");
 			$tpl->FastPrint("BODY");
		}
		else
		{
			echo "<br><center><font color=\"red\">目前沒有任何課程!!</font></center>";
		}
	}

?>