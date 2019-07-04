<?php
require 'fadmin.php';
/***************
 *2007/07/27 by intree
 *re_cacu_exam_grade.php : 在學生作完測驗時若老師有修改答案或配分可依答案記錄重算學生成績
 *check 是否修改比例也要 
 ***************/

if( isset($PHPSESSID) && (session_check_teach($PHPSESSID) >= 2) )
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
        if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
                show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
        }

	//從exam_id取得答案和配分,只要選擇 是非和填充題;
	//若type=3(填充最多4格)則ismultiple=1(依序) 0(無序)
	$Q1 = "SELECT a_id, answer, grade, type, ismultiple, selection1, selection2, selection3, selection4 FROM tiku WHERE exam_id='$exam_id' AND type<4 ORDER BY 'a_id'";
	if ( !($result1 = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
                        show_page( "not_access.tpl" ,"資料庫讀取錯誤1!!" );
        }

	//取得該測驗學生資料,
	//問答題的原始分數從qa table而來
	$Q2 = "SELECT student_id FROM take_exam WHERE exam_id='$exam_id' ORDER BY grade DESC";
	if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
                        show_page( "not_access.tpl" ,"資料庫讀取錯誤2!!" );
        }

	$i=0;
	while($rows = mysql_fetch_array($result1)){
		$answer_set['a_id'][$i]=$rows['a_id'];
		$answer_set['answer'][ $rows['a_id'] ]=$rows['answer'];
		$answer_set['grade'][ $rows['a_id'] ]=$rows['grade'];
		$answer_set['type'][ $rows['a_id'] ]=$rows['type'];
		$answer_set['ismultiple'][ $rows['a_id'] ]=$rows['ismultiple'];
		$answer_set['selection1'][ $rows['a_id'] ]=$rows['selection1'];
		$answer_set['selection2'][ $rows['a_id'] ]=$rows['selection2'];
		$answer_set['selection3'][ $rows['a_id'] ]=$rows['selection3'];
		$answer_set['selection4'][ $rows['a_id'] ]=$rows['selection4'];

		$i++;
	}
	//$answer_set_size = $i;

	while( $rows2 = mysql_fetch_array($result2) ){
		
		$studentID = $rows2['student_id'];
		$qa_grade = get_qa_grade($studentID , $exam_id);
		//echo 'stu_id:'.$studentID.'&qa_grade:'.$qa_grade.'<br>';
		
		$Q3 = "SELECT  * FROM qa2 WHERE student_id='$studentID' AND exam_id='$exam_id' ORDER BY  tiku_a_id ";
		
		if ( !($result3 = mysql_db_query( $DB.$course_id, $Q3 ) ) ) {
             show_page( "not_access.tpl" ,"資料庫讀取錯誤3!!" );
        }
		
		$nonqa_grade=0;
		$tiku_a_id = 0;
		$isDone = 0;// added by jimmykuo 20100504 判斷是否做過測驗

		//取得該學生的所有答案紀錄
		while( $rows3 = mysql_fetch_array($result3) ){
			$isDone = 1;// added by jimmykuo 20100504 有作答記錄在qa2表示有做過測驗
			$tiku_a_id = $rows3['tiku_a_id'];
			$type=$answer_set['type'][$tiku_a_id];
			//若是選擇題和是非題
			if($type <= 2){
				if($rows3['stu_ans_1']==$answer_set['answer'][ $tiku_a_id ]){
					$nonqa_grade += $answer_set['grade'][$tiku_a_id];
				}
			}
			//若是填充題
			else if($type == 3){
				$check = 1; //check = 1表此題正確, 0表錯誤
				//若是依序
				if( $answer_set['ismultiple'][ $tiku_a_id ]==1 ){
					for( $k = 1 ; $k <= $answer_set['answer'][ $tiku_a_id ] ; $k++){
						$number = 'selection'.$k;
						$number_stu = 'stu_ans_'.$k;
						//四格答案順序完全符合才能得到此題分數
						if( $answer_set[$number][$tiku_a_id ] != $rows3[$number_stu] ){
							$check = 0;
							break;
						}
					}
				}
				//若是無順序
				else{
					$check = 0;
					//added by jimmykuo @ 20101006, 
					//功能:複製一份正確答案(selection1~4)，用來比對學生的答案，若其中有一個選項(selectionX)
					//與學生某一個選項的作答結果相同，則selectionX被消掉，剩下的正確答案選項與學生的下一個作答選項進行比對
					$tmp_answer_set['selection1'][$tiku_a_id]=$answer_set['selection1'][$tiku_a_id];
					$tmp_answer_set['selection2'][$tiku_a_id]=$answer_set['selection2'][$tiku_a_id];
					$tmp_answer_set['selection3'][$tiku_a_id]=$answer_set['selection3'][$tiku_a_id];
					$tmp_answer_set['selection4'][$tiku_a_id]=$answer_set['selection4'][$tiku_a_id];

					for($k = 1; $k <= $answer_set['answer'][ $tiku_a_id ] ; $k++){
						$number_stu = 'stu_ans_'.$k;
						
						if( $rows3[$number_stu] == "" ){
							$check = 0;
							break;
						}
						
						for($j = 1; $j <= $answer_set['answer'][ $tiku_a_id ] ; $j++){
						$answer_no = 'selection'.$j;
							if ( $tmp_answer_set[$answer_no][$tiku_a_id ] == $rows3[$number_stu] ){
								$tmp_answer_set[$answer_no][$tiku_a_id ] = "";
								$check ++;
								break;
							}//if
						}//for
					}//for($k)
					//答對的格數=總格數 才能得到此題分數
					if ( $check == $answer_set['answer'][ $tiku_a_id ]  )
						$check = 1;
					else
						$check = 0;
				}//else if( $rows3['ismultiple']==null )
				
				if($check ==1) $nonqa_grade += $answer_set['grade'][$tiku_a_id];
			}//else if($rows3['type'] == 3)
			
		}//while
		//echo 'student:'.$studentID.'&nonqa_grade:'.$nonqa_grade.'<br>';
		$grade = $nonqa_grade + $qa_grade;
		//echo 'grade = '.$grade;
		//更新學生總分 modified by jimmykuo 20100504 有做過測驗才會更新, 否則不會重算成績, 仍然是未測驗
                if( $isDone == 1 ){
                        $Q4 = "UPDATE take_exam SET grade='$grade', nonqa_grade='$nonqa_grade' WHERE exam_id='$exam_id' AND student_id='$studentID' ";

			if( !($result4 = mysql_db_query($DB.$course_id, $Q4)) ){
				show_page( "not_access.tpl" ,"資料庫讀取錯誤7!!" );
			}
		}
		
	}//while

//全部重算完成後頁面重導向
echo"		
    <head>
		<script language=\"JavaScript\" type=\"text/JavaScript\">alert(\"成績重算完畢。\");</script>
        <meta http-equiv=\"refresh\" content=\"0;url=modify_test.php\" />
    </head>
";
	
	
}//if( isset($PHPSESSID) && (session_check_teach($PHPSESSID) >= 2) )
else
{
        if( $version=="C" )
                show_page( "not_access.tpl" ,"你沒有權限使用此功能");
        else
                show_page( "not_access.tpl" ,"You have No Permission!!");
}

function get_qa_grade( $studentID, $examID){
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD,$course_id;
    if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
             echo "資料庫連結錯誤5!!";
    }

	$Q1 = "SELECT grade FROM qa WHERE student_id='$studentID' AND exam_id = '$examID' ";
	if(  !($result1 = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
             echo "資料庫讀取錯誤6!!" ;
    }
	
	$qa_grade=0;
	while($rows = mysql_fetch_array($result1) ){
		// -1表示老師尚未批改
		if($rows['grade']>-1)
			$qa_grade += $rows['grade'];
	}
	
	return $qa_grade;
}

?>
