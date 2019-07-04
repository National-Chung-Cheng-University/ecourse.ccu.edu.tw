<?php

    /*******************************/
    /*  Author:    w60292          */
    /*  Function:  照片位址加密    */
    /*  Date:      2008.9.26       */
    /*******************************/

    include "picture_encryption.php";

    $id = $_GET["id"];

    $stu = pic_decrypt($id);

    // get attachment location
    $attachment_location = "../S0t1u2_3P4h5o6t7o8/".$stu.".jpg";


    if (file_exists($attachment_location)) {
            // attachment exists

            // send open/save pdf dialog to user
            header('Cache-Control: public'); // needed for i.e.
            header('Content-Type: image/pjpeg');
            header('Content-Type: image/jpeg');
            readfile($attachment_location);
            die();
    }
    else {
            die('Error: File not found.');
    }

?>
