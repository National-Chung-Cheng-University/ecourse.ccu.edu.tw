<?	
/**
 * �����G�����G�ѱЮv�m�W�d�߸ӱЮv�}�Ҥ��ҵ{�j��
 * �ק�G�[�W�Ǧ~�Ǵ����M�W�[�[�Ǵ��~�ξ��v��ưѼƵ�intro.php--by.jp@960601
 */
	require 'fadmin.php';
	if (!isset($ver) && isset($PHPSESSID) && session_check_stu($PHPSESSID)) {
		session_unregister("teacher");
		session_unregister("admin");
		session_register("guest");
		$guest = 1;
		$user_name = $_GET[teacher_name];
		$Q1 = "select authorization FROM user where name = '$user_name'";
		if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
			$message = "$message - ��ƮwŪ�����~!!";
		}else
			$row = mysql_fetch_array( $result );
		//-- ���o��Ǵ�
		$Q22 = "SELECT year,term FROM this_semester";
		if ( !($result22 = mysql_db_query( $DB, $Q22 ) ) ) {
			echo ("��ƮwŪ�����~!!$Q22");
			exit;
		}
		$row2 = mysql_fetch_array( $result22 );
		$this_year = $row2['year'];
		$this_term = $row2['term'];
		show_page_d ( );
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
		header( "Location: http://$SERVER_NAME/php/Courses_Admin/guest2.php?PHPSESSID=".session_id());
	}

	function show_page_d ( $message="") {
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		global $version, $course_id, $skinnum;
		$tpl->define ( array ( body => "guest2.tpl" ) );
		$tpl->define_dynamic ( "course_list" , "body" );
		$tpl->define_dynamic ( "reset_list" , "body" );
		$tpl->assign( SKINNUM , $skinnum );
		$color = "#000066";
		$tpl->assign( COLOR , $color );

		if ( $version == "C" ) {
			$tpl->assign( CYEAR, "<font color =#FFFFFF>�Ǧ~</font>" );
			$tpl->assign( CTERM , "<font color =#FFFFFF>�Ǵ�</font>" );
			$tpl->assign( GNAME , "<font color =#FFFFFF>�}�ҳ��</font>" );
			$tpl->assign( CNO , "<font color =#FFFFFF>�ҵ{�s��</font>" );
			$tpl->assign( CNAME , "<font color =#FFFFFF>�ҵ{�W��</font>" );
			$tpl->assign( CTEACH , "<font color =#FFFFFF>�½ұЮv</font>" );
		
			//---------- 2009.06.23 �s�W��� -> �Ǥ��ơB�ݩʡB�W�Үɶ��B�W�Ҧa�I  by w60292  ------------------

                        $tpl->assign( CCREDIT , "<font color =#FFFFFF>�Ǥ���</font>" );
                        $tpl->assign( CATTRI , "<font color =#FFFFFF>�ݩ�</font>" );
                        $tpl->assign( CTIME , "<font color =#FFFFFF>�W�Үɶ�</font>" );
			$tpl->assign( CLOCAL , "<font color =#FFFFFF>�W�Ҧa�I</font>" );
			
			//-------------------------------------------------------------------------------------------------
		}
		else {
			$tpl->assign( CYEAR, "<font color =#FFFFFF>Year</font>" );
			$tpl->assign( CTERM , "<font color =#FFFFFF>Term</font>" );
			$tpl->assign( GNAME , "<font color =#FFFFFF>Department</font>" );
			$tpl->assign( CNO , "<font color =#FFFFFF>No.</font>" );
			$tpl->assign( CNAME , "<font color =#FFFFFF>Course Name</font>" );
			$tpl->assign( CTEACH , "<font color =#FFFFFF>Teachers</font>" );

			//---------- 2009.06.23 �s�W��� -> �Ǥ��ơB�ݩʡB�W�Үɶ��B�W�Ҧa�I  by w60292  ------------------

                        $tpl->assign( CCREDIT , "<font color =#FFFFFF>Credit</font>" );
                        $tpl->assign( CATTRI , "<font color =#FFFFFF>Attribute</font>" );
                        $tpl->assign( CTIME , "<font color =#FFFFFF>Course Time/font>" );
                        $tpl->assign( CLOCAL , "<font color =#FFFFFF>Course Location</font>" );

                        //-------------------------------------------------------------------------------------------------
		}
		$tpl->parse ( COURSE_LIST, ".course_list" );
		$color = "#E6FFFC";

		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $user_name,$this_year,$this_term;
		$Q1 = "select c.group_id, cg.name AS gname, tc.course_id, tc.year as cyear, tc.term as cterm, c.name AS cname FROM course c, course_group cg, teach_course tc , user u where u.name = '$user_name' and tc.teacher_id = u.a_id and c.a_id = tc.course_id and cg.a_id = c.group_id order by tc.year desc, tc.term";
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$message = "$message - ��Ʈw�s�����~!!";
		}
		$D1 = "delete from online where user_id = '$user_id'";
		//mysql_db_query( $DB, $D1 );
		if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
			$message = "$message - ��ƮwŪ�����~!!";
		}
		else if ( mysql_num_rows( mysql_db_query( $DB, $Q1 ) ) != 0 ) {	
			$tpl->assign( HRLINE , "<hr>" );		
			while ( $row = mysql_fetch_array( $result ) ) {
				if ( $color == "#E6FFFC" )
					$color = "#F0FFEE";
				else
					$color = "#E6FFFC";
				
				$tpl->assign( COLOR , $color );				
				$tpl->assign( CYEAR , $row["cyear"] );
				$tpl->assign( CTERM , $row["cterm"] );
				$tpl->assign( GNAME , $row["gname"] );
				//isOld�ΨӧP�_�O�_�O�ª��ҵ{
				$isOld=0;
				//echo $row["cyear"];
				//96.12.12 mark 104�� by Jim  add 105�� by Jim �ت��O�n���ӾǦ~��2�Ǵ���ܥ��T����T
				if(!($row["cyear"]==$this_year && $row["cterm"]==$this_term))	
				//if(!($row["cyear"]==$this_year))				
					$isOld="1";	
				//100.6.9 add by Jim  �]���n����ܤU�Ǧ~���ҵ{�j���A�ҥH�[�J127~128��{���X
				if( $row["cyear"]=='100')				
					$isOld="0";	
				$tpl->assign( CNAME ,"<a href=# onClick=\"window.open('./intro.php?PHPSESSID=".session_id()."&year=".$row['cyear']."&term=".$row['cterm']."&courseid=".$row["course_id"]."&query=1&isOld=".$isOld."', '', 'resizable=1,scrollbars=1,width=640,height=480');\">".$row["cname"]."</a>");
				$name = "";
				$Q2 = "select u.id, u.name, u.nickname, u.a_id, u.php FROM user u , teach_course tc where tc.course_id = '".$row["course_id"]."' and tc.year=".$row["cyear"]." and tc.term=".$row["cterm"]." and tc.teacher_id = u.a_id and u.authorization='1'";
				if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) ) {
					$message = "$message - ��ƮwŪ�����~!!";
				}
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
				$course_no = "";
				$Q3 = "select course_no FROM course where a_id = '".$row["course_id"]."'";
				if ( !($result3 = mysql_db_query( $DB, $Q3 ) ) ) {
					$message = "$message - ��ƮwŪ�����~!!";
				}
				while ( $row3 = mysql_fetch_array( $result3 ) ) {
					$course_no .= $row3['course_no']." ";
				}

				//---------- 2009.06.23 �s�W��� -> �Ǥ��ơB�ݩʡB�W�Үɶ��B�W�Ҧa�I  by w60292  ------------------

        $cno_tmp = strtok ( $course_no , "_");
        $class_tmp = strtok (" ");

        // �s��sybase
        //if( !($cnx = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) ){
        //        Error_handler( "�b sybase_connect �����~�o��" , $cnx );
        //}
        //$csd = @sybase_select_db("academic", $cnx);
	      $c_id = $cno_tmp;
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
        $tpl->assign( CCREDIT , $array001['credit'] );

        //�ݩ�
        $Q002 = "select curcateg from a31vcurriculum_tea where cour_cd = '".$cno_tmp."'";
        //$cur002 = sybase_query($Q002 , $cnx );
        $cur002 = pg_query($cnx, $Q002) or die('��ƪ��s�b�A�гq���q�⤤��');
        //$array002 = sybase_fetch_array($cur002);
        $array002 = pg_fetch_array($cur002, null, PGSQL_ASSOC);
        switch($array002['curcateg']){
                case'1': $tpl->assign( CATTRI , "����" ); break;
                case'2': $tpl->assign( CATTRI , "���" ); break;
                case'3': $tpl->assign( CATTRI , "�q��" ); break;
                default: $tpl->assign( CATTRI , "    " ); break;
        }

				//�W�Үɶ�
        $Q003 = "select distinct week,knot from a31vschedule_tea where cour_cd = '".$cno_tmp."' and grp = '".$class_tmp."' order by week";
        //$cur003 = sybase_query($Q003 , $cnx );
        $cur003 = pg_query($cnx, $Q003) or die('��ƪ��s�b�A�гq���q�⤤��');
        $pre_week = " ";
        $flag = 0;
        $ctime = "";
        //while ($array003 = sybase_fetch_array($cur003)){
        while ($array003 = pg_fetch_array($cur003, null, PGSQL_ASSOC) ){
                if(strcmp($array003["week"],$pre_week) != 0){
                        if($flag == 1)
                                $ctime = $ctime." ";
                        $flag = 1;
                        switch($array003["week"]){
                                case'1': $ctime = $ctime."�@".$array003["knot"];
                                         break;
                                case'2': $ctime = $ctime."�G".$array003["knot"];
                                         break;
                                case'3': $ctime = $ctime."�T".$array003["knot"];
                                         break;
                                case'4': $ctime = $ctime."�|".$array003["knot"];
                                         break;
                                case'5': $ctime = $ctime."��".$array003["knot"];
                                         break;
                                case'6': $ctime = $ctime."��".$array003["knot"];
                                         break;
                                case'7': $ctime = $ctime."��".$array003["knot"];
                                         break;
                        }
                }
                else{
                        $ctime = $ctime.",".$array003["knot"];
                }
                $pre_week = $array003["week"];
        }

				//�W�Ҧa�I
				$Q004 = "select room_cd from a31vschedule_tea where cour_cd = '".$cno_tmp."' and grp = '".$class_tmp."'";
        //$cur004 = sybase_query($Q004 , $cnx );
        $cur004 = pg_query($cnx, $Q004) or die('��ƪ��s�b�A�гq���q�⤤��');
        //$array004 = sybase_fetch_array($cur004);
        $array004 = pg_fetch_array($cur004, null, PGSQL_ASSOC);
        $rcd = $array004["room_cd"];
        $Q005 = "select name from a30troom where no = '".$rcd."'";
        //$cur005 = sybase_query($Q005 , $cnx );
        $cur005 = pg_query($cnx, $Q005) or die('��ƪ��s�b�A�гq���q�⤤��');
        //$array005 = sybase_fetch_array($cur005);
        $array005 = pg_fetch_array($cur005, null, PGSQL_ASSOC);
        $clocal = $array005["name"];
        //sybase_close( $cnx);
        pg_close( $cnx);

				$tpl->assign( CNO , $course_no );
				$tpl->assign( CTEACH , $name );
				//2009.07.10 �]�����δH������,�оǲթ|�b�e���Z��,�ҥH���న�W�N�Ǵ��]�w�ק�
				//�ݭקאּ���T���Ǵ���,�A�ӭק說��239��
				//if($row["cyear"]==$this_year && $row["cterm"]==$this_term)
				//echo $this_year."<br>";
				if( $row["cyear"]==99  )
				{
					$tpl->assign( CTIME , $ctime );
					$tpl->assign( CLOCAL , $clocal );
				} else
				{
					$tpl->assign( CTIME , '' );
					$tpl->assign( CLOCAL , '' );
				}
				
				$tpl->parse ( COURSE_LIST, ".course_list" );
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
		$tpl->parse( BODY, "body" );
		$tpl->FastPrint("BODY");
	}
?>
