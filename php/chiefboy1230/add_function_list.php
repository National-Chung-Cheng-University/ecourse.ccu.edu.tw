<?php
/*********************************/
/* Author   : w60292             */
/* Lab      : HSNG@CSIE in CCU   */
/* Function : 建立系統選單的欄位 */
/* Date     : 2009.09.29         */
/*********************************/

include "fadmin.php"
?>

<html>
<head>
<title>建立系統選單欄位</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
</head>
<body background = "/images/img/bg.gif"><center>
<div><table>
    <tr>
      <td>
        <div>
          <font color=#000000>開始建立系統選單欄位!!</font>
        </div>
      </td>
    </tr>
</table>
<div id="progress">
</div>
<div>
<br>
</div>

<?php

//課程資訊
$Q1 = "alter table function_list add officehr char(1) not null default '0' after tein"; //辦公室時間
$Q2 = "alter table function_list add core char(1) not null default '0' after officehr"; //課程內涵
$Q3 = "alter table function_list add evaluate char(1) not null default '0' after core"; //課程自評

//成績系統
$Q4 = "alter table function_list add warning char(1) not null default '1' after tgquery"; //成績預警
$Q4_1 = "alter table function_list add show_test_rank char(1) not null default '1' after warning"; //顯示學生一般測驗排名
$Q4_2 = "alter table function_list add show_onlinetest_rank char(1) not null default '1' after show_test_rank"; //顯示學生線上測驗排名
$Q4_3 = "alter table function_list add show_homework_rank char(1) not null default '1' after show_onlinetest_rank"; //顯示學生線上作業排名
$Q4_4 = "alter table function_list add show_all_rank char(1) not null default '1' after show_homework_rank"; //顯示學生總排名

//討論區
$Q5 = "alter table function_list add reservation char(1) not null default '0' after discuss"; //預約網路辦公室
$Q6 = "alter table function_list add recording char(1) not null default '0' after reservation"; //錄影檔管理

//學習追蹤
$Q7 = "alter table function_list add eroll char(1) not null default '0' after rollbook"; //電子點名

//開始建立資料庫欄位
global $DB_SERVER, $DB_LOGIN, $DB_PASSWORD, $DB;

if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) 
{
	echo( "資料庫連結錯誤!!" );
	exit;
}

//取得所有課程號碼
$QA = "select * from course order by a_id";

if(!($result1 = mysql_db_query($DB,$QA)))
{
	$error = "mysql資料庫讀取錯誤!!";
        return;
}
$total = mysql_num_rows($result1);
echo "總共 $total 門課<br>\n";
ob_end_flush();
ob_implicit_flush(1);

$count = 0;
$temp = -1;

while($row1 = mysql_fetch_array($result1))
{
	$course_id = $row1["a_id"];
    $course_name = $row1["name"];
	$studyXXX = "study".$course_id;
	
	if(!(mysql_db_query($studyXXX,$Q1)))
        {
	/*
        	$error = "mysql資料庫讀取錯誤 at ".$course_id;
                echo $error." : ".$Q1;
		return;
	*/
	}
	if(!(mysql_db_query($studyXXX,$Q2)))
        {
	/*
                $error = "mysql資料庫讀取錯誤 at ".$course_id;
                echo $error." : ".$Q2;
		return;
	*/
        }
	if(!(mysql_db_query($studyXXX,$Q3)))
        {
	/*
                $error = "mysql資料庫讀取錯誤 at ".$course_id;
                echo $error." : ".$Q3;
		return;
	*/
        }
	if(!(mysql_db_query($studyXXX,$Q4)))
        {
	/*
                $error = "mysql資料庫讀取錯誤 at ".$course_id;
                echo $error." : ".$Q4;
		return;
	*/
        }
		
	if(!(mysql_db_query($studyXXX,$Q4_1)))
        {
	/*
                $error = "mysql資料庫讀取錯誤 at ".$course_id;
                echo $error." : ".$Q4;
		return;
	*/
        }
		
	if(!(mysql_db_query($studyXXX,$Q4_2)))
        {
	/*
                $error = "mysql資料庫讀取錯誤 at ".$course_id;
                echo $error." : ".$Q4;
		return;
	*/
        }
		
	if(!(mysql_db_query($studyXXX,$Q4_3)))
        {
	/*
                $error = "mysql資料庫讀取錯誤 at ".$course_id;
                echo $error." : ".$Q4;
		return;
	*/
        }
		
	if(!(mysql_db_query($studyXXX,$Q4_4)))
        {
	/*
                $error = "mysql資料庫讀取錯誤 at ".$course_id;
                echo $error." : ".$Q4;
		return;
	*/
        }
	
	if(!(mysql_db_query($studyXXX,$Q5)))
        {
	/*
                $error = "mysql資料庫讀取錯誤 at ".$course_id;
                echo $error." : ".$Q5;
		return;
	*/
        }
	if(!(mysql_db_query($studyXXX,$Q6)))
        {
	/*
                $error = "mysql資料庫讀取錯誤 at ".$course_id;
                echo $error." : ".$Q6;
		return;
	*/
        }
	if(!(mysql_db_query($studyXXX,$Q7)))
        {
	/*
                $error = "mysql資料庫讀取錯誤 at ".$course_id;
                echo $error." : ".$Q7;
		return;
	*/
        }
	echo "課程編號：".$course_id."   ".$course_name."...建構完成<br>\n";
	echo "-----------------------------------------------------------<br>\n";
	
}

echo "<br>系統選單資料庫欄位 新增完成<br>\n";
?>
