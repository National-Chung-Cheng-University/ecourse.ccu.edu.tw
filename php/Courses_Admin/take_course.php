<?
require 'fadmin.php';
	if (!(isset($PHPSESSID) && session_check_stu($PHPSESSID)) ) {
		show_page( "not_access.tpl" ,"�v�����~");
	}
	else {
		session_unregister("time");
		session_register("time");
		$time = date("U");
		if ( $frame != 1 ) {
			echo "<frameset rows='*,0' frameborder = 'no'>\n";
			echo "<frame src='take_course.php?PHPSESSID=$PHPSESSID&frame=1' name='main' frameborder = 'no'>\n";
			echo "<frame src='../noop.php?PHPSESSID=$PHPSESSID' name='noop' frameborder = 'no'>\n";
			echo "</frameset>";
		}
		else {
			$Q1 = "select authorization FROM user where id = '$user_id'";
			if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
				$message = "$message - ��ƮwŪ�����~!!";
			}else
				$row = mysql_fetch_array( $result );
	
			if ( $row['authorization'] <= "3" && $teacher != 1) {
				if ( $action == "drop" ) {
					$message = drop_course();
				}	

				//changed here by rja
				//�ΨӥhŪ�ۤv�ҭת��ҵ{���A�����ǹw���|ĳ�Ainclude �U���o��{����A�|�o��@�ӹw���|ĳ��T>�s $reservation_meeting
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
		global $version,$course_id, $skinnum;
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $user_id;		
		
		//Modified by Yuwan 2011.12.08
                //�D�X��u�t���ǥ͡A�b�n�J�����|���p�F�Ϫ��s��

                $Q_r="SELECT id,job FROM  user WHERE id = '$user_id' and job = '��u�t'";
                if ( !($result_r = mysql_db_query( $DB, $Q_r ) ) ) {
                        $message = "$message - ��ƮwŪ�����~!!";
                }
                else{
                        $row_r = mysql_fetch_array( $result_r );
                }
                if ( $row_r['id']  )
                        $tpl->assign( TESTM ," <a href=\"../rgraph/".$user_id.".png\" onclick=\"window.open(this.href);return false;\">�֤߯�O�p�F��</a>" );
                else
                         $tpl->assign( TESTM ,"<!-- -->");		
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
			$tpl->define ( array ( body => "take_course.tpl" ) );
		else
			$tpl->define ( array ( body => "take_course_E.tpl" ) );
		$tpl->define_dynamic ( "course_list" , "body" );
		$tpl->define_dynamic ("new_cour_list", "body" );
		$tpl->assign( SKINNUM , $skinnum );
		$color = "#000066";
		$tpl->assign( COLOR , $color );
		$tpl->assign( TYPE , "colspan=2" );
		$tpl->assign( DEL , "" );
		if ( $version == "C" ) {
			$tpl->assign( GNAME , "<font color =#FFFFFF>�}�ҳ��</font>" );
			$tpl->assign( CNO , "<font color =#FFFFFF>�ҵ{�s��</font>" );
			$tpl->assign( CNAME , "<font color =#FFFFFF>�ҵ{�W�٤εn�J</font>" );
			$tpl->assign( CTEACH , "<font color =#FFFFFF>�½ұЮv</font>" );
			$tpl->assign( CNEWS , "<font color =#FFFFFF>�s���i</font>" );
			$tpl->assign( CHOME , "<font color =#FFFFFF>����@�~</font>" );
			$tpl->assign( CEXAM , "<font color =#FFFFFF>��������</font>" );
			$tpl->assign( CSTATUS , "<font color =#FFFFFF>�׽Ҩ���</font>" );
			// 102.04.16 add by jim
			$tpl->assign( EWSTAT , "<font color =#FFFFFF>�wĵ���</font>" );
      //changed 1 line by rja
      $tpl->assign( MMCONLINE , "<font color =#FFFFFF>�u�W�줽��</font>" );


		}
		else {
			$tpl->assign( GNAME , "<font color =#FFFFFF>Department</font>" );
			$tpl->assign( CNO , "<font color =#FFFFFF>No.</font>" );
			$tpl->assign( CNAME , "<font color =#FFFFFF>Course Name & Login</font>" );
			$tpl->assign( CTEACH , "<font color =#FFFFFF>Teachers</font>" );
			$tpl->assign( CNEWS , "<font color =#FFFFFF>New News</font>" );
			$tpl->assign( CHOME , "<font color =#FFFFFF>New Homework</font>" );
			$tpl->assign( CEXAM , "<font color =#FFFFFF>New Exam</font>" );
			$tpl->assign( CSTATUS , "<font color =#FFFFFF>Identification</font>" );
			// 102.04.16 add by jim
			$tpl->assign( EWSTAT , "<font color =#FFFFFF>�wĵ���</font>" );
      //changed 1 line by rja
      $tpl->assign( MMCONLINE , "<font color =#FFFFFF>Meeting Online</font>" );


		}
		$tpl->parse ( COURSE_LIST, ".course_list" );
		
		$new_color = "#000066";
		$tpl->assign( NEW_COL , $new_color );
		$tpl->assign( TYPE , "colspan=2" );
		
		if ( $version == "C" ) {
			$tpl->assign( G_NEW_NAME , "<font color =#FFFFFF>�}�ҳ��</font>" );
			$tpl->assign( C_NEW_NO , "<font color =#FFFFFF>�ҵ{�s��</font>" );
			$tpl->assign( C_NEW_NAME , "<font color =#FFFFFF>�ҵ{�W��</font>" );
			$tpl->assign( C_NEW_TEACH , "<font color =#FFFFFF>�½ұЮv</font>" );
			$tpl->assign( C_NEW_NEWS , "<font color =#FFFFFF>�s���i</font>" );
			$tpl->assign( C_NEW_HOME , "<font color =#FFFFFF>����@�~</font>" );
			$tpl->assign( C_NEW_EXAM , "<font color =#FFFFFF>��������</font>" );
			$tpl->assign( C_NEW_STATUS , "<font color =#FFFFFF>�׽Ҩ���</font>" );
		}
		else {
			$tpl->assign( G_NEW_NAME , "<font color =#FFFFFF>Department</font>" );
			$tpl->assign( C_NEW_NO , "<font color =#FFFFFF>No.</font>" );
			$tpl->assign( C_NEW_NAME , "<font color =#FFFFFF>Course Name & Login</font>" );
			$tpl->assign( C_NEW_TEACH , "<font color =#FFFFFF>Teachers</font>" );
			$tpl->assign( C_NEW_NEWS , "<font color =#FFFFFF>New News</font>" );
			$tpl->assign( C_NEW_HOME , "<font color =#FFFFFF>New Homework</font>" );
			$tpl->assign( C_NEW_EXAM , "<font color =#FFFFFF>New Exam</font>" );
			$tpl->assign( C_NEW_STATUS , "<font color =#FFFFFF>Identification</font>" );
		}
		$tpl->parse ( NEW_COUR_LIST, ".new_cour_list" );
		
		$color = "#E6FFFC";
		$tpl->assign( TYPE , "" );
		//global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $user_id;
		
		$Qsemester = "select * from this_semester";
		$resultsemester = mysql_db_query($DB, $Qsemester);
		$rowsq = mysql_fetch_array($resultsemester);
		
		
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$message = "$message - ��Ʈw�s�����~!!";
		}
		
		//$Q1 = "select distinct c.group_id, cg.name AS gname, tc.course_id, c.introduction, c.name AS cname, teac.year, teac.term, u.a_id, tc.credit, c.validated FROM course c, course_group cg, take_course tc, teach_course teac , user u where u.id = '$user_id' and tc.student_id = u.a_id and c.a_id = tc.course_id and teac.course_id=tc.course_id and cg.a_id = c.group_id and tc.validated = '1' order by teac.year desc, teac.term asc, cg.a_id";
		//�令�s���ҵ{�d��		
		//�����Xtake_course������year�Mterm �M��զ�SQL 
//$Q_yt = "select distinct year, term from take_course"; -- modify by intree 2007/08/09
                $Q_yt = "select distinct year, term from take_course where year >= $rowsq[year] ";

		if ( !($result_yt = mysql_db_query( $DB, $Q_yt ) ) ) {
			$message = "$message - ��ƮwŪ�����~!!";
		}
    //98.09.15 Jim �Nteac.year�קאּtc.year��teac.term�קאּtc.term
		//$Q1 = "select distinct c.group_id, cg.name AS gname, tc.course_id, c.introduction, c.name AS cname, teac.year, teac.term, u.a_id, tc.credit, c.validated FROM course c, course_group cg, take_course tc, teach_course teac , user u where u.id = '$user_id' and tc.student_id = u.a_id and c.a_id = tc.course_id and teac.course_id=tc.course_id  and cg.a_id = c.group_id and tc.validated = '1' AND ( ";
		$Q1 = "select distinct c.group_id, cg.name AS gname, tc.course_id, c.introduction, c.name AS cname, tc.year, tc.term, u.a_id, tc.credit, c.validated FROM course c, course_group cg, take_course tc, teach_course teac , user u where u.id = '$user_id' and tc.student_id = u.a_id and c.a_id = tc.course_id and teac.course_id=tc.course_id  and cg.a_id = c.group_id and tc.validated = '1' AND ( ";
		$first = 1;
		$have_next = 0; //�O�_���U�Ǵ�
		while ( $row_yt = mysql_fetch_array( $result_yt ) )
		{
			if($first == 1 ){
				$first = 0;
			}
			else{
				$Q1 .= " OR ";
			}
			//98.09.15 Jim �Nteac.year�קאּtc.year��teac.term�קאּtc.term
			//$Q1 .= "( teac.year = $row_yt[year] AND teac.term = $row_yt[term] )";
			$Q1 .= "( tc.year = $row_yt[year] AND tc.term = $row_yt[term] )";
			
			//�P�_�O�_���U�Ǵ�
			
			if( ($row_yt['year'] * 2 + $row_yt['term']) > $rowsq['year']*2+$rowsq['term']){
				$have_next = 1;
			}
			
		}
		//98.09.15 Jim �Nteac.year�קאּtc.year��teac.term�קאּtc.term
		//$Q1 .= " )  order by teac.year desc, teac.term asc, cg.a_id, c.a_id";
		$Q1 .= " )  order by tc.year desc, tc.term asc, cg.a_id, c.a_id";
		//				

		$D1 = "delete from online where user_id = '$user_id'";
//		mysql_db_query( $DB, $D1 );

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
					$Q2 = "select count(h.a_id) as sum from handin_homework hh, homework h where h.a_id = hh.homework_id and hh.handin_time = '0000-00-00' and hh.student_id = '". $row["a_id"] . "' and (h.public = '1' or h.public = '3')";
					if ( !($result2 = mysql_db_query( $DB.$row["course_id"], $Q2 ) ) ) {
						$message = "$message - ��ƮwŪ�����~!!";
					}
					$Q3 = "select count(e.a_id) as sum from take_exam te, exam e where e.is_online = '1' and e.a_id = te.exam_id and te.grade = '-1' and te.student_id = '". $row["a_id"] . "' and ( (e.public = '1' or e.public = '3') ||  e.end_time != '00000000000000' ) and e.beg_time <= ".date("YmdHis")." and e.end_time > ".date("YmdHis");
					if ( !($result3 = mysql_db_query( $DB.$row["course_id"], $Q3 ) ) ) {
						$message = "$message - ��ƮwŪ�����~!!";
					}
					$Q4 = "select count(a_id) as sum FROM news where system = '0' and begin_day <= '".date("Y-m-d")."' and end_day >= '".date("Y-m-d")."' and begin_day+0 > '".date("Ymd")."' - 2";
					if ( !($result4 = mysql_db_query( $DB.$row["course_id"], $Q4 ) ) ) {
						$message = "$message - ��ƮwŪ�����~!!";
					}
					$Q5 = "select distinct u.id, u.name, u.nickname, u.a_id FROM user u , teach_course tc where tc.course_id = '".$row["course_id"]."' and tc.teacher_id = u.a_id and u.authorization='1' and tc.year='".$rowsq["year"]."' and tc.term='".$rowsq["term"]."'";
					if ( !($result5 = mysql_db_query( $DB, $Q5 ) ) ) {
						$message = "$message - ��ƮwŪ�����~!!";
					}
					$row2 = mysql_fetch_array( $result2 );
					$row3 = mysql_fetch_array( $result3 );
					$row4 = mysql_fetch_array( $result4 );
					$tpl->assign( GNAME , "�E</td><td bgcolor=$color width=75><font size=-1>".$row["gname"] );
					
					if( $row["validated"]%2 == 1 &&  $row['credit'] != 1 ) {
						 //echo "  ".$row["course_id"]."&". $row["validated"]; //for test
						if ( $row['introduction'] != "" || is_file("../../".$row["course_id"]."/intro/index.html") )
							$tpl->assign( CNAME , "<a href=# onClick=\"window.open('./intro.php?PHPSESSID=".session_id()."&courseid=".$row["course_id"]."&query=1', '', 'resizable=1,scrollbars=1,width=640,height=480');\">".$row["cname"]."</a>" );
						else
							$tpl->assign( CNAME , $row["cname"] );
					}
					else {
						//echo "  ".$row["course_id"]."&". $row["validated"]; //for test
						$tpl->assign( CNAME , "<a href=\"../login_s.php?courseid=".$row["year"]."_".$row["term"]."_".$row["course_id"]."\" target=\"_top\">".$row["cname"]."</a>" );
					}
					if ( $row['credit'] == 1 ) {
						$tpl->assign( DEL , "" );
						if ( $version == "C" )
							$tpl->assign( CSTATUS , "���ץ�" );
						else
							$tpl->assign( CSTATUS , "Credit" );
					}
					else {
						$tpl->assign( AID , $row["course_id"] );
						$tpl->assign( YEAR , $row["year"] );
						$tpl->assign( TERM , $row["term"] );
						if ( $version == "C" ) {
							$tpl->assign( CSTATUS , "��ť��" );						
							$tpl->assign( DEL , "�h��" );
						}
						else {
							$tpl->assign( CSTATUS , "No Credit" );
							$tpl->assign( DEL , "Drop" );
						}						
					}
					
					//�P�_�O�_���wĵ��
					$early="--";
					$Q6 = "SELECT * FROM early_warning WHERE course_id = '$row[course_id]' AND year = '$rowsq[year]' AND term = '$rowsq[term]' AND student_id = '$row[a_id]' ";
					//echo $Q6;
					if ( $result6 = mysql_db_query( $DB, $Q6 ) ) 
					{
						$count6 = mysql_num_rows( $result6 );
						$row6 = mysql_fetch_array( $result6 );
						
						switch ( $row6['reason'] ) 
		        {
							case 1:
								$reasoncode = '���Z����';
								break;
							case 2:
								$reasoncode = '�ʽ�';
								break;
							case 3:
								$reasoncode = '���Z���ΥB�ʽ�';
								break;						
							case 4:
								$reasoncode = '�@�~���̳W�wú��';
								break;						
							case 5:
								$reasoncode = '�ʦ�';
								break;
							default:
								$reasoncode = '��L';
						}								
													
				
						if($count6 >= 1) $early="<font color=red>-�Ӭ�سQ�wĵ".$reasoncode."</font>"; else $early="--";
					}
					else
					{
						$message = "$message - ��ƮwŪ�����~!!";
					}			
					$tpl->assign( EWSTAT , $early);		
	//changed here by rja
	//�ǥͭn���ݦѮv���S���b�u�W�A�A�ݦ��S���w���|ĳ

/* changed temp here
 
//this
#error_reporting(1);    
	require_once 'my_rja_db_lib.php';
	$course_name = urlencode($row['cname']);                                                   

	$query_teacherid_from_coursename = "http://mmc.elearning.ccu.edu.tw/my_get_mmc_info.php?action=getOnlineTeacherByCourseName&course_name=$course_name";
	//print $query_teacherid_from_coursename;
//this
	$onlineTeacherId = file_get_contents($query_teacherid_from_coursename);


	$onlineTeacherId = explode(',',$onlineTeacherId);
	$onlineTeacherId = (int)$onlineTeacherId[0];


	//error check
	if(is_numeric($onlineTeacherId) && $onlineTeacherId!=0){
		//�Ѯv�w�g�b�u�W(�w���ΧY��)�A�ǳƪ����i�J�|ĳ                                                                             //find user name from user id
		//���i�J�|ĳ�ɡA�۰ʿ�J�W��
		global $user_id;
		$my_query_user_name = "select name from user where id = '$user_id' ";                                                      $my_user_name = query_db_to_value($my_query_user_name);
		$my_gotomeeting_url = "http://mmc.elearning.ccu.edu.tw/gotomeeting.php?u=$onlineTeacherId&c=visit&name=$my_user_name";                                                                                                                          $tpl->assign( MMCONLINE , "<a href=\"$my_gotomeeting_url\" onclick=\"window.open(this.href);return false;\">�W�Ҥ�</a> ");
	}else{                                                                                                                             //�Ѯv���b�u�W�A���ݳo���ҵ{���Ѧ��S���w���|ĳ


		/*
		   �b�ǥͪ�"�ڪ��ҵ{"�C���A���C�@�ӽҵ{�W�١A�e�h mmc �d�߬O�_���w���|ĳ

		 */

		require_once 'my_rja_db_lib.php';

/* changed temp here
		// $reservation_meeting �o���ܼƬO�b�e�� call  show_page_d �h require ���o��
		//next: �q�o�̶}�l debug�A���}�w���|ĳ�Ӵ���
		global $reservation_meeting;
		$haveMeeting = false;
		if (!empty($reservation_meeting)){
			foreach($reservation_meeting as $value){
				//�Y�ҵ{�W�٤@�ˡA�ӥB�w���}�l����]�O����
				if(($row['cname'] == $value['courseName']) && (date('Ymd') == date('Ymd',$value['startTime']))){
					//�u�ݤ�{�b�ɶ��٦��T�p�ɥH�᪺�|ĳ
					if ($value['startTime'] < (time() -10800 )){
						continue;
					}

					global $user_id;
					$teacher_id_num = $value['teacherIdNum'];                                                                                  //�d����m�W
					$my_query_user_name = "select name from user where id = '$user_id' ";
					$my_user_name = query_db_to_value($my_query_user_name);
					$my_gotomeeting_url = "http://mmc.elearning.ccu.edu.tw/gotomeeting.php?u=$teacher_id_num&c=visit&name=$my_user_name";
					if($value['isOnline']){                                                                                                            $tpl->assign( MMCONLINE , "<a href=\"$my_gotomeeting_url\" onclick=\"window.open(this.href);return false;\">�W�Ҥ�</a> ");
					}else if(!$value['finished']){                                                                                                     //�i��}���|��A�n���٬O�|�b isOnline �����A�A�ҥH�h�P�_�@?ӬO�_�����F
						$courseStartTime = date('g:i a',$value['startTime']);
					$tpl->assign( MMCONLINE , "<font>���� {$courseStartTime} �W��</font>");
				}else continue;


				$haveMeeting = true;                                                                                                       break;
				}
			}
		}
		if(!$haveMeeting)
			$tpl->assign( MMCONLINE , "<font>-</font>");
	}

 changed temp here */
	//end of changed

			$tpl->assign( MMCONLINE , "<font>-</font>");








					$name = "";
					while ( $row5 = mysql_fetch_array( $result5 ) )
					{
						if ( $row5['name'] != NULL ) {
							if ( $row5['php'] != NULL ) {
								$name = $name." <a href=# onClick=\"window.open('".$row5['php']."', '', 'resizable=1,scrollbars=1,width=640,height=480');\">".$row5['name']."</a>";
							}
							else {
								$name = $name." <a href=# onClick=\"window.open('../Learner_Profile/TTDATAQuery1.php?PHPSESSID=".session_id()."&user_aid=".$row5['a_id']."&query=1', '', 'resizable=1,scrollbars=1,width=640,height=480');\">".$row5['name']."</a>";
							}
						}
						else if ( $row5['nickname'] != NULL ) {
							if ( $row5['php'] != NULL ) {
								$name = $name." <a href=# onClick=\"window.open('".$row5['php']."', '', 'resizable=1,scrollbars=1,width=640,height=480');\">".$row5['nickname']."</a>";
							}
							else {
								$name = $name." <a href=# onClick=\"window.open('../Learner_Profile/TTDATAQuery1.php?PHPSESSID=".session_id()."&user_aid=".$row5['a_id']."&query=1', '', 'resizable=1,scrollbars=1,width=640,height=480');\">".$row5['nickname']."</a>";
							}
						}
						else {
							if ( $row5['php'] != NULL ) {
								$name = $name." <a href=# onClick=\"window.open('".$row5['php']."', '', 'resizable=1,scrollbars=1,width=640,height=480');\">".$row5['id']."</a>";
							}
							else {
								$name = $name." <a href=# onClick=\"window.open('../Learner_Profile/TTDATAQuery1.php?PHPSESSID=".session_id()."&user_aid=".$row5['a_id']."&query=1', '', 'resizable=1,scrollbars=1,width=640,height=480');\">".$row5['id']."</a>";
							}
						}
					}
					$course_no = "";
					$Q6 = "select course_no FROM course where a_id = '".$row["course_id"]."'";
					if ( !($result6 = mysql_db_query( $DB, $Q6 ) ) ) {
						$message = "$message - ��ƮwŪ�����~!!";
					}
					while ( $row6 = mysql_fetch_array( $result6 ) ) {
						$course_no .= $row6['course_no']." ";
					}
					$tpl->assign( CTEACH , $name );
					$tpl->assign( CNEWS , $row4["sum"] );
					$tpl->assign( CHOME , $row2["sum"] );
					$tpl->assign( CEXAM , $row3["sum"] );
					$tpl->assign( CNO , $course_no );
					$tpl->parse ( COURSE_LIST, ".course_list" );
				}
				else
				{
				
					if ( $color == "#E6FFFC" )
						$color = "#F0FFEE";
					else
						$color = "#E6FFFC";
					$tpl->assign( NEW_COL , $color );
					$Q2 = "select count(h.a_id) as sum from handin_homework hh, homework h where h.a_id = hh.homework_id and hh.handin_time = '0000-00-00' and hh.student_id = '". $row["a_id"] . "' and (h.public = '1' or h.public = '3')";
					if ( !($result2 = mysql_db_query( $DB.$row["course_id"], $Q2 ) ) ) {
						$message = "$message - ��ƮwŪ�����~!!";
					}
					$Q3 = "select count(e.a_id) as sum from take_exam te, exam e where e.is_online = '1' and e.a_id = te.exam_id and te.grade = '-1' and te.student_id = '". $row["a_id"] . "' and ( (e.public = '1' or e.public = '3') ||  e.end_time != '00000000000000' ) and e.beg_time <= ".date("YmdHis")." and e.end_time > ".date("YmdHis");
					if ( !($result3 = mysql_db_query( $DB.$row["course_id"], $Q3 ) ) ) {
						$message = "$message - ��ƮwŪ�����~!!";
					}
					$Q4 = "select count(a_id) as sum FROM news where system = '0' and begin_day <= '".date("Y-m-d")."' and end_day >= '".date("Y-m-d")."' and begin_day+0 > '".date("Ymd")."' - 2";
					if ( !($result4 = mysql_db_query( $DB.$row["course_id"], $Q4 ) ) ) {
						$message = "$message - ��ƮwŪ�����~!!";
					}
					$Q5 = "select distinct u.id, u.name, u.nickname, u.a_id FROM user u , teach_course tc where tc.course_id = '".$row["course_id"]."' and tc.teacher_id = u.a_id and u.authorization='1' and tc.year='".$row["year"]."' and tc.term='".$row["term"]."'";
					if ( !($result5 = mysql_db_query( $DB, $Q5 ) ) ) {
						$message = "$message - ��ƮwŪ�����~!!";
					}
					$row2 = mysql_fetch_array( $result2 );
					$row3 = mysql_fetch_array( $result3 );
					$row4 = mysql_fetch_array( $result4 );
					$tpl->assign( G_NEW_NAME , "�E</td><td bgcolor=$color width=75><font size=-1>".$row["gname"] );
					
					if( $row["validated"]%2 == 1 &&  $row['credit'] != 1 ) {
						if ( $row['introduction'] != "" || is_file("../../".$row["course_id"]."/intro/index.html") )
							$tpl->assign( C_NEW_NAME , "<a href=# onClick=\"window.open('./intro.php?PHPSESSID=".session_id()."&courseid=".$row["course_id"]."&query=1', '', 'resizable=1,scrollbars=1,width=640,height=480');\">".$row["cname"]."</a>" );
						else
							$tpl->assign( C_NEW_NAME , $row["cname"] );
					}
					else {
						$tpl->assign( C_NEW_NAME , "<a href=\"../login_s.php?courseid=".$row["year"]."_".$row["term"]."_".$row["course_id"]."\" target=\"_top\">".$row["cname"]."</a>" );
					}
					if ( $row['credit'] == 1 ) {
						$tpl->assign( C_NEW_DROP , "" );
						if ( $version == "C" )
							$tpl->assign( C_NEW_STATUS , "���ץ�" );
						else
							$tpl->assign( C_NEW_STATUS , "Credit" );
					}
					else {
						$tpl->assign( A_NWEID , $row["course_id"] );
						$tpl->assign( NYR , $row["course_id"] );
						$tpl->assign( NTRM , $row["course_id"] );
						if ( $version == "C" ) {
							$tpl->assign( C_NEW_STATUS , "��ť��" );						
							$tpl->assign( C_NEW_DROP , "�h��" );
						}
						else {
							$tpl->assign( C_NEW_STATUS , "No Credit" );
							$tpl->assign( C_NEW_DROP , "Drop" );
						}
					}
					$name = "";
					while ( $row5 = mysql_fetch_array( $result5 ) )
					{
						if ( $row5['name'] != NULL ) {
							if ( $row5['php'] != NULL ) {
								$name = $name." <a href=# onClick=\"window.open('".$row5['php']."', '', 'resizable=1,scrollbars=1,width=640,height=480');\">".$row5['name']."</a>";
							}
							else {
								$name = $name." <a href=# onClick=\"window.open('../Learner_Profile/TTDATAQuery1.php?PHPSESSID=".session_id()."&user_aid=".$row5['a_id']."&query=1', '', 'resizable=1,scrollbars=1,width=640,height=480');\">".$row5['name']."</a>";
							}
						}
						else if ( $row5['nickname'] != NULL ) {
							if ( $row5['php'] != NULL ) {
								$name = $name." <a href=# onClick=\"window.open('".$row5['php']."', '', 'resizable=1,scrollbars=1,width=640,height=480');\">".$row5['nickname']."</a>";
							}
							else {
								$name = $name." <a href=# onClick=\"window.open('../Learner_Profile/TTDATAQuery1.php?PHPSESSID=".session_id()."&user_aid=".$row5['a_id']."&query=1', '', 'resizable=1,scrollbars=1,width=640,height=480');\">".$row5['nickname']."</a>";
							}
						}
						else {
							if ( $row5['php'] != NULL ) {
								$name = $name." <a href=# onClick=\"window.open('".$row5['php']."', '', 'resizable=1,scrollbars=1,width=640,height=480');\">".$row5['id']."</a>";
							}
							else {
								$name = $name." <a href=# onClick=\"window.open('../Learner_Profile/TTDATAQuery1.php?PHPSESSID=".session_id()."&user_aid=".$row5['a_id']."&query=1', '', 'resizable=1,scrollbars=1,width=640,height=480');\">".$row5['id']."</a>";
							}
						}
					}
					$course_no = "";
					$Q6 = "select course_no FROM course where a_id = '".$row["course_id"]."'";
					if ( !($result6 = mysql_db_query( $DB, $Q6 ) ) ) {
						$message = "$message - ��ƮwŪ�����~!!";
					}
					while ( $row6 = mysql_fetch_array( $result6 ) ) {
						$course_no .= $row6['course_no']." ";
					}
					$tpl->assign( C_NEW_TEACH , $name );
					$tpl->assign( C_NEW_NEWS , $row4["sum"] );
					$tpl->assign( C_NEW_HOME , $row2["sum"] );
					$tpl->assign( C_NEW_EXAM , $row3["sum"] );
					$tpl->assign( C_NEW_NO , $course_no );
					$tpl->parse ( NEW_COUR_LIST, ".new_cour_list" );
					
				}
			}			
		}
		else
			$tpl->assign( HRLINE , "" );	

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
		if ( $course_id == "-1" || $popup != 1)
			$tpl->assign( SYS , "//" );
		else
			$tpl->assign( SYS , "" );
		
		//�p�G�S���U�Ǵ���HTML�������ǥͬݤ���
		if ( $have_next==0){
			$tpl->assign( COMMENT_START , "<!--" );
			$tpl->assign( COMMENT_END , "-->" );
		}
		else{
			$tpl->assign( COMMENT_START , "" );
			$tpl->assign( COMMENT_END , "" );
		}
		//�[�J�ǥ͸�T:�t�� �Ǹ� �m�W �~��
		$Q_stu = "select * FROM user where id = '$user_id'";
		if ( !($result_stu = mysql_db_query( $DB, $Q_stu ) ) ) {
			$message = "$message - ��ƮwŪ�����~!!";
		}else{
			$row_stu = mysql_fetch_array( $result_stu );
		}
		$tpl->assign( STUNAME , $row_stu['name'] );
		$tpl->assign( STUNO , $row_stu['id'] );
		$row_stu['job'] = iconv('utf-8','big5',$row_stu['job']);
		$tpl->assign( STUDEPT , $row_stu['job'] );
		$tpl->assign( STUGRA , $row_stu['grade']);
		//		
		$tpl->parse( BODY, "body" );
		$tpl->FastPrint("BODY");
		session_register("course_id");
		$course_id="-1";
	}
	
	function drop_course() {
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $a_id, $user_id, $year, $term;
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$message = "��Ʈw�s�����~!!";
			return $message;
		}
		$Q1 = "Select a_id From user Where id='$user_id'";
		if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
			$message = "��ƮwŪ�����~!!";
			return $message;
		}
		$row = mysql_fetch_array( $result );
		$aid = $row['a_id'];
		mysql_db_query( $DB.$a_id, "Delete From take_exam Where student_id='$aid'");
		mysql_db_query( $DB.$a_id, "Delete From log Where user_id='$aid'");
		mysql_db_query( $DB, "Delete From take_course Where student_id='$aid' and course_id = '$a_id' and year = '$year' and term = '$term'");
	}
?>
