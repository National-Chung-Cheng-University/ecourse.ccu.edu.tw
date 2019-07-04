<?php
    include_once("hit_encryption.php");
    $encryptor = new EncryptionTool();
    $errcode =$encryptor->pkeEncrypt($ans,"Test_data",
      "/datacenter/htdocs/php/Mmc/key_web_localhost",
      "/datacenter/htdocs/php/Mmc/key_mcu_localhost.x509",
      "key_web_localhost",
      "autogenerate");
       echo $encryptor->errorMessage; 
      var_dump($ans);
?>

