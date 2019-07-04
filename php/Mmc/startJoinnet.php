<?php 
  // 開啟joinnet 

  require_once("jnjData.php");
  require_once("hit_encryption.php");

  // 這台怪怪的，只好寫個中文註解
  $RELEATED_PATH = "../";
  /*
  require_once($RELEATED_PATH . "config.php");
  require_once($RELEATED_PATH . "session.php");
  */

  hitLaunchJoinnet($_SESSION['jnjFile']);  
?>

