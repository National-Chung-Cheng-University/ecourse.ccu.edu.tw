<?php

// use CRLF as carriage return, so Windows users can use Notepad to edit/view XML file.
define( "NEWLINE", "\x0D\x0A" ); 

// Various constants
define( "SUCCESS", 0 );
define( "RUN_ONLY_ONCE", false );
define( "DO_NOT_TRIM_SPACE", false );
define( "REQUIRE_PERMISSION", 0 );
define( "NOT_REQUIRE_PERMISSION", 1 );
define( "ALL_MEMBERS_INVITED", TRUE );
define( "NO_PARAMS", NULL );
define( "FORUM_MODE", 1 );
define( "MESSENGER_COMPATIBLE", 1 );

define( "DONT_INSERT_TIME_STAMP", FALSE );

define( "WAIT_UNTIL_UNLOCK", true );
define( "QUIT_IF_LOCK", false );



define( "DESCENDING", "des" );
define( "ASCENDING", "asc" );





// Error code
define( "XML_PARSE_ERROR", 1000 );
define( "FILE_NOT_FOUND", 1001 );
define( "CANNOT_OPEN_FILE", 1002 );
define( "CANNOT_GET_SHARED_LOCK", 1003 );
define( "CANNOT_REWIND_FILE_POINTER", 1004 );
define( "MISSING_LAST_UPDATE", 1005 );
define( "MISSING_UPDATE_ID", 1006 );
define( "CANNOT_CREATE_FILE", 1007 );
define( "CANNOT_WRITE_FILE", 1008 );
define( "CANNOT_GET_EXCLUSIVE_LOCK", 1009 );
define( "ERROR_WRITING_FILE", 1010 );
define( "MESSAGE_ID_NOT_DEFINED", 1011 );
define( "MEETING_ID_NOT_DEFINED", 1012 );
define( "RECORDING_ID_NOT_DEFINED", 1015 );
define( "USER_ID_NOT_DEFINED", 1016 );
define( "USERNAME_AND_PASSWORD_NOT_ENTERED", 1017 );
define( "AUTO_LOGON_IS_OFF", 1018 );
define( "INVALID_SIGNATURE", 1019 );
define( "REQUEST_EXPIRED", 1020 );

// Automatic OS detection
if( function_exists( "posix_uname" )){
    define( "PLATFORM", "linux" );      
} else {
    define( "PLATFORM", "win32" );
}


//---------------------------------------------------------------------------
// NAME:  GetHashValue
// DESC:  Return the value of a Hash.  This function is useful to avoid
//        PHP displays error message if the key does not exist in the hash.
// PARAM: hashVar [in]
//          variable that stores the hash
//        hashKey [in]
//          name of the key
//        defaultValue [in]
//          return value if the key is not found in the hash
// RETURN: corresponding Value of the hash key
//---------------------------------------------------------------------------
function GetHashValue( &$hashVar, $hashKey, $defaultValue='' ){
  if( isset( $hashVar[$hashKey] ))
    return $hashVar[$hashKey];
  else
    return $defaultValue;
}
?>