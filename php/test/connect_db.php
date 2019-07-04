<?php
// 連結資料庫
$conn_string = "host=140.123.26.159 dbname=academic user=acauser password=!!acauser13";
$dbconn = pg_pconnect($conn_string)
    or die('資料庫沒有回應，請稍後再試');
?>
