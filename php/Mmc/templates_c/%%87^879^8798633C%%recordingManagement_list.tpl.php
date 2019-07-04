<?php /* Smarty version 2.6.14, created on 2011-02-21 17:53:22
         compiled from /usr/local/apache/htdocs/php/Mmc/templates/recordingManagement_list.tpl */ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; ">
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<title>錄影檔清單</title>

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

    <h3 align="center" >錄影檔清單</h3>

    <font >已使用空間：<?php echo $this->_tpl_vars['totalused']; ?>
MB　　　總共可用空間:<?php echo $this->_tpl_vars['totalquota']; ?>
MB 　　使用量:<?php echo $this->_tpl_vars['used']; ?>
%</font><br>
    <?php if ($this->_tpl_vars['used'] >= 95): ?>
        <font color="red">可用空間即將不足</font><br>
    <?php endif; ?>

    <form action="moveRecording.php" method="post">
    <input type="hidden" name="currentFolderId" value="<?php echo $this->_tpl_vars['currentFolderId']; ?>
">
    <input type="hidden" name="currentSeq" value="<?php echo $this->_tpl_vars['currentSeq']; ?>
">
    <input type="submit" name="func" value="移動到" class="btn">
    <select name="moveTofolderId">
    <?php $_from = $this->_tpl_vars['folderList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['folderData']):
?>
       <option value="<?php echo $this->_tpl_vars['folderData']['folderId']; ?>
"><?php echo $this->_tpl_vars['folderData']['folderCaption']; ?>
</option> 
    <?php endforeach; endif; unset($_from); ?>
    </select>

    <?php $_from = $this->_tpl_vars['recordingList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['temp'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['temp']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['meetingData']):
        $this->_foreach['temp']['iteration']++;
?>
      <?php if (($this->_foreach['temp']['iteration'] <= 1)): ?>
        <table class="datatable" border=1>
          <tr>
          <td bgcolor="#E6FFFC"></td>
          <td bgcolor="#E6FFFC">編號</td>
          <td bgcolor="#E6FFFC">課堂名稱</td>
          <td bgcolor="#E6FFFC">標題</td>
          <td bgcolor="#E6FFFC">會議時間</td>
          <td bgcolor="#E6FFFC">會議長度</td>
          <td bgcolor="#E6FFFC">發佈/解除</td>
          <td bgcolor="#E6FFFC">發佈</td>
          <td bgcolor="#E6FFFC">錄影檔操作</td>
          <td bgcolor="#E6FFFC">簡報縮圖</td>
          <td bgcolor="#E6FFFC">交談記錄</td>
        </tr>
      <?php endif; ?>
      <tr>
          <td bgcolor="#F0FFEE"><input type="checkbox" name="recordingId[]" value="<?php echo $this->_tpl_vars['meetingData']['recordingId']; ?>
"></td>
          <td bgcolor="#F0FFEE"><?php echo $this->_tpl_vars['meetingData']['recordingIndex']; ?>
</td>
          <td bgcolor="#F0FFEE"><?php echo $this->_tpl_vars['meetingData']['courseName']; ?>
</td>
          <td bgcolor="#F0FFEE"><?php echo $this->_tpl_vars['meetingData']['title']; ?>
</td>
          <td bgcolor="#F0FFEE"><?php echo $this->_tpl_vars['meetingData']['actualStartTime']; ?>
</td>
          <td bgcolor="#F0FFEE"><?php echo $this->_tpl_vars['meetingData']['durationString']; ?>
</td>
          <td bgcolor="#F0FFEE"><?php if ($this->_tpl_vars['meetingData']['isRead'] == 1): ?><a href='<?php echo $this->_tpl_vars['meetingData']['cancelpublishUrl']; ?>
'>解除</a><?php else: ?><a href='<?php echo $this->_tpl_vars['meetingData']['publishUrl']; ?>
'>發佈</a><?php endif; ?></td>
          <td bgcolor="#F0FFEE"><?php if ($this->_tpl_vars['meetingData']['isRead'] == 1): ?>是<?php else: ?>否<?php endif; ?></td>
          <td bgcolor="#F0FFEE"><a href='<?php echo $this->_tpl_vars['meetingData']['operationUrl']; ?>
'>錄影檔操作</a></td>
          <td bgcolor="#F0FFEE"><a href='<?php echo $this->_tpl_vars['meetingData']['slidingshowUrl']; ?>
' target=_blank>簡報縮圖</a></td>
          <td bgcolor="#F0FFEE"><a href='<?php echo $this->_tpl_vars['meetingData']['chatUrl']; ?>
' target=_blank>交談文字</a></td>
      </tr>
      <?php if (($this->_foreach['temp']['iteration'] == $this->_foreach['temp']['total'])): ?>
        </table>
      <?php endif; ?>

    <?php endforeach; else: ?>
       <br>此資料夾無任何錄影檔
    <?php endif; unset($_from); ?>


</body>
</html>

