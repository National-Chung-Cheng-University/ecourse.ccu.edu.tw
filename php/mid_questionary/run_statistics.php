<?php
//devon 2005-11-07
//�@��������ݨ������G(�b�C�ӽҵ{��Ʈw�̪�mid_ans��)����istudy�̭���mid_statistic�o��table
//require 'fadmin.php';
/*$DB_SERVER = "localhost";
$DB_LOGIN = "study";
$DB_PASSWORD = "2720411";
$DB = "study";*/
require 'fadmin.php';
global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;

if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
	show_page( "not_access.tpl" ,"��Ʈw�s�����~!!" );
}

$Q0 = "select distinct course_id as a_id, this_semester.year, this_semester.term from teach_course, this_semester where teach_course.year=this_semester.year and teach_course.term=this_semester.term order by course_id";
$result0 = mysql_db_query($DB, $Q0);

while($rows0 = mysql_fetch_array($result0)) // start of while_1
{
	$Q1 = "select * from mid_ans where year='".$rows0['year']."' and term='".$rows0['term']."'";
	if ( !($result = mysql_db_query( $DB.$rows0['a_id'], $Q1 ) ) )
	{
		echo $rows0['a_id']."�ҵ{�S��mid_ans�o��table<br>";
		continue;
	}
	if ( mysql_num_rows($result) != 0 ) // start of if_1
	{
		$Q1 = "SELECT mq.a_id, mq.q_id, mq.type, mq.question FROM mid_question mq, mid_subject ms WHERE ms.year='".$rows0['year']."' and ms.term='".$rows0['term']."' and mq.q_id=ms.a_id and mq.type='3'";
		if ( !($result1 = mysql_db_query( $DB, $Q1 ) ) )
		{
			show_page( "not_access.tpl" ,"��ƮwŪ�����~!!" );
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
			$k=0;	//���F�o���ĴX�D�ӳ]���ܼ� ex:k=1�N���1�p�D..and so on..
			
			while ( $rows2 = mysql_fetch_array($result2) ) // start of while_2
			{
				$k++;
				$qcounter ++;$id_counter++;
				if ( $rows2['type'] == "1" )	//type=1�N�����D // start of if_3
				{
					$sum1=0;	//�D�`���N
					$sum2=0;	//�ܺ��N
					$sum3=0;	//���q
					$sum4=0;	//�|�i
					$sum5=0;	//�����N
					
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
					$weight_sum = $sum1*5 + $sum2*4 + $sum3*3 + $sum4*2 + $sum5*1;	//�⺡�N�ץ�
					$fill_counter = $sum1 + $sum2 + $sum3 + $sum4 + $sum5;			//�`��g�H��
					if($fill_counter == 0)
					{
						echo "<font size=\"5\" color=\"red\">".$rows0['a_id']."</font>�ثe�|�����ǥͶ�g�����ݨ�!<br>";
						continue;
					}
					else // start of else
					{
						$Q5 = "select * from take_course where course_id='".$rows0['a_id']."' and year='".$rows0['year']."' and term='".$rows0['term']."' and credit='1'";
						$result5 = mysql_db_query($DB, $Q5);
						$nums = mysql_num_rows($result5);
						
						$avg_weight = $weight_sum / $fill_counter;
						$avg_weight_sec = number_format($avg_weight, 2); //number_format�O���F����p�ƥH�U�ĤG��A�����ᬰ�@��string
						$percent = ( $avg_weight/5 ) * 100;
						$percent_sec = number_format($percent, 2); //number_format�O���F����p�ƥH�U�ĤG��A�����ᬰ�@��string
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
	//echo "~~~~~~~~~~~~~~".$rows0['a_id']."���ҵ{�����ݨ����G�w��istudy��mid_subject�̭��F!!~~~~~~~~~~~~~~<br>";
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
	 *�N�Ҧ��t�Ҷ}���ҵ{���g�b�P�@��Excel�ɡA�M��� �ǰ|->�t�� �إ߸�Ƨ�
	 *�̫����Y�_�ӡA���аȳB���H�o�H�U���C
	 */

include("excelwriter.inc.php");

$Q0 = "select * from this_semester";
$result0 = mysql_db_query($DB, $Q0);
$rows0 = mysql_fetch_array($result0);

$Q1 = "select a_id from mid_subject where year='".$rows0['year']."' and term='".$rows0['term']."'";
$result1 = mysql_db_query($DB, $Q1);
$rows1 = mysql_fetch_array($result1);

if(!(is_dir("./".$rows0['year']."_0".$rows0['term']."�����ݨ�")))
	mkdir("./".$rows0['year']."_0".$rows0['term']."�����ݨ�");
//��ǰ|
$Q6 = "select a_id, name from course_group where parent_id=1 and a_id!=98";
$result6 = mysql_db_query($DB, $Q6);
while($rows6 = mysql_fetch_array($result6))
{
	if(!(is_dir("./".$rows0['year']."_0".$rows0['term']."�����ݨ�/".$rows6[name])))
		mkdir("./".$rows0['year']."_0".$rows0['term']."�����ݨ�/".$rows6[name]);
	//��t��
	$Q7 = "select a_id, name from course_group where parent_id='".$rows6[a_id]."' and a_id!=92 order by name";
	$result7 = mysql_db_query($DB, $Q7);
	while($rows7 = mysql_fetch_array($result7))
	{
		if(is_file("./".$rows0['year']."_0".$rows0['term']."�����ݨ�/".$rows6[name]."/".$rows0['year']."_0".$rows0['term']."_".$rows7[name].".xls"))
			unlink("./".$rows0['year']."_0".$rows0['term']."�����ݨ�/".$rows6[name]."/".$rows0['year']."_0".$rows0['term']."_".$rows7[name].".xls");
		$excel=new ExcelWriter("./".$rows0['year']."_0".$rows0['term']."�����ݨ�/".$rows6[name]."/".$rows0['year']."_0".$rows0['term']."_".$rows7[name].".xls");
		if($excel==false)
			echo $excel->error;
		$data=array("�t��","�ҵ{�s��","�ҵ{�W��","�½ұЮv","�ҵ{���N��","�ҵ{���N�צʤ���","�׽ҤH��","��g�H��","��g�v");
		$excel->writeLine($data);
		//��ҵ{
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
			$excel->writeCol($rows7[name]);			//�t��
			$excel->writeCol($rows8[course_no]);	//�ҵ{�s��
			$excel->writeCol($rows8[name]);			//�ҵ{�W��
			
			$Q9 = "select distinct u.name FROM user u , teach_course tc where tc.course_id = '".$rows8['a_id']."' and tc.teacher_id = u.a_id and u.authorization='1' and tc.year='".$rows0['year']."' and tc.term='".$rows0['term']."'";
			if ( !($result9 = mysql_db_query( $DB, $Q9 ) ) ) {
				$message = "$message - ��ƮwŪ�����~9!!";
			}
			$name="";
			while ($rows9 = mysql_fetch_array($result9))
			{
				if ( $rows9['name'] != NULL )
				{
					//�g�J�ɮץΪ��½ұЮv�ܼ�:$nameforsave
					$name = $name.$rows9['name']." ";
				}
			}
			$excel->writeCol($name);				//�½ұЮv
			
			$Q10 = "SELECT * FROM mid_statistic WHERE course_no = '".$rows8['a_id']."' and year='".$rows0['year']."' and term='".$rows0['term']."' and q_id='".$rows1['a_id']."'";
			if ( !($result10 = mysql_db_query( $DB, $Q10 ) ) ) {
				show_page( "not_access.tpl" ,"��ƮwŪ�����~10!!" );
			}
			$rows10 = mysql_fetch_array( $result10 );
			if ($rows10['satisfy'] == null || $rows10['satisfy'] == "")
			{
				$satisfy = "�ǥͥ���g";	//�g�J�ɮץΪ��ҵ{���N���ܼ�:$satisfy
				$percentsec = "�����ʤ���";	//�g�J�ɮץΪ��ҵ{���N�צʤ����ܼ�:$percentsec
			}
			else
			{
				$satisfy = $rows10['satisfy'];	//�g�J�ɮץΪ��ҵ{���N���ܼ�:$satisfy
				$percent = ( $rows10['satisfy'] / 5 ) * 100;
				$percentsec = number_format($percent, 2);		//�g�J�ɮץΪ��ҵ{���N�צʤ����ܼ�:$percentsec
			}
			$excel->writeCol($satisfy);				//�ҵ{���N��
			$excel->writeCol($percentsec);			//�ҵ{���N�צʤ���
			
			$Q11 = "select * from take_course where course_id='".$rows8['a_id']."' and year='".." and credit='1'";
			$result11 = mysql_db_query($DB, $Q11);
			$nums = mysql_num_rows($result11);		//�g�J�ɮץΪ��׽ҤH���ܼ�:$nums
			$excel->writeCol($nums);				//�׽ҤH��
			
			if($rows10['fill_count'] == "" || $rows10['fill_count'] == "NULL" )
			{
				//�g�J�ɮץΪ���g�H���ܼ�:$filled
				$filled = "0";
				//�g�J�ɮץΪ���g�v�ܼ�:$filledpercentsec
				$filledpercentsec = "�����H��g";
			}
			else
			{
				//�g�J�ɮץΪ���g�H���ܼ�:$filled
				$filled = $rows10['fill_count'];
				$filled_percent = ( $rows10['fill_count'] / $nums ) * 100;
				//�g�J�ɮץΪ���g�v�ܼ�:$filperpercentsec
				$filledpercentsec = number_format($filled_percent,2);
			}
			$excel->writeCol($filled);				//��g�H��
			$excel->writeCol($filledpercentsec);	//��g�v
			
			//echo "�ҵ{�G".$rows8['name']." data is write into ".$rows6[name]."/".$rows0['year']."_0".$rows0['term']."_".$rows7[name].".xls Successfully.<br>";
		}	//end of ��ҵ{
		$excel->close();
	} //end of ��t��
} //end of ��ǰ|
/*
*���Y����U�Ǵ������ݨ���tar��
*/
$location1 = $rows0['year']."_0".$rows0['term']."�����ݨ�";
$location2 = $rows0['year']."_0".$rows0['term'];
exec("tar -cvf $location2.tar $location1/*");
echo "<html>
	  <title>�U�������ݨ����G</title>
	  <body>
	  <center>
	  <a href=./onoff_questionary.php>�^�W�@��</a>
	  <br><hr>
      <a href=./".$location2.".tar>�I���U�����Y��</a>
	  </center>
	  </body>
	  </html>";
?>
