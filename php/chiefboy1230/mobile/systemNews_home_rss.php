<?php
/*
DATE:   2014/03/10
AUTHOR: linsy
*/
require_once("fadmin.php");
require_once("library/rss_generator.php");
require_once("library/time.php");
global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
$HOMEURL = "http://ecourse.elearning.ccu.edu.tw/";
$absoluteURL = $HOMEURL . "System_News/";
$Q1 = "SELECT a_id, begin_day, subject, important, content 
		FROM news 
		WHERE system = '1' 
		AND begin_day <= '".date("Y-m-d")."' 
		AND end_day >= '".date("Y-m-d")."' 
		ORDER BY begin_day DESC ";
if (!($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)))
	$error = "��Ʈw�s�����~!!";
else if (!($result = mysql_db_query($DB, $Q1)))
    $error = "��ƮwŪ�����~!!";
else
{
	$lastPubDate = '';
	$rowCounter = 0;
	while ($row = mysql_fetch_array($result))
	{		
		//���i�s��
        $a_id = $row['a_id'];
		//���i���
		$begin_day = $row['begin_day'] . " 00:00:00";
		//���i���D
		$subject = iconv("big5", "utf-8", $row['subject']);	
		//���i���e
		$content = iconv("big5", "utf-8", $row['content']);
		if($row['content'] != "")
			$showContent = 1;
		else
			$showContent = 0;
	
		//IE7�b�B�zlink��,�p�G�᭱����ӥH�W���Ѽ�(�ϥΨ�Ÿ�&),�|�X��,�ҥH�N�Ҧ��Ѽ��ܦ��@�Ӧr��ѼƴN���|�����D
		$link = "systemNews_show_rss.php?argument=" . $a_id . "_";
		//$pubDate = $begin_day;
		$pubDate = Time_format($begin_day);		
		$title = $subject;
		$description = $content;
		$author = "System Administrator";

		$rssNewsList[$rowCounter] = array(
									"title" => $title,
									"link" => $link,
									"description" => $description,
									"author" => $author,
									"pubDate" => $pubDate
									);
		$lastPubDate = $pubDate;									
		$rowCounter++;
	}
}
        

        $rss = new rss_generator("E-Learning System Announcement");
        $rss->__set("encoding", "UTF-8");
        $rss->__set("title", "E-Learning System Announcement");
        $rss->__set("language", "zh");
        $rss->__set("description", "E-Learning System Announcement");
        $rss->__set("pubDate", $lastPubDate);
        $rss->__set("link", $HOMEURL);
        $xml = $rss->get($rssNewsList);
        echo $xml;
?>

