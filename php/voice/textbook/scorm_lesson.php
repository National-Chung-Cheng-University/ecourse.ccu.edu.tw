<?
	require 'fadmin.php';

	if ( !(isset($PHPSESSID) && session_check_teach($PHPSESSID)) ) {
		show_page( "not_access.tpl" ,"權限錯誤");
	}
	if($guest == "1") {
		$Q1 = "SELECT validated FROM course where a_id = '$course_id'";
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "資料庫連結錯誤!!";
		}
		else if ( !($result = mysql_db_query( $DB, $Q1  )) ) {
			$error = "資料庫讀取錯誤1!!";
		}
		else if ( mysql_num_rows( $result ) == 0 ) {
			$error = "資料庫錯誤!!";
			show_page ( "not_access.tpl", $error );
		}
		else {
			$row = mysql_fetch_array($result);
		}

		if( ($row["validated"]%2 == 1) ) {
			if ( $version == "C" )
				show_page( "not_access.tpl" ,"教材不開放參觀");
			else
				show_page( "not_access.tpl" ,"Access Denied.");
			exit();
		}
	}

	$gotLogout = 0;
	$buildSummary = 0;
	if ( $list == 1 && ($lessonid == null || $lessonid == "" || $lessonid == "null") ) {
		show_lesson_list();
		exit;
	}

	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		echo ( "資料庫連結錯誤!!" );
		exit;
	}
	$Q1 = "select tc.credit from user u, take_course tc where u.id='$user_id' and u.a_id = tc.student_id and tc.course_id = '$course_id'";
	if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
		echo ( "資料庫讀取錯誤2!!" );
		exit;
	}
	$row = mysql_fetch_array( $result );
	$credit = $row['credit'];
	//var_dump ( $aid );
	if ( $aid != "" && $aid != "null" && $aid != null ) {
		if ( $lessonid == null || $lessonid == "null" || $lessonid == "" )
			$Q2 = "select sr.a_id, sr.sco_id, sr.sequence, sr.location, sr.prerequisites from sco_register sr where sr.a_id = '$aid'";
		else
			$Q2 = "select sr.a_id, sr.sco_id, sr.sequence, sr.location, sr.prerequisites from sco_register sr, lesson l where sr.lesson_id = l.lesson_id and l.a_id = '$lessonid' and sr.a_id = '$aid'";
		$aid = "";
		if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
			echo ( "資料庫讀取錯誤3!!" );
			exit;
		}
		else if ( $row2 = mysql_fetch_array( $result2 ) ) {
//			if ( $teacher == 1 || $credit == 0 ) {
			if ( $teacher == 1 || $credit == 0 || $row2['prerequisites'] == "" || $row2['prerequisites'] == "null" || $row2['prerequisites'] == null || handle_pre($row['prerequisites']) ) {
				if ( stristr($row2['location'],"http://") == null ) {
					$next_page= "/$course_id/scorm/" . $row2['location'];
				}
				else {
					$next_page = $row2['location'];
				}
				if ( $lessonid == null || $lessonid == "null" || $lessonid == "" )
					header ( "Location: http://$SERVER_NAME/servlets/Lsession?lesson_id=&seq=".$row2['sequence']."&sco_id=".$row2['sco_id']."&a_id=".$row2['a_id']."&next_page=$next_page" );
				else
					header ( "Location: http://$SERVER_NAME/servlets/Lsession?lesson_id=$lessonid&seq=".$row2['sequence']."&sco_id=".$row2['sco_id']."&a_id=".$row2['a_id']."&next_page=$next_page" );
				exit;
			}
		}
	}
	if ( $lessonid == null || $lessonid == "null" || $lessonid == "" )
		$Q1 = "select sr.a_id, sr.sco_id, sr.sequence, sr.location, sr.prerequisites from sco_register sr order by sr.sequence";
	else
		$Q1 = "select sr.a_id, sr.sco_id, sr.sequence, sr.location, sr.prerequisites, l.lesson_id from sco_register sr, lesson l where l.lesson_id = sr.lesson_id and l.a_id = '$lessonid' order by sr.sequence";
	if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
		echo ( "資料庫讀取錯誤4!!" );
		exit;
	}
	else if ( mysql_num_rows( mysql_db_query( $DB.$course_id, $Q1 ) ) != 0 ) {
		$numAus = mysql_num_rows( mysql_db_query( $DB.$course_id, $Q1 ) );
		$row2;
		$row;
		while ( $row = mysql_fetch_array( $result ) ) {
			$Q2 = "select * from sco_" . $row['a_id'] . "_core where student_id = '".$user_id."'";
			if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
				echo ( "資料庫讀取錯誤5!!" );
				exit;
			}
			$row2 = mysql_fetch_array( $result2 );
			
			if ( stristr($row2['exit'], "logout") ) {
				$gotLogout = 1;
				$Q3 = "update sco_" . $row['a_id'] . "_core set exit = '' where student_id = '". $user_id."'";
				mysql_db_query( $DB.$course_id, $Q3 );
				break;
			}
			//var_dump ( $row2['lesson_mode'] );
			//var_dump ( $row2['lesson_status'] );
			if ( stristr($row2['lesson_mode'], "review") || ( !stristr($row2['lesson_status'], "completed") && !stristr($row2['lesson_status'] , "passed") && !stristr($row2['lesson_status'] , "failed") ) ) {
//			if ( $row2['lesson_mode'] == "review" || ( handle_pre($row['prerequisites']) && !stristr($row2['lesson_status'], "completed") && !stristr($row2['lesson_status'] , "passed") && !stristr($row2['lesson_status'] , "failed") ) ) {
				break;
			}

			$numAus --;
		}
		if ( $list == 1 ) {
			show_lesson_list();
			exit;
		}
		if ( $numAus == 0 ) {
			$buildSummary = 1;
		}

		if($gotLogout == 1) { // Student has exited lesson
			show_lesson_list();
			exit;
		}
		else if($buildSummary == 1) { // Student has completed lesson
			show_summary_page();
			exit;
		}
		else // Serve up the next SCO
		{
			if ( stristr($row['location'],"http://") == NULL ) {
				$next_page= "/$course_id/scorm/" . $row['location'];
			}
			else {
				$next_page = $row['location'];
			}
			
			header ( "Location: http://$SERVER_NAME/servlets/Lsession?lesson_id=".$row['lesson_id']."&seq=".$row['sequence']."&sco_id=".$row['sco_id']."&a_id=".$row['a_id']."&next_page=$next_page" );
			exit;
		}
	}
	else {
		if ( $version == "C" )
			show_page( "not_access.tpl" ,"目前沒有任何教材");
		else
			show_page( "not_access.tpl" ,"There is no TextBook");
	}
	
	function show_lesson_list() {
		global $version;
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		$tpl->define ( array ( body => "lesson_list.tpl") );
		$tpl->define_dynamic ( "lessonlist" , "body" );
		if( $version == "C" ) {
			$tpl->assign( IMG, "img");
			$tpl->assign ( TITLE, "教材Lesson清單" );
			$tpl->assign( LESSONTL , "<center><b><font color =#FFFFFF>Lesson 標題及連結</font></b></center>" );
		}
		else {
			$tpl->assign( IMG, "img_E");
			$tpl->assign ( TITLE, "Lesson List" );
			$tpl->assign( LESSONTL , "<center><b><font color =#FFFFFF>Lesson Title & Link</font></b></center>" );
		}
		$color = "#4d6eb2";
		$tpl->assign( COLOR , $color );
		$tpl->parse ( LESSONLIST, ".lessonlist" );
		$color = "#BFCEBD";
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id;
		$Q1 = "select * from lesson where level = '1' order by a_id";
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$message = "$message - 資料庫連結錯誤!!";
		}
		else if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			$message = "$message - 資料庫讀取錯誤6!!";
		}
		else {
			while ( $row = mysql_fetch_array( $result ) ) {
				if ( $color == "#BFCEBD" )
					$color = "#D0DFE3";
				else
					$color = "#BFCEBD";
				$tpl->assign( COLOR , $color );
				$tpl->assign( LESSONTL , "<a href=\"./scorm_lesson.php?lessonid=".$row['a_id']."\" onClick=\"top.setscovalue(".$row['a_id'].", null, null);\">".$row['title']."</a>" );
				$tpl->parse ( LESSONLIST, ".lessonlist" );
			}
		}
		$tpl->assign( MSG, $message);
		$tpl->parse( BODY, "body");
		$tpl->FastPrint("BODY");
	}

	function show_summary_page() {
		global $version;
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		if ( $version == "C" )
			$tpl->define ( array ( body => "summary_page.tpl") );
		else
			$tpl->define ( array ( body => "summary_page_E.tpl") );

		$tpl->define_dynamic ( "scolist" , "body" );
		if( $version == "C" ) {
			$tpl->assign( IMG, "img");
			$tpl->assign ( TITLE, "教材研讀狀況" );
			$tpl->assign( SCOTL , "<center><b><font color =#FFFFFF>內容標題</font></b></center>" );
			$tpl->assign( SCOTI , "<center><b><font color =#FFFFFF>時間</font></b></center>" );
		}
		else {
			$tpl->assign( IMG, "img_E");
			$tpl->assign ( TITLE, "Learning status" );
			$tpl->assign( SCOTL , "<center><b><font color =#FFFFFF>Content Title</font></b></center>" );
			$tpl->assign( SCOTI , "<center><b><font color =#FFFFFF>Time</font></b></center>" );
		}
		$color = "#4d6eb2";
		$tpl->assign( COLOR , $color );
		$tpl->parse ( SCOLIST, ".scolist" );
		$color = "#BFCEBD";

		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD,$course_id, $lessonid, $user_id;
		if ( $lessonid == null || $lessonid == "null" || $lessonid == "" )
			$Q1 = "select sr.a_id, sr.sco_id, sr.sco_name from sco_register sr order by sr.sequence";
		else
			$Q1 = "select sr.a_id, sr.sco_id, sr.sco_name from sco_register sr, lesson l where sr.lesson_id = l.lesson_id and l.a_id = '$lessonid' order by sr.sequence";
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$message = "$message - 資料庫連結錯誤!!";
		}
		else if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			$message = "$message - 資料庫讀取錯誤7!!";
		}
		else if ( mysql_num_rows( mysql_db_query( $DB.$course_id, $Q1 ) ) != 0 ) {
			while ( $row = mysql_fetch_array( $result ) ) {
				$Q2 = "select * from sco_" . $row['a_id'] . "_core where student_id = '".$user_id."'";
				if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
					$message = "$message - 資料庫讀取錯誤8!!";
					break;
				}
				$row2 = mysql_fetch_array( $result2 );
				if ( $color == "#BFCEBD" )
					$color = "#D0DFE3";
				else
					$color = "#BFCEBD";
				$tpl->assign( COLOR , $color );
				$sco_name = $row['sco_name'];
				$sco_name = htmlspecialchars( $sco_name );
				$sco_name = addslashes( $sco_name );
				$tpl->assign( SCOTL, $row['sco_name'] );
				if ( $version == "C" )
					$tpl->assign( SCOTI, $row2['total_time']."秒" );
				else
					$tpl->assign( SCOTI, $row2['total_time']."Sec" );
				$tpl->parse ( SCOLIST, ".scolist" );
			}
		}
		$tpl->assign( MSG, $message);
		$tpl->parse( BODY, "body");
		$tpl->FastPrint("BODY");
	}

	function handle_pre ( $string ) {
		global $course_id , $user_id, $version;
		$j = 0;
		$symbol=0;
		$buf;
		$sym_table;
		if ( $string == null || $string == "" || $string == "null" )
			return 1;
		for ( $i = 0 ; $i < strlen($string) ; $i ++ ) {
			switch( $string{$i} ) {
				case '&':
					if ( $symbol == 1 ) {
						$symbol = 0;
						$j ++;
					}
					$buf[$j] = "&";
					$j ++;
					break;
				case '|':
					if ( $symbol == 1 ) {
						$symbol = 0;
						$j ++;
					}
					$buf[$j] = "|";
					$j ++;
					break;
				case '~':
					if ( $symbol == 1 ) {
						$symbol = 0;
						$j ++;
					}
					$buf[$j] = "~";
					$j ++;
					break;
				case '=':
					if ( $symbol == 1 ) {
						$symbol = 0;
						$j ++;
					}
					$buf[$j] = "=";
					$j ++;
					break;
				case '<':
					if ( $symbol == 1 ) {
						$symbol = 0;
						$j ++;
					}
					$buf[$j] = "<>";
					$j ++;
					$i ++;
					break;
				case '{':
					if ( $symbol == 1 ) {
						$symbol = 0;
						$j ++;
					}
					$buf[$j] = "{";
					$j ++;
					break;
				case '}':
					if ( $symbol == 1 ) {
						$symbol = 0;
						$j ++;
					}
					$buf[$j] = "}";
					$j ++;
					break;
				case ',':
					if ( $symbol == 1 ) {
						$symbol = 0;
						$j ++;
					}
					break;
				case '*':
					if ( $symbol == 1 ) {
						$symbol = 0;
						$j ++;
					}
					$buf[$j] = "*";
					$j ++;
					break;
				case ' ':
					if ( $symbol == 1 ) {
						$symbol = 0;
						$j ++;
					}
					break;
				default:
					$buf[$j] .= $string{$i};
					$symbol = 1;
					break;
			}
		}
		//to prefix to dead
		//tree complete
		//var_dump ( $buf );
		//symbol table
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "資料庫連結錯誤!!";
			return $error;
		}
		for ( $i = 0 ; $i < count($buf) ; $i ++ ) {
			if ( $buf[$i] != "&" && $buf[$i] != "|" && $buf[$i] != "~" && $buf[$i] != "=" && $buf[$i] != "<>" && $buf[$i] != "{" && $buf[$i] != "}" && $buf[$i] != "&" && $buf[$i]!= "," && $buf[$i] != "*" && !stristr($buf[$i], "\"completed\"") && !stristr($buf[$i], "\"passed\"") && !stristr($buf[$i], "\"incomplete\"") && !stristr($buf[$i], "\"failed\"") && !stristr($buf[$i], "\"not attempted\"") ) {
				$Q1 = "select * from sco_register where sco_id = '$buf[$i]'";
				if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
					echo ("資料庫讀取錯誤11!!");
					exit;
				}else if ( mysql_num_rows( $result ) != 0 ) {
					$row = mysql_fetch_array ( $result );
					$Q12 = "select * from sco_".$row['a_id']."_core where student_id = '$user_id'";
					if ( !($result12 = mysql_db_query( $DB.$course_id, $Q12 ) ) ) {
						echo ("資料庫讀取錯誤12!!");
						exit;
					}else if ( mysql_num_rows( $result12 ) != 0 ) {
						$row12 = mysql_fetch_array ( $result12 );
						$sym_table["$buf[$i]"][0] = $row12['lesson_status'];
						if ( stristr($row12['lesson_status'], "completed") || stristr($row12['lesson_status'], "passed") )
							$sym_table["$buf[$i]"][1] = "1";
						else
							$sym_table["$buf[$i]"][1] = "0";
					}
					else {
						echo ("資料庫讀取錯誤13!!");
						exit;
					}
				}else {
					$Q2 = "select * from lesson where lesson_id = '$buf[$i]'";
					if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
						echo ("資料庫讀取錯誤B1!!");
						exit;
					}else if ( mysql_num_rows( $result2 ) != 0 ) {
						$sym_table["$buf[$i]"][1] = handle_block ( $buf[$i] );
						$sym_table["$buf[$i]"][0] = $sym_table["$buf[$i]"][1];
					}
				}
			}
		}
		//symbol table completed
		//var_dump ( $sym_table );
		//handle start
		return handle_start ( 0, $buf, $sym_table );
		//handle end		
	}
	
	function handle_block ( $block_id ) {
		global $course_id , $user_id, $version;
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			echo ( "資料庫連結錯誤!!" );
			exit;
		}
		$Q1 = "select * from lesson where parent_id = '$block_id'";
		if ( !($result1 = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			echo ("資料庫讀取錯誤B1!!");
			exit;
		}else if ( mysql_num_rows( $result1 ) != 0 ) {
			while ( $row1 = mysql_fetch_array ( $result1 ) ) {
				if ( handle_block( $row1['lesson_id'] ) == 0 ){
					return 0;
				}
			}
		}
		$Q2 = "select * from sco_register where parent_id = '$block_id'";
		if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
			echo ("資料庫讀取錯誤B2!!");
			exit;
		}else if ( mysql_num_rows( $result2 ) != 0 ) {
			while ( $row2 = mysql_fetch_array ( $result2 ) ) {
				$Q3 = "select * from sco_".$row2['a_id']."_core where student_id = '$user_id'";
				if ( !($result3 = mysql_db_query( $DB.$course_id, $Q3 ) ) ) {
					echo ("資料庫讀取錯誤B21!!");
					exit;
				}else if ( mysql_num_rows( $result3 ) != 0 ) {
					$row3 = mysql_fetch_array ( $result3 );
					if ( $row3['lesson_status'] != "completed" && $row3['lesson_status'] != "passed" )
						return 0;
				}
				else {
					echo ("資料庫讀取錯誤13!!");
					exit;
				}
			}
		}
		else {
			echo ("條件錯誤!!");
			exit;
		}
		return 1;
	}

	function handle_start ( $point, $data, $sym_t, $pre = "" ) {
		$neg = 0;
		$tmp = 0;
		$num = 0;
		for ( $i = $point ; $i < count($data) ; $i ++ ) {
			//var_dump ( $data[$i] );
			if ( $data[$i] == "&" ) {
				//echo ( "$pre\n" );
				return ( $pre && handle_start( $i+1, $data, $sym_t ) );
			}
			else if ( $data[$i] == "|" ) {
				//echo ( "$pre\n" );
				return ( $pre || handle_start( $i+1, $data, $sym_t ) );
			}
			else if ( $data[$i] == "~" ) {
				if ( $neg == 1 )
					$neg = 0;
				else
					$neg = 1;
				if ( $data[$i+1] != "~" ) {
					$i ++;
					$pre = ( $neg == 1 ? $sym_t[$data[$i]][1] : ($sym_t[$data[$i]][1] == 1 ? 0 : 1) );
					$neg = 1;
				}
				//echo ( "$pre\n" );
			}
			else if ( $data[$i] == "=" ) {
				if ( "\"".$sym_t[$data[$i-1]][0]."\"" == $data[$i+1] )
					$pre = 1;
				else
					$pre = 0;
				//echo ( "1\"".$sym_t[$data[$i-1]][0]."\" ".$data[$i+1]." $pre\n" );
				$i ++;
			}
			else if ( $data[$i] == "<>" ) {
				if ( "\"".$sym_t[$data[$i-1]][0]."\"" != $data[$i+1] )
					$pre = 1;
				else
					$pre = 0;
				//echo ( "2\"".$sym_t[$data[$i-1]][0]."\" ".$data[$i+1]." $pre\n" );
				$i ++;
			}
			else if ( $data[$i] == "{" ) {
				for ( $j = $i+1; $data[$j] != "}" ; $j ++ ) {
					$tmp += $sym_t["$data[$j]"][1];
				}
				if ( $tmp >= $num )
					$pre = 1;
				else
					$pre = 0;
				$i = $j;
				//echo ( "$pre\n" );
			}
			else if ( $data[$i] == "*" ) {
				$num = $data[$i-1];
				//echo ( "$num\n" );
			}
			else
				return $sym_t["$data[$i]"][1];
		}
		return $pre;
	}
?>