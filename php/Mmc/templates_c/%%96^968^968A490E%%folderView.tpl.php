<?php /* Smarty version 2.6.14, created on 2011-03-03 19:27:10
         compiled from /usr/local/apache/htdocs/php/Mmc/templates/folderView.tpl */ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; ">
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<title>��Ƨ��޲z</title>

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
<?php if ($this->_tpl_vars['funcFolder'] == "�s�W��Ƨ�"): ?>
�z�n�D�b" <font color=red><?php echo $this->_tpl_vars['folderName']; ?>
 </font>"��Ƨ��U<br>�إߤ@�ӷs��Ƨ��C<br>
<?php elseif ($this->_tpl_vars['funcFolder'] == "���s�R�W��Ƨ�"): ?>
�z�n�D���s�R�W"<font color=red><?php echo $this->_tpl_vars['folderName']; ?>
 </font>"��Ƨ��C<br>
<?php else: ?>
<font color="red">�z�T�w�n�R��" <font color=red><?php echo $this->_tpl_vars['folderName']; ?>
 </font>"��Ƨ��H<br><br>
�o�Ӱʧ@���|�R�����v�ɡA<br>���|��o�Ӹ�Ƨ��Ψ�l��Ƨ��U�����v�ɥ����k�^root�U�C</font>
<?php endif; ?>
<form action="processFolder.php" method="post">
<br>
<?php if ($this->_tpl_vars['funcFolder'] != "�R����Ƨ�"): ?>
�s��Ƨ��W�� : <input type="text" name="folderName" size="20">
<?php endif; ?>
<input type="submit" name="func" value="<?php echo $this->_tpl_vars['funcFolder']; ?>
" class="btn">
<input type="submit" name="func" value="����" class="btn">
<input type="hidden" name="folderId" value="<?php echo $this->_tpl_vars['folderId']; ?>
">
</form>

</body>
</html>
