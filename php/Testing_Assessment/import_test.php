<?php
require 'fadmin.php';
update_status ("匯出測驗");

if( isset($PHPSESSID) && (session_check_teach($PHPSESSID) == 2) )
{
	if ( $action == "export" ) {
		export ();
	}
	else if ( $action == "import_file" ) {
		import ();
	}
	else
		show_page_d();
}
else
{
	if( $version=="C" )
		show_page( "not_access.tpl" ,"你沒有權限使用此功能");
	else
		show_page( "not_access.tpl" ,"You have No Permission!!");
}

function import () {
	global $version, $course_id, $importfile, $course_year, $course_term;
	check ();
	$fp = fopen ($importfile, "r");
	while(!feof($fp))
	{
		$exam_q=$exam_q.fgets($fp,80);
	}
	fclose($fp);
	$temp = explode ( "\n", $exam_q );
	$exam_cont;
	$qno = 0;
	for($i=0;$i<count($temp)-1;$i++) {
		$tmp2 = substr ( $temp[$i] , 0, strlen( $temp[$i] ) );
		if ( $tmp2 == "") {
			continue;
		}
		$temp2 = explode ( ":",$tmp2 );
		if ( $temp2[0] == "name" ) {
			$exam_cont[0]["name"] = $temp2[1];
		}
		else if ( $temp2[0] == "random" ) {
			$exam_cont[0]["random"] = $temp2[1];
		}
		else if ( $temp2[0] == "percentage" ) {
			$exam_cont[0]["percentage"] = $temp2[1];
		}
		else if ( $temp2[0] == "type" ) {
			$exam_cont[0]["type"] = $temp2[1];
		}
		else if ( $temp2[0] == "qno" ) {
			$qno ++;
			for ( $j = $i+1 ; $j < count($temp); $j ++ ) {
				$tmp3 = substr ( $temp[$j] , 0, strlen( $temp[$j] ) );
				if ( $tmp3 == "" ) {
					continue;
				}
				$temp3 = explode ( ":",$tmp3 );
				if ( $temp3[0] == "qno" ) {
					$i = $j - 1;
					break;
				}
				$exam_cont[$temp2[1]][$temp3[0]] = $temp3[1];
			}
		}
	}
	$exam_cont[0]["qno"] = $qno;
	
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	$exam_cont[0]["name"] = addslashes( $exam_cont[0]["name"] );
	$Q1 = "INSERT INTO exam (name,percentage, beg_time) values ('".$exam_cont[0]["name"]."','".$exam_cont[0]["percentage"]."', '00000000000000')";
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		echo ( "資料庫連結錯誤!!" );
		return;
	}
	if ( !($resultOBJ = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
		echo ( "資料庫讀取錯誤!!" );
		return;
	}
	$exam_id = mysql_insert_id ();
	
	$Q2 = "SELECT tc.student_id, u.id FROM take_course tc,user u WHERE tc.course_id = '$course_id' AND tc.student_id = u.a_id and tc.year='$course_year' and tc.term = '$course_term'";
	if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) ) {
		show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
	}
	while ( $row2 = mysql_fetch_array($result2) ) {
		$Q3 = "INSERT INTO take_exam (exam_id,student_id,grade) values ('$exam_id','$row2[0]','-1')";
		mysql_db_query($DB.$course_id,$Q3);
	}
	
	for ( $i = 1; $i <= $exam_cont[0]["qno"] ; $i ++ ) {
		$exam_cont[$i]["question"] = addslashes( $exam_cont[$i]["question"] );
		$exam_cont[$i]["selection1"] = addslashes( $exam_cont[$i]["selection1"] );
		$exam_cont[$i]["selection2"] = addslashes( $exam_cont[$i]["selection2"] );
		$exam_cont[$i]["selection3"] = addslashes( $exam_cont[$i]["selection3"] );
		$exam_cont[$i]["selection4"] = addslashes( $exam_cont[$i]["selection4"] );
		$exam_cont[$i]["answer_desc"] = addslashes( $exam_cont[$i]["answer_desc"] );
		$Q4 = "Insert into tiku ( exam_id, type, question, answer, selection1, selection2, selection3, selection4, ismultiple, grade, answer_desc ) values ( '$exam_id', '".$exam_cont[$i]["type"]."', '".$exam_cont[$i]["question"]."','".$exam_cont[$i]["answer"]."','".$exam_cont[$i]["selection1"]."','".$exam_cont[$i]["selection2"]."','".$exam_cont[$i]["selection3"]."','".$exam_cont[$i]["selection4"]."','".$exam_cont[$i]["ismultiple"]."','".$exam_cont[$i]["grade"]."','".$exam_cont[$i]["answer_Desc"]."')";
		mysql_db_query($DB.$course_id,$Q4);
	}
	
	if( $version=="C" )
		$message = "匯入完成!!!";
	else
		$message = "Import Completed!!!";
	show_page_d ( $message );
}

function check () {
	global $version, $course_id, $importfile_name, $importfile;
	if ( isset ( $importfile ) ) {
		if($importfile=="none")
		{
			if( $version=="C" )
				$message = "尚未上傳測驗檔案!!!";
			else
				$message = "There is no file to upload!!!";
			show_page_d ( $message );
			exit;
		}
		else {
			$fp = fopen ($importfile, "r");
			while(!feof($fp))
			{
				$exam_q=$exam_q.fgets($fp,80);
			}
			fclose($fp);
			$content = explode("\n",$exam_q);
			$testExpression=true;
			for($i=0;$i<count($content);$i++) {
				$content[$i] = substr ( $content[$i] , 0, strlen( $content[$i] ) );
				if ( $content[$i] == "" )
					continue;
				if($content[$i]=="")
					$Pos=-1;
				else
					$Pos=strpos($content[$i],":");
				if( $Pos==0 ) {
					$testExpression=false;
					break;
				}
			}
			if(!$testExpression )
			{
				if($version=="C")
					$message = "格式可能有錯誤,請檢查!!!";
				else
					$message = "The format may have errors,please check!!!";
				show_page_d ( $message );
				exit;
			}
		}
	}
	else {
		show_page_d();
		exit;
	}
}

function show_page_d ( $message = "" ) {
	global $version, $course_id;
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate ( "./templates" );
	$tpl->define ( array ( body => "import_test.tpl" ) );
	if ( $version == "C" ) {
		$tpl->assign( IMG , "img" );
		$tpl->assign( IMPROT_DATA, "請選擇本系統匯出的測驗檔<br>\n" );
		$tpl->assign( IMPORT_DISC, "上傳匯入檔" );
	}
	else {
		$tpl->assign( IMG , "img_E" );
		$tpl->assign( IMPROT_DATA, "Please Choise the test file<br>\n" );
		$tpl->assign( IMPORT_DISC, "Upload Import File" );
	}
	$tpl->assign( ACT1 , "import_test.php" );
	$tpl->assign( MSG , $message );
	$tpl->parse( BODY, "body" );
	$tpl->FastPrint("BODY");
}

function export () {
	
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id, $exam_id, $SERVER_NAME;
	$Q1 = "Select * From exam Where a_id='$exam_id'";
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		echo ( "資料庫連結錯誤!!" );
		return;
	}
	if ( !($resultOBJ = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
		echo ( "資料庫讀取錯誤!!" );
		return;
	}
	$row = mysql_fetch_array ( $resultOBJ );

	if ( !is_dir ( "../../$course_id/exam" ) ) {
		mkdir ( "../../$course_id/exam", 0771 );
		chmod ( "../../$course_id/exam", 0771 );
	}
	$file_name="../../$course_id/exam/exam".$exam_id.".txt";

	if( file_exists($file_name) )
  		unlink($file_name);

//#########################//
	$Q2 = "Select * From tiku Where exam_id='$exam_id'";

	if ( !($resultOBJ1 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
		echo ( "資料庫讀取錯誤!!" );
		return;
	}
	if( mysql_num_rows ( $resultOBJ1 ) == 0 )
		return;
	else {
  		$file=fopen("$file_name","w");
	}

//#########################//
	$random = $row['random'];
	$percentage = $row['percentage'];
	$name = $row['name'];
	fwrite($file,"name:$name\nrandom:$random\npercentage:$percentage\n");
	$qno = 1;
	while( $row1 = mysql_fetch_array ( $resultOBJ1 ) )
	{
		$type = $row1['type'];
		$question = $row1['question'];
		$selection1 = $row1['selection1'];
		$selection2 = $row1['selection2'];
		$selection3 = $row1['selection3'];
		$selection4 = $row1['selection4'];
		$ismultiple = $row1['ismultiple'];
		$answer = $row1['answer'];
		$grade = $row1['grade'];
		$answer_desc = $row1['answer_desc'];
		$question_media = $row1['question_media'];
		$answer_media = $row1['answer_media'];
		fwrite($file,"qno:$qno\n");
		fwrite($file,"type:$type\n");
		fwrite($file,"question:$question\n");
		fwrite($file,"selection1:$selection1\n");
		fwrite($file,"selection2:$selection2\n");
		fwrite($file,"selection3:$selection3\n");
		fwrite($file,"selection4:$selection4\n");
		fwrite($file,"ismultiple:$ismultiple\n");
		fwrite($file,"answer:$answer\n");
		fwrite($file,"grade:$grade\n");
		fwrite($file,"answer_desc:$answer_desc\n");
		fwrite($file,"question_media:$question_media\n");
		fwrite($file,"answer_media:$answer_media\n");
		$qno ++;
	}
	fclose($file);
	header ( "Location: http://$SERVER_NAME/$course_id/exam/exam".$exam_id.".txt" );
}
?>
