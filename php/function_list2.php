<?php 
require 'fadmin.php';
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<title>�L���D���</title>
</head>



<body>
<form name="form1" method="post" action="function_list2.php">

<?php
global $modify;

if($modify=="1") {
global $news, 											$info, $sched, $sgquery, $ssquery, $email,
	$material, $online,  								$show_work, $show_test, $show_qs, $check_case,
	$chat, $discuss, $talk_voc, $talk_int, $eboard, 	$search, $stinfo, $psswd, $strank, $ssmodify;


//$news, 											$info, $sched, $sgquery, $ssquery, $email,
if($news!="1")
	$news="1";	
	
if($info!="1")
	$info="1";	
if($sched!="1")
	$sched="0";	
if($sgquery!="1")
	$sgquery="1";	
if($ssquery!="1")
	$ssquery="0";	
if($email!="1")
	$email="0";
	
//$material, $online,  								$show_work, $show_test, $show_qs, $check_case,
if($material!="1")
	$material="1";
if($online!="1")
	$online="0";	
	
	
if($show_work!="1")
	$show_work="1";		
if($show_test!="1")
	$show_test="1";	
if($show_qs!="1")
	$show_qs="0";		
if($check_case!="1")
	$check_case="0";	

//$chat, $discuss, $talk_voc, $talk_int, $eboard, 	$search, $stinfo, $psswd, $strank, $ssmodify;
if($chat!="1")
	$chat="0";	
if($discuss!="1")
	$discuss="1";			
if($talk_voc!="1")
	$talk_voc="0";	
if($talk_int!="1")
	$talk_int="0";	
if($eboard!="1")
	$eboard="0";	

//	$chrank, $sttrace, $complete, $rollbook, $search, 				$stinfo, $psswd, $strank, $ssmodify, $psswd;	
if($search!="1")
	$search="0";			
if($stinfo!="1")
	$stinfo="0";	
if($psswd!="1")
	$psswd="0";	
if($strank!="1")
	$strank="0";	
if($ssmodify!="1")
	$ssmodify="1";	

/* $news, 											$info, $sched, $sgquery, $ssquery, $email,
	$material, $online,  								$show_work, $show_test, $show_qs, $create_case,
	$chat, $discuss, $talk_voc, $talk_int, $eboard, 	$search, $stinfo, $psswd, $strank, $ssmodify;
*/

$m_sql = "UPDATE function_list2 SET "
    ."news='$news',"									."info='$info', sched='$sched', sgquery='$sgquery', ssquery='$ssquery', email='$email',"
	."material='$material', online='$online',"			."show_work='$show_work', show_test='$show_test', show_qs='$show_qs', check_case='$check_case',"
	."chat='$chat', discuss='$discuss', talk_voc='$talk_voc', talk_int='$talk_int', eboard='$eboard'," 
	."search='$search', stinfo='$stinfo', psswd='$psswd', strank='$strank', ssmodify='$ssmodify'"
	." where u_id='$user_id'";
	
	//echo $m_sql;
	
	
		if ( !($m_link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			echo "��Ʈw�s�����~!!";
			//return $error;
		}else {
		
				if ( !( mysql_db_query( "study".$course_id, $m_sql )) ) {
					echo "��Ʈw��s���~!!";
					//return $error;
				}else{
					echo "<font color=red>��Ƥw��s�A�Э��s��z����(Ctrl + F5)�I</font>";
				}

		}


}



function is_check ( $value ) {
	if($value=="1")
		return " checked ";

}
		$sql = "select * FROM  function_list2 where u_id='$user_id'";
		$result = 0;
				
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			echo "��Ʈw�s�����~!!";
			//return $error;
		}else {
		
				if ( !($result = mysql_db_query( "study".$course_id, $sql )) ) {
					echo "��ƮwŪ�����~!!";
					//return $error;
				}
				else {
					$row = mysql_fetch_array ($result) ;

?>
  <table width="50%" border="1">
    <tr bgcolor="#6699FF">
      <td width="40%"><strong>�̷s����</strong></td>
      <td width="60%">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="news" type="checkbox" value="1"  <?php echo is_check($row["news"]);?> disabled="true">      
      ���G�� </td>
    </tr>
	  <tr bgcolor="#6699FF">
      <td><strong>�ҵ{��T</strong></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="info" type="checkbox" value="1"  <?php echo is_check($row["info"]);?> disabled="true">
�½Ҥj��  </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="sched" type="checkbox" value="1"  <?php echo is_check($row["sched"]);?>>
�ҵ{�w��</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="sgquery" type="checkbox" value="1"  <?php echo is_check($row["sgquery"]);?> disabled="true">
���Z�d�� </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="ssquery" type="checkbox" value="1"  <?php echo is_check($row["ssquery"]);?>> 
      �P�Ǭd��</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="email" type="checkbox" value="1"  <?php echo is_check($row["email"]);?>> 
        �ЮvE-mail
</td>
    </tr>
	<tr bgcolor="#6699FF">
      <td><strong>�½ұЧ�</strong></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="material" type="checkbox" value="1"  <?php echo is_check($row["material"]);?> disabled="true">
        �½ұЧ�</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="online" type="checkbox" value="1"  <?php echo is_check($row["online"]);?>>
      �H����T</td>
    </tr>
	<tr bgcolor="#6699FF">
	<td><strong>�ۧڵ��q</strong></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="show_work" type="checkbox" value="1"  <?php echo is_check($row["show_work"]);?> disabled="true">
      �u�W�@�~ </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="show_test" type="checkbox" value="1"  <?php echo is_check($row["show_test"]);?> disabled="true">
      �u�W����</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="show_qs" type="checkbox" value="1"  <?php echo is_check($row["show_qs"]);?> >
�ݨ��լd</td>
    </tr>
<!--    <tr>
      <td>&nbsp;</td>
      <td><input name="check_case" type="checkbox" value="1"  <?php echo is_check($row["check_case"]);?> >
        �X�@�ǲ�</td>
	</tr>
-->
    <tr bgcolor="#6699FF">
      <td><strong>�Q�װ�</strong></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="chat" type="checkbox" value="1"  <?php echo is_check($row["chat"]);?>>
      �u�W�Q�װ� </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="discuss" type="checkbox" value="1"  <?php echo is_check($row["discuss"]);?> disabled="true">
      �ҵ{�Q�װ� </td>
    </tr>
<!--    <tr>
      <td>&nbsp;</td>
      <td><input name="talk_voc" type="checkbox" value="1"  <?php echo is_check($row["talk_voc"]);?>>
      �y����ѫ� </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="talk_int" type="checkbox" value="1"  <?php echo is_check($row["talk_int"]);?>>
      ���ʲ�ѫ� </td>
    </tr>
-->    <tr>
      <td>&nbsp;</td>
      <td><input name="eboard" type="checkbox" value="1"  <?php echo is_check($row["eboard"]);?>>
      EBoard </td>
    </tr>
    <tr bgcolor="#6699FF">
      <td><strong>�ӤH�u��</strong></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="search" type="checkbox" value="1"  <?php echo is_check($row["search"]);?>>
      �����˯�</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="stinfo" type="checkbox" value="1"  <?php echo is_check($row["stinfo"]);?>>
      �ӤH�ϥΰO��</td>
    </tr>
<!--    <tr>
      <td>&nbsp;</td>
      <td><input name="psswd" type="checkbox" value="1"  <?php echo is_check($row["psswd"]);?>>
      �ק�K�X</td>
    </tr>
-->    <tr>
      <td>&nbsp;</td>
      <td><input name="strank" type="checkbox" value="1"  <?php echo is_check($row["strank"]);?>>
      �ǥͨϥΰO��</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="ssmodify" type="checkbox" value="1"  <?php echo is_check($row["ssmodify"]);?> disabled="true">
      �ק�ӤH���</td>
    </tr>
  </table>

  <p>
  	<input type="hidden" name="modify" value="1">
    <input type="submit" name="Submit" value="�e�X�ק�">
  </p>
<?php  
   				}

		}  
?>  
</form>


</body>
</html>
