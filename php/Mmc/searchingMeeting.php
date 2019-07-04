<?php

header("Content-Type:text/html; charset=big5");
include_once("db_meeting.php");
require_once("platform_config.php");

$RELEATED_PATH = "../";
require_once($RELEATED_PATH . "fadmin.php");

/*
$RELEATED_PATH = "../";
require_once($RELEATED_PATH . "config.php");
require_once($RELEATED_PATH . "session.php");
*/


// 如果有收尋功能的話就會用到這項資訊，先不做
$searchingtitle = $_GET['title'];

$personal_id = db_getAid();            //取得個人編號

// 補字 cyberccu2測試機 開頭為1 然後不到五位數要補滿五位數
$ownerId = $personal_id;
while (strlen($ownerId) < 4) {
   $ownerId = '0'.$ownerId;
}

$ownerId = '2'.$ownerId;  // 不同的平台要 ID開頭要不同 ecourse為2


$resultObj = SearchingMeeting($ownerId);



if(mysql_num_rows($resultObj) == 0) {
  echo "<br><font color=\"#FF0000\" >沒有符合的條件的會議</font><p>";
}
else {
    $result = "<p><table border=2 width=\"100%\" bgcolor=\"#F0FFEE\">";
    $result .= "<caption style=\"font-size:18px\">錄影列表</caption>";
    $result .= "<tr>";
    $result .= "<td></td>";
    $result .= "<td>編號</td>";
    $result .= "<td>標題</td>";
    $result .= "<td>開始時間</td>";
    $result .= "<td>檔案長度</td>";
    $result .= "<td>檔案大小</td>";
    $result .= "</tr>";

    $index = 0 ;
    
    while( $row = mysql_fetch_assoc($resultObj)) {
        $index  = $index + 1 ;

        // 編碼可能有問題，因為這台機器應該是utf-8但我的測試機不知道為啥是big5
        /*
        if($row['title'] != iconv("big5", "big5", $row['title'])) {
            $row['title'] = iconv("utf-8", "big5", $row['title']);
        }
        else
            ;
         */

        $recordingHours = intval(($row['actualDuration']/3600));
        $recordingMinutes = intval(($row['actualDuration']%3600)/60);
        $recordingSeconds = intval($row['actualDuration']%60);

        // 因為mmc資料庫與網頁編碼不同 要轉回來 
        $row['title'] = mb_convert_encoding($row['title'],"big5","UTF-8"); 

        $result .= "<tr>";
        $result .= "<td><input type=\"radio\" name=\"continueMeeting\" value=\"{$row['meetingId']}\" ></td>" ;
        $result .= "<td>".$index."</td>" ;
        $result .= "<td name=\"title{$row['meetingId']}\">{$row['title']}</td>" ;
        $result .= "<td name=\"time{$row['meetingId']}\">{$row['actualStartTime']}</td>";

        $result .= "<td name=\"duration{$row['meetingId']}\">";
        if ($recordingHours != 0)
            $result .= "{$recordingHours} 小時 ";
        if($recordingMinutes != 0)
            $result .= "{$recordingMinutes} 分 ";

            $result .="{$recordingSeconds} 秒</td>" ;
        
        
        $filesize = round($row['recordingSize']/1048576,"3") ;

        $result .= "<td>{$filesize} MB</td>" ;
        $result .= "</tr>";
    }
    // 確定接續的會議按鈕

    $result .= "<tr><td colspan=\"6\" ><input type=\"button\" name=\"continueDecided\" value=\"接續會議\"></td></tr>";
    $result .= "</table><p>";
    echo $result ;
}
  

?>

