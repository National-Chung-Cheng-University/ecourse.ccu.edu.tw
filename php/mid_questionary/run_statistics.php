<?php
//devon 2005-11-07
//一次把期中問卷的結果(在每個課程資料庫裡的mid_ans裡)都塞進study裡面的mid_statistic這個table
//require 'fadmin.php';
/*$DB_SERVER = "localhost";
$DB_LOGIN = "study";
$DB_PASSWORD = "2720411";
$DB = "study";*/
require 'fadmin.php';
global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;

if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
	show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
}

$Q0 = "select distinct course_id as a_id, this_semester.year, this_semester.term from teach_course, this_semester where teach_course.year=this_semester.year and teach_course.term=this_semester.term order by course_id";
$result0 = mysql_db_query($DB, $Q0);

while($rows0 = mysql_fetch_array($result0)) // start of while_1
{
	$Q1 = "select * from mid_ans where year='".$rows0['year']."' and term='".$rows0['term']."'";
	if ( !($result = mysql_db_query( $DB.$rows0['a_id'], $Q1 ) ) )
	{
		echo $rows0['a_id']."課程沒有mid_ans這個table<br>";
		continue;
	}
	if ( mysql_num_rows($result) != 0 ) // start of if_1
	{
		$Q1 = "SELECT mq.a_id, mq.q_id, mq.type, mq.question FROM mid_question mq, mid_subject ms WHERE ms.year='".$rows0['year']."' and ms.term='".$rows0['term']."' and mq.q_id=ms.a_id and mq.type='3'";
		if ( !($result1 = mysql_db_query( $DB, $Q1 ) ) )
		{
			show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
		}
		if ( mysql_num_rows($result1) != 0 ) // start of if_2
		{
			$qcounter = 0;
			$rows1 = mysql_fetch_array($result1);

			$Q2 = "SELECT a_id, q_id, type, question, selection1, selection2, selection3, selection4, selection5 FROM mid_question WHERE q_id='".$rows1['q_id']."' and block_id='".$rows1['a_id']."' and type != '3' order by a_id";
			if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) )
			{
				echo $Q2."<br>";
			}
			$k=0;	//為了得知第幾題而設的變數 ex:k=1代表第1小題..and so on..
			
			while ( $rows2 = mysql_fetch_array($result2) ) // start of while_2
			{
				$k++;
				$qcounter ++;$id_counter++;
				if ( $rows2['type'] == "1" )	//type=1代表選擇題 // start of if_3
				{
					$sum1=0;	//非常滿意
					$sum2=0;	//很滿意
					$sum3=0;	//普通
					$sum4=0;	//尚可
					$sum5=0;	//不滿意
					
					$S1 = "select q$k from mid_ans";
					$results1 = mysql_db_query($DB.$rows0['a_id'], $S1);
					while( $s1 = mysql_fetch_array($results1))
					{
						if($s1["q$k"] == 1)
							$sum1++;
						else if($s1["q$k"] == 2)
							$sum2++;
						else if($s1["q$k"] ==3)
							$sum3++;
						else if($s1["q$k"] ==4)
							$sum4++;
						else if($s1["q$k"] ==5)
							$sum5++;
					}
					$weight_sum = $sum1*5 + $sum2*4 + $sum3*3 + $sum4*2 + $sum5*1;	//算滿意度用
					$fill_counter = $sum1 + $sum2 + $sum3 + $sum4 + $sum5;			//總填寫人數
					if($fill_counter == 0)
					{
						echo "<font size=\"5\" color=\"red\">".$rows0['a_id']."</font>目前尚未有學生填寫期中問卷!<br>";
						continue;
					}
					else // start of else
					{
						$Q5 = "select * from take_course where course_id='".$rows0['a_id']."' and year='".$rows0['year']."' and term='".$rows0['term']."' and credit='1'";
						$result5 = mysql_db_query($DB, $Q5);
						$nums = mysql_num_rows($result5);
						
						$avg_weight = $weight_sum / $fill_counter;
						$avg_weight_sec = number_format($avg_weight, 2); //number_format是為了取到小數以下第二位，取完後為一個string
						$percent = ( $avg_weight/5 ) * 100;
						$percent_sec = number_format($percent, 2); //number_format是為了取到小數以下第二位，取完後為一個string
						$Q3 = " SELECT * FROM mid_statistic WHERE course_no = '".$rows0['a_id']."' ";
						$result3 = mysql_db_query ( $DB, $Q3 );
						if ( mysql_num_rows ($result3) !=0 )
						{
							$Q4 = " UPDATE mid_statistic SET fill_count = '$fill_counter', satisfy = '$avg_weight_sec' WHERE course_no='".$rows0['a_id']."' ";
						}
						else
						{
							$Q4 = " INSERT INTO mid_statistic ( course_no, q_id, fill_count, satisfy ) VALUES ( '$rows0[a_id]','$rows2[q_id]', '$fill_counter', '$avg_weight_sec' ) ";
						}
						if ( !($result4 = mysql_db_query( $DB, $Q4 ) ) )
						{
							echo $rows0['a_id']." -- ".$Q4."<br>";
						}
					} // end of else
				} // end of if_3
			} // end of while_2
		} // end of if_2
	} // end of if_1
	//echo "~~~~~~~~~~~~~~".$rows0['a_id']."此課程期中問卷結果已塞進study的mid_subject裡面了!!~~~~~~~~~~~~~~<br>";
} // end of while_1

/************************************************************/
     /*
     ###############################################
     ####                                       ####
     ####    Author : devon			####
     ####    Date   : 13 Apr,2006               ####
     ####    Updated:                           ####
     ####                                       ####
     ###############################################

     */
	 
	 /*
	 *將所有系所開的課程都寫在同一個Excel檔，然後照 學院->系所 建立資料夾
	 *最後壓縮起來，讓教務處之人得以下載。
	 */

include("excelwriter.inc.php");

$Q0 = "select * from this_semester";
$result0 = mysql_db_query($DB, $Q0);
$rows0 = mysql_fetch_array($result0);

$Q1 = "select a_id from mid_subject where year='".$rows0['year']."' and term='".$rows0['term']."'";
$result1 = mysql_db_query($DB, $Q1);
$rows1 = mysql_fetch_array($result1);

if(!(is_dir("./".$rows0['year']."_0".$rows0['term']."期中問卷")))
	mkdir("./".$rows0['year']."_0".$rows0['term']."期中問卷");
//選學院
$Q6 = "select a_id, name from course_group where parent_id=1 and a_id!=98";
$result6 = mysql_db_query($DB, $Q6);
while($rows6 = mysql_fetch_array($result6))
{
	if(!(is_dir("./".$rows0['year']."_0".$rows0['term']."期中問卷/".$rows6[name])))
		mkdir("./".$rows0['year']."_0".$rows0['term']."期中問卷/".$rows6[name]);
	//選系所
	$Q7 = "select a_id, name from course_group where parent_id='".$rows6[a_id]."' and a_id!=92 order by name";
	$result7 = mysql_db_query($DB, $Q7);
	while($rows7 = mysql_fetch_array($result7))
	{
		if(is_file("./".$rows0['year']."_0".$rows0['term']."期中問卷/".$rows6[name]."/".$rows0['year']."_0".$rows0['term']."_".$rows7[name].".xls"))
			unlink("./".$rows0['year']."_0".$rows0['term']."期中問卷/".$rows6[name]."/".$rows0['year']."_0".$rows0['term']."_".$rows7[name].".xls");
		$excel=new ExcelWriter("./".$rows0['year']."_0".$rows0['term']."期中問卷/".$rows6[name]."/".$rows0['year']."_0".$rows0['term']."_".$rows7[name].".xls");
		if($excel==false)
			echo $excel->error;
		$data=array("系所","課程編號","課程名稱","授課教師","課程滿意度","課程滿意度百分比","修課人數","填寫人數","填寫率");
		$excel->writeLine($data);
		//選課程
		$Q8 = "select distinct c.a_id, c.name, c.course_no, ts.year, ts.term
			   from course c, teach_course tc, this_semester ts
			   where c.group_id='$rows7[a_id]'
					 and c.a_id=tc.course_id
					 and tc.year=ts.year
					 and tc.term=ts.term";
		$result8 = mysql_db_query($DB, $Q8);
		while($rows8 = mysql_fetch_array($result8))
		{
			$excel->writeRow();
			$excel->writeCol($rows7[name]);			//系所
			$excel->writeCol($rows8[course_no]);	//課程編號
			$excel->writeCol($rows8[name]);			//課程名稱
			
			$Q9 = "select distinct u.name FROM user u , teach_course tc where tc.course_id = '".$rows8['a_id']."' and tc.teacher_id = u.a_id and u.authorization='1' and tc.year='".$rows0['year']."' and tc.term='".$rows0['term']."'";
			if ( !($result9 = mysql_db_query( $DB, $Q9 ) ) ) {
				$message = "$message - 資料庫讀取錯誤9!!";
			}
			$name="";
			while ($rows9 = mysql_fetch_array($result9))
			{
				if ( $rows9['name'] != NULL )
				{
					//寫入檔案用的授課教師變數:$nameforsave
					$name = $name.$rows9['name']." ";
				}
			}
			$excel->writeCol($name);				//授課教師
			
			$Q10 = "SELECT * FROM mid_statistic WHERE course_no = '".$rows8['a_id']."' and year='".$rows0['year']."' and term='".$rows0['term']."' and q_id='".$rows1['a_id']."'";
			if ( !($result10 = mysql_db_query( $DB, $Q10 ) ) ) {
				show_page( "not_access.tpl" ,"資料庫讀取錯誤10!!" );
			}
			$rows10 = mysql_fetch_array( $result10 );
			if ($rows10['satisfy'] == null || $rows10['satisfy'] == "")
			{
				$satisfy = "學生未填寫";	//寫入檔案用的課程滿意度變數:$satisfy
				$percentsec = "未有百分比";	//寫入檔案用的課程滿意度百分比變數:$percentsec
			}
			else
			{
				$satisfy = $rows10['satisfy'];	//寫入檔案用的課程滿意度變數:$satisfy
				$percent = ( $rows10['satisfy'] / 5 ) * 100;
				$percentsec = number_format($percent, 2);		//寫入檔案用的課程滿意度百分比變數:$percentsec
			}
			$excel->writeCol($satisfy);				//課程滿意度
			$excel->writeCol($percentsec);			//課程滿意度百分比
			
			$Q11 = "select * from take_course where course_id='".$rows8['a_id']."' and year='".." and credit='1'";
			$result11 = mysql_db_query($DB, $Q11);
			$nums = mysql_num_rows($result11);		//寫入檔案用的修課人數變數:$nums
			$excel->writeCol($nums);				//修課人數
			
			if($rows10['fill_count'] == "" || $rows10['fill_count'] == "NULL" )
			{
				//寫入檔案用的填寫人數變數:$filled
				$filled = "0";
				//寫入檔案用的填寫率變數:$filledpercentsec
				$filledpercentsec = "未有人填寫";
			}
			else
			{
				//寫入檔案用的填寫人數變數:$filled
				$filled = $rows10['fill_count'];
				$filled_percent = ( $rows10['fill_count'] / $nums ) * 100;
				//寫入檔案用的填寫率變數:$filperpercentsec
				$filledpercentsec = number_format($filled_percent,2);
			}
			$excel->writeCol($filled);				//填寫人數
			$excel->writeCol($filledpercentsec);	//填寫率
			
			//echo "課程：".$rows8['name']." data is write into ".$rows6[name]."/".$rows0['year']."_0".$rows0['term']."_".$rows7[name].".xls Successfully.<br>";
		}	//end of 選課程
		$excel->close();
	} //end of 選系所
} //end of 選學院
/*
*壓縮成當下學期期中問卷的tar檔
*/
$location1 = $rows0['year']."_0".$rows0['term']."期中問卷";
$location2 = $rows0['year']."_0".$rows0['term'];
exec("tar -cvf $location2.tar $location1/*");
echo "<html>
	  <title>下載期中問卷結果</title>
	  <body>
	  <center>
	  <a href=./onoff_questionary.php>回上一頁</a>
	  <br><hr>
      <a href=./".$location2.".tar>點此下載壓縮檔</a>
	  </center>
	  </body>
	  </html>";
?>
