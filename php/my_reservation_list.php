<?php
/*
   author: rja
   �ΨӦL�X�I�s my_reservation_list_proc.php ��A�o�쪺�|ĳ��T�A�A�e�X���

 */

/*
   include ���o�� my_reservation_list_proc.php �A���ӴN�|����@�� $reservation_meeting �ܼ�
   $reservation_meeting  �o���ܼưO�ۤ@�� mmc �W���|ĳ��T 
   (�w�]�O�q���Ѷ}�l��b�~�����|ĳ�A�P�Юv�Ҷ}���ҦW�٬ۦP���w���|ĳ)
 */

include_once("./my_reservation_list_proc.php");
include_once("./my_reservation_list_print_table_lib.php");
?>


<html>
 <meta http-equiv="content-type" content="text/html; charset=Big5" />
  <title>�w���|ĳ�C��</title>
</head>

<body>


<?php

//var_dump( $reservation_meeting);

echo '���Ǵ��z���Ҧ��ҵ{�w���|ĳ�C��G';
$listTable = editTableContent($reservation_meeting);

printTable($listTable);


?>

</body>
</html>
