<?php
require 'fadmin.php';
include 'logger.php';
?>
<html>
<head>
<title>��s�ǥ͸��</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
</head>
<body background = "/images/img/bg.gif"><center>
<div><table>
    <tr> 
      <td> 
        <div>
          <font color=#000000>��s�ǥ͸��!</font>
        </div>
      </td>
    </tr>
</table>
<div id="progress">	�@
</div>
<?php
/*
if ( isset($PHPSESSID) && session_check_admin($PHPSESSID) ) {
*/
	echo "<body background = \"/images/img/bg.gif\"><center>";
	echo "�@��ǥ͸�ƶ}�l��s!!<br>";
	updateLog("�@��ǥ͸�ƶ}�l��s!!",4);
	if(($error = update_user("academic")) == -1) {
		echo "�@��ǥ͸�Ƨ�s����!!<br>";
		updateLog("�@��ǥ͸�Ƨ�s����!!",4);
	} else{
		echo "$error<br>";
	}
	echo "�M�Z�ǥ͸�ƶ}�l��s!!<br>";
	updateLog("�M�Z�ǥ͸�ƶ}�l��s!!",4);
	if(($error = update_user("academic_gra")) == -1) {
		echo "�M�Z�ǥ͸�Ƨ�s����!!<br>";
		updateLog("�M�Z�ǥ͸�Ƨ�s����!!",4);
	} else{
		echo "$error<br>";
	}
	
	echo "<br><a href=../check_admin.php>�^�t�κ޲z����</a></center></body>";
/*
}
else
	show_page( "index_ad.tpl", "�A���v�����~�A�Э��s�n�J!!");
*/


// �R����Ǵ��H�~���ǥͱb���T�{����~�g


	
// ��s�ǥͦW��
function update_user($db_name) {
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $id, $pass;
	
	if( !($cnx = sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) ){  
		Error_handler( "�b sybase_connect �����~�o��" , $cnx );  
	}
	
	$csd = sybase_select_db($db_name, $cnx);	
	//���o�t�Ҹ�T ���ۭq�}�C �᭱�|�ϥ�
	$cur_unit = sybase_query("select * from h0rtunit_a_", $cnx);
	if(!$cur_unit) {  
		Error_handler( "�b sybase_exec �����~�o��( �S�����жǦ^ ) " , $cnx );  
	}
	$unit = array() ;
	while($result_unit=sybase_fetch_array($cur_unit))
	{
		$unit_id = $result_unit['cd'];
		$unit[$unit_id] = $result_unit['abbrev'];
	}
	//
	
	
	//�b�ǥ͸��(�t���)
	$cur = sybase_query("select * from a11vstd_rec_tea", $cnx);
	if(!$cur) {  
		Error_handler( "�b sybase_exec �����~�o��( �S�����жǦ^ ) " , $cnx );  
	}
	//���~�ΰh��
	$cur2 = sybase_query("select * from a11vleave_rec_tea", $cnx);
	if(!$cur2) {  
		Error_handler( "�b sybase_exec �����~�o��( �S�����жǦ^ ) " , $cnx );  
	} 

	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$error = "mysql��Ʈw�s�����~!!";
		echo "$error";
	}
	
	//�i�ת��
	$count = 0;
	$temp = -1;
	$total = sybase_num_rows($cur);
	ob_end_flush();
	ob_implicit_flush(1);
	while($result=sybase_fetch_array($cur))
	{
		//�i�ת��
		$count++;
		$p = number_format((100*$count)/$total);
		if($p>$temp){
			echo "<script language=\"JavaScript\" type=\"text/JavaScript\">
					document.all.progress.innerHTML = \"�ǥ͸�Ʒs�W���A�еy�J $p%\" ; </script>";
		}
		$temp = $p;
		//
		$stu_name = addslashes($result[name]);
		$Q2 = "select id,disable from user where id = '$result[id]'";
		if ($result2 = mysql_db_query($DB,$Q2)){
			$unit_id = $result['deptcd']; 	//���o�t�ҥN�X
			if($result[status]=="�b��"){
				$row = mysql_fetch_array($result2);			
				// �s��
				if(!$row){						
					$Q3 = "insert into user (id, pass, authorization, name, sex, tel, zip, addr, email, job ,grade) values (\"$result[id]\", \"$result[ps]\", \"3\", \"$stu_name\", \"$result[sex]\", \"$result[tel] \", \"$result[zip]\", \"$result[addr]\", \"$result[email]\", \"$unit[$unit_id]\", \"".$result['grade'].$result['class']."\")";
					if ( !($result3 = mysql_db_query($DB,$Q3)) ){
						$error = "mysql��Ʈw�g�J���~!!";
						echo "$error".": $Q3".": $result3<br>";
					}
				}
				// ��ǥʹ_��,�ҥΨϥ��v��
				else if($row && $row[disable]=='1'){
					$Q3 = "update user set disable='0', name='$stu_name', job='$unit[$unit_id]', grade='".$result['grade'].$result['class']."' where id='$result[id]'";
					if ( !($result3 = mysql_db_query($DB,$Q3)) ){
						$error = "mysql��Ʈw�g�J���~!!";
						echo "$error".": $Q3".": $result3<br>";
					}
				}
				//��l��s�m�W�~��
				else{
					$Q3 = "update user set name='$stu_name', job='$unit[$unit_id]', grade='".$result['grade'].$result['class']."' where id='$result[id]'";
					if ( !($result3 = mysql_db_query($DB,$Q3)) ){
						$error = "mysql��Ʈw�g�J���~!!";
						echo "$error".": $Q3".": $result3<br>";
					}
				}
			}
			
			else if($result[status]=="���"){
				$row = mysql_fetch_array($result2);
				// ��ǥ�(�Ĥ@����s��Ʈɤ~�|�Ψ�)
				if(!$row){
					$Q3 = "insert into user (id, pass, authorization, disable, name, sex, tel, zip, addr, email,job ,grade) values (\"$result[id]\", \"$result[ps]\", \"3\", \"1\", \"$stu_name\", \"$result[sex]\", \"$result[tel] \", \"$result[zip]\", \"$result[addr]\", \"$result[email]\", \"$unit[$unit_id]\", \"".$result['grade'].$result['class']."\")";
					if ( !($result3 = mysql_db_query($DB,$Q3)) ){
						$error = "mysql��Ʈw�g�J���~!!";
						echo "$error".": $Q3".": $result3<br>";
					}
				}
				// �b�ǥͥ��,�Ȱ��ϥ��v��
				else if($row && $row[disable]=='0'){
					$Q3 = "update user set disable='1', name='$stu_name', job='$unit[$unit_id]', grade='".$result['grade'].$result['class']."' where id='$result[id]'";
					if ( !($result3 = mysql_db_query($DB,$Q3)) ){
						$error = "mysql��Ʈw�g�J���~!!";
						echo "$error".": $Q3".": $result3<br>";
					}
				}
				//��l��s�m�W�~��
				else{
					$Q3 = "update user set name='$stu_name', job='$unit[$unit_id]', grade='".$result['grade'].$result['class']."' where id='$result[id]'";
					if ( !($result3 = mysql_db_query($DB,$Q3)) ){
						$error = "mysql��Ʈw�g�J���~!!";
						echo "$error".": $Q3".": $result3<br>";
					}
				}
			}
		}
		else{
			$error = "mysql��ƮwŪ�����~!!";
			echo "$error";
		}
	}
	
	//�e�@�Ǧ~����Ƥ~�R�� �Y �̷s����ӾǴ���take_course����ƪ��δN���R
	//�qsybase���o�s�Ǵ�
	
	$cur3 = sybase_query("select DISTINCT year, term from a31v_sel_class_tea", $cnx);
	if(!$cur3) {  
		Error_handler( "�b sybase_exec �����~�o��( �S�����жǦ^ ) " , $cnx );  
	}
	if($array3=sybase_fetch_array($cur3))
	{
		$new_year = $array3['year'];
		$new_term = $array3['term'];
		if($new_term == 1){
			$pre_year = $new_year - 1;
			$pre_term = 2;
		}
		else{
			$pre_year = $new_year;
			$pre_term = 1;
		}
	}
	else{
		$error = "�Ǵ���Ƥ��s�b!!<BR>";
		updateLog("�Ǵ���Ƥ��s�b!!",4);
		return $error;
	}
	
	//�i�ת��
	$count = 0;
	$temp = -1;
	$total = sybase_num_rows($cur2);
	ob_end_flush();
	ob_implicit_flush(1);
	while($result=sybase_fetch_array($cur2)){
		//�i�ת��
		$count++;
		$p = number_format((100*$count)/$total,1);
		if($p>$temp){
			echo "<script language=\"JavaScript\" type=\"text/JavaScript\">
					document.all.progress.innerHTML = \"���~�ΰh�Ǿǥ͸�ƧR�����A�еy�J $p%\" ; </script>";
		}
		$temp = $p;
		//
		$Q2 = "select a_id, id from user where id = '$result[id]'";
		if ($result2 = mysql_db_query($DB,$Q2)){
			$row = mysql_fetch_row($result2);
			// ���~�ͩΰh�ǥͫh�������
			if($row){
				//�d��take_course �p�G�̷s����ӾǴ���take_course���L��Ƥ~�R
				$Q_t = "select count(student_id) as num from take_course where student_id = '".$row['0']."' and ( (year = $new_year and term = $new_term) OR (year = $pre_year and term = $pre_term) )";
				if($result_t = mysql_db_query($DB,$Q_t)){
					if($array_t = mysql_fetch_array($result_t)){
						if($array_t['num'] == 0){
							//�R���ǥ�
							$Q3 = "delete from user where id='$result[id]'";
							echo "�ǥ͸�Ƥw�M��$result[id]<br>";
							updateLog("�ǥ͸�Ƥw�M��$result[id]",4);
							if ( !($result3 = mysql_db_query($DB,$Q3)) ){
								$error = "mysql��Ʈw�g�J���~!!";
								echo "$error".": $Q3".": $result3<br>";
							}
						}
					}
				}
				else{
					$error = "mysql��ƮwŪ�����~t!!";
					echo "$error";
				}				
			}
		}
		else{
			$error = "mysql��ƮwŪ�����~!!";
			echo "$error";
		}
	}
	
	sybase_close( $cnx);
	return -1;
}

function Error_Handler( $msg, $cnx ) {  
	echo "$msg \n";
	sybase_close( $cnx); exit();  
}	
?>
