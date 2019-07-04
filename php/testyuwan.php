<?php
	/*
	@ Author: carlyle
	@ Description: Password encryption
	*/

	$encrypt_key = "ecourse@ccu";

	//¥[±Kfunction
	function passwd_encrypt($raw_passwd) {
		if (!isset($raw_passwd) || $raw_passwd == "") return "";

		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256,MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($iv_size,MCRYPT_RAND);
		$crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256,$encrypt_key,$raw_passwd,MCRYPT_MODE_ECB,$iv);
		return bin2hex($crypttext);
	}
$encrypted_passwd="4346a6b71df169aaec117bedce51d80c1cb4457d2937df616e2c2447a1f3c673";
//echo passwd_decrypt($encrypted_passwd);
	//¸Ñ±Kfunction
	function passwd_decrypt($encrypted_passwd) {
		if (!isset($encrypted_passwd) || $encrypted_passwd == "") return "";

		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256,MCRYPT_MODE_ECB);
                $iv = mcrypt_create_iv($iv_size,MCRYPT_RAND);
		$tmp = hex2bin($encrypted_passwd);
		$r = mcrypt_decrypt(MCRYPT_RIJNDAEL_256,$encrypt_key,$tmp,MCRYPT_MODE_ECB,$iv);
		$raw_passwd = str_replace("\x0",'',$r);
		return $raw_passwd;
	}
	function hex2bin($data) {
		$len = strlen($data);
		return pack("H".$len,$data);
	}
?>
