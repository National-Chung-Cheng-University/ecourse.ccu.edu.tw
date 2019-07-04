<?php

    $RELEATED_PATH = "../";
    // require_once($RELEATED_PATH . "config.php");
    // require_once($RELEATED_PATH . "session.php");
    require_once($RELEATED_PATH . "fadmin.php");
    $IMAGE_PATH = $IMAGE_PATH;
    $CSS_PATH = $RELEATED_PATH . $CSS_PATH;
    $absoluteURL = $HOMEURL . "Mmc/";

    //include("../class.FastTemplate.php3");
    include("Smarty/Smarty.class.php") ;
    //$tpl = new FastTemplate ( "./templates" );
    // $tpl->define ( array ( body => "instanceMeeting2.tpl") ) ;

if(isset($PHPSESSID) && session_check_teach($PHPSESSID) == 2) {

    $tpl = new Smarty() ;

    // $smarty->setTemplateDir('/var/www/html/php/Mmc/templates');
    // $smarty->setCompileDir('/var/www/html/php/Mmc/templates_c');
    // $smarty->setCacheDir('/var/www/html/php/Mmc/cache');
    // $smarty->setConfigDir('/var/www/html/php/Mmc/configs');

 
    $tpl->assign("imagePath", $IMAGE_PATH);
    $tpl->assign("cssPath", $CSS_PATH);
    $tpl->assign("absoluteURL", $absoluteURL);

    //ç›®å‰çš„é é¢
    $tpl->assign("currentPage", "instanceMeeting.php");
    
    // µ´¹ï¸ô®| 
    $tpl->display("$mmc_templates/instanceMeeting2.tpl") ;
    //$tpl->parse( BODY, "body");
    //$tpl->FastPrint("BODY");
 
    // assignTemplate($tpl, "/mmc/instanceMeeting.tpl");
}
else {

        if( $version=="C" ) {
                show_page( "not_access.tpl" ,"§A¨S¦³Åv­­¨Ï¥Î¦¹¥\¯à");
                exit;
        }
        else {
                show_page( "not_access.tpl" ,"You have No Permission!!");
                exit;
        }
}

?>
