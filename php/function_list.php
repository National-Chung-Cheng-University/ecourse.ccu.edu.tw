<?php 
require 'fadmin.php';
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<title>�\��C��</title>
</head>



<body>
<form name="form1" method="post" action="function_list.php">

<?php
global $modify;

if($modify=="1") {
global $news, $intro, $sched, $info, $tein, $officehr, $core, $evaluate, 	$tgins, $tgdel, $tgmod, $tgquery, $warning, $upload, 
	$editor, $online, $material, $import, $create_work, 			$modify_work, $check_work,$create_test, $modify_test, $create_case,
	$mag_case, $check_case, $create_qs, $modify_qs, $discuss, 		$chat, $talk_voc, $talk_int, $eboard, $strank, 
	$chrank, $sttrace, $complete, $rollbook, $tsins, 				$tsdel, $tsmod, $tschg, $tsquery, $psswd;

/**************/
/*  �ҵ{��T  */
/**************/
// $news, $intro, $sched, $info, $tein, $officehr, $core, $evaluate 		$tgins, $tgdel, $tgmod, $tgquery, $warning, $upload, 
if($news!="1") //���G��
	$news="1";	
if($intro!="1")//�½Ҥj��
	$intro="1";	
if($sched!="1")//�ҵ{�w��
	$sched="0";	
if($info!="1") //�U�и��
	$info="1";	
if($tein!="1") //�Юv���
	$tein="1";
if($officehr!="1")//�줽�Ǯɶ�
	$officehr="0";
if($core!="1")   //�ҵ{���[
	$core="0";
if($evaluate!="1")//�ҵ{�۵�
	$evaluate="0";
	
/**************/
/*  ���Z�t��  */
/**************/
if($tgins!="1")	//���Z�s�W
	$tgins="1";	
if($tgdel!="1") //���Z�R��
	$tgdel="1";	
if($tgmod!="1")	//���Z�ק�
	$tgmod="1";	
if($tgquery!="1")//���Z�d��
	$tgquery="1";	
if($warning!="1")//�wĵ�t��
	$warning="1";

/**************/
/*  �½ұЧ�  */
/**************/
//	$editor, $online, $material, $import, $create_work, 			$modify_work, $check_work,$create_test, $modify_test, $create_case,
	
if($upload!="1") //�W���ɮ�
	$upload="1";
if($editor!="1") //�s��u��
	$editor="1";	
if($online!="1") //�H����T
	$online="0";	
if($material!="1")//�Ч��w��
	$material="1";	
if($import!="1") //�Ч��פJ
	$import="1";	

/**************/	
/*  �u�W�@�~  */
/**************/
if($create_work!="1") //�X�s�@�~
	$create_work="1";		
if($modify_work!="1") //�ק�@�~
	$modify_work="1";	
if($check_work!="1")  //�[�ݧ@�~
	$check_work="1";		

/**************/
/*  �u�W����  */
/**************/
if($create_test!="1") //�s�@����
	$create_test="1";	
if($modify_test!="1") //�ק����
	$modify_test="1";	

/********************/
/*  �ثe�S�Ϊ��\��  */
/********************/
//$mag_case, $check_case, $create_qs, $modify_qs, $discuss, 		$chat, $reservation, $recording, $talk_voc, $talk_int, $eboard, $strank,
if($create_case!="1")	//�s�W�M��
	$create_case="0";	
if($mag_case!="1")	//�M�׺޲z
	$mag_case="0";	
if($check_case!="1")	//�X�@�ǲ�
	$check_case="0";

if($eboard!="1")  //�q�l���G��
	$eboard="0";
if($talk_voc!="1")//�y����ѫ�
        $talk_voc="0";
if($talk_int!="1")//���ʲ�ѫ�
        $talk_int="0";
if($tschg!="1")	//�ק鶴��
        $tschg="0";
if($tsquery!="1")//�ǥ͸�Ƭd��
        $tsquery="1";
if($psswd!="1")	//�d�߾ǥͱK�X
        $psswd="0";

/**************/
/*  �u�W�ݨ�  */
/**************/
if($create_qs!="1") //�s�@�ݨ�
	$create_qs="0";	
if($modify_qs!="1") //�ק�ݨ�
	$modify_qs="0";		

/**************/
/*   �Q�װ�   */
/**************/
if($discuss!="1") //�ҵ{�Q�װ�
	$discuss="1";
if($chat!="1")	  //�e�������줽��
	$chat="1";
if($reservation!="1")//�w�������줽��
	$reservation="0";
if($recording!="1")//���v�ɺ޲z
	$recording="0";

/**************/
/*  �ǲ߰l��  */
/**************/
//$chrank, $sttrace, $complete, $rollbook, $eroll, $tsins, 				$tsdel, $tsmod, $tschg, $tsquery, $psswd;	
if($strank!="1") //�t�ΨϥΰO�� 
	$strank="0";		
if($chrank!="1") //�Ч��s���O��
	$chrank="0";	
if($sttrace!="1")//�ǥͭӧO�O��
	$sttrace="0";	
if($complete!="1")//�O������C��
	$complete="0";	
if($rollbook!="1")//�I�Wï
	$rollbook="1";
if($eroll!="1")	 //�q�l�I�W
	$eroll="0";

/**************/
/*  �ǥͺ޲z  */
/**************/
if($tsins!="1")	//�ǥͷs�W
	$tsins="1";			
if($tsdel!="1")	//�ǥͧR��
	$tsdel="0";	
if($tsmod!="1")	//�ǥͭק�
	$tsmod="0";	

	
$m_sql = "UPDATE function_list SET "
    ."news='$news', intro='$intro', sched='$sched', info='$info', tein='$tein', officehr='$officehr', core='$core', evaluate='$evaluate',"
	."tgins='$tgins', tgdel='$tgdel', tgmod='$tgmod', tgquery='$tgquery', warning='$warning', show_test_rank='$show_test_rank', show_onlinetest_rank='$show_onlinetest_rank', show_homework_rank='$show_homework_rank', show_all_rank='$show_all_rank', upload='$upload'," 
	."editor='$editor', online='$online', material='$material', import='$import', create_work='$create_work',"
	."modify_work='$modify_work', check_work='$check_work',create_test='$create_test', modify_test='$modify_test', create_case='$create_case',"
	."mag_case='$mag_case', check_case='$check_case', create_qs='$create_qs', modify_qs='$modify_qs', discuss='$discuss',"
	."chat='$chat', reservation='$reservation', recording='$recording',talk_voc='$talk_voc', talk_int='$talk_int', eboard='$eboard', strank='$strank'," 
	."chrank='$chrank', sttrace='$sttrace', complete='$complete', rollbook='$rollbook', eroll='$eroll',tsins='$tsins',"
	."tsdel='$tsdel', tsmod='$tsmod', tschg='$tschg', tsquery='$tsquery', psswd='$psswd'"
	." where u_id='$user_id'";
	
	
	
		if ( !($m_link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			echo "��Ʈw�s�����~!!";
			//return $error;
		}else {
		
				if ( !( mysql_db_query( "study".$course_id, $m_sql )) ) {
					echo "��Ʈw��s���~!!";
					//echo "$m_sql";
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
		$sql = "select * FROM  function_list where u_id='$user_id'";
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
      <td width="40%"><strong>�ҵ{��T (Course Information)</strong></td>
      <td width="60%">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="news" type="checkbox" value="1"  <?php echo is_check($row["news"]);?> disabled="true">      
      ���G�� (News) </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="intro" type="checkbox" value="1"  <?php echo is_check($row["intro"]);?> disabled="true">
      �½Ҥj�� (Syllabus) </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="sched" type="checkbox" value="1"  <?php echo is_check($row["sched"]);?>>
      �ҵ{�w�� (Weekly Schedule)</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="info" type="checkbox" value="1"    <?php echo is_check($row["info"]);?> disabled="true">
      �U�и�� (TA Info)</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="tein" type="checkbox" value="1"    <?php echo is_check($row["tein"]);?> disabled="true">
      �Юv��� (Pro. Info)</td>
    </tr>
	<tr>
      <td>&nbsp;</td>
      <td><input name="tsquery" type="checkbox" value="1"  <?php echo is_check($row["tsquery"]);?> disabled="true">
      �ǥ͸�Ƭd�� (Query Students)</td>
    </tr>
	<tr>
      <td>&nbsp;</td>
      <td><input name="funclist" type="checkbox" value="1" checked  disabled="true">
      �t�Υ\��]�w (Function Setup)</td>
    </tr>
    <!-- modify by w60292 @ 20090421 �s�W�i�Ŀ諸�M�� -->
	<tr>
      <td>&nbsp;</td>
      <td><input name="officehr" type="checkbox" value="1" <?php echo is_check($row["officehr"]);?>>
      �줽�Ǯɶ� (Office Hour)</td>
    </tr>
	<tr>
      <td>&nbsp;</td>
      <td><input name="core" type="checkbox" value="1" <?php echo is_check($row["core"]);?>>
      �ҵ{���[ (Core Competencies)</td>
    </tr>
	<tr>
      <td>&nbsp;</td>
      <td><input name="evaluate" type="checkbox" value="1" <?php echo is_check($row["evaluate"]);?>>
      �ҵ{�۵� (Self Evaluate)</td>
    </tr>
    <!-- end modify -->
	  <tr bgcolor="#6699FF">
      <td><strong>���Z�t�� (Score)</strong></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="tgins" type="checkbox" value="1" <?php echo is_check($row["tgins"]);?> disabled="true">
      ���Z�s�W (New Item)</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="tgdel" type="checkbox" value="1"  <?php echo is_check($row["tgdel"]);?> disabled="true">
      ���Z�R�� (Delete Item)</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="tgmod" type="checkbox" value="1"  <?php echo is_check($row["tgmod"]);?> disabled="true">
      ���Z�ק� (Edit Item)</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="tgquery" type="checkbox" value="1"  <?php echo is_check($row["tgquery"]);?> disabled="true">
      ���Z�d�� (Score Listing)</td>
    </tr>
	
	<!-- �s�W��ܾǥͺݦU�رƦW(�@�����B�u�W����B�u�W�@�~���`�ƦW)���v��-->
	<tr>
      <td>&nbsp;</td>
      <td><input name="show_test_rank" type="checkbox" value="1"  <?php echo is_check($row["show_test_rank"]);?>>
      ��ܤ@�����ƦW (Test Rank)</td>
    </tr>
	<tr>
      <td>&nbsp;</td>
      <td><input name="show_onlinetest_rank" type="checkbox" value="1"  <?php echo is_check($row["show_onlinetest_rank"]);?>>
      ��ܽu�W����ƦW (Online Test Rank)</td>
    </tr>
	<tr>
      <td>&nbsp;</td>
      <td><input name="show_homework_rank" type="checkbox" value="1"  <?php echo is_check($row["show_homework_rank"]);?>>
      ��ܽu�W�@�~�ƦW (Homework Rank)</td>
    </tr>
	<tr>
      <td>&nbsp;</td>
      <td><input name="show_all_rank" type="checkbox" value="1"  <?php echo is_check($row["show_all_rank"]);?>>
      ����`�ƦW (Rank)</td>
    </tr>
	
	
	
    <!-- modify by w60292 @ 20090421 �s�W�i�Ŀ諸�M�� -->
    <tr>
      <td>&nbsp;</td>
      <td><input name="warning" type="checkbox" value="1" <?php echo is_check($row["warning"]);?> >
      �wĵ�t�� (Caution)</td>
    </tr>
    <!-- modify end -->
	<tr bgcolor="#6699FF">
      <td><strong>�½ұЧ� (Courseware)</strong></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="upload" type="checkbox" value="1"  <?php echo is_check($row["upload"]);?> disabled="true">
      �W���ɮ� (File upload)</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="editor" type="checkbox" value="1"  <?php echo is_check($row["editor"]);?> disabled="true">
      �s��u�� (Authoring Tool)</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="online" type="checkbox" value="1"  <?php echo is_check($row["online"]);?>>
      �H����T (Vedio Material)</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="material" type="checkbox" value="1"  <?php echo is_check($row["material"]);?> disabled="true">
      �Ч��w�� (Preview)</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="import" type="checkbox" value="1"  <?php echo is_check($row["import"]);?> disabled="true">
      �Ч��פJ (Import)</td>
    </tr>
	<tr bgcolor="#6699FF">
	<td><strong>�u�W�@�~ (Homework)</strong></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="create_work" type="checkbox" value="1"  <?php echo is_check($row["create_work"]);?> disabled="true">
      �X�s�@�~ (New)</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="modify_work" type="checkbox" value="1"  <?php echo is_check($row["modify_work"]);?> disabled="true">
      �ק�@�~ (Edit)</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="check_work" type="checkbox" value="1"  <?php echo is_check($row["check_work"]);?> disabled="true">
      �[�ݧ@�~ (Preview)</td>
	</tr> 
    <tr bgcolor="#6699FF">
      <td><strong>�u�W���� (Online Quiz)</strong></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="create_test" type="checkbox" value="1"  <?php echo is_check($row["create_test"]);?> disabled="true">
      �s�@���� (New)</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="modify_test" type="checkbox" value="1"  <?php echo is_check($row["modify_test"]);?> disabled="true">
      �ק���� (Edit)</td>
    </tr>	 
<!--    <tr bgcolor="#6699FF">
      <td><strong>�X�@�ǲ�</strong></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="create_case" type="checkbox" value="1"  <?php echo is_check($row["create_case"]);?>>
      �s�W�M�� </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="mag_case" type="checkbox" value="1"  <?php echo is_check($row["mag_case"]);?>>
      �M�׺޲z</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="check_case" type="checkbox" value="1"  <?php echo is_check($row["check_case"]);?>>
      �[�ݱM�� </td>
    </tr>
-->
    <tr bgcolor="#6699FF">
      <td><strong>�u�W�ݨ� (Questionary)</strong></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="create_qs" type="checkbox" value="1" checked disabled="true">
      �s�@�ݨ� (New)</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="modify_qs" type="checkbox" value="1"  <?php echo is_check($row["modify_qs"]);?>>
      �ק�ݨ� (Edit)</td>
    </tr>
    <!-- modify by w60292 @ 20090421 �s�W�i�Ŀ諸�M�� -->
    <tr>
      <td>&nbsp;</td>
      <td><input name="ieet_result" type="checkbox" value="1" checked disabled="true">
      �[��IEET�ݨ����G (Result of IEET)</td>
    </tr>
    <!-- end modify -->
    <tr bgcolor="#6699FF">
      <td><strong>�Q�װ� (Discussion)</strong></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="discuss" type="checkbox" value="1"  <?php echo is_check($row["discuss"]);?> disabled="true">
      �ҵ{�Q�װ� (Bulletin board)</td>
    </tr>
    <!-- modify by w60292 @ 20090421 �s�W�i�Ŀ諸�M�� -->
    <tr>
      <td>&nbsp;</td>
      <td><input name="reservation" type="checkbox" value="1"  <?php echo is_check($row["reservation"]);?> disabled="true">
      �w�������줽�� (Reservation of Network Office)</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="chat" type="checkbox" value="1"  <?php echo is_check($row["chat"]);?> disabled="true">
      �e�������줽�� (Go to Network Office)</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="recording" type="checkbox" value="1"  <?php echo is_check($row["recording"]);?> disabled="true">
      ���v�ɺ޲z (Recording Management)</td>
    </tr>
    <!-- end modify -->
<!--
    <tr>
      <td>&nbsp;</td>
      <td><input name="chat" type="checkbox" value="1"  <!?php echo is_check($row["chat"]);?>>
      �u�W�Q�װ� </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="talk_voc" type="checkbox" value="1"  <!?php echo is_check($row["talk_voc"]);?>>
      �y����ѫ� </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="talk_int" type="checkbox" value="1"  <!?php echo is_check($row["talk_int"]);?> >
      ���ʲ�ѫ� </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="eboard" type="checkbox" value="1"  <!?php echo is_check($row["eboard"]);?>>
      EBoard </td>
    </tr>
-->
    <tr bgcolor="#6699FF">
      <td><strong>�ǲ߰l�� (Traces)</strong></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="strank" type="checkbox" value="1"  <?php echo is_check($row["strank"]);?>>
      �t�ΨϥΰO�� (Ranking)</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="chrank" type="checkbox" value="1"  <?php echo is_check($row["chrank"]);?>>
      �Ч��s���O�� (Browsing)</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="sttrace" type="checkbox" value="1"  <?php echo is_check($row["sttrace"]);?>>
      �ǥͭӧO�O�� (Acivities)</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="complete" type="checkbox" value="1"  <?php echo is_check($row["complete"]);?>>
      �O������C�� (Complete list)</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="rollbook" type="checkbox" value="1"  <?php echo is_check($row["rollbook"]);?> disabled="true">
      �I�Wï (Roll book)</td>
    </tr>
    <!-- modify by w60292 @ 20090421 �s�W�i�Ŀ諸�M�� -->
    <tr>
      <td>&nbsp;</td>
      <td><input name="eroll" type="checkbox" value="1"  <?php echo is_check($row["eroll"]);?>>
      �q�l�I�W (Election Roll)</td>
    </tr>
    <!-- end modify -->
    <tr bgcolor="#6699FF">
      <td><strong>�ǥͺ޲z (ST. Management)</strong></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="tsins" type="checkbox" value="1"  <?php echo is_check($row["tsins"]);?> disabled="true">
      �ǥͷs�W (New)</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="tsdel" type="checkbox" value="1"  <?php echo is_check($row["tsdel"]);?> >
      �ǥͧR�� (Delete)</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="tsmod" type="checkbox" value="1"  <?php echo is_check($row["tsmod"]);?>>
      �ǥͭק� (Modify)</td>
    </tr>
<!--    <tr>
      <td>&nbsp;</td>
      <td><input name="tschg" type="checkbox" value="1"  <?php echo is_check($row["tschg"]);?>>
      �ק鶴��</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="tsquery" type="checkbox" value="1"  <?php echo is_check($row["tsquery"]);?> disabled="true">
      �ǥ͸�Ƭd��</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="psswd" type="checkbox" value="1"  <?php echo is_check($row["psswd"]);?>>
      �d�߾ǥͱK�X</td>
    </tr>
-->  </table>

  <p>
  	<input name="modify" type="hidden" value="1">
    <input type="submit" name="Submit" value="�e�X�ק� (Submit)">
  </p>
<?php  
   				}

		}  
?>  
</form>


</body>
</html>
