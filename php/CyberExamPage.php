<?php session_start();?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<HTML><HEAD><TITLE>中正大學 數位學習中心</TITLE>

<base target="_self">

</HEAD>
<BODY >
<TABLE cellSpacing=0 cellPadding=0 width=722 align=center border=0>
  <TBODY>
  <TR vAlign=top bgColor=#99cccc>
    <TD colSpan=2>
	<img border="0" src="head.gif" width="725" height="127"></TD></TR>
  <TR>
    <TD bgColor=#ffffcc><!--問卷開始-->
      <TABLE id=Table2 cellSpacing=0 cellPadding=0 align=center border=0>
        <TBODY>
        <TR>
          <TD align=middle></TD></TR></TBODY></TABLE><BR>
      <CENTER><font face="標楷體" size="5">國立中正大學數位學習中心</font><FONT face=標楷體 color=#000000 
      size=5> 數位教材課程 測驗系統</FONT>
      <CENTER>
      <TABLE 
      style="BORDER-LEFT-COLOR: blue; BORDER-BOTTOM-COLOR: blue; BORDER-TOP-COLOR: blue; BORDER-COLLAPSE: collapse; BORDER-RIGHT-COLOR: blue" 
      cellPadding=5 width="90%" border=1>
        <TBODY>
        <TR>
          <TD vAlign=top align=left>
            <P style="LINE-HEIGHT: 150%"><FONT 
            color=#000000>親愛的同學：您好！感謝您的學習，在此要作一些測驗，若無達到標準成績兩次，你就會被當掉！！！！！好好的做吧！！！寶貝！！！！</FONT></P></TD></TR></TBODY></TABLE>
      <FORM name=FF onsubmit="javascript:return Check(this);" action=CyberRecord.php method=POST>
      <INPUT type=hidden name=Admin> 
      <INPUT type=hidden name=Relation> 
      <INPUT type=hidden value=0 name=sign> 
      <TABLE 
      style="BORDER-LEFT-COLOR: blue; BORDER-BOTTOM-COLOR: blue; BORDER-TOP-COLOR: blue; BORDER-COLLAPSE: collapse; BORDER-RIGHT-COLOR: blue" 
      cellPadding=5 width="90%" border=1 id="table10">
        <TBODY>
        
        <?php
        //連結資料庫位址
        $db = mysql_connect ('localhost', 'study', 'webedu@study') or die("無法連結資料庫.");
        //取得資料庫
				mysql_select_db ('testbase') or die ("找不到資料庫");
				if($_GET[se]==null)
					$chap = 'SELECT * FROM '.$_GET[db].' WHERE chapter= '.$_GET[ch] ;
				else
					$chap = 'SELECT * FROM '.$_GET[db].' WHERE chapter= '.$_GET[ch].' AND section='.$_GET[se] ;
				//echo $chap;
				//echo '<br>';
				//查詢字串pretest
				$result = mysql_query("$chap") or die("你所下的指令對資料庫無效");
				//傳回查詢字串的列的數目(幾題)
				$num=mysql_num_rows($result);
				//變數存0~(題目數-1)的數字
				$item_seq=range(0, $num-1);

				//變數存選項數字(1~4)
				$choice_seq=range(1,4);
				//設定亂數種子
				srand((float)microtime() * 1000000);
				//將$item_seq內的$num個數字打亂 ex:0 1 2 3 4 變成 4 2 0 3 1
				shuffle($item_seq);
				if($num<5)
					$count=$num;
				else if($num >= 5)
					$count=5;	
				//他會把所有題目列出來，可以設定列幾個取代$num,這邊設10
				for($i=0;$i< $count;$i++)
				{
					
					
					
        	echo '<TR style="BACKGROUND-COLOR: #e0e0e0">';
        	echo '<TD><FONT color=#000000><B>';
        	
        	//印出題目 第$i+1題  查詢result內第$i行 的 "question" 字串  印出題目
        	//因為$item_seq已經被打亂了  所以會用打亂的順序把題目印出來
        	echo ($i+1).'、'.mysql_result($result, $item_seq[$i],"question").'</B></FONT></TD></TR>';

					if(mysql_result($result, $item_seq[$i],"answer1") == -1 )
					{		//是非題
						  echo '<TR style="BACKGROUND-COLOR: #ffffff">';
          		echo '<TD><FONT color=#000000>';
							echo '<INPUT type=radio value=1 name=Q'.($i+1).'>True';
							echo '<INPUT type=radio value=0 name=Q'.($i+1).'>False';
							
							echo '</FONT></TD></TR>';
							$answer[0] = -1 ;
          	 	$answer[1] = mysql_result($result, $item_seq[$i],"answer2") ;
					}
          else
          {	//選擇題
         	 	shuffle($choice_seq);			//打亂選項的順序 ex: 1 2 3 4 變成 3 4 2 1
          	for($j=0;$j<4;$j++)
          	{
          		//取出答案放進陣列
          		if(mysql_result($result, $item_seq[$i],"answer".$choice_seq[$j]) == 1) 
          			$answer[$j] = 1 ;
          		else
          			$answer[$j] = 0 ;
          		//show出選項
          		$theChoice = "choice".$choice_seq[$j];
          	          	
        			echo '<TR style="BACKGROUND-COLOR: #ffffff">';
          		echo '<TD><FONT color=#000000>';
							echo '<INPUT type=checkbox value=A'.($i+1).($j+1).' name=Q'.($i+1).($j+1).'>'.($j+1).'.'.mysql_result($result, $item_seq[$i],$theChoice);
							echo '</FONT></TD></TR>';
        		}
        	}
        	//echo 'answer:'.$answer[$i];									//debug used
        	$ans[$i]=$answer;//這樣就可以變成二維的了
        	//測試用  先把解答show出來
        	//echo '第'.($i+1).'題,答案順序為 : '.$ans[$i][0].",".$ans[$i][1].",".$ans[$i][2].",".$ans[$i][3];
        }
        
        
        $_SESSION['answer_seq']=$ans;
        $_SESSION['item_seq']=$item_seq;
?>
</TBODY></TABLE>
<SCRIPT language=JavaScript>
<!--
function Check(formname){
//get question number
var QNameArr = new Array(
													<?php if($count>0) 
																{ 
																	$check_list="'Q1'"; 
																	for($i=2;$i<=$count;$i++) 
																		$check_list=$check_list.",'Q".$i."'";
																} 
																echo $check_list ;
													?>
												);
//alert(QNameArr);				
for(i=0;i<QNameArr.length;i++){
	var Flag = false;
	
	if(document.getElementsByName( QNameArr[i] ).length > 1)
	{
		for(j=0;j<document.all(QNameArr[i]).length;j++){
			Flag = Flag || document.all(QNameArr[i])[j].checked;
		}
	}
	else
	{
		for(j=1;j<5;j++){
			Flag = Flag || document.all(QNameArr[i] + j).checked;
		}
	}
	if(Flag == false){
		alert('請回答'+QNameArr[i]+'的問題!!');
		return false;
	}
}
	/*try 
	{
		if (document.all("UClass").value =="" || document.all("UClass").value ==null)
		{
			document.all("UClass").focus();
			alert('請填寫系級!!');
			return false;
		}
		if (document.all("UID").value =="" || document.all("UID").value ==null)
		{
			document.all("UID").focus();
			alert('請填寫學號!!');
			return false;
		}
	}
	catch(e)
	{//document.write(e) ;
	}*/
	
}

//-->
</SCRIPT>
      <BR>
      
      <CENTER><BR><INPUT type=submit value="  送出  "> 
      </CENTER></FORM><!--問卷結束(成績計算會到record去做)--></CENTER></CENTER></TD></TR>
  <TR>
    <TD background=bottom.gif bgColor="#ffffcc" colSpan=2 height=48 align=center ><img border="0" src="bottom.gif" width="721" height="36">	
    </TD></TR></TBODY></TABLE></BODY></HTML>