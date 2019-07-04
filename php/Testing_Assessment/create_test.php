<?php
require 'fadmin.php';
require '../CoreDescript.php';

update_status ("編輯測驗");

if(isset($PHPSESSID) && session_check_teach($PHPSESSID) == 2)
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD,$total_chap,$course_id;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}
	//ciel
	$Qd = "SELECT chap_num FROM chap_title ORDER BY chap_num DESC";
	if ( !($resultd = mysql_db_query( $DB.$course_id, $Qd ) ) ) {
		show_page( "not_access.tpl" ,"請先編輯教材章節" );
	}
	$rowd = mysql_fetch_array($resultd);
	$total_chap = $rowd[0];
	
	if($action == "newtest")
	{
		$row = CheckError();
		if($row == "null")
		{
			if($version == "C")
				$message = "請輸入考試名稱和比例!";
			else
				$message = "Please input test name or ratio!";
			show_page_d();
		}
		elseif($row == "errorvalue")
		{
			if($version == "C")
				$message = "比例須介於0~100之間";
			else
				$message = "Please input ratio between 0 and 100!";
			show_page_d();
		}
		elseif($row == "exist")
		{
			if($version == "C")
				$message = $test_name."已存在,請更換考試名稱!";
			else
				$message = "This test name $test_name exist, and please change the test name!";
			show_page_d();
		}
		else
		{
                        //取出勾選了那些核心能力
                        $str = "";
                        for($i=0; $i<count($CoreAbility); $i++){
                                if($str != "") $str = $str . "," . $CoreAbility[$i];
                                else $str = $CoreAbility[$i];
                        }

			if ( $test_type == "self_test" )
				$Q1 = "INSERT INTO exam (chap_num,name,percentage, beg_time, CoreAbilities) values ('$chap_num','$test_name','0', '00000000000000', '$str')";
			else
				$Q1 = "INSERT INTO exam (chap_num,name,percentage, beg_time, CoreAbilities) values ('$chap_num','$test_name','$test_ratio', '00000000000000', '$str')";
			if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
				show_page( "not_access.tpl" ,"資料庫寫入錯誤!!" );
			}
			$test_id = mysql_insert_id();
			$Q2 = "SELECT tc.student_id, u.id FROM take_course tc,user u WHERE tc.course_id = '$course_id' AND tc.student_id = u.a_id and tc.year='$course_year' and tc.term = '$course_term'";
			if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) ) {
				show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
			}
			while ( $row2 = mysql_fetch_array($result2) ) {
				$Q3 = "INSERT INTO take_exam (exam_id,student_id,grade) values ('$test_id','$row2[0]','-1')";
				mysql_db_query($DB.$course_id,$Q3);
			}
			show_content ( $test_id, 0 );
		}
	}
	else if($action == "newtestq")
	{
		show_content ( $exam_id, $type );
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
		
		$file_picture_type = "";
		$file_av_type = "";
		
		if(is_file($picture_file))
			$file_picture_type = get_file_type($_FILES['picture_file']['name']);
				
		if(is_file($av_file))
			$file_av_type = get_file_type($_FILES['av_file']['name']);
		
		//echo  $file_picture_type . "<br>";
		//echo  $file_av_type. "<br>";
		//echo "原始檔名：" . $_FILES['pictrue_file']['name'] . "<br>";
		


		if ( $type == 1 ) {
			$Q1 = "Insert into tiku ( exam_id, type, question, answer, selection1, selection2, selection3, selection4, ismultiple, grade, answer_desc, file_picture_type, file_av_type) values ( '$exam_id', '$type', '$qtext','$checkbox','$selection1','$selection2','$selection3','$selection4','$cho','$qgrade','$ans_link', '$file_picture_type', '$file_av_type')";
		}
		else if ( $type == 2 ) {
			$Q1 = "Insert into tiku ( exam_id, type, question, answer, grade, answer_desc, file_picture_type, file_av_type ) values ( '$exam_id', '$type', '$qtext','$cho', '$qgrade', '$ans_link', '$file_picture_type', '$file_av_type')";
		}
		else if ( $type == 3 ) {
			$Q1 = "Insert into tiku ( exam_id, type, question, selection1, selection2, selection3, selection4, answer, grade, answer_desc, file_picture_type, file_av_type ) values ( '$exam_id', '$type', '$qtext','$selection1','$selection2','$selection3','$selection4','$rownum','$qgrade','$ans_link', '$file_picture_type', '$file_av_type')";
		}
//--------------------------------------------------------------------
		//devon
		else if ( $type == 4 ) {
			//echo $qtext."<br>";
			$Q1 = "Insert into tiku ( exam_id, type, question, grade, answer, answer_desc, file_picture_type, file_av_type ) values ( '$exam_id', '$type', '$qtext', '$qgrade', '$answer_qa', '$ans_link', '$file_picture_type', '$file_av_type' )";
		}
//--------------------------------------------------------------------
		else {
			show_page( "not_access.tpl" ,"題型錯誤!!" );
			exit;
		}
		if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫寫入錯誤!!" );
		}
		
		$this_id = mysql_insert_id();
		
		if(is_file($picture_file)){
			if( !fileupload( $picture_file, "../../$course_id/exam", $this_id."_picture.".$file_picture_type ) ){
				show_page("not_access", "檔案寫入錯誤");
			}
			
		}		
		if(is_file($av_file)){
			if( !fileupload( $av_file, "../../$course_id/exam", $this_id."_av.".$file_av_type ) ){
				show_page("not_access", "檔案寫入錯誤");
			}
			
		}		
		if ( $submit == "完成並結束編輯" || $submit == "ADD & End Edit" ) {
			if( $version=="C" )
				show_page( "not_access.tpl" ,"已完成試題編輯!", "", "<a href=\"./create_test.php\">回新增測驗頁</a>");
			else
				show_page( "not_access.tpl" ,"Completing Question Edition!", "", "<a href=\"./create_test.php\">Back to New Exam</a>");
		}
		else {
			if ( $version == "C" )
				$message = "第 $qno 題編輯完成";
			else
				$message = "Question $qno is Add";
			show_content($exam_id, 0);
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

function CheckError()
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $test_name, $test_ratio, $course_id, $test_type;

	if($test_name == "" || ( $test_ratio == "" && $test_type != "self_test" ))
		return "null";
	elseif(($test_ratio > 100 || $test_ratio < 0) && $test_type != "self_test")
		return "errorvalue";
	else
	{
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
		}
		$Q1 = "SELECT name FROM exam WHERE name='$test_name'";
		if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
		}
		if( mysql_num_rows($result) != 0 )
			return "exist";
		else
			return "ok";
	}
}

function show_page_d () {
	global $message, $test_name, $test_ratio, $test_type, $version, $skinnum,$total_chap,$select_chap_num, $course_id;
        $group_id = Get_group_id($course_id);
    if($group_id == 11){                                                                                              $CoreAbilities =
	    "<tr bgcolor='#F0FFEE'><td align=left bgcolor='#E6FFFC'><div align='center'>核心能力 (Core Abilities)：".
            "<input type=\"checkbox\" name=\"CoreAbility[]\" value=\"1\">1.1
            <input type=\"checkbox\" name=\"CoreAbility[]\" value=\"2\">1.2
            <input type=\"checkbox\" name=\"CoreAbility[]\" value=\"3\">1.3
            <input type=\"checkbox\" name=\"CoreAbility[]\" value=\"4\">2.1
            <input type=\"checkbox\" name=\"CoreAbility[]\" value=\"5\">2.2
            <input type=\"checkbox\" name=\"CoreAbility[]\" value=\"6\">2.3
            <input type=\"checkbox\" name=\"CoreAbility[]\" value=\"7\">3.1
            <input type=\"checkbox\" name=\"CoreAbility[]\" value=\"8\">3.2
            <input type=\"checkbox\" name=\"CoreAbility[]\" value=\"9\">3.3
            <input type=\"checkbox\" name=\"CoreAbility[]\" value=\"10\">4.1
            <input type=\"checkbox\" name=\"CoreAbility[]\" value=\"11\">4.2".
	    "</div></td></tr>";
    }
    else if($group_id==12){
        $CoreAbilities =
	    "<tr bgcolor='#F0FFEE'><td align=left bgcolor='#E6FFFC'><div align='center'>核心能力 (Core Abilities)：".
            "<input type=\"checkbox\" name=\"CoreAbility[]\" value=\"1\">A1
            <input type=\"checkbox\" name=\"CoreAbility[]\" value=\"2\">A2
            <input type=\"checkbox\" name=\"CoreAbility[]\" value=\"3\">A3
            <input type=\"checkbox\" name=\"CoreAbility[]\" value=\"4\">A4
            <input type=\"checkbox\" name=\"CoreAbility[]\" value=\"5\">A5
            <input type=\"checkbox\" name=\"CoreAbility[]\" value=\"6\">A6
            <input type=\"checkbox\" name=\"CoreAbility[]\" value=\"7\">A7
            <input type=\"checkbox\" name=\"CoreAbility[]\" value=\"8\">A8".
	    "</div></td></tr>";
    }

	include("class.FastTemplate.php3");
	$tpl = new FastTemplate("./templates");
	if($version == "C") {
		$tpl->define( array(main=>"create_test.tpl") );
		$tpl->assign(BUTTON,"進入試題編輯介面");
		$tpl->assign(TITLE, "製作測驗" );
	}
	else {
		$tpl->define( array(main=>"create_test_E.tpl") );
		$tpl->assign(BUTTON,"Enter_Edit_Test_Interface");
		$tpl->assign(TITLE, "Create Test" );
	}
	//ciel	
	if(empty($select_chap_num)){
		
		for($i=0; $i<=$total_chap; $i++){	
			$select_chap = $select_chap."<option value=".$i." >".$i."</option>";
		}
	}else{
		$select_chap = $select_chap."<option value=".$select_chap_num." >".$select_chap_num."</option>";
	}
	
	$tpl->assign(SELECT_CHAP,$select_chap);	
	
	$tpl->assign(SKINNUM, $skinnum );
	$tpl->assign(IMG,"a331.gif");
	$tpl->assign(ACT1,"create_test.php");
	$tpl->assign(ACT2,"newtest");
	$tpl->assign(TEST_NAME,$test_name);
	$tpl->assign(TEST_RATIO,$test_ratio);
	$tpl->assign(CoreAbilities,$CoreAbilities);
	if ( $test_type != "real_test")
		$tpl->assign( SELF_TEST, "selected");
	else
		$tpl->assign( REAL_TEST, "selected");
		
	$tpl->assign(TESTID,"");
	$tpl->assign(MESSAGE,$message);
	$tpl->parse(BODY,"main");
	$tpl->FastPrint("BODY");

	echo CADes($group_id);
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

	$num = mysql_num_rows($result);
	$tpl->assign(SKINNUM, $skinnum );
	$tpl->assign(QNO,$num + 1);
	$tpl->assign(IMG,"a331.gif");
	$tpl->assign(ACT1,"create_test.php");
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
//----------------------------------------------------------------------
		//devon 2005-07-16 問答題
		else if ( $type == 4 ) {
			if($version == "C")
				$tpl->assign(KIND,"問答題");
			else
				$tpl->assign(KIND,"Q & A");
			if($version == "C")
				$tpl->define(array(choice=>"edit_testqa.tpl"));
			else
				$tpl->define(array(choice=>"edit_testqa_E.tpl"));
			$tpl->assign(ANSWER_QA,"");
		}
//----------------------------------------------------------------------
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
			if ( $rownum == 0 || $rownum == NULL )
				$tpl->assign(ENDLINE, "</body></html>");
			$tpl->parse(SETROW,"setrow");
			$tpl->FastPrint("SETROW");
			
			if($version == "C")
				$tpl->define(array(choice=>"edit_testf.tpl"));
			else
				$tpl->define(array(choice=>"edit_testf_E.tpl"));
			$tpl->define_dynamic("row","choice");
			for ( $i = 1 ; $i <= $rownum; $i++ ) {
				$sele = "selection".$i;
				$tpl->assign(NUM, $i);
				$tpl->assign(ORDER, $i);
				$tpl->assign(VALUE, $$sele);
				$tpl->parse(INPUT,".row");
			}
		}

		if ( $version == "C" ) {
			$tpl->assign(BUTTON,"　<input type=submit name=submit value=\"完成並結束編輯\" OnClick=\"return Check();\">");
			$tpl->assign(SUBMIT,"完成並編輯下一題");
		}
		else {
			$tpl->assign(BUTTON,"　<input type=submit name=submit value=\"ADD & End Edit\" OnClick=\"return Check();\">");
			$tpl->assign(SUBMIT,"ADD & Edit Next Quextion");
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
function get_file_type ( $attach_name ) {
		// 存入檔案的副檔名. 這是為了讓使用者能以正確的程式開啟夾檔....
		    
			
			$type = explode(".",$attach_name);
			
			$type = $type[sizeof($type)-1];
			return $type;
			
		
}

function Get_group_id($a_id){
        //SQL Server的資料                                                                                                            global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
        global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;

        //從資料庫取得group_id
        $SQL_Select = "SELECT group_id FROM course WHERE a_id = '$a_id'";
        if ( !($result = mysql_db_query( $DB, $SQL_Select ) ) ) {
                $message = "function Get_group_id($a_id) 資料庫讀取錯誤!!<br>";                                                               echo $message;
        }
        $row = mysql_fetch_array( $result );

        return $row['group_id'];
}

?>
