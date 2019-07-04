<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<title>建立即時會議</title>

<link rel="stylesheet" href="/images/skin1/css/main-body.css" type="text/css">
<link href="{$tpl_path}/css/tabs.css" rel="stylesheet" type="text/css" />
<link href="{$tpl_path}/css/content.css" rel="stylesheet" type="text/css" />
<link href="{$tpl_path}/css/table.css" rel="stylesheet" type="text/css" />
<link href="{$tpl_path}/css/form.css" rel="stylesheet" type="text/css" />
<link href="{$webroot}/css/jquery.treeview.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="{$webroot}script/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="{$webroot}script/jquery-ui-1.7.2.custom.min.js"></script>
<script type="text/javascript" src="{$webroot}script/jquery.treeview.js"></script>
<script type="text/javascript">

{literal}
$(document).ready(function(){
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

    <h4>Sample 1 - default</h4>
    <ul id="browser" class="filetree">
        <li><span class="folder">Folder 1</span>
            <ul>

                <li><span class="file">Item 1.1</span></li>
            </ul>
        </li>
        <li><span class="folder">Folder 2</span>
            <ul>
                <li><span class="folder">Subfolder 2.1</span>
                    <ul id="folder21">

                        <li><span class="file">File 2.1.1</span></li>
                        <li><span class="file">File 2.1.2</span></li>
                    </ul>
                </li>
                <li><span class="file">File 2.2</span></li>
            </ul>
        </li>

        <li class="closed"><span class="folder">Folder 3 (closed at start)</span>
            <ul>
                <li><span class="file">File 3.1</span></li>
            </ul>
        </li>
        <li><span class="file">File 4</span></li>
    </ul>

    
    <button id="add">Add!</button>


</body>
</html>

