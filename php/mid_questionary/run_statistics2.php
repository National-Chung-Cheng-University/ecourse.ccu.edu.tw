<?php
/** �\��G�����ݨ��έp���G����A�ק�
  * �����G
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
	show_page( "not_access.tpl" ,"��Ʈw�s�����~!!" );
}

	 
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
$Q6 = "select a_id, name from course_group where parent_id=1 and a_id!=98"; //98�����եΨt��
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
			unlink("./".$rows0['year']."_0".$rows0['term']."�����ݨ�/".$rows6[name]."/".$rows0['year']."_0".$rows0['term']."_".$rows7[name].".xls");//�Y�w���ɮ׫h�R��
		$excel=new ExcelWriter("./".$rows0['year']."_0".$rows0['term']."�����ݨ�/".$rows6[name]."/".$rows0['year']."_0".$rows0['term']."_".$rows7[name].".xls");
		if($excel==false)
			echo $excel->error;
		$data=array("�t��","�ҵ{�s��","�ҵ{�W��","�½ұЮv","�׽ҤH��","��g�H��","��g�v","���D�@","���D�G");
		$excel->writeLine($data);
		
		$Q8 = "select distinct c.a_id, c.name, c.course_no, ts.year, ts.term
			   from course c, teach_course tc, this_semester ts
			   where c.group_id='$rows7[a_id]'
					 and c.a_id=tc.course_id
					 and tc.year=ts.year
					 and tc.term=ts.term";
		$result8 = mysql_db_query($DB, $Q8);
		while($rows8 = mysql_fetch_array($result8)) //��ҵ{ while($row8)
		{			
			//--�½ұЮv(1~�h��)
			$name="";
			$Q9 = "select distinct u.name FROM user u , teach_course tc where tc.course_id = '".$rows8['a_id']."' and tc.teacher_id = u.a_id and u.authorization='1' and tc.year='".$rows0['year']."' and tc.term='".$rows0['term']."'";
			if ( !($result9 = mysql_db_query( $DB, $Q9 ) ) ) {
				$message = "$message - ��ƮwŪ�����~9!!";
			}
			while ($rows9 = mysql_fetch_array($result9))
			{
				if ( $rows9['name'] != NULL )
				{
					$name = $name.$rows9['name']." ";
				}
			}
			
			//�׽ҤH��
			$stu_no=0;
			$Q_tmp="select count(tc.student_id) as stu_no from take_course tc, user u where tc.student_id=u.a_id and u.disable='0' and tc.course_id=$rows8[a_id] and year='".$rows0['year']."' and term='".$rows0['term']."'";				
			if ( !($rs_temp = mysql_db_query( $DB, $Q_tmp ) ) ) {
				$message = "$message - ��ƮwŪ�����~-�׽ҤH��!!";
			}
			if($rw_tmp = mysql_fetch_array($rs_temp))
			{
				$stu_no = $rw_tmp['stu_no'];				
			}
			
			//��g�H��
			$join_no=0;
			$Q_tmp="select count(student_id) as join_no FROM mid_ans where year=$rows8[year] and term='$rows8[term]'";				
			if ( !($rs_temp = mysql_db_query( $DB.$rows8[a_id], $Q_tmp ) ) ) {
				$message = "$message - ��ƮwŪ�����~-��g�H��!!";
			}
			if($rw_tmp = mysql_fetch_array($rs_temp))
			{
				$join_no = $rw_tmp['join_no'];				
			}
			//��g�v
			$ratio1=0;
			if ($stu_no!=0)
				$ratio1=number_format((($join_no/$stu_no)*100),2); 
			
			//Ū���ӽҵ{���ݨ����G�A�v���g�Jexcel�ɮ�
			$Q10 = "SELECT q1,q2 FROM mid_ans where year=$rows8[year] and term='$rows8[term]'";
			
			if ( !($result10 = mysql_db_query( $DB.$rows8[a_id], $Q10 ) ) ) {
				show_page( "not_access.tpl" ,"��ƮwŪ�����~10!!" );
			}
			//----��ӽҵ{�L�H��g�ݨ�
			if(!mysql_num_rows($result10)) 
			{
				$excel->writeRow();
				$excel->writeCol($rows7[name]);			//�t��
				$excel->writeCol($rows8[course_no]);	//�ҵ{�s��
				$excel->writeCol($rows8[name]);			//�ҵ{�W��
				$excel->writeCol($name);					//�½ұЮv
				$excel->writeCol($stu_no);					//�׽ҤH��
				$excel->writeCol($join_no);				//��g�H��
				$excel->writeCol($ratio1);					//��g�v
				$excel->writeCol("");						//���D�@
				$excel->writeCol("");						//���D�G
			}	
			//----��ӽҵ{���ݨ���g���
			else {
				while($rows10 = mysql_fetch_array( $result10 ) )
				{
					$excel->writeRow();
					$excel->writeCol($rows7[name]);			//�t��
					$excel->writeCol($rows8[course_no]);	//�ҵ{�s��
					$excel->writeCol($rows8[name]);			//�ҵ{�W��
					$excel->writeCol($name);					//�½ұЮv
					$excel->writeCol($stu_no);					//�׽ҤH��
					$excel->writeCol($join_no);				//��g�H��
					$excel->writeCol($ratio1);					//��g�v
					$excel->writeCol($rows10[q1]);			//���D�@
					$excel->writeCol($rows10[q2]);			//���D�G				
				}
			}		
		}	//end of ��ҵ{ while($row8)
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
