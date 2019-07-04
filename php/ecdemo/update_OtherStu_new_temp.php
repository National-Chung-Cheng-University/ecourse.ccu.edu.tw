<?php
	require 'fadmin.php';
	include 'logger.php';
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
  echo $time;
  if( ( $time >= '01-01' and $time <= '03-31') or ( $time >= '09-01' and $time <= '10-30') )
  {
    echo "更新校際選修生選修資料<br>";
    
    //刪除校際選課生學生選課暫存資料temp_other_course
    $Q9 = "TRUNCATE TABLE temp_other_course";
    if( !($result9 = mysql_db_query($DB, $Q9)) ){
        $error = "資料庫更新錯誤!! $Q9<br>";
        return $error;
    }    
    
    //取得學籍系統校際選課生資料---一般生資料庫
		
		$conn_string = "host=140.123.30.12 dbname=academic user=acauser password=!!acauser13";
		$cnx1 = pg_pconnect($conn_string) or die('資料庫沒有回應，請稍後再試');		
		
		$cur1 = pg_query($cnx1, "select std_no, cour_grp, name, sch_name from a31vcurriculum_other where year='$year' and term='$term' order by std_no") or die('資料表不存在，請通知電算中心');
		
		//while($result1=sybase_fetch_array($cur1))
		while($result1=pg_fetch_array($cur1, null, PGSQL_ASSOC))
		{
			echo $result1['std_no']."--".$result1['cour_grp']."<br>";
			$s_id = $result1['std_no'];
			$c_no = $result1['cour_grp'];
			$name = mb_convert_encoding($result1['name'], "big5", "utf-8");
			$school = mb_convert_encoding($result1['sch_name'], "big5", "utf-8");
			$Q1 = "INSERT INTO temp_other_course_1021004 VALUES( '$s_id', '$c_no', '$name', '$school') ";
			//echo $Q1."<br>";
			//寫入校際選課生學生選課暫存資料temp_other_course
	    if( !($result2 = mysql_db_query($DB, $Q1)) ){
	        $error = "資料庫更新錯誤!! $Q1<br>";
	        return $error;
	    }
			
		}		

    //取得學籍系統校際選課生資料---專班資料庫

		$conn_string = "host=140.123.30.12 dbname=academic_gra user=acauser password=!!acauser13";
		$cnx1 = pg_pconnect($conn_string) or die('資料庫沒有回應，請稍後再試');		
		

		$cur1 = pg_query($cnx1, "select std_no, cour_grp, name, sch_name from a31vcurriculum_other where year='$year' and term='$term' order by std_no") or die('資料表不存在，請通知電算中心');	
		
		while($result1=pg_fetch_array($cur1, null, PGSQL_ASSOC))
		{
			//echo $result1['std_no']."--".$result1['cour_grp']."<br>";
			$s_id = $result1['std_no'];
			$c_no = $result1['cour_grp'];
			$name = mb_convert_encoding($result1['name'], "big5", "utf-8");
			$school = mb_convert_encoding($result1['sch_name'], "big5", "utf-8");
			$Q1 = "INSERT INTO temp_other_course_1021004 VALUES( '$s_id', '$c_no', '$name', '$school') ";
			//echo $Q1."<br>";
			//寫入校際選課生學生選課暫存資料temp_other_course
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
