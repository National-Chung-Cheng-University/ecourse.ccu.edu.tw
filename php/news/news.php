<?php
	require 'fadmin.php';
	update_status ("�[�ݤ��i");
	//echo "heater"."$PHPSESSID";
	
	if ( isset($PHPSESSID) && ($check = session_check_teach($PHPSESSID))) {
		//zqq--
		add_log(22,$user_id,$course_id);

		//--

//intree@2008-01-21, eAccelerator��cache page
//eaccelerator_cache_page($_SERVER['PHP_SELF'].'?GET='.serialize($_GET).'&POST='.serialize($_POST).'&course='.$course_id.'&user_id='.$user_id, 30);

		if ( $submit == "�R��" || $submit == "Del" ) {
			$sub = del_news();
			$subject = "";
			$news = "";
			$onlimit = "";
			show_page_d ( $sub );
		}
		else if( $submit == "�H�H" || $submit == "Mail"){	//�H���i�H���Ҧ��ǥ�
			$sub = mail_to_students();
			$subject = "";
			$news = "";
			$onlimit = "";
			show_page_d ( $sub );
		}
		else if ( !isset($flag) && $check >= 2 )
				show_page_d ();
		else if ( $flag == "1" && $check == 2) {
			if ( checkdate( $start_m, $start_d , $start_y ) && checkdate( $end_m, $end_d , $end_y ) ) {
			if ( $subject != "" ) {
				if ( $news != "" ) {
					if ( ($error = add_news()) == -1 ) {
						$subject2 = stripslashes( $subject );
						$subject = "";
						$news = "";
						$onlimit = "";
						if ( $version == "C" )
							show_page_d ( "���i $subject2 �[�J���\!!" );
						else
							show_page_d ( "News $subject2 Add Successful!!" );
					}
					else
						show_page_d ( $error );
				}
				else if ( $version == "C" )
					show_page_d ( "�м��g���i���e!!!" );
				else
					show_page_d ( "Please Input the Content!!!" );
			}
			else if ( $version == "C" )
				show_page_d ( "�ж�g���i���D!!!" );
			else
				show_page_d ( "Please Input the Subject!!!" );
			}
			else if ( $version == "C" )
				show_page_d ( "������~!!!" );
			else
				show_page_d ( "DATE WRONG!!!" );
		}
		else if ( $check == 1 )
			show_page_d ( "$uesr_id$teacher$course_id" );
		else if ( $version == "C" )
			show_page ( "not_access.tpl", "�v�����~1" );
		else
			show_page ( "not_access.tpl", "Access Deny1" );
	}
	else if ( $version == "C" )
		show_page ( "not_access.tpl", "�v�����~2" );
	else
		show_page ( "not_access.tpl", "Access Deny2" );
	
	check_news ();
	
	function add_news () {
		global $start_y, $start_m, $start_d, $end_y, $end_m, $end_d, $T_y, $T_m, $T_d, $T_w, $course_id;
		$start= $start_y."-".$start_m."-".$start_d;
		$end = $end_y."-".$end_m."-".$end_d;
		$cycle = $T_y."-".$T_m."-".$T_d;
		$week = $T_w;
			
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id, $important, $handle, $subject, $news;
		$Q1 = "insert into news ( begin_day, end_day, cycle, week, important, handle, subject, content) values ( '$start', '$end', '$cycle', '$week', '$important', '$handle', '$subject', '$news' )";
		
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "��Ʈw�s�����~!!";
			return $error;
		}
		if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			$error = "��Ƽg�J���~!!";
			return $error;
		}
		$aid = mysql_insert_id();

		add_log ( 8, "", $aid, $course_id );

		return -1;
	}
	
	function show_page_d ( $message="" ) {
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );

		global $check, $version, $year, $month, $views, $PHPSESSID, $skinnum;
		$tpl->assign( SKINNUM, $skinnum );

//-------------------------------------------------------------------
//2006-02-22
//devon
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id, $order, $user_id;
		
		$Q1 = "select a_id FROM user where id = '$user_id' and authorization='3'";
		if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
			$message = "$message - ��ƮwŪ�����~!!";
		}
		//---��ܦ�ƾ�
//		show_main( $year, $month, $day ,$tpl);		
		
		while( $row = mysql_fetch_array( $result ) )
		{
			$Q2 = "select count(h.a_id) as sum from handin_homework hh, homework h where h.a_id = hh.homework_id and hh.handin_time = '0000-00-00' and hh.student_id = '". $row["a_id"] . "' and (h.public = '1' or h.public = '3')";
			if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) )
			{
				$message = "$message - ��ƮwŪ�����~!!";
			}
			
			$Q3 = "select count(e.a_id) as sum from take_exam te, exam e where e.is_online = '1' and e.a_id = te.exam_id and te.grade = '-1' and te.student_id = '". $row["a_id"] . "' and ( (e.public = '1' or e.public = '3') ||  e.end_time != '00000000000000' ) and e.beg_time <= ".date("YmdHis")." and e.end_time > ".date("YmdHis");
			if ( !($result3 = mysql_db_query( $DB.$course_id, $Q3 ) ) )
			{
				$message = "$message - ��ƮwŪ�����~!!";
			}
			
			$row2 = mysql_fetch_array( $result2 );
			$row3 = mysql_fetch_array( $result3 );
			//$_display�G�p�G�����O�Ѯv�A�N��show�X����@�~�Υ��@����C
			$_display = "<table border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" width=\"35%\"><tr><td><div align=\"right\"><img src=\"/images/skin1/bor/bor_01.GIF\" width=\"12\" height=\"11\"></div></td><td><div align=\"center\"><img src=\"/images/skin1/bor/bor_02.GIF\" width=\"100%\" height=\"11\"></div></td><td><div align=\"left\"><img src=\"/images/skin1/bor/bor_03.GIF\" width=\"17\" height=\"11\"></div></td></tr><tr height=10><td><div align=\"right\"><img src=\"/images/skin1/bor/bor_04.GIF\" width=\"12\" height=\"100%\"></div></td><td bgcolor=\"#CCCCCC\"><table border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"1\"><tr bgcolor=\"#000066\" align=\"center\"><td><a href=\"../Testing_Assessment/show_allwork.php\"><font size=2 color=\"white\">����@�~</font></a></div></td><td><a href=\"../Testing_Assessment/show_alltest.php\"><font size=2 color=\"white\">��������</font></a></td></tr><tr bgcolor=\"#FFFFCC\" align=\"center\"><td><font size=2 color=\"red\">".$row2["sum"]."</font></td><td><font size=2 color=\"red\">".$row3["sum"]."</font></td></tr>";
			$_display_E = "<table border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" width=\"35%\"><tr><td><div align=\"right\"><img src=\"/images/skin1/bor/bor_01.GIF\" width=\"12\" height=\"11\"></div></td><td><div align=\"center\"><img src=\"/images/skin1/bor/bor_02.GIF\" width=\"100%\" height=\"11\"></div></td><td><div align=\"left\"><img src=\"/images/skin1/bor/bor_03.GIF\" width=\"17\" height=\"11\"></div></td></tr><tr height=10><td><div align=\"right\"><img src=\"/images/skin1/bor/bor_04.GIF\" width=\"12\" height=\"100%\"></div></td><td bgcolor=\"#CCCCCC\"><table border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"1\"><tr bgcolor=\"#000066\" align=\"center\"><td><font size=2 color=\"white\">New Homework</font></div></td><td><font size=2 color=\"white\">New Exam</font></td></tr><tr bgcolor=\"#FFFFCC\" align=\"center\"><td><font size=2 color=\"red\">".$row2["sum"]."</font></td><td><font size=2 color=\"red\">".$row3["sum"]."</font></td></tr>";
			
			//�ǲ߷ū׭p���p���k
			//Q4�O�n�ƥX�ǥͬݹL�����`��
			$Q4 = "select count(*) as sum from log where user_id='".$row["a_id"]."' and event_id='3'";
			//Q5�h�O���X�o�ҵ{�Ҧ����`��
			$Q5 = "select count(*) as sum from chap_title";
			
			$result4 = mysql_db_query( $DB.$course_id, $Q4 ) or die("��ƮwŪ�����~, $Q4");
			$result5 = mysql_db_query( $DB.$course_id, $Q5 ) or die("��ƮwŪ�����~, $Q5");
			$row4 = mysql_fetch_array( $result4 );
			$row5 = mysql_fetch_array( $result5 );
			if($row4[sum] == 0 || $row5[sum] == 0)
				$temperature=0;
			else
				$temperature = round(( $row4["sum"]  / $row5["sum"]  ) * 100);
			if($temperature>100)
				$temperature=100;
			//���B��$_display�@�άO�[�J�ǲ߷ū׭p
			$_display = $_display."<tr bgcolor=\"#FFFFCC\" align=\"center\"><td><a href=\"../textbook/material.php\"><font size=2 color=\"#0066FF\"><strong>�ǲ߷ū׭p($temperature%)</strong></font></a></td><td align=left><div align=\"left\"><IMG SRC=\"/images/indicator.jpg\" width=\"$temperature%\" height=7></div></td></tr></table></td><td><div align=\"left\"><img src=\"/images/skin1/bor/bor_06.GIF\" width=\"17\" height=\"100%\"></div></td></tr><tr><td><div align=\"right\"><img src=\"/images/skin1/bor/bor_07.GIF\" width=\"12\" height=\"17\"></div></td><td><div align=\"center\"><img src=\"/images/skin1/bor/bor_08.GIF\" width=\"100%\" height=\"17\"></div></td><td><div align=\"left\"><img src=\"/images/skin1/bor/bor_09.GIF\" width=\"17\" height=\"17\"></div></td></tr></table>";
			$_display_E = $_display_E."<tr bgcolor=\"#FFFFCC\" align=\"center\"><td><font size=2 color=\"#0066FF\"><strong>Learning Thermograph($temperature%)</strong></font></td><td align=left><div align=center><IMG SRC=\"/images/indicator.jpg\" width=\"$temperature%\" height=7></div></td></tr></table></td><td><div align=\"left\"><img src=\"/images/skin1/bor/bor_06.GIF\" width=\"17\" height=\"100%\"></div></td></tr><tr><td><div align=\"right\"><img src=\"/images/skin1/bor/bor_07.GIF\" width=\"12\" height=\"17\"></div></td><td><div align=\"center\"><img src=\"/images/skin1/bor/bor_08.GIF\" width=\"100%\" height=\"17\"></div></td><td><div align=\"left\"><img src=\"/images/skin1/bor/bor_09.GIF\" width=\"17\" height=\"17\"></div></td></tr></table>";

		}
		
//-------------------------------------------------------------------

		if ( !isset($views) )
			$views = 0;

		if ( $views == 4 || $views == 0 || $views == 3 || $views == 1) {
			if ( $version == "C" ) {
				$tpl->define ( array ( head => "news_headl.tpl" ) );
				if ( $check >= 2 ) {
					$tpl->assign( OPTION1 , "<option value=\"3\" V3>���Ĥ��i</option>" );
					$tpl->assign( OPTION2 , "<option value=\"4\" V4>�L�Ĥ��i</option>" );
					//-------------------------------------------
					$tpl->assign( DISPLAY, $_display );
					//-------------------------------------------
				}
				else {
					$tpl->assign( OPTION1 , "" );
					$tpl->assign( OPTION2 , "" );
					//-------------------------------------------
					$tpl->assign( DISPLAY, $_display );
					//-------------------------------------------
				}
			}
			else {
				$tpl->define ( array ( head => "news_headl_E.tpl" ) );
				if ( $check >= 2 ) {
					$tpl->assign( OPTION1 , "<option value=\"3\" V3>Effective</option>" );
					$tpl->assign( OPTION2 , "<option value=\"4\" V4>Ineffective</option>" );
					//-------------------------------------------
					$tpl->assign( DISPLAY, $_display_E );
					//-------------------------------------------
				}
				else {
					$tpl->assign( OPTION1 , "" );
					$tpl->assign( OPTION2 , "" );
					//-------------------------------------------
					$tpl->assign( DISPLAY, $_display_E );
					//-------------------------------------------
				}
			}
			$tpl->assign( "V".$views , "selected" );
			if ( $year == "" )
				$year = date("Y");
			if ( $month == "" )
				$month = date("m");
		}
		else {
			if ( $version == "C" ) {
				$tpl->define ( array ( head => "news_head.tpl" ) );
				if ( $check >= 2 ) {
					$tpl->assign( OPTION1 , "<option value=\"3\" V3>���Ĥ��i</option>" );
					$tpl->assign( OPTION2 , "<option value=\"4\" V4>�L�Ĥ��i</option>" );
					//-------------------------------------------
					$tpl->assign( DISPLAY, $_display );
					//-------------------------------------------
				}
				else {
					$tpl->assign( OPTION1 , "" );
					$tpl->assign( OPTION2 , "" );
					//-------------------------------------------
					$tpl->assign( DISPLAY, $_display );
					//-------------------------------------------
				}
			}
			else {
				$tpl->define ( array ( head => "news_head_E.tpl" ) );
				if ( $check >= 2 ) {
					$tpl->assign( OPTION1 , "<option value=\"3\" V3>Effective</option>" );
					$tpl->assign( OPTION2 , "<option value=\"4\" V4>Ineffective</option>" );
					//-------------------------------------------
					$tpl->assign( DISPLAY, $_display_E );
					//-------------------------------------------
				}
				else {
					$tpl->assign( OPTION1 , "" );
					$tpl->assign( OPTION2 , "" );
					//-------------------------------------------
					$tpl->assign( DISPLAY, $_display_E );
					//-------------------------------------------
				}
			}
			$tpl->define_dynamic ( "year_list" , "head" );
			$tpl->assign( "V".$views , "selected" );
			for ( $i = -3 ; $i <= 3 ; $i++ ) {
				if ( $year == "" ) 
					$y = date("Y") + $i;
				else
					$y = $year + $i;
				if ( $i == 0  )
					$tpl->assign( YVD , $y ." selected" );
				else
					$tpl->assign( YVD , $y );
				$tpl->assign( YID , $y );
				$tpl->parse ( YEAR_LIST, ".year_list" );
			}
			if ( $month == "" )
				$month = date("m");
			$tpl->assign( "LM".$month , "selected" );
			$tpl->assign( "MONT" , $month );
			$tpl->assign( "YEARV" , $year );
			$tpl->assign( "HVLIM" , "<input type=hidden name=views value=$views>" );
			if ( $year == "" )
				$year = date("Y");
		}
//body body		
		if ( $views == 0 ) {
			//$Q1 = "select n.a_id, n.begin_day, n.subject, n.important, n.system, l.tag3, n.cycle, n.week FROM news n ,log l where l.tag1 = n.a_id and l.event_id = '8' and n.system = '0' and n.end_day >= '".date("Y-m-d")."'";
			$Q1 = "select n.a_id, n.begin_day, n.subject, n.important, n.system, l.tag3, n.cycle, n.week FROM news n ,log l where l.tag1 = n.a_id and l.event_id = '8' and n.system = '0' and n.begin_day <= '".date("Y-m-d")."' and n.end_day >= '".date("Y-m-d")."'";
			//$Q11 = "select n.a_id, n.begin_day, n.subject, n.important, n.system, l.tag3, n.cycle, n.week FROM news n ,log l where n.system = '1' and l.tag1 = n.a_id and l.event_id = '8' and n.begin_day <= '".date("Y-m-d")."' and n.end_day >= '".date("Y-m-d")."'";
		}
		else if ( $views == 2 ) {
			//$Q1 = "select n.a_id, n.begin_day, n.subject, n.important, n.system, l.tag3, n.cycle, n.week FROM news n ,log l where l.tag1 = n.a_id and l.event_id = '8' and n.system = '0' and n.begin_day >= '$year-$month-01' and n.end_day >= '".date("Y-m-d")."'";
			$Q1 = "select n.a_id, n.begin_day, n.subject, n.important, n.system, l.tag3, n.cycle, n.week FROM news n ,log l where l.tag1 = n.a_id and l.event_id = '8' and n.system = '0' and n.begin_day >= '$year-$month-01' and n.begin_day <= '".date("Y-m-d")."' and n.end_day >= '".date("Y-m-d")."'";
			//$Q11 = "select n.a_id, n.begin_day, n.subject, n.important, n.system, l.tag3, n.cycle, n.week FROM news n ,log l where n.system = '1' and l.tag1 = n.a_id and l.event_id = '8' and n.begin_day >= '$year-$month-01' and n.begin_day <= '".date("Y-m-d")."' and n.end_day >= '".date("Y-m-d")."'";
		}
		else if ( $views == 3 ) {
			//$Q1 = "select n.a_id, n.begin_day, n.subject, n.important, n.system, l.tag3, n.cycle, n.week FROM news n ,log l where l.tag1 = n.a_id and l.event_id = '8' and n.system = '0' and n.end_day >= '".date("Y-m-d")."'";
			$Q1 = "select n.a_id, n.begin_day, n.subject, n.important, n.system, l.tag3, n.cycle, n.week FROM news n ,log l where l.tag1 = n.a_id and l.event_id = '8' and n.system = '0' and n.begin_day <= '".date("Y-m-d")."' and n.end_day >= '".date("Y-m-d")."'";
			//$Q11 = "select n.a_id, n.begin_day, n.subject, n.important, n.system, l.tag3, n.cycle, n.week FROM news n ,log l where n.system = '1' and l.tag1 = n.a_id and l.event_id = '8' and n.begin_day <= '".date("Y-m-d")."' and n.end_day >= '".date("Y-m-d")."'";
		}
		else if ( $views == 4 ) {
			//$Q1 = "select n.a_id, n.begin_day, n.subject, n.important, n.system, l.tag3, n.cycle, n.week FROM news n ,log l where l.tag1 = n.a_id and l.event_id = '8' and n.system = '0' and ( n.end_day < '".date("Y-m-d")."' or  cycle != '0000-00-00' or week != '0' )";
			$Q1 = "select n.a_id, n.begin_day, n.subject, n.important, n.system, l.tag3, n.cycle, n.week FROM news n ,log l where l.tag1 = n.a_id and l.event_id = '8' and n.system = '0' and ( n.end_day < '".date("Y-m-d")."' or n.begin_day > '".date("Y-m-d")."' or  cycle != '0000-00-00' or week != '0' )";
			//$Q11 = "select n.a_id, n.begin_day, n.subject, n.important, n.system, l.tag3, n.cycle, n.week FROM news n ,log l where l.tag1 = n.a_id and l.event_id = '8' and n.system = '1' and ( n.end_day < '".date("Y-m-d")."' or n.begin_day > '".date("Y-m-d")."' or  cycle != '0000-00-00' or week != '0' )";
		}
		else {
			$Q1 = "select n.a_id, n.begin_day, n.subject, n.important, n.system, l.tag3, n.cycle, n.week FROM news n ,log l where l.tag1 = n.a_id and l.event_id = '8' and n.system = '0' and n.begin_day <= '".date("Y-m-d")."' and n.begin_day like '".date("Y-m")."-__' and n.end_day >= '".date("Y-m-d")."'";
			//$Q11 = "select n.a_id, n.begin_day, n.subject, n.important, n.system, l.tag3, n.cycle, n.week FROM news n ,log l where l.tag1 = n.a_id and l.event_id = '8' and n.system = '1' and n.begin_day <= '".date("Y-m-d")."' and n.begin_day like '".date("Y-m")."-__' and n.end_day >= '".date("Y-m-d")."'";
		}

		if ( $check == 2 ) {
			if ( $version == "C" ) {
				$tpl->assign( DELETE , "<TD ><div align=\"center\"><FONT color=#ffffff size=2>�R��</FONT></div></TD>" );
				$tpl->assign( MAILALL , "<TD ><div align=\"center\"><FONT color=#ffffff size=2>���i�H</FONT></div></TD>" );
				$tpl->assign( POST_LINE, "<br><font size=\"2\"><a href=\"#news\">�E�o�G�s�����E</a></font><br><br>" );
				$tpl->assign( ENDLINE , "<br><font size=2><a href=\"#news\">�E�o�G�s�����E</a></font><br>" );
			}
			else {
				$tpl->assign( DELETE , "<TD ><div align=\"center\"><FONT color=#ffffff size=2>Del</FONT></div></TD>" );
				$tpl->assign( MAILALL , "<TD ><div align=\"center\"><FONT color=#ffffff size=2>Maill to Students</FONT></div></TD>" );
				$tpl->assign( POST_LINE, "<br><font size=\"2\"><a href=\"#news\">�EPost Announcement�E</a></font><br><br>" );
				$tpl->assign( ENDLINE , "<br><font size=2><a href=\"#news\">�EPost Announcement�E</a></font><br>" );
			}
		}
		else {
			$tpl->assign( DELETE , "" );
			$tpl->assign( MAILALL , "" );
			$tpl->assign( POST_LINE, "" );
			$tpl->assign( ENDLINE , "</center></body></html>" );
		}

		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id, $order;
		
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "��Ʈw�s�����~!!";
		}else if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			$error = "��ƮwŪ�����~!!";
		}
		/*else if ( !($result1 = mysql_db_query( $DB, $Q11 ) ) ) {
			$error = "��ƮwŪ�����~!!";
		}*/
		else {
			//if ( mysql_num_rows( $result ) != 0 || mysql_num_rows( $result1 ) != 0) {
			if ( mysql_num_rows( $result ) != 0 ) {
				$tpl->define ( array ( body => "news_body.tpl" ) );
				
				$tpl->define_dynamic ( "news_list" , "body" );
				$color = "#F0FFEE";
				$i = 0;
				
				while ( $row = mysql_fetch_array( $result ) ) {
					$newdata[$i][0] = (int)$row['a_id'];
					$newdata[$i][1] = $row['begin_day'];
					$newdata[$i][2] = $row['subject'];
					$newdata[$i][3] = (int)$row['important'];
					$newdata[$i][4] = $row['system'];
					$newdata[$i][5] = $row['cycle'];
					$newdata[$i][6] = $row['week'];
					$newdata[$i][7] = $row['tag3'];
					$i ++;
					//echo $newdata[$i-1][2]."<br>";
				}
				/*
				if ( $check < 3 ) {
					while ( $row = mysql_fetch_array( $result1 ) ) {
						$newdata[$i][0] = (int)$row['a_id'];
						$newdata[$i][1] = $row['begin_day'];
						$newdata[$i][2] = $row['subject'];
						$newdata[$i][3] = (int)$row['important'];
						$newdata[$i][4] = $row['system'];
						$newdata[$i][5] = $row['cycle'];
						$newdata[$i][6] = $row['week'];
						$newdata[$i][7] = $row['tag3'];
						$i ++;
					}
				}
				*/
				if ( !isset($order) )
					$order = 1;
				$newdata = qsort_multiarray ( $newdata, $order, 1 );
				$i = 0;
				$count = 0;
				while ( $newdata[$i][0] != "" ) {
					$i++;
					if ( $views != 4 ) {
						if ( $newdata[$i - 1][6] != "0" && $newdata[$i - 1][6]%7 != date("w")  )
							continue;
						if ( substr( $newdata[$i - 1][5], 0 , 4 ) != "0000" && substr( $newdata[$i - 1][5], 0 , 4 ) != date("Y") )
							continue;
						if ( substr( $newdata[$i - 1][5], 5 , 2 ) != "00" && substr( $newdata[$i - 1][5], 5 , 2 ) != date("m") ) 
							continue;
						if ( substr( $newdata[$i - 1][5], 8 , 2 ) != "00" && substr( $newdata[$i - 1][5], 8 , 2 ) != date("d") )
							continue;
					}
					else {
						if ( $newdata[$i - 1][6] != "0" || $newdata[$i - 1][5] != "0000-00-00" ) {
							if ( $newdata[$i - 1][6] != "0" && $newdata[$i - 1][6]%7 == date("w")  )
								continue;
							if ( substr( $newdata[$i - 1][5], 0 , 4 ) != "0000" && substr( $newdata[$i - 1][5], 0 , 4 ) == date("Y") )
								continue;
							if ( substr( $newdata[$i - 1][5], 5 , 2 ) != "00" && substr( $newdata[$i - 1][5], 5 , 2 ) == date("m") )
								continue;
							if ( substr( $newdata[$i - 1][5], 8 , 2 ) != "00" && substr( $newdata[$i - 1][5], 8 , 2 ) == date("d") )
								continue;						
						}
					}
					$i--;
					$count ++;
					if ( $check == 2 ) {
						$tpl->assign( FORM1 , "<form method=post action=news.php>" );
						$tpl->assign( FORM2 , "</form>" );
					}
					else {
						$tpl->assign( FORM1 , "" );
						$tpl->assign( FORM2 , "" );
						$tpl->assign( DELETE, "" );
						$tpl->assign( MAILALL, "" );
					}
					$tpl->assign( BCOLOR , $color );	
					if ( $check == 2  ){
						if ( $newdata[$i][4] == 0 ) {
							if ( $version == "C" ){
								$tpl->assign( DATE , "<input type=hidden name=views value=$views><input type=hidden name=a_id value=".$newdata[$i][0]."><input type=submit name=submit value=�R�� onclick=\"return confirm('�T�w�n�R�������i��?')\"></font></div></td><td align=left><div align=\"center\"><FONT size=2>".$newdata[$i][1] );
								$tpl->assign( MAILTOS , "<td align=left><div align=\"center\"><FONT size=2><input type=hidden name=views value=$views><input type=hidden name=system value=0><input type=hidden name=a_id value=".$newdata[$i][0]."><input type=submit name=submit value=�H�H onclick=\"return confirm('�T�w�n�H���Ҧ��ǥͦ����i��?')\"></font></div></td>" );
							}	
							else{
								$tpl->assign( DATE , "<input type=hidden name=views value=$views><input type=hidden name=a_id value=".$newdata[$i][0]."><input type=submit name=submit value=Del onclick=\"return confirm('Sure to Delete?')\"></font></div></td><td align=left><div align=\"center\"><FONT size=2>".$newdata[$i][1] );
								$tpl->assign( MAILTOS , "<td align=left><div align=\"center\"><FONT size=2><input type=hidden name=views value=$views><input type=hidden name=system value=0><input type=hidden name=a_id value=".$newdata[$i][0]."><input type=submit name=submit value=Mail onclick=\"return confirm('�T�w�n�H���Ҧ��ǥͦ����i��?')\"></font></div></td>" );
							}	
						}
						else {
							if ( $version == "C" ){
								$tpl->assign( DATE , "�t�Τ��i</font></div></td><td align=left><div align=\"center\"><FONT size=2>".$newdata[$i][1] );
								$tpl->assign( MAILTOS , "<td align=left><div align=\"center\"><FONT size=2><input type=hidden name=views value=$views><input type=hidden name=system value=1><input type=hidden name=a_id value=".$newdata[$i][0]."><input type=submit name=submit value=�H�H onclick=\"return confirm('�T�w�n�H���Ҧ��ǥͦ����i��?')\"></font></div></td>" );
							}
							else{
								$tpl->assign( DATE , "SysInfo</font></div></td><td align=left><div align=\"center\"><FONT size=2>".$newdata[$i][1] );
								$tpl->assign( MAILTOS , "<td align=left><div align=\"center\"><FONT size=2><input type=hidden name=views value=$views><input type=hidden name=system value=1><input type=hidden name=a_id value=".$newdata[$i][0]."><input type=submit name=submit value=Mail onclick=\"return confirm('�T�w�n�H���Ҧ��ǥͦ����i��?')\"></font></div></td>" );					
							}
						}						
					}							
					else{
						$tpl->assign( DATE , $newdata[$i][1] );
						$tpl->assign( MAILTOS , "" ); //�ǥͨS���H�q���H���\��
					}
					$tpl->assign( VIEWS , $views );
					if ( $version == "C" )
						if ( $newdata[$i][3] == 2 )
							$tpl->assign( IMP , "<font color=#FF0000>�̰�����</font>" );
						else if ( $newdata[$i][3] == 1 )
							$tpl->assign( IMP , "<font color=#336633>�@�뵥��</font>" );
						else
							$tpl->assign( IMP , "�̧C����" );
					else
						if ( $newdata[$i][3] == 2 )
							$tpl->assign( IMP , "<font color=#FF0000>Important</font>" );
						else if ( $newdata[$i][3] == 1 )
							$tpl->assign( IMP , "<font color=#336633>Generic</font>" );
						else
							$tpl->assign( IMP , "Lowest" );
	
					$new = date("d") - 2;
					if ( $new < 10 )
						$new = "0$new";
					$tpl->assign( AID , $newdata[$i][0] );
					$tpl->assign( SYS , $newdata[$i][4] );
					$tpl->assign( PHPSD , $PHPSESSID );
					if ( date("Y")."-".date("m")."-$new" <= $newdata[$i][1] )
						$tpl->assign( LINK , $newdata[$i][2]."<img src=/images/new.gif border=0>" );
					else
						$tpl->assign( LINK , $newdata[$i][2] );
	
					$Q2 = "select SUM( tag3 ) As sum from log where event_id = '8'";
					if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
						$error = "��ƮwŪ�����~!!";
					}
					else
						$row2 = mysql_fetch_array( $result2 );
	
					$Q3 = "select SUM( tag3 ) As sum from log where event_id = '8'";
					if ( !($result3 = mysql_db_query( $DB, $Q3 ) ) ) {
						$error = "��ƮwŪ�����~!!";
					}
					else
						$row3 = mysql_fetch_array( $result3 );
	
					if ( $newdata[$i][7] == '' )
						$newdata[$i][7] = "0";
	
					$tpl->assign( TIME , $newdata[$i][7] );
					$sum = $row2['sum'] + $row3['sum'];
					if ( $sum != "" && $sum != 0 )
						$tpl->assign( PERCONE ,$newdata[$i][7]/$sum*100 );
					else
						$tpl->assign( PERCONE , 0 );
	
					if ( $color == "#F0FFEE" )
						$color = "#E6FFFC";
					else 
						$color = "#F0FFEE";
					$tpl->parse ( N_LIST, ".news_list" );
					$i ++;
				}
				$error = "";
			}
			else {
				if ( $version == "C" )
					$error = "�ثe�S�����i";
				else
					$error = "There is no News";
			}

			$tpl->assign( MES, $error );
			$tpl->parse( HEAD, "head" );
			$tpl->FastPrint("HEAD");

			//if ( ( mysql_num_rows( $result ) != 0 || mysql_num_rows( $result1 ) != 0) && $count != 0 ) {
			if ( ( mysql_num_rows( $result ) != 0 ) && $count != 0 ) {
				$tpl->parse( BODY, "body" );
				$tpl->FastPrint("BODY");
			}
			$tpl->define ( array ( bodb => "newsb_body.tpl" ) );
			$tpl->parse( BODB, "bodb" );
			$tpl->FastPrint("BODB");
		}

//tail post
		if ( $check == 2 ) {
			global $style;
			if ( $version == "C" )
				if ( $style == 2 )
					$tpl->define ( array ( tail => "newst_post.tpl" ) );
				else if ( $style == 1 )
					$tpl->define ( array ( tail => "newsl_post.tpl" ) );
				else
					$tpl->define ( array ( tail => "news_post.tpl" ) );
			else
				if ( $style == 2 )
					$tpl->define ( array ( tail => "newst_post_E.tpl" ) );
				else if ( $style == 1 )
					$tpl->define ( array ( tail => "newsl_post_E.tpl" ) );
				else
					$tpl->define ( array ( tail => "news_post_E.tpl" ) );

			global $start_y, $start_m, $start_d;
			$tpl->define_dynamic ( "start_y" , "tail" );
			for ( $i = 0 ; $i <= 3 ; $i++ ) {
				if ( $start_y == "" )
					$y = date("Y") + $i;
				else
					$y = $start_y + $i;
				if ( $i == 0 )
					$tpl->assign( SYV , $y ." selected" );
				else
					$tpl->assign( SYV , $y );
				$tpl->assign( SYD , $y );
				$tpl->parse ( START_Y, ".start_y" );
			}
			if ( $start_m == "" )
				$m = date("m");
			else
				$m = $start_m;
			$tpl->assign( "SM".$m , "selected" );
			
			if ( $start_d == "" )
				$d = date("d");
			else
				$d = $start_d;
			$tpl->assign( "SD".$d , "selected" );

			if ( $style > 0 ) {
				global $end_y, $end_m, $end_d, $handle;
				$tpl->define_dynamic ( "end_y" , "tail" );
				for ( $i = 0 ; $i <= 3 ; $i++ ) {
					if ( $end_y == "" )
						$y = date("Y") + $i;
					else
						$y = $end_y + $i;
					if ( $i == 0 )
						$tpl->assign( EYV , $y ." selected" );
					else
						$tpl->assign( EYV , $y );
					$tpl->assign( EYD , $y );
					$tpl->parse ( END_Y, ".end_y" );
				}
				if ( $end_m == "" )
					$m = date("m");
				else
					$m = $end_m;
				$tpl->assign( "EM".$m , "selected" );

				if ( $end_d == "" )
					$d = date("d");
				else
					$d = $end_d;
				$tpl->assign( "ED".$d , "selected" );
				if ( $handle == "" )
					$handle = 0;
				$tpl->assign( "H".$handle , "selected" );
			}
			
			global $important;
			if ( $important == "" )
				$important = 1;
			$tpl->assign( "I".$important , "selected" );
			
			if ( $style == 2 ) {
				global $T_y, $T_m, $T_d, $T_w;
				$tpl->define_dynamic ( "t_y" , "tail" );
				if ( $T_y == "0000" || $T_y == "" )
					$tpl->assign( TYV , "0000 selected" );
				else
					$tpl->assign( TYV , "0000" );
				
				if ( $version == "C" )
					$tpl->assign( TYD , "�C�~" );
				else
					$tpl->assign( TYD , "Every Year" );
				$tpl->parse ( T_Y, ".t_y" );
				for ( $i = 0 ; $i <= 3 ; $i++ ) {
					if ( $T_y == "" || $T_y == "0000" ) {
						$y = date("Y") + $i;
						$tpl->assign( TYV , $y );
					}
					else {
						$y = $T_y + $i;
						if ( $i == 0 )
							$tpl->assign( TYV , $y ." selected" );
						else
							$tpl->assign( TYV , $y );
					}
					$tpl->assign( TYD , $y );
					$tpl->parse ( T_Y, ".t_y" );
				}
				if ( $T_m == "" )
					$m = "00";
				else
					$m = $T_m;
				$tpl->assign( "TM".$m , "selected" );

				if ( $T_d == "" )
					$d = "00";
				else
					$d = $T_d;
				$tpl->assign( "TD".$d , "selected" );
				
				if ( $T_w == "" )
					$w = "00";
				else
					$w = $T_w;
				$tpl->assign( "TW".$w , "selected" );

			}
			global $subject, $news, $year, $month;
			$subject = stripslashes( $subject );
			$subject = addslashes( $subject );
			$news = stripslashes( $news );
			$news = addslashes( $news );

			$tpl->assign( SUB, $subject );
			$tpl->assign( NEWS, $news );
			$tpl->assign( MEG, $message );
			$tpl->assign( VIEWS, $views );
			$tpl->assign( HIDDEN1, "<input type=hidden name=year value=$year><input type=hidden name=month value=$month>" );
			$tpl->assign( HIDDEN2, "<input type=hidden name=style value=$style>" );
			$tpl->parse( TAIL, "tail" );	
			$tpl->FastPrint("TAIL");
		}
	}
	
	function check_news () {
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id;
		$Q1 = "select a_id from news where end_day <= '".date("Y-m-d")."' and handle = '0'";
		
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "��Ʈw�s�����~!!";
		}else if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			$error = "��Ʈw�R�����~!!";
		}
		else {
			while ( $row = mysql_fetch_array ( $result ) ) {
				$Q2 = "delete FROM news where a_id = '". $row['a_id'] ."'";
				$Q3 = "delete from log where event_id = '8' and tag1 = '". $row['a_id'];
				if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
					$error = "��Ʈw�R�����~!!";
				}else if ( !($result3 = mysql_db_query( $DB.$course_id, $Q3 ) ) ) {
					$error = "��Ʈw�R�����~!!";
				}
			}
		}
	}

	function del_news () {
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id, $a_id, $version;
		$Q1 = "select subject from news where a_id = '$a_id' and system = '0'";
		$Q2 = "delete FROM news where a_id = '$a_id' and system = '0'";
		$Q3 = "delete FROM log where tag1 = '$a_id' and event_id = '8'";
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "��Ʈw�s�����~!!";
		}else if ( !($result1 = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			$error = "��ƮwŪ�����~1!!";
		}else if( mysql_num_rows( $result1 ) != 0 ) {
			if ( !($result = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
				$error = "��Ʈw�R�����~2!!";
			}
			else if ( !($result = mysql_db_query( $DB.$course_id, $Q3 ) ) ) {
				$error = "��Ʈw�R�����~3!!";
			}else if ( $row = mysql_fetch_array( $result1 ) ) {
				if ( $version == "C" )
					$error = "���i ". $row['subject'] ." �R������";
				else
					$error = "News ". $row['subject'] ." Deleted";
			}else {
				$error = "��ƿ��~!!";
			}
		}
		return $error;
	}
	
	
	//�H���i�����e���Ҧ��ǥ�
	function mail_to_students () {
		//modify by chiefboy1230, add $user_id to global variable
		//global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id, $a_id, $system, $version, $course_year, $course_term;
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id, $a_id, $user_id, $system, $version, $course_year, $course_term;		
		//echo $course_year;		
		//echo $course_term;
		$Q1 = "select * from news where a_id = '$a_id' and system = '$system'";		
		
		if ( !($result1 = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			$error = "��ƮwŪ�����~1!!";
		}else if( $row = mysql_fetch_array( $result1 ) ) {		
			$Q2 = "select name from course where a_id='".$course_id."'";
			$result2 = mysql_db_query( $DB, $Q2 );
			$row2 = mysql_fetch_array( $result2 );	

			//���X�}�ҦѮv�H�c
			$teacher_email = "";
			//modify by chiefboy1230, fix 'mail post to all students' displaying error sender e-mail.
			//$Q3 =  "select u.email from teach_course tc, user u where tc.course_id='".$course_id."' and tc.year='".$course_year."' and tc.term='".$course_term."' and tc.teacher_id=u.a_id";
			$Q3 =  "select u.email from teach_course tc, user u where tc.course_id='".$course_id."' and tc.year='".$course_year."' and tc.term='".$course_term."' and u.id='".$user_id."'";
			$result3 = mysql_db_query( $DB, $Q3 );
			$row3 = mysql_fetch_array( $result3);
			$teacher_email=$row3['email'];
	
			//���X�׽ҾǥͫH�c
			$Q3 = "select u.email from take_course tc, user u where tc.course_id='".$course_id."' and tc.year='".$course_year."' and tc.term='".$course_term."' and tc.student_id=u.a_id";
			$result3 = mysql_db_query( $DB, $Q3 );

			$email = array();
			$index = 0;
			$tmp_list = "";
			while ($row3 = mysql_fetch_array($result3)) {
				if ($row3['email'] != NULL) {
					$tmp_list = $tmp_list . $row3['email'] . ",";
				}

				if (strlen($tmp_list) >= 300) {
					$email[$index] = $tmp_list;
					$tmp_list = "";
					$index++;
				}
			}
			$email[$index] = $tmp_list;
			$index++;
			//�ǥͤH�ƹL�h���ܡA���h���H�H
			$is_succeed = true;
			for ($i=0;$i<count($email);$i++) {
				$header = "From: $teacher_email" . "\n";
				$header .= "Bcc: " . $email[$i] . "\n";
				$config = "-fstudy@mail.elearning.ccu.edu.tw";
				$message = $row['content'];
				//echo $header."<br>";
				$r = mail("", $row2['name']."�q���H-".$row['subject'], $message, $header, $config);
				if (!$r) {
					$is_succeed= false;				
					break;
				}
			}

			if ($is_succeed == true)
				$error = "�H�H����<BR>";
			else
				$error = "�H�H����<BR>";
				//$error = "�H�H���ѡA�ǥͤ�e-mail���~�A���ˬd�]�C��ǥͭ���J�@��email�Aemail�����i���u,�v�B�u;�v�Ψ�ӡu.�v�^<BR>";	
				//$error = "�H�H���ѡA�ǥͤ�e-mail���~�A���ˬd�]�C��ǥͭ���J�@��email�Aemail�����i���u,�v�B�u;�v�Ψ�ӡu.�v�^<BR>";	
		}
		else{
			$error = "�H�H���ѡA�S�������i<BR>";
		}
		return $error;
	}
?>
