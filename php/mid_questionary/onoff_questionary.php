<?php 
require 'fadmin.php';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<title>�����ݨ��s��</title>
<style type="text/css">
<!--
.style1 {
	font-size: 18px;
	font-weight: bold;
}
-->
</style>
</head>
<body>
<?php
	if(session_check_teach($PHPSESSID) != 2) {
		show_page("not_access.tpl", "�A�S���v�����榹�\��.<br>\nYou have no permission to perform this function.");
		exit();
	}

?>
<table border="0" align="center" cellpadding="6">
  <tr>
    <td colspan="2"><div align="center"><h3>�����ݨ��s��</h3></div></td>
  </tr>
  <tr>
    <td colspan="2"><div align="center"> <a href=../check_admin.php>�^�t�κ޲z����</a> </div></td>
  </tr>
  <tr bgcolor="#FFFFCC">
	<form method=post action=./create_questionary.php>
	 <td>
  		<input name="submit" type=submit value=" �s �W �� �� ">
	</td></form>
    <td>&nbsp;�B�J���G�s�W�ӾǴ������ݨ��γ]�w�D�ءA�C�Ǵ��һݷs�W�@��</td>
  </tr>
  <tr bgcolor="#FFFFCC">
	<form method=post action=./modify_questionary.php>
	<td>
  		<input name="submit" type=submit value=" �� �� �� �� ">
	</td></form>
    <td>�B�J���G�ն�B�o�G�ݨ�(<font color=red>�s�W�ݨ���A���]�w�o�G���</font>)�B�έp�B�ק�W�ٻP�ݩʡB �ק��D�ءB�R���ݨ�</td>
  </tr>
  <tr bgcolor="#FFFFCC"><form method=post action="./run_statistics2.php">
  	<td><input name="submit" type="submit" value=" �� �� �� �� ">
  	</td></form>
  	<td>�����ݨ���g�鵲����A�ξ�����ݨ������G�A�ñN���G(Excel��)�U���C(95.12��s)
  	</td>
  </tr>
  <tr bgcolor="#FFFFCC"><!--form method=post action="./run_statistics.php">
  	<td><input name="submit" type="submit" value=" �� �� �� �� ">
  	</td></form-->
  	<td><input type="button" value=" �� �� �� �� ">(�w����)</td>
  	<td>�����ݨ���g�鵲����A�ξ�����ݨ������G(���N��)�A�ñN���G(Excel��)�U���C
  	</td>
  </tr>
</table>
<table border="0" align="center" cellpadding="6">
  <tr>
    <td width="432" colspan="3">&nbsp;</td>
  </tr>
  <tr bgcolor="#FFFFCC">
    <td>A.</td>
    <form method="post" action="./onoff_questionary.php"><td>
      <input type="hidden" name="questionary" value="1">
      <input type="submit" name="open" value=" �} �� �� �� �� �� " >
        </td></form>
    <td>&nbsp;�}�Ҿǥ�&quot;�ݨ��լd&quot; button�A���ɥ������Ѯv &quot;�[�ݰݨ��έp&quot; button</td>
  </tr>
  <tr bgcolor="#FFFFCC">
    <td>B.</td>
    <form method="post" action="./onoff_questionary.php"><td>
      <input type="hidden" name="questionary" value="0">
      <input type="submit" name="close" value=" �� �� �� �� �� �� " >    
        </td></form>
    <td bgcolor="#FFFFCC">�����ǥ�&quot;�ݨ��լd&quot; button�A���ɥ�}�ҦѮv &quot;�[�ݰݨ��έp&quot; button</td>
  </tr>
  <tr bgcolor="#FFFFCC">
    <td>C.</td>
    <form method="post" action="./onoff_questionary.php"><td>
      <input type="hidden" name="questionary" value="2">
      <input type="submit" name="close" value=" ���� �Ѯv�ǥ� �ݨ� " >    
        </td></form>
    <td bgcolor="#FFFFCC">�����ǥ�&quot;�ݨ��լd&quot; button�A���ɥ������Ѯv &quot;�[�ݰݨ��έp&quot; button</td>
  </tr>
  <tr>
    <td colspan="3"><div align="center">
    <?php
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $skinnum;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$error = "��Ʈw�s�����~!!";
		show_page ( "not_access.tpl", $error );
	}
	if(isset($questionary))
	{
		$Q1 = "update function set que =".$questionary;
		mysql_db_query($DB, $Q1);
	}
	$Q1 = "select que from function";
	$result = mysql_db_query($DB, $Q1);
	$row = mysql_fetch_array($result);
	
	if ($row['que'] == 1 )   //��que�ȥΨӱ���bar.php��bar1.tpl��bar2.tpl����ܡC
		echo "�ثe���A�� A ."; 
	else if ($row['que'] == 0 ) 
		echo "�ثe���A�� B .";
	else
		echo "�ثe���A�� C .";
	?></div></td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
</table>
<table border="1" align="center" cellpadding="15" bordercolor="#CCFFFF">
  <tr>
    <td bgcolor="#DBFFFF">�s�W�ݨ��y�{�G<br>
      1.�s�W�ݨ�<br>
      2.�s���D��<br>
      3.�ն� (�Y����J���~�A�ק�)<br>
      4.�o�G�ݨ��G�]�w�ݨ��_�l�P�����ɶ�<br>
    5.�}�Ұݨ�button</td>
  </tr>
</table>

</body>
</html>