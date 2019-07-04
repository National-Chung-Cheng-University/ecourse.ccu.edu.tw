<?php /* Smarty version 2.6.14, created on 2011-02-19 17:14:56
         compiled from /usr/local/apache/htdocs/php/Mmc/templates/recordingManagement_folder.tpl */ ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;">
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<title>建立即時會議</title>

<link rel="stylesheet" href="/images/skin1/css/main-body.css" type="text/css">
<link href="<?php echo $this->_tpl_vars['tpl_path']; ?>
/css/tabs.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $this->_tpl_vars['tpl_path']; ?>
/css/content.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $this->_tpl_vars['tpl_path']; ?>
/css/table.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $this->_tpl_vars['tpl_path']; ?>
/css/form.css" rel="stylesheet" type="text/css" />
<link href="jquery/jquery.treeview.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="jquery/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="jquery/jquery-ui-1.7.2.custom.min.js"></script>
<script type="text/javascript" src="jquery/jquery.treeview.js"></script>
<script type="text/javascript">

<?php echo '
$(document).ready(function(){

    $("#browserfolder").treeview();
    if( $("#checkFrom").attr(\'title\') == 1) {
        parent.list_frame.location.reload();
    }
    else
        ;
    $("#browser").treeview();
    $("#add").click(function() {
        var branches = $("<li><span class=\'folder\'>New Sublist</span><ul>" +
            "<li><span class=\'file\'>Item1</span></li>" +
            "<li><span class=\'file\'>Item2</span></li></ul></li>").appendTo("#browser");
        $("#browser").treeview({
            add: branches
        });
        branches = $("<li class=\'closed\'><span class=\'folder\'>New Sublist</span><ul><li><span class=\'file\'>Item1</span></li><li><span class=\'file\'>Item2</span></li></ul></li>").prependTo("#folder21");
        $("#browser").treeview({
            add: branches
        });
    });

});
'; ?>


</script>

</head>

<body>
    <div id="checkFrom" title="<?php echo $this->_tpl_vars['fromReload']; ?>
"></div>
    <h3>資料夾目錄</h3>
    <form action="folderView.php" method="post">
    <input type="submit" name="func" value="新增資料夾" class="btn">
    <input type="submit" name="func" value="刪除資料夾" class="btn">
    <input type="submit" name="func" value="重新命名資料夾" class="btn">
    <ul id="browserfolder" class="filetree">
    <?php unset($this->_sections['folderData']);
$this->_sections['folderData']['name'] = 'folderData';
$this->_sections['folderData']['loop'] = is_array($_loop=$this->_tpl_vars['folderList']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['folderData']['show'] = true;
$this->_sections['folderData']['max'] = $this->_sections['folderData']['loop'];
$this->_sections['folderData']['step'] = 1;
$this->_sections['folderData']['start'] = $this->_sections['folderData']['step'] > 0 ? 0 : $this->_sections['folderData']['loop']-1;
if ($this->_sections['folderData']['show']) {
    $this->_sections['folderData']['total'] = $this->_sections['folderData']['loop'];
    if ($this->_sections['folderData']['total'] == 0)
        $this->_sections['folderData']['show'] = false;
} else
    $this->_sections['folderData']['total'] = 0;
if ($this->_sections['folderData']['show']):

            for ($this->_sections['folderData']['index'] = $this->_sections['folderData']['start'], $this->_sections['folderData']['iteration'] = 1;
                 $this->_sections['folderData']['iteration'] <= $this->_sections['folderData']['total'];
                 $this->_sections['folderData']['index'] += $this->_sections['folderData']['step'], $this->_sections['folderData']['iteration']++):
$this->_sections['folderData']['rownum'] = $this->_sections['folderData']['iteration'];
$this->_sections['folderData']['index_prev'] = $this->_sections['folderData']['index'] - $this->_sections['folderData']['step'];
$this->_sections['folderData']['index_next'] = $this->_sections['folderData']['index'] + $this->_sections['folderData']['step'];
$this->_sections['folderData']['first']      = ($this->_sections['folderData']['iteration'] == 1);
$this->_sections['folderData']['last']       = ($this->_sections['folderData']['iteration'] == $this->_sections['folderData']['total']);
?>
        <?php if ($this->_tpl_vars['folderList'][$this->_sections['folderData']['index']]['parentId'] == 0): ?>
            <li>
                <input type="radio" name="folderId" value="<?php echo $this->_tpl_vars['folderList'][$this->_sections['folderData']['index']]['folderId']; ?>
" checked >
                <a href="recordingManagement_list.php?rid=<?php echo $this->_tpl_vars['folderList'][$this->_sections['folderData']['index']]['folderId']; ?>
&seq=<?php echo $this->_tpl_vars['folderList'][$this->_sections['folderData']['index']]['sequence']; ?>
" target="list_frame" 
                class="folder"><?php echo $this->_tpl_vars['folderList'][$this->_sections['folderData']['index']]['folderCaption']; ?>
</a>
        <?php else: ?>
            <?php if ($this->_tpl_vars['folderList'][$this->_sections['folderData']['index_prev']]['parentId'] == $this->_tpl_vars['folderList'][$this->_sections['folderData']['index']]['parentId']): ?>
                
                </li><li>
                <input type="radio" name="folderId" value="<?php echo $this->_tpl_vars['folderList'][$this->_sections['folderData']['index']]['folderId']; ?>
">
                <a href="recordingManagement_list.php?rid=<?php echo $this->_tpl_vars['folderList'][$this->_sections['folderData']['index']]['folderId']; ?>
&seq=<?php echo $this->_tpl_vars['folderList'][$this->_sections['folderData']['index']]['sequence']; ?>
" target="list_frame"
                class="folder"><?php echo $this->_tpl_vars['folderList'][$this->_sections['folderData']['index']]['folderCaption']; ?>
</a>
            <?php elseif ($this->_tpl_vars['folderList'][$this->_sections['folderData']['index_prev']]['folderId'] == $this->_tpl_vars['folderList'][$this->_sections['folderData']['index']]['parentId']): ?>
                <ul><li>
                <input type="radio" name="folderId" value="<?php echo $this->_tpl_vars['folderList'][$this->_sections['folderData']['index']]['folderId']; ?>
">
                <a href="recordingManagement_list.php?rid=<?php echo $this->_tpl_vars['folderList'][$this->_sections['folderData']['index']]['folderId']; ?>
&seq=<?php echo $this->_tpl_vars['folderList'][$this->_sections['folderData']['index']]['sequence']; ?>
" target="list_frame"
                class="folder"><?php echo $this->_tpl_vars['folderList'][$this->_sections['folderData']['index']]['folderCaption']; ?>
</a>
            <?php else: ?>
                </li></ul></li><li>
                <input type="radio" name="folderId" value="<?php echo $this->_tpl_vars['folderList'][$this->_sections['folderData']['index']]['folderId']; ?>
">
                <a href="recordingManagement_list.php?rid=<?php echo $this->_tpl_vars['folderList'][$this->_sections['folderData']['index']]['folderId']; ?>
&seq=<?php echo $this->_tpl_vars['folderList'][$this->_sections['folderData']['index']]['sequence']; ?>
" target="list_frame"
                class="folder"><?php echo $this->_tpl_vars['folderList'][$this->_sections['folderData']['index']]['folderCaption']; ?>
</a>
            <?php endif; ?>
        <?php endif; ?>
    <?php endfor; endif; ?>
    </li></ul>
    </div>
    </form>

</body>
</html>

