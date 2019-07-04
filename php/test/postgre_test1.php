<?php

include 'connect_db.php';

//SQL範例
$query = 'select a.year, a.term, a.unitname, a.cour_cd, a.grp, b.name, a.id from a31vcurriculum_tea a, a30vcourse_tea b where a.cour_cd=b.course_no';
$result = pg_query($dbconn, $query) or die('資料表不存在，請通知電算中心');

while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
        echo $line['year']."--".$line['term']."--".$line['cour_cd']."--".$line['name']."<br>";
}

// Free resultset
pg_free_result($result);

// Closing connection
pg_close($dbconn);
?>

