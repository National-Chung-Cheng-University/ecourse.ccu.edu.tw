<?php
/**
 *�{���G�ݸu�Юv�}�Һ��@
 *�����G�C�Ǵ�����������إ��ұЮv�����ݸu���A���A�p�Ȯy�б¡B�������Z�оǤ��ҵ{�A�]�����������eú���Z�A�W�]�����\��C
 *����G2006/11/29
 *��s�G2006/12/15--�Y�H�Ƹ�Ƥw��s�A���}�Ҹ�Ƥw�����s�A�h���N�ӱЮv�b���ܧ󬰫ݸu���A�A�H���@�}�Ҹ�ơA�U����s�H�Ƹ�Ʈɷ|��s���@��Юv�C
 */

require '../fadmin.php';

if ( !(isset($PHPSESSID) && session_check_admin($PHPSESSID)) ) 
{
	show_page( "index_ad.tpl", "�A���v�����~�A�Э��s�n�J!!");
	exit;
}

function Error_Handler( $msg )
{  
	echo "$msg \n";
	exit();  
}
$msg="";

if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) 
	Error_Handler( "mysql��Ʈw�s�����~!!" );
$sdb=mysql_select_db($DB,$link);
if(!$sdb)
	Error_Handler( "mysql��Ʈw���~!!" );
if($action== "addnew") {
	$pass="";
	//--20061215--�Y�w�s�b�H�Ƹ�Ƥ��A��s���ݸu���A
	$q_temp="select id from user where id='$t_id'";
	if(!($rs_temp=mysql_query($q_temp,$link)))
		Error_Handler( "mysql��Ʈw�d�߿��~0!!" );
	if(mysql_fetch_array($rs_temp)) {
		$q_temp1="update user set validated='0' where id='$t_id' and authorization='1'";
		if(!($rs_temp1=mysql_query($q_temp1,$link)))
			Error_Handler( "mysql��Ʈw�d�߿��~01!!" );
		$msg="�շs�W�b�����\��--�ӱЮv�b���w�s�b�A�Y���@��Юv�h��s���ݸu���A";
	}	
	else {
		$q_temp="insert into user (id, pass, ftppass, authorization, name, validated) values ('$t_id', '" . passwd_encrypt($pswd) . "', '" . md5($pswd) . "', '1', '$t_name', '0')";
		if(!($rs_temp=mysql_query($q_temp,$link)))
			Error_Handler( "mysql��Ʈw�d�߿��~1!!" );
		$msg="�շs�W�b�����\��";
	}		
}

else if($action== "delete") {
	//�ˬd�Y�ӱЮv�����}�ҫh���i�R��
	$q_temp="select teacher_id from teach_course where teacher_id=$aid";
	if(!($rs_temp=mysql_query($q_temp,$link)))
		Error_Handler( "mysql��Ʈw�d�߿��~4!!" );
	if(mysql_fetch_array($rs_temp)) {
		$msg="�էR���b�����ѡ� �ӱЮv�����}�Ҹ�ơA���i�R���I"; 
	} 
	else
	{
		$q_temp="delete from user where a_id=$aid";
		if(!($rs_temp=mysql_query($q_temp,$link)))
			Error_Handler( "mysql��Ʈw�d�߿��~2!!" );
		$msg="�էR���b�����\��";
	} 
	  
}else if($action== "modify") {
	$q_temp="update user set id='$t_id', name='$t_name' where a_id=$aid";
	if(!($rs_temp=mysql_query($q_temp,$link)))
		Error_Handler( "mysql��Ʈw�d�߿��~3!!" );
	$msg="�է�s�b�����\��";
}
//--�Ҧ��ݸu�Юv��undefined���~
$Q1="select a_id, id, name from user where authorization='1' and validated='0' and id <> 'undefined' order by id";
$rs1=mysql_query($Q1,$link);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<title>�ݸu�Юv�b�����@</title>
<SCRIPT LANGUAGE="javascript">
function modify(formObj){
	if (check(formObj)){
		formObj.action.value="modify";
		formObj.submit();
	}
}
function del(formObj){
	if (confirm("�T�w�n�R���H�m�W�G" + formObj.t_name.value + " �b���G"+formObj.t_id.value + "?")) {
		formObj.action.value="delete";
		formObj.submit();
	}
}
function check(formObj){
	if (formObj.t_id.value=="" || formObj.t_name.value==""){
		alert("�п�J�Юv�b���M�m�W!!");
	return false;
	}
	return true;
}
</SCRIPT>
</head>

<body bgcolor="">
<table bgcolor="blue" align="center" width="100%">
<tr><td align="center"><font color="#ffff00"><b>�ݸu�Юv�b�����@</b></font></td></tr>
</table>
<div align="center"><font color='red'><? echo $msg; ?></font></div>
<div>&gt;&gt;&gt;�s�W�Юv</div>
<form method="post" name="form1" action="update_o_tch.php" onSubmit="check(this);">
<table width="500" align="center">
<tr><td width="180">�п�J�Юv�b��(�����Ҹ�)�G</td><td width="220"><input type="text" name="t_id" maxlength="10"><td><td width="100"></td></tr>
<tr><td width="180">�п�J�Юv�m�W�G</td><td width="220"><input type="text" name="t_name" maxlength="40">
	<td width="100"><input type="submit" value="�s�W�Юv"><input type="hidden" name="action" value="addnew"></td>
</tr>
</table>
</form>

<hr>
<div>&gt;&gt;&gt;�ק�Юv��� </div>
<table width="500" align="center" border="1">
	<tr bgcolor="#eeeeee"><td width="150" align="center">�b�� </td>
		<td width="150" align="center">�m�W</td>
		<td width="200" align="center">&nbsp;</td>
	</tr>
	<?php
	while($rows=mysql_fetch_array($rs1)) {
	?>
	<tr><form method="post" name="frm_<? echo $rows[a_id] ?>">
		<td width="150"><input type="text" name="t_id" maxlength="10" value="<? echo $rows[id] ?>"></td>
		<td width="150"><input type="text" name="t_name" maxlength="40" value="<? echo $rows[name] ?>"></td>
		<td width="200">
			<input type="button" name="b_delete" value="�R��" onClick="del(document.frm_<? echo $rows[a_id] ?>);">
			<input type="button" name="b_modify" value="��s" onClick="modify(document.frm_<? echo $rows[a_id] ?>);">
			<input type="hidden" name="action" value="">
			<input type="hidden" name="aid" value="<? echo $rows[a_id] ?>">
		</td>
		</form>
	</tr>
	<?php
	}
	mysql_free_result($rs1);
	mysql_close($link);
	?>
</table><p>
<table width="500" align="center" border="0">
<tr><td><font color="red">���R���\��ȭ���ӱЮv�L�}�����p��ƮɡA���}�ҮɵL�k�R���C</font></td></tr>
</table>
<div align="center"><a href=../check_admin.php>�^�޲z����</a></div>
</body>
</html>
