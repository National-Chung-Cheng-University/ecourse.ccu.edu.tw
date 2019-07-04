<?php
        require 'fadmin.php';
        global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;
        if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
                $error = "資料庫連結錯誤!!";
                return $error;
        }

        $Q1 = "select * from temp_user where id like 'assistant%' order by id asc";
        if( !($result1 = mysql_db_query($DB, $Q1)) ){
                $error = "資料庫query錯誤!! $Q1<br>";
                return $error;
        }
        $count=0;
        while(($row = mysql_fetch_array($result1))){
          $password = passwd_decrypt(htmlentities($row["pass"]));
          //因為怕此網頁不小心被看到,所以18行先remark起來
          //echo $row[id]."|".$row[name]."|".$password."<br>";
          $count += 1;
        }
        echo "總共處理user筆數: ".$count;
?>
