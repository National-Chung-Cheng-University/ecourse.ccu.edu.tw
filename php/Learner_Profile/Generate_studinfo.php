<?php
session_start();

include '../picture_encryption.php';
global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $uid, $cid, $version, $teacher;
$Q1 = "Select course_no From course Where a_id='$course_id'";
if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
	echo ( "��Ʈw�s�����~!!" );
	return;
}
if ( !($resultOBJ = mysql_db_query( $DB, $Q1 ) ) ) {
	echo ( "��ƮwŪ�����~!!" );
	return;
}
$row = mysql_fetch_array ( $resultOBJ );
if ( $teacher == "1" ) {
	if ( $version == "C" ) {
		if ( $nocredit != 1 ) {
			$file_name1="../../$course_id/student_info/t_".$row['course_no'].".bin";
			$file_name2="../../$course_id/student_info/t_".$row['course_no'].".xls";
			$file_name4="../../$course_id/student_info/ta_".$row['course_no'].".bin";
		}
		else {
			$file_name1="../../$course_id/student_info/t_".$row['course_no']."nocredit.bin";
			$file_name2="../../$course_id/student_info/t_".$row['course_no']."nocredit.xls";
			$file_name4="../../$course_id/student_info/ta_".$row['course_no']."nocredit.bin";
		}
	}
	else {
		if ( $nocredit != 1 ) {
			$file_name1="../../$course_id/student_info/t_".$row['course_no']."_E.bin";
			$file_name2="../../$course_id/student_info/t_".$row['course_no']."_E.xls";
			$file_name4="../../$course_id/student_info/ta_".$row['course_no']."_E.bin";
		}
		else {
			$file_name1="../../$course_id/student_info/t_".$row['course_no']."nocredit_E.bin";
			$file_name2="../../$course_id/student_info/t_".$row['course_no']."nocredit_E.xls";
			$file_name4="../../$course_id/student_info/ta_".$row['course_no']."nocredit_E.bin";
		}
	}
	if ( $nocredit != 1 ) {
		$file_name3="../../$course_id/student_info/".$row['course_no'].".txt";
	}
	else {
		$file_name3="../../$course_id/student_info/".$row['course_no']."nocredit.txt";
	}
}
else {
	if ( $version == "C" )
		$file_name1="../../$course_id/student_info/s_".$row['course_no'].".bin";
	else
		$file_name1="../../$course_id/student_info/s_".$row['course_no']."_E.bin";
}

if(file_exists($file_name1))
  unlink($file_name1);

//#########################//
if ( $nocredit != 1 )
	$Q2 = "Select * From take_course Where course_id='$course_id' and credit = '1' and year = '$course_year' and term = '$course_term'";
else
	$Q2 = "Select * From take_course Where course_id='$course_id' and credit = '0' and year = '$course_year' and term = '$course_term'";
if ( !($resultOBJ = mysql_db_query( $DB, $Q2 ) ) ) {
	echo ( "��ƮwŪ�����~!!" );
	return;
}
if( mysql_num_rows ( $resultOBJ ) == 0 )
  return;
else {
  $file1=fopen("$file_name1","w");
  if ( $teacher == "1" ) {
	if(file_exists($file_name2))
  		unlink($file_name2);
 	$file2=fopen("$file_name2","w");
 	if(file_exists($file_name3))
  		unlink($file_name3);
 	$file3=fopen("$file_name3","w");
 	if(file_exists($file_name4))
  		unlink($file_name4);
 	$file4=fopen("$file_name4","w");
  }
}

//#########################//

/*
$sname;//�ǥͩm�W(�ǥͦѮv���i�H�ݨ�)
$sid;//�Ǹ�(�ǥͦѮv���i�H�ݨ�)
$sexIndex;//�ʧO(�ǥͦѮv���i�H�ݨ�)
//$sexStr,sexStr_E;//�ʧO�r��,�����媩�P�^�媩(�p�k,�k,F,M)
$birth;//�X�ͤ��(�u���Ѯv�~��ݨ�)
$profIndex;//�t��(�u���Ѯv�~��ݨ�)
//$profStr,profStr_E;//�t�Ҧr��,�����媩�P�^�媩(�p�q�l�~,electronics industry��)
$tel;//�p���q��(�u���Ѯv�~��ݨ�)
$addr;//��}(�u���Ѯv�~��ݨ�)
$email;//E-Mail(�ǥͦѮv���i�H�ݨ�)
$uurl;//�ӤH����(�ǥͦѮv���i�H�ݨ�)
*/

fwrite($file1,"<html><head>\n");
fwrite($file1,"<META HTTP-EQUIV=\"Expires\" CONTENT=0>\n");
fwrite($file1,"<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=big5\">\n");
fwrite($file1,"<link rel=\"stylesheet\" href=\"/images/skin$skinnum/css/main-body.css\" type=\"text/css\">\n");
if ( $teacher == 1 ) {
	fwrite($file1,"<script language=\"JavaScript\">\nfunction Credit(){\n	document.credit.submit();\n}\n</script>\n" );

	fwrite($file4,"<html><head>\n");
	fwrite($file4,"<META HTTP-EQUIV=\"Expires\" CONTENT=0>\n");
	fwrite($file4,"<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=big5\">\n");
	fwrite($file4,"<link rel=\"stylesheet\" href=\"/images/skin$skinnum/css/main-body.css\" type=\"text/css\">\n");
	fwrite($file4,"<script language=\"JavaScript\">\nfunction Credit(){\n	document.credit.submit();\n}\n</script>\n" );
	fwrite($file4,"</head><BODY background=/images/img/bg.gif>\n<center>\n");
}
fwrite($file1,"</head><BODY background=/images/img/bg.gif>\n<center>\n");
//2009.05.06 �]�оǲխn�D�N[�ɮפU��]��m�C��W��,���Юv�i�H�M���ݨ� add by Jim 
if ( $version == "C" && $teacher == 1 ) {
  fwrite($file1,"<br><a href=$file_name2>�ǥͦW��U��</a>\n");
  fwrite($file1,"<br><a href=get_stupic.php>�ǥ͹Ϥ��U��</a>\n");
  //fwrite($file1,"<br><a href=$file_name3>�פJ��</a>\n");

  @fwrite($file4,"<br><a href=$file_name2>�ɮפU��</a>\n");
  //fwrite($file4,"<br><a href=$file_name3>�פJ��</a>\n");
}

if ( $teacher == 1 ) {
	fwrite($file1,"<form action=../../php/Learner_Profile/TSQueryFrame1.php name=credit method=get>\n");
	fwrite($file1,"<select name = nocredit onChange=\"Credit();\">\n");
	if ( $version == "C" )
		fwrite($file1,"<option value=0 >���ץ�</option>\n");
	else
		fwrite($file1,"<option value=0 >Credit</option>\n");
	fwrite($file1,"<option value=1 ");
	if ( $nocredit == 1 )
		fwrite($file1,"selected");
	if ( $version == "C" )
		fwrite($file1,">��ť��</option>\n");
	else
		fwrite($file1,">No Credit</option>\n");
	fwrite($file1,"</select></form>");
	
	fwrite($file4,"<form action=../../php/Learner_Profile/TSQueryFrame1.php name=credit method=get>\n");
	fwrite($file4,"<select name = nocredit onChange=\"Credit();\">\n");
	if ( $version == "C" )
		fwrite($file4,"<option value=0 >���ץ�</option>\n");
	else
		fwrite($file4,"<option value=0 >Credit</option>\n");
	fwrite($file4,"<option value=1 ");
	if ( $nocredit == 1 )
		fwrite($file4,"selected");
	if ( $version == "C" )
		fwrite($file4,">��ť��</option>\n");
	else
		fwrite($file4,">No Credit</option>\n");
	fwrite($file4,"</select></form>");
	fwrite($file4,"<table border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" >\n");
	fwrite($file4,"<tr> \n");
	fwrite($file4,"<td> \n");
	fwrite($file4,"<div align=\"right\"><img src=\"/images/skin$skinnum/bor/bor_01.GIF\" width=\"12\" height=\"11\"></div>\n");
	fwrite($file4,"</td>\n");
	fwrite($file4,"<td> \n");
	fwrite($file4,"<div align=\"center\"><img src=\"/images/skin$skinnum/bor/bor_02.GIF\" width=\"100%\" height=\"11\"></div>\n");
	fwrite($file4,"</td>\n");
	fwrite($file4,"<td> \n");
	fwrite($file4,"<div align=\"left\"><img src=\"/images/skin$skinnum/bor/bor_03.GIF\" width=\"17\" height=\"11\"></div>\n");
	fwrite($file4,"</td>\n");
	fwrite($file4,"</tr>\n");
	fwrite($file4,"<tr> \n");
	fwrite($file4,"<td height=10> \n");
	fwrite($file4,"<div align=\"right\"><img src=\"/images/skin$skinnum/bor/bor_04.GIF\" width=\"12\" height=\"100%\"></div>\n");
	fwrite($file4,"</td>\n");
	fwrite($file4,"<td bgcolor=\"#CCCCCC\"> \n");
	fwrite($file4,"<table border=0 align=\"center\" width=\"100%\" cellpadding=\"3\" cellspacing=\"1\">\n");
}
fwrite($file1,"<table border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" >\n");
fwrite($file1,"<tr> \n");
fwrite($file1,"<td> \n");
fwrite($file1,"<div align=\"right\"><img src=\"/images/skin$skinnum/bor/bor_01.GIF\" width=\"12\" height=\"11\"></div>\n");
fwrite($file1,"</td>\n");
fwrite($file1,"<td> \n");
fwrite($file1,"<div align=\"center\"><img src=\"/images/skin$skinnum/bor/bor_02.GIF\" width=\"100%\" height=\"11\"></div>\n");
fwrite($file1,"</td>\n");
fwrite($file1,"<td> \n");
fwrite($file1,"<div align=\"left\"><img src=\"/images/skin$skinnum/bor/bor_03.GIF\" width=\"17\" height=\"11\"></div>\n");
fwrite($file1,"</td>\n");
fwrite($file1,"</tr>\n");
fwrite($file1,"<tr> \n");
fwrite($file1,"<td height=10> \n");
fwrite($file1,"<div align=\"right\"><img src=\"/images/skin$skinnum/bor/bor_04.GIF\" width=\"12\" height=\"100%\"></div>\n");
fwrite($file1,"</td>\n");
fwrite($file1,"<td bgcolor=\"#CCCCCC\"> \n");
fwrite($file1,"<table border=0 align=\"center\" width=\"100%\" cellpadding=\"3\" cellspacing=\"1\">\n");

if ( $teacher == "1" ) {
	if ( $version == "C" ) {

       		/*2008.07.23 @ modify by w60292 �s�W�Ӥ��@��*/

		fwrite($file1,"<tr bgcolor = #000066><th><font color=#FFFFFF>�s��</font></th><th><font color=#FFFFFF>�t��</font></th><th><font color=#FFFFFF>�Ǹ��κ���</font></th><th><font color=#FFFFFF>�Ӥ�</font></th><th><font color=#FFFFFF>�m�W</font></th><th><font color=#FFFFFF>��#</font></th><th><font color=#FFFFFF>�ʺ�</font></th><th><font color=#FFFFFF>�ʧO</font></th><th><font color=#FFFFFF>�ʦV��m</font></th><th><font color=#FFFFFF>�X�ͤ��</font></th><th><font color=#FFFFFF>�p���q��</font></th><th><font color=#FFFFFF>E-mail</font></th></tr>\n");
		fwrite($file4,"<tr bgcolor = #000066><th><font color=#FFFFFF>�s��</font></th><th><font color=#FFFFFF>�t��</font></th><th><font color=#FFFFFF>�Ǹ��κ���</font></th><th><font color=#FFFFFF>�Ӥ�</font></th><th><font color=#FFFFFF>�m�W</font></th><th><font color=#FFFFFF>�ʺ�</font></th><th><font color=#FFFFFF>�ʧO</font></th><th><font color=#FFFFFF>�ʦV��m</font></th><th><font color=#FFFFFF>�X�ͤ��</font></th><th><font color=#FFFFFF>�p���q��</font></th><th><font color=#FFFFFF>E-mail</font></th></tr>\n");
                
                /********************************************/

		fwrite($file2,"�t��\t�Ǹ�\t�m�W\t�ʺ�\t�ʧO\t�ʦV��m\t�X�ͤ��\t�p���q��\tE-mail\t�ӤH����\n");

	}
	else {

 		/*2008.07.23 @ modify by w60292 �s�W�Ӥ��@��*/

		fwrite($file1,"<tr bgcolor = #000066><th><font color=#FFFFFF>NO.</font></th><th><font color=#FFFFFF>Occupation</font></th><th><font color=#FFFFFF>ID & Home Page</font></th><th><font color=#FFFFFF>Picture</font></th><th><font color=#FFFFFF>Name</font></th><th><font color=#FFFFFF>PHO#.</font></th><th><font color=#FFFFFF>NICK.</font></th><th><font color=#FFFFFF>Sex</font></th><th><font color=#FFFFFF>Color</font></th><th><font color=#FFFFFF>Date of birth</font></th><th><font color=#FFFFFF>Tel</font></th><th><font color=#FFFFFF>E-mail</font></th></tr>\n");
		fwrite($file4,"<tr bgcolor = #000066><th><font color=#FFFFFF>NO.</font></th><th><font color=#FFFFFF>Occupation</font></th><th><font color=#FFFFFF>ID & Home Page</font></th><th><font color=#FFFFFF>Picture</font></th><th><font color=#FFFFFF>Name</font></th><th><font color=#FFFFFF>NICK.</font></th><th><font color=#FFFFFF>Sex</font></th><th><font color=#FFFFFF>Color</font></th><th><font color=#FFFFFF>Date of birth</font></th><th><font color=#FFFFFF>Tel</font></th><th><font color=#FFFFFF>E-mail</font></th></tr>\n");		

		/********************************************/   

		fwrite($file2,"Occupation\tID\tName\tNick.\tSex\tColor\tDate of birth\tTel\tE-mail\tPersonal homepage\n");		
	}
}
else {
	if ( $version == "C" )
		fwrite($file1,"<tr bgcolor = #000066><th><font color=#FFFFFF>�s��</font></th><th><font color=#FFFFFF>�t��</font></th><th><font color=#FFFFFF>�Ǹ�</font></th><th><font color=#FFFFFF>�m�W</font></th><th><font color=#FFFFFF>�ʺ�</font></th><th><font color=#FFFFFF>�ʧO</font></th></tr>\n");
	else
		fwrite($file1,"<tr bgcolor = #000066><th><font color=#FFFFFF>NO.</font></th><th><font color=#FFFFFF>Occupation</font></th><th><font color=#FFFFFF>ID</font></th><th><font color=#FFFFFF>Name</font></th><th><font color=#FFFFFF>NICK.</font></th><th><font color=#FFFFFF>Sex</font></th></tr>\n");
}

if ( $nocredit != 1 )
	$Q3 = "Select user.* From user,take_course Where user.a_id=take_course.student_id And take_course.course_id=$course_id and take_course.credit = '1'  and year = '$course_year' and term = '$course_term' Order By id ASC";
else
	$Q3 = "Select user.* From user,take_course Where user.a_id=take_course.student_id And take_course.course_id=$course_id and take_course.credit = '0'  and year = '$course_year' and term = '$course_term' Order By id ASC";
if ( !($resultOBJ = mysql_db_query( $DB, $Q3 ) ) ) {
	echo ( "��ƮwŪ�����~!!" );
	return;
}

$i = 1;
$color == "#F0FFEE";
$count=0;
while( $row = mysql_fetch_array ( $resultOBJ ) )
{
  /*2008.07.23 @modify by w60292 �s�W�Ӥ�*/
  
  //$spic="<img src=\"../../Stu-Photo/".$row['id'].".jpg\" width=\"103\" height=\"133\">";
  
  /*2008.9.23 @modify by w60292 ��i�U�C��k��performance
  
  //�]�Ӥ��ɦ�m�|�n�S�B�|�Q�s��,�ҥH�������w���ʪ��ק� 2008.09.23 by Jim
  
  $md5str=md5($row['id']."8945");
  $phfile1="/home/study/Stu-Photo/".$row['id'].".jpg";
  $phfile2="../../TMP/".$md5str.".jpg";

  if(file_exists($phfile1))
    copy($phfile1,$phfile2);
  $spic="<img src=\"".$phfile2."\" width=\"103\" height=\"133\">";
  
  //if(file_exists($phfile2))
  //  unlink($phfile2);
  */
  $picid = pic_encrypt($row['id']);
  $spic = "<img src=\"../url_convert.php?id=".$picid."\" width=\"103\" height=\"133\">";
  /****************************************/
  
  $sname=$row['name'];

  $sid=$row['id'];

  $sexIndex=$row['sex'];
  if($sexIndex == "0")
  {
  	if ( $version == "C" ) {
	    $sexStr = "�k";
	}
    	else {
	    $sexStr = "F";
	}
  }
  else if($sexIndex == "1")
  {
  	if ( $version == "C" ) {
	    $sexStr = "�k";
	}
	else {
	    $sexStr = "M";
	}
  }
  else
  {
    $sexStr = "Error";
    $sexStr = "Error";
  }

  $birth=$row['birthday'];
  if($birth == NULL)
    $birth = "N/A";
  //$row['job'] = iconv('utf-8','big5',$row['job']);
  $row['job'] = mb_convert_encoding($row['job'],'big5','utf-8,big5');
  $profStr = $row['job'].$row['grade'];
  if($row['job'] == NULL)
   $profStr="N/A";

  $tel=$row['tel'];
  if($tel == NULL)
    $tel = "N/A";

//  $addr=$row['addr'];
//  if($addr == NULL)
//    $addr = "N/A";
  
  $email=$row['email'];
  
  if($email == NULL)
    $email = "N/A";
  else if($count <85)
	{
		$mail_list=$mail_list.$email.",";
        $count++;
	}
  else if($count <170)
	{
		$mail_list1=$mail_list1.$email.",";
        $count++;
	}
  else if($count < 255)
	{
		$mail_list2=$mail_list2.$email.",";
        $count++;
	}
  $url=$row['php'];
  if($url == NULL) {
    $url = "N/A";
    $pid = $sid;
  }
  else {
    // 2008.9.26 @ modify by w60292 �򥻸�ƭ�����}�[�K
    //$pid = "<a href=$url target=\"_blank\">$sid</a>";
    $new_sid = pic_encrypt($sid);
    $pid = "<a href=\"http://ecourse.elearning.ccu.edu.tw/php/url_convert2.php?id=".$new_sid."\" target=\"_blank\">$sid</a>";
  }

  $nick=$row['nickname'];
  if($nick == NULL)
    $nick = "N/A";
  if ( $version == "C" ) {
	  if($row['color'] == 1)
		$scolor = "���";
	  else if($row['color'] == 2)
		$scolor = "����";
	  else if($row['color'] == 3)
		$scolor = "�Ŧ�";
	  else if($row['color'] == 4)
		$scolor = "���";
	  else
	  	$scolor = "�m�i";
  }else {
	  if($row['color'] == 1)
		$scolor = "Orange";
	  else if($row['color'] == 2)
		$scolor = "Gold";
	  else if($row['color'] == 3)
		$scolor = "Blue";
	  else if($row['color'] == 4)
		$scolor = "Green";
	  else
	  	$scolor = "Rainbow";
  }

  if ( $color == "#F0FFEE" )
	$color = "#E6FFFC";
  else
	$color = "#F0FFEE";
  if ( $teacher == "1" ) {
	fwrite($file3,"$sname,$sid#\n");

      	/*2008.07.23 @modify by w60292 �s�W�Ӥ�*/

	fwrite($file1,"<tr bgcolor = $color><td nowrap>$i</td><td nowrap>".$profStr."</td><td nowrap>".$pid."</td><td nowrap>".$spic."</td><td nowrap>".$sname."</td><td nowrap>".$row['a_id']."</td><td nowrap>".$nick."</td><td nowrap>".$sexStr."</td><td nowrap>".$scolor."</td><td nowrap>".$birth."</td><td nowrap>".$tel."</td><td nowrap><a href=mailto:$email>".$email."</a></td></tr>\n");
	fwrite($file4,"<tr bgcolor = $color><td nowrap>$i</td><td nowrap>".$profStr."</td><td nowrap>".$pid."</td><td nowrap>".$spic."</td><td nowrap>".$sname."</td><td nowrap>".$nick."</td><td nowrap>".$sexStr."</td><td nowrap>".$scolor."</td><td nowrap>".$birth."</td><td nowrap>".$tel."</td><td nowrap><a href=mailto:$email>".$email."</a></td></tr>\n");

	/**************************************/

	fwrite($file2,"$profStr\t$sid\t$sname\t$nick\t$sexStr\t$scolor\t$birth\t$tel\t$email\t$url\n");	
  }
  else {
	fwrite($file1,"<tr bgcolor = $color><td nowrap>".$i."</td><td nowrap>".$profStr."</td><td nowrap>".$sid."</td><td nowrap>".$sname."</td><td nowrap>".$nick."</td><td nowrap>".$sexStr."</td></tr>\n");
  }
  $i ++;
  
}
//unlink("*.jpg");

fwrite($file1,"</table>\n");
fwrite($file1,"</td>\n");
fwrite($file1,"<td height=10> \n");
fwrite($file1,"<div align=\"left\"><img src=\"/images/skin$skinnum/bor/bor_06.GIF\" width=\"17\" height=\"100%\"></div>\n");
fwrite($file1,"</td>\n");
fwrite($file1,"</tr>\n");
fwrite($file1,"<tr> \n");
fwrite($file1,"<td> \n");
fwrite($file1,"<div align=\"right\"><img src=\"/images/skin$skinnum/bor/bor_07.GIF\" width=\"12\" height=\"17\"></div>\n");
fwrite($file1,"</td>\n");
fwrite($file1,"<td> \n");
fwrite($file1,"<div align=\"center\"><img src=\"/images/skin$skinnum/bor/bor_08.GIF\" width=\"100%\" height=\"17\"></div>\n");
fwrite($file1,"</td>\n");
fwrite($file1,"<td> \n");
fwrite($file1,"<div align=\"left\"><img src=\"/images/skin$skinnum/bor/bor_09.GIF\" width=\"17\" height=\"17\"></div>\n");
fwrite($file1,"</td>\n");
fwrite($file1,"</tr>\n");
fwrite($file1,"</table>\n");
if ( $teacher == 1 ) {
	fwrite($file4,"</table>\n");
	fwrite($file4,"</td>\n");
	fwrite($file4,"<td height=10> \n");
	fwrite($file4,"<div align=\"left\"><img src=\"/images/skin$skinnum/bor/bor_06.GIF\" width=\"17\" height=\"100%\"></div>\n");
	fwrite($file4,"</td>\n");
	fwrite($file4,"</tr>\n");
	fwrite($file4,"<tr> \n");
	fwrite($file4,"<td> \n");
	fwrite($file4,"<div align=\"right\"><img src=\"/images/skin$skinnum/bor/bor_07.GIF\" width=\"12\" height=\"17\"></div>\n");
	fwrite($file4,"</td>\n");
	fwrite($file4,"<td> \n");
	fwrite($file4,"<div align=\"center\"><img src=\"/images/skin$skinnum/bor/bor_08.GIF\" width=\"100%\" height=\"17\"></div>\n");
	fwrite($file4,"</td>\n");
	fwrite($file4,"<td> \n");
	fwrite($file4,"<div align=\"left\"><img src=\"/images/skin$skinnum/bor/bor_09.GIF\" width=\"17\" height=\"17\"></div>\n");
	fwrite($file4,"</td>\n");
	fwrite($file4,"</tr>\n");
	fwrite($file4,"</table>\n");
   
	if ( $version == "C" ) {
		fwrite($file1,"<br><a href=$file_name2>�ǥͦW��U��</a>\n");
		fwrite($file1,"<br><a href=get_stupic.php>�ǥ͹Ϥ��U��</a>\n");
		fwrite($file1,"<br><a href=$file_name3>�פJ��</a>\n");

		fwrite($file4,"<br><a href=$file_name2>�ɮפU��</a>\n");
		fwrite($file4,"<br><a href=$file_name3>�פJ��</a>\n");
        if ($count<85)
		{
        	fwrite($file1,"<br><a href=mailto:?subject=�q���H!&bcc=$mail_list>�H�H�������ǥ�</a>\n");
        	fwrite($file4,"<br><a href=mailto:?subject=�q���H!&bcc=$mail_list>�H�H�������ǥ�</a>\n");
		}
        else if($count<160)
		{
			fwrite($file1,"<br>(�]���H�ƹL�h�������⦸�H�H)\n");
			fwrite($file4,"<br>(�]���H�ƹL�h�������⦸�H�H)\n");
			fwrite($file1,"<br><a href=mailto:?subject=�q���H!&bcc=$mail_list>�H�H�������ǥ�(1)</a>\n");
        	fwrite($file4,"<br><a href=mailto:?subject=�q���H!&bcc=$mail_list>�H�H�������ǥ�(1)</a>\n");
			fwrite($file1,"<a href=mailto:?subject=�q���H!&bcc=$mail_list1>�H�H�������ǥ�(2)</a>\n");
        	fwrite($file4,"<a href=mailto:?subject=�q���H!&bcc=$mail_list1>�H�H�������ǥ�(2)</a>\n");
		}
		else if($count<255)
		{
			fwrite($file1,"\n�]���H�ƹL�h�������T���H�H\n");
			fwrite($file4,"\n�]���H�ƹL�h�������T���H�H\n");
			fwrite($file1,"<br><a href=mailto:?subject=�q���H!&bcc=$mail_list>�H�H�������ǥ�(1)</a>\n");
        	fwrite($file4,"<br><a href=mailto:?subject=�q���H!&bcc=$mail_list>�H�H�������ǥ�(1)</a>\n");
			fwrite($file1,"<br><a href=mailto:?subject=�q���H!&bcc=$mail_list1>�H�H�������ǥ�(2)</a>\n");
        	fwrite($file4,"<br><a href=mailto:?subject=�q���H!&bcc=$mail_list1>�H�H�������ǥ�(2)</a>\n");
			fwrite($file1,"<br><a href=mailto:?subject=�q���H!&bcc=$mail_list2>�H�H�������ǥ�(3)</a>\n");
        	fwrite($file4,"<br><a href=mailto:?subject=�q���H!&bcc=$mail_list2>�H�H�������ǥ�(3)</a>\n");
		}		       
	}
	fclose($file2);
	fclose($file3);
	fwrite($file4,"</center></body></html>\n");
	fclose($file4);
}
fwrite($file1,"</center></body></html>\n");
fclose($file1);
?>
