<?php
    require_once("mmc_config.php");
    $RELEATED_PATH = "../";
    /*
    require_once($RELEATED_PATH . "config.php");
    require_once($RELEATED_PATH . "session.php");
    */
    require_once($RELEATED_PATH . "fadmin.php");
    include("Smarty/Smarty.class.php") ;

  if(isset($PHPSESSID) ) {


    $mmc_path_config = new MMC_Path_Config();

    $IMAGE_PATH = $IMAGE_PATH;
    $CSS_PATH = $RELEATED_PATH . $CSS_PATH;
    $absoluteURL = $HOMEURL . "Mmc/";

    $tpl = new Smarty;
    $tpl->assign("imagePath", $IMAGE_PATH);
    $tpl->assign("cssPath", $CSS_PATH);
    $tpl->assign("absoluteURL", $absoluteURL);

    //�ثe������
    $tpl->assign("currentPage", "link_MMC.php");

    $cid = $_GET['cid']; // �Y�ϸ��ܬ����|�Ψ� (�ϥΪ�id)
    $mid = $_GET['mid']; // �Ҧ����|�Ψ� (�|ĳid)
    $id = $_GET['id']; // �u���R�����v�ɷ|�Ψ� (�ϥΪ�id)
    $fn = $_GET['fn']; // �u���R�����v�ɷ|�Ψ� (���v�ɦW��)
    $op = $_GET['op']; // ��ؾާ@ op:1(�R�����v��)�B2:(�Y��)�B3:(��ܬ���)
    $tpl->assign("cid", $cid);  
    $tpl->assign("mid", $mid); 
    $tpl->assign("id", $id); 
    $tpl->assign("fn", $fn); 
    // $tpl->assign("op", $op); // ��ؾާ@ op:1(�R�����v��)�B2:(�Y��)�B3:(��ܬ���)

    if($op == 1 ) { // �R�����v�� ��id
        $timeStamp = strtotime('now');
        $timeStamp = date("Ymd", $timeStamp);
        $st = md5($id.$mid.$op.$timeStamp);
        $site = $mmc_path_config->mcu_localfile_path."deleteRecording.php" ;
    }
    else if ($op == 4 ) { // �M��u�W�|ĳ ��meetingId
        $timeStamp = strtotime('now');
        $timeStamp = date("Ymd", $timeStamp);
        $st = md5($mid.$op.$timeStamp);
        $site = $mmc_path_config->mcu_localfile_path."onlineMeetingSearch.php" ;
    }
    else { // �Y�ϸ��ܬ��� ��cid
        $timeStamp = strtotime('now');
        $timeStamp = date("Ymd", $timeStamp);
        $st = md5($cid.$mid.$op.$timeStamp);
        if ($op == 2 ) { // �Y��
            $site = $mmc_path_config->mcu_localfile_path."my_records_slidingshow.php" ;
        }
        else
            $site = $mmc_path_config->mcu_localfile_path."my_records_chat.php" ;
    }
	
    $tpl->assign("site", $site);
    $tpl->assign("st", $st); // ��{�X�A���O�H«���|�ݤ���
	$tpl->display("$mmc_templates/link_MMC.tpl") ;
	
    // assignTemplate($tpl, "/mmc/link_MMC.tpl");
  }
  else {
        if( $version=="C" ) {
                show_page( "not_access.tpl" ,"�A�S���v���ϥΦ��\��");
                exit;
        }
        else {
                show_page( "not_access.tpl" ,"You have No Permission!!");
                exit;
        }

  }

?>


