<?php
	require 'fadmin.php';
	if($action=="upload")
	{	
		if($uploadfile1 != "none")
		{
			$ext = strrchr( $uploadfile1_name, '.' );	
			$filename="index".$ext;
			fileupload ( $uploadfile1, $location, $filename ) ;
			header( "Location: ./upload_old_intro.php?course_id=$course_id&year=$year&term=$term&PHPSESSID=".session_id());
		}else{
			echo "�S������ɮ�<br>";
		}
	}elseif($action=="delete")
	{
		$target = realpath($path);
		unlink($target."/".$filename);
		header( "Location: ./upload_old_intro.php?course_id=$course_id&year=$year&term=$term&PHPSESSID=".session_id());
	}
	else{	
		
		$location="../../echistory/".$year."/".$term."/".$course_id."/intro";
		//�W���ɮ�
		$title="��".$year."�Ǧ~�ײ�".$term."�Ǵ�";
		$handle = dir($location);
		
		echo"
			<HTML>
			<link rel='stylesheet' href='/images/skinSKINNUM/css/main-body.css' type='text/css'>
			<BODY background='/images/img/bg.gif'>
			<p>
			<center>
			<SCRIPT>
			function check()
			{
				if(form1.uploadfile1.value==\"\")
				{
					alert(\"�W���ɮפ��i����!!!\");
					return false;
				}
			}
			</SCRIPT>
			<form ENCTYPE=multipart/form-data method=POST action=upload_old_intro.php name=form1>
			<input type=hidden name=action value=upload>
			<input type=hidden name=location value=".$location.">
			<font color='#0000FF'><p><b>".$title."�ҵ{�j���G</b></p></font>
			<a href=\"./upload_intro.php?year_term=".$year."_".$term."\">�^��ҵ{�C��</a>
			<br>
			<hr>
			<BR>
			�W�Ǥ��ɮת����ɦW�ݬ�<font color=\"#FF0000\">\"htm\"</font>�B<font color=\"#FF0000\">\"html\"</font>�B<font color=\"#FF0000\">\"doc\"</font>�B<font color=\"#FF0000\">\"pdf\"</font>�B<font color=\"#FF0000\">\"ppt\"</font>�o�X�خ榡�A��i��ܡC<br>
			<br>
			�W���ɮ� : <INPUT TYPE=FILE NAME=uploadfile1 SIZE=20><br>
			<INPUT TYPE=SUBMIT VALUE=�W���ɮ� Onclick=\"return check();\">
			<INPUT TYPE=RESET VALUE=�M��>
			<input type=hidden name=year value=$year>
			<input type=hidden name=term value=$term>
			</form><BR>
			";
			
		$i=false;
		while (  false !== ( $file = $handle->read() ) ) {
			if(strcmp($file,".") !=0 && strcmp($file,"..") !=0 ) {   
			// ���F '.' ���~���ɮ׿�X
				$file;
				$location."/".urlencode($file);
				$size= filesize($location."/".stripslashes($file));
				$size=sprintf("%.3f",($size)/1024);
				$date= date("Y-m-d H:i:s",filemtime($location."/".$file));
				echo "
				    <hr>
					<table border=\"1\">
					<tr bgcolor=\"#4d6be2\">
						<td><font color=#ffffff>�ɦW</font>
						<td><font color=#ffffff>�ɮפj�p</font>
						<td><font color=#ffffff>�̫�ק���</font>
						<td><font color=#ffffff>�R���ɮ�</font>
					</tr>
					<tr bgcolor=#edf3fa>						
					<td><a href=\"$location/$file \">$file</a>
					<td>".$size."KB
					<td>".$date."
					<td><a href=\"upload_old_intro.php?year=$year&term=$term&action=delete&filename=".$file."&path=".$location."\" onclick=\"return confirm('�A�T�w�n�R���o���ɮ׶�?');\">�R���o���ɮ�</a>
					</tr>
					</table>
					";
					
					
			}
		}
		$handle->close();			
		echo "</center>
			  </body>
			  </html>";
	}
?>
