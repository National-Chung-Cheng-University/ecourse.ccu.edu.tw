<?
require 'fadmin.php';
	if (!(isset($PHPSESSID) && session_check_stu($PHPSESSID)) ) {
		show_page( "not_access.tpl" ,"權限錯誤");
	}
	
	else {
		global $courseid, $action;
		$Q1 = "select authorization FROM user where id = '$user_id'";
		if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
			$message = "$message - 資料庫讀取錯誤!!";
		}else
			$row = mysql_fetch_array( $result );

		if ( $row['authorization'] == "4" ) {
			if($action == "showintro"){
				if(!session_is_registered("course_id")){
					session_register("course_id");
				}
				$course_id = $courseid;
				header( "Location: intro.php?PHPSESSID=".session_id());
			}
			else if($action == "upload_material"){
				if(!session_is_registered("course_id")){
					session_register("course_id");
				}
				$course_id = $courseid;
				header( "Location: ../textbook/Upload_main.php?PHPSESSID=".session_id());
			}
			else if($action == "edit_material"){
				if(!session_is_registered("course_id")){
					session_register("course_id");
				}
				$course_id = $courseid;
				header( "Location: ../textbook/editor.php?PHPSESSID=".session_id());
			}
			else if($action == "preview_material"){
				if(!session_is_registered("course_id")){
					session_register("course_id");
				}
				$course_id = $courseid;
				header( "Location: ../textbook/material.php?PHPSESSID=".session_id());
			}
			else if($action == "import_material"){
				if(!session_is_registered("course_id")){
					session_register("course_id");
				}
				$course_id = $courseid;
				header( "Location: ../textbook/import2.php?PHPSESSID=".session_id());
			}
			else if($action == "upload_old_intro"){
				if(!session_is_registered("course_id")){
					session_register("course_id");
				}
				$course_id = $courseid;
				header( "Location: ./upload_old_intro.php?PHPSESSID=".session_id()."&course_id=".$course_id."&year=".$year."&term=".$term);
			}
			else if($action == "show_old_intro")
			{
				$content="";
				if ( is_file("../../echistory/$year/$term/$id/intro/index.html") ) {
						$fp = fopen("../../echistory/$year/$term/$id/intro/index.html", "r");
						$content = fread($fp , filesize("../../echistory/$year/$term/$id/intro/index.html"));
						fclose($fp);

				}
				else if ( is_file("../../echistory/$year/$term/$id/intro/index.htm") ) {

						$fp = fopen("../../echistory/$year/$term/$id/intro/index.htm", "r");
						$content = fread($fp , filesize("../../echistory/$year/$term/$id/intro/index.htm"));
						fclose($fp);;
				}
				else if ( is_file("../../echistory/$year/$term/$id/intro/index.doc") ){
					//global $check, $version, $course_id, $teacher;
					$content = "<HTML>\n<HEAD>\n<TITLE>授課大綱</TITLE>\n".
								"<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=../../echistory/$year/$term/$id/intro/index.doc\">\n".
								"</HEAD>\n</HTML>";	
				}
				else if ( is_file("../../echistory/$year/$term/$id/intro/index.pdf") ){
					//global $check, $version, $course_id, $teacher;
					$content = "<HTML>\n<HEAD>\n<TITLE>授課大綱</TITLE>\n".
								"<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=../../echistory/$year/$term/$id/intro/index.pdf\">\n".
								"</HEAD>\n</HTML>";
				}
				else if (is_file("../../echistory/$year/$term/$id/intro/index.ppt"))
				{
					//global $check, $version, $course_id, $teacher;
					$content = "<html>\n<head>\n<title>授課大綱</title>\n".
								"<meta http-equiv=REFRESH content=\"0;url=../../echistory/$year/$term/$id/intro/index.ppt\">\n".
								"</head>\n</html>";	
				}
				else {
					
					echo "<Html><Head><Title></Title><link rel=\"stylesheet\" href=\"/images/skin1/css/main-body.css\" type=\"text/css\"></Head>
							<center>
							<br><p><b></b></p>
							<font color=#ff0000></font>
							<br>
							  <table border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" width=\"80%\">
								<tr> 
								  <td> 
									<div align=\"right\"><img src=\"/images/skin1/bor/bor_01.GIF\" width=\"12\" height=\"11\"></div>
								  </td>
								  <td> 
									<div align=\"center\"><img src=\"/images/skin1/bor/bor_02.GIF\" width=\"100%\" height=\"11\"></div>
								  </td>
								  <td> 
									<div align=\"left\"><img src=\"/images/skin1/bor/bor_03.GIF\" width=\"17\" height=\"11\"></div>
								  </td>
								</tr>
								<tr> 
								  <td height=10> 
									<div align=\"right\"><img src=\"/images/skin1/bor/bor_04.GIF\" width=\"12\" height=\"100%\"></div>
								  </td>
								  <td bgcolor=\"#CCCCCC\">
									<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" height=\"100%\">
									  <tr>
										<td bgcolor=\"#F0FFEE\"> 
										  <HTML>
							<HEAD>
							<TITLE>授課大綱</TITLE>
								<center>尚無課程大綱<br>
							</HEAD>
							</HTML>
										</td>
									  </tr>
									</table>
								  </td>
								  <td height=10> 
									<div align=\"left\"><img src=\"/images/skin1/bor/bor_06.GIF\" width=\"17\" height=\"100%\"></div>
								  </td>
								</tr>
								<tr> 
								  <td> 
									<div align=\"right\"><img src=\"/images/skin1/bor/bor_07.GIF\" width=\"12\" height=\"17\"></div>
								  </td>
								  <td> 
									<div align=\"center\"><img src=\"/images/skin1/bor/bor_08.GIF\" width=\"100%\" height=\"17\"></div>
								  </td>
								  <td> 
									<div align=\"left\"><img src=\"/images/skin1/bor/bor_09.GIF\" width=\"17\" height=\"17\"></div>
								  </td>
								</tr>
							  </table>
							</center>
							</body></html>";
				}
				if ( stristr($content,"<html>") == NULL )
				$content = str_replace ( "\n", "<BR>", $content );
				echo $content;
				
			}
			else{
				show_page_d ();
			}
		}
		else {
			header( "Location: http://$SERVER_NAME/php/Courses_Admin/guest.php?PHPSESSID=".session_id());
		}
		
	}

	function show_page_d ( $message="" ){
		global $version, $course_id, $skinnum, $DB, $user_id, $year_term;
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		$tpl->define ( array ( body => "upload_intro.tpl" ) );
		$tpl->define_dynamic ( "year_list" , "body");
		$tpl->define_dynamic ( "course_list" , "body" );
		$tpl->define_dynamic ( "table_list" , "body" );
		$tpl->assign( SKINNUM , $skinnum );
		$color = "#000066";		
		$tpl->assign( COLOR , $color );
		$tpl->assign( TYPE , "colspan=2" );
		
		//取出當學期 96.12.07 由 jim 新增 175-179 行 目的是為了要讓下拉式選單預設顯示為當學期
		$Q01 = "select year,term from this_semester";
		$result01 = mysql_db_query( $DB, $Q01 );
		$row01 = mysql_fetch_array( $result01 );
		if($year_term == "") $year_term = $row01['year']."_".$row01['term'];
		//name:系所名稱
		$Q1 = "select name FROM user where id = '$user_id'";
		if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
			$message = "$message - 資料庫讀取錯誤!!";
		}else
			$row = mysql_fetch_array( $result );
		//產生學年的下拉式選單	
		$Q0 = "select distinct teach_course.year ,teach_course.term from this_semester, course, course_group, teach_course where  course_group.name = '$row[name]' and course_group.a_id = course.group_id  and course.a_id = teach_course.course_id order by year desc, term desc, course.course_no";
		
		if ( !($result0 = mysql_db_query( $DB, $Q0 ) ) ) {
			$message = "$message - 資料庫讀取錯誤!!";
		}		
		else if ( mysql_num_rows( $result0 ) != 0 ) {
			while ( $row0 = mysql_fetch_array( $result0 ) ) {
				if ( $year_term == $row0['year']."_".$row0['term'] ) {
					$tpl->assign( Y_M , $row0['year']."_".$row0['term']." selected" );
				} else {
					$tpl->assign( Y_M , $row0['year']."_".$row0['term'] );
					if(mysql_num_rows($result0) == 1) 
					   $year_term=$row0['year']."_".$row0['term'];
				}
				$tpl->assign( YEAR_TERM , "第".$row0['year']."學年第".$row0['term']."學期" );
				$tpl->parse ( YEAR_LIST, ".year_list" );
			}
		}
		//取出當學期 96.12.07 mark by jim from 204-206 行
		//$Q01 = "select year,term from this_semester";
		//$result01 = mysql_db_query( $DB, $Q01 );
		//$row01 = mysql_fetch_array( $result01 );		
		//echo "zqq $year_term<br>";
		$tpl->assign( NOW , "第".$row01['year']."學年第".$row01['term']."學期" );
		if($year_term!="" || $year_term!=NULL)
		{
			$realyt=explode("_",$year_term);
			$row0['year']=$realyt[0];
			$row0['term']=$realyt[1];
			$tpl->assign( NOW , "第".$row0['year']."學年第".$row0['term']."學期" );
		}
		$year_test = $row01['year'];
		//echo "zaa $year_test<br>";
		//echo "YEAR :$row0[year]  TERM:$row0[term] <br>";		
		//取出選出的學年度的課程
		//如果是當學期
		//若要開下學期的課程時,記得來修改這行,如現在為year=97,term=1需修改為year=97,term=2,這樣下學期的課才會正常顯示
		if(($row0['year']==$row01['year'] && $row0['term']==$row01['term']) || ($row0['year']=="102" && $row0['term']=="2")){
		//if(($row0['year']==$row01['year'] ) || ($row0['year']=="" && $row0['term']=="")){
			if ( $version == "C" ) {
				$tpl->assign( FONTCOL, "white" );
				$tpl->assign( GNAME , "<font color =#FFFFFF>開課單位</font>" );
				$tpl->assign( YEAR , "<font color =#FFFFFF>開課學年度</font>" );
				$tpl->assign( CNO , "<font color =#FFFFFF>課程編號</font>" );
				$tpl->assign( CNAME , "<font color =#FFFFFF>課程大綱上傳</font>" );
				$tpl->assign( EWSTAT , "<font color =#FFFFFF>預警狀態</font>" );
				$tpl->assign( FILE_DATE ,"<font color =#FFFFFF>檔案上傳日期</font>");
				$tpl->assign( CMATERIAL , "<font color =#FFFFFF>授課教材上傳</font>" );
				$tpl->assign( CTEACH , "<font color =#FFFFFF>授課教師辦公室時間</font>" );
				$tpl->assign( EMAIL , "<font color = #FFFFFF>學生E-mail</font>" );
				$tpl->assign( QUESTIONARY , "<font color =#FFFFFF>IEET</font>" );
				$tpl->assign( SELFEVALUATE , "" );
			}
			else {
				$tpl->assign( FONTCOL, "white" );
				$tpl->assign( GNAME , "<font color =#FFFFFF>Department</font>" );
				$tpl->assign( YEAR , "<font color =#FFFFFF>Year</font>" );
				$tpl->assign( CNO , "<font color =#FFFFFF>No.</font>" );
				$tpl->assign( CNAME , "<font color =#FFFFFF>Course Name</font>" );
				$tpl->assign( EWSTAT , "<font color =#FFFFFF>Early Waring</font>" );
				$tpl->assign( FILE_DATE ,"<font color =#FFFFFF>File Date</font>");
				$tpl->assign( CMATERIAL , "<font color =#FFFFFF>Material</font>" );
				$tpl->assign( CTEACH , "<font color =#FFFFFF>Teachers</font>" );
				$tpl->assign( EMAIL , "<font color = #FFFFFF>E-mail</font>" );
				$tpl->assign( QUESTIONARY , "<font color =#FFFFFF>IEET</font>" );
				$tpl->assign( SELFEVALUATE , "" );
			}
			$tpl->parse ( COURSE_LIST, ".course_list" );
			$tpl->assign( TYPE , "" );
			// 96.12.07 update by jim
			//$Q2 = "select distinct course.name, course.course_no, course.a_id course_id, teach_course.year, teach_course.term from this_semester, course, course_group, teach_course where teach_course.year=this_semester.year and teach_course.term=this_semester.term and course_group.name = '$row[name]' and course_group.a_id = course.group_id and course.a_id = teach_course.course_id order by year desc, term desc, course.course_no";		
      $Q2 = "select distinct course.name, course.course_no, course.a_id course_id, teach_course.year, teach_course.term from course, course_group, teach_course where teach_course.year='$row0[year]' and teach_course.term='$row0[term]' and course_group.name = '$row[name]' and course_group.a_id = course.group_id and course.a_id = teach_course.course_id order by year desc, term desc, course.course_no";
			if ( $result2 = mysql_db_query( $DB, $Q2 ) ) {
				while($row2 = mysql_fetch_array( $result2 ))
				{
					$color = "white";
					$fontcol = "red";
					$file_date = "";
					//判斷是否有課程大綱
					$Q3 = "select introduction, name, mtime FROM course where a_id ='$row2[course_id]'";
					if ( $result3 = mysql_db_query( $DB, $Q3 ) ) {
						$row3 = mysql_fetch_array( $result3 );
						
						//如果introduction不是空的，而且資料夾下有檔案
						if( $row3[introduction]!= "" || is_file("../../$row2[course_id]/intro/index.html") || is_file("../../$row2[course_id]/intro/index.htm") || is_file("../../$row2[course_id]/intro/index.doc") || is_file("../../$row2[course_id]/intro/index.docx") || is_file("../../$row2[course_id]/intro/index.pdf") || is_file("../../$row2[course_id]/intro/index.ppt") )
						{
							$color = "#E6FFFC";
							$fontcol = "black";
							//抓取檔案時間
							if($row3[introduction]!= ""){
								$tempDate=array(substr($row3[mtime],0,4),substr($row3[mtime],4,2),substr($row3[mtime],6,2));
								//$tempTime=array(substr($row3[mtime],8,2),substr($row3[mtime],10,2),substr($row3[mtime],12,2));
								$date=implode("-",$tempDate);//." ".implode(":",$tempTime);							
								$file_date = $date;
							}else if(is_file("../../$row2[course_id]/intro/index.html")){
								$file = "../../$row2[course_id]/intro/index.html";
								$file_date = date("Y-m-d",filemtime($file));
							}else if(is_file("../../$row2[course_id]/intro/index.htm")){
								$file = "../../$row2[course_id]/intro/index.htm";
								$file_date = date("Y-m-d",filemtime($file));
							}else if(is_file("../../$row2[course_id]/intro/index.doc")){
								$file = "../../$row2[course_id]/intro/index.doc";
								$file_date = date("Y-m-d",filemtime($file));					
							}else if(is_file("../../$row2[course_id]/intro/index.pdf")){
								$file = "../../$row2[course_id]/intro/index.pdf";
								$file_date = date("Y-m-d",filemtime($file));						
							}else if(is_file("../../$row2[course_id]/intro/index.ppt")){
								$file = "../../$row2[course_id]/intro/index.ppt";
								$file_date = date("Y-m-d",filemtime($file));
							}							
						}
												
					}
					else{
						$message = "$message - 資料庫讀取錯誤!!";
					}
					//選出該課程的教師				
					//$Q4 = "select user.name, user.a_id from user, teach_course, this_semester where teach_course.year=this_semester.year and teach_course.term=this_semester.term and teach_course.course_id = $row2[course_id] and teach_course.teacher_id = user.a_id and user.authorization=1";
					$Q4 = "select user.name, user.a_id from user, teach_course where teach_course.year='$row0[year]' and teach_course.term='$row0[term]' and teach_course.course_id = $row2[course_id] and teach_course.teacher_id = user.a_id and user.authorization=1";
					$name = "";
					if ( $result4 = mysql_db_query( $DB, $Q4 ) ) {
						while($row4 = mysql_fetch_array( $result4 )){
							//加入編輯辦公室的連結
							$name = $name."<a href=\"./office_time_assistant.php?teacher_id=".$row4['a_id']."&year=".$row2['year']."&term=".$row2['term']."\">".$row4[name]."</a> ";
						}
					}
					else{
						$message = "$message - 資料庫讀取錯誤!!";
					}
					
					$count = 0;
					//選出email
					//$Q5 = "Select user.* From user,take_course, this_semester Where take_course.year=this_semester.year and take_course.term=this_semester.term and user.a_id=take_course.student_id And take_course.course_id='$row2[course_id]' and take_course.credit = '1' Order By id ASC";
					$Q5 = "Select user.* From user,take_course Where take_course.year='$row0[year]' and take_course.term='$row0[term]' and user.a_id=take_course.student_id And take_course.course_id='$row2[course_id]' and take_course.credit = '1' Order By id ASC";
					$result5 = mysql_db_query( $DB, $Q5 );
					while( $row5 = mysql_fetch_array ( $result5 ) )
					{
						 $email=$row5['email'];
	  
						if($email == NULL)
							$email = "N/A";
						else if($count <85)
						{
							$mail_list=$mail_list.$email.",";
							$count++;
						}
						else if($count <170)
						{
							$mail_list1=$mail_list1.$email.",";
							$count++;
						}
						else if($count < 255)
						{
							$mail_list2=$mail_list2.$email.",";
							$count++;
						}
						else if($count < 340)
						{
							$mail_list3=$mail_list3.$email.",";
							$count++;
						}
						else if($count < 425)
						{
							$mail_list4=$mail_list4.$email.",";
							$count++;
						}
						else if($count < 510)
						{
							$mail_list5=$mail_list5.$email.",";
							$count++;
						}
					}
					
					//判斷是否有預警生
					$early="--";
					$Q6 = "SELECT count(*) as cnt_1 FROM early_warning WHERE course_id = '$row2[course_id]' AND year = '$row0[year]' AND term = '$row0[term]' ";
					//echo $Q6;
					if ( $result6 = mysql_db_query( $DB, $Q6 ) ) 
					{
						$row6 = mysql_fetch_array( $result6 );
						if($row6['cnt_1'] >= 1) $early="Y"; else $early="--";
					}
					else
					{
						$message = "$message - 資料庫讀取錯誤!!";
					}					
					
					if($name != ""){
					
						$tpl->assign( FONTCOL, $fontcol );
						$tpl->assign( COLOR , $color );
						$tpl->assign( GNAME , $row["name"] );
						$tpl->assign( YEAR , $row2["year"]."學年度第".$row2["term"]."學期" );
						//$tpl->assign( CNAME , "<a href=\"upload_intro.php?action=showintro&courseid=$row2[course_id]\">$row2[name]</a>" );
						$tpl->assign( CNAME , "<a href=# onClick=\" window.open('upload_intro.php?action=showintro&courseid=$row2[course_id]', '', 'toolbar=no,location=no,resizable=yes,scrollbars=yes'); \" >".$row2[name]."</a>");
						$tpl->assign( EWSTAT , $early);
						$tpl->assign( FILE_DATE , $file_date);
						$tpl->assign( CMATERIAL , "<a href=\"upload_intro.php?action=upload_material&courseid=$row2[course_id]\">上傳</a>　".
												  "<a href=\"upload_intro.php?action=edit_material&courseid=$row2[course_id]\">編輯</a>　".
												  "<a href=\"upload_intro.php?action=preview_material&courseid=$row2[course_id]\">預覽</a>　".
												  "<a href=\"upload_intro.php?action=import_material&courseid=$row2[course_id]\">匯入</a>　");
						$tpl->assign( CNO , $row2[course_no] );
						$tpl->assign( CTEACH, $name );
						
						if ($count<85)
						{
							$tpl->assign( EMAIL, "<a href=mailto:?subject=通知信!&bcc=$mail_list>mail</a>");
						}
						else if($count<170)
						{
							$tpl->assign( EMAIL, "<a href=mailto:?subject=通知信!&bcc=$mail_list>mail</a> ".
												 "<a href=mailto:?subject=通知信!&bcc=$mail_list1>mail1</a>" );
						}
						else if($count<255)
						{
							$tpl->assign( EMAIL, "<a href=mailto:?subject=通知信!&bcc=$mail_list>mail</a> ".
												 "<a href=mailto:?subject=通知信!&bcc=$mail_list1>mail1</a> ".
												 "<a href=mailto:?subject=通知信!&bcc=$mail_list2>mail2</a>" );
						}
						else if($count<340)
						{
							$tpl->assign( EMAIL, "<a href=mailto:?subject=通知信!&bcc=$mail_list>mail</a> ".
												 "<a href=mailto:?subject=通知信!&bcc=$mail_list1>mail1</a> ".
												 "<a href=mailto:?subject=通知信!&bcc=$mail_list2>mail2</a> ".
												 "<a href=mailto:?subject=通知信!&bcc=$mail_list3>mail3</a>" );
						}
						else if($count<425)
						{
							$tpl->assign( EMAIL, "<a href=mailto:?subject=通知信!&bcc=$mail_list>mail</a> ".
												 "<a href=mailto:?subject=通知信!&bcc=$mail_list1>mail1</a> ".
												 "<a href=mailto:?subject=通知信!&bcc=$mail_list2>mail2</a> ".
												 "<a href=mailto:?subject=通知信!&bcc=$mail_list3>mail3</a> ".
												 "<a href=mailto:?subject=通知信!&bcc=$mail_list4>mail4</a> " );
						}
						else if($count<510)
						{
							$tpl->assign( EMAIL, "<a href=mailto:?subject=通知信!&bcc=$mail_list>mail</a> ".
												 "<a href=mailto:?subject=通知信!&bcc=$mail_list1>mail1</a> ".
												 "<a href=mailto:?subject=通知信!&bcc=$mail_list2>mail2</a> ".
												 "<a href=mailto:?subject=通知信!&bcc=$mail_list3>mail3</a> ".
												 "<a href=mailto:?subject=通知信!&bcc=$mail_list4>mail4</a> ".
												 "<a href=mailto:?subject=通知信!&bcc=$mail_list5>mail5</a> " );
						}
						$count = 0;
						$mail_list = "";						
						$mail_list1 = "";						
						$mail_list2 = "";						
						$mail_list3 = "";						
						$mail_list4 = "";						
						$mail_list5 = "";						

						$tpl->assign( QUESTIONARY , "<a href=\"../questionary/assistantquestionary_showresult.php?courseid=$row2[course_id]&year=$row2[year]&term=$row2[term]\" target=_blank>問卷</a>" );
						$tpl->assign( SELFEVALUATE , "<a href=\"/php/Self_Evaluate/result_display.php?course_id=$row2[course_id]&ac=1\" target=_blank>自評</a>" );
						$tpl->parse ( COURSE_LIST, ".course_list" );
						$tpl->parse ( TABLE_LIST, "table_list" );					
										
					}
				}		
			}
			else{
				$message = "$message - 資料庫讀取錯誤!!";
			}
			
		}
		else{		
			//---------------------------------------
			//取出非這學期的課程
			$color = "#000066";
			$tpl->assign( COLOR , $color );
			$tpl->assign( TYPE , "colspan=2" );					
			if ( $version == "C" ) {
				$tpl->assign( FONTCOL, "white" );
				$tpl->assign( GNAME , "<font color =#FFFFFF>開課單位</font>" );
				$tpl->assign( YEAR , "<font color =#FFFFFF>開課學年度</font>" );
				$tpl->assign( CNO , "<font color =#FFFFFF>課程編號</font>" );
				$tpl->assign( CNAME , "<font color =#FFFFFF>課程名稱</font>" );
				$tpl->assign( EWSTAT , "<font color =#FFFFFF>預警狀態</font>" );
				$tpl->assign( FILE_DATE ,"<font color =#FFFFFF>檔案上傳日期</font>");
				$tpl->assign( CMATERIAL , "<font color =#FFFFFF>課程大綱</font>" );
				$tpl->assign( CTEACH , "<font color =#FFFFFF>授課教師辦公室時間</font>" );
				$tpl->assign( EMAIL , "<font color = #FFFFFF>E-mail</font>" );
				$tpl->assign( QUESTIONARY , "<font color =#FFFFFF>IEET</font>" );
				$tpl->assign( SELFEVALUATE , "" );
			}
			else {
				$tpl->assign( FONTCOL, "white" );
				$tpl->assign( GNAME , "<font color =#FFFFFF>Department</font>" );
				$tpl->assign( YEAR , "<font color =#FFFFFF>Year</font>" );
				$tpl->assign( CNO , "<font color =#FFFFFF>No.</font>" );
				$tpl->assign( CNAME , "<font color =#FFFFFF>Course Name</font>" );
				$tpl->assign( EWSTAT , "<font color =#FFFFFF>Early Waring</font>" );
				$tpl->assign( FILE_DATE ,"<font color =#FFFFFF>File Date</font>");
				$tpl->assign( CMATERIAL , "<font color =#FFFFFF>Material</font>" );
				$tpl->assign( CTEACH , "<font color =#FFFFFF>Teachers</font>" );
				$tpl->assign( EMAIL , "<font color = #FFFFFF>E-mail</font>" );
				$tpl->assign( QUESTIONARY , "<font color =#FFFFFF>IEET</font>" );
				$tpl->assign( SELFEVALUATE , "" );
			}
			$tpl->parse ( COURSE_LIST, "course_list" );
			$tpl->assign( TYPE , "" );
			//$Q2 = "select distinct course.name, course.course_no, course.a_id course_id, teach_course.year, teach_course.term from course, course_group, teach_course where teach_course.year='$row0[year]' and teach_course.term='$row0[term]' and course_group.name = '$row[name]' and course_group.a_id = course.group_id and course.a_id = teach_course.course_id order by year desc, term desc, course.course_no";			
			//97.03.06 update by jim 此為歷史區,所以應該去讀hist_course才對
//			$Q2 = "select distinct c.name, c.course_no, c.a_id course_id, tc.year, tc.term from hist_course c, course_group cg, teach_course tc where tc.year='$row0[year]' and tc.term='$row0[term]' and cg.name = '$row[name]' and cg.a_id = c.group_id and c.a_id = tc.course_id order by tc.year desc, tc.term desc, c.course_no";
			$Q2 = "select distinct c.name, c.course_no, c.a_id course_id, c.year, c.term from hist_course c, course_group cg, teach_course tc where c.year='$row0[year]' and c.term='$row0[term]' and cg.name = '$row[name]' and cg.a_id = c.group_id and c.a_id = tc.course_id order by c.year desc, c.term desc, c.course_no";
			if ( $result2 = mysql_db_query( $DB, $Q2 ) ) {
				$pre_year="";//暫存上一門課的學年，預設為""
				while($row2 = mysql_fetch_array( $result2 ))
				{
					$color = "white";
					$color2 = "white";
					$fontcol = "red";
					//先判斷該門課程是否有建課程目錄，如果沒有建立一個
					//學年資料夾 93,94,...
					if(!is_dir("../../echistory/".$row2[year]))
					{
						mkdir("../../echistory/".$row2[year], 0700);//建立目錄
					}
					//學期資料夾 1,2
					if(!is_dir("../../echistory/".$row2[year]."/".$row2[term]))
					{
						mkdir("../../echistory/".$row2[year]."/".$row2[term], 0700);//建立目錄				
					}	
					//課程資料夾 course_id
					if(!is_dir("../../echistory/".$row2[year]."/".$row2[term]."/".$row2[course_id]))
					{
						mkdir("../../echistory/".$row2[year]."/".$row2[term]."/".$row2[course_id], 0700);//建立目錄
					}					
					//判斷是否有課程大綱
					$Q3 = "select name FROM course where a_id ='$row2[course_id]'";
					if ( $result3 = mysql_db_query( $DB, $Q3 ) ) {
						$row3 = mysql_fetch_array( $result3 );
						//如果introduction不是空的，而且資料夾下有檔案
						//if(is_file("../../old_intro/$row2[year]/$row2[term]/$row2[course_id]/index.html") || is_file("../../old_intro/$row2[year]/$row2[term]/$row2[course_id]/index.htm") || is_file("../../old_intro/$row2[year]/$row2[term]/$row2[course_id]/index.doc") || is_file("../../old_intro/$row2[year]/$row2[term]/$row2[course_id]/index.pdf") || is_file("../../old_intro/$row2[year]/$row2[term]/$row2[course_id]/index.ppt") ) 
						if(is_file("../../echistory/$row2[year]/$row2[term]/$row2[course_id]/intro/index.html") || is_file("../../echistory/$row2[year]/$row2[term]/$row2[course_id]/intro/index.htm") || is_file("../../echistory/$row2[year]/$row2[term]/$row2[course_id]/intro/index.doc") || is_file("../../echistory/$row2[year]/$row2[term]/$row2[course_id]/intro/index.pdf") || is_file("../../echistory/$row2[year]/$row2[term]/$row2[course_id]/intro/index.ppt") )
						{
							$color = "#E6FFFC";
							$color2= "#E6FFFC";
							$fontcol = "black";
						}
					}
					else{
						$message = "$message - 資料庫讀取錯誤!!";
					}
					//選出該課程的教師				
					//$Q4 = "select user.name from user, teach_course, this_semester where teach_course.year=this_semester.year and teach_course.term=this_semester.term and teach_course.course_id = $row2[course_id] and teach_course.teacher_id = user.a_id";
					$Q4 = "select user.name, user.a_id from user, teach_course where teach_course.year=$row2[year] and teach_course.term=$row2[term] and teach_course.course_id = $row2[course_id] and teach_course.teacher_id = user.a_id and user.authorization=1";
					$name = "";
					if ( $result4 = mysql_db_query( $DB, $Q4 ) ) {
						while($row4 = mysql_fetch_array( $result4 )){
							//加入編輯辦公室的連結
							$name = $name."<a href=\"./office_time_assistant.php?teacher_id=".$row4['a_id']."&year=".$row2['year']."&term=".$row2['term']."\">".$row4[name]."</a> ";
						}
					}
					else{
						$message = "$message - 資料庫讀取錯誤!!";
					}
					
					$count = 0;
					//選出email
					//$Q5 = "Select user.* From user,take_course, this_semester  Where take_course.year=this_semester.year and take_course.term=this_semester.term and user.a_id=take_course.student_id And take_course.course_id='$row2[course_id]' and take_course.credit = '1' Order By id ASC";
					$Q5 = "Select user.* From user,take_course Where take_course.year=$row2[year] and take_course.term=$row2[term] and user.a_id=take_course.student_id And take_course.course_id='$row2[course_id]' and take_course.credit = '1' Order By id ASC";
					$result5 = mysql_db_query( $DB, $Q5 );
					while( $row5 = mysql_fetch_array ( $result5 ) )
					{
						 $email=$row5['email'];
	  
						if($email == NULL)
							$email = "N/A";
						else if($count <85)
						{
							$mail_list=$mail_list.$email.",";
							$count++;
						}
						else if($count <170)
						{
							$mail_list1=$mail_list1.$email.",";
							$count++;
						}
						else if($count < 255)
						{
							$mail_list2=$mail_list2.$email.",";
							$count++;
						}
						else if($count < 340)
						{
							$mail_list3=$mail_list3.$email.",";
							$count++;
						}
						else if($count < 425)
						{
							$mail_list4=$mail_list4.$email.",";
							$count++;
						}
						else if($count < 510)
						{
							$mail_list5=$mail_list5.$email.",";
							$count++;
						}
					}

					//判斷是否有預警生
					$early="--";
					$Q6 = "SELECT count(*) as cnt_1 FROM early_warning WHERE course_id = '$row2[course_id]' AND year = '$row0[year]' AND term = '$row0[term]' ";
					//echo $Q6;
					if ( $result6 = mysql_db_query( $DB, $Q6 ) ) 
					{
						$row6 = mysql_fetch_array( $result6 );
						if($row6['cnt_1'] >= 1) $early="Y"; else $early="--";
					}
					else
					{
						$message = "$message - 資料庫讀取錯誤!!";
					}					

					if($name != ""){
						if($pre_year != $row2["year"]."學年度第".$row2["term"]."學期" && $pre_year != ""){
							$tpl->parse ( TABLE_LIST, ".table_list" );					
						
							$tpl->assign( SKINNUM , $skinnum );
							$color = "#000066";
							$tpl->assign( COLOR , $color );
							$tpl->assign( TYPE , "colspan=2" );
							if ( $version == "C" ) {
								$tpl->assign( GNAME , "<font color =#FFFFFF>開課單位</font>" );
								$tpl->assign( YEAR , "<font color =#FFFFFF>開課學年度</font>" );
								$tpl->assign( CNO , "<font color =#FFFFFF>課程編號</font>" );
								$tpl->assign( CNAME , "<font color =#FFFFFF>課程名稱</font>" );
								$tpl->assign( EWSTAT , "<font color =#FFFFFF>預警狀態</font>" );
								$tpl->assign( FILE_DATE ,"<font color =#FFFFFF>檔案上傳日期</font>");
								$tpl->assign( CMATERIAL , "<font color =#FFFFFF>課程大綱</font>" );
								$tpl->assign( CTEACH , "<font color =#FFFFFF>授課教師辦公室時間</font>" );
								$tpl->assign( EMAIL , "<font color = #FFFFFF>E-mail</font>" );
								$tpl->assign( QUESTIONARY , "<font color =#FFFFFF>IEET</font>" );
								$tpl->assign( SELFEVALUATE , "" );
							}
							else {
								$tpl->assign( GNAME , "<font color =#FFFFFF>Department</font>" );
								$tpl->assign( YEAR , "<font color =#FFFFFF>Year</font>" );
								$tpl->assign( CNO , "<font color =#FFFFFF>No.</font>" );
								$tpl->assign( CNAME , "<font color =#FFFFFF>Course Name</font>" );
								$tpl->assign( EWSTAT , "<font color =#FFFFFF>Early Waring</font>" );
								$tpl->assign( FILE_DATE ,"<font color =#FFFFFF>File Date</font>");
								$tpl->assign( CMATERIAL , "<font color =#FFFFFF>Syllabus</font>" );
								$tpl->assign( CTEACH , "<font color =#FFFFFF>Teachers</font>" );
								$tpl->assign( EMAIL , "<font color = #FFFFFF>E-mail</font>" );
								$tpl->assign( QUESTIONARY , "<font color =#FFFFFF>IEET</font>" );
								$tpl->assign( SELFEVALUATE , "" );
							}
							
							$tpl->assign( TYPE , "" );					
							$tpl->parse ( COURSE_LIST, "course_list" );
						
							$color = $color2;
							$tpl->assign( COLOR , $color );
							$tpl->assign( FONTCOL, $fontcol );
							$tpl->assign( GNAME , $row["name"] );
							$tpl->assign( YEAR , $row2["year"]."學年度第".$row2["term"]."學期" );
							$tpl->assign( CNAME , "<a href=# onClick=\"window.open('upload_intro.php?action=show_old_intro&id=$row2[course_id]&year=$row2[year]&term=$row2[term]', '', 'resizable=yes,scrollbars=yes,width=640,height=480');\">".$row2["name"]."</a>");
							//$tpl->assign( CNAME , "<a href=\"upload_intro.php?action=show_old_intro&id=$row2[course_id]&year=$row2[year]&term=$row2[term]\">$row2[name]</a>" );
							$tpl->assign( EWSTAT , $early);
							$tpl->assign( FILE_DATE , $file_date);
							$tpl->assign( CMATERIAL , "<a href=\"upload_intro.php?action=upload_old_intro&courseid=$row2[course_id]&year=".$row2['year']."&term=".$row2['term']."\">上傳課程大綱</a>");
							$tpl->assign( CNO , $row2[course_no] );
							$tpl->assign( CTEACH, $name );
							
							if ($count<85)
							{
								$tpl->assign( EMAIL, "<a href=mailto:?subject=通知信!&bcc=$mail_list>mail</a>");
							}
							else if($count<170)
							{
								$tpl->assign( EMAIL, "<a href=mailto:?subject=通知信!&bcc=$mail_list>mail</a> ".
													 "<a href=mailto:?subject=通知信!&bcc=$mail_list1>mail1</a>" );
							}
							else if($count<255)
							{
								$tpl->assign( EMAIL, "<a href=mailto:?subject=通知信!&bcc=$mail_list>mail</a> ".
													 "<a href=mailto:?subject=通知信!&bcc=$mail_list1>mail1</a> ".
													 "<a href=mailto:?subject=通知信!&bcc=$mail_list2>mail2</a>" );
							}
							else if($count<340)
							{
								$tpl->assign( EMAIL, "<a href=mailto:?subject=通知信!&bcc=$mail_list>mail</a> ".
													 "<a href=mailto:?subject=通知信!&bcc=$mail_list1>mail1</a> ".
													 "<a href=mailto:?subject=通知信!&bcc=$mail_list2>mail2</a> ".
													 "<a href=mailto:?subject=通知信!&bcc=$mail_list3>mail3</a>" );
							}
							else if($count<425)
							{
								$tpl->assign( EMAIL, "<a href=mailto:?subject=通知信!&bcc=$mail_list>mail</a> ".
													 "<a href=mailto:?subject=通知信!&bcc=$mail_list1>mail1</a> ".
													 "<a href=mailto:?subject=通知信!&bcc=$mail_list2>mail2</a> ".
													 "<a href=mailto:?subject=通知信!&bcc=$mail_list3>mail3</a> ".
													 "<a href=mailto:?subject=通知信!&bcc=$mail_list4>mail4</a> " );
							}
							else if($count<510)
							{
								$tpl->assign( EMAIL, "<a href=mailto:?subject=通知信!&bcc=$mail_list>mail</a> ".
													 "<a href=mailto:?subject=通知信!&bcc=$mail_list1>mail1</a> ".
													 "<a href=mailto:?subject=通知信!&bcc=$mail_list2>mail2</a> ".
													 "<a href=mailto:?subject=通知信!&bcc=$mail_list3>mail3</a> ".
													 "<a href=mailto:?subject=通知信!&bcc=$mail_list4>mail4</a> ".
													 "<a href=mailto:?subject=通知信!&bcc=$mail_list5>mail5</a> " );
							}
							$count = 0;
							$mail_list = "";						
							$mail_list1 = "";						
							$mail_list2 = "";						
							$mail_list3 = "";						
							$mail_list4 = "";						
							$mail_list5 = "";						
	
							$tpl->parse ( COURSE_LIST, ".course_list" );
							$pre_year = $row2["year"]."學年度第".$row2["term"]."學期";					
						}else{
							$tpl->assign( FONTCOL, $fontcol );
							$tpl->assign( COLOR , $color );
							$tpl->assign( GNAME , $row["name"] );
							$tpl->assign( YEAR , $row2["year"]."學年度第".$row2["term"]."學期" );
							$tpl->assign( CNAME , "<a href=# onClick=\"window.open('upload_intro.php?action=show_old_intro&id=$row2[course_id]&year=$row2[year]&term=$row2[term]', '', 'resizable=yes,scrollbars=yes,width=640,height=480');\">".$row2["name"]."</a>");
							//$tpl->assign( CNAME , "<a href=\"upload_intro.php?action=show_old_intro&id=$row2[course_id]&year=$row2[year]&term=$row2[term]\">$row2[name]</a>" );
							$tpl->assign( EWSTAT , $early);
							$tpl->assign( FILE_DATE , $file_date);
							$tpl->assign( CMATERIAL , "<a href=\"upload_intro.php?action=upload_old_intro&courseid=$row2[course_id]&year=".$row2['year']."&term=".$row2['term']."\">上傳課程大綱</a>");
							$tpl->assign( CNO , $row2[course_no] );
							$tpl->assign( CTEACH, $name );
							
							if ($count<85)
							{
								$tpl->assign( EMAIL, "<a href=mailto:?subject=通知信!&bcc=$mail_list>mail</a>");
							}
							else if($count<170)
							{
								$tpl->assign( EMAIL, "<a href=mailto:?subject=通知信!&bcc=$mail_list>mail</a> ".
													 "<a href=mailto:?subject=通知信!&bcc=$mail_list1>mail1</a>" );
							}
							else if($count<255)
							{
								$tpl->assign( EMAIL, "<a href=mailto:?subject=通知信!&bcc=$mail_list>mail</a> ".
													 "<a href=mailto:?subject=通知信!&bcc=$mail_list1>mail1</a> ".
													 "<a href=mailto:?subject=通知信!&bcc=$mail_list2>mail2</a>" );
							}
							else if($count<340)
							{
								$tpl->assign( EMAIL, "<a href=mailto:?subject=通知信!&bcc=$mail_list>mail</a> ".
													 "<a href=mailto:?subject=通知信!&bcc=$mail_list1>mail1</a> ".
													 "<a href=mailto:?subject=通知信!&bcc=$mail_list2>mail2</a> ".
													 "<a href=mailto:?subject=通知信!&bcc=$mail_list3>mail3</a>" );
							}
							else if($count<425)
							{
								$tpl->assign( EMAIL, "<a href=mailto:?subject=通知信!&bcc=$mail_list>mail</a> ".
													 "<a href=mailto:?subject=通知信!&bcc=$mail_list1>mail1</a> ".
													 "<a href=mailto:?subject=通知信!&bcc=$mail_list2>mail2</a> ".
													 "<a href=mailto:?subject=通知信!&bcc=$mail_list3>mail3</a> ".
													 "<a href=mailto:?subject=通知信!&bcc=$mail_list4>mail4</a> " );
							}
							else if($count<510)
							{
								$tpl->assign( EMAIL, "<a href=mailto:?subject=通知信!&bcc=$mail_list>mail</a> ".
													 "<a href=mailto:?subject=通知信!&bcc=$mail_list1>mail1</a> ".
													 "<a href=mailto:?subject=通知信!&bcc=$mail_list2>mail2</a> ".
													 "<a href=mailto:?subject=通知信!&bcc=$mail_list3>mail3</a> ".
													 "<a href=mailto:?subject=通知信!&bcc=$mail_list4>mail4</a> ".
													 "<a href=mailto:?subject=通知信!&bcc=$mail_list5>mail5</a> " );
							}
							$count = 0;
							$mail_list = "";						
							$mail_list1 = "";						
							$mail_list2 = "";						
							$mail_list3 = "";						
							$mail_list4 = "";						
							$mail_list5 = "";						
							
							$tpl->assign( QUESTIONARY , "<a href=\"../questionary/assistantquestionary_showresult.php?courseid=$row2[course_id]&year=$row2[year]&term=$row2[term]\" target=_blank>問卷</a>" );
							$tpl->assign( SELFEVALUATE , "<a href=\"/php/Self_Evaluate/result_display.php?course_id=$row2[course_id]&ac=1\" target=_blank>自評</a>" );
							$tpl->parse ( COURSE_LIST, ".course_list" );
							$pre_year = $row2["year"]."學年度第".$row2["term"]."學期";		
						}
					}
				}
				$tpl->parse ( TABLE_LIST, ".table_list" );
			}
			else{
				$message = "$message - 資料庫讀取錯誤!!";
			}
			
		}				
		$tpl->parse( BODY, "body" );
		$tpl->FastPrint("BODY");
	}	
?>
