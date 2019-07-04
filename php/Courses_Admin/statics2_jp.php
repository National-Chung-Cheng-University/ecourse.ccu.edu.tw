<?php
// 95/11/06 統計本學期課程教材上傳率 by julien Pi
// 暫時用的程式

$count_ttl=0; //總課程數
$count_yes=0; //已上傳教材之課程數

$DB_SERVER = "localhost";  //mysql主機IP
$DB_LOGIN = "study";            //資料庫帳號
$DB_PASSWORD = "2720411";
$DB = "study"; 

$link = mysql_pconnect($DB_SERVER, $DB_LOGIN , $DB_PASSWORD);

//本學期的所有開課
$qstr = "select distinct course_id from teach_course where year='95' and term='1'";
$rset = mysql_db_query( $DB, $qstr,$link );
while($row = mysql_fetch_array($rset))
{
	$count_ttl++;
	//若教材目錄不是空的，有檔案或目錄，則代表已上傳教材
	$dir="../../$row[course_id]/textbook";
	if( is_dir($dir)) {
		if ($dh = opendir($dir)) {
     		while(($file = readdir($dh)) !== false) {
     			if (strcmp($file,".") !=0 && strcmp($file,"..") !=0 && strcmp($file,"misc") !=0) {
     				echo $file."<br>";
         		$count_yes++;
         		break;
         	}
         }
         closedir($dh);
      }
      else
       	echo $dir." can't be opened!\n";
   }
   else
   	echo $dir." not exist!\n";
}

echo "95年第一學期 課程總數       ：".$count_ttl."<br>";
echo "95年第一學期已上傳教材課程數：".$count_yes."<br>";
echo "上傳比例﹦".sprintf("%.2f", ($count_yes/$count_ttl)*100)."%";       
 
?>