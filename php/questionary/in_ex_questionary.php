<?php
	require 'fadmin.php';
	//require_once 'export_questionary.php';
	include("class.FastTemplate.php3");
	require_once 'import_questionary.php';



	update_status ("編輯問卷");
	//show_page_d();

	$location = "../../".$course_id."/textbook/";
	 if ( $action == "import_file" ) {
                if ( empty($importfile) || $importfile == "none" ) {
			show_page_d();
			exit();
                }
                else {
                        handleUpLoad($location,"questionary.xml",$importfile);
			import_questionary($course_id);
			successMsg();			
                }
        }
	else{
			show_page_d();

	}
	function show_page_d(){
		global $version, $q_id, $course_id;
		$tpl = new FastTemplate("./templates");
		$tpl->define(array(main => "in_ex_questionary.tpl"));
		if($version =="C"){
			$tpl->assign( IMG , "img" );
			$tpl->assign(ACT1,"in_ex_questionary.php");
			$tpl->assign(MSG,"問卷匯入");
			$tpl->assign(IMPROT_DATA,"請準備符合格式的問卷匯入檔");
			$tpl->assign(IMPORT_DISC,"上傳匯入檔");
			$tpl->assign(COURSE_ID,$course_id);
			$tpl->assign(QUESID,$q_id);
			$tpl->assign(LINK,"<a href =../../../".$course_id."/textbook/questionary.dat>問卷匯出</a> ");
		}
		else{
			$tpl->assign( IMG , "img" );
                        $tpl->assign(ACT1,"in_ex_questionary.php");
                        $tpl->assign(MSG,"import/export questionary");
                        $tpl->assign(IMPROT_DATA,"please prepare the well fomat import file");
                        $tpl->assign(IMPORT_DISC,"upload file");
                        $tpl->assign(COURSE_ID,$course_id);
                        $tpl->assign(QUESID,$q_id);
                        $tpl->assign(LINK,"<a href =../../../".$course_id."/textbook/questionary.dat>export questionary</a> ");
		}
		$tpl->parse(BODY,"main");
		$tpl->FastPrint(BODY);
	}

	function successMsg(){
		global $version, $q_id, $course_id;
		$tpl = new FastTemplate("./templates");
                $tpl->define(array(main => "in_ex_questionary.tpl"));
                if($version =="C"){
			$tpl->assign( IMG , "img" );
                        $tpl->assign(ACT1,"in_ex_questionary.php");
                        $tpl->assign(MSG, "問卷匯入完畢");
    	                $tpl->assign(IMPROT_DATA,"請準備符合格式的問卷匯入檔");
                        $tpl->assign(IMPORT_DISC,"上傳匯入檔");
                        $tpl->assign(COURSE_ID,$course_id);
                        $tpl->assign(QUESID,$q_id);
                }
		else{

			$tpl->assign( IMG , "img" );
                        $tpl->assign(ACT1,"in_ex_questionary.php");
                        $tpl->assign(MSG,"import/export questionary");
                        $tpl->assign(IMPROT_DATA,"please prepare the well fomat import file");
                        $tpl->assign(IMPORT_DISC,"upload file");
                        $tpl->assign(COURSE_ID,$course_id);
                        $tpl->assign(QUESID,$q_id);
		}
                $tpl->parse(BODY,"main");
                $tpl->FastPrint(BODY);
	}

	function handleUpLoad($location,$filename,$Bytes){
		//被上傳檔案的檔名
		global $importfilename,$version;
		$ext = strrchr( $importfile_name, '.' );
		if($ext == ".php"){
			 if ( $version == "C" )
                        show_page("not_access.tpl", "請勿上傳危險檔案");
                	else
                        show_page("not_access.tpl", "The file is dangerous");
                	exit();
		}
		else{
			fileupload($Bytes,$location,$filename);
		}
	}

?>
