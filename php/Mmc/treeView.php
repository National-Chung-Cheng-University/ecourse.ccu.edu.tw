<?php
    $RELEATED_PATH = "../";
    require_once($RELEATED_PATH . "config.php");
    //require_once($RELEATED_PATH . "session.php");
    $IMAGE_PATH = $IMAGE_PATH;
    $CSS_PATH = $RELEATED_PATH . $CSS_PATH;
    $absoluteURL = $HOMEURL . "Mmc/";

    $tpl = new Smarty;
    $tpl->assign("imagePath", $IMAGE_PATH);
    $tpl->assign("cssPath", $CSS_PATH);
    $tpl->assign("absoluteURL", $absoluteURL);

    //目前的頁面
    $tpl->assign("currentPage", "treeView.php");

    assignTemplate($tpl, "/mmc/treeView.tpl");


?>
