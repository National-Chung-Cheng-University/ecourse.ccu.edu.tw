<?
require 'fadmin.php';
	if (!(isset($PHPSESSID) && session_check_stu($PHPSESSID)) ) {
		show_page( "not_access.tpl" ,"權限錯誤");
	}
	else {
		session_unregister("time");
		session_register("time");
//		session_register("user_id");
//		session_register("version");
		$time = date("U");
		if ( $frame != 1 ) {
			echo "<frameset rows='*,0' frameborder = 'no'>\n";
			echo "<frame src='teach_course.php?PHPSESSID=$PHPSESSID&frame=1' name='main' frameborder = 'no'>\n";
			echo "<frame src='../noop.php?PHPSESSID=$PHPSESSID' name='noop' frameborder = 'no'>\n";
			echo "</frameset>";
		}
		else {
			$Q1 = "select authorization FROM user where id = '$user_id'";
			if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
				$message = "$message - 資料庫讀取錯誤!!";
			}else
				$row = mysql_fetch_array( $result );
	
			if ( $row['authorization'] <= "2" && $teacher == 1) {

                                //changed here by rja
                                //用來去讀老師所開的課程中，有哪些預約會議，include 下面這支程式後，會得到一個預約會議資訊>叫 $reservation_meeting
                                include("../my_reservation_list_proc.php");

                                //var_dump( $reservation_meeting);

                                //end of changed




				show_page_d ();
			}
			else {
				header( "Location: http://$SERVER_NAME/php/Courses_Admin/guest.php?PHPSESSID=".session_id());
			}
		}
	}

	function show_page_d ( $message="" ) {
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		global $version, $course_id, $skinnum;
		
		/*
		// 連結sybase
		if( !($cnx = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) ){  
			Error_handler( "在 sybase_connect 有錯誤發生" , $cnx );  
		}
		$csd = @sybase_select_db("academic", $cnx);
		$cur = sybase_query("select year,term,id,cour_cd,grp from a31vcurriculum_tea", $cnx);
		if(!$cur) {  
			Error_handler( "在 sybase_exec 有錯誤發生( 沒有指標傳回 ) " , $cnx );  
		}
		$rowsy = sybase_fetch_array($cur);
		*/
		if ( $version == "C" )
			$tpl->define ( array ( body => "teach_course.tpl" ) );
		else
			$tpl->define ( array ( body => "teach_course_E.tpl" ) );
		$tpl->define_dynamic ( "course_list" , "body" );
		$tpl->define_dynamic ( "pre_next_cour" , "body" ); //上下學期
		$tpl->define_dynamic ( "old_cour", "body" );
		
		$tpl->assign( SKINNUM , $skinnum );
		$color = "#000066";
		$tpl->assign( COLOR , $color );
		//$tpl->assign( TYPE , "colspan=2" );
		if ( $version == "C" ) {
			$tpl->assign( GNAME , "<font color =#FFFFFF>開課單位</font>" );
			$tpl->assign( YEAR, "<font color =#FFFFFF>開課學年度</font>");
			$tpl->assign( CNO , "<font color =#FFFFFF>課程編號</font>" );
			$tpl->assign( CNAME , "<font color =#FFFFFF>課程名稱</font>" );
			$tpl->assign( GRADE, "<font color =#FFFFFF>登錄成績</font>" );
			$tpl->assign( CRNAME , "<font color =#FFFFFF>重置課程連結</font>" );
			$tpl->assign( CTEACH , "<font color =#FFFFFF>授課教師</font>" );
			//changed 1 line by rja
                        $tpl->assign( MMCONLINE , "<font color =#FFFFFF>今日預約課程</font>" );


		}
		else {
			$tpl->assign( GNAME , "<font color =#FFFFFF>Department</font>" );
			$tpl->assign( YEAR, "<font color =#FFFFFF>Year</font>");
			$tpl->assign( CNO , "<font color =#FFFFFF>No.</font>" );
			$tpl->assign( CNAME , "<font color =#FFFFFF>Course Name</font>" );
			$tpl->assign( GRADE, "<font color =#FFFFFF>Upload Grades</font>" );
			$tpl->assign( CRNAME , "<font color =#FFFFFF>Reset Course Link</font>" );
			$tpl->assign( CTEACH , "<font color =#FFFFFF>Teachers</font>" );
			//changed 1 line by rja
                        $tpl->assign( MMCONLINE , "<font color =#FFFFFF>Meeting Online</font>" );


		}
		$tpl->parse ( COURSE_LIST, ".course_list" );
		
		/****上下學期*****/
		$p_n_color = "#000066";
		$tpl->assign( P_N_COL , $p_n_color );
		if ( $version == "C" ) {
			$tpl->assign( P_N_G_NAME , "<font color =#FFFFFF>開課單位</font>" );
			$tpl->assign( P_N_YE, "<font color =#FFFFFF>開課學年度</font>");
			$tpl->assign( P_N_NO , "<font color =#FFFFFF>課程編號</font>" );
			$tpl->assign( P_N_C_NAME , "<font color =#FFFFFF>課程名稱</font>" );
			$tpl->assign( P_N_GRA, "<font color =#FFFFFF>登錄成績</font>" );
			$tpl->assign( P_N_TEACH , "<font color =#FFFFFF>授課教師</font>" );
		}
		else {
			$tpl->assign( P_N_G_NAME , "<font color =#FFFFFF>Department</font>" );
			$tpl->assign( P_N_YE, "<font color =#FFFFFF>Year</font>");
			$tpl->assign( P_N_NO , "<font color =#FFFFFF>No.</font>" );
			$tpl->assign( P_N_C_NAME , "<font color =#FFFFFF>Course Name</font>" );
			$tpl->assign( P_N_GRA, "<font color =#FFFFFF>Upload Grades</font>" );
			$tpl->assign( P_N_TEACH , "<font color =#FFFFFF>Teachers</font>" );
		}
		$tpl->parse ( PRE_NEXT_COUR, ".pre_next_cour" );				
		/*****************/				
		
		/**********修改歷史區的選項**********/
		$old_color = "#000066";
		$tpl->assign( OLD_COL , $old_color );
		if ( $version == "C" ) {
			$tpl->assign( G_OLD_NAME , "<font color =#FFFFFF>開課單位</font>" );
			$tpl->assign( OLD_YE, "<font color =#FFFFFF>開課學年度</font>");
			$tpl->assign( C_OLD_NO , "<font color =#FFFFFF>課程編號</font>" );
			$tpl->assign( C_OLD_NAME , "<font color =#FFFFFF>課程名稱</font>" );
			$tpl->assign( C_OLD_TEACH , "<font color =#FFFFFF>授課教師</font>" );
		}
		else {
			$tpl->assign( G_OLD_NAME , "<font color =#FFFFFF>Department</font>" );
			$tpl->assign( OLD_YE, "<font color =#FFFFFF>Year</font>");
			$tpl->assign( C_OLD_NO , "<font color =#FFFFFF>No.</font>" );
			$tpl->assign( C_OLD_NAME , "<font color =#FFFFFF>Course Name</font>" );	
			$tpl->assign( C_OLD_TEACH , "<font color =#FFFFFF>Teachers</font>" );
		}
		$tpl->parse ( OLD_COUR_LIST, ".old_cour" );
		
		$color = "#E6FFFC";
		//$tpl->assign( TYPE , "" );
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $user_id;

		$Qsemester = "select * from this_semester";
		$resultsemester = mysql_db_query($DB, $Qsemester);
		$rowsq = mysql_fetch_array($resultsemester);
		
		//$Q1 = "select c.group_id, cg.name AS gname, tc.course_id, tc.year, tc.term, c.name AS cname FROM course c, course_group cg, teach_course tc , user u where u.id = '$user_id' and tc.teacher_id = u.a_id and c.a_id = tc.course_id and cg.a_id = c.group_id order by tc.year desc, tc.term asc";
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$message = "$message - 資料庫連結錯誤!!";
		}
		$D1 = "delete from online where user_id = '$user_id'";
//		mysql_db_query( $DB, $D1 );

		/********************判斷上下學期該給的值**************************/
		$p_n_year = 0;	//預設上下學期year = 0 , term = 0
		$p_n_term = 0;   
		$is_next = 0;
		if($rowsq["term"] == 1){
			$Qhave_next = "SELECT * FROM teach_course WHERE year ='".$rowsq["year"]."' AND term = '2'";
		}
		else{
			$Qhave_next = "SELECT * FROM teach_course WHERE year ='".($rowsq["year"]+1)."' AND term = '1'";
		}
		if ( !($result_have = mysql_db_query( $DB, $Qhave_next ) ) ) {
			$message = "$message - 資料庫讀取錯誤!!";
		}
		else{
			if(mysql_num_rows($result_have) == 0){
				//沒有下學期 選擇上學期
				$is_next = 0;
				if($rowsq["term"] == 1){
					$p_n_year = $rowsq["year"]-1;
					$p_n_term = 2;
				}
				else{
					$p_n_year = $rowsq["year"];
					$p_n_term = 1;
				}
			}
			else{
				//有下學期
				$is_next = 1;
				if($rowsq["term"] == 1){
					$p_n_year = $rowsq["year"];
					$p_n_term = 2;
				}
				else{
					$p_n_year = $rowsq["year"]+1;
					$p_n_term = 1;
				}
			}
		}
		/**************************************************************/
		//本學期及上下學期的資訊從course取
		$Q1 = "select c.group_id, cg.name AS gname, tc.course_id, tc.year, tc.term, c.name AS cname FROM course c, course_group cg, teach_course tc , user u where u.id = '$user_id' and tc.teacher_id = u.a_id and c.a_id = tc.course_id and cg.a_id = c.group_id order by tc.year desc, tc.term asc ,c.group_id ASC, c.a_id ASC";

		if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
			$message = "$message - 資料庫讀取錯誤!!";
		}
		else if ( mysql_num_rows( mysql_db_query( $DB, $Q1 ) ) != 0 ) {	
			$tpl->assign( HRLINE , "<hr>" );		
			while ( $row = mysql_fetch_array( $result ) )
			{
				if($row["year"]==$rowsq["year"] && $row["term"]==$rowsq["term"])
				{
					if ( $color == "#E6FFFC" )
						$color = "#F0FFEE";
					else
						$color = "#E6FFFC";
					$tpl->assign( COLOR , $color );
					
					$course_no = "";
					$Q3 = "select course_no FROM course where a_id = '".$row["course_id"]."'";
					if ( !($result3 = mysql_db_query( $DB, $Q3 ) ) ) {
						$message = "$message - 資料庫讀取錯誤!!";
					}
					while ( $row3 = mysql_fetch_array( $result3 ) ) {
						$course_no .= $row3['course_no']." ";
					}
					$tpl->assign( CNO , $course_no );
					$tpl->assign( YEAR, $row['year']."學年度第".$row['term']."學期");
					$Q2 = "select u.id, u.name, u.nickname, u.a_id, u.php FROM user u , teach_course tc where tc.course_id = '".$row["course_id"]."' and tc.teacher_id = u.a_id and u.authorization='1' and tc.year = '$row[year]' and tc.term = '$row[term]'";
					if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) ) {
						$message = "$message - 資料庫讀取錯誤!!";
					}
//----------登錄成績部分:start---------------------------------------------
					//linsy@20111222, 若為助教則不顯示登入總成績
					$QTA = "select authorization from user where id = '$user_id'";
					if ( !($resultTA = mysql_db_query( $DB, $QTA ) ) ) {
						$message = "$message - 資料庫讀取錯誤!!";
					}
					$rowTA = mysql_fetch_assoc($resultTA);
					if ($rowTA['authorization'] == 2)
						$test = "";
					else
						$test ="<a href=\"../Trackin/TGQueryFrame2.php?course=".$row["course_id"]."&year=$row[year]&term=$row[term]&action=upload&PHPSESSID=".session_id()."\">登錄總成績</a>";
					$tpl->assign( GRADE, $test );
//----------登錄成績部分:end-----------------------------------------------
					$tpl->assign( GNAME , $row["gname"] );
					if( substr( $course_no, 3, 1 ) == "A" || substr( $course_no, 3, 1 ) == "B" || substr( $course_no, 3, 1 ) == "C" || substr( $course_no, 3, 1 ) == "D" )
						$tpl->assign( CNAME , "<a href=\"../login.php?courseid=".$row['year']."_".$row['term']."_".$row["course_id"]."\" target=\"_top\">".$row["cname"]."(碩士在職專班)"."</a>" );
					else
						$tpl->assign( CNAME , "<a href=\"../login.php?courseid=".$row['year']."_".$row['term']."_".$row["course_id"]."\" target=\"_top\">".$row["cname"]."</a>" );
					$name = "";
					while ( $row2 = mysql_fetch_array( $result2 ) ) {
						if ( $row2['name'] != NULL ) {
							if ( $row2['php'] != NULL ) {
								$name = $name." <a href=# onClick=\"window.open('".$row2['php']."', '', 'resizable=1,scrollbars=1');\">".$row2['name']."</a>";
							}
							else {
								$name = $name." <a href=# onClick=\"window.open('../Learner_Profile/TTDATAQuery1.php?PHPSESSID=".session_id()."&user_aid=".$row2['a_id']."&query=1', '', 'resizable=1,scrollbars=1');\">".$row2['name']."</a>";
							}
						}
						else if ( $row2['nickname'] != NULL ) {
							if ( $row2['php'] != NULL ) {
								$name = $name." <a href=# onClick=\"window.open('".$row2['php']."', '', 'resizable=1,scrollbars=1');\">".$row2['nickname']."</a>";
							}
							else {
								$name = $name." <a href=# onClick=\"window.open('../Learner_Profile/TTDATAQuery1.php?PHPSESSID=".session_id()."&user_aid=".$row2['a_id']."&query=1', '', 'resizable=1,scrollbars=1');\">".$row2['nickname']."</a>";
							}
						}
						else {
							if ( $row2['php'] != NULL ) {
								$name = $name." <a href=# onClick=\"window.open('".$row2['php']."', '', 'resizable=1,scrollbars=1');\">".$row2['id']."</a>";
							}
							else {
								$name = $name." <a href=# onClick=\"window.open('../Learner_Profile/TTDATAQuery1.php?PHPSESSID=".session_id()."&user_aid=".$row2['a_id']."&query=1', '', 'resizable=1,scrollbars=1');\">".$row2['id']."</a>";
							}
						}
					}

					$tpl->assign( CTEACH , $name );

                              //changed here by rja
                                        /*
                                           在教師的"我的課程"列表中，對於每一個課程名稱，送去 mmc 查詢是否有預約會議

                                         */
                                        require_once 'my_rja_db_lib.php';

// $reservation_meeting 這個變數是在前面 call  show_page_d 去 require 取得的
global $reservation_meeting;
$haveMeeting = false;
if(!empty($reservation_meeting)){
	foreach($reservation_meeting as $value){
		//若課程名稱一樣，而且預約開始日期也是今天
		if(($row['cname'] == $value['courseName']) && (date('Ymd') == date('Ymd',$value['startTime']))){
			//只看比現在時間還早三小時以後的會議
			if ($value['startTime'] < (time() -10800 )){
				continue;
			}

			global $user_id;
			$teacher_id_num = $value['teacherIdNum'];
			//查中文姓名
			$my_query_user_name = "select name from user where id = '$user_id' ";
			$my_user_name = query_db_to_value($my_query_user_name);

			$my_gotomeeting_url = "http://mmc.elearning.ccu.edu.tw/gotomeeting.php?u=$teacher_id_num&c=visit&name=$my_user_name";

			if($value['isOnline']){
				$tpl->assign( MMCONLINE , "<a href=\"$my_gotomeeting_url\" onclick=\"window.open(this.href);return false;\">上課中</a> ");
			}else if(!$value['finished']){
				//可能開完會後，好像還是會在 isOnline 的狀態，所以多判斷一個是否結束了
				$courseStartTime = date('g:i a',$value['startTime']);
			$tpl->assign( MMCONLINE , "<font>今天 {$courseStartTime} 上課</font>");
		}else continue;

		$haveMeeting = true;
		break;
		}

	}
}
if(!$haveMeeting)
    $tpl->assign( MMCONLINE , "<font>-</font>");



        //end of changed






					$tpl->parse ( COURSE_LIST, ".course_list" );
				}
/*******************************上/下學期*****************************************************/
				else if($row["year"]==$p_n_year && $row["term"]==$p_n_term){
					if ( $color == "#E6FFFC" )
						$color = "#F0FFEE";
					else
						$color = "#E6FFFC";
					$tpl->assign( P_N_COL , $color );
					
					$course_no = "";
					$Q3 = "select course_no FROM course where a_id = '".$row["course_id"]."'";
					if ( !($result3 = mysql_db_query( $DB, $Q3 ) ) ) {
						$message = "$message - 資料庫讀取錯誤!!";
					}
					while ( $row3 = mysql_fetch_array( $result3 ) ) {
						$course_no .= $row3['course_no']." ";
					}
					$tpl->assign( P_N_NO , $course_no );
					$tpl->assign( P_N_YE, $row['year']."學年度第".$row['term']."學期");
					$Q2 = "select u.id, u.name, u.nickname, u.a_id, u.php FROM user u , teach_course tc where tc.course_id = '".$row["course_id"]."' and tc.teacher_id = u.a_id and u.authorization='1' and tc.year = '$row[year]' and tc.term = '$row[term]'";
					if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) ) {
						$message = "$message - 資料庫讀取錯誤!!";
					}
					$tpl->assign( P_N_G_NAME , $row["gname"] );
					if( substr( $course_no, 3, 1 ) == "A" || substr( $course_no, 3, 1 ) == "B" || substr( $course_no, 3, 1 ) == "C" || substr( $course_no, 3, 1 ) == "D" )
						$tpl->assign( P_N_C_NAME , "<a href=\"../login.php?courseid=".$row['year']."_".$row['term']."_".$row["course_id"]."\" target=\"_top\">".$row["cname"]."(碩士在職專班)"."</a>" );
					else
						$tpl->assign( P_N_C_NAME , "<a href=\"../login.php?courseid=".$row['year']."_".$row['term']."_".$row["course_id"]."\" target=\"_top\">".$row["cname"]."</a>" );
					//登錄成績
					$test ="<a href=\"../Trackin/TGQueryFrame2.php?course=".$row["course_id"]."&year=$row[year]&term=$row[term]&action=upload&PHPSESSID=".session_id()."\">登錄總成績</a>";
					//$test ="<a href=\"../backup_grade/BGQueryFrame1.php?action=upload&course=".$row["course_id"]."&year=94&term=2\">登錄總成績</a>";
					$tpl->assign( P_N_GRA, $test );					
					$name = "";
					while ( $row2 = mysql_fetch_array( $result2 ) ) {
						if ( $row2['name'] != NULL ) {
							if ( $row2['php'] != NULL ) {
								$name = $name." <a href=# onClick=\"window.open('".$row2['php']."', '', 'resizable=1,scrollbars=1');\">".$row2['name']."</a>";
							}
							else {
								$name = $name." <a href=# onClick=\"window.open('../Learner_Profile/TTDATAQuery1.php?PHPSESSID=".session_id()."&user_aid=".$row2['a_id']."&query=1', '', 'resizable=1,scrollbars=1');\">".$row2['name']."</a>";
							}
						}
						else if ( $row2['nickname'] != NULL ) {
							if ( $row2['php'] != NULL ) {
								$name = $name." <a href=# onClick=\"window.open('".$row2['php']."', '', 'resizable=1,scrollbars=1');\">".$row2['nickname']."</a>";
							}
							else {
								$name = $name." <a href=# onClick=\"window.open('../Learner_Profile/TTDATAQuery1.php?PHPSESSID=".session_id()."&user_aid=".$row2['a_id']."&query=1', '', 'resizable=1,scrollbars=1');\">".$row2['nickname']."</a>";
							}
						}
						else {
							if ( $row2['php'] != NULL ) {
								$name = $name." <a href=# onClick=\"window.open('".$row2['php']."', '', 'resizable=1,scrollbars=1');\">".$row2['id']."</a>";
							}
							else {
								$name = $name." <a href=# onClick=\"window.open('../Learner_Profile/TTDATAQuery1.php?PHPSESSID=".session_id()."&user_aid=".$row2['a_id']."&query=1', '', 'resizable=1,scrollbars=1');\">".$row2['id']."</a>";
							}
						}
					}
					
					$tpl->assign( P_N_TEACH , $name );
					$tpl->parse ( PRE_NEXT_COUR, ".pre_next_cour" );
				}
			}
		}				
/***********************************歷史區**********************************************************/				
		//歷史區的course資訊從hist_course取
		$Q1 = "select c.group_id, cg.name AS gname, tc.course_id, tc.year, tc.term, c.name AS cname, c.course_no AS course_no FROM hist_course c, course_group cg, teach_course tc , user u where u.id = '$user_id' and tc.teacher_id = u.a_id and c.a_id = tc.course_id and cg.a_id = c.group_id and c.year = tc.year and c.term=tc.term and !((tc.year = $rowsq[year] and tc.term = $rowsq[term]) OR (tc.year = $p_n_year and tc.term = $p_n_term)) order by tc.year desc, tc.term asc";		
		if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
			$message = "$message - 資料庫讀取錯誤!!";
		}
		else if ( mysql_num_rows( mysql_db_query( $DB, $Q1 ) ) != 0 ) {	
			$tpl->assign( HRLINE , "<hr>" );		
			while ( $row = mysql_fetch_array( $result ) )
			{						
				if ( $color == "#E6FFFC" )
					$color = "#F0FFEE";
				else
					$color = "#E6FFFC";
				$tpl->assign( OLD_COL , $color );
				
				$course_no = "";
				/*
				$Q3 = "select course_no FROM course where a_id = '".$row["course_id"]."'";
				if ( !($result3 = mysql_db_query( $DB, $Q3 ) ) ) {
					$message = "$message - 資料庫讀取錯誤!!";
				}
				while ( $row3 = mysql_fetch_array( $result3 ) ) {
					$course_no .= $row3['course_no']." ";
				}
				*/
				$course_no = $row['course_no'];
				$tpl->assign( C_OLD_NO , $course_no );
				$tpl->assign( OLD_YE, $row['year']."學年度第".$row['term']."學期");
				$Q2 = "select u.id, u.name, u.nickname, u.a_id, u.php FROM user u , teach_course tc where tc.course_id = '".$row["course_id"]."' and tc.teacher_id = u.a_id and u.authorization='1' and tc.year = '$row[year]' and tc.term = '$row[term]'";
				if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) ) {
					$message = "$message - 資料庫讀取錯誤!!";
				}
				$tpl->assign( G_OLD_NAME , $row["gname"] );
				if( substr( $course_no, 3, 1 ) == "A" || substr( $course_no, 3, 1 ) == "B" || substr( $course_no, 3, 1 ) == "C" || substr( $course_no, 3, 1 ) == "D" )
					$tpl->assign( C_OLD_NAME , "<a href=\"../login.php?courseid=hist_".$row['year']."_".$row['term']."_".$row["course_id"]."\" target=\"_top\">".$row["cname"]."(碩士在職專班)"."</a>" );
				else
					$tpl->assign( C_OLD_NAME , "<a href=\"../login.php?courseid=hist_".$row['year']."_".$row['term']."_".$row["course_id"]."\" target=\"_top\">".$row["cname"]."</a>" );
				$name = "";					
						
				while ( $row2 = mysql_fetch_array( $result2 ) ) {
					if ( $row2['name'] != NULL ) {
						if ( $row2['php'] != NULL ) {
							$name = $name." <a href=# onClick=\"window.open('".$row2['php']."', '', 'resizable=1,scrollbars=1');\">".$row2['name']."</a>";
						}
						else {
							$name = $name." <a href=# onClick=\"window.open('../Learner_Profile/TTDATAQuery1.php?PHPSESSID=".session_id()."&user_aid=".$row2['a_id']."&query=1', '', 'resizable=1,scrollbars=1');\">".$row2['name']."</a>";
						}
					}
					else if ( $row2['nickname'] != NULL ) {
						if ( $row2['php'] != NULL ) {
							$name = $name." <a href=# onClick=\"window.open('".$row2['php']."', '', 'resizable=1,scrollbars=1');\">".$row2['nickname']."</a>";
						}
						else {
							$name = $name." <a href=# onClick=\"window.open('../Learner_Profile/TTDATAQuery1.php?PHPSESSID=".session_id()."&user_aid=".$row2['a_id']."&query=1', '', 'resizable=1,scrollbars=1');\">".$row2['nickname']."</a>";
						}
					}
					else {
						if ( $row2['php'] != NULL ) {
							$name = $name." <a href=# onClick=\"window.open('".$row2['php']."', '', 'resizable=1,scrollbars=1');\">".$row2['id']."</a>";
						}
						else {
							$name = $name." <a href=# onClick=\"window.open('../Learner_Profile/TTDATAQuery1.php?PHPSESSID=".session_id()."&user_aid=".$row2['a_id']."&query=1', '', 'resizable=1,scrollbars=1');\">".$row2['id']."</a>";
						}
					}
				}
				
				$tpl->assign( C_OLD_TEACH , $name );
				$tpl->parse ( OLD_COUR_LIST, ".old_cour" );								
			}
		}
		else {
			$tpl->assign( HRLINE , "<hr>" );
		}
		$tpl->assign( PHPSID , session_id() );
		$Q7 = "select n.begin_day FROM news n where n.system = '1' and n.begin_day <= '".date("Y-m-d")."' and n.end_day >= '".date("Y-m-d")."'";
		if ( !($result7 = mysql_db_query( $DB, $Q7 ) ) ) {
			$message = "$message - 資料庫讀取錯誤!!";
		}
		else {
			$new = date("d") - 1;
			if ( $new < 10 )
				$new = "0$new";
			while ( $row7 = mysql_fetch_array( $result7 ) ) {
				if ( date("Y")."-".date("m")."-$new" <= $row7['begin_day'] ) {
					$popup = 1;
				}
			}
		}		
		if ( $course_id != "" || $popup != 1 )
			$tpl->assign( SYS , "//" );
		else
			$tpl->assign( SYS , "" );
//		echo $test;	
		if($is_next == 1){
			if ( $version == "C" ) {
				$tpl->assign( PREORNEXT , "下" );
			}
			else{
				$tpl->assign( PREORNEXT , "Next" );
			}
		}
		else{
			if ( $version == "C" ) {
				$tpl->assign( PREORNEXT , "上" );
			}
			else{
				$tpl->assign( PREORNEXT , "Previous" );
			}
		}
		$tpl->parse( BODY, "body" );
		$tpl->FastPrint("BODY");
	}
?>
