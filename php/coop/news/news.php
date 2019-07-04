<?php
	require 'fadmin.php';
	include("mail.php");
	update_status ("觀看公告");
	if ( isset($PHPSESSID) && session_check_teach($PHPSESSID) && ($check = check_group ( $course_id, $coopgroup, $coopcaseid )) != 0 ) {
		if ( $submit == "刪除" || $submit == "Del" ) {
			$sub = del_news();
			$subject = "";
			$news = "";
			$onlimit = "";
			show_page_d ( $sub );
		}
		else if ( !isset($flag) && $check != 0 ) {	
			show_page_d ();
		}
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
							show_page_d ( "公告 $subject2 加入成功!!" );
						else
							show_page_d ( "News $subject2 Add Successful!!" );
					}
					else
						show_page_d ( $error );
				}
				else if ( $version == "C" )
					show_page_d ( "請撰寫公告內容!!!" );
				else
					show_page_d ( "Please Input the Content!!!" );
			}
			else if ( $version == "C" )
				show_page_d ( "請填寫公告標題!!!" );
			else
				show_page_d ( "Please Input the Subject!!!" );
			}
			else if ( $version == "C" )
				show_page_d ( "日期錯誤!!!" );
			else
				show_page_d ( "DATE WRONG!!!" );
		}
		else if ( $check == 0 )
			show_page_d ( "$uesr_id$teacher$course_id" );
		else if ( $version == "C" )
			show_page ( "not_access.tpl", "權限錯誤" );
		else
			show_page ( "not_access.tpl", "Access Deny" );
	}
	else if ( $version == "C" )
		show_page ( "not_access.tpl", "權限錯誤" );
	else
		show_page ( "not_access.tpl", "Access Deny" );
	
	check_news ();
	
	function add_news () {
		global $start_y, $start_m, $start_d, $end_y, $end_m, $end_d, $T_y, $T_m, $T_d, $T_w, $course_id, $coopcaseid, $coopgroup;
		$start= $start_y."-".$start_m."-".$start_d;
		$end = $end_y."-".$end_m."-".$end_d;
		$cycle = $T_y."-".$T_m."-".$T_d;
		$week = $T_w;
			
		global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $course_id, $important, $handle, $subject, $news;
		$Q1 = "insert into news_".$coopcaseid." ( group_num, begin_day, end_day, cycle, week, important, handle, subject, content) values ( '$coopgroup', '$start', '$end', '$cycle', '$week', '$important', '$handle', '$subject', '$news' )";
		
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "資料庫連結錯誤!!";
			return $error;
		}
		if ( !($result = mysql_db_query( $DBC.$course_id, $Q1 ) ) ) {
			$error = "資料寫入錯誤!!";
			return $error;
		}
		$aid = mysql_insert_id();

		add_log_coop ( 7, "", $aid, $course_id, "", "", $coopgroup, $coopcaseid );
		mail_news ( $aid );
		return -1;
	}
	
	function show_page_d ( $message="" ) {
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );

		global $check, $version, $year, $month, $views, $PHPSESSID, $skinnum, $coopcaseid, $coopgroup;
		$tpl->assign( SKINNUM, $skinnum );

		if ( !isset($views) )
			$views = 0;

		if ( $views == 4 || $views == 0 || $views == 3 || $views == 1) {
			if ( $version == "C" ) {
				$tpl->define ( array ( head => "news_headl.tpl" ) );
				if ( $check == 2 ) {
					$tpl->assign( OPTION1 , "<option value=\"3\" V3>有效公告</option>" );
					$tpl->assign( OPTION2 , "<option value=\"4\" V4>無效公告</option>" );
				}
				else {
					$tpl->assign( OPTION1 , "" );
					$tpl->assign( OPTION2 , "" );
				}
			}
			else {
				$tpl->define ( array ( head => "news_headl_E.tpl" ) );
				if ( $check == 2 ) {
					$tpl->assign( OPTION1 , "<option value=\"3\" V3>Effective</option>" );
					$tpl->assign( OPTION2 , "<option value=\"4\" V4>Ineffective</option>" );
				}
				else {
					$tpl->assign( OPTION1 , "" );
					$tpl->assign( OPTION2 , "" );
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
				if ( $check == 2 ) {
					$tpl->assign( OPTION1 , "<option value=\"3\" V3>有效公告</option>" );
					$tpl->assign( OPTION2 , "<option value=\"4\" V4>無效公告</option>" );
				}
				else {
					$tpl->assign( OPTION1 , "" );
					$tpl->assign( OPTION2 , "" );
				}
			}
			else {
				$tpl->define ( array ( head => "news_head_E.tpl" ) );
				if ( $check == 2 ) {
					$tpl->assign( OPTION1 , "<option value=\"3\" V3>Effective</option>" );
					$tpl->assign( OPTION2 , "<option value=\"4\" V4>Ineffective</option>" );
				}
				else {
					$tpl->assign( OPTION1 , "" );
					$tpl->assign( OPTION2 , "" );
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
			$Q1 = "select n.a_id, n.begin_day, n.subject, n.important, l.tag3, n.cycle, n.week FROM news_".$coopcaseid." n ,log_".$coopcaseid." l where n.group_num = '$coopgroup' and l.tag1 = n.a_id and l.event_id = '7' and n.begin_day <= '".date("Y-m-d")."' and n.end_day >= '".date("Y-m-d")."'";
		}
		else if ( $views == 2 ) {
			$Q1 = "select n.a_id, n.begin_day, n.subject, n.important, l.tag3, n.cycle, n.week FROM news_".$coopcaseid." n ,log_".$coopcaseid." l where n.group_num = '$coopgroup' and l.tag1 = n.a_id and l.event_id = '7' and n.begin_day >= '$year-$month-01' and n.begin_day <= '".date("Y-m-d")."' and n.end_day >= '".date("Y-m-d")."'";
		}
		else if ( $views == 3 ) {
			$Q1 = "select n.a_id, n.begin_day, n.subject, n.important, l.tag3, n.cycle, n.week FROM news_".$coopcaseid." n ,log_".$coopcaseid." l where n.group_num = '$coopgroup' and l.tag1 = n.a_id and l.event_id = '7' and n.begin_day <= '".date("Y-m-d")."' and n.end_day >= '".date("Y-m-d")."'";
		}
		else if ( $views == 4 ) {
			$Q1 = "select n.a_id, n.begin_day, n.subject, n.important, l.tag3, n.cycle, n.week FROM news_".$coopcaseid." n ,log_".$coopcaseid." l where n.group_num = '$coopgroup' and l.tag1 = n.a_id and l.event_id = '7' and ( n.end_day < '".date("Y-m-d")."' or n.begin_day > '".date("Y-m-d")."' or  cycle != '0000-00-00' or week != '0' )";
		}
		else {
			$Q1 = "select n.a_id, n.begin_day, n.subject, n.important, l.tag3, n.cycle, n.week FROM news_".$coopcaseid." n ,log_".$coopcaseid." l where n.group_num = '$coopgroup' and l.tag1 = n.a_id and l.event_id = '7' and n.begin_day <= '".date("Y-m-d")."' and n.begin_day like '".date("Y-m")."-__' and n.end_day >= '".date("Y-m-d")."'";
		}

		if ( $check == 2 ) {
			if ( $version == "C" ) {
				$tpl->assign( DELETE , "<TD ><div align=\"center\"><FONT color=#ffffff size=2>刪除</FONT></div></TD>" );
				$tpl->assign( POST_LINE, "<br><font size=\"2\"><a href=\"#news\">‧發佈新消息‧</a></font><br><br>" );
				$tpl->assign( ENDLINE , "<br><font size=2><a href=\"#news\">‧發佈新消息‧</a></font><br>" );
			}
			else {
				$tpl->assign( DELETE , "<TD ><div align=\"center\"><FONT color=#ffffff size=2>Del</FONT></div></TD>" );
				$tpl->assign( POST_LINE, "<br><font size=\"2\"><a href=\"#news\">‧Post Announcement‧</a></font><br><br>" );
				$tpl->assign( ENDLINE , "<br><font size=2><a href=\"#news\">‧Post Announcement‧</a></font><br>" );
			}
		}
		else {
			$tpl->assign( DELETE , "" );
			$tpl->assign( POST_LINE, "" );
			$tpl->assign( ENDLINE , "</center></body></html>" );
		}

		global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $course_id, $order;
		
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "資料庫連結錯誤!!";
		}else if ( !($result = mysql_db_query( $DBC.$course_id, $Q1 ) ) ) {
			$error = "資料庫讀取錯誤!!";
		}
		else {
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
					$newdata[$i][5] = $row['cycle'];
					$newdata[$i][6] = $row['week'];
					$newdata[$i][7] = $row['tag3'];
					$i ++;
				}
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
					}
					$tpl->assign( BCOLOR , $color );	
					if ( $check == 2  )
						if ( $version == "C" )
								$tpl->assign( DATE , "<input type=hidden name=views value=$views><input type=hidden name=a_id value=".$newdata[$i][0]."><input type=submit name=submit value=刪除 onclick=\"return confirm('確定要刪除此公告嗎?')\"></font></div></td><td align=left><div align=\"center\"><FONT size=2>".$newdata[$i][1] );
							else
								$tpl->assign( DATE , "<input type=hidden name=views value=$views><input type=hidden name=a_id value=".$newdata[$i][0]."><input type=submit name=submit value=Del onclick=\"return confirm('Sure to Delete?')\"></font></div></td><td align=left><div align=\"center\"><FONT size=2>".$newdata[$i][1] );	
					else
						$tpl->assign( DATE , $newdata[$i][1] );
					$tpl->assign( VIEWS , $views );
					if ( $version == "C" )
						if ( $newdata[$i][3] == 2 )
							$tpl->assign( IMP , "<font color=#FF0000>最高等級</font>" );
						else if ( $newdata[$i][3] == 1 )
							$tpl->assign( IMP , "<font color=#336633>一般等級</font>" );
						else
							$tpl->assign( IMP , "最低等級" );
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
	
					$Q2 = "select SUM( tag3 ) As sum from log_".$coopcaseid." where event_id = '8'";
					if ( !($result2 = mysql_db_query( $DBC.$course_id, $Q2 ) ) ) {
						$error = "資料庫讀取錯誤!!";
					}
					else
						$row2 = mysql_fetch_array( $result2 );
	
					$Q3 = "select SUM( tag3 ) As sum from log_".$coopcaseid." where event_id = '8'";
					if ( !($result3 = mysql_db_query( $DBC, $Q3 ) ) ) {
						$error = "資料庫讀取錯誤!!";
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
					$error = "目前沒有公告";
				else
					$error = "There is no News";
			}

			$tpl->assign( MES, $error );
			$tpl->parse( HEAD, "head" );
			$tpl->FastPrint("HEAD");

			if ( mysql_num_rows( $result ) != 0 && $count != 0 ) {
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
					$tpl->assign( TYD , "每年" );
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
			$news = stripslashes( $news );
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
		global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $course_id, $coopcaseid, $coopgroup;
		$Q1 = "select a_id from news_".$coopcaseid." where end_day <= '".date("Y-m-d")."' and handle = '0'";
		
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "資料庫連結錯誤!!";
		}else if ( !($result = mysql_db_query( $DBC.$course_id, $Q1 ) ) ) {
			$error = "資料庫刪除錯誤!!";
		}
		else {
			while ( $row = mysql_fetch_array ( $result ) ) {
				$Q2 = "delete FROM news_".$coopcaseid." where a_id = '". $row['a_id'] ."'";
				$Q3 = "delete from log_".$coopcaseid." where event_id = '7' and tag1 = '". $row['a_id'];
				if ( !($result2 = mysql_db_query( $DBC.$course_id, $Q2 ) ) ) {
					$error = "資料庫刪除錯誤!!";
				}else if ( !($result3 = mysql_db_query( $DBC.$course_id, $Q3 ) ) ) {
					$error = "資料庫刪除錯誤!!";
				}
			}
		}
	}

	function del_news () {
		global $DB_SERVER, $DB_LOGIN, $DB, $DBC,$DB_PASSWORD, $course_id, $a_id, $version, $coopcaseid, $coopgroup;
		$Q1 = "select subject from news_".$coopcaseid." where a_id = '$a_id'";
		$Q2 = "delete FROM news_".$coopcaseid." where a_id = '$a_id'";
		$Q3 = "delete FROM log_".$coopcaseid." where tag1 = '$a_id' and event_id = '7'";
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "資料庫連結錯誤!!";
		}else if ( !($result1 = mysql_db_query( $DBC.$course_id, $Q1 ) ) ) {
			$error = "資料庫讀取錯誤1!!";
		}else if( mysql_num_rows( $result1 ) != 0 ) {
			if ( !($result = mysql_db_query( $DBC.$course_id, $Q2 ) ) ) {
				$error = "資料庫刪除錯誤2!!";
			}
			else if ( !($result = mysql_db_query( $DBC.$course_id, $Q3 ) ) ) {
				$error = "資料庫刪除錯誤3!!";
			}else if ( $row = mysql_fetch_array( $result1 ) ) {
				if ( $version == "C" )
					$error = "公告 ". $row['subject'] ." 刪除完成";
				else
					$error = "News ". $row['subject'] ." Deleted";
			}else {
				$error = "資料錯誤!!";
			}
		}
		return $error;
	}
	
	function mail_news ( $aid ) {
		global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $course_id, $version, $coopcaseid, $coopgroup, $SERVER_NAME;
		$Q1 = "select * from coop_".$coopcaseid."_group where group_num = '$coopgroup'";
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "資料庫連結錯誤!!";
		}else if ( !($result1 = mysql_db_query( $DBC.$course_id, $Q1 ) ) ) {
			$error = "資料庫讀取錯誤1!!";
		}else if( mysql_num_rows( $result1 ) != 0 ) {
			$course_data = "select * from course where a_id = '$course_id'"; 
			if ( !($result4 = mysql_db_query( $DB, $course_data ) ) ) {
				$error = "資料庫讀取錯誤4!!";
			}
			else if ( !($row4 = mysql_fetch_array( $result4 )) ) {
				$error = "資料庫讀取錯誤5!!";
			}
			$coursename = $row4 ['name'];
			
			$NEWS = "select * from news_".$coopcaseid." where a_id = '$aid'";
			if ( !($result3 = mysql_db_query( $DBC.$course_id, $NEWS ) ) ) {
				$error = "資料庫讀取錯誤2!!";
			}else if( mysql_num_rows( $result3 ) != 0 ) {
				$row3 = mysql_fetch_array ( $result3 );
				$content = $row3['content'];
				$content = str_replace ( "\n", "<BR>", $content );
				$message = "<table width=\"80%\" height=80% border=\"0\"  bordercolor=\"#4d6eb2\">".
						"<tr bordercolor=\"#000000\" > ".
						"<td width=\"70\" height=\"20\" bgcolor=\"#000066\"> ".
						"<div align=\"center\"><font color=\"#FFFFFF\" size=\"2\">標題</font></div>".
						"</td>".
						"<td height=\"20\" bgcolor=\"#E8E8E8\" width=\"411\"> ".
						"<div align=\"center\"><font size=\"2\">".$row3['subject']."</font></div>".
						"</td>".
						"</tr>".
						"<tr bordercolor=\"#000000\" > ".
						"<td width=\"70\" height=\"20\" bgcolor=\"#000066\"> ".
						"<div align=\"center\"><font color=\"#FFFFFF\" size=\"2\">發佈日期</font></div>".
						"</td>".
						"<td height=\"20\" bgcolor=\"#E8E8E8\" width=\"411\"> ".
						"<div align=\"center\"><font size=\"2\">2003-01-11</font></div>".
						"</td>".
						"</tr>".
						"<tr bordercolor=\"#000000\" > ".
						"<td width=\"70\" bgcolor=\"#000066\"> ".
						"<div align=\"center\"><font color=\"#FFFFFF\" size=\"2\">內容</font></div>".
						"</td>".
						"<td bgcolor=\"#E8E8E8\" width=\"411\"> ".
						"<div align=\"left\"><font size=\"2\">$content</font></div>".
						"</td>".
						"</tr>".
						"</table>";
							
				while ( $row1 = mysql_fetch_array ( $result1 ) ) {
					$Q2 = "select email from user where id ='".$row1['student_id']."'";
					if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) ) {
						$error = "資料庫讀取錯誤3!!";
					}else if( mysql_num_rows( $result2 ) != 0 ) {
						$row2 = mysql_fetch_array ( $result2 );
						if ( $row2['email'] != "" && $row2['email'] != NULL ) {
							$mail = new mime_mail();
							$mail->from = "study@".$SERVER_NAME;
							$mail->headers = "Errors-To:kof2k@seed.net.tw\n";
							$mail->headers .= "Reply-To:study@".$SERVER_NAME;
							$mail->to = $row2['email'];
							$mail->subject = $coursename." 合作學習公告 ".$row3['subject'];
					
							$mail->body = "<html><body>".$message.
									"<hr><a href='http://$SERVER_NAME/'> 教學系統 </a><br>\r\n".
									"<a href='http://$SERVER_NAME/'> e-Learning system </a><br>\r\n</body></html>";
							$mail->send();
						}
					}
				}
			}
		}
	}
?>