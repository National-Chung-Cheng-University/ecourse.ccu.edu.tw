<?php
/*********************************/
/* Author   : w60292             */
/* Lab      : HSNG@CSIE in CCU   */
/* Function : �إߨt�ο�檺��� */
/* Date     : 2009.09.29         */
/*********************************/

include "fadmin.php"
?>

<html>
<head>
<title>�إߨt�ο�����</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
</head>
<body background = "/images/img/bg.gif"><center>
<div><table>
    <tr>
      <td>
        <div>
          <font color=#000000>�}�l�إߨt�ο�����!!</font>
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

//�ҵ{��T
$Q1 = "alter table function_list add officehr char(1) not null default '0' after tein"; //�줽�Ǯɶ�
$Q2 = "alter table function_list add core char(1) not null default '0' after officehr"; //�ҵ{���[
$Q3 = "alter table function_list add evaluate char(1) not null default '0' after core"; //�ҵ{�۵�

//���Z�t��
$Q4 = "alter table function_list add warning char(1) not null default '1' after tgquery"; //���Z�wĵ
$Q4_1 = "alter table function_list add show_test_rank char(1) not null default '1' after warning"; //��ܾǥͤ@�����ƦW
$Q4_2 = "alter table function_list add show_onlinetest_rank char(1) not null default '1' after show_test_rank"; //��ܾǥͽu�W����ƦW
$Q4_3 = "alter table function_list add show_homework_rank char(1) not null default '1' after show_onlinetest_rank"; //��ܾǥͽu�W�@�~�ƦW
$Q4_4 = "alter table function_list add show_all_rank char(1) not null default '1' after show_homework_rank"; //��ܾǥ��`�ƦW

//�Q�װ�
$Q5 = "alter table function_list add reservation char(1) not null default '0' after discuss"; //�w�������줽��
$Q6 = "alter table function_list add recording char(1) not null default '0' after reservation"; //���v�ɺ޲z

//�ǲ߰l��
$Q7 = "alter table function_list add eroll char(1) not null default '0' after rollbook"; //�q�l�I�W

//�}�l�إ߸�Ʈw���
global $DB_SERVER, $DB_LOGIN, $DB_PASSWORD, $DB;

if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) 
{
	echo( "��Ʈw�s�����~!!" );
	exit;
}

//���o�Ҧ��ҵ{���X
$QA = "select * from course order by a_id";

if(!($result1 = mysql_db_query($DB,$QA)))
{
	$error = "mysql��ƮwŪ�����~!!";
        return;
}
$total = mysql_num_rows($result1);
echo "�`�@ $total ����<br>\n";
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
        	$error = "mysql��ƮwŪ�����~ at ".$course_id;
                echo $error." : ".$Q1;
		return;
	*/
	}
	if(!(mysql_db_query($studyXXX,$Q2)))
        {
	/*
                $error = "mysql��ƮwŪ�����~ at ".$course_id;
                echo $error." : ".$Q2;
		return;
	*/
        }
	if(!(mysql_db_query($studyXXX,$Q3)))
        {
	/*
                $error = "mysql��ƮwŪ�����~ at ".$course_id;
                echo $error." : ".$Q3;
		return;
	*/
        }
	if(!(mysql_db_query($studyXXX,$Q4)))
        {
	/*
                $error = "mysql��ƮwŪ�����~ at ".$course_id;
                echo $error." : ".$Q4;
		return;
	*/
        }
		
	if(!(mysql_db_query($studyXXX,$Q4_1)))
        {
	/*
                $error = "mysql��ƮwŪ�����~ at ".$course_id;
                echo $error." : ".$Q4;
		return;
	*/
        }
		
	if(!(mysql_db_query($studyXXX,$Q4_2)))
        {
	/*
                $error = "mysql��ƮwŪ�����~ at ".$course_id;
                echo $error." : ".$Q4;
		return;
	*/
        }
		
	if(!(mysql_db_query($studyXXX,$Q4_3)))
        {
	/*
                $error = "mysql��ƮwŪ�����~ at ".$course_id;
                echo $error." : ".$Q4;
		return;
	*/
        }
		
	if(!(mysql_db_query($studyXXX,$Q4_4)))
        {
	/*
                $error = "mysql��ƮwŪ�����~ at ".$course_id;
                echo $error." : ".$Q4;
		return;
	*/
        }
	
	if(!(mysql_db_query($studyXXX,$Q5)))
        {
	/*
                $error = "mysql��ƮwŪ�����~ at ".$course_id;
                echo $error." : ".$Q5;
		return;
	*/
        }
	if(!(mysql_db_query($studyXXX,$Q6)))
        {
	/*
                $error = "mysql��ƮwŪ�����~ at ".$course_id;
                echo $error." : ".$Q6;
		return;
	*/
        }
	if(!(mysql_db_query($studyXXX,$Q7)))
        {
	/*
                $error = "mysql��ƮwŪ�����~ at ".$course_id;
                echo $error." : ".$Q7;
		return;
	*/
        }
	echo "�ҵ{�s���G".$course_id."   ".$course_name."...�غc����<br>\n";
	echo "-----------------------------------------------------------<br>\n";
	
}

echo "<br>�t�ο���Ʈw��� �s�W����<br>\n";
?>
