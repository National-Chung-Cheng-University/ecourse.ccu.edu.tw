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
			echo "沒有選取檔案<br>";
		}
	}elseif($action=="delete")
	{
		$target = realpath($path);
		unlink($target."/".$filename);
		header( "Location: ./upload_old_intro.php?course_id=$course_id&year=$year&term=$term&PHPSESSID=".session_id());
	}
	else{	
		
		$location="../../echistory/".$year."/".$term."/".$course_id."/intro";
		//上傳檔案
		$title="第".$year."學年度第".$term."學期";
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
					alert(\"上傳檔案不可為空!!!\");
					return false;
				}
			}
			</SCRIPT>
			<form ENCTYPE=multipart/form-data method=POST action=upload_old_intro.php name=form1>
			<input type=hidden name=action value=upload>
			<input type=hidden name=location value=".$location.">
			<font color='#0000FF'><p><b>".$title."課程大綱：</b></p></font>
			<a href=\"./upload_intro.php?year_term=".$year."_".$term."\">回到課程列表</a>
			<br>
			<hr>
			<BR>
			上傳之檔案的附檔名需為<font color=\"#FF0000\">\"htm\"</font>、<font color=\"#FF0000\">\"html\"</font>、<font color=\"#FF0000\">\"doc\"</font>、<font color=\"#FF0000\">\"pdf\"</font>、<font color=\"#FF0000\">\"ppt\"</font>這幾種格式，方可顯示。<br>
			<br>
			上傳檔案 : <INPUT TYPE=FILE NAME=uploadfile1 SIZE=20><br>
			<INPUT TYPE=SUBMIT VALUE=上傳檔案 Onclick=\"return check();\">
			<INPUT TYPE=RESET VALUE=清除>
			<input type=hidden name=year value=$year>
			<input type=hidden name=term value=$term>
			</form><BR>
			";
			
		$i=false;
		while (  false !== ( $file = $handle->read() ) ) {
			if(strcmp($file,".") !=0 && strcmp($file,"..") !=0 ) {   
			// 除了 '.' 之外的檔案輸出
				$file;
				$location."/".urlencode($file);
				$size= filesize($location."/".stripslashes($file));
				$size=sprintf("%.3f",($size)/1024);
				$date= date("Y-m-d H:i:s",filemtime($location."/".$file));
				echo "
				    <hr>
					<table border=\"1\">
					<tr bgcolor=\"#4d6be2\">
						<td><font color=#ffffff>檔名</font>
						<td><font color=#ffffff>檔案大小</font>
						<td><font color=#ffffff>最後修改日期</font>
						<td><font color=#ffffff>刪除檔案</font>
					</tr>
					<tr bgcolor=#edf3fa>						
					<td><a href=\"$location/$file \">$file</a>
					<td>".$size."KB
					<td>".$date."
					<td><a href=\"upload_old_intro.php?year=$year&term=$term&action=delete&filename=".$file."&path=".$location."\" onclick=\"return confirm('你確定要刪除這個檔案嗎?');\">刪除這個檔案</a>
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
