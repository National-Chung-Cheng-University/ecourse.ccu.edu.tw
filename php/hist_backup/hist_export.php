<?
	require 'fadmin.php';

	if(session_check_teach($PHPSESSID) != 2) {
		if ( $version == "C" )
			show_page("not_access.tpl", "你沒有權限執行此功能");
		else
			show_page("not_access.tpl", "You have no permission to perform this function.");
		exit();
	}
	$location="../../echistory/$hist_year/$hist_term/$course_id/textbook";
	/*
	大師兄新增
	*/
	mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("資料庫連結錯誤");
	
	$Q1 = "select * from hist_chaptitle WHERE year = '$hist_year' AND term ='$hist_term' AND course_id = '$course_id'";
	
	$result = mysql_db_query($DB, $Q1) or die("資料庫查詢錯誤,".$Q1);
	dumpChap_TitleTable($result,$location);
	
	exec( "cd $location;tar -zcvf ../textbook$course_id.tar.gz *");
	
	unlink($location."/TextBookDumped.sql");

	show_page_d ();

	function show_page_d ( $message = "" ) {
		global $version, $course_id, $hist_year, $hist_term, $is_hist;
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		$tpl->define ( array ( body => "hist_export.tpl" ) );
		if ( $version == "C" ) {
			$tpl->assign( IMG , "img" );
		}
		else {
			$tpl->assign( IMG , "img_E" );
		}
		$location="../../echistory/$hist_year/$hist_term/$course_id/textbook$course_id.tar.gz";
		if ( is_file ( "$location" ) )
			$tpl->assign( LINK , "<a href=\"$location\" >教材匯出</a>" );
		else
			$tpl->assign( LINK , "尚無相關教材檔案" );
		$tpl->assign( MSG , $message );
		$tpl->parse( BODY, "body" );
		$tpl->FastPrint("BODY");
	}		
	
	
/*
written by 大師兄
*/
function dumpChap_TitleTable($result,$location){
	$file = fopen($location."/TextBookDumped.sql","w");
	$Q3 = "insert into chap_title values";
	fwrite($file,$Q1);
	fwrite($file,$Q2);
	fwrite($file,$Q3);

	$num = mysql_num_rows($result);
	for($i=0;$i<$num;$i++){
	 $row = mysql_fetch_array($result);
	

	 if($i < $num-1){
	    fwrite($file,"(".$row['chap_aid'].",".$row['chap_num'].",'". $row['chap_title'] ."',".$row['sec_num'].",'".$row['sec_title']."'),");
	 }
	 else{
	    fwrite($file,"(".$row['chap_aid'].",".$row['chap_num'].",'".$row['chap_title'] ."',".$row['sec_num'].",'".$row['sec_title']."')");

	 }

	}
	fclose($file);	
}

?>
