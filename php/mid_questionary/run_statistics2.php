<?php
/** 功能：期中問卷統計結果報表，修改
  * 說明：
  * by julien 2006.11.23
  * update:
  */
/*$DB_SERVER = "localhost";
$DB_LOGIN = "study";
$DB_PASSWORD = "2720411";
$DB = "study";*/
require 'fadmin.php';
global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;

if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
	show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
}

	 
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
$Q6 = "select a_id, name from course_group where parent_id=1 and a_id!=98"; //98為測試用系所
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
			unlink("./".$rows0['year']."_0".$rows0['term']."期中問卷/".$rows6[name]."/".$rows0['year']."_0".$rows0['term']."_".$rows7[name].".xls");//若已有檔案則刪除
		$excel=new ExcelWriter("./".$rows0['year']."_0".$rows0['term']."期中問卷/".$rows6[name]."/".$rows0['year']."_0".$rows0['term']."_".$rows7[name].".xls");
		if($excel==false)
			echo $excel->error;
		$data=array("系所","課程編號","課程名稱","授課教師","修課人數","填寫人數","填寫率","問題一","問題二");
		$excel->writeLine($data);
		
		$Q8 = "select distinct c.a_id, c.name, c.course_no, ts.year, ts.term
			   from course c, teach_course tc, this_semester ts
			   where c.group_id='$rows7[a_id]'
					 and c.a_id=tc.course_id
					 and tc.year=ts.year
					 and tc.term=ts.term";
		$result8 = mysql_db_query($DB, $Q8);
		while($rows8 = mysql_fetch_array($result8)) //選課程 while($row8)
		{			
			//--授課教師(1~多位)
			$name="";
			$Q9 = "select distinct u.name FROM user u , teach_course tc where tc.course_id = '".$rows8['a_id']."' and tc.teacher_id = u.a_id and u.authorization='1' and tc.year='".$rows0['year']."' and tc.term='".$rows0['term']."'";
			if ( !($result9 = mysql_db_query( $DB, $Q9 ) ) ) {
				$message = "$message - 資料庫讀取錯誤9!!";
			}
			while ($rows9 = mysql_fetch_array($result9))
			{
				if ( $rows9['name'] != NULL )
				{
					$name = $name.$rows9['name']." ";
				}
			}
			
			//修課人數
			$stu_no=0;
			$Q_tmp="select count(tc.student_id) as stu_no from take_course tc, user u where tc.student_id=u.a_id and u.disable='0' and tc.course_id=$rows8[a_id] and year='".$rows0['year']."' and term='".$rows0['term']."'";				
			if ( !($rs_temp = mysql_db_query( $DB, $Q_tmp ) ) ) {
				$message = "$message - 資料庫讀取錯誤-修課人數!!";
			}
			if($rw_tmp = mysql_fetch_array($rs_temp))
			{
				$stu_no = $rw_tmp['stu_no'];				
			}
			
			//填寫人數
			$join_no=0;
			$Q_tmp="select count(student_id) as join_no FROM mid_ans where year=$rows8[year] and term='$rows8[term]'";				
			if ( !($rs_temp = mysql_db_query( $DB.$rows8[a_id], $Q_tmp ) ) ) {
				$message = "$message - 資料庫讀取錯誤-填寫人數!!";
			}
			if($rw_tmp = mysql_fetch_array($rs_temp))
			{
				$join_no = $rw_tmp['join_no'];				
			}
			//填寫率
			$ratio1=0;
			if ($stu_no!=0)
				$ratio1=number_format((($join_no/$stu_no)*100),2); 
			
			//讀取該課程的問卷結果，逐筆寫入excel檔案
			$Q10 = "SELECT q1,q2 FROM mid_ans where year=$rows8[year] and term='$rows8[term]'";
			
			if ( !($result10 = mysql_db_query( $DB.$rows8[a_id], $Q10 ) ) ) {
				show_page( "not_access.tpl" ,"資料庫讀取錯誤10!!" );
			}
			//----當該課程無人填寫問卷
			if(!mysql_num_rows($result10)) 
			{
				$excel->writeRow();
				$excel->writeCol($rows7[name]);			//系所
				$excel->writeCol($rows8[course_no]);	//課程編號
				$excel->writeCol($rows8[name]);			//課程名稱
				$excel->writeCol($name);					//授課教師
				$excel->writeCol($stu_no);					//修課人數
				$excel->writeCol($join_no);				//填寫人數
				$excel->writeCol($ratio1);					//填寫率
				$excel->writeCol("");						//問題一
				$excel->writeCol("");						//問題二
			}	
			//----當該課程有問卷填寫資料
			else {
				while($rows10 = mysql_fetch_array( $result10 ) )
				{
					$excel->writeRow();
					$excel->writeCol($rows7[name]);			//系所
					$excel->writeCol($rows8[course_no]);	//課程編號
					$excel->writeCol($rows8[name]);			//課程名稱
					$excel->writeCol($name);					//授課教師
					$excel->writeCol($stu_no);					//修課人數
					$excel->writeCol($join_no);				//填寫人數
					$excel->writeCol($ratio1);					//填寫率
					$excel->writeCol($rows10[q1]);			//問題一
					$excel->writeCol($rows10[q2]);			//問題二				
				}
			}		
		}	//end of 選課程 while($row8)
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
