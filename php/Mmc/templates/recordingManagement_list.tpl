<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; ">
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<title>錄影檔清單</title>

<link rel="stylesheet" href="/images/skin1/css/main-body.css" type="text/css">
<link href="{$tpl_path}/css/tabs.css" rel="stylesheet" type="text/css" />
<link href="{$tpl_path}/css/content.css" rel="stylesheet" type="text/css" />
<link href="{$tpl_path}/css/table.css" rel="stylesheet" type="text/css" />
<link href="{$tpl_path}/css/form.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="jquery/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="jquery/jquery-ui-1.7.2.custom.min.js"></script>

</head>

<body>

    <h3 align="center" >錄影檔清單</h3>

    <font >已使用空間：{$totalused}MB　　　總共可用空間:{$totalquota}MB 　　使用量:{$used}%</font><br>
    {if $used ge 95}
        <font color="red">可用空間即將不足</font><br>
    {/if}

    <form action="moveRecording.php" method="post">
    <input type="hidden" name="currentFolderId" value="{$currentFolderId}">
    <input type="hidden" name="currentSeq" value="{$currentSeq}">
    <input type="submit" name="func" value="移動到" class="btn">
    <select name="moveTofolderId">
    {foreach from=$folderList item=folderData}
       <option value="{$folderData.folderId}">{$folderData.folderCaption}</option> 
    {/foreach}
    </select>

    {foreach from=$recordingList item=meetingData name=temp}
      {if $smarty.foreach.temp.first}
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
      {/if}
      <tr>
          <td bgcolor="#F0FFEE"><input type="checkbox" name="recordingId[]" value="{$meetingData.recordingId}"></td>
          <td bgcolor="#F0FFEE">{$meetingData.recordingIndex}</td>
          <td bgcolor="#F0FFEE">{$meetingData.courseName}</td>
          <td bgcolor="#F0FFEE">{$meetingData.title}</td>
          <td bgcolor="#F0FFEE">{$meetingData.actualStartTime}</td>
          <td bgcolor="#F0FFEE">{$meetingData.durationString}</td>
          <td bgcolor="#F0FFEE">{if $meetingData.isRead == 1}<a href='{$meetingData.cancelpublishUrl}'>解除</a>{else}<a href='{$meetingData.publishUrl}'>發佈</a>{/if}</td>
          <td bgcolor="#F0FFEE">{if $meetingData.isRead == 1}是{else}否{/if}</td>
          <td bgcolor="#F0FFEE"><a href='{$meetingData.operationUrl}'>錄影檔操作</a></td>
          <td bgcolor="#F0FFEE"><a href='{$meetingData.slidingshowUrl}' target=_blank>簡報縮圖</a></td>
          <td bgcolor="#F0FFEE"><a href='{$meetingData.chatUrl}' target=_blank>交談文字</a></td>
      </tr>
      {if $smarty.foreach.temp.last}
        </table>
      {/if}

    {foreachelse}
       <br>此資料夾無任何錄影檔
    {/foreach}


</body>
</html>


