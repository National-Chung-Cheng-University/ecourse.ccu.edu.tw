<?php
require 'fadmin.php';
?>
<HTML>
	<HEAD>
	<TITLE>�ƥ��ҵ{�j��</TITLE>
		<meta http-equiv="Content-Type" content="text/html; charset=big5">
	</HEAD>
	<BODY background = "/images/img/bg.gif">
		<table>
 		<center>
			<tr>
				<td><a href=../check_admin.php>�^�t�κ޲z����</a></td>
			</tr>
		</table>
		<BR>
			<div id="progress">	�@
			</div>
		<hr>
		<table>
			<tr>
			<td>
			<div id="course_progress">	�@
			</div>
			</td>			
			</tr>
		</table>
						
<?php			
global $DB;
	if (!(isset($PHPSESSID) && session_check_admin($PHPSESSID)) ) {
		show_page( "not_access.tpl" ,"�v�����~");
	}	
	
	//�o���Ǵ� $year, $term
	/*
	$Q1 = "select year, term from this_semester";
	$result1 = mysql_db_query($DB, $Q1);
	$semester= mysql_fetch_array($result1);
	*/
	//�N�o�Ǵ����ҵ{�j���ƥ���../../old_intro/$year/$term/$id/
	$Q2 = "select distinct course.a_id course_id, teach_course.year, teach_course.term from this_semester, course, teach_course where  teach_course.year=this_semester.year and teach_course.term=this_semester.term  and course.a_id = teach_course.course_id order by year desc, term desc, course.course_no";
	if($result2 = mysql_db_query($DB, $Q2)){
		$count = 0;
		$temp = -1;		
		$total = mysql_num_rows($result2);
		echo "�`�@ $total ����<br>";
		ob_end_flush();
		ob_implicit_flush(1);		
		while($data = mysql_fetch_array($result2))
		{
			$count++;
			$p = number_format((100*$count)/$total, 2);
			if($p>$temp){
				echo "<script language=\"JavaScript\" type=\"text/JavaScript\">
						document.all.progress.innerHTML = \"�ҵ{�j���ƥ����A�еy�J $p%\" ; </script>";
			}
			$temp = $p;					
			//�P�_�O�_���ҵ{�j��
			$Q3 = "select introduction, name from course where a_id ='$data[course_id]'";
			if ( $result3 = mysql_db_query( $DB, $Q3 ) ) {
				$row = mysql_fetch_array( $result3 );
				//���P�_�Ӫ��ҵ{�O�_���ؽҵ{�ؿ��A�p�G�S���إߤ@��
				//�Ǧ~��Ƨ� 93,94,...
				if(!is_dir("../../old_intro/".$data[year]))
				{
					mkdir("../../old_intro/".$data[year], 0700);//�إߥؿ�
				}
				//�Ǵ���Ƨ� 1,2
				if(!is_dir("../../old_intro/".$data[year]."/".$data[term]))
				{
					mkdir("../../old_intro/".$data[year]."/".$data[term], 0700);//�إߥؿ�				
				}	
				//�ҵ{��Ƨ� course_id
				if(!is_dir("../../old_intro/".$data[year]."/".$data[term]."/".$data[course_id]))
				{
					mkdir("../../old_intro/".$data[year]."/".$data[term]."/".$data[course_id], 0700);//�إߥؿ�
				}				
				//�p�Gintroduction���O�Ū��A�ӥB��Ƨ��U���ɮ�
				if( $row['introduction']!= "" || is_file("../../$data[course_id]/intro/index.html") || is_file("../../$data[course_id]/intro/index.htm") || is_file("../../$data[course_id]/intro/index.doc") || is_file("../../$data[course_id]/intro/index.pdf") || is_file("../../$data[course_id]/intro/index.ppt") )
				{	
					//�ƥ���../../old_intro/$year/$term/$id/
					//�ƥ���Ʈw�̪��ɮסA�ݭn�t�~�B�z
					//echo "$row[introduction] <br>";
					if($row['introduction']!= ""){
						 $fp = fopen ("../../old_intro/".$data[year]."/".$data[term]."/".$data[course_id]."/index.html", "w");
						 copy_html_intro( $row['introduction'], $fp);
						 fclose ($fp);
						 shell_exec("cp -r ../../".$data['course_id']."/intro/*  ../../old_intro/".$data[year]."/".$data[term]."/".$data[course_id]."/");
					}
					//�ƥ���L���u�n�����ƻs�L�h�Y�i
					else{
						shell_exec("cp -r ../../".$data['course_id']."/intro/*  ../../old_intro/".$data[year]."/".$data[term]."/".$data[course_id]."/");				
					}
					//echo $row['name']."���ҵ{�j���ƥ�����<br />";
					echo "<script language=\"JavaScript\" type=\"text/JavaScript\">
						  document.all.course_progress.innerHTML = \"<font color=red>". $row['name'] ."</font> ���ҵ{�j���ƥ����� \" ; </script>";	
				}
			}
			else{
				$message = "$message - ��ƮwŪ�����~!!";
			}			
		
		}
		echo "!!!!�ơ@�@���@�@���@�@�\!!!!<br />";
	}
	else{
		$message = "$message - ��ƮwŪ�����~!!";
	}
	
	//��ܦ��\�T��
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