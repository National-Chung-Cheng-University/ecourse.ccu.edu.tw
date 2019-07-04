<?php /* Smarty version 2.6.14, created on 2013-02-09 06:40:33
         compiled from /datacenter/htdocs/php/Mmc/templates/listHalfhourMeeting.tpl */ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;">
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<title>會議清單</title>

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

    <h3 align=center>預約會議會議清單</h3>
    <?php $_from = $this->_tpl_vars['meetingList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['temp'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['temp']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['meetingData']):
        $this->_foreach['temp']['iteration']++;
?>
      <?php if (($this->_foreach['temp']['iteration'] <= 1)): ?>
        <table class="datatable" border=1 width=100%>
          <tr>
          <td>編號</td>
          <td>標題</td>
          <td>會議時間</td>
          <td>會議人數</td>
          <td>主持人</td>
        </tr>
      <?php endif; ?>
      <tr>
          <td><?php echo $this->_tpl_vars['meetingData']['index_num']; ?>
</td>
          <td><?php echo $this->_tpl_vars['meetingData']['title']; ?>
</td>
          <td><?php echo $this->_tpl_vars['meetingData']['reservationTime'];  if ($this->_tpl_vars['meetingData']['end'] == 1): ?><br>[已結束]<?php endif; ?></td>
          <td><?php echo $this->_tpl_vars['meetingData']['maxNumAttendee']; ?>
</td>
          <td><?php echo $this->_tpl_vars['meetingData']['nativeName']; ?>
</td>
      </tr>
      <?php if (($this->_foreach['temp']['iteration'] == $this->_foreach['temp']['total'])): ?>
        </table>
      <?php endif; ?>

    <?php endforeach; else: ?>
       <br>此資料夾無任何錄影檔
    <?php endif; unset($_from); ?>


</body>
</html>
