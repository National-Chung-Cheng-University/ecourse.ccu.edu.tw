<?php
/* ���g��� : 2009.02.28
 * ���g��   : w60292
 * ���g�\�� : �������Q�ץD�D
 */

	require 'fadmin.php';
        include("class.FastTemplate.php3");

        function GetUserName($user_id) {

                global $DB;

                $sql = "select name,nickname from user where id='$user_id'";
                $result = mysql_db_query($DB, $sql) or die("��Ʈw�d�߿��~, $sql");

                // check name field. if exists, use it as poster name.
                if(mysql_num_rows($result) > 0) {
                        $row = mysql_fetch_array($result);
                        if( strcmp($row["nickname"], "" )!=0) {
                                $poster = $row["nickname"];
                        }
                        elseif(strcmp($row["name"], "" ) !=0 ) {
                                $poster = $row["name"];
                        }
                        else {
                                $poster = $user_id;
                        }
                }
                else {
                        // Default.
                        $poster = $user_id;
                }

                return $poster;
        }

	function is_read_all($discuss_id, $article_id){
                global $DB, $course_id, $read;

                // �P�_�Y�@�Q�ץD�D���t�C�峹�O�_����Ū�L �Y�L�^��0 ��Ū�L�^��1
                if($read[$article_id]!=1) //���t�C�峹�����g��Ū
                        return 0;

                $q = "SELECT a_id FROM discuss_".$discuss_id." WHERE parent=".$article_id." ORDER BY a_id ASC";
                if (!($res = mysql_db_query($DB.$course_id,$q)))
                        die($q . " ��Ʈw�d�߿��~");

                while($row = mysql_fetch_array($res)){
                        if($read[$row['a_id']]!=1)
                                return 0;
                }

                return 1;
        }

	function is_hot($a_id, $d_id) {

		global $DB, $course_id;
	
		$sql = "select * from discuss_".$d_id." where parent=".$a_id;
                $result = mysql_db_query($DB.$course_id, $sql) or die("��Ʈw�d�߿��~, $sql");
		if(mysql_num_rows($result) >= 3)
                	return 1;
		else
                	return 0;
	}

	$sql = "select * from discuss_info order by group_num,a_id";
        mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("��Ʈw�s�����~");

        $tpl = new FastTemplate("./templates");

        if($version == "C")
                $tpl->define(array(main => "hot_article.tpl"));
        else
                $tpl->define(array(main => "hot_article_E.tpl"));

        $tpl->define_dynamic("article_list", "main");
        $result = mysql_db_query($DB.$course_id, $sql) or die("��Ʈw�d�߿��~, $sql");
        $discuss_num = mysql_num_rows($result);

        if($discuss_num == 0) {
                $t_page = 1;
                $articles = 0;
                $tpl->assign("ITEMA", "");
                $tpl->assign("ITEMB", "");
                $tpl->assign("ITEMC", "");
                $tpl->assign("ITEMD", "");
                $tpl->assign("ITEME", "");
        }
	else {
		$counter = 1;
		$articles = 0;
		while($counter<=$discuss_num) {
			$discuss_table = "discuss_".$counter;
			//��X�D�D
	                $Q1 = "select * from ".$discuss_table." where parent = 0";
        	        $result1 = mysql_db_query($DB.$course_id, $Q1) or die("��Ʈw�d�߿��~, $Q1");
			while($row = mysql_fetch_array($result1)) {
                        	if(is_hot($row["a_id"], $counter) == 1) {
                                	$article_id = $row["a_id"];
	                                $title = "<a href='show_article.php?discuss_id=$counter&article_id=$article_id&page=$page&PHPSESSID=$PHPSESSID'>".$row["title"]."";
        	                        $poster = GetUserName($row["poster"]);
                	                $created = $row["created"];
                        	        $replied = $row["replied"];
                                	$parent = $row["parent"];
	                                $body = $row["body"];
        	                        $viewed = $row["viewed"];

                	                // �P�_�Y�@�Q�ת��D�D�O�_��Ū�L
                        	        $is_read_all = is_read_all($discuss_id, $article_id);
                                	if($is_read_all==0)
                                        	$tpl->assign("ITEMA", "<font color='#F6358A'>+ </font>".$title);
	                                else
        	                                $tpl->assign("ITEMA", $title);
                	                $tpl->assign("ITEMB", $poster);
                        	        $tpl->assign("ITEMC", $created);
                                	$tpl->assign("ITEMD", $replied);
	                                $tpl->assign("ITEME", $viewed);


        	                        if($articles%2 == 0)
                	                        $tpl->assign("ARCOLOR", "#ffffff");
                        	        else
                                	        $tpl->assign("ARCOLOR", "#edf3fa");

	                                $tpl->parse(ROWA, ".article_list");
        	                        $articles++;
                	        }
			}
			$counter++;
		}
	}

	$tpl->assign("ART_TOTAL", $articles);

        $tpl->parse(BODY, "main");

        $tpl->FastPrint(BODY);

?>
