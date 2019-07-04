<?php
 include "../picture_encryption.php";  

 $picid = pic_encrypt('495110043');
 $spic = "<img src=\"../url_convert.php?id=".$picid."\" width=\"103\" height=\"133\">";
 echo $spic;
?>
