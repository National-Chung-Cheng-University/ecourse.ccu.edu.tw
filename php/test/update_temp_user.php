<?php
        require 'fadmin.php';
        global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;
        if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
                $error = "資料庫連結錯誤!!";
                return $error;
        }

        $Q1 = "select a.a_id, a.id, a.disable ,a.pass, a.ftppass from user a , temp_user b where a.id=b.id";
        if( !($result1 = mysql_db_query($DB, $Q1)) ){
                $error = "資料庫query錯誤!! $Q1<br>";
                return $error;
        }
        $count=0;
        echo passwd_encrypt('9407039');
        /*while(($row = mysql_fetch_array($result1))){
                 echo $row['a_id']."--".$row['id']."--".$row['disable']."--".$row[pass]."<br>";
                //echo $row2['num'];
                if( $row['id'] != "" ) {
                  //echo "No Body<br>";
                  $Q3 = "update user set authorization = '3' where a_id = ".$row['a_id'];
                  if ( !($result3 = mysql_db_query($DB,$Q3)) ){
                     $error = "mysql資料庫寫入錯誤!!";
                     echo "$error".": $Q3".": $result3<br>";
                  }
                  $count++;
                  echo "寫入第 $count 筆 ID: $row[id] <br>";
                }
                else
                {
                 echo "$row[id] 不存在<br>";
                }
        }*/
        echo "總共寫入user筆數: ".$count;
?>
