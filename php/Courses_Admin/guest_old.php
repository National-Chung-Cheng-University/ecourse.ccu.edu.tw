<?php
  //-------------------------------------------------------//
  //�Ҧ���English version�����ѱ��F
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
		//�p��ϥήɶ���
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
		header( "Location: http://$SERVER_NAME/php/Courses_Admin/guest.php?groupid=$groupid&PHPSESSID=".session_id());
	}
	if ( $frame != 1 ) {
		echo "<frameset  rows='*,0' cols='*,0' frameborder = 'no'>\n";
		echo "<frame  src='guest.php?groupid=$groupid&PHPSESSID=$PHPSESSID&frame=1&year_term=".$year_term."&name='main'  noresize frameborder = 'no'>\n";
		echo "<frame  src='../noop.php?PHPSESSID=$PHPSESSID' name='noop' frameborder = 'no'>\n";
		echo "</frameset>";
	}
	else {
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		$tpl->define ( array ( body => "guest.tpl" ) );
		$tpl->define_dynamic ( "year_list" , "body");
		$tpl->define_dynamic ( "course_list" , "body" );
		$tpl->define_dynamic ( "group_list" , "body" );
		$tpl->define_dynamic ( "table_list" , "body" );
		$tpl->assign(GROUPID, $groupid);
		
		$color = "#000066";
		$tpl->assign( SKINNUM , $skinnum );
		$tpl->assign( COLOR , $color );
		//if ( $version == "C" ) {
			$tpl->assign( GNAME , "<font color =#FFFFFF>�}�ҳ��</font>" );
			$tpl->assign( YEAR, "<font color =#FFFFFF>�}�ҾǦ~��</font>");
			$tpl->assign( CNO , "<font color =#FFFFFF>�ҵ{�s��</font>" );
			$tpl->assign( CNAME , "<font color =#FFFFFF>�ҵ{�W�٤Τ���</font>" );
			$tpl->assign( CTEACH , "<font color =#FFFFFF>�½ұЮv</font>" );
			//$tpl->assign( CTA , "<font color =#FFFFFF>�H�ҧU��</font>" );
			$tpl->assign( CSTATUS , "<font color =#FFFFFF>�}�Ҫ��A</font>" );
		//}
		/*else {
			$tpl->assign( GNAME , "<font color =#FFFFFF>Department</font>" );
			$tpl->assign( YEAR, "<font color =#FFFFFF>Year</font>");
			$tpl->assign( CNO , "<font color =#FFFFFF>No.</font>" );
			$tpl->assign( CNAME , "<font color =#FFFFFF>Course Name & Introduction</font>" );
			$tpl->assign( CTEACH , "<font color =#FFFFFF>Teachers</font>" );
			$tpl->assign( CTA , "<font color =#FFFFFF>TA</font>" );
			$tpl->assign( CSTATUS , "<font color =#FFFFFF>Status</font>" );
		}*/
		$tpl->parse ( COURSE_LIST, ".course_list" );
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $groupid, $year_term;
		$Q0 = "select a_id , name from course_group where is_leaf = '1' order by a_id";
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$message = "$message - ��Ʈw�s�����~!!";
		}
		//��ܨt�Ҫ�Bar�@�ثe�o�ӥ\��S����ܦb�����W
		/*
		if ( !($result0 = mysql_db_query( $DB, $Q0 ) ) ) {
			$message = "$message - ��ƮwŪ�����~!!";
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
		}*/
		//�p�G�S����w�t�ҡ@��ܹw�]��
		if ( $groupid == NULL || $groupid == "" ) {
			$group_id = mysql_fetch_array(mysql_db_query( $DB, $Q0 ));
			$groupid = $group_id['a_id'];
		}
		
		//��Xcourse_id
		$Q1 = "select tc.course_id FROM course c, course_group cg, take_course tc , user u where u.id = '$user_id' and tc.student_id = u.a_id and c.a_id = tc.course_id and cg.a_id = c.group_id and tc.validated = '1' and c.group_id = '$groupid' order by cg.a_id";
		if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
			$message = "$message - ��ƮwŪ�����~!!";
		}
		else if ( mysql_num_rows( $result ) != 0 ) {
			$nottake = "";
			while ( $row1 = mysql_fetch_array( $result ) ) {
				$nottake = $nottake." and c.a_id != ".$row1['course_id'];
			}
		}
      	
		//���;Ǧ~���U�Ԧ����
		//���X�Ǧ~��
		$Q0 = "select distinct teach_course.year ,teach_course.term from this_semester, course, course_group, teach_course where  course_group.a_id =".$groupid." and course_group.a_id = course.group_id and course.a_id = teach_course.course_id order by year desc, term desc, course.course_no";
		if ( !($result0 = mysql_db_query( $DB, $Q0 ) ) ) {
			$message = "$message - ��ƮwŪ�����~!!";
		}		
		else if ( mysql_num_rows( $result0 ) != 0 ) {
			while ( $row0 = mysql_fetch_array( $result0 ) ) {
				if($row0['year']!=0){
					if ( $year_term == $row0['year']."_".$row0['term'] ) {
						$tpl->assign( Y_M , $row0['year']."_".$row0['term']." selected" );
					}
					else {
						$tpl->assign( Y_M , $row0['year']."_".$row0['term'] );
						//--��S��ܾǴ��ɡM�Ǵ����̷s�Ǵ�--9607 by jp
						if($year_term =="")
							$year_term=$row0['year']."_".$row0['term'];
					}
					$tpl->assign( Y_TERM , "��".$row0['year']."�Ǧ~��".$row0['term']."�Ǵ�" );
					$tpl->parse ( Y_LIST, ".year_list" );
				}
			}
		}
		else{
			$tpl->assign(Y_TERM , "�S������ҵ{��!!!");
			$tpl->parse ( Y_LIST, ".year_list" );
		}
		//-- ���o��Ǵ�
		$Q22 = "SELECT year,term FROM this_semester";
		if ( !($result22 = mysql_db_query( $DB, $Q22 ) ) ) {
			echo ("��ƮwŪ�����~!!$Q22");
			exit;
		}
		$row2 = mysql_fetch_array( $result22 );
		$this_year = $row2['year'];
		$this_term = $row2['term'];
		//--		
		//�p�G����w�~��
		if($year_term !="" || $year_term !=NULL)
		{	
			$realyt=explode("_",$year_term);
			$select['year']=$realyt[0]; //�I�諸�~�@
			$select['term']=$realyt[1]; //�I�諸�Ǵ�
			//$tpl->assign( NOW , "��".$row0['year']."�Ǧ~��".$row0['term']."�Ǵ�" );
			//echo $select['year']."...".$select['term']."<br>";
		}
		else{
		//�p�G�S����w�t�ҡ@��ܹw�]��
			$select['year']=$this_year; //�I�諸�~�@
			$select['term']=$this_term; //�I�諸�Ǵ�
		}	
        //��X�ҵ{��group_id �ҵ{�s�ժ�name �ҵ{��introduction �ҵ{��a_id �ҵ{��name...	
		$Q2 = "select distinct c.group_id, cg.name AS gname, c.introduction, c.a_id, c.name AS cname, c.validated, tc.year, tc.term FROM course c, course_group cg, teach_course tc where tc.year='".$select['year']."' and tc.term='".$select['term']."' and c.group_id = '$groupid' and c.a_id = tc.course_id and cg.a_id = c.group_id  $nottake  order by  tc.year DESC,cg.a_id, tc.term desc, tc.course_id";
		if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) ) {
			echo ("��ƮwŪ�����~2!!$Q2");
			exit;
		}
		if ( mysql_num_rows( $result2 ) != 0 ) {
			$count = 0;
			while ( $row = mysql_fetch_array( $result2 ) ) {
				$data[$count]["gname"] = $row["gname"];
				//isOld�ΨӧP�_�O�_�O�ª��ҵ{
				$isOld=0;
				//if(!($row['year']==$this_year && $row['term']==$this_term))
				if( ($row['year']*2+ $row['term']) < (($this_year*2 + $this_term))  )	//--�P�O1 term�ҵ{--jp9607
				//if( ($row['year']*2+ $row['term']) < (($this_year*2 + $this_term)-1)  )	//--�P�O1�~�e�ҵ{--jp9607
					$isOld="1";
				if( ($row["validated"]%2 == 1) ) {
					$data[$count]["cname"] = "<a href=# onClick=\"window.open('./intro.php?PHPSESSID=".session_id()."&year=".$row['year']."&term=".$row['term']."&courseid=".$row["a_id"]."&query=1&isOld=".$isOld."', '', 'resizable=1,scrollbars=1,width=640,height=480');\">".$row["cname"]."</a>";
					//if ( $version == "C" ) {
						$tpl->assign( CSTATUS , "���i��ť" );
					//}
					/*else {
						$tpl->assign( CSTATUS , "UnChoise" );
					}*/
				}
				else {
					$data[$count]["cname"] = "<a href=# onClick=\"window.open('./intro.php?PHPSESSID=".session_id()."&year=".$row['year']."&term=".$row['term']."&courseid=".$row["a_id"]."&query=1&isOld=".$isOld."', '', 'resizable=1,scrollbars=1,width=640,height=480');\">".$row["cname"]."</a>";
					//if ( $version == "C" )
						$tpl->assign( CSTATUS , "<a href=\"../login_s.php?courseid=". $row["a_id"] . "\" target=\"_top\">�i��ť</a>" );
					/*else
						$tpl->assign( CSTATUS , "<a href=\"../login_s.php?courseid=". $row["a_id"] . "\" target=\"_top\">Choise</a>" );
						*/
				}
				$name = "";
				$name2 = "";
				//$Q5 = "select u.id, u.name, u.nickname, u.a_id, u.authorization, tc.year, tc.term FROM user u , teach_course tc where tc.course_id = '".$row["a_id"]."' and tc.teacher_id = u.a_id and tc.year = '$row[year]' and tc.term = '$row[term]'";
				$Q5 = "select u.id, u.name, u.nickname, u.a_id, u.authorization, tc.year, tc.term FROM user u , teach_course tc where tc.year='".$select['year']."' and tc.term='".$select['term']."' and tc.course_id = '".$row["a_id"]."' and tc.teacher_id = u.a_id ";
				if ( !($result5 = mysql_db_query( $DB, $Q5 ) ) ) {
					$message = "$message - ��ƮwŪ�����~!!";
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
					$data[$count]["year"] = $row5[year]."�Ǧ~�ײ�".$row5[term]."�Ǵ�";
				}
				$course_no = "";
				$Q6 = "select course_no FROM course where a_id = '".$row["a_id"]."'";
				if ( !($result6 = mysql_db_query( $DB, $Q6 ) ) ) {
					$message = "$message - ��ƮwŪ�����~!!";
				}
				while ( $row6 = mysql_fetch_array( $result6 ) ) {
					$course_no .= $row6['course_no']." ";
				}
				$data[$count]["course_no"] = $course_no;
				$data[$count]["name"] = $name;
				$data[$count]["name2"] = $name2;
				$data[$count]["index"] = $data[$count]["year"].$course_no;
				$count++;
			}
//			$data = qsort_multiarray ( $data, "index", SORT_ASC );			
			for($i=0; $i<sizeof($data); $i++){
				if($data[$i]["name"]!=""){
					if ( $color == "#E6FFFC" )
						$color = "#F0FFEE";
					else
						$color = "#E6FFFC";
					$tpl->assign( COLOR , $color );					
					$tpl->assign( GNAME , $data[$i]["gname"] );
					$tpl->assign( CNAME , $data[$i]["cname"] );
					$tpl->assign( YEAR , $data[$i]["year"] );
					$tpl->assign( CNO , $data[$i]["course_no"] );
					$tpl->assign( CTEACH , $data[$i]["name"] );					
					//$tpl->assign( CTA , $data[$i]["name2"]  );
					$tpl->parse ( COURSE_LIST, ".course_list" );
					$tpl->assign( MES , "" );
					$tpl->parse ( TABLE_LIST, "table_list" );			
				}
			}
		}
		else {
			//if ( $version == "C" ){
				$tpl->assign( MES , "�ثe�S������ҵ{" );
				$tpl->assign(TABLE_TITLE," ");
			/*}else{
				$tpl->assign( MES , "There is no Course" );
				$tpl->assign( TABLE_TITLE , " " );
			}*/
		}
		//if ( $version == "C" ) {
			$tpl->assign( PATH , "img" );
		/*}
		else {
			$tpl->assign( PATH , "img_E" );
		}*/
		$Q3 = "select authorization from user where id='$user_id'";
		if ( !($result3 = mysql_db_query( $DB, $Q3 ) ) ) {
			echo ("��ƮwŪ�����~!!");
			exit;
		}
		$row3 = mysql_fetch_array ( $result3 );
		if ( $row3['authorization'] != "9" ) {
			$tpl->assign( BUTTON , "<a href=./guest_his.php target=\"_top\">�d�߾��~�}�Ҹ��</a>" );
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
