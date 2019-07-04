<?php
/*************************************/
/* Author  : w60292                  */
/* Lab     : HSNG@CSIE in CCU        */
/* Fuction : 同步訂閱討論區學生名單  */
/* Date    : 2009/09/28              */
/*************************************/
require 'fadmin.php';
?>

<html>
<head>
<title>同步討論區訂閱資料</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
</head>
<body background = "/images/img/bg.gif"><center>
<div><table>
    <tr>
      <td>
        <div>
          <font color=#000000>開始同步更新討論區學生訂閱名單!!</font>
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

if ( isset($PHPSESSID) && session_check_admin($PHPSESSID) ) 
{
	global $DB_SERVER, $DB_LOGIN, $DB,$DB_PASSWORD;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
                        $error = "資料庫連結錯誤!!";
			return;
        }

	$Q1 = "select * from course order by a_id";
	if(!($result1 = mysql_db_query($DB,$Q1)))
        {
                $error = "mysql資料庫讀取錯誤!!";
		return;
        }
        $total = mysql_num_rows($result1);
        //echo "總共 $total 門課<br>";
	ob_end_flush();
        ob_implicit_flush(1);

	$count = 0;
	$temp = -1;

	//取得本學期為 第幾年度、第幾學期
	$Q2 = "select * from this_semester";
        if(!($result2 = mysql_db_query($DB,$Q2)))
	{
		$error = "mysql資料庫讀取錯誤!!";
		return;
        }
        $row2 = mysql_fetch_array($result2);
        $course_year = $row2["year"];
        $course_term = $row2["term"];
	
	//echo "本學期為".$course_year."年度第".$course_term."學期<br>";

	//搜尋所有課號 
	while($row1 = mysql_fetch_array($result1))
	{	
		$ignore = -1;
		$course_id = $row1["a_id"];
		$course_name = $row1["name"];
		$i = 0;
		
		//取得該門課教師帳號
		$Q6 = "select u.id from user u, teach_course tc where tc.course_id = '$course_id' and tc.year = '$course_year' and tc.term = '$course_term' and tc.teacher_id = u.a_id and authorization = '1'";
		if(!($result6 = mysql_db_query($DB,$Q6)))
        	{
                	$error = "mysql資料庫讀取錯誤!!";
			$ignore = 0;
        	}
		$teacherNum = 0;
		while($row6 = mysql_fetch_array($result6))
		{
			$teach[$teacherNum] = $row6["id"];
			$teacherNum++;
		}

		//搜尋該課程學生名單
		$Q3 = "select u.id from user u, take_course tc where u.a_id=tc.student_id and tc.course_id='$course_id' and tc.credit = '1' and tc.year='$course_year' and tc.term = '$course_term' order by u.id";
		if(!($result3 = mysql_db_query($DB,$Q3)))
        	{
                	$error = "mysql資料庫讀取錯誤!!";
			$ignore = 1;
        	}
		while($row3 = mysql_fetch_array($result3))
		{
			$stu_id[$i] = $row3["id"];
			$i++;
		}
		
		//取得討論區訂閱名單
		$Q4 = "select distinct user_id from discuss_subscribe";
		$studyXXX = "study".$course_id;
		if(!($result4 = mysql_db_query($studyXXX,$Q4)))
        	{
                	$error = "mysql資料庫讀取錯誤!!";
			$ignore = 2;	
        	}
		if(mysql_num_rows($result4) != 0)
		{
			while($row4 = mysql_fetch_array($result4))
	                {
        	                $subscribe = $row4["user_id"];
				$tmp_count = 0;
				$flag = 0;
	
				//比對是否為本學期的學生
				while($tmp_count < $i)
				{
					if($subscribe == $stu_id[$tmp_count])
					{
						$flag = 1;
						break;
					}
					$tmp_count++;
				}

				//比對是否為本學期教師
				$tmp_count = 0;
				while($flag == 0 && $tmp_count < $teacherNum)
        	                {
                	                if($subscribe == $teach[$tmp_count])
                        	        {
	                                        $flag = 1;
        	                                break;
                	                }
                        	        $tmp_count++;
	                        }
				if($flag == 0)
				{
					$Q5 = "select distinct user_id from discuss_subscribe where user_id = '$subscribe'";
					if(!($result5 = mysql_db_query($studyXXX,$Q5)))
					{
						$error = "mysql資料庫讀取錯誤!!";
						$ignore = 3;
					}
					//刪除過期的訂閱資料
					while($row5 = mysql_fetch_array($result5))
					{
						$delestu = $row5["user_id"];
						echo "刪除學生 : ".$delestu."......";
						$Q7 = "delete from discuss_subscribe where user_id = '$delestu'";
						if(!($result7 = mysql_db_query($studyXXX,$Q7)))
						{
        	                			$error = "mysql資料庫讀取錯誤!!";
							$ignore = 4;
                				}
					}
				}
                	}
		}
		else
		{
			$ignore = 5;
		}
		$count++;
		$p = number_format((100*$count)/$total);
		if($p > $temp)
		{
                        echo "<script language=\"JavaScript\">document.all.progress.innerHTML = \"系統同步中，請稍侯 $p%\"; </script>";
                }
                $temp = $p;
		if($ignore < 0)
		{
			echo "課程編號：".$course_id."   ".$course_name."...更新完成<br>";
			echo "-----------------------------------------------------<br>";
		}
	}
	echo "學生訂閱討論區名單 同步完成<br>";

	echo "<br><a href=../check_admin.php>回系統管理介面</a></center></body>";
}
else
	show_page( "index_ad.tpl", "你的權限錯誤，請重新登入!!");
?>
