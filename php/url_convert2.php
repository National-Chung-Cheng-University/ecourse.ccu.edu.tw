<?php

    /************************************/
    /*  Author:    w60292               */
    /*  Function:  學生基本資料網址加密 */
    /*  Date:      2008.09.26           */
    /************************************/

    include 'picture_encryption.php';

    $id = $_GET["id"];

    $stu = pic_decrypt($id);

    // get page location
    $page_location = "../studentPage/".$stu.".html";
    if (file_exists($page_location)) {
        // page exists
        header('Cache-Control: public'); // needed for i.e.
        header('Content-Type: text/html');
        readfile($page_location);
        die();
    }
    else {
         die('Error: Page not found.');
    }
?>
