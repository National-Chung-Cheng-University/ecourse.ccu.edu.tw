<?
        require_once("session.php");
        /*
                Author: ccx97u
                Date: 2013/03/21
                Target: ��UMoblie�����P�_Session
        */
        if(isset($_SESSION['authorization'])){
                header("location:https://140.123.4.75/home/?login=true");
        }
        else{
                header("location:https://140.123.4.75/home/?login=false");
        }

?>

