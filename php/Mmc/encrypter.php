<?php
//
//    ------------------------------------------------------------------------------------
//    Copyright (c) 2004 HOMEMEETING INC. All rights reserved.
//    ------------------------------------------------------------------------------------
//    This source file is subject to HomeMeeting license,
//    that is bundled with this package in the file "license.txt".
//    ------------------------------------------------------------------------------------
//

	// require_once("include_hit/hit_encryption.php");
    require_once("hit_encryption.php");

	define("ENC_TYPE_IDENTITY", "identity");
	define("ENC_TYPE_REGISTER", "register");
	define("ENC_TYPE_MTG_GUEST", "mtgguest");
	define("ENC_TYPE_REDIRECT", "redirect");
	define("ENC_TYPE_SHARE", "share");
	define("ENC_TYPE_MESSAGE", "message");
	
	$_encErrorCode = 0;
	$_encErrorMsg = "";
	
	function encEncrypt($data) {
		global $_appConfig;
		global $_encErrorCode, $_encErrorMsg;
		
		$encData = "";
		if (entryEmpty($_appConfig->webSecretKeyString)) {
			$encData = base64_encode($data);
		} else {
			$encTool = new EncryptionTool();
			$desKey = $_appConfig->webSecretKeyString;
			$_encErrorCode = $encTool->tripleDesEncrypt($encData, $data, $desKey);
			if ($_encErrorCode) {
				$_encErrorMsg = $encTool->errorMessage;
				logError("3DES Encryption - " . $encTool->errorMessage);
			}
		}
		return($encData);
	}

	function encDecrypt($data) {
		global $_appConfig;
		global $_encErrorCode, $_encErrorMsg;
		
		$decData = "";
		if (entryEmpty($_appConfig->webSecretKeyString)) {
			$decData = base64_decode($data);
		} else {
			if (! entryEmpty($data)) {
				$encTool = new EncryptionTool();
				$desKey = $_appConfig->webSecretKeyString;
				$_encErrorCode = $encTool->tripleDesDecrypt($decData, $data, $desKey);
				if ($_encErrorCode) {
					$_encErrorMsg = $encTool->errorMessage;
					/*
					 * NOTES:
					 * The decryption will fail if the data cannot be decrypted correctly
					 * Error messages can be "error reading input file", "bad decrypt", etc.
					 * Ignore the decryption error by not logging the error
					 */
					//logError("3DES Decryption - " . $encTool->errorMessage);
				}
			}
		}
		return($decData);
	}
?>
