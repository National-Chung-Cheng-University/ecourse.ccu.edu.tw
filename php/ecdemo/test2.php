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
    //$Q9 = "TRUNCATE TABLE temp_other_course";
    //if( !($result9 = mysql_db_query($DB, $Q9)) ){
    //    $error = "資料庫更新錯誤!! $Q9<br>";
    //    return $error;
    //}    
    
    //取得學籍系統校際選課生資料---一般生資料庫
		if( !($cnx1 = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) ){  
			Error_handler( "在 sybase_connect 有錯誤發生" , $cnx1 );  
		}
		
		$csd1 = @sybase_select_db("academic", $cnx1);	
		//取得系所資訊 塞到自訂陣列 後面會使用
		//$cur = sybase_query("select count(*) as c_nt from a31vcurriculum_other where year='100' and term='2' ", $cnx1);
		//$result=sybase_fetch_array($cur);
		//echo $result['c_nt'];
		//echo "---";		
		
//		$cur1 = sybase_query("select std_no, cour_grp, name, sch_name from a31vcurriculum_other where year='$year' and term='$term' order by std_no", $cnx1);
//		if(!$cur1) {  
//			echo "在sybase_exec有錯誤發生";  
//		}
//		while($result1=sybase_fetch_array($cur1))
//		{
//			echo $result1['std_no']."--".$result1['cour_grp']."<br>";
//			$s_id = $result1['std_no'];
//			$c_no = $result1['cour_grp'];
//			$name = $result1['name'];
//			$school = $result1['sch_name'];
//			$Q1 = "INSERT INTO temp_other_course VALUES( '$s_id', '$c_no', '$name', '$school') ";
//			//echo $Q1."<br>";
//			//寫入校際選課生學生選課暫存資料temp_other_course
//	    if( !($result2 = mysql_db_query($DB, $Q1)) ){
//	        $error = "資料庫更新錯誤!! $Q1<br>";
//	        return $error;
//	    }
//			
//		}		

//    //取得學籍系統校際選課生資料---專班資料庫
//		if( !($cnx1 = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) ){  
//			Error_handler( "在 sybase_connect 有錯誤發生" , $cnx );  
//		}
//		
//		$csd1 = @sybase_select_db("academic_gra", $cnx1);	
//		//取得系所資訊 塞到自訂陣列 後面會使用
//		$cur1 = sybase_query("select std_no, cour_grp, name, sch_name from a31vcurriculum_other where year='$year' and term='$term' order by std_no", $cnx1);
//		if(!$cur1) {  
//			echo "在sybase_exec有錯誤發生";  
//		}
//		while($result1=sybase_fetch_array($cur1))
//		{
//			//echo $result1['std_no']."--".$result1['cour_grp']."<br>";
//			$s_id = $result1['std_no'];
//			$c_no = $result1['cour_grp'];
//			$name = $result1['name'];
//			$school = $result1['sch_name'];
//			$Q1 = "INSERT INTO temp_other_course VALUES( '$s_id', '$c_no', '$name', '$school') ";
//			//echo $Q1."<br>";
//			//寫入校際選課生學生選課暫存資料temp_other_course
//	    if( !($result2 = mysql_db_query($DB, $Q1)) ){
//	        $error = "資料庫更新錯誤!! $Q1<br>";
//	        return $error;
//	    }
//			
//		}		
		
		//寫入校際選課生至 temp_takecourse, 類別(type=1), 
		//先刪除類別(type=1)為校際選課的資料，然後再加入新的完整資料
		$Q3 = "DELETE FROM temp_takecourse WHERE type = '1' ";
		$Q4 = "INSERT INTO temp_takecourse SELECT st_id, course_no, '1' FROM temp_other_course";
    if( !($result3 = mysql_db_query($DB, $Q3)) ){
        $error = "資料庫更新錯誤!! $Q3<br>";
        return $error;
    }		
    if( !($result4 = mysql_db_query($DB, $Q4)) ){
        $error = "資料庫更新錯誤!! $Q4<br>";
        return $error;
    }	
    
    //刪除校際選課生人員暫存檔temp_user
    $Q6 = "TRUNCATE TABLE temp_user";
    if( !($result6 = mysql_db_query($DB, $Q6)) ){
        $error = "資料庫更新錯誤!! $Q6<br>";
        return $error;
    }        		
		//將校際選課生人員寫入temp_user
		$Q5 = "INSERT INTO temp_user(id, name) SELECT DISTINCT st_id, name FROM temp_other_course ";
    if( !($result5 = mysql_db_query($DB, $Q5)) ){
        $error = "資料庫更新錯誤!! $Q5<br>";
        return $error;
    }
    add_user();
    		
  }
  else
  {
    echo "超過更新時期,未更新  <br>";
    exit;
  }
  
  echo "更新成功!!<br>";

	function add_user()
	{
    global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;
    if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
            $error = "資料庫連結錯誤!!";
            return $error;
    }

    $Q1 = "select * from temp_user";
    if( !($result1 = mysql_db_query($DB, $Q1)) ){
            $error = "資料庫query錯誤!! $Q1<br>";
            return $error;
    }
    $count=0;
    while(($row = mysql_fetch_array($result1))){
            $Q2 = "select count(*) as num from user where id = '$row[id]'";
            if( !($result2 = mysql_db_query($DB, $Q2)) ){
                    $error = "資料庫query錯誤!! $Q2<br>";
                    return $error;
            }
            //$count++;
            $row2 = mysql_fetch_array($result2);
            //echo $row2['num'];
            if( $row2['num'] < 1 ) {
              //echo "No Body<br>";
              $Q3 = "insert into user (id, pass, ftppass, authorization, disable, name, sex, grade) values (\"$row[id]\", \"" . passwd_encrypt($row[id]) . "\", \"" . md5($row[id]) . "\", \"3\", \"0\", \"$row[name]\", \"1\", \" \")";
              if ( !($result3 = mysql_db_query($DB,$Q3)) ){
                 $error = "mysql資料庫寫入錯誤!!";
                 echo "$error".": $Q3".": $result3<br>";
              }
              $count++;
              echo "寫入第 $count 筆 ID: $row[id] <br>";
            }
            else
            {
             echo "$row[id] 已經存在<br>";
            }
    }
    echo "總共寫入user筆數: ".$count;	
	}
	
?>
