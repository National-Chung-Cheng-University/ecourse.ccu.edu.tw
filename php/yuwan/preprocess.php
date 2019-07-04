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
echo "年度:".$row['year']."<br>";
$year = $row['year'];
echo "ID=" . $user_id ."<br>";
$Q1 = "select name FROM user where id = '$user_id'";
if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
       $message = "$message - 資料庫讀取錯誤!!";
}else
	$row = mysql_fetch_array( $result );
       
$stu_name = $row['name']; 
echo " name: " .$stu_name."<br>";

//2011.10.25
//從外部檔案(*.csv檔列出該學生修課情形，存入$compulsory陣列)
//start
$grade = "學士班四年級";
$id = "497410001";
$name = "繆翔";
$compulsory_count=0;
$optional_count=0;
//$handle = fopen("./".$grade."-".$id."_".$name.".CSV", "r");
//$handle = fopen("./yuwan/應屆畢業生-496410005_侯松圳.CSV","r");
//$handle = fopen("./學士班四年級-497410030_林婷萱.CSV","r");
$handle = fopen("./497410031.CSV","r");
//$user_id ='497410031'; 
$com_start_year = 96;
$compulsory_num[0] = 27; //96入學必修課數
$compulsory_num[1] = 29; //97入學必修課數
$compulsory_num[2] = 29; //98入學必修課數
$compulsory_num[3] = 26; //99入學必修課數
$compulsory_num[4] = 26; //100入學必修課數

//判斷學生學級
$grade_year = 96;

//


if ($handle) {

    while (($buffer = fgets($handle, 4096)) !== false) { //一次取得一整行字
    		$school = explode(",", $buffer);         //用,切字串
		//echo $buffer . "<br/>";
		if ($school[0] == "專必" || $school[0] == "專選"){
			$compulsory[$compulsory_count] = $school[2];      //儲存科目名稱
			$course_no_cor[$compulsory_count] = $school[1].'_01';   //儲存科目代碼 (強制加入_01 表修A班的課 (與實際資料不符 但不影響核心能力勾選))
		 	echo "course_no: ". $course_no_cor[$compulsory_count]."X".$compulsory[$compulsory_count]."<br>";
			$compulsory_count++;

		}
		if ($school[18]== "專必")
		{
			$compulsory_grade_num = $school[19];
		}
		if ($school[5]=="通識")
		{
			$general_grade_num = $school[6];
		}
		if( ($school[3] == "專題實驗（一）" || $school[3] == "專題實驗(一)") && $school[17]>59)
		{
			$seminar_experiment_1 = 1;
		}
		if( ($school[3] == "專題實驗（二）" || $school[3] == "專題實驗(二)") && $school[17]>59)
                {
                        $seminar_experiment_2 = 1;
                }

	}
		//print_r ($compulsory)	;
		/*for ($i=0;$i<count($compulsory) ; $i++)
                {
                   echo $compulsory[$i]. "<br>";
                }
		echo "共".$i."堂必修課<br>"; 
		echo "累積專業必修學分: " .$compulsory_grade_num. "<br>";
		echo "累積通識教育學分: " .$general_grade_num. "<br>";
		*/
		 echo "***". $seminar_experiment_1 ." ". $seminar_experiment_2."<br>";

} 
//end

//若使用者不存在，將使用者資料建入資料庫IEEE_Results
//再根據每個核心能力計算出分數 update欄位

$Q = "SELECT  `id` FROM  `IEEE_Results` WHERE  `id` = '$user_id' and `year` = '$year'" ;
$result = mysql_db_query( $DB, $Q );

if(!$row = mysql_fetch_array( $result )){
	$sql= "INSERT INTO `study`.`IEEE_Results` (`id`, `year`, `1_1`, `1_2`, `1_3`, `2_1`, `2_2`, `2_3`, `3_1`, `3_2`, `3_3`, `4_1`, `4_2`) VALUES ('$user_id', '$year','0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');";
	mysql_db_query("study",$sql) or die ("insert 時發生錯誤");
}
//2011.10.27
//計算該學生在每個核心能力符合的堂數、所得到的分數

$Q1_1 = " SELECT DISTINCT course.course_no, course.name, IC.`Classification` FROM  `IEEE_CourseIntro_CoreAbilities` ICC ,  `course` ,  `IEEE_CourseIntro` IC WHERE IC.`Classification` =  '必修' AND ICC.`course_id` = course.a_id AND ICC.`course_id` = IC.`course_id` AND  ICC.`group_id` =11 " ;
if ( !($result = mysql_db_query( $DB, $Q1_1 ) ) ){
	 $message = "$message - 資料庫讀取錯誤!!";
	
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
	//單獨判斷微積分課程(不屬於資工系的開課範圍)
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

	echo "總共有".$count1_1."門符合1.1條件<BR>";
//
}
//判斷標準 1_1 : 符合的課程數 / 規定要修的必修課程數
$score = $count1_1/ $compulsory_num[$grade_year-$com_start_year];
$score = number_format($score, 2, '.', ''); 
if($score >2) $score =2;
echo $score;
$sql= " UPDATE  `study`.`IEEE_Results` SET  `1_1` =  '$score' WHERE  `IEEE_Results`.`id` =  '$user_id' and  `IEEE_Results`.`year` =  '$year'";
mysql_db_query("study",$sql) or die ("update 時發生錯誤");


//
$Q1_2 = " SELECT DISTINCT  course.course_no, course.name FROM IEEE_CourseIntro_CoreAbilities ICC,course ,IEEE_CourseIntro IC  WHERE ICC.`course_id` = course.a_id AND ICC.`course_id` = IC.`course_id` AND IC.group_id = 11 AND course.group_id = ICC.group_id AND ICC.CoreAbilities_Index = '2' AND ICC.`ClassGoal_Index` =  '1' AND ICC.isChecked =  1" ;
if ( !($result = mysql_db_query( $DB, $Q1_2 ) ) ){
         $message = "$message - 資料庫讀取錯誤!!";
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
}echo "總共有".$count1_2."門符合1.2條件<BR>";

}

//判斷標準 1_2 : 符合的課程數 / 1_2條件標準 $standard1_2
$standard1_2 = 16;
$score = $count1_2/ $standard1_2;
if($score >2) $score =2;

echo $score;
$sql= " UPDATE  `study`.`IEEE_Results` SET  `1_2` =  '$score' WHERE  `IEEE_Results`.`id` =  '$user_id' and  `IEEE_Results`.`year` =  '$year'";
mysql_db_query("study",$sql) or die ("update 時發生錯誤");



$Q1_3 = " SELECT DISTINCT  course.course_no, course.name FROM IEEE_CourseIntro_CoreAbilities ICC,course ,IEEE_CourseIntro IC WHERE ICC.`course_id` = course.a_id AND ICC.`course_id` = IC.`course_id` AND IC.group_id = 11 AND course.group_id = ICC.group_id AND ICC.CoreAbilities_Index = '12' AND ICC.`ClassGoal_Index` =  '1' AND ICC.isChecked =  1" ;
if ( !($result = mysql_db_query( $DB, $Q1_3 ) ) ){
         $message = "$message - 資料庫讀取錯誤!!";
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
}echo "總共有".$count1_3."門符合 1.3條件<BR>";

}
//判斷標準 1_3 : 符合的課程數 / 1_3條件標準 $standard1_3
$standard1_3 = 6;
$score = $count1_3/ $standard1_3;
if($score >2) $score =2;
echo $score;
$sql= " UPDATE  `study`.`IEEE_Results` SET  `1_3` =  '$score' WHERE  `IEEE_Results`.`id` =  '$user_id'  and  `IEEE_Results`.`year` =  '$year'";
mysql_db_query("study",$sql) or die ("update 時發生錯誤");



$Q2_1 = " SELECT DISTINCT  course.course_no, course.name FROM IEEE_CourseIntro_CoreAbilities ICC,course ,IEEE_CourseIntro IC WHERE ICC.`course_id` = course.a_id AND ICC.`course_id` = IC.`course_id` AND IC.group_id = 11 AND course.group_id = ICC.group_id AND ICC.CoreAbilities_Index = '3' AND ICC.`ClassGoal_Index` =  '2' AND ICC.isChecked =  1" ;
if ( !($result = mysql_db_query( $DB, $Q2_1 ) ) ){
         $message = "$message - 資料庫讀取錯誤!!";
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
}echo "總共有".$count2_1."門符合 2.1條件<BR>";

}

//判斷標準 2_1 : 符合的課程數 / 2_1條件標準 $standard2_1
$standard2_1 = 10;
$score = $count2_1/ $standard2_1;
if($score >2) $score =2;
echo $score;
$sql= " UPDATE  `study`.`IEEE_Results` SET  `2_1` =  '$score' WHERE  `IEEE_Results`.`id` =  '$user_id' and  `IEEE_Results`.`year` =  '$year'";
mysql_db_query("study",$sql) or die ("update 時發生錯誤");



$Q2_2 = " SELECT DISTINCT course.course_no, course.name FROM IEEE_CourseIntro_CoreAbilities ICC,course ,IEEE_CourseIntro IC WHERE ICC.`course_id` = course.a_id AND ICC.`course_id` = IC.`course_id` AND IC.group_id = 11 AND course.group_id = ICC.group_id AND ICC.CoreAbilities_Index = '4' AND ICC.`ClassGoal_Index` =  '2' AND ICC.isChecked =  1" ;
if ( !($result = mysql_db_query( $DB, $Q2_2 ) ) ){
         $message = "$message - 資料庫讀取錯誤!!";
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
}echo "總共有".$count2_2."門符合 2.2條件<BR>";

}

//判斷標準 2_2 : 符合的課程數 / 2_2條件標準 $standard2_2
$standard2_2 = 8;
$score = $count2_2/ $standard2_2;
if($score >2) $score =2;
echo $score;
$sql= " UPDATE  `study`.`IEEE_Results` SET  `2_2` =  '$score' WHERE  `IEEE_Results`.`id` =  '$user_id'  and  `IEEE_Results`.`year` =  '$year'";
mysql_db_query("study",$sql) or die ("update 時發生錯誤");




$Q2_3 = "SELECT DISTINCT  course.course_no, course.name FROM IEEE_CourseIntro_CoreAbilities ICC,course ,IEEE_CourseIntro IC WHERE ICC.`course_id` = course.a_id AND ICC.`course_id` = IC.`course_id` AND IC.group_id = 11 AND course.group_id = ICC.group_id AND ICC.CoreAbilities_Index = '5' AND ICC.`ClassGoal_Index` =  '2' AND ICC.isChecked =  1" ;
if ( !($result = mysql_db_query( $DB, $Q2_3 ) ) ){
         $message = "$message - 資料庫讀取錯誤!!";
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
}echo "總共有".$count2_3."門符合 2.3條件<BR>";

}

//判斷標準 2_3 : 符合的課程數 / 2_3條件標準 $standard2_3
$standard2_3 = 1;
$score = $count2_3/ $standard2_3;
if($score >2) $score =2;
echo $score;
$sql= " UPDATE  `study`.`IEEE_Results` SET  `2_3` =  '$score' WHERE  `IEEE_Results`.`id` =  '$user_id'  and  `IEEE_Results`.`year` =  '$year'";
mysql_db_query("study",$sql) or die ("update 時發生錯誤");




$Q3_1 = " SELECT DISTINCT  course.course_no,course.name FROM IEEE_CourseIntro_CoreAbilities ICC,course ,IEEE_CourseIntro IC WHERE ICC.`course_id` = course.a_id AND ICC.`course_id` = IC.`course_id` AND IC.group_id = 11 AND course.group_id = ICC.group_id AND ICC.CoreAbilities_Index = '7' AND ICC.`ClassGoal_Index` =  '3' AND ICC.isChecked =  1" ;
if ( !($result = mysql_db_query( $DB, $Q3_1 ) ) ){
         $message = "$message - 資料庫讀取錯誤!!";
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
}echo "總共有".$count3_1."門符合 3.1條件<BR>";

}

//判斷標準 3_1 : 符合的課程數 / 3_1條件標準 $standard3_1
$standard3_1 = 1;
$score = $count3_1/ $standard3_1;
if($score >2) $score =2;
echo $score;
$sql= " UPDATE  `study`.`IEEE_Results` SET  `3_1` =  '$score' WHERE  `IEEE_Results`.`id` =  '$user_id'  and  `IEEE_Results`.`year` =  '$year'";
mysql_db_query("study",$sql) or die ("update 時發生錯誤");


$Q3_2 = " SELECT DISTINCT  course.course_no,course.name FROM IEEE_CourseIntro_CoreAbilities ICC,course ,IEEE_CourseIntro IC WHERE ICC.`course_id` = course.a_id AND ICC.`course_id` = IC.`course_id` AND IC.group_id = 11 AND course.group_id = ICC.group_id AND ICC.CoreAbilities_Index = '8' AND ICC.`ClassGoal_Index` =  '3' AND ICC.isChecked =  1" ;
if ( !($result = mysql_db_query( $DB, $Q3_2 ) ) ){
         $message = "$message - 資料庫讀取錯誤!!";
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
}echo "總共有".$count3_2."門符合 3.2條件<BR>";

}
//判斷標準 3_2 : 符合的課程數 / 3_2條件標準 $standard3_2
$standard3_2 = 1;
$score = $count3_2/ $standard3_2;
if($score >2) $score =2;
echo $score;
$sql= " UPDATE  `study`.`IEEE_Results` SET  `3_2` =  '$score' WHERE  `IEEE_Results`.`id` =  '$user_id'  and  `IEEE_Results`.`year` =  '$year'";
mysql_db_query("study",$sql) or die ("update 時發生錯誤");


$Q3_3 = " SELECT DISTINCT  course.course_no,course.name FROM IEEE_CourseIntro_CoreAbilities ICC,course ,IEEE_CourseIntro IC WHERE ICC.`course_id` = course.a_id AND ICC.`course_id` = IC.`course_id` AND IC.group_id = 11 AND course.group_id = ICC.group_id AND ICC.CoreAbilities_Index = '9' AND ICC.`ClassGoal_Index` =  '3' AND ICC.isChecked =  1" ;
if ( !($result = mysql_db_query( $DB, $Q3_3 ) ) ){
         $message = "$message - 資料庫讀取錯誤!!";
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
}echo "總共有".$count3_3."門符合 3.3條件<BR>";

}
//判斷標準 3_3 : 符合的課程數 / 3_3條件標準 $standard3_3
$standard3_3 = 1;
$score = $count3_3/ $standard3_3;
if($score >2) $score =2;
echo $score;
$sql= " UPDATE  `study`.`IEEE_Results` SET  `3_3` =  '$score' WHERE  `IEEE_Results`.`id` =  '$user_id'  and  `IEEE_Results`.`year` =  '$year'";
mysql_db_query("study",$sql) or die ("update 時發生錯誤");


$Q4_1 = " SELECT DISTINCT  course.course_no,course.name FROM IEEE_CourseIntro_CoreAbilities ICC,course ,IEEE_CourseIntro IC WHERE ICC.`course_id` = course.a_id AND ICC.`course_id` = IC.`course_id` AND IC.group_id = 11 AND course.group_id = ICC.group_id AND ICC.CoreAbilities_Index = '10' AND ICC.`ClassGoal_Index` =  '4' AND ICC.isChecked =  1" ;
if ( !($result = mysql_db_query( $DB, $Q4_1 ) ) ){
         $message = "$message - 資料庫讀取錯誤!!";
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
}echo "總共有".$count4_1."門符合 4.1條件<BR>";

}
//判斷標準 4_1 : 符合的課程數 / 4_1條件標準 $standard4_1
$standard4_1 = 1;
$score = $count4_1/ $standard4_1;
if($score >2) $score =2;
echo $score;
$sql= " UPDATE  `study`.`IEEE_Results` SET  `4_1` =  '$score' WHERE  `IEEE_Results`.`id` =  '$user_id'  and  `IEEE_Results`.`year` =  '$year'";
mysql_db_query("study",$sql) or die ("update 時發生錯誤");


$Q4_2 = " SELECT DISTINCT  course.course_no,course.name FROM IEEE_CourseIntro_CoreAbilities ICC,course ,IEEE_CourseIntro IC WHERE ICC.`course_id` = course.a_id AND ICC.`course_id` = IC.`course_id` AND IC.group_id = 11 AND course.group_id = ICC.group_id AND ICC.CoreAbilities_Index = '11' AND ICC.`ClassGoal_Index` =  '4' AND ICC.isChecked =  1" ;
if ( !($result = mysql_db_query( $DB, $Q4_2 ) ) ){
         $message = "$message - 資料庫讀取錯誤!!";
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
}echo "總共有".$count4_2."門符合 4.2條件<BR>";

}
//判斷標準 4_2 : 符合的課程數 / 4_2條件標準 $standard4_2
$standard4_2 = 1;
$score = $count4_2/ $standard4_2;
if($score >2) $score =2;
echo $score;
$sql= " UPDATE  `study`.`IEEE_Results` SET  `4_2` =  '$score' WHERE  `IEEE_Results`.`id` =  '$user_id'  and  `IEEE_Results`.`year` =  '$year'";
mysql_db_query("study",$sql) or die ("update 時發生錯誤");



?>
