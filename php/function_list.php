<?php 
require 'fadmin.php';
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<title>功能列表</title>
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
/*  課程資訊  */
/**************/
// $news, $intro, $sched, $info, $tein, $officehr, $core, $evaluate 		$tgins, $tgdel, $tgmod, $tgquery, $warning, $upload, 
if($news!="1") //公佈欄
	$news="1";	
if($intro!="1")//授課大綱
	$intro="1";	
if($sched!="1")//課程安排
	$sched="0";	
if($info!="1") //助教資料
	$info="1";	
if($tein!="1") //教師資料
	$tein="1";
if($officehr!="1")//辦公室時間
	$officehr="0";
if($core!="1")   //課程內涵
	$core="0";
if($evaluate!="1")//課程自評
	$evaluate="0";
	
/**************/
/*  成績系統  */
/**************/
if($tgins!="1")	//成績新增
	$tgins="1";	
if($tgdel!="1") //成績刪除
	$tgdel="1";	
if($tgmod!="1")	//成績修改
	$tgmod="1";	
if($tgquery!="1")//成績查詢
	$tgquery="1";	
if($warning!="1")//預警系統
	$warning="1";

/**************/
/*  授課教材  */
/**************/
//	$editor, $online, $material, $import, $create_work, 			$modify_work, $check_work,$create_test, $modify_test, $create_case,
	
if($upload!="1") //上傳檔案
	$upload="1";
if($editor!="1") //編輯工具
	$editor="1";	
if($online!="1") //隨選視訊
	$online="0";	
if($material!="1")//教材預覽
	$material="1";	
if($import!="1") //教材匯入
	$import="1";	

/**************/	
/*  線上作業  */
/**************/
if($create_work!="1") //出新作業
	$create_work="1";		
if($modify_work!="1") //修改作業
	$modify_work="1";	
if($check_work!="1")  //觀看作業
	$check_work="1";		

/**************/
/*  線上測驗  */
/**************/
if($create_test!="1") //製作測驗
	$create_test="1";	
if($modify_test!="1") //修改測驗
	$modify_test="1";	

/********************/
/*  目前沒用的功能  */
/********************/
//$mag_case, $check_case, $create_qs, $modify_qs, $discuss, 		$chat, $reservation, $recording, $talk_voc, $talk_int, $eboard, $strank,
if($create_case!="1")	//新增專案
	$create_case="0";	
if($mag_case!="1")	//專案管理
	$mag_case="0";	
if($check_case!="1")	//合作學習
	$check_case="0";

if($eboard!="1")  //電子公佈欄
	$eboard="0";
if($talk_voc!="1")//語音聊天室
        $talk_voc="0";
if($talk_int!="1")//互動聊天室
        $talk_int="0";
if($tschg!="1")	//修改身分
        $tschg="0";
if($tsquery!="1")//學生資料查詢
        $tsquery="1";
if($psswd!="1")	//查詢學生密碼
        $psswd="0";

/**************/
/*  線上問卷  */
/**************/
if($create_qs!="1") //製作問卷
	$create_qs="0";	
if($modify_qs!="1") //修改問卷
	$modify_qs="0";		

/**************/
/*   討論區   */
/**************/
if($discuss!="1") //課程討論區
	$discuss="1";
if($chat!="1")	  //前往網路辦公室
	$chat="1";
if($reservation!="1")//預約網路辦公室
	$reservation="0";
if($recording!="1")//錄影檔管理
	$recording="0";

/**************/
/*  學習追蹤  */
/**************/
//$chrank, $sttrace, $complete, $rollbook, $eroll, $tsins, 				$tsdel, $tsmod, $tschg, $tsquery, $psswd;	
if($strank!="1") //系統使用記錄 
	$strank="0";		
if($chrank!="1") //教材瀏覽記錄
	$chrank="0";	
if($sttrace!="1")//學生個別記錄
	$sttrace="0";	
if($complete!="1")//記錄完整列表
	$complete="0";	
if($rollbook!="1")//點名簿
	$rollbook="1";
if($eroll!="1")	 //電子點名
	$eroll="0";

/**************/
/*  學生管理  */
/**************/
if($tsins!="1")	//學生新增
	$tsins="1";			
if($tsdel!="1")	//學生刪除
	$tsdel="0";	
if($tsmod!="1")	//學生修改
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
			echo "資料庫連結錯誤!!";
			//return $error;
		}else {
		
				if ( !( mysql_db_query( "study".$course_id, $m_sql )) ) {
					echo "資料庫更新錯誤!!";
					//echo "$m_sql";
					//return $error;
				}else{
					echo "<font color=red>資料已更新，請重新整理頁面(Ctrl + F5)！</font>";
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
			echo "資料庫連結錯誤!!";
			//return $error;
		}else {
		
				if ( !($result = mysql_db_query( "study".$course_id, $sql )) ) {
					echo "資料庫讀取錯誤!!";
					//return $error;
				}
				else {
					$row = mysql_fetch_array ($result) ;

?>
  <table width="50%" border="1">
    <tr bgcolor="#6699FF">
      <td width="40%"><strong>課程資訊 (Course Information)</strong></td>
      <td width="60%">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="news" type="checkbox" value="1"  <?php echo is_check($row["news"]);?> disabled="true">      
      公佈欄 (News) </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="intro" type="checkbox" value="1"  <?php echo is_check($row["intro"]);?> disabled="true">
      授課大綱 (Syllabus) </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="sched" type="checkbox" value="1"  <?php echo is_check($row["sched"]);?>>
      課程安排 (Weekly Schedule)</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="info" type="checkbox" value="1"    <?php echo is_check($row["info"]);?> disabled="true">
      助教資料 (TA Info)</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="tein" type="checkbox" value="1"    <?php echo is_check($row["tein"]);?> disabled="true">
      教師資料 (Pro. Info)</td>
    </tr>
	<tr>
      <td>&nbsp;</td>
      <td><input name="tsquery" type="checkbox" value="1"  <?php echo is_check($row["tsquery"]);?> disabled="true">
      學生資料查詢 (Query Students)</td>
    </tr>
	<tr>
      <td>&nbsp;</td>
      <td><input name="funclist" type="checkbox" value="1" checked  disabled="true">
      系統功能設定 (Function Setup)</td>
    </tr>
    <!-- modify by w60292 @ 20090421 新增可勾選的清單 -->
	<tr>
      <td>&nbsp;</td>
      <td><input name="officehr" type="checkbox" value="1" <?php echo is_check($row["officehr"]);?>>
      辦公室時間 (Office Hour)</td>
    </tr>
	<tr>
      <td>&nbsp;</td>
      <td><input name="core" type="checkbox" value="1" <?php echo is_check($row["core"]);?>>
      課程內涵 (Core Competencies)</td>
    </tr>
	<tr>
      <td>&nbsp;</td>
      <td><input name="evaluate" type="checkbox" value="1" <?php echo is_check($row["evaluate"]);?>>
      課程自評 (Self Evaluate)</td>
    </tr>
    <!-- end modify -->
	  <tr bgcolor="#6699FF">
      <td><strong>成績系統 (Score)</strong></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="tgins" type="checkbox" value="1" <?php echo is_check($row["tgins"]);?> disabled="true">
      成績新增 (New Item)</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="tgdel" type="checkbox" value="1"  <?php echo is_check($row["tgdel"]);?> disabled="true">
      成績刪除 (Delete Item)</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="tgmod" type="checkbox" value="1"  <?php echo is_check($row["tgmod"]);?> disabled="true">
      成績修改 (Edit Item)</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="tgquery" type="checkbox" value="1"  <?php echo is_check($row["tgquery"]);?> disabled="true">
      成績查詢 (Score Listing)</td>
    </tr>
	
	<!-- 新增顯示學生端各種排名(一般測驗、線上測驗、線上作業及總排名)的權限-->
	<tr>
      <td>&nbsp;</td>
      <td><input name="show_test_rank" type="checkbox" value="1"  <?php echo is_check($row["show_test_rank"]);?>>
      顯示一般測驗排名 (Test Rank)</td>
    </tr>
	<tr>
      <td>&nbsp;</td>
      <td><input name="show_onlinetest_rank" type="checkbox" value="1"  <?php echo is_check($row["show_onlinetest_rank"]);?>>
      顯示線上測驗排名 (Online Test Rank)</td>
    </tr>
	<tr>
      <td>&nbsp;</td>
      <td><input name="show_homework_rank" type="checkbox" value="1"  <?php echo is_check($row["show_homework_rank"]);?>>
      顯示線上作業排名 (Homework Rank)</td>
    </tr>
	<tr>
      <td>&nbsp;</td>
      <td><input name="show_all_rank" type="checkbox" value="1"  <?php echo is_check($row["show_all_rank"]);?>>
      顯示總排名 (Rank)</td>
    </tr>
	
	
	
    <!-- modify by w60292 @ 20090421 新增可勾選的清單 -->
    <tr>
      <td>&nbsp;</td>
      <td><input name="warning" type="checkbox" value="1" <?php echo is_check($row["warning"]);?> >
      預警系統 (Caution)</td>
    </tr>
    <!-- modify end -->
	<tr bgcolor="#6699FF">
      <td><strong>授課教材 (Courseware)</strong></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="upload" type="checkbox" value="1"  <?php echo is_check($row["upload"]);?> disabled="true">
      上傳檔案 (File upload)</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="editor" type="checkbox" value="1"  <?php echo is_check($row["editor"]);?> disabled="true">
      編輯工具 (Authoring Tool)</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="online" type="checkbox" value="1"  <?php echo is_check($row["online"]);?>>
      隨選視訊 (Vedio Material)</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="material" type="checkbox" value="1"  <?php echo is_check($row["material"]);?> disabled="true">
      教材預覽 (Preview)</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="import" type="checkbox" value="1"  <?php echo is_check($row["import"]);?> disabled="true">
      教材匯入 (Import)</td>
    </tr>
	<tr bgcolor="#6699FF">
	<td><strong>線上作業 (Homework)</strong></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="create_work" type="checkbox" value="1"  <?php echo is_check($row["create_work"]);?> disabled="true">
      出新作業 (New)</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="modify_work" type="checkbox" value="1"  <?php echo is_check($row["modify_work"]);?> disabled="true">
      修改作業 (Edit)</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="check_work" type="checkbox" value="1"  <?php echo is_check($row["check_work"]);?> disabled="true">
      觀看作業 (Preview)</td>
	</tr> 
    <tr bgcolor="#6699FF">
      <td><strong>線上測驗 (Online Quiz)</strong></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="create_test" type="checkbox" value="1"  <?php echo is_check($row["create_test"]);?> disabled="true">
      製作測驗 (New)</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="modify_test" type="checkbox" value="1"  <?php echo is_check($row["modify_test"]);?> disabled="true">
      修改測驗 (Edit)</td>
    </tr>	 
<!--    <tr bgcolor="#6699FF">
      <td><strong>合作學習</strong></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="create_case" type="checkbox" value="1"  <?php echo is_check($row["create_case"]);?>>
      新增專案 </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="mag_case" type="checkbox" value="1"  <?php echo is_check($row["mag_case"]);?>>
      專案管理</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="check_case" type="checkbox" value="1"  <?php echo is_check($row["check_case"]);?>>
      觀看專案 </td>
    </tr>
-->
    <tr bgcolor="#6699FF">
      <td><strong>線上問卷 (Questionary)</strong></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="create_qs" type="checkbox" value="1" checked disabled="true">
      製作問卷 (New)</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="modify_qs" type="checkbox" value="1"  <?php echo is_check($row["modify_qs"]);?>>
      修改問卷 (Edit)</td>
    </tr>
    <!-- modify by w60292 @ 20090421 新增可勾選的清單 -->
    <tr>
      <td>&nbsp;</td>
      <td><input name="ieet_result" type="checkbox" value="1" checked disabled="true">
      觀看IEET問卷結果 (Result of IEET)</td>
    </tr>
    <!-- end modify -->
    <tr bgcolor="#6699FF">
      <td><strong>討論區 (Discussion)</strong></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="discuss" type="checkbox" value="1"  <?php echo is_check($row["discuss"]);?> disabled="true">
      課程討論區 (Bulletin board)</td>
    </tr>
    <!-- modify by w60292 @ 20090421 新增可勾選的清單 -->
    <tr>
      <td>&nbsp;</td>
      <td><input name="reservation" type="checkbox" value="1"  <?php echo is_check($row["reservation"]);?> disabled="true">
      預約網路辦公室 (Reservation of Network Office)</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="chat" type="checkbox" value="1"  <?php echo is_check($row["chat"]);?> disabled="true">
      前往網路辦公室 (Go to Network Office)</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="recording" type="checkbox" value="1"  <?php echo is_check($row["recording"]);?> disabled="true">
      錄影檔管理 (Recording Management)</td>
    </tr>
    <!-- end modify -->
<!--
    <tr>
      <td>&nbsp;</td>
      <td><input name="chat" type="checkbox" value="1"  <!?php echo is_check($row["chat"]);?>>
      線上討論區 </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="talk_voc" type="checkbox" value="1"  <!?php echo is_check($row["talk_voc"]);?>>
      語音聊天室 </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="talk_int" type="checkbox" value="1"  <!?php echo is_check($row["talk_int"]);?> >
      互動聊天室 </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="eboard" type="checkbox" value="1"  <!?php echo is_check($row["eboard"]);?>>
      EBoard </td>
    </tr>
-->
    <tr bgcolor="#6699FF">
      <td><strong>學習追蹤 (Traces)</strong></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="strank" type="checkbox" value="1"  <?php echo is_check($row["strank"]);?>>
      系統使用記錄 (Ranking)</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="chrank" type="checkbox" value="1"  <?php echo is_check($row["chrank"]);?>>
      教材瀏覽記錄 (Browsing)</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="sttrace" type="checkbox" value="1"  <?php echo is_check($row["sttrace"]);?>>
      學生個別記錄 (Acivities)</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="complete" type="checkbox" value="1"  <?php echo is_check($row["complete"]);?>>
      記錄完整列表 (Complete list)</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="rollbook" type="checkbox" value="1"  <?php echo is_check($row["rollbook"]);?> disabled="true">
      點名簿 (Roll book)</td>
    </tr>
    <!-- modify by w60292 @ 20090421 新增可勾選的清單 -->
    <tr>
      <td>&nbsp;</td>
      <td><input name="eroll" type="checkbox" value="1"  <?php echo is_check($row["eroll"]);?>>
      電子點名 (Election Roll)</td>
    </tr>
    <!-- end modify -->
    <tr bgcolor="#6699FF">
      <td><strong>學生管理 (ST. Management)</strong></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="tsins" type="checkbox" value="1"  <?php echo is_check($row["tsins"]);?> disabled="true">
      學生新增 (New)</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="tsdel" type="checkbox" value="1"  <?php echo is_check($row["tsdel"]);?> >
      學生刪除 (Delete)</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="tsmod" type="checkbox" value="1"  <?php echo is_check($row["tsmod"]);?>>
      學生修改 (Modify)</td>
    </tr>
<!--    <tr>
      <td>&nbsp;</td>
      <td><input name="tschg" type="checkbox" value="1"  <?php echo is_check($row["tschg"]);?>>
      修改身分</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="tsquery" type="checkbox" value="1"  <?php echo is_check($row["tsquery"]);?> disabled="true">
      學生資料查詢</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="psswd" type="checkbox" value="1"  <?php echo is_check($row["psswd"]);?>>
      查詢學生密碼</td>
    </tr>
-->  </table>

  <p>
  	<input name="modify" type="hidden" value="1">
    <input type="submit" name="Submit" value="送出修改 (Submit)">
  </p>
<?php  
   				}

		}  
?>  
</form>


</body>
</html>
