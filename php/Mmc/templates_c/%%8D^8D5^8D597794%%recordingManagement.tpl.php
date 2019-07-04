<?php /* Smarty version 2.6.14, created on 2011-01-10 18:02:34
         compiled from /home/opera/WWW/themes/IE2/mmc/recordingManagement.tpl */ ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<title>錄影檔管理</title>


<link href="<?php echo $this->_tpl_vars['tpl_path']; ?>
/css/tabs.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $this->_tpl_vars['tpl_path']; ?>
/css/content.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $this->_tpl_vars['tpl_path']; ?>
/css/table.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $this->_tpl_vars['tpl_path']; ?>
/css/form.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $this->_tpl_vars['webroot']; ?>
css/jquery.treeview.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $this->_tpl_vars['webroot']; ?>
script/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['webroot']; ?>
script/jquery-ui-1.7.2.custom.min.js"></script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['webroot']; ?>
script/jquery.treeview.js"></script>


</head>

<frameset cols="20%,80%">
<frame name="folder_frame" src="recordingManagement_folder.php?c=0">
<frame name="list_frame" src="recordingManagement_list.php" >
</frameset>

<noframes>
<body>
This is a framed page. Please use a frame-capable browser to view this
page. This page is in fact contains two pages and they are:
<ul>
<li> <a href="recordingManagement_folder.php">Left Frame</a>
<li> <a href="recordingManagement_list.php">Right Frame</a>
</ul>
</body>
</noframes>

</html>
