<?PHP
	require 'fadmin.php';
?>
<html>
<head>
<title>Update Course</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
</head>
<body background = "/images/img/bg.gif"><center>
<div><table>
    <tr> 
      <td> 
        <div>
          <font color=#000000>更新開課資料!</font>
        </div>
      </td>
    </tr>
</table>
<div id="progress">	　
</div>
<?PHP
	if ( isset($PHPSESSID) && session_check_admin($PHPSESSID) ) {
		$success = true;
		
		if(($error = update_course()) != -1){
			$success = false;
			echo "$error (更新課程)<br>";
		}
		if(($error = update_course_gra()) != -1){
			$success = false;
			echo "$error (更新專班課程)<br>";
		}
		
		if(($error = teach_course()) != -1){
			$success = false;
			echo "$error (更新開課資料)<br>";
		}
		if(($error = teach_course_gra()) != -1){
			$success = false;
			echo "$error (更新專班開課資料)<br>";
		}
		
		if(($error = clear_teach_course()) != -1)
		{
			$success = false;
			echo "$error (更新本學期授課資料)<br>";
		}
		if(($error = clear_teach_course_gra()) != -1)
		{
			$success = false;
			echo "$error (更新專班本學期授課資料)<br>";
		}
		
		if($success == true){
			echo "課程資料更新完畢!!<br>";
		}
		else{
			echo "課程資料更新失敗<br>";
		}
		
		echo "<br><a href=../check_admin.php>回系統管理介面</a>";

	}
	else
		show_page( "index_ad.tpl", "你的權限錯誤，請重新登入!!");

	// 更新課程
	function update_course(){
		global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;
		// 連結sybase
		if( !($cnx = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) ){  
			Error_handler( "在 sybase_connect 有錯誤發生" , $cnx );  
		}
		$csd = @sybase_select_db("academic", $cnx);
		
		$cur = sybase_query("select unitname,course_no,name,grp from a31vcurriculum_tea av,a30vcourse_tea ac
							where av.cour_cd=ac.course_no" , $cnx);
		if(!$cur) {  
			Error_handler( "在 sybase_exec 有錯誤發生( 沒有指標傳回 ) " , $cnx );  
		}

		// 連結mysql
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "資料庫連結錯誤!!";
			return $error;
		}	
		$count = 0;
		$temp = -1;
		$total = sybase_num_rows($cur);
		if($total==0){
			echo "沒有課程!!<br>";
			return -1;
		}
		while($array=sybase_fetch_array($cur)){
			$count++;
			$cno = $array[course_no]."_".$array[grp];
			// 查詢資料庫內是否有$cno這門課
			$Q = "select * from course_no where course_no = '$cno'";
			if ($result = mysql_db_query($DB,$Q)){			
				$row = mysql_fetch_array($result);
				// 沒有則為新課程
				if(!$row){
					// 新增到資料庫中
					if(($error = add_course($link, $cno, $array[name], $array[unitname])) != -1){
						echo "add_course($link, $cno, $array[name], $array[unitname] )) <br>";
						return "$error";
					}
				}
				else{
					$cid = $row['course_id'];
					// 新增到資料庫中
					if(($error = add_course_update($link, $cid, $array[unitname], $array[name])) != -1){
						echo "add_course_update($link, $cid, $array[unitname] )) <br>";
						return "$error";
					}
				}
			}
			else{
				$error = "mysql資料庫讀取錯誤!!";
				return "$error $Q1 <br>";
			}
			$p = (int)((100*$count)/$total);
			if($p>$temp){
				echo "<script language=\"JavaScript\" type=\"text/JavaScript\">
						document.all.progress.innerHTML = \"課程資料更新中，請稍侯 $p%\" ; </script>";
			}
			$temp = $p;
			flush();
			ob_flush();
		}

		sybase_close( $cnx);
		return -1;
	}

	// 更新專班課程
	function update_course_gra(){
		global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;
		// 連結sybase
		if( !($cnx = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) ){  
			Error_handler( "在 sybase_connect 有錯誤發生" , $cnx );  
		}
		$csd = @sybase_select_db("academic_gra", $cnx);
		
		$cur = sybase_query("select unitname,course_no,name,grp from a31vcurriculum_tea av,a30vcourse_tea ac
							where av.cour_cd=ac.course_no", $cnx);
		if(!$cur) {  
			Error_handler( "在 sybase_exec 有錯誤發生( 沒有指標傳回 ) " , $cnx );  
		}

		// 連結mysql
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "資料庫連結錯誤!!";
			return $error;
		}	
		$count = 0;
		$temp = -1;
		$total = sybase_num_rows($cur);
		if($total==0){
			return -1;
		}
		while($array=sybase_fetch_array($cur)){
			$count++;
			$cno = $array[course_no]."_".$array[grp];
			// 查詢資料庫內是否有$cno這門課
			$Q = "select * from course_no where course_no = '$cno'";
			if ($result = mysql_db_query($DB,$Q)){			
				$row = mysql_fetch_array($result);
				// 沒有則為新課程
				if(!$row){
					// 新增到資料庫中
					if(($error = add_course($link, $cno, $array[name], $array[unitname])) != -1){
						echo "add_course_gra($link, $cno, $array[name], $array[unitname] )) <br>";
						return "$error";
					}
				}
				else{
					$cid = $row['course_id'];
					// 新增到資料庫中
					if(($error = add_course_update($link, $cid, $array[unitname], $array[name])) != -1){
						echo "add_course_update_gra($link, $cid, $array[unitname] )) <br>";
						return "$error";
					}
				}
			}
			else{
				$error = "mysql資料庫讀取錯誤!!";
				return "$error $Q1 <br>";
			}
			$p = (int)((100*$count)/$total);
			if($p>$temp){
				echo "<script language=\"JavaScript\" type=\"text/JavaScript\">
						document.all.progress.innerHTML = \"專班課程資料更新中，請稍侯 $p%\" ; </script>";
			}
			$temp = $p;
			flush();
			ob_flush();
		}

		sybase_close( $cnx);
		return -1;
	}

	// 清除一般班之授課資料
	function clear_teach_course(){
		global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;

		// 連結sybase
		if( !($cnx = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) ){  
			Error_handler( "在 sybase_connect 有錯誤發生" , $cnx );  
		}
		$csd = @sybase_select_db("academic", $cnx);
		
		// 連結mysql
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "資料庫連結錯誤!!";
			return $error;
		}
		
		/* 修改為由 a31vcurriculum_tea 中取學期*/		
		/*
		$Q = "select * from this_semester";
		if( !($result = mysql_db_query($DB, $Q)) )
		{
			$error = "資料庫讀取錯誤!!$Q<br>";
		}
		$row = mysql_fetch_array($result);
		*/
		//**********取得學期*********
		
		$result = sybase_query("select distinct year, term from a31vcurriculum_tea", $cnx);
		if(!$result) {  
			Error_handler( "在 sybase_exec 有錯誤發生( 沒有指標傳回 ) " , $cnx );  
		}
		if(!$row=sybase_fetch_array($result)){
			Error_handler( "在 sybase_exec 有錯誤發生( 沒有學期資料 ) " , $cnx );  
		}
		
		//***************************
		
		$Q0 = "select u.id, u.a_id, cn.course_no, cn.course_id from user u, course_no cn, teach_course tc where u.authorization='1' and tc.teacher_id=u.a_id and tc.course_id=cn.course_id and tc.year=$row[year] and tc.term=$row[term] and !(cn.course_no like '___A%' or cn.course_no like '___B%' or cn.course_no like '___C%' or cn.course_no like '___D%') order by cn.course_no";
		if( !($result0 =mysql_db_query($DB, $Q0)) )
		{
			$error = "資料庫讀取錯誤!!$Q0<br>";
		}
		
		$total_count = 0;
		$temp = -1;
		$total = mysql_num_rows($result0);
		ob_end_flush();
		ob_implicit_flush(1);
		while($row0 = mysql_fetch_array($result0))
		{
			$total_count++;
			$p = number_format((100*$total_count)/$total, 2);
			if($p>$temp){
			echo "<script language=\"JavaScript\" type=\"text/JavaScript\">
			document.all.progress.innerHTML = \"$class 一般班授課資料清除中，請稍侯 $p%\" ; </script>";
			}
			$temp = $p;
			
			$row1=explode("_", $row0[course_no]);
			$cid = $row1[0];
			$gid = $row1[1];
			
			/*有教師未定情形時需特別處理 平台:id = undefined(a_id =17740)  sybase: id = 99999 */
			if($row0[id] == "undefined"){
				$cur = sybase_query("select * from a31vcurriculum_tea where id='99999' and cour_cd='$cid' and grp='$gid'", $cnx);
			}
			else{
				$cur = sybase_query("select * from a31vcurriculum_tea where id='$row0[id]' and cour_cd='$cid' and grp='$gid'", $cnx);
			}
			/**********************************************************/
			if(!$cur) {  
				Error_handler( "在 sybase_exec 有錯誤發生( 沒有指標傳回 ) " , $cnx );  
			}
			if( ($num=sybase_num_rows($cur)) == 0 )
			{
				$Qd = "delete from teach_course where course_id='$row0[course_id]' and teacher_id=$row0[a_id] and year=$row[year] and term=$row[term]";
				if( !($resultd = mysql_db_query($DB, $Qd)) )
				{
					$error = "資料庫讀取錯誤!!$Qd<br>";
					return $error;
				}
				$count++;
			}
		}
		sybase_close( $cnx);
		return -1;
	}
	// 清除專班之授課資料
	function clear_teach_course_gra(){
		global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;

		// 連結sybase
		if( !($cnx = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) ){  
			Error_handler( "在 sybase_connect 有錯誤發生" , $cnx );  
		}
		$csd = @sybase_select_db("academic_gra", $cnx);
		
		// 連結mysql
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "資料庫連結錯誤!!";
			return $error;
		}
		
		/* 修改為由 a31vcurriculum_tea 中取學期*/	
		/*
		$Q = "select * from this_semester";
		if( !($result = mysql_db_query($DB, $Q)) )
		{
			$error = "資料庫讀取錯誤!!$Q<br>";
		}
		$row = mysql_fetch_array($result);
		*/
		//**********取得學期*********
		$result = sybase_query("select distinct year, term from a31vcurriculum_tea", $cnx);
		if(!$result) {  
			Error_handler( "在 sybase_exec 有錯誤發生( 沒有指標傳回 ) " , $cnx );  
		}
		if(!$row=sybase_fetch_array($result)){
			Error_handler( "在 sybase_exec 有錯誤發生( 沒有學期資料 ) " , $cnx );  
		}
		//***************************
		
		$Q0 = "select u.id, u.a_id, cn.course_no, cn.course_id from user u, course_no cn, teach_course tc where u.authorization='1' and tc.teacher_id=u.a_id and tc.course_id=cn.course_id and tc.year=$row[year] and tc.term=$row[term] and (cn.course_no like '___A%' or cn.course_no like '___B%' or cn.course_no like '___C%' or cn.course_no like '___D%') order by cn.course_no";
		if( !($result0 =mysql_db_query($DB, $Q0)) )
		{
			$error = "資料庫讀取錯誤!!$Q0<br>";
		}
		
		$total_count = 0;
		$temp = -1;
		$total = mysql_num_rows($result0);
		ob_end_flush();
		ob_implicit_flush(1);
		while($row0 = mysql_fetch_array($result0))
		{
			$total_count++;
			$p = number_format((100*$total_count)/$total, 2);
			if($p>$temp){
			echo "<script language=\"JavaScript\" type=\"text/JavaScript\">
			document.all.progress.innerHTML = \"$class 專班授課資料清除中，請稍侯 $p%\" ; </script>";
			}
			$temp = $p;
			
			$row1=explode("_", $row0[course_no]);
			$cid = $row1[0];
			$gid = $row1[1];
			
			/*有教師未定情形時需特別處理 平台:id = undefined(a_id =17740)  sybase: id = 99999 */
			if($row0[id] == "undefined"){
				$cur = sybase_query("select * from a31vcurriculum_tea where id='99999' and cour_cd='$cid' and grp='$gid'", $cnx);
			}
			else{
				$cur = sybase_query("select * from a31vcurriculum_tea where id='$row0[id]' and cour_cd='$cid' and grp='$gid'", $cnx);
			}
			/**********************************************************/
							
			if(!$cur) {  
				Error_handler( "在 sybase_exec 有錯誤發生( 沒有指標傳回 ) " , $cnx );  
			}
			if( ($num=sybase_num_rows($cur)) == 0 )
			{
				$Qd = "delete from teach_course where course_id='$row0[course_id]' and teacher_id=$row0[a_id] and year=$row[year] and term=$row[term]";
				if( !($resultd = mysql_db_query($DB, $Qd)) )
				{
					$error = "資料庫讀取錯誤!!$Qd<br>";
					return $error;
				}
				$count++;
			}
		}
		echo "$count<br>";
		sybase_close( $cnx);
		return -1;
	}
	
	// 更新授課資料
	function teach_course(){
		global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;
		// 連結sybase
		if( !($cnx = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) ){  
			Error_handler( "在 sybase_connect 有錯誤發生" , $cnx );  
		}
		$csd = @sybase_select_db("academic", $cnx);
		$cur = sybase_query("select year,term,id,cour_cd,grp from a31vcurriculum_tea", $cnx);
		if(!$cur) {  
			Error_handler( "在 sybase_exec 有錯誤發生( 沒有指標傳回 ) " , $cnx );  
		}
		
		// 連結mysql
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "資料庫連結錯誤!!";
			return $error;
		}
		
		while($array=sybase_fetch_array($cur)){
		
			/*有教師未定情形時需特別處理 平台:id = undefined(a_id =17740)  sybase: id = 99999 */
			if($array[id] == "99999"){
				$Qs1 = "select a_id from user where id = 'undefined'";
			}
			else{
				$Qs1 = "select a_id from user where id = '$array[id]'";
			}			
			/**********************************************************************************/
			
			if($result1 = mysql_db_query($DB,$Qs1)){
				$row1 = mysql_fetch_array($result1);
			}
			else{
				$error = "mysql資料庫讀取錯誤!!";
				return "$error Qs1<br>";
			}
			
			$cno = $array[cour_cd]."_".$array[grp];
			// 取出$cno這門課的course_id (course之a_id)
			$Qs2 = "select course_id from course_no where course_no = '$cno'";
			if($result2 = mysql_db_query($DB,$Qs2)){
				$row2 = mysql_fetch_array($result2);
			}
			else{
				$error = "mysql資料庫讀取錯誤!!";
				return "$error Qs2<br>";
			}

			if($row1==0){
				$error = "此教師不存在!!";
				return "$error - $array[id]<br>";
			}
			if($row2==0){
				$error = "此課程不存在!!";
				return "$error - $array[cour_cd]<br>";
			}
			
			$Qs3 = "select * from teach_course where teacher_id='$row1[a_id]' and course_id='$row2[course_id]' and year='$array[year]' and term='$array[term]'";
			if($result3 = mysql_db_query($DB,$Qs3)){
				if(($row3 = mysql_fetch_array($result3))==0){
					$Qa = "insert into teach_course (teacher_id, course_id, year, term) values ('$row1[a_id]', '$row2[course_id]', '$array[year]', '$array[term]')";
					if ( !($resulta = mysql_db_query( $DB, $Qa ) ) ) {
						$error = "資料庫寫入錯誤!!".$Qa;
						return $error;
					}
				}
			}
			else{
				$error = "mysql資料庫讀取錯誤!!";
				return "$error $Qs3 <br>";
			}
				
		}
		sybase_close( $cnx);
		return -1;		
	}

	// 更新專班授課資料
	function teach_course_gra(){
		global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;
		// 連結sybase
		if( !($cnx = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) ){  
			Error_handler( "在 sybase_connect 有錯誤發生" , $cnx );  
		}
		$csd = @sybase_select_db("academic_gra", $cnx);
		$cur = sybase_query("select year,term,id,cour_cd,grp from a31vcurriculum_tea", $cnx);
		if(!$cur) {  
			Error_handler( "在 sybase_exec 有錯誤發生( 沒有指標傳回 ) " , $cnx );  
		}
		
		// 連結mysql
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "資料庫連結錯誤!!";
			return $error;
		}
		
		while($array=sybase_fetch_array($cur)){
		
			/*有教師未定情形時需特別處理 平台:id = undefined(a_id =17740)  sybase: id = 99999 */
			if($array[id] == "99999"){
				$Qs1 = "select a_id from user where id = 'undefined'";
			}
			else{
				$Qs1 = "select a_id from user where id = '$array[id]'";
			}			
			/**********************************************************************************/
			
			if($result1 = mysql_db_query($DB,$Qs1)){
				$row1 = mysql_fetch_array($result1);
			}
			else{
				$error = "mysql資料庫讀取錯誤!!";
				return "$error Qs1<br>";
			}
			
			$cno = $array[cour_cd]."_".$array[grp];
			// 取出$cno這門課的course_id (course之a_id)
			$Qs2 = "select course_id from course_no where course_no = '$cno'";
			if($result2 = mysql_db_query($DB,$Qs2)){
				$row2 = mysql_fetch_array($result2);
			}
			else{
				$error = "mysql資料庫讀取錯誤!!";
				return "$error Qs2<br>";
			}

			if($row1==0){
				$error = "此教師不存在(專班)!!";
				return "$error - $array[id]<br>";
			}
			if($row2==0){
				$error = "此課程不存在(專班)!!";
				return "$error - $array[cour_cd]<br>";
			}
			
			$Qs3 = "select * from teach_course where teacher_id='$row1[a_id]' and course_id='$row2[course_id]' and year='$array[year]' and term='$array[term]'";
			if($result3 = mysql_db_query($DB,$Qs3)){
				if(($row3 = mysql_fetch_array($result3))==0){
					$Qa = "insert into teach_course (teacher_id, course_id, year, term) values ('$row1[a_id]', '$row2[course_id]', '$array[year]', '$array[term]')";
					if ( !($resulta = mysql_db_query( $DB, $Qa ) ) ) {
						$error = "資料庫寫入錯誤!!".$Qa;
						return $error;
					}
				}
			}
			else{
				$error = "mysql資料庫讀取錯誤!!";
				return "$error $Qs3 <br>";
			}
				
		}
		sybase_close( $cnx);
		return -1;		
	}
	
	function add_course_update($link, $course_id, $course_unitname, $course_name){
		global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $scorm;
		$Qn = "select a_id from course_group where name='$course_unitname'";
		if ( !($resultn = mysql_db_query( $DB, $Qn ) ) ) {
			$error = "資料庫讀取錯誤!!";
			return $error;
		}
		if ( !($arrayn = mysql_fetch_array($resultn) ) ) {
			$error = "$course_unitname 此課程類別不存在!!";
			return $error;
		}		
		$group = $arrayn[a_id];
		$Q1 = "update course set group_id = $group, name = '$course_name' where a_id = $course_id";
		if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
			$error = "資料庫寫入錯誤!!".$Q1;
			return $error;
		}
		return -1;
	}
	
	function add_course($link, $course_no, $course_name, $course_unitname){
		global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $scorm;
		$Qn = "select a_id from course_group where name='$course_unitname'";
		if ( !($resultn = mysql_db_query( $DB, $Qn ) ) ) {
			$error = "資料庫讀取錯誤!!";
			return $error;
		}
		if ( !($arrayn = mysql_fetch_array($resultn) ) ) {
			$error = "$course_unitname 此課程類別不存在!!";
			return $error;
		}
		
		$group = $arrayn[a_id];
		$Q1 = "insert into course (group_id, name, schedule_unit ) values ('$group', '$course_name', '週')";

		if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
			$error = "資料庫寫入錯誤!!".$Q1;
			return $error;
		}
		$aid = mysql_insert_id();
		$Q2 = "insert into course_no (course_id, course_no ) values ('$aid', '$course_no')";
		if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) ) {
			$error = "資料庫寫入錯誤!!".$Q2;
			return $error;
		}
		
		$Q1 = "CREATE DATABASE study$aid";
		//$Q2 = "CREATE DATABASE coop$aid";
		$Q2 = "grant all privileges on study$aid.* to study";
		//$Q4 = "grant all privileges on coop$aid.* to study";
		$Q3 = "flush privileges";
		$Q6 = "CREATE TABLE course_schedule ( day varchar(11) NOT NULL, idx tinyint(4) DEFAULT '0' NOT NULL, subject varchar(100) NOT NULL, mtime timestamp(14), PRIMARY KEY (idx))";
		$Q7 = "CREATE TABLE news ( a_id int(11) NOT NULL auto_increment, system tinyint(4) DEFAULT '0', begin_day date DEFAULT '0000-00-00' NOT NULL, end_day date DEFAULT '0000-00-00' NOT NULL, cycle date DEFAULT '0000-00-00' NOT NULL, week tinyint(4) DEFAULT '0' NOT NULL, important tinyint(4) DEFAULT '1' NOT NULL, handle char(1) DEFAULT '0' NOT NULL, subject varchar(100) NOT NULL, content text NOT NULL, mtime timestamp(14), PRIMARY KEY (a_id))";
		$Q8 = "CREATE TABLE log ( a_id int(10) unsigned NOT NULL auto_increment, user_id int(11) DEFAULT '0' NOT NULL, event_id tinyint(4) DEFAULT '0' NOT NULL, tag1 varchar(100), tag2 varchar(100), tag3 int(11), tag4 varchar(255), mtime timestamp(14), PRIMARY KEY (a_id), UNIQUE a_id (a_id), KEY a_id_2 (a_id))";
		$Q9 = "CREATE TABLE homework ( a_id int(11) NOT NULL auto_increment, chap_num tinyint(3) unsigned DEFAULT '0' NOT NULL, name varchar(40), public char(1) NOT NULL default '0', question text, q_type varchar(5), answer text, ans_type varchar(5), percentage tinyint(4), late char(1) NOT NULL default '1', due date, mtime timestamp(14), PRIMARY KEY (a_id) )";
		$Q10 = "CREATE TABLE handin_homework ( homework_id int(11) DEFAULT '0' NOT NULL, student_id int(11) DEFAULT '0' NOT NULL, work text, upload tinyint(4) DEFAULT '0', comment text, grade float, public char(1) DEFAULT '0' NOT NULL, handin_time date, mtime timestamp(14), PRIMARY KEY (homework_id, student_id))";
		$Q11 = "CREATE TABLE exam ( a_id int(11) NOT NULL auto_increment, chap_num tinyint(3) unsigned DEFAULT '0' NOT NULL, name varchar(40), is_online char(1) DEFAULT '1' NOT NULL, random char(1) DEFAULT '0' NOT NULL, beg_time timestamp(14), end_time timestamp(14), public char(1) DEFAULT '0' NOT NULL, percentage tinyint(4), mtime timestamp(14), PRIMARY KEY (a_id))";
		$Q12 = "CREATE TABLE tiku ( a_id int(11) NOT NULL auto_increment, type tinyint(4) DEFAULT '1' NOT NULL, exam_id int(11) DEFAULT '0' NOT NULL, question text NOT NULL, selection1 text NOT NULL, selection2 text NOT NULL, selection3 text NOT NULL, selection4 text NOT NULL, ismultiple char(1) NOT NULL, answer char(2) NOT NULL, grade tinyint(4) DEFAULT '0' NOT NULL, answer_desc text, question_media text, answer_media text, file_picture_type varchar(32) NOT NULL, file_av_type varchar(32) NOT NULL , mtime timestamp(14), PRIMARY KEY (a_id))";
		$Q13 = "CREATE TABLE take_exam ( exam_id int(11) DEFAULT '0' NOT NULL, student_id int(11) DEFAULT '0' NOT NULL, grade float, nonqa_grade float, public tinyint(3) DEFAULT '1' NOT NULL, mtime timestamp(14), PRIMARY KEY (exam_id, student_id))";
		$Q14 = "CREATE TABLE on_line ( a_id int(11) NOT NULL auto_increment, date date DEFAULT '0000-00-00' NOT NULL, subject varchar(50), link varchar(100), file varchar(25), rfile varchar(100), mtime timestamp(14), PRIMARY KEY (a_id))";
		$Q15 = "CREATE TABLE chap_title ( a_id int(10) unsigned NOT NULL auto_increment, chap_num tinyint(3) unsigned DEFAULT '0' NOT NULL, chap_title varchar(128) NOT NULL, sect_num tinyint(3) unsigned DEFAULT '0' NOT NULL, sect_title varchar(128) NOT NULL, PRIMARY KEY (a_id))";
		$Q16 = "CREATE TABLE discuss_info ( a_id mediumint(8) unsigned NOT NULL auto_increment, discuss_name varchar(100) NOT NULL, comment varchar(100) DEFAULT 'NULL', group_num tinyint(4) DEFAULT '0' NOT NULL, access tinyint(1) DEFAULT '0' NOT NULL, PRIMARY KEY (a_id), UNIQUE a_id (a_id))";
		$Q17 = "CREATE TABLE discuss_group ( a_id int(10) unsigned NOT NULL auto_increment, group_num tinyint(4) DEFAULT '0' NOT NULL, student_id varchar(12) NOT NULL, PRIMARY KEY (a_id), KEY a_id (a_id))";
		$Q18 = "CREATE TABLE discuss_group_map ( a_id int(11) NOT NULL auto_increment, discuss_id mediumint(8) NOT NULL default '0', student_id int(11) NOT NULL default '0', mtime timestamp(14) NOT NULL, PRIMARY KEY  (a_id) )";
		$Q19 = "CREATE TABLE discuss_subscribe ( a_id int(10) unsigned NOT NULL auto_increment, user_id varchar(12) NOT NULL, discuss_id smallint(5) unsigned DEFAULT '0' NOT NULL, PRIMARY KEY (a_id), KEY a_id (a_id))";
		$Q20 = "CREATE TABLE qa ( item_id int(11) NOT NULL, exam_id int(11) NOT NULL, student_id int(11) NOT NULL, question text, answer text, grade float NOT NULL, grade_limit float NOT NULL)";
		$Q21 = "CREATE TABLE qtiku ( a_id int(11) NOT NULL auto_increment, q_id int(11) NOT NULL default '0', block_id int(11) NOT NULL default '0', type tinyint(4) NOT NULL default '1', question text NOT NULL, selection1 text NOT NULL, selection2 text NOT NULL, selection3 text NOT NULL, selection4 text NOT NULL, selection5 text NOT NULL, note tinyint(4) default NULL, ismultiple char(1) NOT NULL default '', grade tinyint(4) NOT NULL default '0', question_desc text, mtime timestamp(14) NOT NULL, PRIMARY KEY (a_id))";
		$Q22 = "CREATE TABLE questionary ( a_id int(11) NOT NULL auto_increment, name varchar(40) default NULL, is_once tinyint(4) NOT NULL default '1', beg_time timestamp(14) NOT NULL, end_time timestamp(14) NOT NULL, public char(1) NOT NULL default '0', is_named tinyint(4) NOT NULL default '0', mtime timestamp(14) NOT NULL, PRIMARY KEY  (a_id))";
		$Q23 = "CREATE TABLE take_questionary ( q_id tinyint(4) NOT NULL default '0', student_id int(11) NOT NULL default '0', count tinyint(4) NOT NULL default '0', mtime timestamp(14) NOT NULL, PRIMARY KEY (q_id,student_id))";		
		
		$Q24 = "CREATE TABLE function_list ( u_id varchar(40) NOT NULL, news char(1) NOT NULL default '1', intro char(1) NOT NULL default '1', sched char(1) NOT NULL default '0', info char(1) NOT NULL default '1', tein char(1) NOT NULL default '1', 
				tgins char(1) NOT NULL default '1', tgdel char(1) NOT NULL default '1', tgmod char(1) NOT NULL default '1', tgquery char(1) NOT NULL default '1', 
				upload char(1) NOT NULL default '1', editor char(1) NOT NULL default '1', online char(1) NOT NULL default '0', material char(1) NOT NULL default '1', import char(1) NOT NULL default '1', 
				create_work char(1) NOT NULL default '1', modify_work char(1) NOT NULL default '1', check_work char(1) NOT NULL default '1', 
				create_test char(1) NOT NULL default '1', modify_test char(1) NOT NULL default '1', 
				create_case char(1) NOT NULL default '0', mag_case char(1) NOT NULL default '0', check_case char(1) NOT NULL default '0', 
				create_qs char(1) NOT NULL default '0', modify_qs char(1) NOT NULL default '0', 
				chat char(1) NOT NULL default '0', discuss char(1) NOT NULL default '1', talk_voc char(1) NOT NULL default '0', talk_int char(1) NOT NULL default '0', eboard char(1) NOT NULL default '0', 
				strank char(1) NOT NULL default '0', chrank char(1) NOT NULL default '0', sttrace char(1) NOT NULL default '0', complete char(1) NOT NULL default '0', rollbook char(1) NOT NULL default '1', 
				tsins char(1) NOT NULL default '0', tsdel char(1) NOT NULL default '0', tsmod char(1) NOT NULL default '0', tschg char(1) NOT NULL default '0', tsquery char(1) NOT NULL default '1', psswd char(1) NOT NULL default '0', PRIMARY KEY (u_id))";

		$Q25 = "CREATE TABLE function_list2 ( u_id varchar(40) NOT NULL, news char(1) NOT NULL default '1',
				info char(1) NOT NULL default '1', sched char(1) NOT NULL default '0', sgquery char(1) NOT NULL default '1', ssquery char(1) NOT NULL default '0', email char(1) NOT NULL default '0',
				material char(1) NOT NULL default '1', online char(1) NOT NULL default '0',
				show_work char(1) NOT NULL default '1',show_test char(1) NOT NULL default '1', show_qs char(1) NOT NULL default '0', check_case char(1) NOT NULL default '0',
				chat char(1) NOT NULL default '0', discuss char(1) NOT NULL default '1', talk_voc char(1) NOT NULL default '0', talk_int char(1) NOT NULL default '0', eboard char(1) NOT NULL default '0',
				search char(1) NOT NULL default '0', stinfo char(1) NOT NULL default '0', psswd char(1) NOT NULL default '0', strank char(1) NOT NULL default '0', ssmodify char(1) NOT NULL default '1', PRIMARY KEY (u_id))";
		$Q26 = "CREATE TABLE roll_book ( roll_id int(10) unsigned NOT NULL default '0', user_id int(11) NOT NULL default '0', roll_date varchar(100), state tinyint(4), note varchar(100))";
		$Q27 = "CREATE TABLE discuss_list ( discuss_id mediumint(8) unsigned NOT NULL ,  chap_num tinyint(3) unsigned DEFAULT '0' NOT NULL)";
		
		//$Q50 = "CREATE TABLE coop ( a_id int(11) NOT NULL auto_increment, name varchar(40) default NULL, topic text, beg_time timestamp(14) NOT NULL, end_time timestamp(14) NOT NULL, public char(1) NOT NULL default '0', private char(1) NOT NULL default '0', percentage float default NULL, mtime timestamp(14) NOT NULL, PRIMARY KEY (a_id))";
		//$Q51 = "CREATE TABLE take_coop ( case_id int(11) NOT NULL default '0', student_id int(11) NOT NULL default '0', grade float default NULL, mtime timestamp(14) NOT NULL, PRIMARY KEY  (case_id,student_id))";


		$error = "資料庫建立錯誤";
		
		for ( $i = 1 ; $i <= 3 ; $i ++ ) {
			$Q = "Q$i";
			if ( !($result = mysql_query( $$Q , $link ) ) ) {
				$error = $error." ".$$Q;
				return $error;
			}
		}
		
		for ( $i = 6 ; $i <= 27 ; $i ++ ) {
			$Q = "Q$i";
			if ( !($result = mysql_db_query( $DB.$aid, $$Q ) ) ) {
				$error = $error." ".$$Q;
				return $error;
			}
		}
		/*
		for ( $i = 50 ; $i <= 51 ; $i ++ ) {
			$Q = "Q$i";
			if ( !($result = mysql_db_query( $DBC.$aid, $$Q ) ) ) {
				$error = $error." ".$$Q;
				return $error;
			}
		}
		*/
		mkdir ( "../../".$aid, 0771 );
		chmod ( "../../".$aid, 0771 );
		mkdir ( "/backup/".$aid, 0771 );
		chmod ( "/backup/".$aid, 0771 );
		mkdir ( "../../".$aid."/homework", 0771 );
		chmod ( "../../".$aid."/homework", 0771 );
		mkdir ( "../../".$aid."/homework/comment", 0771 );
		chmod ( "../../".$aid."/homework/comment", 0771 );
		mkdir ( "../../".$aid."/homework/upload", 0771 );
		chmod ( "../../".$aid."/homework/upload", 0771 );
		mkdir ( "../../".$aid."/on_line", 0771 );
		chmod ( "../../".$aid."/on_line", 0771 );
		mkdir ( "../../".$aid."/textbook", 0771 );
		chmod ( "../../".$aid."/textbook", 0771 );
		mkdir ( "../../".$aid."/textbook/misc", 0771 );
		chmod ( "../../".$aid."/textbook/misc", 0771 );
		mkdir ( "../../".$aid."/student_info", 0771 );
		chmod ( "../../".$aid."/student_info", 0771 );
		mkdir ( "../../".$aid."/board", 0771 );
		chmod ( "../../".$aid."/board", 0771 );
		mkdir ( "../../".$aid."/intro", 0771 );
		chmod ( "../../".$aid."/intro", 0771 );
		mkdir ( "../../".$aid."/coop", 0771 );
		chmod ( "../../".$aid."/coop", 0771 );
		mkdir ( "../../".$aid."/exam", 0771 );
		chmod ( "../../".$aid."/exam", 0771 );

		if ( $scorm == 1 ) {
			mkdir ( "../../".$aid."/scorm", 0771 );
			chmod ( "../../".$aid."/scorm", 0771 );
			$S1 = "CREATE TABLE lesson ( a_id int(11) NOT NULL auto_increment, lesson_id varchar(255) NOT NULL default '', location text, title text NOT NULL, parent_id varchar(255) NOT NULL default '', level tinyint(4) NOT NULL default '0', is_leaf tinyint(4) NOT NULL default '0', PRIMARY KEY  (a_id,lesson_id), KEY a_id (a_id), KEY lesson_id (lesson_id))";
			$S2 = "CREATE TABLE sco_register ( a_id int(11) NOT NULL auto_increment, sco_id varchar(255) NOT NULL default '0', parent_id varchar(255) NOT NULL default '0', lesson_id varchar(255) NOT NULL default '0', sequence int(11) NOT NULL default '0', prerequisites varchar(200) default NULL, sco_name text NOT NULL, location text NOT NULL, metadata text NOT NULL, data_mastery_score float default NULL, data_max_time_allowed varchar(255) default NULL, data_time_limit_action varchar(255) default NULL, launch_data text NOT NULL, comments_from_lms text, PRIMARY KEY  (a_id), KEY a_id (a_id) )";
			
			for ( $i = 1 ; $i <= 2 ; $i ++ ) {
				$S = "S$i";
				if ( !($result = mysql_db_query( $DB.$aid, $$S ) ) ) {
					$error = $error." $i";
					return $error;
				}
			}
		}
		
		return -1;
	}

	function Error_Handler( $msg, $cnx ) {  
		echo "$msg \n";
		sybase_close( $cnx); exit();  
	}
	
?>
</div>
</center>
</body>
</html>
