<?php
require 'fadmin.php';
require '../CoreDescript.php';
update_status ("處理成績");

if(isset($PHPSESSID) && session_check_teach($PHPSESSID) == 2)
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_year, $course_term;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}
	if($action == "modify") {
		if( ($Submit == "修改成績") || ($Submit == "Modify_Score") ) {
			modify_score ();
		}
		elseif( ($Submit == "修改名稱與比例") || ($Submit == "Modify_Name_and_Ratio") ) {
			modifynameandratio ();
		}
		else {
			if($version == "C")
				$message = "選擇錯誤!";
			else
				$message = "Bad Choise!";
			show_page_d();
		}
	}
	else if ( $action == "insertgrade" ) {
		$Q1 = "SELECT u.id, u.name, u.a_id FROM user u,take_course tc WHERE tc.student_id = u.a_id AND tc.course_id = '$course_id' and tc.credit ='1' and tc.year='$course_year' and tc.term = '$course_term' ORDER BY u.id";
		if ( !($result1 = mysql_db_query( $DB, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
		}
		while ( $rows1 = mysql_fetch_array($result1) )
		{
			$Q0 = "select * from take_exam where exam_id='$exam_id' and student_id='".$rows1[2]."'";
			$result0 = mysql_db_query($DB.$course_id, $Q0);
			$nums = mysql_num_rows($result0);			
			$grade = "G".$rows1['a_id'];
			if($nums != 0)
			{
				if ( $$grade != "" ) {
					$gradev = $$grade;
					$Q2 = "UPDATE take_exam SET grade='$gradev' WHERE exam_id='$exam_id' AND student_id='$rows1[2]'";
					if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
						show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
					}
				}
				else {
					$Q2 = "UPDATE take_exam SET grade=-1 WHERE exam_id='$exam_id' AND student_id='$rows1[2]'";
					if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
						show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
					}
				}
			}
			else
			{
				if($$grade != "")
				{
					$gradev = $$grade;
					$Q2 = "insert into take_exam (grade, exam_id, student_id) values ('$gradev', '$exam_id', '$rows1[2]')";
					if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
						show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
					}
				}/*
				else
				{
					$Q2 = "UPDATE take_exam SET grade=-1 WHERE exam_id='$exam_id' AND student_id='$rows1[2]'";
					if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
						show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
					}
				}*/
			}
		}
		if($version == "C")
			$message = "成績修改完成!";
		else
			$message = "Grade Modify Completely!";
		show_page_d ();
	}
	else if ( $action == "nameratio" ) {
		$row = CheckError( );
		if( $row == "null" )
		{
			if($version == "C")
				$message = "考試名稱或所佔比例尚未輸入!";
			else
				$message = "Please Input Test Name Or Ratio !";
			modifynameandratio ();
		}
		elseif($row == "errorvalue")
		{
			if($version == "C")
				$message = "比例須介於0~100之間!";
			else
				$message = "Please Input Ratio Between 0 And 100 !";
			modifynameandratio ();
		}
		elseif($row == "exist")
		{
			if($version == "C")
				$message = $test_name."已存在,請更換考試名稱!";
			else
				$message = $test_name."This Test Name Exists, And Please Change The Test Name!";
			modifynameandratio ();
		}
		else
		{
			//確定修改
			for($i=0; $i<count($CoreAbility); $i++){
                                if(!isset($str)) $str = $CoreAbility[$i];
                                else $str = $str . "," . $CoreAbility[$i];
                        }

			$Q1 = "UPDATE exam SET name = '$test_name'  , percentage = '$test_ratio',CoreAbilities='$str' WHERE a_id='$test_id'";
			if ( !($result1 = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
				show_page( "not_access.tpl" ,"資料庫寫入錯誤!!" );
			}
			if($version == "C")
				$message = $test_name."名稱與比例修改完成!";
			else
				$message = $test_name."Name and Ratio Modify Complete";
			show_page_d ();
		}
	}
	else
		show_page_d();
}
else
{
	if( $version=="C" ) {
		show_page( "not_access.tpl" ,"你沒有權限使用此功能");
		exit;
	}
	else {
		show_page( "not_access.tpl" ,"You have No Permission!!");
		exit;
	}
}

function show_page_d () {
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $test_name, $message, $version, $course_id, $skinnum;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}
	$Q1 = "SELECT name, a_id FROM exam where is_online != '1' order by name";
	if ( !($result1 = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
		show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
	}
	if(mysql_num_rows($result1) != 0)
	{
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate("./templates");
		if ( $version == "C" )
			$tpl->define(array(main=>"TGModifyFrame1.tpl"));
		else
			$tpl->define(array(main=>"TGModifyFrame1_E.tpl"));
		$tpl->define_dynamic("row","main");
		$tpl->assign( SKINNUM , $skinnum );
		while ( $rows = mysql_fetch_array($result1) )
		{
			$tpl->assign(TESTNAME,$rows[0]);
			$tpl->assign(TESTID,$rows[1]);
			$tpl->assign(ACT1,"TGModifyFrame1.php");
			$tpl->assign(ACT2,"modify");
			$tpl->parse(ROWS,".row");
		}
		$tpl->assign(MESSAGE,$message);
		$tpl->parse(BODY,"main");
		$tpl->FastPrint("BODY");
	}
	else {
		if( $version=="C" ) {
			show_page( "not_access.tpl" ,"沒有任何考試紀錄!!");
			exit;
		}
		else {
			show_page( "not_access.tpl" ,"There is no record!!");
			exit;
		}
	}

}

function modify_score () {
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $version, $test_id, $course_id, $skinnum, $course_year, $course_term;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}
	$Q1 = "SELECT u.id, u.name, u.a_id FROM user u,take_course tc WHERE tc.student_id = u.a_id AND tc.course_id = '$course_id' and tc.credit = '1' and tc.year='$course_year' and tc.term = '$course_term' ORDER BY u.id";
	if ( !($result1 = mysql_db_query( $DB, $Q1 ) ) ) {
		show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
	}
	if(mysql_num_rows($result1) != 0)
	{
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate("./templates");
		if ( $version == "C" )
			$tpl->define(array(main=>"TGInsertFrame2.tpl"));
		else
			$tpl->define(array(main=>"TGInsertFrame2_E.tpl"));
		$tpl->define_dynamic("row","main");
		$tpl->assign( SKINNUM , $skinnum );
		$color = "#E6FFFC";

		$tpl->assign(SMARK2,"");
		$tpl->assign(SMARK3,"");
		$tpl->assign(EMARK3,"");		
		while ( $row1 = mysql_fetch_array( $result1 ) ) {
			if ( $color == "#F0FFEE" )
				$color = "#E6FFFC";
			else
				$color = "#F0FFEE";
			$tpl->assign( COLOR , $color );
			$Q2 = "SELECT te.grade FROM exam e,take_exam te WHERE te.student_id='".$row1['a_id']."' and e.a_id = '$test_id' and te.exam_id='$test_id'";
			if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
				show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
			}
			$row2 = mysql_fetch_array( $result2 );
			$tpl->assign(SNO1,$row1[0]);
			$tpl->assign(SNAME1,$row1[1]);
			$tpl->assign(SVAR1,$row1[2]);
			if ( $row2[0] == -1 )
				$tpl->assign(GRADE1,"");
			else
				$tpl->assign(GRADE1,$row2[0]);
			//-------------應某老師要求修改-___- ----------------
			if( $row1 = mysql_fetch_array( $result1 ) ){
				$Q2 = "SELECT te.grade FROM exam e,take_exam te WHERE te.student_id='".$row1['a_id']."' and e.a_id = '$test_id' and te.exam_id='$test_id'";
				if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
					show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
				}
				$row2 = mysql_fetch_array( $result2 );
				$tpl->assign(SNO2,$row1[0]);
				$tpl->assign(SNAME2,$row1[1]);
				$tpl->assign(SVAR2,$row1[2]);
				if ( $row2[0] == -1 )
					$tpl->assign(GRADE2,"");
				else
					$tpl->assign(GRADE2,$row2[0]);
			}else{
				$tpl->assign(SMARK2,"<td bgcolor=\"#FFFFFF\"></td><td bgcolor=\"#FFFFFF\"></td><td bgcolor=\"#FFFFFF\"></td><!-- ");
				$tpl->assign(EMARK3," -->");
			}

			if( $row1 = mysql_fetch_array( $result1 ) ){
				$Q2 = "SELECT te.grade FROM exam e,take_exam te WHERE te.student_id='".$row1['a_id']."' and e.a_id = '$test_id' and te.exam_id='$test_id'";
				if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
					show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
				}
				$row2 = mysql_fetch_array( $result2 );
				$tpl->assign(SNO3,$row1[0]);
				$tpl->assign(SNAME3,$row1[1]);
				$tpl->assign(SVAR3,$row1[2]);
				if ( $row2[0] == -1 )
					$tpl->assign(GRADE3,"");
				else
					$tpl->assign(GRADE3,$row2[0]);
			}
			else{
				$tpl->assign(SMARK3,"<td bgcolor=\"#FFFFFF\"></td><td bgcolor=\"#FFFFFF\"></td><td bgcolor=\"#FFFFFF\"></td><!-- ");
				$tpl->assign(EMARK3," -->");
			}
			
			$tpl->parse(ROWS,".row");
		}
		$tpl->assign(EXAMID,$test_id);
		$tpl->assign(ACT1,"TGModifyFrame1.php");
		$tpl->assign(ACT2,"insertgrade");
		$tpl->parse(BODY,"main");
		$tpl->FastPrint("BODY");
	}
	else {
		if( $version=="C" )
			show_page( "not_access.tpl" ,"此課程尚未有任何學生!");
		else
			show_page( "not_access.tpl" ,"There is no Student in this Class!!");
	}
}

function modifynameandratio () {
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $version, $test_id, $course_id, $message, $test_name, $test_ratio, $skinnum;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}
	$Q1 = "SELECT name,percentage,a_id,CoreAbilities FROM exam WHERE a_id='$test_id'";
	if ( !($result1 = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
		show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
	}
	$row = mysql_fetch_array( $result1 );
	if ( $test_name == "" )
		$test_name = $row['name'];
	if ( $test_ratio == "" )
		$test_ratio = $row['percentage'];

        //判斷大學部or研究所 顯示資料庫裡原本勾選過的核心能力
	$CoreAbilities = $row['CoreAbilities'];
        $group_id = Get_group_id($course_id);
        $ClassTopic_CoreAbilitiesTmp = split(",",$CoreAbilities);
        $CoreAbilities = "";
        if($group_id == 11){
                for($i=1; $i<=11; $i++){
                        $checked = "";
                        for($j=0; $j<count($ClassTopic_CoreAbilitiesTmp); $j++)
                                if($i == $ClassTopic_CoreAbilitiesTmp[$j]) $checked = "checked"; 
                        $CoreAbilities = $CoreAbilities . "<input type=\"checkbox\" name=\"CoreAbility[]\" value=\"".$i."\"".$checked.">".ceil($i/3).".".((($i-1)%3)+1);
                }
        }
        else if($group_id==12){
                for($i=1; $i<=8; $i++){
                        $checked = "";
                        for($j=0; $j<count($ClassTopic_CoreAbilitiesTmp); $j++)
                                if($i == $ClassTopic_CoreAbilitiesTmp[$j]) $checked = "checked";
                        $CoreAbilities = $CoreAbilities . "<input type=\"checkbox\" name=\"CoreAbility[]\" value=\"".$i."\"".$checked.">A".$i;
                }
        }
	else if($group_id==15 ){
                         for($i=1; $i<=12; $i++){
                                 $checked = "";
                                 for($j=0; $j<count($ClassTopic_CoreAbilitiesTmp); $j++)
                                         if($i == $ClassTopic_CoreAbilitiesTmp[$j]) $checked = "checked";
                                 $CoreAbilities = $CoreAbilities . "<input type=\"checkbox\" name=\"CoreAbility[]\" value=\"".$i."\"".$checked.">B".$i;
                }
        }
	else if( $group_id ==16){
                 for($i=1; $i<=8; $i++){
                         $checked = "";
                         for($j=0; $j<count($ClassTopic_CoreAbilitiesTmp); $j++)
                                 if($i == $ClassTopic_CoreAbilitiesTmp[$j]) $checked = "checked";
                         $CoreAbilities = $CoreAbilities . "<input type=\"checkbox\" name=\"CoreAbility[]\" value=\"".$i."\"".$checked.">D".$i;
                }
        }



	include("class.FastTemplate.php3");
	$tpl = new FastTemplate("./templates");
	if( $version == "C" )
		$tpl->define(array(main=>"TGInsertFrame1.tpl"));
	else
		$tpl->define(array(main=>"TGInsertFrame1_E.tpl"));
	$tpl->assign( SKINNUM , $skinnum );
	$tpl->assign(MESSAGE, $message);
	$tpl->assign(ACT1,"TGModifyFrame1.php");
	$tpl->assign(ACT2,"nameratio");
	$tpl->assign(TESTNAME,$test_name);
	$tpl->assign(RATIO,$test_ratio);
	$tpl->assign(TESTID, $test_id );
	$tpl->assign(IMG, "a23.gif");
	$tpl->assign(CoreAbilities,$CoreAbilities);
	$tpl->parse(BODY,"main");
	$tpl->FastPrint("BODY");

	echo CADes($group_id);
}

function CheckError( ) {
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id, $test_name, $test_ratio, $test_id;

	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}
	$Q1 = "SELECT name, a_id FROM exam WHERE name = '$test_name'";
	if ( !($result1 = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
		show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
	}
	$rows = mysql_fetch_array( $result1 );
	
	if( ($test_name == $rows[0] ) && ( $test_id != $rows[1]) )
		return "exist";
	else if($test_name == "" || $test_ratio == "")
		return "null";
	else if($test_ratio > 100 || $test_ratio < 0)
		return "errorvalue";
	else
		return "ok";
}

function Get_group_id($a_id){
        //SQL Server的資料
        global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;

        //從資料庫取得group_id
        $SQL_Select = "SELECT group_id FROM course WHERE a_id = '$a_id'";
        if ( !($result = mysql_db_query( $DB, $SQL_Select ) ) ) {
                $message = "function Get_group_id($a_id) 資料庫讀取錯誤!!<br>";
                echo $message;
        }
        $row = mysql_fetch_array( $result );
                return $row['group_id'];
}

?>
