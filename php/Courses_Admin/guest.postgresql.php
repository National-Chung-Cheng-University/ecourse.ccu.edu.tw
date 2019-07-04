<?php
  //-------------------------------------------------------//
  //所有跟English version都註解掉了
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
		header( "Location: http://$SERVER_NAME/php/Courses_Admin/guest.php?groupid=$groupid&PHPSESSID=".session_id());
	}
	if ( $frame != 1 ) {
		//linsy@20111017, 過濾xss攻擊，讓groupid需為int
		$groupid = (int)$groupid;
		if($groupid == 0)
		{
		echo "
			<script language='javascript'>
			<!--
			     //alert('請勿輸入不合法字元');
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
			$tpl->assign( GNAME , "<font color =#FFFFFF>開課單位</font>" );
			$tpl->assign( YEAR, "<font color =#FFFFFF>開課學年度</font>");
			$tpl->assign( CNO , "<font color =#FFFFFF>課程編號</font>" );
			$tpl->assign( CNAME , "<font color =#FFFFFF>課程名稱及介紹</font>" );
			$tpl->assign( CTEACH , "<font color =#FFFFFF>授課教師</font>" );

	    //---------- 2008.05.05 新增欄位 -> 學分數、屬性、上課時間  by w60292  ------------------

			$tpl->assign( CCREDIT , "<font color =#FFFFFF>學分數</font>" );
			$tpl->assign( CATTRI , "<font color =#FFFFFF>屬性</font>" );
			$tpl->assign( CTIME , "<font color =#FFFFFF>上課時間</font>" );

      /***************************************************************************************/
     //---------- 2011.03.17 新增欄位 -> 核心能力  by Jim  ------------------
      $tpl->assign( CGDEPT , "<font color =#FFFFFF>核心能力</font>" );
     /***************************************************************************************/

			//$tpl->assign( CTA , "<font color =#FFFFFF>隨課助教</font>" );
			$tpl->assign( CSTATUS , "<font color =#FFFFFF>開課狀態</font>" );
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
			$message = "$message - 資料庫連結錯誤!!";
		}
		
		//如果沒有選定系所　顯示預設的
		if ( $groupid == NULL || $groupid == "" ) {
			$group_id = mysql_fetch_array(mysql_db_query( $DB, $Q0 ));
			$groupid = $group_id['a_id'];
		}
		//-- 取得當學期 96.12.07 由 jim 新增 79-86 行 目的是為了要讓下拉式選單預設顯示為當學期
		$Q22 = "SELECT year,term FROM this_semester";
		if ( !($result22 = mysql_db_query( $DB, $Q22 ) ) ) {
			echo ("資料庫讀取錯誤!!$Q22");
			exit;
		}
		$row2 = mysql_fetch_array( $result22 );
		if($year_term == "") $year_term = $row2['year']."_".$row2['term'];
				      	
		//產生學年的下拉式選單
		//取出學年度
		$Q0 = "select distinct teach_course.year ,teach_course.term from this_semester, course, course_group, teach_course where  course_group.a_id =".$groupid." and course_group.a_id = course.group_id and course.a_id = teach_course.course_id order by year desc, term desc, course.course_no";
		if ( !($result0 = mysql_db_query( $DB, $Q0 ) ) ) {
			$message = "$message - 資料庫讀取錯誤!!";
		}		
		else if ( mysql_num_rows( $result0 ) != 0 ) {
			while ( $row0 = mysql_fetch_array( $result0 ) ) {
				if($row0['year']!=0){
					if ( $year_term == $row0['year']."_".$row0['term'] ) {
						$tpl->assign( Y_M , $row0['year']."_".$row0['term']." selected" );
					}
					else {
						$tpl->assign( Y_M , $row0['year']."_".$row0['term'] );
						//--當沒選擇學期時﹐學期為最新學期--9607 by jp
						if($year_term =="")
							$year_term=$row0['year']."_".$row0['term'];
						if(mysql_num_rows($result0) == 1) 
						  $year_term=$row0['year']."_".$row0['term'];	
					}
					$tpl->assign( Y_TERM , "第".$row0['year']."學年第".$row0['term']."學期" );
					$tpl->parse ( Y_LIST, ".year_list" );
				}
			}
		}
		else{
			$tpl->assign(Y_TERM , "沒有任何課程喔!!!");
			$tpl->parse ( Y_LIST, ".year_list" );
		}
		//-- 取得當學期 96.12.07 mark by jim from 114-119 行
		//$Q22 = "SELECT year,term FROM this_semester";
		//if ( !($result22 = mysql_db_query( $DB, $Q22 ) ) ) {
		//	echo ("資料庫讀取錯誤!!$Q22");
		//	exit;
		//}
		//$row2 = mysql_fetch_array( $result22 );
		$this_year = $row2['year'];
		$this_term = $row2['term'];
		//--		
		//如果有選定年度
		if($year_term !="" || $year_term !=NULL)
		{	
			$realyt=explode("_",$year_term);
			$select['year']=$realyt[0]; //點選的年　
			$select['term']=$realyt[1]; //點選的學期
			//$tpl->assign( NOW , "第".$row0['year']."學年第".$row0['term']."學期" );
			//echo $select['year']."...".$select['term']."<br>";
		}
		else{
		//如果沒有選定系所　顯示預設的
			$select['year']=$this_year; //點選的年　
			$select['term']=$this_term; //點選的學期
		}	
        //選出課程的group_id 課程群組的name 課程的introduction 課程的a_id 課程的name...	
//		$Q2 = "select distinct c.group_id, c.course_no, cg.name AS gname, c.a_id, c.name AS cname, tc.year, tc.term FROM course c, course_group cg, teach_course tc where tc.year='".$select['year']."' and tc.term='".$select['term']."' and c.group_id = '$groupid' and c.a_id = tc.course_id and cg.a_id = c.group_id order by tc.course_id";
//change sort by chiefboy1230
		$Q2 = "select distinct c.group_id, c.course_no, cg.name AS gname, c.a_id, c.name AS cname, tc.year, tc.term, cg.deptcd AS cgdept FROM course c, course_group cg, teach_course tc where tc.year='".$select['year']."' and tc.term='".$select['term']."' and c.group_id = '$groupid' and c.a_id = tc.course_id and cg.a_id = c.group_id order by c.course_no";
		//若為一年前課程﹐需從歷史記錄中捉取課程名稱等資訊 --jp-960929
		if( ($select['year']*2+ $select['term']) < (($this_year*2 + $this_term)-1)  )	//--判別1 year課程--jp9607
//			$Q2 = "select distinct c.group_id, c.course_no, cg.name AS gname, c.a_id, c.name AS cname, tc.year, tc.term FROM hist_course c, course_group cg, teach_course tc where tc.year='".$select['year']."' and tc.term='".$select['term']."' and c.group_id = '$groupid' and c.a_id = tc.course_id and cg.a_id = c.group_id order by tc.course_id";
//			$Q2 = "select distinct c.group_id, c.course_no, cg.name AS gname, c.a_id, c.name AS cname, tc.year, tc.term FROM hist_course c, course_group cg, teach_course tc where c.year='".$select['year']."' and c.term='".$select['term']."' and c.group_id = '$groupid' and c.a_id = tc.course_id and cg.a_id = c.group_id order by tc.course_id";
			$Q2 = "select distinct c.group_id, c.course_no, cg.name AS gname, c.a_id, c.name AS cname, c.year, c.term, cg.deptcd AS cgdept FROM hist_course c, course_group cg, teach_course tc where c.year='".$select['year']."' and c.term='".$select['term']."' and c.group_id = '$groupid' and c.a_id = tc.course_id and cg.a_id = c.group_id order by tc.course_id";
		if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) ) {
			echo ("資料庫讀取錯誤2!!$Q2");
			exit;
		}
		if ( mysql_num_rows( $result2 ) != 0 ) {
			$count = 0;
			while ( $row = mysql_fetch_array( $result2 ) ) {
				$data[$count]["gname"] = $row["gname"];
				//isOld用來判斷是否是舊的課程
				$isOld=0;
				//if(!($row['year']==$this_year && $row['term']==$this_term))


				// modify by chiefboy1230, 判斷若this_semester的year、term兩個值皆不等於選擇的year、term則為舊課程	
				//本174~175 mark起來,因無法先顯示下學期開課大綱(如98/01學期未,要先顯示98/02課程大綱),所以改mark 179~180
				//if( ($row['year']*2+ $row['term']) < (($this_year*2 + $this_term))  )	//--判別1 term課程--jp9607
				//	$isOld="1";
				
				//add by chiefboy1230
				// 174~175 和 179~180 互為交換備註
				/*if($row2['year']!=$select['year'] || $row2['term']!=$select['term'])
					$isOld="1";*/
					
				
				$data[$count]["cname"] = "<a href=# onClick=\"window.open('./intro.php?PHPSESSID=".session_id()."&year=".$row['year']."&term=".$row['term']."&courseid=".$row["a_id"]."&query=1&isOld=".$isOld."', '', 'resizable=1,scrollbars=1,width=640,height=480');\">".$row["cname"]."</a>";
					
				$name = "";
				$name2 = "";
				//$Q5 = "select u.id, u.name, u.nickname, u.a_id, u.authorization, tc.year, tc.term FROM user u , teach_course tc where tc.course_id = '".$row["a_id"]."' and tc.teacher_id = u.a_id and tc.year = '$row[year]' and tc.term = '$row[term]'";
				$Q5 = "select u.id, u.name, u.nickname, u.a_id, u.authorization, tc.year, tc.term FROM user u , teach_course tc where tc.year='".$select['year']."' and tc.term='".$select['term']."' and tc.course_id = '".$row["a_id"]."' and tc.teacher_id = u.a_id ";
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
					$data[$count]["year"] = $row5[year]."學年度第".$row5[term]."學期";
				}
				$data[$count]["course_no"] = $row["course_no"];
				$data[$count]["name"] = $name;
				$data[$count]["name2"] = $name2;
				$data[$count]["index"] = $data[$count]["year"].$row["course_no"];

			//---------- 2008.05.05 新增欄位 -> 學分數、屬性、上課時間  by w60292  ------------------

				$cno_tmp = strtok ($row["course_no"],"_"); 
				$class_tmp = strtok (" ");

				// 連結sybase
        //if( !($cnx = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) ){
        //   		Error_handler( "在 sybase_connect 有錯誤發生" , $cnx );
        //}
        ////$csd = @sybase_select_db("academic", $cnx);
	      $c_id = $row["course_no"];
	      if ( substr( $c_id, 3, 1 ) == "A" || substr( $c_id, 3, 1 ) == "B" || substr( $c_id, 3, 1 ) == "C" || substr( $c_id, 3, 1 ) == "D" )
		       $SDB = "academic_gra";
	      else
		       $SDB = "academic";
        //$csd = @sybase_select_db($SDB, $cnx);
				$conn_string = "host=140.123.30.12 dbname=".$SDB." user=acauser password=!!acauser13";
				$cnx = pg_pconnect($conn_string) or die('資料庫沒有回應，請稍後再試');
        
				//學分數
				$Q001 = "select credit from a30vcourse_tea where course_no = '".$cno_tmp."'";
        //$cur001 = sybase_query($Q001 , $cnx );
        $cur001 = pg_query($cnx, $Q001) or die('資料表不存在，請通知電算中心');
				//$array001 = sybase_fetch_array($cur001);
				$array001 = pg_fetch_array($cur001, null, PGSQL_ASSOC);
				$data[$count]["credit"] = $array001["credit"];

				//屬性
				$Q002 = "select curcateg from a31vcurriculum_tea where cour_cd = '".$cno_tmp."'";
				//$cur002 = sybase_query($Q002 , $cnx );
				$cur002 = pg_query($cnx, $Q002) or die('資料表不存在，請通知電算中心');
				//$array002 = sybase_fetch_array($cur002);
				$array002 = pg_fetch_array($cur002, null, PGSQL_ASSOC);
				switch($array002["curcateg"]){
					case'1': $data[$count]["attri"] = "必修"; break;
					case'2': $data[$count]["attri"] = "選修"; break;
					case'3': $data[$count]["attri"] = "通識"; break;
					default: $data[$count]["attri"] = "    "; break;
				}

				//上課時間

				$Q003 = "select distinct week,knot from a31vschedule_tea where cour_cd = '".$cno_tmp."' and grp = '".$class_tmp."' order by week";
				//$cur003 = sybase_query($Q003 , $cnx );
				$cur003 = pg_query($cnx, $Q003) or die('資料表不存在，請通知電算中心');
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
							case'1': $data[$count]["time"] = $data[$count]["time"]."一".$array003["knot"];
								 break;
							case'2': $data[$count]["time"] = $data[$count]["time"]."二".$array003["knot"];
								 break;
							case'3': $data[$count]["time"] = $data[$count]["time"]."三".$array003["knot"];
								 break;
							case'4': $data[$count]["time"] = $data[$count]["time"]."四".$array003["knot"];
								 break;
							case'5': $data[$count]["time"] = $data[$count]["time"]."五".$array003["knot"];
								 break;
			        case'6': $data[$count]["time"] = $data[$count]["time"]."六".$array003["knot"];
								 break;
						  case'7': $data[$count]["time"] = $data[$count]["time"]."日".$array003["knot"];
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
			//----------- 2011.3.16 新增核心能力  JIM ------------------------------
				$data[$count]["cgdept"] = "<a href=# onClick=\"window.open('http://coursemap.ccu.edu.tw/dept_cour_cap_map.php?deptcd=".$row['cgdept']."', '', 'resizable=1,scrollbars=1,width=640,height=480');\">"."核心能力"."</a>";					
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

			//---------- 2008.05.05 新增欄位 -> 學分數、屬性、上課時間  by w60292  ------------------

		               	 	$tpl->assign( CCREDIT , $data[$i]["credit"] );
					$tpl->assign( CATTRI , $data[$i]["attri"] );
					$tpl->assign( CTIME , $data[$i]["time"] );

			//---------- 2011.03.07 新增欄位 -> 核心能力  by Jim  ------------------
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
				$tpl->assign( MES , "目前沒有任何課程" );
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
			echo ("資料庫讀取錯誤!!");
			exit;
		}
		$row3 = mysql_fetch_array ( $result3 );
		if ( $row3['authorization'] != "9" ) {
			$tpl->assign( BUTTON , "<a href=./guest_his.php target=\"_top\">查詢歷年開課資料</a>" );
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
