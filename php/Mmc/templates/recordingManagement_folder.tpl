<html>
<head>
<meta http-equiv="Content-Type" content="text/html;">
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<title>建立即時會議</title>

<link rel="stylesheet" href="/images/skin1/css/main-body.css" type="text/css">
<link href="{$tpl_path}/css/tabs.css" rel="stylesheet" type="text/css" />
<link href="{$tpl_path}/css/content.css" rel="stylesheet" type="text/css" />
<link href="{$tpl_path}/css/table.css" rel="stylesheet" type="text/css" />
<link href="{$tpl_path}/css/form.css" rel="stylesheet" type="text/css" />
<link href="jquery/jquery.treeview.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="jquery/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="jquery/jquery-ui-1.7.2.custom.min.js"></script>
<script type="text/javascript" src="jquery/jquery.treeview.js"></script>
<script type="text/javascript">

{literal}
$(document).ready(function(){

    $("#browserfolder").treeview();
    if( $("#checkFrom").attr('title') == 1) {
        parent.list_frame.location.reload();
    }
    else
        ;
    $("#browser").treeview();
    $("#add").click(function() {
        var branches = $("<li><span class='folder'>New Sublist</span><ul>" +
            "<li><span class='file'>Item1</span></li>" +
            "<li><span class='file'>Item2</span></li></ul></li>").appendTo("#browser");
        $("#browser").treeview({
            add: branches
        });
        branches = $("<li class='closed'><span class='folder'>New Sublist</span><ul><li><span class='file'>Item1</span></li><li><span class='file'>Item2</span></li></ul></li>").prependTo("#folder21");
        $("#browser").treeview({
            add: branches
        });
    });

});
{/literal}

</script>

</head>

<body>
    <div id="checkFrom" title="{$fromReload}"></div>
    <h3>資料夾目錄</h3>
    <form action="folderView.php" method="post">
    <input type="submit" name="func" value="新增資料夾" class="btn">
    <input type="submit" name="func" value="刪除資料夾" class="btn">
    <input type="submit" name="func" value="重新命名資料夾" class="btn">
    <ul id="browserfolder" class="filetree">
    {section name=folderData loop=$folderList}
        {if $folderList[folderData].parentId == 0}
            <li>
                <input type="radio" name="folderId" value="{$folderList[folderData].folderId}" checked >
                <a href="recordingManagement_list.php?rid={$folderList[folderData].folderId}&seq={$folderList[folderData].sequence}" target="list_frame" 
                class="folder">{$folderList[folderData].folderCaption}</a>
        {else}
            {if $folderList[folderData.index_prev].parentId == $folderList[folderData].parentId}
                
                </li><li>
                <input type="radio" name="folderId" value="{$folderList[folderData].folderId}">
                <a href="recordingManagement_list.php?rid={$folderList[folderData].folderId}&seq={$folderList[folderData].sequence}" target="list_frame"
                class="folder">{$folderList[folderData].folderCaption}</a>
            {elseif $folderList[folderData.index_prev].folderId == $folderList[folderData].parentId}
                <ul><li>
                <input type="radio" name="folderId" value="{$folderList[folderData].folderId}">
                <a href="recordingManagement_list.php?rid={$folderList[folderData].folderId}&seq={$folderList[folderData].sequence}" target="list_frame"
                class="folder">{$folderList[folderData].folderCaption}</a>
            {else}
                </li></ul></li><li>
                <input type="radio" name="folderId" value="{$folderList[folderData].folderId}">
                <a href="recordingManagement_list.php?rid={$folderList[folderData].folderId}&seq={$folderList[folderData].sequence}" target="list_frame"
                class="folder">{$folderList[folderData].folderCaption}</a>
            {/if}
        {/if}
    {/section}
    </li></ul>
    </div>
    </form>

</body>
</html>


