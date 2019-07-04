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

    //目前的頁面
    $tpl->assign("currentPage", "link_MMC.php");

    $cid = $_GET['cid']; // 縮圖跟對話紀錄會用到 (使用者id)
    $mid = $_GET['mid']; // 所有都會用到 (會議id)
    $id = $_GET['id']; // 只有刪除錄影檔會用到 (使用者id)
    $fn = $_GET['fn']; // 只有刪除錄影檔會用到 (錄影檔名稱)
    $op = $_GET['op']; // 何種操作 op:1(刪除錄影檔)、2:(縮圖)、3:(對話紀錄)
    $tpl->assign("cid", $cid);  
    $tpl->assign("mid", $mid); 
    $tpl->assign("id", $id); 
    $tpl->assign("fn", $fn); 
    // $tpl->assign("op", $op); // 何種操作 op:1(刪除錄影檔)、2:(縮圖)、3:(對話紀錄)

    if($op == 1 ) { // 刪除錄影檔 用id
        $timeStamp = strtotime('now');
        $timeStamp = date("Ymd", $timeStamp);
        $st = md5($id.$mid.$op.$timeStamp);
        $site = $mmc_path_config->mcu_localfile_path."deleteRecording.php" ;
    }
    else if ($op == 4 ) { // 尋找線上會議 用meetingId
        $timeStamp = strtotime('now');
        $timeStamp = date("Ymd", $timeStamp);
        $st = md5($mid.$op.$timeStamp);
        $site = $mmc_path_config->mcu_localfile_path."onlineMeetingSearch.php" ;
    }
    else { // 縮圖跟對話紀錄 用cid
        $timeStamp = strtotime('now');
        $timeStamp = date("Ymd", $timeStamp);
        $st = md5($cid.$mid.$op.$timeStamp);
        if ($op == 2 ) { // 縮圖
            $site = $mmc_path_config->mcu_localfile_path."my_records_slidingshow.php" ;
        }
        else
            $site = $mmc_path_config->mcu_localfile_path."my_records_chat.php" ;
    }
	
    $tpl->assign("site", $site);
    $tpl->assign("st", $st); // 辨認碼，讓別人竄改後會看不到
	$tpl->display("$mmc_templates/link_MMC.tpl") ;
	
    // assignTemplate($tpl, "/mmc/link_MMC.tpl");
  }
  else {
        if( $version=="C" ) {
                show_page( "not_access.tpl" ,"你沒有權限使用此功能");
                exit;
        }
        else {
                show_page( "not_access.tpl" ,"You have No Permission!!");
                exit;
        }

  }

?>


