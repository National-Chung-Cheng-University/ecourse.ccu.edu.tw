<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; ">
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<title>��Ƨ��޲z</title>

<link rel="stylesheet" href="/images/skin1/css/main-body.css" type="text/css">
<link href="{$tpl_path}/css/tabs.css" rel="stylesheet" type="text/css" />
<link href="{$tpl_path}/css/content.css" rel="stylesheet" type="text/css" />
<link href="{$tpl_path}/css/table.css" rel="stylesheet" type="text/css" />
<link href="{$tpl_path}/css/form.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="jquery/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="jquery/jquery-ui-1.7.2.custom.min.js"></script>

</head>
<body>
<h1>{$funcFolder}</h1>
{if $funcFolder == "�s�W��Ƨ�"}
�z�n�D�b" <font color=red>{$folderName} </font>"��Ƨ��U<br>�إߤ@�ӷs��Ƨ��C<br>
{elseif $funcFolder == "���s�R�W��Ƨ�"}
�z�n�D���s�R�W"<font color=red>{$folderName} </font>"��Ƨ��C<br>
{else}
<font color="red">�z�T�w�n�R��" <font color=red>{$folderName} </font>"��Ƨ��H<br><br>
�o�Ӱʧ@���|�R�����v�ɡA<br>���|��o�Ӹ�Ƨ��Ψ�l��Ƨ��U�����v�ɥ����k�^root�U�C</font>
{/if}
<form action="processFolder.php" method="post">
<br>
{if $funcFolder != "�R����Ƨ�"}
�s��Ƨ��W�� : <input type="text" name="folderName" size="20">
{/if}
<input type="submit" name="func" value="{$funcFolder}" class="btn">
<input type="submit" name="func" value="����" class="btn">
<input type="hidden" name="folderId" value="{$folderId}">
</form>

</body>
</html>

