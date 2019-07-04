<?php
include ("class.FastTemplate.php3");
include("./filecnt.func");
$aTPL = new FastTemplate("./templates");
$aTPL->define(array("base"=>"count.tpl"));
$aTPL->define_dynamic("news_list","base");
$aTPL->assign("NUM",kjFileCounter("counter.txt"));
$aTPL->parse(BASE,"base");
$aTPL->FastPrint(BASE);
?>
