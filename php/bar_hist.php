<?php
	require 'fadmin.php';
	
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $skinnum;
	
	
	/*********/
	$bar_content = array ( "授課教材", "課程資訊", "成績系統");
	$bar_content_E = array ( "History Courseware", "History Course Information", "History Score");
	$bar_show = array ( "show", "show", "show" );
	/*********/

	if ( !(isset($PHPSESSID) && session_check_stu($PHPSESSID)) )
		show_page( "not_access.tpl" ,"權限錯誤");
		
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $skinnum;
	$Q1 = "SELECT a_id,authorization FROM user where id = '$user_id'";
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$error = "資料庫連結錯誤!!";
		show_page ( "not_access.tpl", $error );
	}
	else if ( !($result = mysql_db_query( $DB, $Q1  )) ) {
		$error = "資料庫讀取錯誤!!1";
		show_page ( "not_access.tpl", $error );
	}
	else if ( mysql_num_rows( mysql_db_query( $DB, $Q1 ) ) == 0 ) {
		$error = "無此使用者!!";
		show_page ( "not_access.tpl", $error );
	}
	else
		$row = mysql_fetch_array($result);

	//取的學期資訊
	$Qsemester = "select * from this_semester";
	$resultsemester = mysql_db_query($DB, $Qsemester);
	$rowssemester = mysql_fetch_array($resultsemester);		
	/********************判斷上下學期該給的值**************************/
	$p_n_year = 0;	//預設上下學期year = 0 , term = 0
	$p_n_term = 0;
	$is_next = 0;   
	if($rowssemester['term'] == 1){
		$Qhave_next = "SELECT * FROM teach_course WHERE year ='".$rowssemester['year']."' AND term = '2'";
	}
	else{
		$Qhave_next = "SELECT * FROM teach_course WHERE year ='".($rowssemester['year']+1)."' AND term = '1'";
	}
	if ( !($result_have = mysql_db_query( $DB, $Qhave_next ) ) ) {
		$error = "資料庫讀取錯誤!!Q";
		show_page ( "not_access.tpl", $error );
	}
	else{
		if(mysql_num_rows($result_have) == 0){
			//沒有下學期 選擇上學期
			$is_next = 0;
			if($rowssemester['term'] == 1){
				$p_n_year = $rowssemester['year']-1;
				$p_n_term = 2;
			}
			else{
				$p_n_year = $rowssemester['year'];
				$p_n_term = 1;
			}
		}
		else{
			//有下學期
			$is_next = 1;
			if($rowssemester['term'] == 1){
				$p_n_year = $rowssemester['year'];
				$p_n_term = 2;
			}
			else{
				$p_n_year = $rowssemester['year']+1;
				$p_n_term = 1;
			}
		}
	}
	/**************************************************************/
	if ( $row["authorization"] <= 2 && $teacher == "1" ){
		//多取得year和term 2007-1-2
		//取得本學期的課程資訊
		$Q21 = "SELECT t.course_id, c.name, c.course_no, t.year, t.term  from teach_course t, course c where t.teacher_id = '".$row["a_id"]."' and c.a_id = t.course_id and t.year = $rowssemester[year] and t.term = $rowssemester[term] order by t.year desc, t.term asc, c.group_id ASC, c.a_id ASC";
		//取得上下學期的課程資訊
		$Q22 = "SELECT t.course_id, c.name, c.course_no, t.year, t.term  from teach_course t, course c where t.teacher_id = '".$row["a_id"]."' and c.a_id = t.course_id and t.year = $p_n_year and t.term = $p_n_term order by t.year desc, t.term asc, c.group_id ASC, c.a_id ASC";
	}
	else{
		$error = "只允許教師進入!!";
		show_page ( "not_access.tpl", $error );
	}
			
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$error = "資料庫連結錯誤!!";
		show_page ( "not_access.tpl", $error );
	}
	if ( !($result21 = mysql_db_query( $DB, $Q21 )) ) {
		$error = "資料庫讀取錯誤!!21";
		show_page ( "not_access.tpl", $error );
	}
	if ( !($result22 = mysql_db_query( $DB, $Q22 )) ) {
		$error = "資料庫讀取錯誤!!22";
		show_page ( "not_access.tpl", $error );
	}
	
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate ( "./templates" );
	$tpl->assign( SKINNUM , $skinnum );
	$tpl->assign( PHPID, $PHPSESSID);
	if ( $row["authorization"] <= 2 && $teacher == "1" ) {
		if( $version == "C" ) {
			$tpl->define ( array ( body => "bar_hist.tpl") );
		}
		else {
			$tpl->define ( array ( body => "bar_hist_E.tpl") );
		}
		//新增group
		$tpl->define_dynamic ( "course_group" , "body" );
		//
		$tpl->define_dynamic ( "course_list" , "body" );
								
		//$Q2 = "SELECT t.course_id, c.name from teach_course t, course c where t.teacher_id = '".$row["a_id"]."' and c.a_id = t.course_id";
		$max = 10;
		
		
		//為了顯示下拉選單的group
		$group_year = 0;
		$group_term = 0;
		$c_group = 0;
		$change_group = 0;
		$have_group1 = 0; //判斷是否有當學期的群組
		/*********************本學期*****************************************/
		while ( $row21 = mysql_fetch_array($result21) ) {
		
			//為了顯示下拉選單的group
			$change_group = 0;
			if( ($group_year != $row21['year'] || $group_term!= $row21['term']) ){
				$tpl->assign( CGROUP, "當學期課程" );
				if($have_group1==0){
					$have_group1=1;
				}
				$change_group = 1;
				$group_year = $row21['year'];
				$group_term = $row21['term'];				
			}
			//			
			if (  ( ($row21['year']."_".$row21['term']."_".$row21['course_id']) == ($course_year."_".$course_term."_".$course_id)) == ($course_year."_".$course_term."_".$course_id) && $is_hist == 0) //不是歷史區才比
				$tpl->assign( CID,  $row21['year']."_".$row21['term']."_".$row21['course_id']." selected");
			else
				$tpl->assign( CID,  $row21['year']."_".$row21['term']."_".$row21['course_id'] );

			$course_no = $row21['course_no'];
			if( substr( $course_no, 3, 1 ) == "A" || substr( $course_no, 3, 1 ) == "B" || substr( $course_no, 3, 1 ) == "C" || substr( $course_no, 3, 1 ) == "D" ){
				$tpl->assign( CNAME, $row21['name']." (專班)" );
				$max = strlen( $row21['name']." (專班)" ) > $max ? strlen( $row21['name']." (專班)" ) : $max;
			}
			else{
				$tpl->assign( CNAME, $row21['name'] );
				$max = strlen( $row21['name'] ) > $max ? strlen( $row21['name'] ) : $max;
			}
			$max = strlen( $row21['name'] ) > $max ? strlen( $row21['name'] ) : $max;
			//為了顯示下拉選單的group
			if($change_group == 1){
				$tpl->parse( C_L, "course_list");		
			}
			else{
				$tpl->parse( C_L, ".course_list");
			}
			//
			//$tpl->parse( C_L, ".course_list");			
		}			
		
		//為了顯示下拉選單的group
		$group_year = 0;
		$group_term = 0;
		$c_group = 0;
		$change_group = 0;
		$have_group2 = 0; //判斷是否有上/下學期的群組
		/*************************上/下學期************************/		
		while ( $row22 = mysql_fetch_array($result22) ) {		
			//為了顯示下拉選單的group
			$change_group = 0;
			if( ($group_year != $row22['year'] || $group_term!= $row22['term'])){

				if($have_group1==1){				
					$tpl->parse( C_G, ".course_group");
				}
				if($is_next == 1){
					$tpl->assign( CGROUP, "下學期課程" );
				}
				else{
					$tpl->assign( CGROUP, "上學期課程" );
				}
				if($have_group2==0){
					$have_group2=1;
				}
				$change_group = 1;

				$group_year = $row22['year'];
				$group_term = $row22['term'];				
			}
			//
					
			if ( ($row22['year']."_".$row22['term']."_".$row22['course_id']) == ($course_year."_".$course_term."_".$course_id) && $is_hist == 0) //不是歷史區才比
				$tpl->assign( CID, $row22['year']."_".$row22['term']."_".$row22['course_id']." selected");
			else
				$tpl->assign( CID, $row22['year']."_".$row22['term']."_".$row22['course_id'] );

			$course_no = $row22['course_no'];
			if( substr( $course_no, 3, 1 ) == "A" || substr( $course_no, 3, 1 ) == "B" || substr( $course_no, 3, 1 ) == "C" || substr( $course_no, 3, 1 ) == "D" ){
				$tpl->assign( CNAME, $row22['name']." (專班)" );
				$max = strlen( $row22['name']." (專班)" ) > $max ? strlen( $row22['name']." (專班)" ) : $max;
			}
			else{
				$tpl->assign( CNAME, $row22['name'] );
				$max = strlen( $row22['name'] ) > $max ? strlen( $row22['name'] ) : $max;
			}
			//為了顯示下拉選單的group
			if($change_group == 1){
				$tpl->parse( C_L, "course_list");		
			}
			else{
				$tpl->parse( C_L, ".course_list");
			}
			//
			//$tpl->parse( C_L, ".course_list");			
		}
		
		/************************歷史區************************/
		//課程資訊需特別從hist_course取
		$Q22 = "SELECT t.course_id, c.name, c.course_no, t.year, t.term  from teach_course t, hist_course c where t.teacher_id = '".$row["a_id"]."' and c.a_id = t.course_id and c.year = t.year and c.term=t.term and !((t.year = $rowssemester[year] and t.term = $rowssemester[term] ) OR (t.year = $p_n_year and t.term = $p_n_term)) order by t.year desc, t.term asc ";
		if ( !($result22 = mysql_db_query( $DB, $Q22  )) ) {
			$error = "資料庫讀取錯誤!!22";
			show_page ( "not_access.tpl", $error );
		}
		//為了顯示下拉選單的group
		$change_group = 1;
		while ( $row22 = mysql_fetch_array($result22) ) {
			if($change_group == 1){
				if($have_group1==1 || $have_group1==2 ){				
					$tpl->parse( C_G, ".course_group");
				}
				$tpl->assign( CGROUP, "歷史課程" );
			}						
			/************歷史區課號要加上hist_**********/		
			$hist_courid = "hist_".$row22['year']."_".$row22['term']."_".$row22['course_id'];				
			if ( $row22['course_id'] == $course_id && $is_hist == 1) //是歷史區才比		
				$tpl->assign( CID, $hist_courid." selected");
			else
				$tpl->assign( CID, $hist_courid );
			/*************************************/

			$course_no = $row22['course_no'];
			if( substr( $course_no, 3, 1 ) == "A" || substr( $course_no, 3, 1 ) == "B" || substr( $course_no, 3, 1 ) == "C" || substr( $course_no, 3, 1 ) == "D" ){
				$tpl->assign( CNAME, $row22['name']." (專班)" );
				$max = strlen( $row22['name']." (專班)" ) > $max ? strlen( $row22['name']." (專班)" ) : $max;
			}
			else{
				$tpl->assign( CNAME, $row22['name'] );
				$max = strlen( $row22['name'] ) > $max ? strlen( $row22['name'] ) : $max;
			}			

			//為了顯示下拉選單的group
			if($change_group == 1){
				$tpl->parse( C_L, "course_list");
				$change_group = 0;
			}
			else{
				$tpl->parse( C_L, ".course_list");
			}			
		}		
		/************************************************/
		
		$tpl->assign( CID, "-1" );
		if ( $version == "C" ) {
			$tpl->assign( CNAME, "我的課程" );
		}
		else {
			$tpl->assign( CNAME, "My Courses" );
		}

		$tpl->parse( C_L, ".course_list");
		
		//為了顯示下拉選單的group
		$tpl->parse( C_G, ".course_group");
		//
		
		$location = 100 + ( $max - 10 )*6;
		if ( $version == "C" ) {
			$content = $bar_content;
		}
		else {
			$content = $bar_content_E;
		}

		$tpl->define_dynamic ( "layer_time" , "body" );
		$tpl->define_dynamic ( "layer_show" , "body" );
		$tpl->define_dynamic ( "option_show" , "body" );
		$tpl->assign( VALUE6, $location + 20 );
		for ( $i = 0; $i < count( $content ) ; $i ++ ) {
			$tpl->assign( ORDER1, $i );
			$tpl->assign( ORDER2, $i+1 );
			$tpl->assign( VALUE1, 10+$i*3 );
			$tpl->assign( VALUE2, 13+$i*3 );
			$tpl->assign( VALUE3, 21+$i*3 );
			if ( /*($i==5 && (!$function['create_case']) && (!$function['mag_case']) && (!$function['check_case'])) ||*/
				 ($i==5 && (!$function['create_qs']) && (!$function['modify_qs'])) ){
				$tpl->assign( LSHOW, "" );
				$tpl->assign( VALUE4, $location );
				$tpl->assign( VALUE5, 0);
			}
			else {
				$tpl->assign( LSHOW, $content[$i] );
				if ( $i != 0 ) {
					$tpl->assign( VALUE4, $location + strlen( $content[$i - 1] )*7 + 19 );
					$location = $location + strlen( $content[$i - 1] )*7 + 19;
				}
				else
					$tpl->assign( VALUE4, $location );
				$tpl->assign( VALUE5, strlen( $content[$i] )*7+14 );
			}
			$status = "";
			for ( $j = 0; $j < count( $content ) ; $j ++ ) {
				$k = $j+1;
				if ( $i == $j ) {
					if ( $bar_show[$j] == "notready" ) {
						$status .= "'Layer".$k."1','','hide'";
					}else {
						$status .= "'Layer".$k."1','','".$bar_show[$j]."'";
					}
				}
				else {
					$status .= "'Layer".$k."1','','hide'";
				}
				if ( $j != count( $content ) - 1 )
					$status .= ",";
				else {
					if ( $bar_show[$i] == "notready" ) {
						$status .= ",'notready','','show'";
					}
					else {
						$status .= ",'notready','','hide'";
					}
				}
			}
			$tpl->assign( STATUS, $status );
			
			$tpl->parse( L_T, ".layer_time");
			$tpl->parse( L_S, ".layer_show");
			$tpl->parse( O_S, ".option_show");
		}

		$tpl->assign( LAYERNUM, count( $content ) );
		$tpl->assign( COURSE, $course_id);
		$tpl->assign( USER, $user_id);
		
		if ( $scorm == 1 ) {
			$tpl->assign( IMPORT, "import.php");
		}
		else {
			$tpl->assign( IMPORT, "import2.php");
		}
		
		$tpl->parse( BODY, "body");
		$tpl->FastPrint("BODY");
	}
	else{
		$error = "只允許教師進入!!";
		show_page ( "not_access.tpl", $error );
	}

?>
