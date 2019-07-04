<?php
/* author: lunsrot
 * Date: 2007/03/12
 */
session_start();

checkIdentification();
function identification_error(){
        global $WEBROOT;
        header('location:'. $WEBROOT. 'identification_error.html');
        exit(0);
}

function checkIdentification(){
        global $WEBROOT ;
        //���P�_ std_course_intro2.php
        if(strpos($_SERVER['REQUEST_URI'],$WEBROOT."/Course/std_course_intro2.php") != 0)
        {
                if(isset($_SESSION['personal_id']) != true || isset($_SESSION['role_cd']) != true){
                        session_destroy();
                        identification_error();
                }
        }
        return ;
}

/*author: lunsrot
 * Date: 2007/03/13
 */
function clearSession(){
        session_destroy();
}

/*author: lunsrot
 * data: 2007/03/28
 */
function verifyFunction($menu_link){
        $result = db_query("select role_cd from `lrtmenu_` A, `menu_role` B where A.menu_link='$menu_link' and B.menu_id=A.menu_id;");
        $role_cd = $_SESSION['role_cd'];
        while(($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) != false){
                if($row['role_cd'] == $role_cd)
                        return ;
        }
        identification_error();
}

/*author: lunsrot
 * date: 2007/07/08
 * �̤��P��PHP�ɡA���̷|�I�s���禡�A�Y���̩I�s�ɶǤJ����}���s�b��session���A�h��ܬ��q���}
 */
function checkMenu($input){
        if(!in_array($input, $_SESSION['menu'])){
                session_destroy();
                identification_error();
        }
        return ;
}
//�޲z��,�аȺ޲z��
function checkAdminAcademic(){
        if(!isset($_SESSION['role_cd']) || ($_SESSION['role_cd'] != 0 && $_SESSION['role_cd'] != 6))
        {
                session_destroy();
            identification_error();
        }
}

//�޲z��,�аȺ޲z��,�U��
function checkAdminTeacherTa(){
        if(!isset($_SESSION['role_cd']) || ($_SESSION['role_cd'] != 0 && $_SESSION['role_cd'] != 1 && $_SESSION['role_cd'] != 2))
        {
                session_destroy();
            identification_error();
        }
}

//�޲z��
function checkAdmin()
{
        if(!isset($_SESSION['role_cd']) || $_SESSION['role_cd'] != 0)
        {
                session_destroy();
            identification_error();
        }
}


function assign_sudo_admin_url($tpl) {
    global $HOMEURL , $WEBROOT ;

    //�ܨ��^�޲z�̥\��
    if( isset($_SESSION['setuid'])) {

        $tpl->assign('sudo_admin_back_url', '<a href="'. $HOMEURL .'/Learner_Profile/sudo_admin.php" target="_top">�ܦ^�W�H!</a>');
    }
}
?>

