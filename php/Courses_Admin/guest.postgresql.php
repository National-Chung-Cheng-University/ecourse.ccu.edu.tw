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
		//linsy@20111017, �L�oxss�����A��groupid�ݬ�int
		$groupid = (int)$groupid;
		if($groupid == 0)
		{
		echo "
			<script language='javascript'>
			<!--
			     //alert('�Фſ�J���X�k�r��');
			     window.location.href ='./show_guest.php';
			//-->
			</script>
		";
		}
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

	    //---------- 2008.05.05 �s�W��� -> �Ǥ��ơB�ݩʡB�W�Үɶ�  by w60292  ------------------

			$tpl->assign( CCREDIT , "<font color =#FFFFFF>�Ǥ���</font>" );
			$tpl->assign( CATTRI , "<font color =#FFFFFF>�ݩ�</font>" );
			$tpl->assign( CTIME , "<font color =#FFFFFF>�W�Үɶ�</font>" );

      /***************************************************************************************/
     //---------- 2011.03.17 �s�W��� -> �֤߯�O  by Jim  ------------------
      $tpl->assign( CGDEPT , "<font color =#FFFFFF>�֤߯�O</font>" );
     /***************************************************************************************/

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
		
		//�p�G�S����w�t�ҡ@��ܹw�]��
		if ( $groupid == NULL || $groupid == "" ) {
			$group_id = mysql_fetch_array(mysql_db_query( $DB, $Q0 ));
			$groupid = $group_id['a_id'];
		}
		//-- ���o��Ǵ� 96.12.07 �� jim �s�W 79-86 �� �ت��O���F�n���U�Ԧ����w�]��ܬ���Ǵ�
		$Q22 = "SELECT year,term FROM this_semester";
		if ( !($result22 = mysql_db_query( $DB, $Q22 ) ) ) {
			echo ("��ƮwŪ�����~!!$Q22");
			exit;
		}
		$row2 = mysql_fetch_array( $result22 );
		if($year_term == "") $year_term = $row2['year']."_".$row2['term'];
				      	
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
						if(mysql_num_rows($result0) == 1) 
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
		//-- ���o��Ǵ� 96.12.07 mark by jim from 114-119 ��
		//$Q22 = "SELECT year,term FROM this_semester";
		//if ( !($result22 = mysql_db_query( $DB, $Q22 ) ) ) {
		//	echo ("��ƮwŪ�����~!!$Q22");
		//	exit;
		//}
		//$row2 = mysql_fetch_array( $result22 );
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
//		$Q2 = "select distinct c.group_id, c.course_no, cg.name AS gname, c.a_id, c.name AS cname, tc.year, tc.term FROM course c, course_group cg, teach_course tc where tc.year='".$select['year']."' and tc.term='".$select['term']."' and c.group_id = '$groupid' and c.a_id = tc.course_id and cg.a_id = c.group_id order by tc.course_id";
//change sort by chiefboy1230
		$Q2 = "select distinct c.group_id, c.course_no, cg.name AS gname, c.a_id, c.name AS cname, tc.year, tc.term, cg.deptcd AS cgdept FROM course c, course_group cg, teach_course tc where tc.year='".$select['year']."' and tc.term='".$select['term']."' and c.group_id = '$groupid' and c.a_id = tc.course_id and cg.a_id = c.group_id order by c.course_no";
		//�Y���@�~�e�ҵ{�M�ݱq���v�O���������ҵ{�W�ٵ���T --jp-960929
		if( ($select['year']*2+ $select['term']) < (($this_year*2 + $this_term)-1)  )	//--�P�O1 year�ҵ{--jp9607
//			$Q2 = "select distinct c.group_id, c.course_no, cg.name AS gname, c.a_id, c.name AS cname, tc.year, tc.term FROM hist_course c, course_group cg, teach_course tc where tc.year='".$select['year']."' and tc.term='".$select['term']."' and c.group_id = '$groupid' and c.a_id = tc.course_id and cg.a_id = c.group_id order by tc.course_id";
//			$Q2 = "select distinct c.group_id, c.course_no, cg.name AS gname, c.a_id, c.name AS cname, tc.year, tc.term FROM hist_course c, course_group cg, teach_course tc where c.year='".$select['year']."' and c.term='".$select['term']."' and c.group_id = '$groupid' and c.a_id = tc.course_id and cg.a_id = c.group_id order by tc.course_id";
			$Q2 = "select distinct c.group_id, c.course_no, cg.name AS gname, c.a_id, c.name AS cname, c.year, c.term, cg.deptcd AS cgdept FROM hist_course c, course_group cg, teach_course tc where c.year='".$select['year']."' and c.term='".$select['term']."' and c.group_id = '$groupid' and c.a_id = tc.course_id and cg.a_id = c.group_id order by tc.course_id";
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


				// modify by chiefboy1230, �P�_�Ythis_semester��year�Bterm��ӭȬҤ������ܪ�year�Bterm�h���½ҵ{	
				//��174~175 mark�_��,�]�L�k����ܤU�Ǵ��}�Ҥj��(�p98/01�Ǵ���,�n�����98/02�ҵ{�j��),�ҥH��mark 179~180
				//if( ($row['year']*2+ $row['term']) < (($this_year*2 + $this_term))  )	//--�P�O1 term�ҵ{--jp9607
				//	$isOld="1";
				
				//add by chiefboy1230
				// 174~175 �M 179~180 �����洫�Ƶ�
				/*if($row2['year']!=$select['year'] || $row2['term']!=$select['term'])
					$isOld="1";*/
					
				
				$data[$count]["cname"] = "<a href=# onClick=\"window.open('./intro.php?PHPSESSID=".session_id()."&year=".$row['year']."&term=".$row['term']."&courseid=".$row["a_id"]."&query=1&isOld=".$isOld."', '', 'resizable=1,scrollbars=1,width=640,height=480');\">".$row["cname"]."</a>";
					
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
				$data[$count]["course_no"] = $row["course_no"];
				$data[$count]["name"] = $name;
				$data[$count]["name2"] = $name2;
				$data[$count]["index"] = $data[$count]["year"].$row["course_no"];

			//---------- 2008.05.05 �s�W��� -> �Ǥ��ơB�ݩʡB�W�Үɶ�  by w60292  ------------------

				$cno_tmp = strtok ($row["course_no"],"_"); 
				$class_tmp = strtok (" ");

				// �s��sybase
        //if( !($cnx = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) ){
        //   		Error_handler( "�b sybase_connect �����~�o��" , $cnx );
        //}
        ////$csd = @sybase_select_db("academic", $cnx);
	      $c_id = $row["course_no"];
	      if ( substr( $c_id, 3, 1 ) == "A" || substr( $c_id, 3, 1 ) == "B" || substr( $c_id, 3, 1 ) == "C" || substr( $c_id, 3, 1 ) == "D" )
		       $SDB = "academic_gra";
	      else
		       $SDB = "academic";
        //$csd = @sybase_select_db($SDB, $cnx);
				$conn_string = "host=140.123.30.12 dbname=".$SDB." user=acauser password=!!acauser13";
				$cnx = pg_pconnect($conn_string) or die('��Ʈw�S���^���A�еy��A��');
        
				//�Ǥ���
				$Q001 = "select credit from a30vcourse_tea where course_no = '".$cno_tmp."'";
        //$cur001 = sybase_query($Q001 , $cnx );
        $cur001 = pg_query($cnx, $Q001) or die('��ƪ��s�b�A�гq���q�⤤��');
				//$array001 = sybase_fetch_array($cur001);
				$array001 = pg_fetch_array($cur001, null, PGSQL_ASSOC);
				$data[$count]["credit"] = $array001["credit"];

				//�ݩ�
				$Q002 = "select curcateg from a31vcurriculum_tea where cour_cd = '".$cno_tmp."'";
				//$cur002 = sybase_query($Q002 , $cnx );
				$cur002 = pg_query($cnx, $Q002) or die('��ƪ��s�b�A�гq���q�⤤��');
				//$array002 = sybase_fetch_array($cur002);
				$array002 = pg_fetch_array($cur002, null, PGSQL_ASSOC);
				switch($array002["curcateg"]){
					case'1': $data[$count]["attri"] = "����"; break;
					case'2': $data[$count]["attri"] = "���"; break;
					case'3': $data[$count]["attri"] = "�q��"; break;
					default: $data[$count]["attri"] = "    "; break;
				}

				//�W�Үɶ�

				$Q003 = "select distinct week,knot from a31vschedule_tea where cour_cd = '".$cno_tmp."' and grp = '".$class_tmp."' order by week";
				//$cur003 = sybase_query($Q003 , $cnx );
				$cur003 = pg_query($cnx, $Q003) or die('��ƪ��s�b�A�гq���q�⤤��');
				$pre_week = " ";
				$flag = 0;
				$data[$count]["time"] = "";
				//while ($array003 = sybase_fetch_array($cur003)){
				while ($array003 = pg_fetch_array($cur003, null, PGSQL_ASSOC) ){	
					//echo $array003["week"]." ".$array003["knot"]."<br>";
					if(strcmp($array003["week"],$pre_week) != 0){
						if($flag == 1)
							$data[$count]["time"] = $data[$count]["time"]." ";
						$flag = 1;
						switch($array003["week"]){
							case'1': $data[$count]["time"] = $data[$count]["time"]."�@".$array003["knot"];
								 break;
							case'2': $data[$count]["time"] = $data[$count]["time"]."�G".$array003["knot"];
								 break;
							case'3': $data[$count]["time"] = $data[$count]["time"]."�T".$array003["knot"];
								 break;
							case'4': $data[$count]["time"] = $data[$count]["time"]."�|".$array003["knot"];
								 break;
							case'5': $data[$count]["time"] = $data[$count]["time"]."��".$array003["knot"];
								 break;
			        case'6': $data[$count]["time"] = $data[$count]["time"]."��".$array003["knot"];
								 break;
						  case'7': $data[$count]["time"] = $data[$count]["time"]."��".$array003["knot"];
								 break;
						}
					}
					else{
						$data[$count]["time"] = $data[$count]["time"].",".$array003["knot"];
					}
					$pre_week = $array003["week"];
				}
				
				//sybase_close( $cnx);
				pg_close( $cnx);

			/***************************************************************************************/
			//----------- 2011.3.16 �s�W�֤߯�O  JIM ------------------------------
				$data[$count]["cgdept"] = "<a href=# onClick=\"window.open('http://coursemap.ccu.edu.tw/dept_cour_cap_map.php?deptcd=".$row['cgdept']."', '', 'resizable=1,scrollbars=1,width=640,height=480');\">"."�֤߯�O"."</a>";					
		  //**************************************************************************************/
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

			//---------- 2008.05.05 �s�W��� -> �Ǥ��ơB�ݩʡB�W�Үɶ�  by w60292  ------------------

		               	 	$tpl->assign( CCREDIT , $data[$i]["credit"] );
					$tpl->assign( CATTRI , $data[$i]["attri"] );
					$tpl->assign( CTIME , $data[$i]["time"] );

			//---------- 2011.03.07 �s�W��� -> �֤߯�O  by Jim  ------------------
					$tpl->assign( CGDEPT , $data[$i]["cgdept"] );

			/***************************************************************************************/

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
