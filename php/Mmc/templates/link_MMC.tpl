<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; ">
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<title>轉換</title>


<link href="{$tpl_path}/css/tabs.css" rel="stylesheet" type="text/css" />
<link href="{$tpl_path}/css/content.css" rel="stylesheet" type="text/css" />
<link href="{$tpl_path}/css/table.css" rel="stylesheet" type="text/css" />
<link href="{$tpl_path}/css/form.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="jquery/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="jquery/jquery-ui-1.7.2.custom.min.js"></script>

</head>

<body>
    <form name=txAddr action='{$site}' method='post'>
        <input type='hidden' name='cid' value='{$cid}' >
        <input type='hidden' name='mid' value='{$mid}' >
        <input type='hidden' name='id' value='{$id}' >
        <input type='hidden' name='fn' value='{$fn}' >
        <input type='hidden' name='st' value='{$st}' >
    </form>
    <script language=javascript>
        document.txAddr.submit();
    </script>
</body>
</html>

