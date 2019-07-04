<?php
require 'fadmin.php';
require_once 'export_questionary.php';
update_status ("編輯問卷");

if( isset($PHPSESSID) && (session_check_teach($PHPSESSID) >= 2) )
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}
	if($action == "modifynr")
	{
		$Q1 = "SELECT name, is_named, is_once FROM questionary WHERE a_id='$q_id'";
		if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
		}
		$rows = mysql_fetch_array($result);
		$questionary_name = $rows['name'];
		$is_named = $rows['is_named'];
		$is_once = $rows['is_once'];
		modifynr ();
	}
	   else if($action == "export_questionary"){
                export_questionary($course_id,$q_id);

                $location="../../".$course_id."/textbook/questionary.xml";

                $file = fopen($location,"r");
                $contents = '';
                while(!feof($file)){
                         $contents .= fread($file,8192);
                }

                $ext = "xml";
                $mime_type = (PMA_USR_BROWSER_AGENT == 'IE' || PMA_USR_BROWSER_AGENT == 'OPERA')
                ? 'application/octetstream'
                : 'application/octet-stream';
                header('Content-Type: ' . $mime_type);
                // lem9 & loic1: IE need specific headers
                if (PMA_USR_BROWSER_AGENT == 'IE') {
                        header('Content-Disposition: inline; filename="' ."qustionary" . '.' . $ext . '"');
                        header('Expires: 0');
                        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                        header('Pragma: public');
                } else {
                        header('Content-Disposition: attachment; filename="' . "questionary" . '.' . $ext . '"');
                        header('Expires: 0');
                        header('Pragma: no-cache');
                }
                echo $contents;

        }

	else if($action == "modifyquestionarynr")
	{
		$row = CheckError();
		if($row == "null")
		{
			if($version == "C")
				$message = "請輸入問卷名稱!";
			else
				$message = "Please input questionary name!";
			modifynr ();
		}
		elseif($row == "exist")
		{
			if($version == "C")
				$message = $questionary_name."已存在,請更換問卷名稱!";
			else
				$message = "This questionary name $questionary_name exist, and please change the questionary name!";
			modifynr ();
		}
		else
		{
			$Q1 = "UPDATE questionary set name = '$questionary_name', is_once = '$is_once', is_named = '$is_named' where a_id = '$q_id'";
			
			if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
				show_page( "not_access.tpl" ,"資料庫寫入錯誤!!" );
			}
			if($version == "C")
				$message = "問卷名稱和屬性修改成功!";
			else
				$message = "Update Successfully !";
			show_page_d ();
		}
	}
	else if($action == "showquestionary")
	{
		$Q1 = "SELECT a_id, q_id, type, question, selection1, selection2, selection3, selection4, selection5, ismultiple, grade FROM qtiku WHERE q_id='$q_id' and type='3' order by a_id";
		if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
		}
		if ( mysql_num_rows($result) != 0 ) {
			include("class.FastTemplate.php3");
			$tpl = new FastTemplate("./templates");
			$tpl->define(array(main=>"showquestionary.tpl"));
			if($version == "C") {
				$tpl->assign(IMG,"img");
				$tpl->assign(SUBMIT,"繳交問卷");
				$tpl->assign(RESET,"重新填寫");
				$tpl->assign(QDISABLE,"disable");
			}
			else {
				$tpl->assign(IMG,"img_E");
				$tpl->assign(SUBMIT,"Complete");
				$tpl->assign(RESET,"Reset");
			}
			$tpl->define_dynamic("rows","main");
			$qcounter = 0;
			while ( $rows = mysql_fetch_array($result) ) {
				$tpl->assign(QUESTION,$rows['question']);
				$tpl->assign(TYPE,"");
				$tpl->assign(QGRADE,"");
				$tpl->assign(QNO,"");
				$tpl->parse(ROWS,".rows");
				
				$Q2 = "SELECT q_id, type, question, selection1, selection2, selection3, selection4, selection5, ismultiple, grade FROM qtiku WHERE q_id='$q_id' and block_id='".$rows['a_id']."' and type != '3' order by a_id";
				if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
					show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
				}
				while ( $rows2 = mysql_fetch_array($result2) ) {
					$qcounter ++;
					$tpl->assign(QNO,$qcounter.".");
					$tpl->assign(QUESTION,$rows2['question']);
					$tpl->assign(SEL,"");
					if ( $rows2['grade'] != null || $rows2['grade'] != "" ) {
						if ( $version == "C" )
							$tpl->assign(QGRADE,"(".$rows2['grade']."權重 ");
						else
							$tpl->assign(QGRADE,"(".$rows2['grade']."Weight ");
					}
					if ( $rows2['type'] == "1" ) {
						if($rows2['ismultiple'] == "0") {
							if($version == "C") {
								$tpl->assign(TYPE,"單選題)");
							}
							else {
								$tpl->assign(TYPE,"Single-select)");
							}
							$tpl->define(array(cont=>"showquestionarys.tpl"));
							$tpl->define_dynamic("row","cont");
							for ( $i = 0 ; $i < 5 ; $i ++ ) {
								$j = $i + 1;
								$sele = "selection".$j;
								if ( $rows2["$sele"] != null || $rows2["$sele"] != "" ) {
									$tpl->assign(ORDER, $j);
									$tpl->assign(VALUE,pow(2,$i));
									$tpl->assign(QUES, $rows2["$sele"]);
									$tpl->parse(CONT,".row");
								}
							}
						}
						else {
							if($version == "C") {
								$tpl->assign(TYPE,"複選題)");
							}
							else {
								$tpl->assign(TYPE,"Multi-select)");
							}
							$tpl->define(array(cont=>"showquestionarym.tpl"));
							$tpl->define_dynamic("row","cont");
							for ( $i = 0 ; $i < 5 ; $i ++ ) {
								$j = $i + 1;
								$sele = "selection".$j;
								if ( $rows2["$sele"] != null || $rows2["$sele"] != "" ) {
									$tpl->assign(ORDER, $j);
									$tpl->assign(QUES, $rows2["$sele"]);
									$tpl->parse(CONT,".row");
								}
							}
						}
					}
					else if ( $rows2['type'] == "2" ) {
						if($version == "C") {
							$tpl->assign(TYPE,"問答題)");
						}
						else {
							$tpl->assign(TYPE,"Q&A)");
						}
						$tpl->define(array(cont=>"showquestionaryf.tpl"));
						$tpl->assign(TEXTAREA, "");
					}
					//讓parse重新抓cont指向的檔案 特殊用法
					$tpl->parse(ROWS,".rows");
					$tpl->parse(ROWS,".cont");
					$tpl->row = "";
					$tpl->CONT = "";
					$tpl->cont = "";
				}
			}
			$tpl->assign(QID,$q_id);
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
	else if($action == "modifyquestionary")
		modifyquestionary ();
/*	else if($action == "updatequestionary")
	{
		if( $qtext == "" ) {
			if ( $version == "C" )
				$message = "請輸入題目!!";
			else
				$message = "Please Input Question!!";
			modifyquestionary ();
			exit;
		}
		else if ($qgrade == "" ) {
			if ( $version == "C" )
				$message = "請輸入配分!!";
			else
				$message = "Please Input score!!";
			modifyquestionary ();
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
				modifyquestionary ();
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
						modifyquestionary ();
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
		modifyquestionary ();
	}
*/	else if($action == "deletequestion") {
		$Q1 = "DELETE FROM tiku WHERE a_id='$a_id'";
		if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫刪除錯誤!!" );
		}
		if ( $version == "C" )
			$message = "刪除完成";
		else
			$message = "Question is delete";
		$a_id = "";
		modifyquestionary ();
	}
/*	elseif($action == "insertquestionary")
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
		modifyquestionary ();
	}
	else if($action == "newquestionaryq")
		show_content ( $exam_id, $type );
*/	else if($action == "showtotal")
		showtotal();
	else if($action == "detail")
		showdetail();
/*	else if($action == "requestionary") {
		$Q1 = "update take_exam set grade = '-1' where exam_id = '$exam_id' and student_id = '$a_id'";
		if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			echo ("資料庫更新錯誤!!");
			exit;
		} 
		showtotal();
	}
*/	else if($action == "deletequestionary")
	{
		$Q0 = "Select name FROM questionary WHERE a_id='$q_id'";
		if ( !($result = mysql_db_query( $DB.$course_id, $Q0 ) ) ) {
			$message ="資料庫讀取錯誤!!";
			show_page_d();
			exit;
		}
		$rows = mysql_fetch_array($result);
		$Q1 = "DELETE FROM questionary WHERE a_id='$q_id'";
		if ( !($result1 = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			$message ="資料庫刪除錯誤!!";
			show_page_d();
			exit;
		}
		$Q2 = "DELETE FROM qtiku WHERE q_id='$q_id'";
		if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
			$message ="資料庫刪除錯誤!!";
			show_page_d();
			exit;
		}
		$Q3 = "DELETE FROM take_questionary WHERE q_id='$q_id'";
		if ( !($result3 = mysql_db_query( $DB.$course_id, $Q3 ) ) ) {
			$message ="資料庫刪除錯誤!!";
			show_page_d();
			exit;
		}
		$Q4 = "Drop table questionary_".$q_id;
		if ( !($result4 = mysql_db_query( $DB.$course_id, $Q4 ) ) ) {
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
	else if($action == "pubquestionary")
		pubquestionary();
	else if($action == "modifypubquestionary") {
		if ( $pub == 1 ) {
			$beg_time=$sel_year.$sel_month.$sel_day.$sel_hour.$sel_minute."00";
			$end_time=$ed_year.$ed_month.$ed_day."23"."59"."59";//date("His");
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
				$Q1 = "UPDATE questionary SET beg_time='$now',end_time='00000000000000',public='0' WHERE a_id='$q_id'";
			}
			else if($pub == 1)
			{
				if ( $check_range < 0 ) {
					if ( $version == "C" )
						$message = "設定錯誤!!";
					else
						$message = "Setup failure!!!";
					pubquestionary();
					exit;
				}
				$Q1 = "UPDATE questionary SET beg_time='$beg_time',end_time='$end_time',public='1' WHERE a_id='$q_id'";
			}
			else {
				$Q1 = "UPDATE questionary SET beg_time='$beg_time',end_time='$end_time',public='3' WHERE a_id='$q_id'";
			}
			if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
				show_page( "not_access.tpl" ,"資料庫更新錯誤!!" );
			}
			if ( $version == "C" )
				$message = "設定完成!!";
			else
				$message = "Setup Complete!!!";
		}
		pubquestionary();
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
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $version, $message, $course_id;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}
	$Q1 = "SELECT name, a_id, is_once, is_named FROM questionary ORDER BY a_id";
	if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
		show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
	}

	if( mysql_num_rows($result) != 0)
	{
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate("./templates");
		if($version == "C")
			$tpl->define(array(main=>"modify_questionary.tpl"));
		else
			$tpl->define(array(main=>"modify_questionary_E.tpl"));
		$tpl->define_dynamic("row","main");
		$color == "#BFCEBD";
		while ( $rows = mysql_fetch_array( $result ) ) {
			if ( $color == "#BFCEBD" )
				$color = "#D0DFE3";
			else
				$color = "#BFCEBD";
			$tpl->assign( COLOR , $color );
			if($rows['is_named'] == 0) {
				if($version == "C")  
					$tpl->assign(QUESTYPE,"不記名");
				else
					$tpl->assign(QUESTYPE,"Unnamed");
			}
			else {
				if($version == "C")
					$tpl->assign(QUESTYPE,"記名");
				else
					$tpl->assign(QUESTYPE,"Named");
			}
			if($version == "C")  
				$tpl->assign(QUESLIMIT,"限填".$rows['is_once']."次");
			else
				$tpl->assign(QUESLIMIT,"limit".$rows['is_once']."Times");
			$tpl->assign(QUESNAME,$rows['name']);
			$tpl->assign(QUESID,$rows['a_id']);
			$tpl->parse(ROWS,".row");
		}
		$tpl->assign(MESSAGE,$message);
		$tpl->parse(BODY,"main");
 		$tpl->FastPrint("BODY");
	}
	else
	{
		if( $version=="C" )
			   show_page( "not_access.tpl" ,"目前沒有任何問卷可供修改!</br>".
                                   "您可以使用<a href =in_ex_questionary.php?course_id=".$course_id.">問卷匯入</a>");

		else
			  show_page( "not_access.tpl" ,"There is no questionary for modification!!".
                                   "you can use<a href =in_ex_questionary.php?course_id=".$course_id.">import questionary</a>");

	}
}

function modifynr () {
	global $message, $questionary_name, $q_id, $is_named, $is_once, $version;
	
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate("./templates");
	if($version == "C") {
		$tpl->define( array(main=>"create_questionary.tpl") );
		$tpl->assign(BUTTON,"修改");
	}
	else {
		$tpl->define( array(main=>"create_questionary_E.tpl") );
		$tpl->assign(BUTTON,"Modify");
	}
	$tpl->assign(IMG,"a332.gif");
	$tpl->assign(ACT1,"modify_questionary.php");
	$tpl->assign(ACT2,"modifyquestionarynr");
	$tpl->assign(QUES_NAME,$questionary_name);
	if ( $is_once < 10 )
		$R = "R0".$is_once;
	else
		$R = "R".$is_once;
	$tpl->assign( $R, "selected");
	
	if ( $is_named != "1")
		$tpl->assign( NRM_NAME, "selected");
	else
		$tpl->assign( REM_NAME, "selected");
	$tpl->assign(QUESID,$q_id);
	$tpl->assign(MESSAGE,$message);
	$tpl->parse(BODY,"main");
	$tpl->FastPrint("BODY");
}

function CheckError()
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $questionary_name, $course_id, $q_id;

	if($questionary_name == "" )
		return "null";
	else
	{
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
		}
		$Q1 = "SELECT name FROM questionary WHERE name='$questionary_name' and a_id != '$q_id'";
		if ( !($result = mysql_db_query( $DB.$course_id, $Q1 )) ) {
			show_page( "not_access.tpl" ,"資料庫讀取錯誤1!!" );
		}
		if( mysql_num_rows($result) != 0 )
			return "exist";
		else
			return "ok";
	}
}

function modifyquestionary () {
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $version, $course_id, $q_id, $a_id, $message;

	include("class.FastTemplate.php3");
	$tpl = new FastTemplate("./templates");
	$tpl->define(array(main=>"editor.tpl"));

	$tpl->assign(QUESID,$q_id);
	$tpl->parse(BODY,"main");
	$tpl->FastPrint("BODY");
}
/*
function show_content ( $questionary_id = "", $type ) {
	global $message, $version, $course_id, $selection1, $selection2, $selection3, $selection4, $check_1, $check_2, $check_3, $check_4, $qgrade, $qtext, $cho, $ans_link, $rownum;
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}
	$Q1 = "SELECT * FROM tiku WHERE exam_id = '$questionary_id'";
	if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
		show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
	}
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate("./templates");
	if($version == "C")
		$tpl->define(array(main=>"edit_questionaryh.tpl"));
	else
		$tpl->define(array(main=>"edit_questionaryh_E.tpl"));

	$num = mysql_num_rows($result);
	
	$tpl->assign(QNO,$num + 1);
	$tpl->assign(IMG,"a332.gif");
	$tpl->assign(ACT1,"modify_questionary.php");
	$tpl->assign(ACT2,"newquestionaryq");
	$tpl->assign(TP.$type,"selected");
	$tpl->assign(EXAMID,$questionary_id);
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
				$tpl->define(array(choice=>"edit_questionaryc.tpl"));
			else
				$tpl->define(array(choice=>"edit_questionaryc_E.tpl"));
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
				$tpl->define(array(choice=>"edit_questionaryyn.tpl"));
			else
				$tpl->define(array(choice=>"edit_questionaryyn_E.tpl"));
			
			$tpl->assign(CHO.$cho, "selected");
		}
		else {
			if($version == "C")
				$tpl->assign(KIND,"填充題");
			else
				$tpl->assign(KIND,"fill out");
			$tpl->define(array(setrow=>"edit_questionaryft.tpl"));
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
				$tpl->define(array(choice=>"edit_questionaryf.tpl"));
			else
				$tpl->define(array(choice=>"edit_questionaryf_E.tpl"));
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
				$tpl->define(array(tail=>"edit_questionaryb.tpl"));
			else
				$tpl->define(array(tail=>"edit_questionaryb_E.tpl"));
			$tpl->assign(QGRADE,$qgrade);
			$tpl->assign(QTEXT,$qtext);
			$tpl->assign(ROW,$rownum);
			$tpl->assign(ACT2,"insertquestionary");
			$tpl->parse(TAIL,"tail");
			$tpl->FastPrint("TAIL");
			//choice
			$tpl->parse(CHI,"choice");
			$tpl->FastPrint("CHI");
		}
	}
}
*/
function showtotal () {
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $q_id, $version, $course_id, $message, $course_year, $course_term;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}
	$Q1 = "select * from questionary where a_id = '$q_id'";
	if ( !($result1 = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
		show_page( "not_access.tpl" ,"資料庫讀取錯誤1!!" );
	}
	if ( mysql_num_rows($result1) != 0 ) {
		$row1 = mysql_fetch_array($result1);
		if ( $row1['is_named'] == 1 ) {
			$Q2 = "SELECT u.a_id, u.id, u.name FROM user u, take_course tc WHERE tc.course_id='$course_id' and tc.student_id = u.a_id and tc.credit = '1' and tc.year='$course_year' and tc.term = '$course_term'";
			if ( !($result1 = mysql_db_query( $DB, $Q2 ) ) ) {
				show_page( "not_access.tpl" ,"資料庫讀取錯誤2!!" );
			}
			if ( mysql_num_rows($result1) != 0 ) {
				include("class.FastTemplate.php3");
				$tpl = new FastTemplate("./templates");
				if($version == "C")
					$tpl->define(array(main=>"showtotal.tpl"));
				else
					$tpl->define(array(main=>"showtotal_E.tpl"));
				$tpl->define_dynamic("total","main");
				$color == "#BFCEBD";
				while ( $rows = mysql_fetch_array($result1) ) {
					if ( $color == "#BFCEBD" )
						$color = "#D0DFE3";
					else
						$color = "#BFCEBD";
					//判斷該學生是否已填寫問卷內容
					$Q3 = "select * from questionary_".$q_id." where student_id='".$rows[a_id]."'";
					$result3 = mysql_db_query($DB.$course_id, $Q3);
					if(mysql_num_rows($result3) == 0)
						$fill = "未填寫";
					else
						$fill = "<font color=red>已填寫</font>";
					$tpl->assign( COLOR , $color );
					$tpl->assign(SNO,$rows[1]);
					$tpl->assign(SNN,$rows[2]);
					$tpl->assign( AID, $rows['a_id'] );
					$tpl->assign( QID, $q_id );
					$tpl->assign( FILL, $fill );
					$tpl->parse(TOTAL,".total");
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
		else {
			showalltotal();
		}
	}
	else {
		if( $version=="C" )
			show_page( "not_access.tpl" ,"沒有此問卷!!");
		else
			show_page( "not_access.tpl" ,"Uncorrect Questionary!!");
	}
}

function showdetail () {
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $q_id, $a_id, $version, $course_id, $message;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}
	$Q1 = "select * from questionary_".$q_id." where student_id = '$a_id'";
	if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
		show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
	}
	if ( mysql_num_rows($result) != 0 ) {
		$Q1 = "SELECT a_id, q_id, type, question, selection1, selection2, selection3, selection4, selection5, ismultiple, grade FROM qtiku WHERE q_id='$q_id' and type='3' order by a_id";
		if ( !($result1 = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
		}
		if ( mysql_num_rows($result1) != 0 ) {
			include("class.FastTemplate.php3");
			$tpl = new FastTemplate("./templates");
			$tpl->define(array(main=>"showdetail.tpl"));
			$tpl->assign(SUBMIT,"");
			$tpl->assign(RESET,"");
			if($version == "C") {
				$tpl->assign(IMG,"img");	
			}
			else {
				$tpl->assign(IMG,"img_E");
			}

			$tpl->define_dynamic("rows","main");
			$qcounter = 0;
			while ( $rows = mysql_fetch_array($result1) ) {
				$tpl->assign(QUESTION,$rows['question']);
				$tpl->assign(TYPE,"");
				$tpl->assign(QGRADE,"");
				$tpl->assign(QNO,"");
				$tpl->parse(ROWS,".rows");
				
				$Q2 = "SELECT a_id, q_id, type, question, selection1, selection2, selection3, selection4, selection5, ismultiple, grade FROM qtiku WHERE q_id='$q_id' and block_id='".$rows['a_id']."' and type != '3' order by a_id";
				if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
					show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
				}
				while ( $rows2 = mysql_fetch_array($result2) ) {
					$qcounter ++;
					$tpl->assign(QNO,$qcounter);
					$tpl->assign(QUESTION,$rows2['question']);
					$tpl->assign(SEL,"");
					if ( $rows2['grade'] != null || $rows2['grade'] != "" ) {
						if ( $version == "C" )
							$tpl->assign(QGRADE,"(".$rows2['grade']."權重 ");
						else
							$tpl->assign(QGRADE,"(".$rows2['grade']."Weight ");
					}
					if ( $rows2['type'] == "1" ) {
						if($rows2['ismultiple'] == "0") {
							if($version == "C") {
								$tpl->assign(TYPE,"單選題)");
							}
							else {
								$tpl->assign(TYPE,"Single-select)");
							}
						}
						else {
							if($version == "C") {
								$tpl->assign(TYPE,"複選題)");
							}
							else {
								$tpl->assign(TYPE,"Multi-select)");
							}
						}
						$tpl->define(array(cont=>"showdetails.tpl"));
						$tpl->define_dynamic("row","cont");
						for ( $i = 0 ; $i < 5 ; $i ++ ) {
							$j = $i + 1;
							$S1 = "select count(a_id) as SUM from questionary_".$q_id." where q".$rows2['a_id']." = '".pow(2, $i)."' and student_id = '$a_id'";
							if ( !($s1 = mysql_db_query( $DB.$course_id, $S1 ) ) ) {
								echo( "資料庫讀取錯誤!! $S1" );
								exit;
							}
							$row = mysql_fetch_array($s1);
							$sele = "selection".$j;
							if ( $rows2["$sele"] != null || $rows2["$sele"] != "" ) {
								$tpl->assign(ORDER, $j);
								$tpl->assign(QUES, $rows2["$sele"]." <font color=\"#FF0000\">X".$row['SUM']."</font>" );
								$tpl->parse(CONT,".row");
							}
						}
					}
					else if ( $rows2['type'] == "2" ) {
						if($version == "C") {
							$tpl->assign(TYPE,"問答題)");
						}
						else {
							$tpl->assign(TYPE,"Q&A)");
						}
						$S1 = "select q".$rows2['a_id']." from questionary_".$q_id." where student_id = '$a_id' and q".$rows2['a_id']." != ''";
						if ( !($s1 = mysql_db_query( $DB.$course_id, $S1 ) ) ) {
							echo( "資料庫讀取錯誤!! $S1" );
							exit;
						}
						
						$tpl->define(array(cont=>"showdetailf.tpl"));
						
						while ( $row = mysql_fetch_array($s1) ) {
							$answer .= "$row[0]<br>";
						}
						$tpl->assign(TEXTAREA, "$answer");
					}
					//讓parse重新抓cont指向的檔案 特殊用法
					$tpl->parse(ROWS,".rows");
					$tpl->parse(ROWS,".cont");
					$tpl->row = "";
					$tpl->CONT = "";
					$tpl->cont = "";
				}
			}
			$tpl->parse(BODY,"main");
 			$tpl->FastPrint("BODY");
		}
		else {
			if($version == "C")
				$message = "沒有題目!!";
			else
				$message = "No Question";
			show_page_d ();
		}
	}
	else {
		if( $version=="C" )
			show_page( "not_access.tpl" ,"尚未填寫!!");
		else
			show_page( "not_access.tpl" ,"Never full out!!");
	}
}
function showalltotal () {
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $q_id, $a_id, $version, $course_id, $message;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}
	$Q1 = "select * from questionary_".$q_id;
	if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
		show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
	}
	$Q2 = "select sum(count) as total from take_questionary where q_id = '$q_id'";
	if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
		show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
	}
	$row2 = mysql_fetch_array( $result2 );
	$total = $row2['total'];
	if ( mysql_num_rows($result) != 0 ) {
		$Q1 = "SELECT a_id, q_id, type, question, selection1, selection2, selection3, selection4, selection5, ismultiple, grade FROM qtiku WHERE q_id='$q_id' and type='3' order by a_id";
		if ( !($result1 = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
		}
		if ( mysql_num_rows($result1) != 0 ) {
			include("class.FastTemplate.php3");
			$tpl = new FastTemplate("./templates");
			$tpl->define(array(main=>"showdetail.tpl"));
			$tpl->assign(SUBMIT,"");
			$tpl->assign(RESET,"");
			if($version == "C") {
				$tpl->assign(IMG,"img");	
			}
			else {
				$tpl->assign(IMG,"img_E");
			}

			$tpl->define_dynamic("rows","main");
			$qcounter = 0;
			while ( $rows = mysql_fetch_array($result1) ) {
				$tpl->assign(QUESTION,"<font color=\"#000000\"><b>".$rows['question']."</b></font>");
				$tpl->assign(TYPE,"");
				$tpl->assign(QGRADE,"");
				$tpl->assign(QNO,"");
				$tpl->parse(ROWS,".rows");
				
				$Q2 = "SELECT a_id, q_id, type, question, selection1, selection2, selection3, selection4, selection5, ismultiple, grade, question_desc FROM qtiku WHERE q_id='$q_id' and block_id='".$rows['a_id']."' and type != '3' order by a_id";
				if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
					show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
				}
				while ( $rows2 = mysql_fetch_array($result2) ) {
					$qcounter ++;
					$tpl->assign(QNO,$qcounter);
					if ( ($rows2['question_desc']%2) == "1" ) {
						$tpl->assign(QUESTION,"<font color=\"#FF0000\">".$rows2['question']."</font>");
					}
					else {
						$tpl->assign(QUESTION,"<font color=\"#0000FF\">".$rows2['question']."</font>");
					}
					$tpl->assign(SEL,"");
					if ( $rows2['grade'] != null || $rows2['grade'] != "" ) {
						if ( $version == "C" )
							$tpl->assign(QGRADE,"(".$rows2['grade']."權重 ");
						else
							$tpl->assign(QGRADE,"(".$rows2['grade']."Weight ");
					}
					if ( $rows2['type'] == "1" ) {
						$tpl->define(array(cont=>"showdetails.tpl"));
						$tpl->define_dynamic("row","cont");
						if($rows2['ismultiple'] == "0") {
							if($version == "C") {
								$tpl->assign(TYPE,"單選題)");
							}
							else {
								$tpl->assign(TYPE,"Single-select)");
							}
							for ( $i = 0 ; $i < 5 ; $i ++ ) {
								$j = $i + 1;
								$S1 = "select count(a_id) as SUM from questionary_".$q_id." where q".$rows2['a_id']." = '".pow(2, $i)."'";
								if ( !($s1 = mysql_db_query( $DB.$course_id, $S1 ) ) ) {
									echo( "資料庫讀取錯誤!! $S1" );
									exit;
								}
								$row = mysql_fetch_array($s1);
								$sele = "selection".$j;
								if ( $rows2["$sele"] != null || $rows2["$sele"] != "" ) {
									$tpl->assign(ORDER, $j);
									$percent = $row['SUM']/$total*100;
									$tpl->assign(QUES, $rows2["$sele"]." <font color=\"#FF0000\">X".$row['SUM']."　　　".substr($percent, 0, 4)."%</font>" );
									
									//$tpl->assign(QUES, $rows2["$sele"]." <font color=\"#FF0000\">X".$row['SUM']."</font>" );
									$tpl->parse(CONT,".row");
								}
							}
						}
						else {
							if($version == "C") {
								$tpl->assign(TYPE,"複選題)");
							}
							else {
								$tpl->assign(TYPE,"Multi-select)");
							}
							for ( $i = 0 ; $i < 5 ; $i ++ ) {
								$j = $i + 1;
								$S1 = "select count(a_id) as SUM from questionary_".$q_id." where mod(q".$rows2['a_id'].", ".pow(2, $j)." ) >= '".pow(2, $i)."'";
								if ( !($s1 = mysql_db_query( $DB.$course_id, $S1 ) ) ) {
									echo( "資料庫讀取錯誤!! $S1" );
									exit;
								}
								$row = mysql_fetch_array($s1);
								$sele = "selection".$j;
								if ( $rows2["$sele"] != null || $rows2["$sele"] != "" ) {
									$tpl->assign(ORDER, $j);
									$percent = $row['SUM']/$total*100;
									$tpl->assign(QUES, $rows2["$sele"]." <font color=\"#FF0000\">X".$row['SUM']."　　　".substr($percent, 0, 4)."%</font>" );
									
									//$tpl->assign(QUES, $rows2["$sele"]." <font color=\"#FF0000\">X".$row['SUM']."</font>" );
									$tpl->parse(CONT,".row");
								}
							}
						}
					}
					else if ( $rows2['type'] == "2" ) {
						if($version == "C") {
							$tpl->assign(TYPE,"問答題)");
						}
						else {
							$tpl->assign(TYPE,"Q&A)");
						}
						$S1 = "select q".$rows2['a_id']." from questionary_".$q_id." where q".$rows2['a_id']." != ''";
						if ( !($s1 = mysql_db_query( $DB.$course_id, $S1 ) ) ) {
							echo( "資料庫讀取錯誤!! $S1" );
							exit;
						}
						
						$answer = "";
						
						$tpl->define(array(cont=>"showdetailf.tpl"));
						
						while ( $row = mysql_fetch_array($s1) ) {
							$answer .= "<li>$row[0]</li><br>";
						}
						$tpl->assign(TEXTAREA, "$answer");
					}
					//讓parse重新抓cont指向的檔案 特殊用法
					$tpl->parse(ROWS,".rows");
					$tpl->parse(ROWS,".cont");
					$tpl->row = "";
					$tpl->CONT = "";
					$tpl->cont = "";
				}
			}
			$tpl->parse(BODY,"main");
 			$tpl->FastPrint("BODY");
		}
		else {
			if($version == "C")
				$message = "沒有題目!!";
			else
				$message = "No Question";
			show_page_d ();
		}
	}
	else {
		if( $version=="C" )
			show_page( "not_access.tpl" ,"尚未填寫!!");
		else
			show_page( "not_access.tpl" ,"Never full out!!");
	}
}

/*
function showalltotal () { //不記名
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $q_id, $a_id, $version, $course_id, $message;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}
	$Q1 = "select * from questionary_".$q_id;
	if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
		show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
	}
	if ( mysql_num_rows($result) != 0 ) {
		$Q1 = "SELECT a_id, q_id, type, question, selection1, selection2, selection3, selection4, selection5, ismultiple, grade FROM qtiku WHERE q_id='$q_id' and type='3' order by a_id";
		if ( !($result1 = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
		}
		if ( mysql_num_rows($result1) != 0 ) {
			include("class.FastTemplate.php3");
			$tpl = new FastTemplate("./templates");
			$tpl->define(array(main=>"showdetail.tpl"));
			$tpl->assign(SUBMIT,"");
			$tpl->assign(RESET,"");
			if($version == "C") {
				$tpl->assign(IMG,"img");	
			}
			else {
				$tpl->assign(IMG,"img_E");
			}

			$tpl->define_dynamic("rows","main");
			$qcounter = 0;
			while ( $rows = mysql_fetch_array($result1) ) {
				$tpl->assign(QUESTION,$rows['question']);
				$tpl->assign(TYPE,"");
				$tpl->assign(QGRADE,"");
				$tpl->assign(QNO,"");
				$tpl->parse(ROWS,".rows");
				
				$Q2 = "SELECT a_id, q_id, type, question, selection1, selection2, selection3, selection4, selection5, ismultiple, grade FROM qtiku WHERE q_id='$q_id' and block_id='".$rows['a_id']."' and type != '3' order by a_id";
				if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
					show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
				}
				while ( $rows2 = mysql_fetch_array($result2) ) {
					$qcounter ++;$id_counter++;
					$tpl->assign(QNO,$qcounter.".");
					$tpl->assign(QUESTION,$rows2['question']);
					$tpl->assign(SEL,"");
					if ( $rows2['grade'] != null || $rows2['grade'] != "" ) {
						if ( $version == "C" )
							$tpl->assign(QGRADE,"(".$rows2['grade']."權重 ");
						else
							$tpl->assign(QGRADE,"(".$rows2['grade']."Weight ");
					}
					if ( $rows2['type'] == "1" ) {
						if($rows2['ismultiple'] == "0") {
							if($version == "C") {
								$tpl->assign(TYPE,"單選題)");
							}
							else {
								$tpl->assign(TYPE,"Single-select)");
							}
						}
						else {
							if($version == "C") {
								$tpl->assign(TYPE,"複選題)");
							}
							else {
								$tpl->assign(TYPE,"Multi-select)");
							}
						}
						$tpl->define(array(cont=>"showdetails.tpl"));  
						$tpl->define_dynamic("row","cont");  
						$weight=4;
						$fill_counter=0; //已填寫人數
						
						for ( $i = 0 ; $i < 5 ; $i ++ ) { //最多5個選項
							$j = $i + 1;
							$S1 = "select count(a_id) as SUM from questionary_".$q_id." where q".$rows2['a_id']." = '$j'";
							if ( !($s1 = mysql_db_query( $DB.$course_id, $S1 ) ) ) {
								echo( "資料庫讀取錯誤!! $S1" );
								exit;
							}
							$row = mysql_fetch_array($s1);
							$sele = "selection".$j;
							
							$a=$row['SUM']*$weight; //單次加權
							$weight--; //五個選項，加權分別為4,3,2,1,0
							$weight_sum+=$a;  //總加權
							$fill_counter+=$row['SUM'];
							
							if ( $rows2["$sele"] != null || $rows2["$sele"] != "" ) {
								$tpl->assign(ORDER, $j);
								$tpl->assign(QUES, $rows2["$sele"]." <font color=\"#FF0000\">x".$row['SUM']."</font>" );  //選擇此題的有幾(sum)人
								$tpl->parse(CONT,".row"); //動態樣板分析
							}
						}
						$avg_weight = $weight_sum / $fill_counter;
						echo(" 此科目的整體滿意度為： "."$avg_weight"."<br>"); 
						echo(" (滿意度介於0~4分) ");
					}
					else if ( $rows2['type'] == "2" ) {
						if($version == "C") {
							$tpl->assign(TYPE,"問答題)");
						}
						else {
							$tpl->assign(TYPE,"Q&A)");
						}
						$S1 = "select q".$rows2['a_id']." from questionary_".$q_id." where q".$rows2['a_id']." != ''";
						if ( !($s1 = mysql_db_query( $DB.$course_id, $S1 ) ) ) {
							echo( "資料庫讀取錯誤!! $S1" );
							exit;
						}
						$answer = "";
						
						$tpl->define(array(cont=>"showdetailf.tpl")); 
						 
						while ( $row = mysql_fetch_array($s1) ) {  //有幾個人填就跑幾次
							$answer .= "<li>$row[0]</li><br>";					
						}
						$tpl->assign(TEXTAREA, "$answer");
					}
					//讓parse重新抓cont指向的檔案 特殊用法
					$tpl->parse(ROWS,".rows");
					$tpl->parse(ROWS,".cont");
					$tpl->row = "";
					$tpl->CONT = "";
					$tpl->cont = "";
				}
			}
			$tpl->parse(BODY,"main");
 			$tpl->FastPrint("BODY");
		}
		else {
			if($version == "C")
				$message = "沒有題目!!";
			else
				$message = "No Question";
			show_page_d ();
		}
	}
	else {
		if( $version=="C" )
			show_page( "not_access.tpl" ,"尚未填寫!!");
		else
			show_page( "not_access.tpl" ,"Never full out!!");
	}
}
/*function showalltotal () {  //不記名
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $q_id, $a_id, $version, $course_id, $message;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}
	$Q1 = "select * from questionary_".$q_id;
	if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
		show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
	}
	if ( mysql_num_rows($result) != 0 ) {
		$Q1 = "SELECT a_id, q_id, type, question, selection1, selection2, selection3, selection4, selection5, ismultiple, grade FROM qtiku WHERE q_id='$q_id' and type='3' order by a_id";
		if ( !($result1 = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
		}
		if ( mysql_num_rows($result1) != 0 ) {
			include("class.FastTemplate.php3");
			$tpl = new FastTemplate("./templates");
			$tpl->define(array(main=>"showdetail.tpl"));
			$tpl->assign(SUBMIT,"");
			$tpl->assign(RESET,"");
			if($version == "C") {
				$tpl->assign(IMG,"img");	
			}
			else {
				$tpl->assign(IMG,"img_E");
			}

			$tpl->define_dynamic("rows","main");
			$qcounter = 0;
			while ( $rows = mysql_fetch_array($result1) ) {
				$tpl->assign(QUESTION,$rows['question']);
				$tpl->assign(TYPE,"");
				$tpl->assign(QGRADE,"");
				$tpl->assign(QNO,"");
				$tpl->parse(ROWS,".rows");
				
				$Q2 = "SELECT a_id, q_id, type, question, selection1, selection2, selection3, selection4, selection5, ismultiple, grade FROM qtiku WHERE q_id='$q_id' and block_id='".$rows['a_id']."' and type != '3' order by a_id";
				if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
					show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
				}
				while ( $rows2 = mysql_fetch_array($result2) ) {
					$qcounter ++;
					$tpl->assign(QNO,$qcounter);
					$tpl->assign(QUESTION,$rows2['question']);
					$tpl->assign(SEL,"");
					if ( $rows2['grade'] != null || $rows2['grade'] != "" ) {
						if ( $version == "C" )
							$tpl->assign(QGRADE,"(".$rows2['grade']."權重 ");
						else
							$tpl->assign(QGRADE,"(".$rows2['grade']."Weight ");
					}
					if ( $rows2['type'] == "1" ) {
						if($rows2['ismultiple'] == "0") {
							if($version == "C") {
								$tpl->assign(TYPE,"單選題)");
							}
							else {
								$tpl->assign(TYPE,"Single-select)");
							}
						}
						else {
							if($version == "C") {
								$tpl->assign(TYPE,"複選題)");
							}
							else {
								$tpl->assign(TYPE,"Multi-select)");
							}
						}
						$tpl->define(array(cont=>"showdetails.tpl"));
						$tpl->define_dynamic("row","cont");
						for ( $i = 0 ; $i < 5 ; $i ++ ) {
							$j = $i + 1;
							$S1 = "select count(a_id) as SUM from questionary_".$q_id." where q".$rows2['a_id']." = '$j'";
							$a=$row['SUM']*$weight;
							$weight--;
							$weight_sum+=$a;
							if ( !($s1 = mysql_db_query( $DB.$course_id, $S1 ) ) ) {
								echo( "資料庫讀取錯誤!! $S1" );
								exit;
							}
							$row = mysql_fetch_array($s1);
							$sele = "selection".$j;
							if ( $rows2["$sele"] != null || $rows2["$sele"] != "" ) {
								$tpl->assign(ORDER, $j);
								$tpl->assign(QUES, $rows2["$sele"]." <font color=\"#FF0000\">x".$row['SUM']."</font>" );
								$tpl->parse(CONT,".row");
							}
						}
						$avg_weight = $weight_sum / $id_counter; //by via
						// --enable-bcmath;
						//bcscale(2);
						echo(" 此科目的整體滿意度為： "."$avg_weight"."<br>"); 
						echo(" (滿意度介於0~4分) ");
					}
					else if ( $rows2['type'] == "2" ) {
						if($version == "C") {
							$tpl->assign(TYPE,"問答題)");
						}
						else {
							$tpl->assign(TYPE,"Q&A)");
						}
						$S1 = "select q".$rows2['a_id']." from C_".$q_id." where q".$rows2['a_id']." != ''";
						if ( !($s1 = mysql_db_query( $DB.$course_id, $S1 ) ) ) {
							echo( "資料庫讀取錯誤!! $S1" );
							exit;
						}
						
						$tpl->define(array(cont=>"showdetailf.tpl"));
						
						while ( $row = mysql_fetch_array($s1) ) {
							$answer .= "$row[0]<br>";
						}
						$tpl->assign(TEXTAREA, "$answer");
					}
					//讓parse重新抓cont指向的檔案 特殊用法
					$tpl->parse(ROWS,".rows");
					$tpl->parse(ROWS,".cont");
					$tpl->row = "";
					$tpl->CONT = "";
					$tpl->cont = "";
				}
			}
			$tpl->parse(BODY,"main");
 			$tpl->FastPrint("BODY");
		}
		else {
			if($version == "C")
				$message = "沒有題目!!";
			else
				$message = "No Question";
			show_page_d ();
		}
	}
	else {
		if( $version=="C" )
			show_page( "not_access.tpl" ,"尚未填寫!!");
		else
			show_page( "not_access.tpl" ,"Never full out!!");
	}
}
*/
function pubquestionary () {
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $q_id, $version, $course_id, $message;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}
	$Q1 = "SELECT name,beg_time,end_time,public FROM questionary WHERE a_id='$q_id'";
	if ( !($result1 = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
		show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
	}
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate("./templates");
	if ( $version == "C" )
		$tpl->define(array(main=>"pubquestionary.tpl"));
	else
		$tpl->define(array(main=>"pubquestionary_E.tpl"));
	$tpl->define_dynamic("yy","main");
	$tpl->define_dynamic("ye","main");

	$row1 = mysql_fetch_row( $result1 );
	$tpl->assign(QNAME,$row1[0]);
	$tpl->assign(QID,$q_id);
	if(($row1[2] == "00000000000000")&&($row1[3] == "0"))
	{  
		if($version == "C")  
			$tpl->assign(STATUS,"未發佈");
		else
			$tpl->assign(STATUS,"Never Public");
		$end_day = date("d");
		$end_month = date("m");
	}
	else
	{
		//mysql 4 to 5, timestamp由YYYYMMDDHHMMSS改為YYYY-MM-DD HH:MM:SS
		/*
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
		*/
		
		$beg_y = (int) substr($row1[1],0,4);
		$beg_mo = (int) substr($row1[1],5,2);
		$beg_d = (int) substr($row1[1],8,2);
		$beg_h = (int) substr($row1[1],11,2);
		$beg_m = (int) substr($row1[1],14,2);
		$end_y = (int) substr($row1[2],0,4);
		$end_mo = (int) substr($row1[2],5,2);
		$end_d = (int) substr($row1[2],8,2);
		$end_h = (int) substr($row1[2],11,2);
		$end_m = (int) substr($row1[2],14,2);
		
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
					$tpl->assign(STATUS,date("Y-m-d H:i",mktime(substr($row1[1],11,2),substr($row1[1],14,2),0,substr($row1[1],5,2),substr($row1[1],8,2),substr($row1[1],0,4)))."發佈 維期 $range 小時");
				else
					$tpl->assign(STATUS,date("Y-m-d H:i",mktime(substr($row1[1],11,2),substr($row1[1],14,2),0,substr($row1[1],5,2),substr($row1[1],8,2),substr($row1[1],0,4)))."Public During $range Hourse");
			}
			else {
				if($version == "C" )
					$tpl->assign(STATUS,date("Y-m-d H:i",mktime(substr($row1[1],11,2),substr($row1[1],14,2),0,substr($row1[1],5,2),substr($row1[1],8,2),substr($row1[1],0,4)))."發佈 ".date("Y-m-d H:i",mktime(substr($row1[2],11,2),substr($row1[2],14,2),0,substr($row1[2],5,2),substr($row1[2],8,2),substr($row1[2],0,4)))."結束");
				else
					$tpl->assign(STATUS,date("Y-m-d H:i",mktime(substr($row1[1],11,2),substr($row1[1],14,2),0,substr($row1[1],5,2),substr($row1[1],8,2),substr($row1[1],0,4)))."Public, End at".date("Y-m-d H:i",mktime(substr($row1[2],11,2),substr($row1[2],14,2),0,substr($row1[2],5,2),substr($row1[2],8,2),substr($row1[2],0,4))));
			}
		}
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
		if ( ($j == 2 && $row1[3] != 1 && $row1[3] != 3) || ($year == substr( $row1[2], 0, 4 ) && $row1[3] == 1)  )
			$tpl->assign(YEAEV, "$year selected");
		else
			$tpl->assign(YEAEV,$year);
		$tpl->assign(YEAED,$year);
		$tpl->parse(YE,".ye");
	} 
	$beg_day=substr($row1[1],6,2);
	$beg_month=substr($row1[1],4,2);
	$DV = "DA".$beg_day;
	$MV = "MA".$beg_month;
	$tpl->assign($DV, "selected");	
	$tpl->assign($MV , "selected");
	$bh= substr($row1[1],8,2);
	$bm= substr($row1[1],10,2);
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
