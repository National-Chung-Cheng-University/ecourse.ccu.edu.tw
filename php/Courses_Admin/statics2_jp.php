<?php
// 95/11/06 �έp���Ǵ��ҵ{�Ч��W�ǲv by julien Pi
// �ȮɥΪ��{��

$count_ttl=0; //�`�ҵ{��
$count_yes=0; //�w�W�ǱЧ����ҵ{��

$DB_SERVER = "localhost";  //mysql�D��IP
$DB_LOGIN = "study";            //��Ʈw�b��
$DB_PASSWORD = "2720411";
$DB = "study"; 

$link = mysql_pconnect($DB_SERVER, $DB_LOGIN , $DB_PASSWORD);

//���Ǵ����Ҧ��}��
$qstr = "select distinct course_id from teach_course where year='95' and term='1'";
$rset = mysql_db_query( $DB, $qstr,$link );
while($row = mysql_fetch_array($rset))
{
	$count_ttl++;
	//�Y�Ч��ؿ����O�Ū��A���ɮשΥؿ��A�h�N��w�W�ǱЧ�
	$dir="../../$row[course_id]/textbook";
	if( is_dir($dir)) {
		if ($dh = opendir($dir)) {
     		while(($file = readdir($dh)) !== false) {
     			if (strcmp($file,".") !=0 && strcmp($file,"..") !=0 && strcmp($file,"misc") !=0) {
     				echo $file."<br>";
         		$count_yes++;
         		break;
         	}
         }
         closedir($dh);
      }
      else
       	echo $dir." can't be opened!\n";
   }
   else
   	echo $dir." not exist!\n";
}

echo "95�~�Ĥ@�Ǵ� �ҵ{�`��       �G".$count_ttl."<br>";
echo "95�~�Ĥ@�Ǵ��w�W�ǱЧ��ҵ{�ơG".$count_yes."<br>";
echo "�W�Ǥ�ҡ�".sprintf("%.2f", ($count_yes/$count_ttl)*100)."%";       
 
?>