<?php
require 'fadmin.php';
/***************
 *2007/07/27 by intree
 *re_cacu_exam_grade.php : �b�ǥͧ@������ɭY�Ѯv���קﵪ�שΰt���i�̵��װO������ǥͦ��Z
 *check �O�_�ק��Ҥ]�n 
 ***************/

if( isset($PHPSESSID) && (session_check_teach($PHPSESSID) >= 2) )
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
        if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
                show_page( "not_access.tpl" ,"��Ʈw�s�����~!!" );
        }

	//�qexam_id���o���שM�t��,�u�n��� �O�D�M��R�D;
	//�Ytype=3(��R�̦h4��)�hismultiple=1(�̧�) 0(�L��)
	$Q1 = "SELECT a_id, answer, grade, type, ismultiple, selection1, selection2, selection3, selection4 FROM tiku WHERE exam_id='$exam_id' AND type<4 ORDER BY 'a_id'";
	if ( !($result1 = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
                        show_page( "not_access.tpl" ,"��ƮwŪ�����~1!!" );
        }

	//���o�Ӵ���ǥ͸��,
	//�ݵ��D����l���Ʊqqa table�Ө�
	$Q2 = "SELECT student_id FROM take_exam WHERE exam_id='$exam_id' ORDER BY grade DESC";
	if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
                        show_page( "not_access.tpl" ,"��ƮwŪ�����~2!!" );
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
             show_page( "not_access.tpl" ,"��ƮwŪ�����~3!!" );
        }
		
		$nonqa_grade=0;
		$tiku_a_id = 0;
		$isDone = 0;// added by jimmykuo 20100504 �P�_�O�_���L����

		//���o�Ӿǥͪ��Ҧ����׬���
		while( $rows3 = mysql_fetch_array($result3) ){
			$isDone = 1;// added by jimmykuo 20100504 ���@���O���bqa2��ܦ����L����
			$tiku_a_id = $rows3['tiku_a_id'];
			$type=$answer_set['type'][$tiku_a_id];
			//�Y�O����D�M�O�D�D
			if($type <= 2){
				if($rows3['stu_ans_1']==$answer_set['answer'][ $tiku_a_id ]){
					$nonqa_grade += $answer_set['grade'][$tiku_a_id];
				}
			}
			//�Y�O��R�D
			else if($type == 3){
				$check = 1; //check = 1���D���T, 0����~
				//�Y�O�̧�
				if( $answer_set['ismultiple'][ $tiku_a_id ]==1 ){
					for( $k = 1 ; $k <= $answer_set['answer'][ $tiku_a_id ] ; $k++){
						$number = 'selection'.$k;
						$number_stu = 'stu_ans_'.$k;
						//�|�浪�׶��ǧ����ŦX�~��o�즹�D����
						if( $answer_set[$number][$tiku_a_id ] != $rows3[$number_stu] ){
							$check = 0;
							break;
						}
					}
				}
				//�Y�O�L����
				else{
					$check = 0;
					//added by jimmykuo @ 20101006, 
					//�\��:�ƻs�@�����T����(selection1~4)�A�ΨӤ��ǥͪ����סA�Y�䤤���@�ӿﶵ(selectionX)
					//�P�ǥͬY�@�ӿﶵ���@�����G�ۦP�A�hselectionX�Q�����A�ѤU�����T���׿ﶵ�P�ǥͪ��U�@�ӧ@���ﶵ�i����
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
					//���諸���=�`��� �~��o�즹�D����
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
		//��s�ǥ��`�� modified by jimmykuo 20100504 �����L����~�|��s, �_�h���|���⦨�Z, ���M�O������
                if( $isDone == 1 ){
                        $Q4 = "UPDATE take_exam SET grade='$grade', nonqa_grade='$nonqa_grade' WHERE exam_id='$exam_id' AND student_id='$studentID' ";

			if( !($result4 = mysql_db_query($DB.$course_id, $Q4)) ){
				show_page( "not_access.tpl" ,"��ƮwŪ�����~7!!" );
			}
		}
		
	}//while

//�������⧹���᭶�����ɦV
echo"		
    <head>
		<script language=\"JavaScript\" type=\"text/JavaScript\">alert(\"���Z���⧹���C\");</script>
        <meta http-equiv=\"refresh\" content=\"0;url=modify_test.php\" />
    </head>
";
	
	
}//if( isset($PHPSESSID) && (session_check_teach($PHPSESSID) >= 2) )
else
{
        if( $version=="C" )
                show_page( "not_access.tpl" ,"�A�S���v���ϥΦ��\��");
        else
                show_page( "not_access.tpl" ,"You have No Permission!!");
}

function get_qa_grade( $studentID, $examID){
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD,$course_id;
    if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
             echo "��Ʈw�s�����~5!!";
    }

	$Q1 = "SELECT grade FROM qa WHERE student_id='$studentID' AND exam_id = '$examID' ";
	if(  !($result1 = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
             echo "��ƮwŪ�����~6!!" ;
    }
	
	$qa_grade=0;
	while($rows = mysql_fetch_array($result1) ){
		// -1��ܦѮv�|�����
		if($rows['grade']>-1)
			$qa_grade += $rows['grade'];
	}
	
	return $qa_grade;
}

?>
