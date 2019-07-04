<?php
        /*
        @ Author: w60292
        @ Description: Picture Path encryption
        */

        $encrypt_key = "student@number";

        //¥[±Kfunction
        function pic_encrypt($raw_passwd) {
                if (!isset($raw_passwd) || $raw_passwd == "") return "";

                $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256,MCRYPT_MODE_ECB);
                $iv = mcrypt_create_iv($iv_size,MCRYPT_RAND);
                $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256,$encrypt_key,$raw_passwd,MCRYPT_MODE_ECB,$iv);
                return bin2hex($crypttext);
        }

        //¸Ñ±Kfunction
        function pic_decrypt($encrypted_passwd) {
                if (!isset($encrypted_passwd) || $encrypted_passwd == "") return "";

                $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256,MCRYPT_MODE_ECB);
                $iv = mcrypt_create_iv($iv_size,MCRYPT_RAND);
                $tmp = pic_hex2bin($encrypted_passwd);
                $r = mcrypt_decrypt(MCRYPT_RIJNDAEL_256,$encrypt_key,$tmp,MCRYPT_MODE_ECB,$iv);
                $raw_passwd = str_replace("\x0",'',$r);
                return $raw_passwd;
        }

        function pic_hex2bin($data) {
                $len = strlen($data);
                return pack("H".$len,$data);
        }
?>
