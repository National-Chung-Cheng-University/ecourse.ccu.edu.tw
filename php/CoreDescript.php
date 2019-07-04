<?php
//Creat by ghost777 at 2008/03/07
//Show Core Abilities Descrption

global $Content;
global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;

function CADes($group_id)
{
	global $Content;
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;

	/*linsy@20140313, 讓系統自動抓取group_id的核心能力資訊
		if($group_id==11){
			$Content[0] = "1.1. 資訊工程相關基礎知識之吸收與了解的能力 。";
			$Content[1] = "1.2. 運用資訊工程理論及應用知識，分析與解決相關問題的能力 。";
			$Content[2] = "1.3. 在資訊工程的許多領域中，具有至少某一項專業能力例如：硬體、軟體、多媒體、系統、網路、理論等 。";
			$Content[3] = "2.1. 具有資訊工程實作技術及使用計算機輔助工具的能力 。";
			$Content[4] = "2.2. 具有設計資訊系統、元件或製程的能力 。";
			$Content[5] = "2.3. 具有優秀科技簡報與寫作的能力 。";
			$Content[6] = "3.1. 除了已有的應用領域之外，亦可以將自己的專業知識應用於新的領域或跨多重領域，進行研發或創新的能力 。";
			$Content[7] = "3.2. 領導或參與一個團隊完成一項專案任務的能力並且具有溝通、協調與團隊合作的能力 。";
			$Content[8] = "3.3. 因應資訊科技快速變遷之能力，培養自我持續學習之能力 。";
			$Content[9] = "4.1. 具有社會責任、人文素養及奉獻精神 。";
			$Content[10] = "4.2. 具有工程倫理、宏觀能力、國際觀及前瞻視野 。";
			return BulidTable();
		}
		else if($group_id==12){
			$Content[0] = "A1.具有資訊工程與科學領域之專業知識。";
			$Content[1] = "A2.具有創新思考、問題解決、獨立研究之能力。";
			$Content[2] = "A3.具有撰寫中英文專業論文及簡報之能力。";
			$Content[3] = "A4.具策劃及執行專題研究之能力。";
			$Content[4] = "A5.具有溝通、協調、整合及進行跨領域團隊合作之能力。";
			$Content[5] = "A6.具有終身學習與因應資訊科技快速變遷之能力。";
			$Content[6] = "A7.認識並遵循學術與工程倫理。";
			$Content[7] = "A8.具國際觀及科技前瞻視野 。";
			return BulidTable();
		}
	*/


	$sql = "SELECT CA.*, CG.ClassGoalNo  
			FROM `IEET_CoreAbilities` AS CA, `IEET_ClassGoal` AS CG 
			WHERE CA.group_id=$group_id 
			AND CA.ClassGoal_Index = CG.ClassGoal_Index 
			ORDER BY CG.ClassGoalNo, CA.CoreAbilitiesNo
			";
	if(!($result = mysql_db_query($DB, $sql)))
	{
		$message = "'$sql' 資料庫讀取錯誤!!<br>";
		echo $message;
	}
	$index = 0;
	while($row = mysql_fetch_assoc($result))
	{
		$Content[$index] = $row['ClassGoalNo'] . "." . $row['CoreAbilitiesNo'] . ". " . $row['content'];
		$index++;
	}
	return BulidTable();
}

function BulidTable(){
	global $Content;

	$str =  "<table align='center'>";
	foreach($Content as $value){
		$str .= BulidTR(BulidTD($value));
	}
	$str .= "</table>";
	return $str;
}

function BulidTR($value){
	return "<tr>" . $value . "</tr>";
}

function BulidTD($value){
	return "<td><font color=\"#FF0000\">" . $value . "</font></td>";
}

?>

