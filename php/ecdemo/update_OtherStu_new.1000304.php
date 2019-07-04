<?php
	require 'fadmin.php';
  global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;
  if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
          $error = "資料庫連結錯誤!!";
          return $error;
  }
?>
<?php
	//取得本學期資料
	$qs_sem = "SELECT * FROM this_semester";
	if ($result_sem = mysql_db_query($DB, $qs_sem )){
		if(($row_sem = mysql_fetch_array($result_sem))==0){
			$error = "本學期資料不存在!!<BR>";
			return $error;
		}
	}
	else{
		$error = "mysql資料庫讀取錯誤1!!<br>";
		return $error;
	
	}
	$year = $row_sem['year'];
	$term = $row_sem['term'];	
	
	echo "This semester is:".$year."學年第".$term."學期<br>";
  //--------------------------------------------------------------
  $time = date("m-d");
  if( ( $time >= '01-01' and $time <= '03-31') or ( $time >= '10-01' and $time <= '10-31') )
  {
    echo "更新校際選修生選修資料<br>";
    
    //刪除校際選課生學生選課暫存資料temp_other_course_test
    $Q9 = "TRUNCATE TABLE temp_other_course_test";
    if( !($result9 = mysql_db_query($DB, $Q9)) ){
        $error = "資料庫更新錯誤!! $Q9<br>";
        return $error;
    }    
    
    //取得學籍系統校際選課生資料
		if( !($cnx1 = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) ){  
			Error_handler( "在 sybase_connect 有錯誤發生" , $cnx );  
		}
		
		$csd1 = @sybase_select_db("academic", $cnx1);	
		//取得系所資訊 塞到自訂陣列 後面會使用
		$cur1 = sybase_query("select std_no, cour_grp, name, sch_name from a31vcurriculum_other where year='$year' and term='$term' order by std_no", $cnx1);
		if(!$cur1) {  
			echo "在sybase_exec有錯誤發生";  
		}
		while($result1=sybase_fetch_array($cur1))
		{
			//echo $result1['std_no']."--".$result1['cour_grp']."<br>";
			$s_id = $result1['std_no'];
			$c_no = $result1['cour_grp'];
			$name = $result1['name'];
			$school = $result1['sch_name'];
			$Q1 = "INSERT INTO temp_other_course_test VALUES( '$s_id', '$c_no', '$name', '$school') ";
			//echo $Q1."<br>";
			//寫入校際選課生學生選課暫存資料temp_other_course_test
	    if( !($result2 = mysql_db_query($DB, $Q1)) ){
	        $error = "資料庫更新錯誤!! $Q1<br>";
	        return $error;
	    }
			
		}		
		
  }
  else
  {
    echo "超過更新時期,未更新  <br>";
    exit;
  }
  
  echo "更新成功!!<br>";

?>

</body>
</html>