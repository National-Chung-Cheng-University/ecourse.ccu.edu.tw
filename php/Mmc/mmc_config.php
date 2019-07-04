<?php

Class MMC_DB_Config
{

  // 有關mmc資料庫的一些參數
  var $Mmc_DB_SERVER ; // ups中mmc的mysql ip
  var $Mmc_DB_LOGIN ; // ups中mmc的mysql帳號
  var $Mmc_DB_PASSWORD ; // ups中mmc的mysql密碼
  var $Mmc_DB ; // ups中mmc的mysql db

  function MMC_DB_Config()
  {
          $this->reset();
  }

  function reset() {
      $this->Mmc_DB_SERVER='140.123.4.56'; // [jfish]要換掉
      $this->Mmc_DB_LOGIN='ecoursePlatform'; // [jfish]要換掉
      $this->Mmc_DB_PASSWORD='ecoursePlatform@mmc'; // [jfish]要換掉
      $this->Mmc_DB='mmcDb';  // [jfish]要換掉
  }
}

Class MCU_Config
{
  // mcu的一些參數
  var $codeType ; // 一律都是13，還不知道有什麼差別
  var $portm; // 使用的port
  var $Mcu_ip ; // mcu的ip

  function MCU_Config()
  {
          $this->reset();
  }

  function reset() {
      $this->codeType = '13';
      $this->portm = '443' ; // [jfish] 要換
      $this->Mcu_ip = '140.123.4.56'; // [jfish] 要換
  }

}

Class MMC_Jnj_Config
{
  // 有關jnj加密的一些參數 
  var $privateKeyPath ;  // private key path
  var $publicKeyPath ;   // public key path
  var $siteId ;          // siteId
  var $passPhrase ;      // passPhrase

  function MMC_Jnj_Config()
  {
          $this->reset();
  }

  function reset() {
    $this->privateKeyPath = "/datacenter/htdocs/php/Mmc/key_web_localhost" ;  // [jfish]要換
    $this->publicKeyPath = "/datacenter/htdocs/php/Mmc/key_mcu_localhost.x509" ;   // [jfish]要換
    $this->siteId = "key_web_localhost";          
    $this->passPhrase = "autogenerate";   

  }
 
}

Class MMC_Path_Config
{
  // 有關網頁的一些參數(發佈會議取消發佈會議的網址的記錄)
  var $path ; //mmc的網址
  var $mcu_path ; // mcu基本位置
  var $mcu_localfile_path ; //mcu的部分檔案網址

  function MMC_Path_Config()
  {
          $this->reset();
  }

  function reset() {
      $this->path = 'http://ecourse.elearning.ccu.edu.tw/php/Mmc/'; // [jfish]要換
      $this->mcu_path = 'http://mmc.elearning.ccu.edu.tw/';
      $this->mcu_localfile_path = 'http://mmc.elearning.ccu.edu.tw/localFile/'; // [jfish]要換

  }

}

?>
