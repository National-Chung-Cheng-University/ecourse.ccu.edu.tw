<?php
	/*
	@ author: carlyle
	@ description: �C�X n �Ѥ����峹
	*/
	
	require 'fadmin.php';

	//�������
	$n_day = $_GET['d'];
	if (!isset($n_day)) die('fatal error!');

	global $DB,$DB_SERVER,$DB_LOGIN,$DB_PASSWORD,$course_id;

	//�C�X�Ҧ��Q�װ�
	$Q1 = "SELECT a_id,discuss_name FROM discuss_info";
	if (!($result1 = mysql_db_query($DB.$course_id,$Q1))) {
		show_page("not_access.tpl","��ƮwŪ�����~!!");
		exit();
	}

	//�L�Q�װ�
	$d_count = mysql_num_rows($result1);
	if ($d_count == 0) {
		show_page("not_access.tpl","���ҵ{�|�L�Q�װ�!!");
		exit();
	}

	//�ŦX�������峹��
	$matched = 0;

	//�Ȧs�ŦX���G��array (�̫�̤���Ƨǥ�)
	$r_array = array();

	//�ӧOquery�C�ӰQ�װϪ�table,��X�ŦX�������峹
	for ($i=0;$i<$d_count;$i++) {
		$row1 = mysql_fetch_array($result1);

		/* modify by w60292 @ 20090326 �^�Ф峹�]�b�d�߽d�� */
		//$Q2 = "SELECT a_id,title,poster,created FROM discuss_" . $row1['a_id'] . " WHERE parent = '0' AND created >= '" . cacuDate($n_day) . "'";
		$Q2 = "SELECT a_id,title,poster,created FROM discuss_" . $row1['a_id'] . " WHERE created >= '" . cacuDate($n_day) . "'";
		if (!($result2 = mysql_db_query($DB.$course_id,$Q2))) continue;

		$tmp_count = mysql_num_rows($result2);
		for ($j=0;$j<$tmp_count;$j++) {
			$matched++;
			$row2 = mysql_fetch_array($result2);

			$link = "<a href=\"/php/discuss/show_article.php?discuss_id=" . $row1['a_id'] . "&article_id=" . $row2['a_id'] . "&page=0\">" . $row2['title'] . "</a>";
			
			//�d�Xposter��nickname
			//-------------------------------------------------------------------
			$Q3 = "SELECT name,nickname FROM user WHERE id='" . $row2['poster'] . "'";
			if (!($result3 = mysql_db_query($DB,$Q3))) continue;

			if (mysql_num_rows($result3)) {
				$row3 = mysql_fetch_array($result3);
				if ($row3['nickname'] == NULL || $row3['nickname'] == "")
					$poster = $row3['name'];
				else
					$poster = $row3['nickname'];
			} else
				$poster = $row2['poster'];
			//-------------------------------------------------------------------

			$tmp_array = array("ITEMA" => $link,
					"ITEMB" => $poster,
					"ITEMC" => $row1['discuss_name'],
					"ITEMD" => $row2['created']);

			//�Τ峹�إߤ����key insert into $r_array (key�O�Ƨǥ�)
			$r_array[$row2['created']] = $tmp_array;
		}
	}

	if ($matched == 0) {
                show_page("not_access.tpl",("��p�A" .  $n_day . "�Ѥ��L�s�峹!!"));
                exit();
        } else
		krsort($r_array); //�̤����ŦX���峹���Ƨ�

        //tpl
        include("class.FastTemplate.php3");
        $tpl = new FastTemplate("./templates");
        $tpl->define(array(main=>"recentPosts.tpl"));
        $tpl->define_dynamic("article_list","main");
        $tpl->assign("TITLE",($n_day . "�Ѥ����s�峹"));

	//set the internal pointer of an array to its first element
	reset($r_array);

	//print result
	for ($i=0;$i<count($r_array);$i++) {
		$tmp = current($r_array); //return the current element in an array

		$tpl->assign("ITEMA",$tmp['ITEMA']);
		$tpl->assign("ITEMB",$tmp['ITEMB']);
		$tpl->assign("ITEMC",$tmp['ITEMC']);
		$tpl->assign("ITEMD",$tmp['ITEMD']);

		if (($i % 2) == 0)
			$tpl->assign("TRCOLOR","#ffffff");
		else
			$tpl->assign("TRCOLOR","#edf3fa");

		$tpl->parse(NEXTONE,".article_list");

		next($r_array); //advance the internal array pointer of an array
	}

	//display tpl
	$tpl->parse(BODY,"main");
        $tpl->FastPrint(BODY);
	exit();



	/* �p�� $day �ѫe������ήɶ� */
	function cacuDate($day)
	{
		$t = mktime(date("H"),date("i"),date("s"),date("m"),date("d")-$day,date("Y")); //$day�ѫe
		return date("Y-m-d H:i:s",$t);
	}
?>
