<?php
require 'fadmin.php';
require '../CoreDescript.php';

update_status ("�ҵ{����");
if ( !(isset($PHPSESSID) && ($check = session_check_teach($PHPSESSID)) || ( isset( $courseid ) && ($check = session_check_stu($PHPSESSID)) ) ) ) {

		show_page( "not_access.tpl" ,"�v�����~");
}
if ( $query == 1 )
	$course_id = $courseid;
	if ( $check == 2 ) {
		if ( isset($upload) && $upload == "1" ) {
			if ( $file != "none" && $file != "") {
				//���ޤW�Ǫ��ɦW�O����A�@�ߧאּindex.xxx----------------------------------
				$ext = strrchr( $file_name, '.' );
				$new_file = "index".$ext;
				if( fileupload ( $file, "../../$course_id/intro", $new_file ) ) {
					$message = "�ɮ� $new_file �W�ǧ���";
					add_log("27",$user_id,"",$course_id,"","");
				}
				else
					$message = "�ɮ� $new_file �W�ǥ���";//�W�ǧ���->�W�ǥ��� by intree
			}
			show_page_d ( $message );
		}
		else if ( isset($del) && $del == "1" ) {
			if(strlen($filename) == 0) {
				header("Location: ./intro.php?PHPSESSID=$PHPSESSID");
				exit;
			}

			$_target = realpath( "../../$course_id/intro/$filename" );
			$doc_root = "/$course_id/intro/";
			// �w���ˬd
			$_target2 = str_replace ( "\\", "/", $_target );
			$pos = strpos($_target2, $doc_root);
			if($pos === false) {
				show_page("not_access.tpl", "Access Denied.");
				exit();
			}

			if(unlink($_target))
			{
				$message = "�ɮ� $filename �R������";
				add_log("26",$user_id,"",$course_id,"","");
				//var_dump($course_id);
			}
			else
			{
				$message = "�ɮ� $filename �R�����~!!";
			}
			
			show_page_d ( $message );
		}
		else //�B�z��J��form ClassIntroIndexHtml

			if(isset($FormType) == 0 || $FormType == 0){
				//form���ǲή榡

				$FormType = 0;

				if ( isset($intro) ) {
					if ( ($error = add_intro( $intro )) == -1 ) {
						if ( $version == "C" )
							show_page_d ( "�ҵ{���Х[�J����" );
						else
							show_page_d ( "Data insert complete" );
					}
					else
						show_page_d ( $error );
				}
				else{
					show_page_d ();
				}
			}
			else{
				//form�����ؤu�{�{�Ү榡

				//�q��Ʈw���oa_id

				//$a_id = Get_a_id($course_id);  modify by bluejam

				$a_id = $course_id;

				$group_id = Get_group_id($a_id);

				//echo "course_id:" . $course_id . "<br>";	//for test
				//echo "a_id:" . $a_id . "<br>";	//for test
				//echo "group_id:" . $group_id . "<br>";	//for test


				//���NLearningGoal�ʺA��쪺�Ȧs�b�@�_
				for($LearningGoalNumberCounter = 1; $LearningGoalNumberCounter<=$LearningGoalNumber; $LearningGoalNumberCounter++){
					$LearningGoalNameTemp = "LearningGoal".$LearningGoalNumberCounter;
					$LearningGoalList[ $LearningGoalNumberCounter] = $$LearningGoalNameTemp;
				}

				//���NTeachKeyPoint��쪺�Ȧs�b�@�_
				$TeachKeyPointNumber = 17;
				$TeachKeyPointGradeMannerStart = 5;
				$TeachKeyPointGradeMannerEnd = 14;
				for($TeachKeyPointNumberCounter = 1; $TeachKeyPointNumberCounter<=$TeachKeyPointNumber; $TeachKeyPointNumberCounter++){
					$TeachKeyPointNameTemp = "TeachKeyPoint" . $TeachKeyPointNumberCounter;

					if($$TeachKeyPointNameTemp.checked == 1){	$TeachKeyPointList[ $TeachKeyPointNumberCounter] = 1;	}
					else{										$TeachKeyPointList[ $TeachKeyPointNumberCounter] = 0;	}

					//echo $TeachKeyPointList[ $TeachKeyPointNumberCounter];	//for test

					//���o���Z�����t
					if( ($TeachKeyPointNumberCounter>=$TeachKeyPointGradeMannerStart) && ($TeachKeyPointNumberCounter<=$TeachKeyPointGradeMannerEnd))
					{
						$TeachKeyPointGradeNameTemp = "TeachKeyPointGrade" . $TeachKeyPointNumberCounter;

						$TeachKeyPointGradeList[ $TeachKeyPointNumberCounter] = $$TeachKeyPointGradeNameTemp;
					}
					else
					{
						$TeachKeyPointGradeList[ $TeachKeyPointNumberCounter] = "";
					}
				}

				//���NClassTopic�ʺA��쪺�Ȧs�b�@�_
				for($ClassTopicNumberCounter = 1; $ClassTopicNumberCounter<=$ClassTopicNumber; $ClassTopicNumberCounter++){
					$ClassTopicNameTemp = "ClassTopic".$ClassTopicNumberCounter;
					$ClassTopicList[ $ClassTopicNumberCounter] = $$ClassTopicNameTemp;

					$ClassTopicContentNameTemp = "ClassTopicContent".$ClassTopicNumberCounter;
					$ClassTopicContentList[ $ClassTopicNumberCounter] = $$ClassTopicContentNameTemp;

					$ClassTopicTeachTimeNameTemp = "ClassTopicTeachTime".$ClassTopicNumberCounter;
					$ClassTopicTeachTimeList[ $ClassTopicNumberCounter] = $$ClassTopicTeachTimeNameTemp;

					$ClassTopicDemonstrateTimeNameTemp = "ClassTopicDemonstrateTime".$ClassTopicNumberCounter;
					$ClassTopicDemonstrateTimeList[ $ClassTopicNumberCounter] = $$ClassTopicDemonstrateTimeNameTemp;
					//echo $ClassTopicDemonstrateTimeList[ $ClassTopicNumberCounter];//for test

					$ClassTopicExerciseTimeNameTemp = "ClassTopicExerciseTime".$ClassTopicNumberCounter;
					$ClassTopicExerciseTimeList[ $ClassTopicNumberCounter] = $$ClassTopicExerciseTimeNameTemp;

					$ClassTopicOtherTimeNameTemp = "ClassTopicOtherTime".$ClassTopicNumberCounter;
					$ClassTopicOtherTimeList[ $ClassTopicNumberCounter] = $$ClassTopicOtherTimeNameTemp;

					$ClassTopicRemarkNameTemp = "ClassTopicRemark".$ClassTopicNumberCounter;
					$ClassTopicRemarkList[ $ClassTopicNumberCounter] = $$ClassTopicRemarkNameTemp;
				}


				//SQL Server�����
				global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;


				//�qTable course���o�ҵ{�W�ٸ�ҵ{�N�X
				$SQL_Select = "SELECT * FROM course WHERE a_id = '$a_id'";
				if ( !($result = mysql_db_query( $DB, $SQL_Select ) ) ) {
					$message = "'$SQL_Select' ��ƮwŪ�����~!!<br>";
					echo $message;
				}
				$CourseNumber = mysql_num_rows( $result);
				if($CourseNumber == 1)
				{
					$row = mysql_fetch_array( $result );

					$ClassNameChinese = $row['name'];
					$ClassID = $row['course_no'];
				}

				//�qTable course_group���o�}�ҳ��
				$SQL_Select = "SELECT * FROM course A, course_group B WHERE A.a_id = '$a_id' AND A.group_id = B.a_id";
				if ( !($result = mysql_db_query( $DB, $SQL_Select ) ) ) {
					$message = "'$SQL_Select' ��ƮwŪ�����~!!<br>";
					echo $message;
				}
				$GroupNumber = mysql_num_rows( $result);
				if($GroupNumber == 1)
				{
					$row = mysql_fetch_array( $result );

					$ClassOpenDepartment = $row['name'];
				}

				//�qTable user���o�½ұЮv�W��
				session_start();
				$course_year = $_SESSION['course_year'];	//���o�Ǧ~��
				$course_term = $_SESSION['course_term'];	//���o�Ǵ�

				$SQL_Select = "SELECT B.name FROM teach_course A, user B WHERE A.course_id = '$a_id' AND A.year = '$course_year' AND A.term = '$course_term' AND A.teacher_id = B.a_id AND B.authorization=1";
				if ( !($result = mysql_db_query( $DB, $SQL_Select ) ) ) {
					$message = "'$SQL_Select' ��ƮwŪ�����~!!<br>";
					echo $message;
				}
				$GroupNumber = mysql_num_rows( $result);
				if($GroupNumber == 1)
				{
					$row = mysql_fetch_array( $result );

					$Teacher = $row['name'];
				}



				//���oClassGoal�Ҧ��ܼƪ��s��
				$SQL_Select = "SELECT * FROM IEET_ClassGoal WHERE group_id = '$group_id' ORDER BY ClassGoalNo ASC";
				if ( !($result = mysql_db_query( $DB, $SQL_Select ) ) ) {
					$message = "'$SQL_Select' ��ƮwŪ�����~!!<br>";
					echo $message;
				}
				$ClassGoalNumber = mysql_num_rows( $result);
				//echo "ClassGoalNumber:" . $ClassGoalNumber . "<br>";	//for test
				for($ClassGoalNumberCounter = 1; $ClassGoalNumberCounter <= $ClassGoalNumber; $ClassGoalNumberCounter++){
					$row = mysql_fetch_array( $result );

					$ClassGoal_Index[ $ClassGoalNumberCounter] = $row['ClassGoal_Index'];
					$ClassGoalNo[ $ClassGoalNumberCounter] = $row['ClassGoalNo'];
					//echo "ClassGoal_Index:" . $ClassGoal_Index[ $ClassGoalNumberCounter] . "<br>";	//for test
				}

				$CoreAbilitiesTotalNumber = 0;
				for($ClassGoalNumberCounter = 1; $ClassGoalNumberCounter <= $ClassGoalNumber; $ClassGoalNumberCounter++){
					$SQL_Select = "SELECT * FROM IEET_CoreAbilities WHERE group_id = '$group_id' AND ClassGoal_Index = '$ClassGoalNo[$ClassGoalNumberCounter]' ORDER BY CoreAbilitiesNo ASC";

					if ( !($result = mysql_db_query( $DB, $SQL_Select ) ) ) {
						$message = "'$SQL_Select' ��ƮwŪ�����~!!<br>";
						echo $message;
					}

					$CoreAbilitiesNumber[ $ClassGoalNumberCounter] = mysql_num_rows( $result);
					for($CoreAbilitiesNumberCounter = 1; $CoreAbilitiesNumberCounter <= $CoreAbilitiesNumber[ $ClassGoalNumberCounter]; $CoreAbilitiesNumberCounter++){
						$row = mysql_fetch_array( $result );

						$CoreAbilities_Index[ $ClassGoalNumberCounter][ $CoreAbilitiesNumberCounter] = $row['CoreAbilities_Index'];
						$CoreAbilitiesNo[ $ClassGoalNumberCounter][ $CoreAbilitiesNumberCounter] = $row['CoreAbilitiesNo'];
						$content[ $ClassGoalNumberCounter][ $CoreAbilitiesNumberCounter] = $row['content'];

						//echo $CoreAbilities_Index[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter] . "<br>";	//for test
						//echo $CoreAbilitiesNo[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter] . "<br>";	//for test
					}
				}

				//���w�֤ߤO���`��
				if($group_id==11) $TotalCoreNumber=11;
				else if($group_id==12) $TotalCoreNumber=8;
				else if($group_id==15) $TotalCoreNumber=12;
                                else if($group_id==16) $TotalCoreNumber=8;

				//���o�U���ҤĿ諸�֤߯�O
				for($i=1; $i<=$ClassTopicNumber; $i++){
					for($j=1; $j<=$TotalCoreNumber; $j++){
						$CoreAbilityTempName = "CoreAbility".$i."_".$j;
						if(isset($$CoreAbilityTempName)) $checkedList[$i][$j] = "checked";
            		}
				}

				//�۰ʨ��o���Ǯ֤߯�O���Q�Ŀ�
				for($i=1; $i<=$TotalCoreNumber; $i++)
					$isChecked[$i]="";
				for($i=1; $i<=$ClassTopicNumber; $i++){
					for($j=1; $j<=$TotalCoreNumber; $j++){
						if($checkedList[$i][$j]=="checked")
							$isChecked[$j]="checked";
					}
				}

				//���NCoreAbilities��CoreAbilitiesReason�ʺA��쪺�Ȧs�b�@�_
				for($ClassGoalNumberCounter = 1; $ClassGoalNumberCounter <= $ClassGoalNumber; $ClassGoalNumberCounter++){
					for($CoreAbilitiesNumberCounter = 1; $CoreAbilitiesNumberCounter <= $CoreAbilitiesNumber[ $ClassGoalNumberCounter]; $CoreAbilitiesNumberCounter++){
						$CoreAbilities_NameTemp = "CoreAbilities" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
						$CoreAbilitiesReason_NameTemp = "CoreAbilitiesReason" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
						$CoreAbilitiesTarget_NameTemp = "CoreAbilitiesTarget" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
						$CoreAbilitiesMethod_NameTemp = "CoreAbilitiesMethod" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;

						if($group_id==11){
							if($isChecked[($ClassGoalNumberCounter-1)*3+$CoreAbilitiesNumberCounter] == "checked"){	$CoreAbilitiesList[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter] = 1;	}
							else									{	$CoreAbilitiesList[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter] = 0;	}
						}
						else if($group_id==12){
							if($isChecked[($ClassGoalNumberCounter-1)*2+$CoreAbilitiesNumberCounter] == "checked"){	$CoreAbilitiesList[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter] = 1;	}
							else									{	$CoreAbilitiesList[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter] = 0;	}
						}
						else if( $group_id==15 ){
                                                        if($isChecked[($ClassGoalNumberCounter-1)*3+$CoreAbilitiesNumberCounter] == "checked")
                                                        {       $CoreAbilitiesList[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter] = 1;   }
                                                        else
                                                        {       $CoreAbilitiesList[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter] = 0;   }
                                                }
                                                else if( $group_id==16){
                                                        if($isChecked[($ClassGoalNumberCounter-1)*2+$CoreAbilitiesNumberCounter] == "checked")
                                                        {       $CoreAbilitiesList[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter] = 1;   }
                                                        else
                                                        {       $CoreAbilitiesList[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter] = 0;   }
                                                } 
						//echo $CoreAbilitiesList[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter] . "<br>";	//for test

						$CoreAbilitiesReasonList[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter] = $$CoreAbilitiesReason_NameTemp;
						$CoreAbilitiesTargetList[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter] = $$CoreAbilitiesTarget_NameTemp;
                                                $CoreAbilitiesMethodList[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter] = $$CoreAbilitiesMethod_NameTemp;
					}
				}


				$FormType = 1;
				//echo "add_intro_form1_ActionType:" . $ActionType . "<br>";	//for test
				if(isset($ActionType) == 0 || $ActionType == 1){
					//isset($ActionType) == 0 
					//�쥻�}��intro.php�������B�z

					//ActionType ==1
					//�B�z�ʺA����TPL_REPLACE_ClassTopicNumberOption and TPL_REPLACE_ClassTopic_Content���ƥ�

					show_page_d ();
				}			
				else if($ActionType == 0){
					//���U"�T�w"���B�z


					//�N��Ƽg�J�ɮ�
					//echo "top:1<br>";	//for test
					if ( ($error = add_intro_form_ClassIntroIndexHtml() ) == -1 ) {
						//echo "top:2<br>";	//for test
						if ( $version == "C" )
							show_page_d ( "�ҵ{���Х[�J����" );
						else
							show_page_d ( "Data insert complete" );
					}
					else{
						//echo "top:3<br>";	//for test
						show_page_d ( $error );
					}
				}
			}
	}
else
show_page_d ();

function add_intro ( $intro ) {
	global $course_id;
	if ( is_file("../../$course_id/intro/index.html") ) {
		$fp = fopen("../../$course_id/intro/index.html", "w");
		//fwrite( $fp, $intro );
		$content = $intro;
		$content = str_replace ( "\\\"", "\"", $content );
		$content = str_replace ( "\\\'", "\'", $content );
		$content = str_replace ( "\\\\", "\\", $content );
		$content = str_replace ( "\\\?", "\?", $content );
		fwrite( $fp, $content );
		fclose($fp);
	}
	else {
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
		$Q1 = "update course set introduction = '$intro' where a_id ='$course_id'";
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "��Ʈw�s�����~!!";
			return $error;
		}
		else if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
			$error = "��Ʈw�g�J���~!!";
			return $error;
		}
		else
			return -1;
	}
}

//
//
//
//
//
function add_intro_form_ClassIntroIndexHtml()
{	
	global $course_id;


	//��ӭ������Ƽg�J�@�Ӻ����ɮ�
	$fp = fopen("../../$course_id/intro/index.html", "w");

	//echo "add_intro_form1:222";	//for test
	$content = createForm(1, 0);
	fwrite( $fp, $content );
	fclose($fp);


	//�N��Ƨ�s���Ʈw
	UpdateDataToSQL();


	return -1;
}

//
//
//
//
//
function Get_a_id($course_id){
	//SQL Server�����
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;

	//�q��Ʈw���oa_id
	$SQL_Select = "SELECT a_id FROM course_no WHERE course_id = '$course_id'";
	if ( !($result = mysql_db_query( $DB, $SQL_Select ) ) ) {
		$message = "function Get_a_id($course_id) ��ƮwŪ�����~!!<br>";
		echo $message;
	}
	$row = mysql_fetch_array( $result );

	return $row['a_id'];
}

//
//
//
//
//
function Get_group_id($a_id){
	//SQL Server�����
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;

	//�q��Ʈw���ogroup_id
	$SQL_Select = "SELECT group_id FROM course WHERE a_id = '$a_id'";
	if ( !($result = mysql_db_query( $DB, $SQL_Select ) ) ) {
		$message = "function Get_group_id($a_id) ��ƮwŪ�����~!!<br>";
		echo $message;
	}
	$row = mysql_fetch_array( $result );

	return $row['group_id'];
}


//function updateToSQL()
//�γ~:�N ���ؤu�{�{�Ү榡 ����Ƨ�s���Ʈw
//
//
//
//
function UpdateDataToSQL(){

	//SQL Server�����
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;

	//�ҵ{���
	global $group_id, $course_id;

	//�ҵ{�W�٪�����
	global $ClassNameChinese, $ClassOpenDepartment, $ClassNameEnglish, $ClassID, $Teacher, $CreditPoints, $Classification, $StudentRange, $ClassPremise, $ClassIntroduction, $TextBook;

	//�ǲߥؼЪ�����
	global $LearningGoalNumber;
	global $LearningGoalList;

	//�ҵ{�j��������
	global $ClassTopicNumber;
	global $ClassTopicList, $ClassTopicContentList, $ClassTopicTeachTimeList, $ClassTopicDemonstrateTimeList, $ClassTopicExerciseTimeList, $ClassTopicOtherTimeList, $ClassTopicRemarkList;

	//�оǭn�I���z������
	global $TeachKeyPointNumber;
	global $TeachKeyPointGradeMannerStart;
	global $TeachKeyPointGradeMannerEnd;
	global $TeachKeyPointGradeList;
	global $TeachKeyPointList;
	global $TeachKeyPoint_ReleatedWork;

	//�ҵ{�ؼлP�Ш|�֤߯�O�����ʪ�����
	global $ClassGoalNumber, $ClassGoal_Index, $ClassGoalNo, $checkedList;
	global $CoreAbilitiesNumber, $CoreAbilities_Index, $CoreAbilitiesNo, $content, $TotalCoreNumber;
	global $CoreAbilitiesList;
	global $CoreAbilitiesReasonList;
	global $CoreAbilitiesTargetList;
        global $CoreAbilitiesMethodList;
	//global $TeachGoal1, $TeachGoal2, $TeachGoal3, $TeachGoal4, $TeachGoal5, $TeachGoal6, $TeachGoal7, $TeachGoal8, $TeachGoal9, $TeachGoal10, $TeachGoal11;
	//global $TeachGoalReason1, $TeachGoalReason2, $TeachGoalReason3, $TeachGoalReason4, $TeachGoalReason5, $TeachGoalReason6, $TeachGoalReason7, $TeachGoalReason8, $TeachGoalReason9, $TeachGoalReason10, $TeachGoalReason11;


	//�s�W��ƨ�IEEE_CourseIntro
	$SQL_Delete = "DELETE FROM IEEE_CourseIntro WHERE group_id LIKE '$group_id' AND course_id LIKE '$course_id'";
	mysql_query( $SQL_Delete) or die( mysql_error());

	$SQL_Insert = 
		"INSERT INTO IEEE_CourseIntro 
		(
		 group_id, 
		 course_id, 
		 ClassNameChinese, 
		 ClassOpenDepartment, 
		 ClassNameEnglish, 
		 ClassID, 
		 Teacher, 
		 CreditPoints, 
		 Classification, 
		 StudentRange, 
		 ClassPremise, 
		 ClassIntroduction, 
		 TextBook, 
		 TeachKeyPoint_ReleatedWork
		) 
		VALUES
		(
		 '$group_id', 
		 '$course_id', 
		 '$ClassNameChinese', 
		 '$ClassOpenDepartment', 
		 '$ClassNameEnglish', 
		 '$ClassID', 
		 '$Teacher', 
		 '$CreditPoints', 
		 '$Classification', 
		 '$StudentRange', 
		 '$ClassPremise', 
		 '$ClassIntroduction', 
		 '$TextBook', 
		 '$TeachKeyPoint_ReleatedWork'
		)";
	mysql_query( $SQL_Insert) or die( mysql_error());

	//�s�W��ƨ�IEEE_CourseIntro_LearningGoal
	$SQL_Delete = "DELETE FROM IEEE_CourseIntro_LearningGoal WHERE group_id LIKE '$group_id' AND course_id LIKE '$course_id'";
	mysql_query( $SQL_Delete) or die( mysql_error());

	for($LearningGoalNumberCounter = 1; $LearningGoalNumberCounter<=$LearningGoalNumber; $LearningGoalNumberCounter++){
		$SQL_Insert = 
			"INSERT INTO IEEE_CourseIntro_LearningGoal 
			(
			 group_id, 
			 course_id, 
			 LearningGoalNo, 
			 LearningGoal
			) 
			VALUES
			(
			 '$group_id', 
			 '$course_id', 
			 '$LearningGoalNumberCounter', 
			 '$LearningGoalList[$LearningGoalNumberCounter]'
			)";
		mysql_query( $SQL_Insert) or die( mysql_error());
	}

	//�s�W��ƨ�IEEE_CourseIntro_ClassTopic
	$SQL_Delete = "DELETE FROM IEEE_CourseIntro_ClassTopic WHERE group_id LIKE '$group_id' AND course_id LIKE '$course_id'";
	mysql_query( $SQL_Delete) or die( mysql_error());

	for($ClassTopicNumberCounter = 1; $ClassTopicNumberCounter<=$ClassTopicNumber; $ClassTopicNumberCounter++){
		$str = "";
		for($i=1; $i<=$TotalCoreNumber; $i++){
			if($checkedList[$ClassTopicNumberCounter][$i]=="checked"){
				if($str != "") $str = $str . "," . $i;
				else $str = $i;
			}
		}

		$SQL_Insert = 
			"INSERT INTO IEEE_CourseIntro_ClassTopic 
			(
			 group_id, 
			 course_id, 
			 ClassTopicNo, 
			 ClassTopic, 
			 ClassTopicContent, 
			 ClassTopicTeachTime, 
			 ClassTopicDemonstrateTime, 
			 ClassTopicExerciseTime, 
			 ClassTopicOtherTime, 
			 ClassTopicRemark,
			 CoreAbilities
			) 
			VALUES
			(
			 '$group_id', 
			 '$course_id', 
			 '$ClassTopicNumberCounter', 
			 '$ClassTopicList[$ClassTopicNumberCounter]', 
			 '$ClassTopicContentList[$ClassTopicNumberCounter]', 
			 '$ClassTopicTeachTimeList[$ClassTopicNumberCounter]', 
			 '$ClassTopicDemonstrateTimeList[$ClassTopicNumberCounter]', 
			 '$ClassTopicExerciseTimeList[$ClassTopicNumberCounter]', 
			 '$ClassTopicOtherTimeList[$ClassTopicNumberCounter]', 
			 '$ClassTopicRemarkList[$ClassTopicNumberCounter]',
			 '$str'
			)";
		mysql_query( $SQL_Insert) or die( mysql_error());
	}


	//�s�W��ƨ�IEEE_CourseIntro_TeachKeyPoint
	$SQL_Delete = "DELETE FROM IEEE_CourseIntro_TeachKeyPoint WHERE group_id LIKE '$group_id' AND course_id LIKE '$course_id'";
	mysql_query( $SQL_Delete) or die( mysql_error());

	for($TeachKeyPointNumberCounter = 1; $TeachKeyPointNumberCounter<=$TeachKeyPointNumber; $TeachKeyPointNumberCounter++){		
		$SQL_Insert = 
			"INSERT INTO IEEE_CourseIntro_TeachKeyPoint 
			(
			 group_id, 
			 course_id, 
			 TeachKeyPointNo, 
			 isChecked, 
			 grade
			) 
			VALUES
			(
			 '$group_id', 
			 '$course_id', 
			 '$TeachKeyPointNumberCounter', 
			 '$TeachKeyPointList[$TeachKeyPointNumberCounter]', 
			 '$TeachKeyPointGradeList[$TeachKeyPointNumberCounter]'
			)";
		mysql_query( $SQL_Insert) or die( mysql_error());
	}


	//�s�W��ƨ�IEEE_CourseIntro_CoreAbilities 
	$SQL_Delete = "DELETE FROM IEEE_CourseIntro_CoreAbilities WHERE group_id LIKE '$group_id' AND course_id LIKE '$course_id'";
	mysql_query( $SQL_Delete) or die( mysql_error());

	for($ClassGoalNumberCounter = 1; $ClassGoalNumberCounter <= $ClassGoalNumber; $ClassGoalNumberCounter++){
		for($CoreAbilitiesNumberCounter = 1; $CoreAbilitiesNumberCounter <= $CoreAbilitiesNumber[ $ClassGoalNumberCounter]; $CoreAbilitiesNumberCounter++){
			//echo "ClassGoal_Index:" . $ClassGoal_Index[$ClassGoalNumberCounter] . "<br>";	//for test
			//echo "CoreAbilities_Index:" . $CoreAbilities_Index[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter] . "<br>";	//for test
			//echo "CoreAbilitiesList:" . $CoreAbilitiesList[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter] . "<br>";	//for test
			//echo "CoreAbilitiesReasonList:" . $CoreAbilitiesReasonList[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter] . "<br>";	//for test


			$CoreAbilities_Index_Temp = $CoreAbilities_Index[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter];
			$CoreAbilitiesList_Temp = $CoreAbilitiesList[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter];
			$CoreAbilitiesReasonList_Temp = $CoreAbilitiesReasonList[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter];
			$CoreAbilitiesTargetList_Temp = $CoreAbilitiesTargetList[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter];
                        $CoreAbilitiesMethodList_Temp = $CoreAbilitiesMethodList[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter];
			$SQL_Insert = 
				"INSERT INTO IEEE_CourseIntro_CoreAbilities 
				(
				 group_id, 
				 course_id, 
				 ClassGoal_Index, 
				 CoreAbilities_Index,
				 isChecked,
				 CoreAbilities_Reason,
				 CoreAbilities_Target,
                                 CoreAbilities_Method

				) 
				VALUES
				(
				 '$group_id', 
				 '$course_id', 
				 '$ClassGoal_Index[$ClassGoalNumberCounter]', 
				 '$CoreAbilities_Index_Temp',
				 '$CoreAbilitiesList_Temp',
				 '$CoreAbilitiesReasonList_Temp',
				 '$CoreAbilitiesTargetList_Temp',
                                 '$CoreAbilitiesMethodList_Temp'
				)";
			mysql_query( $SQL_Insert) or die( mysql_error());
		}
	}

}

//
//
//
//
//
function GetDataFromSQL(){
	//SQL Server�����
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;

	//�ҵ{���
	global $group_id, $course_id;
	//echo "function createForm group_id:" . $group_id . "<br>" . "course_id:" . $course_id . "<br>";	//for test

	//�ҵ{�W�٪�����
	global $ClassNameChinese, $ClassOpenDepartment, $ClassNameEnglish, $ClassID, $Teacher, $CreditPoints, $Classification, $StudentRange, $ClassPremise, $ClassIntroduction, $TextBook;

	//�ǲߥؼЪ�����
	global $LearningGoalNumber;
	global $LearningGoalList;

	//�ҵ{�j��������
	global $ClassTopicNumber;
	global $ClassTopicList, $ClassTopicContentList, $ClassTopicTeachTimeList, $ClassTopicDemonstrateTimeList, $ClassTopicExerciseTimeList, $ClassTopicOtherTimeList, $ClassTopicRemarkList;

	//�оǭn�I���z������
	global $TeachKeyPointNumber;
	global $TeachKeyPointGradeMannerStart;
	global $TeachKeyPointGradeMannerEnd;
	global $TeachKeyPointGradeList;
	global $TeachKeyPointList;
	global $TeachKeyPoint_ReleatedWork;

	//�ҵ{�ؼлP�Ш|�֤߯�O�����ʪ�����
	global $ClassGoalNumber, $ClassGoal_Index, $ClassGoalNo;
	global $CoreAbilitiesNumber, $CoreAbilities_Index, $CoreAbilitiesNo, $content, $TotalCoreNumber, $checkedList;
	global $CoreAbilitiesList;
	global $CoreAbilitiesReasonList;
	global $CoreAbilitiesTargetList;
        global $CoreAbilitiesMethodList;



	//IEEE_CourseIntro������
	$SQL_Select = "SELECT * FROM IEEE_CourseIntro WHERE group_id = '$group_id' AND course_id = '$course_id'";
	if ( !($result = mysql_db_query( $DB, $SQL_Select ) ) ) {
		$message = "function createForm ��ƮwŪ�����~!!<br>";
		echo $message;
	}
	$row = mysql_fetch_array( $result );

	$ClassNameChinese = $row['ClassNameChinese'];
	$ClassOpenDepartment = $row['ClassOpenDepartment'];
	$ClassNameEnglish = $row['ClassNameEnglish'];
	$ClassID = $row['ClassID'];
	$Teacher = $row['Teacher'];
	$CreditPoints = $row['CreditPoints'];
	$Classification = $row['Classification'];
	$StudentRange = $row['StudentRange'];
	$ClassPremise = $row['ClassPremise'];
	$ClassIntroduction = $row['ClassIntroduction'];
	$TextBook = $row['TextBook'];
	$TeachKeyPoint_ReleatedWork = $row['TeachKeyPoint_ReleatedWork'];


	//IEEE_CourseIntro_LearningGoal������
	$SQL_Select = "SELECT * FROM IEEE_CourseIntro_LearningGoal WHERE group_id = '$group_id' AND course_id = '$course_id' ORDER BY LearningGoalNo ASC";
	if ( !($result = mysql_db_query( $DB, $SQL_Select ) ) ) {
		$message = "function createForm '$SQL_Select' ��ƮwŪ�����~!!<br>";
		echo $message;
	}
	$LearningGoalNumber = mysql_num_rows( $result);
	for($LearningGoalNumberCounter = 1; $LearningGoalNumberCounter<=$LearningGoalNumber; $LearningGoalNumberCounter++){
		$row = mysql_fetch_array( $result );
		$LearningGoalList[ $LearningGoalNumberCounter] = $row['LearningGoal'];
		//echo $LearningGoalNumberCounter . ":" . $LearningGoalList[ $LearningGoalNumberCounter];	//for test
	}


	//IEEE_CourseIntro_ClassTopic������
	$SQL_Select = "SELECT * FROM IEEE_CourseIntro_ClassTopic WHERE group_id = '$group_id' AND course_id = '$course_id' ORDER BY ClassTopicNo ASC";
	if ( !($result = mysql_db_query( $DB, $SQL_Select ) ) ) {
		$message = "function createForm '$SQL_Select' ��ƮwŪ�����~!!<br>";
		echo $message;
	}
	$ClassTopicNumber = mysql_num_rows( $result);
	for($ClassTopicNumberCounter = 1; $ClassTopicNumberCounter<=$ClassTopicNumber; $ClassTopicNumberCounter++){
		$row = mysql_fetch_array( $result );
		$ClassTopicList[ $ClassTopicNumberCounter] = $row['ClassTopic'];
		$ClassTopicContentList[ $ClassTopicNumberCounter] = $row['ClassTopicContent'];
		$ClassTopicTeachTimeList[ $ClassTopicNumberCounter] = $row['ClassTopicTeachTime'];
		$ClassTopicDemonstrateTimeList[ $ClassTopicNumberCounter] = $row['ClassTopicDemonstrateTime'];
		$ClassTopicExerciseTimeList[ $ClassTopicNumberCounter] = $row['ClassTopicExerciseTime'];
		$ClassTopicOtherTimeList[ $ClassTopicNumberCounter] = $row['ClassTopicOtherTime'];
		$ClassTopicRemarkList[ $ClassTopicNumberCounter] = $row['ClassTopicRemark'];
		$ClassTopicCoreAbilitiesTemp = split(",",$row['CoreAbilities']);
		for($j=0; $j<count($ClassTopicCoreAbilitiesTemp); $j++){
			$checkedList[$ClassTopicNumberCounter][$ClassTopicCoreAbilitiesTemp[$j]] = "checked";
		}
	}


	//IEEE_CourseIntro_TeachKeyPoint������
	$SQL_Select = "SELECT * FROM IEEE_CourseIntro_TeachKeyPoint WHERE group_id = '$group_id' AND course_id = '$course_id' ORDER BY TeachKeyPointNo ASC";
	if ( !($result = mysql_db_query( $DB, $SQL_Select ) ) ) {
		$message = "function createForm '$SQL_Select' ��ƮwŪ�����~!!<br>";
		echo $message;
	}
	$TeachKeyPointNumber = mysql_num_rows( $result);
	for($TeachKeyPointNumberCounter = 1; $TeachKeyPointNumberCounter<=$TeachKeyPointNumber; $TeachKeyPointNumberCounter++){
		$row = mysql_fetch_array( $result );
		$TeachKeyPointList[ $TeachKeyPointNumberCounter] = $row['isChecked'];

		$TeachKeyPointGradeList[ $TeachKeyPointNumberCounter] = $row['grade'];
	}


	//IEEE_CourseIntro_CoreAbilities������
/*	$SQL_Select = "SELECT A.ClassGoal_Index, A.CoreAbilities_Index, A.isChecked, A.CoreAbilities_Reason 
				   FROM IEEE_CourseIntro_CoreAbilities A, IEET_ClassGoal B, IEET_CoreAbilities C 
				   WHERE A.group_id = '$group_id' AND A.group_id = B.group_id AND A.group_id = C.group_id AND 
					A.course_id = '$course_id' AND A.ClassGoal_Index = B.ClassGoal_Index AND 
					A.ClassGoal_Index = C.ClassGoal_Index AND A.CoreAbilities_Index = C.CoreAbilities_Index 
				   ORDER BY B.ClassGoalNo ASC, C.CoreAbilitiesNo ASC";
*/
	$SQL_Select = "SELECT * FROM IEEE_CourseIntro_CoreAbilities WHERE group_id ='$group_id' and course_id = '$course_id' ORDER BY ClassGoal_Index, CoreAbilities_Index";
	if ( !($result = mysql_db_query( $DB, $SQL_Select ) ) ) {
		$message = "function createForm '$SQL_Select' ��ƮwŪ�����~!!<br>";
		echo $message;
	}
	$CoreAbilitiesTotalNumber = mysql_num_rows( $result);
	//echo $CoreAbilitiesTotalNumber . "<br>";	//for test

	//echo $ClassGoalNumber . "<br>";	//for test
	//echo $CoreAbilitiesNumber[1] . "<br>";	//for test
	$CoreAbilitiesTotalNumberCounter = 1;
	$IsMaped = 1;
	for($ClassGoalNumberCounter = 1; $ClassGoalNumberCounter <= $ClassGoalNumber; $ClassGoalNumberCounter++){
		for($CoreAbilitiesNumberCounter = 1; $CoreAbilitiesNumberCounter <= $CoreAbilitiesNumber[ $ClassGoalNumberCounter]; $CoreAbilitiesNumberCounter++){
			if($IsMaped == 1 && $CoreAbilitiesTotalNumberCounter <= $CoreAbilitiesTotalNumber){
				$row = mysql_fetch_array( $result );
				$IsMaped = 0;
			}

			//echo $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter . ":";	//for test
			//�M��۹��������, ��������~��SQL����Ƶ��L�h
			if($ClassGoal_Index[$ClassGoalNumberCounter] == $row['ClassGoal_Index'] && $CoreAbilities_Index[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter] == $row['CoreAbilities_Index']){
				$CoreAbilitiesList[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter] = $row['isChecked'];
				$CoreAbilitiesReasonList[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter] = $row['CoreAbilities_Reason'];	
				$CoreAbilitiesTargetList[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter] = $row['CoreAbilities_Target'];
                                $CoreAbilitiesMethodList[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter] = $row['CoreAbilities_Method'];
				//echo $CoreAbilitiesList[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter] . "<br>";	//for test
				//echo "1<br>";	//for test
				$IsMaped = 1;
				$CoreAbilitiesTotalNumberCounter++;
			}else{
				//echo "0<br>";	//for test
				$CoreAbilitiesList[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter] = 0;
				$CoreAbilitiesReasonList[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter] = "";
				$CoreAbilitiesTargetList[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter] = "";
                                $CoreAbilitiesMethodList[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter] = "";
			}
		}
	}
}


//function createForm( $ShowType, $disabled)
//�γ~:	���� ���ؤu�{�{�Ү榡 ��form
//$ShowType 0 �@�}�l����J������
//			1 �g�J�ɮת�����
//			2 �g�J�ɮ׫� �n�e�{������
//
//$disabled 0 ���\���ק�
//			1 �����\���ק�
function createForm( $ShowType, $disabled){	

	if( $disabled == 1)	{	$disabled = "disabled";	}
	else				{	$disabled = "";			}

	//SQL Server�����
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;

	//�ҵ{���
	global $group_id, $course_id;
	//echo "function createForm group_id:" . $group_id . "<br>" . "course_id:" . $course_id . "<br>";	//for test

	//�P�O���ؤu�{�{�Ү榡 ��ActionType
	//0 �S��Action�����p
	//1 ���ͰʺA��쪺���p
	global $ActionType;

	//�ҵ{�W�٪�����
	global $ClassNameChinese, $ClassOpenDepartment, $ClassNameEnglish, $ClassID, $Teacher, $CreditPoints, $Classification, $StudentRange, $ClassPremise, $ClassIntroduction, $TextBook;

	//�ǲߥؼЪ�����
	global $LearningGoalNumber;
	global $LearningGoalList;

	//�ҵ{�j��������
	global $ClassTopicNumber;
	global $ClassTopicList, $ClassTopicContentList, $ClassTopicTeachTimeList, $ClassTopicDemonstrateTimeList, $ClassTopicExerciseTimeList, $ClassTopicOtherTimeList, $ClassTopicRemarkList;

	//�оǭn�I���z������
	global $TeachKeyPointNumber;
	global $TeachKeyPointGradeMannerStart;
	global $TeachKeyPointGradeMannerEnd;
	global $TeachKeyPointGradeList;
	global $TeachKeyPointList;
	global $TeachKeyPoint_ReleatedWork;

	//�ҵ{�ؼлP�Ш|�֤߯�O�����ʪ�����
	global $ClassGoalNumber, $ClassGoal_Index, $ClassGoalNo;
	global $CoreAbilitiesNumber, $CoreAbilities_Index, $CoreAbilitiesNo, $content, $TotalCoreNumber, $checkedList, $isChecked;
	global $CoreAbilitiesList;
	global $CoreAbilitiesReasonList;
	global $CoreAbilitiesTargetList;
        global $CoreAbilitiesMethodList;

	//echo "ClassGoalNumber:" . $ClassGoalNumber . "<br>";	//for test
	//echo "ClassGoalNumber:" . $CoreAbilitiesNumber[1] . "<br>";	//for test


	//echo "createForm_Type:".$ShowType."<br>";	//for test
	//echo "createForm_ActionType:".$ActionType."<br>";	//for test
	//echo "createForm_111".$ClassNameChinese;	//for test		

	if($ActionType == 1 || $ShowType == 1 || $ShowType == 2){
		//�B�z�ʺA����TPL_REPLACE_ClassTopicNumberOption and TPL_REPLACE_ClassTopic_Content���ƥ�
		//�����O�d�ϥΪ̤w��J�����
		//
		//�ʺA�B�z��<input type="reset" value="�M��">���\��|����

		//�ҵ{�W�٪�����
		$TPL_REPLACE_ClassNameChineseValue = "value=\"" . $ClassNameChinese . "\"";
		$TPL_REPLACE_ClassOpenDepartmentValue = "value=\"" . $ClassOpenDepartment . "\"";
		$TPL_REPLACE_ClassNameEnglishValue = "value=\"" . $ClassNameEnglish . "\"";
		$TPL_REPLACE_ClassIDValue = "value=\"" . $ClassID . "\"";
		$TPL_REPLACE_TeacherValue = "value=\"" . $Teacher . "\"";
		$TPL_REPLACE_CreditPointsValue = "value=\"" . $CreditPoints . "\"";

		if($Classification == "����"){
			$TPL_REPLACE_ClassificationSelected1 = "selected";
			$TPL_REPLACE_ClassificationSelected2 = "";
		}
		else{
			$TPL_REPLACE_ClassificationSelected1 = "";
			$TPL_REPLACE_ClassificationSelected2 = "selected";
		}

		$TPL_REPLACE_StudentRangeValue = "value=\"" . $StudentRange . "\"";
		$TPL_REPLACE_ClassPremiseValue = $ClassPremise;
		$TPL_REPLACE_ClassIntroductionValue = $ClassIntroduction;
		$TPL_REPLACE_TextBookValue = $TextBook;

		//�оǭn�I���z������
		for($TeachKeyPointNumberCounter = 1; $TeachKeyPointNumberCounter<=$TeachKeyPointNumber; $TeachKeyPointNumberCounter++){
			$TPL_REPLACE_TeachKeyPointCheckedNameTemp = "TPL_REPLACE_TeachKeyPointChecked" . $TeachKeyPointNumberCounter;
			if($TeachKeyPointList[ $TeachKeyPointNumberCounter] == 1){	$$TPL_REPLACE_TeachKeyPointCheckedNameTemp = "checked";	}


			//���o���Z�����t
			$TPL_REPLACE_TeachKeyPointGradeNameTemp = "TPL_REPLACE_TeachKeyPointGrade" . $TeachKeyPointNumberCounter;
			$$TPL_REPLACE_TeachKeyPointGradeNameTemp = $TeachKeyPointGradeList[$TeachKeyPointNumberCounter];
		}
		$TPL_REPLACE_TeachKeyPoint_ReleatedWorkValue = "value=\"" . $TeachKeyPoint_ReleatedWork . "\"";


		//�ҵ{�ؼлP�Ш|�֤߯�O�����ʪ�����
		if($ShowType == 0 || $ShowType == 2){	$TPL_REPLACE_CoreAbilities_Reason_Info = "�п�J����ı�o�����G<br>";	}
		for($ClassGoalNumberCounter = 1; $ClassGoalNumberCounter<=$ClassGoalNumber; $ClassGoalNumberCounter++){
			for($CoreAbilitiesNumberCounter = 1; $CoreAbilitiesNumberCounter<=$CoreAbilitiesNumber[$ClassGoalNumberCounter]; $CoreAbilitiesNumberCounter++){
				if($CoreAbilitiesList[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter] == 1){
					$TPL_REPLACE_CoreAbilities_NameTemp = "TPL_REPLACE_CoreAbilities" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
					$TPL_REPLACE_CoreAbilitiesReason_NameTemp = "TPL_REPLACE_CoreAbilitiesReason" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
					$TPL_REPLACE_CoreAbilitiesTarget_NameTemp = "TPL_REPLACE_CoreAbilitiesTarget" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
                                        $TPL_REPLACE_CoreAbilitiesMethod_NameTemp = "TPL_REPLACE_CoreAbilitiesMethod" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
					$CoreAbilitiesReason_NameTemp = "CoreAbilitiesReason" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
					$CoreAbilitiesTarget_NameTemp = "CoreAbilitiesTarget" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
					$CoreAbilitiesMethod_NameTemp = "CoreAbilitiesMethod" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;

					$$TPL_REPLACE_CoreAbilities_NameTemp = "checked";

					//$ShowType 0 �@�}�l����J������
					//			1 �g�J�ɮת�����
					//			2 �g�J�ɮ׫� �n�e�{������
					if($ShowType == 1)
					{
						$$TPL_REPLACE_CoreAbilitiesReason_NameTemp = 
							//$TPL_REPLACE_CoreAbilities_Reason_Info . $CoreAbilitiesReasonList[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter];
							"�������G<br>" . $CoreAbilitiesReasonList[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter];
						$$TPL_REPLACE_CoreAbilitiesTarget_NameTemp =
							"�F�����СG<br>" . $CoreAbilitiesTargetList[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter];
						$$TPL_REPLACE_CoreAbilitiesMethod_NameTemp =
							"���q��k�G<br>" . $CoreAbilitiesMethodList[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter];
					}
					else
					{
						$$TPL_REPLACE_CoreAbilitiesReason_NameTemp = 
							//$TPL_REPLACE_CoreAbilities_Reason_Info . 
							"�������G<br>".
							"<textarea name=\"" . $CoreAbilitiesReason_NameTemp . "\" id=\"" . $CoreAbilitiesReason_NameTemp . "\" rows=\"3\" cols=\"100\" " . $disabled . ">" . $CoreAbilitiesReasonList[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter] . "</textarea>";
						$$TPL_REPLACE_CoreAbilitiesTarget_NameTemp = "�F�����СG<br>" .
                                                        "<textarea name=\"" . $CoreAbilitiesTarget_NameTemp . "\" id=\"" . $CoreAbilitiesTarget_NameTemp . "\" rows=\"3\" cols=\"100\" " . $disabled . ">" . $CoreAbilitiesTargetList[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter] . "</textarea>";
                                                $$TPL_REPLACE_CoreAbilitiesMethod_NameTemp = "���q��k�G<br>" .
                                                        "<textarea name=\"" . $CoreAbilitiesMethod_NameTemp . "\" id=\"" . $CoreAbilitiesMethod_NameTemp . "\" rows=\"3\" cols=\"100\" " . $disabled . ">" . $CoreAbilitiesMethodList[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter] . "</textarea>";

					}
				}
			}
		}
	}
	else{
		//�쥻�}��intro.php�������B�z

		//�P�O��Ʈw���O�_�w�����
		$SQL_Select = "SELECT * FROM IEEE_CourseIntro WHERE group_id = '$group_id' AND course_id = '$course_id'";
		if ( !($result = mysql_db_query( $DB, $SQL_Select ) ) ) {
			$message = "function createForm ��ƮwŪ�����~!!<br>";
			echo $message;
		}
		$resultNumber = mysql_num_rows( $result);


		if($resultNumber == 0){
			//��Ʈw�|�������

			//�ҵ{�W�٪�����
			$TPL_REPLACE_ClassNameChineseValue = "value=\"" . $ClassNameChinese . "\"";
			$TPL_REPLACE_ClassOpenDepartmentValue = "value=\"" . $ClassOpenDepartment . "\"";
			$TPL_REPLACE_ClassNameEnglishValue = "";
			$TPL_REPLACE_ClassIDValue = "value=\"" . $ClassID . "\"";
			$TPL_REPLACE_TeacherValue = "value=\"" . $Teacher . "\"";
			$TPL_REPLACE_CreditPointsValue = "";

			$TPL_REPLACE_ClassificationSelected1 = "selected";
			$TPL_REPLACE_ClassificationSelected2 = "";

			$TPL_REPLACE_StudentRangeValue = "";
			$TPL_REPLACE_ClassPremiseValue = "";
			$TPL_REPLACE_ClassIntroductionValue = "";
			$TPL_REPLACE_TextBookValue = "";

			//�оǭn�I���z������
			for($TeachKeyPointNumberCounter = 1; $TeachKeyPointNumberCounter<=$TeachKeyPointNumber; $TeachKeyPointNumberCounter++){
				$TPL_REPLACE_TeachKeyPointCheckedNameTemp = "TPL_REPLACE_TeachKeyPointChecked" . $TeachKeyPointNumberCounter;
				$$TPL_REPLACE_TeachKeyPointCheckedNameTemp = "";

				//���Z�����t
				$TPL_REPLACE_TeachKeyPointGradeNameTemp = "TPL_REPLACE_TeachKeyPointGrade" . $TeachKeyPointNumberCounter;
				$$TPL_REPLACE_TeachKeyPointGradeNameTemp = "0";
			}


			//�ҵ{�ؼлP�Ш|�֤߯�O�����ʪ�����
			for($ClassGoalNumberCounter = 1; $ClassGoalNumberCounter<=$ClassGoalNumber; $ClassGoalNumberCounter++){
				for($CoreAbilitiesNumberCounter = 1; $CoreAbilitiesNumberCounter<=$CoreAbilitiesNumber[$ClassGoalNumberCounter]; $CoreAbilitiesNumberCounter++){
					$TPL_REPLACE_CoreAbilities_NameTemp = "TPL_REPLACE_CoreAbilities" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
					$TPL_REPLACE_CoreAbilitiesReason_NameTemp = "TPL_REPLACE_CoreAbilitiesReason" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
					$TPL_REPLACE_CoreAbilitiesTarget_NameTemp = "TPL_REPLACE_CoreAbilitiesTarget" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
                                        $TPL_REPLACE_CoreAbilitiesMethod_NameTemp = "TPL_REPLACE_CoreAbilitiesMethod" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;

					$$TPL_REPLACE_CoreAbilities_NameTemp = "";
					$$TPL_REPLACE_CoreAbilitiesReason_NameTemp = "&nbsp; ";
					$$TPL_REPLACE_CoreAbilitiesTarget_NameTemp = "&nbsp; ";
                                        $$TPL_REPLACE_CoreAbilitiesMethod_NameTemp = "&nbsp; ";
				}
			}

		}else{
			//��Ʈw���w�����

			//�N��Ʊq��Ʈw�����X
			GetDataFromSQL();


			//�ҵ{�W�٪�����
			$TPL_REPLACE_ClassNameChineseValue = "value=\"" . $ClassNameChinese . "\"";
			$TPL_REPLACE_ClassOpenDepartmentValue = "value=\"" . $ClassOpenDepartment . "\"";
			$TPL_REPLACE_ClassNameEnglishValue = "value=\"" . $ClassNameEnglish . "\"";
			$TPL_REPLACE_ClassIDValue = "value=\"" . $ClassID . "\"";
			$TPL_REPLACE_TeacherValue = "value=\"" . $Teacher . "\"";
			$TPL_REPLACE_CreditPointsValue = "value=\"" . $CreditPoints . "\"";

			if($Classification == "����"){
				$TPL_REPLACE_ClassificationSelected1 = "selected";
				$TPL_REPLACE_ClassificationSelected2 = "";
			}
			else{
				$TPL_REPLACE_ClassificationSelected1 = "";
				$TPL_REPLACE_ClassificationSelected2 = "selected";
			}

			$TPL_REPLACE_StudentRangeValue = "value=\"" . $StudentRange . "\"";
			$TPL_REPLACE_ClassPremiseValue = $ClassPremise;
			$TPL_REPLACE_ClassIntroductionValue = $ClassIntroduction;
			$TPL_REPLACE_TextBookValue = $TextBook;

			//�оǭn�I���z������
			for($TeachKeyPointNumberCounter = 1; $TeachKeyPointNumberCounter<=$TeachKeyPointNumber; $TeachKeyPointNumberCounter++){
				$TPL_REPLACE_TeachKeyPointCheckedNameTemp = "TPL_REPLACE_TeachKeyPointChecked" . $TeachKeyPointNumberCounter;
				if($TeachKeyPointList[ $TeachKeyPointNumberCounter] == 1){	$$TPL_REPLACE_TeachKeyPointCheckedNameTemp = "checked";	}

				//���o���Z�����t
				$TPL_REPLACE_TeachKeyPointGradeNameTemp = "TPL_REPLACE_TeachKeyPointGrade" . $TeachKeyPointNumberCounter;
				$$TPL_REPLACE_TeachKeyPointGradeNameTemp = $TeachKeyPointGradeList[$TeachKeyPointNumberCounter];
			}
			$TPL_REPLACE_TeachKeyPoint_ReleatedWorkValue = "value=\"" . $TeachKeyPoint_ReleatedWork . "\"";

			//�ҵ{�ؼлP�Ш|�֤߯�O�����ʪ�����
			if($ShowType == 0 || $ShowType == 2){	$TPL_REPLACE_CoreAbilities_Reason_Info = "�п�J����ı�o�����G<br>";	}
			for($ClassGoalNumberCounter = 1; $ClassGoalNumberCounter<=$ClassGoalNumber; $ClassGoalNumberCounter++){
				for($CoreAbilitiesNumberCounter = 1; $CoreAbilitiesNumberCounter<=$CoreAbilitiesNumber[$ClassGoalNumberCounter]; $CoreAbilitiesNumberCounter++){
					if($CoreAbilitiesList[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter] == 1){
						$TPL_REPLACE_CoreAbilities_NameTemp = "TPL_REPLACE_CoreAbilities" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
						$TPL_REPLACE_CoreAbilitiesReason_NameTemp = "TPL_REPLACE_CoreAbilitiesReason" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
						
						$TPL_REPLACE_CoreAbilitiesTarget_NameTemp = "TPL_REPLACE_CoreAbilitiesTarget" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
                                                $TPL_REPLACE_CoreAbilitiesMethod_NameTemp = "TPL_REPLACE_CoreAbilitiesMethod" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
						$CoreAbilitiesReason_NameTemp = "CoreAbilitiesReason" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
						$CoreAbilitiesTarget_NameTemp = "CoreAbilitiesTarget" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
                                                $CoreAbilitiesMethod_NameTemp = "CoreAbilitiesMethod" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;

						$$TPL_REPLACE_CoreAbilities_NameTemp = "checked";

						//$ShowType 0 �@�}�l����J������
						//			1 �g�J�ɮת�����
						//			2 �g�J�ɮ׫� �n�e�{������
						if($ShowType == 1)
						{
							$$TPL_REPLACE_CoreAbilitiesReason_NameTemp = 
								//$TPL_REPLACE_CoreAbilities_Reason_Info . $CoreAbilitiesReasonList[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter];
								"�������G<br>" . $CoreAbilitiesReasonList[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter];
							$$TPL_REPLACE_CoreAbilitiesTarget_NameTemp =
                                                                 "�F�����СG<br>" . $CoreAbilitiesTargetList[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter];
                                                        $$TPL_REPLACE_CoreAbilitiesMethod_NameTemp =
                                                                 "���q��k�G<br>" . $CoreAbilitiesMethodList[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter];

						}
						else
						{
							$$TPL_REPLACE_CoreAbilitiesReason_NameTemp = 
								//$TPL_REPLACE_CoreAbilities_Reason_Info . 
								"�������G<br>".
								"<textarea name=\"" . $CoreAbilitiesReason_NameTemp . "\" id=\"" . $CoreAbilitiesReason_NameTemp . "\" rows=\"3\" cols=\"100\" " . $disabled . ">" . $CoreAbilitiesReasonList[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter] . "</textarea>";
							$$TPL_REPLACE_CoreAbilitiesTarget_NameTemp = "�F�����СG<br>" .	
                                                                "<textarea name=\"" . $CoreAbilitiesTarget_NameTemp . "\" id=\"" . $CoreAbilitiesTarget_NameTemp . "\" rows=\"3\" cols=\"100\" " . $disabled . ">" . $CoreAbilitiesTargetList[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter] . "</textarea>";				
                                                        $$TPL_REPLACE_CoreAbilitiesMethod_NameTemp = "���q��k�G<br>" .
                                                                "<textarea name=\"" . $CoreAbilitiesMethod_NameTemp . "\" id=\"" . $CoreAbilitiesMethod_NameTemp . "\" rows=\"3\" cols=\"100\" " . $disabled . ">" . $CoreAbilitiesMethodList[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter] . "</textarea>";
						}
					}
				}
			}
		}
	}


	if ( isset($LearningGoalNumber) ) 	{				}
	else							{	$LearningGoalNumber = 5;	}

	//�ʺA����TPL_REPLACE_LearningGoalNumberOption
	$TPL_REPLACE_LearningGoalNumberOption_Content = "";
	for($TPL_REPLACE_LearningGoalNumberOptionCounter = 1; $TPL_REPLACE_LearningGoalNumberOptionCounter<=20; $TPL_REPLACE_LearningGoalNumberOptionCounter++){
		if( $TPL_REPLACE_LearningGoalNumberOptionCounter == $LearningGoalNumber){
			$TPL_REPLACE_LearningGoalNumberOption_Content = 
				$TPL_REPLACE_LearningGoalNumberOption_Content . 
				"<option value=\"" . $TPL_REPLACE_LearningGoalNumberOptionCounter . "\" selected>" . $TPL_REPLACE_LearningGoalNumberOptionCounter . "</option>";
		}
		else{
			$TPL_REPLACE_LearningGoalNumberOption_Content = 
				$TPL_REPLACE_LearningGoalNumberOption_Content . 
				"<option value=\"" . $TPL_REPLACE_LearningGoalNumberOptionCounter . "\">" . $TPL_REPLACE_LearningGoalNumberOptionCounter . "</option>";
		}
	}
	$TPL_REPLACE_LearningGoalNumberOption = $TPL_REPLACE_LearningGoalNumberOption_Content;

	//�ʺA����TPL_REPLACE_LearningGoal_Content������
	$TPL_REPLACE_LearningGoal_Content = "";
	for($LearningGoalNumberCounter = 1; $LearningGoalNumberCounter<=$LearningGoalNumber; $LearningGoalNumberCounter++){
		$LearningGoal_InputName = "LearningGoal" . $LearningGoalNumberCounter;

		$TPL_REPLACE_LearningGoal_Content =	
			$TPL_REPLACE_LearningGoal_Content . 
			$LearningGoalNumberCounter .
			". <input name=\"" . $LearningGoal_InputName . "\" type=\"text\" id=\"" . $LearningGoal_InputName . "\"   size=\"80\" maxlength=\"80\" value=\"" . $LearningGoalList[ $LearningGoalNumberCounter] . "\" " . $disabled . "><br>";
	}

	//����TPL_REPLACE_LearningGoalNumber
	if($ShowType == 0 || $ShowType == 2){
		//���\��J���榡

		$TPL_REPLACE_LearningGoalNumber = 
			"<a name='TPL_REPLACE_LearningGoalNumberLink'>�ǲߥؼмơG</a>
			<select name=\"LearningGoalNumber\" id=\"LearningGoalNumber\" onChange=\"LearningGoalNumberOnChange()\">"
			. $TPL_REPLACE_LearningGoalNumberOption . 
			"</select>";
	}					



	if ( isset($ClassTopicNumber) ) {				}
	else							{	$ClassTopicNumber = 10;	}

	//�ʺA����TPL_REPLACE_ClassTopicNumberOption
	$TPL_REPLACE_ClassTopicNumberOption_Content = "";
	for($TPL_REPLACE_ClassTopicNumberOptionCounter = 1; $TPL_REPLACE_ClassTopicNumberOptionCounter<=30; $TPL_REPLACE_ClassTopicNumberOptionCounter++){
		if( $TPL_REPLACE_ClassTopicNumberOptionCounter == $ClassTopicNumber){
			$TPL_REPLACE_ClassTopicNumberOption_Content = 
				$TPL_REPLACE_ClassTopicNumberOption_Content . 
				"<option value=\"" . $TPL_REPLACE_ClassTopicNumberOptionCounter . "\" selected>" . $TPL_REPLACE_ClassTopicNumberOptionCounter . "</option>";
		}
		else{
			$TPL_REPLACE_ClassTopicNumberOption_Content = 
				$TPL_REPLACE_ClassTopicNumberOption_Content . 
				"<option value=\"" . $TPL_REPLACE_ClassTopicNumberOptionCounter . "\">" . $TPL_REPLACE_ClassTopicNumberOptionCounter . "</option>";
		}
	}
	$TPL_REPLACE_ClassTopicNumberOption = $TPL_REPLACE_ClassTopicNumberOption_Content;


	//�ʺA����TPL_REPLACE_ClassTopic_Content������
	$TPL_REPLACE_ClassTopic_Content = "";
	for($ClassTopicNumberCounter = 1; $ClassTopicNumberCounter<=$ClassTopicNumber; $ClassTopicNumberCounter++){
		//$ShowType 0 �@�}�l����J������
		//			1 �g�J�ɮת�����
		//			2 �g�J�ɮ׫� �n�e�{������
		if($ShowType == 1)
		{
			$TPL_REPLACE_ClassTopic_Content =	
				$TPL_REPLACE_ClassTopic_Content . 
				"<tr bordercolor=\"#000000\">
				<td valign=\"top\">" . str_replace("\n", "<br>", $ClassTopicList[ $ClassTopicNumberCounter]) . "&nbsp;</td>
				<td valign=\"top\">" . str_replace("\n", "<br>", $ClassTopicContentList[ $ClassTopicNumberCounter]) . "&nbsp;</td>
				<td align=\"center\">" . $ClassTopicTeachTimeList[ $ClassTopicNumberCounter] . "&nbsp;</td>
				<td align=\"center\">" . $ClassTopicDemonstrateTimeList[ $ClassTopicNumberCounter] . "&nbsp;</td>
				<td align=\"center\">" . $ClassTopicExerciseTimeList[ $ClassTopicNumberCounter] . "&nbsp;</td>
				<td align=\"center\">" . $ClassTopicOtherTimeList[ $ClassTopicNumberCounter] . "&nbsp;</td>
				<td>&nbsp;";

			for($i=1; $i<=$TotalCoreNumber; $i++){
				if($group_id==11){
					$TPL_REPLACE_ClassTopic_Content =	
						$TPL_REPLACE_ClassTopic_Content .
						"<input type=\"checkbox\" name=\"CoreAbility".$ClassTopicNumberCounter."_".$i."\" value=\"".$i."\" ".$checkedList[$ClassTopicNumberCounter][$i]." disabled>".ceil($i/3).".".((($i-1)%3)+1);
				}
				else if($group_id==12){
					$TPL_REPLACE_ClassTopic_Content =	
						$TPL_REPLACE_ClassTopic_Content .
						"<input type=\"checkbox\" name=\"CoreAbility".$ClassTopicNumberCounter."_".$i."\" value=\"".$i."\" ".$checkedList[$ClassTopicNumberCounter][$i]." disabled>A".$i;
				}
				//
				else if($group_id==15 ){
                                        $TPL_REPLACE_ClassTopic_Content =
                                                $TPL_REPLACE_ClassTopic_Content .
                                                "<input type=\"checkbox\" name=\"CoreAbility".$ClassTopicNumberCounter."_".$i."\" value=\"".$i."\" ".$checkedList[$ClassTopicNumberCounter][$i]." disabled>B".$i;
                                }
                                else if($group_id==16 ){
                                        $TPL_REPLACE_ClassTopic_Content =
                                                $TPL_REPLACE_ClassTopic_Content .
                                                "<input type=\"checkbox\" name=\"CoreAbility".$ClassTopicNumberCounter."_".$i."\" value=\"".$i."\" ".$checkedList[$ClassTopicNumberCounter][$i]." disabled>D".$i;
                                }

				//
			}
			$TPL_REPLACE_ClassTopic_Content =
				$TPL_REPLACE_ClassTopic_Content .
				"</td>
				<td valign=\"top\">" . str_replace("\n", "<br>", $ClassTopicRemarkList[ $ClassTopicNumberCounter]) . "&nbsp;</td>
				</tr>
				";
		}
		else
		{					
			$TPL_REPLACE_ClassTopic_Content =	
				$TPL_REPLACE_ClassTopic_Content . 
				"<tr bordercolor=\"#000000\">
				<td><textarea name=\"ClassTopic" . $ClassTopicNumberCounter . "\" rows=\"3\" cols=\"20\" " . $disabled . ">" . $ClassTopicList[ $ClassTopicNumberCounter] . "</textarea>
				</td>
				<td><textarea name=\"ClassTopicContent" . $ClassTopicNumberCounter . "\" rows=\"3\" cols=\"40\" " . $disabled . ">" . $ClassTopicContentList[ $ClassTopicNumberCounter] . "</textarea>
				</td>
				<td align=\"center\"><input type=\"text\" name=\"ClassTopicTeachTime" . $ClassTopicNumberCounter . "\" size=\"3\" maxlength=\"5\" value=\"" . $ClassTopicTeachTimeList[ $ClassTopicNumberCounter] . "\" " . $disabled . ">
				</td>
				<td align=\"center\"><input type=\"text\" name=\"ClassTopicDemonstrateTime" . $ClassTopicNumberCounter . "\" size=\"3\" maxlength=\"5\" value=\"" . $ClassTopicDemonstrateTimeList[ $ClassTopicNumberCounter] . "\" " . $disabled . ">
				</td>
				<td align=\"center\"><input type=\"text\" name=\"ClassTopicExerciseTime" . $ClassTopicNumberCounter . "\" size=\"3\" maxlength=\"5\" value=\"" . $ClassTopicExerciseTimeList[ $ClassTopicNumberCounter] . "\" " . $disabled . ">
				</td>
				<td align=\"center\"><input type=\"text\" name=\"ClassTopicOtherTime" . $ClassTopicNumberCounter . "\" size=\"3\" maxlength=\"5\" value=\"" . $ClassTopicOtherTimeList[ $ClassTopicNumberCounter] . "\" " . $disabled . ">
				</td><td>";
			for($i=1; $i<=$TotalCoreNumber; $i++){
				if($group_id==11){
					$TPL_REPLACE_ClassTopic_Content =	
						$TPL_REPLACE_ClassTopic_Content .
						"<input type=\"checkbox\" name=\"CoreAbility".$ClassTopicNumberCounter."_".$i."\" value=\"".$i."\" ".$checkedList[$ClassTopicNumberCounter][$i]." >".ceil($i/3).".".((($i-1)%3)+1);
				}
				else if($group_id==12){
					$TPL_REPLACE_ClassTopic_Content =	
						$TPL_REPLACE_ClassTopic_Content .
						"<input type=\"checkbox\" name=\"CoreAbility".$ClassTopicNumberCounter."_".$i."\" value=\"".$i."\" ".$checkedList[$ClassTopicNumberCounter][$i]." >A".$i;
				}
				//
				else if($group_id==15 ){
                                        $TPL_REPLACE_ClassTopic_Content =
                                                $TPL_REPLACE_ClassTopic_Content .
                                                "<input type=\"checkbox\" name=\"CoreAbility".$ClassTopicNumberCounter."_".$i."\" value=\"".$i."\" ".$checkedList[$ClassTopicNumberCounter][$i]." >B".$i;
                                }
                                else if($group_id==16){
                                        $TPL_REPLACE_ClassTopic_Content =
                                              $TPL_REPLACE_ClassTopic_Content .
                                              "<input type=\"checkbox\" name=\"CoreAbility".$ClassTopicNumberCounter."_".$i."\" value=\"".$i."\" ".$checkedList[$ClassTopicNumberCounter][$i]." >D".$i;
                                }
				//
			}
			$TPL_REPLACE_ClassTopic_Content =	
				$TPL_REPLACE_ClassTopic_Content .
				"</td><td><textarea name=\"ClassTopicRemark" . $ClassTopicNumberCounter . "\" rows=\"3\" cols=\"20\" " . $disabled . ">" . $ClassTopicRemarkList[ $ClassTopicNumberCounter] . "</textarea>
				</td>
				</tr>
				";
		}
	}
	//����TPL_REPLACE_ClassTopicNumber
	if($ShowType == 0 || $ShowType == 2){
		//���\��J���榡

		$TPL_REPLACE_ClassTopicNumber = 
			"<a name='TPL_REPLACE_ClassTopicNumberLink'>�ҵ{�j�� - �椸�D�D�ơG</a>
			<select name=\"ClassTopicNumber\" id=\"ClassTopicNumber\" onChange=\"ClassTopicNumberOnChange()\">"
			. $TPL_REPLACE_ClassTopicNumberOption . 
			"</select>";
	}


	//����TPL_REPLACE_TeachKeyPointClassificationContent
	$TPL_REPLACE_TeachKeyPointClassificationContent1 =
		"<input type=\"checkbox\" name=\"TeachKeyPoint1\" value=\"1\" " . $TPL_REPLACE_TeachKeyPointChecked1 . " " . $disabled . ">�۽s�Ч�
		<input type=\"checkbox\" name=\"TeachKeyPoint2\" value=\"1\" " . $TPL_REPLACE_TeachKeyPointChecked2 . " " . $disabled . ">�Ь�ѧ@�̴���";

	$TPL_REPLACE_TeachKeyPointClassificationContent2 =
		"<input type=\"checkbox\" name=\"TeachKeyPoint3\" value=\"1\" " . $TPL_REPLACE_TeachKeyPointChecked3 . " " . $disabled . ">��v�����z
		<input type=\"checkbox\" name=\"TeachKeyPoint4\" value=\"1\" " . $TPL_REPLACE_TeachKeyPointChecked4 . " " . $disabled . ">�O�����z";

	//$ShowType 0 �@�}�l����J������
	//			1 �g�J�ɮת�����
	//			2 �g�J�ɮ׫� �n�e�{������
	if($ShowType == 1)
	{
		$TPL_REPLACE_TeachKeyPointClassificationContent3 =
			"<input type=\"checkbox\" name=\"TeachKeyPoint5\" value=\"1\" " . $TPL_REPLACE_TeachKeyPointChecked5 . " " . $disabled . ">�W���I�W 
			" . $TPL_REPLACE_TeachKeyPointGrade5 . "%, 
			<input type=\"checkbox\" name=\"TeachKeyPoint6\" value=\"1\" " . $TPL_REPLACE_TeachKeyPointChecked6 . " " . $disabled . ">�p��
				" . $TPL_REPLACE_TeachKeyPointGrade6 . "%, 
			<input type=\"checkbox\" name=\"TeachKeyPoint7\" value=\"1\" " . $TPL_REPLACE_TeachKeyPointChecked7 . " " . $disabled . ">�@�~
				" . $TPL_REPLACE_TeachKeyPointGrade7 . "%, 
			<input type=\"checkbox\" name=\"TeachKeyPoint8\" value=\"1\" " . $TPL_REPLACE_TeachKeyPointChecked8 . " " . $disabled . ">�{����@
				" . $TPL_REPLACE_TeachKeyPointGrade8 . "%, 
			<input type=\"checkbox\" name=\"TeachKeyPoint9\" value=\"1\" " . $TPL_REPLACE_TeachKeyPointChecked9 . " " . $disabled . ">��߳��i
				" . $TPL_REPLACE_TeachKeyPointGrade9 . "%, 
			<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type=\"checkbox\" name=\"TeachKeyPoint10\" value=\"1\" " . $TPL_REPLACE_TeachKeyPointChecked10 . " " . $disabled . ">�M��
			" . $TPL_REPLACE_TeachKeyPointGrade10 . "%, 
			<input type=\"checkbox\" name=\"TeachKeyPoint11\" value=\"1\" " . $TPL_REPLACE_TeachKeyPointChecked11 . " " . $disabled . ">������
				" . $TPL_REPLACE_TeachKeyPointGrade11 . "%, 
			<input type=\"checkbox\" name=\"TeachKeyPoint12\" value=\"1\" " . $TPL_REPLACE_TeachKeyPointChecked12 . " " . $disabled . ">������
				" . $TPL_REPLACE_TeachKeyPointGrade12 . "%, 
			<input type=\"checkbox\" name=\"TeachKeyPoint13\" value=\"1\" " . $TPL_REPLACE_TeachKeyPointChecked13 . " " . $disabled . ">�������i
				" . $TPL_REPLACE_TeachKeyPointGrade13 . "%, 
			<input type=\"checkbox\" name=\"TeachKeyPoint14\" value=\"1\" " . $TPL_REPLACE_TeachKeyPointChecked14 . " " . $disabled . ">�䥦
				" . $TPL_REPLACE_TeachKeyPointGrade14 . "% ";

		$TPL_REPLACE_TeachKeyPoint_ReleatedWork = $TeachKeyPoint_ReleatedWork;
	}
	else
	{
		$TPL_REPLACE_TeachKeyPointClassificationContent3 =
			"<input type=\"checkbox\" name=\"TeachKeyPoint5\" value=\"1\" " . $TPL_REPLACE_TeachKeyPointChecked5 . " " . $disabled . ">�W���I�W 
			<input type=\"text\" name=\"TeachKeyPointGrade5\" size=\"3\" maxlength=\"3\" value=\"" . $TPL_REPLACE_TeachKeyPointGrade5 . "\" " . $disabled . ">%, 
			<input type=\"checkbox\" name=\"TeachKeyPoint6\" value=\"1\" " . $TPL_REPLACE_TeachKeyPointChecked6 . " " . $disabled . ">�p��
			<input type=\"text\" name=\"TeachKeyPointGrade6\" size=\"3\" maxlength=\"3\" value=\"" . $TPL_REPLACE_TeachKeyPointGrade6 . "\" " . $disabled . ">%, 
			<input type=\"checkbox\" name=\"TeachKeyPoint7\" value=\"1\" " . $TPL_REPLACE_TeachKeyPointChecked7 . " " . $disabled . ">�@�~
			<input type=\"text\" name=\"TeachKeyPointGrade7\" size=\"3\" maxlength=\"3\" value=\"" . $TPL_REPLACE_TeachKeyPointGrade7 . "\" " . $disabled . ">%, 
			<input type=\"checkbox\" name=\"TeachKeyPoint8\" value=\"1\" " . $TPL_REPLACE_TeachKeyPointChecked8 . " " . $disabled . ">�{����@
			<input type=\"text\" name=\"TeachKeyPointGrade8\" size=\"3\" maxlength=\"3\" value=\"" . $TPL_REPLACE_TeachKeyPointGrade8 . "\" " . $disabled . ">%, 
			<input type=\"checkbox\" name=\"TeachKeyPoint9\" value=\"1\" " . $TPL_REPLACE_TeachKeyPointChecked9 . " " . $disabled . ">��߳��i
			<input type=\"text\" name=\"TeachKeyPointGrade9\" size=\"3\" maxlength=\"3\" value=\"" . $TPL_REPLACE_TeachKeyPointGrade9 . "\" " . $disabled . ">%, 
			<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type=\"checkbox\" name=\"TeachKeyPoint10\" value=\"1\" " . $TPL_REPLACE_TeachKeyPointChecked10 . " " . $disabled . ">�M��
			<input type=\"text\" name=\"TeachKeyPointGrade10\" size=\"3\" maxlength=\"3\" value=\"" . $TPL_REPLACE_TeachKeyPointGrade10 . "\" " . $disabled . ">%, 
			<input type=\"checkbox\" name=\"TeachKeyPoint11\" value=\"1\" " . $TPL_REPLACE_TeachKeyPointChecked11 . " " . $disabled . ">������
			<input type=\"text\" name=\"TeachKeyPointGrade11\" size=\"3\" maxlength=\"3\" value=\"" . $TPL_REPLACE_TeachKeyPointGrade11 . "\" " . $disabled . ">%, 
			<input type=\"checkbox\" name=\"TeachKeyPoint12\" value=\"1\" " . $TPL_REPLACE_TeachKeyPointChecked12 . " " . $disabled . ">������
			<input type=\"text\" name=\"TeachKeyPointGrade12\" size=\"3\" maxlength=\"3\" value=\"" . $TPL_REPLACE_TeachKeyPointGrade12 . "\" " . $disabled . ">%, 
			<input type=\"checkbox\" name=\"TeachKeyPoint13\" value=\"1\" " . $TPL_REPLACE_TeachKeyPointChecked13 . " " . $disabled . ">�������i
			<input type=\"text\" name=\"TeachKeyPointGrade13\" size=\"3\" maxlength=\"3\" value=\"" . $TPL_REPLACE_TeachKeyPointGrade13 . "\" " . $disabled . ">%, 
			<input type=\"checkbox\" name=\"TeachKeyPoint14\" value=\"1\" " . $TPL_REPLACE_TeachKeyPointChecked14 . " " . $disabled . ">�䥦
			<input type=\"text\" name=\"TeachKeyPointGrade14\" size=\"3\" maxlength=\"3\" value=\"" . $TPL_REPLACE_TeachKeyPointGrade14 . "\" " . $disabled . ">% ";

		$TPL_REPLACE_TeachKeyPoint_ReleatedWork = "<input name=\"TeachKeyPoint_ReleatedWork\" type=\"text\" id=\"TeachKeyPoint_ReleatedWork\" size=\"85\" maxlength=\"100\" " . $TPL_REPLACE_TeachKeyPoint_ReleatedWorkValue . " " . $disabled . ">";
	}

	$TPL_REPLACE_TeachKeyPointClassificationContent4 =
		"<input type=\"checkbox\" name=\"TeachKeyPoint15\" value=\"1\" " . $TPL_REPLACE_TeachKeyPointChecked15 . " " . $disabled . ">�ҵ{����  
		<input type=\"checkbox\" name=\"TeachKeyPoint16\" value=\"1\" " . $TPL_REPLACE_TeachKeyPointChecked16 . " " . $disabled . ">�Ч��q�l�ɨѤU��  
		<input type=\"checkbox\" name=\"TeachKeyPoint17\" value=\"1\" " . $TPL_REPLACE_TeachKeyPointChecked17 . " " . $disabled . ">��ߺ��� ";



	//����TPL_REPLACE_CoreAbilities_JavaScript_OnChick
	$TPL_REPLACE_CoreAbilities_JavaScript_OnChick = "";
	for($ClassGoalNumberCounter = 1; $ClassGoalNumberCounter<=$ClassGoalNumber; $ClassGoalNumberCounter++){
		for($CoreAbilitiesNumberCounter = 1; $CoreAbilitiesNumberCounter<=$CoreAbilitiesNumber[$ClassGoalNumberCounter]; $CoreAbilitiesNumberCounter++){
			$CoreAbilitiesOnClick_NameTemp = "CoreAbilitiesOnClick" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
			$CoreAbilities_NameTemp = "CoreAbilities" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
			$TPL_REPLACE_CoreAbilitiesReason_NameTemp = "TPL_REPLACE_CoreAbilitiesReason" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
			$TPL_REPLACE_CoreAbilitiesTarget_NameTemp = "TPL_REPLACE_CoreAbilitiesTarget" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
                        $TPL_REPLACE_CoreAbilitiesMethod_NameTemp = "TPL_REPLACE_CoreAbilitiesMethod" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
			
			$CoreAbilitiesReason_NameTemp = "CoreAbilitiesReason" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
			$CoreAbilitiesTarget_NameTemp = "CoreAbilitiesTarget" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
                        $CoreAbilitiesMethod_NameTemp = "CoreAbilitiesMethod" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
			
			$JavaScript_REPLACE_CoreAbilitiesReason_NameTemp = "JavaScript_REPLACE_CoreAbilitiesReason" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
			
			$JavaScript_REPLACE_CoreAbilitiesTarget_NameTemp = "JavaScript_REPLACE_CoreAbilitiesTarget" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
                        $JavaScript_REPLACE_CoreAbilitiesMethod_NameTemp = "JavaScript_REPLACE_CoreAbilitiesMethod" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
			$TPL_REPLACE_CoreAbilities_JavaScript_OnChick = 
				$TPL_REPLACE_CoreAbilities_JavaScript_OnChick . 
				"function " . $CoreAbilitiesOnClick_NameTemp . "(){
				if(ClassIntroIndexHtml." . $CoreAbilities_NameTemp . ".checked == 1){
					var " . $TPL_REPLACE_CoreAbilitiesReason_NameTemp . " = '';
					if(ClassIntroIndexHtml.ShowType.value == 0 || ClassIntroIndexHtml.ShowType.value == 2){	" . $TPL_REPLACE_CoreAbilitiesReason_NameTemp . " = '�п�J����ı�o�����G<br>';	}
					" . $TPL_REPLACE_CoreAbilitiesReason_NameTemp . " = 
						" . $TPL_REPLACE_CoreAbilitiesReason_NameTemp . " +  
						'<textarea name=\"" . $CoreAbilitiesReason_NameTemp . "\" id=\"" . $CoreAbilitiesReason_NameTemp . "\" rows=\"5\" cols=\"100\"></textarea>';

					document.all(\"" . $JavaScript_REPLACE_CoreAbilitiesReason_NameTemp . "\").innerHTML = " .$TPL_REPLACE_CoreAbilitiesReason_NameTemp . ";
				}else{
					document.all(\"" . $JavaScript_REPLACE_CoreAbilitiesReason_NameTemp . "\").innerHTML = ' &nbsp; ';
				}
		}";
		}
	}

	//�۰ʨ��o���Ǯ֤߯�O���Q�Ŀ�
	for($i=1; $i<=$TotalCoreNumber; $i++)
		$isChecked[$i]="";
	for($i=1; $i<=$ClassTopicNumber; $i++){
		for($j=1; $j<=$TotalCoreNumber; $j++){
			if($checkedList[$i][$j]=="checked")
				$isChecked[$j]="checked";
		}
	}

	//����TPL_REPLACE_CoreAbilities_TR
	$TPL_REPLACE_CoreAbilities_TR =
		"<tr bordercolor=\"#000000\">
		<td colspan=\"2\">
		�ФĿ�G";

	if($group_id==11){
		for($ClassGoalNumberCounter = 1; $ClassGoalNumberCounter<=$ClassGoalNumber; $ClassGoalNumberCounter++){
			for($CoreAbilitiesNumberCounter = 1; $CoreAbilitiesNumberCounter<=$CoreAbilitiesNumber[$ClassGoalNumberCounter]; $CoreAbilitiesNumberCounter++){
				$CoreAbilities_NameTemp = "CoreAbilities" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
				$CoreAbilitiesOnClick_NameTemp = "CoreAbilitiesOnClick" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
				$TPL_REPLACE_CoreAbilities_NameTemp = "TPL_REPLACE_CoreAbilities" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;


				$TPL_REPLACE_CoreAbilities_TR =
					$TPL_REPLACE_CoreAbilities_TR . 
					"<input name=\"" . $CoreAbilities_NameTemp . "\" type=\"checkbox\" value=\"1\" onClick=\"" . $CoreAbilitiesOnClick_NameTemp . "()\" " .  $$TPL_REPLACE_CoreAbilities_NameTemp . " ". $isChecked[(($ClassGoalNumberCounter-1)*3+$CoreAbilitiesNumberCounter)] . " disabled>"
					. $ClassGoalNo[$ClassGoalNumberCounter] . "." . $CoreAbilitiesNo[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter];
			}
		}
	}
	else if($group_id==12){
		for($ClassGoalNumberCounter = 1; $ClassGoalNumberCounter<=$ClassGoalNumber; $ClassGoalNumberCounter++){
			for($CoreAbilitiesNumberCounter = 1; $CoreAbilitiesNumberCounter<=$CoreAbilitiesNumber[$ClassGoalNumberCounter]; $CoreAbilitiesNumberCounter++){
				$CoreAbilities_NameTemp = "CoreAbilities" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
				$CoreAbilitiesOnClick_NameTemp = "CoreAbilitiesOnClick" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
				$TPL_REPLACE_CoreAbilities_NameTemp = "TPL_REPLACE_CoreAbilities" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;


				$TPL_REPLACE_CoreAbilities_TR =
					$TPL_REPLACE_CoreAbilities_TR . 
					"<input name=\"" . $CoreAbilities_NameTemp . "\" type=\"checkbox\" value=\"1\" onClick=\"" . $CoreAbilitiesOnClick_NameTemp . "()\" " .  $$TPL_REPLACE_CoreAbilities_NameTemp . " ". $isChecked[$CoreAbilitiesNo[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter]] . " disabled>"
					. "A" . $CoreAbilitiesNo[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter];
			}
		}
	}
	//
	else if($group_id==15){
                for($ClassGoalNumberCounter = 1; $ClassGoalNumberCounter<=$ClassGoalNumber; $ClassGoalNumberCounter++){
                        for($CoreAbilitiesNumberCounter = 1; $CoreAbilitiesNumberCounter<=$CoreAbilitiesNumber[$ClassGoalNumberCounter]; $CoreAbilitiesNumberCounter++){
                                $CoreAbilities_NameTemp = "CoreAbilities" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
                                $CoreAbilitiesOnClick_NameTemp = "CoreAbilitiesOnClick" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
                                $TPL_REPLACE_CoreAbilities_NameTemp = "TPL_REPLACE_CoreAbilities" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
                                $TPL_REPLACE_CoreAbilities_TR =
                                        $TPL_REPLACE_CoreAbilities_TR .
                                        "<input name=\"" . $CoreAbilities_NameTemp . "\" type=\"checkbox\" value=\"1\" onClick=\"" . $CoreAbilitiesOnClick_NameTemp . "()\" " .  $$TPL_REPLACE_CoreAbilities_NameTemp . " ". $isChecked[$CoreAbilitiesNo[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter]] . " disabled>"
                                        . "B" . $CoreAbilitiesNo[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter];
                        }
                }
        }
        else if($group_id==16){
                for($ClassGoalNumberCounter = 1; $ClassGoalNumberCounter<=$ClassGoalNumber; $ClassGoalNumberCounter++){
                        for($CoreAbilitiesNumberCounter = 1; $CoreAbilitiesNumberCounter<=$CoreAbilitiesNumber[$ClassGoalNumberCounter]; $CoreAbilitiesNumberCounter++){
                                $CoreAbilities_NameTemp = "CoreAbilities" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
                                $CoreAbilitiesOnClick_NameTemp = "CoreAbilitiesOnClick" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
                                $TPL_REPLACE_CoreAbilities_NameTemp = "TPL_REPLACE_CoreAbilities" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
                                $TPL_REPLACE_CoreAbilities_TR =
                                        $TPL_REPLACE_CoreAbilities_TR .
                                        "<input name=\"" . $CoreAbilities_NameTemp . "\" type=\"checkbox\" value=\"1\" onClick=\"" . $CoreAbilitiesOnClick_NameTemp . "()\" " .  $$TPL_REPLACE_CoreAbilities_NameTemp . " ". $isChecked[$CoreAbilitiesNo[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter]] . " disabled>"
                                        . "D" . $CoreAbilitiesNo[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter];
                        }
                }
        }

	//

	$TPL_REPLACE_CoreAbilities_TR = 
		$TPL_REPLACE_CoreAbilities_TR . 			
		"</td>
		</tr>";


	//���ͨC�@��TPL_REPLACE_CoreAbilitiesReason_TR
	if($group_id==11){
		for($ClassGoalNumberCounter = 1; $ClassGoalNumberCounter<=$ClassGoalNumber; $ClassGoalNumberCounter++){
			for($CoreAbilitiesNumberCounter = 1; $CoreAbilitiesNumberCounter<=$CoreAbilitiesNumber[$ClassGoalNumberCounter]; $CoreAbilitiesNumberCounter++){
				$TPL_REPLACE_CoreAbilitiesReason_TR_NameTemp = "TPL_REPLACE_CoreAbilitiesReason_TR" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
				$JavaScript_REPLACE_CoreAbilitiesReason_NameTemp = "JavaScript_REPLACE_CoreAbilitiesReason" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
				$JavaScript_REPLACE_CoreAbilitiesTarget_NameTemp = "JavaScript_REPLACE_CoreAbilitiesTarget" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
                                $JavaScript_REPLACE_CoreAbilitiesMethod_NameTemp = "JavaScript_REPLACE_CoreAbilitiesMethod" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
				$TPL_REPLACE_CoreAbilitiesReason_NameTemp = "TPL_REPLACE_CoreAbilitiesReason" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
				$TPL_REPLACE_CoreAbilitiesTarget_NameTemp = "TPL_REPLACE_CoreAbilitiesTarget" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
                                $TPL_REPLACE_CoreAbilitiesMethod_NameTemp = "TPL_REPLACE_CoreAbilitiesMethod" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;

				if($ShowType == 0 || $ShowType == 2 || ($ShowType == 1 && $CoreAbilitiesList[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter] == 1))
				{
					$$TPL_REPLACE_CoreAbilitiesReason_TR_NameTemp = 
						"<tr bordercolor=\"#000000\">
						<td width=\"80\" rowspan=\"4\" align=\"center\">" . $ClassGoalNo[$ClassGoalNumberCounter] . "." . $CoreAbilitiesNo[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter] . "</td>
						<td> <strong>" . $content[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter] . "</strong> </td>
						</tr>
						<tr bordercolor=\"#000000\">
						<td id=\"" . $JavaScript_REPLACE_CoreAbilitiesReason_NameTemp . "\">"
						. $$TPL_REPLACE_CoreAbilitiesReason_NameTemp . 
						"</td>
						
						<tr bordercolor=\"#000000\">
                                                <td id=\"" . $JavaScript_REPLACE_CoreAbilitiesTarget_NameTemp . "\">  "
                                                . $$TPL_REPLACE_CoreAbilitiesTarget_NameTemp .
                                                "</td>
                                                <tr bordercolor=\"#000000\">
                                                <td id=\"" . $JavaScript_REPLACE_CoreAbilitiesMethod_NameTemp . "\"> "
                                                . $$TPL_REPLACE_CoreAbilitiesMethod_NameTemp .
                                                "</td>
						</tr>";
				}
			}
		}
	}
	else if($group_id==12){
		for($ClassGoalNumberCounter = 1; $ClassGoalNumberCounter<=$ClassGoalNumber; $ClassGoalNumberCounter++){
			for($CoreAbilitiesNumberCounter = 1; $CoreAbilitiesNumberCounter<=$CoreAbilitiesNumber[$ClassGoalNumberCounter]; $CoreAbilitiesNumberCounter++){
				$TPL_REPLACE_CoreAbilitiesReason_TR_NameTemp = "TPL_REPLACE_CoreAbilitiesReason_TR" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
				$JavaScript_REPLACE_CoreAbilitiesReason_NameTemp = "JavaScript_REPLACE_CoreAbilitiesReason" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
				$JavaScript_REPLACE_CoreAbilitiesTarget_NameTemp = "JavaScript_REPLACE_CoreAbilitiesTarget" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
                                $JavaScript_REPLACE_CoreAbilitiesMethod_NameTemp = "JavaScript_REPLACE_CoreAbilitiesMethod" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
				$TPL_REPLACE_CoreAbilitiesReason_NameTemp = "TPL_REPLACE_CoreAbilitiesReason" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
				$TPL_REPLACE_CoreAbilitiesTarget_NameTemp = "TPL_REPLACE_CoreAbilitiesTarget" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
                                $TPL_REPLACE_CoreAbilitiesMethod_NameTemp = "TPL_REPLACE_CoreAbilitiesMethod" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;

				if($ShowType == 0 || $ShowType == 2 || ($ShowType == 1 && $CoreAbilitiesList[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter] == 1))
				{
					$$TPL_REPLACE_CoreAbilitiesReason_TR_NameTemp = 
						"<tr bordercolor=\"#000000\">
						<td width=\"80\" rowspan=\"4\" align=\"center\">" . "A" . $CoreAbilitiesNo[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter] . "</td>
						<td> <strong>" . $content[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter] . "</strong> </td>
						</tr>
						<tr bordercolor=\"#000000\">
						<td id=\"" . $JavaScript_REPLACE_CoreAbilitiesReason_NameTemp . "\">"
						. $$TPL_REPLACE_CoreAbilitiesReason_NameTemp . 
						"</td>
						<tr bordercolor=\"#000000\">
                                                <td id=\"" . $JavaScript_REPLACE_CoreAbilitiesTarget_NameTemp . "\">"
                                                . $$TPL_REPLACE_CoreAbilitiesTarget_NameTemp .
                                                "</td>
                                                <tr bordercolor=\"#000000\">
                                                <td id=\"" . $JavaScript_REPLACE_CoreAbilitiesMethod_NameTemp . "\">"
                                                . $$TPL_REPLACE_CoreAbilitiesMethod_NameTemp .
                                                "</td>
						</tr>";
				}
			}
		}
	}
	//
	else if($group_id==15 ){
                for($ClassGoalNumberCounter = 1; $ClassGoalNumberCounter<=$ClassGoalNumber; $ClassGoalNumberCounter++){
                        for($CoreAbilitiesNumberCounter = 1; $CoreAbilitiesNumberCounter<=$CoreAbilitiesNumber[$ClassGoalNumberCounter]; $CoreAbilitiesNumberCounter++){
                                $TPL_REPLACE_CoreAbilitiesReason_TR_NameTemp = "TPL_REPLACE_CoreAbilitiesReason_TR" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
                                $JavaScript_REPLACE_CoreAbilitiesReason_NameTemp = "JavaScript_REPLACE_CoreAbilitiesReason" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
                                $JavaScript_REPLACE_CoreAbilitiesTarget_NameTemp = "JavaScript_REPLACE_CoreAbilitiesTarget" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
                                $JavaScript_REPLACE_CoreAbilitiesMethod_NameTemp = "JavaScript_REPLACE_CoreAbilitiesMethod" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;

                                $TPL_REPLACE_CoreAbilitiesReason_NameTemp = "TPL_REPLACE_CoreAbilitiesReason" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
                                $TPL_REPLACE_CoreAbilitiesTarget_NameTemp = "TPL_REPLACE_CoreAbilitiesTarget" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
                                $TPL_REPLACE_CoreAbilitiesMethod_NameTemp = "TPL_REPLACE_CoreAbilitiesMethod" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;

                                if($ShowType == 0 || $ShowType == 2 || ($ShowType == 1 && $CoreAbilitiesList[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter] == 1))
                                {
                                        $$TPL_REPLACE_CoreAbilitiesReason_TR_NameTemp =
                                                "<tr bordercolor=\"#000000\">
                                                <td width=\"80\" rowspan=\"4\" align=\"center\">" . "B" . $CoreAbilitiesNo[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter] . "</td>
                                                <td> <strong>" . $content[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter] . "</strong> </td>
                                                </tr>
                                                <tr bordercolor=\"#000000\">
                                                <td id=\"" . $JavaScript_REPLACE_CoreAbilitiesReason_NameTemp . "\">"
                                                . $$TPL_REPLACE_CoreAbilitiesReason_NameTemp .
                                                "</td>
                                                <tr bordercolor=\"#000000\">
                                                <td id=\"" . $JavaScript_REPLACE_CoreAbilitiesTarget_NameTemp . "\">"
                                                . $$TPL_REPLACE_CoreAbilitiesTarget_NameTemp .
                                                "</td>
                                                <tr bordercolor=\"#000000\">
                                                <td id=\"" . $JavaScript_REPLACE_CoreAbilitiesMethod_NameTemp . "\">"
                                                . $$TPL_REPLACE_CoreAbilitiesMethod_NameTemp .
                                                "</td>
                                                </tr>";
                                }
                        }
                }
        }
	else if($group_id==16 ){
                for($ClassGoalNumberCounter = 1; $ClassGoalNumberCounter<=$ClassGoalNumber; $ClassGoalNumberCounter++){
                        for($CoreAbilitiesNumberCounter = 1; $CoreAbilitiesNumberCounter<=$CoreAbilitiesNumber[$ClassGoalNumberCounter]; $CoreAbilitiesNumberCounter++){
                                $TPL_REPLACE_CoreAbilitiesReason_TR_NameTemp = "TPL_REPLACE_CoreAbilitiesReason_TR" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
                                $JavaScript_REPLACE_CoreAbilitiesReason_NameTemp = "JavaScript_REPLACE_CoreAbilitiesReason" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
                                $JavaScript_REPLACE_CoreAbilitiesTarget_NameTemp = "JavaScript_REPLACE_CoreAbilitiesTarget" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
                                $JavaScript_REPLACE_CoreAbilitiesMethod_NameTemp = "JavaScript_REPLACE_CoreAbilitiesMethod" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;

                                $TPL_REPLACE_CoreAbilitiesReason_NameTemp = "TPL_REPLACE_CoreAbilitiesReason" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
                                $TPL_REPLACE_CoreAbilitiesTarget_NameTemp = "TPL_REPLACE_CoreAbilitiesTarget" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
                                $TPL_REPLACE_CoreAbilitiesMethod_NameTemp = "TPL_REPLACE_CoreAbilitiesMethod" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;

                                if($ShowType == 0 || $ShowType == 2 || ($ShowType == 1 && $CoreAbilitiesList[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter] == 1))
                                {
                                        $$TPL_REPLACE_CoreAbilitiesReason_TR_NameTemp =
                                                "<tr bordercolor=\"#000000\">
                                                <td width=\"80\" rowspan=\"4\" align=\"center\">" . "D" . $CoreAbilitiesNo[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter] . "</td>
                                                <td> <strong>" . $content[$ClassGoalNumberCounter][$CoreAbilitiesNumberCounter] . "</strong> </td>
                                                </tr>
                                                <tr bordercolor=\"#000000\">
                                                <td id=\"" . $JavaScript_REPLACE_CoreAbilitiesReason_NameTemp . "\">"
                                                . $$TPL_REPLACE_CoreAbilitiesReason_NameTemp .
                                                "</td>
                                                <tr bordercolor=\"#000000\">
                                                <td id=\"" . $JavaScript_REPLACE_CoreAbilitiesTarget_NameTemp . "\">"
                                                . $$TPL_REPLACE_CoreAbilitiesTarget_NameTemp .
                                                "</td>
                                                <tr bordercolor=\"#000000\">
                                                <td id=\"" . $JavaScript_REPLACE_CoreAbilitiesMethod_NameTemp . "\">"
                                                . $$TPL_REPLACE_CoreAbilitiesMethod_NameTemp .
                                                "</td>
                                                </tr>";
                                }
                        }
                }
        }

	//

	//����TPL_REPLACE_CoreAbilities
	$TPL_REPLACE_CoreAbilities = $TPL_REPLACE_CoreAbilities_TR;
	for($ClassGoalNumberCounter = 1; $ClassGoalNumberCounter<=$ClassGoalNumber; $ClassGoalNumberCounter++){
		for($CoreAbilitiesNumberCounter = 1; $CoreAbilitiesNumberCounter<=$CoreAbilitiesNumber[$ClassGoalNumberCounter]; $CoreAbilitiesNumberCounter++){
			$TPL_REPLACE_CoreAbilitiesReason_TR_NameTemp = "TPL_REPLACE_CoreAbilitiesReason_TR" . $ClassGoalNumberCounter . "_" . $CoreAbilitiesNumberCounter;
			$TPL_REPLACE_CoreAbilities = 
				$TPL_REPLACE_CoreAbilities
				. $$TPL_REPLACE_CoreAbilitiesReason_TR_NameTemp;
		}
	}



	//����output���˦�
	//$ShowType 0 �@�}�l����J������
	//			1 �g�J�ɮת�����
	//			2 �g�J�ɮ׫� �n�e�{������
	if($ShowType == 1)
	{
		$OUT_TABLE = "<table width=\"90%\" border=\"1\" cellspacing=\"0\" bordercolor=\"#000000\">";

		$OUT_Title = "�����j�ǽҵ{�j��<br>" . $ClassOpenDepartment;

		$OUT_ClassNameChinese = $ClassNameChinese . "&nbsp;";
		$OUT_ClassOpenDepartment = $ClassOpenDepartment . "&nbsp;";
		$OUT_ClassNameEnglish = $ClassNameEnglish . "&nbsp;";
		$OUT_ClassID = $ClassID . "&nbsp;";
		$OUT_Teacher = $Teacher . "&nbsp;";
		$OUT_CreditPoints = $CreditPoints . "&nbsp;";
		$OUT_Classification = $Classification . "&nbsp;";
		$OUT_StudentRange = $StudentRange . "&nbsp;";
		$OUT_ClassPremise = str_replace("\n", "<br>", $ClassPremise) . "&nbsp;";
		$OUT_ClassIntroduction = str_replace("\n", "<br>", $ClassIntroduction) . "&nbsp;";

		//�ʺA����OUT_LearningGoal������
		$OUT_LearningGoal = "";
		for($LearningGoalNumberCounter = 1; $LearningGoalNumberCounter<=$LearningGoalNumber; $LearningGoalNumberCounter++){
			$LearningGoal_InputName = "LearningGoal" . $LearningGoalNumberCounter;

			$OUT_LearningGoal =	
				$OUT_LearningGoal . 
				$LearningGoalNumberCounter . "." . $LearningGoalList[ $LearningGoalNumberCounter] . "<br>";
		}

		$OUT_TextBook = str_replace("\n", "<br>", $TextBook) . "&nbsp;";
	}
	else
	{
		$OUT_TABLE = "<table width=\"80%\"  border=\"1\" bordercolor=\"#000000\">";

		$OUT_ClassNameChinese = "<input name=\"ClassNameChinese\" type=\"text\" id=\"ClassNameChinese\" size=\"30\" maxlength=\"40\" " . $TPL_REPLACE_ClassNameChineseValue . " disabled>";
		$OUT_ClassOpenDepartment = "<input name=\"ClassOpenDepartment\" type=\"text\" id=\"ClassOpenDepartment\"   size=\"40\" maxlength=\"40\" " . $TPL_REPLACE_ClassOpenDepartmentValue . " disabled>";
		$OUT_ClassNameEnglish = "<input name=\"ClassNameEnglish\" type=\"text\" id=\"ClassNameEnglish\"  size=\"30\" maxlength=\"40\" " . $TPL_REPLACE_ClassNameEnglishValue . " " . $disabled . ">";
		$OUT_ClassID = "<input name=\"ClassID\" type=\"text\" id=\"ClassID\"  size=\"40\" maxlength=\"40\" maxlength=\"40\" " . $TPL_REPLACE_ClassIDValue . " disabled>";
		$OUT_Teacher = "<input name=\"Teacher\" type=\"text\" id=\"Teacher\" size=\"40\" maxlength=\"40\" " . $TPL_REPLACE_TeacherValue . " disabled>";
		$OUT_CreditPoints = "<input name=\"CreditPoints\" type=\"text\" id=\"CreditPoints\" maxlength=\"10\" " . $TPL_REPLACE_CreditPointsValue . " " . $disabled . ">";
		$OUT_Classification = "	<select name=\"Classification\" id=\"Classification\" " . $disabled . ">
			<option " . $TPL_REPLACE_ClassificationSelected1 . " value=\"����\">����</option>
			<option " . $TPL_REPLACE_ClassificationSelected2 . " value=\"���\">���</option>
			</select>";
		$OUT_StudentRange = "<input name=\"StudentRange\" type=\"text\" id=\"StudentRange\" size=\"20\" maxlength=\"40\" " . $TPL_REPLACE_StudentRangeValue . " " . $disabled . ">";
		$OUT_ClassPremise = "<textarea name=\"ClassPremise\" id=\"ClassPremise\"  rows=\"10\" cols=\"80\" " . $disabled . ">" . str_replace("\n", "",$TPL_REPLACE_ClassPremiseValue) . "</textarea>";
		$OUT_ClassIntroduction = "<textarea name=\"ClassIntroduction\" id=\"ClassIntroduction\" rows=\"10\" cols=\"80\" " . $disabled . ">" . str_replace("\n", "",$TPL_REPLACE_ClassIntroductionValue) . "</textarea>";

		$OUT_LearningGoal = $TPL_REPLACE_LearningGoalNumber . "<br>
			���ҵ{�ǲߥؼЬ��G<br>" . $TPL_REPLACE_LearningGoal_Content;

		$OUT_TextBook = "<textarea name=\"TextBook\" id=\"TextBook\"  rows=\"10\" cols=\"80\" " . $disabled . ">" . str_replace("\n", "",$TPL_REPLACE_TextBookValue) . "</textarea>";
	}


	if($ShowType == 0 || $ShowType == 2){	
		$TPL_REPLACE_FormActionButton = 
			"<input type=\"submit\" value=\"�T�w\" onMouseDown=\"SubmitOnMouseDown()\" onKeyDown=\"SubmitOnMouseDown()\">
			<input type=\"reset\" value=\"�M��\">";
	}
	if($ShowType == 0 || $ShowType == 2){

		$TPL_REPLACE_Detail = 
			$OUT_TABLE . 
			" <tr>
			<td align=\"left\">
			���G1. ��L��]�t�ѳX�B�M�D�t�������ʡC<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2. �оǭn�I�ж�g�Ч��s��B�оǤ�k�B���q��k�B�оǸ귽�B�оǬ����t�X�ƶ����C<br>
			</td>
			</tr>
			</table>
			";
	}

	if($ShowType == 1){	
		$TPL_REPLACE_MARK = "<input name=\"MARK_ClassIntroIndexHtml\" type=\"hidden\" value=\"1\">";
		$note = "";
	}
	$note = "<font color=\"#FF0000\">�p�����X�{���֤߯�O�A�Х����y�T�w�z�e�X���</font>";

	$TPL_REPLACE_Form_Content = 
		$TPL_REPLACE_Form_Content . 
		"<script language=\"JavaScript\">
		function LearningGoalNumberOnChange() {
			ClassIntroIndexHtml.ActionType.value = 1;
			ClassIntroIndexHtml.action = './intro.php#TPL_REPLACE_LearningGoalNumberLink';
			ClassIntroIndexHtml.submit();
		}

	function ClassTopicNumberOnChange() {
		ClassIntroIndexHtml.ActionType.value = 1;
		ClassIntroIndexHtml.action = './intro.php#TPL_REPLACE_ClassTopicNumberLink';
		ClassIntroIndexHtml.submit();
	}"
	. $TPL_REPLACE_CoreAbilities_JavaScript_OnChick .
		"function SubmitOnMouseDown(){
		ClassIntroIndexHtml.ShowType.value = 2;
}
</script>"
. $TPL_REPLACE_MARK .
"<form action=\"./intro.php\" method=\"post\" name=\"ClassIntroIndexHtml\">
<center>
<input name=\"FormType\" type=\"hidden\" value=\"1\">
<input name=\"ShowType\" type=\"hidden\" value=\"0\">
<input name=\"ActionType\" type=\"hidden\" value=\"0\">
<span align=\"center\">" . $OUT_Title . "</span>"
. $OUT_TABLE . 
"	  <tr bordercolor=\"#000000\">
<td width=\"125\">�ҵ{�W��(����)�G</td>
<td>" . $OUT_ClassNameChinese . "</td>
<td width=\"80\">�}�ҳ��G</td>
<td colspan=\"3\">" . $OUT_ClassOpenDepartment .  "</td>
</tr>
<tr bordercolor=\"#000000\">
<td>�ҵ{�W��(�^��)�G</td>
<td>" . $OUT_ClassNameEnglish . "</td>
<td>�ҵ{�N�X�G</td>
<td colspan=\"3\">" . $OUT_ClassID . "</td>
</tr>
<tr bordercolor=\"#000000\">
<td>�½ұЮv�G</td>
<td colspan=\"5\">" . $OUT_Teacher . "</td>
</tr>
<tr bordercolor=\"#000000\">
<td>�Ǥ��ơG</td>
<td>" . $OUT_CreditPoints . "</td>
<td>��/��סG</td>
<td>" . $OUT_Classification . "</td>
<td width=\"80\">�}��~�šG</td>
<td>" . $OUT_StudentRange . "</td>
</tr>
<tr bordercolor=\"#000000\">
<td> ���׬�ة�<br>���Ư�O�G </td>
<td colspan=\"5\" >" . $OUT_ClassPremise . "</td>
</tr>
<tr bordercolor=\"#000000\">
<td> �ҵ{���z�G </td>
<td colspan=\"5\">" . $OUT_ClassIntroduction . "</td>
</tr>
<tr bordercolor=\"#000000\">
<td> �ǲߥؼСG </td>
<td colspan=\"5\">" . $OUT_LearningGoal . "</td>
</tr>
<tr bordercolor=\"#000000\">
<td> �Ь�ѡG</td>
<td colspan=\"5\">" . $OUT_TextBook . "</td>
</tr>
</table> 
<br>
<br>"
. $TPL_REPLACE_ClassTopicNumber . 
"<br>"
. $OUT_TABLE . 
"	<tr bordercolor=\"#000000\">
<td colspan=\"2\" align=\"center\">


�ҵ{�j��
</td>
<td colspan=\"4\" align=\"center\">
���t�ɼ�
</td>
<td rowspan=\"2\" align=\"center\"> �֤߯�O </td>
<td rowspan=\"2\" align=\"center\"> �Ƶ� </td>
</tr>
<tr bordercolor=\"#000000\">
<td align=\"center\">
�椸�D�D
</td>
<td align=\"center\">
���e���n
</td>
<td width=\"35\" align=\"center\">
����
</td>
<td width=\"35\" align=\"center\">
�ܽd
</td>
<td width=\"35\" align=\"center\">
�ߧ@
</td>
<td width=\"35\" align=\"center\">
��L
</td>
</tr>"
. $TPL_REPLACE_ClassTopic_Content .
"</table>" .

CADes($group_id)

."<br>"
. $OUT_TABLE . 
"  <tr bordercolor=\"#000000\">
<td align=\"left\">
�оǭn�I���z�G
</td>
</tr>
<tr bordercolor=\"#000000\">
<td align=\"left\">
1.	�Ч��s��G"
. $TPL_REPLACE_TeachKeyPointClassificationContent1 . 
"
</td>
</tr>
<tr bordercolor=\"#000000\">
<td align=\"left\">
2.	�оǤ�k�G"
. $TPL_REPLACE_TeachKeyPointClassificationContent2 . 
"
</td>
</tr>
<tr bordercolor=\"#000000\">
<td align=\"left\">
3.	���q��k�G"
. $TPL_REPLACE_TeachKeyPointClassificationContent3 . 
"
</td>
</tr>
<tr bordercolor=\"#000000\">
<td align=\"left\">
4.	�оǸ귽�G"
. $TPL_REPLACE_TeachKeyPointClassificationContent4 . 
"
</td>
</tr>
<tr bordercolor=\"#000000\">
<td align=\"left\">
5.	�оǬ����t�X�ƶ��G 
" . $TPL_REPLACE_TeachKeyPoint_ReleatedWork . "
</td>
</tr>
</table>

<br>"
. $OUT_TABLE . 
"  <tr bordercolor=\"#000000\">
<td colspan=\"2\"> �ҵ{�ؼлP�Ш|�֤߯�O������ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
" . $note . "
</td>
</tr>"
. $TPL_REPLACE_CoreAbilities . 
"</table>"
. $TPL_REPLACE_Detail . 							
"<br>"
. $TPL_REPLACE_FormActionButton . 
"</center>
</form>"
;


return $TPL_REPLACE_Form_Content;
}

//
//
//
//
//
//
//
function show_page_d ( $error="" ) {
	global $course_id, $check, $teacher, $version, $query, $skinnum, $PHPSESSID, $isOld, $year, $term;	
	//echo "show_page_d course_id:".$course_id."\n";	//for test
	global $intro;

	global $FormType;//�P�_�� �ǲή榡 �� ���ؤu�{�{�Ү榡
	global $ShowType;
	if(isset($ShowType) == 0){	$ShowType = 0;	}
	//echo "show_page_d ShowType:".$ShowType;	//for test

	if($isOld !="1")
	{
		//echo "$year  $term $isOld<br>";
		if ( is_file("../../$course_id/intro/index.html") ) {
			if ( $check == 2 && $teacher == 1 ) {
				$fp = fopen("../..".$old_path."/$course_id/intro/index.html", "r");
				if( filesize("../../$course_id/intro/index.html") > 0){
					//$content = fread($fp , filesize("../../$course_id/intro/index.html"));
					if(isset($intro) == 0){
						$content = fread($fp , filesize("../../$course_id/intro/index.html"));
					}else{
						$content = $intro;
					}
				}
				fclose($fp);
				include("class.FastTemplate.php3");
				$tpl = new FastTemplate ( "./templates" );
				$tpl->define ( array ( body => "intro2.tpl") );
				$ip = getenv ("SERVER_NAME" );
				if ( $ip == "" )
					$ip = $SERVER_NAME;
				$tpl->assign ( BASE, "http://$ip/$course_id/intro/");
				$tpl->assign ( HEAD, "<base href=\"http://$ip/php/Courses_Admin/\" target=\"_self\">");
				$tpl->assign( SKINNUM , $skinnum );
			}
			else {
				header( "Location: ../../$course_id/intro/index.html");
				exit;

			}
		}
		else if ( is_file("../../$course_id/intro/index.htm") ) {
			if ( $check == 2 && $teacher == 1 ) {
				$fp = fopen("../../$course_id/intro/index.htm", "r");
				$content = fread($fp , filesize("../../$course_id/intro/index.htm"));
				fclose($fp);
				include("class.FastTemplate.php3");
				$tpl = new FastTemplate ( "./templates" );
				$tpl->define ( array ( body => "intro2.tpl") );
				$ip = getenv ("SERVER_NAME" );
				if ( $ip == "" )
					$ip = $SERVER_NAME;
				$tpl->assign ( BASE, "http://$ip/$course_id/intro/");
				$tpl->assign ( HEAD, "<base href=\"http://$ip/php/Courses_Admin/\" target=\"_self\">");
				$tpl->assign( SKINNUM , $skinnum );
			}
			else {
				header( "Location: ../../$course_id/intro/index.htm");
				exit;

			}
		}
		else if ( is_file("../../$course_id/intro/index.doc") ){
			global $check, $version, $course_id, $teacher;
			include("class.FastTemplate.php3");
			$tpl = new FastTemplate ( "./templates" );
			$content = "<HTML>\n<HEAD>\n<TITLE>�½Ҥj��</TITLE>\n".
				"<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=../../$course_id/intro/index.doc\">\n".
				"</HEAD>\n</HTML>";
			$tpl->define ( array ( body => "intro2.tpl") );
			$tpl->assign ( HEAD, "");
			$tpl->assign( SKINNUM , $skinnum );
		}
		else if ( is_file("../../$course_id/intro/index.docx") ){
			global $check, $version, $course_id, $teacher;
			include("class.FastTemplate.php3");
			$tpl = new FastTemplate ( "./templates" );
			$content = "<HTML>\n<HEAD>\n<TITLE>�½Ҥj��</TITLE>\n".
				"<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=../../$course_id/intro/index.docx\">\n".
				"</HEAD>\n</HTML>";
			$tpl->define ( array ( body => "intro2.tpl") );
			$tpl->assign ( HEAD, "");
			$tpl->assign( SKINNUM , $skinnum );
		}
		else if ( is_file("../../$course_id/intro/index.pdf") ){
			global $check, $version, $course_id, $teacher;
			include("class.FastTemplate.php3");
			$tpl = new FastTemplate ( "./templates" );
			$content = "<HTML>\n<HEAD>\n<TITLE>�½Ҥj��</TITLE>\n".
				"<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=../../$course_id/intro/index.pdf\">\n".
				"</HEAD>\n</HTML>";
			$tpl->define ( array ( body => "intro2.tpl") );
			$tpl->assign ( HEAD, "");
			$tpl->assign( SKINNUM , $skinnum );
		}
		else if (is_file("../../$course_id/intro/index.ppt"))
		{
			global $check, $version, $course_id, $teacher;
			include("class.FastTemplate.php3");
			$tpl = new FastTemplate ("./templates");
			$content = "<html>\n<head>\n<title>�½Ҥj��</title>\n".
				"<meta http-equiv=REFRESH content=\"0;url=../../$course_id/intro/index.ppt\">\n".
				"</head>\n</html>";
			$tpl->define (array(body => "intro2.tpl"));
			$tpl->assign (HEAD, "");
			$tpl->assign (SKINNUM, $skinnum);
		}
		else if (is_file("../../$course_id/intro/index.pptx"))
		{
			global $check, $version, $course_id, $teacher;
			include("class.FastTemplate.php3");
			$tpl = new FastTemplate ("./templates");
			$content = "<html>\n<head>\n<title>�½Ҥj��</title>\n".
				"<meta http-equiv=REFRESH content=\"0;url=../../$course_id/intro/index.pptx\">\n".
				"</head>\n</html>";
			$tpl->define (array(body => "intro2.tpl"));
			$tpl->assign (HEAD, "");
			$tpl->assign (SKINNUM, $skinnum);
		}
		else {
			global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
			$Q1 = "select introduction, name FROM course where a_id ='$course_id'";
			if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
				$message = "$message - ��Ʈw�s�����~!!";
				show_page ( "not_access.tpl", $message, "", "<a href=./guest.php>�^�W�@��</a>" );
			}
			else if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
				$message = "$message - ��ƮwŪ�����~!!";
				show_page ( "not_access.tpl", $message, "", "<a href=./guest.php>�^�W�@��</a>" );
			}
			else if( $row = mysql_fetch_array( $result ) ) {
				global $check, $version, $course_id, $teacher;
				include("class.FastTemplate.php3");
				$tpl = new FastTemplate ( "./templates" );
				$content = $row['introduction'];
				$tpl->define ( array ( body => "intro.tpl") );
				$tpl->assign ( HEAD, "");
				$tpl->assign( SKINNUM , $skinnum );
			}
			else
				show_page ( "not_access.tpl", $course_id."�S�����" );
		}

		if ( $check == 2 && $teacher == 1 ) {
			if($FormType == 0){
				//form���ǲή榡

				$tpl->assign ( 
						TPL_REPLACE_Form_FormType, 
						"<form action=\"./intro.php\" method=\"post\" name=\"FormType\">
						<input name=\"FormType\" type=\"hidden\" value=\"1\">
						<input type=\"submit\" value=\"������ ���ؤu�{�{�Ү榡\">
						</form>	"
					     );

				//�ɮ׬����ؤu�{�{�Ү榡  �h���q�X��
				if( stristr($content, "MARK_ClassIntroIndexHtml") != false){
					$content = "";
				}

				$tpl->assign ( 
						TPL_REPLACE_Form_Content, 
						"<form action=\"./intro.php\" method=\"post\">
						<input name=\"FormType\" type=\"hidden\" value=\"0\">
						<textarea name=intro  rows=\"10\" cols=\"60\">" . $content . "</textarea><br>
						<input type=\"submit\" value=\"�T�w\">
						<input type=\"reset\" value=\"�M��\">
						</form>"
					     );

				$tpl->assign ( TITLE, $row['name'] );
				//$tpl->assign ( MES, $content );
				$tpl->assign( SKINNUM , $skinnum );

				if ( stristr($content,"<html>") == NULL )
					$content = str_replace ( "\n", "<BR>", $content );
				$tpl->assign ( MER, $content );
				$tpl->assign ( ERR, $error );
				$tpl->parse( BODY2, "body");
			}else if($FormType == 1){
				//form�����ؤu�{�{�Ү榡

				//����TPL_REPLACE_Form_FormType
				$tpl->assign ( 
						TPL_REPLACE_Form_FormType, 
						"<form action=\"./intro.php\" method=\"post\" name=\"FormType\">
						<input name=\"FormType\" type=\"hidden\" value=\"0\">
						<input type=\"submit\" value=\"������ �ǲή榡\">
						</form>	"
					     );				

				//����TPL_REPLACE_Form_Content
				$tpl->assign ( 
						TPL_REPLACE_Form_Content, 
						createForm($ShowType, 0)
					     );


			}

			if ( $_GET[showintro] == 1 ) {
				$tpl->FastPrint("BODY2");
			}
			else
			{
				echo "<div align=\"center\" class=\"style1\"><a href=# onClick=\"window.open('./intro_content.php?PHPSESSID=$PHPSESSID&courseid=$course_id&showintro=1', '', 'width=720,height=540,resizable=1,scrollbars=1');\">�w���½Ҥj��</a></div>";
				echo "<div align=\"center\" class=\"style1\"><a href=# onClick=\"window.open('./intro_content.php?PHPSESSID=$PHPSESSID&courseid=$course_id&showintro=1&action=print', '', 'width=720,height=540,resizable=1,scrollbars=1');\">�C�L�½Ҥj��</a></div>";
			}	
			if ( $version == "C" )
				$tpl->define ( array ( tail => "introi.tpl") );
			else
				$tpl->define ( array ( tail => "introi_E.tpl") );				
			$tpl->define_dynamic("file_list", "tail");

			$work_dir = "../../$course_id/intro";
			$handle = dir($work_dir);
			$i=false;
			while (( $file = $handle->read() ) ) {
				if(strcmp($file,".") !=0 && strcmp($file,"..")) {   
					// ���F '.' '..'���~���ɮ׿�X
					$tpl->assign("FILE_N", $file);
					$tpl->assign("FILE_LINK", $work_dir."/".$file);
					$tpl->assign("FILE_SIZE", filesize($work_dir."/".stripslashes($file)));
					$tpl->assign("FILE_DATE", date("Y-m-d H:i:s",filemtime($work_dir."/".$file)));
					if ( $version == "C" ) {
						$tpl->assign("DELETE", "<a href=\"./intro.php?del=1&filename=$file\" onclick=\"return confirm('�A�T�w�n�R���o���ɮ׶�?');\">�R���o���ɮ�</a>" );
					}
					else {
						$tpl->assign("DELETE", "<a href=\"./intro.php?del=1&filename=$file\" onclick=\"return confirm('Are You Sure to Del This File?');\">Del This File</a>" );
					}

					// �C�ⱱ��.
					if($i)
						$tpl->assign("F_COLOR", "#ffffff");
					else
						$tpl->assign("F_COLOR", "#edf3fa");

					$i=!$i;

					$tpl->parse(ROWF, ".file_list");
					$set_file = 1;
				}
			}
			$handle->close();

			// �S�������ɮשΥؿ��ɪ��ҥ~�B�z
			if($set_file==0) {
				$tpl->assign("FILE_N", "");
				$tpl->assign("FILE_SIZE", "");
				$tpl->assign("FILE_DATE", "");
				$tpl->assign("DELETE", "");
				$tpl->assign("F_COLOR", "#edf3fa");
			}
			$tpl->parse( TAIL, "tail");
			$tpl->FastPrint("TAIL");
		}
		else {
			$tpl->assign ( ERR, $error );
			$tpl->define ( array ( body => "intro.tpl") );
			$tpl->assign ( TITLE, $row['name'] );
			$tpl->assign( SKINNUM , $skinnum );
			//$content = $row['introduction'];
			if ( stristr($content,"<html>") == NULL )
				$content = str_replace ( "\n", "<BR>", $content );
			$tpl->assign ( MER, $content );
			if ( $version == "C" )
				$tpl->assign ( IMAGE, "img" );
			else
				$tpl->assign ( IMAGE, "img_E" );
			$tpl->assign ( MES, $content );
			$tpl->parse( BODY, "body");
			$tpl->FastPrint("BODY");
			if ( $query == 1 )
				$course_id = "-1";
		}			
	}else{
		$id=$course_id;
		$content="";
		
		//�N�w�]��Ū��index.html��b�̤U���A�H�����½ҵ{�Y��index.html�]�I�s../../$year/$term/$id/index.xxx�|�hŪ���Ǵ�����ơ^

		//if ( is_file("../../old_intro/$year/$term/$id/index.htm") ) {
		if ( is_file("../../echistory/$year/$term/$id/intro/index.htm") ) {

			$fp = fopen("../../echistory/$year/$term/$id/intro/index.htm", "r");
			$content = fread($fp , filesize("../../echistory/$year/$term/$id/intro/index.htm"));
			fclose($fp);;
		}
		//else if ( is_file("../../old_intro/$year/$term/$id/index.doc") ){
		else if ( is_file("../../echistory/$year/$term/$id/intro/index.doc") ){
			//global $check, $version, $course_id, $teacher;
			$content = "<HTML>\n<HEAD>\n<TITLE>�½Ҥj��</TITLE>\n".
				"<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=../../echistory/$year/$term/$id/intro/index.doc\">\n".
				"</HEAD>\n</HTML>";	
		}
		else if ( is_file("../../echistory/$year/$term/$id/intro/index.docx") ){
			//global $check, $version, $course_id, $teacher;
			$content = "<HTML>\n<HEAD>\n<TITLE>�½Ҥj��</TITLE>\n".
				"<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=../../echistory/$year/$term/$id/intro/index.docx\">\n".
				"</HEAD>\n</HTML>";	
		}
		//else if ( is_file("../../old_intro/$year/$term/$id/index.pdf") ){
		else if ( is_file("../../echistory/$year/$term/$id/intro/index.pdf") ){
			//global $check, $version, $course_id, $teacher;
			$content = "<HTML>\n<HEAD>\n<TITLE>�½Ҥj��</TITLE>\n".
				"<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=../../echistory/$year/$term/$id/intro/index.pdf\">\n".
				"</HEAD>\n</HTML>";
		}
		//else if (is_file("../../old_intro/$year/$term/$id/index.ppt"))
		else if (is_file("../../echistory/$year/$term/$id/intro/index.ppt"))
		{
			//global $check, $version, $course_id, $teacher;
			$content = "<html>\n<head>\n<title>�½Ҥj��</title>\n".
				"<meta http-equiv=REFRESH content=\"0;url=../../echistory/$year/$term/$id/intro/index.ppt\">\n".
				"</head>\n</html>";	
		}
		else if (is_file("../../echistory/$year/$term/$id/intro/index.pptx"))
		{
			//global $check, $version, $course_id, $teacher;
			$content = "<html>\n<head>\n<title>�½Ҥj��</title>\n".
				"<meta http-equiv=REFRESH content=\"0;url=../../echistory/$year/$term/$id/intro/index.pptx\">\n".
				"</head>\n</html>";	
		}
		//else if ( is_file("../../old_intro/$year/$term/$id/index.html") ) {
		else if ( is_file("../../echistory/$year/$term/$id/intro/index.html") ) {
			$fp = fopen("../../echistory/$year/$term/$id/intro/index.html", "r");
			$content = fread($fp , filesize("../../echistory/$year/$term/$id/intro/index.html"));
			fclose($fp);

		}
		else {
			echo "<html>\n<head>\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=big5\" />\n";
			echo "<title>�S���ҵ{�j��</title>\n</head>\n<body>";
			echo "�S���ҵ{�j��<br>";
			echo "</body>\n</html>";
		}

		//commented by carlyle
		/*
		   if ( stristr($content,"<html>") == NULL )
		   $content = str_replace ( "\n", "<BR>", $content );
		 */

		echo $content;		
	}
	}
	?>
