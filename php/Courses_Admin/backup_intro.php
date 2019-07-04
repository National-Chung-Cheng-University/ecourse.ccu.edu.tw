<?php
require 'fadmin.php';
?>
<HTML>
	<HEAD>
	<TITLE>備份課程大綱</TITLE>
		<meta http-equiv="Content-Type" content="text/html; charset=big5">
	</HEAD>
	<BODY background = "/images/img/bg.gif">
		<table>
 		<center>
			<tr>
				<td><a href=../check_admin.php>回系統管理介面</a></td>
			</tr>
		</table>
		<BR>
			<div id="progress">	　
			</div>
		<hr>
		<table>
			<tr>
			<td>
			<div id="course_progress">	　
			</div>
			</td>			
			</tr>
		</table>
						
<?php			
global $DB;
	if (!(isset($PHPSESSID) && session_check_admin($PHPSESSID)) ) {
		show_page( "not_access.tpl" ,"權限錯誤");
	}	
	
	//得到當學期 $year, $term
	/*
	$Q1 = "select year, term from this_semester";
	$result1 = mysql_db_query($DB, $Q1);
	$semester= mysql_fetch_array($result1);
	*/
	//將這學期的課程大綱備份到../../old_intro/$year/$term/$id/
	$Q2 = "select distinct course.a_id course_id, teach_course.year, teach_course.term from this_semester, course, teach_course where  teach_course.year=this_semester.year and teach_course.term=this_semester.term  and course.a_id = teach_course.course_id order by year desc, term desc, course.course_no";
	if($result2 = mysql_db_query($DB, $Q2)){
		$count = 0;
		$temp = -1;		
		$total = mysql_num_rows($result2);
		echo "總共 $total 門課<br>";
		ob_end_flush();
		ob_implicit_flush(1);		
		while($data = mysql_fetch_array($result2))
		{
			$count++;
			$p = number_format((100*$count)/$total, 2);
			if($p>$temp){
				echo "<script language=\"JavaScript\" type=\"text/JavaScript\">
						document.all.progress.innerHTML = \"課程大綱備份中，請稍侯 $p%\" ; </script>";
			}
			$temp = $p;					
			//判斷是否有課程大綱
			$Q3 = "select introduction, name from course where a_id ='$data[course_id]'";
			if ( $result3 = mysql_db_query( $DB, $Q3 ) ) {
				$row = mysql_fetch_array( $result3 );
				//先判斷該門課程是否有建課程目錄，如果沒有建立一個
				//學年資料夾 93,94,...
				if(!is_dir("../../old_intro/".$data[year]))
				{
					mkdir("../../old_intro/".$data[year], 0700);//建立目錄
				}
				//學期資料夾 1,2
				if(!is_dir("../../old_intro/".$data[year]."/".$data[term]))
				{
					mkdir("../../old_intro/".$data[year]."/".$data[term], 0700);//建立目錄				
				}	
				//課程資料夾 course_id
				if(!is_dir("../../old_intro/".$data[year]."/".$data[term]."/".$data[course_id]))
				{
					mkdir("../../old_intro/".$data[year]."/".$data[term]."/".$data[course_id], 0700);//建立目錄
				}				
				//如果introduction不是空的，而且資料夾下有檔案
				if( $row['introduction']!= "" || is_file("../../$data[course_id]/intro/index.html") || is_file("../../$data[course_id]/intro/index.htm") || is_file("../../$data[course_id]/intro/index.doc") || is_file("../../$data[course_id]/intro/index.pdf") || is_file("../../$data[course_id]/intro/index.ppt") )
				{	
					//備份到../../old_intro/$year/$term/$id/
					//備份資料庫裡的檔案，需要另外處理
					//echo "$row[introduction] <br>";
					if($row['introduction']!= ""){
						 $fp = fopen ("../../old_intro/".$data[year]."/".$data[term]."/".$data[course_id]."/index.html", "w");
						 copy_html_intro( $row['introduction'], $fp);
						 fclose ($fp);
						 shell_exec("cp -r ../../".$data['course_id']."/intro/*  ../../old_intro/".$data[year]."/".$data[term]."/".$data[course_id]."/");
					}
					//備份其他的只要直接複製過去即可
					else{
						shell_exec("cp -r ../../".$data['course_id']."/intro/*  ../../old_intro/".$data[year]."/".$data[term]."/".$data[course_id]."/");				
					}
					//echo $row['name']."的課程大綱備份完畢<br />";
					echo "<script language=\"JavaScript\" type=\"text/JavaScript\">
						  document.all.course_progress.innerHTML = \"<font color=red>". $row['name'] ."</font> 的課程大綱備份完畢 \" ; </script>";	
				}
			}
			else{
				$message = "$message - 資料庫讀取錯誤!!";
			}			
		
		}
		echo "!!!!備　　份　　成　　功!!!!<br />";
	}
	else{
		$message = "$message - 資料庫讀取錯誤!!";
	}
	
	//顯示成功訊息
	function copy_html_intro( $intro , $fp)
	{
		if(!fwrite ($fp,$intro)){
			echo("write error!!!");
		}	
	}
?>
</center>
</body>
</html>