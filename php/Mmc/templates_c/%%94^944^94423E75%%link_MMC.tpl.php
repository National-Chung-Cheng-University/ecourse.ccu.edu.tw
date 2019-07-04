<?php /* Smarty version 2.6.14, created on 2011-01-10 18:02:53
         compiled from /home/opera/WWW/themes/IE2/mmc/link_MMC.tpl */ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<title>轉換</title>


<link href="<?php echo $this->_tpl_vars['tpl_path']; ?>
/css/tabs.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $this->_tpl_vars['tpl_path']; ?>
/css/content.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $this->_tpl_vars['tpl_path']; ?>
/css/table.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $this->_tpl_vars['tpl_path']; ?>
/css/form.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $this->_tpl_vars['webroot']; ?>
script/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['webroot']; ?>
script/jquery-ui-1.7.2.custom.min.js"></script>

</head>

<body>
    <form name=txAddr action='<?php echo $this->_tpl_vars['site']; ?>
' method='post'>
        <input type='hidden' name='cid' value='<?php echo $this->_tpl_vars['cid']; ?>
' >
        <input type='hidden' name='mid' value='<?php echo $this->_tpl_vars['mid']; ?>
' >
        <input type='hidden' name='id' value='<?php echo $this->_tpl_vars['id']; ?>
' >
        <input type='hidden' name='fn' value='<?php echo $this->_tpl_vars['fn']; ?>
' >
        <input type='hidden' name='st' value='<?php echo $this->_tpl_vars['st']; ?>
' >
    </form>
    <script language=javascript>
        document.txAddr.submit();
    </script>
</body>
</html>
