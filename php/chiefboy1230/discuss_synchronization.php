<?php
/*************************************/
/* Author  : w60292                  */
/* Lab     : HSNG@CSIE in CCU        */
/* Fuction : �P�B�q�\�Q�װϾǥͦW��  */
/* Date    : 2009/09/28              */
/*************************************/
require 'fadmin.php';
?>

<html>
<head>
<title>�P�B�Q�װϭq�\���</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
</head>
<body background = "/images/img/bg.gif"><center>
<div><table>
    <tr>
      <td>
        <div>
          <font color=#000000>�}�l�P�B��s�Q�װϾǥͭq�\�W��!!</font>
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
                        $error = "��Ʈw�s�����~!!";
			return;
        }

	$Q1 = "select * from course order by a_id";
	if(!($result1 = mysql_db_query($DB,$Q1)))
        {
                $error = "mysql��ƮwŪ�����~!!";
		return;
        }
        $total = mysql_num_rows($result1);
        //echo "�`�@ $total ����<br>";
	ob_end_flush();
        ob_implicit_flush(1);

	$count = 0;
	$temp = -1;

	//���o���Ǵ��� �ĴX�~�סB�ĴX�Ǵ�
	$Q2 = "select * from this_semester";
        if(!($result2 = mysql_db_query($DB,$Q2)))
	{
		$error = "mysql��ƮwŪ�����~!!";
		return;
        }
        $row2 = mysql_fetch_array($result2);
        $course_year = $row2["year"];
        $course_term = $row2["term"];
	
	//echo "���Ǵ���".$course_year."�~�ײ�".$course_term."�Ǵ�<br>";

	//�j�M�Ҧ��Ҹ� 
	while($row1 = mysql_fetch_array($result1))
	{	
		$ignore = -1;
		$course_id = $row1["a_id"];
		$course_name = $row1["name"];
		$i = 0;
		
		//���o�Ӫ��ұЮv�b��
		$Q6 = "select u.id from user u, teach_course tc where tc.course_id = '$course_id' and tc.year = '$course_year' and tc.term = '$course_term' and tc.teacher_id = u.a_id and authorization = '1'";
		if(!($result6 = mysql_db_query($DB,$Q6)))
        	{
                	$error = "mysql��ƮwŪ�����~!!";
			$ignore = 0;
        	}
		$teacherNum = 0;
		while($row6 = mysql_fetch_array($result6))
		{
			$teach[$teacherNum] = $row6["id"];
			$teacherNum++;
		}

		//�j�M�ӽҵ{�ǥͦW��
		$Q3 = "select u.id from user u, take_course tc where u.a_id=tc.student_id and tc.course_id='$course_id' and tc.credit = '1' and tc.year='$course_year' and tc.term = '$course_term' order by u.id";
		if(!($result3 = mysql_db_query($DB,$Q3)))
        	{
                	$error = "mysql��ƮwŪ�����~!!";
			$ignore = 1;
        	}
		while($row3 = mysql_fetch_array($result3))
		{
			$stu_id[$i] = $row3["id"];
			$i++;
		}
		
		//���o�Q�װϭq�\�W��
		$Q4 = "select distinct user_id from discuss_subscribe";
		$studyXXX = "study".$course_id;
		if(!($result4 = mysql_db_query($studyXXX,$Q4)))
        	{
                	$error = "mysql��ƮwŪ�����~!!";
			$ignore = 2;	
        	}
		if(mysql_num_rows($result4) != 0)
		{
			while($row4 = mysql_fetch_array($result4))
	                {
        	                $subscribe = $row4["user_id"];
				$tmp_count = 0;
				$flag = 0;
	
				//���O�_�����Ǵ����ǥ�
				while($tmp_count < $i)
				{
					if($subscribe == $stu_id[$tmp_count])
					{
						$flag = 1;
						break;
					}
					$tmp_count++;
				}

				//���O�_�����Ǵ��Юv
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
						$error = "mysql��ƮwŪ�����~!!";
						$ignore = 3;
					}
					//�R���L�����q�\���
					while($row5 = mysql_fetch_array($result5))
					{
						$delestu = $row5["user_id"];
						echo "�R���ǥ� : ".$delestu."......";
						$Q7 = "delete from discuss_subscribe where user_id = '$delestu'";
						if(!($result7 = mysql_db_query($studyXXX,$Q7)))
						{
        	                			$error = "mysql��ƮwŪ�����~!!";
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
                        echo "<script language=\"JavaScript\">document.all.progress.innerHTML = \"�t�ΦP�B���A�еy�J $p%\"; </script>";
                }
                $temp = $p;
		if($ignore < 0)
		{
			echo "�ҵ{�s���G".$course_id."   ".$course_name."...��s����<br>";
			echo "-----------------------------------------------------<br>";
		}
	}
	echo "�ǥͭq�\�Q�װϦW�� �P�B����<br>";

	echo "<br><a href=../check_admin.php>�^�t�κ޲z����</a></center></body>";
}
else
	show_page( "index_ad.tpl", "�A���v�����~�A�Э��s�n�J!!");
?>
