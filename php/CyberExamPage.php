<?php session_start();?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<HTML><HEAD><TITLE>�����j�� �Ʀ�ǲߤ���</TITLE>

<base target="_self">

</HEAD>
<BODY >
<TABLE cellSpacing=0 cellPadding=0 width=722 align=center border=0>
  <TBODY>
  <TR vAlign=top bgColor=#99cccc>
    <TD colSpan=2>
	<img border="0" src="head.gif" width="725" height="127"></TD></TR>
  <TR>
    <TD bgColor=#ffffcc><!--�ݨ��}�l-->
      <TABLE id=Table2 cellSpacing=0 cellPadding=0 align=center border=0>
        <TBODY>
        <TR>
          <TD align=middle></TD></TR></TBODY></TABLE><BR>
      <CENTER><font face="�з���" size="5">��ߤ����j�ǼƦ�ǲߤ���</font><FONT face=�з��� color=#000000 
      size=5> �Ʀ�Ч��ҵ{ ����t��</FONT>
      <CENTER>
      <TABLE 
      style="BORDER-LEFT-COLOR: blue; BORDER-BOTTOM-COLOR: blue; BORDER-TOP-COLOR: blue; BORDER-COLLAPSE: collapse; BORDER-RIGHT-COLOR: blue" 
      cellPadding=5 width="90%" border=1>
        <TBODY>
        <TR>
          <TD vAlign=top align=left>
            <P style="LINE-HEIGHT: 150%"><FONT 
            color=#000000>�˷R���P�ǡG�z�n�I�P�±z���ǲߡA�b���n�@�@�Ǵ���A�Y�L�F��зǦ��Z�⦸�A�A�N�|�Q���I�I�I�I�I�n�n�����a�I�I�I�_���I�I�I�I</FONT></P></TD></TR></TBODY></TABLE>
      <FORM name=FF onsubmit="javascript:return Check(this);" action=CyberRecord.php method=POST>
      <INPUT type=hidden name=Admin> 
      <INPUT type=hidden name=Relation> 
      <INPUT type=hidden value=0 name=sign> 
      <TABLE 
      style="BORDER-LEFT-COLOR: blue; BORDER-BOTTOM-COLOR: blue; BORDER-TOP-COLOR: blue; BORDER-COLLAPSE: collapse; BORDER-RIGHT-COLOR: blue" 
      cellPadding=5 width="90%" border=1 id="table10">
        <TBODY>
        
        <?php
        //�s����Ʈw��}
        $db = mysql_connect ('localhost', 'study', 'webedu@study') or die("�L�k�s����Ʈw.");
        //���o��Ʈw
				mysql_select_db ('testbase') or die ("�䤣���Ʈw");
				if($_GET[se]==null)
					$chap = 'SELECT * FROM '.$_GET[db].' WHERE chapter= '.$_GET[ch] ;
				else
					$chap = 'SELECT * FROM '.$_GET[db].' WHERE chapter= '.$_GET[ch].' AND section='.$_GET[se] ;
				//echo $chap;
				//echo '<br>';
				//�d�ߦr��pretest
				$result = mysql_query("$chap") or die("�A�ҤU�����O���Ʈw�L��");
				//�Ǧ^�d�ߦr�ꪺ�C���ƥ�(�X�D)
				$num=mysql_num_rows($result);
				//�ܼƦs0~(�D�ؼ�-1)���Ʀr
				$item_seq=range(0, $num-1);

				//�ܼƦs�ﶵ�Ʀr(1~4)
				$choice_seq=range(1,4);
				//�]�w�üƺؤl
				srand((float)microtime() * 1000000);
				//�N$item_seq����$num�ӼƦr���� ex:0 1 2 3 4 �ܦ� 4 2 0 3 1
				shuffle($item_seq);
				if($num<5)
					$count=$num;
				else if($num >= 5)
					$count=5;	
				//�L�|��Ҧ��D�ئC�X�ӡA�i�H�]�w�C�X�Ө��N$num,�o��]10
				for($i=0;$i< $count;$i++)
				{
					
					
					
        	echo '<TR style="BACKGROUND-COLOR: #e0e0e0">';
        	echo '<TD><FONT color=#000000><B>';
        	
        	//�L�X�D�� ��$i+1�D  �d��result����$i�� �� "question" �r��  �L�X�D��
        	//�]��$item_seq�w�g�Q���äF  �ҥH�|�Υ��ê����ǧ��D�ئL�X��
        	echo ($i+1).'�B'.mysql_result($result, $item_seq[$i],"question").'</B></FONT></TD></TR>';

					if(mysql_result($result, $item_seq[$i],"answer1") == -1 )
					{		//�O�D�D
						  echo '<TR style="BACKGROUND-COLOR: #ffffff">';
          		echo '<TD><FONT color=#000000>';
							echo '<INPUT type=radio value=1 name=Q'.($i+1).'>True';
							echo '<INPUT type=radio value=0 name=Q'.($i+1).'>False';
							
							echo '</FONT></TD></TR>';
							$answer[0] = -1 ;
          	 	$answer[1] = mysql_result($result, $item_seq[$i],"answer2") ;
					}
          else
          {	//����D
         	 	shuffle($choice_seq);			//���ÿﶵ������ ex: 1 2 3 4 �ܦ� 3 4 2 1
          	for($j=0;$j<4;$j++)
          	{
          		//���X���ש�i�}�C
          		if(mysql_result($result, $item_seq[$i],"answer".$choice_seq[$j]) == 1) 
          			$answer[$j] = 1 ;
          		else
          			$answer[$j] = 0 ;
          		//show�X�ﶵ
          		$theChoice = "choice".$choice_seq[$j];
          	          	
        			echo '<TR style="BACKGROUND-COLOR: #ffffff">';
          		echo '<TD><FONT color=#000000>';
							echo '<INPUT type=checkbox value=A'.($i+1).($j+1).' name=Q'.($i+1).($j+1).'>'.($j+1).'.'.mysql_result($result, $item_seq[$i],$theChoice);
							echo '</FONT></TD></TR>';
        		}
        	}
        	//echo 'answer:'.$answer[$i];									//debug used
        	$ans[$i]=$answer;//�o�˴N�i�H�ܦ��G�����F
        	//���ե�  ����ѵ�show�X��
        	//echo '��'.($i+1).'�D,���׶��Ǭ� : '.$ans[$i][0].",".$ans[$i][1].",".$ans[$i][2].",".$ans[$i][3];
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
		alert('�Ц^��'+QNameArr[i]+'�����D!!');
		return false;
	}
}
	/*try 
	{
		if (document.all("UClass").value =="" || document.all("UClass").value ==null)
		{
			document.all("UClass").focus();
			alert('�ж�g�t��!!');
			return false;
		}
		if (document.all("UID").value =="" || document.all("UID").value ==null)
		{
			document.all("UID").focus();
			alert('�ж�g�Ǹ�!!');
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
      
      <CENTER><BR><INPUT type=submit value="  �e�X  "> 
      </CENTER></FORM><!--�ݨ�����(���Z�p��|��record�h��)--></CENTER></CENTER></TD></TR>
  <TR>
    <TD background=bottom.gif bgColor="#ffffcc" colSpan=2 height=48 align=center ><img border="0" src="bottom.gif" width="721" height="36">	
    </TD></TR></TBODY></TABLE></BODY></HTML>