<?PHP
/*
 �M���@�~�H�~���ҵ{
 �R��course�����ӵ������Bdrop db�Bremove htdocs�U�ӽҸ����ɮץؿ�
*/
require 'fadmin.php';
?>
<html>
<head>
<title>��s�}��</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
</head>
<body background = "/images/img/bg.gif"><center>
<div id="progress">	�@
</div>

<?PHP
if ( isset($PHPSESSID) && session_check_admin($PHPSESSID) ) {
	$success = true;
	echo "�M���L�Ľҵ{��ƶ}�l<br>";
	if( del_unused_course() != -1)
	{
		$success = false;
		echo "�M���L�Ľҵ{��ƿ��~<br>";
	}	
	
	if($success == true){
		echo "�M���L�Ľҵ{��Ƨ���!!<br>";
	}
	else{
		echo "�M���L�Ľҵ{��ƥ���<br>";
	}
	
	echo "<br><a href=../check_admin.php>�^�t�κ޲z����</a>";
}
else
	show_page( "index_ad.tpl", "�A���v�����~�A�Э��s�n�J!!");

function del_unused_course(){
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;
	
	// �s��mysql
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$error = "��Ʈw�s�����~!!";
		return $error;
	}
	
	//��X���Ǵ����Ǧ~�Ǵ����
	$qs1 = "SELECT DISTINCT year, term FROM teach_course ORDER BY year DESC, term DESC";
	if ($result1 = mysql_db_query($DB, $qs1)){
		if(($row1 = mysql_fetch_array($result1))==0){
			$error = "�Ǵ���Ƥ��s�b!!<BR>";
			return $error;
		}
	}
	else{
		$error = "mysql��ƮwŪ�����~1!!<br>";
		return $error;
	}
	$now_year = $row1['year'];
	$now_term = $row1['term'];
	$pre_year = "";
	$pre_term = "";;
	if($now_term =="1")
	{
		$pre_year = $now_year - 1;
		$pre_term = 2;
	}
	else{
		$pre_year = $now_year;
		$pre_term = 1;
	}
	
	
	
	
	//log �}��
	$log_fp = fopen("/home/study/logs/del_unused_course_".$now_year."_".$now_term.".log", "a");
	$count = 0;
	//
	
	//��X�Ҧ�course���
	
	$qs2 = "SELECT * FROM course order by a_id";
	if ($result2 = mysql_db_query($DB, $qs2)){
		//�p��i�ץ�
		$realcount=0;
		$temp = -1;
		$total = mysql_num_rows($result2);
		echo "�`�@ $total ����<br>";
		ob_end_flush();
		ob_implicit_flush(1);
		//
		while($row2 = mysql_fetch_array($result2)){
			//�p��i�ץ�
			$realcount++;	
			$p = number_format((100*$realcount)/$total);
			if($p>$temp){
				echo "<script language=\"JavaScript\" type=\"text/JavaScript\">
						document.all.progress.innerHTML = \"�T�{�Χ�s���A�еy�J $p%\" ; </script>";
			}
			$temp = $p;
			//
			$qs3 = "SELECT count(teacher_id) as teach_num FROM teach_course where course_id='$row2[a_id]' AND ((year='$now_year' AND term='$now_term') OR (year='$pre_year' AND term='$pre_term'))";
			if ($result3 = mysql_db_query($DB, $qs3)){
				$row3 = mysql_fetch_array($result3);
				//�p�G1�~������Ƨ䤣��A�h�R���ҵ{
				if( $row3["teach_num"]==0){
					//log�_��
					$count++;
					echo "�R���ҵ{:".$row2['a_id']."<BR>";
					$log_content = "�R���ҵ{:".$row2['a_id']."\n";
					fwrite($log_fp, $log_content);
					//
					del_course ($row2['a_id'],$log_fp );
				}
			}
			else{
				$error = "mysql��ƮwŪ�����~3!!<br>";
				return $error;
			}
		}			
	}
	else{
		$error = "mysql��ƮwŪ�����~2!!<br>";
		return $error;
	}
	
	//��LOG��
	
	echo "�`�@�R��:".$count."�Ӹ�Ʈw<BR>";
	$log_content = "�`�@�R��:".$count."�Ӹ�Ʈw\n";
	fwrite($log_fp, $log_content);
	fclose($log_fp);
	
	return -1;
}
	
function del_course ($course_aid,$log_fp ) {
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	
	$Q1 = "delete from course where a_id = '$course_aid'";
//	$Q2 = "delete from teach_course where course_id = '$course_aid'";
	$Q3 = "drop database study$course_aid";
	$Q4 = "drop database coop$course_aid";
	
	$error = -1;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$error = "��Ʈw�s�����~1!!";
	}
	for ( $i = 1 ; $i <= 1 ; $i ++ ) {
		$Q = "Q$i";
		$log_content = "����".$$Q."\n";
		fwrite($log_fp, $log_content);

		if ( !( mysql_db_query( $DB, $$Q ) ) ) {
			$error = "$error - ��Ʈw�R�����~2$i!!";
		}

	}
	//���R���ϥΪ�
/*		
	$U1 = "select student_id from take_course where course_id = '$course_aid'";
	$U2 = "delete from take_course where course_id = '$course_aid'";
	if ( !($result1 = mysql_db_query( $DB, $U1 ) ) ) {
		$error = "$error - ��ƮwŪ�����~3!!";
	}
	if ( !($result = mysql_db_query( $DB, $U2 ) ) ) {
		$error = "$error - ��Ʈw�R�����~4!!";
	}
	while ( $row1 = mysql_fetch_array( $result1 ) ) {
		$U3 = "select * from take_course where student_id = '".$row1['student_id']."'";
		if ( $result = mysql_db_query( $DB, $U3 ) ) {
			if ( mysql_num_rows( $result ) == 0 ) {
				$U4 = "delete from user where a_id = '".$row1['student_id']."'";
				$U5 = "delete from log where user_id = '".$row1['student_id']."'";
				$U6 = "delete from gbfriend where my_id = '".$row1['student_id']."' or friend_id='".$row1['student_id']."'";
				if ( !( mysql_db_query( $DB, $U4 ) ) ) {
					$error = "$error - ��Ʈw�R�����~5!!";
				}
				if ( !( mysql_db_query( $DB, $U5 ) ) ) {
					$error = "$error - ��Ʈw�R�����~6!!";
				}
				if ( !( mysql_db_query( $DB, $U6 ) ) ) {
					$error = "$error - ��Ʈw�R�����~6!!";
				}
			}
		}
		else
			$error = "$error - ��Ʈw�R�����~7!!";
	}
*/	
	$log_content = "����".$Q3."\n";
	fwrite($log_fp, $log_content);	

	if ( !( mysql_query( $Q3 , $link ) ) ) {
			$error = "$error - ��Ʈw�R�����~8!!";
	}

	//���R�X�@�ǲ߸�Ʈw�A�]���]�S��^^"
/*
	if ( !( mysql_query( $Q4 , $link ) ) ) {
			$error = "$error - ��Ʈw�R�����~9!!";
	}
*/	
	$target = "../../".$course_aid;
	$cmd ="rm -rfv ".$target;
	$output = shell_exec($cmd);
	$log_content = "�R���ؿ�".$target.": ".$output."\n";
	fwrite($log_fp, $log_content);	

	$target = "/backup/".$course_aid;
	$cmd ="rm -rfv ".$target;
	$output = shell_exec($cmd);
	$log_content = "�R���ؿ�".$target.": ".$output."\n";
	fwrite($log_fp, $log_content);	

	$log_content ="\n";
	fwrite($log_fp, $log_content);	
	return $error;
}

?>
</div>
</center>
</body>
</html>