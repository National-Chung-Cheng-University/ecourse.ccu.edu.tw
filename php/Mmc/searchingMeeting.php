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


// �p�G�����M�\�઺�ܴN�|�Ψ�o����T�A������
$searchingtitle = $_GET['title'];

$personal_id = db_getAid();            //���o�ӤH�s��

// �ɦr cyberccu2���վ� �}�Y��1 �M�ᤣ�줭��ƭn�ɺ������
$ownerId = $personal_id;
while (strlen($ownerId) < 4) {
   $ownerId = '0'.$ownerId;
}

$ownerId = '2'.$ownerId;  // ���P�����x�n ID�}�Y�n���P ecourse��2


$resultObj = SearchingMeeting($ownerId);



if(mysql_num_rows($resultObj) == 0) {
  echo "<br><font color=\"#FF0000\" >�S���ŦX�����󪺷|ĳ</font><p>";
}
else {
    $result = "<p><table border=2 width=\"100%\" bgcolor=\"#F0FFEE\">";
    $result .= "<caption style=\"font-size:18px\">���v�C��</caption>";
    $result .= "<tr>";
    $result .= "<td></td>";
    $result .= "<td>�s��</td>";
    $result .= "<td>���D</td>";
    $result .= "<td>�}�l�ɶ�</td>";
    $result .= "<td>�ɮת���</td>";
    $result .= "<td>�ɮפj�p</td>";
    $result .= "</tr>";

    $index = 0 ;
    
    while( $row = mysql_fetch_assoc($resultObj)) {
        $index  = $index + 1 ;

        // �s�X�i�঳���D�A�]���o�x�������ӬOutf-8���ڪ����վ������D��ԣ�Obig5
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

        // �]��mmc��Ʈw�P�����s�X���P �n��^�� 
        $row['title'] = mb_convert_encoding($row['title'],"big5","UTF-8"); 

        $result .= "<tr>";
        $result .= "<td><input type=\"radio\" name=\"continueMeeting\" value=\"{$row['meetingId']}\" ></td>" ;
        $result .= "<td>".$index."</td>" ;
        $result .= "<td name=\"title{$row['meetingId']}\">{$row['title']}</td>" ;
        $result .= "<td name=\"time{$row['meetingId']}\">{$row['actualStartTime']}</td>";

        $result .= "<td name=\"duration{$row['meetingId']}\">";
        if ($recordingHours != 0)
            $result .= "{$recordingHours} �p�� ";
        if($recordingMinutes != 0)
            $result .= "{$recordingMinutes} �� ";

            $result .="{$recordingSeconds} ��</td>" ;
        
        
        $filesize = round($row['recordingSize']/1048576,"3") ;

        $result .= "<td>{$filesize} MB</td>" ;
        $result .= "</tr>";
    }
    // �T�w���򪺷|ĳ���s

    $result .= "<tr><td colspan=\"6\" ><input type=\"button\" name=\"continueDecided\" value=\"����|ĳ\"></td></tr>";
    $result .= "</table><p>";
    echo $result ;
}
  

?>

