<?php
    require_once("platform_config.php");
    $RELEATED_PATH = "../";
    require_once($RELEATED_PATH . "fadmin.php");

    include("Smarty/Smarty.class.php") ;
    /*
    require_once($RELEATED_PATH . "config.php");
    require_once($RELEATED_PATH . "session.php");
    */
    $IMAGE_PATH = $IMAGE_PATH;
    $CSS_PATH = $RELEATED_PATH . $CSS_PATH;
    $absoluteURL = $HOMEURL . "Mmc/";

  if(isset($PHPSESSID)) {

    $tpl = new Smarty;
    $tpl->assign("imagePath", $IMAGE_PATH);
    $tpl->assign("cssPath", $CSS_PATH);
    $tpl->assign("absoluteURL", $absoluteURL);

    //目前的頁面
    $tpl->assign("currentPage", "leavemessage.php");

    // assignTemplate($tpl, "/mmc/leavemessage.tpl");
    $tpl->display("$mmc_templates/leavemessage.tpl");
  }
  else {

        if( $version=="C" ) {
                show_page( "not_access.tpl" ,"你沒有權限使用此功能");
                exit;
        }
        else {
                show_page( "not_access.tpl" ,"You have No Permission!!");
                exit;
        }
  }
?>


