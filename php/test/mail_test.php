<?
 $ls_succ = true;
 $r = mail("jim.huang@ccu.edu.tw","Hello Jim","This ia a test letter");
 if(!$r) $ls_succ = false;
 if($ls_succ == true)
   echo "It's right<br>";
 else
   echo "It's error<br>";

?>
