<?php
/**
 *程式：待聘教師開課維護
 *說明：每學期均有部份科目任課教師為〝待聘狀態〞，如客座教授、收撥遠距教學之課程，因應全面網路送繳成績，增設此項功能。
 *日期：2006/11/29
 *更新：2006/12/15--若人事資料已更新，但開課資料已不能更新，則先將該教師帳號變更為待聘狀態，以維護開課資料，下次更新人事資料時會更新為一般教師。
 */

require '../fadmin.php';

if ( !(isset($PHPSESSID) && session_check_admin($PHPSESSID)) ) 
{
	show_page( "index_ad.tpl", "你的權限錯誤，請重新登入!!");
	exit;
}

function Error_Handler( $msg )
{  
	echo "$msg \n";
	exit();  
}
$msg="";

if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) 
	Error_Handler( "mysql資料庫連結錯誤!!" );
$sdb=mysql_select_db($DB,$link);
if(!$sdb)
	Error_Handler( "mysql資料庫錯誤!!" );
if($action== "addnew") {
	$pass="";
	//--20061215--若已存在人事資料中，更新為待聘狀態
	$q_temp="select id from user where id='$t_id'";
	if(!($rs_temp=mysql_query($q_temp,$link)))
		Error_Handler( "mysql資料庫查詢錯誤0!!" );
	if(mysql_fetch_array($rs_temp)) {
		$q_temp1="update user set validated='0' where id='$t_id' and authorization='1'";
		if(!($rs_temp1=mysql_query($q_temp1,$link)))
			Error_Handler( "mysql資料庫查詢錯誤01!!" );
		$msg="＜新增帳號成功＞--該教師帳號已存在，若為一般教師則更新為待聘狀態";
	}	
	else {
		$q_temp="insert into user (id, pass, ftppass, authorization, name, validated) values ('$t_id', '" . passwd_encrypt($pswd) . "', '" . md5($pswd) . "', '1', '$t_name', '0')";
		if(!($rs_temp=mysql_query($q_temp,$link)))
			Error_Handler( "mysql資料庫查詢錯誤1!!" );
		$msg="＜新增帳號成功＞";
	}		
}

else if($action== "delete") {
	//檢查若該教師仍有開課則不可刪除
	$q_temp="select teacher_id from teach_course where teacher_id=$aid";
	if(!($rs_temp=mysql_query($q_temp,$link)))
		Error_Handler( "mysql資料庫查詢錯誤4!!" );
	if(mysql_fetch_array($rs_temp)) {
		$msg="＜刪除帳號失敗＞ 該教師仍有開課資料，不可刪除！"; 
	} 
	else
	{
		$q_temp="delete from user where a_id=$aid";
		if(!($rs_temp=mysql_query($q_temp,$link)))
			Error_Handler( "mysql資料庫查詢錯誤2!!" );
		$msg="＜刪除帳號成功＞";
	} 
	  
}else if($action== "modify") {
	$q_temp="update user set id='$t_id', name='$t_name' where a_id=$aid";
	if(!($rs_temp=mysql_query($q_temp,$link)))
		Error_Handler( "mysql資料庫查詢錯誤3!!" );
	$msg="＜更新帳號成功＞";
}
//--所有待聘教師－undefined除外
$Q1="select a_id, id, name from user where authorization='1' and validated='0' and id <> 'undefined' order by id";
$rs1=mysql_query($Q1,$link);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<title>待聘教師帳號維護</title>
<SCRIPT LANGUAGE="javascript">
function modify(formObj){
	if (check(formObj)){
		formObj.action.value="modify";
		formObj.submit();
	}
}
function del(formObj){
	if (confirm("確定要刪除？姓名：" + formObj.t_name.value + " 帳號："+formObj.t_id.value + "?")) {
		formObj.action.value="delete";
		formObj.submit();
	}
}
function check(formObj){
	if (formObj.t_id.value=="" || formObj.t_name.value==""){
		alert("請輸入教師帳號和姓名!!");
	return false;
	}
	return true;
}
</SCRIPT>
</head>

<body bgcolor="">
<table bgcolor="blue" align="center" width="100%">
<tr><td align="center"><font color="#ffff00"><b>待聘教師帳號維護</b></font></td></tr>
</table>
<div align="center"><font color='red'><? echo $msg; ?></font></div>
<div>&gt;&gt;&gt;新增教師</div>
<form method="post" name="form1" action="update_o_tch.php" onSubmit="check(this);">
<table width="500" align="center">
<tr><td width="180">請輸入教師帳號(身分證號)：</td><td width="220"><input type="text" name="t_id" maxlength="10"><td><td width="100"></td></tr>
<tr><td width="180">請輸入教師姓名：</td><td width="220"><input type="text" name="t_name" maxlength="40">
	<td width="100"><input type="submit" value="新增教師"><input type="hidden" name="action" value="addnew"></td>
</tr>
</table>
</form>

<hr>
<div>&gt;&gt;&gt;修改教師資料 </div>
<table width="500" align="center" border="1">
	<tr bgcolor="#eeeeee"><td width="150" align="center">帳號 </td>
		<td width="150" align="center">姓名</td>
		<td width="200" align="center">&nbsp;</td>
	</tr>
	<?php
	while($rows=mysql_fetch_array($rs1)) {
	?>
	<tr><form method="post" name="frm_<? echo $rows[a_id] ?>">
		<td width="150"><input type="text" name="t_id" maxlength="10" value="<? echo $rows[id] ?>"></td>
		<td width="150"><input type="text" name="t_name" maxlength="40" value="<? echo $rows[name] ?>"></td>
		<td width="200">
			<input type="button" name="b_delete" value="刪除" onClick="del(document.frm_<? echo $rows[a_id] ?>);">
			<input type="button" name="b_modify" value="更新" onClick="modify(document.frm_<? echo $rows[a_id] ?>);">
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
<tr><td><font color="red">※刪除功能僅限於該教師無開課關聯資料時，有開課時無法刪除。</font></td></tr>
</table>
<div align="center"><a href=../check_admin.php>回管理首頁</a></div>
</body>
</html>
