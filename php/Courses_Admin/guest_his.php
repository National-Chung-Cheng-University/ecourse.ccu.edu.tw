<?php
	require 'fadmin.php';
	if (!isset($ver) && isset($PHPSESSID) && session_check_stu($PHPSESSID)) {
		session_unregister("teacher");
		session_unregister("admin");
		session_register("guest");
		$guest = 1;
	}
	else {
		session_start();
		session_unregister("teacher");
		session_unregister("admin");
		session_unregister("course_id");
		//計算使用時間用
		session_unregister("time");
		session_register("time");
		session_register("user_id");
		session_register("version");
		session_register("guest");
		$version = $ver;
		$user_id = $id;
		$guest = 1;
		$time = date("U");
		add_log ( 1, $user_id );
		unset($ver);
		header( "Location: http://$SERVER_NAME/php/Courses_Admin/guest_his.php?groupid=$groupid&PHPSESSID=".session_id());
	}
	if ( $frame != 1 ) {
		echo "<frameset rows='*,0' cols='*,0' frameborder = 'no'>\n";
		echo "<frame src='guest_his.php?groupid=$groupid&PHPSESSID=$PHPSESSID&frame=1' name='main' frameborder = 'no'>\n";
		echo "<frame src='../noop.php?PHPSESSID=$PHPSESSID' name='noop' frameborder = 'no'>\n";
		echo "</frameset>";
	}
	else {
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		$tpl->define ( array ( body => "guest_his.tpl" ) );
		$tpl->define_dynamic ( "course_list" , "body" );
		$tpl->define_dynamic ( "group_list" , "body" );
		
		$color = "#000066";
		$tpl->assign( SKINNUM , $skinnum );
		$tpl->assign( COLOR , $color );
		$tpl->assign( TYPE , "colspan=2" );
		if ( $version == "C" ) {
			$tpl->assign( GNAME , "<font color =#FFFFFF>開課單位</font>" );
			$tpl->assign( CNO , "<font color =#FFFFFF>課程編號</font>" );
			$tpl->assign( CNAME , "<font color =#FFFFFF>課程名稱及介紹</font>" );
			$tpl->assign( CTEACH , "<font color =#FFFFFF>授課教師</font>" );
			$tpl->assign( CTA , "<font color =#FFFFFF>隨課助教</font>" );
			$tpl->assign( CSTATUS , "<font color =#FFFFFF>開課狀態</font>" );
		}
		else {
			$tpl->assign( GNAME , "<font color =#FFFFFF>Department</font>" );
			$tpl->assign( CNO , "<font color =#FFFFFF>No.</font>" );
			$tpl->assign( CNAME , "<font color =#FFFFFF>Course Name & Introduction</font>" );
			$tpl->assign( CTEACH , "<font color =#FFFFFF>Teachers</font>" );
			$tpl->assign( CTA , "<font color =#FFFFFF>TA</font>" );
			$tpl->assign( CSTATUS , "<font color =#FFFFFF>Status</font>" );
		}
		$tpl->parse ( COURSE_LIST, ".course_list" );
		$tpl->assign( TYPE , "" );
		
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $groupid;
		$Q0 = "select a_id , name from course_group where is_leaf = '1' order by a_id";
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$message = "$message - 資料庫連結錯誤!!";
		}
		
		if ( !($result0 = mysql_db_query( $DB, $Q0 ) ) ) {
			$message = "$message - 資料庫讀取錯誤!!";
		}
		else if ( mysql_num_rows( $result0 ) != 0 ) {
			while ( $row0 = mysql_fetch_array( $result0 ) ) {
				if ( $groupid == $row0['a_id'] ) {
					$tpl->assign( GID , $row0['a_id']." selected" );
				} else {
					$tpl->assign( GID , $row0['a_id'] );
				}
				$tpl->assign( GNAME , $row0['name'] );
				$tpl->parse ( GROUP_LIST, ".group_list" );
			}
		}
		if ( $groupid == NULL || $groupid == "" ) {
			$group_id = mysql_fetch_array(mysql_db_query( $DB, $Q0 ));
			$groupid = $group_id['a_id'];
		}
		
		$Q1 = "select tc.course_id FROM course c, course_group cg, take_course tc , user u where u.id = '$user_id' and tc.student_id = u.a_id and c.a_id = tc.course_id and cg.a_id = c.group_id and tc.validated = '1' and c.group_id = '$groupid' order by cg.a_id";
		if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
			$message = "$message - 資料庫讀取錯誤!!";
		}
		else if ( mysql_num_rows( $result ) != 0 ) {
			$nottake = "";
			while ( $row1 = mysql_fetch_array( $result ) ) {
				$nottake = $nottake." and c.a_id != ".$row1['course_id'];
			}
		}
		$Q2 = "select c.group_id, cg.name AS gname, c.introduction, c.a_id, c.name AS cname, c.validated FROM course c, course_group cg where c.group_id = '$groupid' and cg.a_id = c.group_id  $nottake  order by cg.a_id";
		if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) ) {
			echo ("資料庫讀取錯誤2!!$Q2");
			exit;
		}
		if ( mysql_num_rows( $result2 ) != 0 ) {
			$count = 0;
			while ( $row = mysql_fetch_array( $result2 ) ) {
				//$tpl->assign( COLOR , $color );
				$data[$count]["gname"] = "‧</td><td width=75><font size=-1>".$row["gname"];
				//$tpl->assign( GNAME , "‧</td><td bgcolor=$color width=75><font size=-1>".$row["gname"] );
				if( ($row["validated"]%2 == 1) ) {
					//if ( $row['introduction'] != "" || is_file("../../".$row["course_id"]."/intro/index.html") ) {
						//$tpl->assign( CNAME ,"<a href=# onClick=\"window.open('./intro.php?PHPSESSID=".session_id()."&courseid=".$row["a_id"]."&query=1', '', 'resizable=1,scrollbars=1,width=640,height=480');\">".$row["cname"]."</a>");
						$data[$count]["cname"] = "<a href=# onClick=\"window.open('./intro.php?PHPSESSID=".session_id()."&courseid=".$row["a_id"]."&query=1', '', 'resizable=1,scrollbars=1,width=640,height=480');\">".$row["cname"]."</a>";
					//}
					/*else {
						$tpl->assign( CNAME , $row["cname"] );
					}*/
						
					if ( $version == "C" ) {
						$tpl->assign( CSTATUS , "不可旁聽" );
					}
					else {
						$tpl->assign( CSTATUS , "UnChoise" );
					}
				}
				else {
					$data[$count]["cname"] = "<a href=# onClick=\"window.open('./intro.php?PHPSESSID=".session_id()."&courseid=".$row["a_id"]."&query=1', '', 'resizable=1,scrollbars=1,width=640,height=480');\">".$row["cname"]."</a>";
					//$tpl->assign( CNAME ,"<a href=# onClick=\"window.open('./intro.php?PHPSESSID=".session_id()."&courseid=".$row["a_id"]."&query=1', '', 'resizable=1,scrollbars=1,width=640,height=480');\">".$row["cname"]."</a>");
					if ( $version == "C" )
						$tpl->assign( CSTATUS , "<a href=\"../login_s.php?courseid=". $row["a_id"] . "\" target=\"_top\">可旁聽</a>" );
					else
						$tpl->assign( CSTATUS , "<a href=\"../login_s.php?courseid=". $row["a_id"] . "\" target=\"_top\">Choise</a>" );
				}
				$name = "";
				$name2 = "";
				$Q5 = "select u.id, u.name, u.nickname, u.a_id, u.authorization FROM user u , teach_course tc where tc.course_id = '".$row["a_id"]."' and tc.teacher_id = u.a_id ";
				if ( !($result5 = mysql_db_query( $DB, $Q5 ) ) ) {
					$message = "$message - 資料庫讀取錯誤!!";
				}
				while ( $row5 = mysql_fetch_array( $result5 ) ) {
					if ( $row5['name'] != NULL ) {
						if ( $row5['php'] != NULL ) {
							if ( $row5['authorization'] == 1 )
								$name = $name." <a href=# onClick=\"window.open('".$row5['php']."', '', 'resizable=1,scrollbars=1,width=640,height=480');\">".$row5['name']."</a>";
							else
								$name2 = $name2." <a href=# onClick=\"window.open('".$row5['php']."', '', 'resizable=1,scrollbars=1,width=640,height=480');\">".$row5['name']."</a>";
						}
						else {
							if ( $row5['authorization'] == 1 )
								$name = $name." <a href=# onClick=\"window.open('../Learner_Profile/TTDATAQuery1.php?PHPSESSID=".session_id()."&user_aid=".$row5['a_id']."&query=1', '', 'resizable=1,scrollbars=1,width=640,height=480');\">".$row5['name']."</a>";
							else
								$name2 = $name2." <a href=# onClick=\"window.open('../Learner_Profile/TTDATAQuery1.php?PHPSESSID=".session_id()."&user_aid=".$row5['a_id']."&query=1', '', 'resizable=1,scrollbars=1,width=640,height=480');\">".$row5['name']."</a>";
						}
					}
					else if ( $row5['nickname'] != NULL ) {
						if ( $row5['php'] != NULL ) {
							if ( $row5['authorization'] == 1 )
								$name = $name." <a href=# onClick=\"window.open('".$row5['php']."', '', 'resizable=1,scrollbars=1,width=640,height=480');\">".$row5['nickname']."</a>";
							else
								$name2 = $name2." <a href=# onClick=\"window.open('".$row5['php']."', '', 'resizable=1,scrollbars=1,width=640,height=480');\">".$row5['nickname']."</a>";
						}
						else {
							if ( $row5['authorization'] == 1 )
								$name = $name." <a href=# onClick=\"window.open('../Learner_Profile/TTDATAQuery1.php?PHPSESSID=".session_id()."&user_aid=".$row5['a_id']."&query=1', '', 'resizable=1,scrollbars=1,width=640,height=480');\">".$row5['nickname']."</a>";
							else
								$name2 = $name2." <a href=# onClick=\"window.open('../Learner_Profile/TTDATAQuery1.php?PHPSESSID=".session_id()."&user_aid=".$row5['a_id']."&query=1', '', 'resizable=1,scrollbars=1,width=640,height=480');\">".$row5['nickname']."</a>";
						}
					}
					else {
						if ( $row5['php'] != NULL ) {
							if ( $row5['authorization'] == 1 )
								$name = $name." <a href=# onClick=\"window.open('".$row5['php']."', '', 'resizable=1,scrollbars=1,width=640,height=480');\">".$row5['id']."</a>";
							else
								$name2 = $name2." <a href=# onClick=\"window.open('".$row5['php']."', '', 'resizable=1,scrollbars=1,width=640,height=480');\">".$row5['id']."</a>";
						}
						else {
							if ( $row5['authorization'] == 1 )
								$name = $name." <a href=# onClick=\"window.open('../Learner_Profile/TTDATAQuery1.php?PHPSESSID=".session_id()."&user_aid=".$row5['a_id']."&query=1', '', 'resizable=1,scrollbars=1,width=640,height=480');\">".$row5['id']."</a>";
							else
								$name2 = $name2." <a href=# onClick=\"window.open('../Learner_Profile/TTDATAQuery1.php?PHPSESSID=".session_id()."&user_aid=".$row5['a_id']."&query=1', '', 'resizable=1,scrollbars=1,width=640,height=480');\">".$row5['id']."</a>";
						}
					}
				}
				$course_no = "";
				$Q6 = "select course_no FROM course where a_id = '".$row["a_id"]."'";
				if ( !($result6 = mysql_db_query( $DB, $Q6 ) ) ) {
					$message = "$message - 資料庫讀取錯誤!!";
				}
				while ( $row6 = mysql_fetch_array( $result6 ) ) {
					$course_no .= $row6['course_no']." ";
				}
				$data[$count]["course_no"] = $course_no;
				$data[$count]["name"] = $name;
				$data[$count]["name2"] = $name2;
				$count++;
			}
			$data = qsort_multiarray ( $data, "course_no", SORT_ASC );
			for($i=0; $i<sizeof($data); $i++){
				if ( $color == "#E6FFFC" )
					$color = "#F0FFEE";
				else
					$color = "#E6FFFC";
				$tpl->assign( COLOR , $color );
				$tpl->assign( GNAME , $data[$i]["gname"] );
				$tpl->assign( CNAME , $data[$i]["cname"]  );
				$tpl->assign( CNO , $data[$i]["course_no"] );
				$tpl->assign( CTEACH , $data[$i]["name"] );
				$tpl->assign( CTA , $data[$i]["name2"]  );
				$tpl->parse ( COURSE_LIST, ".course_list" );
				$tpl->assign( MES , "" );
			}
		}
		else {
			if ( $version == "C" )
				$tpl->assign( MES , "目前沒有任何課程" );
			else
				$tpl->assign( MES , "There is no Course" );
		}
		if ( $version == "C" ) {
			$tpl->assign( PATH , "img" );
		}
		else {
			$tpl->assign( PATH , "img_E" );
		}
		$Q3 = "select authorization from user where id='$user_id'";
		if ( !($result3 = mysql_db_query( $DB, $Q3 ) ) ) {
			echo ("資料庫讀取錯誤!!");
			exit;
		}
		$row3 = mysql_fetch_array ( $result3 );
		if ( $row3['authorization'] != "9" ) {
			$tpl->assign( BUTTON , "<a href=./guest.php target=\"_top\">查詢本學期開課資料</a>" );
		}
		else {
			$ip = getenv ( "REMOTE_ADDR" );
			if ( $ip == "" )
				$ip = $HTTP_X_FORWARDED_FOR;
			if ( $ip == "" )
				$ip = $REMOTE_ADDR;
			$D1 = "delete from online where user_id = '$user_id' and host='$ip'";
			mysql_db_query( $DB, $D1 );
			$tpl->assign( BUTTON , "" );
		}
		if ( $course_id == "-1" || $groupid != NULL )
			$tpl->assign( SYS , "//" );
		else
			$tpl->assign( SYS , "" );
		$tpl->assign( PHPSID , session_id() );
		$tpl->parse( BODY, "body" );
		$tpl->FastPrint("BODY");
	}
?>
