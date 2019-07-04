<?php
require 'fadmin.php';
update_status ("編輯測驗");

if( isset($PHPSESSID) && (session_check_teach($PHPSESSID) >= 2) )
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}
	if($action == "modifynr")
	{
		$Q1 = "SELECT name, percentage FROM exam WHERE a_id='$exam_id'";
		if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
		}
		$rows = mysql_fetch_row($result);
		$test_name = $rows[0];
		$test_ratio = $rows[1];
		modifynr ();
	}
	else if($action == "modifytestnr")
	{
		$row = CheckError();
		if($row == "null")
		{
			if($version == "C")
				$message = "請輸入考試名稱和比例!";
			else
				$message = "Please input test name or ratio!";
			modifynr ();
		}
		elseif($row == "errorvalue")
		{
			if($version == "C")
				$message = "比例須介於0~100之間";
			else
				$message = "Please input ratio between 0 and 100!";
			modifynr ();
		}
		elseif($row == "exist")
		{
			if($version == "C")
				$message = $test_name."已存在,請更換考試名稱!";
			else
				$message = "This test name $test_name exist, and please change the test name!";
			modifynr ();
		}
		else
		{
			if ( $test_type == "self_test" )
				$Q1 = "UPDATE exam SET name='$test_name',percentage='0' WHERE a_id='$exam_id'";
			else
				$Q1 = "UPDATE exam SET name='$test_name',percentage='$test_ratio' WHERE a_id='$exam_id'";
			if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
				show_page( "not_access.tpl" ,"資料庫寫入錯誤!!" );
			}
			if($version == "C")
				$message = "考試名稱和比例修改成功!";
			else
				$message = "Update Successfully !";
			show_page_d ();
		}
	}
	else if($action == "showtest")
	{
		$Q1 = "SELECT exam_id, type, question, selection1, selection2, selection3, selection4, answer, ismultiple, grade FROM tiku WHERE exam_id='$exam_id' order by a_id";
		if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
		}
		if ( mysql_num_rows($result) != 0 ) {
			include("class.FastTemplate.php3");
			$tpl = new FastTemplate("./templates");
			$tpl->define(array(main=>"showtest.tpl"));
			$tpl->assign( SKINNUM , $skinnum );
			if($version == "C") {
				$tpl->assign(IMG,"img");
				$tpl->assign(SUBMIT,"繳交試卷");
				$tpl->assign(RESET,"重新填寫");
				$tpl->assign(POINT, "分");
			}
			else {
				$tpl->assign(IMG,"img_E");
				$tpl->assign(SUBMIT,"Complete");
				$tpl->assign(RESET,"Reset");
				$tpl->assign(POINT, "Point");
			}
			$tpl->define_dynamic("rows","main");
			$qcounter = 0;
			while ( $rows = mysql_fetch_array($result) ) {
				$qcounter ++;
				$tpl->assign(QNO,$qcounter);
				$tpl->assign(QUESTION,$rows['question']);
				$tpl->assign(QGRADE,$rows['grade']);
				if ( $rows['type'] == "1" ) {
					if($rows['ismultiple'] == "0") {
						if($version == "C") {
							$tpl->assign(TYPE,"單選題");
						}
						else {
							$tpl->assign(TYPE,"Single-select");
						}
						$tpl->define(array(cont=>"showtests.tpl"));
					}
					else {
						if($version == "C") {
							$tpl->assign(TYPE,"複選題");
						}
						else {
							$tpl->assign(TYPE,"Multi-select");
						}
						$tpl->define(array(cont=>"showtestm.tpl"));
					}
					$tpl->assign(S1,$rows['selection1']);
					$tpl->assign(S2,$rows['selection2']);
					$tpl->assign(S3,$rows['selection3']);
					$tpl->assign(S4,$rows['selection4']);
				}
				else if ( $rows['type'] == "2" ) {
					$tpl->define(array(cont=>"showtestyn.tpl"));
					if($version == "C") {
						$tpl->assign(TYPE,"是非題");
						$tpl->assign(NO,"非");
						$tpl->assign(YES,"是");
					}
					else
						$tpl->assign(TYPE,"Yes & No");
				}
				else {
					$tpl->define(array(cont=>"showtestf.tpl"));
					$tpl->define_dynamic ( "row", "cont" );
					if($version == "C")
						$tpl->assign(TYPE,"填充題");
					else
						$tpl->assign(TYPE,"fill out");
					for ( $i = 1 ; $i <= $rows['answer'] ; $i ++ ) {
						$sele = "selection".$i."_".$qno;
						$tpl->assign(NUM, $i);
						$tpl->assign(ORDER, $i);
						$tpl->assign(VALUE,$$sele);
						$tpl->parse(CONT,".row");
					}
				}
				//讓parse重新抓cont指向的檔案 特殊用法
				$tpl->parse(ROWS,".rows");
				$tpl->parse(ROWS,".cont");
				$tpl->row = "";
				$tpl->CONT = "";
				$tpl->cont = "";
			}
			$tpl->assign(EXAMID,$exam_id);
			$tpl->parse(BODY,"main");
 			$tpl->FastPrint("BODY");
		}
		else {
			if($version == "C")
				$message = "此題沒有題目!!";
			else
				$message = "There is no Question on this Exam!!";
			show_page_d ();
		}
	}
	else if($action == "modifytest")
		modifytest ();
	else if($action == "updatetest")
	{
		if( $qtext == "" ) {
			if ( $version == "C" )
				$message = "請輸入題目!!";
			else
				$message = "Please Input Question!!";
			modifytest ();
			exit;
		}
		else if ($qgrade == "" ) {
			if ( $version == "C" )
				$message = "請輸入配分!!";
			else
				$message = "Please Input score!!";
			modifytest ();
			exit;
		}
		if ( $type == 1 ) {
			$checkbox=0;
			if($check_1 == "1")
				$checkbox = $checkbox + 1;
			if($check_2 == "2")
				$checkbox = $checkbox + 2;
			if($check_3 == "3")
				$checkbox = $checkbox + 4;
			if($check_4 == "4")
				$checkbox = $checkbox + 8;
			if($checkbox == 0) {
				if ( $version == "C" )
					$message = "請選擇答案!!";
				else
					$message = "Please Select Ans!!";
				modifytest ();
				exit;
			}	 
			else {
				if($cho == "0")
				{
					if(($checkbox != 1)&&($checkbox != 2)&&($checkbox != 4)&&($checkbox != 8))
					{
						if($version == "C")
							$message = "此題為單選,請勿勾選多項答案!";
						else
							$message = "It's single-select, not multi-select !";
						modifytest ();
						exit;
					}
				}
			}
		}
		if ( $type == 1 ) {
			$Q1 = "UPDATE tiku SET grade='$qgrade',question='$qtext',ismultiple='$cho',selection1='$selection1',selection2='$selection2',selection3='$selection3',selection4='$selection4',answer='$checkbox',answer_desc='$ans_link' WHERE a_id='$a_id' AND exam_id='$exam_id'";
		}
		else if ( $type == 2 ) {
			$Q1 = "UPDATE tiku SET grade='$qgrade',question='$qtext',answer='$cho',answer_desc='$ans_link' WHERE a_id='$a_id' AND exam_id='$exam_id'";
		}
		else if ( $type == 3 ) {
			$Q1 = "UPDATE tiku SET grade='$qgrade',question='$qtext',selection1='$selection1',selection2='$selection2',selection3='$selection3',selection4='$selection4',answer_desc='$ans_link',ismultiple='$cho',answer='$rownum' WHERE a_id='$a_id' AND exam_id='$exam_id'";
		}
		else {
			show_page( "not_access.tpl" ,"題型錯誤!!" );
			exit;
		}
		if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫寫入錯誤!!" );
			exit;
		}
		if ( $version == "C" )
			$message = "更新完成";
		else
			$message = "Question is updated";
		modifytest ();
	}
	else if($action == "deletequestion") {
		$Q1 = "DELETE FROM tiku WHERE a_id='$a_id'";
		if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫刪除錯誤!!" );
		}
		if ( $version == "C" )
			$message = "刪除完成";
		else
			$message = "Question is delete";
		$a_id = "";
		modifytest ();
	}
	elseif($action == "inserttest")
	{
		if( $qtext == "" ) {
			if ( $version == "C" )
				$message = "請輸入題目!!";
			else
				$message = "Please Input Question!!";
			show_content($exam_id, $type);
			exit;
		}
		else if ($qgrade == "" ) {
			if ( $version == "C" )
				$message = "請輸入配分!!";
			else
				$message = "Please Input score!!";
			show_content($exam_id, $type);
			exit;
		}	
		else if ( $type == 1 ) {
			$checkbox=0;
			if($check_1 == "1")
				$checkbox = $checkbox + 1;
			if($check_2 == "2")
				$checkbox = $checkbox + 2;
			if($check_3 == "3")
				$checkbox = $checkbox + 4;
			if($check_4 == "4")
				$checkbox = $checkbox + 8;
			if( $checkbox == 0 ) {
				if ( $version == "C" )
					$message = "請輸選擇答案!!";
				else
					$message = "Please Select Ans!!";
				show_content($exam_id, $type);
				exit;
			}
			else if($cho == "0")
			{
				if(($checkbox != 1)&&($checkbox != 2)&&($checkbox != 4)&&($checkbox != 8))
				{
					if($version == "C")
						$message = "此題為單選,請勿勾選多項答案!";
					else
						$message = "It's single-select, not multi-select !";
					show_content($exam_id, $type);
					exit;
				}
			}
		}
		if ( $type == 1 ) {
			$Q1 = "Insert into tiku ( exam_id, type, question, answer, selection1, selection2, selection3, selection4, ismultiple, grade, answer_desc ) values ( '$exam_id', '$type', '$qtext','$checkbox','$selection1','$selection2','$selection3','$selection4','$cho','$qgrade','$ans_link')";
		}
		else if ( $type == 2 ) {
			$Q1 = "Insert into tiku ( exam_id, type, question, answer, grade, answer_desc ) values ( '$exam_id', '$type', '$qtext','$cho', '$qgrade', '$ans_link')";
		}
		else if ( $type == 3 ) {
			$Q1 = "Insert into tiku ( exam_id, type, question, selection1, selection2, selection3, selection4, ismultiple, answer, grade, answer_desc ) values ( '$exam_id', '$type', '$qtext','$selection1','$selection2','$selection3','$selection4','$cho','$rownum','$qgrade','$ans_link')";
		}
		else {
			show_page( "not_access.tpl" ,"題型錯誤!!" );
			exit;
		}
		if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫寫入錯誤!!" );
		}
		if ( $version == "C" )
			$message = "加入完成";
		else
			$message = "Add Complete";
		$a_id = "";
		modifytest ();
	}
	else if($action == "newtestq")
		show_content ( $exam_id, $type );
	else if($action == "showgrade")
		showgrade();
	else if($action == "retest") {
		$Q1 = "update take_exam set grade = '-1' where exam_id = '$exam_id' and student_id = '$a_id'";
		if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			echo ("資料庫更新錯誤!!");
			exit;
		} 
		showgrade();
	}
	else if($action == "deletetest")
	{
		$Q0 = "Select name FROM exam WHERE a_id='$exam_id'";
		if ( !($result = mysql_db_query( $DB.$course_id, $Q0 ) ) ) {
			$message ="資料庫讀取錯誤!!";
			show_page_d();
			exit;
		}
		$rows = mysql_fetch_array($result);
		$Q1 = "DELETE FROM exam WHERE a_id='$exam_id'";
		if ( !($result1 = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			$message ="資料庫刪除錯誤!!";
			show_page_d();
			exit;
		}
		$Q2 = "DELETE FROM tiku WHERE exam_id='$exam_id'";
		if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
			$message ="資料庫刪除錯誤!!";
			show_page_d();
			exit;
		}
		$Q3 = "DELETE FROM take_exam WHERE exam_id='$exam_id'";
		if ( !($result3 = mysql_db_query( $DB.$course_id, $Q3 ) ) ) {
			$message ="資料庫刪除錯誤!!";
			show_page_d();
			exit;
		}
		if ( $version == "C" )
			$message = $rows['name']." 刪除完成!";
		else
			$message = $rows['name']." Delete Complete";
		show_page_d();
	}
	else if($action == "pubtest")
		pubtest();
	else if($action == "modifypubtest") {
		if ( $pub == 1 ) {
			$beg_time=$sel_year.$sel_month.$sel_day.$sel_hour.$sel_minute."00";
			$end_time=$ed_year.$ed_month.$ed_day.date("His");
			$check_range = timecount($ed_year, $ed_month, $ed_day, date(H), date(i), $sel_year, $sel_month, $sel_day, $sel_hour, $sel_minute);
		}
		else {
			$beg_time=$sel_year.$sel_month.$sel_day.$sel_hour.$sel_minute."00";
			$year=(int) $sel_year;
			$month=(int) $sel_month;
			$day=(int) $sel_day;
			$hour=(int) $sel_hour;
			$minute=(int) $sel_minute;
			switch($range)
			{
				case 1: $hour=$hour + 1;
					break;
				case 2: $hour=$hour + 2;
					break;
				case 4: $hour=$hour + 4;
					break;
				case 8: $hour=$hour + 8;
					break;
			}
			if($hour > 23)
			{
				$hour=$hour - 24;
				$day++;
			}
			if($day > 31)
			{
				$day=$day - 31;
				$month++;
			}
			if($month > 12)
			{
				$month=$month - 12;
				$year++;
			}
			if($minute < 10)
				$minute="0".$minute;
			if($hour < 10)
				$hour="0".$hour;
			if($day < 10)
				$day="0".$day;
			if($month < 10)
				$month="0".$month;
			$second = "00";
			$end_time=$year.$month.$day.$hour.$minute.$second;
		}

		if ( !isset($range) && $sure != "取消發佈" && $sure != "Not Public" && $pub != "1") {		
			if ( $version == "C" )
				$message = "請選擇時間間隔!!";
			else
				$message = "Please Choise Rang!!!";
		}	
		else {
			if ( $sure == "取消發佈" || $sure == "Not Public" ) {
				$now=date("YmdHis");
				$Q1 = "UPDATE exam SET beg_time='$now',end_time='00000000000000',public='0' WHERE a_id='$exam_id'";
			}
			else if($pub == 1)
			{
				if ( $check_range < 0 ) {
					if ( $version == "C" )
						$message = "設定錯誤!!";
					else
						$message = "Setup failure!!!";
					pubtest();
					exit;
				}	
				$Q1 = "UPDATE exam SET beg_time='$beg_time',end_time='$end_time',public='1' WHERE a_id='$exam_id'";
			}
			else {
				$Q1 = "UPDATE exam SET beg_time='$beg_time',end_time='$end_time',public='3' WHERE a_id='$exam_id'";
			}
			if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
				show_page( "not_access.tpl" ,"資料庫更新錯誤!!" );
			}
			if ( $version == "C" )
				$message = "設定完成!!";
			else
				$message = "Setup Complete!!!";
		}
		pubtest();
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
  
function show_page_d () {
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $version, $message, $course_id, $skinnum, $chap;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}
	$Q1 = "SELECT name, percentage, a_id FROM exam WHERE is_online='1' AND chap_num='$chap' ORDER BY name";
	if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
		show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
	}

	if( mysql_num_rows($result) != 0)
	{
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate("./templates");
		if($version == "C")
			$tpl->define(array(main=>"modify_test.tpl"));
		else
			$tpl->define(array(main=>"modify_test_E.tpl"));
		$tpl->define_dynamic("row","main");
		$tpl->assign( SKINNUM , $skinnum );
		$color == "#F0FFEE";
		while ( $rows = mysql_fetch_array( $result ) ) {
			if ( $color == "#F0FFEE" )
				$color = "#E6FFFC";
			else
				$color = "#F0FFEE";
			$tpl->assign( COLOR , $color );
			if($rows[1] == 0)
				if($version == "C")  
					$tpl->assign(TESTTYPE,"自我評量");
				else
					$tpl->assign(TESTTYPE,"Self-test");
			else
				if($version == "C")
					$tpl->assign(TESTTYPE,"正式測驗");
				else
					$tpl->assign(TESTTYPE,"Normal-test");
			$tpl->assign(CHAP_NUM,$chap );
			$tpl->assign(TESTNAME,$rows[0]);
			$tpl->assign(RATIO,$rows[1]);
			/////////////////////////////////////////////////////////////////////////////////////
			//modified by devon 2005-04-11
			//公佈成績與否
			$examid = $rows[2];
			$Q2 = "select public from take_exam where exam_id='$examid'";
			$result2 = mysql_db_query( $DB.$course_id, $Q2 );
			$row2 = mysql_fetch_array( $result2 );
			
			if( $row2[0] == "1" )
			{
				if($version == "C")
				{  
					$tpl->assign(ISPUBGRADE, "不公佈");
				}
				else
				{
					$tpl->assign(ISPUBGRADE, "Never_Public");
				}
			}elseif( $row2[0] == "0" )
			{
				if($version == "C")
				{  
					$tpl->assign(ISPUBGRADE, "公佈");
				}
				else
				{
					$tpl->assign(ISPUBGRADE, "Public It");
				}
			}
			/////////////////////////////////////////////////////////////////////////////////////
			$tpl->assign(EXAMID,$rows[2]);
			$tpl->parse(ROWS,".row");
		}
		$tpl->assign(MESSAGE,$message);
		$tpl->parse(BODY,"main");
 		$tpl->FastPrint("BODY");
	}
	else
	{
		if( $version=="C" )
			show_page( "not_access.tpl" ,"目前沒有任何考試可供修改!");
		else
			show_page( "not_access.tpl" ,"There is no test for modification!!");
	}
}

function modifynr () {
	global $message, $test_name,$exam_id, $test_ratio, $test_type, $version, $skinnum;
	
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate("./templates");
	if($version == "C") {
		$tpl->define( array(main=>"create_test.tpl") );
		$tpl->assign(BUTTON,"修改");
		$tpl->assign(TITLE, "修改測驗" );
	}
	else {
		$tpl->define( array(main=>"create_test_E.tpl") );
		$tpl->assign(BUTTON,"Modify");
		$tpl->assign(TITLE, "Modify Test" );
	}
	$tpl->assign( SKINNUM , $skinnum );
	$tpl->assign(IMG,"a332.gif");
	$tpl->assign(ACT1,"modify_test.php");
	$tpl->assign(ACT2,"modifytestnr");
	$tpl->assign(TEST_NAME,$test_name);
	$tpl->assign(TEST_RATIO,$test_ratio);
	if ( !isset($test_type) ) {
		if ( $test_ratio != 0 )
			$tpl->assign( REAL_TEST, "selected");
		else
			$tpl->assign( SELF_TEST, "selected");
	}
	else {
		if ( $test_type != "self_test" )
			$tpl->assign( REAL_TEST, "selected");
		else
			$tpl->assign( SELF_TEST, "selected");
	}
		
	$tpl->assign(TESTID,$exam_id);
	$tpl->assign(MESSAGE,$message);
	$tpl->parse(BODY,"main");
	$tpl->FastPrint("BODY");
}

function CheckError()
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $test_name, $test_ratio, $course_id, $test_type, $exam_id;

	if($test_name == "" || ( $test_ratio == "" && $test_type != "self_test" ))
		return "null";
	elseif(($test_ratio > 100 || $test_ratio < 0) && $test_type != "self_test")
		return "errorvalue";
	else
	{
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
		}
		$Q1 = "SELECT a_id, name FROM exam WHERE name='$test_name'";
		if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
		}
		if ( mysql_num_rows($result) != 0 ) {
			$rows = mysql_fetch_array($result);
			if( $test_name == $rows['name'] && $exam_id != $rows['a_id'] )
				return "exist";
			else
				return "ok";
		}
		else
			return "ok";
	}
}

function modifytest () {
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $version, $course_id, $exam_id, $a_id, $message, $skinnum;
	global $qgrade,$qtext,$type,$cho,$selection1,$selection2,$selection3,$selection4,$checkbox,$ans_link,$rownum;

	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}
	$Q1 = "SELECT a_id, type, question, selection1, selection2, selection3, selection4, ismultiple, answer, grade, answer_desc FROM tiku WHERE exam_id='$exam_id' order by a_id";
	if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
		show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
	}
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate("./templates");
	if($version == "C") {
		$tpl->define(array(head=>"modifytesth.tpl"));
		$tpl->define(array(main=>"modifytest.tpl"));
		$tpl->define(array(body=>"edit_testb.tpl"));
	}
	else {
		$tpl->define(array(head=>"modifytesth_E.tpl"));
		$tpl->define(array(main=>"modifytest_E.tpl"));
		$tpl->define(array(body=>"edit_testb_E.tpl"));
	}
	$tpl->define_dynamic("select","main");
	$tpl->assign(EXAMID,$exam_id);
	$tpl->assign( SKINNUM , $skinnum );	
	if ( mysql_num_rows($result) == 0 ) {
		$tpl->assign(ENDLINE, "</body><html>" );
		$tpl->assign(DELETE, "" );
		$tpl->parse(HEAD,"head");
		$tpl->FastPrint("HEAD");
	}
	else {
		$qcounter = 0;
		$number;
		while ( $rows = mysql_fetch_array($result) ) {
			$qcounter++;
			$number[$rows['a_id']] = $qcounter;
			if ( $a_id == $rows['a_id'] )
				$tpl->assign(QNO,$rows['a_id']." selected");
			else
				$tpl->assign(QNO,$rows['a_id']);
			$tpl->assign(QNN,$qcounter);
			$tpl->parse(SELECT,".select");
		}
		if ( isset($a_id) && $a_id != "" ) {
			$Q1 = "SELECT a_id, type, question, selection1, selection2, selection3, selection4, ismultiple, answer, grade, answer_desc FROM tiku WHERE exam_id='$exam_id' and a_id = '$a_id'";
			if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
				$message = "資料庫讀取錯誤!!";
			}
		}
		else
			mysql_data_seek($result,0);

		$rows = mysql_fetch_array($result);
		//head
		$tpl->assign(AID,$rows['a_id']);
		if ( $version == "C" )
			$tpl->assign(DELETE, "<input type=submit name=submit value=刪除此題>" );
		else
			$tpl->assign(DELETE, "<input type=submit name=submit value=\"Delete This Question\">" );
		$tpl->assign(ENDLINE, "" );
		$tpl->parse(HEAD,"head");
		$tpl->FastPrint("HEAD");
		//main
		$tpl->assign(MESSAGE,$message);
		$tpl->assign(NUMBER,$number[$rows['a_id']]);
		
		$tpl->parse(MAIN,"main");
		$tpl->FastPrint("MAIN");
		//body
		$tpl->assign(TYPE, $rows['type']);
		$tpl->assign(ACT1,"modify_test.php");
		if ( isset($qtext) && $qtext != "" && $a_id != "" )
			$rows['question'] = $qtext;
		if ( isset($qgrade) && $qgrade != "" && $a_id != "" )
			$rows['grade'] = $qgrade;
		//tail
		if ( isset($ans_link) && $ans_link != "" && $a_id != "" )
			$rows['answer_desc'] = $ans_link;
		if ( isset($selection1) && $selection1 != "" && $a_id != "" )
			$rows['selection1'] = $selection1;
		if ( isset($selection2) && $selection2 != "" && $a_id != "" )
			$rows['selection2'] = $selection2;
		if ( isset($selection3) && $selection3 != "" && $a_id != "" )
			$rows['selection3'] = $selection3;
		if ( isset($selection4) && $selection4 != "" && $a_id != "" )
			$rows['selection4'] = $selection4;
		$tpl->assign(ANS_LINK,$rows['answer_desc']);
		if ( $rows['type'] == 1 ) {
			if($version == "C")
				$tpl->assign(KIND,"選擇題");
			else
				$tpl->assign(KIND,"Selection");
			if($version == "C") {
				$tpl->define(array(tail=>"edit_testc.tpl"));
			}
			else {
				$tpl->define(array(tail=>"edit_testc_E.tpl"));
			}
				
			if ( isset($cho) && $cho != "" && $a_id != "" )
				$rows['ismultiple'] = $cho;
			if ( isset($checkbox) && $checkbox != "" && $a_id != "" )
				$rows['answer'] = $checkbox;

			$tpl->assign(CHO.$rows['ismultiple'],"selected");
			if($rows['answer'] == "1") {
				$tpl->assign(CHECK1,"checked");
			}
			elseif($rows['answer'] == "2") {
				$tpl->assign(CHECK2,"checked");
		        }
			elseif($rows['answer'] == "3") {
				$tpl->assign(CHECK1,"checked");
				$tpl->assign(CHECK2,"checked");
			}
			elseif($rows['answer'] == "4") {
				$tpl->assign(CHECK3,"checked");
			}
			elseif($rows['answer'] == "5") {
				$tpl->assign(CHECK1,"checked");
				$tpl->assign(CHECK3,"checked");
			}
			elseif($rows['answer'] == "6") {
				$tpl->assign(CHECK2,"checked");
				$tpl->assign(CHECK3,"checked");
			}
			elseif($rows['answer'] == "7") {
				$tpl->assign(CHECK1,"checked");
				$tpl->assign(CHECK2,"checked");
				$tpl->assign(CHECK3,"checked");
			}
			elseif($rows['answer'] == "8") {
				$tpl->assign(CHECK4,"checked");
			}
			elseif($rows['answer'] == "9") {
				$tpl->assign(CHECK1,"checked");
				$tpl->assign(CHECK4,"checked");
			}
			elseif($rows['answer'] == "10") {
				$tpl->assign(CHECK2,"checked");
				$tpl->assign(CHECK4,"checked");
			}
			elseif($rows['answer'] == "11") {
				$tpl->assign(CHECK1,"checked");
				$tpl->assign(CHECK2,"checked");
				$tpl->assign(CHECK4,"checked");
			}
			elseif($rows['answer'] == "12") {
				$tpl->assign(CHECK3,"checked");
				$tpl->assign(CHECK4,"checked");
			}
			elseif($rows['answer'] == "13") {
				$tpl->assign(CHECK1,"checked");
				$tpl->assign(CHECK3,"checked");
				$tpl->assign(CHECK4,"checked");
			}
			elseif($rows['answer'] == "14") {
				$tpl->assign(CHECK2,"checked");
				$tpl->assign(CHECK3,"checked");
				$tpl->assign(CHECK4,"checked");
			}
			elseif($rows['answer'] == "15") {
				$tpl->assign(CHECK1,"checked");
				$tpl->assign(CHECK2,"checked");
				$tpl->assign(CHECK3,"checked");
				$tpl->assign(CHECK4,"checked");
			}
			$rows['selection1'] = stripslashes( $rows['selection1'] );
			$rows['selection2'] = stripslashes( $rows['selection2'] );
			$rows['selection3'] = stripslashes( $rows['selection3'] );
			$rows['selection4'] = stripslashes( $rows['selection4'] );
			$tpl->assign(SEL1,$rows['selection1']);
			$tpl->assign(SEL2,$rows['selection2']);
			$tpl->assign(SEL3,$rows['selection3']);
			$tpl->assign(SEL4,$rows['selection4']);
		}
		else if ( $rows['type'] == 2 ) {
			if($version == "C")
				$tpl->assign(KIND,"是非題");
			else
				$tpl->assign(KIND,"Yes & No");
			if($version == "C") {
				$tpl->define(array(tail=>"edit_testyn.tpl"));
			}
			else {
				$tpl->define(array(tail=>"edit_testyn_E.tpl"));
			}
			if ( isset($cho) && $cho != "" && $a_id != "" )
				$rows['answer'] = $cho;

			$tpl->assign(CHO.$rows['answer'],"selected");
		}
		else {
			if($version == "C")
				$tpl->assign(KIND,"填充題");
			else
				$tpl->assign(KIND,"fill out");
			$tpl->define(array(setrow=>"edit_testft.tpl"));
			if($version == "C") {
				$tpl->assign(ROWNUM, "請選擇空格數");
				$tpl->assign( TITLE, "空格數" );
			}
			else {
				$tpl->assign(ROWNUM, "Num of Blank");
				$tpl->assign( TITLE, "Set Num of Blank" );
			}
			if ( isset($rownum) && $rownum != "" && $a_id != "" )
				$rows['answer'] = $rownum;
			if ( isset($cho) && $cho != "" && $a_id != "" )
				$rows['ismultiple'] = $cho;
			
			$tpl->assign(RO.$rows['answer'], "selected");
			$tpl->assign(CHO.$rows['ismultiple'],"selected");
			$tpl->assign(ACT2,"modifytest");
			if ( $rows['answer'] == 0 || $rows['answer'] == NULL )
				$tpl->assign(ENDLINE, "</body></html>");
			$tpl->parse(SETROW,"setrow");
			$tpl->FastPrint("SETROW");
			
			if($version == "C")
				$tpl->define(array(tail=>"edit_testf.tpl"));
			else
				$tpl->define(array(tail=>"edit_testf_E.tpl"));
			$tpl->define_dynamic("row","tail");
			
			for ( $i = 1 ; $i <= $rows['answer']; $i++ ) {
				$sele = "selection".$i;
				$tpl->assign(NUM, $i);
				$tpl->assign(ORDER, $i);
				$tpl->assign(VALUE, $rows["$sele"]);
				$tpl->parse(INPUT,".row");
			}
		}
		$tpl->assign(BUTTON,"");
		if ( $version == "C" ) {			
			$tpl->assign(SUBMIT,"修改");
		}
		else {
			$tpl->assign(SUBMIT,"Modify");
		}
		if ( !($type == "3" && ($rows['answer'] == "0" || $rows['answer'] == "") ) ) {
			//body
			$tpl->assign(QGRADE, $rows['grade']);
			$rows['question'] = stripslashes( $rows['question'] );
			$tpl->assign(QTEXT, $rows['question']);
			$tpl->assign(ROW,$rows['answer']);
			$tpl->assign(ACT2,"updatetest");
			$tpl->parse(BODY,"body");
			$tpl->FastPrint("BODY");
			//tail
			$tpl->parse(TAIL,"tail");
			$tpl->FastPrint("TAIL");
		}
	}
}
function show_content ( $test_id = "", $type ) {
	global $message, $version, $course_id, $selection1, $selection2, $selection3, $selection4, $check_1, $check_2, $check_3, $check_4, $qgrade, $qtext, $cho, $ans_link, $rownum, $skinnum;
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}
	$Q1 = "SELECT * FROM tiku WHERE exam_id = '$test_id'";
	if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
		show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
	}
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate("./templates");
	if($version == "C")
		$tpl->define(array(main=>"edit_testh.tpl"));
	else
		$tpl->define(array(main=>"edit_testh_E.tpl"));
	$tpl->assign( SKINNUM , $skinnum );
	$num = mysql_num_rows($result);
	
	$tpl->assign(QNO,$num + 1);
	$tpl->assign(IMG,"a332.gif");
	$tpl->assign(ACT1,"modify_test.php");
	$tpl->assign(ACT2,"newtestq");
	$tpl->assign(TP.$type,"selected");
	$tpl->assign(EXAMID,$test_id);
	$tpl->assign(MESSAGE, $message);
	if ( $type == 0 )
		$tpl->assign(ENDLINE, "</BODY></HTML>" );
	else
		$tpl->assign(ENDLINE, "" );
	$tpl->parse(BODY,"main");
	$tpl->FastPrint("BODY");
	if ( $type != 0 ) {
		$tpl->assign(ANS_LINK,$ans_link);
		$tpl->assign(TYPE,$type);
		if ( $type == 1 ) {
			if($version == "C")
				$tpl->assign(KIND,"選擇題");
			else
				$tpl->assign(KIND,"Selection");
			if($version == "C")
				$tpl->define(array(choice=>"edit_testc.tpl"));
			else
				$tpl->define(array(choice=>"edit_testc_E.tpl"));
			$tpl->assign(SEL1, $selection1);
			$tpl->assign(SEL2, $selection2);
			$tpl->assign(SEL3, $selection3);
			$tpl->assign(SEL4, $selection4);
			if ( $check_1 == 1 )
				$tpl->assign(CHECK1,"checked" );
			if ( $check_2 == 2 )
				$tpl->assign(CHECK2,"checked" );
			if ( $check_3 == 3 )
				$tpl->assign(CHECK3,"checked" );
			if ( $check_4 == 4 )
				$tpl->assign(CHECK4,"checked" );
			
			$tpl->assign(CHO.$cho,"selected");
		}
		else if ( $type == 2 ) {
			if($version == "C")
				$tpl->assign(KIND,"是非題");
			else
				$tpl->assign(KIND,"Yes & No");
			if($version == "C")
				$tpl->define(array(choice=>"edit_testyn.tpl"));
			else
				$tpl->define(array(choice=>"edit_testyn_E.tpl"));
			
			$tpl->assign(CHO.$cho, "selected");
		}
		else {
			if($version == "C")
				$tpl->assign(KIND,"填充題");
			else
				$tpl->assign(KIND,"fill out");
			$tpl->define(array(setrow=>"edit_testft.tpl"));
			if($version == "C") {
				$tpl->assign(ROWNUM, "請選擇空格數");
				$tpl->assign( TITLE, "空格數" );
			}
			else {
				$tpl->assign(ROWNUM, "Num of Blank");
				$tpl->assign( TITLE, "Set Num of Blank" );
			}
			$tpl->assign(RO.$rownum, "selected");
			$tpl->parse(SETROW,"setrow");
			$tpl->FastPrint("SETROW");
			
			if($version == "C")
				$tpl->define(array(choice=>"edit_testf.tpl"));
			else
				$tpl->define(array(choice=>"edit_testf_E.tpl"));
			$tpl->define_dynamic("row","choice");
			$tpl->assign(CHO.$cho,"selected");
			for ( $i = 1 ; $i <= $rownum; $i++ ) {
				$sele = "selection".$i;
				$tpl->assign(NUM, $i);
				$tpl->assign(ORDER, $i);
				$tpl->assign(VALUE, $$sele);
				$tpl->parse(INPUT,".row");
			}
		}
		$tpl->assign(BUTTON,"");
		if ( $version == "C" ) {
			
			$tpl->assign(SUBMIT,"加入");
		}
		else {
			$tpl->assign(SUBMIT,"ADD");
		}
		if ( !($type == "3" && ($rownum == "0" || $rownum == "") ) ) {
			//tail
			if($version == "C")
				$tpl->define(array(tail=>"edit_testb.tpl"));
			else
				$tpl->define(array(tail=>"edit_testb_E.tpl"));
			$tpl->assign(QGRADE,$qgrade);
			$tpl->assign(QTEXT,$qtext);
			$tpl->assign(ROW,$rownum);
			$tpl->assign(ACT2,"inserttest");
			$tpl->parse(TAIL,"tail");
			$tpl->FastPrint("TAIL");
			//choice
			$tpl->parse(CHI,"choice");
			$tpl->FastPrint("CHI");
		}
	}
}

function showgrade () {
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $exam_id, $version, $course_id, $message, $skinnum, $course_year, $course_term;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}
	$Q1 = "SELECT u.a_id, u.id, u.name FROM user u, take_course tc WHERE tc.course_id='$course_id' and tc.student_id = u.a_id and tc.credit = '1' and tc.year='$course_year' and tc.term = '$course_term'";
	if ( !($result1 = mysql_db_query( $DB, $Q1 ) ) ) {
		show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
	}
	if ( mysql_num_rows($result1) != 0 ) {
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate("./templates");
		if($version == "C")
			$tpl->define(array(main=>"showgrade.tpl"));
		else
			$tpl->define(array(main=>"showgrade_E.tpl"));
		$tpl->define_dynamic("grade","main");
		$tpl->assign( SKINNUM , $skinnum );
		$color == "#F0FFEE";
		while ( $rows = mysql_fetch_array($result1) )
		{
			if ( $color == "#F0FFEE" )
				$color = "#E6FFFC";
			else
				$color = "#F0FFEE";
			$tpl->assign( COLOR , $color );
			$Q2 = "SELECT grade FROM take_exam WHERE exam_id = '$exam_id' and student_id='".$rows['a_id']."'";
			if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
				$message ="資料庫讀取錯誤!!";
				show_page_d();
				exit;
			}
			$rows2 = mysql_fetch_array($result2);
			$tpl->assign(SNO,$rows[1]);
			$tpl->assign(SNN,$rows[2]);
			if ( $rows2[0] != "-1" )
				$tpl->assign(GRADE,$rows2[0]);
			else {
				if ( $version == "C" )
					$tpl->assign(GRADE,"未考試");
				else
					$tpl->assign(GRADE,"N/A");
			}
			$tpl->assign( AID, $rows['a_id'] );
			$tpl->assign( EXAMID, $exam_id );
			$tpl->parse(GRADE,".grade");
		}
		$tpl->parse(BODY,"main");
		$tpl->FastPrint("BODY");
	}
	else {
		if( $version=="C" )
			show_page( "not_access.tpl" ,"此課程尚未有任何學生!!");
		else
			show_page( "not_access.tpl" ,"There is no student in this class!!");
	}
}

function pubtest () {
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $exam_id, $version, $course_id, $message, $skinnum;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}
	$Q1 = "SELECT name,beg_time,end_time,public FROM exam WHERE a_id='$exam_id' AND is_online='1'";
	if ( !($result1 = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
		show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
	}
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate("./templates");
	if ( $version == "C" )
		$tpl->define(array(main=>"pubtest.tpl"));
	else
		$tpl->define(array(main=>"pubtest_E.tpl"));
	$tpl->define_dynamic("yy","main");
	$tpl->define_dynamic("ye","main");
	$tpl->assign( SKINNUM , $skinnum );
	$row1 = mysql_fetch_row( $result1 );
	$tpl->assign(TESTNAME,$row1[0]);
	$tpl->assign(EXAMID,$exam_id);
	if(($row1[2] == "00000000000000")&&($row1[3] == "0"))
	{  
		if($version == "C")  
			$tpl->assign(STATUS,"未發佈");
		else
			$tpl->assign(STATUS,"Never Public");
		$end_day = date("d");
		$end_month = date("m");
		$beg_day=date("d");
		$beg_month=date("m");
		$bh= date("H");
		$bm= date("i");
	}
	else
	{
		$beg_y = (int) substr($row1[1],0,4);
		$beg_mo = (int) substr($row1[1],4,2);
		$beg_d = (int) substr($row1[1],6,2);
		$beg_h = (int) substr($row1[1],8,2);
		$beg_m = (int) substr($row1[1],10,2);
		$end_y = (int) substr($row1[2],0,4);
		$end_mo = (int) substr($row1[2],4,2);
		$end_d = (int) substr($row1[2],6,2);
		$end_h = (int) substr($row1[2],8,2);
		$end_m = (int) substr($row1[2],10,2);
		$range = timecount($end_y, $end_mo, $end_d, $end_h, $end_m, $beg_y, $beg_mo, $beg_d, $beg_h, $beg_m);
		if ( $row1[3] != 1 ) {
			switch($range)
			{	
				case 1: $tpl->assign(CHECK1,"checked");
					$tpl->assign(CHECK2,"");
					$tpl->assign(CHECK3,"");
					$tpl->assign(CHECK4,"");
					break;
				case 2: $tpl->assign(CHECK1,"");
					$tpl->assign(CHECK2,"checked");
					$tpl->assign(CHECK3,"");
					$tpl->assign(CHECK4,"");
					break;
				case 4: $tpl->assign(CHECK1,"");
					$tpl->assign(CHECK2,"");
					$tpl->assign(CHECK3,"checked");
					$tpl->assign(CHECK4,"");
					break;
				case 8: $tpl->assign(CHECK1,"");
					$tpl->assign(CHECK2,"");
					$tpl->assign(CHECK3,"");
					$tpl->assign(CHECK4,"checked");
					break;
				default:
					if($version == "C")  
						$tpl->assign(STATUS,"未發佈");
					else
						$tpl->assign(STATUS,"Never Public");
					break;
			}
			$end_day = substr($row1[2],6,2);
			$end_month = substr($row1[2],4,2);
		}
		else {
			$end_day = substr($row1[2],6,2);
			$end_month = substr($row1[2],4,2);
		}
		if ( $range >= 0 ) {
			if ( $row1[3] != 1 ) {
				if($version == "C" )
					$tpl->assign(STATUS,date("Y-m-d H:i",mktime(substr($row1[1],8,2),substr($row1[1],10,2),0,substr($row1[1],4,2),substr($row1[1],6,2),substr($row1[1],0,4)))."發佈 維期 $range 小時");
				else
					$tpl->assign(STATUS,date("Y-m-d H:i",mktime(substr($row1[1],8,2),substr($row1[1],10,2),0,substr($row1[1],4,2),substr($row1[1],6,2),substr($row1[1],0,4)))."Public During $range Hourse");
			}
			else {
				if($version == "C" )
					$tpl->assign(STATUS,date("Y-m-d H:i",mktime(substr($row1[1],8,2),substr($row1[1],10,2),0,substr($row1[1],4,2),substr($row1[1],6,2),substr($row1[1],0,4)))."發佈 ".date("Y-m-d H:i",mktime(substr($row1[2],8,2),substr($row1[2],10,2),0,substr($row1[2],4,2),substr($row1[2],6,2),substr($row1[2],0,4)))."結束");
				else
					$tpl->assign(STATUS,date("Y-m-d H:i",mktime(substr($row1[1],8,2),substr($row1[1],10,2),0,substr($row1[1],4,2),substr($row1[1],6,2),substr($row1[1],0,4)))."Public, End at".date("Y-m-d H:i",mktime(substr($row1[2],8,2),substr($row1[2],10,2),0,substr($row1[2],4,2),substr($row1[2],6,2),substr($row1[2],0,4))));
			}
		}
		$beg_day=substr($row1[1],6,2);
		$beg_month=substr($row1[1],4,2);
		$bh= substr($row1[1],8,2);
		$bm= substr($row1[1],10,2);
	}
	for($j=0;$j < 3;$j++)
	{
		$year = date("Y")+$j;
		if ( $year == substr( $row1[1], 0, 4 ) )
			$tpl->assign(YEAV, "$year selected");
		else
			$tpl->assign(YEAV,$year);
		$tpl->assign(YEAR,$year);
		$tpl->parse(YY,".yy");
	}
	for($j=0;$j < 3;$j++)
	{
		$year = date("Y")+$j;
		if ( ($j == 2 && $row1[3] != 1 && $row1[3] != 3 ) || ($year == substr( $row1[2], 0, 4 ) && $row1[3] == 1)  )
			$tpl->assign(YEAEV, "$year selected");
		else
			$tpl->assign(YEAEV,$year);
		$tpl->assign(YEAED,$year);
		$tpl->parse(YE,".ye");
	} 
	
	$DV = "DA".$beg_day;
	$MV = "MA".$beg_month;
	$tpl->assign($DV, "selected");	
	$tpl->assign($MV , "selected");

	$HV = "HB".$bh;
	$BV = "MB".$bm;
	$tpl->assign($HV, "selected");	
	$tpl->assign($BV , "selected");
	
	$DEV = "DE".$end_day;
	$MDV = "MOE".$end_month;
	$tpl->assign($DEV, "selected");	
	$tpl->assign($MDV , "selected");
	if ( $row1[3] == 1 )
		$tpl->assign(PUB, "checked" );
	$tpl->assign(MESSAGE, $message);
	$tpl->parse(BODY,"main");
	$tpl->FastPrint("BODY");
}

?>
