<?
require 'fadmin.php';
	if (!(isset($PHPSESSID) && session_check_stu($PHPSESSID)) ) {
		show_page( "not_access.tpl" ,"�v�����~");
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
				$message = "$message - ��ƮwŪ�����~!!";
			}else
				$row = mysql_fetch_array( $result );
	
			if ( $row['authorization'] <= "2" && $teacher == 1) {

                                //changed here by rja
                                //�ΨӥhŪ�Ѯv�Ҷ}���ҵ{���A�����ǹw���|ĳ�Ainclude �U���o��{����A�|�o��@�ӹw���|ĳ��T>�s $reservation_meeting
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
		// �s��sybase
		if( !($cnx = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) ){  
			Error_handler( "�b sybase_connect �����~�o��" , $cnx );  
		}
		$csd = @sybase_select_db("academic", $cnx);
		$cur = sybase_query("select year,term,id,cour_cd,grp from a31vcurriculum_tea", $cnx);
		if(!$cur) {  
			Error_handler( "�b sybase_exec �����~�o��( �S�����жǦ^ ) " , $cnx );  
		}
		$rowsy = sybase_fetch_array($cur);
		*/
		if ( $version == "C" )
			$tpl->define ( array ( body => "teach_course.tpl" ) );
		else
			$tpl->define ( array ( body => "teach_course_E.tpl" ) );
		$tpl->define_dynamic ( "course_list" , "body" );
		$tpl->define_dynamic ( "pre_next_cour" , "body" ); //�W�U�Ǵ�
		$tpl->define_dynamic ( "old_cour", "body" );
		
		$tpl->assign( SKINNUM , $skinnum );
		$color = "#000066";
		$tpl->assign( COLOR , $color );
		//$tpl->assign( TYPE , "colspan=2" );
		if ( $version == "C" ) {
			$tpl->assign( GNAME , "<font color =#FFFFFF>�}�ҳ��</font>" );
			$tpl->assign( YEAR, "<font color =#FFFFFF>�}�ҾǦ~��</font>");
			$tpl->assign( CNO , "<font color =#FFFFFF>�ҵ{�s��</font>" );
			$tpl->assign( CNAME , "<font color =#FFFFFF>�ҵ{�W��</font>" );
			$tpl->assign( GRADE, "<font color =#FFFFFF>�n�����Z</font>" );
			$tpl->assign( CRNAME , "<font color =#FFFFFF>���m�ҵ{�s��</font>" );
			$tpl->assign( CTEACH , "<font color =#FFFFFF>�½ұЮv</font>" );
			//changed 1 line by rja
                        $tpl->assign( MMCONLINE , "<font color =#FFFFFF>����w���ҵ{</font>" );


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
		
		/****�W�U�Ǵ�*****/
		$p_n_color = "#000066";
		$tpl->assign( P_N_COL , $p_n_color );
		if ( $version == "C" ) {
			$tpl->assign( P_N_G_NAME , "<font color =#FFFFFF>�}�ҳ��</font>" );
			$tpl->assign( P_N_YE, "<font color =#FFFFFF>�}�ҾǦ~��</font>");
			$tpl->assign( P_N_NO , "<font color =#FFFFFF>�ҵ{�s��</font>" );
			$tpl->assign( P_N_C_NAME , "<font color =#FFFFFF>�ҵ{�W��</font>" );
			$tpl->assign( P_N_GRA, "<font color =#FFFFFF>�n�����Z</font>" );
			$tpl->assign( P_N_TEACH , "<font color =#FFFFFF>�½ұЮv</font>" );
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
		
		/**********�ק���v�Ϫ��ﶵ**********/
		$old_color = "#000066";
		$tpl->assign( OLD_COL , $old_color );
		if ( $version == "C" ) {
			$tpl->assign( G_OLD_NAME , "<font color =#FFFFFF>�}�ҳ��</font>" );
			$tpl->assign( OLD_YE, "<font color =#FFFFFF>�}�ҾǦ~��</font>");
			$tpl->assign( C_OLD_NO , "<font color =#FFFFFF>�ҵ{�s��</font>" );
			$tpl->assign( C_OLD_NAME , "<font color =#FFFFFF>�ҵ{�W��</font>" );
			$tpl->assign( C_OLD_TEACH , "<font color =#FFFFFF>�½ұЮv</font>" );
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
			$message = "$message - ��Ʈw�s�����~!!";
		}
		$D1 = "delete from online where user_id = '$user_id'";
//		mysql_db_query( $DB, $D1 );

		/********************�P�_�W�U�Ǵ��ӵ�����**************************/
		$p_n_year = 0;	//�w�]�W�U�Ǵ�year = 0 , term = 0
		$p_n_term = 0;   
		$is_next = 0;
		if($rowsq["term"] == 1){
			$Qhave_next = "SELECT * FROM teach_course WHERE year ='".$rowsq["year"]."' AND term = '2'";
		}
		else{
			$Qhave_next = "SELECT * FROM teach_course WHERE year ='".($rowsq["year"]+1)."' AND term = '1'";
		}
		if ( !($result_have = mysql_db_query( $DB, $Qhave_next ) ) ) {
			$message = "$message - ��ƮwŪ�����~!!";
		}
		else{
			if(mysql_num_rows($result_have) == 0){
				//�S���U�Ǵ� ��ܤW�Ǵ�
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
				//���U�Ǵ�
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
		//���Ǵ��ΤW�U�Ǵ�����T�qcourse��
		$Q1 = "select c.group_id, cg.name AS gname, tc.course_id, tc.year, tc.term, c.name AS cname FROM course c, course_group cg, teach_course tc , user u where u.id = '$user_id' and tc.teacher_id = u.a_id and c.a_id = tc.course_id and cg.a_id = c.group_id order by tc.year desc, tc.term asc ,c.group_id ASC, c.a_id ASC";

		if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
			$message = "$message - ��ƮwŪ�����~!!";
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
						$message = "$message - ��ƮwŪ�����~!!";
					}
					while ( $row3 = mysql_fetch_array( $result3 ) ) {
						$course_no .= $row3['course_no']." ";
					}
					$tpl->assign( CNO , $course_no );
					$tpl->assign( YEAR, $row['year']."�Ǧ~�ײ�".$row['term']."�Ǵ�");
					$Q2 = "select u.id, u.name, u.nickname, u.a_id, u.php FROM user u , teach_course tc where tc.course_id = '".$row["course_id"]."' and tc.teacher_id = u.a_id and u.authorization='1' and tc.year = '$row[year]' and tc.term = '$row[term]'";
					if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) ) {
						$message = "$message - ��ƮwŪ�����~!!";
					}
//----------�n�����Z����:start---------------------------------------------
					//linsy@20111222, �Y���U�Ыh����ܵn�J�`���Z
					$QTA = "select authorization from user where id = '$user_id'";
					if ( !($resultTA = mysql_db_query( $DB, $QTA ) ) ) {
						$message = "$message - ��ƮwŪ�����~!!";
					}
					$rowTA = mysql_fetch_assoc($resultTA);
					if ($rowTA['authorization'] == 2)
						$test = "";
					else
						$test ="<a href=\"../Trackin/TGQueryFrame2.php?course=".$row["course_id"]."&year=$row[year]&term=$row[term]&action=upload&PHPSESSID=".session_id()."\">�n���`���Z</a>";
					$tpl->assign( GRADE, $test );
//----------�n�����Z����:end-----------------------------------------------
					$tpl->assign( GNAME , $row["gname"] );
					if( substr( $course_no, 3, 1 ) == "A" || substr( $course_no, 3, 1 ) == "B" || substr( $course_no, 3, 1 ) == "C" || substr( $course_no, 3, 1 ) == "D" )
						$tpl->assign( CNAME , "<a href=\"../login.php?courseid=".$row['year']."_".$row['term']."_".$row["course_id"]."\" target=\"_top\">".$row["cname"]."(�Ӥh�b¾�M�Z)"."</a>" );
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
                                           �b�Юv��"�ڪ��ҵ{"�C���A���C�@�ӽҵ{�W�١A�e�h mmc �d�߬O�_���w���|ĳ

                                         */
                                        require_once 'my_rja_db_lib.php';

// $reservation_meeting �o���ܼƬO�b�e�� call  show_page_d �h require ���o��
global $reservation_meeting;
$haveMeeting = false;
if(!empty($reservation_meeting)){
	foreach($reservation_meeting as $value){
		//�Y�ҵ{�W�٤@�ˡA�ӥB�w���}�l����]�O����
		if(($row['cname'] == $value['courseName']) && (date('Ymd') == date('Ymd',$value['startTime']))){
			//�u�ݤ�{�b�ɶ��٦��T�p�ɥH�᪺�|ĳ
			if ($value['startTime'] < (time() -10800 )){
				continue;
			}

			global $user_id;
			$teacher_id_num = $value['teacherIdNum'];
			//�d����m�W
			$my_query_user_name = "select name from user where id = '$user_id' ";
			$my_user_name = query_db_to_value($my_query_user_name);

			$my_gotomeeting_url = "http://mmc.elearning.ccu.edu.tw/gotomeeting.php?u=$teacher_id_num&c=visit&name=$my_user_name";

			if($value['isOnline']){
				$tpl->assign( MMCONLINE , "<a href=\"$my_gotomeeting_url\" onclick=\"window.open(this.href);return false;\">�W�Ҥ�</a> ");
			}else if(!$value['finished']){
				//�i��}���|��A�n���٬O�|�b isOnline �����A�A�ҥH�h�P�_�@�ӬO�_�����F
				$courseStartTime = date('g:i a',$value['startTime']);
			$tpl->assign( MMCONLINE , "<font>���� {$courseStartTime} �W��</font>");
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
/*******************************�W/�U�Ǵ�*****************************************************/
				else if($row["year"]==$p_n_year && $row["term"]==$p_n_term){
					if ( $color == "#E6FFFC" )
						$color = "#F0FFEE";
					else
						$color = "#E6FFFC";
					$tpl->assign( P_N_COL , $color );
					
					$course_no = "";
					$Q3 = "select course_no FROM course where a_id = '".$row["course_id"]."'";
					if ( !($result3 = mysql_db_query( $DB, $Q3 ) ) ) {
						$message = "$message - ��ƮwŪ�����~!!";
					}
					while ( $row3 = mysql_fetch_array( $result3 ) ) {
						$course_no .= $row3['course_no']." ";
					}
					$tpl->assign( P_N_NO , $course_no );
					$tpl->assign( P_N_YE, $row['year']."�Ǧ~�ײ�".$row['term']."�Ǵ�");
					$Q2 = "select u.id, u.name, u.nickname, u.a_id, u.php FROM user u , teach_course tc where tc.course_id = '".$row["course_id"]."' and tc.teacher_id = u.a_id and u.authorization='1' and tc.year = '$row[year]' and tc.term = '$row[term]'";
					if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) ) {
						$message = "$message - ��ƮwŪ�����~!!";
					}
					$tpl->assign( P_N_G_NAME , $row["gname"] );
					if( substr( $course_no, 3, 1 ) == "A" || substr( $course_no, 3, 1 ) == "B" || substr( $course_no, 3, 1 ) == "C" || substr( $course_no, 3, 1 ) == "D" )
						$tpl->assign( P_N_C_NAME , "<a href=\"../login.php?courseid=".$row['year']."_".$row['term']."_".$row["course_id"]."\" target=\"_top\">".$row["cname"]."(�Ӥh�b¾�M�Z)"."</a>" );
					else
						$tpl->assign( P_N_C_NAME , "<a href=\"../login.php?courseid=".$row['year']."_".$row['term']."_".$row["course_id"]."\" target=\"_top\">".$row["cname"]."</a>" );
					//�n�����Z
					$test ="<a href=\"../Trackin/TGQueryFrame2.php?course=".$row["course_id"]."&year=$row[year]&term=$row[term]&action=upload&PHPSESSID=".session_id()."\">�n���`���Z</a>";
					//$test ="<a href=\"../backup_grade/BGQueryFrame1.php?action=upload&course=".$row["course_id"]."&year=94&term=2\">�n���`���Z</a>";
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
/***********************************���v��**********************************************************/				
		//���v�Ϫ�course��T�qhist_course��
		$Q1 = "select c.group_id, cg.name AS gname, tc.course_id, tc.year, tc.term, c.name AS cname, c.course_no AS course_no FROM hist_course c, course_group cg, teach_course tc , user u where u.id = '$user_id' and tc.teacher_id = u.a_id and c.a_id = tc.course_id and cg.a_id = c.group_id and c.year = tc.year and c.term=tc.term and !((tc.year = $rowsq[year] and tc.term = $rowsq[term]) OR (tc.year = $p_n_year and tc.term = $p_n_term)) order by tc.year desc, tc.term asc";		
		if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
			$message = "$message - ��ƮwŪ�����~!!";
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
					$message = "$message - ��ƮwŪ�����~!!";
				}
				while ( $row3 = mysql_fetch_array( $result3 ) ) {
					$course_no .= $row3['course_no']." ";
				}
				*/
				$course_no = $row['course_no'];
				$tpl->assign( C_OLD_NO , $course_no );
				$tpl->assign( OLD_YE, $row['year']."�Ǧ~�ײ�".$row['term']."�Ǵ�");
				$Q2 = "select u.id, u.name, u.nickname, u.a_id, u.php FROM user u , teach_course tc where tc.course_id = '".$row["course_id"]."' and tc.teacher_id = u.a_id and u.authorization='1' and tc.year = '$row[year]' and tc.term = '$row[term]'";
				if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) ) {
					$message = "$message - ��ƮwŪ�����~!!";
				}
				$tpl->assign( G_OLD_NAME , $row["gname"] );
				if( substr( $course_no, 3, 1 ) == "A" || substr( $course_no, 3, 1 ) == "B" || substr( $course_no, 3, 1 ) == "C" || substr( $course_no, 3, 1 ) == "D" )
					$tpl->assign( C_OLD_NAME , "<a href=\"../login.php?courseid=hist_".$row['year']."_".$row['term']."_".$row["course_id"]."\" target=\"_top\">".$row["cname"]."(�Ӥh�b¾�M�Z)"."</a>" );
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
			$message = "$message - ��ƮwŪ�����~!!";
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
				$tpl->assign( PREORNEXT , "�U" );
			}
			else{
				$tpl->assign( PREORNEXT , "Next" );
			}
		}
		else{
			if ( $version == "C" ) {
				$tpl->assign( PREORNEXT , "�W" );
			}
			else{
				$tpl->assign( PREORNEXT , "Previous" );
			}
		}
		$tpl->parse( BODY, "body" );
		$tpl->FastPrint("BODY");
	}
?>
