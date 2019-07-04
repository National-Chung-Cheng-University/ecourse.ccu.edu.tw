<?php
        require 'fadmin.php';
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
?>
