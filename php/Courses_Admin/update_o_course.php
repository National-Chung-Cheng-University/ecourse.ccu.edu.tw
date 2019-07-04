<?php
/**
 *程式：待聘教師開課維護
 *說明：每學期均有部份科目任課教師為〝待聘狀態〞，如客座教授、收撥遠距教學之課程，因應全面網路送繳成績，增設此項功能。
 *日期：2006/11/29
 *更新：2006/11/29
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

//---更新資料
if($action== "modify") {
	$q_temp="update teach_course set teacher_id='$tid' where teacher_id=$uid and course_id=$cid";
	if(!($rs_temp=mysql_query($q_temp,$link)))
		Error_Handler( "mysql資料庫查詢錯誤1!!" );
	$msg="＜更新開課教師成功＞";
}

//所有待聘狀態之教師

$Q0="select a_id, id, name from user where authorization='1' and validated='0' order by id";
$rs0=mysql_query($Q0,$link);
$j=0;
while($rows0=mysql_fetch_array($rs0)) {
	$teachers[$j]=array($rows0[a_id],$rows0[id],$rows0[name]);
	$j++;	
}
mysql_free_result($rs0);
//echo "!".count($teachers)."!!";
/*for($j=0; $j < count($teachers); $j++){
	echo $teachers[$j][0];
	echo $teachers[$j][1];
	echo $teachers[$j][2];
}*/

//query當學期的year&term (2007/10/12 by carlyle)
$Q1 = "SELECT * FROM `this_semester`";
$result = mysql_query($Q1,$link);
$rows = mysql_fetch_array($result);
$this_year = $rows['year'];
$this_term = $rows['term'];

//所有待聘教師開課
$Q1="select cg.name gname, c.course_no, c.name cname, c.a_id cid, u.a_id uid, u.name uname
 		from teach_course tc, course c, user u, course_group cg
		where tc.teacher_id=u.a_id 			
 			and tc.course_id=c.a_id
 			and cg.a_id=c.group_id
 			and u.validated='0' and u.authorization='1'
			and tc.year='" . $this_year . "' and tc.term='" . $this_term . "' order by c.course_no";
$rs1=mysql_query($Q1,$link);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<title>待聘教師開課維護</title>
</head>

<body bgcolor="">
<table bgcolor="blue" align="center" width="100%">
<tr><td align="center"><font color="#ffff00">待聘教師開課維護</font></td></tr>
</table>

<hr>
<div align="center"><font color='red'><? echo $msg; ?></font></div>
<table width="700" align="center" border="1">
<tr bgcolor="#eeeeee">
	<td width="150" align="center">系所 </td>
	<td width="200" align="center">課程名稱</td>
	<td width="50" align="center">課程代號</td>
	<td width="50" align="center">班別</td>
	<td width="200" align="center">授課教師</td>
	<td width="50" align="center">&nbsp;</td>
</tr>
<?php
$i=0;
while($rows=mysql_fetch_array($rs1)) {
	$i++;	
	list($courseno,$class)=split("_",$rows[course_no]);
	$showoption=showTch($rows[uid],$teachers);
?>
<tr>
<form method="post" name="frm_<? echo $i ?>" action="update_o_course.php">
	<td width="150"><? echo $rows[gname]; ?></td>
	<td width="200"><? echo $rows[cname]; ?></td>
	<td width="50"><? echo $courseno; ?></td>
	<td width="50"><? echo $class; ?></td>
	<td width="200"><select name="tid"><? echo $showoption; ?></select></td>
	<td width="50">
		<input type="button" name="b_modify" value="更新" onClick="document.frm_<? echo $i ?>.submit();">
		<input type="hidden" name="action" value="modify">
		<input type="hidden" name="cid" value="<? echo $rows[cid]; ?>">
		<input type="hidden" name="uid" value="<? echo $rows[uid]; ?>">
	</td>
</form>
</tr>
<?php
} //---end while

function showTch($uid,$teachers){
	$str="";
	$checked="";
	for($j=0; $j < count($teachers); $j++) {
		if($teachers[$j][0]==$uid)
			$checked="selected";
		else
			$checked="";
		$str.="<option value=\"".$teachers[$j][0]."\" ".$checked .">".$teachers[$j][1]." ".$teachers[$j][2]."</option>\n";
	}
	return $str;
}
mysql_free_result($rs1);
mysql_close($link);
?>
</table>
<div align="center"><a href="../check_admin.php">回管理首頁</a></div>
</body>
</html>
