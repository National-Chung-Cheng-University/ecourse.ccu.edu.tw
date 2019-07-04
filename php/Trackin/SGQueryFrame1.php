<?
	require 'fadmin.php';
	update_status ("成績查詢");

	if ( !(isset($PHPSESSID) && session_check_teach($PHPSESSID)) ) {
		show_page( "not_access.tpl" ,"權限錯誤");
	}

	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $skinnum;
	$Q1 = "SELECT authorization, name, nickname, a_id FROM user where id = '$user_id'";
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$error = "資料庫連結錯誤!!";
		show_page ( "not_access.tpl", $error );
		exit;
	}else if ( !($result = mysql_db_query( $DB, $Q1  )) ) {
		$error = "資料庫讀取錯誤!!";
		show_page ( "not_access.tpl", $error );
		exit;
	}else if ( mysql_num_rows($result) == 0 ) {
		$error = "使用者不存在!!";
		show_page ( "not_access.tpl", $error );
		exit;
	}
	else if ( !($row = mysql_fetch_array($result)) ) {
		$error = "資料讀取錯誤!!";
		show_page ( "not_access.tpl", $error );
		exit;
	}
	else if ( $row["authorization"] == 9 ) {
		if( $version == "C" ) {
			show_page ( "not_access.tpl", "$error 你沒有權限使用此功能!!" );
			exit;
		}
		else {
			show_page ( "not_access.tpl", "$error You have No Permission!!" );
			exit;
		}
	}
	
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate ( "./templates" );
	$tpl->define ( array ( body => "SGQueryFrame1.tpl") );
	$tpl->define_dynamic ( "n_list" , "body" );
	$tpl->define_dynamic ( "l_list" , "body" );
	$tpl->define_dynamic ( "hw_list" , "body" );
	//$tpl->define_dynamic ( "co_list" , "body" );
	$tpl->define_dynamic ( "to_list" , "body" );
	$tpl->assign( SKINNUM , $skinnum );
	if ( $row['name'] != "" ) 
		$tpl->assign ( UNAME, $row['name'] );
	else if ( $row['nickname'] != "" )
		$tpl->assign ( UNAME, $row['nickname'] );
	else
		$tpl->assign ( UNAME, "N/A" );
	$tpl->assign ( UID, $user_id );
	
	if( $version == "C" ) {
		$tpl->assign ( TITLE, "學生成績查詢" );
		$tpl->assign ( IMG, "img");
		$tpl->assign ( TNAME, "姓名");
		$tpl->assign ( TID, "帳號");
		$tpl->assign ( NNAME, "<b><font color=#FFFFFF>一般測驗(名稱)</font></b>");
		$tpl->assign ( NPER, "<b><font color=#FFFFFF>比例</font></b>");
		$tpl->assign ( NG, "<b><font color=#FFFFFF>分數</font></b>" );
		$tpl->assign ( NORD, "<b><font color=#FFFFFF>排名</font></b>" );
		$tpl->assign ( COLOR, "#4d6eb2" );
		$tpl->parse( N_LIST, ".n_list" );
		$tpl->assign ( LNAME, "<b><font color=#FFFFFF>線上測驗(名稱)</font></b>");
		$tpl->assign ( LPER, "<b><font color=#FFFFFF>比例</font></b>");
		$tpl->assign ( LG, "<b><font color=#FFFFFF>分數</font></b>" );
		$tpl->assign ( LORD, "<b><font color=#FFFFFF>排名</font></b>" );
		$tpl->assign ( COLOR, "#4d6eb2" );
		$tpl->parse( L_LIST, ".l_list" );
		$tpl->assign ( HWNAME, "<b><font color=#FFFFFF>線上作業(名稱)</font></b>");
		$tpl->assign ( HWPER, "<b><font color=#FFFFFF>比例</font></b>");
		$tpl->assign ( HWG, "<b><font color=#FFFFFF>分數</font></b>" );
		$tpl->assign ( HORD, "<b><font color=#FFFFFF>排名</font></b>" );
		$tpl->assign ( COLOR, "#4d6eb2" );
		$tpl->parse( HW_LIST, ".hw_list" );
/*		$tpl->assign ( CONAME, "<b><font color=#FFFFFF>合作學習(名稱)</font></b>");
		$tpl->assign ( COPER, "<b><font color=#FFFFFF>比例</font></b>");
		$tpl->assign ( COG, "<b><font color=#FFFFFF>分數</font></b>" );
		$tpl->assign ( CORD, "<b><font color=#FFFFFF>排名</font></b>" );
		$tpl->assign ( COLOR, "#4d6eb2" );
		$tpl->parse( CO_LIST, ".co_list" );
		*/
	}
	else {
		$tpl->assign ( TITLE, "Score Query" );
		$tpl->assign ( IMG, "img_E");
		$tpl->assign ( TNAME, "Name");
		$tpl->assign ( TID, "ID");
		$tpl->assign ( NNAME, "<b><font color=#FFFFFF>Normal Exam.(Name)</font></b>");
		$tpl->assign ( NPER, "<b><font color=#FFFFFF>Percentage</font></b>");
		$tpl->assign ( NG, "<b><font color=#FFFFFF>Grade</font></b>" );
		$tpl->assign ( NORD, "<b><font color=#FFFFFF>Order</font></b>" );
		$tpl->assign ( COLOR, "#4d6eb2" );
		$tpl->parse( N_LIST, ".n_list" );
		$tpl->assign ( LNAME, "<b><font color=#FFFFFF>Online Exam.(Name)</font></b>");
		$tpl->assign ( LPER, "<b><font color=#FFFFFF>Percentage</font></b>");
		$tpl->assign ( LG, "<b><font color=#FFFFFF>Grade</font></b>" );
		$tpl->assign ( LORD, "<b><font color=#FFFFFF>Order</font></b>" );
		$tpl->assign ( COLOR, "#4d6eb2" );
		$tpl->parse( L_LIST, ".l_list" );
		$tpl->assign ( HWNAME, "<b><font color=#FFFFFF>Online homework.(Name)</font></b>");
		$tpl->assign ( HWPER, "<b><font color=#FFFFFF>Percentage</font></b>");
		$tpl->assign ( HWG, "<b><font color=#FFFFFF>Grade</font></b>" );
		$tpl->assign ( HORD, "<b><font color=#FFFFFF>Order</font></b>" );
		$tpl->assign ( COLOR, "#4d6eb2" );
		$tpl->parse( HW_LIST, ".hw_list" );
/*		$tpl->assign ( CONAME, "<b><font color=#FFFFFF>COOP (Name)</font></b>");
		$tpl->assign ( COPER, "<b><font color=#FFFFFF>Percentage</font></b>");
		$tpl->assign ( COG, "<b><font color=#FFFFFF>Grade</font></b>" );
		$tpl->assign ( CORD, "<b><font color=#FFFFFF>Order</font></b>" );
		$tpl->assign ( COLOR, "#4d6eb2" );
		$tpl->parse( CO_LIST, ".co_list" );
		*/
	}

	$Q2 = "select e.name, e.a_id, e.percentage, te.grade FROM exam e, take_exam te WHERE te.student_id = '".$row['a_id']."' and te.exam_id = e.a_id and e.is_online = '0'";
	$Q3 = "select e.name, e.a_id, e.percentage, te.grade FROM exam e, take_exam te WHERE te.student_id = '".$row['a_id']."' and te.exam_id = e.a_id and e.is_online = '1' and ( e.public = '1' or (e.end_time != '00000000000000' && e.beg_time <= ".date("YmdHis")." ) )";
	$Q4 = "select h.name, h.a_id, h.percentage, hh.grade FROM homework h, handin_homework hh WHERE hh.student_id = '".$row['a_id']."' and hh.homework_id = h.a_id and (h.public = '1' or h.public = '3')";
	//$Q41 = "select c.name, c.a_id, c.percentage, tc.grade FROM coop c, take_coop tc WHERE tc.student_id = '".$row['a_id']."' and tc.case_id = c.a_id and ( c.public = '1' or (c.end_time != '00000000000000' && c.beg_time <= '".date("YmdHis")."' ) )";
	
	//linsy@20150414,取得該門課的授課教師，並取得其在系統功能設定中，針對是否顯示排名(一般測驗、線上測驗、線上作業及總排名)的資訊
	$Q5 = "SELECT u.id FROM user u, teach_course tc where u.a_id = tc.teacher_id and u.authorization = '1' and tc.course_id = $_SESSION[course_id]";
	if ( !($res5 = mysql_db_query( $DB, $Q5 )) ) {
	}
	else
	{
		$row5 = mysql_fetch_assoc( $res5 );
		$Q6 = "SELECT show_test_rank, show_onlinetest_rank, show_homework_rank, show_all_rank FROM function_list where u_id='$row5[id]'";
		$res6 = mysql_db_query( $DB.$course_id, $Q6 );
		$row6 = mysql_fetch_assoc($res6);			
	}
	
	
	$sum = 0.0;
	if ( !($result = mysql_db_query( $DB.$course_id, $Q2 )) ) {
		$error = "成績資料錯誤!!!";
	}
	$color == "#F0FFEE";
	while ( $row = mysql_fetch_array( $result ) )
	{
		if ( $color == "#F0FFEE" )
			$color = "#E6FFFC";
		else
			$color = "#F0FFEE";
		$tpl->assign( COLOR , $color );
		$tpl->assign ( NNAME, $row['name'] );
		$tpl->assign ( NPER, $row['percentage']."%" );
		if ( $row['grade'] == "-1" ) {
			$row['grade'] = "";
		}
		if ( $row['grade'] < 60 )
			$tpl->assign ( NG, "<font color=#FF0000>".$row['grade']."</font>" );
		else
			$tpl->assign ( NG, $row['grade'] );
		if($row6['show_test_rank'])
			$tpl->assign ( NORD, order( "exam", $row['a_id'] ));
		else
			$tpl->assign ( NORD, "老師未公佈排名");
		
		$tpl->parse( N_LIST, ".n_list" );
		$sum = $sum + $row['grade']*$row['percentage'];
	}

	if ( !($result = mysql_db_query( $DB.$course_id, $Q3 )) ) {
		$error = "成績資料錯誤!!!";
	}
	while ( $row = mysql_fetch_array( $result ) )
	{
		if ( $color == "#F0FFEE" )
			$color = "#E6FFFC";
		else
			$color = "#F0FFEE";
		$tpl->assign( COLOR , $color );
		$tpl->assign ( LNAME, $row['name'] );
		$tpl->assign ( LPER, $row['percentage']."%" );
		if ( $row['grade'] == "-1" ) {
			$row['grade'] = "";
		}
		if ( $row['grade'] < 60 )
			$tpl->assign ( LG, "<font color=#FF0000>".$row['grade']."</font>" );
		else
			$tpl->assign ( LG, $row['grade'] );
		
		if($row6['show_onlinetest_rank'])
			$tpl->assign ( LORD, order( "exam", $row['a_id'] ));
		else
			$tpl->assign ( LORD, "老師未公佈排名");
		
		$tpl->parse( L_LIST, ".l_list" );
		$sum = $sum + $row['grade']*$row['percentage'];
	}

	if ( !($result = mysql_db_query( $DB.$course_id, $Q4 )) ) {
		$error = "成績資料錯誤!!!";
	}
	while ( $row = mysql_fetch_array( $result ) )
	{
		if ( $color == "#F0FFEE" )
			$color = "#E6FFFC";
		else
			$color = "#F0FFEE";
		$tpl->assign( COLOR , $color );
		$tpl->assign ( HWNAME, $row['name'] );
		$tpl->assign ( HWPER, $row['percentage']."%" );
		if ( $row['grade'] == "-1" ) {
			$row['grade'] = "";
		}
		if ( $row['grade'] < 60 )
			$tpl->assign ( HWG, "<font color=#FF0000>".$row['grade']."</font>" );
		else
			$tpl->assign ( HWG, $row['grade'] );
		
		if($row6['show_homework_rank'])
			$tpl->assign ( HORD, order( "homework", $row['a_id'] ));
		else
			$tpl->assign ( HORD, "老師未公佈排名");
		
		$tpl->parse( HW_LIST, ".hw_list" );
		$sum = $sum + $row['grade']*$row['percentage'];
	}
	
/*	if ( !($result = mysql_db_query( $DBC.$course_id, $Q41 )) ) {
		$error = "成績資料錯誤!!!";
	}
	while ( $row = mysql_fetch_array( $result ) )
	{
		if ( $color == "#F0FFEE" )
			$color = "#E6FFFC";
		else
			$color = "#F0FFEE";
		$tpl->assign( COLOR , $color );
		$tpl->assign ( CONAME, $row['name'] );
		$tpl->assign ( COPER, $row['percentage']."%" );
		if ( $row['grade'] == "-1" ) {
			$row['grade'] = "";
		}
		if ( $row['grade'] < 60 )
			$tpl->assign ( COG, "<font color=#FF0000>".$row['grade']."</font>" );
		else
			$tpl->assign ( COG, $row['grade'] );
		$tpl->assign ( CORD, order( "coop", $row['a_id'] ));
		$tpl->parse( CO_LIST, ".co_list" );
		$sum = $sum + $row['grade']*$row['percentage'];
	}
*/
	if ( $version == "C" )
		$tpl->assign ( TSU, "總排名" );
	else
		$tpl->assign ( TSU, "Total Order" );
	$color = "#B0BFC3";
	$tpl->assign( COLOR , $color );
	
	if($row6['show_all_rank'])
		$tpl->assign ( SUM, order ( "total" ) );
	else
		$tpl->assign ( SUM, "老師未公佈排名");
	
	$tpl->parse( TO_LIST, ".to_list" );	

	if ( $version == "C" )
		$tpl->assign ( TSU, "總成績" );
	else
		$tpl->assign ( TSU, "Total grade" );
	$color = "#B0BFC3";
	$tpl->assign( COLOR , $color );
	$total = $sum/100;
	if ( $total < 60 )
		$tpl->assign ( SUM, "<font color=#FF0000>$total</font>" );
	else
		$tpl->assign ( SUM, $total );
	$tpl->parse( TO_LIST, ".to_list" );
	
	$tpl->assign ( MES, $error );
	$tpl->parse( BODY, "body" );
	$tpl->FastPrint("BODY");

	function order ( $type, $a_id = 0 ) {
		global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $course_id, $user_id, $version, $course_year, $course_term;
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			echo ( "資料庫連結錯誤!!" );
			exit;
		}
		$Q1 = "SELECT u.a_id, tc.credit FROM user u, take_course tc WHERE u.a_id=tc.student_id AND tc.course_id='$course_id' and u.id = '$user_id' and tc.year='$course_year' and tc.term = '$course_term'";
		if ( !($result1 = mysql_db_query( $DB, $Q1 ) ) ) {
			echo ( "資料庫讀取錯誤1!!" );
			exit;
		}
		$row1 = mysql_fetch_array( $result1 );
		if ( $row1['credit'] == '1' ) {
			if ( $type != "total" ) {
				if ( $type == "exam" ) {
					$Q2 = "select student_id, grade FROM take_exam WHERE exam_id = '$a_id' order by grade DESC";
				}
				else if ( $type == "coop" ) {
					$Q2 = "select student_id, grade FROM take_coop WHERE case_id = '$a_id' order by grade DESC";
				}
				else {
					$Q2 = "select student_id, grade FROM handin_homework WHERE homework_id = '$a_id' order by grade DESC";
				}
				if ( $type == "coop" ) {
					if ( !($result2 = mysql_db_query( $DBC.$course_id, $Q2 ) ) ) {
						echo ( "資料庫讀取錯誤2!!" );
						exit;
					}
				}
				else {
					if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
						echo ( "資料庫讀取錯誤2!!" );
						exit;
					}
				}
				$i = 0;
				$j = 0;
				$allstu = mysql_num_rows( $result2 );
				while ( $row2 = mysql_fetch_array( $result2 ) ) {
					if ( $i == 0 ) {
						$grade = $row2['grade'];
						if ( $grade != "" && $grade != "-1" )
							$i ++;
					}
					else if ( $grade == $row2['grade'] && $row2['grade'] != "" && $row2['grade'] != "-1" ) {
						$j ++;
					}
					else if ( $row2['grade'] != "" && $row2['grade'] != "-1" ) {
						$i = $i + $j + 1;
						$j = 0;
						$grade = $row2['grade'];
					}
					if ( $row2['student_id'] == $row1['a_id'] ) {
						if ( $row2['grade'] == "" || $row2['grade'] == "-1" )
							$i = 0;
						break;
					}
				}
				if ( $i == 0 ) {
					if ( $version == "C" )
						return "你沒有成績";
					else
						return "You have no grade";
				}
				else
					return "$i/$allstu";
			}
			else {
				$scordata;
				$Q2 = "SELECT u.a_id FROM user u, take_course tc WHERE u.a_id=tc.student_id AND tc.course_id='$course_id' and tc.credit = '1' and tc.year='$course_year' and tc.term = '$course_term'";
				if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) ) {
					echo ( "資料庫讀取錯誤1!!" );
					exit;
				}
				$i = 0;
				$allstu = mysql_num_rows( $result2 );
				while ( $row2 = mysql_fetch_array( $result2 ) ) {
					$scordata[$i][0] = $row2['a_id'];
					$scordata[$i][1] = 0;
					$Q3 = "SELECT te.grade,e.percentage FROM exam e,take_exam te WHERE e.a_id = te.exam_id AND te.student_id = '".$row2['a_id']."' and ( e.public = '1' or (e.end_time != '00000000000000' && e.beg_time <= ".date("YmdHis")." ) ) order by e.a_id";
					if ( !($result3 = mysql_db_query( $DB.$course_id, $Q3 ) ) ) {
						echo ( "資料庫讀取錯誤3!!" );
						exit;
					}
					$Q4 = "SELECT hh.grade,h.percentage FROM homework h, handin_homework hh WHERE h.a_id = hh.homework_id AND hh.student_id='".$row2['a_id']."' and (h.public = '1' or h.public = '3') order by h.a_id";
					if ( !($result4 = mysql_db_query( $DB.$course_id, $Q4 ) ) ) {
						echo ( "資料庫讀取錯誤4!!" );
						exit;
					}/*
					$Q41 = "SELECT tc.grade,c.percentage FROM coop c,take_coop tc WHERE c.a_id = tc.case_id AND tc.student_id = '".$row2['a_id']."' and ( c.public = '1' or (c.end_time != '00000000000000' && c.beg_time <= '".date("YmdHis")."' ) ) order by c.a_id";
					if ( !($result41 = mysql_db_query( $DBC.$course_id, $Q41 ) ) ) {
						echo ( "資料庫讀取錯誤41!!" );
						exit;
					}*/
					while ( $row3 = mysql_fetch_array( $result3 ) ) {
						if ( $row3['grade'] == "-1" )
							$row3['grade'] = "";
						$scordata[$i][1] += $row3['grade']*$row3['percentage'];
					}
					while ( $row4 = mysql_fetch_array( $result4 ) ) {
						if ( $row4['grade'] == "-1" )
							$row4['grade'] = "";
						$scordata[$i][1] += $row4['grade']*$row4['percentage'];
					}/*
					while ( $row41 = mysql_fetch_array( $result41 ) ) {
						if ( $row41['grade'] == "-1" )
							$row41['grade'] = "";
						$scordata[$i][1] += $row41['grade']*$row41['percentage'];
					}*/
					$i ++;
				}
				$scordata = qsort_multiarray ( $scordata, 1 , 1 );
				$k = 0;
				$j = 0;
				for ( $i = 0; $i < count($scordata); $i ++ ) {
					if ( $i == 0 ) {
						$grade = $scordata[$i][1];
						$k ++;
					}
					else if ( $grade == $scordata[$i][1] ) {
						$j ++;
					}
					else {
						$k = $k + $j + 1;
						$j = 0;
						$grade = $scordata[$i][1];
					}
					if ( $scordata[$i][0] == $row1['a_id'] ) {
						if ( $scordata[$i][1] == "" )
							$k = 0;
						break;
					}
				}
				if ( $k == 0 ) {
					if ( $version == "C" )
						return "你沒有成績";
					else
						return "You have no grade";
				}
				else
					return "$k/$allstu";
			}
		}
		else
			return "　";
	}
?>