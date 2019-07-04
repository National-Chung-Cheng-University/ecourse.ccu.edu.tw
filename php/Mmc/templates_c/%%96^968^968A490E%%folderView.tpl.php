<?php /* Smarty version 2.6.14, created on 2011-03-03 19:27:10
         compiled from /usr/local/apache/htdocs/php/Mmc/templates/folderView.tpl */ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; ">
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<title>資料夾管理</title>

<link rel="stylesheet" href="/images/skin1/css/main-body.css" type="text/css">
<link href="<?php echo $this->_tpl_vars['tpl_path']; ?>
/css/tabs.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $this->_tpl_vars['tpl_path']; ?>
/css/content.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $this->_tpl_vars['tpl_path']; ?>
/css/table.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $this->_tpl_vars['tpl_path']; ?>
/css/form.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="jquery/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="jquery/jquery-ui-1.7.2.custom.min.js"></script>

</head>
<body>
<h1><?php echo $this->_tpl_vars['funcFolder']; ?>
</h1>
<?php if ($this->_tpl_vars['funcFolder'] == "新增資料夾"): ?>
您要求在" <font color=red><?php echo $this->_tpl_vars['folderName']; ?>
 </font>"資料夾下<br>建立一個新資料夾。<br>
<?php elseif ($this->_tpl_vars['funcFolder'] == "重新命名資料夾"): ?>
您要求重新命名"<font color=red><?php echo $this->_tpl_vars['folderName']; ?>
 </font>"資料夾。<br>
<?php else: ?>
<font color="red">您確定要刪除" <font color=red><?php echo $this->_tpl_vars['folderName']; ?>
 </font>"資料夾？<br><br>
這個動作不會刪除錄影檔，<br>但會把這個資料夾及其子資料夾下的錄影檔全部歸回root下。</font>
<?php endif; ?>
<form action="processFolder.php" method="post">
<br>
<?php if ($this->_tpl_vars['funcFolder'] != "刪除資料夾"): ?>
新資料夾名稱 : <input type="text" name="folderName" size="20">
<?php endif; ?>
<input type="submit" name="func" value="<?php echo $this->_tpl_vars['funcFolder']; ?>
" class="btn">
<input type="submit" name="func" value="取消" class="btn">
<input type="hidden" name="folderId" value="<?php echo $this->_tpl_vars['folderId']; ?>
">
</form>

</body>
</html>
