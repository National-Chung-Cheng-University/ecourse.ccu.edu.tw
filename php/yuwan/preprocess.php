<?php

require_once 'common.php';
require_once 'fadmin.php';
//echo die("tes");
global $user_id;
global $PHPSESSID;
global $year;


$Qyear = "select year FROM this_semester";
$Qyearresult = mysql_db_query( $DB, $Qyear );
$row = mysql_fetch_array( $Qyearresult);
echo "�~��:".$row['year']."<br>";
$year = $row['year'];
echo "ID=" . $user_id ."<br>";
$Q1 = "select name FROM user where id = '$user_id'";
if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
       $message = "$message - ��ƮwŪ�����~!!";
}else
	$row = mysql_fetch_array( $result );
       
$stu_name = $row['name']; 
echo " name: " .$stu_name."<br>";

//2011.10.25
//�q�~���ɮ�(*.csv�ɦC�X�Ӿǥͭ׽ұ��ΡA�s�J$compulsory�}�C)
//start
$grade = "�Ǥh�Z�|�~��";
$id = "497410001";
$name = "�[��";
$compulsory_count=0;
$optional_count=0;
//$handle = fopen("./".$grade."-".$id."_".$name.".CSV", "r");
//$handle = fopen("./yuwan/�������~��-496410005_�J�Q�`.CSV","r");
//$handle = fopen("./�Ǥh�Z�|�~��-497410030_�L�@��.CSV","r");
$handle = fopen("./497410031.CSV","r");
//$user_id ='497410031'; 
$com_start_year = 96;
$compulsory_num[0] = 27; //96�J�ǥ��׽Ҽ�
$compulsory_num[1] = 29; //97�J�ǥ��׽Ҽ�
$compulsory_num[2] = 29; //98�J�ǥ��׽Ҽ�
$compulsory_num[3] = 26; //99�J�ǥ��׽Ҽ�
$compulsory_num[4] = 26; //100�J�ǥ��׽Ҽ�

//�P�_�ǥ;ǯ�
$grade_year = 96;

//


if ($handle) {

    while (($buffer = fgets($handle, 4096)) !== false) { //�@�����o�@���r
    		$school = explode(",", $buffer);         //��,���r��
		//echo $buffer . "<br/>";
		if ($school[0] == "�M��" || $school[0] == "�M��"){
			$compulsory[$compulsory_count] = $school[2];      //�x�s��ئW��
			$course_no_cor[$compulsory_count] = $school[1].'_01';   //�x�s��إN�X (�j��[�J_01 ����A�Z���� (�P��ڸ�Ƥ��� �����v�T�֤߯�O�Ŀ�))
		 	echo "course_no: ". $course_no_cor[$compulsory_count]."X".$compulsory[$compulsory_count]."<br>";
			$compulsory_count++;

		}
		if ($school[18]== "�M��")
		{
			$compulsory_grade_num = $school[19];
		}
		if ($school[5]=="�q��")
		{
			$general_grade_num = $school[6];
		}
		if( ($school[3] == "�M�D����]�@�^" || $school[3] == "�M�D����(�@)") && $school[17]>59)
		{
			$seminar_experiment_1 = 1;
		}
		if( ($school[3] == "�M�D����]�G�^" || $school[3] == "�M�D����(�G)") && $school[17]>59)
                {
                        $seminar_experiment_2 = 1;
                }

	}
		//print_r ($compulsory)	;
		/*for ($i=0;$i<count($compulsory) ; $i++)
                {
                   echo $compulsory[$i]. "<br>";
                }
		echo "�@".$i."�󥲭׽�<br>"; 
		echo "�ֿn�M�~���׾Ǥ�: " .$compulsory_grade_num. "<br>";
		echo "�ֿn�q�ѱШ|�Ǥ�: " .$general_grade_num. "<br>";
		*/
		 echo "***". $seminar_experiment_1 ." ". $seminar_experiment_2."<br>";

} 
//end

//�Y�ϥΪ̤��s�b�A�N�ϥΪ̸�ƫؤJ��ƮwIEEE_Results
//�A�ھڨC�Ӯ֤߯�O�p��X���� update���

$Q = "SELECT  `id` FROM  `IEEE_Results` WHERE  `id` = '$user_id' and `year` = '$year'" ;
$result = mysql_db_query( $DB, $Q );

if(!$row = mysql_fetch_array( $result )){
	$sql= "INSERT INTO `study`.`IEEE_Results` (`id`, `year`, `1_1`, `1_2`, `1_3`, `2_1`, `2_2`, `2_3`, `3_1`, `3_2`, `3_3`, `4_1`, `4_2`) VALUES ('$user_id', '$year','0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');";
	mysql_db_query("study",$sql) or die ("insert �ɵo�Ϳ��~");
}
//2011.10.27
//�p��Ӿǥͦb�C�Ӯ֤߯�O�ŦX����ơB�ұo�쪺����

$Q1_1 = " SELECT DISTINCT course.course_no, course.name, IC.`Classification` FROM  `IEEE_CourseIntro_CoreAbilities` ICC ,  `course` ,  `IEEE_CourseIntro` IC WHERE IC.`Classification` =  '����' AND ICC.`course_id` = course.a_id AND ICC.`course_id` = IC.`course_id` AND  ICC.`group_id` =11 " ;
if ( !($result = mysql_db_query( $DB, $Q1_1 ) ) ){
	 $message = "$message - ��ƮwŪ�����~!!";
	
}
else{
	$count1_1=0;
	while( $row1_1 = mysql_fetch_array( $result )){
 		$j=0;
		for( $j=0;$j<count($course_no_cor);$j++ ){
 			//if( $compulsory[$j]== $row1_1[0])
			if( $course_no_cor[$j]== $row1_1[0] )
			{
				//echo $row1_1[0]." ".$row1_1[1]."<br>";
				$count1_1 ++;
			}

		}	
	}
	//��W�P�_�L�n���ҵ{(���ݩ��u�t���}�ҽd��)
	for( $j=0;$j<count($compulsory);$j++ ){

		if ($course_no_cor[$j] == '2101001_01')
		{
			$count1_1 ++;
		}
		else if ($course_no_cor[$j] == '2101002_01')
		{
		        $count1_1 ++;
		}

	}

	echo "�`�@��".$count1_1."���ŦX1.1����<BR>";
//
}
//�P�_�з� 1_1 : �ŦX���ҵ{�� / �W�w�n�ת����׽ҵ{��
$score = $count1_1/ $compulsory_num[$grade_year-$com_start_year];
$score = number_format($score, 2, '.', ''); 
if($score >2) $score =2;
echo $score;
$sql= " UPDATE  `study`.`IEEE_Results` SET  `1_1` =  '$score' WHERE  `IEEE_Results`.`id` =  '$user_id' and  `IEEE_Results`.`year` =  '$year'";
mysql_db_query("study",$sql) or die ("update �ɵo�Ϳ��~");


//
$Q1_2 = " SELECT DISTINCT  course.course_no, course.name FROM IEEE_CourseIntro_CoreAbilities ICC,course ,IEEE_CourseIntro IC  WHERE ICC.`course_id` = course.a_id AND ICC.`course_id` = IC.`course_id` AND IC.group_id = 11 AND course.group_id = ICC.group_id AND ICC.CoreAbilities_Index = '2' AND ICC.`ClassGoal_Index` =  '1' AND ICC.isChecked =  1" ;
if ( !($result = mysql_db_query( $DB, $Q1_2 ) ) ){
         $message = "$message - ��ƮwŪ�����~!!";
}
else{
$count1_2=0;
 while( $row1_2 = mysql_fetch_array( $result )){
        $j=0;

	for( $j=0;$j< count( $course_no_cor) ;$j++ ){
                if(  $course_no_cor[$j]== $row1_2[0]  ) 
                {
                        $count1_2 ++;
                }
        }
}echo "�`�@��".$count1_2."���ŦX1.2����<BR>";

}

//�P�_�з� 1_2 : �ŦX���ҵ{�� / 1_2����з� $standard1_2
$standard1_2 = 16;
$score = $count1_2/ $standard1_2;
if($score >2) $score =2;

echo $score;
$sql= " UPDATE  `study`.`IEEE_Results` SET  `1_2` =  '$score' WHERE  `IEEE_Results`.`id` =  '$user_id' and  `IEEE_Results`.`year` =  '$year'";
mysql_db_query("study",$sql) or die ("update �ɵo�Ϳ��~");



$Q1_3 = " SELECT DISTINCT  course.course_no, course.name FROM IEEE_CourseIntro_CoreAbilities ICC,course ,IEEE_CourseIntro IC WHERE ICC.`course_id` = course.a_id AND ICC.`course_id` = IC.`course_id` AND IC.group_id = 11 AND course.group_id = ICC.group_id AND ICC.CoreAbilities_Index = '12' AND ICC.`ClassGoal_Index` =  '1' AND ICC.isChecked =  1" ;
if ( !($result = mysql_db_query( $DB, $Q1_3 ) ) ){
         $message = "$message - ��ƮwŪ�����~!!";
}
else{
$count1_3=0;
 while( $row1_3 = mysql_fetch_array( $result )){
        $j=0;

        for( $j=0;$j< count( $course_no_cor) ;$j++ ){
                if( $course_no_cor[$j]== $row1_3[0]  )
                {
                       // echo $row1_3[0]."<br>";
                        $count1_3 ++;
                }
        }
}echo "�`�@��".$count1_3."���ŦX 1.3����<BR>";

}
//�P�_�з� 1_3 : �ŦX���ҵ{�� / 1_3����з� $standard1_3
$standard1_3 = 6;
$score = $count1_3/ $standard1_3;
if($score >2) $score =2;
echo $score;
$sql= " UPDATE  `study`.`IEEE_Results` SET  `1_3` =  '$score' WHERE  `IEEE_Results`.`id` =  '$user_id'  and  `IEEE_Results`.`year` =  '$year'";
mysql_db_query("study",$sql) or die ("update �ɵo�Ϳ��~");



$Q2_1 = " SELECT DISTINCT  course.course_no, course.name FROM IEEE_CourseIntro_CoreAbilities ICC,course ,IEEE_CourseIntro IC WHERE ICC.`course_id` = course.a_id AND ICC.`course_id` = IC.`course_id` AND IC.group_id = 11 AND course.group_id = ICC.group_id AND ICC.CoreAbilities_Index = '3' AND ICC.`ClassGoal_Index` =  '2' AND ICC.isChecked =  1" ;
if ( !($result = mysql_db_query( $DB, $Q2_1 ) ) ){
         $message = "$message - ��ƮwŪ�����~!!";
}
else{
$count2_1=0;
 while( $row2_1 = mysql_fetch_array( $result )){
        $j=0;

        for( $j=0;$j< count( $course_no_cor) ;$j++ ){
                if(  $course_no_cor[$j]== $row2_1[0]  )
                {
                        //echo $row2_1[0]."<br>";
                        $count2_1 ++;
                }
        }
}echo "�`�@��".$count2_1."���ŦX 2.1����<BR>";

}

//�P�_�з� 2_1 : �ŦX���ҵ{�� / 2_1����з� $standard2_1
$standard2_1 = 10;
$score = $count2_1/ $standard2_1;
if($score >2) $score =2;
echo $score;
$sql= " UPDATE  `study`.`IEEE_Results` SET  `2_1` =  '$score' WHERE  `IEEE_Results`.`id` =  '$user_id' and  `IEEE_Results`.`year` =  '$year'";
mysql_db_query("study",$sql) or die ("update �ɵo�Ϳ��~");



$Q2_2 = " SELECT DISTINCT course.course_no, course.name FROM IEEE_CourseIntro_CoreAbilities ICC,course ,IEEE_CourseIntro IC WHERE ICC.`course_id` = course.a_id AND ICC.`course_id` = IC.`course_id` AND IC.group_id = 11 AND course.group_id = ICC.group_id AND ICC.CoreAbilities_Index = '4' AND ICC.`ClassGoal_Index` =  '2' AND ICC.isChecked =  1" ;
if ( !($result = mysql_db_query( $DB, $Q2_2 ) ) ){
         $message = "$message - ��ƮwŪ�����~!!";
}
else{
$count2_2=0;
 while( $row2_2 = mysql_fetch_array( $result )){
        $j=0;

        for( $j=0;$j< count( $course_no_cor) ;$j++ ){
                if(  $course_no_cor[$j]== $row2_2[0]  )
                {
                        //echo $row2_2[0]."<br>";
                        $count2_2 ++;
                }
        }
}echo "�`�@��".$count2_2."���ŦX 2.2����<BR>";

}

//�P�_�з� 2_2 : �ŦX���ҵ{�� / 2_2����з� $standard2_2
$standard2_2 = 8;
$score = $count2_2/ $standard2_2;
if($score >2) $score =2;
echo $score;
$sql= " UPDATE  `study`.`IEEE_Results` SET  `2_2` =  '$score' WHERE  `IEEE_Results`.`id` =  '$user_id'  and  `IEEE_Results`.`year` =  '$year'";
mysql_db_query("study",$sql) or die ("update �ɵo�Ϳ��~");




$Q2_3 = "SELECT DISTINCT  course.course_no, course.name FROM IEEE_CourseIntro_CoreAbilities ICC,course ,IEEE_CourseIntro IC WHERE ICC.`course_id` = course.a_id AND ICC.`course_id` = IC.`course_id` AND IC.group_id = 11 AND course.group_id = ICC.group_id AND ICC.CoreAbilities_Index = '5' AND ICC.`ClassGoal_Index` =  '2' AND ICC.isChecked =  1" ;
if ( !($result = mysql_db_query( $DB, $Q2_3 ) ) ){
         $message = "$message - ��ƮwŪ�����~!!";
}
else{
$count2_3=0;
 while( $row2_3 = mysql_fetch_array( $result )){
        $j=0;

        for( $j=0;$j< count( $course_no_cor) ;$j++ ){
                if(  $course_no_cor[$j]== $row2_3[0]  )
                {
                        //echo $row2_3[0]."<br>";
                        $count2_3 ++;
                }
        }
}echo "�`�@��".$count2_3."���ŦX 2.3����<BR>";

}

//�P�_�з� 2_3 : �ŦX���ҵ{�� / 2_3����з� $standard2_3
$standard2_3 = 1;
$score = $count2_3/ $standard2_3;
if($score >2) $score =2;
echo $score;
$sql= " UPDATE  `study`.`IEEE_Results` SET  `2_3` =  '$score' WHERE  `IEEE_Results`.`id` =  '$user_id'  and  `IEEE_Results`.`year` =  '$year'";
mysql_db_query("study",$sql) or die ("update �ɵo�Ϳ��~");




$Q3_1 = " SELECT DISTINCT  course.course_no,course.name FROM IEEE_CourseIntro_CoreAbilities ICC,course ,IEEE_CourseIntro IC WHERE ICC.`course_id` = course.a_id AND ICC.`course_id` = IC.`course_id` AND IC.group_id = 11 AND course.group_id = ICC.group_id AND ICC.CoreAbilities_Index = '7' AND ICC.`ClassGoal_Index` =  '3' AND ICC.isChecked =  1" ;
if ( !($result = mysql_db_query( $DB, $Q3_1 ) ) ){
         $message = "$message - ��ƮwŪ�����~!!";
}
else{
$count3_1=0;
 while( $row3_1 = mysql_fetch_array( $result )){
        $j=0;

        for( $j=0;$j< count( $course_no_cor) ;$j++ ){
                if(  $course_no_cor[$j]== $row3_1[0]  )
                {
                        //echo $row3_1[0]."<br>";
                        $count3_1 ++;
                }
        }
}echo "�`�@��".$count3_1."���ŦX 3.1����<BR>";

}

//�P�_�з� 3_1 : �ŦX���ҵ{�� / 3_1����з� $standard3_1
$standard3_1 = 1;
$score = $count3_1/ $standard3_1;
if($score >2) $score =2;
echo $score;
$sql= " UPDATE  `study`.`IEEE_Results` SET  `3_1` =  '$score' WHERE  `IEEE_Results`.`id` =  '$user_id'  and  `IEEE_Results`.`year` =  '$year'";
mysql_db_query("study",$sql) or die ("update �ɵo�Ϳ��~");


$Q3_2 = " SELECT DISTINCT  course.course_no,course.name FROM IEEE_CourseIntro_CoreAbilities ICC,course ,IEEE_CourseIntro IC WHERE ICC.`course_id` = course.a_id AND ICC.`course_id` = IC.`course_id` AND IC.group_id = 11 AND course.group_id = ICC.group_id AND ICC.CoreAbilities_Index = '8' AND ICC.`ClassGoal_Index` =  '3' AND ICC.isChecked =  1" ;
if ( !($result = mysql_db_query( $DB, $Q3_2 ) ) ){
         $message = "$message - ��ƮwŪ�����~!!";
}
else{
$count3_2=0;
 while( $row3_2 = mysql_fetch_array( $result )){
        $j=0;

        for( $j=0;$j< count( $course_no_cor) ;$j++ ){
                if(  $course_no_cor[$j]== $row3_2[0]  )
                {
                        //echo $row3_2[0]."<br>";
                        $count3_2 ++;
                }
        }
}echo "�`�@��".$count3_2."���ŦX 3.2����<BR>";

}
//�P�_�з� 3_2 : �ŦX���ҵ{�� / 3_2����з� $standard3_2
$standard3_2 = 1;
$score = $count3_2/ $standard3_2;
if($score >2) $score =2;
echo $score;
$sql= " UPDATE  `study`.`IEEE_Results` SET  `3_2` =  '$score' WHERE  `IEEE_Results`.`id` =  '$user_id'  and  `IEEE_Results`.`year` =  '$year'";
mysql_db_query("study",$sql) or die ("update �ɵo�Ϳ��~");


$Q3_3 = " SELECT DISTINCT  course.course_no,course.name FROM IEEE_CourseIntro_CoreAbilities ICC,course ,IEEE_CourseIntro IC WHERE ICC.`course_id` = course.a_id AND ICC.`course_id` = IC.`course_id` AND IC.group_id = 11 AND course.group_id = ICC.group_id AND ICC.CoreAbilities_Index = '9' AND ICC.`ClassGoal_Index` =  '3' AND ICC.isChecked =  1" ;
if ( !($result = mysql_db_query( $DB, $Q3_3 ) ) ){
         $message = "$message - ��ƮwŪ�����~!!";
}
else{
$count3_3=0;
 while( $row3_3 = mysql_fetch_array( $result )){
        $j=0;

        for( $j=0;$j< count( $course_no_cor) ;$j++ ){
                if(  $course_no_cor[$j]== $row3_3[0]  )
                {
                        //echo $row3_3[0]."<br>";
                        $count3_3 ++;
                }
        }
}echo "�`�@��".$count3_3."���ŦX 3.3����<BR>";

}
//�P�_�з� 3_3 : �ŦX���ҵ{�� / 3_3����з� $standard3_3
$standard3_3 = 1;
$score = $count3_3/ $standard3_3;
if($score >2) $score =2;
echo $score;
$sql= " UPDATE  `study`.`IEEE_Results` SET  `3_3` =  '$score' WHERE  `IEEE_Results`.`id` =  '$user_id'  and  `IEEE_Results`.`year` =  '$year'";
mysql_db_query("study",$sql) or die ("update �ɵo�Ϳ��~");


$Q4_1 = " SELECT DISTINCT  course.course_no,course.name FROM IEEE_CourseIntro_CoreAbilities ICC,course ,IEEE_CourseIntro IC WHERE ICC.`course_id` = course.a_id AND ICC.`course_id` = IC.`course_id` AND IC.group_id = 11 AND course.group_id = ICC.group_id AND ICC.CoreAbilities_Index = '10' AND ICC.`ClassGoal_Index` =  '4' AND ICC.isChecked =  1" ;
if ( !($result = mysql_db_query( $DB, $Q4_1 ) ) ){
         $message = "$message - ��ƮwŪ�����~!!";
}
else{
$count4_1=0;
 while( $row4_1 = mysql_fetch_array( $result )){
        $j=0;

        for( $j=0;$j< count( $course_no_cor) ;$j++ ){
                if( $course_no_cor[$j]== $row4_1[0]  )
                {
                        //echo $row4_1[0]."<br>";
                        $count4_1 ++;
                }
        }
}echo "�`�@��".$count4_1."���ŦX 4.1����<BR>";

}
//�P�_�з� 4_1 : �ŦX���ҵ{�� / 4_1����з� $standard4_1
$standard4_1 = 1;
$score = $count4_1/ $standard4_1;
if($score >2) $score =2;
echo $score;
$sql= " UPDATE  `study`.`IEEE_Results` SET  `4_1` =  '$score' WHERE  `IEEE_Results`.`id` =  '$user_id'  and  `IEEE_Results`.`year` =  '$year'";
mysql_db_query("study",$sql) or die ("update �ɵo�Ϳ��~");


$Q4_2 = " SELECT DISTINCT  course.course_no,course.name FROM IEEE_CourseIntro_CoreAbilities ICC,course ,IEEE_CourseIntro IC WHERE ICC.`course_id` = course.a_id AND ICC.`course_id` = IC.`course_id` AND IC.group_id = 11 AND course.group_id = ICC.group_id AND ICC.CoreAbilities_Index = '11' AND ICC.`ClassGoal_Index` =  '4' AND ICC.isChecked =  1" ;
if ( !($result = mysql_db_query( $DB, $Q4_2 ) ) ){
         $message = "$message - ��ƮwŪ�����~!!";
}
else{
$count4_2=0;
 while( $row4_2 = mysql_fetch_array( $result )){
        $j=0;

        for( $j=0;$j< count( $course_no_cor) ;$j++ ){
                if( $course_no_cor[$j]== $row4_2[0]  )
                {
                        //echo $row4_2[0]."<br>";
                        $count4_2 ++;
                }
        }
}echo "�`�@��".$count4_2."���ŦX 4.2����<BR>";

}
//�P�_�з� 4_2 : �ŦX���ҵ{�� / 4_2����з� $standard4_2
$standard4_2 = 1;
$score = $count4_2/ $standard4_2;
if($score >2) $score =2;
echo $score;
$sql= " UPDATE  `study`.`IEEE_Results` SET  `4_2` =  '$score' WHERE  `IEEE_Results`.`id` =  '$user_id'  and  `IEEE_Results`.`year` =  '$year'";
mysql_db_query("study",$sql) or die ("update �ɵo�Ϳ��~");



?>